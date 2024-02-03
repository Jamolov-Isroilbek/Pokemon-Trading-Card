<?php
session_start();
require_once 'storage.php';

if (!isset($_POST['card_name'])) {
    header('Location: user_details.php');
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

// Fetch the card data
$storage = new Storage('data/cards.json');
$card = $storage->getCardByName($cardName);

// Remove the card from the user's cards array
$currentUser['cards'] = array_diff($currentUser['cards'], [$cardName]);

// Add the card to the admin's cards array
foreach ($users as $i => $user) {
    if ($user['username'] === 'admin') {
        $users[$i]['cards'][] = $cardName;
        break;
    }
}

// Change the owner of the card to 'admin'
$card['owner'] = 'admin';
$storage->updateCard($card);

// Add 90% of the card's price to the user's balance
$currentUser['balance'] += intval($card['price'] * 0.9);

// Save the updated user data
file_put_contents('data/users.json', json_encode($users));

header('Location: user_details.php');
