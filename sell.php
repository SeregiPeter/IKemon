<?php
session_start();
require_once("Storage.php");
$users_storage = new Storage(new JsonIO("users.json"));
$cards_storage = new Storage(new JsonIO("cards.json"));
$card = $cards_storage->findOne(["id" => $_GET["cardid"]]);
$user = $users_storage->findById($_SESSION["userid"]);

$is_admin = $user["admin"];
$sold = false;
if(!$is_admin) {
    $user["money"] = $user["money"] + $card["price"] * 0.9;
    $card["owner"] = "admin";
    $users_storage->update($user["id"], $user);
    $cards_storage->update($card["id"], $card);
    $sold = true;
    $_SESSION["money"] += $card["price"] * 0.9;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $sold ? "Successful sell" : "Failed sell" ?></title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <link rel="stylesheet" href="styles/details.css">
</head>
<body>
    <header><?php include_once("navbar.php"); ?></header>
    <h2><?= $sold ? ("Congratulations! You sold " . $card["name"] . " for " . ($card["price"] * 0.9) . ".") : "You can't sell as an admin!" ?></h2>
</body>
</html>