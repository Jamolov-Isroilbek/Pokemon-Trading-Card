<?php
session_start();
if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
    header('Location: index.php'); // Redirect to main page if not admin
    exit();
}


$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'upload.php'; 

    // Store form inputs in session variables
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['hp'] = $_POST['hp'];
    $_SESSION['type'] = $_POST['type'];
    $_SESSION['attack'] = $_POST['attack'];
    $_SESSION['defense'] = $_POST['defense'];
    $_SESSION['price'] = $_POST['price'];
    $_SESSION['description'] = $_POST['description'];

    $name = trim(htmlspecialchars(filter_input(INPUT_POST, 'name')));
    $hp = filter_input(INPUT_POST, 'hp', FILTER_VALIDATE_INT);
    $type = trim(htmlspecialchars(filter_input(INPUT_POST, 'type')));
    $attack = filter_input(INPUT_POST, 'attack', FILTER_VALIDATE_INT);
    $defense = filter_input(INPUT_POST, 'defense', FILTER_VALIDATE_INT);
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_INT);
    $description = trim(htmlspecialchars(filter_input(INPUT_POST, 'description')));
    $imagePath = $_SESSION['image'];

    // Validation
    if (!$name) $errors[] = "Name is required.";
    if ($hp === false || $hp < 0) $errors[] = "HP must be a non-negative number.";
    if (!$type) $errors[] = "Type is required.";
    if ($attack === false || $attack < 0) $errors[] = "Attack power must be a non-negative number.";
    if ($defense === false || $defense < 0) $errors[] = "Defense must be a non-negative number.";
    if ($price === false || $price < 0) $errors[] = "Price must be a non-negative number.";

    if (!preg_match("/^[a-zA-Z ']*$/", $name)) $errors[] = "Only letters, single quote and white space allowed in name.";
    $allowed_types = ['grass', 'electric', 'normal', 'water', 'fire', 'fairy', 'bug', 'poison'];
    
    if (!in_array($type, $allowed_types)) $errors[] = "Invalid type. Allowed types are: " . implode(', ', $allowed_types);
    // Assuming the name is entered in lowercase
    $formattedName = ucfirst(strtolower($name)); // Capitalize the first letter

    if (empty($errors)) {
        // Load cards.json
        $cards = json_decode(file_get_contents('data/cards.json'), true) ?: [];
        
        // Check if card name already exists
        foreach ($cards as $card) {
            if (strtolower($card['name']) === strtolower($name)) {
                $errors[] = "Card name already exists.";
                break;
            }
        }

        if (empty($errors)) { // Only add new card if no errors
            $cards[] = [
                'name' => $formattedName,
                'hp' => $hp,
                'type' => $type,
                'attack' => $attack,
                'defense' => $defense,
                'price' => $price,
                'description' => $description,
                'image' => $imagePath,
                'owner' => 'admin',
            ];
            file_put_contents('data/cards.json', json_encode($cards, JSON_PRETTY_PRINT));
            
            // Load users.json
            $users = json_decode(file_get_contents('data/users.json'), true) ?: [];
            foreach ($users as &$user) {
                if ($user['username'] === 'admin') {
                    $user['cards'][] = $formattedName; // Add new card to admin's cards
                }
            }
            
            file_put_contents('data/users.json', json_encode($users, JSON_PRETTY_PRINT)); // Save users.json

            $success = "New card added successfully!";
            header('Location: index.php'); 
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Pokémon Card</title>
    <link rel="stylesheet" href="styles/add_card.css">
    <script src="scripts/form.js"></script>
</head>

<body>
    <div class="overlay">
        <div class="form-container">
            <?php if (!empty($errors)) : ?>
                <div class="errors">
                    <?php foreach ($errors as $error) : ?>
                        <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form id="addCardForm" action="add_card.php" method="post" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Name" value="<?= isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?>" required><br>
                <input type="number" name="hp" placeholder="HP" value="<?= isset($_SESSION['hp']) ? $_SESSION['hp'] : '' ?>" required><br>
                <input type="text" name="type" placeholder="Type" value="<?= isset($_SESSION['type']) ? $_SESSION['type'] : '' ?>" required><br>
                <input type="number" step="1" name="attack" placeholder="Attack" value="<?= isset($_SESSION['attack']) ? $_SESSION['attack'] : '' ?>" required><br>
                <input type="number" step="1" name="defense" placeholder="Defense" value="<?= isset($_SESSION['defense']) ? $_SESSION['defense'] : '' ?>" required><br>
                <input type="number" step="1" name="price" placeholder="Price" value="<?= isset($_SESSION['price']) ? $_SESSION['price'] : '' ?>" required><br>
                <textarea name="description" placeholder="Description"value="<?= isset($_SESSION['description']) ? $_SESSION['description'] : '' ?>" ></textarea><br>
                <input type="file" name="image" required><br>
                <div class="other-buttons">
                    <input type="button" value="Clear" onclick="clearForm(addCardForm)">
                    <input type="button" value="Cancel"  onclick=" window.location.href='index.php'">
                </div>
                <input type="submit" value="Add Card">
            </form>
        </div>
    </div>
</body>

</html>