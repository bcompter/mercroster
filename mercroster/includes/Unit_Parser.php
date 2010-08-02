<?php
require("includes/Parser.php");
class Unitparser extends Parser
{
  /**
   * This function is used to handle log relaited squeries
   * @return string
   */
  function parse()
  {
    if(isset($_SESSION['SESS_NAME']) && $_SESSION['SESS_TYPE']<='3') //for Commanders, GMs and administrators
    {
      require("htdocs/dbsetup.php");
      $dbf=new DBFunctions;

      switch ($_POST['QueryType'])
      {
        case "Organization":
          switch ($_POST['QueryAction'])
          {
            case "Assign":
              if($this->checkposint($_POST['ID']))
              {
                $id=$this->strip($_POST['ID']);
                $prefpos=$this->strip($_POST['prefpos']);
                $queryArray[sizeof($queryArray)]="UPDATE unit SET parent='1', prefpos='{$prefpos}' WHERE id='{$id}';";
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=editunit&unit={$id}";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Detach":
              if($this->checkposint($_POST['ID']))
              {
                $id=$this->strip($_POST['ID']);
                $prefpos=$this->strip($_POST['prefpos']);
                $parent=$this->strip($_POST['parent']);
                $queryArray[sizeof($queryArray)]="UPDATE unit SET parent='0' WHERE id='{$id}';";
                //update parellal subunits' PrefPos if needed
                $positionsiftcrrewresult=$dbf->queryselect("SELECT id, prefpos FROM unit WHERE parent='{$parent}';");
                while($array = mysql_fetch_array($positionsiftcrrewresult, MYSQL_NUM))
                {
                  if($array[1]>$prefpos && $array[0]!=$ID)
                  {
                    $pp = $array[1]-1;
                    $queryArray[sizeof($queryArray)]="UPDATE unit SET prefpos='{$pp}' WHERE id='{$array[0]}';";
                  }
                }
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=editunit&unit=0";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Add Unit":
              if($this->checkposint($_POST['ID']))
              {
                $id=$this->strip($_POST['ID']);
                $prefpos=$this->strip($_POST['prefpos']);
                $parent=$this->strip($_POST['parent']);
                $type=$this->strip($_POST['type']);
                if($type=="Unit")//add unit to parent organization
                {
                  $queryArray[sizeof($queryArray)]="UPDATE unit SET parent='{$parent}', prefpos='{$prefpos}' WHERE id='{$id}';";
                }
                if($type=="Crew")//add crew to parent organization
                {
                  $queryArray[sizeof($queryArray)]="UPDATE crew SET Parent='{$parent}' WHERE ID='{$id}';";
                }
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=editunit&unit={$parent}";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Remove Unit":
              if($this->checkposint($_POST['ID']))
              {
                $id=$this->strip($_POST['ID']);
                $prefpos=$this->strip($_POST['prefpos']);
                $parent=$this->strip($_POST['parent']);
                $type=$this->strip($_POST['type']);
                if($type=="Unit")
                {
                  $queryArray[sizeof($queryArray)]="UPDATE unit SET parent='0', prefpos='0' WHERE ID='{$id}';";
                  $positionsiftcrrewresult=$dbf->queryselect("SELECT id, prefpos FROM unit WHERE Parent='{$parent}';");
                  while($array = mysql_fetch_array($positionsiftcrrewresult, MYSQL_NUM))
                  {
                    if($array[1]>$prefpos && $array[0]!=$id)
                    {
                      $pp = $array[1]-1;
                      $queryArray[sizeof($queryArray)] = "UPDATE unit SET prefpos='{$pp}' WHERE id='{$array[0]}';";
                    }
                  }
                }
                if($type=="Crew")
                {
                  $queryArray[sizeof($queryArray)] = "UPDATE crew SET parent='0' WHERE id='{$id}';";
                }
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=editunit&unit={$parent}";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Save":
              if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['type']))
              {
                $errMSG="";
                $id=$this->strip($_POST['ID']);
                $type=$this->strip($_POST['type']);
                $name=$this->strip($_POST['name']);
                $level=$this->strip($_POST['level']);
                $limage=$this->strip($_POST['limage']);
                $rimage=$this->strip($_POST['rimage']);
                $text=$this->strip($_POST['text']);
                if($name=="")
                {
                  $errMSG="no unit's name";
                }
                if($errMSG=="")
                {
                  $queryArray[sizeof($queryArray)]="UPDATE unit SET type='{$type}', name='{$name}', level='{$level}', limage='{$limage}', rimage='{$rimage}', text='{$text}' WHERE id='{$id}';";
                  $dbf->queryarray($queryArray);
                  $parseheader="location:index.php?action=editunit&unit={$id}";
                }
                else
                {
                  $parseheader="location:index.php?action=editunit&unit={$id}&err={$errMSG}";
                }
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;

            case "Add":

              $errMSG="";
              $id=$this->strip($_POST['ID']);
              $type=$this->strip($_POST['type']);
              $name=$this->strip($_POST['name']);
              $level=$this->strip($_POST['level']);
              $limage=$this->strip($_POST['limage']);
              $rimage=$this->strip($_POST['rimage']);
              $text=$this->strip($_POST['text']);
              if($name=="")
              {
                $errMSG="no unit's name";
              }
              if($errMSG=="")
              {
                $queryArray[sizeof($queryArray)]="INSERT INTO unit (type, name, parent, limage, rimage, level, text) VALUES ('{$type}', '{$name}', '0', '{$limage}', '{$rimage}', '{$level}', '{$text}');";
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=unittable&unit=0";
              }
              else
              {
                $parseheader="location:index.php?action=editunit&unit=0&err={$errMSG}";
              }
              break;

            case "Delete":
              if($this->checkposint($_POST['ID']) && $this->checkposint($_POST['prefpos']))
              {
                $id=$this->strip($_POST['ID']);
                $prefpos=$this->strip($_POST['prefpos']);
                $parent=$this->strip($_POST['parent']);
                $queryArray[sizeof($queryArray)]="DELETE FROM unit WHERE id='{$id}';";
                //need to set each attached crew's parent to 0
                $result=$dbf->queryselect("SELECT id FROM crew WHERE parent='{$id}';");
                while($array = mysql_fetch_array($result, MYSQL_NUM))
                {
                  $queryArray[sizeof($queryArray)]="UPDATE crew SET parent='0' WHERE id='{$array[0]}';";
                }
                //need to set each attached subunit's parent to 0
                $result=$dbf->queryselect("SELECT id FROM unit WHERE parent='{$id}';");
                while($array = mysql_fetch_array($result, MYSQL_NUM))
                {
                  $queryArray[sizeof($queryArray)]="UPDATE unit SET parent='0', prefpos='0' WHERE id='{$array[0]}';";
                }
                //update parellal subunits' PrefPos if needed
                $result=$dbf->queryselect("SELECT id, prefpos FROM unit WHERE parent='{$parent}';");
                while($array = mysql_fetch_array($result, MYSQL_NUM))
                {
                  if($array[1]>$prefpos && $array[0]!=$id)
                  {
                    $pp=$array[1]-1;
                    $queryArray[sizeof($queryArray)]="UPDATE unit SET prefpos='{$pp}' WHERE id='{$array[0]}';";
                  }
                }
                //if unit is someones favored unit, set that user's favored unit to 0
                $result=$dbf->queryselect("SELECT DISTINCT id FROM members WHERE favoredunit='{$id}';");
                while($array = mysql_fetch_array($result, MYSQL_NUM))
                {
                  $queryArray[sizeof($queryArray)]="UPDATE members SET favoredunit='0' WHERE id='{$array[0]}';";
                }
                $dbf->queryarray($queryArray);
                $parseheader="location:index.php?action=unittable&unit=0";
              }
              else
              {
                $parseheader="location:index.php?action=incorrectargument";
              }
              break;
          }
          break;

        case "Move":
          $id=$this->strip($_POST['ID']);
          $prefpos=$this->strip($_POST['prefpos']);
          $newpos=$this->strip($_POST['otherprefpos']);
          $otherid=$this->strip($_POST['otherid']);
          $back=$this->strip($_POST['parent']);

          $queryArray[sizeof($queryArray)]="UPDATE unit SET prefpos='$newpos' WHERE id='{$id}';";
          $queryArray[sizeof($queryArray)]="UPDATE unit SET prefpos='$prefpos' WHERE id='{$otherid}';";
          $dbf->queryarray($queryArray);
          $parseheader="location:index.php?action=unittable&unit={$back}";
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