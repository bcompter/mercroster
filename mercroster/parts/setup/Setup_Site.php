<?php
if(!defined('huy5g4Ghj4H'))
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

if (!isset ($_SESSION['SESS_NAME']) || $_SESSION['SESS_TYPE']!='1')
{
  $error=true;
  $errormsg="Access denied.";
}
else
{
  require("htdocs/dbsetup.php");
  $page=$_GET['page'];

  echo "<div id='content'>\n";
  echo "<div id='setupheader'>\n";
  echo "<ul>\n";
  switch ($page)
  {
    case "2":
      echo "<li><a class='generictablenonedit' href='index.php?action=site&amp;page=1'>Board types</a></li>\n";
      echo "<li id='selected'><a class='generictableedit' href='index.php?action=site&amp;page=2'>Failed Login Log</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=site&amp;page=3'>Image Log</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=site&amp;page=4'>PDF Log</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=site&amp;page=5&amp;first=0'>Guest Log</a></li>\n";
      break;
    case "3":
      echo "<li><a class='generictablenonedit' href='index.php?action=site&amp;page=1'>Board types</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=site&amp;page=2'>Failed Login Log</a></li>\n";
      echo "<li id='selected'><a class='generictableedit' href='index.php?action=site&amp;page=3'>Image Log</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=site&amp;page=4'>PDF Log</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=site&amp;page=5&amp;first=0'>Guest Log</a></li>\n";
      break;
    case "4":
      echo "<li><a class='generictablenonedit' href='index.php?action=site&amp;page=1'>Board types</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=site&amp;page=2'>Failed Login Log</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=site&amp;page=3'>Image Log</a></li>\n";
      echo "<li id='selected'><a class='generictableedit' href='index.php?action=site&amp;page=4'>PDF Log</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=site&amp;page=5&amp;first=0'>Guest Log</a></li>\n";
      break;
    case "5":
      echo "<li><a class='generictablenonedit' href='index.php?action=site&amp;page=1'>Board types</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=site&amp;page=2'>Failed Login Log</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=site&amp;page=3'>Image Log</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=site&amp;page=4'>PDF Log</a></li>\n";
      echo "<li id='selected'><a class='generictableedit' href='index.php?action=site&amp;page=5&amp;first=0'>Guest Log</a></li>\n";
      break;
    default:
      echo "<li id='selected'><a class='generictableedit' href='index.php?action=site&amp;page=1'>Board types</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=site&amp;page=2'>Failed Login Log</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=site&amp;page=3'>Image Log</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=site&amp;page=4'>PDF Log</a></li>\n";
      echo "<li><a class='generictablenonedit' href='index.php?action=site&amp;page=5&amp;first=0'>Guest Log</a></li>\n";
      break;
  }
  echo "</ul>\n";
  echo "</div>\n";

  switch ($page)
  {
    //Failed login attempt log
    case "2":
      echo "<div class='genericarea'>\n";
      echo "<h1 class='headercenter'>Failed Login Attempts</h1>\n";
      if(is_file("logs/failedlogins.log"))
      {
        $lines=file("logs/failedlogins.log") or die("test");

        echo "<table class='rostertable' border='0'>\n";
        echo "<thead class='rostertable'>\n";
        echo "<tr>\n";
        echo "<th class='rostertable'>Action</th>\n";
        echo "<th class='rostertable'>Username</th>\n";
        echo "<th class='rostertable'>Time</th>\n";
        echo "</tr>\n";
        echo "</thead>\n";

        foreach ($lines as $line_num => $line)
        {
          $linearray=explode("|", $line);
          echo "<tr>\n";
          echo "<td class='rostertable'>{$linearray[0]}</td>\n";
          echo "<td class='rostertable'>{$linearray[1]}</td>\n";
          echo "<td class='rostertable'>{$linearray[2]}</td>\n";
          echo "</tr>\n";
          //echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
        }
        echo "</table>\n";
      }
      else
      {
        echo "<b>No log file detected</b><br />\n";
      }
      echo "</div>\n";
      break;

      //Images upload/remove log
    case "3":
      echo "<div class='genericarea'>\n";
      echo "<h1 class='headercenter'>Image Log</h1>\n";
      if(is_file("logs/images.log"))
      {
        $lines=file("logs/images.log") or die("test");
        echo "<table class='rostertable' border='0'>\n";
        echo "<thead class='rostertable'>\n";
        echo "<tr>\n";
        echo "<th class='rostertable'>Operation</th>\n";
        echo "<th class='rostertable'>User</th>\n";
        echo "<th class='rostertable'>Time</th>\n";
        echo "<th class='rostertable'>File</th>\n";
        echo "<th class='rostertable'>Size</th>\n";
        echo "<th class='rostertable'>Filetype</th>\n";
        echo "</tr>\n";
        echo "</thead>\n";
        foreach ($lines as $line_num => $line)
        {
          //echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
          $linearray=explode("|", $line);
          echo "<tr>\n";
          echo "<td class='rostertable'>{$linearray[0]}</td>\n";
          echo "<td class='rostertable'>{$linearray[1]}</td>\n";
          echo "<td class='rostertable'>{$linearray[2]}</td>\n";
          echo "<td class='rostertable'>{$linearray[3]}</td>\n";
          echo "<td class='rostertable'>{$linearray[4]}</td>\n";
          echo "<td class='rostertable'>{$linearray[5]}</td>\n";
          echo "</tr>\n";
        }
        echo "</table>\n";
      }
      else
      {
        echo "<b>No log file detected</b><br />\n";
      }
      echo "</div>\n";
      break;

      //PDFs upload/remove log
    case "4":
      echo "<div class='genericarea'>\n";
      echo "<h1 class='headercenter'>PDF-File Log</h1>\n";
      if(is_file("logs/pdf.log"))
      {
        $lines=file("logs/pdf.log") or die("test");
        echo "<table class='rostertable' border='0'>\n";
        echo "<thead class='rostertable'>\n";
        echo "<tr>\n";
        echo "<th class='rostertable'>Operation</th>\n";
        echo "<th class='rostertable'>User</th>\n";
        echo "<th class='rostertable'>Time</th>\n";
        echo "<th class='rostertable'>File</th>\n";
        echo "<th class='rostertable'>Size</th>\n";
        echo "<th class='rostertable'>Filetype</th>\n";
        echo "</tr>\n";
        echo "</thead>\n";
        foreach ($lines as $line_num => $line)
        {
          //echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
          $linearray=explode("|", $line);
          echo "<tr>\n";
          echo "<td class='rostertable'>{$linearray[0]}</td>\n";
          echo "<td class='rostertable'>{$linearray[1]}</td>\n";
          echo "<td class='rostertable'>{$linearray[2]}</td>\n";
          echo "<td class='rostertable'>{$linearray[3]}</td>\n";
          echo "<td class='rostertable'>{$linearray[4]}</td>\n";
          echo "<td class='rostertable'>{$linearray[5]}</td>\n";

          echo "</tr>\n";
        }
        echo "</table>\n";
      }
      else
      {
        echo "<b>No log file detected</b><br />\n";
      }
      echo "</div>\n";
      break;

      //Guest log
    case "5":

      require("includes/PageBar.php");
      $pb=new PageBar;

      require("htdocs/dbsetup.php");
      $first=$_GET['first'];
      $first=stripslashes($first);
      $first=mysql_real_escape_string($first);

      $range=30;

      $rResult=$dbf->queryselect("SELECT COUNT(*) count FROM guests;");
      $rnumber=mysql_result($rResult, 0);
      $link="site&amp;page=5&amp;logged=0";
      $guestResult=$dbf->queryselect("SELECT INET_NTOA(ipaddress) AS ip, logins, lastlogin, logged, referer FROM guests ORDER BY lastlogin DESC LIMIT $first, $range;");

      echo "<div class='genericarea'>\n";
      echo "<h1 class='headercenter'>Guest user Log</h1>\n";
      $pb->generatebarlink($rnumber, $first, $range, $link, $add, $addtype);

      echo "<hr />\n";
      $guestnumber=mysql_num_rows($guestResult);
      if($guestnumber>0)
      {
        echo "<table class='guesttable' border='0'>\n";
        echo "<thead class='guesttable'>\n";
        echo "<tr>\n";
        echo "<th class='rosterlogimage'></th>\n";
        echo "<th class='guesttableip'>IP Address</th>\n";
        echo "<th class='guesttablesession'>Sessions</th>\n";
        echo "<th class='guesttabletime'>Last Time Online</th>\n";
        echo "<th class='guesttablelast'>Last External Referer</th>\n";

        echo "</tr>\n";
        echo "</thead>\n";
        while($guestArray=mysql_fetch_array($guestResult, MYSQL_ASSOC))
        {
          echo "<tr>\n";
          echo "<td class='rostertable'>\n";
          if($guestArray[logged]==1)
          {
            echo "<img src='./images/small/online.png' alt='on' />";
          }
          else
          {
            echo "<img src='./images/small/offline.png' alt='off' />";
          }
          echo "</td>\n";
          echo "<td class='guesttable'>{$guestArray[ip]}</td>\n";
          echo "<td class='guesttable'>{$guestArray[logins]}</td>\n";
          $lastLogTime="".$dp->getTime($guestArray[lastlogin], $offset, $timeformat);
          echo "<td class='guesttable'>{$lastLogTime}</td>\n";
          echo "<td class='guesttable'>{$guestArray[referer]}</td>\n";

          echo "</tr>\n";
        }

        echo "</table>\n";
        echo "<hr />\n";
        $pb->generatebarlink($rnumber, $first, $range, $link, $add, $addtype);
      }
      else
      {
        echo "No Guests currently online\n";
      }
      echo "</div>\n";
      break;

      /*************
       * Log Types *
       *************/
    default:
      //Fetching Crewtype data
      $logResult=$dbf->queryselect("SELECT id, type FROM logtypes ORDER BY prefpos ASC;");
      $logTypes=mysql_result($dbf->queryselect("SELECT COUNT(*) count FROM logtypes;"), 0);

      //Fetching used crewtype data
      $usedlogtypeResult=$dbf->queryselect("SELECT DISTINCT logtype FROM logentry;");
      $usedlogtypeArray=$dbf->resulttoarraysingle($usedlogtypeResult);

      $tarray[1]="Administrator";
      $tarray[2]="Game Master";
      $tarray[3]="Commander";
      $tarray[4]="Player";
      $tarray[5]="Spectator";
      $tarray[6]="Everyone";

      echo"<div class='typecontainer' style='overflow: auto;'>\n";
      echo"<div id='typelist' class='typelist'>\n";
      echo "<ul>\n";
      if(!isset($_GET['sub']))
      {
        echo "<li class='selectedtype'><a class='selectedtype' href='index.php?action=site&amp;page=1'>New Log Type</a></li>\n";
      }
      else
      {
        echo "<li><a class='notselectedtype' href='index.php?action=site&amp;page=1'>New Log Type</a></li>\n";
      }
      while ($logArray=mysql_fetch_array($logResult, MYSQL_ASSOC))
      {
        if($_GET['sub']==$logArray[id])
        {
          echo "<li class='selectedtype'><a class='selectedtype' href='index.php?action=site&amp;page=1&amp;sub={$logArray[id]}'>{$logArray[type]}</a></li>\n";
        }
        else
        {
          echo "<li><a class='notselectedtype' href='index.php?action=site&amp;page=1&amp;sub={$logArray[id]}'>{$logArray[type]}</a></li>\n";
        }
      }
      echo "</ul>\n";
      echo"</div>\n";

      if(isset($_GET['err']))
      {
        echo"<b>No changes was made because {$_GET['err']} was given.</b><br />\n";
      }

      if(isset($_GET['sub']))
      {
        $sub=strip($_GET['sub']);
        $logTypeResult=$dbf->queryselect("SELECT * FROM logtypes WHERE id='{$sub}';");
        $logTypeArray=mysql_fetch_array($logTypeResult, MYSQL_ASSOC);

        echo"<div id='typeeditarea' class='typeeditarea'>\n";
        echo"<form action='index.php?action=setupsitequery' method='post'>\n";
        echo"<table border='0'>\n";
        //Type
        echo"<tr>\n";
        echo "<th class='setuptabletext'>Log type</th>\n";
        ?>
<td><input class="setuptabletext" name="logtype" type="text"
	maxlength="100" value="<?php echo"$logTypeArray[type]" ?>" /></td>
        <?php
        echo"</tr>\n";
        //Read Permission
        echo"<tr>\n";
        echo "<th class='setuptablebox'>Read Permission</th>\n";
        echo "<td><select class='setuptablebox' name='readpermission'>\n";
        for($i=1; $i<=6; $i++)
        {
          if($i==$logTypeArray[readpermission])
          {
            echo "<option value='{$i}' selected='selected'>{$tarray[$i]}</option>\n";
          }
          else
          {
            echo "<option value='{$i}'>{$tarray[$i]}</option>\n";
          }
        }
        echo "</select></td>\n";
        echo"</tr>\n";
        //Write Permission
        echo"<tr>\n";
        echo "<th class='setuptablebox'>Write Permission</th>\n";
        echo "<td><select class='setuptablebox' name='writepermission'>\n";
        for($i=1; $i<=5; $i++)
        {
          if($i==$logTypeArray[writepermission])
          {
            echo "<option value='{$i}' selected='selected'>{$tarray[$i]}</option>\n";
          }
          else
          {
            echo "<option value='{$i}'>{$tarray[$i]}</option>\n";
          }
        }
        echo "</select></td>\n";
        echo"</tr>\n";
        //Start field
        echo"<tr>\n";
        echo "<th class='setuptablecheck'>Starting Time</th>\n";
        if ($logTypeArray[start]==1)
        {
          echo "<td><input class='setuptablecheck' name='start' type='checkbox' checked='checked' /></td>\n";
        }
        else
        {
          echo "<td><input class='setuptablecheck' name='start' type='checkbox' /></td>\n";
        }
        echo"</tr>\n";
        //End Field
        echo"<tr>\n";
        echo "<th class='setuptablecheck'>Ending Time</th>\n";
        if ($logTypeArray[end]==1)
        {
          echo "<td><input class='setuptablecheck' name='end' type='checkbox' checked='checked' /></td>\n";
        }
        else
        {
          echo "<td><input class='setuptablecheck' name='end' type='checkbox' /></td>\n";
        }
        echo"</tr>\n";
        //Location Field
        echo"<tr>\n";
        echo "<th class='setuptablecheck'>Location</th>\n";
        if ($logTypeArray[location]==1)
        {
          echo "<td><input class='setuptablecheck' name='location' type='checkbox' checked='checked' /></td>\n";
        }
        else
        {
          echo "<td><input class='toessetuptablecheckhort' name='location' type='checkbox' /></td>\n";
        }
        echo"</tr>\n";
        //Text Field
        echo"<tr>\n";
        echo "<th class='setuptablecheck'>Text Field</th>\n";
        if ($logTypeArray[text] == 1)
        {
          echo "<td><input class='setuptablecheck' name='text' type='checkbox' checked='checked' /></td>\n";
        }
        else
        {
          echo "<td><input class='setuptablecheck' name='text' type='checkbox' /></td>\n";
        }
        echo"</tr>\n";
        //Contract Field
        echo"<tr>\n";
        echo "<th class='setuptablecheck'>Contract Field</th>\n";
        if ($logTypeArray[contract] == 1)
        {
          echo "<td><input class='setuptablecheck' name='contract' type='checkbox' checked='checked' /></td>\n";
        }
        else
        {
          echo "<td><input class='setuptablecheck' name='contract' type='checkbox' /></td>\n";
        }
        echo"</tr>\n";

        echo"<tr>\n";
        echo "<td colspan='2'>\n";
        echo "<input type='hidden' name='ID' value='{$logTypeArray[id]}' />\n";
        echo "<input type='hidden' name='prefpos' value='{$logTypeArray[prefpos]}' /> \n";
        echo "<input type='hidden' name='QueryType' value='logtype' /> \n";
        echo "<input type='hidden' name='QueryAction' value='AddChange' /> \n";
        echo "<input class='setuptablebutton' name='QueryAction' type='submit' value='Change' /> \n";
        if (!in_array($logTypeArray[id], $usedlogtypeArray))
        {
          echo "<input class='setuptablebutton' name='QueryAction' type='submit' value='Delete' onclick='return confirmSubmit(\"Delete\")' />\n";
        }
        else
        {
          echo "<input class='setuptablebutton' name='QueryAction' type='submit' value='Delete' disabled='disabled' />\n";
        }
        echo "</td>\n";
        echo "</tr>\n";

        echo "</table>\n";
        echo "</form>\n";

        //Up & Down buttons
        echo "<hr />\n";
        echo "<b>Site Position</b><br />\n";
        echo "<form action='index.php?action=setupsitequery' method='post'>\n";
        echo "<table border='0'>\n";
        echo "<tr>\n";
        echo "<td>\n";
        echo "<input type='hidden' name='ID' value='{$logTypeArray[id]}' />\n";
        echo "<input type='hidden' name='prefpos' value='{$logTypeArray[prefpos]}' />\n";
        echo "<input type='hidden' name='QueryType' value='logtypemove' />\n";
        echo "<input type='hidden' name='QueryAction' value='up' />\n";
        if ($logTypeArray[prefpos]!=1)
        {
          echo "<input class='toebutton' name='QueryAction' type='submit' value='Up' />\n";
        }
        else
        {
          echo "<input class='toebutton' name='QueryAction' type='submit' value='Up' disabled='disabled' />\n";
        }
        if ($logTypeArray[prefpos]!=$logTypes)
        {
          echo "<input class='toebutton' name='QueryAction' type='submit' value='Down' />\n";
        }
        else
        {
          echo "<input class='toebutton' type='submit' value='Down' disabled='disabled' />\n";
        }
        echo "</td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        echo "</form>\n";

        echo"</div>\n";
      }
      else
      {
        echo"<div id='typeeditarea' class='typeeditarea'>\n";
        echo"<form action='index.php?action=setupsitequery' method='post'>\n";
        echo"<table border='0'>\n";
        //Type
        echo"<tr>\n";
        echo "<th class='setuptabletext'>Log type</th>\n";
        echo "<td><input class='setuptabletext' name='logtype' type='text' maxlength='100' value='' /></td>\n";
        echo"</tr>\n";
        //Read Permission
        echo"<tr>\n";
        echo "<th class='setuptablebox'>Read Permission</th>\n";
        echo "<td><select class='setuptablebox' name='readpermission'>\n";
        for($i=1; $i<=6; $i++)
        {
          echo "<option value='{$i}'>{$tarray[$i]}</option>\n";
        }
        echo "</select></td>\n";
        echo"</tr>\n";
        //Write Permission
        echo"<tr>\n";
        echo "<th class='setuptablebox'>Write Permission</th>\n";
        echo "<td><select class='setuptablebox' name='writepermission'>\n";
        for($i=1; $i<=5; $i++)
        {
          echo "<option value='{$i}'>{$tarray[$i]}</option>\n";
        }
        echo "</select></td>\n";
        echo"</tr>\n";
        //Start field
        echo"<tr>\n";
        echo "<th class='setuptablecheck'>Starting Time</th>\n";
        echo "<td><input class='setuptablecheck' name='start' type='checkbox' /></td>\n";
        echo"</tr>\n";
        //End Field
        echo"<tr>\n";
        echo "<th class='setuptablecheck'>Ending Time</th>\n";
        echo "<td><input class='setuptablecheck' name='end' type='checkbox' /></td>\n";
        echo"</tr>\n";
        //Location Field
        echo"<tr>\n";
        echo "<th class='setuptablecheck'>Location</th>\n";
        echo "<td><input class='toessetuptablecheckhort' name='location' type='checkbox' /></td>\n";
        echo"</tr>\n";
        //Text Field
        echo"<tr>\n";
        echo "<th class='setuptablecheck'>Text Field</th>\n";
        echo "<td><input class='setuptablecheck' name='text' type='checkbox' /></td>\n";
        echo"</tr>\n";
        //Contract Field
        echo"<tr>\n";
        echo "<th class='setuptablecheck'>Contract Field</th>\n";
        echo "<td><input class='setuptablecheck' name='contract' type='checkbox' /></td>\n";
        echo"</tr>\n";

        echo"<tr>\n";
        echo "<td>\n";
        echo "<input type='hidden' name='ID' value='0' />\n";
        echo "<input type='hidden' name='QueryType' value='logtype' />\n";
        echo "<input class='setuptablebutton' name='QueryAction' type='submit' value='Add' />\n";
        echo "</td>\n";
        echo "</tr>\n";

        echo "</table>\n";
        echo "</form>\n";

        echo "</div>\n";
      }
      echo "</div>\n";
      break;
  }
  echo "</div>\n";
}
if($error)
{
  echo "<div id='content'>\n";
  echo "<div class='error'>\n";
  echo "<b>An error has occurred</b> while accessing this page.<br />\n";
  echo "You don't have rights to access this page.<br />\n";
  echo "Please be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg,"<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>