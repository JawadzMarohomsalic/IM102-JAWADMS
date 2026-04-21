<?php
session_start();

function getDbConnection() {
    $conn = new mysqli("localhost", "root", "", "junaid-db-wk7");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function requireLogin() {
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
}

function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

function requireRole($role) {
    requireLogin();
    if (!hasRole($role)) {
        die("Access Denied! You do not have the $role role.");
    }
}

function hasAnyRole($roles) {
    return isset($_SESSION['role']) && in_array($_SESSION['role'], $roles);
}

// --- NEW FILE UPLOAD FUNCTION ---
function uploadFile($file) {
    $errors = [];
    
    // 1. Check for upload error
    if ($file['error'] !== 0) {
        $errors[] = "Upload failed! Error code: " . $file['error'];
        return ['success' => false, 'errors' => $errors];
    }
    
    // 2. Check file size (max 2MB)
    $maxSize = 2 * 1024 * 1024; 
    if ($file['size'] > $maxSize) {
        $errors[] = "File too big! Max 2MB.";
        return ['success' => false, 'errors' => $errors];
    }
    
    // 3. Check file type
    $allowed = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed)) {
        $errors[] = "Only JPG, PNG, and GIF allowed!";
        return ['success' => false, 'errors' => $errors];
    }
    
    // 4. Create uploads folder if not exists
    if (!is_dir('uploads')) {
        mkdir('uploads', 0755, true);
    }
    
    // 5. Generate safe filename using uniqid
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName = uniqid('profile_', true) . '.' . $extension;
    
    // 6. Move file from temp to uploads
    if (move_uploaded_file($file['tmp_name'], 'uploads/' . $newName)) {
        return ['success' => true, 'filename' => $newName];
    }
    
    $errors[] = "Failed to save file!";
    return ['success' => false, 'errors' => $errors];
}
?>