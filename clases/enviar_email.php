<?php

use PHPMailer\PHPMailer\{PHPMailer, SMTP, Exception};
 
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';

$mail = new PHPMailer(true);

try {
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer'=>false,
            'verify_peer_name' => false,
            'allow_sel_signed' => true
        )
    );
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; //SMTP::DEBUG_OFF;                     //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp-mail.outlook.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'leandro.magallanes@outlook.com';                     //SMTP username
    $mail->Password   = 'Nahiara_1991';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('leandro.magallanes@outlook.com', 'VENTAS Le-Mark');
    $mail->addAddress('thesharkcommunity@outlook.com', 'COMPRAS Le-Mark');     //Add a recipient
    $mail->addReplyTo('leandro.magallanes@outlook.com');
    
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Detalles de su compra';
    $cuerpo = '<h4>Gracias por su compra</h4>';
    $cuerpo .= '<p>El ID de su compra es <b>' . $id_transaccion . '</b></p>';

    $mail->Body    = utf8_decode($cuerpo);
    $mail->AltBody = 'Le enviamos los detalles de su compra.';

    $mail->setLanguage('es','../phpmailer/language/phpmailer.lang-es.php');

    $mail->send();
    
} catch (Exception $e) {
    echo "Error al enviar correo electronico de la compra: {$mail->ErrorInfo}";
    
} 