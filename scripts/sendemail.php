<?php
if($_POST['emailbody']!='<table class="table table-hover"><thead><tr><th>#</th><th>Comienzo</th><th>Fin</th></tr></thead>'){

    if(isset($_POST['emailbody']) and isset($_POST['emailuser']) and isset($_POST['emaildni']) and isset($_POST['emailoffice']) and isset($_POST['emailemail'])){

    $to = 'villamartinaconsultorios@gmail.com';
    $subject = "Reserva creada";
    $message = "<html>
    <body>
        <h3>El usuario ".$_POST['emailuser']." (DNI: ".$_POST['emaildni'].") hizo la siguiente reserva en el consultorio Nro ".$_POST['emailoffice'].":</h3>
        <p></p>
        ".$_POST['emailbody']."
        <p></p>
        <A>https://www.villamartinarosario.com</A>
    </body>
    </html>";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: VMC <villamartinaconsultorios@gmail.com>' . "\r\n";
    mail($to,$subject,$message,$headers);

    
    $to = $_POST['emailemail'];
    $subject = "Consultorios Villa Martina: Reserva creada";
    $message = "<html>
    <body>
        <h3>Usted: ".$_POST['emailuser']." hizo la siguiente reserva en el consultorio Nro ".$_POST['emailoffice']." de Consultorios Villa Martina:</h3>
        <p></p>
        ".$_POST['emailbody']."
        <p></p>
        <a>https://www.villamartinarosario.com</a>
    </body>
    </html>";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: VMC <villamartinaconsultorios@gmail.com>' . "\r\n";
    mail($to,$subject,$message,$headers);

    }
}
?>