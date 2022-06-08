<?php
    define('SERVER_URL', "http://localhost/plagiarism/");
    define('PYTHON_URL', "http://127.0.0.1:5000/api/parsing/doc");
    define('JAVA_SEARCH_URL', "http://127.0.0.1:8082/api/example/search");
    define('JAVA_STORE_URL', "http://127.0.0.1:8082/api/example/store");
    define('WORD_COUNT', "3");
    $UploadDirectory = $_SERVER['DOCUMENT_ROOT'] ."/plagiarism/document_file/";

?>


<?php

    $serverName="localhost";
    $userName="root";
    $password="";
    $dbname="plagiarism_db";
    // Connect to MySQL
    $link = mysqli_connect($serverName, $userName, $password);
    mysqli_set_charset($link,'utf8');
    if (!$link) {
        die('Could not connect: ' . mysqli_error());
    }

    // Make my_db the current database
    $db_selected = mysqli_select_db($link, $dbname);

    if (!$db_selected) {
    // If we couldn't, then it either doesn't exist, or we can't see it.
        $sql = 'CREATE DATABASE $dbname';

        if (mysqli_query($sql, $link)) {
            echo "Database my_db created successfully\n";
            
            

        } else {
            echo 'Error creating database: ' . mysqli_error() . "\n";
        }
    }

    else{
        $table = "user_tbl";

        $str = "id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(250),
        email VARCHAR(250),
        password VARCHAR(250),
        user_type VARCHAR(250),
        code VARCHAR(250),
        email_verified tinyint(1) default 0,
        admin_verified tinyint(1) default 0,
        sts tinyint(1) default 1";

        create_table($link, $table, $str);

        $table = "document_tbl";

        $str = "id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        guid TEXT,
        file_name VARCHAR(255),
        path VARCHAR(255),
        sts tinyint(1) default 1";

        create_table($link, $table, $str);
       
    }

    function create_table($link, $table, $str){
        $create_table = "CREATE TABLE IF NOT EXISTS $table 
                        (
                            $str
                        )";

        $create_tbl = $link->query($create_table);
        //echo $create_table;

        if ($create_tbl)
        {
            //echo "Table has created";
        }
        else 
        {
            echo "error!!";  
        }
    }


   
?>