<?php
if(!defined('V2tyU8lMT'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}

require("htdocs/dbsetup.php");
$first=$_GET['first'];
$first=stripslashes($first);
$first=mysql_real_escape_string($first);

$type=$_SESSION['SESS_TYPE'];

require "includes/PageBar.php";
$pb=new PageBar;
$range=50;

echo "<div id='content'>\n";
echo "<h1 class='headercenter'>Members</h1>\n";

if (!isset ($_SESSION['SESS_ID']) || $_SESSION['SESS_NAME'] == "")
{
  echo "<b>Access Denied</b>";
}
else
{
  $userResult = $dbf->queryselect("SELECT username, sitename, fname, lname, type, lastlogin, postcount, online FROM members ORDER BY type, username LIMIT $first, $range;");
  $rResult = $dbf->queryselect("SELECT COUNT(*) sitename FROM members;");
  $rnumber = mysql_result($rResult, 0);

  $link="users";
  $add="?action=profile";
  $addtype="New User";
  $permission=1;
  $pb->generatebarx($rnumber, $first, $range, $link, $permission, $add, $addtype);

  echo "<hr />\n";
  echo "<table class='usertable'>\n";
  echo "<tr>\n";
  if($type==1)
  {
    echo "<th class='usertable'>Username</th>\n";
  }
  echo "<th class='usertable'>Name</th>\n";
  if($type==1)
  {
    echo "<th class='usertable'>First Name</th>\n";
    echo "<th class='usertable'>Last Name</th>\n";
  }
  echo "<th class='usertable'>Position</th>\n";
  echo "<th class='usertable'>Status</th>\n";
  echo "<th class='usertable'>Post Count</th>\n";
  echo "</tr>\n";
  while ($userArray = mysql_fetch_array($userResult, MYSQL_NUM))
  {
    if($userArray[7]==1)//if user is marked online
    {
      $lastLogTime="<b>Online</b>";//we will tell so
    }
    if($userArray[7]==0)//if user is not marked online
    {
      if($_SESSION['SESS_TYPE']==1)//if user is admin
      {
        if($userArray[5]!="0000-00-00 00:00:00")//if this user has been online
        {
          $lastLogTime="Last seen ".$dp->getTime($userArray[5], $offset, $timeformat);//we will tell when
        }
        else//if not
        {
          $lastLogTime="Never been Online";//we will tell that he hasen't been online ever
        }
      }
      else //if user is not admin
      {
        $lastLogTime="Offline";//we will just tell that this user is ofline
      }
    }
    echo "<tr>\n";
    if($type==1)
    {
      echo "<td class='usertable'>\n";
      echo "<a class='usertable' href='index.php?action=profile&amp;user=$userArray[0]'>$userArray[0]</a>\n";
      echo "</td>\n";
    }
    echo "<td class='usertable'>\n";
    echo "$userArray[1]";
    echo "</td>\n";
    if($type==1)
    {
      echo "<td class='usertable'>\n";
      echo "$userArray[2]";
      echo "</td>\n";
      echo "<td class='usertable'>\n";
      echo "$userArray[3]";
      echo "</td>\n";
    }
    echo "<td class='usertable'>\n";
    switch ($userArray[4])
    {
      case 1:
        echo "Administrator";
        break;
      case 2:
        echo "Game Master";
        break;
      case 3:
        echo "Commander";
        break;
      case 4:
        echo "Player";
        break;
      case 5:
        echo "Spectator";
        break;
    }
    echo "</td>\n";
    echo "<td class='usertable'>\n";
    echo "$lastLogTime";
    echo "</td>\n";
    echo "<td class='usertable'>\n";
    echo "$userArray[6]";
    echo "</td>\n";
    echo "</tr>\n";
  }
  echo "</table>\n";
}
echo "</div>\n";
?>