<?php
    session_start();
    $logindni=$loginpass='';
    $validation=true;

    //DB connection
    require '../scripts/connection.php';
    
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
                        if($logindni==38333166 || $logindni==22773396){
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
    <title>Panel de usuario</title>
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../images/favicon.png" type="image/png">
    <link rel='stylesheet' type="text/css" href='../calendar/fullcalendar.min.css' />
    <script src='../calendar/lib/moment.min.js'></script>
    <script src='../calendar/fullcalendar.js'></script>
    <script src='../calendar/locale/es.js'></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/index2.css" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
             $(document).ready(function() {

                var selection=`<table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Comienzo</th>
                                            <th>Fin</th>
                                        </tr>
                                    </thead>`;
                var selectionnumber=0;
                var selected=false;
                var selection2=`<table class="table table-hover">
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
                var selectarr=[];
                var selectobj={};
                var clickevobj={};
                var clickevarr=[];

                //Calendar config
                $('#calendar').fullCalendar({
                    //Header buttons
                    header: {
				        left: 'today',
				        center: 'prev title next',
				        right: 'month,agendaWeek listMonth'
                    },
                    themeSystem:'bootstrap3',
                    bootstrapGlyphicons: {
                        close: 'glyphicon-remove',
                        prev: 'glyphicon-chevron-left',
                        next: 'glyphicon-chevron-right',
                        prevYear: 'glyphicon-backward',
                        nextYear: 'glyphicon-forward'
                    },
                    minTime:"09:00:00",
                    maxTime:"20:00:00",
                    allDaySlot:false,
                    slotEventOverlap:false,
                    timeFormat:'HH(:mm)',
                    slotLabelFormat:'HH(:mm)A',
                    displayEventEnd:true,
                    slotDuration:'01:00:00',
                    defaultView:'agendaWeek',
                    hiddenDays: [0],
                    noEventsMessage: 'No hay eventos para mostrar',
                    selectable: true,
                    selectHelper:true,
                    selectOverlap:false,

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
                        if(event.id!=2 && event.id!=1 && alreadyselected==false && event.backgroundColor=='green'){
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

                            $('#cancelevent').removeClass('hidden'); //Show cancel submit button

                            //Set selected event color to red
                            var backevent=
                            {
                                'id':1,
                                'title':'Borrar ->',
                                'start':event.start.format(),
                                'end':event.end.format(),
                                'backgroundColor':'red'
                            };
                            $('#calendar').fullCalendar( 'renderEvent',backevent,true);
                            selectedevents.push(event.id);

                            selected=true;

                            clickevobj.evstart=event.start.format();
                            clickevobj.evend=event.end.format();

                            clickevarr.push(clickevobj);

                            clickevobj={};
                        }  
                    },

                    //Select callback
                    select: function(start, end){          
                        $('#cancelselection').click();

                        //Show selected events
                        if(moment().isBefore(start.format())){
                            selection2number++;
                            selection2=selection2+`<tbody>
                                <tr>
                                    <td>`+selection2number+`</td>                    
                                    <td >`+start.format('DD/MM/YYYY HH:mm')+`</td>
                                    <td>`+end.format('DD/MM/YYYY HH:mm')+`</td>
                                </tr>
                                </tbody>`;
                                
                            $('#selection2').html(selection2);

                            $('#reservetext').removeClass('hidden'); //Show title input and reserve submit button

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

                            selectobj.evstart=start.format();
                            selectobj.evend=end.format();

                            selectarr.push(selectobj);

                            selectobj={};
                        }

                        //Unselect method
                        $('#calendar').fullCalendar( 'unselect' );

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
                            '../scripts/loadevents.php',
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

                //Delete selected events
                $('#deleteevent').click(function(){
                    if(selected){
                        $('#selection').html('');
                        $('#cancelevent').addClass('hidden');
                        selectionnumber=0;
                        selection=`<table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Comienzo</th>
                                    <th>Fin</th>
                                </tr>
                            </thead>`;
                        alreadyselected=false;
                        selectedevents=[];
                        clickjson=JSON.stringify(clickevarr);
                        clickevobj={};
                        clickevarr=[];
                        $.post(
                            '../scripts/deleteevents.php',
                            {
                                clickevjson:clickjson,
                                officenumber:office,
                                deletedni:<?php echo $_SESSION['logged']; ?>
                            },
                            function(data){
                                console.log(data);
                                $('#selection').html(data);
                                $('#calendar').fullCalendar( 'removeEvents',1 ); //Remove red events
                                $('#calendar').fullCalendar( 'refetchEvents' );
                            }
                        );
                    }
                });


                //Send selected dates to db
                $('#reserve').click(function(){ 
                    if(selected2){ //If there is no selection avoid post 
                        var title=$('#titletext').val();
                        if(title==''){
                            evdesc=username;
                        }else{
                            evdesc=title;
                        }
                        $('#selection2').html('');
                        $('#reservetext').addClass('hidden');
                        jsonarr=JSON.stringify(selectarr);
                        selectobj={};
                        selectarr=[];
                        $.post(
                            '../scripts/loadevents.php',
                            {
                                moment:'reserve',
                                evjson:jsonarr,
                                titleev:evdesc,
                                evdni:<?php echo $_SESSION['logged']; ?>,
                                officenumber:office
                            },
                            function(data){
                                if(data!='<div class="alert alert-warning"><strong>Atencion!</strong> Error creando reserva, parece que otro usuario ya ocupo las fechas solicitadas, la descripcion no es valida o solicito fechas anteriores al dia de hoy.</div><BR>'){
                                    $.post(
                                        '../scripts/sendemail.php',
                                        {
                                            emailbody:selection2,
                                            emailuser:username,
                                            emaildni:<?php echo $_SESSION['logged']; ?>,
                                            emailoffice:office,
                                            emailemail:useremail
                                        }
                                    );
                                }
                                        
                                selection2number=0;
                                selection2=`<table class="table table-hover">
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

                //Cancel remove selected events button
                $('#cancelselection').click(function(){
                    $('#calendar').fullCalendar( 'removeEvents',1 );
                    $('#selection').html('');
                    $('#cancelevent').addClass('hidden');
                    $('#response').html('');
                    clickevobj={};
                    clickevarr=[];
                    selected=false;
                    selectionnumber=0;
                    selection=`<table class="table table-hover">
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
                    selectobj={};
                    selectarr=[];
                    selection2number=0;
                    selection2=`<table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Comienzo</th>
                                            <th>Fin</th>
                                        </tr>
                                    </thead>`;
                    $('#reserve').click(); //dump click handlers
                });

                //Select office
                $('#office1').click(function(){
                    office=1;
                    $("#office1").addClass('active');
                    $("#office2").removeClass('active');

                    //Reset
                    $('#calendar').fullCalendar( 'removeEvents',1 );
                    $('#calendar').fullCalendar( 'removeEvents',2 );
                    $('#selection').html('');
                    $('#selection2').html('');
                    $('#cancelevent').addClass('hidden');
                    $('#reservetext').addClass('hidden');
                    $('#titletext').val('');
                    clickevobj={};
                    clickevarr=[];
                    selectobj={};
                    selectarr=[];
                    selected=false;
                    selected2=false;
                    selectionnumber=0;
                    selection=`<table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Comienzo</th>
                                            <th>Fin</th>
                                        </tr>
                                    </thead>`;
                    selection2number=0;
                    selection2=`<table class="table table-hover">
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
                    $("#office2").addClass('active');
                    $("#office1").removeClass('active');

                    //Reset
                    $('#calendar').fullCalendar( 'removeEvents',1 );
                    $('#calendar').fullCalendar( 'removeEvents',2 );
                    $('#selection').html('');
                    $('#selection2').html('');
                    $('#cancelevent').addClass('hidden');
                    $('#reservetext').addClass('hidden');
                    $('#titletext').val('');
                    clickevobj={};
                    clickevarr=[];
                    selectobj={};
                    selectarr=[];
                    selected=false;
                    selected2=false;
                    selectionnumber=0;
                    selection=`<table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Comienzo</th>
                                            <th>Fin</th>
                                        </tr>
                                    </thead>`;
                    selection2number=0;
                    selection2=`<table class="table table-hover">
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

                //Get user name by id
                $.post(
                    '../scripts/getname.php',
                    {
                        userdni:<?php echo $_SESSION['logged']; ?>
                    },
                    function(data){
                        userdata=JSON.parse(data);
                        $('#welcome').append(userdata.namelastname);
                        useremail='';
                        useremail=userdata.getemail;
                        username='';
                        username=username.concat(userdata.namelastname.replace(/\b\w/g, l => l.toUpperCase()));
                    }
                );

                // Add smooth scrolling
                $(".navbar a, footer a[href='#myPage']").on('click', function (event) {
                    if (this.hash !== "") {
                        // Prevent default anchor click behavior
                        event.preventDefault();

                        // Store hash
                        var hash = this.hash;

                        $('html, body').animate({
                            scrollTop: $(hash).offset().top
                        }, 900, function () { //900 miliseconds to scroll to te desired area

                        window.location.hash = hash;
                        });
                    }
                });

                //Hide collapse after click
                $('#collapseitems, #movecontact').click(function(){
                    $(".collapse").collapse('hide');
                });

                //Check if user is on touch device
                if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|BB|PlayBook|IEMobile|Windows Phone|Kindle|Silk|Opera Mini/i.test(navigator.userAgent)) {
                    $('#mobile').html('<div class="alert alert-danger"><strong>ATENCION!</strong>Si se encuentra en un dispositivo tactil debe mantener apretado el calendario por un breve lapso y luego arrastrar el dedo para seleccionar las horas deseadas.</div>');
                }
                
            });
        </script>
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
                            <a href="../changepass.php" id='collapseitems'>CAMBIAR CONTRASEÑA</a>
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
                            </a>
                        </li>
                        <?php
                            if(isset($_SESSION['admin'])){
                                echo '<li>
                                    <a href="admin.php" id="collapseitems" class="alterlogo">
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
                <div class="col-sm-6">
                    <p>&nbsp;</p>
                    <h3 id='welcome' style='text-transform:capitalize;'>Bienvenido </h3>
                    <div id='mobile'></div>
                    <nav class="fakenavbar navbar-default" >
                        <div class="container">
                            <div class="navbar-header">
                                <a class="navbar-brand" >Mostrar:</a>
                            </div>
                            <ul class="nav navbar-nav">
                                <li id='office1' class='active'><a href='#'>CONSULTORIO 1</a></li>
                                <li id='office2'><a href='#'>CONSULTORIO 2</a></li>
                            </ul>
                        </div>
                    </nav>
                    <p>&nbsp;</p>
                    <div id='calendar'></div>
                </div>
                <div class="col-sm-6">
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <h3>En esta seccion usted puede:</h3>
                    <ul>
                        <li>Ver todas las reservas y horarios disponibles siguientes al dia de hoy.</li>
                        <li class='bg-danger'>Recuerde que los horarios de reserva solo pueden ser de Lunes a Viernes (9:00 a 13:00 y 16:00 a 20:00) y Sabados (9:00 a 13:00).</li>
                        <li>Disponemos de 2 consultorios y cada uno posee su respectivo calendario, puede seleccionar cual mostrar en las opciones arriba del mismo.</li>
                    </ul>
                    <button data-toggle="collapse" data-target="#instructions"class='btn btn-default' style='width:100%;'>Instrucciones</button>
                    <div id="instructions" class="collapse">
                        <ul>
                            <li>Puede cancelar reservas haciendo click en las mismas y seleccionando "Borrar evento" (Siempre que esten dentro del plazo permitido).</li>
                            <li>Puede realizar una nueva reserva <strong>ARRASTRANDO</strong> el raton sobre los dias deseados y seleccionando "Reservar" (Notese que solo puede reservar por horas en la pestaña <strong>"Semana"</strong>).</li>
                            <li>Puede agregar una descripcion a la hora de hacer una reserva, la cual sera mostrada junto con el evento (Si decide no agregar una, se usara su nombre y apellido como descripcion).</li>
                            <li>Para un uso eficiente del calendario seleccione todas las fechas q desea y luego haga click en "Reservar", en vez de hacerlo uno por uno.</li>
                        </ul>
                    </div>
                    <h4 id='selection'></h4>
                    <h4 id='selection2'></h4>
                    <div id="cancelevent" class="hidden">
                        <button class="btn btn-success" id="deleteevent">Borrar eventos</button>
                        <button class="btn btn-danger" id="cancelselection">Cancelar</button>
                    </div>
                    <div id="reservetext" class="hidden">
                        <h3>Descripcion de la reserva (Si no ingresa nada se usara por defecto su nombre y apellido).</h3>
                        <div class='form-group' >
                            <input type="text" id="titletext" class='form-control'>
                        </div>
                        <button class="btn btn-success" id="reserve">Reservar</button>
                        <button class="btn btn-danger" id="removeevents">Cancelar</button>
                    </div>  
                </div>
            </div>
        </div>

        <!-- Footer --> 
        <script src="../templates/footer2.js"></script>
    </body>
