<?php
if(!defined('Ghe36Jacb3b'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
echo "<html xmlns='http://www.w3.org/1999/xhtml'  dir='ltr' xml:lang='en-US'>\n";
echo "<head>\n";

foreach ($scriptArray as $variable)
{
  switch ($variable)
  {
    case "delete":
      echo "<script type='text/javascript' src='scripts/delete.js'></script>\n";
      break;
      
    case "textarea":
      echo "<script type='text/javascript' src='scripts/textarea.js'></script>\n";
      break;
      
    case "image":
      echo "<script type='text/javascript' src='scripts/imagechange.js'></script>\n";
      break;
      
    case "unitlevelimage":
      $unitlevelresult = $dbf->queryselect("SELECT * FROM unitlevel;");
      ?>
<script type="text/javascript">
  <!--
  function change_unitlevel_image(val)
  {
    switch(val)
    {
	<?php
	while($array = mysql_fetch_array($unitlevelresult, MYSQL_NUM))
    {
      echo " case '{$array[2]}':\n";
	  echo " var imagesrc='./images/unitlevel/{$array[3]}';\n";
	  echo " break;\n";
	}
	?>
    }
    document.unitlevelimage.src=imagesrc;
  }
  // -->
  </script>
	<?php
	break;
	
    case "validateuser":
      echo "<script type='text/javascript' src='scripts/validateuser.js'></script>\n";
      break;
  }
}

echo "<title>{$title}</title>\n";
echo "<meta http-equiv='Content-type' content='text/html;charset=UTF-8' />\n";
echo "<link rel='stylesheet' href='css/back.css' type='text/css' />\n";
echo "<link rel='icon' type='image/png' href='images/commandicons/{$commandIcon}' />\n";
echo "</head>\n";
echo "<body>\n";
echo "<div id='header'>\n";

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