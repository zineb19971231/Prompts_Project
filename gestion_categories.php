<?php
session_start();
require("db_prompt.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: auth/login.php');
    exit();
}

if(isset($_GET['id'])){
    $id=$_GET['id'];
    $stmt=$pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: gestion_categories.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
        header('Location: gestion_categories.php');
        exit();
    }
}

$query = "
    SELECT c.id, c.name, COUNT(p.id) as prompt_count 
    FROM categories c 
    LEFT JOIN prompts p ON c.id = p.categorie_id 
    GROUP BY c.id, c.name
";
$categories = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/gestion_categories.css">
    <title>Gestion Catégories</title>
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
        <a href="admin_dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
        <a href="categories.php" class="active"><i class="fa-solid fa-tags"></i> Categories</a>
        <a href="contributor.php"><i class="fa-solid fa-users-gear"></i> Contributors</a>
    </div>

    <div class="logout-area">
        <a href="auth/logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<div class="main">
    <div class="header-flex">
        <h2>Category Management</h2>
        <button onclick="showAddModal()" class="btn-add"><i class="fa-solid fa-plus"></i> New Category</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>Category Name</th>
                <th>Prompt Count</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
            <tr>
                <td><strong><?= htmlspecialchars($category['name']) ?></strong></td>
                <td><span class="badge-count"><?= $category['prompt_count'] ?> prompts</span></td>
                <td>
                    <a href="edit_category.php?id=<?= $category['id'] ?>" style="color: var(--primary); margin-right: 15px;"><i class="fa-solid fa-pen-to-square"></i></a>
                    <a href="gestion_categories.php?id=<?= $category['id'] ?>" style="color: #ef4444;" onclick="return confirm('are you sure you want to delete this category?');"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="NewcatModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Category</h3>
            <span class="close-btn" onclick="closeModal()">&times;</span>
        </div>
        <form action="" method="POST">
            <div class="modal-body">
                <label for="categoryName" style="color: #475569; font-weight: 500;">Category Name</label>
                <input type="text" id="categoryName" name="name" placeholder="e.g. Technology, Art..." required>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn-submit">Save Category</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showAddModal() {
        document.getElementById('NewcatModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('NewcatModal').style.display = 'none';
    }

    window.onclick = function(event) {
        let modal = document.getElementById('NewcatModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>

</body>
</html>