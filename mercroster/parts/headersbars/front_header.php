<?php
if(!defined('Ghe36Jacb3b'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}
//echo "<?xml version='1.0' encoding='UTF-8' standalone='no'>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
$timerstart= microtime(true);
echo "<html xmlns='http://www.w3.org/1999/xhtml'  dir='ltr' xml:lang='en-US'>\n";
echo "<head>\n";
echo "<title>{$title}</title>\n";
echo "<meta http-equiv='Content-type' content='text/html;charset=UTF-8' />\n";
echo "<link rel='stylesheet' href='css/front.css' type='text/css' />\n";
echo "<link rel='icon' type='image/png' href='images/commandicons/{$commandIcon}' />\n";
echo "<link rel='alternate' type='application/rss+xml' href='feeds/feed.xml' title='GKKs RSS feed' />\n";

echo "</head>\n";
echo "<body>\n";
echo "<div id='header'>\n";

echo "<div class='innerheader'>\n";
echo "<div class='links' id='links'>\n";
echo "<ul>\n";
echo "<li><a href='index.php?action=main'>Home</a></li>\n";
echo "<li><a href='index.php?action=information'>About {$commandAbb}</a></li>\n";
echo "<li><a href='index.php?action=services'>Services</a></li>\n";
echo "<li><a href='index.php?action=contact'>Hire us</a></li>\n";
echo "<li><a href='index.php?action=status'>Tables</a></li>\n";
if(!isset($_SESSION['SESS_ID']) || (trim($_SESSION['SESS_ID'])==''))
{
  echo "<li><a href='index.php?action=flogin'>Login</a></li>\n";
}
else
{
  echo "<li><a href='index.php?action=logout'>Logout</a></li>\n";
}
echo "</ul>\n";
echo "</div>\n";
echo "</div>\n";

echo "</div>\n";
echo "<div id='container'>\n";
?>