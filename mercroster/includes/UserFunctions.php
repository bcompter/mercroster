<?php
class UserFunctions
{
  private $_logging;
  private $_registerd;
  private $_logTypeArray;
  private $_lastTopicArray;

  public function __construct()
  {
    $this->_logTypeArray=array();
    $this->_lastTopicArray=array();
    $this->_registerd=0;
    $this->_logging=true;
  }

  /**
   * This funtion is used to strip all kind of nasty thing out of _POST data
   * @param <string> $data
   * @return <string>
   */
  private function strip($data)
  {
    require("htdocs/dbsetup.php");
    $data=stripslashes($data);
    $data=mysql_real_escape_string($data);
    $data=strip_tags($data);
    return $data;
  }

  /**
   * This funtion is used to return Zulu time in string format
   * @return <string>
   */
  private function getZTime()
  {
    $time=date("Y-m-d H:i:s", time()-date("Z",time()));
    return $time;
  }

  /**
   * This function is used to update cookie
   * @param $cookie
   * @param $save
   */
  private function updateCookie($cookie)
  {
    $_SESSION['cookie']=$cookie;
    $cookieinfo=serialize(array($_SESSION['SESS_ID'], $cookie) );
    setcookie('mrrinf', $cookieinfo, time() + 31104000, '/');
  }

  /**
   * This function is used to update user last online time
   * @param <type> $dbf
   */
  public function updateUserTimes($dbf)
  {
    if(isset($_SESSION['SESS_ID']) && !$_SESSION['SESS_ID']=="" && isset($_SESSION['SESS_TYPE']))
    {
      $time=$this->strip($this->getZTime());
      $userid=$this->strip($_SESSION['SESS_ID']);
      $queryArray[0]="UPDATE members SET lastlogin='$time', online='1' WHERE id='{$userid}';";
      $dbf->queryarray($queryArray);
    }
  }

  /**
   * This function is used to log user in automatically with cookie
   * @param $dbf
   */
  public function persistentcheck($dbf)
  {
    $tbl_name="members";
    $line=$_COOKIE['mrrinf'];
    if (get_magic_quotes_gpc())
    {
      $line = stripslashes($line);
    }
    list($id, $cookie) = unserialize($line);
    $result = $dbf->queryselect("SELECT id, type, username FROM $tbl_name WHERE cookie='{$cookie}' and id='{$id}'");

    // Mysql_num_row is counting table row
    $count=mysql_num_rows($result);
    $loginarray = mysql_fetch_array($result, MYSQL_NUM);
    // If result matched $myusername and $mypassword, table row must be 1 row
    if($count==1)
    {
      session_regenerate_id();
      $_SESSION['SESS_ID']=$loginarray[0];
      $_SESSION['SESS_NAME']=$loginarray[2];
      $_SESSION['SESS_TYPE']=$loginarray[1];
      $time=$this->strip($this->getZTime());
      //Update last login time
      $queryArray[0]="UPDATE members SET lastlogin='{$time}', online='1' WHERE username='{$loginarray[2]}';";
      $dbf->queryarray($queryArray);
      $this->updateCookie($cookie);
      $this->_registerd=1;
    }
  }

  /**
   * This function is used to log user out
   * @param $dbf
   */
  public function logOut($dbf)
  {
    $myusername=$this->strip($_SESSION['SESS_NAME']);
    $time=$this->strip($this->getZTime());
    $queryArray[0]="UPDATE members SET lastlogin='$time', online='0' WHERE username='{$myusername}';";
    $dbf->queryarray($queryArray);
    unset($_SESSION['SESS_NAME']);
    unset($_SESSION['SESS_ID']);
    unset($_SESSION['SESS_TYPE']);
    $_SESSION = array(); // reset session array
    setcookie('mrrinf', "", time()-31104000, '/');
    $this->_registerd=0;
    session_destroy(); // destroy session.
  }

  /**
   * This function is used to log user in
   * @param $myusername
   * @param $mypassword
   * @param $remember
   * @param $dbf
   */
  public function logincheck($myusername, $mypassword, $remember, $ip, $dbf)
  {
    $tbl_name="members";
    // username and password sent from form

    $encrypted_mypassword=md5($mypassword);

    $result = $dbf->queryselect("SELECT id, type, cookie FROM $tbl_name WHERE username='$myusername' and password='$encrypted_mypassword'");

    // Mysql_num_row is counting table row
    $count=mysql_num_rows($result);
    $loginarray=mysql_fetch_array($result, MYSQL_NUM);
    // If result matched $myusername and $mypassword, table row must be 1 row
    if($count==1)
    {
      session_regenerate_id();
      $_SESSION['SESS_ID']=$loginarray[0];
      $_SESSION['SESS_NAME']=$myusername;
      $_SESSION['SESS_TYPE']=$loginarray[1];
      $time=$this->strip($this->getZTime());
      $cookie=$loginarray[2];
      //Update last login time
      $queryArray[0]="UPDATE members SET lastlogin='$time', online='1' WHERE username='{$myusername}';";
      $dbf->queryarray($queryArray);
      if($remember=="on")
      {
        $this->updateCookie($loginarray[2]);
      }
      $this->_registerd=1;
      $ip=$this->strip($ip);
      $ipResult=$dbf->queryselect("SELECT COUNT(ipaddress) AS count, logins, logged FROM guests WHERE ipaddress=INET_ATON('{$ip}') GROUP BY ipaddress;");
      $ipArray=mysql_fetch_array($ipResult, MYSQL_ASSOC);
      if($ipArray[count]==1)
      {
        $queryArray[0]="UPDATE guests SET logged='0' WHERE ipaddress=INET_ATON('{$ip}');";
        $dbf->queryarray($queryArray);
      }
      return(1);
    }
    else
    {
      if($this->_logging)
      {
        $time=date("Y-m-d, H:i:s");
        $tolog=("Failed Login attempt|" . $myusername . "|" . $time . "\n");
        $log=fopen("logs/failedlogins.log", "a");
        fwrite($log, $tolog);
        fclose($log);
      }
      return(2);
    }
  }

  /**
   * This function is used for checking whatever another user is really online.
   * @param <string> $time
   * @param <integer> $offset
   * @param <string> $timeformat
   */
  public function isUserOnline($lastknowtime, $offset)
  {
    $lastknowtime=strtotime($lastknowtime);
    //$now=strtotime($this->getZTime());
    $now=time()-date("Z",time());
    $differece=$now-$lastknowtime;
    if($differece<$offset)
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  /**
   * This function is used to update user last online time
   * @param <type> $dbf
   */
  public function userLogOut($dbf, $id)
  {
    $time=$this->strip($this->getZTime());
    $myusername=$this->strip($_SESSION['SESS_NAME']);
    $queryArray[0]="UPDATE members SET online='0' WHERE id='{$id}';";
    $dbf->queryarray($queryArray);
  }

  /**
   *
   * @param $logType
   * @param $user
   * @param $dbf
   */
  public function updateLogTable($logType, $user, $dbf)
  {
    $logType=$this->strip($logType);
    $user=$this->strip($user);
    $latestLogResult=$dbf->queryselect("SELECT id, start FROM logentry WHERE logtype='{$logType}' ORDER BY id DESC LIMIT 1;");
    $checkCount=mysql_num_rows($latestLogResult);
    if($checkCount==1)
    {
      $latestLogArray=mysql_fetch_array($latestLogResult, MYSQL_NUM);
      $queryArray[0]="UPDATE lastlog SET lasttopic='$latestLogArray[0]' WHERE member='{$user}' AND logtype='{$logType}';";
      $dbf->queryarray($queryArray);
    }
  }

  /**
   *
   */
  public function updateLogArrays($user, $dbf)
  {
    $this->_logTypeArray=array();
    $this->_lastTopicArray=array();
    $lastVisitedTopicResult=$dbf->queryselect("SELECT logtype, lasttopic FROM lastlog WHERE member='{$user}' ORDER BY logtype ASC;");
    while($array=mysql_fetch_array($lastVisitedTopicResult, MYSQL_ASSOC))
    {
      array_push($this->_logTypeArray, "{$array[logtype]}");
      array_push($this->_lastTopicArray,  "{$array[lasttopic]}");
    }
  }

  /**
   * This function return array containg users currently online. 
   * @param <dataasefunctions> $dbf
   * @return array
   */
  public function checkUsers($dbf)
  {
    $onlineUserResult=$dbf->queryselect("SELECT id, username, sitename, lastlogin FROM members WHERE online='1' ORDER BY type, username;");
    $users=array();
    while($onlineUserArray=mysql_fetch_array($onlineUserResult, MYSQL_NUM))
    {
      if($this->isUserOnline($onlineUserArray[3], 600))
      {
        $users[sizeof($users)]=$onlineUserArray[2];
      }
      else
      {
        $this->userLogOut($dbf, $onlineUserArray[0]);
      }
    }
    return $users;
  }
  
  /**
   * This function returns whatever user is registered and logged in
   * @return boolean
   */
  public function getLogTypeArray()
  {
    return $this->_logTypeArray;
  }

  public function getLastTopicArray()
  {
    return $this->_lastTopicArray;
  }

  public function setRegistered($reg)
  {
    return $this->_registerd=$reg;
  }

  public function isregisterd()
  {
    return $this->_registerd;
  }
}
?>