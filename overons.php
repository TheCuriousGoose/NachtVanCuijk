<?php
/*
 * Gemaakt door: Justin Lama Perez
 */
include 'includes/header.php';
include 'includes/nav.php';
include 'lib/db-functions.php';

?>

    <div class="overons-wrapper">
        <div class="frontpage">
            <div class="background-front">
                <div class="overlay">
                </div>
                <img src="images/section_digiboard_with_people_around.jpg" alt="Mensen rondom een digibord">
            </div>
            <div class="text">
                <h1>OVER ONS</h1>
                <h2>WAT IS DE NACHT VAN CUIJK?</h2>
            </div>
        </div>
        <section>
            <div class="about-nachtvancuijk">
                <h2>
                    NACHT VAN CUIJK
                </h2>
                <p>
                    Nacht van cuijk is een evenement dat 2 keer per jaar word georganiseerd door de leerlingen van
                    Koning Willem 1 College in cuijk. Het doel van dit event is om leerlingen samen te brengen door een
                    gesamelijke hobby. Dit doen we door de LAN Party nacht van cuijk te orginiseren. Iedereen is welkom
                    om langs te komen en mee te doen.
                </p>
            </div>
        </section>
        <hr>
        <div class="timeline-section">
            <div class="timeline-header">
                <h2>Tijdlijn</h2>
                <p>In deze tijdlijn kan je de grote punten die nacht van cuijk heeft bereikt zien.</p>
            </div>
            <div class="timeline-wrapper">
                <div class="timeline-flex-container">

                    <?php
                    startConnection('NachtVanCuijk');

                    $query = 'select * from Timeline';
                    $number = 0;
                    $result = executeQuery($query, '');

                    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                        $resultnumber = ($number % 2 == 0) ? 'even' : 'odd'; ?>
                        <div class="timeline-point <?php echo $resultnumber ?>">
                            <div class="timeline-image">
                                <img src="<?php echo $row["img"] ?>" alt="Afbeelding van de tijdlijn">
                            </div>

                            <div class="timeline-text">
                                <p class="year"><?php echo $row["Year"]->format("Y") ?></p>
                                <h4 class="timeline-title"><?php echo $row["Title"] ?></h4>
                                <h4 class="timeline-subheading"><?php echo $row["TimelineName"] ?></h4>
                            </div>
                        </div>
                        <?php
                        $number++;
                    }
                    ?>
                    <div class="timeline-line">
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
include 'includes/footer.php';
