<?php
    session_start();
    $logindni=$loginpass='';
    $validation=true;

    //DB connection
    require 'connection.php';
    
    if(!isset($_SESSION['logged'])){
        if($_SERVER['REQUEST_METHOD']=='POST'){

            //Validation 
            $loginpass=sanitizestring('loginpass');
            $logindni=sanitizeint('logindni');

            validateint($logindni);

            //Check user query
            if($validation){
                $sql='SELECT password,authorization FROM clientes WHERE dni='.$logindni;
                $result=mysqli_query($conn,$sql);
                if(mysqli_num_rows($result)==1){
                    $row=mysqli_fetch_assoc($result);
                    if(password_verify($loginpass,$row['password'])){
                        if($row['authorization']=='si'){
                            $_SESSION['logged']=$logindni;
                        }
                        if($logindni==38333166){
                            $_SESSION['admin']=true;
                        }
                    }
                }
            }
        }
    }

    //Check if logged
    if(!isset($_SESSION['logged'])){
        header ("Location: ../index.php");
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

<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
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
                    <h1>Bienvenido usuario<?php echo ' '.$_SESSION['logged'].'<BR>';?></h1>
                </div>
                <div class='aside'>
                    <form action="changepass.php">
                        <button type="submit" class="btn" name="changepass">Cambiar contraseña</button>
                    </form>
                    <p></p>
                    <?php
                        if(isset($_SESSION['admin'])){
                            echo '<form action="admin.php">
                                <button type="submit" class="btn" name="gotoadmin">Panel de administrador</button>
                            </form>';
                        }
                    ?>               
                </div>
			</div>
		</div>
        <script src="../templates/footer.js"></script>
    </body>
</html>