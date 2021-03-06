Tasks!
======
Version 1.0 ALPHA
--------------------------
Tasks! is a simple browser based LAMP application to help you keep track of your daily tasks. My aim is to create simple, easy to use interface to help you manage your everyday tasks. It's aimed to be a web developer's companion, but it can be used by anyone for anything that involves keeping track of daily occurrences as well.

It consists of a login screen - once logged in you have a minimalist input to start entering your tasks. Once you enter a couple of them, you can check them off with a strike through to mark them as complete, and you can also switch their order in the list with a simple drag & drop. It uses ajax to save all of the changes to the database, so every item you strike through, every item you re-order or every item you delete gets saved immediately. 

Try the demo at http://dani3l.com/tasks/tasks.php
============================================
Tasks! is currently under development and there is still many features that need to be implemented. Tasks! is built on top of Twitter Bootstrap and uses jQuery UI and jQuery which are included on the project. I would like to give credit to Mark Otto (@mdo) and Jacob Thornton (@fat) for releasing such a great tool to make development go faster.

Future Versions Will include:
=======================
Other than minor fixes to the way Tasks! work right now, I plan on taking this project in a very interesting direction. I want to make Tasks! into a tool for web developers to keep track of their work. Each time you start a new project, you add it to the projects list. The projects list takes notes, domain name, DNS records, client details, contact numbers, and even makes sure the right analytics code is set up on the website once it goes live. If you have any suggestions, please let me know. 

There's a million apps that do this
----------------------------------------------
True, but the main reason for building this app is to make myself more confortable working with PHP. I'm building it from the ground up without any PHP framework. I am not using unit testing as of yet.

Features:
-------------
- Object oriented
- Uses PDO so you can choose your favorite RDBMS that supports it.
- Careful consideration has been taking into account to avoid SQL injection, such as prepared statements.

Setting up:
---------------
- Clone project to your favorite LAMP setup.
- Set your DB config under 'inc/connection.inc.php'
- Make sure magic_quotes are disabled.
- Create the following tables: <br>
CREATE TABLE users ( <br>
       user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, <br>
       username VARCHAR(255) NOT NULL, <br>
       password VARCHAR(255) NOT NULL<br>
);<br>
CREATE TABLE tasks ( <br>
       task_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, <br>
       user_id INT NOT NULL, task VARCHAR(255) NOT NULL, <br>
       enabled TINYINT(11) NOT NULL DEFAULT 1, order INT NOT NULL<br>
);

Enjoy!
