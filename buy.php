<?php
session_start();
require_once("Storage.php");
$users_storage = new Storage(new JsonIO("users.json"));
$cards_storage = new Storage(new JsonIO("cards.json"));
$card = $cards_storage->findOne(["id" => $_GET["cardid"]]);
$user = $users_storage->findById($_SESSION["userid"]);
$has_money = $user["money"] >= $card["price"];
$not_admin = !$user["admin"];
$already_owned = $card["owner"] != "admin";
$sold = false;
$error_message = "";
$success_message = "";
if($has_money && $not_admin && !$already_owned) {
    $card["owner"] = $user["id"];
    $user["money"] = $user["money"] - $card["price"];
    $users_storage->update($user["id"], $user);
    $cards_storage->update($card["id"], $card);
    $sold = true;
    $success_message = "Congratulations! You purchased " . $card["name"] . "!";
    $_SESSION["money"] -= $card["price"];
} else if($user["admin"]) {
    $error_message = "You can't buy cards as an admin.";
} else if($already_owned) {
    $error_message = "You can't buy " . $card["name"] . " because another user already owns it.";
} else if(!$has_money) {
    $error_message = "You don't have enough money to buy " . $card["name"] . ".";
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $sold ? "Successful purchase" : "Failed purchase" ?></title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <link rel="stylesheet" href="styles/details.css">
</head>
<body>
    <header><?php include_once("navbar.php"); ?></header>
    <h2><?= $sold ? $success_message : $error_message ?></h2>
</body>
</html>