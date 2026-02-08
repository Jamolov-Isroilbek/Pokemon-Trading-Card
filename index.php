<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    unset($_SESSION['isAdmin']);
    unset($_SESSION['balance']);
    header('Location: index.php');
    exit();
}

// Initialize session variables
$username = $_SESSION['username'] ?? '';
$isAdmin = $_SESSION['isAdmin'] ?? false;
$balance = $_SESSION['balance'] ?? 0;

$users = json_decode(file_get_contents('data/users.json'), true);
$adminCards = [];
foreach ($users as $user) {
    if ($user['username'] === 'admin') {
        $adminCards = $user['cards'];
        break;
    }
}

// Fetch the user data
$users = json_decode(file_get_contents('data/users.json'), true);
$currentUser = [];
foreach ($users as $user) {
    if ($user['username'] === $username) {
        $currentUser = $user;
        break;
    }
}
$currentUserCards = $currentUser['cards'] ?? [];
$currentUserBalance = $currentUser['balance'] ?? 0;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IKémon Trading Platform</title>
    <link rel="stylesheet" href="styles/index.css" />
    <link rel="stylesheet" href="styles/alert.css" />
    <script src="scripts/form.js"></script>
</head>

<body>
    <div id="alert-box" class="alert-box hidden">
        <span class="closebtn" onclick="closeAlert()">&times;</span>
        <p id="alert-message"></p>
    </div>
    <header>
        <nav>
            <a href="index.php">IKémon</a>

            <?php if ($username && !$isAdmin) : ?>
                <div class="user-info">
                    <p>Welcome, <?= $username ?>!</p>
                    <p>Your balance: €<?= intval($currentUser['balance']) ?></p>
                </div>
            <?php endif; ?>

            <div class="button-group">
                <?php if ($isAdmin) : ?>
                    <button onclick="location.href='add_card.php'">Add New Card</button>
                    <button onclick="location.href='index.php?logout=true'">Log Out</button>
                <?php elseif ($username) : ?>
                    <a href="user_details.php">User Details</a>
                    <button onclick="location.href='index.php?logout=true'">Log Out</button>
                <?php else : ?>
                    <button onclick="location.href='signup.php'">Sign Up</button>
                    <button onclick="location.href='login.php'">Log In</button>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main>

        <div class="filter-form">
            <form action="index.php" method="get">
                <select name="type">
                    <option value="">All Types</option>
                    <option value="electric">Electric</option>
                    <option value="water">Water</option>
                    <option value="fire">Fire</option>
                    <option value="grass">Grass</option>
                    <option value="normal">Normal</option>
                    <option value="fairy">Fairy</option>
                    <option value="bug">Bug</option>
                    <option value="poison">Poison</option>
                </select>
                <input type="submit" value="Filter">
            </form>
        </div>



        <div class="card-grid">
            <?php
            require_once 'storage.php';
            $storage = new Storage('data/cards.json');
            $cards = $storage->getAllCards();

            $selectedType = $_GET['type'] ?? '';
            $filteredCards = array_filter($cards, function ($card) use ($selectedType) {
                return $selectedType === '' || $card['type'] === $selectedType;
            });
            ?>


            <?php foreach ($filteredCards as $card) : ?>
                <?php if (in_array($card['name'], $adminCards)) : ?>
                    <div class="card-container">
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
                        <?php if ($username && !$isAdmin) : ?>
                            <form class="buy-card-form" action="buy_card.php" method="post">
                                <input type="hidden" name="card_name" value="<?= $card["name"]; ?>">
                                <input type="submit" class="buy-button" value="Buy">
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <a href="index.php">IKémon</a>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            console.log('DOM fully loaded and parsed');
            let forms = document.querySelectorAll('.buy-card-form');
            forms.forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    let cardName = form.querySelector('input[name="card_name"]').value;
                    // Fetch card details and currentUser details from your server or local storage
                    let card = JSON.parse('<?= json_encode($card); ?>');
                    let currentUser = JSON.parse('<?= json_encode($currentUser); ?>');
                    if (currentUser.cards.length >= 5) {
                        event.preventDefault(); // Prevent the form from being submitted
                        showAlert('You already have 5 cards!');
                    } else if (currentUser.balance < card.price) {
                        event.preventDefault(); // Prevent the form from being submitted
                        showAlert('You do not have enough balance to buy this card!');
                    }
                });
            });
        });
    </script>
</body>

</html>
