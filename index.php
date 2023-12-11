<?php
    session_start();
    require_once __DIR__."/vendor/autoload.php";
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
    require_once "config/config.php";

    use Controllers\FrontController;
    FrontController::main();


?>

