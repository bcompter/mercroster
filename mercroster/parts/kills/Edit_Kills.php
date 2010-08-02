<?php
if(!defined('45Fsc35G53'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}
require("htdocs/dbsetup.php");
$killID=$_GET['kill'];
$killID=stripslashes($killID);
$killID=mysql_real_escape_string($killID);

require("includes/InputFields.php");
$inputFields=new InputFields;

if(isset($_GET['kill']))
{
  $title="Edit Kill";
  $deletescript=1;
}
else
{
  $title="Add Kill";
}


if(isset($_SESSION['SESS_ID'])  && $_SESSION['SESS_TYPE']<='3')
{
  //Fetching used dates data
  $datesResult = $dbf->queryselect("SELECT * FROM dates WHERE id=1;");
  if(!$datesResult)
  {
    die('Could not get that dates data: ' . mysql_error());
  }
  $datesArray = mysql_fetch_array($datesResult, MYSQL_NUM);
  $date=$datesArray[1];
  $startingYear=strtok($date, "-");
  $date=$datesArray[3];
  $endingYear=strtok($date, "-");

  //Fetching cres data
  $personelResult = $dbf->queryselect("SELECT id, lname, fname FROM crew ORDER BY fname, lname;");
  if(!$personelResult)
  {
    die('Could not get that mission Kills data: ' . mysql_error());
  }

  if(isset($_GET['kill']))
  {
    $killResult=$dbf->queryselect("SELECT parent, type, weight, killdate, equipment, eweight FROM kills WHERE id='$killID';");
    if(mysql_num_rows($killResult)==1)
    {
      $array=mysql_fetch_array($killResult, MYSQL_NUM);
      $head="<h1 class='headercenter'>Edit Kill</h1>\n";
      $submitButtonText='Save';
      $date=$array[3];
    }
    else
    {
      $error=true;
      $errormsg="No kill found.";
    }
  }
  else
  {
    $head="<h1 class='headercenter'>New Kill</h1>\n";
    $killID = 0;
    $submitButtonText='Add';
    $date=$datesArray[2];
  }

  if(!$error)
  {

    echo "<div id='content'>\n";
    echo $head;
    echo "<div class='genericarea'>\n";
    echo "<form action='index.php?action=killsquery' method='post'>\n";
    echo "<table border='0'>\n";
    if(isset($_GET['err']))
    {
      echo"<tr>\n";
      echo"<td colspan='8'><b>No changes was made because {$_GET['err']} was given.</b></td>\n";
      echo"</tr>\n";
    }
    echo "<tr>\n";
    //Awarded to
    echo "<td class='edittableleft'>Awarded to:</td>\n";
    echo "<td class='edittableright' colspan='6'>\n";
    echo "<select class='edittablebox' name='parent'>\n";
    while($personelArray = mysql_fetch_array($personelResult, MYSQL_NUM))
    {
      if($personelArray[0]==$array[0])
      {
        echo "<option value='{$personelArray[0]}' selected='selected'>{$personelArray[2]} {$personelArray[1]}</option>\n";
      }
      else
      {
        echo "<option value='{$personelArray[0]}'>{$personelArray[2]} {$personelArray[1]}</option>\n";
      }
    }
    echo "</select>\n";
    echo "</td>\n";
    echo "</tr>\n";
    //Kill Type
    echo "<tr>\n";
    echo "<td class='edittableleft'>Kill Type:</td>\n";
    echo "<td class='edittableright' colspan='6'>";
    $inputFields->textinput("edittablecommon265","type",60,$array[1]);
    echo "</td>\n";
    echo "</tr>\n";
    //Date
    echo "<tr>\n";
    echo "<td class='edittableleft'>Date:</td>\n";
    $inputFields->datebar($date, $endingYear, $startingYear, "year", "month", "day", false);
    echo "</tr>\n";
    //Equipment Type
    echo "<tr>\n";
    echo "<td class='edittableleft'>Killed with:</td>\n";
    echo "<td class='edittableright' colspan='6'>";
    $inputFields->textinput("edittablecommon265","equipment",45,$array[4]);
    echo "</td>\n";
    echo "</tr>\n";
    //Hidden
    echo "<tr>\n";
    echo "<td class='edittablebottom' colspan='6' >\n";
    echo "<input type='hidden' name='ID' value='{$killID}' />\n";
    echo "<input type='hidden' name='QueryType' value='Kill' />\n";
    echo "<input class='edittablebutton' name='QueryAction' type='submit' value='{$submitButtonText}' />\n";
    if(isset($_GET['kill']))
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
  echo "<b>An error has occurred</b> while accessing kill information edit.<br />\n";
  echo "No kill information found or you don't have rights to access this information.<br />\n";
  echo "Please use correct links and be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg."<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>