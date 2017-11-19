<?php
    require 'connection.php';

    $sql='SELECT clientes.email 
    FROM clientes
    INNER JOIN reservas 
    ON clientes.dni = reservas.dni 
    WHERE (reservas.start="'.$_POST['startev'].'") AND (reservas.end="'.$_POST['endev'].'") AND reservas.officenumber='.$_POST['officenumber'];
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)==1){
        $row=mysqli_fetch_assoc($result);

        $useremail=$row['email'];
    }
    
    $sql='DELETE FROM reservas WHERE (start="'.$_POST['startev'].'") AND (end="'.$_POST['endev'].'") AND officenumber='.$_POST['officenumber'];
    if(mysqli_query($conn,$sql)){;
        if(mysqli_affected_rows($conn)==1){    

            $obj=(object)[
                'msg'=>'Reserva borrada con exito, se le envio un mail al usuario notificandolo.<BR>',
                'useremail'=>$useremail,
            ]; 

            $json=json_encode($obj);

            echo $json;
        }
    }else{
        echo '<errorspan>Error borrando reserva<errorspan><BR>';
    }
?>