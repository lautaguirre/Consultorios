<?php
    if($_POST['moment']=='reserve' and isset($_POST['officenumber']) and isset($_POST['evjson']) and isset($_POST['titleev']) and isset($_POST['evdni'])){
        $evarray=json_decode($_POST['evjson']);
        $lenght=count($evarray);
        for($arrpos=0;$arrpos<$lenght;$arrpos++){
            $sql='SELECT start,end FROM reservas WHERE (CAST(end AS DATETIME) >= CAST("'.$todaydate.'" AS DATETIME)) AND officenumber='.$_POST['officenumber'];
            $result=mysqli_query($conn,$sql);
            if(mysqli_num_rows($result)>0){
                while($row=mysqli_fetch_assoc($result)){
                    $validation=checkoverlap($row['start'],$row['end'],$evarray[$arrpos]->evstart,$evarray[$arrpos]->evend,$todaydate);
                    if($validation==false){
                        break 2;
                    }
                }
            }
        }

        $createtitle=sanitizestring('titleev');
        validateaddress($createtitle);

        if($validation==true){
            for($arrpos=0;$arrpos<$lenght;$arrpos++){
                $sql='INSERT INTO reservas (title,start,end,dni,officenumber) VALUES ("'.$_POST['titleev'].'","'.$evarray[$arrpos]->evstart.'","'.$evarray[$arrpos]->evend.'",'.$_POST['evdni'].','.$_POST['officenumber'].')';
                mysqli_query($conn,$sql);
            }
            echo '<div class="alert alert-success"><strong>Exito!</strong> Nueva reserva creada.</div><BR>';
        }else{
            echo '<div class="alert alert-warning"><strong>Atencion!</strong> Error creando reserva, parece que otro usuario ya ocupo las fechas solicitadas, la descripcion no es valida (Ingrese solo letras y numeros, no se permiten caracteres del tipo [.,;/-]) o solicito fechas anteriores al dia de hoy.</div><BR>';
        }
    }
?>