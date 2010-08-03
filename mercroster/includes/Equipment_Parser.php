<?php
require("includes/Parser.php");
class Equipmentparser extends Parser
{
  /**
   * This function is used to handle log relaited squeries
   * @return string
   */
  function parse()
  {
    if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<'4') //for Commanders, GMs and administrators
    {
      require("htdocs/dbsetup.php");
      $dbf=new DBFunctions;

      switch ($_POST['QueryAction'])
      {
        case "Delete":
          if($this->checkposint($_POST['ID']))
          {
            $id=$this->strip($_POST['ID']);
            $type=$this->strip($_POST['type']);
            $queryArray[sizeof($queryArray)]="DELETE FROM equipment WHERE id='{$id}';";
            $dbf->queryarray($queryArray);
            $parseheader="location:index.php?action=equipmenttable&type={$type}&order=1&first=0";
          }
          else
          {
            $parseheader="location:index.php?action=incorrectargument";
          }
          break;

        case "Save":
          if($this->checkposint($_POST['ID']))
          {
            $id=$this->strip($_POST['ID']);
            $name=$this->strip($_POST['name']);
            $subtype=$this->strip($_POST['subtype']);
            $regnumber=$this->strip($_POST['gkknumber']);
            $weight=$this->strip($_POST['weight']);
            $notes=$this->strip($_POST['notes']);
            $type=$this->strip($_POST['type']);
            $troid=$this->strip($_POST['tro']);
            $image=$this->strip($_POST['image']);

            $errMSG="";
            if($name=="")
            {
              $errMSG="no name";
            }
            if($subtype=="")
            {
              $errMSG="no subtype";
            }
            if($errMSG=="")
            {
              $queryArray[sizeof($queryArray)] = "UPDATE equipment SET type='{$type}', name='{$name}', subtype='{$subtype}', weight='{$weight}', regnumber='{$regnumber}', notes='{$notes}', troid='{$troid}', image='{$image}' WHERE id='{$id}';";
              $dbf->queryarray($queryArray);
              $parseheader="location:index.php?action=editequipment&equipment={$id}";
            }
            else
            {
              $parseheader="location:index.php?action=editequipment&equipment={$id}&err={$errMSG}";
            }
          }
          else
          {
            $parseheader="location:index.php?action=incorrectargument";
          }
          break;

        case "Add":
          $name=$this->strip($_POST['name']);
          $subtype=$this->strip($_POST['subtype']);
          $regnumber=$this->strip($_POST['gkknumber']);
          $weight=$this->strip($_POST['weight']);
          $notes=$this->strip($_POST['notes']);
          $type=$this->strip($_POST['type']);
          $troid=$this->strip($_POST['tro']);
          $image=$this->strip($_POST['image']);

          $errMSG="";
          if($name=="")
          {
            $errMSG="no name";
          }
          if($subtype=="")
          {
            $errMSG="no subtype";
          }
          if($errMSG=="")
          {
            $queryArray[sizeof($queryArray)]="INSERT INTO equipment (type, name, subtype, weight, regnumber, notes, troid, image) VALUES ('{$type}', '{$name}', '{$subtype}', '{$weight}', '{$regnumber}', '{$notes}', '{$troid}', {$image}');";
            $dbf->queryarray($queryArray);
            $parseheader="location:index.php?action=equipmenttable&type={$type}&order=1&first=0";
          }
          else
          {
            $parseheader="location:index.php?action=editequipment&type={$type}&err={$errMSG}";
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