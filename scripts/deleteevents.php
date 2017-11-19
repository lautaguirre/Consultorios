<?php
    require 'connection.php';
    
    $sql='DELETE FROM reservas WHERE (start="'.$_POST['startev'].'") AND (end="'.$_POST['endev'].'") AND (officenumber='.$_POST['officenumber'].') AND dni='.$_POST['deletedni'];
    if(mysqli_query($conn,$sql)){;
        if(mysqli_affected_rows($conn)==1){        
            echo 'Reserva borrada con exito<BR>';
        }
    }else{
        echo '<errorspan>Error borrando reserva<errorspan><BR>';
    }
?>