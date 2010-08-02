<?php
if(!defined('Fr5v30m3F5'))
{
  header('HTTP/1.0 404 not found');
  exit;
}

require("htdocs/dbsetup.php");
$galleryID=$_GET['gallery'];
$galleryID=stripslashes($galleryID);
$galleryID=mysql_real_escape_string($galleryID);

$imageID=$_GET['image'];
$imageID=stripslashes($imageID);
$imageID=mysql_real_escape_string($imageID);

require("includes/InputFields.php");
$inputFields=new InputFields;

if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<='4')
{
  if(isset($_GET['gallery']) && isset($_GET['image']))
  {
    $galleryresult=$dbf->queryselect("SELECT g.id, g.user, g.type, g.name, m.sitename, m.id AS userid FROM gallery g LEFT JOIN members m ON g.user=m.username WHERE g.id='{$galleryID}';");
    $imageresult=$dbf->queryselect("SELECT id, name, filename, gallery, comment FROM images WHERE id='{$imageID}';");

    if(mysql_num_rows($galleryresult)==1 && mysql_num_rows($imageresult)==1)
    {
      $galleryarray=mysql_fetch_array($galleryresult, MYSQL_ASSOC);
      $imagearray=mysql_fetch_array($imageresult, MYSQL_ASSOC);
      $topicText="Edit {$galleryarray['name']}'s / {$imagearray['name']} information";
      $submitButtonText='Save';

      if($galleryarray['userid']!=$_SESSION['SESS_ID'] && $_SESSION['SESS_TYPE']!='1')
      {
        $error=true;
        $errormsg="Access denied.";
      }
    }
    else
    {
      $error=true;
      $errormsg="No gallery or image found.";
    }
  }
  else if(isset($_GET['gallery']) && !isset($_GET['image']))
  {
    $galleryresult=$dbf->queryselect("SELECT g.id, g.user, g.type, g.name, m.sitename, m.id AS userid FROM gallery g LEFT JOIN members m ON g.user=m.username WHERE g.id='{$galleryID}';");

    if(mysql_num_rows($galleryresult)==1)
    {
      $galleryarray=mysql_fetch_array($galleryresult, MYSQL_ASSOC);
      $topicText="Add Image to {$galleryarray['name']}";
      $submitButtonText='Add';

      if($galleryarray['userid']!=$_SESSION['SESS_ID'] && $_SESSION['SESS_TYPE']!='1')
      {
        $error=true;
        $errormsg="Access denied.";
      }
    }
    else
    {
      $error=true;
      $errormsg="No gallery found.";
    }
  }
  else
  {
    $error=true;
    $errormsg="Insufficient arguments.";
  }
  if(!$error)
  {
    echo "<div id='content'>\n";

    echo "<div class='genericarea'>\n";
    echo "<b>{$topicText}</b>\n";

    if(!isset($_GET['image']))
    {
      echo "<form enctype='multipart/form-data' action='index.php?action=imageupload' method='post' id='modified'>\n";
    }
    else
    {
      echo "<form action='index.php?action=imageupload' method='post' id='modified'>\n";
    }
    echo "<table class='main' border='0'>\n";
    //Name
    echo "<tr>\n";
    echo "<td class='edittableleft'>Name:</td>\n";
    echo "<td class='edittableright' colspan='6'>";
    $inputFields->textinput("edittablecommon265","name",100,$array['name']);
    echo "</td>\n";
    echo "</tr>\n";
    if(!isset($_GET['image']))
    {
      echo "<tr>\n";
      echo "<td class='edittableleft'>File:</td>\n";
      echo "<td class='edittableright' colspan='6'>";
      echo "<input name='file' type='file' />\n";
      echo "</td>\n";
      echo "</tr>\n";
    }
    //Description
    $inputFields->textarea("edittableleft", "edittableright", 6, "Description", "edittablecommon", "comment", $imagearray['comment']);
    echo "<tr>\n";
    echo "<td colspan='7' class='edittablebottom'>\n";
    echo "<input type='hidden' name='imageid' value='{$imagearray['id']}' />\n";
    echo "<input type='hidden' name='sub' value='{$galleryID}' />\n";
    echo "<input type='hidden' name='path' value='./images/galleryimages/' />\n";
    echo "<input type='hidden' name='type' value='galleryimageadd' />\n";
    echo "<input class='edittablebutton' name='QueryAction' type='submit' value='{$submitButtonText}' />\n";
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "</form>\n";
    echo "</div>\n";

    echo "</div>\n";
  }
}
else
{
  $error=true;
  $errormsg="Access denied.";
}
//Error Handling
if($error)
{
  echo "<div id='content'>\n";
  echo "<div class='error'>\n";
  echo "<b>An error has occurred</b> while accessing gallery information.<br />\n";
  echo "No gallery found or you don't have rights to access this gallery's information.<br />\n";
  echo "Please use correct links and be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg,"<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>