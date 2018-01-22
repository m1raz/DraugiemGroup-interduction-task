To launch script You will need to install 
  + Apache 2.4.25 [web server]
  + MariaDB 10.1.21 [Database]
  + PHP 5.6.30 (VC11 X86 32bit thread safe) + PEAR [server side programming language PHP interpreter]
  + phpMyAdmin 4.6.5.2 [Database Management tool]


For localhost best solution would be Windows Desktop Oriented solution, like Windows 10 Pro.
Current script were tested on Windows 10 Pro. Without any problem.
To get it you will need to install XAMPP via installation you can install all components, they offer or choose only what you need.

XAMPP v 5.6.30 can be found here: 
  + https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/5.6.30/

After installing XAMPP, go to directory, where XAMPP is installed, and change an php.ini file to php.ini from this repo "install" folder. This will config your PHP for Production mode.

Now you must enable server. Open xampp-control.exe and click and run Apache (server) and MySql(database).

Drop all files and folders from repo, except "install" folder, Files .gitignore, LICENSE and README.md to folder htdocs in your xampp directory

To access script. You will need an browser which support CSS3. Script were tested successfully in Opera 50.0.
The script is not created to work with another browser, so use them on your own risk.

Now open your browser and inside address bar type "localhost/phpmyadmin".

Click New on left side to create database and name it "draugiem_test". In Collation choose "utf8_general_ci".
Then click to your just created database on left side and then click "Import" on the TOP.
Import file "database.sql" from install folder in repo. Don't change any options and press Go.

Now type in address bar "localhost" 
If everything is done you should see an an index.php page which is start page for script.

UI contains two pages:
+index.php
+test.php
Serverside scripts are stored inside "json" and "config" folders


##How to add new TEST

Open your browser and inside address bar type "localhost/phpmyadmin".

Step 1:
Go to "draugiem_test" database "test" table.
Click INSERT on the top.
field id: do not change
field title: type title of your test
Click Browse on the top and find your TEST id.

Step 2:
Go to "draugiem_test" database "questions" table.
Click INSERT on the top.
field id: do not change
field test_id: put your test id here
field question: type your question
Click Browse on the top and find your question id.

Step 3:
Go to "draugiem_test" database "answer" table.
Click INSERT on the top.
field id: do not change
field question_id: put your question id here
field answer: type answer
field correct: type "1" if this is correct answer, else type "0"
Repeat until you entered at least 2 answers

Repeat step 2 and step 3 until there is enough questions. 