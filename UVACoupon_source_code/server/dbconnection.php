<?php
    header('Access-Control-Allow-Origin:*');
    header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: *');

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
      die('OK');
    }

    //$host = 'mysql.cs.virginia.edu:3306';
    $host = 'mysql.cs.virginia.edu:3306';
    $username = 'wl6bd';
    $password = '94FCP8up' ;
    $dbname = 'wl6bd_spring2019dbpj';
    $conn = mysqli_connect($host, $username, $password, $dbname);
    if (!$conn) {
        http_response_code(404);
        die("Connect Error: " . mysqli_connect_error());
    }
?>