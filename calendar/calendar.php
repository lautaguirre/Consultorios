<?php
    session_start();

    if(!isset($_SESSION['logged'])){
        header ("Location: ../index.php");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Calendario</title>
        <link rel='stylesheet' href='fullcalendar.css' />
        <script src='lib/jquery.min.js'></script>
        <script src='lib/moment.min.js'></script>
        <script src='fullcalendar.js'></script>
        <script src='locale/es.js'></script>
        <meta charset="UTF-8">
		<link rel="shortcut icon" href="../images/favicon.png" type="image/png">
        <link rel="stylesheet" href="../css/index.css">
        <script>
            $(document).ready(function() {
                var selection='';
                var selected=false;
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
                    navLinks:true,
                    noEventsMessage: 'No hay eventos para mostrar',
                    selectable: true,
                    selectHelper:true,
                    selectOverlap:false,
                    selectMinDistance:50,
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

                    //Select callback
                    select: function(start, end){          

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
                                    <td >`+start.format()+`</td>
                                    <td>`+end.format()+`</td>
                                    </tr>`;
                        selection=selection+"</tbody></table>";
                    
                        $('#selection').html(selection);
                        console.log(selection);

                        $('#reservetext').removeClass('hidden'); //Show title input and reserve submit button

                        //Unselect method
                        $('#calendar').fullCalendar( 'unselect' );

                        //Render selected event as background event
                        var backevent=
                            {
                                'id':1,
                                'start':start.format(),
                                'end':end.format(),
                                'backgroundColor':'green'
                            }
                        $('#calendar').fullCalendar( 'renderEvent',backevent,true);

                        selected=true;

                        //Send selected dates to db
                        $('#reserve').one('click',function(){ 
                            if(selected){ //If there is no selection avoid post handlers acumulated
                                var title=$('#titletext').val();
                                $('#selection').html('');
                                $('#reservetext').addClass('hidden');
                                selection='';
                                $.post(
                                    'loadevents.php',
                                    {
                                        moment:'reserve',
                                        startev:start.format(),
                                        endev:end.format(),
                                        titleev:title,
                                        evdni:<?php echo $_SESSION['logged']; ?>,
                                        officenumber:office
                                    },
                                    function(data){
                                        console.log(data);
                                        $('#selection').html(data);
                                        $('#calendar').fullCalendar( 'removeEvents',1 ); //Remove green highlight
                                        $('#calendar').fullCalendar( 'refetchEvents' );
                                        $('#titletext').val('');
                                    }
                                );
                            }
                        });
                    },

                    //Events load function
                    events: function(start,end,timezone,callback){
                        //Request events
                        $.post(
                            'loadevents.php',
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

                //Remove selected events button
                $('#removeevents').click(function(){
                    $('#calendar').fullCalendar( 'removeEvents',1 );
                    $('#selection').html('');
                    $('#titletext').val('');
                    $('#reservetext').addClass('hidden');
                    selected=false;
                    selection='';
                    $('#reserve').click(); //dump click handlers
                });

                //Select office
                $('#office1').click(function(){
                    office=1;
                    $("#office1").addClass('reserve');
                    $("#office2").removeClass('reserve');

                    //Reset
                    $('#calendar').fullCalendar( 'removeEvents',1 );
                    $('#selection').html('');
                    $('#titletext').val('');
                    $('#reservetext').addClass('hidden');
                    selected=false;
                    selection='';
                    $('#reserve').click(); //dump click handlers

                    $('#calendar').fullCalendar( 'refetchEvents' );
                });
                $('#office2').click(function(){
                    office=2;
                    $("#office2").addClass('reserve');
                    $("#office1").removeClass('reserve');

                    //Reset
                    $('#calendar').fullCalendar( 'removeEvents',1 );
                    $('#selection').html('');
                    $('#titletext').val('');
                    $('#reservetext').addClass('hidden');
                    selected=false;
                    selection='';
                    $('#reserve').click(); //dump click handlers

                    $('#calendar').fullCalendar( 'refetchEvents' );
                });

            });
        </script>
    </head>

    <body>
        <?php
            if(isset($_SESSION['logged'])){
                echo '<div class="container">
                    <div class="header2">
                        <ul class="headerlist">
                            <li>
                                <A class="btn2" HREF = "../pages/logout.php">Cerrar sesion</A>
                            </li>
                        </ul>
                    </div>
                </div>';
            }
        ?>
        <script src="../templates/calendarheader.js"></script>
        <div class="content">
			<div class="container">  
                <div class='main'>
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
                <div class='aside'>
                    <div class='horizontalnavbar'>
                        <ul>
                            <li><A HREF = "../pages/login.php">Panel de usuario</A></li>
                            <li>
                                <?php
                                    if(isset($_SESSION['admin'])){
                                        echo '<a HREF = "../pages/admin.php">Panel de administrador</a>';
                                    }
                                ?>
                            </li>
                        </ul>
                    </div>
                    <hr>
                    <h3 id="response"></h3>
                    <h3 id="selection"></h3>
                    <div id="reservetext" class="hidden">
                        <h3>Ingrese titulo de reserva</h3>
                        <input type="text" id="titletext">
                        <input class="btn3" type="button" value="Reservar" id="reserve">
                        <input class="btn2" type="button" value="Cancelar" id="removeevents">
                    </div>
                </div>
			</div>
		</div>
        <script src="../templates/calendarfooter.js"></script>
    </body>
</html>