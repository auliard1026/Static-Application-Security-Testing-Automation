<?php
$host = "localhost";
$dbname = "user_auth";
$username = "root";  
$password = "";      

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
