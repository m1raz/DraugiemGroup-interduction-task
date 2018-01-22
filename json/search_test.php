<?php
    header("Content-Type: application/json; charset=UTF-8");
    require '../config/db.php';
    $obj = json_decode($_POST["search"], false); //decode json string to object to get access to value by index
    
    //obj = { "search_for": search_for };
    
    $search_for = $mysqli->real_escape_string($obj->search_for); //Prevent MySQL injection
    
    $result = $mysqli->query("SELECT id, title FROM test WHERE title LIKE '%" . $search_for . "%'"); //find test
    
    $outp['data'] = "<select multiple class='form-control' id='select-test'>";
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()) {
            $outp['data'] .= "<option value='" . $row['id'] . "'>" . $row['title'] . "</option>";
        }
    }
    $outp['data'] .= "</select>";

    echo json_encode($outp, JSON_UNESCAPED_SLASHES); //send json with prepeared HTML. JSON_UNESCAPED_SLASHES prevents artifacts with backslashes
?>

