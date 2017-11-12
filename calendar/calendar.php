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

                //Calendar config
                $('#calendar').fullCalendar({
                    //Header buttons
                    header: {
				        left: 'prevYear,nextYear today',
				        center: 'prev title next',
				        right: 'month,agendaWeek,agendaDay listMonth'
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

                    /* Click callback
                        dayClick: function(date) {
                        alert('Clicked on: ' + date.format());
                    },*/

                    //Event click callback
                    eventClick:function(event){
                        alert('Titulo de evento: '+event.title);
                    },
                    //Select callback
                    select: function(start, end){          

                        //Show selected events
                        selection=selection.concat('Comienzo de reserva: '+start.format()+'<BR>Final de reserva: '+end.format()+'<BR>'); 
                        $('#selection').html(selection);
                        console.log(selection);

                        $('#reservetext').removeClass('hidden'); //Show title input and reserve submit button

                        //Unselect method
                        $('#calendar').fullCalendar( 'unselect' );
                        var backevent=
                            {
                                'id':1,
                                'start':start.format(),
                                'end':end.format(),
                                'backgroundColor':'green'
                            }
                        $('#calendar').fullCalendar( 'renderEvent',backevent,true) //Render selected event as background event
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
                                        evdni:<?php echo $_SESSION['logged']; ?>
                                    },
                                    function(data){
                                        console.log(data);
                                        $('#selection').html(data);
                                        $('#calendar').fullCalendar( 'refetchEvents' );
                                        $('#calendar').fullCalendar( 'removeEvents',1 ); //Remove green highlight
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
            });
        </script>
    </head>

    <body>
        <script src="../templates/calendarheader.js"></script>
        <div class="content">
			<div class="container">  
                <div class='main'>
                    <div id='calendar'></div>
                </div>
                <div class='aside'>
                    <form action="../pages/login.php">
                        <button type="submit" class="btn" name="gotologin">Panel de usuario</button>
                    </form>
                    <hr>
                    <h3 id="response"></h3>
                    <h3 id="selection"></h3>
                    <div id="reservetext" class="hidden">
                        <h3>Ingrese titulo de reserva</h3>
                        <input type="text" id="titletext">
                        <input class="btn" type="button" value="Reservar" id="reserve">
                        <input class="btn" type="button" value="Borrar selecion" id="removeevents">
                    </div>
                </div>
			</div>
		</div>
        <script src="../templates/calendarfooter.js"></script>
    </body>
</html>