<?php
    require '../pages/connection.php';

    if($_POST['moment']=='onload'){

        $arr=array();

        $sql='SELECT title,start,end FROM reservas'; //seleccionar turnos de hoy en adelante
        $result=mysqli_query($conn,$sql);
        if(mysqli_num_rows($result)>0){
            while($row=mysqli_fetch_assoc($result)){
                $obj=(object)[
                    'title'=>$row['title'],
                    'start'=>$row['start'],
                    'end'=>$row['end'],
                ];
                array_push($arr,$obj);
            }
            $json=json_encode($arr);
            
            echo $json;
        }
    }else if($_POST['moment']=='reserve'){
        $var=false;
        $sql='SELECT start,end FROM reservas';
        $result=mysqli_query($conn,$sql);
        if(mysqli_num_rows($result)>0){
            while($row=mysqli_fetch_assoc($result)){
                $validation=checkoverlap($row['start'],$row['end'],$_POST['startev'],$_POST['endev']);
                if($validation==false){
                    break;
                }
            }
        }
        if($validation==true){
            $sql='INSERT INTO reservas (title,start,end) VALUES ("'.$_POST['titleev'].'","'.$_POST['startev'].'","'.$_POST['endev'].'")';
            if(mysqli_query($conn,$sql)){        
                echo 'Nueva reserva creada<BR>';
            }
        }else{
            echo 'Error creando reserva<BR>';
        }
    }

    //Avoid overlaping function
    function checkoverlap($sttime,$enTime,$checkstarttime,$checkendtime){
        $startTime = strtotime($sttime);
        $endTime   = strtotime($enTime);
        
        $chkStartTime = strtotime($checkstarttime);
        $chkEndTime   = strtotime($checkendtime);
        
        if($chkStartTime > $startTime && $chkEndTime < $endTime)
        {	
            return false;
        }elseif(($chkStartTime > $startTime && $chkStartTime < $endTime) || ($chkEndTime > $startTime && $chkEndTime < $endTime))
        {	
            return false;
        }elseif($chkStartTime==$startTime || $chkEndTime==$endTime)
        {	
            return false;
        }elseif($startTime > $chkStartTime && $endTime < $chkEndTime)
        {	
            return false;
        }else{
            return true;
        }
    }
?>