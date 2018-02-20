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
                echo 'Titulo actualizado. ';                                                         
            }else{
                echo 'ERROR AL ACTUALIZAR EL TITULO. ';
            }
        }
        if(!empty($_POST['datadesc'.$idtochange])){
            $datadesc=sanitizeitem('datadesc'.$idtochange);
            $sql='UPDATE general SET description="'.$datadesc.'" WHERE id='.$idtochange;
            if(mysqli_query($conn,$sql)){
                echo 'Descripcion actualizada. ';                                                         
            }else{
                echo 'ERROR AL ACTUALIZAR LA DESCRIPCION. ';
            }
        }
        if(!empty($_POST['dataprice'.$idtochange])){
            $dataprice=sanitizeitem('dataprice'.$idtochange);
            $sql='UPDATE general SET price='.$dataprice.' WHERE id='.$idtochange;
            if(mysqli_query($conn,$sql)){
                echo 'Precio actualizado. ';                                                         
            }else{
                echo 'ERROR AL ACTUALIZAR EL PRECIO. ';
            }
        }
        if(empty($_POST['datatitle'.$idtochange]) && empty($_POST['datadesc'.$idtochange]) && empty($_POST['dataprice'.$idtochange]) ){
            echo 'No hay informacion para actualizar.';
        }
    }

    //Sanitize
    function sanitizeitem($itemtosanitize){
        global $conn;
        return mysqli_real_escape_string($conn,(strip_tags($_POST[$itemtosanitize])));
    }   

?>