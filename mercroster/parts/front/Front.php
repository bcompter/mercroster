<?php
if(!defined('Gyu53Hkl3'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}
require("includes/BBFunctions.php");
$bbf=new BBFunctions;

$pageID = $_GET['page'];
$pageID = stripslashes($pageID);
$pageID = mysql_real_escape_string($pageID);

$headerResult=$dbf->queryselect("SELECT name, motto, description, image, services, contact, main FROM command WHERE id='1';");
$headerArray=mysql_fetch_array($headerResult, MYSQL_ASSOC);



if($_GET["action"]=="pages" || !isset($_GET["action"]))
{
	$pageResult=$dbf->queryselect("SELECT text FROM pages WHERE id='{$pageID}';");
	$pageArray=mysql_fetch_array($pageResult, MYSQL_ASSOC);
	$text=nl2br($pageArray[text]);
  	$text=$bbf->addTags($text);
  	echo "<div id='content'>\n";;
  	echo "{$text}\n";
  	echo "</div>\n";
}

if($_GET["action"]=="main" || !isset($_GET["action"]))
{
	$logResult=$dbf->queryselect("SELECT r.id, r.logtype, r.start, r.end, r.place, r.text, r.op, r.opdate, r.le, r.ledate, r.topic, r.opid, c.name FROM logentry r LEFT JOIN logtypes l ON r.logtype=l.id LEFT JOIN contracts c ON r.contract=c.ID WHERE l.readpermission>={$readpermission} ORDER BY r.start DESC, r.opdate DESC, r.id ASC LIMIT 0, 5;");
	while($logArray = mysql_fetch_array($logResult, MYSQL_ASSOC)) {
		//Get date information
	    $date=$dp->datestring($logArray[start]);
	    if($logArray[end]!="")
	    {
	      $date=$date . " - " . $dp->datestring($logArray[end]);
	    }
	    //get location information
	    if($logArray[place]!="")
	    {
	      $at = "at <b>{$logArray[place]}</b>";
	    }
	    //get contract information
	    if($logArray[name]!="")
	    {
	      $during = "during <b>{$logArray[name]}</b>";
	    }
	
	    $originalTime=$dp->getTime($logArray[opdate], $offset, $timeformat);
	    $editTime=$dp->getTime($logArray[ledate], $offset, $timeformat);
	
	    $text=nl2br($logArray[text]);
	    $text=str_replace("&", "&amp;", $text);
	    $text=$bbf->addTags($text);
	
	    echo "<div id='content'>\n";
	    echo "<div class='post'>\n";
	    echo "<div class='postheader'>\n";	
	    echo "<span class='postheader'><a class='newstable' href='index.php?action=news&amp;log=$logArray[id]&amp;first=0'>{$logArray[topic]}</a></span><br />\n";
	    echo "<b>$date</b> {$at} {$during}\n";
	    echo "</div>\n";
	    echo "<div class='posttext'>\n";
	    echo "{$text}\n";
	    if(isset($logArray[opid]))
	    {
	      echo "<br />\n";
	      echo "<small>Last edit by {$logArray[opid]} on {$editTime}</small>\n";
	    }
	    echo "</div>\n";
	    //footer for original post
	    echo "<div class='postfooter'>\n";
	    echo "Posted by {$logArray[op]} on {$originalTime}\n";
	    echo "</div>\n";
	    echo "</div>\n";
	    echo "</div>\n";
		/*
		echo "<div id='content'>\n";
		echo "<div class='genericarea'>\n";
			echo "{$array[topic]}";
		echo "</div>\n";
		echo "</div>\n";
		*/
		
	}
}

?>