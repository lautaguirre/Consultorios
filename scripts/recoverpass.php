<?php
    require 'connection.php';

    $validation=true;

    $recoverdni=sanitizeint('dni');
    validateint($recoverdni);

    $recoveremail=sanitizestring('email');
    $recoveremail=validateemail($recoveremail);

    if($validation){
        $newpass=randompass();
        $hashednewpass=password_hash($newpass, PASSWORD_DEFAULT);
        $sql='UPDATE clientes SET password="'.$hashednewpass.'" WHERE dni='.$recoverdni.' AND email="'.$recoveremail.'"';
        mysqli_query($conn,$sql);
        if(mysqli_affected_rows($conn)==1){

            $to = $recoveremail;
            $subject = "Consultorios Villa Martina: Recuperar contraseña";
            $message = "<html>
            <body>
                <h3>Al parecer usted solicito un cambio de contraseña, por lo tanto se genero una nueva para usted:</h3>
                <p>Nueva contraseña: ".$newpass."</p>
                <p style='color:red;'>UNA VEZ INGRESADO A SU CUENTA RECUERDE SELECCIONAR 'CAMBIAR CONTRASEÑA'</p>
            </body>
            </html>";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            mail($to,$subject,$message,$headers);

            echo '<H3>Solicitud enviada, deberia recibir un E-mail con la nueva contraseña.</H3><BR>';
        }else{
            echo '<errorspan>Error al recuperar contraseña, E-mail o DNI invalidos.</errorspan>';
        }

    }

    function randompass() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $pass = array(); 
        $alphalength = strlen($alphabet) - 1;
        for ($i = 0; $i < 4; $i++) {
            $n = rand(0, $alphalength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //Turn the array into a string
    }

    function sanitizeint($inttosanitize){
        global $conn;
        return mysqli_real_escape_string($conn,(strip_tags($_POST[$inttosanitize])));
    }

    function sanitizestring($stringtosanitize){
        global $conn;
        return mysqli_real_escape_string($conn,strtolower(strip_tags($_POST[$stringtosanitize])));
    }

    function validateemail($emailtovalidate){
        $emailtovalidate=filter_var($emailtovalidate, FILTER_SANITIZE_EMAIL);
        if (!filter_var($emailtovalidate, FILTER_VALIDATE_EMAIL)){
            echo '<errorspan>Error al insertar E-mail</errorspan><br>';
            global $validation;
            $validation=false;
            $emailtovalidate='';
            return $emailtovalidate;
        }else{
            return $emailtovalidate;
        }                        
    }

    function validateint($inttovalidate){
        if (!filter_var($inttovalidate, FILTER_VALIDATE_INT)) {
            echo '<errorspan>Error al insertar DNI o telefono</errorspan><br>';
            global $validation;
            $validation=false;
        }
    }
?>