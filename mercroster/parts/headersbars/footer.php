<?php
if(!defined('t2sl3ofGKlh'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}
echo"</div>\n";
echo"<div id='lowerfooter'>\n";
echo"<table class='main' border='0'>\n";
echo"<tr>\n";
echo"<td style='width: 40%'>\n";
?>
<p><a href="http://validator.w3.org/check?uri=referer"><img
	src="http://www.w3.org/Icons/valid-xhtml10-blue"
	alt="Valid XHTML 1.0 Strict"
	style="border: 0; width: 88px; height: 31px" /></a></p>
<?php
echo"</td>\n";
echo"<td style='width: 20%'>\n";
echo"BattleTech Mercenary Roster Version 0.7.7\n";
echo"<br />\n";
echo"&copy; 2009-2010 Juho Savela\n";
echo"<br />\n";
$timerstop=microtime(true);
$time=round($timerstop-$timerstart, 5);
echo"Page was created in {$time} seconds.\n";
echo"<br />\n";
echo"</td>\n";
echo"<td style='width: 40%'>\n";
?>
<p><a href="http://jigsaw.w3.org/css-validator/check/referer"><img
	style="border: 0; width: 88px; height: 31px"
	src="http://jigsaw.w3.org/css-validator/images/vcss-blue"
	alt="Valid CSS!" /></a></p>
<?php
echo"</td>\n";
echo"</tr>\n";
echo"</table>\n";
echo"</div>\n";
echo"</body>\n";
echo"</html>\n";
?>