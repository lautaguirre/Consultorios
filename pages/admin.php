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
        <title>Panel de administrador</title>
        <meta charset="UTF-8">
		<link rel="shortcut icon" href="../images/favicon.png" type="image/png">
		<link rel="stylesheet" href="../css/index.css">
    </head>

    <body>
        <script src="../templates/header.js"></script>
        <div class="content">
			<div class="container">  
                <div class='main2'>
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
                                    <li><input type="text" name='actname' placeholder='Nombre'></li>
                                    <li><input type="text" name='actlastname' placeholder='Apellido'></li>
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
                <div class='aside2'>
                    <div class='horizontalnavbar'>
                        <ul>
                            <li><a class='reserve' href="../calendar/calendar.php">Hacer una reserva</a></li>
                            <li><A class='userpanel' HREF = "login.php">Panel de usuario</A></li>
                            <li style="float:right"><a class="active" href="logout.php">Cerrar sesion</a></li>
                        </ul>
                    </div>
                    <hr>
                    <div class='autoscroll'>
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

                            //Validate address
                            validateaddress($createaddress);

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

                                    $tabletext=$tabletext.'<table class="table">
                                    <thead>
                                        <tr>
                                            <th>DNI</th>
                                            <th>Nombre</th>
                                            <th>Apellido</th>
                                            <th>E-mail</th>
                                            <th>Telefono</th>
                                            <th>Domicilio</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                    $tabletext=$tabletext.'<tr>                    
                                    <th scope=row>'.$createdni.'</th>
                                    <td>'.$createname.'</td>
                                    <td>'.$createlastname.'</td>
                                    <td>'.$createemail.'</td>
                                    <td>'.$createphone.'</td>
                                    <td>'.$createaddress.'</td>
                                    </tr>';
                                    $tabletext=$tabletext."</tbody></table>";

                                    echo $tabletext;
                                }else{
                                    echo '<errorspan>Error creando usuario</errorspan><BR>';
                                }
                            }else{
                                echo '<errorspan>Algun campo no es valido</errorspan><BR>';
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
                                            echo "<errorspan>Error al actualizar E-mail o DNI erroneo</errorspan><BR>";
                                        }else{
                                            echo '<h3>E-mail actualizado</h3><BR>';                                                              
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
                                            echo "<errorspan>Error al actualizar telefono o DNI erroneo</errorspan><BR>";
                                        }else{
                                            echo '<h3>Telefono actualizado</h3><BR>';                                                              
                                        }
                                    }
                                }
                            }
                            if(!empty($_POST['actaddress'])){
                                $actaddress=sanitizestring('actaddress');
                                validateaddress($actaddress);
                                if($validation){
                                    $sql='UPDATE clientes SET address="'.$actaddress.'" WHERE dni='.$actdni;
                                    if(mysqli_query($conn,$sql)){
                                        if(mysqli_affected_rows($conn)==0){
                                            echo "<errorspan>Error al actualizar domicilio o DNI erroneo</errorspan><BR>";
                                        }else{
                                            echo '<h3>Domicilio actualizado</h3><BR>';                                                              
                                        }
                                    }
                                }
                            }
                            if(!empty($_POST['actname'])){
                                $actname=sanitizestring('actname');
                                validatestring($actname);
                                if($validation){
                                    $sql='UPDATE clientes SET name="'.$actname.'" WHERE dni='.$actdni;
                                    if(mysqli_query($conn,$sql)){
                                        if(mysqli_affected_rows($conn)==0){
                                            echo "<errorspan>Error al actualizar nombre o DNI erroneo</errorspan><BR>";
                                        }else{
                                            echo '<h3>Nombre actualizado</h3><BR>';                                                              
                                        }
                                    }
                                }
                            }
                            if(!empty($_POST['actlastname'])){
                                $actlastname=sanitizestring('actlastname');
                                validatestring($actlastname);
                                if($validation){
                                    $sql='UPDATE clientes SET lastname="'.$actlastname.'" WHERE dni='.$actdni;
                                    if(mysqli_query($conn,$sql)){
                                        if(mysqli_affected_rows($conn)==0){
                                            echo "<errorspan>Error al actualizar apellido o DNI erroneo</errorspan><BR>";
                                        }else{
                                            echo '<h3>Apellido actualizado</h3><BR>';                                                              
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
                                    if(mysqli_num_rows($result)==1){
                                        $row=mysqli_fetch_assoc($result);

                                        $tabletext=$tabletext.'<table class="table">
                                        <thead>
                                            <tr>
                                                <th>DNI</th>
                                                <th>Nombre</th>
                                                <th>Apellido</th>
                                                <th>E-mail</th>
                                                <th>Telefono</th>
                                                <th>Domicilio</th>
                                                <th>Habilitado</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                                        $tabletext=$tabletext.'<tr>                    
                                        <th scope=row>'.$row['dni'].'</th>
                                        <td>'.$row['name'].'</td>
                                        <td>'.$row['lastname'].'</td>
                                        <td>'.$row['email'].'</td>
                                        <td>'.$row['phone'].'</td>
                                        <td>'.$row['address'].'</td>
                                        <td>'.$row['authorization'].'</td>
                                        </tr>';
                                        $tabletext=$tabletext."</tbody></table>";
                                    
                                        echo $tabletext;
                                    }else{
                                        echo '<errorspan>Error consultando dni</errorspan><br>';
                                    }
                                }else{
                                    echo '<errorspan>DNI invalido</errorspan><br>';
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
                                        $tabletext=$tabletext.'<table class="table">
                                        <thead>
                                            <tr>
                                                <th>DNI</th>
                                                <th>Nombre</th>
                                                <th>Apellido</th>
                                                <th>E-mail</th>
                                                <th>Telefono</th>
                                                <th>Domicilio</th>
                                                <th>Habilitado</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                                        while($row=mysqli_fetch_assoc($result)){
                                            $totalresults++;
                                            $tabletext=$tabletext.'<tr>                    
                                            <th scope=row>'.$row['dni'].'</th>
                                            <td>'.$row['name'].'</td>
                                            <td>'.$row['lastname'].'</td>
                                            <td>'.$row['email'].'</td>
                                            <td>'.$row['phone'].'</td>
                                            <td>'.$row['address'].'</td>
                                            <td>'.$row['authorization'].'</td>
                                            </tr>';
                                        }
                                        $tabletext=$tabletext.'</tbody></table>';
                                        echo '<h3>Cantidad de resultados: '.$totalresults.'</h3><P>';
                                        echo $tabletext;
                                    }else{
                                        echo '<errorspan>Error consultando nombre o apellido</errorspan><br>';
                                    }
                                }else{
                                    echo '<errorspan>Nombre o apellido invalido</errorspan><br>';
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
                                        echo "<errorspan>Error al deshabilitar o ya esta deshabilitado</errorspan><BR>";
                                    }else{
                                        echo '<h3>Usuario '.$unauthdni.' deshabilitado</h3><BR>';                                                              
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
                                        echo "<errorspan>Error al habilitar o ya esta habilitado</errorspan><BR>";
                                    }else{
                                        echo '<h3>Usuario '.$authdni.' habilitado</h3><BR>';                                                              
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
                                    $tabletext=$tabletext.'<table class="table">
                                    <thead>
                                        <tr>
                                            <th>DNI</th>
                                            <th>Nombre</th>
                                            <th>Apellido</th>
                                            <th>E-mail</th>
                                            <th>Telefono</th>
                                            <th>Domicilio</th>
                                            <th>Habilitado</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                    while($row=mysqli_fetch_assoc($result)){
                                        $totalresults++;
                                        $tabletext=$tabletext.'<tr>                    
                                        <th scope=row>'.$row['dni'].'</th>
                                        <td>'.$row['name'].'</td>
                                        <td>'.$row['lastname'].'</td>
                                        <td>'.$row['email'].'</td>
                                        <td>'.$row['phone'].'</td>
                                        <td>'.$row['address'].'</td>
                                        <td>'.$row['authorization'].'</td>
                                        </tr>';
                                    }
                                    $tabletext=$tabletext.'</tbody></table>';
                                    echo '<h3>Cantidad de resultados: '.$totalresults.'</h3><P>';
                                    echo $tabletext;
                                }else{
                                    echo '<errorspan>Error consultando nombre o apellido</errorspan><BR>';
                                }
                            }else{
                                echo '<errorspan>Nombre o apellido invalido</errorspan><BR>';
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
                            echo '<errorspan>Error al insertar E-mail</errorspan><br>';
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
                            echo '<errorspan>Error al insertar el nombre o apellido</errorspan><br>';
                            global $validation;
                            $validation=false;
                        }                        
                    }

                    function validateaddress($stringtovalidate){
                        if (!preg_match("/^[a-z0-9 ]*$/",$stringtovalidate)){
                            echo '<errorspan>Error al insertar el domicilio</errorspan><br>';
                            global $validation;
                            $validation=false;
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
                </div>
                </div>
			</div>
		</div>
        <script src="../templates/footer.js"></script>
    </body>
</html>