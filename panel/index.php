<?php
/*
 * Gemaakt door: Justin Lama Perez
*/
include('includes/header.php');
include('includes/nav.php');

require('lib/db-functions.php');

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 1800) {
    // session started more than 30 minutes ago
    session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
    $_SESSION['CREATED'] = time();  // update creation time
}

if(!array_key_exists('LoggedIn', $_SESSION) || !$_SESSION['LoggedIn']) {
    header('location: login.php');
}

if(!array_key_exists('username', $_SESSION)) {
    $_SESSION['username'] = '';
    header('location: login.php');
}

?>

<div class="wrapper">
    <div class="wrapper-header">
        <?php
        echo '<h1>Welkom, ' . $_SESSION['username'] . '</h1>'
        ?>
    </div>
    <hr>
</div>
