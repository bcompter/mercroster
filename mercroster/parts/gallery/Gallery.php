<?php
if(!defined('FGk348dEDf'))
{
  header('HTTP/1.0 404 not found');
  exit;
}

require("htdocs/dbsetup.php");
$galleryID=$_GET['gallery'];
$galleryID=stripslashes($galleryID);
$galleryID=mysql_real_escape_string($galleryID);

$typeTextArray=array("", "Log Images","Map Images","Art","Generic");

$galleryResult=$dbf->queryselect("SELECT m.sitename, g.type, g.name, m.id AS userid FROM gallery g, members m WHERE g.id='{$galleryID}' AND m.username=g.user;");

if(mysql_num_rows($galleryResult)==1)
{
  $galleryArray=mysql_fetch_array($galleryResult, MYSQL_ASSOC);
  $imagesResult=$dbf->queryselect("SELECT id, name, filename, comment FROM images WHERE gallery='{$galleryID}';");

  echo "<div id='content'>\n";
  echo "<h1 class='headercenter'>{$galleryArray['name']}</h1>\n";
  if(mysql_num_rows($imagesResult)>0)
  {
    echo"<div class='typecontainer' style='overflow: auto;'>\n";
    echo "<div class='genericheader'>\n";
    echo "{$galleryArray['sitename']}'s {$typeTextArray[$galleryArray['type']]} gallery\n";
    if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<='4' && ($array['userid']==$_SESSION['SESS_ID'] || $_SESSION['SESS_TYPE']=='1'))
    {
      echo "<a class='genericedit' href='index.php?action=editgallery&amp;gallery={$galleryID}'>Edit</a>\n";
    }
    echo "</div>\n";
    while($array=mysql_fetch_array($imagesResult, MYSQL_ASSOC))
    {
      echo "<table style='display: inline;'>\n";
      echo "<tr>\n";
      echo "<td>\n";
      echo "<table class='imagetable'>\n";
      echo "<tr>\n";
      echo "<th class='imagetableheader'>{$array['name']}</th>\n";
      echo "</tr>\n";

      echo "<tr>\n";
      echo "<td class='imagetableimage'>\n";
      echo "<img class='imagetableimage' src='./images/galleryimages/{$array['filename']}' alt='{$array['name']}' />";
      echo "</td>\n";
      echo "</tr>\n";

      if(isset($_SESSION['SESS_ID']) && ($_SESSION['SESS_ID']==$galleryArray['userid'] || $_SESSION['SESS_TYPE']==1))
      {
        echo "<tr>\n";
        echo "<td class='imagetabletext'>\n";
        echo "<form action='index.php?action=imageupload' method='post'>\n";
        echo "<div>\n";
        echo "<input type='hidden' name='imagefilename' value='{$array['filename']}' />\n";
        echo "<input type='hidden' name='imageid' value='{$array['id']}' />\n";
        echo "<input type='hidden' name='sub' value='{$galleryID}' />\n";
        echo "<input type='hidden' name='path' value='./images/galleryimages/' />\n";
        echo "<input type='hidden' name='type' value='galleryimagerm' />\n";
        echo "<input type='submit' value='Delete' onclick='return confirmSubmit(\"Delete\")' />\n";
        echo "</div>\n";
        echo "</form>\n";
        echo "</td>\n";
        echo "</tr>\n";
      }
      echo "</table>\n";

      echo "</td>\n";
      echo "</tr>\n";
      echo "</table>\n";
    }

    if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<='4' && ($array['userid']==$_SESSION['SESS_ID'] || $_SESSION['SESS_TYPE']=='1'))
    {
      echo "<div class='genericfooter'>\n";
      echo "<a class='rostertabletopic' href='index.php?action=editimage&amp;gallery={$galleryID}'>Add Image</a>\n";
      echo "</div>\n";
    }

    echo "</div>\n";
  }
  else
  {
    echo "<div class='genericarea'>\n";
    echo "<div class='genericheader'>\n";
    echo "{$galleryArray['sitename']}'s {$galleryArray['type']} dosen't contain any images yet.\n";
    if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<='4' && ($galleryArray['userid']==$_SESSION['SESS_ID'] || $_SESSION['SESS_TYPE']=='1'))
    {
      echo "<a class='genericedit' href='index.php?action=editgallery&amp;gallery={$galleryID}'>Edit</a>\n";
    }
    echo "</div>\n";
    
    echo "<div class='genericfooter'>\n";
    if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<='4' && ($galleryArray['userid']==$_SESSION['SESS_ID'] || $_SESSION['SESS_TYPE']=='1'))
    {
      echo "<a class='rostertabletopic' href='index.php?action=editimage&amp;gallery={$galleryID}'>Add Image</a>\n";
    }
    echo "</div>\n";
    echo "</div>\n";
  }
  echo "</div>\n";
}
else
{
  $error=true;
  $errormsg="Personnel not found";
}
//Error Handling
if($error)
{
  echo "<div id='content'>\n";
  echo "<div class='error'>\n";
  echo "<b>An error has occurred</b> while accessing gallery information.<br />\n";
  echo "No personnel found or you don't have rights to access this gallery.<br />\n";
  echo "Please use correct links and be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg,"<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>