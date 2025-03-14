<?php
session_start();
require_once("Storage.php");
$users_storage = new Storage(new JsonIO("users.json"));
$cards_storage = new Storage(new JsonIO("cards.json"));
$cards = $cards_storage->findAll();
$user = [];
$users_cards_num = 0;
$logged_in = false;
if (isset($_SESSION["userid"])) {
    $logged_in = true;
    $user = $users_storage->findById($_SESSION["userid"]);
    $users_cards_num = count($cards_storage->findAll(["owner" => $user["id"]]));
}
if ($_GET) {
    if ($_GET["type"] != "all") {
        $cards = $cards_storage->findAll(["type" => $_GET["type"]]);
    }

}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Home</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <link rel="stylesheet" href="styles/details.css">
</head>

<body>
    <header>
        <!--<h1><a href="index.php">IKémon</a> > Home</h1> -->
        <?php include_once("navbar.php"); ?>
    </header>
    <h1 id="main_h1">IKÉMON</h1>
    <h2 id="main_h2">Trade your favourite Pokemon cards!</h2>

    <form action="index.php" method="get" id="search">
        <label for="type">Type: </label>
        <select name="type">
            <option name="all" <?= isset($_GET["type"]) && $_GET["type"] == "all" ? "selected" : "" ?>>all</option>
            <option name="electric" <?= isset($_GET["type"]) && $_GET["type"] == "electric" ? "selected" : "" ?>>electric
            </option>
            <option name="fire" <?= isset($_GET["type"]) && $_GET["type"] == "fire" ? "selected" : "" ?>>fire</option>
            <option name="water" <?= isset($_GET["type"]) && $_GET["type"] == "water" ? "selected" : "" ?>>water</option>
            <option name="bug" <?= isset($_GET["type"]) && $_GET["type"] == "bug" ? "selected" : "" ?>>bug</option>
            <option name="normal" <?= isset($_GET["type"]) && $_GET["type"] == "normal" ? "selected" : "" ?>>normal
            </option>
            <option name="poison" <?= isset($_GET["type"]) && $_GET["type"] == "poison" ? "selected" : "" ?>>poison
            </option>
            <option name="ice" <?= isset($_GET["type"]) && $_GET["type"] == "ice" ? "selected" : "" ?>>ice</option>
            <option name="fighting" <?= isset($_GET["type"]) && $_GET["type"] == "fighting" ? "selected" : "" ?>>fighting
            </option>
            <option name="ground" <?= isset($_GET["type"]) && $_GET["type"] == "ground" ? "selected" : "" ?>>ground</option>
            <option name="psychic" <?= isset($_GET["type"]) && $_GET["type"] == "psychic" ? "selected" : "" ?>>psychic</option>
            <option name="rock" <?= isset($_GET["type"]) && $_GET["type"] == "rock" ? "selected" : "" ?>>rock</option>
            <option name="ghost" <?= isset($_GET["type"]) && $_GET["type"] == "ghost" ? "selected" : "" ?>>ghost
            </option>
            <option name="dark" <?= isset($_GET["type"]) && $_GET["type"] == "dark" ? "selected" : "" ?>>dark
            </option>
            <option name="steel" <?= isset($_GET["type"]) && $_GET["type"] == "steel" ? "selected" : "" ?>>steel
            </option>
        </select>
        <button type="submit">Search</button>
    </form>
    <div id="content">
        <div id="card-list">
            <!--<div class="pokemon-card">
                <div class="image clr-electric">
                    <img src="https://assets.pokemon.com/assets/cms2/img/pokedex/full/025.png" alt="">
                </div>
                <div class="details">
                    <h2><a href="details.php?id=card0">Pikachu</a></h2>
                    <span class="card-type"><span class="icon">🏷</span> electric</span>
                    <span class="attributes">
                        <span class="card-hp"><span class="icon">❤</span> 60</span>
                        <span class="card-attack"><span class="icon">⚔</span> 20</span>
                        <span class="card-defense"><span class="icon">🛡</span> 20</span>
                    </span>
                </div>
                <div class="buy">
                    <span class="card-price"><span class="icon">💰</span> 160</span>
                </div>
            </div>
            <div class="pokemon-card">
                <div class="image clr-fire">
                    <img src="https://assets.pokemon.com/assets/cms2/img/pokedex/full/006.png" alt="">
                </div>
                <div class="details">
                    <h2><a href="details.php?id=card1">Charizard</a></h2>
                    <span class="card-type"><span class="icon">🏷</span> fire</span>
                    <span class="attributes">
                        <span class="card-hp"><span class="icon">❤</span> 78</span>
                        <span class="card-attack"><span class="icon">⚔</span> 84</span>
                        <span class="card-defense"><span class="icon">🛡</span> 78</span>
                    </span>
                </div>
                <div class="buy">
                    <span class="card-price"><span class="icon">💰</span> 534</span>
                </div>
            </div>
        </div> -->
            <?php foreach ($cards as $card): ?>
                <div class="pokemon-card">
                    <div class="image clr-<?= $card["type"] ?>">
                        <img src="<?= $card["image"] ?>">
                    </div>
                    <div class="details">
                        <h2><a href="card_details.php?cardid=<?= $card["id"] ?>">
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
                    <?php if ($logged_in && (isset($_SESSION["admin"]) && !$_SESSION["admin"]) && $card["owner"] == "admin" && $users_cards_num < 5): ?>
                        <div class="buy">
                            <a href="buy.php?cardid=<?= $card["id"] ?>"><span class="card-price"><span class="icon">💰</span>
                                    <?= $card["price"] ?>
                                </span></a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>



        </div>
    </div>
        <footer>
            <p>IKémon | ELTE IK Webprogramozás</p>
        </footer>
</body>

</html>