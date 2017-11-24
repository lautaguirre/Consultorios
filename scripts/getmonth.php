<?php
    require 'connection.php';

    $arr=array(); //Set up events array
    
    if($_POST['getmonthdni']==''){
        $sql='SELECT title,start,end,officenumber FROM reservas WHERE (CAST(start AS DATETIME) > CAST("'.$_POST['getmonthstart'].'" AS DATETIME)) AND (CAST(end AS DATETIME) < CAST("'.$_POST['getmonthend'].'" AS DATETIME)) ORDER BY start ASC';
        $result=mysqli_query($conn,$sql);
        if(mysqli_num_rows($result)>0){
            while($row=mysqli_fetch_assoc($result)){
                $obj=(object)[
                    'title'=>$row['title'],
                    'start'=>$row['start'],
                    'end'=>$row['end'],
                    'monthofficenumber'=>$row['officenumber'],
                ];
                array_push($arr,$obj);
            }
        }
    }else{
        $sql='SELECT title,start,end,officenumber FROM reservas WHERE (CAST(start AS DATETIME) > CAST("'.$_POST['getmonthstart'].'" AS DATETIME)) AND (CAST(end AS DATETIME) < CAST("'.$_POST['getmonthend'].'" AS DATETIME)) AND dni='.$_POST['getmonthdni'].' ORDER BY start ASC';
        $result=mysqli_query($conn,$sql);
        if(mysqli_num_rows($result)>0){
            while($row=mysqli_fetch_assoc($result)){
                $obj=(object)[
                    'title'=>$row['title'],
                    'start'=>$row['start'],
                    'end'=>$row['end'],
                    'monthofficenumber'=>$row['officenumber'],
                ];
                array_push($arr,$obj);
            }
        }
    }
    $json=json_encode($arr);

    echo $json;
?>