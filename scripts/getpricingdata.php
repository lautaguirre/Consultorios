<?php
    require 'connection.php';

    $arr=array(); //Set up events array

    //Load only events that ends after today
    $sql='SELECT price,title,description FROM general';
    $result=mysqli_query($conn,$sql);
    while($row=mysqli_fetch_assoc($result)){
        $obj=(object)[
            'price'=>$row['price'],
            'title'=>$row['title'],
            'desc'=>$row['description'],
        ];
        array_push($arr,$obj);
    }
    
    $json=json_encode($arr);

    echo $json;
    
?>