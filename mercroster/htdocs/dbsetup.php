<?
$dbhost = 'localhost:3306';
$dbuser = 'INSERT_HOST';
$dbpass = 'INSERT_PASSWORD';

$conn = mysql_connect($dbhost, $dbuser, $dbpass);
if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}
mysql_select_db( 'mercroster' );
?>
