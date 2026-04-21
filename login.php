<?php
require_once 'validate.php';
require_once 'auth_functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validateLogin($_POST);
    
    if (empty($errors)) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $conn = getDbConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password_hash'])) {
                // Login success!
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                header('Location: index.php'); 
                exit();
            } else {
                $errors[] = "Incorrect password!";
            }
        } else {
            $errors[] = "User not found!";
        }
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .error-box { background: #ffebee; color: #c62828; padding: 10px; border-radius: 5px; margin-bottom: 15px;}
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <?php foreach ($errors as $error): ?>
                    <div>• <?php echo $error; ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            Username: <input type="text" name="username">
            Password: <input type="password" name="password">
            <button type="submit">Login</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px;"><a href="register.php">No account? Register</a></p>
    </div>
</body>
</html>