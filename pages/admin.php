<?php
    //Check session
    session_start();
    if(!isset($_SESSION['admin'])){
        header('Location: ../index.php');
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Admin Panel</title>
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
                    <ul class='list-inline'>
                        <li>
                            <form action="admin.php" method='post'>
                                <ul class='list-unstyled'>                            
                                    <li><h3>Crear usuario</h3></li>
                                    <li><input type="text" name='createname' required placeholder='Nombre'></li>
                                    <li><input type="text" name='createlastname' required placeholder='Apellido' ></li>
                                    <li><input type="email" name='createemail' required placeholder='E-mail' ></li>
                                    <li><input type="text" name='createphone'required placeholder='Telefono' ></li>   
                                    <li><input type="text" maxlength="9" name='createdni' required placeholder='DNI' ></li>
                                    <li><input type="text" name='createaddress' required placeholder='Domicilio' ></li>
                                    <li><input type="submit" class='btn' value="Crear" name='createsubmit'></li>                               
                                </ul>
                            </form>
                        </li>
                        <li>
                            <form action="admin.php" method='post'>
                                <ul class='list-unstyled'>
                                    <li><h3>Actualizar usuario</h3></li>
                                    <li><input type="text" maxlength="9" name='actdni' required placeholder='DNI de usuario a actualizar'></li>
                                    <li><input type="text" name='actphone' placeholder='Telefono'></li>
                                    <li><input type="email" name='actemail' placeholder='E-mail'></li>
                                    <li><input type="text" name='actaddress' placeholder='Domicilio' ></li>
                                    <li><input type="text" disabled></li>
                                    <li><input type="text" disabled></li>
                                    <li><input type="submit" class='btn' value="Actualizar" name='actsubmit'></li>
                                </ul>
                            </form>
                        </li>
                        <li>
                            <form action="admin.php" method='post'>
                                <ul class='list-unstyled'>
                                    <li><h3>Inhabilitar usuario</h3></li>
                                    <li><input type="text" maxlength="9" name='unauthdni' required placeholder='DNI de usuario a deshabilitar'></li>
                                    <li><input type="text" disabled></li>
                                    <li><input type="submit" class='btn' value="Inhabilitar" name='unauthsubmit'></li>
                                </ul>
                            </form>
                                <hr>
                            <form action="admin.php" method='post'>
                                <ul class='list-unstyled'>
                                    <li><h3>Habilitar</h3></li>
                                    <li><input type="text" maxlength="9" name='authdni' required placeholder='DNI de usuario a habilitar'></li>
                                    <li><input type="text" disabled></li>
                                    <li><input type="submit" class='btn' value="Habilitar" name='authsubmit'></li>
                                </ul>
                            </form>
                        </li>
                        <li>
                            <form action="admin.php" method='post'>
                                <ul class='list-unstyled'>             
                                    <li><h3>Consultar usuario</h3></li>
                                    <li><input type="text" name='consultname' placeholder='Nombre'></li>
                                    <li><input type="text" name='consultlastname' placeholder='Apellido'></li>
                                    <li><input type="text" maxlength="9" name='consultdni' placeholder='DNI'></li>
                                    <li><input type="text" disabled></li>
                                    <li><input type="text" disabled></li>
                                    <li><input type="text" disabled></li>
                                    <li><input type="submit" class='btn' value="Consultar" name='consultsubmit'></li>
                                </ul>
                            </form>
                        </li>
                    </ul>
                </div>
                <div class='aside'>
                    <form action="login.php">
                        <button type="submit" class="btn" name="gotologin">Panel de usuario</button>
                    </form>
                    <hr>
                <?php 
                    //Set variables
                    $createname=$createlastname=$createemail=$createphone=$createdni='';
                    $actdni=$actphone=$actemail='';
                    $consultname=$consultlastname=$consultdni='';
                    $tabletext='';
                    $allinfoneeded=false;
                    $validation=true;

                    if ($_SERVER["REQUEST_METHOD"] == "POST"){
                        //DB connection
                        require 'connection.php';

                        //Create user
                        if(isset($_POST["createsubmit"])){

                            //Escape input
                            $createname=sanitizestring('createname');
                            $createlastname=sanitizestring('createlastname');
                            $createemail=sanitizestring('createemail');
                            $createphone=sanitizeint('createphone');
                            $createdni=sanitizeint('createdni');
                            $createaddress=sanitizestring('createaddress');

                            //Validate name and lastname
                            validatestring($createname);
                            validatestring($createlastname);

                            //Validate email
                            $createemail=validateemail($createemail);

                            //Validate phone and dni
                            validateint($createphone);
                            validateint($createdni);

                            //Generate password
                            $createpass=randompass();
                            echo 'Pass: '.$createpass.'<p></p>'; //Send email to client
                            $hashedpass=password_hash($createpass, PASSWORD_DEFAULT);

                            //Query
                            if($validation){
                                $sql='INSERT INTO clientes (name,lastname,email,address,phone,dni,password) VALUES ("'.$createname.'","'.$createlastname.'","'.$createemail.'","'.$createaddress.'","'.$createphone.'","'.$createdni.'","'.$hashedpass.'")';
                                if(mysqli_query($conn,$sql)){        
                                    echo '<h3>Nuevo usuario creado</H3><BR>';
                                    $tabletext=$tabletext."<table border='1'>";
                                    $tabletext=$tabletext."<tr><td>DNI: ".$createdni."</td></tr><tr><td>Nombre: ".$createname."</td></tr><tr><td>Apellido: ".$createlastname."</td></tr><tr><td>E-mail: ".$createemail."</td></tr><tr><td>Domicilio: ".$createaddress."</td></tr><tr><td>Telefono: ".$createphone."</td></tr>";
                                    $tabletext=$tabletext."</table><P></P>";
                                    echo $tabletext;
                                }else{
                                    echo 'Error creando usuario<BR>';
                                }
                            }else{
                                echo 'Algun campo no es valido.<BR>';
                            } 
                        }

                        //Update user
                        if(isset($_POST['actsubmit'])){
                            $actdni=sanitizeint('actdni');
                            validateint($actdni);
                            if(!empty($_POST['actemail'])){
                                $actemail=sanitizestring('actemail');
                                $actemail=validateemail($actemail);
                                if($validation){
                                    $sql='UPDATE clientes SET email="'.$actemail.'" WHERE dni='.$actdni;
                                    if(mysqli_query($conn,$sql)){
                                        if(mysqli_affected_rows($conn)==0){
                                            echo "Error al actualizar E-mail o DNI erroneo<BR>";
                                        }else{
                                            echo 'E-mail actualizado<BR>';                                                              
                                        }
                                    }
                                }
                            }
                            if(!empty($_POST['actphone'])){
                                $actphone=sanitizeint('actphone');
                                validateint($actphone);
                                if($validation){
                                    $sql='UPDATE clientes SET phone='.$actphone.' WHERE dni='.$actdni;
                                    if(mysqli_query($conn,$sql)){
                                        if(mysqli_affected_rows($conn)==0){
                                            echo "Error al actualizar telefono o DNI erroneo<BR>";
                                        }else{
                                            echo 'Telefono actualizado<BR>';                                                              
                                        }
                                    }
                                }
                            }
                            if(!empty($_POST['actaddress'])){
                                $actaddress=sanitizestring('actaddress');
                                if($validation){
                                    $sql='UPDATE clientes SET address="'.$actaddress.'" WHERE dni='.$actdni;
                                    if(mysqli_query($conn,$sql)){
                                        if(mysqli_affected_rows($conn)==0){
                                            echo "Error al actualizar domicilio o DNI erroneo<BR>";
                                        }else{
                                            echo 'Domicilio actualizado<BR>';                                                              
                                        }
                                    }
                                }
                            }
                        }

                        //Consult
                        //Consult dni
                        if(isset($_POST['consultsubmit'])){
                            if(!empty($_POST['consultdni'])){
                                $allinfoneeded=true;
                                $consultdni=sanitizeint('consultdni');
                                validateint($consultdni);
                                if($validation){
                                    $sql='SELECT name,lastname,dni,email,phone,authorization,address FROM clientes WHERE dni='.$consultdni;
                                    $result=mysqli_query($conn,$sql);
                                    if(mysqli_num_rows($result)>0){
                                        $totalresults=0;
                                        while($row=mysqli_fetch_assoc($result)){
                                            $totalresults++;
                                            $tabletext=$tabletext."<table border='1'>";
                                            $tabletext=$tabletext."<tr><td>DNI: ".$row['dni']."</td></tr><tr><td>Nombre: ".$row['name']."</td></tr><tr><td>Apellido: ".$row['lastname']."</td></tr><tr><td>E-mail: ".$row['email']."</td></tr><tr><td>Domicilio: ".$row['address']."</td></tr><tr><td>Telefono: ".$row['phone']."</td></tr><tr><td>Habilitado: ".$row['authorization']."</td></tr>";
                                            $tabletext=$tabletext."</table><P></P>";
                                        }
                                        echo 'Cantidad de resultados: '.$totalresults.'<P>';
                                        echo $tabletext;
                                    }else{
                                        echo 'Error consultando dni<br>';
                                    }
                                }else{
                                    echo 'DNI invalido.<br>';
                                }
                            }
                            //Consult name and lastname
                            if(!empty($_POST['consultname']) and !empty($_POST['consultlastname'] and $allinfoneeded==false)){
                                $consultname=sanitizestring('consultname');
                                $consultlastname=sanitizestring('consultlastname');
                                validatestring($consultname);
                                validatestring($consultlastname);
                                if($validation){
                                    $sql='SELECT name,lastname,dni,email,phone,authorization,address FROM clientes WHERE name="'.$consultname.'" AND lastname="'.$consultlastname.'"';
                                    $result=mysqli_query($conn,$sql);
                                    if(mysqli_num_rows($result)>0){
                                        $totalresults=0;
                                        while($row=mysqli_fetch_assoc($result)){
                                            $totalresults++;
                                            $tabletext=$tabletext."<table border='1'>";
                                            $tabletext=$tabletext."<tr><td>DNI: ".$row['dni']."</td></tr><tr><td>Nombre: ".$row['name']."</td></tr><tr><td>Apellido: ".$row['lastname']."</td></tr><tr><td>E-mail: ".$row['email']."</td></tr><tr><td>Domicilio: ".$row['address']."</td></tr><tr><td>Telefono: ".$row['phone']."</td></tr><tr><td>Habilitado: ".$row['authorization']."</td></tr>";
                                            $tabletext=$tabletext."</table><P></P>";
                                        }
                                        echo 'Cantidad de resultados: '.$totalresults.'<P>';
                                        echo $tabletext;
                                    }else{
                                        echo 'Error consultando nombre o apellido<br>';
                                    }
                                }else{
                                    echo 'Nombre o apellido invalido.<br>';
                                }
                            }else if($allinfoneeded==false){
                                //Consult name or lastname
                                consultnameorlastname($consultname,'consultname','name');
                                consultnameorlastname($consultlastname,'consultlastname','lastname');
                            }
                        }

                        //Unathorize user
                        if(isset($_POST['unauthsubmit'])){
                            $unauthdni=sanitizeint('unauthdni');
                            validateint($unauthdni);
                            if($validation){
                                $sql='UPDATE clientes SET authorization="no" WHERE dni='.$unauthdni;
                                if(mysqli_query($conn,$sql)){
                                    if(mysqli_affected_rows($conn)==0){
                                        echo "Error al deshabilitar<BR>";
                                    }else{
                                        echo 'Usuario deshabilitado<BR>';                                                              
                                    }
                                }
                            }
                        //Authorize user    
                        }else if(isset($_POST['authsubmit'])){ 
                            $authdni=sanitizeint('authdni');
                            validateint($authdni);
                            if($validation){
                                $sql='UPDATE clientes SET authorization="si" WHERE dni='.$authdni;
                                if(mysqli_query($conn,$sql)){
                                    if(mysqli_affected_rows($conn)==0){
                                        echo "Error al habilitar<BR>";
                                    }else{
                                        echo 'Usuario habilitado<BR>';                                                              
                                    }
                                }
                            }
                        }
                    }
                    
                    //Consult name or lastname function
                    function consultnameorlastname($nameorlastname,$option,$dboption){
                       if(!empty($_POST[$option])){
                            $nameorlastname=sanitizestring($option);
                            validatestring($nameorlastname);
                            global $validation;
                            global $conn;
                            if($validation){
                                $sql='SELECT name,lastname,dni,email,phone,authorization,address FROM clientes WHERE '.$dboption.'="'.$nameorlastname.'"';
                                $result=mysqli_query($conn,$sql);
                                if(mysqli_num_rows($result)>0){
                                    $totalresults=0;
                                    global $tabletext;
                                    while($row=mysqli_fetch_assoc($result)){
                                        $totalresults++;
                                        $tabletext=$tabletext."<table border='1'>";
                                        $tabletext=$tabletext."<tr><td>DNI: ".$row['dni']."</td></tr><tr><td>Nombre: ".$row['name']."</td></tr><tr><td>Apellido: ".$row['lastname']."</td></tr><tr><td>E-mail: ".$row['email']."</td></tr><tr><td>Domicilio: ".$row['address']."</td></tr><tr><td>Telefono: ".$row['phone']."</td></tr><tr><td>Habilitado: ".$row['authorization']."</td></tr>";
                                        $tabletext=$tabletext."</table><P></P>";
                                    }
                                    echo 'Cantidad de resultados: '.$totalresults.'<P>';
                                    echo $tabletext;
                                }else{
                                    echo 'Error consultando nombre o apellido<BR>';
                                }
                            }else{
                                echo 'Nombre o apellido invalido<BR>';
                            }
                        }
                    }

                    //Random 4 digit string generator
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

                    //Sanitize
                    function sanitizestring($stringtosanitize){
                        global $conn;
                        return mysqli_real_escape_string($conn,strtolower(strip_tags($_POST[$stringtosanitize])));
                    }

                    function sanitizeint($inttosanitize){
                        global $conn;
                        return mysqli_real_escape_string($conn,(strip_tags($_POST[$inttosanitize])));
                    }

                    //Validation
                    function validateemail($emailtovalidate){
                        $emailtovalidate=filter_var($emailtovalidate, FILTER_SANITIZE_EMAIL);
                        if (!filter_var($emailtovalidate, FILTER_VALIDATE_EMAIL)){
                            echo 'Error al insertar E-mail.<br>';
                            global $validation;
                            $validation=false;
                            $emailtovalidate='';
                            return $emailtovalidate;
                        }else{
                            return $emailtovalidate;
                        }                        
                    }

                    function validatestring($stringtovalidate){
                        if (!preg_match("/^[a-z ]*$/",$stringtovalidate)){
                            echo 'Error al insertar el nombre o apellido.<br>';
                            global $validation;
                            $validation=false;
                        }                        
                    }

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