<?php
if(!defined('gt5fhsb64'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}

require("includes/BBFunctions.php");
$bbf=new BBFunctions;

function strip($data)
{
  require("htdocs/dbsetup.php");
  $data = stripslashes($data);
  $data = mysql_real_escape_string($data);
  $data = strip_tags($data);
  return $data;
}

if(isset($_SESSION['SESS_TYPE']))
{
  $readpermission=strip($_SESSION['SESS_TYPE']);
}
else
{
  $readpermission=6;
}

$logID=strip($_GET['log']);

$accessResult=$dbf->queryselect("SELECT l.readpermission, l.writepermission FROM logentry r LEFT JOIN logtypes l ON r.logtype=l.id WHERE r.id='{$logID}';");
if(mysql_num_rows($accessResult)==1)
{
  $accessArray=mysql_fetch_array($accessResult, MYSQL_NUM);

  if($readpermission<=$accessArray[0])
  {
    $logResult=$dbf->queryselect("SELECT r.id, r.logtype, r.start, r.end, r.place, r.text, r.op, r.opdate, r.le, r.ledate, c.name, r.topic, r.opid FROM logentry r LEFT JOIN contracts c ON r.contract=c.ID WHERE r.id='{$logID}';");
    $logArray=mysql_fetch_array($logResult, MYSQL_NUM) or die("Error retrieving log table.");

    //Get date information
    $date=$dp->datestring($logArray[2]);
    if($logArray[3]!="")
    {
      $date=$date . " - " . $dp->datestring($logArray[3]);
    }
    //get location information
    if($logArray[4]!="")
    {
      $at = "at <b>{$logArray[4]}</b>";
    }
    //get contract information
    if($logArray[10]!="")
    {
      $during = "during <b>{$logArray[10]}</b>";
    }

    $originalTime=$dp->getTime($logArray[7], $offset, $timeformat);
    $editTime=$dp->getTime($logArray[9], $offset, $timeformat);

    $text=nl2br($logArray[5]);
    $text=str_replace("&", "&amp;", $text);
    $text=$bbf->addTags($text);

    echo "<div id='content'>\n";

    echo "<div class='post'>\n";
    echo "<div class='postheader'>\n";

    echo "<span class='postheader'>{$logArray[11]}</span><br />\n";
    echo "<b>$date</b> {$at} {$during}\n";
    echo "</div>\n";
    echo "<div class='posttext'>\n";
    echo "{$text}\n";
    if(isset($logArray[8]))
    {
      echo "<br />\n";
      echo "<small>Last edit by {$logArray[8]} on {$editTime}</small>\n";
    }
    echo "</div>\n";
    //footer for original post
    echo "<div class='postfooter'>\n";
    echo "Posted by {$logArray[6]} on {$originalTime}\n";
    echo "</div>\n";
    echo "</div>\n";
    echo "</div>\n";

  }
  else
  {
    $error=true;
    $errormsg="Access denied.";
  }
}
else
{
  $error=true;
  $errormsg="Log not found";
}
//Error Handling
if($error)
{
  echo "<div id='content'>\n";
  echo "<div class='error'>\n";
  echo "<b>An error has occurred</b> while accessing log entry.<br />\n";
  echo "No log entry found or you don't have rights to access this log.<br />\n";
  echo "Please use correct links and be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg,"<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>