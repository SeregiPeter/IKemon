<?php
session_start();
require_once("Storage.php");
$users_storage = new Storage(new JsonIO("users.json"));
$users = $users_storage->findAll();
$cards_storage = new Storage(new JsonIO("cards.json"));
$cards = $cards_storage->findAll();
$card_id = "";
if (isset($_GET["cardid"])) {
    $card_id = $_GET["cardid"];
}
$card = $cards_storage->findById($card_id);

$background_colors = ["fire" => "#e96933", "electric" => "#f5f583", "grass" => "#87cf87", "water" => "#6188a1", "bug" => "#c19748", "normal" => "#c5c5a1", "poison" => "#844784", "ice" => "#7ce1e1", "fighting" => "#db93db", "ground" => "#776043", "psychic" => "#c04f8b", "rock" => "#9e4646", "ghost" => "#57576d", "dark" => "#6b6a6a", "steel" => "#c5c5c5"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $card["name"] ?> details
    </title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <link rel="stylesheet" href="styles/details.css">
</head>

<body style="background-color: <?= $background_colors[$card["type"]] ?>;">
    <header>
        <?php include_once("navbar.php"); ?>
    </header>
    <div id="content">
        <div class="pokemon-card">
            <div class="image clr-<?= $card["type"] ?>">
                <img src="<?= $card["image"] ?>">
            </div>
            <div class="details">
                <h2><a href="details.php?cardid=<?= $card["id"] ?>">
                        <?= $card["name"] ?>
                    </a></h2>
                <span class="card-type"><span class="icon">🏷</span>
                    <?= $card["type"] ?>
                </span>
                <span class="attributes">
                    <span class="card-hp"><span class="icon">❤</span>
                        <?= $card["hp"] ?>
                    </span>
                    <span class="card-attack"><span class="icon">⚔</span>
                        <?= $card["attack"] ?>
                    </span>
                    <span class="card-defense"><span class="icon">🛡</span>
                        <?= $card["defense"] ?>
                    </span>
                </span>
            </div>
            <div class="buy">
                <span class="card-price"><span class="icon">💰</span>
                    <?= $card["price"] ?>
                </span>
            </div>
            <div class="description">
                <span>
                    <?= $card["description"] ?>
                </span>
            </div>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>