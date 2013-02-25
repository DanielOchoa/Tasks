<?php

function dbConnect($connectionType = 'pdo') {
  $host = 'ENTER HOSTNAME HERE, MOST LIKELY LOCALHOST';
  $db = 'DATABASE NAME';
  $user = 'DATABASE USER';
  $pwd = 'DATABASE PASSWORD';

  if ($connectionType == 'mysqli') {
    return new mysqli($host, $user, $pwd, $db) or die('Cannot open database');
  } else {
    try {
      return new PDO("mysql:host=$host;dbname=$db", $user, $pwd);
    } catch (PDOException $e) {
      echo 'Cannot connect to database';
      exit;
    }
  }
}
