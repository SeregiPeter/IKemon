<?php
    session_start();
    require_once("Storage.php");
    $users_storage = new Storage(new JsonIO("users.json"));
    $users = $users_storage->findAll();
    $usernames= array_column($users,"username");
    $passwords = array_column($users,"password");
    $errors = [];
    $valid_login = false;

    if($_POST) {
        $user= [];
        if(isset($_POST["username"])) {
            $user = $users_storage->findOne(["username" => $_POST["username"]]);
        }
        if(!isset($_POST["username"]) || empty(trim($_POST["username"]))) {
            $errors["username"] = "Name is required!";
        } else if(!in_array($_POST["username"], $usernames)) {
            $errors["username"] = "No user registered with this name!";
        } else if(!isset($_POST["password"]) || empty(trim($_POST["password"]))) {
            $errors["password"] = "Password is required!";
        } else if($user["password"] != $_POST["password"]) {
            $errors["password"] = "Invalid password!";
        }

        $valid_login = empty($errors);
        if($valid_login) {
            $_SESSION["userid"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["money"] = $user["money"];
            $_SESSION["admin"] = $user["admin"];
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
    <title>Log in</title>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
    <header><?php include_once("navbar.php"); ?></header>
    <form action="login.php" method="post" class="auth" novalidate>
        <label for="username">Username: </label>
        <input type="text" name="username" id="username" value="<?= isset($_POST["username"]) ? $_POST["username"] : "" ?>">
        <span style="color: red;"><?= isset($errors["username"]) ? $errors["username"] : "" ?></span><br>

        <label for="password">Password: </label>
        <input type="password" name="password" id="password" value="<?= isset($_POST["password"]) ? $_POST["password"] : "" ?>">
        <span style="color: red;"><?= isset($errors["password"]) ? $errors["password"] : "" ?></span><br>

        <button type="submit">Log in</button>      
    </form>

    <footer class="auth_footer">
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>