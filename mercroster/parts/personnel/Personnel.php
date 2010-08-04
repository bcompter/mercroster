<?php
if(!defined('F3xVH894Vdsv'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}

require("includes/BBFunctions.php");
$bbf = new BBFunctions;

require("htdocs/dbsetup.php");
$personnelID = $_GET['personnel'];
$personnelID = stripslashes($personnelID);
$personnelID = mysql_real_escape_string($personnelID);

function age($BDay, $CDay)
{
  $bdate=$BDay;
  $byear=strtok($bdate, "-");
  $bmoth=strtok("-");
  $bday=strtok("-");

  $cdate=$CDay;
  $cyear=strtok($cdate, "-");
  $cmoth=strtok("-");
  $cday=strtok("-");

  $age=$cyear-$byear;
  if($bmoth>$cmoth)
  {
    $age=$age-1;
  }
  if($bmoth==$cmoth && $bday>$cday)
  {
    $age=$age-1;
  }
  return $age;
}

//Need to determine what type of roster to fecth from database
$personnelResult = $dbf->queryselect("SELECT c.id, r.rankname, c.lname, c.fname, c.callsign, c.joiningdate, c.notes, v.subtype, v.name as equipmentname, u.name as unitname, c.bday, c.status, u.id as unitid, v.id as equipmentid, c.image, c.crewnumber FROM crew c LEFT JOIN ranks r ON c.rank=r.number LEFT JOIN equipment v ON c.id=v.crew LEFT JOIN unit u ON c.parent=u.id WHERE c.id='$personnelID';");
if(mysql_num_rows($personnelResult)==1)
{
  //Fetching used dates data
  $datesResult = $dbf->queryselect("SELECT * FROM dates WHERE id=1;");
  $datesArray = mysql_fetch_array($datesResult, MYSQL_NUM);

  $personnelArray = mysql_fetch_array($personnelResult, MYSQL_BOTH);
  
  //$crewtypeResult = $dbf->queryselect("SELECT * FROM crewtypes WHERE id='$personnelArray[type]';");
  //$crewtypeArray = mysql_fetch_array($crewtypeResult, MYSQL_NUM);

  $personnelSkillResult=$dbf->queryselect("SELECT st.name, s.value FROM skills s, skilltypes st WHERE s.skill=st.id AND s.person='{$personnelArray[id]}' ORDER BY st.name ASC;");

  //Fetch special abilities
  $usedAbilities=$dbf->resulttoarray($dbf->queryselect("SELECT a.id, t.name, a.notes, a.ability, t.id AS abilityid FROM abilities a, abilitytypes t WHERE a.person='{$personnelArray[id]}' AND a.ability=t.id;"));
        
  //Fetch kills from database
  $killsRetVal = $dbf->queryselect("SELECT parent, type, KillDate FROM kills;");
  $i=0;
  while($killsArray[$i] = mysql_fetch_array($killsRetVal, MYSQL_NUM))
  {
    $i++;
  }
  mysql_free_result($killsRetVal);

  echo "<div id='content'>\n";
  echo "<div class='genericheader'>\n";
  echo "<b>Personnel Information</b>\n";
  if($action!="units" && $action!="notable" && isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<='4')
  {
    echo "<a class='genericedit' href='index.php?action=editpersonnel&amp;personnel={$personnelArray[id]}'>edit</a>\n";
  }
  echo "</div>\n";
  echo "<div class='genericarea'>\n";
  if($personnelArray[image]!="" && $personnelArray[image]!=null)
  {
    echo "<div class='unitimage'>\n";
    echo "<img class='unitlogoimage' src='./images/personnelimages/{$personnelArray[image]}' alt='{$personnelArray[image]}' />\n";
    echo "</div>\n";
    echo "<div class='unittableright'>\n";
  }
  else
  {
    echo "<div class='unittableleft'>\n";
  }
  echo "<table class='generictable' border='0'>\n";
  //Last Name
  echo "<tr>\n";
  echo "<td class='generictablecell15'><b>First Name:</b></td>\n";
  echo "<td class='generictablecell85' colspan='2'>{$personnelArray[fname]}</td>\n";
  echo "</tr>\n";
  //First Name
  echo "<tr>\n";
  echo "<td class='generictablecell15'><b>Last Name:</b></td>\n";
  echo "<td class='generictablecell85' colspan='2'>{$personnelArray[lname]}</td>\n";
  echo "</tr>\n";
  //Rank
  echo "<tr>\n";
  echo "<td class='generictablecell15'><b>Rank:</b></td>\n";
  echo "<td class='generictablecell85' colspan='2'>{$personnelArray[rankname]}</td>\n";
  echo "</tr>\n";
  //Call Sign
  echo "<tr>\n";
  echo "<td class='generictablecell15'><b>Callsign:</b></td>\n";
  echo "<td class='generictablecell85' colspan='2'>{$personnelArray[callsign]}</td>\n";
  echo "</tr>\n";
  //Age
  $BDay=$personnelArray[bday];
  $CDay=$datesArray[2];
  $Age=age($BDay, $CDay);
  echo "<tr>\n";
  echo "<td class='generictablecell15'><b>Age:</b></td>\n";
  echo "<td class='generictablecell85' colspan='2'>{$Age}</td>\n";
  echo "</tr>\n";
  //Status
  $Status=$personnelArray[status];
  echo "<tr>\n";
  echo "<td class='generictablecell15'><b>Status:</b></td>\n";
  echo "<td class='generictablecell85' colspan='2'>{$Status}</td>\n";
  echo "</tr>\n";

  while($skillArray =  mysql_fetch_array($personnelSkillResult, MYSQL_ASSOC))
  {
    echo "<tr>\n";
    echo "<td class='generictablecell15'><b>{$skillArray[name]}:</b></td>\n";
    echo "<td class='generictablecell85' colspan='2'>{$skillArray[value]}</td>\n";
    echo "</tr>\n";
  }
  //special abilities
  $string="";
  for($i=0; $i<sizeof($usedAbilities); $i++)
  {
  	$abilityArray=$usedAbilities[$i];
  	if($i==0)
    {
       $string = $string.$abilityArray[1];
    }
    else
    {
       $string = $string.", ".$abilityArray[1];
    }
    if($abilityArray[2]!="") {
       	$string = $string." (".$abilityArray[2].")";
    }
  }
  if($i>0)
  {
    echo "<tr>\n";
    echo "<td class='generictablecell15'><b>Abilities:</b></td>\n";
    echo "<td class='generictablecell85'>{$string}</td>\n";
    echo "</tr>\n";
  }
  
  //Equipment
  echo "<tr>\n";
  echo "<td class='generictablecell15'><b>Equipment:</b></td>\n";
  if($action!="notable")
  {
   	echo "<td class='generictablecell85' colspan='2'><a class='personnellink' href='index.php?action=equipment&amp;equipment={$personnelArray[equipmentid]}'>{$personnelArray[subtype]} {$personnelArray[equipmentname]}</a></td>\n";
  }
  else
  {
    echo "<td class='generictablecell85' colspan='2'>{$personnelArray[subtype]} {$personnelArray[equipmentname]}</td>\n";
  }
  echo "</tr>\n";
  //Squad
  if($personnelArray[crewnumber]>1)
  {
    echo "<tr>\n";
    echo "<td class='generictablecell15'><b>Squad Size:</b></td>\n";
    echo "<td class='generictablecell85' colspan='2'>{$personnelArray[crewnumber]}</td>\n";
    echo "</tr>\n";
  }
  //Assigned unit
  echo "<tr>\n";
  echo "<td class='generictablecell15'><b>Assigned to:</b></td>\n";
  if($action!="notable")
  {
    echo "<td class='generictablecell85' colspan='2'><a class='personnellink' href='index.php?action=unit&amp;unit={$personnelArray[unitid]}'>{$personnelArray[unitname]}</a></td>\n";
  }
  else
  {
    echo "<td class='generictablecell85' colspan='2'><a class='personnellink' href='index.php?action=units&amp;unit={$personnelArray[unitid]}'>{$personnelArray[unitname]}</a></td>\n";
  }
  echo "</tr>\n";
  //Joining Date
  $date = $dp->datestring($personnelArray[joiningdate]);
  echo "<tr>\n";
  echo "<td class='generictablecell15'><b>Joined:</b></td>\n";
  echo "<td class='generictablecell85' colspan='2'>{$date}</td>\n";
  echo "</tr>\n";
  //Kills list
  $string="";
  $i=0;
  foreach ($killsArray as $value)
  {
    if($value[0]==$personnelArray[id])
    {
      if($i==0)
      {
        $string = $string." ".$value[1];
      }
      else
      {
        $string = $string.", ".$value[1];
      }
      $i++;
    }
  }
  if($i>0)
  {
    echo "<tr>\n";
    echo "<td class='generictablecell15'><b>Kills:</b></td>\n";
    echo "<td class='generictablecell85'><b>{$i}</b>: {$string}</td>\n";
    echo "</tr>\n";
  }
  echo "</table>\n";
  echo "</div>\n";
  //Notes
  if($personnelArray[notes]!="" && $personnelArray[notes]!=null)
  {
    echo "<div class='unitnotes'>\n";
    $text=nl2br($personnelArray[notes]);
    $text=$bbf->addTags($text);
    echo "<b>Notes:</b><br />\n";
    echo "$text\n";
    echo "</div>\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
else
{
  $error=true;
  $errormsg="Personnel not found";
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