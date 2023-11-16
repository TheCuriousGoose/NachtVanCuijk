<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

function sendConfirmationMail($emailAdress, $userName, $classroom, $date, $time, $price): bool
{

    global $config;
    $mail = new PHPMailer(true);

    if ($classroom == 'noclassroom') {
        $addClassroom = '';
    } else {
        $addClassroom = 'In lokaal ' . $classroom . '.';
    }


    $signUpEmail = '
    <html>
        <head>
            <meta http-equiv="x-ua-compatible" content="ie=edge">
            <meta name="x-apple-disable-message-reformatting">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <style>
                table {
                    width: 600px;
                    background-color: #1F1B48;
                    padding: 20px 30px;
                }
                tr {
                    height: auto;

                }
                tr > td:not(:nth-of-type(2)){
                    width: 20px;
                }
                td {
                    text-align: center;
                    color: white;
                    font-size: 18px;
                    font-family: "Roboto Slab", sans-serif;
                    margin: 20px 0;
                }
                .header-text {
                    font-size: 55px;
                    color: white;
                    background-color: #1F1B48;
                    vertical-align: center;
                    font-family: "Montserrat", sans-serif;
                }
                
            </style>
        </head>
        <body>
        <table>
            <tr>
                <td class="center" align="center" valign="top">
                    <center>
                    <table>
                            <tr class="header">
                                <th class="header-text">NACHT VAN CUIJK</th>
                            </tr>
                        <hr>
                            <tr>
                                <td>
                                    Beste, ' . $userName . '
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Bedankt dat je je hebt ingeschreven voor de Nacht van Cuijk!
                                </td>
                            </tr>
                            <br>
                            <tr>
                                <td>
                                    Graag zien wij <strong>jou</strong> op <strong>' . $date . '</strong> vanaf <strong>' . $time . '</strong> uur op Jan van Cuijkstraat 52, 5431 GC Cuijk. ' . $addClassroom . ' 
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Heb je gekozen voor de frietkar van &euro;' . $price . '? Neem dit dan contant mee als je gaat inchecken!
                                </td>
                            </tr>
                            <br>
                            <tr>
                                <td>
                                    Met vriendelijke groeten, <br>
                                    Team Nacht van Cuijk
                                </td>
                            </tr>
                            <br>
                            <tr>
                                <td>
                                    Voor meer over Nacht van Cuijk, <br>
				    <a href="https://discord.gg/3zhYPS3gZM">Join onze discord</a>
                                </td>
                            </tr>
                            <br>
                            <tr>
                                <td>
                                   <a href="nachtvancuijk.nl">nachtvancuijk.nl</a><br> 
                                   <a href="mailto:info@denachtvancuijk.nl">info@denachtvancuijk.nl</a>
                                </td>
                            </tr>
                    </table>
                    </center>
                </td>
            </tr>
        </table>
            
        </body>
        ';

    $signUpEmailplain = 'Beste, ' . $userName . ' 
Bedankt dat je je hebt ingeschreven voor de Nacht van Cuijk!
Graag zien wij jou op ' . $date . ' vanaf ' . $time . ' uur op Jan van Cuijkstraat 52, 5431 GC Cuijk. ' . $addClassroom . '
Heb je gekozen voor de frietkar van €7? Neem dit dan contant mee als je gaat inchecken!

Met vriendelijke groeten, 
Team Nacht van Cuijk

denachtvancuijk.nl
info@denachtvancuijk.nl
        ';

    try {
        //Server settings
        $mail->isSMTP();
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => false
            )
        );
        $mail->Host = $config::EMAIL_SERVER;                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = $config::EMAIL_USERNAME;                     //SMTP username
        $mail->Password = $config::EMAIL_PASSWORD;                               //SMTP password
//        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        //Enable implicit TLS encryption
        //$mail->SMTPAutoTLS = false;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('info@denachtvancuijk.nl', 'Nacht Van Cuijk');
        $mail->addAddress($emailAdress, $userName);     //Add a recipient

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Nacht van Cuijk - Inschrijving';
        $mail->Body = $signUpEmail;
        $mail->AltBody = $signUpEmailplain;

        $mail->send();
        return true;
    } catch (Exception $e) {
        //echo $e;
    }

    return false;
}


