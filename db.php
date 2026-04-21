<?php
$conn = new mysqli("localhost", "root", "", "junaid-db-wk7");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>