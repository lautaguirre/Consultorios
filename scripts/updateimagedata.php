<?php
    require 'connection.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        for($i=1 ; $i<=6 ; $i++){
            if(isset($_POST['imgdesc'.$i])){
                $idtochange=$i;
                break;
            }
        }

        if(!empty($_POST['imgdesc'.$idtochange])){
            $imgdesc=sanitizeitem('imgdesc'.$idtochange);
            $sql='UPDATE images SET imgdesc="'.$imgdesc.'" WHERE id='.$idtochange;
            if(mysqli_query($conn,$sql)){
                echo 'Descripcion actualizada. ';                                                         
            }else{
                echo 'ERROR AL ACTUALIZAR DESCRIPCION. ';
            }
        }else{
            echo 'No hay informacion para actualizar. ';
        }
    }

    //Sanitize
    function sanitizeitem($itemtosanitize){
        global $conn;
        return mysqli_real_escape_string($conn,(strip_tags($_POST[$itemtosanitize])));
    }   
?>