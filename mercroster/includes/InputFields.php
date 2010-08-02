<?php
class InputFields
{
  function dropboxqu($result, $value, $name, $class, $blank)
  {
    echo "<select class='{$class}' name='{$name}'>\n";
    if($blank)
    {
      echo "<option value='0'></option>\n";
    }
    while($array = mysql_fetch_array($result, MYSQL_NUM))
    {
      if($array[0]==$value)
      {
        echo "<option value='{$array[0]}' selected='selected'>$array[1]</option>\n";
      }
      else
      {
        echo "<option value='{$array[0]}'>$array[1]</option>\n";
      }
    }
    echo "</select>\n";
  }

  function dropboxquerydual($result, $value, $name, $class, $blank)
  {
    echo "<select class='{$class}' name='{$name}'>\n";
    if($blank)
    {
      echo "<option value='0'></option>\n";
    }
    while($array = mysql_fetch_array($result, MYSQL_NUM))
    {
      if($array[0]==$value)
      {
        echo "<option value='{$array[0]}' selected='selected'>{$array[3]} / {$array[1]}</option>\n";
      }
      else
      {
        echo "<option value='{$array[0]}'>{$array[3]} / {$array[1]}</option>\n";
      }
    }
    echo "</select>\n";
  }
  
  function dropboxar($array, $value, $name, $class)
  {
    echo "<select class='{$class}' name='{$name}'>\n";
    for($i=0;$i<sizeof($array);$i++)
    {
      if($value==$array[$i])
      {

        echo "<option value='{$array[$i]}' selected='selected'>$array[$i]</option>\n";
      }
      else
      {
        echo "<option value='$array[$i]'>$array[$i]</option>\n";
      }
    }
    echo "</select>\n";
  }
  
  function dropboxarscript($array, $value, $name, $class, $script, $empty)
  {
    echo "<select class='{$class}' name='{$name}' {$script}>\n";
    if($empty)
    {
      echo "<option value=''>no image</option>\n";
    }
    for($i=0;$i<sizeof($array);$i++)
    {
      if($value==$array[$i])
      {

        echo "<option value='{$array[$i]}' selected='selected'>$array[$i]</option>\n";
      }
      else
      {
        echo "<option value='$array[$i]'>$array[$i]</option>\n";
      }
    }
    echo "</select>\n";
  }

  function dropboxarnumbers($texts, $array, $value, $name, $class)
  {
    echo "<select class='{$class}' name='{$name}'>\n";
    for($i=0;$i<sizeof($array);$i++)
    {
      if($value==$array[$i])
      {

        echo "<option value='{$array[$i]}' selected='selected'>$texts[$i]</option>\n";
      }
      else
      {
        echo "<option value='$array[$i]'>$texts[$i]</option>\n";
      }
    }
    echo "</select>\n";
  }
  
  function dropboxardualscript($result, $value, $name, $class, $script, $empty)
  {
    echo "<select class='{$class}' name='{$name}' {$script}>\n";
    if($empty)
    {
      echo "<option value=''>no image</option>\n";
    }
    while($array = mysql_fetch_array($result, MYSQL_ASSOC))
    {
      if($value==$array[id])
      {

        echo "<option value='{$array[id]}' selected='selected'>$array[imagename]</option>\n";
      }
      else
      {
        echo "<option value='$array[id]'>$array[imagename]</option>\n";
      }
    }
    echo "</select>\n";
  }

  function dropboxbw($max, $min, $value, $name, $class)
  {
    echo "<select class='{$class}' name='{$name}'>\n";
    for($i=$min;$i<$max;$i++)
    {
      if($i==$value)
      {
        echo "<option value='{$i}' selected='selected'>$i</option>\n";
      }
      else
      {
        echo "<option value='{$i}'>$i</option>\n";
      }
    }
    echo "</select>\n";
  }

  function datebar($date, $maxYear, $minYear, $yearName, $monthName, $dayName, $blank)
  {
    $year=strtok($date, "-");
    $moth=strtok("-");
    $day=strtok("-");

    echo "<td class='edit_date_year_text'>Year:</td>\n";
    echo "<td class='edit_date_year_box'>\n";
    echo "<select name='{$yearName}'>\n";
    if($blank)
    {
      echo "<option value='0000'></option>\n";
    }
    for($i=$minYear;$i<$maxYear;$i++)
    {
      if($i==$year)
      {
        echo "<option value='{$i}' selected='selected'>$i</option>\n";
      }
      else
      {
        echo "<option value='{$i}'>$i</option>\n";
      }
    }
    echo "</select>\n";
    echo "</td>\n";
    echo "<td class='edit_date_month_text'>Month:</td>\n";
    echo "<td class='edit_date_month_box'>\n";
    echo "<select name='{$monthName}'>\n";
    if($blank)
    {
      echo "<option value='00'></option>\n";
    }
    for($i=1;$i<13;$i++)
    {
      if($i==$moth)
      {
        echo "<option value='{$i}' selected='selected'>$i</option>\n";
      }
      else
      {
        echo "<option value='{$i}'>$i</option>\n";
      }
    }
    echo "</select>\n";
    echo "</td>\n";
    echo "<td class='edit_date_day_text'>Day:</td>\n";
    echo "<td class='edit_date_day_box'>\n";
    echo "<select name='{$dayName}'>\n";
    if($blank)
    {
      echo "<option value='00'></option>\n";
    }
    for($i=1;$i<32;$i++)
    {
      if($i==$day)
      {
        echo "<option value='{$i}' selected='selected'>$i</option>\n";
      }
      else
      {
        echo "<option value='{$i}'>$i</option>\n";
      }
    }
    echo "</select>\n";
    echo "</td>\n";
  }

  /**
   * This function generates <input type="text" /> field with given paraeters
   * @param $class
   * @param $name
   * @param $maxlength
   * @param $value
   */
  function textinput($class, $name, $maxlength, $value)
  {
    ?>
<input class="<?php echo"{$class}" ?>" name="<?php echo"{$name}" ?>"
	type="text" maxlength="<?php echo"{$maxlength}" ?>"
	value="<?php echo"{$value}" ?>" />
    <?php
  }

  function textarea($tdclassleft, $tdclassright, $tdrightspan, $text, $textareaclass, $name, $value)
  {
    echo "<tr>\n";
    echo "<td class='{$tdclassleft}'></td>\n";
    ?>
<td class="<?php echo"{$tdclassright}" ?>"
	colspan="<?php echo"{$tdrightspan}" ?>"><a href="javascript:void(0);"
	onclick="surroundText('[b]','[/b]', document.forms.modified.<?php echo"{$name}" ?>); return false;"><img
	style="border: 0; width: 20px; height: 20px"
	src="./images/small/bold.png" alt="B" /></a> <a
	href="javascript:void(0);"
	onclick="surroundText('[i]','[/i]', document.forms.modified.<?php echo"{$name}" ?>); return false;"><img
	style="border: 0; width: 20px; height: 20px"
	src="./images/small/italic.png" alt="I" /></a> <a
	href="javascript:void(0);"
	onclick="surroundText('[u]','[/u]', document.forms.modified.<?php echo"{$name}" ?>); return false;"><img
	style="border: 0; width: 20px; height: 20px"
	src="./images/small/underline.png" alt="U" /></a> <a
	href="javascript:void(0);"
	onclick="surroundText('[q]','[/q]', document.forms.modified.<?php echo"{$name}" ?>); return false;"><img
	style="border: 0; width: 20px; height: 20px"
	src="./images/small/quote.png" alt="Q" /></a> <a
	href="javascript:void(0);"
	onclick="replaceText('[hr]', document.forms.modified.<?php echo"{$name}" ?>); return false;"><img
	style="border: 0; width: 20px; height: 20px"
	src="./images/small/line.png" alt="B" /></a> <a
	href="javascript:void(0);"
	onclick="surroundText('[img]','[/img]', document.forms.modified.<?php echo"{$name}" ?>); return false;"><img
	style="border: 0; width: 20px; height: 20px"
	src="./images/small/image.png" alt="image" /></a> <a
	href="javascript:void(0);"
	onclick="surroundText('[url]','[/url]', document.forms.modified.<?php echo"{$name}" ?>); return false;"><img
	style="border: 0; width: 20px; height: 20px"
	src="./images/small/url.png" alt="image" /></a></td>
    <?php
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class='{$tdclassleft}'>{$text}:</td>\n";
    echo "<td class='{$tdclassright}' colspan='{$tdrightspan}'><textarea class='{$textareaclass}' cols='6' rows='50' name='{$name}' onselect='javascript:storeCaret(this);' onclick='javascript:storeCaret(this);' onkeyup='javascript:storeCaret(this);' onchange='javascript:storeCaret(this);'>{$value}</textarea></td>\n";
    echo "</tr>\n";
  }
}
?>
