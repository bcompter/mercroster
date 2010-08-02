<?php
if(!defined('5gJHk452Gs'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}

require("htdocs/dbsetup.php");
$logID=$_GET['log'];
$logID=stripslashes($logID);
$logID=mysql_real_escape_string($logID);

$logType=$_GET['type'];
$logType=stripslashes($logType);
$logType=mysql_real_escape_string($logType);

require("includes/InputFields.php");
$inputFields=new InputFields;

//Fetching log type information
$logTypeResult=$dbf->queryselect("SELECT * FROM logtypes WHERE id='{$logType}';");
if(mysql_num_rows($logTypeResult)==1)
{
  $logTypeArray=$dbf->resulttoarray($logTypeResult);
  $logTypeArray=$logTypeArray[0];

  if(isset($_SESSION['SESS_NAME']) && $_SESSION['SESS_TYPE']<=$logTypeArray[7] && $logTypeArray[7]!=0)
  {
    //Fetching used dates data
    $datesResult = $dbf->queryselect("SELECT * FROM dates WHERE id=1;");
    $datesArray = mysql_fetch_array($datesResult, MYSQL_NUM);
    $date=$datesArray[1];
    $startingYear=strtok($date, "-");
    $date=$datesArray[3];
    $endingYear=strtok($date, "-");

    //Fecthing Contract names
    $contractNameResult=$dbf->queryselect("SELECT id, name FROM contracts ORDER BY start DESC;");

    if(isset($_GET['log']))
    {
      $result=$dbf->queryselect("SELECT * FROM logentry WHERE id='$logID';");
      //Check that we found correct log entry
      if(mysql_num_rows($result)==1)
      {
        $array=mysql_fetch_array($result, MYSQL_NUM);
        $submitButtonText='Save';
        $startDate=$array[3];
        $endDate=$array[4];
        $logType=$array[1];
        $mode="Edit";
      }
      else
      {
        $error=true;
        $errormsg="No log found.";
      }
    }
    else
    {
      $logID=0;
      $submitButtonText='Add';
      $startDate=$datesArray[2];
      $mode="Add";
    }

    if(!$error)
    {
      echo "<div id='content'>\n";
      echo "<h1 class='headercenter'>{$mode} {$logTypeArray[1]}</h1>\n";
      echo "<div class='postouter' style='padding: 3px;'>\n";
      echo "<div class='genericarea'>\n";
      echo "<form action='index.php?action=logquery' method='post' id='modified'>\n";
      echo "<table class='main' border='0'>\n";

      if(isset($_GET['err']))
      {
        echo"<tr>\n";
        echo"<td colspan='8'><b>No changes was made because {$_GET['err']} was given.</b></td>\n";
        echo"</tr>\n";
      }

      if(isset($_GET['log']))
      {
        echo "<tr>\n";
        echo "<td colspan='7' class='edittablelefttop'><b>Posted originally by $array[7]</b><small class='commenttabletopictime'> on {$dp->getTime($array[8], $offset, $timeformat)}</small></td>\n";
        echo "</tr>\n";
      }
      //Contract
      if($logTypeArray[6]==1)
      {
        echo "<tr>\n";
        echo "<td class='edittableleft'>During:</td>\n";
        echo "<td class='edittableright' colspan='6'>\n";
        $inputFields->dropboxqu($contractNameResult, $array[11], "contract", "edittablebox", true);
        echo "</td>\n";
        echo "</tr>\n";
      }
      //Starting Date
      if($logTypeArray[2]==1)
      {
        echo "<tr>\n";
        echo "<td class='edittableleft'>Start:</td>\n";
        $inputFields->datebar($startDate, $endingYear, $startingYear, "startyear", "startmonth", "startday", false);
        echo "</tr>\n";
      }
      //Ending Date
      if($logTypeArray[3]==1)
      {
        echo "<tr>\n";
        echo "<td class='edittableleft'>End:</td>\n";
        $inputFields->datebar($endDate, $endingYear, $startingYear, "endyear", "endmonth", "endday", true);
        echo "</tr>\n";
      }
      //Place
      if($logTypeArray[4]==1)
      {
        echo "<tr>\n";
        echo "<td class='edittableleft'>Place:</td>\n";
        echo "<td class='edittableright' colspan='6'>";
        $inputFields->textinput("edittablecommon","place",60,$array[5]);
        echo "</td>\n";
        echo "</tr>\n";
      }
      //topic
      echo "<tr>\n";
      echo "<td class='edittableleft'>Topic:</td>\n";
      echo "<td class='edittableright' colspan='6'>";
      $inputFields->textinput("edittablecommon","topic",60,$array[12]);
      echo "</td>\n";
      echo "</tr>\n";
      echo "<tr><td colspan='7'><hr /></td></tr>\n";
      //Text
      if($logTypeArray[5]==1)
      {
        $textarea=str_replace("&", "&amp;", $array[6]);
        $inputFields->textarea("edittableleft", "edittableright", 6, "Text", "edittablecommon", "text", $textarea);
      }
      echo "<tr><td colspan='7'><hr /></td></tr>\n";
      //Hidden
      echo "<tr>\n";
      echo "<td class='edittablebottom' colspan='7'>\n";
      echo "<input type='hidden' name='ID' value='{$logID}' />\n";
      echo "<input type='hidden' name='logtype' value='{$logType}' />\n";
      echo "<input type='hidden' name='QueryType' value='Log' />\n";
      echo "<input type='hidden' name='readpermission' value='{$logTypeArray[8]}' />\n";
      echo "<input class='edittablebutton' name='QueryAction' type='submit' value='{$submitButtonText}' />\n";
      if(isset($_GET['log']))
      {
        echo "<input class='edittablebutton' name='QueryAction' type='submit' value='Delete' onclick='return confirmSubmit(\"Delete\")' />\n";
      }
      echo "</td>\n";
      echo "</tr>\n";
      echo "</table>\n";
      echo "</form>\n";
      echo "</div>\n";
      echo "</div>\n";
      echo "</div>\n";
    }
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
  $errormsg="No log type found.";
}
//Error Handling
if($error)
{
  echo "<div id='content'>\n";
  echo "<div class='error'>\n";
  echo "<b>An error has occurred</b> while accessing log entry edit.<br />\n";
  echo "No log entry found or you don't have rights to access this log.<br />\n";
  echo "Please use correct links and be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg."<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>