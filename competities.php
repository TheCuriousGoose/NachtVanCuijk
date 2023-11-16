<?php
include 'includes/header.php';
include 'includes/nav.php';
require 'lib/db-functions.php';

?>

<div class="comp-wrapper">
    <div class="comp-header">
        <h2>COMPETITIES</h2>
        <h3>Dit zijn de competities die we deze editie van Nacht van Cuijk houden</h3>
        <p>Selecteer hieronder welke competities jij aan mee wilt doen</p>
    </div>
    <div class="comp-body">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="comp-card-wrapper">
                <?php
                startConnection('NachtVanCuijk');
                $query = 'SELECT * FROM CompetitionGames';
                $result = executeQuery($query, '');
                $gameNameWithId = array();
                $gameNames = array();

                while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                    $gameTitle = str_replace(' ', '_', $row['GameTitle']);
                    $gameNameWithId += [$gameTitle => $row['GameId']];
                    $gameNames[] = $gameTitle;
                ?>
                <div class="comp-card">
                    <input type="checkbox" id="<?php echo $gameTitle; ?>" name="<?php echo $gameTitle; ?>">
                    <label for="<?php echo $gameTitle; ?>"><img src="<?php echo $row['GameImage']; ?>"></label>
                </div>
                <?php
                }
                ?>
            </div>
            <label for="comp-email">Email <span>(verplicht)</span></label>
            <p>Let op, Geen KW1C email adres invoeren!</p>
            <input type="email" id="comp-email" name="comp-email" required placeholder="Je Email hier...">
            <div class="grecaptcha-comp">
                <div class="g-recaptcha" data-sitekey="<?php echo $config::GOOGLE_RECAPTCHA_SITE_KEY ?>"></div>
            </div>
            <button type="submit">Inschrijven</button>
        </form>
    </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recaptcha_secret = $config::GOOGLE_RECAPTCHA_SECRET_KEY;
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $recaptcha_secret . "&response=" . $_POST['g-recaptcha-response']);
    $response = json_decode($response, true);

    if (!$response["success"]) {
        echo '<script>alert("Voer de Recaptcha in")</script>';
    } else {
        $filledIn = false;
        $chosenGames = array();

        foreach ($gameNames as $game) {
            if (isset($_POST[$game])) {
                $filledIn = true;
                $chosenGames[] = $game;
            }
        }

        if (!$filledIn) {
            echo '<script>alert("Selecteer minimaal 1 game")</script>';
        } else {
            startConnection('NachtVanCuijk');
            $query = 'SELECT COUNT(1) AS count, PersonId FROM SignUp WHERE StudentEmail = ? group by PersonId';
            $param = array($_POST['comp-email']);
            $result = executeQuery($query, $param);
            $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

            if (!$row) {
                echo '<script>alert("Meld je eerst aan voor Nacht van Cuijk voordat je jezelf aanmeldt voor een competitie")</script>';
            } else {
                $personId = $row['PersonId'];

                foreach ($chosenGames as $game) {
                    $query = 'INSERT INTO SignUpCompetition (CompetitionPersonId, GameId) VALUES (?, ?)';
                    $param = array($personId, $gameNameWithId[$game]);
                    executeQuery($query, $param);
                }

                echo '<script>alert("Je hebt je aangemeld voor de competitie!")</script>';
            }
        }
    }
}

include 'includes/footer.php';
?>
