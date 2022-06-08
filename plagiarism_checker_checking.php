<?php
include "config.php";
include "curl.php";

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
    $url = PYTHON_URL;
}

$res = curlSendFile(new CURLFile($file_name), $url);

$res = substr_replace($res ,"",-1);
$res = rtrim($res, '"');
$res = ltrim($res, '"');

$url = JAVA_SEARCH_URL;
$res_java = curlSendText($res, $url, $top);

//print_r($res);

$query = $res;
$arr = json_decode($res_java, true);


$json = json_decode($res_java, true);

$query_explode = explode("।",$query);
$sizes = sizeof($query_explode);

$query = str_replace('"','',$query);

//print_r($json);
//print_r($sizes);

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


if (is_array($json) || is_object($json))
{
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
}

//print_r($json);
$match_file=array();
if (is_array($json) || is_object($json))
{
    foreach($json as $key=>$value){
        if($flag){ 
            if($key!="counter"){
                //print_r($value);
                foreach ($value as $key2 => $value2) {
                    foreach ($value2 as $key1 => $value1) {
                        //print_r($value1['id']);
                        $score = $value1['score'];
                        $query = $value1['query'];
                        $text = $value1['value'];
                        $id = $value1['id'];
                        $type = $value1['type'];

                        //$match_file[$id][] = $query;

                        //$matched_value="";

                        //print_r($query);
                        //print_r($text);
                        //$check = check_match($text,$query);


                        
                        $query_array = get_list_of_words($query);
                        
                        $k=0;
                        

                        $num_of_words = sizeof($query_array) - 1;

                        //print_r("line: ".($num_of_words)."\n");
                    
                        for($i = 0; $i < $num_of_words; $i++){
                            $k=$i;
                            if($k%WORD_COUNT==0){
                                $words_sen="";
                                for($j = 0; $j < WORD_COUNT; $j++){
                                    if($k<$num_of_words){
                                        $words_sen.=$query_array[$k]." ";
                                        //print_r("k: ".$i."\n");
                                        $k++;
                                    }
                                    
                                }
                                //print_r("line: ".$words_sen."\n");
                                //print_r("\n");

                                $check = check_match($text,$words_sen);

                                //print_r("check: ".$check."\n");


                                if($check!==""){
                                    $matched++;
                                    //$matched_value.= $check."।";
                                    $match_file[$id]["score"]=$score;
                                    $match_file[$id]["type"]=$type;
                                    $match_file[$id][] = $check;
                                    
                                    //break;
                                    
                                }
                                else{
                                    //print_r($query);
                                    $not_match_file[$id][] = $query."।";
                                    $not_matched++;
                                }
                            }

                        }
                        


                        //print_r(sizeof($query_array));



                        

                        //print_r("\n");
                        //print_r($check);

                        
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

if($size==0){
    $size=1;
}

$percentage = 100-(($size-$matched)*100)/$size;
if($percentage>100){
    $percentage = 100 - $percentage;
}
$percentage = number_format((float)$percentage, 2, '.', '');
$type="url";
//print_r($match_file);
if (is_array($match_file) || is_object($match_file))
{
    foreach($match_file as  $key=>$value){
        $matched_value="";
        $id = $key;
        //print_r($value['type']);
        $type = $value['type'];
        $score = $value['score'];
        
        foreach ($value as $key1 => $value1) {
            if($key1!="type" && $key1!="score"){
                $matched_value.= $value1;
                //print_r($key1);
            }

        }
        $myArray[] = array("id" => $id,"type" => $type,"value" => $matched_value, "percentage" => $score);
        //print_r($key);
        //print_r($value);
        //print_r($matched_value);
    }
}

function check_match($result,$query){
    $matched_value = "";
    //print_r($query);
    //print_r("\n");
    //exit;
    if($result!=""){
        if (strpos($result, $query) !== false) {
            $matched_value.=$query;
        }
    }
    
    return $matched_value;
}

function get_list_of_words($query){
    
    $query_array = explode(' ', $query);

    //print_r($query_array);

    return $query_array;


}


//convert to json
$json = json_encode($myArray, JSON_UNESCAPED_UNICODE);

echo $json;





?>