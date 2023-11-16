<?php
/*
 * Gemaakt door: Justin Lama Perez
*/
include('includes/header.php');
include('includes/nav.php');

require('lib/db-functions.php');

startConnection('NachtVanCuijk'); // Establish a connection to the database (assuming this is a function call)

// Check if the user's last activity was more than 30 minutes ago and unset session variables if necessary
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    session_unset();     // Unset $_SESSION variable for the current runtime
    session_destroy();   // Destroy session data in storage
}

$_SESSION['LAST_ACTIVITY'] = time(); // Update the last activity time stamp to the current time

// Check if the session was not created before or if it was created more than 30 minutes ago
if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time(); // Set the creation time of the session to the current time
} else if (time() - $_SESSION['CREATED'] > 1800) {
    session_regenerate_id(true);    // Change the session ID for the current session and invalidate the old session ID
    $_SESSION['CREATED'] = time();  // Update the creation time of the session to the current time
}

// Check if the 'LoggedIn' key exists in the session and if the value is false (indicating the user is not logged in)
if (!array_key_exists('LoggedIn', $_SESSION) || !$_SESSION['LoggedIn']) {
    header('location: login.php'); // Redirect the user to the login page
}

?>

<div class="wrapper">
    <div class="wrapper-header">
        <h1>Aanmeldingen</h1>
    </div>
    <hr>
    <div class="signups">
        <table class="signups-table">
            <thead>
            <tr>
                <th>Id</th>
                <th class="extra-width">Voornaam</th>
                <th class="extra-width">Achternaam</th>
                <th class="extra-width">Email</th>
                <th>Klaslokaal</th>
                <?php
                $query = 'SELECT * FROM CompetitionGames';
                $param = '';

                $result = executeQuery($query, $param);

                $games = [];

                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                    echo '<th class="extra-width">' . $row['GameTitle'] . '</th>';
                    $games[] = $row['GameTitle'];
                }

                ?>
            </tr>
            </thead>
            <tbody class="signup-presents-form">
            <?php
            startConnection('NachtVanCuijk'); // Establish a connection to the database (assuming this is a function call)

            $query = 'SELECT SignUp.PersonId, SignUp.Firstname, SignUp.Lastname, SignUp.StudentEmail, SignUp.Classroom, SignUpCompetition.CompetitionPersonId, SignUpCompetition.GameId, CompetitionGames.GameId, CompetitionGames.GameTitle as Game FROM SignUp INNER JOIN SignUpCompetition on SignUp.PersonId = SignUpCompetition.CompetitionPersonId INNER JOIN CompetitionGames on SignUpCompetition.GameId = CompetitionGames.GameId;';

            $param = '';

            $result = executeQuery($query, $param); // Execute the query and get the result

            $rowCount = 1; // Initialize the row count variable

            $signups = []; // Initialize an array to store the signups

            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                $emailExists = false;
                foreach ($signups as $signup) {
                    if ($signup['Email'] === $row['StudentEmail']) {
                        $emailExists = true;
                        break;
                    }
                }
                if ($emailExists) {
                    // If the email already exists in the signups array, find its position
                    $arrayPos = array_search($row['StudentEmail'], array_column($signups, 'Email'));
                    // Add the game to the existing signup's games array
                    $signups[$arrayPos]['Games'][] = $row['Game'];
                } else {
                    // If the email does not exist in the signups array, create a new signup entry
                    $signup = [
                        'Firstname' => $row['Firstname'],
                        'Lastname' => $row['Lastname'],
                        'Email' => $row['StudentEmail'],
                        'Classroom' => $row['Classroom'],
                        'Games' => [$row['Game']]
                    ];
                    // Add the new signup entry to the signups array
                    $signups[] = $signup;
                }
            }

            $gameCheck = []; // Initialize an array to store the game check results

            foreach ($signups as $signup) {
                $gameCheck = [];
                foreach ($games as $game) {
                    $gameCheck[$game] = in_array($game, $signup['Games']); // Check if the game exists in the signup's games
                }

                echo '<tr id="sign-up-tr">';
                echo '<td>' . $rowCount . '</td>';
                echo '<td>' . $signup['Firstname'] . '</td>';
                echo '<td>' . $signup['Lastname'] . '</td>';
                echo '<td>' . $signup['Email'] . '</td>';
                echo '<td>' . $signup['Classroom'] . '</td>';
                foreach ($gameCheck as $game => $exists) {
                    echo '<td>' . ($exists ? 'Ja' : 'Nee') . '</td>'; // Output 'Ja' if the game exists, 'Nee' otherwise
                }
                echo '</tr>';

                $rowCount++;
            }
            ?>

            </tbody>
        </table>
    </div>
</div>



