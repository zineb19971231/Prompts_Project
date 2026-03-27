<?php
session_start();

require ("../db_prompt.php");
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $_POST["email"] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)){
        $stmt=$pdo->prepare('SELECT * from users where email = :email');
        $stmt->execute([":email" => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if($user){
                    if(password_verify($password,$user['password'])){

                        $_SESSION['user_id']= $user['id'];
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['user_name'] = $user['name'];
                        $_SESSION['role'] = $user['role'];
                        
                        if ($user['role'] === 'admin') {
                             header("Location: ../admin_dashboard.php");
                                    exit();}
                        else {
                            header("Location: ../user_dashboard.php");
                                        exit();
                                        }
                    }
                    else { $error = 'Invalid password';}}
                else { $error= 'user not found';}
}
    else { $error = 'Please fill in all fields';}
     }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/login.css">
    <title>Login</title>

</head>
<body>

<div class="login-box">
    <h2>Login</h2>
    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <a href="auth/register.php">Create account</a>

</div>

</body>
</html>