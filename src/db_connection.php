<?php

$serverName = "localhost";
$userName = ""; //usunięty username
$password = ""; //usunięty password
$baseName = "sylverster_db";

$conn = new mysqli($serverName, $userName, $password, $baseName);

if ($conn->connect_error) {
    die ("Błąd: " . $conn->connect_error);
} 

//$conn->close();
//$conn = null;
?>