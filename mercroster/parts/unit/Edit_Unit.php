<?php
if(!defined('hd7jkV4dg78'))
{
  header('HTTP/1.0 404 not found');
  exit;
}
/**
 * function used to fetch array that contains $value in $column
 * @param <array> $array
 * @param <int> $column
 * @param <int> $value
 */
function inarray($array, $column, $value)
{
  $returnArray = array();
  for($i=0; $i<sizeof($array); $i++)
  {
    $tempArray=$array[$i];
    if($tempArray[$column]==$value)
    {
      $returnArray[sizeof($returnArray)]=$tempArray;
    }
  }
  return $returnArray;
}

/**
 * Function used to get first fee prefpos slot
 * @param <class> $dbf
 * @param <int> $id
 */
function getfirstfreeslot($dbf, $id)
{
  $result = $dbf->queryselect("SELECT id FROM unit WHERE Parent='$id';");
  $count=mysql_num_rows($result);
  return $count;
}

require("includes/InputFields.php");
$inputFields = new InputFields;

require("htdocs/dbsetup.php");
$unit=$_GET["unit"];
$unit=stripslashes($unit);
$unit=mysql_real_escape_string($unit);
if($unit!=0)
{
  $AddChange="Save";
  $title="Edit ";
  $deletescript=1;
}
else
{
  $AddChange="Add";
  $title="Add new unit";
}

if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<='3')
{
  $highestchildunitlevel=0;
  $parentunitlevel=10;

  if($unit!=0)
  {
    //Validating Unit Type if editing existing unit
    $checkResult = $dbf->queryselect("SELECT id, type, name, limage, rimage, parent, level, prefpos, text FROM unit WHERE id='$unit';");
    if(mysql_num_rows($checkResult)==1)
    {

      $checkArray = mysql_fetch_array($checkResult, MYSQL_NUM);

      $parentarray = $dbf->resulttoarraysingle($dbf->queryselect("SELECT id, level FROM unit WHERE id='$checkArray[6]';"));
      if($parentarray[1]>0)
      {
        $parentunitlevel=$parentarray[1];
      }
      $parentResult = $dbf->queryselect("SELECT id, level FROM unit WHERE id='$checkArray[5]';");
      $parentCount=mysql_num_rows($parentResult);
      if($parentCount==1)
      {
        $parentarray = mysql_fetch_array($parentResult, MYSQL_NUM);
        if($parentarray[1]>0)
        {
          $parentunitlevel=$parentarray[1];
        }
      }

      //Fething sub unit data from DB to later use
      $childorganizationsResult = $dbf->resulttoarray($dbf->queryselect("SELECT * FROM unit WHERE parent='$unit';"));
      $organizationCheckCount=sizeof($childorganizationsResult);
      //If unit contains units, setting free unit array
      if ($organizationCheckCount!=0 || ($organizationCheckCount==0 && $crewCheckCount==0))
      {
        $type=1;
        $freeUnitArray = $dbf->resulttoarray($dbf->queryselect("SELECT * FROM unit WHERE parent='0';"));
        for($i=0; $i<sizeof($childorganizationsResult); $i++)
        {
          $temp=$childorganizationsResult[$i];
          if($temp[5]>$highestchildunitlevel)
          {
            $highestchildunitlevel=$temp[5];
          }
        }
      }

      //Checking if this unit contains crews
      $crewResult = $dbf->queryselect("SELECT c.id, r.rankname, c.lname, c.fname FROM crew c LEFT JOIN ranks r ON c.rank=r.number WHERE parent='$unit';");
      $crewCheckCount=mysql_num_rows($crewResult);

      //If unit contains personels, Feching needed personel data
      if ($crewCheckCount!=0 || ($organizationCheckCount==0 && $crewCheckCount==0))
      {
        $type=2;
        $freeCrewResult = $dbf->queryselect("SELECT c.ID, r.rankname, c.LName, c.FName FROM crew c LEFT JOIN ranks r ON c.Rank=r.number WHERE Parent=0 AND Status='Active';");
        $i=0;
        while($array = mysql_fetch_array($freeCrewResult, MYSQL_NUM))
        {
          $freeCrewArray[$i]=$array;
          $i++;
        }
      }

      if ($organizationCheckCount==0 && $crewCheckCount==0)
      {
        $type=0;
      }
    }
    else
    {
      $error=true;
      $errormsg="No unit found.";

    }
  }

  if(!$error)
  {
    //Fecthing UnitTypes data
    $unitTypesResult = $dbf->queryselect("SELECT id, name FROM unittypes;");
    //Fething unit level data from DB to later use
    $unitlevelresult = $dbf->queryselect("SELECT * FROM unitlevel ORDER BY prefpos ASC;");

    //Findding and organizing units tactical symbols
    $leftimages = array();
    if ($handle = opendir('./images/unittype/'))
    {
      while (false !== ($file = readdir($handle)))
      {
        $fileChunks = explode(".", $file);
        if ($file != "." && $file != ".." &&  preg_match('/png|jpg|gif/', $fileChunks[1]))
        {
          list($width, $height) = getimagesize("images/unittype/".$file);
            array_push($leftimages, $file);
        }
      }
      closedir($handle);
    }
    sort($leftimages);

    //Findding and organizing units tactical symbols
    $unitimages = array();
    if ($handle = opendir('./images/unitimages/'))
    {
      while (false !== ($file = readdir($handle)))
      {
        $fileChunks = explode(".", $file);
        if ($file != "." && $file != ".." &&  preg_match('/png|jpg|gif/', $fileChunks[1]))
        {
            array_push($unitimages, $file);
        }
      }
      closedir($handle);
    }
    sort($unitimages);
    echo "<div id='content'>\n";
    echo "<table border='0' class='edittable'>\n";
    echo "<tr>\n";
    echo "<th colspan='3'>$title $checkArray[2]</th>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td>\n";

    echo "<form action='index.php?action=unitquery' method='post' id='modified'>\n";
    echo "<table border='0'>\n";
    //Name
    echo "<tr>\n";
    echo "<td class='edittableleft'>Unit's Name:</td>\n";
    echo "<td>";
    $inputFields->textinput("edittablecommon265","name",45,$checkArray[2]);
    echo "</td>\n";
    echo "</tr>\n";
    //Type
    echo "<tr>\n";
    echo "<td class='edittableleft'>Unit's Type:</td>\n";
    echo "<td>\n";
    $inputFields->dropboxqu($unitTypesResult, $checkArray[1], "type", "edittablebox", false);
    echo "</td>\n";
    echo "</tr>\n";
    //Unit Level. This is so specialized dropbox that we don't want to use InputFields to make it.
    echo "<tr>\n";
    echo "<td class='edittableleft'>Unit Level</td>\n";
    echo "<td>\n";
    echo "<select class='edittablebox' name='level' onchange='javascript:change_unitlevel_image(this.value)'>\n";
    $level=0;
    while($array = mysql_fetch_array($unitlevelresult, MYSQL_NUM))
    {
      if($checkArray[6]==$array[0])
      {
        $level=$array[3];
        echo "<option value='{$array[0]}' selected='selected'>{$array[1]}</option>\n";
      }
      else
      {
        echo "<option value='{$array[0]}'>{$array[1]}</option>\n";
      }
    }
    echo "</select>\n";
    echo "</td>\n";
    echo "<td><img id='unitlevelimage' class='unitlevelimage' src='./images/unitlevel/{$level}' alt='{$level}' /></td>\n";
    echo "</tr>\n";
    //Unit type image
    echo "<tr>\n";
    echo "<td class='edittableleft'>Left Image:</td>\n";
    echo "<td>\n";
    $inputFields->dropboxarscript($leftimages, $checkArray[3], "limage", "edittablebox", "onchange='javascript:change_image(this.value, \"unittype\")'", true);
    echo "</td>\n";
    echo "<td><img id='unittype' class='unittypeimage' src='./images/unittype/{$checkArray[3]}' alt='{$checkArray[3]}' /></td>\n";
    echo "</tr>\n";
    //Unit's logo
    echo "<tr>\n";
    echo "<td class='edittableleft'>Unit Image:</td>\n";
    echo "<td>\n";
    $inputFields->dropboxarscript($unitimages, $checkArray[4], "rimage", "edittablebox", "onchange='javascript:change_image(this.value, \"unitimages\")'", true);
    echo "</td>\n";
    echo "<td><img id='unitimages' class='unittypeimage' src='./images/unitimages/{$checkArray[4]}' alt='{$checkArray[4]}' /></td>\n";
    echo "</tr>\n";
    //Text
    $inputFields->textarea("edittableleft", "edittableright", 2, "Text", "edittablecommon", "text", $checkArray[8]);
    echo "<tr><td colspan='3'><hr /></td></tr>\n";
    //Submit Button
    echo "<tr>\n";
    echo "<td colspan='3'>\n";
    echo "<input type='hidden' name='ID' value='{$unit}' />\n";
    echo "<input type='hidden' name='parent' value='$checkArray[5]' />\n";
    echo "<input type='hidden' name='prefpos' value='$checkArray[7]' />\n";
    echo "<input type='hidden' name='QueryType' value='Organization' />\n";
    echo "<input type='hidden' name='toplvlorg' value='$unit' />\n";
    //Add or Change
    echo "<input class='edittablebutton' name='QueryAction' type='submit' value='{$AddChange}' />\n";
    //Delete
    if($unit>1)
    {
      echo "<input class='edittablebutton' name='QueryAction' type='submit' value='Delete' onclick='return confirmSubmit(\"Delete\")' />\n";
    }
    //Assing as Top Level Organization Button
    if($checkArray[5]==0 && $unit!=0)
    {
      $firstFreeSlot=getfirstFreeSlot($dbf, 1)+1;
      echo "<input type='hidden' name='prefpos' value='$firstFreeSlot' />\n";
      echo "<input class='edittablebutton' name='QueryAction' type='submit' value='Assign' />\n";
    }
    //Detach as Top Level Organization Button
    if($checkArray[5]==1 && $unit!=0)
    {
      echo "<input type='hidden' name='prefpos' value='$checkArray[7]' />\n";
      echo "<input class='edittablebutton' name='QueryAction' type='submit' value='Detach' />\n";
    }

    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "</form>\n";

    echo "</td>\n";
    echo "</tr>\n";
    echo "<tr><td colspan='3'><hr /></td></tr>\n";

    if($unit!=0)
    {
      if ($type==1)
      {
        echo "<tr>\n";
        echo "<td>\n";
        echo "<table border='0'>\n";
         
        for($i=0; $i<sizeof($childorganizationsResult); $i++)
        {
          $array=$childorganizationsResult[$i];
          echo "<tr>\n";
          echo "<td>Sub unit:</td>\n";
          echo "<td>$array[2]</td>\n";
          echo "<td>\n";
          echo "<form action='index.php?action=unitquery' method='post'>\n";
          echo "<div>\n";
          echo "<input type='hidden' name='ID' value='{$array[0]}' />\n";
          echo "<input type='hidden' name='QueryType' value='Organization' />\n";
          echo "<input type='hidden' name='toplvlorg' value='$unit' />\n";
          echo "<input type='hidden' name='type' value='Unit' />\n";
          echo "<input type='hidden' name='parent' value='$unit' />\n";
          echo "<input type='hidden' name='prefpos' value='{$array[4]}' />\n";
          echo "<input type='hidden' name='QueryAction' value='Remove Unit' />\n";
          echo "<input class='edittablebutton' type='submit' value='Remove Unit' onclick='return confirmSubmit(\"Remove\")' />\n";
          echo "</div>\n";
          echo "</form>\n";
          echo "</td>\n";
          echo "</tr>\n";
        }
        echo "</table>\n";
        echo "</td>\n";
        echo "</tr>\n";
      }
      if ($type==2)
      {
        echo "<tr>\n";
        echo "<td>\n";
        echo "<table border='0'>\n";

        while($array =  mysql_fetch_array($crewResult, MYSQL_NUM))
        {
          echo "<tr>\n";
          echo "<td>Personnel:</td>\n";
          echo "<td>{$array[1]} {$array[3]} {$array[2]}</td>\n";
          echo "<td>\n";
          echo "<form action='index.php?action=unitquery' method='post'>\n";
          echo "<div>\n";
          echo "<input type='hidden' name='ID' value='{$array[0]}' />\n";
          echo "<input type='hidden' name='type' value='Crew' />\n";
          echo "<input type='hidden' name='parent' value='$unit' />\n";
          echo "<input type='hidden' name='QueryType' value='Organization' />\n";
          echo "<input type='hidden' name='toplvlorg' value='$unit' />\n";
          echo "<input type='hidden' name='QueryAction' value='Remove Unit' />\n";
          echo "<input class='edittablebutton' type='submit' value='Remove' onclick='return confirmSubmit(\"Remove\")' />\n";
          echo "</div>\n";
          echo "</form>\n";
          echo "</td>\n";
          echo "</tr>\n";
        }
        echo "</table>\n";
        echo "</td>\n";
        echo "</tr>\n";
      }

      if($type==0 || $type==1)
      {
        $firstFreeSlot=getfirstFreeSlot($dbf, $unit)+1;
        echo "<tr>\n";
        echo "<td>\n";

        echo "<form action='index.php?action=unitquery' method='post'>\n";
        echo "<table border='0'>\n";
        echo "<tr>\n";
        echo "<td>Add sub unit:</td>\n";
        echo "<td>\n";
        echo "<select class='edittablebox' name='ID'>\n";
        $counter=0;
        for($i=0; $i<sizeof($freeUnitArray); $i++)
        {
          $array=$freeUnitArray[$i];
          if($array!=null)
          {
            if($checkArray[6]>$array[5])
            {
              echo "<option value='{$array[0]}'>$array[2]</option>\n";
              $counter++;
            }
          }
        }
        if($counter==0)
        {
          echo "<option value=''>No free units</option>\n";
        }

        echo "</select>\n";
        echo "</td>\n";
        echo "<td>\n";
        echo "<input type='hidden' name='QueryType' value='Organization' />\n";
        echo "<input type='hidden' name='toplvlorg' value='$unit' />\n";
        echo "<input type='hidden' name='parent' value='$unit' />\n";
        echo "<input type='hidden' name='type' value='Unit' />\n";
        echo "<input type='hidden' name='prefpos' value='{$firstFreeSlot}' />\n";
        echo "<input type='hidden' name='QueryAction' value='Add Unit' />\n";
        if($counter==0)
        {
          echo "<input class='edittablebutton' type='submit' value='Add' disabled='disabled'/>\n";
        }
        else
        {
          echo "<input class='edittablebutton' type='submit' value='Add' />\n";
        }
        echo "</td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        echo "</form>\n";
         
        echo "</td>\n";
        echo "</tr>\n";
      }
      if($type==0 || $type==2)
      {
        echo "<tr>\n";
        echo "<td>\n";

        echo "<form action='index.php?action=unitquery' method='post'>\n";
        echo "<table border='0'>\n";
        echo "<tr>\n";
        echo "<td>Add Personnel:</td>\n";
        echo "<td>\n";
        echo "<select class='edittablebox' name='ID'>\n";
        for($i=0; $i<sizeof($freeCrewArray); $i++)
        {
          $array=$freeCrewArray[$i];
          echo "<option value='{$array[0]}'>$array[1] $array[3] $array[2]</option>\n";
        }
        if(sizeof($freeCrewArray)==0)
        {
          echo "<option>No fee units</option>\n";
        }
        echo "</select>\n";
        echo "</td>\n";
        echo "<td>\n";
        echo "<input type='hidden' name='QueryType' value='Organization' />\n";
        echo "<input type='hidden' name='toplvlorg' value='$unit' />\n";
        echo "<input type='hidden' name='parent' value='$unit' />\n";
        echo "<input type='hidden' name='type' value='Crew' />\n";
        echo "<input type='hidden' name='QueryAction' value='Add Unit' />\n";
        if(sizeof($freeCrewArray)==0)
        {
          echo "<input class='edittablebutton' type='submit' value='Add' disabled='disabled' />\n";
        }
        else
        {
          echo "<input class='edittablebutton' type='submit' value='Add' />\n";
        }
        echo "</td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        echo "</form>\n";
         
        echo "</td>\n";
        echo "</tr>\n";
      }
    }
    echo "</table>\n";
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
  echo "<b>An error has occurred</b> while accessing unit.<br />\n";
  echo "No unit entry found or you don't have rights to access this unit.<br />\n";
  echo "Please use correct links and be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg,"<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>