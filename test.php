<?php
    session_start();
?>

<html>
    <head>
    <?php require './config/head.php' //bibliotēkas ?>
    </head>
    <body>
        <?php
            //Prepare first question
            require './config/db.php';
            $test;
            if(isset($_POST['test_id']) && $_POST['test_id'] != ""){
                $test = $mysqli->real_escape_string($_POST['test_id']);
            }else{
                exit("<div class='alert alert-danger'><strong>Kļūda.</strong> <br>Sazinieties ar administrātoru! <a href='./'>Sākums</a></div>");
            }
        ?>
        <div id="info"></div>
        <div class="panel panel-default" style="margin:auto; width:60%">
            <div class="panel-heading">
                <div id="question"></div>
            </div>
            <div class="panel-body">
                <div id="answers" style="margin: auto; width:90%"></div>
                <div id="progress_bar" style="margin: auto; width:90%"></div>
        </div>
        </div>
        <script>
            answ(0, <?php echo $test ?>);
        </script>
    </body>
</html>