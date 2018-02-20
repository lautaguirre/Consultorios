<?php
    require 'connection.php';

    $arr=array(); //Set up events array

    //Load only events that ends after today
    $sql='SELECT imgdesc FROM images';
    $result=mysqli_query($conn,$sql);
    while($row=mysqli_fetch_assoc($result)){
        $obj=(object)[
            'imgdesc'=>$row['imgdesc'],
        ];
        array_push($arr,$obj);
    }
    
    $json=json_encode($arr);

    echo $json;
    
?>