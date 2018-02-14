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
                echo 'Desc success<br>';                                                         
            }else{
                echo 'Desc Fail<br>';
            }
        }
        if(isset($_POST['check'.$idtochange])){
            $imgcheck=sanitizeitem('check'.$idtochange);
            $sql='UPDATE images SET hide="'.$imgcheck.'" WHERE id='.$idtochange;
            if(mysqli_query($conn,$sql)){
                echo 'Check success<br>';                                                         
            }else{
                echo 'Check Fail<br>';
            }
        }else{
            $sql='UPDATE images SET hide="off" WHERE id='.$idtochange;
            if(mysqli_query($conn,$sql)){
                echo 'Check 2 success<br>';                                                         
            }else{
                echo 'Check 2 Fail<br>';
            }
        }
    }

    //Sanitize
    function sanitizeitem($itemtosanitize){
        global $conn;
        return mysqli_real_escape_string($conn,(strip_tags($_POST[$itemtosanitize])));
    }   
?>