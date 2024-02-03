<?php
require_once 'Storage.php';
$storage = new Storage('data/cards.json');
$cardName = $_GET['name'] ?? 'Pikachu';
$card = $storage->getCardByName($cardName);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pikachu Details - IKémon Trading Platform</title>
    <link rel="stylesheet" href="styles/details.css">
</head>

<body class="<?= strtolower($card['type']) ?>">
    <header>
        <a href="index.php">IKémon</a>
    </header>

    <main class="<?= strtolower($card['type']) ?>">
        <div class="details-container">
            <h1 class="pokemon-name"><?= $card['name'] ?></h1>
            <div class="card-details">
                <div class="card-image">
                    <img src="<?= $card['image'] ?>" alt="<?= $card['name'] ?> Card">
                </div>
                <div class="card-info">
                    <h2>HP: <?= $card['hp'] ?></h2>
                    <span>Element: <?= $card['type'] ?></span>
                    <p><?= $card['description'] ?></p>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <a href="index.php">IKémon</a>
    </footer>
</body>

</html>