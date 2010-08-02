<?php

class DBFunctions
{
  /**
   * This Funtion is used to make SELECT qeuries and then return result array
   * @param <array> $queryArray
   * @return <msql query>
   */
  function queryselect($queryArray)
  {
    require("htdocs/dbsetup.php");
    $result = mysql_query($queryArray, $conn);
    mysql_close($conn);
    if(!$result )
    {
      die('SQL ERROR '. $queryArray);
    }
    return $result;
    mysql_close($conn);
  }

  /**
   * This function is used to make INSERT and UPDATE queries.
   * @param <msql query> $queryArray
   */
  function queryarray($queryArray)
  {
    require("htdocs/dbsetup.php");
    for($i=0; $i<sizeof($queryArray);$i++)
    {
      $result=mysql_query($queryArray[$i], $conn);
      if(!$result)
      {
        die('query :' . $i . ' : ' . $queryArray[$i] . '');
      }
    }
    mysql_close($conn);
  }

  /**
   * This function is used to return array of arrays containing quered table data
   * @param <msql query> $result
   * @return <array>
   */
  function resulttoarray($result)
  {
    $counter=0;
    $returnArray = array();
    while($array = mysql_fetch_array($result, MYSQL_BOTH))
    {
      $returnArray[$counter]=$array;
      $counter++;
    }
    return $returnArray;
  }

  /**
   * This function is used to return array containing quered table data
   * @param <msql query> $result
   * @return <array>
   */
  function resulttoarraysingle($result)
  {
    $counter=0;
    while($array = mysql_fetch_array($result, MYSQL_BOTH))
    {
      $returnArray[$counter]=$array[0];
      $counter++;
    }
    return $returnArray;
  }
}
?>