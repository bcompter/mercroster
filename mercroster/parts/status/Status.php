<?php
if(!defined('F5gf47gDc'))
{
  header('HTTP/1.0 404 not found');
  include("../error404.html");
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

/**
 * Funtion used to check whaetever first date is earlier than last
 */
function checkdates($first, $last)
{
  $checkedYear=strtok($first, "-");
  $checkedMoth=strtok("-");
  $checkedDay=strtok("-");

  $currentYear=strtok($last, "-");
  $currentMoth=strtok("-");
  $currentDay=strtok("-");

  $rbool=0;
  if($checkedYear>$currentYear || ($checkedYear==$currentYear && $checkedMoth>$currentMoth) || ($checkedYear==$currentYear && $checkedMoth==$currentMoth && $checkedDay>$currentDay))
  {
    $rbool=1;
  }
  return $rbool;
}

if(isset($_GET['first']))
{
  $first=strip($_GET['first']);
}
else
{
  $first=0;
}
$range=15;

require("includes/BBFunctions.php");
$bbf=new BBFunctions;

require("includes/PageBar.php");
$pb=new PageBar;

if(isset($favunit) && $favunit!="" && $favunit!=0)
{
  $unitResult=$dbf->queryselect("SELECT u.id, u.name, u.limage, u.rimage, u.parent, u.level, u.text, ut.name AS unittype FROM unit u, unittypes ut WHERE u.type=ut.id AND u.id='$favunit';");
  $unitArray=mysql_fetch_array($unitResult, MYSQL_ASSOC);

  $parentUnitResult=$dbf->queryselect("SELECT id, name FROM unit WHERE id='$unitArray[parent]';");
  $parentUnitArray=mysql_fetch_array($parentUnitResult, MYSQL_ASSOC);

  //$personnelResult=$dbf->queryselect("SELECT c.id, c.lname, c.fname, v.name, v.subtype, r.rankname, gunnery.value AS gunnery, piloting.value AS piloting FROM crew c LEFT JOIN equipment v ON c.id=v.crew LEFT JOIN skills gunnery ON c.id=gunnery.person LEFT JOIN skills piloting ON c.id=piloting.person, ranks r, skilltypes st1, skilltypes st2 WHERE c.parent='$favunit' AND r.number=c.rank AND gunnery.skill=st1.id AND st1.shortname='Gunnery' AND piloting.skill=st2.id AND st2.shortname='Piloting' ORDER BY c.rank DESC, c.joiningdate ASC, c.lname ASC, c.id ASC;");
  $personnelResult=$dbf->queryselect("SELECT c.id, c.lname, c.fname, v.name, v.subtype, et.requirement, r.rankname FROM crew c LEFT JOIN equipment v ON c.id=v.crew LEFT JOIN equipmenttypes et ON v.type=et.id, ranks r WHERE c.parent='$favunit' AND r.number=c.rank ORDER BY c.rank DESC, c.joiningdate ASC, c.lname ASC, c.id ASC;");
  $personnelArray=$dbf->resulttoarray($personnelResult);
  $personnelArraySize=sizeof($personnelArray);
  for ($i=0; $i<sizeof($personnelArray); $i++)
  {
    $tempAr=$personnelArray[$i];
    $tempResult=$dbf->queryselect("SELECT s.value, st.shortname, st.name, pt.type FROM skills s LEFT JOIN skilltypes st ON s.skill=st.id LEFT JOIN skillrequirements sr ON st.id=sr.skilltype LEFT JOIN crewtypes pt ON pt.id=sr.personneltype WHERE s.person='{$tempAr[id]}' AND pt.id='{$tempAr[requirement]}' GROUP BY st.name;");
    while($tempArray=mysql_fetch_array($tempResult, MYSQL_ASSOC))
    {
      $skillArray=array("{$tempArray[shortname]}" => $tempArray[value]);
      //echo "Test:{$skillArray[Piloting]}\n";
      $tempAr=array_merge($tempAr, $skillArray);
    }
    $personnelArray[$i]=$tempAr;
    //echo "{$tempAr[lname]} Gunnery:{$tempAr[Gunnery]}/Piloting:{$tempAr[Piloting]}\n";
  }
}

$link="contract&amp;contract={$id}&amp;number={$number}";

//Fetching contracts from database
$contractResult = $dbf->queryselect("SELECT id, start, end, employer, missiontype, target, result, name FROM contracts ORDER BY start DESC LIMIT 1;");
$contractArray = mysql_fetch_array($contractResult, MYSQL_ASSOC);

//Fetching log data linked to this contract
if(isset($_SESSION['SESS_TYPE']))
{
  $readpermission=strip($_SESSION['SESS_TYPE']);
}
else
{
  $readpermission=6;
}
$rResult=$dbf->queryselect("SELECT COUNT(*) count FROM logentry r LEFT JOIN logtypes l ON r.logtype=l.id WHERE r.contract='{$contractArray[id]}' AND l.readpermission>={$readpermission};");
$rnumber = mysql_result($rResult, 0);
if($rnumber>0)
{
  $logResult=$dbf->queryselect("SELECT r.id, r.logtype, r.topic, l.type, r.start, max(c.id) AS lastcomment FROM logentry r LEFT JOIN logtypes l ON r.logtype=l.id LEFT JOIN comments c ON r.id=c.parent WHERE r.contract='{$contractArray[id]}' AND l.readpermission>={$readpermission} GROUP BY r.id ORDER BY r.start DESC, r.id DESC LIMIT {$first}, {$range};");
  //$logResult=$dbf->queryselect("SELECT r.id, r.start, r.place, r.op, r.opdate, r.topic, r.logtype, max(c.id) AS lastcomment FROM logentry r LEFT JOIN comments c ON r.id=c.parent WHERE logtype='{$logType}' GROUP BY r.id ORDER BY start DESC, id DESC LIMIT $first, $range;");
}

if(checkdates($contractArray[start], $currentGameDateArray[2])==1)
{
  $begintopic="Begins";
}
else
{
  $begintopic="Began";
}
if(checkdates($contractArray[end], $currentGameDateArray[2])==1)
{
  $endtopic="Ends";
  $contracttopic="Current";
}
else
{
  $endtopic="Ended";
  $contracttopic="Last";
}
$begin = $dp->datestring($contractArray[start]);
$end = $dp->datestring($contractArray[end]);

echo"<div id='content'>\n";

if(isset($favunit) && $favunit!="" && $favunit!=0)
{
  echo "<div class='genericheader'>\n";
  echo "<b>{$parentUnitArray[name]} / {$unitArray[name]}</b>\n";
  echo "</div>\n";

  echo "<div class='genericarea'>\n";

  if(intval($unitArray[rimage])!=0)
  {
    echo "<div class='unitimage'>\n";
    echo '<img src="includes/image.php?id='.$unitArray[rimage].'" alt="'.$unitArray[rimage].'" />';
    echo "\n";
    echo "</div>\n";
    echo "<div class='unittableright'>\n";
  }
  else
  {
    echo "<div class='unittableleft'>\n";
  }
  echo "<table class='unittable' border='0'>\n";
  //unit Type
  echo "<tr>\n";
  echo "<th class='unittablecell'><b>Unit type:</b></th>\n";
  echo "<td class='unittablecell'>{$unitArray[unittype]}</td>\n";
  echo "</tr>\n";
  if($parentUnitArray!="" && $parentUnitArray!=null)
  {
    //parent unit
    echo "<tr>\n";
    echo "<th class='unittablecell'><b>Attached to:</b></th>\n";
    echo "<td class='unittablecell'><a class='personnellink' href='index.php?action=unit&amp;unit={$parentUnitArray[id]}'>{$parentUnitArray[name]}</a></td>\n";
    echo "</tr>\n";
  }
  //Personnel
  echo "<tr>\n";
  echo "<th class='unittablecell'><b>Personnel:</b></th>\n";
  echo "<th class='left'>Rank</th>\n";
  echo "<th class='left'>Name</th>\n";
  echo "<th class='center'>Gunnery</th>\n";
  echo "<th class='center'>Piloting</th>\n";
  echo "<th class='left'>Equipped</th>\n";

  echo "</tr>\n";
  for ($i=0; $i<$personnelArraySize; $i++)
  {
    echo "<tr>\n";
    echo "<td></td>\n";
    $temp=$personnelArray[$i];
    echo"<td class='left'>{$temp[rankname]}</td>\n";
    echo"<td class='left'><a class='personnellink' href='index.php?action=personnel&amp;personnel={$temp[id]}'>{$temp[fname]} {$temp[lname]}</a></td>\n";
    echo"<td class='center'>{$temp[Gunnery]}</td>\n";
    //if($temp[ispiloting])
    //{
    echo"<td class='center'>{$temp[Piloting]}</td>\n";
    //}
    //else
    //{
    //echo"<td class='center'></td>\n";
    //}
    echo"<td class='left'>{$temp[subtype]} {$temp[name]}</td>\n";
    echo "</tr>\n";
  }
  echo "</table>\n";
  echo "</div>\n";
  if($unitArray[text]!="" && $unitArray[text]!=null)
  {
    echo "<div class='unitnotes'>\n";
    //Notes
    $text=nl2br($unitArray[text]);
    $text=$bbf->addTags($text);
    echo "<b>Notes:</b><br />\n";
    echo "$text\n";
    echo "</div>\n";
  }
  echo "</div>\n";
  echo "<br />\n";
}

//Current Contract information
echo "<div class='genericheader'>\n";
echo "{$contracttopic} contract: <b>{$contractArray[name]}</b>\n";
echo "</div>\n";
echo "<div class='genericarea'>\n";
echo "<table class='generictable' border='0'>\n";
echo "<tr>\n";
echo "<td class='generictablecell10'>{$begintopic}:</td>\n";
echo "<td class='generictablecell90'>{$begin}</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td class='generictablecell10'>{$endtopic}:</td>\n";
echo "<td class='generictablecell90'>{$end}</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td class='generictablecell10'>Employer:</td>\n";
echo "<td class='generictablecell90'>{$contractArray[employer]}</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td class='generictablecell10'>Type:</td>\n";
echo "<td class='generictablecell90'>{$contractArray[missiontype]}</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td class='generictablecell10'>Location:</td>\n";
echo "<td class='generictablecell90'>{$contractArray[target]}</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td class='generictablecell10'>Status:</td>\n";
echo "<td class='generictablecell90'>{$contractArray[result]}</td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</div>\n";

if($rnumber>0)
{
  $logidArray=array();
  array_push($logidArray, "0");
  $lastCommentArray=array();
  array_push($lastCommentArray, "0");

  if(isset($_SESSION['SESS_ID']) && isset($_SESSION['SESS_TYPE']))
  {
    $visitedLogsResult=$dbf->queryselect("SELECT logid, lastcomment FROM logsvisited WHERE member='{$data}' ORDER BY logid ASC;");
    while($array=mysql_fetch_array($visitedLogsResult, MYSQL_ASSOC))
    {
      array_push($logidArray, "{$array[logid]}");
      array_push($lastCommentArray,  "{$array[lastcomment]}");
    }
  }

  $range=15;
  $link="status";

  echo "<br />\n";
  //echo "<h2 class='headercenter'>Contract Related Log Entries</h2>\n";
  $pb->generatebarx($rnumber, $first, $range, $link, "", "", "");
  echo "<hr />\n";

  echo "<table class='rostertable' border='0'>\n";
  echo "<thead class='rostertable'>\n";
  echo "<tr>\n";
  if(isset($_SESSION['SESS_ID']) || (trim($_SESSION['SESS_ID'])!=''))
  {
    echo "<th class='rosterlogimage'></th>\n";
  }
  echo "<th class='rosterlogtopic'>Log Topic</th>\n";
  echo "<th class='rosterlogposter'>Log Type</th>\n";
  echo "<th class='rosterlogdate'>Game Date</th>\n";
  echo "</tr>\n";
  echo "</thead>\n";
  echo "<tbody class='rostertable'>\n";
  while($array = mysql_fetch_array($logResult, MYSQL_NUM))
  {
    $date=$dp->datestring($array[4]);
    echo "<tr>\n";
    if(isset($_SESSION['SESS_ID']) || (trim($_SESSION['SESS_ID'])!=''))
    {
      echo "<td class='rosterlogimage'>";
      if(array_search($array[0], $logidArray)==false)
      {
        echo "<img src='./images/small/newtopic3.png' alt='olde' />";
      }
      else if($lastCommentArray[array_search($array[0], $logidArray)]<$array[5])
      {
        echo "<img src='./images/small/oldnewtopic3.png' alt='com' />";
      }
      else
      {
        echo "<img src='./images/small/oldtopic3.png' alt='new' />";
      }
      echo "</td>\n";
    }
    echo "<td class='rosterlogtopic'><a class='rostertable' href='index.php?action=log&amp;log={$array[0]}&amp;first=lst.0'>{$array[2]}</a></td>\n";
    echo "<td class='rosterlogposter'>{$array[3]}</td>\n";
    echo "<td class='rosterlogdate'>{$date}</td>\n";
    echo "</tr>\n";
  }
  echo "</tbody>\n";
  echo "</table>\n";
  echo "<hr />\n";
  $pb->generatebarx($rnumber, $first, $range, $link, "", "", "");
}



echo"</div>\n";