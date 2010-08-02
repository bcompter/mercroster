<?php
require("includes/Parser.php");
class Personnelparser extends Parser
{
  /**
   *
   * @return unknown_type
   */
  function parse()
  {
    if(isset($_SESSION['SESS_NAME']) && $_SESSION['SESS_TYPE']<'5') //for players, commanders, GMs and administrators
    {
      require("htdocs/dbsetup.php");
      $dbf=new DBFunctions;
      switch ($_POST['QueryType'])
      {
        case "Personnel":
          switch ($_POST['QueryAction'])
          {
            case "Delete":
              if($this->checkposint($_POST['ID']))
              {
                $id=$this->strip($_POST['ID']);
                $type=$this->strip($_POST['type']);
                //find if soon to be deleted personel has Vehicle
                $vehicleqeury  = "SELECT id FROM equipment WHERE crew='$id';";
                $result = $dbf->queryselect($vehicleqeury);
                //if so change that equipment's owner to 0
                while($array = mysql_fetch_array($result, MYSQL_NUM))
                {
                  $queryArray[sizeof($queryArray)] = "UPDATE equipment SET crew='0' WHERE id='{$array[0]}';";
                }
                //Personnel DELETE Query
                $queryArray[sizeof($queryArray)] = "DELETE FROM crew WHERE id='{$id}';"; //delete person
                $queryArray[sizeof($queryArray)] = "DELETE FROM personnelpositions WHERE person='{$id}';"; //delete person's positions
                $queryArray[sizeof($queryArray)] = "DELETE FROM skills WHERE person='{$id}';"; //delete person's skills
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=personneltable&type={$type}&order=1&first=0";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Save":
                $id=$this->strip($_POST['ID']);
                $rank=$this->strip($_POST['rank']);
                $lname=$this->strip($_POST['lname']);
                $fname=$this->strip($_POST['fname']);
    			$callsign=$this->strip($_POST['callsign']);
                $status=$this->strip($_POST['status']);
                $crewnumber=$this->strip($_POST['crewnumber']);
                $joiningdate=$this->strip($_POST['year'])."-".$this->strip($_POST['month'])."-".$this->strip($_POST['day']);
                $birthdate=$this->strip($_POST['birthyear'])."-".$this->strip($_POST['birthmonth'])."-".$this->strip($_POST['birthday']);
                $notable=$this->strip($_POST['notable']);
                $notes=$this->strip($_POST['notes']);
                $vehicleid=$this->strip($_POST['vehicleid']);
                $lastvehicleid=$this->strip($_POST['lastvehicleid']);
                $image=$this->strip($_POST['image']);

                $errMSG="";
                if($notable=="on")
                {
                  $notable=1;
                }
                else
                {
                  $notable=0;
                }

                if($fname=="")
                {
                  $errMSG="no first name";
                }
                if($lname=="")
                {
                  $errMSG="no last name";
                }

                if($errMSG=="")
                {
                  //Personel UPDATE Query
                  $queryArray[sizeof($queryArray)] = "UPDATE crew SET rank='{$rank}', lname='{$lname}', fname='{$fname}', callsign='{$callsign}', status='{$status}', crewnumber='{$crewnumber}', joiningdate='{$joiningdate}', notes='{$notes}', bday='{$birthdate}', notable='{$notable}', image='{$image}' WHERE id='{$id}';";
                  if($vehicleid!=$lastvehicleid)
                  {
                    //Set old equipment's Owner to 0
                    $queryArray[sizeof($queryArray)] = "UPDATE equipment SET crew='0' WHERE id='{$lastvehicleid}';";
                    //Set new equipment's Owner to this personel
                    $queryArray[sizeof($queryArray)] = "UPDATE equipment SET crew='{$id}' WHERE id='{$vehicleid}';";
                  }
                  $dbf->queryarray($queryArray);
                  $parseheader="location:index.php?action=personnel&personnel={$id}";
                }
                else
                {
                  $parseheader="location:index.php?action=editpersonnel&personnel={$id}&err={$errMSG}";
                }
              break;

            case "Add":

              $rank=$this->strip($_POST['rank']);
              $lname=$this->strip($_POST['lname']);
              $fname=$this->strip($_POST['fname']);
              $callsign=$this->strip($_POST['callsign']);
              $status=$this->strip($_POST['status']);
              $crewnumber=$this->strip($_POST['crewnumber']);
              $joiningdate=$this->strip($_POST['year'])."-".$this->strip($_POST['month'])."-".$this->strip($_POST['day']);
              $birthdate=$this->strip($_POST['birthyear'])."-".$this->strip($_POST['birthmonth'])."-".$this->strip($_POST['birthday']);
              $notable=$this->strip($_POST['notable']);
              $notes=$this->strip($_POST['notes']);
              $image=$this->strip($_POST['image']);

              $errMSG="";
              if($notable=="on")
              {
                $notable=1;
              }
              else
              {
                $notable=0;
              }

              if($fname=="")
              {
                $errMSG="no first name";
              }
              if($lname=="")
              {
                $errMSG="no last name";
              }

              if($errMSG=="")
              {
                $queryArray[sizeof($queryArray)] = "INSERT INTO crew (rank, lname, fname, callsign, status, crewnumber, joiningdate, notes, bday, notable, image) VALUES ('{$rank}', '{$lname}', '{$fname}', '{$status}', '{$crewnumber}', '{$joiningdate}', '{$notes}', '{$birthdate}', '{$notable}', '{$image}');";
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=personneltable&first=0";
              }
              else
              {
                $parseheader="location:index.php?action=editpersonnel&err={$errMSG}";
              }
              break;
          }
          break;

        case "Personnelposition":
          switch ($_POST['QueryAction'])
          {
            case "Remove":
              if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['personnel']))
              {
                $personnel=$this->strip($_POST['personnel']);
                $id=$this->strip($_POST['ID']);
                $queryArray[sizeof($queryArray)] = "DELETE FROM personnelpositions WHERE id='{$id}';";
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=editpersonnel&personnel={$personnel}";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Add":
              if($this->checkposint($_POST['personnel']) && $this->checkposint($_POST['personneltype']))
              {
                $personnel=$this->strip($_POST['personnel']);
                $personneltype=$this->strip($_POST['personneltype']);
                $queryArray[sizeof($queryArray)] = "INSERT INTO personnelpositions (personneltype, person) VALUES ('{$personneltype}', '{$personnel}');";
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=editpersonnel&personnel={$personnel}";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;
          }
          break;

        case "Skill":
          switch ($_POST['QueryAction'])
          {
            case "Remove":
              if($this->checkposint($_POST['personnel']) && $this->checkposint($_POST['ID']))
              {
                $personnel=$this->strip($_POST['personnel']);
                $id=$this->strip($_POST['ID']);
                $queryArray[sizeof($queryArray)] = "DELETE FROM skills WHERE id='{$id}';";
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=editpersonnel&personnel={$personnel}";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Change":
              if($this->checkposint($_POST['personnel']) && $this->checkposint($_POST['ID']) && $this->checkposint($_POST['skillvalue']))
              {
                $personnel=$this->strip($_POST['personnel']);
                $id=$this->strip($_POST['ID']);
                $skillvalue=$this->strip($_POST['skillvalue']);
                $queryArray[sizeof($queryArray)] = "UPDATE skills SET value='{$skillvalue}' WHERE id='{$id}';";
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=editpersonnel&personnel={$personnel}";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Add":
              if($this->checkposint($_POST['personnel']) && $this->checkposint($_POST['skilltype']) && $this->checkposint($_POST['skillvalue']))
              {
                $personnel=$this->strip($_POST['personnel']);
                $skilltype=$this->strip($_POST['skilltype']);
                $skillvalue=$this->strip($_POST['skillvalue']);
                $queryArray[sizeof($queryArray)] = "INSERT INTO skills (person, skill, value) VALUES ('{$personnel}', '{$skilltype}', '{$skillvalue}');";
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=editpersonnel&personnel={$personnel}";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;
          }
          break;
      }
      return $parseheader;
    }
    else
    {
      return "location:index.php?action=accessdenied";
    }
  }
}
?>