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
$top = $_POST['top'];

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

$res = substr_replace($res ,"",-1);
$res = rtrim($res, '"');
$res = ltrim($res, '"');

$url = "http://127.0.0.1:8082/api/example/search";
$res_java = curlSendText($res, $url, $top);



$query = $res;
$arr = json_decode($res_java, true);



$json = json_decode($res_java, true);

$query_explode = explode("।",$query);
$sizes = sizeof($query_explode);

$query = str_replace('"','',$query);

//print_r($json);

$p_array = [];
$name_array = [];
$id_array = [];

$firstArray[] = array();
$myArray[] = array("query"=>$query);
$percentage = 0;
$flag=0;
$counter = 0;




foreach($json as $key=>$value){
    //echo $key;
    //print_r ($value);
    if($key=="counter"){
        if($value!="0"){
            $flag=1;
            $counter = $value;
        }
    }
}
foreach($json as $key=>$value){
    if($flag){ 
        if($key!="counter"){
            //echo'<pre>';print_r($key);
            foreach ($value as $key1 => $value1) {
                
                //print_r($value1['score']);
                //echo '</br>';
              

                $score = $value1['score'];
                $query = $value1['query'];
                $text = $value1['value'];
                $id = $value1['id'];
                $type = $value1['type'];
                $matched_value = "";

                /* $text = substr_replace($text ,"",-1);
                $res = substr_replace($res ,"",-1);

                $text = str_replace('"','',$text);
                $res = str_replace('"','',$res); */

                /* echo 'res: '.$res; 
                echo 'text: '.$text;  */


                $text_explode = explode("।",$text);

                $matched=0;

                foreach($text_explode as $result){

                    $check = check_match($result,$res);
                    if($check!==""){
                        $matched++;
                        $matched_value.= $check."।";
                    }

                    //else{
                        
                    //}
                    
                    
                    
                    
                }   
                
                
                if($matched!==0){
                    //echo 'matched: '.$c; 
                    //echo 'size: '.$size; 
                    $size = $sizes-1;
                    //echo $matched;
                    //echo $size;
                    
                    $percentage = 100-(($size-$matched)*100)/$size;
                    $percentage = number_format((float)$percentage, 2, '.', ''); 
                    //echo 'matched: '.$left;  

                    $matched_value = rtrim($matched_value, '।');

                    $myArray[] = array("id" => $id,"type" => $type,"value" => $matched_value, "percentage" => $percentage);
                }
                


                
            }
        }
        
    }
}

function check_match($result,$query){
    $matched_value = "";
    if($result!=""){
        if (strpos($query, $result) !== false) {
            $matched_value.=$result;
        }
    }
    
    return $matched_value;
}

/* function check_match($result,$query){
    $matched_value = "";
    $query_explode = explode("।",$query);
    foreach($query_explode as $query){
        if (strcmp($query, $result) !== 0) {
            //$matched_value.=$result;
            $result_explode = explode("\n",$result);
            foreach($result_explode as $result){
                if (strcmp($query, $result) !== 0) 
                {
                    $result_explode = explode("\t",$result);
                    foreach($result_explode as $result){
                        if (strcmp($query, $result) !== 0) 
                        {
                            
                        }
                        else {
                            $matched_value.=$result;
                
                        }
                    }
                }
                else {
                    $matched_value.=$result;
        
                }
            }


        }
        else {
            $matched_value.=$result;

        }
    }
    
    return $matched_value;
} */ 

//print_r($myArray);

/* foreach($json as $key=>$value)
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
} */



//echo $query;
//convert to json
$json = json_encode($myArray, JSON_UNESCAPED_UNICODE);

echo $json;





?>