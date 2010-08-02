<?php
if(!defined('gHsb64G7jjT'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}
require("htdocs/dbsetup.php");
$first=$_GET['first'];
$first=stripslashes($first);
$first=mysql_real_escape_string($first);

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
$range=5;
$add="?action=editcontract";
$addtype="Contract";
$permission=5;
$link="contracttable";

//Fetching contracts from database
$contractsRetVal = $dbf->queryselect("SELECT id, start, end, employer, missiontype, target, result, name FROM contracts ORDER BY start DESC LIMIT $first, $range;");
$rResult = $dbf->queryselect("SELECT COUNT(*) count FROM contracts;");
$rnumber = mysql_result($rResult, 0);

echo "<div id='content'>\n";
echo "<h1 class='headercenter'>Contracts</h1>\n";

if($rnumber>0)
{
  $pb->generatebarx($rnumber, $first, $range, $link, $permission, $add, $addtype);
  echo "<hr />\n";
  $index = $rnumber-$first;
  while($contractsArray = mysql_fetch_array($contractsRetVal, MYSQL_NUM))
  {
    if(checkdates($contractsArray[1], $currentGameDateArray[2])==1)
    {
      $begintopic="Begins";
    }
    else
    {
      $begintopic="Began";
    }
    if(checkdates($contractsArray[2], $currentGameDateArray[2])==1)
    {
      $endtopic="Ends";
    }
    else
    {
      $endtopic="Ended";
    }

    $begin = $dp->datestring($contractsArray[1]);
    $end = $dp->datestring($contractsArray[2]);
    echo "<div class='genericheader'>\n";
    echo "Contract <a class='generictableedit' href='index.php?action=contract&amp;contract={$contractsArray[0]}&amp;first=0'><b>{$contractsArray[7]}</b></a> (Contract #{$index})\n";
    if(isset($_SESSION['SESS_NAME']) && $_SESSION['SESS_TYPE']<'5')
    {
      echo "<a class='genericedit' href='index.php?action=editcontract&amp;contract={$contractsArray[0]}'>edit</a>\n";
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
    echo "<td class='generictablecell90'>{$contractsArray[3]}</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class='generictablecell10'>Type:</td>\n";
    echo "<td class='generictablecell90'>{$contractsArray[4]}</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class='generictablecell10'>Location:</td>\n";
    echo "<td class='generictablecell90'>{$contractsArray[5]}</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class='generictablecell10'>Status:</td>\n";
    echo "<td class='generictablecell90'>{$contractsArray[6]}</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "</div>\n";
    $index--;
  }
  echo "<hr />\n";
  $pb->generatebarx($rnumber, $first, $range, $link, $permission, $add, $addtype);
}
else
{
  echo "<div class='genericheader'>\n";
  echo "<b>Note</b>\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<=$permission)
  {
    echo "<a class='genericedit' href='index.php?action=editcontract'>Add new Contract</a>\n";
  }
  echo "</div>\n";
  echo "<div class='genericarea'>\n";
  echo "No Contracts!\n";
  echo "</div>\n";
}
echo "</div>\n";
?>