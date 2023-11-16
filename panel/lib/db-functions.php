<?php
/*
 * Gemaakt door: Justin Lama Perez
 */

$servername = $config::SQL_SERVERNAME;
$username = $config::SQL_USERNAME;
$password = $config::SQL_PASSWORD;

function startConnection($dbname): void
{
    global $servername, $username, $password;
    $connectionInfo = array(
        "Database" => $dbname,
        "UID" => $username,
        "PWD" => $password
    );

    $GLOBALS["conn"] = sqlsrv_connect($servername, $connectionInfo);

    if ($GLOBALS["conn"] === false) {
        die("Connection could not be established.<br />" . print_r(sqlsrv_errors(), true));
    }
}

function executeQuery($sql, $param)
{
    if($param){
        try {
            // Query uitvoeren
            return sqlsrv_query($GLOBALS["conn"], $sql, $param);
        } catch (PDOException $e) {
            echo 'Er is een probleem van het ophalen van de data: ' . $e->getMessage();
            die();
        }
    }else{
        try {
            // Query uitvoeren
            return sqlsrv_query($GLOBALS["conn"], $sql);
        } catch (PDOException $e) {
            echo 'Er is een probleem van het ophalen van de data: ' . $e->getMessage();
            die();
        }
    }


}