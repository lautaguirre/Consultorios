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
        <title>Panel de usuario</title>
        <meta charset="UTF-8">
		<link rel="shortcut icon" href="../images/favicon.png" type="image/png">
        <link rel="stylesheet" href="../css/index.css">
        <link rel='stylesheet' href='../calendar/fullcalendar.css' />
        <script src='../calendar/lib/jquery.min.js'></script>
        <script src='../calendar/lib/moment.min.js'></script>
        <script src='../calendar/fullcalendar.js'></script>
        <script src='../calendar/locale/es.js'></script>
        <script>
             $(document).ready(function() {
                var selection='';
                var selected=false;
                var selection2='';
                var selected2=false;
                var alreadyselected=false;
                var selectedevents=[];
                var office=1;

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
                    slotEventOverlap:false,
                    timeFormat:'HH(:mm)',
                    slotLabelFormat:'HH(:mm)A',
                    displayEventEnd:true,
                    slotDuration:'01:00:00',
                    navLinks:true,
                    noEventsMessage: 'No hay eventos para mostrar',
                    selectable: true,
                    selectHelper:true,
                    selectOverlap:false,
                    selectMinDistance:25,
                    businessHours:[ 
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
                    ], 

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
                            selection=selection+`<table class="table">
                                    <thead>
                                        <tr>
                                            <th>Comienzo</th>
                                            <th>Fin</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                            selection=selection+`<tr>                    
                                    <td >`+event.start.format('DD/MM/YYYY HH:mm')+`</td>
                                    <td>`+event.end.format('DD/MM/YYYY HH:mm')+`</td>
                                    </tr>`;
                            selection=selection+"</tbody></table>";

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
                            }
                            $('#calendar').fullCalendar( 'renderEvent',backevent,true);
                            selectedevents.push(event.id);

                            selected=true;

                            //Delete selected events
                            $('#deleteevent').one('click',function(){
                                if(selected){
                                    $('#selection').html('');
                                    $('#cancelevent').addClass('hidden');
                                    selection='';
                                    alreadyselected=false;
                                    selectedevents=[];
                                    $.post(
                                        '../scripts/deleteevents.php',
                                        {
                                            startev:event.start.format(),
                                            endev:event.end.format(),
                                            officenumber:office
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
                        }
                    },

                    //Select callback
                    select: function(start, end){          
                        $('#cancelselection').click();

                        //Show selected events
                        
                        selection2=selection2+`<table class="table">
                                    <thead>
                                        <tr>
                                            <th>Comienzo</th>
                                            <th>Fin</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                        selection2=selection2+`<tr>                    
                                    <td >`+start.format('DD/MM/YYYY HH:mm')+`</td>
                                    <td>`+end.format('DD/MM/YYYY HH:mm')+`</td>
                                    </tr>`;
                        selection2=selection2+"</tbody></table>";
                    
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
                            }
                        $('#calendar').fullCalendar( 'renderEvent',backevent,true);

                        selected2=true;

                        //Send selected dates to db
                        $('#reserve').one('click',function(){ 
                            if(selected2){ //If there is no selection avoid post handlers acumulated
                                //var title=$('#titletext').val();
                                $('#selection2').html('');
                                $('#reservetext').addClass('hidden');
                                selection2='';
                                $.post(
                                    '../scripts/loadevents.php',
                                    {
                                        moment:'reserve',
                                        startev:start.format(),
                                        endev:end.format(),
                                        titleev:username,
                                        evdni:<?php echo $_SESSION['logged']; ?>,
                                        officenumber:office
                                    },
                                    function(data){
                                        console.log(data);
                                        $('#selection2').html(data);
                                        $('#calendar').fullCalendar( 'removeEvents',2 ); //Remove green highlight
                                        $('#calendar').fullCalendar( 'refetchEvents' );
                                        //$('#titletext').val('');
                                    }
                                );
                            }
                        });
                    },

                    //Events load function
                    events: function(start,end,timezone,callback){
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

                //Cancel remove selected events button
                $('#cancelselection').click(function(){
                    $('#calendar').fullCalendar( 'removeEvents',1 );
                    $('#selection').html('');
                    $('#cancelevent').addClass('hidden');
                    $('#response').html('');
                    selected=false;
                    selection='';
                    alreadyselected=false;
                    selectedevents=[];
                    $('#deleteevent').click(); //dump click handlers
                });

                //Remove selected events button
                $('#removeevents').click(function(){
                    $('#calendar').fullCalendar( 'removeEvents',2 );
                    $('#selection2').html('');
                    //$('#titletext').val('');
                    $('#reservetext').addClass('hidden');
                    $('#response').html('');
                    selected2=false;
                    selection2='';
                    $('#reserve').click(); //dump click handlers
                });

                //Get user name by id
                $.post(
                    '../scripts/getname.php',
                    {
                        userdni:<?php echo $_SESSION['logged']; ?>
                    },
                    function(data){
                        $('#welcome').append(data)
                        username='Dr./Dra. ';
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
                    //$('#titletext').val('');
                    selected=false;
                    selected2=false;
                    selection='';
                    selection2='';
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
                    //$('#titletext').val('');
                    selected=false;
                    selected2=false;
                    selection='';
                    selection2='';
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
                    <h1 id='welcome' style='text-transform:capitalize;'>Bienvenido </h1>
                    <p></p>
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
                            <li>
                                <?php
                                    if(isset($_SESSION['admin'])){
                                        echo '<a class="userpanel" HREF = "admin.php">Panel de administrador</a>';
                                    }
                                ?>
                            </li>
                            <li><a href="changepass.php">Modificar contraseña</a></li>
                            <li style="float:right"><A class="active" HREF = "../scripts/logout.php">Cerrar sesion</A></li>
                        </ul>
                    </div> 
                    <p></p>
                    <h3>En esta seccion usted puede:</h3>
                    <ul>
                        <li>Ver todas las reservas y horarios disponibles.</li>
                        <li>Cancelar reservas haciendo click en las mismas y seleccionando "Borrar evento" (Siempre que esten dentro del plazo permitido).</li>
                        <li>Realizar una nueva reserva <b>ARRASTRANDO</b> el raton sobre los dias deseados y seleccionando "Reservar" (Notese que puede ver y reservar por horas en la pestaña "Semana").</li>
                        <li><errorspan>Recuerde que los horarios de reserva solo pueden ser de Lunes a Viernes (9:00 a 13:00 y 16:00 a 20:00) y Sabados de 9:00 a 13:00. </errorspan></li>
                        <li>Disponemos de 2 consultorios y cada uno posee su respectivo calendario, puede seleccionar cual mostrar en las opciones arriba del mismo.</li>
                    </ul>
                    <p></p>
                    <hr>
                    <h3 id='response'></h3>
                    <h3 id='selection'></h3>
                    <h3 id='selection2'></h3>
                    <div id="cancelevent" class="hidden">
                        <input class="btn2" type="button" value="Borrar eventos" id="deleteevent">
                        <input class="btn" type="button" value="Cancelar" id="cancelselection">
                    </div>
                    <div id="reservetext" class="hidden">
                        <!--<h3>Ingrese titulo de reserva</h3>
                        <input type="text" id="titletext"> -->
                        <input class="btn3" type="button" value="Reservar" id="reserve">
                        <input class="btn" type="button" value="Cancelar" id="removeevents">
                    </div>      
                </div>
			</div>
		</div>
        <script src="../templates/footer.js"></script>
    </body>
</html>