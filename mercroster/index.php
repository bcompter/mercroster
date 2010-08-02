<?php
session_start();
if (!isset($_SESSION['initiated']))
{
  session_regenerate_id();
  $_SESSION['initiated']=true;
}

date_default_timezone_set("America/Los_Angeles");

$timerstart=microtime(true);

define('s3Ew4bjJd4f', TRUE);
require("parts/User_Logic.php");

define('Gki58Bdg63v', TRUE);
require("parts/Choose_Logic.php");
?>