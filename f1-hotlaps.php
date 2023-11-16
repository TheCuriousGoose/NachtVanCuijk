<?php

include('includes/header.php');

require('lib/db-functions.php');

startConnection('NachtVanCuijk');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

// Check if the form is submitted
    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $time = $_POST['time'];
        $itime = "0:" . $time;

        // Insert the data into the database
        $query = "INSERT INTO f1HotLaps (name, hotlap_time) VALUES (?, ?)";
        $params = array($name, $itime);

        executeQuery($query, $params);

    }
}

?>

<!doctype html>
<html lang=nl>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>F1 Hotlaps</title>
</head>
<body>
<h1>Enter F1 Hotlap Times</h1>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="name">Voornaam-achternaam:</label>
    <input type="text" name="name" placeholder="(e.g., Alex Cooper" required><br>

    <label for="time">Ronde Tijd (m:ss:ms):</label>
    <input type="text" name="time" pattern="[0-9]{1,2}:[0-5][0-9].[0-9]{1,3}" placeholder="(e.g., 1:16:330)"
           required><br>

    <input type="submit" value="Submit" name="submit">
</form>
<h1>F1 Rondetijden</h1>
<table>
    <tr>
        <th>Positie</th>
        <th>Naam</th>
        <th>Ronde tijd</th>
    </tr>
    <?php
    // Fetch the data from the database
    $query = "SELECT * FROM f1HotLaps ORDER BY hotlap_time ASC";

    $result = executeQuery($query, '');

    $position = 1;
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $position . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['hotlap_time']->format('i:s.u') . "</td>";
        echo "</tr>";
        $position++;
    }
    ?>
</table>
</body>
</html>