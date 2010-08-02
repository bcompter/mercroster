<?php
if(!defined('9Gu4VdtJ453'))
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

require("includes/InputFields.php");
$inputFields = new InputFields;

//edit or add comment get arguments
if(isset($_GET['comment']) && $_GET['comment']!="")
{
  $id=strip($_GET['comment']);
  $edit=1;
}
else
{
  $parentid=strip($_GET['logid']);
  $opid=strip($_SESSION['SESS_ID']);
}
$type=strip($_GET['logtype']);

if(isset($_GET['quote']) && $_GET['quote']!="")
{
  $type=explode('.', $_GET['quote']);
  $quotedType=$type[0];
  $quotedID=strip($type[1]);
  if($quotedType=="l")
  {
    $quoredResult=$dbf->queryselect("SELECT id, op, opdate, text FROM logentry WHERE id='{$quotedID}';");
  }
  if($quotedType=="c")
  {
    $quoredResult=$dbf->queryselect("SELECT id AS cid, parent AS id, op, opdate, text FROM comments WHERE id='{$quotedID}';");
  }
  if(mysql_num_rows($quoredResult)==1)
  {
    $quoredArray=mysql_fetch_array($quoredResult, MYSQL_ASSOC);
    if($quotedType=="c")
    {
      $first="msg.{$quoredArray['cid']}";
      $alink="#msg{$quoredArray['cid']}";
    }
    else
    {
      $first="lst.0";
    }
    $quotedLink="index.php?action=log&log={$quoredArray['id']}&first={$first}{$alink}";
    $quotedText=$quoredArray['text'];
    $quote="[quote author={$quoredArray['op']} link={$quotedLink} date={$quoredArray['opdate']}]{$quotedText}[/quote]";
  }
}

echo "<div id='content'>\n";

if(isset($_SESSION['SESS_ID']) || $_SESSION['SESS_ID']!="")
{
  if($edit)
  {
    $result=$dbf->queryselect("SELECT * FROM comments WHERE ID='$id';");
    $array=mysql_fetch_array($result, MYSQL_NUM);
    echo "<h1 class='headercenter'>Edit Comment</h1>\n";
    $submitButtonText="Save";
    $parentid = $array[1];
  }
  else
  {
    echo "<h1 class='headercenter'>New Comment</h1>\n";
    $submitButtonText="Add";
  }
  echo "<div class='genericarea'>\n";
  echo "<form action='index.php?action=logquery' method='post' id='modified'>\n";
  echo "<table class='main' border='0'>\n";
  if(isset($_GET['err']))
  {
    echo"<tr>\n";
    echo"<td colspan='8'><b>No changes was made because {$_GET['err']} was given.</b></td>\n";
    echo"</tr>\n";
  }
  if($edit)
  {
    echo "<tr>\n";
    echo "<td colspan='7' class='commentbarlefttop'><b>Comment posted originally by $array[4]</b><small class='commenttabletopictime'> on {$dp->getTime($array[5], $offset, $timeformat)}</small></td>\n";
    echo "</tr>\n";
  }
  echo "<tr>\n";
  if(isset($quote) && !isset($_GET['comment']))
  {
    $search = array(' ', '&');
    $replace = array('&nbsp;', '&amp;');
    $quote=strtr($quote, array_combine($search, $replace) );
    //$quote=str_replace("&", "&amp;", $quote);
    $inputFields->textarea("edittableleft", "edittableright", 6, "Comment", "edittablecommon", "text", $quote);
  }
  else
  {
    $textarea=str_replace("&", "&amp;", $array[3]);
    $inputFields->textarea("edittableleft", "edittableright", 6, "Comment", "edittablecommon", "text", $textarea);
  }
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td class='edittablebottom' colspan='2'>\n";
  if($edit)//for edit
  {
    echo "<input type='hidden' name='ID' value='{$id}' />\n";//id of comment (0=if new comment)
  }
  else
  {
    echo "<input type='hidden' name='opid' value='{$opid}' />\n";
  }
  echo "<input type='hidden' name='pID' value='{$parentid}' />\n";//ID of comment's parent
  echo "<input type='hidden' name='ptype' value='{$type}' />\n";//type of comment's parent
  echo "<input type='hidden' name='QueryType' value='Comment' />\n";
  echo "<input class='edittablebutton' name='QueryAction' type='submit' value='{$submitButtonText}' />\n";
  if($edit)
  {
    echo "<input class='edittablebutton' name='QueryAction' type='submit' value='Delete' onclick='return confirmSubmit(\"Delete\")' />\n";
  }
  echo "</td>\n";
  echo "</tr>\n";
  echo "</table>\n";
  echo "</form>\n";
  echo "</div>\n";
}
else
{
  echo "<b>Access Denied</b>\n";
}
echo "</div>\n";
?>