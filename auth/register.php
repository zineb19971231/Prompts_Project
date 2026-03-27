<?php
require("../db_prompt.php");

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"] ?? '';
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    if (!empty($name) && !empty($email) && !empty($password)) {

        $check = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $check->execute([':email' => $email]);

        if ($check->fetch()) {
            $message = "Email already exists";
        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashedPassword
            ]);

            $message = "Account created successfully";
        }

    }
else {
        $message = "Please fill all fields";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Register</title>
<link rel="stylesheet" href="../css/register.css">
</head>
<body>

<div class="register-box">

<h2>Create Account</h2>

<?php if ($message): ?>
    <p class="message"><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>

<a href="auth/login.php">Already have an account? Login</a>

</div>

</body>
</html>