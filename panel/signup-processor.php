<?php
include('includes/header.php');

include('lib/db-functions.php');

startConnection('NachtVanCuijk');

if($_POST['updateColumn'] == 'Present'){
    $query = 'UPDATE SignUp SET Present = ? Where PersonId = ?';
    $param = array($_POST['value'], $_POST['personId']);

    executeQuery($query, $param);

}else if($_POST['updateColumn'] == 'PaidSnackCar'){
    $query = 'UPDATE SignUp SET PaidSnackCar = ? Where PersonId = ?';
    $param = array($_POST['value'], $_POST['personId']);

    executeQuery($query, $param);
}




