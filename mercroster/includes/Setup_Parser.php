<?php
require("includes/Parser.php");
class Setupparser  extends Parser
{
  private function move($type, $id, $prefpos, $direction, $dbf)
  {
    //if we have crewtype placement
    if($type=="crewtypes" || $type=="equipmenttypes" || $type=="logtypes" || $type=="unitlevel" || $type=="pages")
    {
      if($direction=="Down")
      {
        $newpos=$prefpos+1;
      }
      else
      {
        $newpos=$prefpos-1;
      }
      $usedCrewTypeArray=mysql_fetch_array($dbf->queryselect("SELECT id FROM {$type} WHERE prefpos='{$newpos}';"), MYSQL_NUM);
      $otherid=$usedCrewTypeArray[0];
    }
    //echo "{$ID}=> {$newpos} | {$otherid}=>{$prefpos}";
    $queryArray[sizeof($queryArray)]="UPDATE {$type} SET prefpos='$newpos' WHERE id='{$id}';";
    $queryArray[sizeof($queryArray)]="UPDATE {$type} SET prefpos='$prefpos' WHERE id='{$otherid}';";
    return $queryArray;
  }

  /**
   * This function is used to handle log relaited squeries
   * @return string
   */
  function parse()
  {
    if(isset($_SESSION['SESS_NAME']) && $_SESSION['SESS_TYPE']<'4') //for Commanders, GMs and administrators
    {
      require("htdocs/dbsetup.php");
      $dbf=new DBFunctions;

      switch ($_POST['QueryType'])
      {
        case "Crewtype":
          switch ($_POST['QueryAction'])
          {
            case "Delete":
              if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['prefpos']))
              {
                $id=$this->strip($_POST['ID']);
                $prefpos=$this->strip($_POST['prefpos']);
                $queryArray[sizeof($queryArray)]="DELETE FROM crewtypes WHERE id='{$id}';";
                $queryArray[sizeof($queryArray)]="DELETE FROM skillrequirements WHERE personneltype='{$id}';";
                $positionresult=$dbf->queryselect("SELECT id, prefpos FROM crewtypes WHERE prefpos>'{$prefpos}';");
                while($array=mysql_fetch_array($positionresult, MYSQL_NUM))
                {
                  $pp = $array[1]-1;
                  $queryArray[sizeof($queryArray)]="UPDATE crewtypes SET prefpos='{$pp}' WHERE id='{$array[0]}';";
                }
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=toe&page=3";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Change":
              if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['crewtype']) && $this->checkposint($_POST['vehicletype']) && $this->checkposint($_POST['prefpos']))
              {
                $id=$this->strip($_POST['ID']);
                $crewtype=$this->strip($_POST['crewtype']);
                $vehicletype=$this->strip($_POST['vehicletype']);
                $squad=$this->strip($_POST['squad']);
                $equippable=$this->strip($_POST['equippable']);
                $prefpos=$this->strip($_POST['prefpos']);

                $errMSG="";
                if($crewtype=="")
                {
                  $errMSG="no crewtype";
                }
                if($errMSG=="")
                {
                  if($squad=="on")
                  {
                    $squad=1;
                  }
                  else
                  {
                    $squad=0;
                  }
                  if($piloting=="on")
                  {
                    $piloting=1;
                  }
                  else
                  {
                    $piloting=0;
                  }
                  if($equippable=="on")
                  {
                    $equippable=1;
                  }
                  else
                  {
                    $equippable=0;
                    $vehicletype=0;
                  }

                  $queryArray[sizeof($queryArray)]="UPDATE crewtypes SET type='{$crewtype}', squad='{$squad}', vehicletype='{$vehicletype}', equipment='{$equippable}' WHERE id='{$id}';";
                  $dbf->queryarray($queryArray);
                  $parseheader="location:index.php?action=toe&page=3&sub={$id}";
                }
                else
                {
                  $parseheader="location:index.php?action=toe&page=3&sub={$id}&err={$errMSG}";
                }
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Add":
              if($this->checkposint($_POST['vehicletype']))
              {
                $crewtype=$this->strip($_POST['crewtype']);
                $vehicletype=$this->strip($_POST['vehicletype']);
                $squad=$this->strip($_POST['squad']);
                $equippable=$this->strip($_POST['equippable']);

                $errMSG="";
                if($crewtype=="")
                {
                  $errMSG="no crewtype";
                }
                if($errMSG=="")
                {
                  if($squad=="on")
                  {
                    $squad=1;
                  }
                  else
                  {
                    $squad=0;
                  }
                  if($piloting=="on")
                  {
                    $piloting=1;
                  }
                  else
                  {
                    $piloting=0;
                  }
                  if($equippable=="on")
                  {
                    $equippable=1;
                  }
                  else
                  {
                    $equippable=0;
                    $vehicletype=0;
                  }
                  $rResult=$dbf->queryselect("SELECT COUNT(*) count FROM crewtypes;");
                  $crewtypesnumber=mysql_result($rResult, 0)+1;
                  $queryArray[sizeof($queryArray)] = "INSERT INTO crewtypes (type, squad, vehicletype, prefpos, equipment) VALUES ('{$crewtype}', '{$squad}', '{$vehicletype}', '{$crewtypesnumber}', '{$equippable}');";
                  $dbf->queryarray($queryArray);
                  $parseheader="location:index.php?action=toe&page=3";
                }
                else
                {
                  $parseheader="location:index.php?action=toe&page=3&err={$errMSG}";
                }
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;
          }
          break;
          
        case "pagesmove":
	        if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['prefpos']))
	        {
	          $id=$this->strip($_POST['ID']);
	          $prefpos=$this->strip($_POST['prefpos']);
	          $direction=$this->strip($_POST['QueryAction']);
	          $dbf->queryarray($this->move("pages", $id, $prefpos, $direction, $dbf));
	          $parseheader="location:index.php?action=command&page=2&sub={$id}";
	        }
	        else
	        {
	          $parseheader="location:index.php?action=incorrectargument";
	        }         
	        break;

        case "UnitType":
          switch ($_POST['QueryAction'])
          {
            case "Delete":
              if($this->checkposint($_POST['ID']))
              {
                $id=$this->strip($_POST['ID']);
                $queryArray[sizeof($queryArray)]="DELETE FROM unittypes WHERE id='{$id}';";
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=toe&page=4";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Change":
              if($this->checkposint($_POST['ID']))
              {
                $id=$this->strip($_POST['ID']);
                $unittype=$this->strip($_POST['unittype']);
                $color=$this->strip($_POST['color']);

                $errMSG="";
                if($unittype=="")
                {
                  $errMSG="no unittype";
                }
                if($color=="")
                {
                  $errMSG="no/illegal color";
                }
                if($errMSG=="")
                {
                  $queryArray[sizeof($queryArray)]="UPDATE unittypes SET name='{$unittype}', color='{$color}' WHERE id='{$id}';";
                  $dbf->queryarray($queryArray);
                  $parseheader="location:index.php?action=toe&page=4&sub={$id}";
                }
                else
                {
                  $parseheader="location:index.php?action=toe&page=4&sub={$id}&err={$errMSG}";
                }
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Add":
              $unittype=$this->strip($_POST['unittype']);
              $color=$this->strip($_POST['color']);

              $errMSG="";
              if($unittype=="")
              {
                $errMSG="no unittype";
              }
              if($color=="")
              {
                $errMSG="no/illegal color";
              }
              if($errMSG=="")
              {
                $queryArray[sizeof($queryArray)] = "INSERT INTO unittypes (name, color) VALUES ('{$unittype}', '{$color}');";
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=toe&page=4";
              }
              else
              {
                $parseheader="location:index.php?action=toe&page=4&err={$errMSG}";
              }

              break;
          }
          break;

        case "UnitLevel":
          switch ($_POST['QueryAction'])
          {
            case "Delete":
              if($this->checkposint($_POST['ID']))
              {
                $id=$this->strip($_POST['ID']);
                $prefpos=$this->strip($_POST['prefpos']);
                $queryArray[sizeof($queryArray)]="DELETE FROM unitlevel WHERE id='{$id}';";
                $positionresult=$dbf->queryselect("SELECT id, prefpos FROM unitlevel WHERE prefpos>'{$prefpos}';");
                while($array=mysql_fetch_array($positionresult, MYSQL_NUM))
                {
                  $pp=$array[1]-1;
                  $queryArray[sizeof($queryArray)]="UPDATE unitlevel SET prefpos='{$pp}' WHERE id='{$array[0]}';";
                }
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=toe&page=5&prefpos={$_POST['prefpos']}";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Change":
              if($this->checkposint($_POST['ID']))
              {
                $id=$this->strip($_POST['ID']);
                $picture=$this->strip($_POST['picture']);
                $name=$this->strip($_POST['name']);

                $errMSG="";
                if($name=="")
                {
                  $errMSG="no unittype";
                }
                if($errMSG=="")
                {
                  $queryArray[sizeof($queryArray)] = "UPDATE unitlevel SET name='{$name}', picture='{$picture}' WHERE id='{$id}';";
                  $dbf->queryarray($queryArray);
                  $parseheader="location:index.php?action=toe&page=5&sub={$id}";
                }
                else
                {
                  $parseheader="location:index.php?action=toe&page=5&err={$errMSG}";
                }
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Add":
              $picture=$this->strip($_POST['picture']);
              $name=$this->strip($_POST['name']);

              $errMSG="";
              if($name=="")
              {
                $errMSG="no unittype";
              }
              if($errMSG=="")
              {
                $rResult=$dbf->queryselect("SELECT COUNT(*) count FROM unitlevel;");
                $unitlevelnumber=mysql_result($rResult, 0)+1;
                $queryArray[sizeof($queryArray)]="INSERT INTO unitlevel (name, prefpos, picture) VALUES ('{$name}', '{$unitlevelnumber}', '{$picture}');";
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=toe&page=5";
              }
              else
              {
                $parseheader="location:index.php?action=toe&page=5&err={$errMSG}";
              }
          }
          break;

        case "EquipmentType":
          switch ($_POST['QueryAction'])
          {
            case "Delete":
              if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['prefpos']))
              {
                $id=$this->strip($_POST['ID']);
                $prefpos=$this->strip($_POST['prefpos']);
                $queryArray[sizeof($queryArray)] = "DELETE FROM equipmenttypes WHERE id='{$id}';";
                $positionresult=$dbf->queryselect("SELECT id, prefpos FROM equipmenttypes WHERE prefpos>'{$prefpos}';");
                while($array = mysql_fetch_array($positionresult, MYSQL_NUM))
                {
                  $pp = $array[1]-1;
                  $queryArray[sizeof($queryArray)] = "UPDATE equipmenttypes SET prefpos='{$pp}' WHERE id='{$array[0]}';";
                }
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=toe&page=1";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Change":
              if($this->checkposint($_POST['ID']))
              {
                $id=$this->strip($_POST['ID']);
                $name=$this->strip($_POST['name']);
                $license=$this->strip($_POST['license']);
                $maxweight=$this->strip($_POST['maxweight']);
                $minweight=$this->strip($_POST['minweight']);
                $weightstep=$this->strip($_POST['weightstep']);
                $weightscale=$this->strip($_POST['weightscale']);
                $requirement=$this->strip($_POST['requirement']);
                $used=$this->strip($_POST['usedequipment']);

                $errMSG="";
                if($name=="")
                {
                  $errMSG="no name";
                }
                if($license=="" || !ctype_digit($license))
                {
                  $errMSG="no/illegal licence prefix";
                }
                if($maxweight=="" || !ctype_digit($maxweight))
                {
                  $errMSG="no/illegal maximum weight";
                }
                if($minweight=="" || !ctype_digit($minweight))
                {
                  $errMSG="no/illegal minimum weight";
                }
                if($weightstep=="" || !ctype_digit($weightstep))
                {
                  $errMSG="no/illegal weight step";
                }

                if($errMSG=="")
                {
                  if($used=="on")
                  {
                    $used=1;
                  }
                  else
                  {
                    $used=0;
                  }
                  $queryArray[sizeof($queryArray)] = "UPDATE equipmenttypes SET name='{$name}', license='{$license}', maxweight='{$maxweight}', minweight='{$minweight}', weightstep='{$weightstep}', weightscale='{$weightscale}', used='{$used}', requirement='{$requirement}' WHERE id='{$id}';";
                  $dbf->queryarray($queryArray);
                  $parseheader="location:index.php?action=toe&page=1&sub={$id}";
                }
                else
                {
                  $parseheader="location:index.php?action=toe&page=1&sub={$id}&err={$errMSG}";
                }
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Add":
              $name=$this->strip($_POST['name']);
              $license=$this->strip($_POST['license']);
              $maxweight=$this->strip($_POST['maxweight']);
              $minweight=$this->strip($_POST['minweight']);
              $weightstep=$this->strip($_POST['weightstep']);
              $weightscale=$this->strip($_POST['weightscale']);
              $requirement=$this->strip($_POST['requirement']);
              $used=$this->strip($_POST['usedequipment']);

              $errMSG="";
              if($name=="")
              {
                $errMSG="no name";
              }
              if($license=="" || !ctype_digit($license))
              {
                $errMSG="no/illegal licence prefix";
              }
              if($maxweight=="" || !ctype_digit($maxweight))
              {
                $errMSG="no/illegal maximum weight";
              }
              if($minweight=="" || !ctype_digit($minweight))
              {
                $errMSG="no/illegal minimum weight";
              }
              if($weightstep=="" || !ctype_digit($weightstep))
              {
                $errMSG="no/illegal weight step";
              }

              if($errMSG=="")
              {
                $rResult=$dbf->queryselect("SELECT COUNT(*) count FROM equipmenttypes;");
                $equipmenttypesnumber=mysql_result($rResult, 0)+1;
                if($used=="on")
                {
                  $used=1;
                }
                else
                {
                  $used=0;
                }
                $queryArray[sizeof($queryArray)]="INSERT INTO equipmenttypes (name, license, maxweight, minweight, weightstep, weightscale, prefpos, used, requirement) VALUES ('{$name}', '{$license}', '{$maxweight}', '{$minweight}', '{$weightstep}', '{$weightscale}', '{$equipmenttypesnumber}', '{$used}', '{$requirement}');";
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=toe&page=1";
              }
              else
              {
                $parseheader="location:index.php?action=toe&page=1&err={$errMSG}";
              }
              break;
          }
          break;

        case "AbilityType":
          switch ($_POST['QueryAction'])
          {
            case "Delete":
              if($this->checkposint($_POST['ID']))
              {
                $id=$this->strip($_POST['ID']);
                $queryArray[sizeof($queryArray)]="DELETE FROM abilitytypes WHERE id='{$id}';";
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=toe&page=7";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Change":
              if($this->checkposint($_POST['ID']))
              {
                $id=$this->strip($_POST['ID']);
                $name=$this->strip($_POST['name']);

                $errMSG="";
                if($name=="")
                {
                  $errMSG="no name";
                }
                if($errMSG=="")
                {
                  $queryArray[sizeof($queryArray)] = "UPDATE abilitytypes SET name='{$name}' WHERE id='{$id}';";
                  $dbf->queryarray($queryArray);
                  $parseheader="location:index.php?action=toe&page=7&sub={$id}";
                }
                else
                {
                  $parseheader="location:index.php?action=toe&page=7&sub={$id}err={$errMSG}";
                }
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Add":
              $name=$this->strip($_POST['name']);

              $errMSG="";
              if($name=="")
              {
                $errMSG="no name";
              }

              if($errMSG=="")
              {
                $queryArray[sizeof($queryArray)] = "INSERT INTO abilitytypes (name) VALUES ('{$name}');";
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=toe&page=7";
              }
              else
              {
                $parseheader="location:index.php?action=toe&page=7&err={$errMSG}";
              }
              break;
          }
          break;
		
        case "SkillType":
          switch ($_POST['QueryAction'])
          {
            case "Delete":
              if($this->checkposint($_POST['ID']))
              {
                $id=$this->strip($_POST['ID']);
                $queryArray[sizeof($queryArray)]="DELETE FROM skilltypes WHERE id='{$id}';";
                $queryArray[sizeof($queryArray)]="DELETE FROM skillrequirements WHERE skilltype='{$id}';";
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=toe&page=2";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Change":
              if($this->checkposint($_POST['ID']))
              {
                $id=$this->strip($_POST['ID']);
                $name=$this->strip($_POST['name']);
                $shortname=$this->strip($_POST['shortname']);

                $errMSG="";
                if($name=="")
                {
                  $errMSG="no name";
                }
                if($shortname=="")
                {
                  $errMSG="no shortname";
                }

                if($errMSG=="")
                {
                  $queryArray[sizeof($queryArray)] = "UPDATE skilltypes SET name='{$name}', shortname='{$shortname}' WHERE id='{$id}';";
                  $dbf->queryarray($queryArray);
                  $parseheader="location:index.php?action=toe&page=2&sub={$id}";
                }
                else
                {
                  $parseheader="location:index.php?action=toe&page=2&sub={$id}err={$errMSG}";
                }
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Add":
              $name=$this->strip($_POST['name']);
              $shortname=$this->strip($_POST['shortname']);

              $errMSG="";
              if($name=="")
              {
                $errMSG="no name";
              }
              if($shortname=="")
              {
                $errMSG="no shortname";
              }

              if($errMSG=="")
              {
                $queryArray[sizeof($queryArray)] = "INSERT INTO skilltypes (name, shortname) VALUES ('{$name}', '{$shortname}');";
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=toe&page=2";
              }
              else
              {
                $parseheader="location:index.php?action=toe&page=2&err={$errMSG}";
              }
              break;
          }
          break;  
          
        case "SkillRequirement":
          switch ($_POST['QueryAction'])
          {
            case "Remove":
              if($this->checkposint($_POST['personneltype']))
              {
                $personneltype=$this->strip($_POST['personneltype']);
                $id=$this->strip($_POST['ID']);
                $queryArray[sizeof($queryArray)] = "DELETE FROM skillrequirements WHERE id='{$id}';";
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=toe&page=3&sub={$personneltype}";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Add":
              if($this->checkposint($_POST['skilltype']))
              {
                $skilltype=$this->strip($_POST['skilltype']);
                $personneltype=$this->strip($_POST['personneltype']);
                $queryArray[sizeof($queryArray)]="INSERT INTO skillrequirements (skilltype, personneltype) VALUES ('{$skilltype}', '{$personneltype}');";
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=toe&page=3&sub={$personneltype}";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;
          }
          break;

        case "Rank":
          if($this->checkposint($_POST['ID']))
          {
            $id=$this->strip($_POST['ID']);
            $rank=$this->strip($_POST['rank']);
            $queryArray[sizeof($queryArray)] = "Update ranks SET rankname='$rank' WHERE number='{$id}';";
            $dbf->queryarray($queryArray);
            $parseheader="location:index.php?action=toe&page=6";
          }
          else
          {
            $parseheader="location:index.php?action=incorrectargument";
          }
          break;

        case "Years":
          if($this->checkposint($_POST['syear']) && $this->checkposint($_POST['smonth']) && $this->checkposint($_POST['sday'])&&
          $this->checkposint($_POST['cyear']) && $this->checkposint($_POST['cmonth']) && $this->checkposint($_POST['cday'])&&
          $this->checkposint($_POST['eyear']) && $this->checkposint($_POST['emonth']) && $this->checkposint($_POST['eday']))
          {
            $sdate=$this->strip($_POST['syear'])."-".$this->strip($_POST['smonth'])."-".$this->strip($_POST['sday']);
            $cdate=$this->strip($_POST['cyear'])."-".$this->strip($_POST['cmonth'])."-".$this->strip($_POST['cday']);
            $edate=$this->strip($_POST['eyear'])."-".$this->strip($_POST['emonth'])."-".$this->strip($_POST['eday']);
            $queryArray[sizeof($queryArray)] = "UPDATE dates SET startingdate='{$sdate}', currentdate='{$cdate}', endingdate='{$edate}' WHERE ID=1;";
            $dbf->queryarray($queryArray);
            $parseheader="location:index.php?action=command&page=6";
          }
          else
          {
            $parseheader="location:index.php?action=incorrectargument";
          }
          break;

        case "pages":
          switch ($_POST['QueryAction'])
          {
            case "Delete":
              if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['prefpos']))
              {
                $id=$this->strip($_POST['ID']);
                $prefpos=$this->strip($_POST['prefpos']);
                $queryArray[sizeof($queryArray)]="DELETE FROM pages WHERE id='{$id}';";
                $positionresult=$dbf->queryselect("SELECT id, prefpos FROM pages WHERE prefpos>'{$prefpos}';");
                while($array = mysql_fetch_array($positionresult, MYSQL_NUM))
                {
                  $pp = $array[1]-1;
                  $queryArray[sizeof($queryArray)] = "UPDATE pages SET prefpos='{$pp}' WHERE id='{$array[0]}';";
                }
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=command&page=2";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Change":
              if($this->checkposint($_POST['ID']))
              {
                $id=$this->strip($_POST['ID']);
                $name=$this->strip($_POST['name']);
                $game=$this->strip($_POST['game']);
                $news=$this->strip($_POST['news']);
                $units=$this->strip($_POST['units']);
                $notables=$this->strip($_POST['notables']);
                $text=$this->strip($_POST['text']);
                $prefpos=$this->strip($_POST['prefpos']);

                $errMSG="";
                if($name=="")
                {
                  $errMSG="no page name.";
                }
                if($errMSG=="")
                {
                  if($game=="on")
                  {
                    $game=1;
                  }
                  else
                  {
                    $game=0;
                  }
                  if($news=="on")
                  {
                    $news=1;
                  }
                  else
                  {
                    $news=0;
                  }
                  if($units=="on")
                  {
                    $units=1;
                  }
                  else
                  {
                    $units=0;
                  }
                  if($notables=="on")
                  {
                    $notables=1;
                  }
                  else
                  {
                    $notables=0;
                  }
                  $queryArray[sizeof($queryArray)] = "UPDATE pages SET name='{$name}', game='{$game}', news='{$news}', units='{$units}', notables='{$notables}', text='{$text}' WHERE ID='{$id}';";
                  $dbf->queryarray($queryArray);
                  $parseheader="location:index.php?action=command&page=2&sub={$id}";
                }
                else
                {
                  $parseheader="location:index.php?action=command&page=2&err={$errMSG}";
                }
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Add":
              $name=$this->strip($_POST['name']);
              $game=$this->strip($_POST['game']);
              $news=$this->strip($_POST['news']);
              $units=$this->strip($_POST['units']);
              $notables=$this->strip($_POST['notables']);
              $text=$this->strip($_POST['text']);
          	  $errMSG="";
                if($name=="")
                {
                  $errMSG="no page name.";
                }
                if($errMSG=="")
                {
                  if($game=="on")
                  {
                    $game=1;
                  }
                  else
                  {
                    $game=0;
                  }
                  if($news=="on")
                  {
                    $news=1;
                  }
                  else
                  {
                    $news=0;
                  }
                  if($units=="on")
                  {
                    $units=1;
                  }
                  else
                  {
                    $units=0;
                  }
                  if($notables=="on")
                  {
                    $notables=1;
                  }
                  else
                  {
                    $notables=0;
                  }
                  $rResult=$dbf->queryselect("SELECT COUNT(*) count FROM pages;");
                  $pagesnumber=mysql_result($rResult, 0)+1;
                  $nextidResult=$dbf->queryselect("SELECT max(id) FROM pages;");
                  $temp=mysql_result($nextidResult, 0)+1;
                  //$temp=$nextidArray[0]+1;
                  $queryArray[sizeof($queryArray)]="INSERT INTO pages (id, name, game, news, units, notables, text, prefpos) VALUES ('{$temp}', '{$name}', '{$game}', '{$news}', '{$units}', '{$notables}', '{$text}', '{$pagesnumber}');";
                  $dbf->queryarray($queryArray);
                  $parseheader="location:index.php?action=command&page=2";
              	}
              	else
              	{
                	$parseheader="location:index.php?action=command&page=2&err={$errMSG}";
              	}
              	break;
          	}
          	break;
          
        case "Command":
          //Command information check
          if($_POST['sub']=="plain")
          {
            $name=$this->strip($_POST['name']);
            $abb=$this->strip($_POST['abb']);
            $motto=$this->strip($_POST['motto']);

            $image=$this->strip($_POST['image']);
            $commandheader=$this->strip($_POST['commandheader']);
            $icon=$this->strip($_POST['icon']);

            $errMSG="";
            if($name=="")
            {
              $errMSG="no name";
            }
            if($abb=="")
            {
              $errMSG="nno abbreviation";
            }
            if($motto=="")
            {
              $errMSG="no motto";
            }
            if($errMSG=="")
            {
              $queryArray[sizeof($queryArray)]="UPDATE command SET name='{$name}', abbreviation='{$abb}', motto='{$motto}', image='{$image}', header='{$commandheader}', icon='{$icon}' WHERE id=1;";
              $dbf->queryarray($queryArray);
              $parseheader="location:index.php?action=command&page=1";
            }
            else
            {
              $parseheader="location:index.php?action=command&page=1&err={$errMSG}";
            }
          }         
          break;

        case "personneltypemove":
          if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['prefpos']))
          {
            $id=$this->strip($_POST['ID']);
            $prefpos=$this->strip($_POST['prefpos']);
            $direction=$this->strip($_POST['QueryAction']);
            $dbf->queryarray($this->move("crewtypes", $id, $prefpos, $direction, $dbf));
            $parseheader="location:index.php?action=toe&page=3&sub={$id}";
          }
          else
          {
            $parseheader="location:index.php?action=incorrectargument";
          }
          break;

        case "equipmenttypemove":
          if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['prefpos']))
          {
            $id=$this->strip($_POST['ID']);
            $prefpos=$this->strip($_POST['prefpos']);
            $direction=$this->strip($_POST['QueryAction']);
            $dbf->queryarray($this->move("equipmenttypes", $id, $prefpos, $direction, $dbf));
            $parseheader="location:index.php?action=toe&page=1&sub={$id}";
          }
          else
          {
            $parseheader="location:index.php?action=incorrectargument";
          }
          break;

        case "unitlevelmove":
          if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['prefpos']))
          {
            $id=$this->strip($_POST['ID']);
            $prefpos=$this->strip($_POST['prefpos']);
            $direction=$this->strip($_POST['QueryAction']);
            $dbf->queryarray($this->move("unitlevel", $id, $prefpos, $direction, $dbf));
            $parseheader="location:index.php?action=toe&page=5&sub={$id}";
          }
          else
          {
            $parseheader="location:index.php?action=incorrectargument";
          }
          break;
      }
    }
    else
    {
      $parseheader="location:index.php?action=accessdenied";
    }
    return $parseheader;
  }
}
?>