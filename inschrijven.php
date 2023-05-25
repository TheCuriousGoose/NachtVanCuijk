<?php
/*
 * Gemaakt door: Justin Lama Perez
 */

include 'includes/nav.php';

require 'lib/db-functions.php';
require 'lib/recaptcha.php';
//require 'lib/mailer.php';

$firstnameErr = $lastnameErr = $emailErr = $visitorErr = "";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $recaptcha_secret = $config::GOOGLE_RECAPTCHA_SECRET_KEY;
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$recaptcha_secret."&response=".$_POST['g-recaptcha-response']);
    $response = json_decode($response, true);

    if($response["success"] === true){
        if (empty($_POST["firstname"])) {
            $firstnameErr = 'Voer aub je voornaam in';
        }
        if (empty($_POST["lastname"])) {
            $lastnameErr = 'Voer aub je achternaam in';
        }
        if (empty($_POST["studentnumber"])) {
            $studentNumberErr = 'Voer je studenten nummer of postcode in';
        }
        if (empty($_POST["email"])) {
            $emailErr = 'Voer je email in';
        }
        if (empty($_POST["visitors"])) {
            $visitorErr = 'Voer in of je een bezoeker ben of niet';
        }
    }else{
        $recaptchaErr = 'Vul de recaptcha in';
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
                        <span class="error"><?php echo $firstnameErr ?? null; ?></span>
                    </div>
                    <div class="lastname">
                        <label for="lastname">Achternaam<span>(verplicht)</span></label>
                        <input type="text" name="lastname" placeholder="Je achternaam hier..."
                               value="<?php if ($_POST['lastname'] ?? null) {
                                   echo $_POST['lastname'];
                               } ?>">
                        <span class="error"><?php echo $firstnameErr ?? null; ?></span>
                    </div>
                    <div class="student-number">
                        <label for="student-number">Leerlingnummer<span>(verplicht)</span></label>
                        <p>Indien je geen leerlingnummer hebt gebruik je postcode</p>
                        <input type="text" name="student-number" placeholder="Je leeringnummer/postcode hier..."
                               value="<?php if ($_POST['studentnumber'] ?? null) {
                                   echo $_POST['studentnumber'];
                               } ?>">
                        <span class="error"><?php echo $lastnameErr ?? null; ?></span>
                    </div>
                    <div class="email">
                        <label for="email">Email<span>(verplicht)</span></label>
                        <p>Let op, Geen KW1C email adres invoeren!</p>
                        <input type="email" name="email" placeholder="Je Email hier..."
                               value="<?php if ($_POST['email'] ?? null) {
                                   echo $_POST['email'];
                               } ?>">
                        <span class="error"><?php echo $emailErr ?? null; ?></span>
                    </div>
                    <div class="visitors">
                        <label for="visitor">Bezoeker<span>(verplicht)</span></label>
                        <select name="visitor" id="visitor">
                            <option value="">Maak een keuze</option>
                            <option value="yes">Ik ben een bezoeker</option>
                            <option value="no">Ik ben geen bezoeker</option>
                        </select>
                        <span class="error"><?php echo $visitorErr ?? null; ?></span>
                    </div>
                    <div class="snackcar">
                        <label for="snackcar">Frietkar (&euro;7)</label>
                        <select name="snackcar" id="snackcar">
                            <option value="">Maak een keuze</option>
                            <option value="yes">Ja</option>
                            <option value="no">Nee</option>
                        </select>
                    </div>
                    <div class="classroom">
                        <label for="classroom">Kies je plek</label>
                        <p>Bezoekers hoeven geen plek te kiezen</p>
                        <select name="classroom" id="classroom">
                            <option value="">Maak een keuze</option>
                            <?php
                            startConnection('NachtVanCuijk');

                            $query = 'SELECT * FROM Classrooms';

                            $result = executeQuery($query);

                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                echo "<option value=:" . $row["ClassroomNumber"] . "> " . $row["ClassroomNumber"] . "</option>";
                            }
                            ?>
                        </select>
                        <span class="error"><?php echo $classroomErr ?? null; ?></span>
                    </div>
                    <div class="grecaptcha">
                        <div class="g-recaptcha" data-sitekey="<?php echo $config::GOOGLE_RECAPTCHA_SITE_KEY ?>"></div>
                        <span class="error"><?php echo $emailErr ?? null; ?></span>
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
                    <img src="images/dj_in_kantine.png" alt="Een dj die muziek speelt in de kantine">
                </div>
            </div>
        </div>
    </div>

<?php

include "includes/footer.php";


