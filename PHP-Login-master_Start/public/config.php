<?php
/**
* Configuration for database connection
*
*/
$host = "localhost";
$username = "root";
$password = "";
$dbname = "user_db"; 
$dsn = "mysql:host=$host;dbname=$dbname"; 
$options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            )

?>