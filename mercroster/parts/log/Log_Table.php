<?php
if(!defined('HyG34v5dj4'))
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

require("includes/BBFunctions.php");
$bbf=new BBFunctions;
$first=strip($_GET['first']);
$logType=strip($_GET['type']);

require("includes/PageBar.php");
$pb=new PageBar;

$range=30;

//Fetching log type information
$logTypeResult=$dbf->queryselect("SELECT id, type, writepermission, readpermission FROM logtypes WHERE id='{$logType}';");
if(mysql_num_rows($logTypeResult)==1)
{
  $logidArray=array();
  array_push($logidArray, "0");
  $lastCommentArray=array();
  array_push($lastCommentArray, "0");

  $logTypeA=mysql_fetch_array($logTypeResult, MYSQL_ASSOC); //id, type, writepermission, readpermission

  //$logResult=$dbf->queryselect("SELECT id, start, place, op, opdate, topic, logtype FROM logentry WHERE logtype='{$logType}' ORDER BY start DESC, id DESC LIMIT $first, $range;");
  $logResult=$dbf->queryselect("SELECT r.id, r.start, r.place, r.op, r.opdate, r.topic, r.logtype, max(c.id) AS lastcomment, COUNT(c.id) AS comments, views FROM logentry r LEFT JOIN comments c ON r.id=c.parent WHERE logtype='{$logType}' GROUP BY r.id ORDER BY start DESC, id DESC LIMIT $first, $range;");

  $rResult=$dbf->queryselect("SELECT COUNT(*) count FROM logentry WHERE logtype='$logType';");
  $rnumber=mysql_result($rResult, 0);

  if(!isset($_SESSION['SESS_ID']) && (!isset($_SESSION['SESS_TYPE']) || $_SESSION['SESS_TYPE']==""))
  {
    $permissionlimit=6;
  }
  if(isset($_SESSION['SESS_ID']) && isset($_SESSION['SESS_TYPE']))
  {
    $permissionlimit=$_SESSION['SESS_TYPE'];
    $visitedLogsResult=$dbf->queryselect("SELECT logid, lastcomment FROM logsvisited WHERE logtype='{$logType}' AND member='{$data}' ORDER BY logid ASC;");
    while($array=mysql_fetch_array($visitedLogsResult, MYSQL_ASSOC))
    {
      array_push($logidArray, "{$array[logid]}");
      array_push($lastCommentArray, "{$array[lastcomment]}");
    }
  }

  if($permissionlimit<=$logTypeA[readpermission] && $permissionlimit!=0)
  {
    echo "<div id='content'>\n";
    echo "<h1 class='headercenter'>{$logTypeA[type]}s</h1>\n";
    //We have permission to see these logs
    $permission=$logTypeA[writepermission];
    if($rnumber>0)
    {
      //there is logs to see
      $link="logtable&amp;type={$logType}";
      $add="?action=editlog&amp;type={$logType}";

      echo "<div class='postbartop'>\n";
      $pb->generatebar($rnumber, $first, $range, $link);

      if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<=$permission)
      {
        echo "<div class='postlinks' id='postup'>\n";
        echo "<ul>\n";
        echo "<li><a href='index.php{$add}'>Add {$logTypeA[type]}</a></li>\n";
        echo "</ul>\n";
        echo "</div>\n";
      }

      echo "</div>\n";

      echo "<div class='postouter' style='padding: 3px;'>\n";
      echo "<table class='logtable'>\n";
      echo "<thead class='logtable'>\n";
      echo "<tr>\n";
      if(isset($_SESSION['SESS_ID']) || (trim($_SESSION['SESS_ID'])!=''))
      {
        echo "<th class='logimage'></th>\n";
      }
      echo "<th class='logtopic'>Log Topic</th>\n";
      echo "<th class='logdate'>Game Date</th>\n";
      echo "<th class='logview'>Replies</th>\n";
      echo "<th class='logview'>Views</th>\n";
      echo "<th class='logposter'>Posted by</th>\n";
      echo "</tr>\n";
      echo "</thead>\n";
      echo "<tbody class='rostertable'>\n";
      while($array=mysql_fetch_array($logResult, MYSQL_ASSOC))
      {
        $date=$dp->datestring($array[start]);
        $opdate=$dp->getTime($array[opdate], $offset, $timeformat);
        echo "<tr>\n";
        if(isset($_SESSION['SESS_ID']) || (trim($_SESSION['SESS_ID'])!=''))
        {
          echo "<td class='logimage'>";
          if(array_search($array[id], $logidArray)==false)
          {
            echo "<img src='./images/small/newtopic3.png' alt='olde' />";
          }
          else if($lastCommentArray[array_search($array[id], $logidArray)]<$array[lastcomment])
          {
            echo "<img src='./images/small/oldnewtopic3.png' alt='com' />";
          }
          else
          {
            echo "<img src='./images/small/oldtopic3.png' alt='new' />";
          }
          echo "</td>\n";
        }
        echo "<td class='logtopic'><a class='logtable' href='index.php?action=log&amp;log={$array[id]}&amp;first=lst.0'>{$array[topic]}</a></td>\n";
        echo "<td class='logdate'>{$date}</td>\n";
        echo "<td class='logview'>{$array['comments']}</td>\n";
        echo "<td class='logview'>{$array['views']}</td>\n";
        echo "<td class='logposter'>{$opdate}<br /> by {$array[op]}</td>\n";
        echo "</tr>\n";
      }
      echo "</tbody>\n";
      echo "</table>\n";
      echo "</div>\n";
      echo "<div class='postbartop'>\n";
      $pb->generatebar($rnumber, $first, $range, $link);

      if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<=$permission)
      {
        echo "<div class='postlinks' id='postdown'>\n";
        echo "<ul>\n";
        echo "<li><a href='index.php{$add}'>Add {$logTypeA[type]}</a></li>\n";
        echo "</ul>\n";
        echo "</div>\n";
      }

      echo "</div>\n";
      //$pb->generatebarx($rnumber, $first, $range, $link, $permission, $add, $addtype);
    }
    else
    {
      //there is no logs to see
      echo "<div class='genericheader'>\n";
      echo "<b>Note</b>\n";
      if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<=$permission)
      {
        echo "<a class='genericedit' href='index.php?action=editlog&amp;type={$logType}'>Add new Log entry</a>\n";
      }
      echo "</div>\n";
      echo "<div class='genericarea'>\n";
      echo "No Logs of this Type!\n";
      echo "</div>\n";
    }
    echo "</div>\n";
  }
  else
  {
    $error=true;
    $errormsg="Access denied.";
  }
}
else
{
  $error=true;
  $errormsg="Log Type not found";
}
//Error Handling
if($error)
{
  echo "<div id='content'>\n";
  echo "<div class='error'>\n";
  echo "<b><b>An error has occurred</b></b> while accessing log table.<br />\n";
  echo "No logs found or you don't have rights to access these logs.<br />\n";
  echo "Please use correct links and be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg,"<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>