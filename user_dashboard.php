<?php
session_start();
require("db_prompt.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'developper') {
    header('Location: auth/login.php');
    exit();
}

// Logique de suppression
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']))
{
    $id = $_POST['id'];
    $stmt = $pdo ->prepare("DELETE FROM prompts where id = ?");
    $stmt -> execute([$id]);
    header('Location: user_dashboard.php');
    exit();
}

// Stats
$countPrompts = $pdo->query("SELECT COUNT(*) FROM prompts")->fetchColumn();
$countCategories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();

// Data
$sql = 'SELECT p.*, u.name as user_name, c.name as cat_name FROM prompts p 
        JOIN users u ON p.user_id = u.id JOIN categories c ON p.categorie_id = c.id';
$prompts = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/user_dashboard.css">
    <title>DevGenius Dashboard</title>
</head>
<body class="dg-body">

    <aside class="dg-sidebar">
        <div class="dg-logo">DevGenius</div>
        <nav class="dg-nav">
            <a href="#" class="dg-nav-item active"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="#" class="dg-nav-item"><i class="fa-solid fa-terminal"></i> Prompts</a>
            <a href="#" class="dg-nav-item"><i class="fa-solid fa-sliders"></i> Settings</a>
        </nav>
        <div class="dg-logout-box">
            <a href="auth/logout.php" class="dg-btn-logout"><i class="fa-solid fa-power-off"></i> Logout</a>
        </div>
    </aside>

    <main class="dg-wrapper">
        <header class="dg-header">
            <div class="dg-welcome">
                <h1>Tableau de bord</h1>
                <p>Bienvenue, <strong><?= htmlspecialchars($_SESSION['user_name']); ?></strong> !</p>
            </div>
        </header>

        <div class="dg-stats-grid">
            <div class="dg-card-stat">
                <div class="dg-stat-icon"><i class="fa-solid fa-microchip"></i></div>
                <div class="dg-stat-data">
                    <p>Total Prompts</p>
                    <h3><?= $countPrompts; ?></h3>
                </div>
            </div>
            <div class="dg-card-stat">
                <div class="dg-stat-icon"><i class="fa-solid fa-folder-tree"></i></div>
                <div class="dg-stat-data">
                    <p>Catégories</p>
                    <h3><?= $countCategories; ?></h3>
                </div>
            </div>
            <div class="dg-card-stat dg-featured">
                <div class="dg-stat-data">
                    <h3><a href="NewPrompt.php" style="color:white; text-decoration:none;">+ New Prompt</a></h3>
                </div>
            </div>
        </div>

        <div class="dg-table-tools">
            <div class="dg-search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Rechercher un prompt...">
            </div>
        </div>

        <section class="dg-table-container">
            <table class="dg-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prompts as $p): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($p['title']); ?></strong></td>
                        <td><span class="dg-tag"><?= htmlspecialchars($p['cat_name']); ?></span></td>
                        <td class="dg-actions">
                            <a href="prompt_details.php?id=<?= $p['id']; ?>" class="dg-view-details">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="edit.php?id=<?= $p['id']; ?>" class="dg-edit"><i class="fa-solid fa-pen"></i></a>
                            <form action="" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                <input type="hidden" name="id" value="<?= $p['id']; ?>">
                                <button type="submit" class="dg-delete-btn">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>