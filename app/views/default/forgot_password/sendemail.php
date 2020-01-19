<?php

$mail = new PHPMailer;
$mail->isSMTP();                            // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';             // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                     // Enable SMTP authentication
$mail->Username = '';          // SMTP username
$mail->Password = ''; // SMTP password
$mail->SMTPSecure = 'tls';                  // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                          // TCP port to connect to

$mail->setFrom('info@localhost.com', 'Admin');
$mail->addReplyTo('info@localhost.com', 'Admin');
$mail->addAddress('');   // Add a recipient
$mail->addCC('');
// $mail->addBCC('bcc@example.com');

$mail->isHTML(true);  // Set email format to HTML

$bodyContent = '<h1>How to Send Email using PHP in Localhost by Admin</h1>';
$bodyContent .= '<p>This is the HTML email sent from localhost using PHP script by <b>Admin</b></p>';

$mail->Subject = 'Email from Localhost by Admin';
$mail->Body    = $bodyContent;

$mail->smtpConnect(
    array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
            "allow_self_signed" => true
        )
    )
);

if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "E-Mail has been sent";
}
