<?php
    //Change password
    if(isset($_POST['changepasssubmit'])){
        //Validation
        $changepass=sanitizestring('changepass');
        $changepassdni=sanitizeint('changepassdni');
        $changepass2=sanitizestring('changepass2');

        validateint($changepassdni);

        if($validation){
            if($changepass==$changepass2 and $changepassdni==$_SESSION['logged']){
                $hashednewpass=password_hash($changepass, PASSWORD_DEFAULT);
                $sql='UPDATE clientes SET password="'.$hashednewpass.'" WHERE dni='.$changepassdni;
                if(mysqli_query($conn,$sql)){
                    echo '<div class="alert alert-success">
                        Contraseña actualizada correctamente.
                    </div><BR>';
                }else{
                    echo '<div class="alert alert-danger">
                        Error al actualizar contraseña.
                    </div><BR>';
                }
            }else{
                echo '<div class="alert alert-danger">
                    La contraseña debe ser igual en ambos campos.
                </div><BR>';
            }
        }
    }

    //Sanitize
    function sanitizeint($inttosanitize){
        global $conn;
        return mysqli_real_escape_string($conn,(strip_tags($_POST[$inttosanitize])));
    }

    function sanitizestring($stringtosanitize){
        global $conn;
        return mysqli_real_escape_string($conn,(strip_tags($_POST[$stringtosanitize])));
    }       
            
    //Validate
    
    function validateint($inttovalidate){
        if (!filter_var($inttovalidate, FILTER_VALIDATE_INT)) {
            echo '<div class="alert alert-danger">
                Error al insertar DNI.
            </div><BR>';
            global $validation;
            $validation=false;
        }
    }
?>      