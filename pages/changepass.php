<?php
    session_start();
    $validation=true;
    
    //DB connection
    require 'connection.php';

    //Check if logged
    if(!isset($_SESSION['logged'])){
        header ("Location: ../index.php");
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Cambiar contraseña</title>
        <meta charset="UTF-8">
		<link rel="shortcut icon" href="../images/favicon.png" type="image/png">
		<link rel="stylesheet" href="../css/index.css">
    </head>

    <body>
        <?php
            //Check if logged for logout button
            require 'logoutbutton.php';
        ?>
        <script src="../templates/header.js"></script>
        <div class="content">
			<div class="container">  
                <div class='main'>
                    <form action="changepass.php" method="post">
						<div class="logincontainer">
                            <h3>Modificar contraseña</h3>
							<label><b>DNI</b></label>
							<input type="text" maxlength="9" placeholder="Ingresar su DNI" name="changepassdni" required>

							<label><b>Ingresar nueva contraseña</b> (No mas de 16 caracteres)</label>
                            <input type="password" maxlength="16" placeholder="Ingresar contraseña" name="changepass" required>
                            
                            <label><b>Repita la contraseña</b> (No mas de 16 caracteres)</label>
							<input type="password" maxlength="16" placeholder="Repetir contraseña" name="changepass2" required>

							<button class="btn" type="submit" name='changepasssubmit' >Modificar contraseña</button>
						</div>
					</form>
                </div>
                <div class='aside'>
                    <A class="btn" HREF = "login.php">Panel de usuario</A>
                    <hr>
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
                                        echo '<H1>Contraseña actualizada</H1><BR>';
                                    }
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
                                echo 'Error al insertar DNI o telefono.<br>';
                                global $validation;
                                $validation=false;
                            }
                        }
                    ?>
                </div>
			</div>
		</div>
        <script src="../templates/footer.js"></script>
    </body>
</html>