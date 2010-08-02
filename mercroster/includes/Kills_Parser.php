<?php
require("includes/Parser.php");
class Killsparser extends Parser
{
  /**
   * This function is used to handle kill relaited squeries
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
            $id=$_POST['ID'];
            $queryArray[sizeof($queryArray)] = "DELETE FROM kills WHERE id='{$id}';";
            $dbf->queryarray($queryArray);
            $parseheader="location:index.php?action=kill&first=0";
          }
          else
          {
            $parseheader="location:index.php?action=incorrectargument";
          }
          break;

        case "Save":
          if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['year']) && $this->checkposint($_POST['month']) && $this->checkposint($_POST['day']))
          {
            $id=$_POST['ID'];
            $parent=$this->strip($_POST['parent']);
            $type=$this->strip($_POST['type']);
            $weight=$this->strip($_POST['weight']);
            $killdate=$this->strip($_POST['year'])."-".$this->strip($_POST['month'])."-".$this->strip($_POST['day']);
            $equipment=$this->strip($_POST['equipment']);
            $eweight=$this->strip($_POST['eweight']);

            $errMSG="";
            if($type=="")
            {
              $errMSG="no type";
            }
            if($equipment=="")
            {
              $errMSG="no equipment";
            }
            if($errMSG=="")
            {
              $queryArray[sizeof($queryArray)] = "UPDATE kills SET parent='{$parent}', type='{$type}', weight='{$weight}', killdate='{$killdate}', equipment='{$equipment}', eweight='{$eweight}' WHERE id='{$id}';";
              $dbf->queryarray($queryArray);
              $parseheader="location:index.php?action=kill&first=0";
            }
            else
            {
              $parseheader="location:index.php?action=editkill&kill={$id}&err={$errMSG}";
            }
          }
          else
          {
            $parseheader="location:index.php?action=incorrectargument";
          }
          break;

        case "Add":
          if($this->checkposint($_POST['year']) && $this->checkposint($_POST['month']) && $this->checkposint($_POST['day']))
          {
            $parent=$this->strip($_POST['parent']);
            $type=$this->strip($_POST['type']);
            $weight=$this->strip($_POST['weight']);
            $killdate=$this->strip($_POST['year'])."-".$this->strip($_POST['month'])."-".$this->strip($_POST['day']);
            $equipment=$this->strip($_POST['equipment']);
            $eweight=$this->strip($_POST['eweight']);

            $errMSG="";
            if($type=="")
            {
              $errMSG="no type";
            }
            if($equipment=="")
            {
              $errMSG="no equipment";
            }
            if($errMSG=="")
            {
              $queryArray[sizeof($queryArray)] = "INSERT INTO kills (parent, type, weight, killdate, equipment, eweight) VALUES ('{$parent}', '{$type}', '{$weight}', '{$killdate}' ,'{$equipment}', '{$eweight}');";
              $dbf->queryarray($queryArray);
              $parseheader="location:index.php?action=kill&first=0";
            }
            else
            {
              $parseheader="location:index.php?action=editkill&err={$errMSG}";
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