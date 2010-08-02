<?php
class DateFunctions
{
  /**
   * This funtion is used to change datetime to gametime stringformat
   * @param <datetime> $date
   * @return <string>
   */
  function datestring($date)
  {
    $year=strtok($date, "-");
    $month=strtok("-");
    $day=strtok("-");
    if(substr($day, 0, 1)==0)
    {
      $day = substr($day, 1, 1);
    }
    switch ($month)
    {
      case 1:
        $smonth="January";
        break;
      case 2:
        $smonth="February";
        break;
      case 3:
        $smonth="March";
        break;
      case 4:
        $smonth="April";
        break;
      case 5:
        $smonth="May";
        break;
      case 6:
        $smonth="June";
        break;
      case 7:
        $smonth="July";
        break;
      case 8:
        $smonth="August";
        break;
      case 9:
        $smonth="September";
        break;
      case 10:
        $smonth="October";
        break;
      case 11:
        $smonth="November";
        break;
      case 12:
        $smonth="December";
        break;
      default:
        $smonth="Error in month format";
        break;
    }
    switch ($day)
    {
      case 1:
        $sday="st.";
        break;
      case 2:
        $sday="nd.";
        break;
      case 3:
        $sday="rd.";
        break;
      case 21:
        $sday="st.";
        break;
      case 22:
        $sday="nd.";
        break;
      case 23:
        $sday="rd.";
        break;
      case 31:
        $sday="st.";
        break;
      default:
        $sday="th.";
        break;
    }
    $stringdate=$day.$sday." ".$smonth." ".$year;
    return $stringdate;
  }

  /**
   * This funtion is used to change string containing date and time to preferred localization and format
   * @param <string> $time
   * @param <integer> $offset
   * @param <string> $timeformat
   * @return <string>
   */
  function getTime($time, $offset, $timeformat)
  {
    $time=strtotime($time)+($offset*3600);
    $date=date("Y-m-d", $time+date("Z",$time));
    $today=date("Y-m-d", time()+($offset*3600));
    $yesterday=date("Y-m-d", time()+($offset*3600)-86400);
    //echo "date:{$date} today:{$today}";
    if($date==$today)
    {
      $time="<b>Today</b> at ". date("H:i:s", $time+date("Z",$time));//correction for zulu time to server's local time and formattting that to user's format
    }
    else if($date==$yesterday)
    {
      $time="<b>Yesterday</b> at ". date("H:i:s", $time+date("Z",$time));//correction for zulu time to server's local time and formattting that to user's format
    }
    else
    {
      $time=date($timeformat, $time+date("Z",$time));//correction for zulu time to server's local time and formattting that to user's format
    }
    return $time;
  }
}

?>