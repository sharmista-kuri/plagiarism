<?php
    function curlSendFile(CURLFile $file, $url = '', $key = "123456")
    {
        if ($file == null || $url == '')
            return false;
        $post_data = [];
        $post_data["file"] = $file;
        $post_data["key"] = $key;
        return postCurl($url, $post_data);
    }

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
    $txt = "";
    for($i=1;$i<1005;$i++){
        
        $NewFileNameEnq = "query_".$i.".docx";
        $UploadDirectory = $_SERVER['DOCUMENT_ROOT'] ."/web_crawler/query_doc/";
        $file_name = $UploadDirectory.$NewFileNameEnq;
        $url = "http://127.0.0.1:5000/api/parsing/doc";
        $res = curlSendFile(new CURLFile($file_name), $url);

        $query_explode = explode("।",$res);
        $sizes = sizeof($query_explode);

        //$txt.= $NewFileNameEnq." lines: ".$sizes."\n";
        //echo $i."<br>";
        //echo $NewFileNameEnq."<br>";
        $txt.= $sizes."\n";
        

    }
    write($txt);

    function write($txt){
        $myfile = fopen("lines.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $txt);
        fclose($myfile);
    }
    
    

?>