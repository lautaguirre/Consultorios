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
		<link rel="stylesheet" href="../css/index.css" type="text/css">
        <link rel='stylesheet' type="text/css" href='../calendar/fullcalendar.min.css' />
        <script src='../calendar/lib/jquery.min.js'></script>
        <script src='../calendar/lib/moment.min.js'></script>
        <script src='../calendar/fullcalendar.js'></script>
        <script src='../calendar/locale/es.js'></script>
        <script>
            $(document).ready(function(){
                var selection=`<table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Comienzo</th>
                                            <th>Fin</th>
                                        </tr>
                                    </thead>`;
                var selectionnumber=0;
                var selected=false;
                var selection2=`<table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Comienzo</th>
                                            <th>Fin</th>
                                        </tr>
                                    </thead>`;
                var selection2number=0;
                var selected2=false;
                var alreadyselected=false;
                var selectedevents=[];
                var office=1;
                var evdesc='';

                //Calendar config
                $('#calendar').fullCalendar({
                    //Header buttons
                    header: {
				        left: 'today',
				        center: 'prev title next',
				        right: 'month,agendaWeek listMonth'
                    },
                    
                    minTime:"09:00:00",
                    maxTime:"20:00:00",
                    allDaySlot:false,
                    slotEventOverlap:false,
                    timeFormat:'HH(:mm)',
                    slotLabelFormat:'HH(:mm)A',
                    displayEventEnd:true,
                    slotDuration:'01:00:00',
                    //selectConstraint: 'businessHours', In case you want to avoid selection
                    hiddenDays: [0],
                    noEventsMessage: 'No hay eventos para mostrar',
                    selectable: true,
                    selectHelper:true,
                    selectOverlap:false,
                    selectMinDistance:10,
                    /*businessHours:[ 
                        {
                            dow: [ 1, 2, 3, 4, 5, 6], 
                            start: '09:00', 
                            end: '13:00' 
                        },
                        {
                            dow: [ 1, 2, 3, 4, 5],
                            start: '16:00', 
                            end: '20:00' 
                        }
                    ], */

                    //Event click callback
                    eventClick:function(event){
                        if(event.id!=2){
                            $('#removeevents').click();
                        }
                        
                        for(selectedevi=0;selectedevi<selectedevents.length;selectedevi++){
                            if(selectedevents[selectedevi]==event.id){
                                alreadyselected=true;
                                break;
                            }else{
                                alreadyselected=false;
                            }
                        }
                        if(event.id!=2 && event.id!=1 && alreadyselected==false){
                            //Show selected events
                            selectionnumber++;
                            selection=selection+`<tbody>
                                    <tr> 
                                        <td>`+selectionnumber+`</td>                   
                                        <td >`+event.start.format('DD/MM/YYYY HH:mm')+`</td>
                                        <td>`+event.end.format('DD/MM/YYYY HH:mm')+`</td>
                                    </tr>
                                    </tbody>`;

                            $('#selection').html(selection);
                            console.log(selection);

                            $('#cancelevent').removeClass('hidden'); //Show cancel submit button

                            //Set selected event color to red
                            var backevent=
                            {
                                'id':1,
                                'title':event.title,
                                'start':event.start.format(),
                                'end':event.end.format(),
                                'backgroundColor':'red'
                            };
                            $('#calendar').fullCalendar( 'renderEvent',backevent,true);
                            selectedevents.push(event.id);

                            selected=true;

                            //Delete selected events
                            $('#deleteevent').one('click',function(){
                                if(selected){
                                    $('#selection').html('');
                                    $('#cancelevent').addClass('hidden');
                                    alreadyselected=false;
                                    selectedevents=[];
                                    $.post(
                                        '../scripts/admindeleteevents.php', 
                                        {
                                            startev:event.start.format(),
                                            endev:event.end.format(),
                                            officenumber:office
                                        },
                                        function(data){
                                            admindeletedata=JSON.parse(data);
                                            console.log(data);
                                            $.post(
                                                '../scripts/admindeleteemail.php',
                                                {
                                                    deleteemailbody:selection,
                                                    deleteemail:admindeletedata.useremail
                                                }
                                            );
                                            selectionnumber=0;
                                            selection=`<table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Comienzo</th>
                                                        <th>Fin</th>
                                                    </tr>
                                                </thead>`;
                                            $('#selection').html(admindeletedata.msg);
                                            $('#calendar').fullCalendar( 'removeEvents',1 ); //Remove red events
                                            $('#calendar').fullCalendar( 'refetchEvents' );
                                        }
                                    );
                                }
                            });
                        }
                    },

                    //Select callback
                    select: function(start, end){          
                        $('#cancelselection').click();

                        //Show selected events
                        selection2number++;
                        selection2=selection2+`<tbody>
                        <tr>
                            <td>`+selection2number+`</td>                    
                            <td >`+start.format('DD/MM/YYYY HH:mm')+`</td>
                            <td>`+end.format('DD/MM/YYYY HH:mm')+`</td>
                        </tr>
                        </tbody>`;
                    
                        $('#selection2').html(selection2);
                        console.log(selection2);

                        $('#reservetext').removeClass('hidden'); //Show title input and reserve submit button

                        //Unselect method
                        $('#calendar').fullCalendar( 'unselect' );

                        //Render selected event as background event
                        var backevent=
                            {
                                'id':2,
                                'start':start.format(),
                                'end':end.format(),
                                'backgroundColor':'green'
                            };
                        $('#calendar').fullCalendar( 'renderEvent',backevent,true);

                        selected2=true;

                        //Send selected dates to db
                        $('#reserve').one('click',function(){ 
                            if(selected2){ //If there is no selection avoid post handlers acumulated
                                var title=$('#titletext').val();
                                if(title==''){
                                    evdesc=username;
                                }else{
                                    evdesc=title;
                                }
                                $('#selection2').html('');
                                $('#reservetext').addClass('hidden');
                                $.post(
                                    '../scripts/loadevents.php',
                                    {
                                        moment:'reserve',
                                        startev:start.format(),
                                        endev:end.format(),
                                        titleev:evdesc,
                                        evdni:<?php echo $_SESSION['logged']; ?>,
                                        officenumber:office,
                                        mailev:selection2
                                    },
                                    function(data){
                                        if(data!='<errorspan>Error creando reserva, parece que otro usuario ya ocupo las fechas solicitadas, la descripcion no es valida o solicito fechas anteriores al dia de hoy.</errorspan><BR>'){
                                            if(selection2==`<table class="table"><thead><tr><th>#</th><th>Comienzo</th><th>Fin</th></tr></thead>`){
                                                selection2=selectionaux;
                                            }
                                            $.post(
                                                '../scripts/sendemail.php',
                                                {
                                                    emailbody:selection2,
                                                    emailuser:username,
                                                    emaildni:<?php echo $_SESSION['logged']; ?>,
                                                    emailoffice:office
                                                }
                                            );
                                        }else{
                                            if(selection2!=`<table class="table"><thead><tr><th>#</th><th>Comienzo</th><th>Fin</th></tr></thead>`){
                                                selectionaux=selection2;
                                            }
                                        }
                                        selection2number=0;
                                        selection2=`<table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Comienzo</th>
                                            <th>Fin</th>
                                        </tr>
                                    </thead>`;
                                        console.log(data);
                                        $('#selection2').html(data);
                                        $('#calendar').fullCalendar( 'removeEvents',2 ); //Remove green highlight
                                        $('#calendar').fullCalendar( 'refetchEvents' );
                                        $('#titletext').val('');
                                    }
                                );
                            }
                        });
                    },

                    //Events load function
                    events: function(start,end,timezone,callback){

                        $('#calendar').fullCalendar( 'removeEvents',3 );

                        var bhours= 
                        [{
                            id:3,
                            start: '13:00:00',
                            end: '16:00:00',
                            color: 'gray',
                            rendering: 'background',
                            dow: [1,2,3,4,5]
                        },
                        {
                            id:3,
                            start: '13:00:00',
                            end: '20:00:00',
                            color: 'gray',
                            rendering: 'background',
                            dow: [6]
                        }];
                        $('#calendar').fullCalendar( 'renderEvents',bhours,true); //Avoid bussines hours (to avoid css issues with higher hour slots)
                        
                        //Request events
                        $.post(
                            '../scripts/adminloadevents.php',
                            {
                                moment:'onload',
                                evdni:<?php echo $_SESSION['logged']; ?>,
                                officenumber:office
                            },
                            function(data){
                                eventos=JSON.parse(data);
                                console.log(data);
                                callback(eventos);
                            }
                        );
                    }
                });

                //Cancel remove selected events button
                $('#cancelselection').click(function(){
                    $('#calendar').fullCalendar( 'removeEvents',1 );
                    $('#selection').html('');
                    $('#cancelevent').addClass('hidden');
                    $('#response').html('');
                    selected=false;
                    selectionnumber=0;
                    selection=`<table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Comienzo</th>
                                            <th>Fin</th>
                                        </tr>
                                    </thead>`;
                    alreadyselected=false;
                    selectedevents=[];
                    $('#deleteevent').click(); //dump click handlers
                });

                //Remove selected events button
                $('#removeevents').click(function(){
                    $('#calendar').fullCalendar( 'removeEvents',2 );
                    $('#selection2').html('');
                    $('#titletext').val('');
                    $('#reservetext').addClass('hidden');
                    $('#response').html('');
                    selected2=false;
                    selection2number=0;
                    selection2=`<table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Comienzo</th>
                                            <th>Fin</th>
                                        </tr>
                                    </thead>`;
                    $('#reserve').click(); //dump click handlers
                });

                //Get user name by id
                $.post(
                    '../scripts/getname.php',
                    {
                        userdni:<?php echo $_SESSION['logged']; ?>
                    },
                    function(data){
                        $('#welcome').append(data);
                        username='';
                        username=username.concat(data.replace(/\b\w/g, l => l.toUpperCase()));
                    }
                );

                //Select office
                $('#office1').click(function(){
                    office=1;
                    $("#office1").addClass('reserve');
                    $("#office2").removeClass('reserve');

                    //Reset
                    $('#calendar').fullCalendar( 'removeEvents',1 );
                    $('#calendar').fullCalendar( 'removeEvents',2 );
                    $('#selection').html('');
                    $('#selection2').html('');
                    $('#cancelevent').addClass('hidden');
                    $('#reservetext').addClass('hidden');
                    $('#titletext').val('');
                    selected=false;
                    selected2=false;
                    selectionnumber=0;
                    selection=`<table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Comienzo</th>
                                            <th>Fin</th>
                                        </tr>
                                    </thead>`;
                    selection2number=0;
                    selection2=`<table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Comienzo</th>
                                            <th>Fin</th>
                                        </tr>
                                    </thead>`;
                    alreadyselected=false;
                    selectedevents=[];
                    $('#deleteevent').click(); //dump click handlers
                    $('#reserve').click(); //dump click handlers

                    $('#calendar').fullCalendar( 'refetchEvents' );
                });
                $('#office2').click(function(){
                    office=2;
                    $("#office2").addClass('reserve');
                    $("#office1").removeClass('reserve');

                    //Reset
                    $('#calendar').fullCalendar( 'removeEvents',1 );
                    $('#calendar').fullCalendar( 'removeEvents',2 );
                    $('#selection').html('');
                    $('#selection2').html('');
                    $('#cancelevent').addClass('hidden');
                    $('#reservetext').addClass('hidden');
                    $('#titletext').val('');
                    selected=false;
                    selected2=false;
                    selectionnumber=0;
                    selection=`<table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Comienzo</th>
                                            <th>Fin</th>
                                        </tr>
                                    </thead>`;
                    selection2number=0;
                    selection2=`<table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Comienzo</th>
                                            <th>Fin</th>
                                        </tr>
                                    </thead>`;
                    alreadyselected=false;
                    selectedevents=[];
                    $('#deleteevent').click(); //dump click handlers
                    $('#reserve').click(); //dump click handlers

                    $('#calendar').fullCalendar( 'refetchEvents' );
                });
                
            });
            </script>
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
                    <P></P>
                    <div class='horizontalnavbar'>
                        <ul>
                            <li><a class='show'>Mostrar: </a></li>
                            <li><a id='office1' class='reserve'>Consultorio 1</a></li>
                            <li><a id='office2'>Consultorio 2</a></li>
                        </ul>
                    </div> 
                    <p></p>
                    <div id='calendar'></div>
                </div>
                <div class='aside2'>
                    <div class='horizontalnavbar'>
                        <ul>
                            <li><A class='userpanel' HREF = "login.php">Panel de usuario</A></li>
                            <li style="float:right"><a class="active" href="../scripts/logout.php">Cerrar sesion</a></li>
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
                                        <p><H2>Bienvenido, su cuenta en Consultorios Villa Martina ya fue creada.<br>
                                        Para ingresar a la misma use los siguientes datos:</h2></p>
                                        <p><h3>- Su DNI: ".$createdni."<br>
                                        - Y la siguiente contraseña: ".$createpass."</h3></p>
                                        <p style='color:red;'>UNA VEZ INGRESADO A SU CUENTA RECUERDE SELECCIONAR 'CAMBIAR CONTRASEÑA'</p>
                                        <p><a>http://consultoriosvillamartina.000webhostapp.com</a></p>
                                    </body>
                                    </html>";
                                    $headers = "MIME-Version: 1.0" . "\r\n";
                                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                                    mail($to,$subject,$message,$headers);

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
                                            echo '<h3>E-mail del usuario '.$actdni.' actualizado</h3><BR>';                                                              
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
                                            echo '<h3>Telefono del usuario '.$actdni.' actualizado</h3><BR>';                                                              
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
                                            echo '<h3>Domicilio del usuario '.$actdni.' actualizado</h3><BR>';                                                              
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
                                            echo '<h3>Nombre del usuario '.$actdni.' actualizado</h3><BR>';                                                              
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
                                            echo '<h3>Apellido  del usuario '.$actdni.' actualizado</h3><BR>';                                                              
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
                    <p></p>
                    <h3 id='response'></h3>
                    <h3 id='selection'></h3>
                    <h3 id='selection2'></h3>
                    <div id="cancelevent" class="hidden">
                        <input class="btn2" type="button" value="Borrar eventos" id="deleteevent">
                        <input class="btn" type="button" value="Cancelar" id="cancelselection">
                    </div>
                    <div id="reservetext" class="hidden">
                        <h3>Descripcion de la reserva (Si no ingresa nada se usara por defecto su nombre y apellido).</h3>
                        <input type="text" id="titletext">
                        <input class="btn3" type="button" value="Reservar" id="reserve">
                        <input class="btn" type="button" value="Cancelar" id="removeevents">
                    </div> 
                </div>
			</div>
		</div>
        <script src="../templates/footer.js"></script>
    </body>
</html>