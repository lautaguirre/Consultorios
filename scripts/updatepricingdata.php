<?php
    require 'connection.php';

    if(true){
        for($i=1 ; $i<=3 ; $i++){
            if(isset($_POST['datatitle'.$i])){
                $idtochange=$i;
                break;
            }
        }

        $sql='UPDATE general SET title="'.$_POST['datatitle'.$idtochange].'", price='.$_POST['dataprice'.$idtochange].', description="'.$_POST['datadesc'.$idtochange].'" WHERE id='.$idtochange;
        echo $idtochange;
        if(mysqli_query($conn,$sql)){
        }else{
            echo 'not ok';
        }
    }
?>