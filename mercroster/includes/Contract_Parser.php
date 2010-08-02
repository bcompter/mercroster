<?php
require("includes/Parser.php");
class Contractparser extends Parser
{
  /**
   * This function is used to handle log relaited squeries
   * @return string
   */
  function parse()
  {
    if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<'5') //for Commanders, GMs and administrators
    {
      require("htdocs/dbsetup.php");
      $dbf=new DBFunctions;

      switch ($_POST['QueryAction'])
      {
        case "Delete":
          if($this->checkposint($_POST['ID']))
          {
            $id=$this->strip($_POST['ID']);
            $queryArray[sizeof($queryArray)]="DELETE FROM contracts WHERE id='{$id}';";
            $dbf->queryarray($queryArray);
            $parseheader="location:index.php?action=contracttable&first=0";
          }
          else
          {
            $parseheader="location:index.php?action=incorrectargument";
          }
          break;

        case "Save":
          if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['startingyear']) && $this->checkposint($_POST['startingmonth']) && $this->checkposint($_POST['startingday']) && $this->checkposint($_POST['endingyear']) && $this->checkposint($_POST['endingmonth']) && $this->checkposint($_POST['endingday']))
          {
            $id=$this->strip($_POST['ID']);
            $name=$this->strip($_POST['name']);
            $start=$this->strip($_POST['startingyear'])."-".$this->strip($_POST['startingmonth'])."-".$this->strip($_POST['startingday']);
            $end=$this->strip($_POST['endingyear'])."-".$this->strip($_POST['endingmonth'])."-".$this->strip($_POST['endingday']);
            $employer=$this->strip($_POST['employer']);
            $mission=$this->strip($_POST['missiontype']);
            $target=$this->strip($_POST['target']);
            $result=$this->strip($_POST['result']);

            $errMSG="";
            if($name=="")
            {
              $errMSG="no name";
            }
            if($employer=="")
            {
              $errMSG="no employer";
            }
            if($mission=="")
            {
              $errMSG="no mission";
            }
            if($errMSG=="")
            {
              $queryArray[sizeof($queryArray)] = "UPDATE contracts SET start='{$start}', end='{$end}', employer='{$employer}', missiontype='{$mission}', target='{$target}', result='{$result}', name='{$name}' WHERE id='{$id}';";
              $dbf->queryarray($queryArray);
              $parseheader="location:index.php?action=contracttable&first=0";
            }
            else
            {
              $parseheader="location:index.php?action=editcontract&contract={$id}&err={$errMSG}";
            }
          }
          else
          {
            $parseheader="location:index.php?action=incorrectargument";
          }
          break;

        case "Add":
          if($this->checkposint($_POST['startingyear']) && $this->checkposint($_POST['startingmonth']) && $this->checkposint($_POST['startingday']) && $this->checkposint($_POST['endingyear']) && $this->checkposint($_POST['endingmonth']) && $this->checkposint($_POST['endingday']))
          {
            $name=$this->strip($_POST['name']);
            $start=$this->strip($_POST['startingyear'])."-".$this->strip($_POST['startingmonth'])."-".$this->strip($_POST['startingday']);
            $end=$this->strip($_POST['endingyear'])."-".$this->strip($_POST['endingmonth'])."-".$this->strip($_POST['endingday']);
            $employer=$this->strip($_POST['employer']);
            $mission=$this->strip($_POST['missiontype']);
            $target=$this->strip($_POST['target']);
            $result=$this->strip($_POST['result']);

            $errMSG="";
            if($name=="")
            {
              $errMSG="no name";
            }
            if($employer=="")
            {
              $errMSG="no employer";
            }
            if($mission=="")
            {
              $errMSG="no mission";
            }
            if($errMSG=="")
            {
              $queryArray[sizeof($queryArray)]="INSERT INTO contracts (start, end, employer, missiontype, target, result, name) VALUES ('{$start}', '{$end}', '{$employer}', '{$mission}', '{$target}', '{$result}', '{$name}');";
              $dbf->queryarray($queryArray);
              $parseheader="location:index.php?action=contracttable&first=0";
            }
            else
            {
              $parseheader="location:index.php?action=editcontract&err={$errMSG}";
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
      $parseheader="location:index.php?action=accessdenied";
    }
    return $parseheader;
  }
}
?>