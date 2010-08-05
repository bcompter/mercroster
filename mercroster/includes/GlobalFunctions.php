<?php
class GlobalFunctions
{
 /**
  * Given equipment class and subtype decide how to display them
  */	
  public function displayEquipmentName($subtype, $name, $dbf)
  {
    $result = $dbf->queryselect("SELECT value FROM options WHERE id='1';");
    $last=mysql_result($result, 0);
    if($last==1) {
    	return $name." ".$subtype;
    }
    return $subtype." ".$name;
  }
	
}
?>