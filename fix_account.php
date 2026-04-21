<?php
require_once 'auth_functions.php';
$conn = getDbConnection();

$plain_password = "12345";
$perfect_hash = password_hash($plain_password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password_hash = ?");
$stmt->bind_param("s", $perfect_hash);

if ($stmt->execute()) {
    echo "<h2>Success! All passwords reset to 12345.</h2>";
    echo "<a href='login.php'>Go to Login</a>";
}
?>