<?php
require("includes/Parser.php");
class Setupsiteparser extends Parser
{

  private function move($type, $id, $prefpos, $direction, $dbf)
  {
    //if we have crewtype placement
    if($type=="logtypes")
    {
      if($direction=="Down")
      {
        $newpos=$prefpos+1;
      }
      else
      {
        $newpos=$prefpos-1;
      }
      $usedCrewTypeArray=mysql_fetch_array($dbf->queryselect("SELECT id FROM {$type} WHERE prefpos='{$newpos}';"), MYSQL_NUM);
      $otherid=$usedCrewTypeArray[0];

      //echo "{$ID}=> {$newpos} | {$otherid}=>{$prefpos}";
      $queryArray[sizeof($queryArray)]="UPDATE {$type} SET prefpos='$newpos' WHERE id='{$id}';";
      $queryArray[sizeof($queryArray)]="UPDATE {$type} SET prefpos='$prefpos' WHERE id='{$otherid}';";
    }
    return $queryArray;
  }

  /**
   * This function is used to handle log relaited squeries
   * @return string
   */
  function parse()
  {
    if(isset($_SESSION['SESS_NAME']) && $_SESSION['SESS_TYPE']=='1') //for Commanders, GMs and administrators
    {
      require("htdocs/dbsetup.php");
      $dbf=new DBFunctions;
      switch ($_POST['QueryType'])
      {
        case "logtype":
          switch ($_POST['QueryAction'])
          {
            case "Delete":
              if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['prefpos']))
              {
                $id=$this->strip($_POST['ID']);
                $prefpos=$this->strip($_POST['prefpos']);
                $queryArray[sizeof($queryArray)]="DELETE FROM logtypes WHERE id='{$id}';";
                $queryArray[sizeof($queryArray)]="DELETE FROM lastlog WHERE logtype='{$id}';";
                $queryArray[sizeof($queryArray)]="DELETE FROM logsvisited WHERE logtype='{$id}';";
                $positionresult=$dbf->queryselect("SELECT id, prefpos FROM logtypes WHERE prefpos>'{$prefpos}';");
                while($array = mysql_fetch_array($positionresult, MYSQL_NUM))
                {
                  $pp = $array[1]-1;
                  $queryArray[sizeof($queryArray)] = "UPDATE logtypes SET prefpos='{$pp}' WHERE id='{$array[0]}';";
                }
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=site&page=1";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Change":
              if($this->checkposint($_POST['ID']))
              {
                $id=$this->strip($_POST['ID']);
                $logtype=$this->strip($_POST['logtype']);
                $writepermission=$this->strip($_POST['writepermission']);
                $readpermission=$this->strip($_POST['readpermission']);
                $start=$this->strip($_POST['start']);
                $end=$this->strip($_POST['end']);
                $location=$this->strip($_POST['location']);
                $text=$this->strip($_POST['text']);
                $contract=$this->strip($_POST['contract']);
                $prefpos=$this->strip($_POST['prefpos']);

                $errMSG="";
                if($logtype=="")
                {
                  $errMSG="no topic name.";
                }
                if($errMSG=="")
                {
                  if($start=="on")
                  {
                    $start=1;
                  }
                  else
                  {
                    $start=0;
                  }
                  if($end=="on")
                  {
                    $end=1;
                  }
                  else
                  {
                    $end=0;
                  }
                  if($location=="on")
                  {
                    $location=1;
                  }
                  else
                  {
                    $location=0;
                  }
                  if($text=="on")
                  {
                    $text=1;
                  }
                  else
                  {
                    $text=0;
                  }
                  if($contract=="on")
                  {
                    $contract=1;
                  }
                  else
                  {
                    $contract=0;
                  }
                  $queryArray[sizeof($queryArray)] = "UPDATE logtypes SET type='{$logtype}', start='{$start}', end='{$end}', location='{$location}', text='{$text}', contract='{$contract}', writepermission='{$writepermission}', readpermission='{$readpermission}' WHERE ID='{$id}';";
                  $dbf->queryarray($queryArray);
                  $parseheader="location:index.php?action=site&page=1&sub={$id}";
                }
                else
                {
                  $parseheader="location:index.php?action=site&page=1&err={$errMSG}";
                }
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Add":
              $logtype=$this->strip($_POST['logtype']);
              $writepermission=$this->strip($_POST['writepermission']);
              $readpermission=$this->strip($_POST['readpermission']);
              $start=$this->strip($_POST['start']);
              $end=$this->strip($_POST['end']);
              $location=$this->strip($_POST['location']);
              $text=$this->strip($_POST['text']);
              $contract=$this->strip($_POST['contract']);

              $errMSG="";
              if($logtype=="")
              {
                $errMSG="no topic name.";
              }
              if($errMSG=="")
              {
                if($start=="on")
                {
                  $start=1;
                }
                else
                {
                  $start=0;
                }
                if($end=="on")
                {
                  $end=1;
                }
                else
                {
                  $end=0;
                }
                if($location=="on")
                {
                  $location=1;
                }
                else
                {
                  $location=0;
                }
                if($text=="on")
                {
                  $text=1;
                }
                else
                {
                  $text=0;
                }
                if($contract=="on")
                {
                  $contract=1;
                }
                else
                {
                  $contract=0;
                }
                $rResult=$dbf->queryselect("SELECT COUNT(*) count FROM logtypes;");
                $logtypesnumber=mysql_result($rResult, 0)+1;
                $nextidResult=$dbf->queryselect("SELECT max(id) FROM logtypes;");
                $temp=mysql_result($nextidResult, 0)+1;
                //$temp=$nextidArray[0]+1;
                $queryArray[sizeof($queryArray)]="INSERT INTO logtypes (id, type, start, end, location, text, contract, writepermission, readpermission, prefpos) VALUES ('{$temp}', '{$logtype}', '{$start}', '{$end}', '{$location}', '{$text}', '{$contract}', '{$writepermission}', '{$readpermission}', '{$logtypesnumber}');";
                $userResult=$dbf->queryselect("SELECT id, type FROM members;");
                while ($userArray=mysql_fetch_array($userResult, MYSQL_NUM))
                {
                  $queryArray[sizeof($queryArray)]="INSERT INTO lastlog (member, logtype, lasttopic) VALUES ('{$userArray[0]}', '{$temp}', '0');";
                }
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=site&page=1";
              }
              else
              {
                $parseheader="location:index.php?action=site&page=1&err={$errMSG}";
              }
              break;
          }
          break;

        case "logtypemove":
          if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['prefpos']))
          {
            $id=$this->strip($_POST['ID']);
            $prefpos=$this->strip($_POST['prefpos']);
            $direction=$this->strip($_POST['QueryAction']);
            $dbf->queryarray($this->move("logtypes", $id, $prefpos, $direction, $dbf));
            $parseheader="location:index.php?action=site&page=1&sub={$id}";
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
      $parseheader="location:index.php?action=accessdenied";
    }
    return $parseheader;
  }
}
?>