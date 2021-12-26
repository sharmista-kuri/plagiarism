<?php
ini_set('memory_limit', '-1');
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

function GUID()
{
    if (function_exists('com_create_guid') === true)
    {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}


$File_Ext = "";
$NewFileNameEnq = "";

$match_text= "";
$match_per= "";


$GUID = GUID();
$top = "100";

for($i=721;$i<731;$i++){
    $NewFileNameEnq = "query_".$i.".docx";
    $UploadDirectory = $_SERVER['DOCUMENT_ROOT'] ."/plagiarism/query_doc/";
    $file_name = $UploadDirectory.$NewFileNameEnq;

    $url = "";
    $res = "";
    $url = "http://127.0.0.1:5000/api/parsing/doc";
    $res = curlSendFile(new CURLFile($file_name), $url);

    $res = substr_replace($res ,"",-1);
    $res = rtrim($res, '"');
    $res = ltrim($res, '"');

    //echo $res; exit;

    $url = "http://127.0.0.1:8082/api/example/search";
    $res_java = curlSendText($res, $url, $top);

    $query = $res;
    $arr = json_decode($res_java, true);

    
    $json = json_decode($res_java, true);

    $query_explode = explode("।",$query);
    $sizes = sizeof($query_explode);

    $query = str_replace('"','',$query);

    $p_array = [];
    $name_array = [];
    $id_array = [];

    $firstArray[] = array();
    $myArray[] = array("query"=>$query);
    $percentage = 0;
    $flag=0;
    $counter = 0;
    $matched=0;
    $not_matched=0;

    

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
                //print_r($value);
                foreach ($value as $key2 => $value2) {
                    $flag_match=0;
                    foreach ($value2 as $key1 => $value1) {
                        //print_r($value1['id']);
                        if($flag_match==0){
                            $score = $value1['score'];
                            $query = $value1['query'];
                            $text = $value1['value'];
                            $id = $value1['id'];
                            $type = $value1['type'];
        
                            //$match_file[$id][] = $query;
        
                            //$matched_value="";
        
                            //print_r($query);
                            //print_r($text);
                            $check = check_match($text,$query);
                            //print_r("\n");
                            //print_r($check);
        
                            if($check!==""){
                                $flag_match=1;
                                $matched++;
                                //$matched_value.= $check."।";
                                $match_file[$id]["type"]=$type;
                                $match_file[$id][] = $check."।";
                                //break;
                                
                            }
                            else{
                                //print_r($query);
                                $not_match_file[$id][] = $query."।";
                                $not_matched++;
                            }
                        }
                    }
                }
            }
        }
    
    }

    $size = $sizes-1;
    if($matched>$size){
        if($matched-$size==1){
            $matched =$matched-1;
        }
    }
    $percentage = 100-(($size-$matched)*100)/$size;
    $percentage = number_format((float)$percentage, 2, '.', '');
    $type="url";
    //print_r($match_file);
    $txt = $size."\n".$matched."\n".$percentage;

    if($matched==0){
        echo "\n matched: 0: ".$i;
    }
    if($percentage>100){
        echo "\n percentage: 100: ".$percentage." q: ".$i;
    }

    $match_text.= $matched."\n";
    $match_per.= $percentage."\n";

    write($txt,$i);

    /* write_all_match($match_text);
    write_all_per($match_per); */


}

write_all_match($match_text);
write_all_per($match_per);

function check_match($result,$query){
    $matched_value = "";
    //print_r($query);
    //print_r("\n");
    //exit;
    if($result!=""){
        if (strpos($result, $query) !== false) {
            $matched_value.=$query;
        }
        else{
            /* print_r("res:".$result);
            print_r("qu:".$query);
            $pos = strpos($result, $query);
            print_r("pos: ".$pos); */
        }
    }
    
    return $matched_value;
}



function write($txt,$n){
    $myfile = fopen("match_100/matched_100_query_".$n.".txt", "w") or die("Unable to open file!");
    fwrite($myfile, $txt);
    fclose($myfile);
}

function write_all_match($txt){
    $myfile = fopen("matched_lines_100.txt", "a") or die("Unable to open file!");
    fwrite($myfile, $txt);
    fclose($myfile);
}

function write_all_per($txt){
    $myfile = fopen("percentages_100.txt", "a") or die("Unable to open file!");
    fwrite($myfile, $txt);
    fclose($myfile);
}







?>