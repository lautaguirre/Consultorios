<?php
if(isset($_POST['deleteemail']) and isset($_POST['deleteemailbody'])){

    $to = $_POST['deleteemail'];
    $subject = "Consultorios Villa Martina: Evento eliminado";
    $message = "<html>
    <body>
    <h3>La siguiente reserva/s en Consultorios Villa Martina fue cancelada:</h3>
    <p></p>
    ".$_POST['deleteemailbody']."
    <p></p>
    <a>https://www.villamartinarosario.com</a>
    </body>
    </html>";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: VMC <villamartinaconsultorios@gmail.com>' . "\r\n";
    mail($to,$subject,$message,$headers);
    
}
?>