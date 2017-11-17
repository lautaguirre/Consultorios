<?php
    require 'connection.php';
    
    $sql='DELETE FROM reservas WHERE (start="'.$_POST['startev'].'") AND (end="'.$_POST['endev'].'") AND officenumber='.$_POST['officenumber'];
    if(mysqli_query($conn,$sql)){        
        echo 'Reserva borrada con exito<BR>';
    }else{
        echo 'Error borrando reserva<BR>';
    }
?>