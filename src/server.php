<?php

$servername = htmlspecialchars("db");
$username = htmlspecialchars("root");
$password = htmlspecialchars("example");
$dbname = htmlspecialchars("anomalyDetection");

// Connect to MySQL // crate dbabase if not already exist
$link = new mysqli($servername, $username, $password);
if (!$link) {
  die('Could not connect: ' . mysqli_error($link));
}
// Create database
$sql = 'CREATE DATABASE ' . $dbname;

if ($link->query($sql) === TRUE) {
  //echo "Database created successfully";
  $connection = new mysqli($servername, $username, $password, $dbname);
  createUsersTable();
  createInjectionsTable();
} else {
  // if database exist connect to it
  $connection = new mysqli($servername, $username, $password, $dbname);
}


// functions for table creation
function createUsersTable()
{
  global $connection;
  $table_name = htmlspecialchars("Users");
  $sql_user =  "CREATE TABLE $table_name (
    id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    user_type VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL,
    password2 VARCHAR(50) DEFAULT '000' NOT NULL,
    code mediumint(50) NOT NULL,
    status text NOT NULL,
    last_activity VARCHAR(30) DEFAULT '0000-00-00 00:00:00' NOT NULL,
    reg_date VARCHAR(30) NOT NULL
    )";
  if ($connection->query($sql_user) === TRUE) {
  } else {
  }
  $pass = md5('admin');
  $sql = "INSERT INTO $table_name (username, email, fullname, user_type, password,code,status) 
     VALUES('admin', 'admin@admin.com','admin', 'admin','$pass',1111,'verified')";
  if (mysqli_query($connection, $sql)) {
    // echo "New record created successfully";
  } else {
    // echo "Error: " . $sql . "<br>" . mysqli_error($connection);
  }
}
function createInjectionsTable()
{
  global $connection;
  $table_name = htmlspecialchars("Detected_Attacks");
  $sql_xss =  "CREATE TABLE $table_name (
    id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    date VARCHAR(30) NOT NULL,
    time VARCHAR(50) NOT NULL,
    hostname VARCHAR(300) NOT NULL,
    path VARCHAR(500) NOT NULL,
    headers VARCHAR(1000) NOT NULL,
    query VARCHAR(500),
    http_method VARCHAR(50) NOT NULL, -- get or post
    description VARCHAR(500) NOT NULL, -- description of injection
    type VARCHAR(50) NOT NULL, -- reflected / sqli
    body VARCHAR(3000) NOT NULL,
    )";
  if ($connection->query($sql_xss) === TRUE) {
  } else {
  }
}
