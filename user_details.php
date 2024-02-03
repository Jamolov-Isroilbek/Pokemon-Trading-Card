<?php
session_start();
require_once 'storage.php';

// Assuming user data is stored in 'data/users.json'
$users = json_decode(file_get_contents('data/users.json'), true);
$currentUser = [];

// Find the current user's details based on the session username
if (isset($_SESSION['username'])) {
    foreach ($users as $user) {
        if ($user['username'] === $_SESSION['username']) {
            $currentUser = $user;
            break;
        }
    }
}

// Redirect if not logged in or current user details not found
if (!isset($_SESSION['username']) || empty($currentUser)) {
    header('Location: login.php');
    exit();
}

// Fetch the user's cards using the Storage class
$storage = new Storage('data/cards.json');
$userCards = [];
foreach ($currentUser['cards'] as $cardName) {
    $card = $storage->getCardByName($cardName);
    if ($card !== null) {
        $userCards[] = $card;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="styles/user_details.css">
    <link rel="stylesheet" href="styles/index.css">
</head>

<body>
    <header>
        <a href="index.php">IKémon</a>
    </header>

    <main>
        <section class="user-info">
            <h1>Username: <?= htmlspecialchars($currentUser['username']) ?></h1>
            <p>Email: <?= htmlspecialchars($currentUser['email']) ?></p>
            <p>Balance: €<?= htmlspecialchars($currentUser['balance']) ?></p>
            <h2>Your Cards:</h2>
            <ul>
                <?php foreach ($userCards as $card) : ?>
                    <li class="card-container">
                        <div class="card">
                            <div class="card-image <?= htmlspecialchars($card['type']) ?>">
                                <a href="card_details.php?name=<?= $card["name"]; ?>">
                                    <img src="<?= $card["image"]; ?>" alt="<?= $card["name"]; ?>">
                                </a>
                            </div>

                            <div class="card-content">
                                <a href="card_details.php?card=<?= $card["name"]; ?>">
                                    <h3 class="card-name"><?= $card["name"]; ?></h3>
                                </a>
                                <span class="card-type <?= $card["type"]; ?>"><?= $card["type"]; ?></span>
                                <div class="card-stats">
                                    <span class="hp">
                                        <img src="assets/Icons/hp.png" alt="HP"> <?= $card["hp"]; ?>
                                    </span>
                                    <span class="attack">
                                        <img src="assets/Icons/attack.png" alt="Attack"> <?= $card["attack"]; ?>
                                    </span>
                                    <span class="defense">
                                        <img src="assets/Icons/defense.png" alt="Defense"> <?= $card["defense"]; ?>
                                    </span>
                                </div>
                                <div class="card-price">
                                    <img src="assets/Icons/price.png" alt="Price"> <?= $card["price"]; ?>
                                </div>
                            </div>
                        </div>
                        <form action="sell_card.php" method="post">
                            <input type="hidden" name="card_name" value="<?= htmlspecialchars($card['name']) ?>">
                            <input type="submit" value="Sell" class="sell-button">
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>


        </section>
    </main>

    <footer>
        <a href="index.php">IKémon</a>
    </footer>
</body>

</html>