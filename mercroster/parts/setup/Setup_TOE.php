<?php
if(!defined('hr2sDs257S8'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}

function strip($data)
{
  require("htdocs/dbsetup.php");
  $data=stripslashes($data);
  $data=mysql_real_escape_string($data);
  $data=strip_tags($data);
  return $data;
}

if (!isset ($_SESSION['SESS_NAME']) || $_SESSION['SESS_TYPE'] > '4')
{
  $error=true;
  $errormsg="Access denied.";
}
else
{
  require("htdocs/dbsetup.php");
  $page = $_GET['page'];

  require("includes/InputFields.php");
  $inputFields = new InputFields;

  echo "<div id='content'>\n";
  echo "<div id='setupheader'>\n";
  echo "<ul>\n";
  switch ($page)
  {
    case "2":
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=1'>Equipment types</a></li>\n";
      echo "<li id='selected'><a class='generictableedit' href='index.php?action=toe&amp;page=2'>Skills & Traits</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=3'>Personnel types</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=4'>Unit types</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=5'>Unit levels</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=6'>Personnel ranks</a></li>\n";
      break;
    case "3":
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=1'>Equipment types</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=2'>Skills & Traits</a></li>\n";
      echo "<li id='selected'><a class='generictableedit' href='index.php?action=toe&amp;page=3'>Personnel types</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=4'>Unit types</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=5'>Unit levels</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=6'>Personnel ranks</a></li>\n";
      break;
    case "4":
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=1'>Equipment types</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=2'>Skills & Traits</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=3'>Personnel types</a></li>\n";
      echo "<li id='selected'><a class='generictableedit' href='index.php?action=toe&amp;page=4'>Unit types</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=5'>Unit levels</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=6'>Personnel ranks</a></li>\n";
      break;
    case "5":
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=1'>Equipment types</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=2'>Skills & Traits</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=3'>Personnel types</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=4'>Unit types</a></li>\n";
      echo "<li id='selected'><a class='generictableedit' href='index.php?action=toe&amp;page=5'>Unit levels</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=6'>Personnel ranks</a></li>\n";
      break;
    case "6":
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=1'>Equipment types</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=2'>Skills & Traits</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=3'>Personnel types</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=4'>Unit types</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=5'>Unit levels</a></li>\n";
      echo "<li id='selected'><a class='generictableedit' href='index.php?action=toe&amp;page=6'>Personnel ranks</a></li>\n";
      break;
    default:
      echo "<li id='selected'><a class='generictableedit' href='index.php?action=toe&amp;page=1'>Equipment types</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=2'>Skills & Traits</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=3'>Personnel types</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=4'>Unit types</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=5'>Unit levels</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=toe&amp;page=6'>Personnel ranks</a></li>\n";
      break;
  }
  echo "</ul>\n";
  echo "</div>\n";

  switch ($page)
  {
    case "2":
      //Fetching Skill data
      $skillTypeResult=$dbf->queryselect("SELECT id, name FROM skilltypes ORDER BY name ASC;");

      $usedSkillTypeResult=$dbf->queryselect("SELECT DISTINCT skill FROM skills;");
      $usedSkillTypeArray=$dbf->resulttoarraysingle($usedSkillTypeResult);

      echo"<div class='typecontainer' style='overflow: auto;'>\n";
      echo"<div id='typelist' class='typelist'>\n";
      echo "<ul>\n";
      if(!isset($_GET['sub']))
      {
        echo "<li class='selectedtype'><a class='selectedtype' href='index.php?action=toe&amp;page=2'>New Skill</a></li>\n";
      }
      else
      {
        echo "<li><a class='notselectedtype' href='index.php?action=toe&amp;page=2'>New skill</a></li>\n";
      }
      while ($skillArray=mysql_fetch_array($skillTypeResult, MYSQL_ASSOC))
      {
        if($_GET['sub']==$skillArray[id])
        {
          echo "<li class='selectedtype'><a class='selectedtype' href='index.php?action=toe&amp;page=2&amp;sub={$skillArray[id]}'>{$skillArray[name]}</a></li>\n";
        }
        else
        {
          echo "<li><a class='notselectedtype' href='index.php?action=toe&amp;page=2&amp;sub={$skillArray[id]}'>{$skillArray[name]}</a></li>\n";
        }
      }
      echo "</ul>\n";
      echo"</div>\n";

      if(isset($_GET['sub']))
      {
        $sub=strip($_GET['sub']);
        $skillTypesResult=$dbf->queryselect("SELECT * FROM skilltypes WHERE ID='{$sub}';");
        $skillTypesArray=mysql_fetch_array($skillTypesResult, MYSQL_ASSOC);

        echo"<div id='typeeditarea' class='typeeditarea'>\n";

        if(isset($_GET['err']))
        {
          echo"<tr>\n";
          echo"<td colspan='8'><b>No changes was made because {$_GET['err']} was given.</b></td>\n";
          echo"</tr>\n";
        }

        echo"<form action='index.php?action=setupquery' method='post'>\n";
        echo"<table border='0'>\n";
        echo"<tr>\n";
        echo"<th class='toelong'>Name</th>\n";
        echo"<td><input class='toelong' name='name' type='text' maxlength='60' value='{$skillTypesArray[name]}' /></td>\n";
        echo"</tr>\n";
        echo"<tr>\n";
        echo"<th class='toelong'>Short name</th>\n";
        echo"<td><input class='toelong' name='shortname' type='text' maxlength='60' value='{$skillTypesArray[shortname]}' /></td>\n";
        echo"</tr>\n";
        echo"<tr>\n";
        echo "<td colspan='2'><input type='hidden' name='ID' value='{$skillTypesArray[id]}' />\n";
        echo "<input type='hidden' name='QueryType' value='SkillType' />\n";
        echo "<input class='toebutton' name='QueryAction' type='submit' value='Change' />\n";
        //Delete not used skilltype
        if (!in_array($skillTypesArray[id], $usedSkillTypeArray))
        {
          echo "<input class='toebutton' name='QueryAction' type='submit' value='Delete' onclick='return confirmSubmit(\"Delete\")' />\n";
        }
        else
        {
          echo "<input class='toebutton' name='QueryAction' type='submit' value='Delete' disabled='disabled' />\n";
        }
        echo "</td>\n";
        echo"</tr>\n";
        echo"</table>\n";
        echo"</form>\n";
        echo"</div>\n";
      }
      else
      {
        echo"<div id='typeeditarea' class='typeeditarea'>\n";
        echo "<form action='index.php?action=setupquery' method='post'>\n";
        echo "<table border='0'>\n";
        echo "<tr>\n";
        echo"<th class='toelong'>Name</th>\n";
        echo"<td><input class='toelong' name='name' type='text' maxlength='60' value='' /></td>\n";
        echo"</tr>\n";
        echo"<tr>\n";
        echo"<th class='toelong'>Short name</th>\n";
        echo"<td><input class='toelong' name='shortname' type='text' maxlength='60' value='' /></td>\n";
        echo "</tr>\n";
        echo "<tr>\n";
        echo "<td colspan='2'>\n";
        echo "<input type='hidden' name='ID' value='0' /> \n";
        echo "<input type='hidden' name='QueryType' value='SkillType' /> \n";
        echo "<input class='toebutton' name='QueryAction' type='submit' value='Add' /></td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        echo "</form>\n";
        echo"</div>\n";
      }
      echo"</div>\n";
      echo "</div>\n";
      break;

    case "3": //Oppucation types
      $crewResult=$dbf->queryselect("SELECT id, type FROM crewtypes ORDER BY prefpos ASC;");
      $crewtypes=mysql_result($dbf->queryselect("SELECT COUNT(*) count FROM crewtypes;"), 0);

      echo"<div class='typecontainer' style='overflow: auto;'>\n";
      echo"<div id='typelist' class='typelist'>\n";
      echo "<ul>\n";
      if(!isset($_GET['sub']))
      {
        echo "<li class='selectedtype'><a class='selectedtype' href='index.php?action=toe&amp;page=3'>New Profession</a></li>\n";
      }
      else
      {
        echo "<li><a class='notselectedtype' href='index.php?action=toe&amp;page=3'>New Profession</a></li>\n";
      }
      while ($crewArray=mysql_fetch_array($crewResult, MYSQL_ASSOC))
      {
        if($_GET['sub']==$crewArray[id])
        {
          echo "<li class='selectedtype'><a class='selectedtype' href='index.php?action=toe&amp;page=3&amp;sub={$crewArray[id]}'>{$crewArray[type]}</a></li>\n";
        }
        else
        {
          echo "<li><a class='notselectedtype' href='index.php?action=toe&amp;page=3&amp;sub={$crewArray[id]}'>{$crewArray[type]}</a></li>\n";
        }
      }
      echo "</ul>\n";
      echo"</div>\n";

      $equipmentTypeResult=$dbf->queryselect("SELECT * FROM equipmenttypes ORDER BY prefpos ASC;");
      $equipmenttypes=mysql_result($dbf->queryselect("SELECT COUNT(*) count FROM equipmenttypes;"), 0);

      $equipmentTypersArray;
      $equipmentTypersArrayID;
      $counter=0;
      while ($equipmentTypeArray = mysql_fetch_array($equipmentTypeResult, MYSQL_NUM))
      {
        $equipmentTypersArrayID[$counter] = $equipmentTypeArray[0];
        $equipmentTypersArray[$counter] = $equipmentTypeArray[1];
        $counter++;
      }

      if(isset($_GET['sub']))
      {
        $sub=strip($_GET['sub']);
        $crewResult=$dbf->queryselect("SELECT * FROM crewtypes WHERE ID='{$sub}';");
        $crewArray=mysql_fetch_array($crewResult, MYSQL_ASSOC);

        echo"<div id='typeeditarea' class='typeeditarea'>\n";

        if(isset($_GET['err']))
        {
          echo"<tr>\n";
          echo"<td colspan='8'><b>No changes was made because {$_GET['err']} was given.</b></td>\n";
          echo"</tr>\n";
        }

        echo"<form action='index.php?action=setupquery' method='post'>\n";
        echo"<table border='0'>\n";
        echo"<tr>\n";
        echo"<th class='toelong'>Name</th>\n";
        echo"<td><input class='toelong' name='crewtype' type='text' maxlength='60' value='{$crewArray[type]}' /></td>\n";
        echo"</tr>\n";

        echo"<tr>\n";
        echo"<th class='toelong'>Equipment</th>\n";
        echo"<td>\n";
        if ($crewArray[equipment])
        {
          echo "<select class='toelong' name='vehicletype'>\n";
          for ($counter=0; $counter<sizeof($equipmentTypersArray); $counter++)
          {
            if ($equipmentTypersArrayID[$counter] == $crewArray[vehicletype])
            {
              echo "<option value='$equipmentTypersArrayID[$counter]' selected='selected'>{$equipmentTypersArray[$counter]}</option>\n";
            }
            else
            {
              echo "<option value='$equipmentTypersArrayID[$counter]'>{$equipmentTypersArray[$counter]}</option>\n";
            }
          }
          echo "</select>\n";
        }
        else
        {
          echo "<select class='toelong' name='vehicletype' disabled='disabled'>\n";
          echo "<option>n/a</option>";
          echo "</select>\n";
        }
        echo "</td>\n";
        echo"</tr>\n";
        echo"<tr>\n";
        echo"<th class=''>Squad Leader</th>\n";
        if ($crewArray[squad])
        {
          echo "<td><input class='toeshort' name='squad' type='checkbox' checked='checked' /></td>\n";
        }
        else
        {
          echo "<td><input class='toeshort' name='squad' type='checkbox' /></td>\n";
        }
        echo"</tr>\n";

        echo"<tr>\n";
        echo"<th class=''>Equippable</th>\n";
        if ($crewArray[equipment])
        {
          echo "<td><input class='toeshort' name='equippable' type='checkbox' checked='checked' /></td>\n";
        }
        else
        {
          echo "<td><input class='toeshort' name='equippable' type='checkbox' /></td>\n";
        }
        echo "</tr>\n";
        //Buttons
        echo "<tr>\n";
        echo "<td>\n";
        echo "<input type='hidden' name='ID' value='{$crewArray[id]}' /> \n";
        echo "<input type='hidden' name='QueryType' value='Crewtype' /> \n";
        echo "<input type='hidden' name='prefpos' value='{$crewArray[prefpos]}' />\n";
        echo "<input class='toebutton' name='QueryAction' type='submit' value='Change' />\n";
        echo "<input class='toebutton' name='QueryAction' type='submit' value='Delete' onclick='return confirmSubmit(\"Delete\")' />\n";


        echo "</td>\n";
        echo"</tr>\n";
        echo"</table>\n";
        echo"</form>\n";

        echo "<hr />\n";

        //Skills
        $requirementResult=$dbf->queryselect("SELECT sr.id, st.name FROM skillrequirements sr, skilltypes st WHERE sr.personneltype='{$crewArray[id]}' AND sr.skilltype=st.id;");
        $skillTypeArray=$dbf->resulttoarray($dbf->queryselect("SELECT id, name FROM skilltypes ORDER BY name ASC;"));
        $usedSkillTypeArray=array();
        $reqconter=0;
        echo "<b>Requirements</b>\n";
        echo "<table border='0'>\n";
        while($array =  mysql_fetch_array($requirementResult, MYSQL_NUM))
        {
          $reqconter++;
          $usedSkillTypeArray[sizeof($usedSkillTypeArray)]=$array[1];
          echo "<tr>\n";
          echo "<td><b>Skill</b>:</td>\n";
          echo "<td>{$array[1]}</td>\n";
          echo "<td>\n";
          echo "<form action='index.php?action=setupquery' method='post'>\n";
          echo "<div>\n";
          echo "<input type='hidden' name='ID' value='{$array[0]}' />\n";
          echo "<input type='hidden' name='personneltype' value='{$crewArray[id]}' />\n";
          echo "<input type='hidden' name='QueryType' value='SkillRequirement' />\n";
          echo "<input class='edittablebutton' name='QueryAction' type='submit' value='Remove' onclick='return confirmSubmit(\"Remove\")' />\n";
          echo "</div>\n";
          echo "</form>\n";
          echo "</td>\n";
          echo "</tr>\n";
        }
        if($reqconter==0)
        {
          echo "<tr>\n";
          echo "<td>\n";
          echo "No Requirements\n";
          echo "</td>\n";
          echo "</tr>\n";
        }
        echo "</table>\n";
        echo "<form action='index.php?action=setupquery' method='post'>\n";
        echo "<table border='0'>\n";
        echo "<tr>\n";
        echo "<td>Add Requirement:</td>\n";
        echo "<td>\n";
        echo "<select class='edittablebox' name='skilltype'>\n";
        $counter=0;
        for($i=0; $i<sizeof($skillTypeArray); $i++)
        {
          $array=$skillTypeArray[$i];
          if($array!=null)
          {
            if(!in_array($array[1], $usedSkillTypeArray))
            {
              echo "<option value='{$array[0]}'>$array[1]</option>\n";
              $counter++;
            }
          }
        }
        if($counter==0)
        {
          echo "<option value=''>No Requirements</option>\n";
        }

        echo "</select>\n";
        echo "</td>\n";
        echo "<td>\n";
        echo "<input type='hidden' name='QueryType' value='SkillRequirement' />\n";
        echo "<input type='hidden' name='personneltype' value='{$crewArray[id]}' />\n";
        if($counter==0)
        {
          echo "<input class='edittablebutton' name='QueryAction' type='submit' value='Add' disabled='disabled'/>\n";
        }
        else
        {
          echo "<input class='edittablebutton' name='QueryAction' type='submit' value='Add' />\n";
        }
        echo "</td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        echo "</form>\n";
         

        //Up & Down buttons
        echo "<hr />\n";
        echo "<b>Site Position</b><br />\n";
        echo "<form action='index.php?action=setupquery' method='post'>\n";
        echo "<table border='0'>\n";
        echo "<tr>\n";
        echo "<td>\n";
        echo "<input type='hidden' name='ID' value='$crewArray[id]' />\n";
        echo "<input type='hidden' name='prefpos' value='$crewArray[prefpos]' />\n";
        echo "<input type='hidden' name='QueryType' value='personneltypemove' />\n";
        echo "<input type='hidden' name='QueryAction' value='up' />\n";
        if ($crewArray[prefpos]!=1)
        {
          echo "<input class='toebutton' name='QueryAction' type='submit' value='Up' />\n";
        }
        else
        {
          echo "<input class='toebutton' name='QueryAction' type='submit' value='Up' disabled='disabled' />\n";
        }
        if ($crewArray[prefpos]!=$crewtypes)
        {
          echo "<input class='toebutton' name='QueryAction' type='submit' value='Down' />\n";
        }
        else
        {
          echo "<input class='toebutton' type='submit' value='Down' disabled='disabled' />\n";
        }
        echo "</td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        echo "</form>\n";
        echo"</div>\n";
      }
      else
      {
        echo"<div id='typeeditarea' class='typeeditarea'>\n";
        echo "<form action='index.php?action=setupquery' method='post'>\n";
        echo "<table border='0'>\n";
        echo "<tr>\n";
        echo"<th class='toelong'>Name</th>\n";
        echo "<td><input class='toelong' name='crewtype' type='text' maxlength='60' /></td>\n";
        echo "</tr>\n";
        echo "<tr>\n";
        echo"<th class='toelong'>Equipment</th>\n";
        echo "<td><select class='toelong' name='vehicletype'>\n";
        for ($counter = 0; $counter < sizeof($equipmentTypersArray); $counter++)
        {
          echo "<option value='$equipmentTypersArrayID[$counter]'>{$equipmentTypersArray[$counter]}</option>\n";
        }
        echo "</select></td>\n";
        echo "</tr>\n";
        echo "<tr>\n";
        echo"<th class=''>Squad</th>\n";
        echo "<td><input class='toeshort' name='squad' type='checkbox' /></td>\n";
        echo "</tr>\n";
        echo "<tr>\n";
        echo"<th class=''>Equippable</th>\n";
        echo "<td><input class='toeshort' name='equippable' type='checkbox' /></td>\n";
        echo "</tr>\n";
        echo "<tr>\n";
        echo "<td colspan='2'>\n";
        echo "<input type='hidden' name='ID' value='0' /> \n";
        echo "<input type='hidden' name='QueryType' value='Crewtype' /> \n";
        echo "<input class='toebutton' name='QueryAction' type='submit' value='Add' /></td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        echo "</form>\n";
        echo"</div>\n";
      }

      echo "</div>\n";
      echo "</div>\n";
      break;

    case "4":
      //Fetching UnitType data
      $unitTypeResult=$dbf->queryselect("SELECT id, name FROM unittypes;");
      //Fetching used unittypes data
      $usedUnitTypeResult=$dbf->queryselect("SELECT DISTINCT type FROM unit;");
      $usedUnitTypeArray=$dbf->resulttoarraysingle($usedUnitTypeResult);


      echo"<div class='typecontainer' style='overflow: auto;'>\n";
      echo"<div id='typelist' class='typelist'>\n";
      echo "<ul>\n";
      if(!isset($_GET['sub']))
      {
        echo "<li class='selectedtype'><a class='selectedtype' href='index.php?action=toe&amp;page=4'>New Unit Type</a></li>\n";
      }
      else
      {
        echo "<li><a class='notselectedtype' href='index.php?action=toe&amp;page=4'>New Unit Type</a></li>\n";
      }
      while ($unitArray=mysql_fetch_array($unitTypeResult, MYSQL_ASSOC))
      {
        if($_GET['sub']==$unitArray[id])
        {
          echo "<li class='selectedtype'><a class='selectedtype' href='index.php?action=toe&amp;page=4&amp;sub={$unitArray[id]}'>{$unitArray[name]}</a></li>\n";
        }
        else
        {
          echo "<li><a class='notselectedtype' href='index.php?action=toe&amp;page=4&amp;sub={$unitArray[id]}'>{$unitArray[name]}</a></li>\n";
        }
      }
      echo "</ul>\n";
      echo"</div>\n";

      if(isset($_GET['sub']))
      {
        $sub=strip($_GET['sub']);
        $unitTypesResult=$dbf->queryselect("SELECT * FROM unittypes WHERE id='{$sub}';");
        $unitTypesArray=mysql_fetch_array($unitTypesResult, MYSQL_ASSOC);

        echo"<div id='typeeditarea' class='typeeditarea'>\n";

        if(isset($_GET['err']))
        {
          echo"<tr>\n";
          echo"<td colspan='8'><b>No changes was made because {$_GET['err']} was given.</b></td>\n";
          echo"</tr>\n";
        }

        echo"<form action='index.php?action=setupquery' method='post'>\n";
        echo"<table border='0'>\n";
        echo"<tr>\n";
        echo"<th class='toelong'>Unit Type</th>\n";
        echo"<td><input class='toelong' name='unittype' type='text' maxlength='60' value='{$unitTypesArray[name]}' /></td>\n";
        echo"</tr>\n";
        echo"<tr>\n";
        echo"<th class='toelong'>Color</th>\n";
        echo"<td><input class='toelong' name='color' type='text' maxlength='60' value='{$unitTypesArray[color]}' /></td>\n";
        echo"</tr>\n";
        echo"<tr>\n";
        echo "<td colspan='2'><input type='hidden' name='ID' value='{$unitTypesArray[id]}' />\n";
        echo "<input type='hidden' name='QueryType' value='UnitType' />\n";
        echo "<input class='toebutton' name='QueryAction' type='submit' value='Change' />\n";
        //Delete not used skilltype
        if (!in_array($unitTypesArray[id], $usedUnitTypeArray))
        {
          echo "<input class='toebutton' name='QueryAction' type='submit' value='Delete' onclick='return confirmSubmit(\"Delete\")' />\n";
        }
        else
        {
          echo "<input class='toebutton' name='QueryAction' type='submit' value='Delete' disabled='disabled' />\n";
        }
        echo "</td>\n";
        echo"</tr>\n";
        echo"</table>\n";
        echo"</form>\n";
        echo"</div>\n";
      }
      else
      {
        echo"<div id='typeeditarea' class='typeeditarea'>\n";
        echo "<form action='index.php?action=setupquery' method='post'>\n";
        echo "<table border='0'>\n";
        echo "<tr>\n";
        echo"<th class='toelong'>Unit Type</th>\n";
        echo"<td><input class='toelong' name='unittype' type='text' maxlength='60' value='' /></td>\n";
        echo"</tr>\n";
        echo"<tr>\n";
        echo"<th class='toelong'>Color</th>\n";
        echo"<td><input class='toelong' name='color' type='text' maxlength='60' value='' /></td>\n";
        echo "</tr>\n";
        echo "<tr>\n";
        echo "<td colspan='2'>\n";
        echo "<input type='hidden' name='ID' value='0' /> \n";
        echo "<input type='hidden' name='QueryType' value='UnitType' /> \n";
        echo "<input class='toebutton' name='QueryAction' type='submit' value='Add' /></td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        echo "</form>\n";
        echo"</div>\n";
      }
      echo"</div>\n";
      echo "</div>\n";
      break;

    case "5":
      //Fetching Unit level data
      $unitLevelResult=$dbf->queryselect("SELECT id, name FROM unitlevel ORDER BY prefpos ASC;");
      $unitleveltypes = mysql_result($dbf->queryselect("SELECT COUNT(*) count FROM unitlevel;"), 0);
      //Fetching used Unit data
      $usedUnitLevelResult=$dbf->queryselect("SELECT DISTINCT level FROM unit;");
      $usedUnitLevelArray=$dbf->resulttoarraysingle($usedUnitLevelResult);

      //Findding and organizing units tactical symbols
      $unittypeimages=array();
      if ($handle=opendir('./images/unitlevel/'))
      {
        while (false !== ($file = readdir($handle)))
        {
          $fileChunks = explode(".", $file);
          if ($file != "." && $file != ".." && preg_match('/png|jpg|gif/', $fileChunks[1]))
          {
            list($width, $height) = getimagesize("images/unitlevel/".$file);
              array_push($unittypeimages, $file);
          }
        }
        closedir($handle);
      }
      sort($unittypeimages);

      echo"<div class='typecontainer' style='overflow: auto;'>\n";
      echo"<div id='typelist' class='typelist'>\n";
      echo "<ul>\n";
      if(!isset($_GET['sub']))
      {
        echo "<li class='selectedtype'><a class='selectedtype' href='index.php?action=toe&amp;page=5'>New Unit Level</a></li>\n";
      }
      else
      {
        echo "<li><a class='notselectedtype' href='index.php?action=toe&amp;page=5'>New Unit Level</a></li>\n";
      }
      while ($unitArray=mysql_fetch_array($unitLevelResult, MYSQL_ASSOC))
      {
        if($_GET['sub']==$unitArray[id])
        {
          echo "<li class='selectedtype'><a class='selectedtype' href='index.php?action=toe&amp;page=5&amp;sub={$unitArray[id]}'>{$unitArray[name]}</a></li>\n";
        }
        else
        {
          echo "<li><a class='notselectedtype' href='index.php?action=toe&amp;page=5&amp;sub={$unitArray[id]}'>{$unitArray[name]}</a></li>\n";
        }
      }
      echo "</ul>\n";
      echo"</div>\n";

      if(isset($_GET['sub']))
      {
        $sub=strip($_GET['sub']);
        $unitLevelResult=$dbf->queryselect("SELECT * FROM unitlevel WHERE id='{$sub}';");
        $unitLevelArray=mysql_fetch_array($unitLevelResult, MYSQL_ASSOC);

        echo"<div id='typeeditarea' class='typeeditarea'>\n";

        if(isset($_GET['err']))
        {
          echo"<tr>\n";
          echo"<td colspan='8'><b>No changes was made because {$_GET['err']} was given.</b></td>\n";
          echo"</tr>\n";
        }

        echo"<form action='index.php?action=setupquery' method='post'>\n";
        echo"<table border='0'>\n";
        echo"<tr>\n";
        echo"<th class='toelong'>Name</th>\n";
        echo"<td><input class='toelong' name='name' type='text' maxlength='60' value='{$unitLevelArray[name]}' /></td>\n";
        echo"</tr>\n";
        echo"<tr>\n";
        echo"<th class='toelong'>Picture</th>\n";
        echo"<td>\n";
        $inputFields->dropboxar($unittypeimages, $unitLevelArray[picture], "picture", "edittablebox");
        echo"</td>\n";
        //echo"<td><input class='toelong' name='picture' type='text' maxlength='60' value='{$unitTypesArray[picture]}' /></td>\n";
        echo"</tr>\n";
        echo"<tr>\n";
        echo "<td colspan='2'><input type='hidden' name='ID' value='{$unitLevelArray[id]}' />\n";
        echo "<input type='hidden' name='prefpos' value='{$unitLevelArray[prefpos]}' /> \n";
        echo "<input type='hidden' name='QueryType' value='UnitLevel' />\n";
        echo "<input class='toebutton' name='QueryAction' type='submit' value='Change' />\n";
        //Delete not used skilltype
        if (!in_array($unitLevelArray[id], $usedUnitLevelArray))
        {
          echo "<input class='toebutton' name='QueryAction' type='submit' value='Delete' onclick='return confirmSubmit(\"Delete\")' />\n";
        }
        else
        {
          echo "<input class='toebutton' name='QueryAction' type='submit' value='Delete' disabled='disabled' />\n";
        }
        echo "</td>\n";
        echo"</tr>\n";
        echo"</table>\n";
        echo"</form>\n";

        echo"<hr />\n";

        echo"<form action='index.php?action=setupquery' method='post'>\n";
        echo"<table border='0'>\n";
        echo"<tr>\n";
        echo"<td>\n";
        echo"<input type='hidden' name='ID' value='{$unitLevelArray[id]}' />\n";
        echo"<input type='hidden' name='prefpos' value='{$unitLevelArray[prefpos]}' />\n";
        echo"<input type='hidden' name='QueryType' value='unitlevelmove' />\n";
        //Up button
        if ($unitLevelArray[prefpos]!=1)
        {
          echo "<input class='toebutton' name='QueryAction' type='submit' value='Up' />\n";
        }
        else
        {
          echo "<input class='toebutton' name='QueryAction' type='submit' value='Up' disabled='disabled' />\n";
        }
        if ($unitLevelArray[prefpos]!=$unitleveltypes)
        {
          echo "<input class='toebutton' name='QueryAction' type='submit' value='Down' />\n";
        }
        else
        {
          echo "<input class='toebutton' name='QueryAction' type='submit' value='Down' disabled='disabled' />\n";
        }
        echo"</td>\n";
        echo"</tr>\n";
        echo"</table>\n";
        echo"</form>\n";

        echo"</div>\n";
      }
      else
      {
        echo"<div id='typeeditarea' class='typeeditarea'>\n";
        echo "<form action='index.php?action=setupquery' method='post'>\n";
        echo "<table border='0'>\n";
        echo "<tr>\n";
        echo"<th class='toelong'>Name</th>\n";
        echo"<td><input class='toelong' name='name' type='text' maxlength='60' value='' /></td>\n";
        echo"</tr>\n";
        echo"<tr>\n";
        echo"<th class='toelong'>Picture</th>\n";
        echo"<td>\n";
        $inputFields->dropboxar($unittypeimages, $unitTypesArray[picture], "picture", "edittablebox");
        echo"</td>\n";
        echo "</tr>\n";
        echo "<tr>\n";
        echo "<td colspan='2'>\n";
        echo "<input type='hidden' name='ID' value='0' /> \n";
        echo "<input type='hidden' name='QueryType' value='UnitLevel' /> \n";
        echo "<input class='toebutton' name='QueryAction' type='submit' value='Add' /></td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        echo "</form>\n";
        echo"</div>\n";
      }
      echo"</div>\n";
      echo "</div>\n";
      break;

      /*
       ?>
       <div class="genericarea">
       <table border="0">
       <?php
       if(isset($_GET['err']))
       {
       echo"<tr>\n";
       echo"<td colspan='8'><b>No changes was made because {$_GET['err']} was given.</b></td>\n";
       echo"</tr>\n";
       }
       ?>
       <tr>
       <th class="toelong">Name</th>
       <th class="toeshort">picture</th>
       <th class="toeshort">level</th>
       </tr>
       </table>

       <table border="0">
       <?php
       while ($unitTypeArray = mysql_fetch_array($unitLevelResult, MYSQL_NUM))
       {
       ?>
       <tr>
       <td>
       <form action="index.php?action=setupquery" method="post">
       <table border="0">
       <tr>
       <td><input class="toelong" name="name" type="text" maxlength="60"
       value="<?php echo"$unitTypeArray[1]"?>" /></td>
       <td><?php   $inputFields->dropboxar($unittypeimages, $unitTypeArray[3], "picture", "edittablebox");?>
       </td>
       <td><?php echo"$unitTypeArray[2]"?></td>
       <td><input type="hidden" name="ID"
       value="<?php echo"$unitTypeArray[0]"?>" /> <input type="hidden"
       name="prefpos" value="<?php echo"$unitTypeArray[2]"?>" /> <input
       type="hidden" name="QueryType" value="UnitLevel" /> <input
       class="toebutton" name="QueryAction" type="submit" value="Change" />
       <?php
       if (!in_array($unitTypeArray[0], $usedUnitLevelArray))
       {
       echo "<input class='toebutton' name='QueryAction' type='submit' value='Delete' onclick='return confirmSubmit(\"Delete\")' />\n";
       }
       else
       {
       echo "<input class='toebutton' name='QueryAction' type='submit' value='Delete' disabled='disabled' />\n";
       }
       ?></td>
       </tr>
       </table>
       </form>
       </td>
       <td>
       <form action="index.php?action=setupquery" method="post">
       <table border="0">
       <tr>
       <td><!--Hidden--> <input type="hidden" name="ID"
       value="<?php echo"$unitTypeArray[0]"?>" /> <input type="hidden"
       name="prefpos" value="<?php echo"$unitTypeArray[2]"?>" /> <input
       type="hidden" name="QueryType" value="unitlevelmove" /> <?php
       //Up button
       if ($unitTypeArray[2]!=1)
       {
       echo "<input class='toebutton' name='QueryAction' type='submit' value='Up' />\n";
       }
       else
       {
       echo "<input class='toebutton' name='QueryAction' type='submit' value='Up' disabled='disabled' />\n";
       }
       if ($unitTypeArray[2]!=$unitleveltypes)
       {
       echo "<input class='toebutton' name='QueryAction' type='submit' value='Down' />\n";
       }
       else
       {
       echo "<input class='toebutton' name='QueryAction' type='submit' value='Down' disabled='disabled' />\n";
       }
       ?></td>
       </tr>
       </table>
       </form>
       </td>
       </tr>
       <?php
       }
       ?>
       <tr>
       <td>
       <form action="index.php?action=setupquery" method="post">
       <table border="0">
       <tr>
       <td><input class="toelong" name="name" type="text" /></td>
       <td><?php  $inputFields->dropboxar($unittypeimages, $unitTypeArray[3], "picture", "edittablebox");?>
       </td>
       <td colspan="2"><input type="hidden" name="ID" value="0" /> <input
       type="hidden" name="QueryType" value="UnitLevel" /> <input
       class="toebutton" name="QueryAction" type="submit" value="Add" /></td>
       </tr>
       </table>
       </form>
       </td>
       </tr>
       </table>
       </div>
       <?php
       echo "</div>";
       break;

       */
    case "6":
      //Fetching Rank data
      $rankResult = $dbf->queryselect("SELECT * FROM ranks;");

      ?>
<div class="genericarea">
<table class="generictable" border="0">
	<tr>
		<td>
		<table border="0">
			<tr>
				<th class="toelong">Ranks</th>
			</tr>
		</table>
		</td>
	</tr>
	<?php
	while ($rankArray = mysql_fetch_array($rankResult, MYSQL_NUM))
	{
	  ?>
	<tr>
		<td>
		<form action="index.php?action=setupquery" method="post">
		<table border="0">
			<tr>
				<td><input class="toelong" name="rank" type="text" maxlength="60"
					value="<?php echo"$rankArray[1]"?>" /></td>
				<td><input type="hidden" name="ID"
					value="<?php echo"$rankArray[0]"?>" /> <input type="hidden"
					name="QueryType" value="Rank" /> <input type="hidden"
					name="QueryAction" value="AddChange" /> <input class="toebutton"
					type="submit" value="Change" /></td>
			</tr>
		</table>
		</form>
		</td>
	</tr>
	<?php
	}
	?>
</table>
</div>
	<?php
	echo "</div>";
	break;
default:

  //Fetching Equipment data
  $equipmentResult=$dbf->queryselect("SELECT id, name FROM equipmenttypes ORDER BY prefpos ASC;");
  $equipmenttypes=mysql_result($dbf->queryselect("SELECT COUNT(*) count FROM equipmenttypes;"), 0); //Fetching used equipmenttype data
  $usedEquipmentTypeResult=$dbf->queryselect("SELECT DISTINCT vehicletype FROM crewtypes;");
  $usedEquipmentTypeArray=$dbf->resulttoarraysingle($usedEquipmentTypeResult);

  $requirementResult=$dbf->queryselect("SELECT id, type FROM crewtypes WHERE equipment='1' ORDER BY prefpos ASC;");

  $requirementsArray;
  $requirementsArrayID;
  $counter=0;
  while ($recArray = mysql_fetch_array($requirementResult, MYSQL_ASSOC))
  {
    $requirementsArrayID[$counter]=$recArray[id];
    $requirementsArray[$counter]=$recArray[type];
    $counter++;
  }

  //echo "<div class='genericarea'>";
  //echo "<table border='0'>";

  echo"<div class='typecontainer' style='overflow: auto;'>\n";
  echo"<div id='typelist' class='typelist'>\n";
  echo "<ul>\n";
  if(!isset($_GET['sub']))
  {
    echo "<li class='selectedtype'><a class='selectedtype' href='index.php?action=toe&amp;page=1'>New Equipment Type</a></li>\n";
  }
  else
  {
    echo "<li><a class='notselectedtype' href='index.php?action=toe&amp;page=1'>New Equipment Type</a></li>\n";
  }
  while ($equipmentArray=mysql_fetch_array($equipmentResult, MYSQL_ASSOC))
  {
    if($_GET['sub']==$equipmentArray[id])
    {
      echo "<li class='selectedtype'><a class='selectedtype' href='index.php?action=toe&amp;page=1&amp;sub={$equipmentArray[id]}'>{$equipmentArray[name]}</a></li>\n";
    }
    else
    {
      echo "<li><a class='notselectedtype' href='index.php?action=toe&amp;page=1&amp;sub={$equipmentArray[id]}'>{$equipmentArray[name]}</a></li>\n";
    }
  }
  echo "</ul>\n";
  echo"</div>\n";

  if(isset($_GET['err']))
  {
    echo"<td colspan='8'><b>No changes was made because {$_GET['err']} was given.</b></td>\n";
  }

  if(isset($_GET['sub']))
  {
    $sub=strip($_GET['sub']);
    $equipmentTypeResult=$dbf->queryselect("SELECT * FROM equipmenttypes WHERE id='{$sub}';");
    $equipmentTypeArray=mysql_fetch_array($equipmentTypeResult, MYSQL_ASSOC);

    echo "<div id='typeeditarea' class='typeeditarea'>\n";
    echo "<form action='index.php?action=setupquery' method='post'>\n";
    echo "<table border='0'>\n";
    //Type
    echo "<tr>\n";
    echo "<th class='toelong'>Type</th>\n";
    echo "<td><input class='toelong' name='name' type='text' maxlength='60' value='{$equipmentTypeArray[name]}' /></td>\n";
    echo "</tr>\n";
    //ID Number
    echo "<tr>\n";
    echo "<th class='toeshort'>ID Number</th>\n";
    echo "<td><input class='toeshort' name='license' type='text' maxlength='60' value='{$equipmentTypeArray[license]}'/></td>\n";
    echo "</tr>\n";
    //Max Weight
    echo "<tr>\n";
    echo "<th class='toeshort'>Max Weight</th>\n";
    echo "<td><input class='toeshort' name='maxweight' type='text' maxlength='3' value='{$equipmentTypeArray[maxweight]}' /></td>\n";
    echo"</tr>\n";
    //Min Weight
    echo "<tr>\n";
    echo "<th class='toeshort'>Min Weight</th>\n";
    echo "<td><input class='toeshort' name='minweight' type='text' maxlength='3' value='{$equipmentTypeArray[minweight]}' /></td>\n";
    echo"</tr>\n";
    //Weight Step
    echo "<tr>\n";
    echo "<th class='toeshort'>Weight Step</th>\n";
    echo "<td><input class='toeshort' name='weightstep' type='text' maxlength='3' value='{$equipmentTypeArray[weightstep]}' /></td>\n";
    echo "</tr>\n";
    //Weight Scale
    echo "<tr>\n";
    echo "<th class='toeshort'>Weight Scale</th>\n";
    echo "<td><select class='edt_left_short' name='weightscale'>\n";

    if ($equipmentTypeArray[weightscale]=="ton")
    {
      echo "<option value='ton' selected='selected'>ton</option>\n";
      echo "<option value='Mton'>Mton</option>\n";
      echo "<option value='kg'>kg</option>\n";
    }
    else
    {
      if ($equipmentTypeArray[weightscale]=="Mton")
      {
        echo "<option value='ton'>ton</option>\n";
        echo "<option value='Mton' selected='selected'>Mton</option>\n";
        echo "<option value='kg'>kg</option>\n";
      }
      else
      {
        echo "<option value='ton'>ton</option>\n";
        echo "<option value='Mton'>Mton</option>\n";
        echo "<option value='kg' selected='selected'>kg</option>\n";
      }
    }
    echo "</select></td>\n";
    echo "</tr>\n";
    //Requirement
    echo "<tr>\n";
    echo "<th class='toeshort'>Requirement</th>\n";
    echo"<td>\n";
    echo "<select class='toelong' name='requirement'>\n";
    echo "<option value='0' selected='selected'>No Requirements</option>\n";
    for ($counter=0; $counter<sizeof($requirementsArray); $counter++)
    {
      if ($requirementsArrayID[$counter]==$equipmentTypeArray[requirement])
      {
        echo "<option value='$requirementsArrayID[$counter]' selected='selected'>{$requirementsArray[$counter]}</option>\n";
      }
      else
      {
        echo "<option value='$requirementsArrayID[$counter]'>{$requirementsArray[$counter]}</option>\n";
      }
    }
    echo "</select>\n";
    echo"</td>\n";
    echo "</tr>\n";
    //Used
    echo "<tr>\n";
    echo "<th class='toeshort'>Used</th>\n";
    if ($equipmentTypeArray[used])
    {
      echo "<td><input class='toeshort' name='usedequipment' type='checkbox' checked='checked' /></td>\n";
    }
    else
    {
      echo "<td><input class='toeshort' name='usedequipment' type='checkbox' /></td>\n";
    }
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td><input type='hidden' name='ID' value='{$equipmentTypeArray[id]}' />\n";
    echo "<input type='hidden' name='QueryType' value='EquipmentType' />\n";
    echo "<input type='hidden' name='prefpos' value='{$equipmentTypeArray[prefpos]}' />\n";
    echo "<input class='toebutton' name='QueryAction' type='submit' value='Change' />\n";

    if (!in_array($equipmentTypeArray[id], $usedEquipmentTypeArray))
    {
      echo "<input class='toebutton' name='QueryAction' type='submit' value='Delete' onclick='return confirmSubmit(\"Delete\")' />\n";
    }
    else
    {
      echo "<input class='toebutton' name='QueryAction' type='submit' value='Delete' disabled='disabled' />\n";
    }
    echo "</td>\n";
    echo "</tr>\n";

    echo "</table>\n";
    echo "</form>\n";

    echo "<hr />\n";
    echo "<b>Position</b><br />\n";
    echo "<form action='index.php?action=setupquery' method='post'>\n";
    echo "<table border='0'>\n";
    echo "<tr>\n";
    echo "<td><input type='hidden' name='ID' value='{$equipmentTypeArray[id]}' />\n";
    echo "<input type='hidden' name='prefpos' value='{$equipmentTypeArray[prefpos]}' />\n";
    echo "<input type='hidden' name='QueryType' value='equipmenttypemove' />\n";
    //Up button
    if ($equipmentTypeArray[prefpos]!=1)
    {
      echo "<input class='toebutton' name='QueryAction' type='submit' value='Up' />\n";
    }
    else
    {
      echo "<input class='toebutton' name='QueryAction' type='submit' value='Up' disabled='disabled' />\n";
    }
    if ($equipmentTypeArray[prefpos]!=$equipmenttypes)
    {
      echo "<input class='toebutton' name='QueryAction' type='submit' value='Down' />\n";
    }
    else
    {
      echo "<input class='toebutton' name='QueryAction' type='submit' value='Down' disabled='disabled' />\n";
    }
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "</form>\n";

    echo "</div>\n";
  }
  else
  {
    echo "<div id='typeeditarea' class='typeeditarea'>\n";
    echo "<form action='index.php?action=setupquery' method='post'>\n";
    echo "<table border='0'>\n";
    //Type
    echo "<tr>\n";
    echo "<th class='toelong'>Type</th>\n";
    echo "<td><input class='toelong' name='name' type='text' maxlength='60' value='' /></td>\n";
    echo "</tr>\n";
    //ID Number
    echo "<tr>\n";
    echo "<th class='toeshort'>ID Number</th>\n";
    echo "<td><input class='toeshort' name='license' type='text' maxlength='60' value=''/></td>\n";
    echo "</tr>\n";
    //Max Weight
    echo "<tr>\n";
    echo "<th class='toeshort'>Max Weight</th>\n";
    echo "<td><input class='toeshort' name='maxweight' type='text' maxlength='3' value='' /></td>\n";
    echo"</tr>\n";
    //Min Weight
    echo "<tr>\n";
    echo "<th class='toeshort'>Min Weight</th>\n";
    echo "<td><input class='toeshort' name='minweight' type='text' maxlength='3' value='' /></td>\n";
    echo"</tr>\n";
    //Weight Step
    echo "<tr>\n";
    echo "<th class='toeshort'>Weight Step</th>\n";
    echo "<td><input class='toeshort' name='weightstep' type='text' maxlength='3' value='' /></td>\n";
    echo "</tr>\n";
    //Weight Scale
    echo "<tr>\n";
    echo "<th class='toeshort'>Weight Scale</th>\n";
    echo "<td><select class='edt_left_short' name='weightscale'>\n";
    echo "<option value='ton'>ton</option>\n";
    echo "<option value='Mton'>Mton</option>\n";
    echo "<option value='kg'>kg</option>\n";
    echo "</select></td>\n";
    echo "</tr>\n";
    //Requirement
    echo "<tr>\n";
    echo "<th class='toeshort'>Requirement</th>\n";
    echo"<td>\n";
    echo "<select class='toelong' name='requirement'>\n";
    echo "<option value='0' selected='selected'>No Requirements</option>\n";
    for ($counter=0; $counter<sizeof($requirementsArray); $counter++)
    {
      echo "<option value='$requirementsArrayID[$counter]'>{$requirementsArray[$counter]}</option>\n";
    }
    echo "</select>\n";
    echo"</td>\n";
    echo "</tr>\n";
    //Used
    echo "<tr>\n";
    echo "<th class='toeshort'>Used</th>\n";
    echo "<td><input class='toeshort' name='usedequipment' type='checkbox' /></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td colspan='2'><input type='hidden' name='ID' value='0' /> ";
    echo "<input type='hidden' name='QueryType' value='EquipmentType' /> ";
    echo "<input class='toebutton' name='QueryAction' type='submit' value='Add' /></td>\n";
    echo "</tr>\n";

    echo "</table>\n";
    echo "</form>\n";

    echo "</div>\n";
  }

  echo "</div>\n";
  echo "</div>\n";
  break;
  }
}
if($error)
{
  echo "<div id='content'>\n";
  echo "<div class='error'>\n";
  echo "<b>An error has occurred</b> while accessing this page.<br />\n";
  echo "You don't have rights to access this page.<br />\n";
  echo "Please be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg,"<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>