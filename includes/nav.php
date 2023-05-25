<?php
/*
 * Gemaakt door: Justin Lama Perez
*/

include 'config.php';

$config = new Config();

?>

<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/styles/stylesheet.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat|Roboto+Slab">
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>

    <title>Nacht Van Cuijk</title>
</head>
<body>
<div class="wrapper">
    <div class="nav-wrapper">
        <div class="logo">
            <a href="/index.php">
                <img src="/images/logo.png" alt="Het logo van Nacht Van Cuijk">
            </a>
        </div>
        <div class="nav-pages">
            <ul class="nav-list">
                <li class="nav-items"><a href="index.php">HOME</a></li>
                <li class="nav-items"><a href="overons.php">OVER ONS</a></li>
                <li class="nav-items"><a href="inschrijven.php">INSCHRIJVEN</a></li>
                <li class="nav-items"><a href="competities.php">COMPETITIES</a></li>
                <li class="nav-items"><a href="contact.php">CONTACT</a></li>
            </ul>
        </div>
    </div>

