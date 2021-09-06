<?php

function crawl_page($url, $depth = 5)
{
    static $seen = array();
    if (isset($seen[$url]) || $depth === 0) {
        return;
        echo 'END';
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
            write($row->nodeValue);
		}
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

$url = "https://bn.wikipedia.org/wiki/ঢাকা_বিশ্ববিদ্যালয়";
crawl_page($url, 5);



function write($txt){
    $myfile = fopen("scrape_file.txt", "a") or die("Unable to open file!");
    fwrite($myfile, $txt);
    fclose($myfile);
}

