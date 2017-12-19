<?php
if(isset($_POST['clickevjson'])){
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
            'msg'=>'<div class="alert alert-success"><strong>Exito!</strong> Reserva borrada, se le envio un email al usuario notificandolo.</div><BR>',
            'useremail'=>$useremail,
        ]; 

        $json=json_encode($obj);
        echo $json;
    }else{
        echo '<div class="alert alert-warning"><strong>Atencion!</strong> Error borrando reserva, puede que haya elegido varios usuarios diferentes.</div><BR>';
    }
}
?>