<?php
require_once 'validate.php';
require_once 'auth_functions.php';

$errors = [];
// ... rest of code
$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validateRegistration($_POST);
    
    if (empty($errors)) {
        $conn = getDbConnection();
        $username = $_POST['username'];
        $email = $_POST['email'];
        
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $errors[] = "Username or email already exists!";
        } else {
            // Insert new user
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'user')");
            $stmt->bind_param("sss", $username, $email, $password);
            
            if ($stmt->execute()) {
                $success = "Registration successful! <a href='login.php'>Login here</a>";
            } else {
                logError("Database Insert Error: " . $stmt->error);
                $errors[] = "System error during registration. Please try again later.";
            }
        }
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .error-box { background: #ffebee; color: #c62828; padding: 10px; border-radius: 5px; margin-bottom: 15px;}
        .success-box { background: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 5px; margin-bottom: 15px;}
    </style>
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <?php foreach ($errors as $error): ?>
                    <div>• <?php echo $error; ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-box"><?php echo $success; ?></div>
        <?php else: ?>
            <form method="POST">
                Username: <input type="text" name="username">
                Email: <input type="email" name="email">
                Password: <input type="password" name="password">
                <button type="submit">Register</button>
            </form>
        <?php endif; ?>
        
        <p style="text-align: center; margin-top: 20px;"><a href="login.php">Already have an account? Login</a></p>
    </div>
</body>
</html>