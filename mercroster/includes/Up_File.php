<?php
class Upfile
{
  private $_logging;

  public function __construct($logging)
  {
    $this->_logging=$logging;
  }

  /**
   * Funtion used to strip all kind of nasty thing out of _POST data
   */
  private function strip($data)
  {
    require("htdocs/dbsetup.php");
    $data=stripslashes($data);
    $data=mysql_real_escape_string($data);
    $data=strip_tags($data);
    return $data;
  }

  /**
   *
   * @param <string> $path
   * @param int $sub
   * @return <string>
   */
  private function uploadimage($path, $sub)
  {
    $max_size=500000;

    $file_size=$_FILES['file']['size'];
    $file_name=$_FILES['file']['name'];
    $file_tmp_name=$_FILES['file']['tmp_name'];
    $file_type=$_FILES['file']['type'];

    $valid=true;
    $errmsg="";

    //Exist check
    if (file_exists("$path" . "$file_name"))
    {
      $valid=false;
      $errmsg.="FileExists";
    }

    //Size Check
    if ((int)$file_size > (int)$max_size)
    {
      $valid=false;
      $errmsg.="FileTooLarge";
    }

    //Type check
    $type=explode("/", $file_type);
    $type=$type[0];
    if ($type != "image")
    {
      $valid=false;
      $errmsg.="FileNotImage";
    }

    if($valid)
    {
      move_uploaded_file($file_tmp_name, $path . $file_name);
      if($this->_logging)
      {
        $uploadtime=date("Y-m-d, H:i:s");
        $size=number_format($file_size/1024, 1, ',', '');
        $tolog=("Adding image|" . $_SESSION['SESS_NAME'] . "|" . $uploadtime . "|" . $file_name . "|" . $size . "kB|" . $file_type ."\n");
        $log=fopen("logs/images.log", "a");
        fwrite($log, $tolog);
        fclose($log);
      }
    }
    if($errmsg!="")
    {
      return("location:index.php?action=command&page=4&sub={$sub}&mgs={$errmsg}");
    }
    else
    {
      return("location:index.php?action=command&page=4&sub={$sub}");
    }
  }

  /**
   *
   * @param $path
   * @param $imagefilename
   * @param $sub
   * @return unknown_type
   */
  private function removeimage($path, $imagefilename, $sub)
  {
    $path = $path.$imagefilename;
    $fh = fopen($path, 'w') or die("ERROR: CAN'T FIND FILE IN DIRECTORY");
    fclose($fh);
    unlink($path);

    if($this->_logging)
    {
      $uploadtime=date("Y-m-d, H:i:s");
      $tolog=("Removing image|" . $_SESSION['SESS_NAME'] . "|" . $uploadtime . "|" . $imagefilename ."\n");
      $log=fopen("logs/images.log", "a");
      fwrite($log, $tolog);
      fclose($log);
    }

    return("location:index.php?action=command&page=7&sub={$sub}");
  }

  /**
   *
   * @param <string> $path
   * @param int $sub
   * @return <string>
   */
  private function uploadgalleryimage($path, $sub, $name, $comment)
  {
    $max_size=500000;

    $file_size=$_FILES['file']['size'];
    $file_name=$_FILES['file']['name'];
    $file_tmp_name=$_FILES['file']['tmp_name'];
    $file_type=$_FILES['file']['type'];

    $valid=true;
    $errmsg="";

    //Exist check
    if (file_exists("$path" . "$file_name"))
    {
      $valid=false;
      $errmsg.="FileExists";
    }

    //Size Check
    if ((int)$file_size > (int)$max_size)
    {
      $valid=false;
      $errmsg.="FileTooLarge";
    }

    //Type check
    $type=explode("/", $file_type);
    $type=$type[0];
    if ($type != "image")
    {
      $valid=false;
      $errmsg.="FileNotImage";
    }

    if($valid)
    {
      move_uploaded_file($file_tmp_name, $path . $file_name);
      if($this->_logging)
      {
        $uploadtime=date("Y-m-d, H:i:s");
        $size=number_format($file_size/1024, 1, ',', '');
        $tolog=("Adding image|" . $_SESSION['SESS_NAME'] . "|" . $uploadtime . "|" . $file_name . "|" . $size . "kB|" . $file_type ."\n");
        $log=fopen("logs/images.log", "a");
        fwrite($log, $tolog);
        fclose($log);
      }
    }
    if($errmsg!="")
    {
      return("location:index.php?action=gallery&gallery={$sub}&mgs={$errmsg}");
    }
    else
    {
      require("htdocs/dbsetup.php");
      $dbf=new DBFunctions;
      $queryArray[sizeof($queryArray)]="INSERT INTO images (name, filename, gallery, comment) VALUES ('{$name}', '{$file_name}', '{$sub}', '{$comment}');";
      $dbf->queryarray($queryArray);
      return("location:index.php?action=gallery&gallery={$sub}");
    }
  }

  /**
   *
   * @param $path
   * @param $imagefilename
   * @param $sub
   * @return unknown_type
   */
  private function removegalleryimage($imageid, $path, $imagefilename, $sub)
  {
    require("htdocs/dbsetup.php");
    $dbf=new DBFunctions;
    $queryArray[sizeof($queryArray)]="DELETE FROM images WHERE id='{$imageid}';"; //delete image
    $dbf->queryarray($queryArray);

    $path = $path.$imagefilename;
    $fh = fopen($path, 'w') or die("ERROR: CAN'T FIND FILE IN DIRECTORY");
    fclose($fh);
    unlink($path);

    if($this->_logging)
    {
      $uploadtime=date("Y-m-d, H:i:s");
      $tolog=("Removing image|" . $_SESSION['SESS_NAME'] . "|" . $uploadtime . "|" . $imagefilename ."\n");
      $log=fopen("logs/images.log", "a");
      fwrite($log, $tolog);
      fclose($log);
    }
    return("location:index.php?action=gallery&gallery={$sub}");
  }

  /**
   *
   * @param <string> $path
   * @return <string>
   */
  private function uploadpdf($path)
  {
    $max_size=500000;

    $file_size=$_FILES['file']['size'];
    $file_name=$_FILES['file']['name'];
    $file_tmp_name=$_FILES['file']['tmp_name'];
    $file_type=$_FILES['file']['type'];

    $valid=true;
    $errmsg="";

    //Exist check
    if (file_exists("$path" . "$file_name"))
    {
      $valid=false;
      $errmsg.="FileExists";
    }

    //Size Check
    if ((int)$file_size > (int)$max_size)
    {
      $valid=false;
      $errmsg.="FileTooLarge";
    }

    //Type check
    if ($file_type!="application/pdf")
    {
      $valid=false;
      $errmsg.="FileNotPDF";
    }

    if($valid)
    {
      move_uploaded_file($file_tmp_name, $path . $file_name);
      if($this->_logging)
      {
        $uploadtime=date("Y-m-d, H:i:s");
        $size=number_format($file_size/1024, 1, ',', '');
        $tolog=("Adding pdf|" . $_SESSION['SESS_NAME'] . "|" . $uploadtime . "|" . $file_name . "|" . $size . "kB|" . $file_type ."\n");
        $log=fopen("logs/pdf.log", "a");
        fwrite($log, $tolog);
        fclose($log);
      }
    }
    if($errmsg!="")
    {
      return("location:index.php?action=controlsheettable&first=0&mgs={$errmsg}");
    }
    else
    {
      return("location:index.php?action=controlsheettable&first=0");
    }
  }

  /**
   *
   * @param $path
   * @param $imagefilename
   * @return unknown_type
   */
  private function removepdf($path, $imagefilename)
  {
    $path = $path.$imagefilename;
    $fh = fopen($path, 'w') or die("ERROR: CAN'T FIND FILE IN DIRECTORY");
    fclose($fh);
    unlink($path);

    if($this->_logging)
    {
      $uploadtime=date("Y-m-d, H:i:s");
      $tolog=("Removing pdf|". $_SESSION['SESS_NAME'] ."|" . $uploadtime . "|" . $imagefilename ."\n");
      $log=fopen("logs/pdf.log", "a");
      fwrite($log, $tolog);
      fclose($log);
    }
    return("location:index.php?action=controlsheettable&first=0");
  }

  function handleimages()
  {
    if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<'4')
    {
      $type=$this->strip($_POST['type']);
      $sub=$this->strip($_POST['sub']);
      $path=$this->strip($_POST['path']);
      if(substr_count($path, ".."))
      {

      }
      if($type=="imageadd" && $path!="")
      {
        return($this->uploadimage($path, $sub));
      }
      if($type=="galleryimageadd" && $path!="")
      {
        $name=$this->strip($_POST['name']);
        $comment=$this->strip($_POST['comment']);
        return($this->uploadgalleryimage($path, $sub, $name, $comment));
      }
      if($type=="imagerm" && $path!="")
      {
        $imagefilename=$this->strip($_POST['imagefilename']);
        return($this->removeimage($path, $imagefilename, $sub));
      }
      if($type=="galleryimagerm" && $path!="")
      {
        $imageid=$this->strip($_POST['imageid']);
        $imagefilename=$this->strip($_POST['imagefilename']);
        return($this->removegalleryimage($imageid, $path, $imagefilename, $sub));
      }
      return("location:index.php?action=incorrectargument");
    }
    else
    {
      return("location:index.php?action=accessdenied");
    }
  }

  function handlecontrolsheets()
  {
    if(isset($_SESSION['SESS_ID']) && $_SESSION['SESS_TYPE']<'4')
    {
      $type=$this->strip($_POST['type']);
      $path="controlsheets/";
      if($type=="pdfadd")
      {
        return($this->uploadpdf($path));
      }
      if($type=="pdfrm")
      {
        $pdffilename=$this->strip($_POST['filename']);
        return($this->removepdf($path, $pdffilename));
      }
      return("location:index.php?action=incorrectargument");
    }
    else
    {
      return("location:index.php?action=accessdenied");
    }
  }
}
?>