<?php
require("includes/Parser.php");
class Userparser extends Parser
{
  /**
   * This function is used to handle user related squeries
   * @return unknown_type
   */
  function parse()
  {
    //for spectators, players, commanders, GM's and administrators
    if((isset($_SESSION['SESS_ID']) && $_SESSION['SESS_ID']!="" && $_SESSION['SESS_TYPE']<='5' && $_POST['QueryType']=="User") && ($_POST['ID']==$_SESSION['SESS_ID'] || $_SESSION['SESS_TYPE']=='1'))
    {
      require("htdocs/dbsetup.php");
      $dbf = new DBFunctions;

      $parseheader="location:index.php";

      //user is editing his/her own data
      if($_POST['ID']==$_SESSION['SESS_ID'] && $_SESSION['SESS_TYPE']!='1')
      {
        $password=md5($this->strip($_POST['curretpw']));
        $id=$this->strip($_POST['ID']);
        $result=$dbf->queryselect("SELECT id FROM members WHERE password='{$password}' and id='{$id}';");
        $count=mysql_num_rows($result);
      }
      //user is editing his/her own data
      if($_SESSION['SESS_TYPE']=='1')
      {
        $aid=$this->strip($_SESSION['SESS_ID']);
        $password=md5($this->strip($_POST['curretpw']));
        $result=$dbf->queryselect("SELECT id FROM members WHERE password='{$password}' and id='{$aid}';");
        $count=mysql_num_rows($result);
      }

      if($count==1)
      {
        switch ($this->strip($_POST['QueryAction']))
        {
          case "Delete":
            if($this->checkposint($_POST['ID']))
            {
              //get parameters
              $id=$this->strip($_POST['ID']);
              $logs=$this->strip($_POST['logs']);
              $comments=$this->strip($_POST['comments']);
              //parse queries
              $queryArray[0] = "DELETE FROM members WHERE id='{$id}';";
              $queryArray[sizeof($queryArray)] = "DELETE FROM lastlog WHERE member='{$id}';";
              $queryArray[sizeof($queryArray)] = "DELETE FROM logsvisited WHERE member='{$id}';";
              if($logs=="on")
              {
                $logsResult=$dbf->queryselect("SELECT id FROM logentry WHERE opid='{$id}';");
                while($array=mysql_fetch_array($logsResult, MYSQL_NUM))
                {
                  $queryArray[sizeof($queryArray)] = "DELETE FROM comments WHERE parent='{$array[0]}';";
                }
                $queryArray[sizeof($queryArray)]= "DELETE FROM logentry WHERE opid='{$id}';";
              }
              if($comments=="on")
              {
                $queryArray[sizeof($queryArray)]= "DELETE FROM comments WHERE opid='{$id}';";
              }
              //execute queries
              $dbf->queryarray($queryArray);
              //back to edit pages
              if($_SESSION['SESS_TYPE']=='1')
              {
                $parseheader="location:index.php?action=users&first=0";
              }
              else
              {
                $parseheader="location:index.php?action=logout";
              }
            }
            else
            {
              $parseheader="location:index.php?action=incorrectargument";
            }
            break;

          case "Add":
            //get parameters
            $username=$this->strip($_POST['username']);
            $sitename=$this->strip($_POST['name']);
            $fname=$this->strip($_POST['firstname']);
            $lname=$this->strip($_POST['lastname']);
            $utype=$this->strip($_POST['utype']);
            $timeformat=$this->strip($_POST['timeformat']);
            $timeoffset=$this->strip($_POST['timeoffset']);

            $newpw=$this->strip($_POST['newpw']);
            $repeatpw=$this->strip($_POST['repeat']);

            //validating data
            $errMSG="";
            if($this->strip($_POST['username'])=="")
            {
              $errMSG=($errMSG==""? "0": "{$errMSG}.0");
            }
            else
            {
              $usernameresult=$dbf->queryselect("SELECT COUNT(*) count FROM members WHERE username='{$username}';");
              if(mysql_result($usernameresult, 0)!=0)
              {
                $errMSG=($errMSG==""? "1": "{$errMSG}.1");
              }
            }
            if($this->strip($_POST['name'])=="")
            {
              $errMSG=($errMSG==""? "2": "{$errMSG}.2");
            }
            if($this->strip($_POST['firstname'])=="")
            {
              $errMSG=($errMSG==""? "3": "{$errMSG}.3");
            }
            if($this->strip($_POST['lastname'])=="")
            {
              $errMSG=($errMSG==""? "4": "{$errMSG}.4");
            }
            if($newpw=="" || $repeatpw=="" || $newpw!=$repeatpw || !ctype_alnum($newpw))
            {
              $errMSG=($errMSG==""? "5": "{$errMSG}.5");;
            }


            if($errMSG=="")
            {
              //check offsets
              if($timeoffset>23 || $timeoffset<-23)
              {
                $timeoffset=0;
              }
              //random cookie
              $cookie="";
              $newpw=md5($newpw);
              for($i=0;$i<20;$i++)
              {
                $charto= substr($newpw, rand(0, strlen($newpw)), 1);
                $cookie=$cookie.$charto;
              }
              $cookie=md5($this->strip($cookie));
              //parse query
              $queryArray[0] = "INSERT INTO members (username, password, cookie, sitename, fname, lname, type, timeformat, timeoffset) VALUES ('{$username}', '{$newpw}', '{$cookie}', '{$sitename}', '{$fname}', '{$lname}', '{$utype}', '{$timeformat}', '{$timeoffset}');";
              //execute query
              $dbf->queryarray($queryArray);
              //get additional data
              $userResult=$dbf->queryselect("SELECT id FROM members WHERE username='{$username}';");//need id
              $userID=mysql_result($userResult, 0);
              $logTypesResult=$dbf->queryselect("SELECT id, type FROM logtypes;");//need types
              //parse additional queries
              unset($queryArray);
              while ($userArray=mysql_fetch_array($logTypesResult, MYSQL_NUM))
              {
                $queryArray[sizeof($queryArray)]="INSERT INTO lastlog (member, logtype, lasttopic) VALUES ('{$temp}', '{$logTypesResult[0]}', '0');";
              }
              //execute query
              $dbf->queryarray($queryArray);
              //back to edit pages
              if($_SESSION['SESS_TYPE']=='1')
              {
                $parseheader="location:index.php?action=users&first=0";
              }
              else
              {
                $parseheader="location:index.php?action=users&f=0";
              }
            }
            else
            {
              $parseheader="location:index.php?action=profile&err={$errMSG}";
            }
            break;

          case "Change":
            if($this->checkposint($_POST['ID']))
            {
              //get parameters
              $id=$this->strip($_POST['ID']);
              $username=$this->strip($_POST['username']);
              $sitename=$this->strip($_POST['name']);
              $fname=$this->strip($_POST['firstname']);
              $lname=$this->strip($_POST['lastname']);
              $utype=$this->strip($_POST['utype']);
              $timeformat=$this->strip($_POST['timeformat']);
              $timeoffset=$this->strip($_POST['timeoffset']);
              $favoredunit=$this->strip($_POST['favoredunit']);

              $newpw=$this->strip($_POST['newpw']);
              $repeatpw=$this->strip($_POST['repeat']);


              if($this->strip($_POST['name'])=="")
              {
                $errMSG=($errMSG==""? "2": "{$errMSG}.2");
              }
              if($this->strip($_POST['firstname'])=="")
              {
                $errMSG=($errMSG==""? "3": "{$errMSG}.3");
              }
              if($this->strip($_POST['lastname'])=="")
              {
                $errMSG=($errMSG==""? "4": "{$errMSG}.4");
              }
              //if($newpw=="" || $repeatpw=="" || $newpw!=$repeatpw || ctype_alnum($newpw))
              //{
              //  $errMSG=($errMSG==""? "5": "{$errMSG}.5");;
              //}

              if($errMSG=="")
              {
                //Only non paswword date changed
                if($newpw=="" && $repeatpw=="")
                {
                  //check offsets
                  if($timeoffset>23 || $timeoffset<-23)
                  {
                    $timeoffset=0;
                  }
                  //parse query
                  $queryArray[sizeof($queryArray)]="UPDATE members SET username='{$username}', sitename='{$sitename}', fname='{$fname}', lname='{$lname}', type='{$utype}', timeformat='{$timeformat}', timeoffset='{$timeoffset}', favoredunit='{$favoredunit}' WHERE id='{$id}';";
                  //execute query
                  $dbf->queryarray($queryArray);
                  //back to edit pages
                  if($_SESSION['SESS_TYPE']=='1')
                  {
                    $parseheader="location:index.php?action=profile&user={$username}&page=1&c={$count}";
                  }
                  else
                  {
                    $parseheader="location:index.php?action=profile";
                  }
                }
                elseif($newpw!="" && $repeatpw!="" && $newpw==$repeatpw && ctype_alnum($newpw))
                {
                  //data including password is changed
                  $newpw=md5($newpw);
                  //parse query
                  $queryArray[sizeof($queryArray)]="UPDATE members SET password='{$newpw}' WHERE id='{$id}';";
                  //check offsets
                  if($timeoffset>23 || $timeoffset<-23)
                  {
                    $timeoffset=0;
                  }
                  //parse query
                  $queryArray[sizeof($queryArray)]="UPDATE members SET username='{$username}', sitename='{$sitename}', fname='{$fname}', lname='{$lname}', type='{$utype}', timeformat='{$timeformat}', timeoffset='{$timeoffset}', favoredunit='{$favoredunit}' WHERE id='{$id}';";
                  //execute query
                  $dbf->queryarray($queryArray);
                  //back to edit pages
                  if($_SESSION['SESS_TYPE']=='1')
                  {
                    $parseheader="location:index.php?action=profile&user={$username}&page=1&c={$count}";
                  }
                  else
                  {
                    $parseheader="location:index.php?action=profile";
                  }
                }
                else
                {
                  //unsuccefull password data change attempt, failed because new and repeat was different
                  $parseheader="location:index.php?action=profile&user={$username}&err=5";
                }

              }
              else
              {
                $parseheader="location:index.php?action=profile&user={$username}&page=1&err={$errMSG}";
              }
            }
            else
            {
              $parseheader="location:index.php?action=incorrectargument";
            }
            break;
        }
      }
      else
      {
        $username=$this->strip($_POST['username']);
        if($username!="")
        {
          $parseheader="location:index.php?action=profile&user={$username}&page=1&err=10";
        }
        else
        {
          $parseheader="location:index.php?action=profile&page=1&err=10";
        }
        
      }
    }
    else
    {
      //User isen't adminstrator or editing his own data. Direct unallowed attempt to access denied page.
      $parseheader="location:index.php?action=accessdenied";
    }
    return $parseheader;
  }
}
?>