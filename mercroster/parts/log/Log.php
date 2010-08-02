<?php
if(!defined('gt5fhsb64'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
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
 * Funtion used to get Zulu time
 */
function getZTime()
{
  $time=date("Y-m-d H:i:s", time()-date("Z",time()));
  return $time;
}

require("includes/BBFunctions.php");
$bbf=new BBFunctions;

$logID=strip($_GET['log']);

if(isset($_SESSION['SESS_TYPE']))
{
  $readpermission=strip($_SESSION['SESS_TYPE']);
}
else
{
  $readpermission=6;
}

$accessResult=$dbf->queryselect("SELECT l.readpermission, l.writepermission FROM logentry r LEFT JOIN logtypes l ON r.logtype=l.id WHERE r.id='{$logID}';");
if(mysql_num_rows($accessResult)==1)
{
  $accessArray=mysql_fetch_array($accessResult, MYSQL_NUM);

  if($readpermission<=$accessArray[0])
  {
    require("includes/PageBar.php");
    $pb=new PageBar;
    $range=15;

    $logResult=$dbf->queryselect("SELECT r.id, r.logtype, r.start, r.end, r.place, r.text, r.op, r.opdate, r.le, r.ledate, c.name, r.topic, r.opid FROM logentry r LEFT JOIN contracts c ON r.contract=c.ID WHERE r.id='{$logID}';");
    $logArray=mysql_fetch_array($logResult, MYSQL_NUM) or die("Error retrieving log table.");

    if($userfuntions->isregisterd()==1)
    {
      $lastLogResult=$dbf->queryselect("SELECT lastlogvisitedtime, lastlogvisited FROM members WHERE id='{$data}';");
      $lastLogArray=mysql_fetch_array($lastLogResult, MYSQL_ASSOC);
      $lastLoginTime=$lastLogArray['lastlogvisitedtime'];
      $lastLog=$lastLogArray['lastlogvisited'];
      $lastLoginTime=strtotime($lastLoginTime);
      $now=time()-date("Z",time());
      $differece=$now-$lastLoginTime;
      if($lastLog!=$logID || $differece>60)
      {
        $now=getZTime();
        $queryArray[0]=("UPDATE logentry SET views=views+1 WHERE id='{$logID}';");
        $queryArray[sizeof($queryArray)]=("UPDATE members SET lastlogvisitedtime='{$now}', lastlogvisited='{$logID}' WHERE id='{$data}';");
        $dbf->queryarray($queryArray);
      }
    }
    else
    {
      $lastLogResult=$dbf->queryselect("SELECT lastlogvisitedtime, lastlogvisited FROM guests WHERE ipaddress=INET_ATON('{$ip}');");
      $lastLogArray=mysql_fetch_array($lastLogResult, MYSQL_ASSOC);
      $lastLoginTime=$lastLogArray['lastlogvisitedtime'];
      $lastLog=$lastLogArray['lastlogvisited'];
      $lastLoginTime=strtotime($lastLoginTime);
      $now=time()-date("Z",time());
      $differece=$now-$lastLoginTime;
      if($lastLog!=$logID || $differece>60)
      {
        $now=getZTime();
        $queryArray[0]=("UPDATE logentry SET views=views+1 WHERE id='{$logID}';");
        $queryArray[sizeof($queryArray)]=("UPDATE guests SET lastlogvisitedtime='{$now}', lastlogvisited='{$logID}' WHERE ipaddress=INET_ATON('{$ip}');");
        $dbf->queryarray($queryArray);
      }
    }


    $commentsnumberResult=$dbf->queryselect("SELECT COUNT(*) count FROM comments WHERE parent='$logID';");
    $commentsnumber=mysql_result($commentsnumberResult, 0);

    //$logTypeResult=$dbf->queryselect("SELECT * FROM logtypes WHERE id='{$logArray[1]}';");
    //$logTypeArray=mysql_fetch_array($logTypeResult, MYSQL_NUM);

    $firstcommentarray=explode(".", $_GET['first']);
    $firstcommenttype=strip($firstcommentarray[0]);
    $firstcommentid=strip($firstcommentarray[1]);
    $firstcomment=0;
    if($firstcommenttype=="end")
    {
      $firstcomment=$commentsnumber-($commentsnumber%$range);
    }
    if($firstcommenttype=="lst")
    {
      $firstcomment=$firstcommentid;
    }
    if($firstcommenttype=="msg")
    {
      $commentNumberResult=$dbf->queryselect("SELECT rownum FROM (SELECT @rownum:=@rownum+1 rownum, c.id FROM (SELECT @rownum:=0) r, comments c, logentry le WHERE le.id='{$logID}' AND c.parent=le.id) AS temp WHERE id='{$firstcommentid}';");
      if(mysql_num_rows($accessResult)==1)
      {
        $commentNumber=mysql_result($commentNumberResult, 0)-1;
        $firstcomment=(($commentNumber-($commentNumber%15)));
      }
      else
      {
        $firstcomment=0;
      }
    }
    $commentResult=$dbf->queryselect("SELECT * FROM comments WHERE parent='$logID' LIMIT $firstcomment, $range;");

    $date=$dp->datestring($logArray[2]);
    if($logArray[3]!="")
    {
      $date=$date . " - " . $dp->datestring($logArray[3]);
    }
    //get location information
    if($logArray[4]!="")
    {
      $at = "at <b>{$logArray[4]}</b>";
    }
    //get contract information
    if($logArray[10]!="")
    {
      $during = "during <b>{$logArray[10]}</b>";
    }
    $originalTime=$dp->getTime($logArray[7], $offset, $timeformat);
    $editTime=$dp->getTime($logArray[9], $offset, $timeformat);

    $link="log&amp;log=$logID";
    //$permission=5;
    $add="?action=editcomment&amp;logid={$logID}&amp;logtype={$logArray[1]}";
    $addtype="Comment";

    $permission=$accessArray[1];
    //$text=nl2br($logArray[5]);

    $text=$logArray[5];
    //$text=preg_replace("#(&(?!amp;))#U",'&amp;',$text);
    //$text=htmlspecialchars($text);
    $text=$bbf->addTags($text);
    $text=nl2br($text);
    $search=array("<hr /><br />", "</h1><br />", "</h2><br />", "</h3><br />", "</h4><br />", "</h5><br />", "</h6><br />");
    $replacement=array("<hr />", "</h1>", "</h2>", "</h3>", "</h4>", "</h5>", "</h6>");
    $text=str_replace($search, $replacement, $text);


    echo "<div id='content'>\n";
    //if not news add upper bar

    echo "<div class='postbartop'>\n";
    $pb->generatecommentbar($commentsnumber, $firstcomment, $range, $link);

    if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<=$permission && $_SESSION['SESS_TYPE']>0 && isset($add) && $add!="")
    {
      echo "<div class='postlinks' id='postup'>\n";
      echo "<ul>\n";
      echo "<li><a href='index.php{$add}'>Add {$addtype}</a></li>\n";
      echo "</ul>\n";
      echo "</div>\n";
    }

    echo "</div>\n";
    echo "<div class='postouter'>\n";

    //Main Message
    echo "<div class='post'>\n";
    echo "<div class='postheader'>\n";

    if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<=$permission && $_SESSION['SESS_TYPE']>0 && isset($add) && $add!="")
    {
      echo "<div class='postbarinner'>\n";
      echo "<ul class='postinner'>\n";
      echo "<li class='postinner'><a class='postinner' href='index.php{$add}&amp;quote=l.{$logID}'>Quote</a></li>\n";
      if((($_SESSION['SESS_ID']==$logArray[12] && $_SESSION['SESS_TYPE']<=$permission) || (isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']=='1')))
      {

        echo "<li class='postinner'><a class='postinner' href='index.php?action=editlog&amp;log={$logArray[0]}&amp;type={$logArray[1]}'>edit</a></li>\n";

      }
      echo "</ul>\n";
      echo "</div>\n";
    }

    echo "<span class='postheader'>{$logArray[11]}</span><br />\n";
    echo "<b>$date</b> {$at} {$during}\n";
    echo "</div>\n";
    echo "<div class='posttext'>\n";
    echo "{$text}\n";
    if(isset($logArray[8]))
    {
      echo "<br />\n";
      echo "<small>Last edit by {$logArray[8]} on {$editTime}</small>\n";
    }
    echo "</div>\n";
    //footer for original post
    echo "<div class='postfooter'>\n";
    echo "Posted by {$logArray[6]} on {$originalTime}\n";
    echo "</div>\n";
    echo "</div>\n";

    if($commentsnumber>0)
    {
      $counter=1;
      while($commentArray = mysql_fetch_array($commentResult, MYSQL_NUM))
      {
        $originalTime=$dp->getTime($commentArray[5], $offset, $timeformat);
        $editTime=$dp->getTime($commentArray[7], $offset, $timeformat);
        $cnumber=$counter+$firstcomment;

        echo"<div class='post'>\n";
        echo"<div class='postcommentheader'>\n";
        echo"<div class='postbarinner'>\n";
        //if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_ID']!="" && $_SESSION['SESS_TYPE']<=$permission)
        if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<=$permission && $_SESSION['SESS_TYPE']>0 && isset($add) && $add!="")
        {
          echo "<ul class='postinner'>\n";
          echo "<li class='postinner'><a class='postinner' href='index.php{$add}&amp;quote=c.{$commentArray[0]}'>Quote</a></li>\n";
          if(($_SESSION['SESS_ID']==$commentArray[2] && $_SESSION['SESS_TYPE']<'5') || (isset($_SESSION['SESS_NAME']) && $_SESSION['SESS_TYPE']=='1'))
          {
            echo "<li class='postinner'><a class='postinner' href='index.php?action=editcomment&amp;comment={$commentArray[0]}'>edit</a></li>\n";
          }
          echo "</ul>\n";
        }
        echo"</div>";
        echo "<a name='msg{$commentArray[0]}'><b>Re: {$logArray[11]}</b></a><br />\n";
        echo "<small>Comment #{$cnumber} by <b>$commentArray[4]</b> on {$originalTime}</small>\n";

        echo"</div>";
        echo"<div class='posttext'>";

        $text=nl2br($commentArray[3]);
        $text=$bbf->addTags($text);
        echo "{$text}\n";
        if(isset($commentArray[6]))
        {
          echo "<br />\n";
          echo "<small>Last edit by {$commentArray[6]} on {$editTime}</small>\n";
        }
        echo"</div>";
        echo"</div>";
        $counter++;
      }
    }
    echo "</div>\n";

    if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<='4')
    {
      echo "<div class='postbarbottom'>\n";
      $pb->generatecommentbar($commentsnumber, $firstcomment, $range, $link);

      if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<=$permission && $_SESSION['SESS_TYPE']>0 && isset($add) && $add!="")
      {
        echo "<div class='postlinks' id='postdown'>\n";
        echo "<ul>\n";
        echo "<li><a href='index.php{$add}'>Add {$addtype}</a></li>\n";
        echo "</ul>\n";
        echo "</div>\n";
      }

      echo "</div>\n";
    }

    if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<='4')
    {
      $opid=$_SESSION['SESS_ID'];
      $opid=strip($opid);
      echo "<div class='postouter'>\n";
      echo "<form action='index.php?action=logquery' method='post'>\n";
      echo "<div class='commentarea'>\n";
      echo "<b>Add new Comment</b>\n";
      echo "<br />\n";
      echo "<textarea cols='20' rows='4' class='commenttextarea' name='text'></textarea>\n";
      echo "<input type='hidden' name='pID' value='{$logID}' />\n";//ID of comment's parent
      echo "<input type='hidden' name='ptype' value='{$logArray[1]}' />\n";//type of comment's parent
      echo "<input type='hidden' name='opid' value='{$opid}' />\n";
      echo "<input type='hidden' name='QueryType' value='Comment' />\n";
      echo "<input type='hidden' name='QueryAction' value='AddChange' />\n";
      echo "<input class='edittablebutton' name='QueryAction' type='submit' value='Submit' />\n";
      echo "</div>\n";
      echo "</form>\n";
      echo "</div>\n";
    }
    echo "</div>\n";
    if($userfuntions->isregisterd()==1)
    {
      $visitedResult=$dbf->queryselect("SELECT lastcomment FROM logsvisited WHERE member='{$data}' AND logtype='{$logArray[1]}' AND logid='{$logID}';");
      $visitednumber=mysql_num_rows($visitedResult);
      if($visitednumber==0)
      {
        $lasCommentID=0;
        if($action!="news")
        {
          $lastResult=$dbf->queryselect("SELECT max(id) FROM comments WHERE parent='{$logID}';");
          if(mysql_num_rows($lastResult)==1)
          {
            $lasCommentID=mysql_result($lastResult, 0);
          }
        }
        $queryArray[0]="INSERT INTO logsvisited (logtype, member, logid, lastcomment) VALUES ('{$logArray[1]}', '{$data}', '{$logID}', '{$lasCommentID}');";
        $dbf->queryarray($queryArray);
      }
      if($visitednumber==1 && $action!="news")// && mysql_result($visitedResult, 0)<$lasCommentID)
      {
        $lasCommentID=0;
        $lastResult=$dbf->queryselect("SELECT max(id) FROM comments WHERE parent='{$logID}';");
        if(mysql_num_rows($lastResult)==1)
        {
          $lasCommentID=mysql_result($lastResult, 0);
          if(mysql_result($visitedResult, 0)<$lasCommentID)
          {
            $queryArray[0]="UPDATE logsvisited SET lastcomment='{$lasCommentID}' WHERE logtype='{$logArray[1]}' AND member='{$data}' AND logid='{$logID}';";
            $dbf->queryarray($queryArray);
          }
        }
      }
    }
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
  $errormsg="Log not found";
}
//Error Handling
if($error)
{
  echo "<div id='content'>\n";
  echo "<div class='error'>\n";
  echo "<b>An error has occurred</b> while accessing log entry.<br />\n";
  echo "No log entry found or you don't have rights to access this log.<br />\n";
  echo "Please use correct links and be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg,"<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>