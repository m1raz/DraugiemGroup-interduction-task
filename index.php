<html>
    <head>
    <?php 
        require './config/head.php' //bibliotēkas
    ?>
    </head>
    <body>
        <div id="info"></div>
        <div class="container" style="width: 60%">
            <div class="panel panel-default">
                <div class="form-group row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="name">Vārds:</label>
                            <input type="name" class="form-control" id="name">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-12">
                        <div class="input-group">
                            <input id="search_field" type="text" class="form-control sm" placeholder="Search" autocomplete="off" onchange="search()">
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <i class="glyphicon glyphicon-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>        
                <div class="form-group row">
                    <div class="col-xs-12">
                        <div id="test">
                            <div id="test-list">
                                <select multiple class='form-control' id='select-test'>
                                    <option value=-1 selected>Ievadiet daļu no testa nosaukuma lai atrast testu</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <form class="form-inline" action="test.php" method="post" id="accept">
                    <input id="user-name" type="hidden" name="name" value="" />
                    <input id="user-test_id"type="hidden" name="test_id" value="" />
                </form>
                <button type="submit" class="btn btn-default" onclick="submit_forms()">Submit</button>
            </div>
        </div>        
    </body>
    <script>
        setInterval(search, 5000);
    </script>
</html>