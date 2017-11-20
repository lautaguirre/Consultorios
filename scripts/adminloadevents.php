<?php
    require 'connection.php';

    //Define timezone and today date
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $todaydate = date('Y-m-d H:i:s', time());
    $todaydate=substr_replace($todaydate,'T',10,1);

    if($_POST['moment']=='onload'){
        
        $arr=array(); //Set up events array
        
        //Load only events that ends after today
        $sql='SELECT title,start,end,dni,id FROM reservas WHERE (CAST(end AS DATETIME) >= CAST(DATE_SUB("'.$todaydate.'",INTERVAL 60 DAY) AS DATETIME)) AND officenumber='.$_POST['officenumber']; 
        $result=mysqli_query($conn,$sql);
        if(mysqli_num_rows($result)>0){
            while($row=mysqli_fetch_assoc($result)){
                if($_POST['evdni']==$row['dni']){
                    $obj=(object)[
                        'title'=>$row['dni'].' - '.$row['title'],
                        'start'=>$row['start'],
                        'end'=>$row['end'],
                        'id'=>$row['id'],
                        'backgroundColor'=>'green',
                    ];
                }else{
                    $obj=(object)[
                        'title'=>$row['dni'].' - '.$row['title'],
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
    }
?>