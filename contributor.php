
<?php
session_start();
require("db_prompt.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: auth/login.php');
    exit();
}

$users = $pdo->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/admin_dashboard.css">
    <title>Admin Dashboard</title>
    
</head>
<body>
    <div class="sidebar">
    <div class="sidebar-header">
        <img src="" alt="" class="profile-img">
        <div class="profile-info">
            <strong><?= htmlspecialchars($_SESSION['user_name']); ?></strong>
            <small><?= htmlspecialchars($_SESSION['user_email']); ?></small>
        </div>
    </div>

    <div class="nav-links">
        <a href="admin_dashboard.php" ><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
        <a href="gestion_categories.php"><i class="fa-solid fa-tags"></i> Categories</a>
        <a href="contributor.php" class="active"><i class="fa-solid fa-users-gear"></i> Contributors</a>
    </div>

    <div class="logout-area">
        <a href="auth/logout.php" class="logout-btn">Logout</a>
    </div>
</div>
<div class="main">
 <h2>Users List</h2>
    <table>
        <thead>
            <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td>#<?= $user['id']; ?></td>
                <td><?= htmlspecialchars($user['name']); ?></td>
                <td><?= htmlspecialchars($user['email']); ?></td>
                <td><?= htmlspecialchars($user['role']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</body>
 