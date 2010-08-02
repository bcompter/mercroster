<?php
if(!defined('FD3rasG34dd'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}
require("htdocs/dbsetup.php");
$contractID=$_GET['contract'];
$contractID=stripslashes($contractID);
$contractID=mysql_real_escape_string($contractID);

require("includes/InputFields.php");
$inputFields=new InputFields;

if(isset($_GET['contract']))
{
  $title="Edit Contract";
  $deletescript=1;
}
else
{
  $title="Add Contract";
}

if(isset($_SESSION['SESS_ID']) || (trim($_SESSION['SESS_ID']) != ''))
{
  //Fetching used dates data
  $datesResult = $dbf->queryselect("SELECT * FROM dates WHERE id=1;");
  $datesArray = mysql_fetch_array($datesResult, MYSQL_NUM);
  $date=$datesArray[1];
  $startingYear=strtok($date, "-");
  $date=$datesArray[3];
  $endingYear=strtok($date, "-");

  if(isset($_GET['contract']))
  {
    $result = $dbf->queryselect("SELECT id, start, end, employer, missiontype, target, result, name FROM contracts WHERE id='$contractID';");
    //Check that we found correct log entry
    if(mysql_num_rows($result)==1)
    {
      $array = mysql_fetch_array($result, MYSQL_NUM);
      $head="<h1 class='headercenter'>Edit Contract information</h1>\n";
      $submitButtonText='Save';
      $date=$array[1];
    }
    else
    {
      $error=true;
      $errormsg="No contract found.";
    }
  }
  else
  {
    $head="<h1 class='headercenter'>New Contract</h1>\n";
    $contractID = 0;
    $submitButtonText='Add';
    $date=$datesArray[2];
  }

  if(!$error)
  {
    echo "<div id='content'>\n";
    echo $head;
    echo "<div class='genericarea'>\n";
    echo "<form action='index.php?action=contractquery' method='post'>\n";
    echo "<table border='0'>\n";

    if(isset($_GET['err']))
    {
      echo"<tr>\n";
      echo"<td colspan='8'><b>No changes was made because {$_GET['err']} was given.</b></td>\n";
      echo"</tr>\n";
    }

    echo "<tr>\n";
    echo "<td class='edittableleft'>Name:</td>\n";
    //echo "<td class='edittableright' colspan='6'><input class='edittablecommon' name='name' type='text' maxlength='100' value='$array[7]' /></td>\n";
    echo "<td class='edittableright' colspan='6'>";
    $inputFields->textinput("edittablecommon","name",100,$array[7]);
    echo "</td>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class='edittableleft'>Starting</td>\n";
    $inputFields->datebar($date, $endingYear, $startingYear, "startingyear", "startingmonth", "startingday", false);
    echo "</tr>\n";
    if(isset($_GET['contract']))
    {
      $date=$array[2];
    }
    else
    {
      $date=$datesArray[2];

    }
    echo "<tr>\n";
    echo "<td class='edittableleft'>Ending</td>\n";
    $inputFields->datebar($date, $endingYear, $startingYear, "endingyear", "endingmonth", "endingday", false);
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class='edittableleft'>Employer:</td>\n";
    echo "<td class='edittableright' colspan='6'>";
    $inputFields->textinput("edittablecommon","employer",100,$array[3]);
    echo "</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class='edittableleft'>Mission Type:</td>\n";
    echo "<td class='edittableright' colspan='6'>";
    $inputFields->textinput("edittablecommon","missiontype",100,$array[4]);
    echo "</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class='edittableleft'>Target:</td>\n";
    echo "<td class='edittableright' colspan='6'>";
    $inputFields->textinput("edittablecommon","target",100,$array[5]);
    echo "</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class='edittableleft'>Result:</td>\n";
    echo "<td class='edittableright' colspan='6'>";
    $inputFields->textinput("edittablecommon","result",100,$array[6]);
    echo "</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class='edittablebottom' colspan='7'>\n";
    echo "<input type='hidden' name='ID' value='{$contractID}' />\n";
    echo "<input type='hidden' name='QueryType' value='Contract' />\n";
    echo "<input class='edittablebutton' name='QueryAction' type='submit' value='{$submitButtonText}' />\n";
    if(isset($_GET['contract']))
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
  $errormsg="Access Denied";
}
//Error Handling
if($error)
{
  echo "<div id='content'>\n";
  echo "<div class='error'>\n";
  echo "<b><b>An error has occurred</b></b> while accessing contract.<br />\n";
  echo "No contract found or you don't have rights to access this information.<br />\n";
  echo "Please use correct links and be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg,"<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>