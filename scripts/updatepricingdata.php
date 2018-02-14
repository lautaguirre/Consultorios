<?php
    require 'connection.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        for($i=1 ; $i<=3 ; $i++){
            if(isset($_POST['datatitle'.$i])){
                $idtochange=$i;
                break;
            }
        }

        if(!empty($_POST['datatitle'.$idtochange])){
            $datatitle=sanitizeitem('datatitle'.$idtochange);
            $sql='UPDATE general SET title="'.$datatitle.'" WHERE id='.$idtochange;
            if(mysqli_query($conn,$sql)){
                echo 'Title success<br>';                                                         
            }else{
                echo 'Title Fail<br>';
            }
        }
        if(!empty($_POST['datadesc'.$idtochange])){
            $datadesc=sanitizeitem('datadesc'.$idtochange);
            $sql='UPDATE general SET description="'.$datadesc.'" WHERE id='.$idtochange;
            if(mysqli_query($conn,$sql)){
                echo 'Desc success<br>';                                                         
            }else{
                echo 'Desc Fail<br>';
            }
        }
        if(!empty($_POST['dataprice'.$idtochange])){
            $dataprice=sanitizeitem('dataprice'.$idtochange);
            $sql='UPDATE general SET price='.$dataprice.' WHERE id='.$idtochange;
            if(mysqli_query($conn,$sql)){
                echo 'Price success<br>';                                                         
            }else{
                echo 'Price Fail<br>';
            }
        }
    }

    //Sanitize
    function sanitizeitem($itemtosanitize){
        global $conn;
        return mysqli_real_escape_string($conn,(strip_tags($_POST[$itemtosanitize])));
    }   

?>