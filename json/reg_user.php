<?php
    session_start();
    header("Content-Type: application/json; charset=UTF-8");
    require '../config/db.php';
    $obj = json_decode($_POST["u"], false); //decode json string to object to get access to value by index
    
    $json['err'] = "none";
    
    //register new peaople for this test; Multiple peaple with identic name is possible;
    $name = $mysqli->real_escape_string($obj->user);
    if($name === ""){
        $json['err'] = 0;
        exit(json_encode($json)); //send json with prepeared HTML. JSON_UNESCAPED_SLASHES prevents artifacts with backslashes
    
    }
    $sql = "INSERT INTO `people` (`id`, `name`) VALUES (NULL, '$name')"; 
    if(!$mysqli->query($sql)){
        $json['err'] = 3;
        exit(json_encode($json)); //send json with prepeared HTML. JSON_UNESCAPED_SLASHES prevents artifacts with backslashes
    }else{
        $_SESSION["USER_ID"] = $mysqli->insert_id;
        $_SESSION["name"] = $name;
        $json['err'] = "none";
    }
    
    exit(json_encode($json)); //send json with prepeared HTML. JSON_UNESCAPED_SLASHES prevents artifacts with backslashes
