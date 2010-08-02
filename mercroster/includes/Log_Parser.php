<?php
require("includes/Parser.php");
class Logparser extends Parser
{
  /**
   * Funtion used to get Zulu time
   */
  private function getZTime()
  {
    $time=date("Y-m-d H:i:s", time()-date("Z",time()));
    return $time;
  }

  /**
   * Funtion user to get session users site name and return it
   * @param <int> $user
   * @param <DBFunctions> $dbf
   * @return <string>
   */
  private function getUserName($user, $dbf)
  {
    $nameArray=$dbf->queryselect("SELECT sitename FROM members WHERE username='{$user}';");
    $name=mysql_fetch_array($nameArray, MYSQL_NUM);
    return $name[0];
  }

  /**
   * Funtion user to get session users site name and return it
   * @param <int> $user
   * @param <DBFunctions> $dbf
   * @return <string>
   */
  private function getNewPostCount($user, $dbf)
  {
    $nameArray=$dbf->queryselect("SELECT postcount FROM members WHERE username='{$user}';");
    $postCountResult=mysql_fetch_array($nameArray, MYSQL_NUM);
    $postCount=$postCountResult[0]+1;
    $queryArray="UPDATE members SET postcount='{$postCount}' WHERE username='{$user}';";
    return $queryArray;
  }

  /**
   * This function is used to handle log relaited squeries
   * @return string
   */
  function parse()
  {
    if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<='5')//for spectators, players, commanders, GM's and administrators
    {
      require("htdocs/dbsetup.php");
      $dbf=new DBFunctions;

      require("includes/Feed_Generator.php");
      $feedgenerator=new Feed_Generator($dbf);

      $parseheader="location:index.php";
      if($_POST['QueryType']=="Comment")
      {
        switch ($_POST['QueryAction'])
        {
          case "Delete":
            if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['pID']))
            {
              //get parameters
              $id=$this->strip($_POST['ID']);
              $parentid=$this->strip($_POST['pID']);
              //parse query
              $queryArray[sizeof($queryArray)]="DELETE FROM comments WHERE id='{$id}';";
              //execute query
              $dbf->queryarray($queryArray);
              //back to parent log page
              $parseheader="location:index.php?action=log&log={$parentid}&first=end";
            }
            else
            {
              $parseheader="location:index.php?action=incorrectargument";
            }
            break;

          case "Save":
            if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['pID']))
            {
              //get parameters
              $id=$this->strip($_POST['ID']);
              $text=$this->strip($_POST['text']);
              $user=$this->getUserName($this->strip($_SESSION['SESS_NAME']), $dbf);
              $datetime=$this->getZTime();
              $parentid=$this->strip($_POST['pID']);
              //Chaeck for errors
              $errMSG="";
              if($text=="")
              {
                $errMSG="no text";
              }
              if($errMSG=="")
              {
                //parse query
                $queryArray[sizeof($queryArray)] = "UPDATE comments SET text='{$text}', le='{$user}', ledate='{$datetime}' WHERE id='{$id}';";
                //execute query
                $dbf->queryarray($queryArray);
                //back to parent log page
                $parseheader="location:index.php?action=log&log={$parentid}&first=msg.{$id}";
              }
              else
              {
                //back to edit comment page with error string
                $parseheader="location:index.php?action=editcomment&comment={$id}&err={$errMSG}";
              }
            }
            else
            {
              $parseheader="location:index.php?action=incorrectargument";
            }
            break;

          case "Add" || "Submit":
            //get parameters
            $id=$this->strip($_POST['ID']);
            $text=$this->strip($_POST['text']);
            $user=$this->getUserName($this->strip($_SESSION['SESS_NAME']), $dbf);
            $datetime=$this->getZTime();
            $parentid=$this->strip($_POST['pID']);
            $opid=$this->strip($_POST['opid']);
            //Chaeck for errors
            $errMSG="";
            if($text=="")
            {
              $errMSG="no text";
            }
            if($errMSG=="")
            {
              //parse query
              $queryArray[sizeof($queryArray)]="INSERT INTO comments (parent, opid, text, op, opdate) VALUES ('{$parentid}', '{$opid}', '{$text}', '{$user}', '{$datetime}');";
              $queryArray[sizeof($queryArray)]=$this->getNewPostCount($this->strip($_SESSION['SESS_NAME']), $dbf);
              //execute query
              $dbf->queryarray($queryArray);
              //back to parent log page
              $parseheader="location:index.php?action=log&log={$parentid}&first=end";
            }
            else
            {
              if($_POST['QueryAction']=="Submit")
              {
                //back to parent log page with error string
                $parseheader="location:index.php?action=log&log={$parentid}&first=end&err={$errMSG}";
              }
              else
              {
                if($id!="")
                {
                  $ids="&i={$id}";
                }
                //back to edit comment page with error string
                $parseheader="location:index.php?action=editcomment{$ids}&err={$errMSG}";
              }
            }
            break;
        }
      }

      if($_POST['QueryType']=="Log") //for spectators, players, commanders, GM's and administrators
      {
        switch ($_POST['QueryAction'])
        {
          case "Delete":
            if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['logtype']))
            {
              //get parameters
              $id=$this->strip($_POST['ID']);
              $logtype=$this->strip($_POST['logtype']);
              $readpermission=$this->strip($_POST['readpermission']);
              //parse queries
              $queryArray[sizeof($queryArray)]="DELETE FROM logentry WHERE ID='{$id}';";
              $deletedcommentsresult=$dbf->queryselect("SELECT ID FROM comments WHERE parent='$id';");
              //while($array=mysql_fetch_array($deletedcommentsresult, MYSQL_NUM))
              //{
                $queryArray[sizeof($queryArray)]="DELETE FROM comments WHERE parent='{$id}';";
              //}
              $queryArray[sizeof($queryArray)]="DELETE FROM logsvisited WHERE logid='{$id}';";
              //execute query
              $dbf->queryarray($queryArray);
              //execute feed parser
              if($readpermission==6)
              {
                $feedgenerator->parsefeed();
              }
              //back to log table page
              $parseheader="location:index.php?action=logtable&type={$logtype}&first=0";
            }
            else
            {
              $parseheader="location:index.php?action=incorrectargument";
            }
            break;

          case "Save":
            if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['logtype']))
            {
              //get parameters
              $id=$this->strip($_POST['ID']);
              $logtype=$this->strip($_POST['logtype']);
              $topic=$this->strip($_POST['topic']);
              $contract=$this->strip($_POST['contract']);
              $start=$this->strip($_POST['startyear'])."-".$this->strip($_POST['startmonth'])."-".$this->strip($_POST['startday']);
              $year=$this->strip($_POST['endyear']);
              $month=$this->strip($_POST['endmonth']);
              $day=$this->strip($_POST['endday']);
              $readpermission=$this->strip($_POST['readpermission']);
              if(($year!="0000" && $month!="00" && $day!="00") && ($year!="" && $month!="" && $day!=""))
              {
                $end="'".$year."-".$month."-".$day."'";
              }
              else
              {
                $end="NULL";
              }
              $place=$this->strip($_POST['place']);
              $text=$this->strip($_POST['text']);
              $user=$this->getUserName($this->strip($_SESSION['SESS_NAME']), $dbf);
              $datetime=$this->getZTime();
              //Chaeck for errors
              $errMSG="";
              if($topic=="")
              {
                $errMSG="no topic";
              }

              $logTypeResult=$dbf->queryselect("SELECT * FROM logtypes WHERE id='{$logtype}';");
              $logTypeArray=mysql_fetch_array($logTypeResult, MYSQL_NUM);
              if($place=="" && $logTypeArray[4]==1)
              {
                $errMSG="no location";
              }

              if($text=="" && $logTypeArray[5]==1)
              {
                $errMSG="no text";
              }

              if($errMSG=="" || $action=="Delete")
              {
                //parse query
                $queryArray[sizeof($queryArray)]="UPDATE logentry SET logtype='{$logtype}', start='{$start}', end={$end}, place='{$place}', text='{$text}', le='{$user}', ledate='{$datetime}', contract='{$contract}', topic='{$topic}' WHERE ID='{$id}';";
                //execute query
                $dbf->queryarray($queryArray);
                //execute feed parser
                if($readpermission==6)
                {
                  $feedgenerator->parsefeed();
                }
                //back to edit log table page
                $parseheader="location:index.php?action=logtable&type={$logtype}&first=0";
              }
              else
              {
                //back to edit log page with error string
                $parseheader="location:index.php?action=editlog&logd={$id}&type={$logtype}&err={$errMSG}";
              }
            }
            else
            {
              $parseheader="location:index.php?action=incorrectargument";
            }
            break;

          case "Add":
            if($this->checkposint($_POST['logtype']))
            {
              //get parameters
              $opid=$this->strip($_SESSION['SESS_ID']);
              $logtype=$this->strip($_POST['logtype']);
              $topic=$this->strip($_POST['topic']);
              $contract=$this->strip($_POST['contract']);
              $start=$this->strip($_POST['startyear'])."-".$this->strip($_POST['startmonth'])."-".$this->strip($_POST['startday']);
              $year=$this->strip($_POST['endyear']);
              $month=$this->strip($_POST['endmonth']);
              $day=$this->strip($_POST['endday']);
              $place=$this->strip($_POST['place']);
              $text=$this->strip($_POST['text']);
              $user=$this->getUserName($this->strip($_SESSION['SESS_NAME']), $dbf);
              $datetime=$this->getZTime();
              $readpermission=$this->strip($_POST['readpermission']);
              if(($year!="0000" && $month!="00" && $day!="00") && ($year!="" && $month!="" && $day!=""))
              {
                $end="'".$year."-".$month."-".$day."'";
              }
              else
              {
                $end="NULL";
              }
              $logTypeResult=$dbf->queryselect("SELECT * FROM logtypes WHERE id='{$logtype}';");
              $logTypeArray=mysql_fetch_array($logTypeResult, MYSQL_NUM);

              //Chaeck for errors
              $errMSG="";
              if($topic=="")
              {
                $errMSG="no topic";
              }

              if($place=="" && $logTypeArray[4]==1)
              {
                $errMSG="no location";
              }

              if($text=="" && $logTypeArray[5]==1)
              {
                $errMSG="no text";
              }

              if($errMSG=="" || $action=="Delete")
              {
                //parse queries
                $queryArray[sizeof($queryArray)]="INSERT INTO logentry (logtype, start, end, place, text, op, opdate, contract, topic, opid) VALUES ('{$logtype}', '{$start}', {$end}, '{$place}', '{$text}', '{$user}', '{$datetime}', '{$contract}', '{$topic}', {$opid});";
                $queryArray[sizeof($queryArray)]="INSERT INTO logsvisited (logtype, member, logid, lastcomment) VALUES ('{$logtype}', '{$opid}', LAST_INSERT_ID(), '0');";
                $queryArray[sizeof($queryArray)]=$this->getNewPostCount($this->strip($_SESSION['SESS_NAME']), $dbf);
                //execute queries
                $dbf->queryarray($queryArray);
                //execute feed parser
                if($readpermission==6)
                {
                  $feedgenerator->parsefeed();
                }
                //back to edit log table page
                $parseheader="location:index.php?action=logtable&type={$logtype}&first=0";
              }
              else
              {
                //back to edit log page with error string
                $parseheader="location:index.php?action=editlog&type={$logtype}&err={$errMSG}";
              }
            }
            else
            {
              $parseheader="location:index.php?action=incorrectargument";
            }
            break;
        }
      }
    }
    else
    {
      $parseheader="location:index.php?action=accessdenied";
    }
    return $parseheader;
  }
}
?>