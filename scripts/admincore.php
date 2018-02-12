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
        require '../scripts/connection.php';

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
            $hashedpass=password_hash($createpass, PASSWORD_DEFAULT);

            //Query
            if($validation){
                $sql='INSERT INTO clientes (name,lastname,email,address,phone,dni,password) VALUES ("'.$createname.'","'.$createlastname.'","'.$createemail.'","'.$createaddress.'","'.$createphone.'","'.$createdni.'","'.$hashedpass.'")';
                if(mysqli_query($conn,$sql)){        

                    $to = $createemail;
                    $subject = "Consultorios Villa Martina: Usuario creado";
                    $message = "<html>
                        <body>
                            <p>
                                <h2>
                                    Bienvenido, su cuenta en Consultorios Villa Martina ya fue creada.<br>
                                    Para ingresar a la misma use los siguientes datos:
                                </h2>
                            </p>
                            <p>
                                <h3>- Su DNI: ".$createdni."<br>
                                    - Y la siguiente contraseña: ".$createpass."
                                </h3>
                            </p>
                            <p style='color:red;'>UNA VEZ INGRESADO A SU CUENTA RECUERDE SELECCIONAR 'CAMBIAR CONTRASEÑA'</p>
                            <p><a>https://www.villamartinarosario.com</a></p>
                        </body>
                    </html>";
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= 'From: VMC <villamartinaconsultorios@gmail.com>' . "\r\n";
                    mail($to,$subject,$message,$headers);

                    echo '<div class="alert alert-success"><strong>Exito!</strong> Nuevo usuario creado.</div><BR>';

                    $tabletext=$tabletext.'<table class="table table-hover">
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
                }else
                {
                    echo '<div class="alert alert-warning"><strong>Atencion!</strong> Error creando usuario.</div><BR>';
                }
            }else
            {
                echo '<div class="alert alert-warning"><strong>Atencion!</strong> Algun campo no es valido.</div><BR>';
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
                            echo '<div class="alert alert-warning"><strong>Atencion!</strong> Error al actualizar, E-mail o DNI erroneo.</div><BR>';
                        }else
                        {
                            echo '<div class="alert alert-success"><strong>Exito!</strong> E-mail del usuario '.$actdni.' actualizado.</div><BR>';                                                             
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
                            echo '<div class="alert alert-warning"><strong>Atencion!</strong> Error al actualizar, telefono o DNI erroneo.</div><BR>';
                        }else
                        {
                            echo '<div class="alert alert-success"><strong>Exito!</strong> Telefono del usuario '.$actdni.' actualizado.</div><BR>';                                                              
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
                            echo '<div class="alert alert-warning"><strong>Atencion!</strong> Error al actualizar, domicilio o DNI erroneo.</div><BR>';
                        }else
                        {
                            echo '<div class="alert alert-success"><strong>Exito!</strong> Domicilio del usuario '.$actdni.' actualizado.</div><BR>';                                                              
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
                            echo '<div class="alert alert-warning"><strong>Atencion!</strong> Error al actualizar, nombre o DNI erroneo.</div><BR>';
                        }else
                        {
                            echo '<div class="alert alert-success"><strong>Exito!</strong> Nombre del usuario '.$actdni.' actualizado.</div><BR>';                                                              
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
                            echo '<div class="alert alert-warning"><strong>Atencion!</strong> Error al actualizar, apellido o DNI erroneo.</div><BR>';
                        }else
                        {
                            echo '<div class="alert alert-success"><strong>Exito!</strong> Apellido del usuario '.$actdni.' actualizado.</div><BR>';                                                              
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

                        $tabletext=$tabletext.'<div class="table-responsive"><table class="table table-hover">
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
                        $tabletext=$tabletext."</tbody></table></div>";
                                    
                        echo $tabletext;
                    }else
                    {
                        echo '<div class="alert alert-warning"><strong>Atencion!</strong> Error consultando DNI.</div><BR>';
                    }
                }else
                {
                    echo '<div class="alert alert-warning"><strong>Atencion!</strong> DNI invalido.</div><BR>';
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
                        $tabletext=$tabletext.'<div class="table-responsive"><table class="table table-hover">
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
                        $tabletext=$tabletext.'</tbody></table></div>';
                        echo '<h3>Cantidad de resultados: '.$totalresults.'</h3><P>';
                        echo $tabletext;
                    }else
                    {
                        echo '<div class="alert alert-warning"><strong>Atencion!</strong> Error consultando nombre o apellido.</div><BR>';
                    }
                }else
                {
                    echo '<div class="alert alert-warning"><strong>Atencion!</strong> Nombre o apellido invalido.</div><BR>';
                }
            }else if($allinfoneeded==false){
                //Consult name or lastname
                consultnameorlastname($consultname,'consultname','name');
                consultnameorlastname($consultlastname,'consultlastname','lastname');
            }

            //Empty consult
            if(empty($_POST['consultname']) and empty($_POST['consultlastname']) and empty($_POST['consultdni'])){
                $sql='SELECT name,lastname,dni,email,phone,authorization,address FROM clientes';
                $result=mysqli_query($conn,$sql);
                $totalresults=0;
                $tabletext='<div class="table-responsive"><table class="table table-hover">
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
                $tabletext=$tabletext.'</tbody></table></div>';
                echo '<h3>Cantidad de resultados: '.$totalresults.'</h3><P>';
                echo $tabletext;
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
                        echo '<div class="alert alert-warning"><strong>Atencion!</strong> Error al deshabilitar o ya esta deshabilitado.</div><BR>';
                    }else
                    {
                        echo '<div class="alert alert-success"><strong>Exito!</strong> Usuario '.$unauthdni.' deshabilitado.</div><BR>';                                                          
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
                        echo '<div class="alert alert-warning"><strong>Atencion!</strong> Error al habilitar o ya esta habilitado.</div><BR>';
                    }else
                    {
                        echo '<div class="alert alert-success"><strong>Exito!</strong> Usuario '.$authdni.' habilitado.</div><BR>';                                                            
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
                    $tabletext=$tabletext.'<div class="table-responsive"><table class="table table-hover">
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
                    $tabletext=$tabletext.'</tbody></table></div>';
                    echo '<h3>Cantidad de resultados: '.$totalresults.'</h3><P>';
                    echo $tabletext;
                }else
                {
                    echo '<div class="alert alert-warning"><strong>Atencion!</strong> Error consultando nombre o apellido.</div><BR>';
                }
            }else
            {
                echo '<div class="alert alert-warning"><strong>Atencion!</strong> Nombre o apellido invalido.</div><BR>';
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
            echo '<div class="alert alert-warning"><strong>Atencion!</strong> Error al insertar E-mail.</div><BR>';
            global $validation;
            $validation=false;
            $emailtovalidate='';
            return $emailtovalidate;
        }else
        {
            return $emailtovalidate;
        }                        
    }

    function validatestring($stringtovalidate){
        if (!preg_match("/^[a-z ]*$/",$stringtovalidate)){
            echo '<div class="alert alert-warning"><strong>Atencion!</strong> Error al insertar el nombre o apellido.</div><BR>';
            global $validation;
            $validation=false;
        }                        
    }

    function validateaddress($stringtovalidate){
        if (!preg_match("/^[a-z0-9 ]*$/",$stringtovalidate)){
            echo '<div class="alert alert-warning"><strong>Atencion!</strong> Error al insertar el domicilio.</div><BR>';
            global $validation;
            $validation=false;
        }                        
    }

    function validateint($inttovalidate){
        if (!filter_var($inttovalidate, FILTER_VALIDATE_INT)) {
            echo '<div class="alert alert-warning"><strong>Atencion!</strong> Error al insertar DNI o telefono.</div><BR>';
            global $validation;
            $validation=false;
        }
    }
?>