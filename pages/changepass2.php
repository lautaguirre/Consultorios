<?php
    session_start();
    $validation=true;
    
    //DB connection
    require '../scripts/connection.php';

    //Check if logged
    if(!isset($_SESSION['logged'])){
        header ("Location: ../index.php");
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>Cambiar contraseña</title>
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../images/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/index2.css" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
        <!-- Navbar section-->
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header navbar-left">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" id='collapseitems' href="#myPage">Consultorios Villa Martina</a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="../index.php" id='collapseitems'>INICIO</a>
                        </li>
                        <li>
                            <a href="login.php" id="collapseitems" class="alterlogo">
                                <span class="glyphicon glyphicon-user"></span>
                                PANEL DE USUARIO
                            </a>
                        </li>
                        <li>
                            <a class="alterlogo2" href="../scripts/logout.php">
                                <span class="glyphicon glyphicon-log-out"></span>
                                CERRAR SESION
                            </a>
                        </li>
                        <?php
                            if(isset($_SESSION['admin'])){
                                echo '<li>
                                    <a href="admin.php" id="collapseitems" class="alterlogo">
                                        <span class="glyphicon glyphicon-eye-open"></span>
                                        ADMIN
                                    </a>
                                </li>';
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
        
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3">
                    
                </div>
                <div class="col-sm-3">
                    <p>&nbsp;</p>
                    <form action="changepass2.php" method="post">
                        <div class="form-group">
                            <label for="changepassdni">DNI :</label>
                            <input type="text" maxlength="9" placeholder="Ingrese su DNI" class="form-control" id='changepassdni' name="changepassdni" required>
                        </div>
                        <div class="form-group">
                            <label for="changepass">Ingresar nueva contraseña (No mas de 16 caracteres) :</label>
                            <input type="password" name="changepass" required placeholder="Ingresar nueva contraseña" maxlength="16" class="form-control" id="changepass">
                        </div>
                        <div class="form-group">
                            <label for="changepass2">Repita la contraseña :</label>
                            <input type="password" name="changepass2" required placeholder="Repetir contraseña" maxlength="16" class="form-control" id="changepass2">
                        </div>
                        <button type="submit" name='changepasssubmit' class="btn btn-success">Cambiar contraseña</button>
                    </form>
                </div>
                <div class="col-sm-3">
                    <p>&nbsp;</p>
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
                </div>
                <div class="col-sm-3">
                     
                </div>
            </div>
        </div>

        <!-- Footer --> 
        <script src="../templates/footer2.js"></script>
</body>