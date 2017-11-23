<?php
    require 'connection.php';

    $evarray=json_decode($_POST['clickevjson']);
    $lenght=count($evarray);
    $aux='';
    $validation=true;

    for($arrpos=0;$arrpos<$lenght;$arrpos++){
        $sql='SELECT clientes.email 
        FROM clientes
        INNER JOIN reservas 
        ON clientes.dni = reservas.dni 
        WHERE (reservas.start="'.$evarray[$arrpos]->evstart.'") AND (reservas.end="'.$evarray[$arrpos]->evend.'") AND reservas.officenumber='.$_POST['officenumber'];
        $result=mysqli_query($conn,$sql);
        $row=mysqli_fetch_assoc($result);
        $useremail=$row['email'];
        if($arrpos==0){
            $aux=$useremail;
        }else{
            if($aux!=$useremail){
                $validation=false;
                break;
            }
        }
    }

    if($validation){
        for($arrpos=0;$arrpos<$lenght;$arrpos++){
            $sql='DELETE FROM reservas WHERE (start="'.$evarray[$arrpos]->evstart.'") AND (end="'.$evarray[$arrpos]->evend.'") AND officenumber='.$_POST['officenumber'];
            mysqli_query($conn,$sql);  
        }
        $obj=(object)[
            'msg'=>'Reserva borrada con exito, se le envio un mail al usuario notificandolo.<BR>',
            'useremail'=>$useremail,
        ]; 

        $json=json_encode($obj);
        echo $json;
    }else{
        echo '<errorspan>Error borrando reserva, puede que haya elegido varios usuarios diferentes<errorspan><BR>';
    }
?>