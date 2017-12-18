<?php

    require 'connection.php';

    //Define timezone and today date
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $todaydate = date('Y-m-d H:i:s', time());
    $todaydate=substr_replace($todaydate,'T',10,1);

    $tabletext='<table>
        <thead>
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Comienzo</th>
                <th>Fin</th>
            </tr>
    </thead>';

    $sql='SELECT clientes.name,clientes.lastname,clientes.dni,reservas.start,reservas.end
    FROM clientes
    INNER JOIN reservas 
    ON clientes.dni = reservas.dni 
    WHERE (CAST(reservas.end AS DATETIME) < CAST(DATE_ADD("'.$todaydate.'",INTERVAL 1 DAY) AS DATETIME)) AND (CAST(reservas.start AS DATETIME) > CAST("'.$todaydate.'" AS DATETIME))';
    $result=mysqli_query($conn,$sql);
    while($row=mysqli_fetch_assoc($result)){

        $tabletext=$tabletext.'<tbody>
            <tr>
                <td>'.$row['dni'].'</td>
                <td>'.$row['name'].'</td>
                <td>'.$row['lastname'].'</td>
                <td>'.$row['start'].'</td>
                <td>'.$row['end'].'</td>
            </tr>
        </tbody>';
    }

    $tabletext=$tabletext."</table>";

    $to = 'villamartinaconsultorios@gmail.com';
    $subject = "CVM: Informe diario";
    $message = "<html>
    <body>
    <h3>Estas son las reservas para el dia de hoy:</h3>
    <p>
    ".$tabletext."
    </p>
    <a>https://www.villamartinarosario.com</a>
    </body>
    </html>";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: VMC <villamartinaconsultorios@gmail.com>' . "\r\n";
    mail($to,$subject,$message,$headers);

?>