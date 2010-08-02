<?php
require("includes/Parser.php");
class Troparser extends Parser
{
  /**
   * This function is used to handle log relaited squeries
   * @return string
   */
  function parse()
  {
    if(isset($_SESSION['SESS_NAME']) && $_SESSION['SESS_TYPE']<'4') //for Commanders, GMs and administrators
    {
      require("htdocs/dbsetup.php");
      $dbf=new DBFunctions;
      /**
       * This function is used to check and determine which tro queries will be made
       * @param $dbf
       * @param $parser
       */
      switch ($_POST['QueryAction'])
      {
        case "Delete":
          if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['type']))
          {
            $id=$this->strip($_POST['ID']);
            $type=$this->strip($_POST['type']);
            $queryArray[sizeof($queryArray)]="DELETE FROM technicalreadouts WHERE id='{$id}';";
            $dbf->queryarray($queryArray);
            $parseheader="location:index.php?action=trotable&type={$type}&order=1&first=0";
          }
          else
          {
            $parseheader="location:index.php?action=incorrectargument";
          }
          break;

        case "Save":
          if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['type']))
          {
            $id=$this->strip($_POST['ID']);
            $name=$this->strip($_POST['name']);
            $weight=$this->strip($_POST['weight']);
            $notes=$this->strip($_POST['notes']);
            $type=$this->strip($_POST['type']);

            $errMSG="";
            if($name=="")
            {
              $errMSG="no name";
            }
            if($notes=="")
            {
              $errMSG="no note";
            }
            if($errMSG=="")
            {
              $queryArray[sizeof($queryArray)]="UPDATE technicalreadouts SET name='{$name}', type='{$type}', weight='{$weight}', text='{$notes}' WHERE id='{$id}';";
              $dbf->queryarray($queryArray);
              $parseheader="location:index.php?action=trotable&type={$type}&order=1&first=0";
            }
            else
            {
              $parseheader="location:index.php?action=edittro&trorder={$id}&err={$errMSG}";
            }
          }
          else
          {
            $parseheader="location:index.php?action=incorrectargument";
          }
          break;

        case "Add":
          if($this->checkposint($_POST['type']))
          {
            $name=$this->strip($_POST['name']);
            $weight=$this->strip($_POST['weight']);
            $notes=$this->strip($_POST['notes']);
            $type=$this->strip($_POST['type']);

            $errMSG="";
            if($name=="")
            {
              $errMSG="no name";
            }
            if($notes=="")
            {
              $errMSG="no note";
            }
            if($errMSG=="")
            {
              $queryArray[sizeof($queryArray)]="INSERT INTO technicalreadouts (name, type, weight, text) VALUES ('{$name}', '{$type}', '{$weight}', '{$notes}');";
              $dbf->queryarray($queryArray);
              $type=$this->strip($_POST['type']);
              $parseheader="location:index.php?action=trotable&type={$type}&order=1&first=0";
            }
            else
            {
              $parseheader="location:index.php?action=edittro&type={$type}&err={$errMSG}";
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