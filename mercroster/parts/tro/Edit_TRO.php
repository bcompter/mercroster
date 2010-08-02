<?php
if(!defined('h4yt6f9eDtu'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}

require("includes/InputFields.php");
$inputFields=new InputFields;

require("htdocs/dbsetup.php");
$troID=$_GET['tro'];
$troID=stripslashes($troID);
$troID=mysql_real_escape_string($troID);



if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<'4')
{
  if(isset($_GET['tro']))
  {
    $troResult=$dbf->queryselect("SELECT * FROM technicalreadouts WHERE id='$troID';");
    if(mysql_num_rows($troResult)==1)
    {
      $troArray=mysql_fetch_array($troResult, MYSQL_NUM);
      $troType=$troArray[2];
    }
    else
    {
      $error=true;
      $errormsg="No TRO found.";
    }
  }
  else
  {
    require("htdocs/dbsetup.php");
    $troType=$_GET["type"];
    $troType = stripslashes($troType);
    $troType = mysql_real_escape_string($troType);
  }

  if(!$error)
  {
    //Validating Equipment Type
    $checkResult=$dbf->queryselect("SELECT maxweight, minweight, weightstep, name, weightscale FROM equipmenttypes WHERE id='$troType';");
    if(mysql_num_rows($checkResult)==1)
    {
      echo "<div id='content'>\n";
      $checkArray = mysql_fetch_array($checkResult, MYSQL_NUM);
      $MaxWeight=$checkArray[0];
      $MinWeight=$checkArray[1];
      $WeightModulo=$checkArray[2];
      if(isset($_GET['tro']))
      {
        echo "<h1 class='headercenter'>Edit {$checkArray[3]} TRO</h1>\n";
        $submitButtonText='Save';
      }
      else
      {
        echo "<h1 class='headercenter'>New {$checkArray[3]} TRO</h1>\n";
        $troID = 0;
        $submitButtonText='Add';
      }

      echo "<div class='genericarea'>\n";
      echo "<form action='index.php?action=troquery' method='post'>\n";
      echo "<table class='main' border='0'>\n";
      if(isset($_GET['err']))
      {
        echo"<tr>\n";
        echo"<td colspan='8'><b>No changes was made because {$_GET['err']} was given.</b></td>\n";
        echo"</tr>\n";
      }
      //Name
      echo "<tr>\n";
      echo "<td class='edittableleft'><b>Name:</b></td>\n";
      //echo "<td class='edittableright' colspan='6'><input class='edittablecommon' name='name' type='text' maxlength='45' value='$troArray[1]' /></td>\n";
      echo "<td class='edittableright' colspan='6'>\n";
      $inputFields->textinput("edittablecommon","name",45,$troArray[1]);
      echo "</td>\n";
      echo "</tr>\n";
      //Weight
      echo "<tr>\n";
      echo "<td class='edittableleft'><b>Weight:</b></td>\n";
      echo "<td class='edittableright' colspan='6'>\n";
      echo "<select class='edittablecommonbox' name='weight'>\n";
      for($i=$MinWeight;$i<$MaxWeight+1;$i=$i+$WeightModulo)
      {
        if($i==$troArray[3])
        {
          echo "<option value='{$i}' selected='selected'>$i</option>\n";
        }
        else
        {
          echo "<option value='{$i}'>$i</option>\n";
        }
      }
      echo "</select>\n";
      echo " $checkArray[4]s\n";
      echo "</td>\n";
      echo "</tr>\n";
      //Notes
      echo "<tr>\n";
      echo "<td class='edittableleft'><b>Notes:</b></td>\n";
      echo "<td class='edittableright' colspan='6'><textarea cols='50' rows='6' class='edittablecommon' name='notes'>{$troArray[4]}</textarea></td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td colspan='2' class='edittablebottom'>\n";
      //Buttons
      echo "<input type='hidden' name='ID' value='{$troID}' />\n";
      echo "<input type='hidden' name='type' value='{$troType}' />\n";
      echo "<input type='hidden' name='QueryType' value='TRO' />\n";
      echo "<input class='edittablebutton' name='QueryAction' type='submit' value='{$submitButtonText}' />\n";
      if(isset($_GET['tro']))
      {
        echo "<input class='edittablebutton' name='QueryAction' type='submit' value='Delete' onclick='return confirmSubmit(\"Delete\")' />\n";
      }
      echo "</td>\n";
      echo "</tr>\n";
      echo "</table>\n";
      echo "</form>\n";
      echo "</div>";
      echo "</div>";
    }
    else
    {
      $error=true;
      $errormsg="TRO type not found";
    }
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
  echo "<b>An error has occurred</b> while accessing TRO.<br />\n";
  echo "No TRO entry found or you don't have rights to access this TRO.<br />\n";
  echo "Please use correct links and be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg,"<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>