<?php
if(!defined('t5hdGh86G'))
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

require("includes/PageBar.php");
$pb = new PageBar;

$id=strip($_GET['contract']);
$first=strip($_GET['first']);

$range=15;
$link="contract&amp;contract={$id}";

//Fetching contracts from database
$contractResult=$dbf->queryselect("SELECT id, start, end, employer, missiontype, target, result, name FROM contracts WHERE id='{$id}';");
$contractArray=mysql_fetch_array($contractResult, MYSQL_NUM);
$cnumber=mysql_num_rows($contractResult);
if($cnumber==1)
{
  //Fetching log data linked to this contract
  if(isset($_SESSION['SESS_TYPE']))
  {
    $readpermission=strip($_SESSION['SESS_TYPE']);
  }
  else
  {
    $readpermission=6;
  }
  $rResult=$dbf->queryselect("SELECT COUNT(*) count FROM logentry r LEFT JOIN logtypes l ON r.logtype=l.id WHERE r.contract='{$id}' AND l.readpermission>={$readpermission}");
  $rnumber=mysql_result($rResult, 0);
  $logResult=$dbf->queryselect("SELECT r.id, r.logtype, r.topic, l.type, r.start FROM logentry r LEFT JOIN logtypes l ON r.logtype=l.id WHERE r.contract='{$id}' AND l.readpermission>={$readpermission} ORDER BY r.start ASC, r.id ASC LIMIT $first, $range;");


  if(checkdates($contractArray[1], $currentGameDateArray[2])==1)
  {
    $begintopic="Begins";
  }
  else
  {
    $begintopic="Began";
  }
  if(checkdates($contractArray[2], $currentGameDateArray[2])==1)
  {
    $endtopic="Ends";
  }
  else
  {
    $endtopic="Ended";
  }
  $begin=$dp->datestring($contractArray[1]);
  $end=$dp->datestring($contractArray[2]);

  echo "<div id='content'>\n";
  echo "<h1 class='headercenter'>{$contractArray[7]}</h1>\n";
  echo "<div class='genericheader'>\n";
  echo "<b>Contract Information</b>\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<='4')
  {
    echo "<a class='genericedit' href='index.php?action=editcontract&amp;contract={$contractArray[0]}'>edit</a>\n";
  }
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
  echo "<td class='generictablecell90'>{$contractArray[3]}</td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td class='generictablecell10'>Type:</td>\n";
  echo "<td class='generictablecell90'>{$contractArray[4]}</td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td class='generictablecell10'>Location:</td>\n";
  echo "<td class='generictablecell90'>{$contractArray[5]}</td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td class='generictablecell10'>Status:</td>\n";
  echo "<td class='generictablecell90'>{$contractArray[6]}</td>\n";
  echo "</tr>\n";
  echo "</table>\n";
  echo "</div>\n";

  if($rnumber>0)
  {
    echo "<h2 class='headercenter'>Contract Related Log Entries</h2>\n";
    $pb->generatebarx($rnumber, $first, $range, $link, "", "", "");
    echo "<hr />\n";

    echo "<table class='rostertable' border='0'>\n";
    echo "<thead class='rostertable'>\n";
    echo "<tr>\n";
    echo "<th class='rosterlogtopic'>Log's Topic</th>\n";
    echo "<th class='rosterlogposter'>Log's Type</th>\n";
    echo "<th class='rosterlogdate'>Game Date</th>\n";
    echo "</tr>\n";
    echo "</thead>\n";
    echo "<tbody class='rostertable'>\n";
    while($array = mysql_fetch_array($logResult, MYSQL_NUM))
    {
      $date=$dp->datestring($array[4]);
      echo "<tr>\n";
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
  else
  {
    echo "<h2 class='headercenter'>No Log Entries</h2>\n";
  }
  echo "</div>\n";
}
else
{
  $error=true;
  $errormsg="Contract not found";
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