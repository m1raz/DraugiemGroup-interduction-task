function info( numb ){
    if( numb === 0)
        document.getElementById("info").innerHTML = "<div class='alert alert-danger'>Nav ievadīts vārds, vai izvēlēts tests.</div>";
    if( numb === 1)
        document.getElementById("info").innerHTML = "<div class='alert alert-danger'><strong>Kļūda.</strong> <br>Sazinieties ar administrātoru!</div>";
    if( numb === 2)
        document.getElementById("info").innerHTML = "<div class='alert alert-danger'><strong>Kļūda.</strong> <br>Liekas, ka centies šmaukties, vai arī radās kļūdas, kas liek man tā domāt. Jebkurā gadijumā, šī atbilde nav ieskaitīta.</div>";
    if( numb === 3)
        document.getElementById("info").innerHTML = "<div class='alert alert-danger'><strong>Kļūda.</strong> <br>Neizdevās piereģistrēt tevi testam. Pamēģini vēlreiz vai sazinies ar administratoru.</div>";
         
}
function search(){
    var search_for = document.getElementById("search_field").value;
    obj = { "search_for": search_for };
    req = JSON.stringify(obj);
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            return_vals = JSON.parse(this.responseText);
            document.getElementById("test-list").innerHTML = return_vals.data;
        }
    };
    xmlhttp.open("POST", "json/search_test.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("search=" + req);
}



function submit_forms(){
    var name = document.getElementById("name").value;
    
    /*register user*/
    var search_for = document.getElementById("search_field").value;
    obj = { "user": name };
    req = JSON.stringify(obj);
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            return_vals = JSON.parse(this.responseText);
            if(return_vals.err != "none"){
                info( return_vals.err );
            }
        }
    };
    xmlhttp.open("POST", "json/reg_user.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("u=" + req);
    
    var success = document.getElementById("info").innerHTML;
    if(success != "") return;
    
    
    var e = document.getElementById("select-test");
    var value = -1;

    try{
        value = e.options[e.selectedIndex].value; 
    }catch( err ){
        value = -1;
    }

    if(value != -1){
        document.getElementById("user-name").value = name;
        document.getElementById("user-test_id").value = value;
        document.getElementById("accept").submit();
    }else{
        info(0);
    }
    
}
function answ( answ_selected, test_id ){
    obj = { "answer": answ_selected, "test": test_id };
    req = JSON.stringify(obj);
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            return_vals = JSON.parse(this.responseText);
            if(return_vals.err == "none"){
                document.getElementById("question").innerHTML = return_vals.question;
                document.getElementById("answers").innerHTML = return_vals.answers;
                document.getElementById("progress_bar").innerHTML = return_vals.progress_bar;
            }else{
                info(return_vals.err);
            }
        }
    };
    xmlhttp.open("POST", "json/answ.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("r=" + req);
}