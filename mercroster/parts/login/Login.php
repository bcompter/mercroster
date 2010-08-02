<?php
if(!defined('gh5sxcBdu4'))
{
  header('HTTP/1.0 404 not found');
  include("../error404.html");
  exit;
}
require("htdocs/dbsetup.php");
$type=$_GET['error'];

echo "<div id='content'>\n";

if(!isset($_SESSION['SESS_ID']) || (trim($_SESSION['SESS_ID']) == ''))
{
  echo "<table class='edittable'>\n";
  echo "<tr>\n";
  echo "<td>\n";

  echo "<form method='post' action='index.php?action=checklogin'>\n";
  echo "<table border='0'>\n";

  if($type=="failed")
  {
    echo "<tr>\n";
    echo "<td colspan='2'>\n";
    echo "<div class='error'>\n";
    echo "<strong>Login Failed<br />Username or Password wrong<br />Please try again</strong>\n";
    /*echo "<td colspan='2'><strong>Login Failed</strong></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td colspan='2'><strong>Username or Password wrong</strong></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td colspan='2'><strong>Please try again</strong></td>\n";
    */
    echo "</div>\n";
    echo "</td>\n";
    echo "</tr>\n";
  }
  else
  {
    echo "<tr>\n";
    echo "<td colspan='2'><strong>Member Login</strong></td>\n";
    echo "</tr>\n";
  }

  echo "<tr>\n";
  echo "<td>Username:</td>\n";
  echo "<td><input name='myusername' type='text' id='myusername' /></td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td>Password:</td>\n";
  echo "<td><input name='mypassword' type='password' id='mypassword' /></td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td>Always stay logged in:</td>\n";
  echo "<td><input name='remember' type='checkbox' /></td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td></td>\n";
  echo "<td><input type='submit' name='Submit' value='Login' /></td>\n";
  echo "</tr>\n";
  echo "</table>\n";
  echo "</form>\n";

  echo "</td>\n";
  echo "</tr>\n";
  echo "</table>\n";
}
else
{
  echo "<a href='index.php?action=logout'>logout</a>\n";
}
echo "</div>\n";
?>