<?php
if(!defined('T56ujjd3n73FG'))
{
  header('HTTP/1.0 404 not found');
  include("../../error404.html");
  exit;
}

require("includes/BBFunctions.php");
$bbf = new BBFunctions;

/**
 * Function used to calculate persons age from their birgthday
 * @param $BDay
 * @param $CDay
 */
function age($BDay, $CDay)
{
  $bdate=$BDay;
  $byear=strtok($bdate, "-");
  $bmoth=strtok("-");
  $bday=strtok("-");

  $cdate=$CDay;
  $cyear=strtok($cdate, "-");
  $cmoth=strtok("-");
  $cday=strtok("-");

  $age=$cyear-$byear;
  if($bmoth>$cmoth)
  {
    $age=$age-1;
  }
  if($bmoth==$cmoth && $bday>$cday)
  {
    $age=$age-1;
  }
  return $age;
}

require("htdocs/dbsetup.php");
$first=$_GET['first'];
$first=stripslashes($first);
$first=mysql_real_escape_string($first);

$personnelType=$_GET["type"];
$personnelType=stripslashes($personnelType);
$personnelType=mysql_real_escape_string($personnelType);

$personnelStatus=$_GET["status"];
$personnelStatus=stripslashes($personnelStatus);
$personnelStatus=mysql_real_escape_string($personnelStatus);

require("includes/PageBar.php");
$pb=new PageBar;

$range=30;
$permission=4;

if(isset($_GET["type"]))
{
  //Personnel status. Whatever they will still be with command or not
  switch ($personnelStatus)
  {
    case "in":
      $gstatus="(c.status='Active' OR c.status='Hospitalized' OR c.status='On Leave')";
      $linkstatus="in";
      break;
    case "out":
      $gstatus="(c.status='Retired' OR c.status='Deceased' OR c.status='Missing In Action')";
      $linkstatus="out";
      break;
    default:
      $gstatus="(c.status='Active' OR c.status='Hospitalized' OR c.status='On Leave')";
      break;
  }
  
  //Check to see if any callsigns are being used - assume personnel without type get no call signs
  $callUsed = 0;
  $personelQeury="SELECT callsign FROM crew c LEFT JOIN personnelpositions p ON c.id=p.person WHERE p.personneltype='{$personnelType}' AND ";
  $personelQeury.=$gstatus;
  $personnelResult=$dbf->queryselect($personelQeury);
  while($array = mysql_fetch_array($personnelResult, MYSQL_BOTH))
  {
 	if(!is_null($array[callsign]) && $array[callsign]!="") {
 		$callUsed = 1;
 		break;
 	}
  }
  
  //Personnel without type
  if($personnelType==0)
  {
    //order
    if(isset($_GET["order"]))
    {
      $firstrow=$_GET["order"];
    }
    else
    {
      $firstrow=0;
    }
    $ordertable[0]="c.rank DESC";
    $ordertable[sizeof($ordertable)]="c.fname ASC";
    if($callUsed) {
    	$ordertable[sizeof($ordertable)]="c.callsign ASC";
    }
    $ordertable[sizeof($ordertable)]="c.bday ASC";
    $ordertable[sizeof($ordertable)]="v.subtype ASC";
    $ordertable[sizeof($ordertable)]="u.name ASC";
    $ordertable[sizeof($ordertable)]="c.status ASC";

    $order="$ordertable[$firstrow]";
    for ($i=0; $i<sizeof($ordertable); $i++)
    {
      if($i!=$firstrow)
      {
        $order.=", {$ordertable[$i]}";
      }
    }

    $personelQeury="SELECT c.id, r.rankname, r.number AS ranknumber, c.lname, c.fname, c.callsign, v.subtype, v.name AS vname, u.name AS uname, c.bday, c.status, c.notes FROM crew c LEFT JOIN ranks r ON c.rank=r.number LEFT JOIN equipment v ON c.id=v.crew LEFT JOIN unit u ON c.parent=u.id LEFT JOIN personnelpositions p ON c.id=p.person WHERE p.personneltype IS NULL AND ";
    $personelQeury.=$gstatus;
    $personelQeury.=" ORDER BY ".$order;
    $crewnumberque="SELECT COUNT(*) count FROM crew c LEFT JOIN personnelpositions p ON c.id=p.person WHERE p.personneltype IS NULL AND ";
    $crewnumberque.=$gstatus;

    $personnelResult=$dbf->queryselect($personelQeury);
    $rnumber=mysql_result($dbf->queryselect($crewnumberque), 0);

    $title="Other Personnel";
    $equippable=1;
    $skillsUsedArray=array();
  }
  //Personnels with type
  else
  {
    //Validating Personel Type
    $checkResult=$dbf->queryselect("SELECT * FROM crewtypes WHERE id='$personnelType';");
    if(mysql_num_rows($checkResult)>0)
    {
      $checkArray=mysql_fetch_array($checkResult, MYSQL_NUM);

      $skillsUsedResult=$dbf->queryselect("SELECT st.name, st.shortname, st.id FROM skillrequirements sr, skilltypes st WHERE sr.skilltype=st.id AND personneltype='{$personnelType}' ORDER BY st.name ASC;");
      $skillsUsedArray=$dbf->resulttoarray($skillsUsedResult);
      if($checkArray[5])
      {
        $skillque="SELECT c.id, r.rankname, r.number AS ranknumber, c.lname, c.fname, c.callsign, v.subtype, v.name AS vname, u.name AS uname, c.bday, c.status, c.notes";
      }
      else
      {
        $skillque="SELECT c.id, r.rankname, r.number AS ranknumber, c.lname, c.fname, c.callsign, u.name as uname, c.bday, c.status, c.notes";
      }
      $crewnumberque="SELECT COUNT(*) count FROM crew c  LEFT JOIN unit u ON c.parent=u.id ";
      $joins="";
      $where=" WHERE ct.id={$personnelType} AND c.id=pp.person AND pp.personneltype=ct.id AND ";
      $crewnumberque.=", crewtypes ct, personnelpositions pp ".$where;
      for ($i=0; $i<sizeof($skillsUsedArray); $i++)
      {
        $temp=$skillsUsedArray[$i];
        $skillque.=", s{$i}.value as {$temp[1]}";
        if($checkArray[5])
        {
          $joins.="LEFT JOIN skills s{$i} ON s{$i}.person=c.id ";
        }
        else
        {
          $joins.="LEFT JOIN skills s{$i} ON s{$i}.person=c.id ";
        }
        $where.=" s{$i}.skill='{$temp[2]}' AND";
      }

      if($checkArray[5])
      {
        $skillque.=" FROM crew c LEFT JOIN ranks r ON c.rank=r.number LEFT JOIN equipment v ON c.id=v.crew LEFT JOIN unit u ON c.parent=u.id ";
        $skillque.=$joins.", crewtypes ct, personnelpositions pp ".$where;
        //$crewnumberque.=$joins.", crewtypes ct, personnelpositions pp ".$where;
      }
      else
      {
        $skillque.=" FROM crew c LEFT JOIN ranks r ON c.rank=r.number LEFT JOIN unit u ON c.parent=u.id ";
        $skillque.=$joins.", crewtypes ct, personnelpositions pp ".$where;
        //$crewnumberque.=$joins.", crewtypes ct, personnelpositions pp ".$where;
      }
      //order
      if(isset($_GET["order"]))
      {
        $firstrow=$_GET["order"];
      }
      else
      {
        $firstrow=0;
      }
      $ordertable[0]="c.rank DESC";
      $ordertable[sizeof($ordertable)]="c.fname ASC";
      if($callUsed) {
    	$ordertable[sizeof($ordertable)]="c.callsign ASC";
      }
      for ($i=0; $i<sizeof($skillsUsedArray); $i++)
      {
        $ordertable[sizeof($ordertable)]="s{$i}.value ASC";
      }

      $ordertable[sizeof($ordertable)]="c.bday ASC";
      if($checkArray[5])
      {
        $ordertable[sizeof($ordertable)]="v.subtype ASC";
      }
      $ordertable[sizeof($ordertable)]="u.name ASC";
      $ordertable[sizeof($ordertable)]="c.status ASC";

      $order="$ordertable[$firstrow]";
      for ($i=0; $i<sizeof($ordertable); $i++)
      {
        if($i!=$firstrow)
        {
          $order.=", {$ordertable[$i]}";
        }
      }

      $personelQeury=$skillque. " " . $gstatus . " ORDER BY " . $order . " LIMIT $first, $range;";

      $personnelResult=$dbf->queryselect($personelQeury);

      $crewnumberque.=" " . $gstatus;
      $rnumber=mysql_result($dbf->queryselect($crewnumberque), 0);
      $title=$checkArray[1];
      $equippable=$checkArray[5];
    }
    else
    {
      $error=true;
      $errormsg="Personnel Type not found";
    }
  }

  //Fetching used dates data
  $datesResult=$dbf->queryselect("SELECT * FROM dates WHERE id=1;");
  $datesArray=mysql_fetch_array($datesResult, MYSQL_NUM);

  //Parsing table for certain personneltype

  //echo "$personelQeury\n";
  $mod=0;
  if(sizeof($personnelResult)>0)
  {
    echo "<div id='content'>\n";
    echo "<h1 class='headercenter'>{$title}s</h1>\n";

    if($personnelStatus!="out")
    {
      $status="in";
      $link="personneltable&amp;type={$personnelType}&amp;order={$_GET["order"]}&amp;status={$status}";
      $add="?action=personneltable&amp;type={$personnelType}&amp;status=out&amp;order={$_GET["order"]}&amp;first=0";
      $addtype="Former Personnel";
    }
    else
    {
      $status="out";
      $link="personneltable&amp;type={$personnelType}&amp;order={$_GET["order"]}&amp;status={$status}";
      $add="?action=personneltable&amp;type={$personnelType}&amp;status=in&amp;order={$_GET["order"]}&amp;first=0";
      $addtype="Current Personnel";
    }
    $pb->generatebarlink($rnumber, $first, $range, $link, $add, $addtype);

    echo "<hr />\n";
    echo "<table class='rostertable'>\n";
    echo "<thead class='rostertable'>\n";
    echo "<tr>\n";
    $tableorder=0;
    echo "<th class='rostertable'><a class='rostertabletopic' href='index.php?action=personneltable&amp;type={$personnelType}&amp;status={$linkstatus}&amp;order={$tableorder}&amp;first=0'>Rank</a></th>\n";
    $tableorder++;
    echo "<th class='rostertable'><a class='rostertabletopic' href='index.php?action=personneltable&amp;type={$personnelType}&amp;status={$linkstatus}&amp;order={$tableorder}&amp;first=0'>Name</a></th>\n";
    if($callUsed) {
    	$tableorder++;
    	echo "<th class='rostertable'><a class='rostertabletopic' href='index.php?action=personneltable&amp;type={$personnelType}&amp;status={$linkstatus}&amp;order={$tableorder}&amp;first=0'>Callsign</a></th>\n";
    }
    for ($i=0; $i<sizeof($skillsUsedArray); $i++)
    {
      $temp=$skillsUsedArray[$i];
      $tableorder++;
      echo "<th class='rostertable'><a class='rostertabletopic' href='index.php?action=personneltable&amp;type={$personnelType}&amp;status={$linkstatus}&amp;order={$tableorder}&amp;first=0'>$temp[1]</a></th>\n";
    }
    $tableorder++;
    echo "<th class='rostertable'><a class='rostertabletopic' href='index.php?action=personneltable&amp;type={$personnelType}&amp;status={$linkstatus}&amp;order={$tableorder}&amp;first=0'>Age</a></th>\n";
    if($equippable && $personnelStatus!="out")//only for those who have capasity to be assigned to vehicle and are active
    {
      $tableorder++;
      echo "<th class='rostertable'><a class='rostertabletopic' href='index.php?action=personneltable&amp;type={$personnelType}&amp;status={$linkstatus}&amp;order={$tableorder}&amp;first=0'>Vehicle</a></th>\n";
    }
     
    if($personnelStatus!="out")//only for those who are active
    {
      $tableorder++;
      echo "<th class='rostertable'><a class='rostertabletopic' href='index.php?action=personneltable&amp;type={$personnelType}&amp;status={$linkstatus}&amp;order={$tableorder}&amp;first=0'>Assigned to</a></th>\n";
    }
    $tableorder++;
    echo "<th class='rostertable'><a class='rostertabletopic' href='index.php?action=personneltable&amp;type={$personnelType}&amp;status={$linkstatus}&amp;order={$tableorder}&amp;first=0'>Status</a></th>\n";
    if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<$permission)//only for those who have rights for editing
    {
      echo "<th class='rostertable'></th>\n";
    }
    echo "</tr>\n";
    echo "</thead>\n";
    echo "<tbody class='rostertable'>\n";
    while($array = mysql_fetch_array($personnelResult, MYSQL_BOTH))
    {
      echo "<tr>\n";
      echo "<td class='rostertable'>{$array[rankname]}</td>\n";
      echo "<td class='rostertable'><a class='rostertable' href='index.php?action=personnel&amp;personnel={$array[id]}'>{$array[fname]} {$array[lname]}</a>";
      if($array[notes]!="")
      {
        echo "<img style='margin-top:2px; margin-right:2px; float:right;' src='./images/small/notes.png' alt='notes' />";
      }
      echo "</td>\n";
      if($callUsed) {
      	echo "<td class='rostertable'>{$array[callsign]}</td>\n";
      }
      for ($i=0; $i<sizeof($skillsUsedArray); $i++)
      {
        $temp=$skillsUsedArray[$i];
        if($equippable)
        {
          $j=$i+12;
        }
        else
        {
          $j=$i+10;
        }
        echo "<td class='rostertable'>$array[$j]</td>\n";
      }
      $BDay=$array[bday];
      $CDay=$datesArray[2];
      $Age=age($BDay, $CDay);
      echo "<td class='rostertable'>{$Age}</td>\n";
      if($equippable && $personnelStatus!="out")//only for those who have capasity to be assigned to vehicle
      {
        echo "<td class='rostertable'>{$array[subtype]} {$array[vname]}</td>\n";
      }
      if($personnelStatus!="out")
      {
        echo "<td class='rostertable'>{$array[uname]}</td>\n";
      }
      echo "<td class='rostertable'>{$array[status]}</td>\n";
      if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<=$permission)//only for those who have rights for editing
      {
        echo "<td class='rostertable'><a class='rostertable' href='index.php?action=editpersonnel&amp;personnel={$array[id]}'>edit</a></td>\n";
      }
      echo "</tr>\n";
    }
    echo "</tbody>\n";
    echo "</table>\n";
    echo "<hr />\n";
    $pb->generatebarlink($rnumber, $first, $range, $link, $add, $addtype);
    echo "</div>\n";
  }
}
//parsing table for personneltypes
else
{
  $range = 30;
  $link="personneltable";
  $add="?action=editpersonnel";
  $addtype=$title;
  //fetch perssonneltypes
  $personnelTypeResult=$dbf->queryselect(" SELECT * FROM crewtypes e LEFT JOIN (SELECT pp.personneltype, COUNT(pp.personneltype) as poscount FROM crew c, personnelpositions pp WHERE c.id=pp.person AND status<>'Deceased' AND status<>'Retired' GROUP BY pp.personneltype) v ON e.id=v.personneltype ORDER BY e.prefpos LIMIT 0, 30;");
  //fetch number of different personneltypes
  $rnumber=mysql_result($dbf->queryselect("SELECT COUNT(*) count FROM crewtypes;"), 0);
  //fetch number of personnel that don't have any position
  $onumber=mysql_result($dbf->queryselect("SELECT COUNT(*) count FROM crew c LEFT JOIN personnelpositions p ON c.id=p.person WHERE p.personneltype IS NULL;"), 0);

  if($rnumber>0)
  {
    echo "<div id='content'>\n";
    echo "<h1 class='headercenter'>{$title}</h1>\n";
    $pb->generatebarx($rnumber, $first, $range, $link, $permission, $add, $addtype);
    echo "<hr />\n";
    echo "<table class='rostertable'>\n";
    echo "<thead class='rostertable'>\n";
    echo "<tr>\n";
    echo "<th class='rostertabletype'>Personnel Type</th>\n";
    echo "<th class='rostertablenumber'>Number</th>\n";
    echo "</tr>\n";
    echo "</thead>\n";
    echo "<tbody class='rostertable'>\n";
    while($array = mysql_fetch_array($personnelTypeResult, MYSQL_ASSOC))
    {
      echo "<tr>\n";
      echo "<td class='rostertabletype'><a class='rostertable' href='index.php?action=personneltable&amp;type={$array[id]}&amp;order=0&amp;first=0'>{$array[type]}s</a></td>\n";
      echo "<td class='rostertablenumber'>{$array[poscount]}</td>\n";
      echo "</tr>\n";
    }
    echo "<tr>\n";
    echo "<td class='rostertabletype'><a class='rostertable' href='index.php?action=personneltable&amp;type=0&amp;order=0&amp;first=0'>Other Personnel</a></td>\n";
    echo "<td class='rostertablenumber'>{$onumber}</td>\n";
    echo "</tr>\n";
    echo "</tbody>\n";
    echo "</table>\n";
  }
  else
  {
    echo "<div class='genericheader'>\n";
    echo "<b>Note</b>\n";
    echo "</div>\n";
    echo "<div class='genericarea'>\n";
    echo "No Personnel of any kind!\n";
    echo "</div>\n";
  }
  echo "</div>\n";
}
//Error Handling
if($error)
{
  echo "<div id='content'>\n";
  echo "<div class='error'>\n";
  echo "<b>An error has occurred</b> while accessing personnel type table.<br />\n";
  echo "No personnel type found or you don't have rights to access this personnel type table.<br />\n";
  echo "Please use correct links and be sure that you have needed privileges.<br />\n";
  if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']==1)
  {
    echo "<b>Error Message</b>: ".$errormsg,"<br />\n";
  }
  echo "</div>\n";
  echo "</div>\n";
}
?>