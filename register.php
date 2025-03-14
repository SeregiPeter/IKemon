<?php
    session_start();
    require_once("Storage.php");
    $users_storage = new Storage(new JsonIO("users.json"));
    $users = $users_storage->findAll();
    $usernames= array_column($users,"username");
    $errors = [];
    $valid_register = false;
    if($_POST) {
        if(!isset($_POST["username"]) || empty(trim($_POST["username"]))) {
            $errors["username"] = "Name is required!";
        } else if(in_array($_POST["username"], $usernames)) {
            $errors["username"] = "The given username is already taken!";
        }

        if(!isset($_POST["username"]) || empty(trim($_POST["username"]))) {
            $errors["email"] = "Email is required!";
        } else if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Invalid emial format!";
        }

        if(!isset($_POST["password1"]) || empty(trim($_POST["password1"]))) {
            $errors["password1"] = "Password is required!";
        } else if(!isset($_POST["password2"]) || empty(trim($_POST["password2"]))) {
            $errors["password2"] = "You need to give the password twice!";
        } else if($_POST["password1"] != $_POST["password2"]) {
            $errors["password2"] = "The passwords should be the same!";
        }

        $valid_register = empty($errors);
        if($valid_register) {
            $new_user = [];
            $new_user["username"] = $_POST["username"];
            $new_user["email"] = $_POST["email"];
            $new_user["password"] = $_POST["password1"];
            $new_user["money"] = 1000;
            $new_user["admin"] = false;
            $users_storage->add($new_user);
            $new_id = $users_storage->findOne(["username" => $new_user["username"]])["id"];
            $_SESSION["userid"] = $new_id;
            $_SESSION["username"] = $new_user["username"];
            $_SESSION["money"] = $new_user["money"];
            $_SESSION["admin"] = $new_user["admin"];
            header("Location: index.php");
            die;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
    <header><?php include_once("navbar.php"); ?></header>
    <form action="register.php" method="post" novalidate>
        <label for="username">Username: </label>
        <input type="text" name="username" id="username" value="<?= isset($_POST["username"]) ? $_POST["username"] : "" ?>">
        <span style="color: red;"><?= isset($errors["username"]) ? $errors["username"] : "" ?></span><br>

        <label for="email">Email: </label>
        <input type="text" name="email" id="email" value="<?= isset($_POST["email"]) ? $_POST["email"] : "" ?>">
        <span style="color: red;"><?= isset($errors["email"]) ? $errors["email"] : "" ?></span><br>

        <label for="password1">Password: </label>
        <input type="password" name="password1" id="password1" value="<?= isset($_POST["password1"]) ? $_POST["password1"] : "" ?>">
        <span style="color: red;"><?= isset($errors["password1"]) ? $errors["password1"] : "" ?></span><br>

        <label for="password2">Password again: </label>
        <input type="password" name="password2" id="password2" value="<?= isset($_POST["password2"]) ? $_POST["password2"] : "" ?>">
        <span style="color: red;"><?= isset($errors["password2"]) ? $errors["password2"] : "" ?></span><br>

        <button type="submit">Register</button>
    </form>

    <footer class="auth_footer">
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>