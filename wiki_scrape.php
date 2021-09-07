<?php

function curlSendText($text = '', $url = '', $GUID="0", $type)
{
    if ($text == '' || $url == '')
        return false;
    $post_data = [];
    $post_data["text"] = $text;
    $post_data["type"] = $type;
    $post_data["GUID"] = $GUID;
    return postCurlJava($url, $post_data);
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

function crawl_page($url, $depth = 5)
{
    static $seen = array();
    $text = "";
    
    if (isset($seen[$url]) || $depth === 0) {
        return;
    }

    $seen[$url] = true;

    //echo "URL: ".$url;

    $dom = new DOMDocument('1.0');
    @$dom->loadHTMLFile($url);

    $anchors = $dom->getElementsByTagName('a');

    libxml_clear_errors(); //remove errors for yucky html

    $xpath = new DOMXPath($dom);

    $row = $xpath->query('//p');

	if($row->length > 0){
		foreach($row as $row){
			echo $row->nodeValue . "<br/>";
            $text.= $row->nodeValue;
		}
	}
    
    //write_url($url,$depth);
    if($text!=""){
        //$text = "URL:".$url."___##$$##!@#$%___".$text;
        //write($text,$depth);

        $url_java = "http://127.0.0.1:8081/api/example/store";
        $res_java = curlSendText($text, $url_java, $url, "url");
    }

    

    foreach ($anchors as $element) {
        $href = $element->getAttribute('href');
        
        if (0 !== strpos($href, 'https://bn.wikipedia.org/')) {
            $path = '/' . ltrim($href, '/');
            if (extension_loaded('http')) {
                $href = http_build_url($url, array('path' => $path));

                //echo "if: ".$href;

                //echo strpos($href, 'https://bn.wikipedia.org/');

            } else {
                $parts = parse_url($url);

                if($parts['host']!='bn.wikipedia.org'){
                    return;
                    //echo $parts['host'];
                }

                $href = $parts['scheme'] . '://';
                if (isset($parts['user']) && isset($parts['pass'])) {
                    $href .= $parts['user'] . ':' . $parts['pass'] . '@';
                }
                $href .= $parts['host'];
                if (isset($parts['port'])) {
                    $href .= ':' . $parts['port'];
                }
                $href .= dirname($parts['path'], 1).$path;

                //echo "scheme: ".$parts['scheme'];
                //echo "host: ".$parts['host'];

                //echo "ELSEPART:".strpos($href, 'https://bn.wikipedia.org/');
            }
        }
        crawl_page($href, $depth - 1);
    }

    

    //echo "URL:",$url,PHP_EOL,"CONTENT:",PHP_EOL,$dom->saveHTML(),PHP_EOL,PHP_EOL;
}

//$url = "https://bn.wikipedia.org/wiki/ঢাকা_বিশ্ববিদ্যালয়";
$url = $_POST['url'];
crawl_page($url, 5);

function write_url($url,$counter){
    $myfile = fopen("scrape_file.txt", "a") or die("Unable to open file!");
    fwrite($myfile, $counter);
    fwrite($myfile, $url);
    fclose($myfile);
}

function write($txt,$counter){
    
    //$myfile = fopen("scrape_file_".$counter.".txt", "w") or die("Unable to open file!");
    $myfile = fopen("scrape_file.txt", "a") or die("Unable to open file!");
    fwrite($myfile, $txt);
    fclose($myfile);
}

