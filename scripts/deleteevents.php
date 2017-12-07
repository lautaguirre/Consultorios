<?php
    require 'connection.php';

    $evarray=json_decode($_POST['clickevjson']);
    $lenght=count($evarray);
    for($arrpos=0;$arrpos<$lenght;$arrpos++){
        $sql='DELETE FROM reservas WHERE (start="'.$evarray[$arrpos]->evstart.'") AND (end="'.$evarray[$arrpos]->evend.'") AND (officenumber='.$_POST['officenumber'].') AND dni='.$_POST['deletedni'];
        mysqli_query($conn,$sql);
    }
    echo '<div class="alert alert-success"><strong>Exito!</strong> Reserva borrada.</div><BR>';
?>