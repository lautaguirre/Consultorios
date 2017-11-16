<?php
    require 'connection.php';

    //Define timezone and today date
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $todaydate = date('Y-m-d H:i:s', time());
    $todaydate=substr_replace($todaydate,'T',10,1);

    //Load the events on calendar startup
    if($_POST['moment']=='onload'){

        $arr=array(); //Set up events array

        //Load only events that ends after today
        $sql='SELECT title,start,end,dni,id FROM reservas WHERE (CAST(end AS DATETIME) >= CAST("'.$todaydate.'" AS DATETIME)) AND officenumber='.$_POST['officenumber']; 
        $result=mysqli_query($conn,$sql);
        if(mysqli_num_rows($result)>0){
            while($row=mysqli_fetch_assoc($result)){
                if($_POST['evdni']==$row['dni']){
                    $obj=(object)[
                        'title'=>$row['title'],
                        'start'=>$row['start'],
                        'end'=>$row['end'],
                        'id'=>$row['id'],
                        'backgroundColor'=>'green',
                    ];
                }else{
                    $obj=(object)[
                        'title'=>$row['title'],
                        'start'=>$row['start'],
                        'end'=>$row['end'],
                        'id'=>$row['id'],
                    ];
                }
                array_push($arr,$obj);
            }
        }
        $json=json_encode($arr);
        
        echo $json;

    //Insert reservation
    }else if($_POST['moment']=='reserve'){
        $validation=true; //Check overlap
        $sql='SELECT start,end FROM reservas WHERE (CAST(end AS DATETIME) >= CAST("'.$todaydate.'" AS DATETIME)) AND officenumber='.$_POST['officenumber'];
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
            $sql='INSERT INTO reservas (title,start,end,dni,officenumber) VALUES ("'.$_POST['titleev'].'","'.$_POST['startev'].'","'.$_POST['endev'].'",'.$_POST['evdni'].','.$_POST['officenumber'].')';
            if(mysqli_query($conn,$sql)){  

                $to = 'damelakey@gmail.com';
                $subject = "Villa Martina Consultorios: Reserva creada";
                $message = "<html>
                <head>
                    <title>Villa Martina Consultorios</title>
                </head>
                <body>
                    El usuario ".$_POST['evdni']." hizo la siguiente reserva en el consultorio Nro ".$_POST['officenumber'].":
                    <p></p>
                    ".$_POST['']."
                </body>
                </html>";
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                mail($to,$subject,$message,$headers);

                echo 'Nueva reserva creada<BR>';
            }
        }else{
            echo 'Error creando reserva, parece que otro usuario ya ocupo las fechas solicitadas.<BR>';
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