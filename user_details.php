<?php
session_start();
require_once("Storage.php");
$users_storage = new Storage(new JsonIO("users.json"));
$cards_storage = new Storage(new JsonIO("cards.json"));
$cards = $cards_storage->findAll(["owner" => $_SESSION["userid"]]);
$user = [];
if (isset($_SESSION["userid"])) {
    $user = $users_storage->findById($_SESSION["userid"]);
}

$errors = [];
$valid_create = false;
if ($_POST) {
    if (!isset($_POST["name"]) || empty(trim($_POST["name"]))) {
        $errors["name"] = "Name is required!";
    }

    if (!isset($_POST["type"]) || empty(trim($_POST["type"]))) {
        $errors["type"] = "Type is required!";
    }

    if (!isset($_POST["hp"]) || empty(trim($_POST["hp"]))) {
        $errors["hp"] = "Hp is required!";
    } else if (!filter_var($_POST["hp"], FILTER_VALIDATE_INT)) {
        $errors["hp"] = "Hp has to be a number!";
    } else if ($_POST["hp"] < 1) {
        $errors["hp"] = "Hp has to be more than 0!";
    }

    if (!isset($_POST["attack"]) || empty(trim($_POST["attack"]))) {
        $errors["attack"] = "Attack is required!";
    } else if (!filter_var($_POST["attack"], FILTER_VALIDATE_INT)) {
        $errors["attack"] = "Attack has to be a number!";
    } else if ($_POST["attack"] < 1) {
        $errors["attack"] = "Attack has to be more than 0!";
    }

    if (!isset($_POST["defense"]) || empty(trim($_POST["defense"]))) {
        $errors["defense"] = "Defense is required!";
    } else if (!filter_var($_POST["defense"], FILTER_VALIDATE_INT)) {
        $errors["defense"] = "Defense has to be a number!";
    } else if ($_POST["defense"] < 1) {
        $errors["defense"] = "Defense has to be more than 0!";
    }

    if (!isset($_POST["price"]) || empty(trim($_POST["price"]))) {
        $errors["price"] = "Price is required!";
    } else if (!filter_var($_POST["price"], FILTER_VALIDATE_INT)) {
        $errors["price"] = "Price has to be a number!";
    } else if ($_POST["price"] < 1) {
        $errors["price"] = "Price has to be more than 0!";
    }

    if (!isset($_POST["description"]) || empty(trim($_POST["description"]))) {
        $errors["description"] = "Description is required!";
    }

    if (!isset($_POST["image"]) || empty(trim($_POST["image"]))) {
        $errors["image"] = "Image is required!";
    } else if (!filter_var($_POST["image"], FILTER_VALIDATE_URL)) {
        $errors["image"] = "Invalid image URL!";
    }

    $valid_create = empty($errors);
    if ($valid_create) {
        $new_card = [];
        $new_card["name"] = $_POST["name"];
        $new_card["type"] = $_POST["type"];
        $new_card["hp"] = $_POST["hp"];
        $new_card["attack"] = $_POST["attack"];
        $new_card["defense"] = $_POST["defense"];
        $new_card["price"] = $_POST["price"];
        $new_card["description"] = $_POST["description"];
        $new_card["image"] = $_POST["image"];
        $new_card["owner"] = "admin";

        $cards_storage->add($new_card);
        $cards = $cards_storage->findAll(["owner" => $_SESSION["userid"]]);
    }



}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $user["username"] ?>'s details
    </title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <link rel="stylesheet" href="styles/details.css">
</head>

<body>
    <header>
        <?php include_once("navbar.php"); ?>
    </header>

    <div id="user_info">
        <span>Username:
            <?= $user["username"] ?>
        </span><br>
        <span>Email:
            <?= $user["email"] ?>
        </span><br>
        <?php if (!$user["admin"]): ?>
            <span>Money:
                <?= $user["money"] ?>
            </span><br>
        <?php endif; ?>
        <span>Rights:
            <?= $user["admin"] ? "admin" : "normal" ?>
        </span><br>
    </div>

    <div id="content">
        <div id="card-list">
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
                    <?php $sell_price = $card["price"] * 0.9 ?>
                    <?php if (!$_SESSION["admin"]): ?>
                        <div class="sell">
                            <a href="sell.php?cardid=<?= $card["id"] ?>"><span class="card-price"><span class="icon">Sell for
                                    </span>
                                    <?= $sell_price ?>
                                </span></a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <?php if (empty($cards)): ?>
                <p>You don't have cards.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($user["admin"]): ?>

        <form action="user_details.php" method="post" novalidate>
            <h2>Create a new card</h2>
            <label for="name">Name: </label>
            <input type="text" name="name" id="name" value="<?= isset($_POST["name"]) ? $_POST["name"] : "" ?>">
            <span style="color: red;">
                <?= isset($errors["name"]) ? $errors["name"] : "" ?>
            </span><br>

            <label for="type">Type: </label>
            <select id="type" name="type">
                <option name="fire" <?= isset($_POST["type"]) && $_POST["type"] == "fire" ? "selected" : "" ?>>fire</option>
                <option name="electric" <?= isset($_POST["type"]) && $_POST["type"] == "electric" ? "selected" : "" ?>>electric
                </option>
                <option name="grass" <?= isset($_POST["type"]) && $_POST["type"] == "grass" ? "selected" : "" ?>>grass</option>
                <option name="water" <?= isset($_POST["type"]) && $_POST["type"] == "water" ? "selected" : "" ?>>water</option>
                <option name="bug" <?= isset($_POST["type"]) && $_POST["type"] == "bug" ? "selected" : "" ?>>bug</option>
                <option name="normal" <?= isset($_POST["type"]) && $_POST["type"] == "normal" ? "selected" : "" ?>>normal
                </option>
                <option name="poison" <?= isset($_POST["type"]) && $_POST["type"] == "poison" ? "selected" : "" ?>>poison
                </option>
                <option name="ice" <?= isset($_POST["type"]) && $_POST["type"] == "ice" ? "selected" : "" ?>>ice</option>
                <option name="fighting" <?= isset($_POST["type"]) && $_POST["type"] == "fighting" ? "selected" : "" ?>>fighting
                </option>
                <option name="ground" <?= isset($_POST["type"]) && $_POST["type"] == "ground" ? "selected" : "" ?>>ground
                </option>
                <option name="psychic" <?= isset($_POST["type"]) && $_POST["type"] == "psychic" ? "selected" : "" ?>>psychic
                </option>
                <option name="rock" <?= isset($_POST["type"]) && $_POST["type"] == "rock" ? "selected" : "" ?>>rock</option>
                <option name="ghost" <?= isset($_POST["type"]) && $_POST["type"] == "ghost" ? "selected" : "" ?>>ghost
                </option>
                <option name="dark" <?= isset($_POST["type"]) && $_POST["type"] == "dark" ? "selected" : "" ?>>dark
                </option>
                <option name="steel" <?= isset($_POST["type"]) && $_POST["type"] == "steel" ? "selected" : "" ?>>steel
                </option>
            </select>
            <span style="color: red;">
                <?= isset($errors["type"]) ? $errors["type"] : "" ?>
            </span><br>

            <label for="hp">Hp: </label>
            <input type="number" name="hp" id="hp" value="<?= isset($_POST["hp"]) ? $_POST["hp"] : "" ?>">
            <span style="color: red;">
                <?= isset($errors["hp"]) ? $errors["hp"] : "" ?>
            </span><br>

            <label for="attack">Attack: </label>
            <input type="number" name="attack" id="attack" value="<?= isset($_POST["attack"]) ? $_POST["attack"] : "" ?>">
            <span style="color: red;">
                <?= isset($errors["attack"]) ? $errors["attack"] : "" ?>
            </span><br>

            <label for="defense">Defence: </label>
            <input type="number" name="defense" id="defense"
                value="<?= isset($_POST["defense"]) ? $_POST["defense"] : "" ?>">
            <span style="color: red;">
                <?= isset($errors["defense"]) ? $errors["defense"] : "" ?>
            </span><br>

            <label for="price">Price: </label>
            <input type="number" name="price" id="price" value="<?= isset($_POST["price"]) ? $_POST["price"] : "" ?>">
            <span style="color: red;">
                <?= isset($errors["price"]) ? $errors["price"] : "" ?>
            </span><br>

            <label for="description">Description: </label>
            <textarea name="description"
                id="description"><?= isset($_POST["description"]) ? $_POST["description"] : "" ?></textarea>
            <span style="color: red;">
                <?= isset($errors["description"]) ? $errors["description"] : "" ?>
            </span><br>

            <label for="image">Image: </label>
            <input type="text" name="image" id="image" value="<?= isset($_POST["image"]) ? $_POST["image"] : "" ?>">
            <span style="color: red;">
                <?= isset($errors["image"]) ? $errors["image"] : "" ?>
            </span><br>

            <button type="submit">Create</button>
        </form>
    <?php endif; ?>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>