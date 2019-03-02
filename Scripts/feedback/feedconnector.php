<?php
  # MySQL with PDO_MYSQL
  # $DBH = new PDO("mysql:host=$server;dbname=$employees", $@#$%, $###);//removed actual username and password for security purporses
  $host = '127.0.0.1';
 $db   = 'amains_feedback';
 $user = 'username';
 $pass = 'apassword';
 $charset = 'utf8mb4';

 $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
 $opt = [
     PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
     PDO::ATTR_EMULATE_PREPARES   => false,
 ];
 $pdo = new PDO($dsn, $user, $pass, $opt);
?>
