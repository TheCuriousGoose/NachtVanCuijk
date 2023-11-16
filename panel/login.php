<?php
/*
 * Gemaakt door: Justin Lama Perez
*/
include('includes/header.php');
require('lib/db-functions.php');

startConnection('NachtVanCuijk');

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

if(array_key_exists('LoggedIn', $_SESSION) && $_SESSION['LoggedIn']) {
    header('location: index.php');
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = 'select * from Users where Username like ?';
    $param = array($_POST['username']);

    $result = executeQuery($query, $param);

    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        if (password_verify($_POST['password'], $row['UserPassword'])) {

            $_SESSION['LoggedIn'] = true;
            $_SESSION['username'] = $row['Username'];
            header('location: index.php');
        } else {
            echo '
<script>
alert("Incorrect credentials");
document.getElementById("loginForm").reset();    
</script>';

        }
    }
}

?>

<div class="login-wrapper">
    <div class="login">
        <div class="login-image">
            <img src="images/logo.png" alt="Nacht van Cuijk">
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="loginForm">
            <label for="username">Username</label>
            <input name="username" type="text" placeholder="Enter username...">
            <label for="password">Password</label>
            <input name="password" type="password" placeholder="Enter password...">
            <button type="submit">Login</button>
        </form>
    </div>
</div>
