<?php
/*
 * Gemaakt door: Justin Lama Perez
*/

include 'includes/header.php';
include 'includes/nav.php';

require 'lib/db-functions.php';
require 'lib/time_formatter.php';
?>

<div class="frontpage">
    <div class="background">
        <div class="background-overlay">
        </div>
        <img src="images/Background-Front-page.jpg" alt="Afbeelding van een jongen die aan het gamen is">
    </div>
    <div class="text">
        <h1>NACHT VAN <span class="purple">CUIJK</span></h1>
        <h2><span class="purple">VOOR</span> EN <span class="purple">DOOR</span> STUDENTEN</h2>
        <div class="button">
            <a href="inschrijven.php">INSCHRIJVEN</a>
        </div>
    </div>
</div>
<div class="section-kw1c">
    <div class="section-wrapper">
        <div class="title">
            <h2>KONING WILLEM 1 COLLEGE</h2>
        </div>
        <div class="section-content">
            <div class="text">
                <p>De Nacht van Cuijk word georganiseerd door en voor leerlingen van het Koning Willem 1 College in Cuijk.</p>
                    <p>Samen met 100+ man kan je gamen van <b>17:00 t/m 7:00</b> met tussendoor leuke competities waar prijzen bij te halen zijn.De eerstvolgende editie van de Nacht van Cuijk zal plaatsvinden op
                    <?php
                    startConnection('NachtVanCuijk');

                    $query = 'select * from Date';

                    $result = executeQuery($query, '');

                    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
                        echo longDateNoTime($row['NachtVanCuijkDate']) . ' <b>(aanvang vanaf ' . noDateWithTime($row['NachtVanCuijkDate']) . '</b>)';
                    }
                    ?>
                </p>
            </div>
            <div class="image">
                <img src="images/section_digiboard_with_people_around.jpg"
                     alt="Een afbeelding van mensen die rondom een digibord zitten">
            </div>
        </div>
    </div>
</div>
<div class="section-almost100">
    <div class="section-background">
        <div class="overlay">
        </div>
        <img src="images/background-board-back-of-people.jpg"
             alt="Een afbeelding van mensen die voor een digibord zitten">
    </div>
    <div class="overlay-content-wrapper">
        <div class="signup-promotion">
            <h2>
                <?php

                $query = "SELECT count(*) AS 'count' FROM SignUp";

                $result = executeQuery($query,'');

                $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

                if ($row['count'] >= 50) {
                    echo "De aanmeldingen gaan al hard! We zijn hard op weg naar de 80 en voor de 80ste inschrijving hebben wij iets leuks klaar staan. Meld je snel aan en wie weet gaat deze prijs met jou mee!";
                } else {
                    echo "De eerste paar aanmeldingen stromen al binnen! Word jij 1 van de nachtbrakers? Meld je snel aan!";
                }
                ?>

            </h2>
            <div class="button">
                <a href="inschrijven.php">INSCHRIJVEN</a>
            </div>
        </div>
    </div>
</div>
<div class="section-sponsors">
    <div class="sponsor-topper">
        <h2>Sponsoren</h2>
        <p>De nacht van Cuijk wordt mede mogelijk gemaakt door:</p>
    </div>
    <div class="card-wrapper">
        <?php
        $query = 'select * from Sponsors';

        $result = executeQuery($query,'');

        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) { ?>
            <div class="card">
                <a href="<?php echo $row['SponsorWeblink'] ?>" target="_blank">
                    <img src="<?php echo $row['SponsorLogo'] ?>" alt="Logo van een sponsor">
                    <div class="background">
                    </div>
                    <div class="hover">
                        <div class="hover-overlay">
                        </div>
                        <h3>
                            <?php echo $row['SponsorName'] ?>
                        </h3>
                    </div>
                </a>

            </div>

            <?php
        }
        ?>
    </div>
</div>
<?php
include 'includes/footer.php';


?>
</div>
</body>
</html>
