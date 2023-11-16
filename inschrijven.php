<?php
/*
 * Gemaakt door: Justin Lama Perez
 */
include 'includes/header.php';
include 'includes/nav.php';

require 'lib/db-functions.php';
//require 'lib/mail-functions.php';

$firstnameErr = $lastnameErr = $emailErr = $visitorErr = "";

$filledIn = true;

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the secret key for Google reCAPTCHA from the configuration
    $recaptcha_secret = $config::GOOGLE_RECAPTCHA_SECRET_KEY;
    // Send a request to Google reCAPTCHA API to verify the user's response
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $recaptcha_secret . "&response=" . $_POST['g-recaptcha-response']);
    $response = json_decode($response, true);

    // Check if the reCAPTCHA verification succeeded
    if ($response["success"] === true) {

        $fields = [
            "firstname" => "Voer aub je voornaam in",
            "lastname" => "Voer aub je achternaam in",
            "student-number" => "Voer je studenten nummer of postcode in",
            "email" => "Voer je email in",
            "visitor" => "Voer in of je een bezoeker bent of niet",
        ];

        if ($_POST['visitor'] == 'no') {
            $fields["classroom"] = "Voer in of je een bezoeker bent of niet";
        }

        // Initialize an empty array to store the error messages
        $errors = [];

        // Check each field for emptiness and populate the errors array if necessary
        foreach ($fields as $fieldName => $errorMessage) {
            if (empty($_POST[$fieldName])) {
                $errors[$fieldName] = $errorMessage;
                $filledIn = false;
            }
        }
    } else {
        // The reCAPTCHA verification failed
        $recaptchaErr = 'Vul de reCAPTCHA in';
        $filledIn = false;
    }

    if ($filledIn) {
        ?>
        <form id="redirectForm" action="inschrijven-conformatie.php" method="post">
            <?php
            foreach ($_POST as $a => $b) {
                echo '<input type="hidden" name="' . htmlentities($a) . '" value="' . htmlentities($b) . '">';
            }
            ?>
        </form>
        <script type="text/javascript">
            document.getElementById('redirectForm').submit();
        </script>
        <?php
    }
}
?>

    <div class="signup-wrapper">
        <div class="signup">
            <h2>INSCHRIJVEN</h2>
        </div>
        <div class="content-wrapper">
            <div class="form">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="firstname">
                        <label for="firstname">Voornaam<span>(verplicht)</span></label>
                        <input type="text" name="firstname" placeholder="Je voornaam hier..."
                               value="<?php if ($_POST['firstname'] ?? null) {
                                   echo $_POST['firstname'];
                               } ?>">
                        <div class="firstname-error">

                        </div>
<!--                        <span class="error">--><?php //echo $firstnameErr = $errors["firstname"] ?? ''; ?><!--</span>-->
                    </div>
                    <div class="lastname">
                        <label for="lastname">Achternaam<span>(verplicht)</span></label>
                        <input type="text" name="lastname" required placeholder="Je achternaam hier..."
                               value="<?php if ($_POST['lastname'] ?? null) {
                                   echo $_POST['lastname'];
                               } ?>">
                        <span class="error"><?php echo $lastnameErr = $errors["lastname"] ?? ''; ?></span>
                    </div>
                    <div class="student-number">
                        <label for="student-number">Leerlingnummer<span>(verplicht)</span></label>
                        <p>Indien je geen leerlingnummer hebt gebruik je postcode</p>
                        <input type="text" name="student-number" required placeholder="Je leeringnummer/postcode hier..."
                               value="<?php if ($_POST['student-number'] ?? null) {
                                   echo $_POST['student-number'];
                               } ?>">
                        <span class="error"><?php echo $studentNumberErr = $errors["student-number"] ?? ''; ?></span>
                    </div>
                    <div class="email">
                        <label for="email">Email<span>(verplicht)</span></label>
                        <p>Let op, Geen KW1C email adres invoeren!</p>
                        <input type="email" name="email" required placeholder="Je Email hier..."
                               value="<?php if ($_POST['email'] ?? null) {
                                   echo $_POST['email'];
                               } ?>">
                        <span class="error"><?php echo $emailErr = $errors["email"] ?? ''; ?></span>
                    </div>
                    <div class="visitors">
                        <label for="visitor">Bezoeker<span>(verplicht)</span></label>
                        <select name="visitor" id="visitor" required onchange="classroomAdder(this.value)">
                            <option value="" id="visitor-">Maak een keuze</option>
                            <option value="true" id="visitor-true">Ik kom alleen even kijken</option>
                            <option value="false" id="visitor-false">Ik doe mee aan Nacht Van Cuijk</option>
                        </select>
                        <span class="error"><?php echo $visitorErr = $errors["visitor"] ?? ''; ?></span>
                    </div>
                    <div class="snackcar">
                        <?php
                        // Establish a connection to the database using the 'NachtVanCuijk' database name
                        startConnection('NachtVanCuijk');

                        $query = 'select * from snackcarPrice';

                        $result = executeQuery($query, '');

                        $price = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

                        ?>
                        <label for="snackcar">Frietkar (&euro;<?= $price['price'] ?>)</label>
                        <select name="snackcar" id="snackcar">
                            <option value="">Maak een keuze</option>
                            <option value="true">Ja</option>
                            <option value="false">Nee</option>
                        </select>
                    </div>
                    <div class="classroom" id="classroom-section">
                        <label for="classroom">Kies je plek</label>
                        <p>Kies een lokaal</p>
                        <select name="classroom" id="classroom">
                            <option value="">Maak een keuze</option>
                            <?php


                            // Define the SQL query to select all rows from the 'Classrooms' table
                            $query = 'select Classrooms.*, count(SignUp.Classroom) as ClassroomCount from Classrooms WITH (NOLOCK) left join SignUp on Classrooms.ClassroomNumber = SignUp.Classroom group by ClassroomNumber, ClassroomLimit, ClassroomId';

                            // Execute the SQL query and store the result
                            $result = executeQuery($query, '');

                            // Iterate through each row in the result and generate HTML options for a dropdown menu
                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                // Extract the "ClassroomNumber" value from the current row and use it as the option value and text
                                $classroomNumber = $row["ClassroomNumber"];
                                if (!($row['ClassroomCount'] >= $row['ClassroomLimit'])) {
                                    echo "<option value='$classroomNumber'>$classroomNumber (" . $row['ClassroomCount'] . "/" . $row['ClassroomLimit'] . ")</option>";
                                }
                            }
                            ?>
                        </select>
                        <span class="error"><?php echo $errors["classroom"] ?? ''; ?></span>
                    </div>
                    <div class="grecaptcha">
                        <div class="g-recaptcha" data-sitekey="<?php echo $config::GOOGLE_RECAPTCHA_SITE_KEY ?>"></div>
                        <span class="error"><?php echo $recaptchaErr ?? null; ?></span>
                    </div>
                    <div class="acceptag">
                        <label for="acceptag">
                            Met het inschrijven ga je akkoord met de &nbsp;<a href="algemene%20voorwaarden.php">algemene
                                voorwaarden</a></label>
                    </div>
                    <div class="submit">
                        <button type="submit">Inschrijven</button>
                    </div>
                </form>
            </div>
            <div class="sign-up-image">
                <div class="image-center">
                    <iframe src="https://discord.com/widget?id=1149000240326783016&theme=dark" width="350" height="500" allowtransparency="true" frameborder="0" sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts"></iframe>
                </div>
            </div>
        </div>
    </div>

<?php

include "includes/footer.php";