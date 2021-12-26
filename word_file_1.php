<?php
ini_set('memory_limit', '-1');
require_once 'vendor/autoload.php';

$results=array();
foreach(glob('/xampp/htdocs/plagiarism/query_1/*.*') as $filename){
    //$results[] = $path;
    array_push($results,$filename);
}
print_r($results);



$n=0;
$counter=1000;
$words="";
$filenames="";
foreach ($results as $key => $value) {
    $n++;
    if(is_file($value))
    {
        $filename = basename($value);
        $filename_ex_txt = explode(".",$filename);
        $filename_ex = explode("_",$filename_ex_txt[0]);

        $filenames.="\n".$filename_ex[1];
        $file_name_count = $filename_ex[1];

        //if($file_name_count%10==0){
            $myfile = fopen($value, "r") or die("Unable to open file!");
            while(!feof($myfile)) {
                print_r($value);
                $words.=fgets($myfile);
                //print_r($words);
                

            }
            //$words = preg_replace("/[a-zA-Z0-9]/", "", $words);
            $words.="######%%######";
            fclose($myfile);

            print_r($filenames);
        
            if($n%5==0){
                $counter++;
                $myfile = fopen("query_doc_new/query_".$counter.".txt", "a") or die("Unable to open file!");
                fwrite($myfile, $filenames);
                fclose($myfile);

                $phpword = new \PhpOffice\PhpWord\PhpWord();
                $section = $phpword->addSection();
                
                $textlines = explode("######%%######", $words);

                $textrun = $section->addTextRun();
                $textrun->addText(array_shift($textlines));
                foreach($textlines as $line) {
                    $textrun->addTextBreak(2);
                    // maybe twice if you want to seperate the text
                    // $textrun->addTextBreak(2);
                    $textrun->addText($line);
                }
                //$section->addText($words);
                $phpword->save('query_doc_new/query_'.$counter.'.docx', 'Word2007');

                $words="";
                $filenames="";

            }
        //}
        

        //echo $result;
    }
        
}

