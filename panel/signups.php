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

if (!array_key_exists('LoggedIn', $_SESSION) || !$_SESSION['LoggedIn']) {
    header('location: login.php');
}

$filterActive = false;
$amountRows = 25;
$visitor = null;
$classroom = null;
$nameSearch = null;
$presents = null;
$snackcarFilter = null;
$snackcarPaidFilter = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $filterActive = true;

    echo implode(', ', $_POST);

    if ($_POST['visitor'] != 'all') {
        if ($_POST['visitor'] == 'visitor') {
            $visitor = 1;
        } else {
            $visitor = 0;
        }
    } else {
        $visitor = null;
    }

    if ($_POST['classroom'] != 'all') {
        $classroom = $_POST['classroom'];
    } else {
        $classroom = null;
    }

    if (!empty($_POST['nameSearch'])) {
        $nameSearch = $_POST['nameSearch'];
    } else {
        $nameSearch = '';
    }

    if ($_POST['presents'] != 'all') {
        if ($_POST['presents'] == 'present') {
            $presents = 1;
        } else {
            $presents = 0;
        }
    } else {
        $presents = null;
    }

    if ($_POST['snackcar'] != 'all') {
        if ($_POST['snackcar'] == 'snackcar') {
            $snackcarFilter = 1;
        } else {
            $snackcarFilter = 0;
        }
    } else {
        $snackcarFilter = null;
    }

    if ($_POST['snackcarPaid'] != 'all') {
        if ($_POST['snackcarPaid'] == 'paid') {
            $snackcarPaidFilter = 1;
        } else {
            $snackcarPaidFilter = 0;
        }
    } else {
        $snackcarPaidFilter = null;
    }
}

?>

<div class="wrapper">
    <div class="wrapper-header">
        <h1>Aanmeldingen</h1>
    </div>
    <hr>
    <div class="signups">
        <div class="filters">
            <form action="signups.php" method="post">
                <div>
                    <label for="visitor">Bezoeker:</label>
                    <select name="visitor" id="visitorFilter">
                        <option value="all">Alles</option>
                        <option value="visitor">Bezoeker</option>
                        <option value="notVisitor">Geen bezoeker</option>
                    </select>
                </div>
                <div>
                    <label for="classroom">Klaslokaal:</label>
                    <select name="classroom" id="classroomFilter">
                        <option value="all">Alles</option>
                        <?php
                        // Establish a connection to the database using the 'NachtVanCuijk' database name
                        startConnection('NachtVanCuijk');

                        // Define the SQL query to select all rows from the 'Classrooms' table
                        $query = 'select * from Classrooms WITH (NOLOCK)';

                        // Execute the SQL query and store the result
                        $result = executeQuery($query, '');

                        // Iterate through each row in the result and generate HTML options for a dropdown menu
                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                            // Extract the "ClassroomNumber" value from the current row and use it as the option value and text
                            $classroomNumber = $row["ClassroomNumber"];
                            echo "<option value='$classroomNumber'>$classroomNumber</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="nameSearch">Zoek op voornaam:</label>
                    <input type="text" name="nameSearch" placeholder="Voer voornaam in...">
                </div>
                <div>
                    <label for="presents">Aanwezigheid:</label>
                    <select name="presents" id="presentsFilter">
                        <option value="all">Alles</option>
                        <option value="present">Aanwezig</option>
                        <option value="notPresent">Afwezig</option>
                    </select>
                </div>
                <div>
                    <label for="snackcar">Frietkar:</label>
                    <select name="snackcar" id="snackcarFilter">
                        <option value="all">Alles</option>
                        <option value="snackcar">Frietkar</option>
                        <option value="notSnackcar">Geen frietkar</option>
                    </select>
                </div>
                <div>
                    <label for="snackcarPaid">Frietkar betaald:</label>
                    <select name="snackcarPaid" id="snackcarPaidFilter">
                        <option value="all">Alles</option>
                        <option value="paid">Betaald</option>
                        <option value="notPaid">Niet betaald</option>
                    </select>
                </div>
                <div>
                    <button type="submit">Laat zien</button>
                </div>
            </form>
        </div>
        <form action="signup-processor.php">
            <table class="signups-table">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Voornaam</th>
                    <th>Achternaam</th>
                    <th>Studentennummer</th>
                    <th class="extra-width">Email</th>
                    <th>Bezoeker</th>
                    <th>Klaslokaal</th>
                    <th>Frietkar</th>
                    <th style="text-align: center">Aanwezig</th>
                    <th style="text-align: center">Betaald Frietkar</th>
                </tr>
                </thead>
                <tbody class="signup-presents-form">
                <?php
                startConnection('NachtVanCuijk');

                $query = 'select * from SignUp';

                $visitorSQL = '';
                $classroomSQL = '';
                $nameSearchSQL = '';
                $presentsSQL = '';
                $snackcarFilterSQL = '';
                $snackcarPaidFilterSQL = '';

                if ($filterActive) {
                    $visitorSQL = ($visitor === null) ? '(Visitor >= 0 or Visitor is null)' : "Visitor = $visitor";

                    $classroomSQL = ($classroom === null) ? "Classroom like '%%'" : "Classroom like '$classroom'";

                    $nameSearchSQL = ($nameSearch == null) ? "Firstname like '%%'" : "Firstname like '$nameSearch'";

                    $presentsSQL = ($presents == null) ? '(Present >= 0 or Present is null)' : "Present = $presents";

                    $snackcarSQL = ($snackcarFilter === null) ? '(SnackCar >= 0 or SnackCar is null)' : "SnackCar = $snackcarFilter";

                    $snackcarPaidFilterSQL = ($snackcarPaidFilter == null) ? '(PaidSnackCar >= 0 or PaidSnackCar is null)' : "PaidSnackCar = $snackcarPaidFilter";

                    $query = "SELECT * FROM SignUp WHERE $visitorSQL AND $classroomSQL AND $nameSearchSQL AND $presentsSQL AND $snackcarSQL AND $snackcarPaidFilterSQL";
                }

                $param = array();

                $result = executeQuery($query, $param);

                $rowCount = 1;

                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                    $visitor = (!$row['Visitor']) ? 'Nee' : 'Ja';
                    $snackcar = (!$row['SnackCar']) ? 'Nee' : 'Ja';
                    $present = (!$row['Present']) ? '' : 'checked';
                    $paidSnackcar = (!$row['PaidSnackCar']) ? '' : 'checked';

                    $PersonId[] = $row['PersonId'];

                    echo '<tr id="sign-up-tr">';
                    echo '<td>' . $rowCount . '</td>';
                    echo '<td>' . $row['Firstname'] . '</td>';
                    echo '<td>' . $row['Lastname'] . '</td>';
                    echo '<td>' . $row['StudentNumber'] . '</td>';
                    echo '<td>' . $row['StudentEmail'] . '</td>';
                    echo '<td>' . $visitor . '</td>';
                    echo '<td>' . $row['Classroom'] . '</td>';
                    echo '<td>' . $snackcar . '</td>';
                    echo '<td class="center-checkbox-table" style="text-align: center"> <input id="sign-up-in" type="checkbox" ' . $present . ' name="' . $row['PersonId'] . '" oninput="updatePresents(this)"></td>';
                    echo '<td class="center-checkbox-table" style="text-align: center"> <input id="sign-up-tr" type="checkbox" ' . $paidSnackcar . ' name="' . $row['PersonId'] . '" oninput="updateSnackcar(this)"></td>';
                    echo '</tr>';

                    $rowCount++;

                }
                ?>
                </tbody>
            </table>
        </form>
    </div>
</div>
