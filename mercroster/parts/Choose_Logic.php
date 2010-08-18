<?php
if(!defined('Gki58Bdg63v'))
{
  header('HTTP/1.0 404 not found');
  include("../error404.html");
  exit;
}
require("htdocs/appsetup.php");

require("htdocs/dbsetup.php");
$action=$_GET["action"];
$action=stripslashes($action);
$action=mysql_real_escape_string($action);
$scriptArray=array();

define('Ghe36Jacb3b', TRUE);
define('kgE3c68Fg2bnM', TRUE);
switch ($action)
{
  case "pages":
  	$title="Main";
    $headerbar="front";
    require("parts/headersbars/header.php");
    require("parts/headersbars/front_leftbar.php");
    require("parts/headersbars/front_rightbar.php");
    define('Gyu53Hkl3', TRUE);
    require("parts/front/Front.php");
    break;
  case "news":
    $title="News";
    $headerbar="front";
    require("parts/headersbars/header.php");
    require("parts/headersbars/front_leftbar.php");
    define('gt5fhsb64', TRUE);
    require("parts/log/News.php");
    break;
  case "notable":
    $title="Personnel";
    $headerbar="front";
    require("parts/headersbars/header.php");
    require("parts/headersbars/front_leftbar.php");
    require("parts/headersbars/front_rightbar.php");
    define('F3xVH894Vdsv', TRUE);
    require("parts/personnel/Personnel.php");
    break;
  case "readout":
    $title="Equipment";
    $headerbar="front";
    require("parts/headersbars/header.php");
    require("parts/headersbars/front_leftbar.php");
    define('g6afyHgJhHu87F', TRUE);
    require("parts/equipment/Equipment.php");
    break;
  case "units":
    $title="Unit";
    $headerbar="front";
    require("parts/headersbars/header.php");
    require("parts/headersbars/front_leftbar.php");
    define('j6Fr4F7k0cs8', TRUE);
    require("parts/unit/Unit.php");
    break;
  case "flogin":
    $title="Login";
    $headerbar="front";
    require("parts/headersbars/header.php");
    require("parts/headersbars/front_leftbar.php");
    define('gh5sxcBdu4', TRUE);
    require("parts/login/Login.php");
    break;
  case "contracttable":
    $title="Contracts";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('gHsb64G7jjT', TRUE);
    require("parts/contract/Contract_Table.php");
    break;
  case "contract":
    $title="Contract";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('t5hdGh86G', TRUE);
    require("parts/contract/Contract.php");
    break;
  case "editcontract":
    $title="Edit Contract";
    $scriptArray[sizeof($scriptArray)]="delete";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('FD3rasG34dd', TRUE);
    require("parts/contract/Edit_Contract.php");
    break;
  case "kill":
    $title="Kills";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('Ksf4t6Gws3', TRUE);
    require("parts/kills/Kills.php");
    break;
  case "editkill":
    $title="Edit Kill";
    $scriptArray[sizeof($scriptArray)]="delete";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('45Fsc35G53', TRUE);
    require("parts/kills/Edit_Kills.php");
    break;
  case "logtable":
    if($userfuntions->isregisterd()==1 && $_GET["first"]==0)
    {
      $userfuntions->updateLogTable($_GET["type"], $data, $dbf);
      $userfuntions->updateLogArrays($data, $dbf);
    }
    $title="Logs";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('HyG34v5dj4', TRUE);
    require("parts/log/Log_Table.php");
    break;
  case "log":
    $title="Log";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('gt5fhsb64', TRUE);
    require("parts/log/Log.php");
    break;
  case "editlog":
    $title="Edit";
    $scriptArray[sizeof($scriptArray)]="delete";
    $scriptArray[sizeof($scriptArray)]="textarea";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('5gJHk452Gs', TRUE);
    require("parts/log/Edit_Log.php");
    break;
  case "unittable":
    $title="Organization";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('j6Fr4F7k0cs8', TRUE);
    require("parts/unit/Unit_Table.php");
    break;
  case "unit":
    $title="Unit";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('j6Fr4F7k0cs8', TRUE);
    require("parts/unit/Unit.php");
    break;
  case "editunit":
    $title="Edit Unit";
    $scriptArray[sizeof($scriptArray)]="delete";
    $scriptArray[sizeof($scriptArray)]="textarea";
    $scriptArray[sizeof($scriptArray)]="image";
    $scriptArray[sizeof($scriptArray)]="unitlevelimage";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('hd7jkV4dg78', TRUE);
    require("parts/unit/Edit_Unit.php");
    break;
  case "personneltable":
    $title="Personnel";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('T56ujjd3n73FG', TRUE);
    require("parts/personnel/Personnel_Table.php");
    break;
  case "personnel":
    $title="Personnel";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('F3xVH894Vdsv', TRUE);
    require("parts/personnel/Personnel.php");
    break;
  case "editpersonnel":
    $title="Edit Personnel";
    $scriptArray[sizeof($scriptArray)]="delete";
    $scriptArray[sizeof($scriptArray)]="textarea";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('Mbs35cED2daj', TRUE);
    require("parts/personnel/Edit_Personnel.php");
    break;
  case "equipmenttable":
    $title="Equipments &amp; Vehicles";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('d5Uy76hG54', TRUE);
    require("parts/equipment/Equipment_Table.php");
    break;
  case "equipment":
    $title="Equipments";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('g6afyHgJhHu87F', TRUE);
    require("parts/equipment/Equipment.php");
    break;
  case "editequipment":
    $title="Edit Equipment";
    $scriptArray[sizeof($scriptArray)]="delete";
    $scriptArray[sizeof($scriptArray)]="textarea";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('Jdc56GHd46R5v', TRUE);
    require("parts/equipment/Edit_Equipment.php");
    break;
  case "tro":
    $title="TRO";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('hyk74Gd434', TRUE);
    require("parts/tro/TRO.php");
    break;
  case "edittro":
    $title="Edit TRO";
    $scriptArray[sizeof($scriptArray)]="delete";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('h4yt6f9eDtu', TRUE);
    require("parts/tro/Edit_TRO.php");
    break;
  case "trotable":
    $title="Technical Readouts";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('gT6uj4D67J', TRUE);
    require("parts/tro/TRO_Table.php");
    break;
  case "controlsheettable":
    $title="Controlsheets";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('5gDh4v7vFhs6', TRUE);
    require("parts/controlsheet/Controlsheet_Table.php");
    break;
  case "gallery":
    $title="Image";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('FGk348dEDf', TRUE);
    require("parts/gallery/Gallery.php");
    break;
  case "editgallery":
    $title="Add Image";
    $scriptArray[sizeof($scriptArray)]="delete";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('Ir4cwe57FdC', TRUE);
    require("parts/gallery/Edit_Gallery.php");
    break;
  case "gallerytable":
    $title="Images";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('Ziq93mdD34', TRUE);
    require("parts/gallery/Gallery_Table.php");
    break;
  case "editimage":
    $title="Edit Gallery Image";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('Fr5v30m3F5', TRUE);
    require("parts/gallery/Edit_Image.php");
    break;
  case "editcomment":
    $title="Edit comment";
    $scriptArray[sizeof($scriptArray)]="delete";
    $scriptArray[sizeof($scriptArray)]="textarea";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('9Gu4VdtJ453', TRUE);
    require("parts/log/Edit_Comment.php");
    break;
  case "login":
    $title="Login";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('gh5sxcBdu4', TRUE);
    require("parts/login/Login.php");
    break;
  case "loginfailed":
    $title="Login Failed";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('wxcV35g7f2', TRUE);
    require("parts/errors/Login_Failed.php");
    break;
  case "logout":
    $userfuntions->logOut($dbf);
    header("location:index.php");
    break;
  case "site":
    $title="Site Options";
    $scriptArray[sizeof($scriptArray)]="delete";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('huy5g4Ghj4H', TRUE);
    require("parts/setup/Setup_Site.php");
    break;
  case "command":
    $title="Command Options";
    $scriptArray[sizeof($scriptArray)]="delete";
    $scriptArray[sizeof($scriptArray)]="textarea";
    $scriptArray[sizeof($scriptArray)]="image";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('H4dc35F43cs', TRUE);
    require("parts/setup/Setup_Command.php");
    break;
  case "toe":
    $title="TOE Options";
    $scriptArray[sizeof($scriptArray)]="delete";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('hr2sDs257S8', TRUE);
    require("parts/setup/Setup_TOE.php");
    break;
  case "users":
    $title="User Management";
    $scriptArray[sizeof($scriptArray)]="delete";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('V2tyU8lMT', TRUE);
    require("parts/user/User_Mngmnt.php");
    break;
  case "profile":
    $title="User profile";
    $scriptArray[sizeof($scriptArray)]="delete";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    if($_SESSION['SESS_TYPE']!='1')
    {
      define('ty7Ui54F5', TRUE);
      require("parts/user/User_Profile.php");
    }
    else
    {
      define('J67bF536hx', TRUE);
      require("parts/user/User_Profile_Admin.php");
    }
    break;
  case "accessdenied":
    $title="Access Denied";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('h68Yr57F4', TRUE);
    require("parts/errors/Access_Denied.php");
    break;
  case "incorrectargument":
    $title="Access Denied";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('y58Cs3F3g', TRUE);
    require("parts/errors/Invalid_Argument.php");
    break;
  case "status":
    $title="Status";
    $headerbar="back";
    require("parts/headersbars/header.php");
    require("parts/headersbars/back_navbar.php");
    define('F5gf47gDc', TRUE);
    require("parts/status/Status.php");
    break;
    //Login checks, uploads and parsers
  case "checklogin":
    require("htdocs/dbsetup.php");
    $myusername=stripslashes($_POST['myusername']);
    $myusername=mysql_real_escape_string($myusername);
    $mypassword=stripslashes($_POST['mypassword']);
    $mypassword=mysql_real_escape_string($mypassword);
    $remember=stripslashes($_POST['remember']);
    $remember=mysql_real_escape_string($remember);
    $userfuntions=new UserFunctions();
    $login=$userfuntions->logincheck($myusername, $mypassword, $remember, $ip, $dbf);
    if($login==1)
    {
      header("location:index.php");
    }
    else
    {
      header("location:index.php?action=login&error=failed");
    }
    break;
  case "imageupload":
    require("includes/Up_File.php");
    $upimages=new Upfile(true);
    header($upimages->handleimages());
    break;
  case "pdfupload":
    require("includes/Up_File.php");
    $upimages=new Upfile(true);
    header($upimages->handlecontrolsheets());
    break;
  case "logquery":
    require("includes/Log_Parser.php");
    $logparser=new Logparser();
    header($logparser->parse());
    break;
  case "userquery":
    require("includes/User_Parser.php");
    $userparser=new Userparser();
    header($userparser->parse());
    break;
  case "setupquery":
    require("includes/Setup_Parser.php");
    $setupparser=new Setupparser();
    header($setupparser->parse());
    break;
  case "setupsitequery":
    require("includes/Setup_Site_Parser.php");
    $setupsiteparser=new Setupsiteparser();
    header($setupsiteparser->parse());
    break;
  case "personnelquery":
    require("includes/Personnel_Parser.php");
    $personnelparser=new Personnelparser();
    header($personnelparser->parse());
    break;
  case "unitquery":
    require("includes/Unit_Parser.php");
    $unitparser=new Unitparser();
    header($unitparser->parse());
    break;
  case "troquery":
    require("includes/TRO_Parser.php");
    $troparser=new Troparser();
    header($troparser->parse());
    break;
  case "contractquery":
    require("includes/Contract_Parser.php");
    $contractparser=new Contractparser();
    header($contractparser->parse());
    break;
  case "equipmentquery":
    require("includes/Equipment_Parser.php");
    $equipmentparser=new Equipmentparser();
    header($equipmentparser->parse());
    break;
  case "killsquery":
    require("includes/Kills_Parser.php");
    $killsparser=new Killsparser();
    header($killsparser->parse());
    break;
  case "galleryquery":
    require("includes/Gallery_Parser.php");
    $galleryparser=new Galleryparser();
    header($galleryparser->parse());
    break;

  default:
      $title="Main";
      $headerbar="front";
      require("parts/headersbars/header.php");
      require("parts/headersbars/front_leftbar.php");
      define('Gyu53Hkl3', TRUE);
      require("parts/front/Front.php");
    
}
define('t2sl3ofGKlh', TRUE);
require("parts/headersbars/footer.php");
?>