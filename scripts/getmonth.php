<?php
    require 'connection.php';

    $validation=true;
    $arr=array(); //Set up events array
    $getmonthdni=sanitizeint('getmonthdni');
    validateint($getmonthdni);
    
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
        if($validation){
            $sql='SELECT title,start,end,officenumber FROM reservas WHERE (CAST(start AS DATETIME) > CAST("'.$_POST['getmonthstart'].'" AS DATETIME)) AND (CAST(end AS DATETIME) < CAST("'.$_POST['getmonthend'].'" AS DATETIME)) AND dni='.$getmonthdni.' ORDER BY start ASC';
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
    }
    $json=json_encode($arr);

    echo $json;


    function validateint($inttovalidate){
        if (!filter_var($inttovalidate, FILTER_VALIDATE_INT)) {
            global $validation;
            $validation=false;
        }
    }

    function sanitizeint($inttosanitize){
        global $conn;
        return mysqli_real_escape_string($conn,(strip_tags($_POST[$inttosanitize])));
    }
?>