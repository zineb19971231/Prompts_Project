<?php
session_start();
require("db_prompt.php");

if (!isset($_SESSION['user_id']) || $_SESSION["role"] !== "developper"){
   header("location: auth/login.php");
   exit();
}

// Vérifier ID
if (!isset($_GET['id'])) {
    header("location: user_dashboard.php");
    exit();
}

$id = $_GET['id'];

// Récupérer les catégories
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer le prompt
$stmt = $pdo->prepare("SELECT * FROM prompts WHERE id = ?");
$stmt->execute([$id]);
$prompt = $stmt->fetch(PDO::FETCH_ASSOC);

// Si pas trouvé
if (!$prompt) {
    die("Prompt not found");
}

$message = '';

$title = $prompt['title'];
$content = $prompt['content'];
$selectedcategory = $prompt['categorie_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $title = $_POST['title'];
    $content = $_POST['content'];
    $selectedcategory = $_POST['selectedcategory'];

    $stmt = $pdo->prepare("UPDATE prompts SET title = ?, content = ?, categorie_id = ? WHERE id = ?");
    $stmt->execute([$title, $content, $selectedcategory, $id]);

    $message = "✅ Prompt updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Prompt</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/newprompt.css">
</head>
<body>

<h2>Edit Prompt</h2>

<a href="user_dashboard.php" class="dg-btn-back">
    <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
</a>

<?php if ($message): ?>
    <div class="alert success">
        <?= $message ?>
    </div>
<?php endif; ?>

<form method="post">

    <label for="title">Title</label>
    <input type="text" id="title" name="title" value="<?= htmlspecialchars($title) ?>" required>

    <label for="content">Content</label>
    <textarea id="content" name="content" required><?= htmlspecialchars($content) ?></textarea>

    <label for="category">Category</label>
    <select id="category" name="selectedcategory" required>
        <option value="">Select a category</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?= $category['id'] ?>"
                <?= ($selectedcategory == $category['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($category['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Update Prompt</button>

</form>

</body>
</html>