<?php
 include "config.php";
 include "curl.php";

$File_Ext = "";
$NewFileNameEnq = "";
//print_r($_FILES);

$GUID = $_POST['GUID'];

if(!empty($_FILES) && $_FILES['file_upload']['name'] !="" && $_FILES['file_upload']['tmp_name']!="")
{
    
    $File_Ext = substr($_FILES['file_upload']['name'], strrpos($_FILES['file_upload']['name'], '.')); //get file extention
    $NewFileNameEnq = "file"."_".$GUID.$File_Ext; //new file name

    if(move_uploaded_file($_FILES['file_upload']['tmp_name'], $UploadDirectory.$NewFileNameEnq))
    {
        $flag = 0;
        $datas['file_upload'] = $NewFileNameEnq; 

        
    }
    else{ $flag++; }
}

$file_name = $UploadDirectory.$NewFileNameEnq;

$url = "";
if($File_Ext==".doc"||$File_Ext==".docx")
{
    $url = PYTHON_URL;
    $res = curlSendFile(new CURLFile($file_name), $url);
}

elseif($File_Ext==".txt")
{
    $fh = fopen($file_name,'r');
    $str = "";
    while ($line = fgets($fh)) {
        $str.=$line;
    }
    fclose($fh);
    $res = $str;
}


$res = substr_replace($res ,"",-1);
$res = rtrim($res, '"');
$res = ltrim($res, '"');
//echo $res;exit;
$type = "file";
$url = JAVA_STORE_URL;
$res_java = curlSendTextIndexing($res, $url, $GUID, "file");
 
echo $NewFileNameEnq;

?>