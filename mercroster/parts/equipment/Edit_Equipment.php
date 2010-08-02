<?php
if(!defined('Jdc56GHd46R5v'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}

require("includes/InputFields.php");
$inputFields = new InputFields;

require("htdocs/dbsetup.php");
$vehicleID=$_GET['equipment'];
$vehicleID=stripslashes($vehicleID);
$vehicleID=mysql_real_escape_string($vehicleID);

if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<='4')
{
  if(isset($_GET['equipment']))
  {
    $vehicleResult=$dbf->queryselect("SELECT * FROM equipment WHERE id='$vehicleID';");
    if(mysql_num_rows($vehicleResult)==1)
    {
      $vehicleArray=mysql_fetch_array($vehicleResult, MYSQL_BOTH);
      $vehicleType=$vehicleArray[type];
    }
    else
    {
      $error=true;
      $errormsg="Equipment information not found.";
    }
  }
  else
  {
    require("htdocs/dbsetup.php");
    $vehicleType=$_GET["type"];
    $vehicleType=stripslashes($vehicleType);
    $vehicleType=mysql_real_escape_string($vehicleType);
  }

  if(!$error)
  {
    //Validating Equipment Type
    $checkResult=$dbf->queryselect("SELECT license, maxweight, minweight, weightstep, name FROM equipmenttypes WHERE id='$vehicleType';");
    if(mysql_num_rows($checkResult)==1)
    {

      $checkArray = mysql_fetch_array($checkResult, MYSQL_NUM);

      $GKKLicenseHeader=$checkArray[0]*10000;
      $MaxWeight=$checkArray[1];
      $MinWeight=$checkArray[2];
      $WeightModulo=$checkArray[3];
      $usedIDresult = $dbf->queryselect("SELECT regnumber FROM equipment WHERE Type='$vehicleType' ORDER BY 'regnumber';");
      $i=0;
      while($usedID = mysql_fetch_array($usedIDresult, MYSQL_NUM))
      {
        $usedIDArray[$i] = $usedID[0];
        $i++;
      }

      //Findding personnel images
      $desired_extension = 'png';
      $equipmentimages = array();
      if ($handle = opendir('./images/equipmentimages/'))
      {
        while (false !== ($file = readdir($handle)))
        {
          $fileChunks = explode(".", $file);
          if ($file != "." && $file != ".." &&  $fileChunks[1] == $desired_extension)
          {
            list($width, $height) = getimagesize("images/equipmentimages/".$file);
            if($width==200 && $height==200)
            {
              array_push($equipmentimages, $file);
            }
          }
        }
        closedir($handle);
      }
      sort($equipmentimages);

      echo "<div id='content'>\n";
      if(isset($_GET['equipment']))
      {
        echo "<h1 class='headercenter'>Edit {$checkArray[4]} information</h1>\n";
        $submitButtonText='Save';
      }
      else
      {
        echo "<h1 class='headercenter'>New {$checkArray[4]}</h1>\n";
        $vehicleID=0;
        $submitButtonText='Add';
      }
      echo "<div class='genericarea'>\n";
      echo "<form action='index.php?action=equipmentquery' method='post' id='modified'>\n";
      echo "<table class='main' border='0'>\n";
      if(isset($_GET['err']))
      {
        echo"<tr>\n";
        echo"<td colspan='8'><b>No changes was made because {$_GET['err']} was given.</b></td>\n";
        echo"</tr>\n";
      }
      //Name
      echo "<tr>\n";
      echo "<td class='edittableleft'>Name:</td>\n";
      echo "<td class='edittableright' colspan='6'>";
      $inputFields->textinput("edittablecommon265","name",45,$vehicleArray[name]);
      echo "</td>\n";
      echo "</tr>\n";
      //SubType
      echo "<tr>\n";
      echo "<td class='edittableleft'>Sub type:</td>\n";
      echo "<td class='edittableright' colspan='6'>";
      $inputFields->textinput("edittablecommon265","subtype",45,$vehicleArray[subtype]);
      echo "</td>\n";
      echo "</tr>\n";
      //GKKID
      echo "<tr>\n";
      echo "<td class='edittableleft'>Register Number:</td>\n";
      echo "<td class='edittableright' colspan='6'>\n";
      echo "<select class='edittablebox' name='gkknumber'>\n";
      $counter=0;
      for($i=1;$i<1001;$i++)
      {
        if(($GKKLicenseHeader+$i)==$vehicleArray[regnumber])
        {
          $temp=$GKKLicenseHeader+$i;
          echo "<option value='{$temp}' selected='selected'>$temp</option>\n";
        }
        else
        {
          if(!in_array(($GKKLicenseHeader+$i), $usedIDArray))
          {
            $temp=$GKKLicenseHeader+$i;
            echo "<option value='$temp'>$temp</option>\n";
            $counter++;
          }
        }
        if($counter>25)
        {
          break;
        }
      }
      echo "</select>\n";
      echo "</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
      //Weight
      echo "<td class='edittableleft'>Weight:</td>\n";
      echo "<td class='edittableright' colspan='6'>\n";
      echo "<select class='edittablebox' name='weight'>\n";
      for($i=$MinWeight;$i<$MaxWeight+1;$i=$i+$WeightModulo)
      {
        if($i==$vehicleArray[weight])
        {
          echo "<option value='{$i}' selected='selected'>$i</option>\n";
        }
        else
        {
          echo "<option value='{$i}'>$i</option>\n";
        }
      }
      echo "</select>\n";
      echo "</td>\n";
      echo "</tr>\n";
      echo "<tr><td colspan='7'><hr /></td></tr>\n";
      //image
      echo "<tr>\n";
      echo "<td class='edittableleft'>Image:</td>\n";
      echo "<td colspan='6'>\n";
      $inputFields->dropboxarscript($equipmentimages, $vehicleArray[image], "image", "edittablebox", "onchange='javascript:change_image(this.value, \"equipmentimages\")'", true);
      echo "</td>\n";
      echo "<td><img id='equipmentimages' class='unittypeimage' src='./images/equipmentimages/{$checkArray[14]}' alt='{$checkArray[14]}' /></td>\n";
      echo "</tr>\n";
      //Notes
      $inputFields->textarea("edittableleft", "edittableright", 6, "Notes", "edittablecommon", "notes", $vehicleArray[notes]);
      echo "<tr><td colspan='7'><hr /></td></tr>\n";
      echo "<tr>\n";
      echo "<td colspan='2' class='edittablebottom'>\n";
      //Buttons
      echo "<input type='hidden' name='ID' value='{$vehicleID}' />\n";
      echo "<input type='hidden' name='type' value='{$vehicleType}' />\n";
      echo "<input type='hidden' name='QueryType' value='Vehicle' />\n";
      echo "<input class='edittablebutton' name='QueryAction' type='submit' value='{$submitButtonText}' />\n";
      if(isset($_GET['equipment']))
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
    else
    {
      $error=true;
      $errormsg="Equipment type information not found.";
    }
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
  echo "<b>An error has occurred</b> while accessing equipment information.<br />\n";
  echo "No equipment found or you don't have rights to access this equipment information.<br />\n";
  echo "Please use correct links and be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg,"<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>