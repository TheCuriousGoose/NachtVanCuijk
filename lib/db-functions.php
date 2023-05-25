<?php
/*
 * Gemaakt door: Justin Lama Perez
 */

$servername = $config::SQLServerName;
$username = $config::SQLUserName;
$password = $config::SQLPassword;

function startConnection($dbname): void
{
    global $servername, $username, $password;
    $connectionInfo = array("Database" => $dbname, "UID" => $username, "PWD" => $password);
    $GLOBALS["conn"] = sqlsrv_connect($servername, $connectionInfo);

    if (!$GLOBALS["conn"]) {
        echo "Connection could not be established.<br />";
        die(print_r(sqlsrv_errors(), true));
    }
}

function executeQuery($sql)
{
    try {
        // Query uitvoeren
        return sqlsrv_query($GLOBALS["conn"], $sql);
    } catch (PDOException $e) {
        echo 'Er is een probleem van het ophalen van de data: ' . $e->getMessage();
        die();
    }
}