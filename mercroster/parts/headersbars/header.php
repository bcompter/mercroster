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
echo "<html xmlns='http://www.w3.org/1999/xhtml'  dir='ltr' xml:lang='en-US'>\n";
echo "<head>\n";
echo "<title>{$title}</title>\n";
echo "<meta http-equiv='Content-type' content='text/html;charset=UTF-8' />\n";
if($headerbar=="front")
{
  echo "<link rel='stylesheet' href='css/front.css' type='text/css' />\n";
}
if($headerbar=="back")
{
  echo "<link rel='stylesheet' href='css/back.css' type='text/css' />\n";
}
echo "<link rel='icon' type='image/png' href='images/commandicons/{$commandIcon}' />\n";
echo "<link rel='alternate' type='application/rss+xml' href='feeds/feed.xml' title='GKKs RSS feed' />\n";
foreach ($scriptArray as $script)
{
  switch ($script)
  {
    case "delete":
      echo "<script src='scripts/delete.js' type='text/javascript'></script>\n";
      break;
    case "textarea":
      echo "<script src='scripts/textarea.js' type='text/javascript'></script>\n";
      break;
    case "image":
      echo "<script src='scripts/imagechange.js' type='text/javascript'></script>\n";
      break;
    case "unitlevelimage":
      $unitlevelimagesresult=$dbf->queryselect("SELECT id, picture FROM unitlevel;");
      $unitlevelimageArray=$dbf->resulttoarray($unitlevelimagesresult);
      ?>
<script type="text/javascript">
<!--
function change_unitlevel_image(val)
{
  switch(val)
  {
<?php
    foreach ($unitlevelimageArray as $unitlevelimage) 
    {
      echo "  case '{$unitlevelimage[id]}':\n";
	  echo "    var imagesrc='./images/unitlevel/{$unitlevelimage[picture]}';\n";
	  echo "    break;\n";
	}
?>
  }
  document.unitlevelimage.src=imagesrc;
}
// -->
</script>
<?php
	break;
  }
}
echo "</head>\n";
echo "<body>\n";
echo "<div id='header'>\n";
//Front's Header bar
if($headerbar=="front")
{
  echo "<div class='innerheader'>\n";
  echo "<div class='links' id='links'>\n";
  echo "<ul>\n";
  echo "<li><a href='index.php?action=main'>Home</a></li>\n";
  //echo "<li><a href='index.php?action=information'>About {$commandAbb}</a></li>\n";
  //echo "<li><a href='index.php?action=services'>Services</a></li>\n";
  //echo "<li><a href='index.php?action=contact'>Hire us</a></li>\n";
  echo "<li><a href='index.php?action=status'>Tables</a></li>\n";
}
//Tables's Header bar
if($headerbar=="back")
{
  echo "<div class='innerheader'>\n";
  echo "<div class='links' id='links'>\n";
  echo "<ul>\n";
  echo "<li><a href='index.php?action=status'>Home</a></li>\n";
  echo "<li><a href='index.php?action=main'>Front</a></li>\n";

  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_ID']!="")
  {
    if($_SESSION['SESS_TYPE']=='1')
    {
      echo "<li><a class='headerlink' href='index.php?action=site'>Admin</a></li>\n";
    }
    if($_SESSION['SESS_TYPE']>='1' && $_SESSION['SESS_TYPE']<='3')
    {
      echo "<li><a class='headerlink' href='index.php?action=command'>Command</a></li>\n";
      echo "<li><a class='headerlink' href='index.php?action=toe'>TOE</a></li>\n";
    }
    echo "<li><a class='headerlink' href='index.php?action=users&amp;first=0'>Members</a></li>\n";
    if($_SESSION['SESS_TYPE']>='2' && $_SESSION['SESS_TYPE']<='5')
    {
      echo "<li><a class='headerlink' href='index.php?action=profile'>Profile</a></li>\n";
    }
  }
}
//Login/Logout button to header bar
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