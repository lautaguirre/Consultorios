<?php
    $scomment=strip_tags($_POST['ccomment']);
    $semail=strip_tags($_POST['cemail']);
    $sname=strip_tags($_POST['cname']);

    $to = 'villamartinaconsultorios@gmail.com';
    $subject = "CONTACTO";
    $message = "<html>
    <body>
        <h3>".$sname." quiere contactarse con usted y le envia el siguiente mensaje:</h3>   
        <p>Email: ".$semail."</p>
        <p>Mensaje: ".$scomment."</p>
        <A>https://www.villamartinarosario.com</A>
    </body>
    </html>";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    mail($to,$subject,$message,$headers);

?>