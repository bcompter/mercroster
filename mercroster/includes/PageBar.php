<?php
class PageBar
{
  function generatebar($rnumber, $first, $range, $link)
  {
    if($rnumber>0)
    {
      echo "<b>Pages:</b>\n";

      $i=0;
      $rangecount=$rnumber+$range;
      $brakets=1+($first/$range);
      $pages=ceil($rnumber/$range);
      if($pages<8)
      {
        while($rangecount>$range)
        {
          $j=$i*$range;
          $i++;
          if($i==$brakets)
          {
            echo "<b>[{$i}]</b>";
          }
          else
          {
            echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=$j'> $i </a>";
          }
          $rangecount=$rangecount-$range;
        }
      }
      else
      {
        if($brakets<5)
        {
          for($i=1; $i<7; $i++)
          {
            $j=($i*$range)-$range;
            if($i==$brakets)
            {
              echo "<b>[{$i}]</b>";
            }
            else
            {
              echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=$j'> $i </a>";
            }
          }
          echo"...";
          $j=$range*($pages-1);
          echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=$j'> $pages  </a>";
        }
        else if(($pages-$brakets)<5)
        {
          echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=0'> 1 </a>";
          echo"...";
          for($i=$pages-5; $i<$pages+1; $i++)
          {
            $j=($i*$range)-$range;
            if($i==$brakets)
            {
              echo "<b>[{$i}]</b>";
            }
            else
            {
              echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=$j'> $i </a>";
            }
          }
        }
        else
        {
          echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=0'> 1 </a>";
          echo"...";
          for($i=$brakets-2; $i<$brakets+3; $i++)
          {
            $j=($i*$range)-$range;
            if($i==$brakets)
            {
              echo "<b>[{$i}]</b>";
            }
            else
            {
              echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=$j'> $i </a>";
            }
          }
          echo"...";
          $j=$range*($pages-1);
          echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=$j'> $pages  </a>";
        }
      }
    }
    else
    {
      echo "<b>Pages: [1]</b>\n";
    }
  }

function generatecommentbar($rnumber, $first, $range, $link)
  {
    if($rnumber>0)
    {
      echo "<b>Pages:</b>\n";

      $i=0;
      $rangecount=$rnumber+$range;
      $brakets=1+($first/$range);
      $pages=ceil($rnumber/$range);
      if($pages<8)
      {
        while($rangecount>$range)
        {
          $j=$i*$range;
          $i++;
          if($i==$brakets)
          {
            echo "<b>[{$i}]</b>";
          }
          else
          {
            echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=lst.{$j}'> $i </a>";
          }
          $rangecount=$rangecount-$range;
        }
      }
      else
      {
        if($brakets<5)
        {
          for($i=1; $i<7; $i++)
          {
            $j=($i*$range)-$range;
            if($i==$brakets)
            {
              echo "<b>[{$i}]</b>";
            }
            else
            {
              echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=lst.{$j}'> $i </a>";
            }
          }
          echo"...";
          $j=$range*($pages-1);
          echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=lst.{$j}'> $pages  </a>";
        }
        else if(($pages-$brakets)<5)
        {
          echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=0'> 1 </a>";
          echo"...";
          for($i=$pages-5; $i<$pages+1; $i++)
          {
            $j=($i*$range)-$range;
            if($i==$brakets)
            {
              echo "<b>[{$i}]</b>";
            }
            else
            {
              echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=lst.{$j}'> $i </a>";
            }
          }
        }
        else
        {
          echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=0'> 1 </a>";
          echo"...";
          for($i=$brakets-2; $i<$brakets+3; $i++)
          {
            $j=($i*$range)-$range;
            if($i==$brakets)
            {
              echo "<b>[{$i}]</b>";
            }
            else
            {
              echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=lst.{$j}'> $i </a>";
            }
          }
          echo"...";
          $j=$range*($pages-1);
          echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=lst.{$j}'> $pages  </a>";
        }
      }
    }
    else
    {
      echo "<b>Pages: [1]</b>\n";
    }
  }
  
  function generatebarlink($rnumber, $first, $range, $link, $rightlink, $rightlinktext)
  {
    $i=0;
    $rangecount=$rnumber+$range;
    $brakets=1+($first/$range);
    $pages=ceil($rnumber/$range);

    echo "<div class='pagebar'>\n";
    echo "<b>Pages:</b>\n";
    if($pages<8)
    {
      while($rangecount>$range)
      {
        $j=$i*$range;
        $i++;
        if($i==$brakets)
        {
          echo "<b>[{$i}]</b>";
        }
        else
        {
          echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=$j'> $i </a>";
        }
        $rangecount=$rangecount-$range;
      }
    }
    else
    {
      if($brakets<5)
      {
        for($i=1; $i<7; $i++)
        {
          $j=($i*$range)-$range;
          if($i==$brakets)
          {
            echo "<b>[{$i}]</b>";
          }
          else
          {
            echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=$j'> $i </a>";
          }
        }
        echo"...";
        $j=$range*($pages-1);
        echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=$j'> $pages  </a>";
      }
      else if(($pages-$brakets)<5)
      {
        echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=0'> 1 </a>";
        echo"...";
        for($i=$pages-5; $i<$pages+1; $i++)
        {
          $j=($i*$range)-$range;
          if($i==$brakets)
          {
            echo "<b>[{$i}]</b>";
          }
          else
          {
            echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=$j'> $i </a>";
          }
        }
      }
      else
      {
        echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=0'> 1 </a>";
        echo"...";
        for($i=$brakets-2; $i<$brakets+3; $i++)
        {
          $j=($i*$range)-$range;
          if($i==$brakets)
          {
            echo "<b>[{$i}]</b>";
          }
          else
          {
            echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=$j'> $i </a>";
          }
        }
        echo"...";
        $j=$range*($pages-1);
        echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=$j'> $pages  </a>";
      }
    }
    echo "<a class='pagebartableedit' href='index.php{$rightlink}'>{$rightlinktext}</a>\n";
    echo "</div>\n";
  }

  function generatebarx($rnumber, $first, $range, $link, $permission, $add, $addtype)
  {
    $i=0;
    $rangecount=$rnumber+$range;
    $brakets=1+($first/$range);
    $pages=ceil($rnumber/$range);

    echo "<div class='pagebar'>\n";
    echo "<b>Pages:</b>\n";
    if($pages<8)
    {
      while($rangecount>$range)
      {
        $j=$i*$range;
        $i++;
        if($i==$brakets)
        {
          echo "<b>[{$i}]</b>";
        }
        else
        {
          echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=$j'> $i </a>";
        }
        $rangecount=$rangecount-$range;
      }
    }
    else
    {
      if($brakets<5)
      {
        for($i=1; $i<7; $i++)
        {
          $j=($i*$range)-$range;
          if($i==$brakets)
          {
            echo "<b>[{$i}]</b>";
          }
          else
          {
            echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=$j'> $i </a>";
          }
        }
        echo"...";
        $j=$range*($pages-1);
        echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=$j'> $pages  </a>";
      }
      else if(($pages-$brakets)<5)
      {
        echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=0'> 1 </a>";
        echo"...";
        for($i=$pages-5; $i<$pages+1; $i++)
        {
          $j=($i*$range)-$range;
          if($i==$brakets)
          {
            echo "<b>[{$i}]</b>";
          }
          else
          {
            echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=$j'> $i </a>";
          }
        }
      }
      else
      {
        echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=0'> 1 </a>";
        echo"...";
        for($i=$brakets-2; $i<$brakets+3; $i++)
        {
          $j=($i*$range)-$range;
          if($i==$brakets)
          {
            echo "<b>[{$i}]</b>";
          }
          else
          {
            echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=$j'> $i </a>";
          }
        }
        echo"...";
        $j=$range*($pages-1);
        echo"<a class='pagebartablelink' href='index.php?action={$link}&amp;first=$j'> $pages  </a>";
      }
    }
    if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<=$permission && $_SESSION['SESS_TYPE']>0 && isset($add) && $add!="")
    {
      echo "<a class='pagebartableedit' href='index.php{$add}'>Add {$addtype}</a>\n";
    }
    echo "</div>\n";
  }
}
?>