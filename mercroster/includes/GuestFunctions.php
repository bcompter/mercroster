<?php
class GuestFunctions
{

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

  private function logGuestOut($ip, $dbf)
  {
    $ip=$this->strip($ip);
    $queryArray[sizeof($queryArray)]="UPDATE guests SET logged='0' WHERE ipaddress='{$ip}';";
    $dbf->queryarray($queryArray);
  }

  public function HandleGuest($ip, $dbf, $refe, $path)
  {
    $ip=$this->strip($ip);
    $time=$this->getZTime();
    $ipResult=$dbf->queryselect("SELECT COUNT(ipaddress) AS count, logins, logged FROM guests WHERE ipaddress=INET_ATON('{$ip}') GROUP BY ipaddress;");
    $ipArray=mysql_fetch_array($ipResult, MYSQL_ASSOC);
    if($ipArray[count]==1)
    {
      if($ipArray[logged]==0)
      {
        $logins=$this->strip($ipArray[logins]+1);
        if($refe!=null && $refe!="" && substr_count($refe, $path)==0)
        {
          $cc=substr_count($refe, $path);
          //echo "Go referer:".$refe. " sitepath:".$path. " counr:".$cc;
          $queryArray[sizeof($queryArray)]="UPDATE guests SET logged='1', logins='{$logins}', lastlogin='{$time}', referer='{$refe}' WHERE ipaddress=INET_ATON('{$ip}');";
        }
        else
        {
          $cc=substr_count($refe, $path);
          //echo "NoGo referer:".$refe. " sitepath:".$path. " counr:".$cc;
          $queryArray[sizeof($queryArray)]="UPDATE guests SET logged='1', logins='{$logins}', lastlogin='{$time}' WHERE ipaddress=INET_ATON('{$ip}');";
        }
        $dbf->queryarray($queryArray);

      }
      else
      {
        $queryArray[sizeof($queryArray)]="UPDATE guests SET lastlogin='{$time}' WHERE ipaddress=INET_ATON('{$ip}');";
        $dbf->queryarray($queryArray);
      }
    }
    else
    {
      $queryArray[0]="INSERT INTO guests (ipaddress, lastlogin, logins, logged, referer) VALUES (INET_ATON('{$ip}'), '{$time}', 1, 1, '{$refe}');";
      $dbf->queryarray($queryArray);
    }
  }

  public function CheckLogged($dbf)
  {
    $ipLoggedResult=$dbf->queryselect("SELECT ipaddress, lastlogin FROM guests WHERE logged='1';");
    while($ipLoggedArray=mysql_fetch_array($ipLoggedResult, MYSQL_ASSOC))
    {
      $now=time()-date("Z",time());
      $differece=$now-strtotime($ipLoggedArray[lastlogin]);
      if($differece>300)
      {
        $this->logGuestOut($ipLoggedArray[ipaddress], $dbf);
      }
    }
  }

  public function GetGuestNumber($dbf)
  {
    $guestNumberResult=$dbf->queryselect("SELECT COUNT(*) AS count FROM guests WHERE logged='1';");
    $guestNumber=mysql_result($guestNumberResult, 0);
    return $guestNumber;
  }
}
?>