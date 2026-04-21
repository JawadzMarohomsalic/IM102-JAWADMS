<?php
// validate.php

function validateRegistration($data) {
    $errors = [];
    
    // Check username
    if (empty($data['username'])) {
        $errors[] = "Username is required";
    } elseif (strlen($data['username']) < 3) {
        $errors[] = "Username must be at least 3 characters";
    } elseif (strlen($data['username']) > 20) {
        $errors[] = "Username must be less than 20 characters";
    }
    
    // Check email
    if (empty($data['email'])) {
        $errors[] = "Email is required";
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email is not valid";
    }
    
    // Check password
    if (empty($data['password'])) {
        $errors[] = "Password is required";
    } elseif (strlen($data['password']) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    return $errors;
}

function validateLogin($data) {
    $errors = [];
    
    if (empty($data['username'])) {
        $errors[] = "Username is required";
    }
    if (empty($data['password'])) {
        $errors[] = "Password is required";
    }
    
    return $errors;
}

// Error Logging Function (Task 3)
function logError($message) {
    $date = date('Y-m-d H:i:s');
    $logMessage = "[$date] $message\n";
    @file_put_contents('error.log', $logMessage, FILE_APPEND);
}
?>