<?php
    $to = 'villamartinaconsultorios@gmail.com';
    $subject = "CONTACTO";
    $message = "<html>
    <body>
        <h3>".$_POST['cname']." quiere contactarse con usted y le envia el siguiente mensaje:</h3>   
        <p>Email: ".$_POST['cemail']."</p>
        <p>Mensaje: ".$_POST['ccomment']."</p>
        <A>https://www.villamartinarosario.com</A>
    </body>
    </html>";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    mail($to,$subject,$message,$headers);
?>