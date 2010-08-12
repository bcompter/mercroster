<?php
if(!defined('s3Ew4bjJd4f'))
{
  header('HTTP/1.0 404 not found');
  include("../error404.html");
  exit;
}

require("includes/DBFunctions.php");
$dbf=new DBFunctions;
require("includes/DateFunctions.php");
$dp=new DateFunctions;

require("includes/UserFunctions.php");
$userfuntions=new UserFunctions();
require("includes/GuestFunctions.php");
$guestfuntions=new GuestFunctions();

require("htdocs/appsetup.php");

//check for cookie login
if(!isset($_SESSION['SESS_ID']) && isset($_COOKIE['mrrinf']))
{
  $userfuntions->persistentcheck($dbf);
}

$ip=isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
require("htdocs/dbsetup.php");
$ip=stripslashes($ip);
$ip=mysql_real_escape_string($ip);

$guestfuntions->CheckLogged($dbf);
$guests=$guestfuntions->GetGuestNumber($dbf);

$offset=0;
$timeformat="Y-m-d H:i:s";
if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_ID']!="" && isset($_SESSION['SESS_TYPE']))
{
  $userfuntions->setRegistered(1);

  //get user preferences from database
  require("htdocs/dbsetup.php");
  $data=stripslashes($_SESSION['SESS_ID']);
  $data=mysql_real_escape_string($data);
  $userResult=$dbf->queryselect("SELECT timeoffset, timeformat, sitename, favoredunit FROM members WHERE id='{$data}';");
  $timesets=mysql_fetch_array($userResult, MYSQL_NUM);

  //set time settings
  $offset=$timesets[0];
  $timeformat=$timesets[1];
  $favunit=$timesets[3];

  //update las login time to database
  $userfuntions->updateUserTimes($dbf);

  //highest log id seen
  $lastVisitedTopicResult=$dbf->queryselect("SELECT logtype, lasttopic FROM lastlog WHERE member='{$data}' ORDER BY logtype ASC;");
  $userfuntions->updateLogArrays($data, $dbf);
}
else
{
  $path=$sitepath;
  $refe="";
  $refe=getenv('HTTP_REFERER');
  $guestfuntions->HandleGuest($ip, $dbf, $refe, $path);
}

$users=$userfuntions->checkUsers($dbf);


//Fetching used dates data
$currentGameDateResult=$dbf->queryselect("SELECT * FROM dates WHERE id=1;");
$currentGameDateArray=mysql_fetch_array($currentGameDateResult, MYSQL_NUM);
$currentDate=$currentGameDateArray[2];
$currentGameDate=$dp->datestring($currentGameDateArray[2]);

//Fetching used dates data
$commandResult=$dbf->queryselect("SELECT icon, abbreviation, header FROM command WHERE id=1;");
$commandArray=mysql_fetch_array($commandResult, MYSQL_NUM);
$commandIcon=$commandArray[0];
$commandAbb=$commandArray[1];
$commandHeader=$commandArray[2];
?>