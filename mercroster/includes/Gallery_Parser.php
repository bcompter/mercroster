<?php
require("includes/Parser.php");
class Galleryparser extends Parser
{
  /**
   * This function is used to handle gallery relaited squeries
   * @return string
   */
  function parse()
  {
    if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<='4')
    {
      require("htdocs/dbsetup.php");
      $dbf=new DBFunctions;

      switch ($_POST['QueryAction'])
      {
        case "Delete":
          if($this->checkposint($_POST['id']))
          {
            $id=$_POST['id'];
            $queryArray[sizeof($queryArray)] = "DELETE FROM gallery WHERE id='{$id}';";
            $dbf->queryarray($queryArray);
            $parseheader="location:index.php?action=gallerytable&first=0";
          }
          else
          {
            $parseheader="location:index.php?action=incorrectargument";
          }
          break;

        case "Save":
          if($this->checkposint($_POST['id']) && $this->checkposint($_POST['type']))
          {
            $id=$_POST['id'];
            $name=$this->strip($_POST['name']);
            $type=$this->strip($_POST['type']);

            $errMSG="";
            if($type=="")
            {
              $errMSG="no type";
            }
            if($name=="")
            {
              $errMSG="no name";
            }
            if($errMSG=="")
            {
              $queryArray[sizeof($queryArray)] = "UPDATE gallery SET name='{$name}', type='{$type}' WHERE id='{$id}';";
              $dbf->queryarray($queryArray);
              $parseheader="location:index.php?action=gallery&gallery={$id}";
            }
            else
            {
              $parseheader="location:index.php?action=editgallery&gallery={$id}&err={$errMSG}";
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
            $type=$this->strip($_POST['type']);
            $user=$this->strip($_POST['user']);

            $errMSG="";
            if($type=="")
            {
              $errMSG="no type";
            }
            if($name=="")
            {
              $errMSG="no name";
            }
            if($user=="")
            {
              $errMSG="no user";
            }
            if($errMSG=="")
            {
              $queryArray[sizeof($queryArray)] = "INSERT INTO gallery (user, type, name) VALUES ('{$user}', '{$type}', '{$name}');";
              $dbf->queryarray($queryArray);
              $parseheader="location:index.php?action=gallerytable&first=0";
            }
            else
            {
              $parseheader="location:index.php?action=editgallery&err={$errMSG}";
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