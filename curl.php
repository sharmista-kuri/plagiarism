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

function curlSendTextIndexing($text = '', $url = '', $GUID="0", $type)
{
    if ($text == '' || $url == '')
        return false;
    $post_data = [];
    $post_data["text"] = $text;
    $post_data["type"] = $type;
    $post_data["GUID"] = $GUID;
    return postCurlJava($url, $post_data);
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