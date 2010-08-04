<?php
if(!defined('j6Fr4F7k0cs8'))
{
  header('HTTP/1.0 404 not found');
  exit;
}
require("htdocs/dbsetup.php");
$parent=$_GET["unit"];
$parent = stripslashes($parent);
$parent = mysql_real_escape_string($parent);


function calculateRank($gunnery, $piloting)
{
  $ratingc = $gunnery + $piloting;
  $rating="";
  if ($ratingc>11)
  {
    $rating="Ultra Green";
  } elseif ($ratingc>9)
  {
    $rating="Green";
  } elseif ($ratingc>7)
  {
    $rating="Regular";
  } elseif ($ratingc>5)
  {
    $rating="Veteran";
  } elseif ($ratingc>3)
  {
    $rating="Elite";
  } else
  {
    $rating="Ultra Elite";
  }
  return $rating;
}

function calculateInfantryRank($gunnery)
{
  $rating="";
  if ($gunnery>5)
  {
    $rating="Ultra Green";
  } elseif ($gunnery>4)
  {
    $rating="Green";
  } elseif ($gunnery>3)
  {
    $rating="Regular";
  } elseif ($gunnery>2)
  {
    $rating="Veteran";
  } elseif ($gunnery>1)
  {
    $rating="Elite";
  } else
  {
    $rating="Ultra Elite";
  }
  return $rating;
}


function generateorganizationlist($parent, $organizationAy)
{
  $oArray[0]=$parent;
  $tArray=array();
  for($i=0; $i<sizeof($organizationAy); $i++)
  {
    $temp=$organizationAy[$i];
    if($temp[1]==$parent)
    {
      $tArray[sizeof($tArray)]=$temp;
    }
  }

  for($i=0; $i<sizeof($tArray); $i++)
  {
    $temp=$tArray[$i];
    $oArray[sizeof($oArray)]=generateorganizationlist($temp[0], $organizationAy);
  }
  return $oArray;
}

function getUnit($ID, $unitArray)
{
  $returnArray = array();
  for($i=0; $i<sizeof($unitArray); $i++)
  {
    $array=$unitArray[$i];
    if($array[0]==$ID)
    {
      $returnArray=$array;
      break;
    }
  }
  return $returnArray;
}

function getCrews($ID, $crewsArray)
{
  $returnArray = array();
  $count=sizeof($crewsArray);
  for($i=0; $i<$count; $i++)
  {
    $array=$crewsArray[$i];
    if($array[0]==$ID)
    {
      $returnArray[sizeof($returnArray)]=$array;
    }
  }
  return $returnArray;
}

function generateorganizationchart($recursionArray, $unitArray, $crewsArray, $upperid)
{
  $uCount=sizeof($recursionArray)-1; //because uppermost level is page itself
  $uID=$recursionArray[0]; //this unit id
  $unit=getUnit($uID, $unitArray); //find unit from unit array
  $vehicleType=$unit[3];

  echo "<table class='main' border='0' cellspacing='0' cellpadding='0'>\n";
  for($i=1; $i<sizeof($recursionArray); $i++)
  {
    echo "<tr>\n";
    echo "<td class='organization_left_empty'></td>\n";
    if($i!=sizeof($recursionArray)-1)
    {
      echo "<td class='organization_vertical_line'></td>\n";
    }
    else
    {
      echo "<td class='organization_vertical_line_white' valign='top'><table border='0' cellpadding='0' cellspacing='0'>\n";
      echo "<tr><td class='organization_vertical_line_small'></td></tr>\n";
      echo "<tr><td class='organization_vertical_line_white'></td></tr>\n";
      echo "</table></td>\n";
    }

    echo "<td class='organization_empty' valign='top'><table border='0' cellpadding='0' cellspacing='0'>\n";
    echo "<tr><td class='organization_horizontal_padding'></td></tr>\n";
    echo "<tr><td class='organization_horizontal_line'></td></tr>\n";
    echo "<tr><td class='organization_empty'></td></tr>\n";
    echo "</table></td>\n";

    echo "<td>\n";
    parseunittables($recursionArray[$i], $unitArray, $crewsArray, $upperid); //recursive call to genarate next level of units under uppermost unit
    echo "</td>\n";
    echo "</tr>\n";
  }
  echo "</table>\n";
}

function parseunittables($recursionArray, $unitArray, $crewsArray, $upperid)
{
  $uCount = sizeof($recursionArray)-1; //because uppermost level is page itself
  $uID = $recursionArray[0]; //this unit id
  $unit = getUnit($uID, $unitArray); //find unit from unit array

  $vehicleType=$unit[3];
  //Makes Unit parts of the tables
  if(!$uCount==0)
  {
    //unit level table
    echo "<table class='main' border='0' cellpadding='0' cellspacing='0'>\n";
    echo "<tr>\n";
    echo "<td colspan='4'>\n";
     
    //table under unit level table containing table header information
    echo "<table class='organizationtable' style='background-color: {$vehicleType};' border='0'>\n";
    echo "<tr>\n";
    $lvlimagepath = "./images/unitlevel/ul{$unit[8]}.png";
    echo "<td style='width: 58px;'>\n";
    echo "<table border='0' cellpadding='0' cellspacing='0'>\n";
    echo "<tr><td><img class='unitlevelimage' src='{$lvlimagepath}' alt='$lvlimagepath' /></td></tr>\n";
    echo "<tr><td><img class='unittypeimage' src='./images/unittype/{$unit[6]}' alt='{$unit[6]}' /></td></tr>\n";
    echo "</table>\n";
    echo "</td>\n";
    if(isset($_SESSION['SESS_NAME']) && $_SESSION['SESS_TYPE']<='3')
    {
      echo "<td style='width: 42px;'></td>\n";
      //edit link
      echo "<th class='title'>\n";
      echo "<a class='organizationlink' href='index.php?action=unit&amp;unit={$uID}'>{$unit[1]}</a>\n";
      echo "</th>\n";
      echo "<td style='width:50px;'>\n";
      //movement button 1
      echo "<form action='index.php?action=unitquery' method='post'>\n";
      echo "<div>\n";
      $uppersid=hasupperparellel($uID, $unitArray);
      if($uppersid[0]>0)
      {
        echo "<input type='hidden' name='QueryType' value='Move' />\n";
        echo "<input type='hidden' name='ID' value='{$uppersid[2]}' />\n";
        echo "<input type='hidden' name='otherid' value='{$uppersid[0]}' />\n";
        echo "<input type='hidden' name='otherprefpos' value='{$uppersid[1]}' />\n";
        echo "<input type='hidden' name='prefpos' value='{$uppersid[3]}' />\n";
        echo "<input type='hidden' name='parent' value='{$upperid}' />\n";
        echo "<input class='organizationbutton' type='submit' value='Up' />\n";
      }
      else
      {
        echo "<input class='organizationbutton' type='submit' value='Up' disabled='disabled' />\n";
      }
      echo "</div>\n";
      echo "</form>\n";
      echo "</td>\n";
      echo "<td style='width:50px;'>\n";
      //movement button 2
      echo "<form action='index.php?action=unitquery' method='post'>\n";
      echo "<div>\n";
      $lowersid=haslowerparellel($uID, $unitArray);
      if($lowersid[0]>0)
      {
        echo "<input type='hidden' name='QueryType' value='Move' />\n";
        echo "<input type='hidden' name='ID' value='{$lowersid[2]}' />\n";
        echo "<input type='hidden' name='otherid' value='{$lowersid[0]}' />\n";
        echo "<input type='hidden' name='otherprefpos' value='{$lowersid[1]}' />\n";
        echo "<input type='hidden' name='prefpos' value='{$lowersid[3]}' />\n";
        echo "<input type='hidden' name='parent' value='{$upperid}' />\n";
        echo "<input class='organizationbutton' type='submit' value='Down' />\n";
      }
      else
      {
        echo "<input class='organizationbutton' type='submit' value='Down' disabled='disabled' />\n";
      }
      echo "</div>\n";
      echo "</form>\n";
      echo "</td>\n";
    }
    else
    {
      //echo "<th class='notloggedtitle'>{$unit[1]}</th>\n";
      echo "<th class='notloggedtitle'><a class='organizationlink' href='index.php?action=unit&amp;unit={$uID}'>{$unit[1]}</a></th>\n";
      echo "<td style='width: 58px;'></td>\n";
    }
    echo "</tr>\n";
    echo "</table>\n";

    //add spacing between unit tables
    echo "<table border='0' cellpadding='0' cellspacing='0'>\n";
    echo "<tr>\n";
    echo "<td class='organization_empty'></td>\n";
    echo "<td class='organization_vertical_organization_gap_line' colspan='4'>\n";
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
     
    echo "</td>\n";
    echo "</tr>\n";

    //add new units
    for($i=1; $i<sizeof($recursionArray); $i++)
    {
      echo "<tr>\n";
      echo "<td class='organization_empty'></td>\n";
      if($i!=sizeof($recursionArray)-1)
      {
        echo "<td class='organization_vertical_line'></td>\n";
      }
      else
      {
        echo "<td class='organization_vertical_line_white' valign='top'><table border='0' cellpadding='0' cellspacing='0'>\n";
        echo "<tr><td class='organization_vertical_line_small'></td></tr>\n";
        echo "<tr><td class='organization_vertical_line_white'></td></tr>\n";
        echo "</table></td>\n";
      }

      echo "<td class='organization_empty' valign='top'><table border='0' cellpadding='0' cellspacing='0'>\n";
      echo "<tr><td class='organization_horizontal_padding'></td></tr>\n";
      echo "<tr><td class='organization_horizontal_line'></td></tr>\n";
      echo "<tr><td class='organization_empty'></td></tr>\n";
      echo "</table></td>\n";

      echo "<td>\n";
      parseunittables($recursionArray[$i], $unitArray, $crewsArray, $upperid); //recursive call to make inner unit tables
      echo "</td>\n";
      echo "</tr>\n";
    }
    echo "</table>\n";
  }

  //Make Personnel parts of the tables as there is no more units under this unit level
  else
  {
    $crews=getCrews($uID, $crewsArray);
    echo "<table class='lance' border='0'>\n";
    echo "<tr>\n";
    echo "<td colspan='6'>\n";
    echo "<table class='organizationtable' style='background-color: {$vehicleType};' border='0'>\n";
    echo "<tr>\n";
    $lvlimagepath = "./images/unitlevel/ul{$unit[8]}.png";
    echo "<td style='width: 58px;'>\n";
    echo "<table border='0' cellpadding='0' cellspacing='0'>\n";
    echo "<tr><td><img class='unitlevelimage' src='{$lvlimagepath}' alt='$lvlimagepath' /></td></tr>\n";
    echo "<tr><td><img class='unittypeimage' src='./images/unittype/{$unit[6]}' alt='{$unit[6]}' /></td></tr>\n";
    echo "</table>\n";
    echo "</td>\n";

    if(isset($_SESSION['SESS_NAME']) && $_SESSION['SESS_TYPE']<'4')
    {
      //edit link
      echo "<td style='width: 42px;'></td>\n";
      echo "<th class='title'>\n";
      echo "<a class='organizationlink' href='index.php?action=unit&amp;unit={$uID}'>{$unit[1]}</a>\n";
      echo "</th>\n";
      echo "<td style='width:50px;'>\n";
      //movement button 1
      echo "<form action='index.php?action=unitquery' method='post'>\n";
      echo "<div>\n";
      $uppersid=hasupperparellel($uID, $unitArray);
      if($uppersid[0]>0)
      {
        echo "<input type='hidden' name='QueryType' value='Move' />\n";
        echo "<input type='hidden' name='ID' value='{$uppersid[2]}' />\n";
        echo "<input type='hidden' name='otherid' value='{$uppersid[0]}' />\n";
        echo "<input type='hidden' name='otherprefpos' value='{$uppersid[1]}' />\n";
        echo "<input type='hidden' name='prefpos' value='{$uppersid[3]}' />\n";
        echo "<input type='hidden' name='parent' value='{$upperid}' />\n";
        echo "<input class='organizationbutton' type='submit' value='Up' />\n";
      }
      else
      {
        echo "<input class='organizationbutton' type='submit' value='Up' disabled='disabled' />\n";
      }
      echo "</div>\n";
      echo "</form>\n";
      echo "</td>\n";
      echo "<td style='width:50px;'>\n";
      //movement button 2
      echo "<form action='index.php?action=unitquery' method='post'>\n";
      echo "<div>\n";
      $lowersid=haslowerparellel($uID, $unitArray);
      if($lowersid[0]>0)
      {
        echo "<input type='hidden' name='QueryType' value='Move' />\n";
        echo "<input type='hidden' name='ID' value='{$lowersid[2]}' />\n";
        echo "<input type='hidden' name='otherid' value='{$lowersid[0]}' />\n";
        echo "<input type='hidden' name='otherprefpos' value='{$lowersid[1]}' />\n";
        echo "<input type='hidden' name='prefpos' value='{$lowersid[3]}' />\n";
        echo "<input type='hidden' name='parent' value='{$upperid}' />\n";
        echo "<input class='organizationbutton' type='submit' value='Down' />\n";
      }
      else
      {
        echo "<input class='organizationbutton' type='submit' value='Down' disabled='disabled' />\n";
      }
      echo "</div>\n";
      echo "</form>\n";
      echo "</td>\n";
    }
    else
    {
      //echo "<th class='notloggedtitle'>{$unit[1]}</th>\n";
      echo "<th class='notloggedtitle'><a class='organizationlink' href='index.php?action=unit&amp;unit={$uID}'>{$unit[1]}</a></th>\n";
      echo "<td style='width: 58px;'></td>\n";
    }
    echo "</tr>\n";
    echo "</table>\n";
    echo "</td>\n";

    echo "</tr>\n";
    for($i=0; $i<sizeof($crews); $i++)
    {
      $crew=$crews[$i];
      if(is_numeric($crew[Piloting]) && is_numeric($crew[Gunnery]))
      {
        $rating=calculateRank($crew[Gunnery], $crew[Piloting]);
      }
      if(!is_numeric($crew[Piloting]) && is_numeric($crew[Gunnery]))
      {
        $rating=calculateInfantryRank($crew[Gunnery]);
      }
      if(is_numeric($crew[Piloting]) && !is_numeric($crew[Gunnery]))
      {
        $rating=calculateInfantryRank($crew[Piloting]);
      }
       
      echo "<tr>\n";     
      echo "<td class='experiancetd'>{$rating}</td>\n";
      echo "<td class='calltd'>{$crew[callsign]}</td>\n";
      echo "<td class='ranktd'>{$crew[rankname]}</td>\n";
      echo "<td class='nametd'><a class='personnellink' href='index.php?action=personnel&amp;personnel={$crew[pid]}'>{$crew[fname]} {$crew[lname]}</a></td>\n";    
      echo "<td class='ranktd'>{$crew[Gunnery]} / {$crew[Piloting]}</td>\n";
      echo "<td class='vehicletd'><a class='personnellink' href='index.php?action=equipment&amp;equipment={$crew[uid]}'>{$crew[subtype]} {$crew[vname]}</a></td>\n";

      echo "</tr>\n";
    }
    echo "</table>\n";
    //add spacing between unit tables
    echo "<table border='0' cellpadding='0' cellspacing='0'>\n";
    echo "<tr>\n";
    echo "<td class='organization_vertical_organization_gap' colspan='4'>\n";
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
  }
}

function hasupperparellel($id, $organizationArray)
{
  $returnarray[0]=0;
  $returnarray[1]=0;
  $returnarray[2]=$id;
  $returnarray[3]=0;
  $parent=0;
  $prefpos=0;
  for ($index=0; $index<sizeof($organizationArray); $index++ )
  {
    $array=$organizationArray[$index];
    if($id==$array['id'])
    {
      $parent=$array['parent'];
      $prefpos=$array['prefpos'];
      $returnarray[3]=$prefpos;
      break;
    }
  }
  for ($index=0; $index<sizeof($organizationArray); $index++ )
  {
    $array=$organizationArray[$index];
    if($parent==$array['parent'] && $prefpos==($array['prefpos']+1))
    {
      $returnarray[0]=$array['id'];
      $returnarray[1]=$array['prefpos'];
      break;
    }
  }
  return $returnarray;
}

function haslowerparellel($id, $organizationArray)
{
  $returnarray[0]=0;
  $returnarray[1]=0;
  $returnarray[2]=$id;
  $returnarray[3]=0;
  $parent=0;
  $prefpos=0;
  for ($index=0; $index<sizeof($organizationArray); $index++ )
  {
    $array=$organizationArray[$index];
    if($id==$array['id'])
    {
      $parent=$array['parent'];
      $prefpos=$array['prefpos'];
      $returnarray[3]=$prefpos;
      break;
    }
  }
  for ($index=0; $index<sizeof($organizationArray); $index++ )
  {
    $array=$organizationArray[$index];
    if($parent==$array['parent'] && $prefpos==($array['prefpos']-1))
    {
      $returnarray[0]=$array['id'];
      $returnarray[1]=$array['prefpos'];
      break;
    }
  }
  return $returnarray;
}

function rectest($selectedUnits, $array, $parent)
{
  $tempArray=array();
  while($key=array_search($parent, $array))
  {
    array_push($tempArray, $key);
    unset($array[$key]);
    $retarray=rectest($selectedUnits, $array, $key);
    foreach ($retarray as $variable)
    {
      array_push($tempArray, $variable);
    }
  }
  return $tempArray;
}

$unitResult=$dbf->queryselect("SELECT id, parent FROM unit;");
$allUnitsArray=$dbf->resulttoarray($unitResult);
$unitArray=array();

foreach ($allUnitsArray as $unit)
{
  $unitArray[$unit['id']]=$unit['parent'];
}

$selectedUnits=array();
//$s=sizeof($unitArray);

$unitString=" u.id='{$parent}'";

$selectedUnits=rectest($selectedUnits, $unitArray, $parent);


for($i=0; $i<sizeof($selectedUnits); $i++)
{
  $unitString.=" OR u.id='{$selectedUnits[$i]}'";
}
//$s=sizeof($selectedUnits);
//echo "units:{$unitString} size($s);";

//$organizationResult=$dbf->queryselect("SELECT u.id, u.name, u.type, ut.color, u.prefpos, u.parent, u.limage, u.rimage, u.Level FROM unit u LEFT JOIN unittypes ut on u.type=ut.id ORDER BY prefpos;");
$organizationResult=$dbf->queryselect("SELECT u.id, u.name, u.type, ut.color, u.prefpos, u.parent, u.limage, u.rimage, u.Level FROM unit u LEFT JOIN unittypes ut on u.type=ut.id WHERE {$unitString} ORDER BY prefpos;");
$organizationArray=$dbf->resulttoarray($organizationResult);

//$crewResult=$dbf->queryselect("SELECT c.parent, r.rankname, c.lname, c.fname, v.name as vname, v.subtype, c.id as pid, v.id as uid, et.requirement FROM crew c LEFT JOIN ranks r ON c.rank=r.number LEFT JOIN equipment v ON c.id=v.crew LEFT JOIN equipmenttypes et ON v.type=et.id ORDER BY c.rank DESC, c.joiningdate ASC, c.lname ASC, c.id ASC;");
$crewResult=$dbf->queryselect("SELECT c.parent, r.rankname, c.lname, c.fname, c.callsign, v.name as vname, v.subtype, c.id as pid, v.id as uid, et.requirement FROM crew c LEFT JOIN ranks r ON c.rank=r.number LEFT JOIN equipment v ON c.id=v.crew LEFT JOIN equipmenttypes et ON v.type=et.id LEFT JOIN unit u ON c.parent=u.id WHERE {$unitString} ORDER BY c.rank DESC, c.joiningdate ASC, c.lname ASC, c.id ASC;");
$crewsArray=$dbf->resulttoarray($crewResult);

for ($i=0; $i<sizeof($crewsArray); $i++)
{
  $tempAr=$crewsArray[$i];
  $tempResult=$dbf->queryselect("SELECT s.value, st.shortname, st.name, pt.type FROM skills s LEFT JOIN skilltypes st ON s.skill=st.id LEFT JOIN skillrequirements sr ON st.id=sr.skilltype LEFT JOIN crewtypes pt ON pt.id=sr.personneltype WHERE s.person='{$tempAr[pid]}' AND pt.id='{$tempAr[requirement]}' GROUP BY st.name;");
  while($tempArray=mysql_fetch_array($tempResult, MYSQL_ASSOC))
  {
    $skillArray=array("{$tempArray[shortname]}" => $tempArray[value]);
    $tempAr=array_merge($tempAr, $skillArray);
  }
  $crewsArray[$i]=$tempAr;
}

$counter=0;
$returnArray = array();
while($counter<sizeof($organizationArray))
{
  $temp=$organizationArray[$counter];
  $organizationAy[$counter]= array($temp[0], $temp[5]);
  $counter++;
}
$recArray = generateorganizationlist($parent, $organizationAy);

$a;
$upper;
if($parent==0)
{
  $title="Units Deteched from Table of Organization";
}
else
{
  for ($index=0; $index<sizeof($organizationArray); $index++ )
  {
    $array=$organizationArray[$index];
    if($parent==$array[0])
    {
      $title=$array[1];
      $upper=$array[5];
      $a=$array;
      break;
    }
  }
  $parellalUnitsResult=$dbf->queryselect("SELECT id, parent, prefpos FROM unit WHERE parent='{$upper}' ORDER BY prefpos;");
  $parellalUnitsArray=$dbf->resulttoarray($parellalUnitsResult);
}

echo "<div id='content'>\n";
echo "<h1 class='headercenter'>$title</h1>\n";
echo "<table border='0' cellpadding='0' cellspacing='0'>\n";
echo "<tr><td><img class='unitlevelimage' src='./images/unitlevel/ul{$a[8]}.png' alt='{$a[8]}' /></td></tr>\n";
echo "<tr>\n";
echo "<td><img class='unittypeimage' src='./images/unittype/{$a[6]}' alt='{$a[6]}' /></td>\n";

if(isset($_SESSION['SESS_NAME']) && $_SESSION['SESS_TYPE']<'4')
{
  if($parent!=0)
  {
    echo "<td>\n";
    echo "<a class='organizationlink' href='index.php?action=unit&amp;unit={$parent}'>{$title}</a>\n";
    echo "</td>\n";

    echo "<td style='width:50px;'>\n";
    //movement button 1
    echo "<form action='index.php?action=unitquery' method='post'>\n";
    echo "<div>\n";
    $uppersid=hasupperparellel($a[0], $parellalUnitsArray);
    if($uppersid[0]>0)
    {
      echo "<input type='hidden' name='QueryType' value='Move' />\n";
      echo "<input type='hidden' name='ID' value='{$uppersid[2]}' />\n";
      echo "<input type='hidden' name='otherid' value='{$uppersid[0]}' />\n";
      echo "<input type='hidden' name='otherprefpos' value='{$uppersid[1]}' />\n";
      echo "<input type='hidden' name='prefpos' value='{$uppersid[3]}' />\n";
      echo "<input type='hidden' name='parent' value='{$uppersid[2]}' />\n";
      echo "<input class='organizationbutton' type='submit' value='Up' />\n";
    }
    else
    {
      echo "<input class='organizationbutton' type='submit' value='Up' disabled='disabled' />\n";
    }
    echo "</div>\n";
    echo "</form>\n";
    echo "</td>\n";
    echo "<td style='width:50px;'>\n";
    //movement button 2
    echo "<form action='index.php?action=unitquery' method='post'>\n";
    echo "<div>\n";
    $lowersid=haslowerparellel($a[0], $parellalUnitsArray);
    if($lowersid[0]>0)
    {
      echo "<input type='hidden' name='QueryType' value='Move' />\n";
      echo "<input type='hidden' name='ID' value='{$lowersid[2]}' />\n";
      echo "<input type='hidden' name='otherid' value='{$lowersid[0]}' />\n";
      echo "<input type='hidden' name='otherprefpos' value='{$lowersid[1]}' />\n";
      echo "<input type='hidden' name='prefpos' value='{$lowersid[3]}' />\n";
      echo "<input type='hidden' name='parent' value='{$lowersid[2]}' />\n";
      echo "<input class='organizationbutton' type='submit' value='Down' />\n";
    }
    else
    {
      echo "<input class='organizationbutton' type='submit' value='Down' disabled='disabled' />\n";
    }
    echo "</div>\n";
    echo "</form>\n";
    echo "</td>\n";
  }
  else
  {
    echo "<td>\n";
    echo "<a class='organizationlink' href='index.php?action=editunit&amp;unit=0'>Add new Unit</a>\n";
    echo "</td>\n";
  }

}
echo "</tr>\n";
echo "</table>\n";
echo "<table border='0' cellpadding='0' cellspacing='0'>\n";
echo "<tr>\n";
echo "<td class='organization_left_empty' style='height: 20px;'></td>\n";
echo "<td class='organization_vertical_line'></td>\n";
echo "</tr>\n";
echo "</table>\n";
if(sizeof($recArray)>1)
{
  generateorganizationchart($recArray, $organizationArray, $crewsArray, $parent);
}
echo "</div>\n";
?>