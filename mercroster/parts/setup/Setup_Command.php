<?php
if(!defined('H4dc35F43cs'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}

function strip($data)
{
  require("htdocs/dbsetup.php");
  $data=stripslashes($data);
  $data=mysql_real_escape_string($data);
  $data=strip_tags($data);
  return $data;
}

if(!isset($_SESSION['SESS_ID']) || $_SESSION['SESS_TYPE']>'3')
{
  $error=true;
  $errormsg="Access denied.";
}
else
{
  require("includes/InputFields.php");
  $inputFields = new InputFields;

  //Fetching used dates data
  $datesResult = $dbf->queryselect("SELECT * FROM dates WHERE id=1;");
  $datesArray = mysql_fetch_array($datesResult, MYSQL_NUM);

  //Fetching Command data
  $commandResult = $dbf->queryselect("SELECT name, abbreviation, motto, description, image, icon, services, contact, main FROM command WHERE id=1;");
  $commandArray = mysql_fetch_array($commandResult, MYSQL_ASSOC);

  $sdate=$datesArray[1];
  $cdate=$datesArray[2];
  $edate=$datesArray[3];

  $min=2600;
  $max=3200;

  require("htdocs/dbsetup.php");
  $page=strip($_GET['page']);
  $sub=strip($_GET['sub']);

  echo "<div id='content'>\n";

  echo "<div id='setupheader'>\n";
  echo "<ul>\n";
  switch ($page)
  {
    case "2":
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=1'>Command Information</a></li>\n";
      echo "<li id='selected'><a class='generictableedit' href='index.php?action=command&amp;page=2'>Main Page</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=3'>Description</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=4'>Services</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=5'>Contact information:</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=6'>Dates</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=7&amp;sub=1'>Images</a></li>\n";
      break;
    case "3":
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=1'>Command Information</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=2'>Main Page</a></li>\n";
      echo "<li id='selected'><a class='generictableedit' href='index.php?action=command&amp;page=3'>Description</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=4'>Services</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=5'>Contact information:</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=6'>Dates</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=7&amp;sub=1'>Images</a></li>\n";
      break;
    case "4":
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=1'>Command Information</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=2'>Main Page</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=3'>Description</a></li>\n";
      echo "<li id='selected'><a class='generictableedit' href='index.php?action=command&amp;page=4'>Services</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=5'>Contact information:</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=6'>Dates</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=7&amp;sub=1'>Images</a></li>\n";
      break;
    case "5":
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=1'>Command Information</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=2'>Main Page</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=3'>Description</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=4'>Services</a></li>\n";
      echo "<li id='selected'><a class='generictableedit' href='index.php?action=command&amp;page=5'>Contact information:</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=6'>Dates</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=7&amp;sub=1'>Images</a></li>\n";
      break;
    case "6":
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=1'>Command Information</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=2'>Main Page</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=3'>Description</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=4'>Services</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=5'>Contact information:</a></li>\n";
      echo "<li id='selected'><a class='generictableedit' href='index.php?action=command&amp;page=6'>Dates</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=7&amp;sub=1'>Images</a></li>\n";
      break;
    case "7":
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=1'>Command Information</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=2'>Main Page</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=3'>Description</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=4'>Services</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=5'>Contact information:</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=6'>Dates</a></li>\n";
      echo "<li id='selected'><a class='generictableedit' href='index.php?action=command&amp;page=7&amp;sub=1'>Images</a></li>\n";
      break;
    default:
      echo "<li id='selected'><a class='generictableedit' href='index.php?action=command&amp;page=1'>Command Information</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=2'>Main Page</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=3'>Description</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=4'>Services</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=5'>Contact information:</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=6'>Dates</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=command&amp;page=7&amp;sub=1'>Images</a></li>\n";
      break;
  }
  echo "</ul>\n";
  echo "</div>\n";

  switch ($page)
  {
    //Dates
    case "6":
      echo "<div class='genericarea'>\n";
      echo "<form action='index.php?action=setupquery' method='post'>\n";
      echo "<table class='setupdatetable' border='0'>\n";
      //Starting Year
      echo "<tr>\n";
      echo "<th class='setupdateheader'>Starting</th>\n";
      $inputFields->datebar($sdate, $max, $min, "syear", "smonth", "sday", false);
      echo "</tr>\n";
      //Current Year
      echo "<tr>\n";
      echo "<th class='setupdateheader'>Current</th>\n";
      $inputFields->datebar($cdate, $max, $min, "cyear", "cmonth", "cday", false);
      echo "</tr>\n";
      //Ending Year
      echo "<tr>\n";
      echo "<th class='setupdateheader'>Ending</th>\n";
      $inputFields->datebar($edate, $max, $min, "eyear", "emonth", "eday", false);
      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td colspan='4'><input type='hidden' name='QueryType' value='Years' />\n";
      echo "<input class='edittablebutton' type='submit' value='Save' /></td>\n";
      echo "</tr>\n";
      echo "</table>\n";
      echo "</form>\n";
      echo "</div>\n";
      break;

      //Images
    case "7":
      echo"<div class='typecontainer' style='overflow: auto;'>\n";

      echo"<div id='typelist' class='typelist'>\n";
      echo "<ul>\n";
      $path="";
      switch ($sub)
      {
        case "2":
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=1'>Unit images</a></li>\n";
          echo "<li class='selectedtype'><a class='selectedtype' href='index.php?action=command&amp;page=7&amp;sub=2'>Personnel images</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=3'>Equipment images</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=4'>Command images</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=5'>Command icons</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=6'>Unit type icons</a></li>\n";
          $path="./images/personnelimages/";
          $opath="./images/personnelimages/";
          $width=200;
          $height=200;
          break;
        case "3":
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=1'>Unit images</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=2'>Personnel images</a></li>\n";
          echo "<li class='selectedtype'><a class='selectedtype' href='index.php?action=command&amp;page=7&amp;sub=3'>Equipment images</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=4'>Command images</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=5'>Command icons</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=6'>Unit type icons</a></li>\n";
          $path="./images/equipmentimages/";
          $opath="./images/equipmentimages/";
          $width=200;
          $height=200;
          break;
        case "4":
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=1'>Unit images</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=2'>Personnel images</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=3'>Equipment images</a></li>\n";
          echo "<li class='selectedtype'><a class='selectedtype' href='index.php?action=command&amp;page=7&amp;sub=4'>Command images</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=5'>Command icons</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=6'>Unit type icons</a></li>\n";
          $path="./images/commandimages/";
          $opath="./images/commandimages/";
          $width=0;
          $height=0;
          break;
        case "5":
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=1'>Unit images</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=2'>Personnel images</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=3'>Equipment images</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=4'>Command images</a></li>\n";
          echo "<li class='selectedtype'><a class='selectedtype' href='index.php?action=command&amp;page=7&amp;sub=5'>Command icons</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=6'>Unit type icons</a></li>\n";
          $path="./images/commandicons/";
          $opath="./images/commandicons/";
          $width=64;
          $height=64;
          break;
        case "6":
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=1'>Unit images</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=2'>Personnel images</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=3'>Equipment images</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=4'>Command images</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=5'>Command icons</a></li>\n";
          echo "<li class='selectedtype'><a class='selectedtype' href='index.php?action=command&amp;page=7&amp;sub=6'>Unit type icons</a></li>\n";
          $path="./images/unittype/";
          $opath="./images/unittype/";
          $width=58;
          $height=30;
          break;
        default:
          $sub=1;
          echo "<li class='selectedtype'><a class='selectedtype' href='index.php?action=command&amp;page=7&amp;sub=1'>Unit images</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=2'>Personnel images</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=3'>Equipment images</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=4'>Command images</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=5'>Command icons</a></li>\n";
          echo "<li><a class='notselectedtype' href='index.php?action=command&amp;page=7&amp;sub=6'>Unit type icons</a></li>\n";
          $path="./images/unitimages/";
          $opath="./images/unitimages/";
          $width=200;
          $height=200;
          break;
      }
      echo "</ul>\n";
      echo "</div>\n";

      //Finding images
      $desired_extension='png';
      $images=array();
      if($handle=opendir($opath))
      {
        while (false!==($file=readdir($handle)))
        {
          $fileChunks=explode(".", $file);
          if ($file!="." && $file!= ".." && $fileChunks[1]==$desired_extension)
          {
            list($iwidth, $iheight)=getimagesize($opath.$file);
            if(($iwidth==$width && $iheight==$height) || ($width==0 && $height==0))
            {
              array_push($images, $file);
            }
          }
        }
        closedir($handle);
      }
      sort($images);

      echo "<div id='typeeditarea' class='typeeditarea'>\n";
      for ($i=0; $i<sizeof($images); $i++)
      {
        echo "<table style='display: inline;'>\n";
        echo "<tr>\n";
        echo "<td>\n";
        echo "<table class='imagetable'>\n";
        echo "<tr>\n";
        echo "<th class='imagetableheader'>{$images[$i]}</th>\n";
        echo "</tr>\n";

        echo "<tr>\n";
        echo "<td class='imagetableimage'>\n";
        echo "<img class='imagetableimage' src='{$opath}{$images[$i]}' alt='$images[$i]' />";
        echo "</td>\n";
        echo "</tr>\n";

        echo "<tr>\n";
        echo "<td class='imagetabletext'>\n";
        //echo "<form action='includes/Up_File.php' method='post'>\n";
        echo "<form action='index.php?action=imageupload' method='post'>\n";
        echo "<div>\n";
        echo "\n";
        echo "<input type='hidden' name='sub' value='{$sub}' />\n";
        echo "<input type='hidden' name='path' value='{$path}' />\n";
        echo "<input type='hidden' name='imagefilename' value='{$images[$i]}' />\n";
        echo "<input type='hidden' name='type' value='imagerm' />\n";
        echo "<input type='submit' value='Delete' onclick='return confirmSubmit(\"Delete\")' />\n";
        echo "</div>\n";
        echo "</form>\n";
        echo "</td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "</td>\n";
        echo "</tr>\n";
        echo "</table>\n";
      }

      echo "<hr />\n";
      echo "Add Image<br />\n";
      if(isset($_GET['mgs']))
      {
        switch ($_GET['mgs'])
        {
          case "FileNotImage":
            $errormsg="Unable to upload image. No file given.";
            break;
          case "FileTooLarge":
            $errormsg="Unable to upload image. File too large.";
            break;
          case "FileExists":
            $errormsg="Unable to upload image. File already exists.";
            break;
          default:
            $errormsg="Unable to upload image..";
            break;

        }
        echo "<b>" . $errormsg . "</b><br />\n";
        echo "<b>Make corrections and try again.</b><br />\n";
      }
      echo "<form enctype='multipart/form-data' action='index.php?action=imageupload' method='post'>\n";
      echo "<div>\n";
      echo "<input type='hidden' name='sub' value='{$sub}' />\n";
      echo "<input type='hidden' name='path' value='{$path}' />\n";
      echo "<input type='hidden' name='type' value='imageadd' />\n";
      echo "<input name='file' type='file' />\n";
      echo "<input type='submit' value='Submit' />\n";
      echo "</div>\n";
      echo "</form>\n";
      echo "</div>\n";

      echo "</div>\n";
      break;

      //Main Page:
    case "2":
      echo "<div class='genericarea'>\n";
      echo "<form id='modified' action='index.php?action=setupquery' method='post'>\n";
      echo "<table class='main' border='0'>\n";
      if(isset($_GET['err']))
      {
        echo "<tr>\n";
        echo "<td colspan='2'><b>No changes was made because {$_GET['err']} was given.</b></td>\n";
        echo "</tr>\n";
      }
      $inputFields->textarea('edittableleft', 'edittableright', 1, 'Main Page', 'edittablecommon', 'main', $commandArray[main]);
      echo "<tr>\n";
      echo "<td colspan='2' class='edittablebottom'><input type='hidden' name='QueryType' value='Command' /> \n";
      echo "<input type='hidden' name='sub' value='main' /> \n";
      echo "<input class='edittablebutton' type='submit' value='Save' /></td>\n";
      echo "</tr>\n";
      echo "</table>\n";
      echo "</form>\n";
      echo "</div>\n";
      break;

      //Description:
    case "3":
      echo "<div class='genericarea'>\n";
      echo "<form id='modified' action='index.php?action=setupquery' method='post'>\n";
      echo "<table class='main' border='0'>\n";
      if(isset($_GET['err']))
      {
        echo "<tr>\n";
        echo "<td colspan='2'><b>No changes was made because {$_GET['err']} was given.</b></td>\n";
        echo "</tr>\n";
      }
      $inputFields->textarea("edittableleft", "edittableright", 1, "Description", "edittablecommon", "desc", $commandArray[description]);
      echo "<tr>\n";
      echo "<td colspan='2' class='edittablebottom'><input type='hidden' name='QueryType' value='Command' /> \n";
      echo "<input type='hidden' name='sub' value='desc' /> \n";
      echo "<input class='edittablebutton' type='submit' value='Save' /></td>\n";
      echo "</tr>\n";
      echo "</table>\n";
      echo "</form>\n";
      echo "</div>\n";
      break;

      //Services:
    case "4":
      echo "<div class='genericarea'>\n";
      echo "<form id='modified' action='index.php?action=setupquery' method='post'>\n";
      echo "<table class='main' border='0'>\n";
      if(isset($_GET['err']))
      {
        echo "<tr>\n";
        echo "<td colspan='2'><b>No changes was made because {$_GET['err']} was given.</b></td>\n";
        echo "</tr>\n";
      }
      $inputFields->textarea("edittableleft", "edittableright", 1, "Services", "edittablecommon", "services", $commandArray[services]);
      echo "<tr>\n";
      echo "<td colspan='2' class='edittablebottom'><input type='hidden' name='QueryType' value='Command' /> \n";
      echo "<input type='hidden' name='sub' value='serv' /> \n";
      echo "<input class='edittablebutton' type='submit' value='Save' /></td>\n";
      echo "</tr>\n";
      echo "</table>\n";
      echo "</form>\n";
      echo "</div>\n";
      break;

      //Contact information:
    case "5":
      echo "<div class='genericarea'>\n";
      echo "<form id='modified' action='index.php?action=setupquery' method='post'>\n";
      echo "<table class='main' border='0'>\n";
      if(isset($_GET['err']))
      {
        echo "<tr>\n";
        echo "<td colspan='2'><b>No changes was made because {$_GET['err']} was given.</b></td>\n";
        echo "</tr>\n";
      }
      $inputFields->textarea("edittableleft", "edittableright", 1, "Contact information", "edittablecommon", "contact", $commandArray[contact]);
      echo "<tr>\n";
      echo "<td colspan='2' class='edittablebottom'><input type='hidden' name='QueryType' value='Command' /> \n";
      echo "<input type='hidden' name='sub' value='cont' /> \n";
      echo "<input class='edittablebutton' type='submit' value='Save' /></td>\n";
      echo "</tr>\n";
      echo "</table>\n";
      echo "</form>\n";
      echo "</div>\n";
      break;

      //Command Information
    default:

      //Finding and organizing command images
      $desired_extension='png';
      $commandimages=array();
      if ($handle = opendir('./images/commandimages/'))
      {
        while (false!==($file = readdir($handle)))
        {
          $fileChunks=explode(".", $file);
          if ($file!="." && $file!=".." &&  $fileChunks[1]==$desired_extension)
          {
            array_push($commandimages, $file);
          }
        }
        closedir($handle);
      }
      sort($commandimages);

      //Finding and organizing command icons
      $desired_extension='png';
      $commandicons=array();
      if ($handle = opendir('./images/commandicons/'))
      {
        while (false!==($file = readdir($handle)))
        {
          $fileChunks=explode(".", $file);
          if ($file!="." && $file!=".." &&  $fileChunks[1]==$desired_extension)
          {
            list($width, $height) = getimagesize("images/commandicons/".$file);
            if($width==64 && $height==64)
            {
              array_push($commandicons, $file);
            }
          }
        }
        closedir($handle);
      }
      sort($commandicons);

      echo "<div class='genericarea'>\n";
      echo "<form id='modified' action='index.php?action=setupquery' method='post'>\n";
      echo "<table class='main' border='0'>\n";
      if(isset($_GET['err']))
      {
        echo "<tr>\n";
        echo "<td colspan='2'><b>No changes was made because {$_GET['err']} was given.</b></td>\n";
        echo "</tr>\n";
      }
      echo "<tr>\n";
      echo "<td class='edittableleft'>Name:</td>\n";
      echo "<td class='edittableright'><input class='edittablecommon' name='name' type='text' maxlength='100' value='{$commandArray[name]}' /></td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td class='edittableleft'>Abbreviation:</td>\n";
      echo "<td class='edittableright'><input class='edittablecommon' name='abb' 	type='text' maxlength='10' 	value='{$commandArray[abbreviation]}' /></td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td class='edittableleft'>Motto:</td>\n";
      echo "<td class='edittableright'><input class='edittablecommon' name='motto' type='text' maxlength='100' value='{$commandArray[motto]}' /></td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td colspan='2'>\n";
      echo "<hr />\n";
      echo "</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td class='edittableleft'>Image:</td>\n";
      //echo "<td class='edittableright'><input class='edittablecommon' name='image' type='text' maxlength='100' value='{$commandArray[image]}' /></td>\n";
      echo "<td class='edittableright'>\n";
      //$stringi="onchange='javascript:change_image(this.value, \"commandimages\")'";
      $inputFields->dropboxarscript($commandimages, $commandArray[image], "image", "edittablebox", "onchange='javascript:change_image(this.value, \"commandimages\")'", true);
      //$inputFields->dropboxarscript($commandimages, $commandArray[image], "image", "edittablebox", $stringi, true);
      echo "</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td class='edittableleft'>Icon:</td>\n";
      //echo "<td class='edittableright'><input class='edittablecommon' name='icon' type='text' maxlength='100' value='{$commandArray[icon]}' /></td>\n";
      echo "<td class='edittableright'>\n";
      $inputFields->dropboxarscript($commandicons, $commandArray[icon], "icon", "edittablebox", "onchange='javascript:change_image(this.value, \"commandicons\")'", true);
      echo "</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td colspan='2'>\n";

      echo "<table style='display: inline;'>\n";
      echo "<tr>\n";
      echo "<td>\n";
      echo "<table class='imagetable'>\n";
      echo "<tr>\n";
      echo "<th class='imagetableheader'>Command's Logo</th>\n";
      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td class='imagetableimage'>\n";
      echo "<img id='commandimages' class='imagetableimage' src='images/commandimages/$commandArray[image]' alt='$commandArray[image]' />";
      echo "</td>\n";
      echo "</tr>\n";
      echo "</table>\n";
      echo "</td>\n";
      echo "</tr>\n";
      echo "</table>\n";

      echo "<table style='display: inline;'>\n";
      echo "<tr>\n";
      echo "<td>\n";
      echo "<table class='imagetable'>\n";
      echo "<tr>\n";
      echo "<th class='imagetableheader'>Command's Icon</th>\n";
      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td class='imagetableimage'>\n";
      echo "<img id='commandicons' class='imagetableimage' src='images/commandicons/$commandArray[icon]' alt='$commandArray[icon]' />";
      echo "</td>\n";
      echo "</tr>\n";
      echo "</table>\n";
      echo "</td>\n";
      echo "</tr>\n";
      echo "</table>\n";

      //echo "<img id='commandimages' src='images/commandimages/$commandArray[image]' alt='$commandArray[image]' />";
      //echo "<img id='commandicons' src='images/commandicons/$commandArray[icon]' alt='$commandArray[icon]' />";
      echo "</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td colspan='2'>\n";
      echo "<hr />\n";
      echo "</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td colspan='2' class='edittablebottom'><input type='hidden' name='QueryType' value='Command' /> \n";
      echo "<input type='hidden' name='sub' value='plain' /> \n";
      echo "<input class='edittablebutton' type='submit' value='Save' /></td>\n";
      echo "</tr>\n";
      echo "</table>\n";
      echo "</form>\n";
      echo "</div>\n";
      break;
  }
  echo "</div>\n";
}
if($error)
{
  echo "<div id='content'>\n";
  echo "<div class='error'>\n";
  echo "<b>An error has occurred</b> while accessing this page.<br />\n";
  echo "You don't have rights to access this page.<br />\n";
  echo "Please be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg,"<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>