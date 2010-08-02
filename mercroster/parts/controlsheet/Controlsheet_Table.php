<?php
if(!defined('5gDh4v7vFhs6'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}

$first=$_GET['first'];

require("includes/PageBar.php");
$pb = new PageBar;

$range=30;

//Finding and organizing pdf controlsheet
$desired_extension="pdf";
$controlsheets=array();
if($handle=opendir("./controlsheets/"))
{
  while(false!==($file=readdir($handle)))
  {
    $fileChunks=explode(".", $file);
    if ($file!="." && $file!=".." &&  $fileChunks[1]==$desired_extension)
    {
      array_push($controlsheets, $file);
    }
  }
  closedir($handle);
}
sort($controlsheets);
$sheetarrayaize=sizeof($controlsheets);
$controlsheets=array_slice($controlsheets, $first, $range);

echo "<div id='content'>\n";

//Parsing personel data
echo "<h1 class='headercenter'>Control Sheets</h1>\n";
$link="controlsheettable";
echo "<div class='pagebar'>\n";
$pb->generatebar($sheetarrayaize, $first, $range, $link);
echo "</div>\n";
echo "<hr />\n";

echo "<table class='rostertable'>\n";
echo "<thead class='rostertable'>\n";
echo "<tr>\n";
echo "<th class='rostertabletype'>Vehicle</th>\n";
if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<'4')
{
  echo "<th class='rostertabletype'>Delete</th>\n";
}
echo "</tr>\n";
echo "</thead>\n";
echo "<tbody class='rostertable'>\n";
$counter=0;
if(sizeof($controlsheets)>0)
{
  for($i=0; $i<sizeof($controlsheets); $i++)
  {
    echo "<tr>\n";
    echo "<td class='rostertablenumber'><a class='rostertable'  href='".$sitepath."controlsheets/".$controlsheets[$i]."'>{$controlsheets[$i]}</a></td>\n";

    if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<'4')
    {
      echo "<td class='rostertablenumber'>\n";
      echo "<form action='index.php?action=pdfupload' method='post'>\n";
      echo "<div>\n";
      echo "<input type='hidden' name='filename' value='{$controlsheets[$i]}' />\n";
      echo "<input type='hidden' name='type' value='pdfrm' />\n";
      echo "<input type='submit' value='Delete' onclick='return confirmSubmit(\"Delete\")' />\n";
      echo "</div>\n";
      echo "</form>\n";
      echo "</td>\n";
    }
        echo "</tr>\n";
  }
}
else
{
  echo "<tr>\n";
  echo "<td class='rostertablenumber'>No Control Sheets</td>\n";
  echo "</tr>\n";
}
echo "</tbody>\n";
echo "</table>\n";
if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<'4')
{
  echo "<hr />\n";
  echo "Add Control Sheet<br />\n";
  if(isset($_GET['mgs']))
  {
    switch ($_GET['mgs'])
    {
      case "FileNotImage":
        $errormsg="Unable to upload image. No file given.";
        break;
      case "FileTooLarge":
        $errormsg="Unable to upload image. File too large.";
        break;
      case "FileExists":
        $errormsg="Unable to upload image. File already exists.";
        break;
      default:
        $errormsg="Unable to upload image..";
        break;

    }
    echo "<b>" . $errormsg . "</b><br />\n";
    echo "<b>Make corrections and try again.</b><br />\n";
  }
  echo "<form enctype='multipart/form-data' action='index.php?action=pdfupload' method='post'>\n";
  echo "<div>\n";
  echo "<input type='hidden' name='type' value='pdfadd' />\n";
  echo "<input name='file' type='file' />\n";
  echo "<input type='submit' value='Submit' />\n";
  echo "</div>\n";
  echo "</form>\n";
}
echo "</div>\n";

?>