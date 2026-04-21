<?php
require_once 'auth_functions.php';
requireLogin();

$uploadMessage = "";
$conn = getDbConnection();

// Logic to handle the file upload when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    $result = uploadFile($_FILES['profile_pic']);
    
    if ($result['success']) {
        $filename = $result['filename'];
        $username = $_SESSION['username'];
        
        // Save filename to database
        $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE username = ?");
        $stmt->bind_param("ss", $filename, $username);
        
        if ($stmt->execute()) {
            $uploadMessage = "<p style='color:green;'>Profile picture updated!</p>";
        }
    } else {
        foreach ($result['errors'] as $error) {
            $uploadMessage .= "<p style='color:red;'>$error</p>";
        }
    }
}

// Fetch the latest profile picture from DB
$stmt = $conn->prepare("SELECT profile_picture FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$userRes = $stmt->get_result()->fetch_assoc();
$currentPic = $userRes['profile_picture'];
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-img { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #1a73e8; margin-bottom: 10px; }
        .upload-section { background: #f9f9f9; padding: 15px; border-radius: 8px; margin-top: 20px; border: 1px dashed #ccc; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div style="text-align: center;">
            <?php if ($currentPic): ?>
                <img src="uploads/<?php echo htmlspecialchars($currentPic); ?>" class="profile-img">
            <?php else: ?>
                <div class="profile-img" style="background:#ddd; display:inline-block; line-height:100px;">No Photo</div>
            <?php endif; ?>
            
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <p>Your role is: <span class="role-badge"><?php echo $_SESSION['role']; ?></span></p>
        </div>

        <div class="upload-section">
            <h3>Update Photo</h3>
            <?php echo $uploadMessage; ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="profile_pic" accept="image/*" required>
                <button type="submit">Upload</button>
            </form>
        </div>

        <hr>
        <?php if (hasAnyRole(['admin', 'editor'])): ?>
            <a href="editor_page.php">Editor Page</a><br><br>
        <?php endif; ?>

        <?php if (hasRole('admin')): ?>
            <a href="admin_page.php">Admin Control Panel</a><br><br>
        <?php endif; ?>
        
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>