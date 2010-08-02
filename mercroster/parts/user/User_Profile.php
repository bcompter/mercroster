<?php
if(!defined('ty7Ui54F5'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}

require("includes/InputFields.php");
$inputFields=new InputFields;

require("htdocs/dbsetup.php");
$data=stripslashes($_SESSION['SESS_ID']);
$data=mysql_real_escape_string($data);
$data=strip_tags($data);

$tarray[1]="Administrator";
$tarray[2]="Game Master";
$tarray[3]="Commander";
$tarray[4]="Player";
$tarray[5]="Spectator";

//user is editing his/her own user profile
if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_ID']!="" && $_SESSION['SESS_ID']>0 && isset($_SESSION['SESS_TYPE']) && $_SESSION['SESS_TYPE'] > 1 && $_SESSION['SESS_TYPE']<= 5)
{
  echo "<div id='content'>\n";
  echo "<div class='genericheader'>\n";
  echo "<b>Edit Profile</b>\n";
  echo "</div>\n";
  echo "<div class='genericarea'>\n";
  //so you are user that wants to edit his/her own profile
  if(isset($_GET['change']))
  {
    if($_GET['change']=="fail1")
    {
      echo "<b>Password change failed.</b> <br />\n";
      echo "<b>Password and Repeat not the same.</b> <br />\n";
      echo "<b>Retype Password and Repeat.</b>\n";
    }
    if($_GET['change']=="fail2")
    {
      echo "<b>Wrong current password.</b> <br />\n";
      echo "<b>No changes made.</b> <br />\n";
    }
    if($_GET['change']=="true")
    {
      echo "<b>Changes made</b>\n";
    }
    if($_GET['change']=="truepw")
    {
      echo "<b>Changes made and password changed</b>\n";
    }
  }

  //$userResult = $dbf->queryselect("SELECT id, username, sitename, fname, lname, type, timeoffset, timeformat FROM members WHERE username='{$userName}';");
  $userResult=$dbf->queryselect("SELECT id, username, sitename, fname, lname, type, timeoffset, timeformat, favoredunit FROM members WHERE id='{$data}';");
  $userArray=mysql_fetch_array($userResult, MYSQL_BOTH);

  $unitResult=$dbf->queryselect("SELECT DISTINCT u.id AS uid, u.name AS uname, p.id AS pid, p.name AS pname FROM crew c, unit u, unit p WHERE c.parent=u.id AND p.id=u.parent ORDER BY p.name ASC, u.name ASC;");

  echo "<form action='index.php?action=userquery' onsubmit='return validate_form(this)' method='post'>\n";
  echo "<table class='profidataletable' border='0'>\n";
  if(isset($_GET['err']))
  {
    echo"<tr>\n";
    echo"<td colspan='8'><div class='error'>\n";
    $errors=explode(".", $_GET['err']);
    echo "<b>Following errors happend</b>:<br />\n";
    foreach ($errors as $variable)
    {
      switch ($variable)
      {
        case "0":
          echo "No username given.<br />\n";
          break;
        case "1":
          echo "Username is illegal or already taken.<br />\n";
          break;
        case "2":
          echo "Site name missing.\n<br />";
          break;
        case "3":
          echo "First name missing.<br />\n";
          break;
        case "4":
          echo "Last name missing.<br />\n";
          break;
        case "5":
          echo "New Password is illegal or it dosen't match.<br />\n";
          break;
        case "10":
          echo "Wrong Passwords given.<br />\n";
          break;
      }
    }
    echo"</div></td>\n";
    echo"</tr>\n";
  }

  echo "<tr>\n";
  echo "<td class='edittableleft'>Username:</td>\n";
  echo "<td class='edittableleft'>$userArray[1]</td>\n";
  echo "<td><input class='edittablecommon265' name='username' type='hidden' value='$userArray[1]' /></td>\n";
  echo "</tr>\n";

  echo "<tr><td colspan='2' class='edittableleft'><small>Password is required to chance any information</small></td></tr>\n";
  echo "<tr>\n";
  echo "<td class='edittableleft'>Password:</td>\n";
  echo "<td><input class='edittablecommon265' name='curretpw' type='password' maxlength='60' value='$userArray[2]' /></td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td class='edittableleft'>Name:</td>\n";
  echo "<td><input class='edittablecommon265' name='name' type='text' maxlength='60' value='$userArray[2]' /></td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td class='edittableleft'>First Name:</td>\n";
  echo "<td><input class='edittablecommon265' name='firstname' type='text' maxlength='60' value='$userArray[3]' /></td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td class='edittableleft'>Last Name:</td>\n";
  echo "<td><input class='edittablecommon265' name='lastname' type='text' maxlength='60' value='$userArray[4]' /></td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td class='edittableleft'>User Type:</td>\n";
  echo "<td class='edittablebox'>{$tarray[$userArray[5]]}</td>\n";
  echo "<td><input class='edittablebox' name='utype' type='hidden' value='$userArray[5]' /></td>\n";
  echo "</tr>\n";

  echo "<tr><td colspan='2'><hr class='profiledatatable' /></td></tr>\n";

  echo "<tr><td colspan='2' class='edittableleft'><small>These setting affect only real timestamps, not game timestamps.</small></td></tr>\n";
  echo "<tr>\n";
  echo "<td class='edittableleft'>Time Format:</td>\n";
  echo "<td><input class='edittablecommon265' name='timeformat' type='text' maxlength='60' value='$userArray[7]' /></td>\n";
  echo "</tr>\n";
  echo "<tr><td colspan='2' class='edittableleft'><small>Use only valid php time formats.</small></td></tr>\n";

  echo "<tr>\n";
  echo "<td class='edittableleft'>Time Offset:</td>\n";
  echo "<td><input class='edittablecommon265' name='timeoffset' type='text' maxlength='60' value='$userArray[6]' /></td>\n";
  echo "</tr>\n";
  $time=date("H:i:s");
  echo "<tr><td colspan='2' class='edittableleft'><small>+/- hours to Server Time.  Current server time: {$time}</small></td></tr>\n";

  echo "<tr>\n";
  echo "<td class='edittableleft'>Favored Unit:</td>\n";
  echo "<td>\n";
  $inputFields->dropboxquerydual($unitResult,$userArray[favoredunit],'favoredunit','edittablebox',true);
  echo "</td>\n";
  echo "</tr>\n";

  echo "<tr><td colspan='2'><hr class='profiledatatable' /></td></tr>\n";

  echo "<tr>\n";
  echo "<td class='edittableleft'>New Password:</td>\n";
  echo "<td colspan='6'><input class='edittablecommon265' name='newpw' type='password' maxlength='60' value='' /></td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td class='edittableleft'>Verify Password:</td>\n";
  echo "<td colspan='6'><input class='edittablecommon265' name='repeat' type='password' maxlength='60' value='' /></td>\n";
  echo "</tr>\n";

  echo "<tr><td colspan='2'><hr class='profiledatatable' /></td></tr>\n";

  echo "<tr>\n";
  echo "<td colspan='2' class='edittablebottom'>\n";
  echo "<input type='hidden' name='ID' value='{$userArray[0]}' />\n";
  echo "<input type='hidden' name='QueryType' value='User' />\n";
  //echo "<input type='hidden' name='QueryAction' value='AddChange' />\n";
  echo "<input class='edittablebutton' name='QueryAction' type='submit' value='Change' />\n";
  if(isset($_GET['user']))
  {
    echo "<input class='edittablebutton' name='QueryAction' type='submit' value='Delete' onclick='return confirmSubmit(\"Delete\")' />\n";
  }
  echo "</td>\n";
  echo "</tr>\n";
  echo "</table>\n";
  echo "</form>\n";
  echo "</div>\n";
  echo "</div>\n";
}
else
{
  $error=true;
  $errormsg="Access denied.";
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