<?php
require 'connection.php';

if(isset($_POST['userdni'])){
    $sql='SELECT name,lastname,email FROM clientes WHERE dni='.$_POST['userdni'];
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)==1){
        $row=mysqli_fetch_assoc($result);

        $obj=(object)[
            'namelastname'=>$row['name'].' '.$row['lastname'],
            'getemail'=>$row['email'],
        ];

        $json=json_encode($obj);

        echo $json;

    }
}
?>