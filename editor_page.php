<?php
require_once 'auth_functions.php';
if (!hasAnyRole(['admin', 'editor'])) { die("Unauthorized"); }
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="style.css"></head>
<body>
    <div class="sidebar">
        <h2>Content Studio</h2>
        <a href="index.php">Dashboard</a>
        <a href="#" class="active">All Posts</a>
        <a href="#">Categories</a>
        <a href="logout.php" style="margin-top:auto">Logout</a>
    </div>
    <div class="main-content">
        <div class="top-bar">
            <span>Editor Workspace</span>
            <button class="btn btn-primary">+ Create New Post</button>
        </div>
        <div class="content-body">
            <div class="card">
                <h3>Recent Drafts</h3>
                <p style="color:#718096">Manage your articles and media here.</p>
                <table>
                    <tr>
                        <td><strong>How to use XAMPP</strong></td>
                        <td><span class="role-badge user-bg">Published</span></td>
                        <td>Apr 19, 2026</td>
                        <td><a href="#">Edit</a></td>
                    </tr>
                    <tr>
                        <td><strong>PHP Security 101</strong></td>
                        <td><span class="role-badge editor-bg">Draft</span></td>
                        <td>Apr 18, 2026</td>
                        <td><a href="#">Edit</a></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>