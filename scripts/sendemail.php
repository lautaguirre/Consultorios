<?php
if($_POST['emailbody']!=''){

    $to = 'damelakey@gmail.com';
    $subject = "Consultorios Villa Martina: Reserva creada";
    $message = "<html>
    <body>
        <h3>El usuario ".$_POST['emailuser']." (DNI: ".$_POST['emaildni'].") hizo la siguiente reserva en el consultorio Nro ".$_POST['emailoffice'].":</h3>
        <p></p>
        ".$_POST['emailbody']."
    </body>
    </html>";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    mail($to,$subject,$message,$headers);
}
?>