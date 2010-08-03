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
  $data = stripslashes($data);
  $data = mysql_real_escape_string($data);
  $data = strip_tags($data);
  return $data;
}

if (!isset ($_SESSION['SESS_NAME']) || $_SESSION['SESS_TYPE'] > '4')
{
  echo "<div id='content'>\n";
  echo "<b>Access Denied</b>";
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
      $skillTypeResult=$dbf->queryselect("SELECT * FROM skilltypes ORDER BY name ASC;");

      $usedSkillTypeResult=$dbf->queryselect("SELECT DISTINCT skill FROM skills;");
      $usedSkillTypeArray=$dbf->resulttoarraysingle($usedSkillTypeResult);

      echo "<div class='genericarea'>\n";
      echo "<table class='generictable'' border='0'>\n";

      if(isset($_GET['err']))
      {
        echo"<tr>\n";
        echo"<td colspan='8'><b>No changes was made because {$_GET['err']} was given.</b></td>\n";
        echo"</tr>\n";
      }

      echo "<tr>\n";
      echo "<td>\n";
      echo "<table border='0'>\n";
      echo "<tr>\n";
      echo "<th class='toelong'>Name</th>\n";
      echo "<th class='toelong'>Short Name</th>\n";
      echo "</tr>\n";
      echo "</table>\n";
      echo "</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td>\n";
      echo "<table border='0'>\n";

      while ($skillArray=mysql_fetch_array($skillTypeResult, MYSQL_ASSOC))
      {
        echo "<tr>\n";
        echo "<td>\n";
        echo "<form action='index.php?action=setupquery' method='post'>\n";
        echo "<table border='0'>\n";
        echo "<tr>\n";
        echo "<td><input class='toelong' name='name' type='text' maxlength='60'	value='{$skillArray[name]}' /></td>\n";
        echo "<td><input class='toelong' name='shortname' type='text' maxlength='60' value='{$skillArray[shortname]}' /></td>\n";

        echo "<td><input type='hidden' name='ID' value='{$skillArray[id]}' />\n";
        echo "<input type='hidden' name='QueryType' value='SkillType' />\n";
        echo "<input class='toebutton' name='QueryAction' type='submit' value='Change' />\n";

        //Delete not used skilltype
        if (!in_array($skillArray[id], $usedSkillTypeArray))
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
        echo "</td>\n";
        echo "</tr>\n";
      }

      //add new skill
      echo "<tr>\n";
      echo "<td>\n";
      echo "<form action='index.php?action=setupquery' method='post'>\n";
      echo "<table border='0'>\n";
      echo "<tr>\n";
      echo "<td><input class='toelong' name='name' type='text' maxlength='60' /></td>\n";
      echo "<td><input class='toelong' name='shortname' type='text' maxlength='60' /></td>\n";
      echo "<td colspan='2'>\n";
      echo "<input type='hidden' name='ID' value='0' /> \n";
      echo "<input type='hidden' name='QueryType' value='SkillType' /> \n";
      echo "<input class='toebutton' name='QueryAction' type='submit' value='Add' /></td>\n";
      echo "</tr>\n";
      echo "</table>\n";
      echo "</form>\n";
      echo "</td>\n";
      echo "</tr>\n";
      echo "</table>\n";
      echo "</td>\n";
      echo "</tr>\n";
      echo "</table>\n";
      echo "</div>\n";
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
          for ($counter = 0; $counter < sizeof($equipmentTypersArray); $counter++)
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
      //Fetching used unittypes data
      $usedUnitTypeResult = $dbf->queryselect("SELECT DISTINCT type FROM unit;");
      $usedUnitTypeArray = $dbf->resulttoarraysingle($usedUnitTypeResult);
      //Fetching UnitType data
      $unitResult = $dbf->queryselect("SELECT * FROM unittypes;");
       
      ?>
<div class="genericarea">
<table class="generictable" border="0">
<?php
if(isset($_GET['err']))
{
  echo"<tr>\n";
  echo"<td colspan='8'><b>No changes was made because {$_GET['err']} was given.</b></td>\n";
  echo"</tr>\n";
}
?>
	<tr>
		<td>

		<table border="0">
			<tr>
				<th class="toelong">Unit type</th>
				<th class="toelong">Color</th>
			</tr>
		</table>

		</td>
	</tr>


	<?php
	while ($unitArray = mysql_fetch_array($unitResult, MYSQL_NUM))
	{
	  ?>
	<tr>
		<td>
		<form action="index.php?action=setupquery" method="post">
		<table border="0">
			<tr>
				<td><input class="toelong" name="unittype" type="text"
					maxlength="60" value="<?php echo"$unitArray[1]"?>" /></td>
				<td><input class="toelong" name="color" type="text" maxlength="60"
					value="<?php echo"$unitArray[2]"?>" /></td>
				<td><input type="hidden" name="ID"
					value="<?php echo"$unitArray[0]"?>" /> <input type="hidden"
					name="QueryType" value="UnitType" /> <input class="toebutton"
					name="QueryAction" type="submit" value="Change" /> <?php
					if (!in_array($unitArray[0], $usedUnitTypeArray))
					{
					  echo "<input class='toebutton' type='submit' name='QueryAction' value='Delete' onclick='return confirmSubmit(\"Delete\")' />\n";
					}
					else
					{
					  echo "<input class='toebutton' type='submit' name='QueryAction' value='Delete' disabled='disabled' />\n";
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
				<td><input class="toelong" name="unittype" type="text"
					maxlength="60" /></td>
				<td><input class="toelong" name="color" type="text" maxlength="60" /></td>
				<td colspan="2"><input type="hidden" name="ID" value="0" /> <input
					type="hidden" name="QueryType" value="UnitType" /> <input
					class="toebutton" type="submit" name="QueryAction" value="Add" /></td>
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
case "5":

  //Fetching Unit level data
  $unitLevelResult=$dbf->queryselect("SELECT * FROM unitlevel ORDER BY prefpos ASC;");
  $unitleveltypes = mysql_result($dbf->queryselect("SELECT COUNT(*) count FROM unitlevel;"), 0);
  $usedUnitLevelResult=$dbf->queryselect("SELECT DISTINCT level FROM unit;");
  $usedUnitLevelArray=$dbf->resulttoarraysingle($usedUnitLevelResult);

  //Findding and organizing units tactical symbols
  $unittypeimages=array();
  if ($handle=opendir('./images/unitlevel/'))
  {
    while (false !== ($file = readdir($handle)))
    {
      $fileChunks = explode(".", $file);
      if ($file != "." && $file != ".." &&  preg_match('/png|jpg|gif/', $fileChunks[1]))
      {
      	array_push($unittypeimages, $file);
      }
    }
    closedir($handle);
  }
  sort($unittypeimages);
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
  $equipmentTypeResult = $dbf->queryselect("SELECT * FROM equipmenttypes ORDER BY prefpos ASC;");
  $equipmenttypes = mysql_result($dbf->queryselect("SELECT COUNT(*) count FROM equipmenttypes;"), 0); //Fetching used equipmenttype data
  $usedEquipmentTypeResult = $dbf->queryselect("SELECT DISTINCT vehicletype FROM crewtypes;");
  $usedEquipmentTypeArray = $dbf->resulttoarraysingle($usedEquipmentTypeResult);

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
		<th class="toelong">Type</th>
		<th class="toeshort">ID Number</th>
		<th class="toeshort">Max Weight</th>
		<th class="toeshort">Min Weight</th>
		<th class="toeshort">Weight Step</th>
		<th class="toeshort">Weight Scale</th>
		<th class="toeshort">Used</th>
	</tr>
</table>

<table border="0">
<?php
//$equipmentTypersArray;
//$equipmentTypersArrayID;
//$counter = 0;
while ($equipmentTypeArray = mysql_fetch_array($equipmentTypeResult, MYSQL_NUM))
{
  //$equipmentTypersArrayID[$counter] = $equipmentTypeArray[0];
  //$equipmentTypersArray[$counter] = $equipmentTypeArray[1];
  ?>
	<tr>
		<td>
		<form action="index.php?action=setupquery" method="post">
		<table border="0">
			<tr>
				<td><input class="toelong" name="name" type="text" maxlength="60"
					value="<?php echo"$equipmentTypeArray[1]"?>" /></td>
				<td><input class="toeshort" name="license" type="text"
					maxlength="60" value="<?php echo"$equipmentTypeArray[2]"?>" /></td>
				<td><input class="toeshort" name="maxweight" type="text"
					maxlength="8" value="<?php echo"$equipmentTypeArray[3]"?>" /></td>
				<td><input class="toeshort" name="minweight" type="text"
					maxlength="8" value="<?php echo"$equipmentTypeArray[4]"?>" /></td>
				<td><input class="toeshort" name="weightstep" type="text"
					maxlength="3" value="<?php echo"$equipmentTypeArray[5]"?>" /></td>
				<td><select class='edt_left_short' name='weightscale'>
				<?php
				if ($equipmentTypeArray[6] == "ton")
				{
				  ?>
					<option value="ton" selected="selected">ton</option>
					<option value="Mton">Mton</option>
					<option value="kg">kg</option>
					<?php
				}
				else
				{
				  if ($equipmentTypeArray[6] == "Mton")
				  {
						  ?>
					<option value="ton">ton</option>
					<option value="Mton" selected="selected">Mton</option>
					<option value="kg">kg</option>
					<?php
				  }
				  else
				  {
						  ?>
					<option value="ton">ton</option>
					<option value="Mton">Mton</option>
					<option value="kg" selected="selected">kg</option>
					<?php
				  }
				}
				?>
				</select></td>
				<?php
				//Squad checkbox
				if ($equipmentTypeArray[8])
				{
				  echo "<td><input class='toeshort' name='usedequipment' type='checkbox' checked='checked' /></td>\n";
				}
				else
				{
				  echo "<td><input class='toeshort' name='usedequipment' type='checkbox' /></td>\n";
				}
				?>

				<td><input type="hidden" name="ID"
					value="<?php echo"$equipmentTypeArray[0]"?>" /> <input
					type="hidden" name="QueryType" value="EquipmentType" /> <input
					type="hidden" name="prefpos"
					value="<?php echo"$equipmentTypeArray[7]"?>" /> <input
					class="toebutton" name="QueryAction" type="submit" value="Change" />
					<?php
					if (!in_array($equipmentTypeArray[0], $usedEquipmentTypeArray))
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
		<td><!--Move up/down Equipmenttype-->
		<form action="index.php?action=setupquery" method="post">
		<table border="0">
			<tr>
				<td><!--Hidden--> <input type="hidden" name="ID"
					value="<?php echo"$equipmentTypeArray[0]"?>" /> <input
					type="hidden" name="prefpos"
					value="<?php echo"$equipmentTypeArray[7]"?>" /> <input
					type="hidden" name="QueryType" value="equipmenttypemove" /> <?php
					//Up button
					if ($equipmentTypeArray[7]!=1)
					{
					  echo "<input class='toebutton' name='QueryAction' type='submit' value='Up' />\n";
					}
					else
					{
					  echo "<input class='toebutton' name='QueryAction' type='submit' value='Up' disabled='disabled' />\n";
					}
					if ($equipmentTypeArray[7]!=$equipmenttypes)
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
	//$counter++;
}
?>
	<tr>
		<td>
		<form action="index.php?action=setupquery" method="post">
		<table border="0">
			<tr>
				<td><input class="toelong" name="name" type="text" /></td>
				<td><input class="toeshort" name="license" type="text" maxlength="2" /></td>
				<td><input class="toeshort" name="maxweight" type="text"
					maxlength="8" /></td>
				<td><input class="toeshort" name="minweight" type="text"
					maxlength="8" /></td>
				<td><input class="toeshort" name="weightstep" type="text"
					maxlength="4" /></td>
				<td><select class="toeshort" name="weightscale">
					<option value="ton" selected="selected">ton</option>
					<option value="Mton">Mton</option>
					<option value="kg">kg</option>
				</select></td>
				<td><input class="toeshort" name="usedequipment" type="checkbox" /></td>

				<td colspan="2"><input type="hidden" name="ID" value="0" /> <input
					type="hidden" name="QueryType" value="EquipmentType" /> <input
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
  }
}
?>
