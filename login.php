<?php
session_start(); // Start the session at the beginning

// Load users from JSON
function loadUsers()
{
    return json_decode(file_get_contents('data/users.json'), true) ?? [];
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $users = loadUsers();

    foreach ($users as $user) {
        if ($user['username'] === $username && ('admin' === $username ? 'admin' === $password : password_verify($password, $user['password']))) {
            // Successful login
            $_SESSION['username'] = $username; // Set the username session variable
            $_SESSION['isAdmin'] = $user['isAdmin'] ?? false; // Set the isAdmin session variable
            $_SESSION['balance'] = $user['balance'] ?? 0; // Set the balance session variable
            header("Location: index.php"); // Redirect to main page
            exit;
        }
    }

    $error = "Invalid username or password.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon Trading Platform - Login</title>
    <link rel="stylesheet" href="styles/signup.css">
    <script src="scripts/form.js"></script>
</head>

<body>
    <div class="overlay">
        <div class="form-container">
            <form id="loginForm" method="post" action="login.php">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" title="Enter your username" placeholder="Username"><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" title="Enter your password" placeholder="Password"><br>
                <div class="other-buttons">
                    <input type="button" value="Clear" onclick="clearForm('loginForm')">
                    <input type="button" value="Cancel" onclick="window.location.href='index.php'">
                </div>
                <input type="submit" value="Login">
                <?php if (!empty($error)) : ?>
                    <p style="color:red;"><?= $error ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>

</html>