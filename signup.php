<?php

session_start();

function isPasswordStrong($password)
{
    // Check for minimum length, numeric, uppercase, lowercase and special character
    return preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*\W).{8,}$/', $password);
}

function loadUsers($filename)
{
    if (!file_exists($filename)) return [];
    $json = file_get_contents($filename);
    return json_decode($json, true) ?? [];
}

function saveUsers($users, $filename)
{
    $json = json_encode($users, JSON_PRETTY_PRINT);
    file_put_contents($filename, $json);
}

$error = '';
$username = $email = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if (!$username || !$email || !$password || !$confirmPassword) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (!isPasswordStrong($password)) {
        $error = 'Password should be at least 8 characters long and include a number, an uppercase letter, a lowercase letter, and a symbol.';
    } else {
        $users = loadUsers('data/users.json');
        foreach ($users as $user) {
            if ($user['username'] === $username || $user['email'] === $email) {
                $error = 'User already exists!';
                break;
            }
        }
    }
    if (!preg_match("/^[a-zA-Z]*$/", $username)) $error = 'Only letters allowed in username.';

    if (empty($error)) {
        $newUser = [
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'isAdmin' => $username === 'admin',
            'balance' => 2000, // Default balance is 2000
            'cards' => []
        ];

        $users[] = $newUser;
        file_put_contents('data/users.json', json_encode($users, JSON_PRETTY_PRINT));

        $_SESSION['username'] = $username;
        $_SESSION['isAdmin'] = false;
        $_SESSION['balance'] = $newUser['balance'];
        $_SESSION['cards'] = $newUser['cards'];
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon Trading Platform</title>
    <link rel="stylesheet" href="styles/signup.css">
    <script src="scripts/form.js"></script>
</head>

<body>
    <!-- <?php if (!empty($error)) : ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?> -->

    <div class="overlay">
        <div class="form-container">
            <form id="signupForm" method="post" action="signup.php">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" title="Enter your username" placeholder="Username" value="<?= htmlspecialchars($username) ?>"><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" title="Enter your email" placeholder="Email" value="<?= htmlspecialchars($email) ?>"><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" title="Enter your password" placeholder="Password"><br>
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" title="Confirm your password" placeholder="Confirm Password"><br>
                <?php if (!empty($error)) : ?>
                    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
                <div class="other-buttons">
                    <input type="button" value="Clear" onclick="clearForm('signupForm')">
                    <input type="button" value="Cancel" onclick="window.location.href='index.php'">
                </div>
                <input type="submit" value="Register">
            </form>
        </div>
    </div>
</body>

</html>