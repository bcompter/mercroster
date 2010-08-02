<?php
if(!defined('Ir4cwe57FdC'))
{
  header('HTTP/1.0 404 not found');
  exit;
}

require("htdocs/dbsetup.php");
$galleryID=$_GET['gallery'];
$galleryID=stripslashes($galleryID);
$galleryID=mysql_real_escape_string($galleryID);

require("includes/InputFields.php");
$inputFields=new InputFields;

$typeArray=array(1,2,3,4);
$typeTextArray=array("Log Images","Map Images","Art","Generic");


if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<='4')
{
  if(isset($_GET['gallery']))
  {
    $result=$dbf->queryselect("SELECT g.id, g.user, g.type, g.name, m.sitename FROM gallery g LEFT JOIN members m ON g.user=m.username WHERE g.id='$galleryID';");

    if(mysql_num_rows($result)==1)
    {
      $array=mysql_fetch_array($result, MYSQL_ASSOC);
      $topicText="{$array['sitename']}'s ";
      $submitButtonText='Save';
      $guser=$array['user'];

      if($array[user]!=$_SESSION['SESS_NAME'] && $_SESSION['SESS_TYPE']!='1')
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
    $submitButtonText='Add';
    $topicText='New ';
    require("htdocs/dbsetup.php");
    $guser=$_SESSION['SESS_NAME'];
    $guser=stripslashes($guser);
    $guser=mysql_real_escape_string($guser);
  }
  if(!$error)
  {
    echo "<div id='content'>\n";

    echo "<div class='genericarea'>\n";
    echo "<b>{$topicText} Gallery Information</b>\n";
    echo "<form action='index.php?action=galleryquery' method='post' id='modified'>\n";
    echo "<table class='main' border='0'>\n";
    //Name
    echo "<tr>\n";
    echo "<td class='edittableleft'>Name:</td>\n";
    echo "<td class='edittableright' colspan='6'>";
    $inputFields->textinput("edittablecommon265","name",45,$array['name']);
    echo "</td>\n";
    echo "</tr>\n";
    //Type
    echo "<tr>\n";
    echo "<td class='edittableleft'>Type:</td>\n";
    echo "<td class='edittableright' colspan='6'>\n";
    $inputFields->dropboxarnumbers($typeTextArray, $typeArray, $array['type'], "type", "edittablebox");
    echo "</td>\n";
    echo "</tr>\n";

    echo "<td colspan='7' class='edittablebottom'>\n";
    echo "<input type='hidden' name='id' value='{$array['id']}' />\n";
    echo "<input type='hidden' name='user' value='{$guser}' />\n";
    echo "<input type='hidden' name='QueryType' value='Gallery' />\n";
    echo "<input class='edittablebutton' name='QueryAction' type='submit' value='{$submitButtonText}' />\n";
    if(isset($_GET['gallery']))
    {
      echo "<input class='edittablebutton' name='QueryAction' type='submit' value='Delete' onclick='return confirmSubmit(\"Delete\")' />\n";
    }
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