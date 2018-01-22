<?php
    $host = "localhost";
    $db = "draugiem_test";
    $login = "root";
    $pass = "";
    
    $mysqli = new mysqli($host,$login, $pass);
    $mysqli->select_db($db);
    $mysqli->set_charset("utf8");
    
    if($mysqli->connect_errno){
        die($mysqli->errno);
    }
?>
