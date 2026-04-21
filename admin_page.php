<?php
require_once 'auth_functions.php';
requireRole('admin');
$conn = getDbConnection();

// 1. Handle Role Updates
if (isset($_POST['update_role'])) {
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $_POST['role'], $_POST['user_id']);
    $stmt->execute();
}

// 2. Fetch Users (Secure: only ID, Username, and Role)
$users = $conn->query("SELECT id, username, role FROM users");

if (!$users) {
    die("Database Error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Extra style to make the back button very noticeable */
        .back-btn-container {
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }
        .btn-back {
            background-color: #1a73e8; /* Matches your header color */
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: background 0.3s;
            box-shadow: 0 4px 6px rgba(26, 115, 232, 0.2);
        }
        .btn-back:hover {
            background-color: #1557b0;
            box-shadow: 0 6px 12px rgba(26, 115, 232, 0.3);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Pro</h2>
        <a href="index.php">Dashboard</a> 
        <a href="admin_page.php" class="active">User Management</a>
        <a href="logout.php" style="margin-top:auto">Logout</a>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <span>System Master Control</span>
            <strong>Admin: <?php echo htmlspecialchars($_SESSION['username']); ?></strong>
        </div>

        <div class="content-body">
            
            <div class="back-btn-container">
                <a href="index.php" class="btn-back">
                    <span style="font-size: 20px;">&larr;</span> Back to Dashboard
                </a>
            </div>

            <div class="card">
                <h3>User Management</h3>
                <p style="color: #666; font-size: 0.9rem; margin-bottom: 20px;">Manage user permissions and system roles.</p>
                
                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Security Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $users->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                            <td>
                                <span class="role-badge <?php echo $row['role']; ?>-bg">
                                    <?php echo strtoupper($row['role']); ?>
                                </span>
                            </td>
                            <td>
                                <span style="color: #2e7d32; font-size: 0.85rem; font-weight: 600;">
                                    ● Encrypted
                                </span>
                            </td>
                            <td>
                                <form method="POST" style="display:inline-flex; gap:10px; align-items: center;">
                                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                    <select name="role" style="margin: 0; padding: 5px;">
                                        <option value="user" <?php if($row['role']=='user') echo 'selected'; ?>>User</option>
                                        <option value="editor" <?php if($row['role']=='editor') echo 'selected'; ?>>Editor</option>
                                        <option value="admin" <?php if($row['role']=='admin') echo 'selected'; ?>>Admin</option>
                                    </select>
                                    <button name="update_role" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">Save</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>