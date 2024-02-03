<?php
session_start();
require_once 'storage.php';

if (!isset($_POST['card_name'])) {
    header('Location: index.php');
    exit();
}

$cardName = $_POST['card_name'];

// Fetch the user data
$users = json_decode(file_get_contents('data/users.json'), true);
$currentUser = [];
foreach ($users as $i => $user) {
    if ($user['username'] === $_SESSION['username']) {
        $currentUser = &$users[$i];
        break;
    }
}

// Check if the user already has 5 cards
if (count($currentUser['cards']) >= 5) {
    $_SESSION['error'] = "You can't own more than 5 cards.";
    header('Location: index.php');
    exit();
}

// Fetch the card data
$storage = new Storage('data/cards.json');
$card = $storage->getCardByName($cardName);

// Check if the user can afford the card
if ($currentUser['balance'] < $card['price']) {
    $_SESSION['error'] = "You can't afford this card.";
    header('Location: index.php');
    exit();
}

// Add the card to the user's cards array
$currentUser['cards'][] = $cardName;

// Remove the card from the admin's cards array
foreach ($users as $i => $user) {
    if ($user['username'] === 'admin') {
        $users[$i]['cards'] = array_diff($users[$i]['cards'], [$cardName]);
        break;
    }
}

// Change the owner of the card to the current user
$card['owner'] = $currentUser['username'];
$storage->updateCard($card, $cardName);

// Deduct the card's price from the user's balance
$currentUser['balance'] -= intval($card['price']);

// Save the updated user data
file_put_contents('data/users.json', json_encode($users));

header('Location: index.php');
