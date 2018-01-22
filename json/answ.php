<?php
    session_start();
    header("Content-Type: application/json; charset=UTF-8");
    require '../config/db.php';
    $obj = json_decode($_POST["r"], false); //decode json string to object to get access to value by index
    
    
    
    $answ = $mysqli->real_escape_string($obj->answer); //Prevent MySQL injection
    $test = $mysqli->real_escape_string($obj->test); //Prevent MySQL injection
    $uid = $mysqli->real_escape_string($_SESSION["USER_ID"]); //Prevent MySQL injection
    
    

    $json['err'] = "none";
    $json['question'] = "";
    $json['answers'] = "";
    $json['answers'] = "";
    $json['progress_bar'] = "";
    //record answer
    
    $sql = "SELECT * FROM `testing` WHERE people_id = $uid AND test_id = $test";

    if($result = $mysqli->query($sql)){
        if($result->num_rows <= 0 && $answ == 0){ //first question (display only)
            $sql = "SELECT id, question FROM question WHERE test_id = " . $test . " LIMIT 0,1"; //find first id of q in test
            $row = array();
            $result = $mysqli->query($sql);
            $row = $result->fetch_assoc();
            $json['question'] = "<h1>" . $row['question'] . "</h1>";
            $qid = $row['id'];

            //find answers for question
            $sql = "SELECT answer, id FROM answer WHERE question_id = $qid"; //find answers
            if($result = $mysqli->query($sql)){
                if($result->num_rows > 2){/// There must be at least two answers.
                    //printing all answers
                    while($row = $result->fetch_assoc()){
                        $json['answers'] .= "<button type='button' class='btn btn-primary btn-lg' onclick='answ(" . $row['id'] . ", " . $test . ")'>" . $row['answer'] . "</button>";
                    }
                }else {
                    $json['err'] = "1";
                    exit(json_encode($json));
                }
            } else {
                $json['err'] = "1";
                exit(json_encode($json));
            }
            
            //progress bar

            $sql = "SELECT * FROM `question` WHERE test_id = $test";
            $result = $mysqli->query($sql);
            $total = $result->num_rows;
            $current = 1;
            $prc = ($current / $total) * 100;
            $json['progress_bar'] = "   <div class='progress'>
                                            <div class='progress-bar' role='progressbar' aria-valuenow='$current'
                                            aria-valuemin='0' aria-valuemax='$total' style='width:$prc%'>
                                            $prc%
                                            </div>
                                        </div>";
            
        }elseif($result->num_rows > 0 && $answ == 0){ // reloaded page. Display last unanswered question. If test compleated, show result
            
            
            
            $sql = "SELECT q.id FROM testing t
                    LEFT JOIN answer a ON a.id = t.answer_id
                    LEFT JOIN question q ON q.id = a.question_id
                    WHERE t.people_id = $uid
                    ORDER BY q.id DESC
                    LIMIT 0,1"; // find last question
            
            $result = $mysqli->query($sql);
            $row = $result->fetch_assoc();
            $qid = $row['id'];

            //find question
            $sql = "SELECT question, id FROM question WHERE id > $qid LIMIT 0,1"; //q id
            $result = $mysqli->query($sql);
            
            
            if($result->num_rows === 0){//lastd one, confirmed, display result
                $json['question'] = "<h1>" . $_SESSION["name"] . ", tests pabeigts!</h1>";
                
                $sql = "SELECT * FROM `question` WHERE test_id = $test";
                $result = $mysqli->query($sql);
                $total = $result->num_rows;
                
                $sql = "SELECT * FROM testing t 
                        LEFT JOIN answer a ON t.answer_id = a.id 
                        WHERE people_id = $uid AND test_id = $test AND a.correct = 1";
                $result = $mysqli->query($sql);
                $ok = $result->num_rows;
                $json['answers'] = "<h2>$ok / $total</h2>";
                
                
                $sql = "SELECT correct, question_id FROM testing t 
                        LEFT JOIN answer a ON t.answer_id = a.id 
                        WHERE people_id = $uid AND test_id = $test";
                $result = $mysqli->query($sql);
                
                $json['progress_bar'] = "<div class='progress'>";
                $prc = (1/$total) * 100; //prc of one tile
                while($row = $result->fetch_assoc()){
                    if($row['correct'] === "1"){
                        $json['progress_bar'] .= "  <div class='progress-bar progress-bar-success' role='progressbar' style='width:$prc%'>
                                                        pareizi
                                                    </div>";
                    }else{
                        $sql = "SELECT question FROM `question` WHERE `id` = ".$row['question_id'];
                        $res = $mysqli->query($sql);
                        $q = $res->fetch_assoc();
                        
                        $sql = "SELECT answer FROM `answer` WHERE `question_id` = ".$row['question_id']." AND `correct` = 1";
                        $res = $mysqli->query($sql);
                        $correct_answer = $res->fetch_assoc();
                        $json['progress_bar'] .= "  <div class='progress-bar progress-bar-danger' role='progressbar' style='width:$prc%'>
                                                        <a href='#' data-toggle='tooltip' title='Uz jautājumu \"".$q['question']."\" pareizā atbilde ir \"".$correct_answer['answer']."\"' class='non-link-link'>nepareizi</a>
                                                    </div>";
                    }
                }
                $json['progress_bar'] .= "</div>";
                exit(json_encode($json, JSON_UNESCAPED_SLASHES));
            }
            
            
            $row = $result->fetch_assoc();
            $json['question'] .= "<h1>" . $row['question'] . "</h1>";
            $qid = $row['id'];

            
            
            
            //find answers for question
            $sql = "SELECT answer, id FROM answer WHERE question_id = $qid"; //find answers
            if($result = $mysqli->query($sql)){;
                if($result->num_rows > 2){/// There must be at least two answers.
                    //printing all answers
                    while($row = $result->fetch_assoc()){
                        $json['answers'] .= "<button type='button' class='btn btn-primary btn-lg' onclick='answ(" . $row['id'] . ", " . $test . ")'>" . $row['answer'] . "</button>";
                    }
                }else{
                    $json['err'] = "1";
                    exit(json_encode($json));
                }
            }else{
                $json['err'] = "1";
                exit(json_encode($json));
            }
            
            
            //progress bar

            $sql = "SELECT * FROM `question` WHERE test_id = $test";
            $result = $mysqli->query($sql);
            $total = $result->num_rows;
            
            $sql = "SELECT q.id FROM testing t
                    LEFT JOIN answer a ON a.id = t.answer_id
                    LEFT JOIN question q ON q.id = a.question_id
                    WHERE t.people_id = $uid
                    ORDER BY q.id DESC";
            $result = $mysqli->query($sql);
            
            $current = $result->num_rows + 1;
            $prc = ($current / $total) * 100;
            $json['progress_bar'] = "   <div class='progress'>
                                            <div class='progress-bar' role='progressbar' aria-valuenow='$current'
                                            aria-valuemin='0' aria-valuemax='$total' style='width:$prc%'>
                                            $prc%
                                            </div>
                                        </div>";
            
        }else{//any other question (insert answer, and display next)
            $sql = "SELECT question_id FROM answer WHERE id = $answ"; //current question
            $result = $mysqli->query($sql);
            $row = $result->fetch_assoc();
            $qid = $row['question_id'];

            /* register answer */
            $sql = "INSERT INTO `testing` (`id`, `test_id`, `answer_id`, `people_id`, `date`) VALUES (NULL, '$test', '$answ', '$uid', CURRENT_TIMESTAMP);";
            $mysqli->query($sql);

            $sql = "SELECT id, question FROM question WHERE test_id = $test AND id > $qid LIMIT 0,1"; //find next  question ID
            $result = $mysqli->query($sql);
            
            if($result->num_rows === 0){//lastd one, coonfirmed, display result
                $json['question'] = "<h1>" . $_SESSION["name"] . ", tests pabeigts!</h1>";
                
                $sql = "SELECT * FROM `question` WHERE test_id = $test";
                $result = $mysqli->query($sql);
                $total = $result->num_rows;
                
                $sql = "SELECT * FROM testing t 
                        LEFT JOIN answer a ON t.answer_id = a.id 
                        WHERE people_id = $uid AND test_id = $test AND a.correct = 1";
                $result = $mysqli->query($sql);
                $ok = $result->num_rows;
                $json['answers'] = "<h2>$ok / $total</h2>";
                
                
                $sql = "SELECT correct FROM testing t 
                        LEFT JOIN answer a ON t.answer_id = a.id 
                        WHERE people_id = $uid AND test_id = $test";
                $result = $mysqli->query($sql);
                
                $json['progress_bar'] = "<div class='progress'>";
                $prc = (1/$total) * 100; //prc of one tile
                while($row = $result->fetch_assoc()){
                    if($row['correct'] === "1"){
                        $json['progress_bar'] .= "  <div class='progress-bar progress-bar-success' role='progressbar' style='width:$prc%'>
                                                        pareizi
                                                    </div>";
                    }else{
                        $json['progress_bar'] .= "  <div class='progress-bar progress-bar-danger' role='progressbar' style='width:$prc%'>
                                                        <a href='#' data-toggle='tooltip' title='Hooray!'>nepareizi</a>
                                                    </div>";
                    }
                }
                $json['progress_bar'] .= "</div>";
                exit(json_encode($json, JSON_UNESCAPED_SLASHES));
            }
            
            $row = $result->fetch_assoc();
            $json['question'] = "<h1>" . $row['question'] . "</h1>";
            $qid = $row['id'];

            //find answers for question
            $sql = "SELECT answer, id FROM answer WHERE question_id = $qid"; //find answers
            if($result = $mysqli->query($sql)){
                if($result->num_rows > 2){/// There must be at least two answers.
                    //printing all answers
                    while($row = $result->fetch_assoc()){
                        $json['answers'] .= "<button type='button' class='btn btn-primary btn-lg' onclick='answ(" . $row['id'] . ", " . $test . ")'>" . $row['answer'] . "</button>";
                    }
                }else{
                    $json['err'] = "1";
                    exit(json_encode($json));
                }
            }else{
                $json['err'] = "1";
                exit(json_encode($json));
            }
            
            //progress bar
            $sql = "SELECT * FROM `question` WHERE test_id = $test";
            $result = $mysqli->query($sql);
            $total = $result->num_rows;
            
            $sql = "SELECT * FROM question WHERE test_id = $test AND id <= $qid";
            $result = $mysqli->query($sql);
            $current = $result->num_rows;
            
            $prc = ($current / $total) * 100;
            
            $json['progress_bar'] = "   <div class='progress'>
                                            <div class='progress-bar' role='progressbar' aria-valuenow='$current'
                                            aria-valuemin='0' aria-valuemax='$total' style='width:$prc%'>
                                            $prc%
                                            </div>
                                        </div>";
        }
    }    
    exit(json_encode($json, JSON_UNESCAPED_SLASHES)); //send json with prepeared HTML. JSON_UNESCAPED_SLASHES prevents artifacts with backslashes
?>