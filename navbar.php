<?php
if(!isset($_SESSION)) {
    session_start();
}

?>
<nav>
    <a href="index.php">IKÉMON</a>
    <?php if(!isset($_SESSION["userid"])): ?>
        <a href="register.php" id="register">Register</a>
        <a href="login.php" id="login">Log in</a>
        
    <?php endif; ?>
    <?php if(isset($_SESSION["userid"])): ?>
    <a href="user_details.php" id="user_details"><?= $_SESSION["username"] ?></a>
    <?php if(!$_SESSION["admin"]): ?>
    <span id="money"><?= $_SESSION["money"] ?> 💰</span>
    <?php endif; ?>
    <a href="logout.php" id="logout">Log out</a>
    <?php endif; ?>
</nav>