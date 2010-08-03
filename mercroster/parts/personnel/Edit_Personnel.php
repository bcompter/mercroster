<?php
if(!defined('Mbs35cED2daj'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}

require("htdocs/dbsetup.php");
$crewID=$_GET['personnel'];
$crewID=stripslashes($crewID);
$crewID=mysql_real_escape_string($crewID);

require("includes/InputFields.php");
$inputFields = new InputFields;

$min=0;
$max=8;

if(isset($_SESSION['SESS_NAME']) && $_SESSION['SESS_TYPE']<'5')
{
  if(isset($_GET['personnel']))
  {
    $result=$dbf->queryselect("SELECT id, rank, lname, fname, callsign, crewnumber, joiningdate, notes, bday, status, parent, notable, image FROM crew WHERE id='$crewID';");
    if(mysql_num_rows($result)==1)
    {
      $array=mysql_fetch_array($result, MYSQL_ASSOC);
      $personnelType=$array[1];
      $submitButtonText='Save';

      $usedSkills=$dbf->resulttoarray($dbf->queryselect("SELECT s.id, st.name, s.value, s.skill, st.id AS skillid FROM skills s, skilltypes st WHERE s.person='{$array[id]}' AND s.skill=st.id;"));
      $freeSkillArray=$dbf->resulttoarray($dbf->queryselect("SELECT id, name FROM skilltypes ORDER BY name ASC;"));
      $positionsArray=$dbf->resulttoarray($dbf->queryselect("SELECT pp.id, pp.personneltype, pp.person, ct.type FROM personnelpositions pp, crewtypes ct WHERE pp.personneltype=ct.id AND person='{$array[id]}' ORDER BY ct.prefpos ASC;"));
      $usedPositionsIDArray=$dbf->resulttoarraysingle($dbf->queryselect("SELECT ct.id FROM personnelpositions pp, crewtypes ct WHERE pp.personneltype=ct.id AND person='{$array[id]}' ORDER BY ct.prefpos ASC;"));
      $ctids="";
      $councts=0;
      foreach ($usedPositionsIDArray as $variable)
      {
        if($councts>0)
        {
          $ctids.=" OR ct.id='".$variable."'";
        }
        else
        {
          $ctids="ct.id='".$variable."'";
        }
        $councts++;
      }
      if($councts>0)
      {
        $ctids="AND (".$ctids.")";
        $usedSkillsIDArray=$dbf->resulttoarraysingle($dbf->queryselect("SELECT st.id FROM skilltypes st, skillrequirements sr, crewtypes ct WHERE st.id=sr.skilltype AND sr.personneltype=ct.id {$ctids};"));
      }
      else
      {
        $usedSkillsIDArray=array();
      }
      
      $reqArray=$dbf->resulttoarray($dbf->queryselect("SELECT c.id, c.type, sr.skilltype, st.name FROM crewtypes c, skillrequirements sr, skilltypes st WHERE c.id=sr.personneltype AND sr.skilltype=st.id"));
      $reqid=array();
      $reqq=array();
      for($i=0; $i<sizeof($reqArray); $i++)
      {
        if(in_array($reqArray[$i][id], $reqid))
        {
          array_push($reqq[array_search($reqArray[$i][id], $reqid)], $reqArray[$i][skilltype]);
        }
        else
        {
          $reqq[sizeof($reqid)][0]=$reqArray[$i][id];
          $reqq[sizeof($reqid)][1]=$reqArray[$i][type];
          $reqq[sizeof($reqid)][2]=$reqArray[$i][skilltype];
          $reqid[sizeof($reqid)]=$reqArray[$i][id];
        }
      }
      $usedSkillsArray=array();
      for($i=0; $i<sizeof($usedSkills); $i++)
      {
        $usedSkillsArray[sizeof($usedSkillsArray)]=$usedSkills[$i][skillid];
      }
      $positionid=array();
      $positiontype=array();
      for($i=0; $i<sizeof($reqq); $i++)
      {
        $accepted=1;
        for($j=2; $j<sizeof($reqq[$i]); $j++)
        {
          $tempid=$reqq[$i][0];
          $temptype=$reqq[$i][1];
          if(!in_array($reqq[$i][$j], $usedSkillsArray))
          {
            $accepted=0;
            break;
          }
        }
        $usea=0;
        if(sizeof($usedPositionsIDArray)>0 && in_array($tempid, $usedPositionsIDArray))
        {
          $usea=1;
        }

        if($accepted && !$usea)
        {
          $positionid[sizeof($positionid)]=$tempid;
          $positiontype[sizeof($positiontype)]=$temptype;
        }
      }
    }
    else
    {
      $error=true;
      $errormsg="No personnel found.";
    }
  }
  else
  {
    require("htdocs/dbsetup.php");
    $personnelType=$_GET["type"];
    $personnelType=stripslashes($personnelType);
    $personnelType=mysql_real_escape_string($personnelType);
    $crewID = 0;
    $submitButtonText='Add';
  }

  if(!$error)
  {
    //Fetching used dates data
    $datesResult=$dbf->queryselect("SELECT * FROM dates WHERE id=1;");
    $datesArray=mysql_fetch_array($datesResult, MYSQL_NUM);
    $date=$datesArray[1];
    $startingYear=strtok($date, "-");
    $date=$datesArray[3];
    $endingYear=strtok($date, "-");

    //Setting las
    $lastVehicleID=0;

    //Fetching Rank data
    $rankResult=$dbf->queryselect("SELECT number, rankname FROM ranks WHERE rankname<>'';");

    //Fetching Current & Available Vehicles data
    if(isset($_GET['personnel']))
    {
      $vehicleResult=$dbf->queryselect("SELECT id, name, subtype, crew FROM equipment WHERE crew='$crewID';");
    }
    $availableVehicleResult=$dbf->queryselect("SELECT e.id, e.name, e.subtype, e.crew, et.requirement FROM equipment e LEFT JOIN equipmenttypes et ON e.type=et.id WHERE crew='0';");

    //Findding personnel images
    $personnelimages = array();
    if ($handle = opendir('./images/personnelimages/'))
    {
      while (false !== ($file = readdir($handle)))
      {
        $fileChunks = explode(".", $file);
        if ($file != "." && $file != ".." && preg_match('/png|jpg|gif/', $fileChunks[1]))
        {
          array_push($personnelimages, $file);
        }
      }
      closedir($handle);
    }
    sort($personnelimages);

    echo "<div id='content'>\n";
    echo "<div class='genericarea'>\n";
    echo "<b>Personal Information</b>\n";
    echo "<form action='index.php?action=personnelquery' method='post' id='modified'>\n";
    echo "<table class='main' border='0'>\n";
    //Rank
    echo "<tr>\n";
    echo "<td class='edittableleft'>Rank:</td>\n";
    echo "<td class='edittableright' colspan='6'>\n";
    $inputFields->dropboxqu($rankResult, $array[rank], "rank", "edittablebox", false);
    echo "</td>\n";
    echo "</tr>\n";
    //Last Name
    echo "<tr>\n";
    echo "<td class='edittableleft'>Last name:</td>\n";
    echo "<td class='edittableright' colspan='6'>";
    $inputFields->textinput("edittablecommon265","lname",45,$array[lname]);
    echo "</td>\n";
    echo "</tr>\n";
    //First Name
    echo "<tr>\n";
    echo "<td class='edittableleft'>First name:</td>\n";
    echo "<td class='edittableright' colspan='6'>";
    $inputFields->textinput("edittablecommon265","fname",45,$array[fname]);
    echo "</td>\n";
    echo "</tr>\n";
    //Call Sign
    echo "<tr>\n";
    echo "<td class='edittableleft'>Callsign:</td>\n";
    echo "<td class='edittableright' colspan='6'>";
    $inputFields->textinput("edittablecommon265","callsign",45,$array[callsign]);
    echo "</td>\n";
    echo "</tr>\n";
    //Birth Day
    echo "<tr>\n";
    echo "<td class='edittableleft'>Birthday:</td>\n";
    $minYear=2950;
    $maxYear=3050;
    $inputFields->datebar($array[bday], $maxYear, $minYear, "birthyear", "birthmonth", "birthday", false);
    echo "</tr>\n";
    //Status
    $status=$array[status];
    if ($array[status]==0)
    {
      $statusArray[0]="Active";
      $statusArray[1]="Hospitalized";
      $statusArray[2]="On Leave";
      $statusArray[3]="Retired";
      $statusArray[4]="Deceased";
      $statusArray[5]="Missing In Action";
      echo "<tr>\n";
      echo "<td class='edittableleft'>Status:</td>\n";
      echo "<td colspan='6'>\n";
      $inputFields->dropboxar($statusArray, $status, "status", "edittablebox");
      echo "</td>\n";
      echo "</tr>\n";
    }
    //Equipment
    echo "<tr>\n";
    echo "<td class='edittableleft'>Equipment:</td>\n";
    echo "<td class='edittableright' colspan='6'>\n";
    echo "<select class='edittablebox' name='vehicleid'>\n";
    echo "<option value='0'>No Equipment</option>\n";
    while($vehicleArray=mysql_fetch_array($vehicleResult, MYSQL_NUM))
    {
      echo "<option value='{$vehicleArray[0]}' selected='selected'>{$vehicleArray[2]} {$vehicleArray[1]}</option>\n";
      $lastVehicleID=$vehicleArray[0];
    }
    while($availableVehicleArray = mysql_fetch_array($availableVehicleResult, MYSQL_NUM))
    {
      if(in_array($availableVehicleArray[4], $usedPositionsIDArray))
      {
        echo "<option value='{$availableVehicleArray[0]}'>{$availableVehicleArray[2]} {$availableVehicleArray[1]}</option>\n";
      }
    }
    echo "</select>\n";
    echo "</td>\n";
    echo "</tr>\n";

    //Crew Number
    echo "<tr>\n";
    echo "<td class='edittableleft'>CrewNumber:</td>\n";
    echo "<td class='edittableright' colspan='6'>\n";
    echo "<select class='edittablebox' name='crewnumber'>\n";
    for($i=1;$i<11;$i++)
    {
      if($i==$array[crewnumber])
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

    //Joining Date
    if(isset($_GET['personnel']))
    {
      $date=$array[joiningdate];
    }
    else
    {
      $date=$datesArray[2];
    }
    echo "<tr>\n";
    echo "<td class='edittableleft'>Joining date:</td>\n";
    $inputFields->datebar($date, $endingYear, $startingYear, "year", "month", "day" , false);
    echo "</tr>\n";
    //Notable checkbox
    echo "<tr>\n";
    echo "<td class='edittableleft'>Notable:</td>\n";
    if ($array[notable])
    {
      echo "<td colspan='6'><input name='notable' type='checkbox' checked='checked' /></td>\n";
    }
    else
    {
      echo "<td colspan='6'><input name='notable' type='checkbox' /></td>\n";
    }
    echo "</tr>\n";
    echo "<tr><td colspan='7'><hr /></td></tr>\n";
    //image
    echo "<tr>\n";
    echo "<td class='edittableleft'>Image:</td>\n";
    echo "<td colspan='6'>\n";
    $inputFields->dropboxarscript($personnelimages, $array[image], "image", "edittablebox", "onchange='javascript:change_image(this.value, \"personnelimages\")'", true);
    echo "</td>\n";
    echo "<td><img id='personnelimages' class='unittypeimage' src='./images/personnelimages/{$checkArray[14]}' alt='{$checkArray[14]}' /></td>\n";
    echo "</tr>\n";
    //Notes
    $inputFields->textarea("edittableleft", "edittableright", 6, "Notes", "edittablecommon", "notes", $array[notes]);
    echo "<tr><td colspan='7'><hr /></td></tr>\n";
    echo "<tr>\n";
    echo "<td colspan='7' class='edittablebottom'>\n";
    if ($array[status]!=0)
    {
      echo "<input type='hidden' name='status' value='{$status}' />\n";
    }
    echo "<input type='hidden' name='ID' value='{$crewID}' />\n";
    //echo "<input type='hidden' name='type' value='{$personnelType}' />\n";
    echo "<input type='hidden' name='lastvehicleid' value='{$lastVehicleID}' />\n";
    echo "<input type='hidden' name='QueryType' value='Personnel' />\n";
    echo "<input class='edittablebutton' name='QueryAction' type='submit' value='{$submitButtonText}' />\n";
    if(isset($_GET['personnel']))
    {
      echo "<input class='edittablebutton' name='QueryAction' type='submit' value='Delete' onclick='return confirmSubmit(\"Delete\")' />\n";
    }
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "</form>\n";

    //Skills
    if(isset($_GET['personnel']))
    {
      $usedcounter=0;
      echo "<hr />\n";
      echo "<b>Skills</b>\n";

      for($i=0; $i<sizeof($usedSkills); $i++)
      {
        $skillArray=$usedSkills[$i];

        $usedcounter++;

        echo "<form action='index.php?action=personnelquery' method='post'>\n";
        echo "<table border='0'>\n";
        echo "<tr>\n";
        echo "<td class='edittableleft'>{$skillArray[1]}</td>\n";
        echo "<td>\n";
        $inputFields->dropboxbw($max, $min, $skillArray[2], "skillvalue", "edittablebox");
        echo "</td>\n";
        echo "<td>\n";
        echo "<input type='hidden' name='ID' value='{$skillArray[0]}' />\n";
        echo "<input type='hidden' name='personnel' value='{$array[id]}' />\n";
        echo "<input type='hidden' name='QueryType' value='Skill' />\n";
        echo "<input class='edittablebutton' name='QueryAction' type='submit' value='Change' />\n";
        if(!in_array($skillArray[skillid], $usedSkillsIDArray))
        {
          echo "<input class='edittablebutton' name='QueryAction' type='submit' value='Remove' onclick='return confirmSubmit(\"Remove\")' />\n";
        }
        echo "</td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        echo "</form>\n";

      }
      if($usedcounter==0)
      {
        echo "No Skills\n";
      }

      echo "<form action='index.php?action=personnelquery' method='post'>\n";
      echo "<table border='0'>\n";
      echo "<tr>\n";
      echo "<td class='edittableleft'>Add Skill:</td>\n";
      echo "<td>\n";
      echo "<select class='edittablebox' name='skilltype'>\n";
      $counter=0;
      for($i=0; $i<sizeof($freeSkillArray); $i++)
      {
        $skillArray=$freeSkillArray[$i];
        if($skillArray!=null)
        {
          if(!in_array($skillArray[0], $usedSkills))
          {
            echo "<option value='{$skillArray[0]}'>{$skillArray[1]}</option>\n";
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
      $inputFields->dropboxbw($max, $min, 4, "skillvalue", "edittablebox");
      echo "</td>\n";
      echo "<td>\n";
      echo "<input type='hidden' name='QueryType' value='Skill' />\n";
      echo "<input type='hidden' name='personnel' value='{$array[id]}' />\n";
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

      echo "<hr />\n";
      echo "<b>Positions</b>\n";

      for($i=0; $i<sizeof($positionsArray); $i++)
      {
        echo "<form action='index.php?action=personnelquery' method='post'>\n";
        echo "<table border='0'>\n";
        echo "<tr>\n";
        echo "<td class='edittableleft'>{$positionsArray[$i][type]}</td>\n";
        echo "<td>\n";
        echo "<input type='hidden' name='ID' value='{$positionsArray[$i][id]}' />\n";
        echo "<input type='hidden' name='personnel' value='{$array[id]}' />\n";
        echo "<input type='hidden' name='QueryType' value='Personnelposition' />\n";
        echo "<input class='edittablebutton' name='QueryAction' type='submit' value='Remove' onclick='return confirmSubmit(\"Remove\")' />\n";
        echo "</td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        echo "</form>\n";
      }

      echo "<form action='index.php?action=personnelquery' method='post'>\n";
      echo "<table border='0'>\n";
      echo "<tr>\n";
      echo "<td class='edittableleft'>Add Position:</td>\n";
      echo "<td>\n";
      echo "<select class='edittablebox' name='personneltype'>\n";
      $counter=0;
      for($i=0; $i<sizeof($positionid); $i++)
      {
        echo "<option value='{$positionid[$i]}'>{$positiontype[$i]}</option>\n";
        $counter++;
      }
      if($counter==0)
      {
        echo "<option value=''>No Positions</option>\n";
      }

      echo "</select>\n";
      echo "</td>\n";
      echo "<td>\n";
      echo "<input type='hidden' name='QueryType' value='Personnelposition' />\n";
      echo "<input type='hidden' name='personnel' value='{$array[id]}' />\n";
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
    }
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
  echo "<b>An error has occurred</b> while accessing personnel information.<br />\n";
  echo "No personnel found or you don't have rights to access this personnel's information.<br />\n";
  echo "Please use correct links and be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg,"<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>