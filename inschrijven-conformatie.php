<?php
/*
 * Gemaakt door: Justin Lama Perez
 */

include 'includes/header.php';
include 'includes/nav.php';

require 'lib/db-functions.php';
require 'lib/mailer.php';
require 'lib/time_formatter.php';

$query = 'SELECT * FROM Date';

startConnection('NachtVanCuijk');

$result = executeQuery($query, '');

$datetime = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

$query = 'SELECT * FROM snackCarPrice';

$result = executeQuery($query, '');

$price = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$studentnumber = $_POST['student-number'];
$email = $_POST['email'];
if($_POST['visitor'] == 'true'){
    $visitor = 1;
}else{
    $visitor = 0;
}
if($_POST['snackcar'] == 'true'){
    $snackcar = 1;
}else{
    $snackcar = 0;
}
$classroom = $_POST['classroom'];

if($classroom == null)
{
    $emailResult = sendConfirmationMail($email, $firstname . ' ' . $lastname, 'noclassroom', longDateNoTime($datetime['NachtVanCuijkDate']), noDateWithTime($datetime['NachtVanCuijkDate']), $price['price']);
}else{
    $emailResult = sendConfirmationMail($email, $firstname . ' ' . $lastname, $classroom, longDateNoTime($datetime['NachtVanCuijkDate']), noDateWithTime($datetime['NachtVanCuijkDate']), $price['price']);
}

if(!$emailResult) {
    echo '<script>alert("Voer een geldig Email adres in")</script>';
    //header('Location:  inschrijven.php');
}else{
    $signupThank = 'Bedankt voor het inschrijven!';
    $query = "insert into SignUp (Firstname, Lastname, StudentNumber, StudentEmail, Visitor, SnackCar, Classroom) values (?, ? ,?, ?, ?, ?, ?)";
    $param = array($firstname, $lastname, $studentnumber, $email, $visitor, $snackcar, $classroom);
    $result = executeQuery($query, $param);
}


?>

<div class="thank-you-wrapper">
    <div class="thank-you-card">
        <h1>
            Bedankt voor het inschrijven!
        </h1>
        <hr>
        <p><?php echo 'We zien je graag op ' . longDateNoTime($datetime['NachtVanCuijkDate']) . ' vanaf ' . noDateWithTime($datetime['NachtVanCuijkDate']) ?></p>
        <p>De conformatie mail kan in spam terecht komen</p>
    </div>
</div>

<?php
include "includes/footer.php";