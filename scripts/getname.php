<?php
    require 'connection.php';

    $sql='SELECT name,lastname FROM clientes WHERE dni='.$_POST['userdni'];
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)==1){
        $row=mysqli_fetch_assoc($result);

        echo 'Bienvenido '.$row['name'].' '.$row['lastname'];
    }
?>