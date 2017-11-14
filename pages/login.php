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
                var alreadyselected=false;
                var selectedevents=[];

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
                    navLinks:true,
                    noEventsMessage: 'No hay eventos para mostrar',
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
                        for(selectedevi=0;selectedevi<selectedevents.length;selectedevi++){
                            if(selectedevents[selectedevi]==event.id){
                                alreadyselected=true;
                                break;
                            }else{
                                alreadyselected=false;
                            }
                        }
                        if(event.id!=1 && alreadyselected==false){
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
                                    <td >`+event.start.format()+`</td>
                                    <td>`+event.end.format()+`</td>
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
                                        'deleteevents.php',
                                        {
                                            startev:event.start.format(),
                                            endev:event.end.format()
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

                    //Events load function
                    events: function(start,end,timezone,callback){
                        //Request events
                        $.post(
                            '../calendar/loadevents.php',
                            {
                                moment:'onlogin',
                                evdni:<?php echo $_SESSION['logged']; ?>
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
                    selected=false;
                    selection='';
                    alreadyselected=false;
                    selectedevents=[];
                    $('#deleteevent').click(); //dump click handlers
                });

                //Get user name by id
                $.post(
                    'getname.php',
                    {
                        userdni:<?php echo $_SESSION['logged']; ?>
                    },
                    function(data){
                        $('#welcome').html(data);
                    }
                );

            });
        </script>
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
                    <h1 id='welcome' style='text-transform:capitalize;'></h1>
                    <p></p>
                    <div id='calendar'></div>
                </div>
                <div class='aside'>
                    <div class='horizontalnavbar'>
                        <ul>
                            <li><a class='reserve' href="../calendar/calendar.php">Hacer una reserva</a></li>
                            <li>
                                <?php
                                    if(isset($_SESSION['admin'])){
                                        echo '<a HREF = "admin.php">Panel de administrador</a>';
                                    }
                                ?>
                            </li>
                            <li style="float:right"><a class="active" href="changepass.php">Modificar contraseña</a></li>
                        </ul>
                    </div> 
                    <hr>
                    <h3 id='response'></h3>
                    <h3 id='selection'></h3>
                    <div id="cancelevent" class="hidden">
                        <input class="btn2" type="button" value="Borrar eventos" id="deleteevent">
                        <input class="btn" type="button" value="Cancelar" id="cancelselection">
                    </div>      
                </div>
			</div>
		</div>
        <script src="../templates/footer.js"></script>
    </body>
</html>