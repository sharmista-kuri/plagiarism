<?php
 
/**
 * htppCurl form upload file
* @param $src
* @param string $urlRoute
* @return bool|mixed
*/
function curlSendFile(CURLFile $file, $url = '', $key = "123456")
{
    if ($file == null || $url == '')
        return false;
    $post_data = [];
    $post_data["file"] = $file;
    $post_data["key"] = $key;
    return postCurl($url, $post_data);
}

function curlSendText($text = '', $url = '', $top=10)
{
    if ($text == '' || $url == '')
        return false;
    $post_data = [];
    $post_data["text"] = $text;
    $post_data["top"] = $top;
    return postCurlJava($url, $post_data);
}

function curlCompare($text = '',$query = '', $url = '')
{
    if ($text == '' || $url == '')
        return false;
    $post_data = [];
    $post_data["text"] = $text;
    $post_data["query"] = $query;
    return postCurlCompare($url, $post_data);
}
  
 /**
    * CurlPost request
  * @param $url
  * @param $data
  * @return mixed
  * @author Bill
  */
function postCurl($url, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $output = curl_exec($ch);
    curl_close($ch);
    //echo $output;
    return $output;
}

function postCurlJava($url, $data)
{  
    
    $data = json_encode($data, JSON_UNESCAPED_UNICODE);    
    $ch = curl_init($url);
    $headers = array(

        'Content-Type: application/json',
        'Accept: application/json'
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $output = curl_exec($ch);

    curl_close($ch);
    //echo $output;

    return $output;
}

function postCurlCompare($url, $data)
{
    $data = json_encode($data, JSON_UNESCAPED_UNICODE); 
    $ch = curl_init();
    $headers = array(

        'Content-Type: application/json',
        'Accept: application/json'
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $output = curl_exec($ch);
    curl_close($ch);
    //echo $output;
    return $output;
}
  

$UploadDirectory = $_SERVER['DOCUMENT_ROOT'] ."/plagiarism/document_file/";
$File_Ext = "";
$NewFileNameEnq = "";


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
$res = "";
if($File_Ext==".doc"||$File_Ext==".docx")
{
    $url = "http://127.0.0.1:5000/api/parsing/doc";
}
elseif($File_Ext==".pdf")
{
    $url = "http://127.0.0.1:5000/api/parsing/pdf";
}
$res = curlSendFile(new CURLFile($file_name), $url);


//function search_function($res)
{
    $top = $_POST['top'];
    $url = "http://127.0.0.1:8081/api/example/search";
    $res_java = curlSendText($res, $url, $top);

    $query = $res;




    $json = json_decode($res_java, true);

    print_r($json);
    $p_array = [];
    $name_array = [];
    $id_array = [];

    $firstArray[] = array();
    $myArray[] = array("query"=>$query);
    $percentage = 0;
    foreach($json as $key=>$value)
    {
        //echo $key;

        if($key=="data"){
            foreach ($value as $key1 => $value1) {
                
                //print_r($value1['id']);
                //echo '</br>';
                $id = $value1['id'];
                $text = $value1['value'];


                $url = "http://127.0.0.1:5000/api/compare";
                $percentage = curlCompare($text, $query, $url);

                
                $myArray[] = array("id" => $id,"value" => $text, "percentage" => $percentage);

                //print_r($firstArray);
                
            }
        }
    }



    //echo $query;
    //convert to json
    $json = json_encode($myArray, JSON_UNESCAPED_UNICODE);

    //return $json;
    echo $json;

}


/* $res_explode = explode("।",$res);

//echo'<pre>';print_r($res_explode);exit;
//search_function($res);
foreach($res_explode as $result){
    $str = search_function($result);
    echo json_encode($str, JSON_UNESCAPED_UNICODE);
}

function search_function($res){
    $top = $_POST['top'];
    $url = "http://127.0.0.1:8081/api/example/search";
    $res_java = curlSendText($res, $url, $top);

    $query = $res;




    $json = json_decode($res_java, true);

    print_r($json);
    $p_array = [];
    $name_array = [];
    $id_array = [];

    $firstArray[] = array();
    $myArray[] = array("query"=>$query);
    $percentage = 0;
    foreach($json as $key=>$value)
    {
        //echo $key;

        if($key=="data"){
            foreach ($value as $key1 => $value1) {
                
                //print_r($value1['id']);
                //echo '</br>';
                $id = $value1['id'];
                $text = $value1['value'];


                $url = "http://127.0.0.1:5000/api/compare";
                $percentage = curlCompare($text, $query, $url);

                
                $myArray[] = array("id" => $id,"value" => $text, "percentage" => $percentage);

                //print_r($firstArray);
                
            }
        }
    }



    //echo $query;
    //convert to json
    $json = json_encode($myArray, JSON_UNESCAPED_UNICODE);

    return $json;
    //echo $json;
} */







?>