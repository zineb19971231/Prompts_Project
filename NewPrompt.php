<?php
session_start();
require("db_prompt.php");
  
if (!isset($_SESSION['user_id']) || $_SESSION["role"] !== "developper"){
   header("location: auth/login.php");
   exit();
}
$categories = $pdo-> query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
 $message = '';
 $title = '';
 $content = '';
 $selectedcategory = '';
 $user_id= $_SESSION['user_id'];

 if ($_SERVER['REQUEST_METHOD'] == 'POST'){
$title = $_POST['title'] ;
$content = $_POST['content'] ;
$selectedcategory = $_POST['selectedcategory'];

$stmt = $pdo ->prepare('INSERT INTO prompts (title , content , user_id , categorie_id , created_at ) VALUES (?,?,?,? ,NOW())');
$stmt -> execute ([$title,$content,$user_id,$selectedcategory]);
$message = "Prompt created successfully!";
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link rel="stylesheet" href="css/newprompt.css">
        <title>New Prompt</title>

</head>
<body>

<h2>New Prompt</h2> 
<a href="user_dashboard.php" class="dg-btn-back">
    <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
</a>

<?php if ($message): ?>
    <div class="alert success">
        <?= $message ?>
    </div>
<?php endif; ?>
<form action="" method="post">
<label for="title">Title</label>
<input type="text" id="title" name="title" required>
<label for="content">Content</label>
<textarea id="content" name="content" required>
</textarea>
<label for="category">Category</label>
<select id="category" name="selectedcategory" required>
    <option value="">Select a category</option>
    <?php foreach ($categories as $category): ?>
        <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
    <?php endforeach; ?>
</select>
<button type="submit">Create Prompt</button>
</form>

</body>