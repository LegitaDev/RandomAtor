<link rel="stylesheet" href="assests/css/all.min.css">
<link rel="stylesheet" href="assests/css/bootstrap.min.css">
<link rel="stylesheet" href="assests/css/style.css?v=2.5">

<?php
include_once("./incl/conn.php");

spl_autoload_register(function ($class) {
    require_once("./incl/classes/" . $class . ".class.php");
});

session_start();

$curPageName = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);

if ($curPageName == "catFacts.php") : ?>
    <link rel="stylesheet" href="assests/css/catFactsStyle.css?v=1.1">
    <link href="https://fonts.googleapis.com/css?family=Archivo+Black&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Archivo:700&display=swap" rel="stylesheet">

<?php elseif ($curPageName == "steam.php") : ?>
    <link rel="stylesheet" href="assests/css/steam.css?v=1.3">

<?php elseif ($curPageName == "login.php" || $curPageName == "register.php") : ?>
    <link rel="stylesheet" href="assests/css/login.css">

<?php endif; ?>