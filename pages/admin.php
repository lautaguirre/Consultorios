<?php
    session_start();
    //Check admin session
    require '../scripts/checkadminsession.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Panel de administrador</title>
        <meta charset="utf-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="../images/favicon.png" type="image/png">
        <link rel='stylesheet' type="text/css" href='../calendar/fullcalendar.min.css' />
        <script src='../calendar/lib/moment.min.js'></script>
        <script src='../calendar/fullcalendar.min.js'></script>
        <script src='../calendar/locale/es.js'></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/index.css" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function(){

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

                $('#calendar').fullCalendar({
                    //Calendar config
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
                    longPressDelay:500,
                    minTime:"09:00:00",
                    maxTime:"20:00:00",
                    allDaySlot:false,
                    slotEventOverlap:false,
                    timeFormat:'HH(:mm)',
                    slotLabelFormat:'HH(:mm)A',
                    displayEventEnd:true,
                    slotDuration:'01:00:00',
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
                        if(event.id!=2 && event.id!=1 && alreadyselected==false && moment().isBefore(event.start.format())){
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

                //Delete selected events
                $('#deleteevent').click(function(){
                    if(selected){
                        $('#selection').html('');
                        $('#cancelevent').addClass('hidden');
                        alreadyselected=false;
                        selectedevents=[];
                        clickjson=JSON.stringify(clickevarr);
                        clickevobj={};
                        clickevarr=[];
                        $.post(
                            '../scripts/admindeleteevents.php', 
                            {
                                clickevjson:clickjson,
                                officenumber:office
                            },
                            function(data){
                                console.log(data);
                                if(data=='<div class="alert alert-warning"><strong>Atencion!</strong> Error borrando reserva, puede que haya elegido varios usuarios diferentes.</div><BR>'){
                                    $('#selection').html(data);
                                }else{
                                    admindeletedata=JSON.parse(data);
                                    $.post(
                                        '../scripts/admindeleteemail.php',
                                        {
                                            deleteemailbody:selection,
                                            deleteemail:admindeletedata.useremail
                                        }
                                    );
                                    $('#selection').html(admindeletedata.msg);
                                }
                                selectionnumber=0;
                                selection=`<table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Comienzo</th>
                                            <th>Fin</th>
                                        </tr>
                                    </thead>`;
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
                        selectarr=[];
                        selectobj={};
                        $.post(
                            '../scripts/loadevents.php',
                            {
                                moment:'reserve',
                                evjson:jsonarr,
                                titleev:evdesc,
                                evdni:<?php echo $_SESSION['logged']; ?>,
                                officenumber:office,
                            },
                            function(data){
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
                    selectarr=[];
                    selectobj={};
                    selected2=false;
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
                    selectarr=[];
                    selectobj={};
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
                    selectarr=[];
                    selectobj={};
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

                //Get month resume
                $('#getmonthform').submit(function(e){
                    e.preventDefault();
                    month=$('#monthselector').val();
                    monthdni='';
                    monthdni=$('#monthdni').val();
                    monthend = moment(month).endOf('month');
                    monthstart = moment(month).startOf('month');
                    ME=monthend.format('YYYY-MM-DD HH:mm:ss');
                    MS=monthstart.format('YYYY-MM-DD HH:mm:ss');
                    MS=MS.replace(' ','T');
                    ME=ME.replace(' ','T');
                    totalhours=0;
                    $.post(
                        '../scripts/getmonth.php',
                        {
                            getmonthstart:MS,
                            getmonthend:ME,
                            getmonthdni:monthdni
                        },
                        function(data){
                            monthev=JSON.parse(data);
                            totaltable='';
                            thishours=0;
                            panelcount=1;
                            namecount=0;
                            while(namecount<monthev.length){
                                if(typeof monthev[namecount].monthdni!=='undefined'){
                                    dnicategory='  DNI: <b>'+monthev[namecount].monthdni+'</b>';
                                    openedpanel='';
                                }else{
                                    dnicategory='';
                                    openedpanel=' in';
                                }
                                monthtable=`<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse`+panelcount+`">
                                                <div style="text-transform:capitalize;">`
                                                    +monthev[namecount].title+
                                                `</div>`
                                                +dnicategory+`
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse`+panelcount+`" class="panel-collapse collapse`+openedpanel+`">
                                        <div class="panel-body">`;
                                monthtable=monthtable+`<div class="table-responsive"><table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Comienzo</th>
                                            <th>Fin</th>
                                            <th>Consultorio</th>
                                        </tr>
                                    </thead>`;
                                monthpos=namecount;
                                while(monthpos<monthev.length && monthev[namecount].title==monthev[monthpos].title){
                                    totalhours=totalhours+moment(monthev[monthpos].start).diff(moment(monthev[monthpos].end),'hours');
                                    thishours=thishours+moment(monthev[monthpos].start).diff(moment(monthev[monthpos].end),'hours');
                                    monthtable=monthtable+`<tbody>
                                    <tr>           
                                        <td >`+moment(monthev[monthpos].start).format('DD/MM/YYYY HH:mm')+`</td>
                                        <td>`+moment(monthev[monthpos].end).format('DD/MM/YYYY HH:mm')+`</td>
                                        <td>`+monthev[monthpos].monthofficenumber+`</td>
                                    </tr>
                                    </tbody>`;
                                    monthpos=monthpos+1;
                                }
                                monthtable=monthtable+'</table></div><strong>Horas: '+Math.abs(thishours)+'</strong></div></div></div>';
                                thishours=0;
                                totaltable=totaltable+monthtable;
                                namecount=monthpos;
                                panelcount=panelcount+1;
                            }
                            totalhours=Math.abs(totalhours);
                            $('#adminphp').html('<strong>Horas totales: '+totalhours+'</strong><BR><p>&nbsp;</p>'+'<div class="panel-group" id="accordion">'+totaltable+'</div>');
                        }
                    );
                });

                //Hide collapse after click
                $('#collapseitems, #movecontact').click(function(){
                    $(".collapse").collapse('hide');
                });

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
                            <a href="changepass.php" id='collapseitems'>CAMBIAR CONTRASEÑA</a>
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
                    </ul>
                </div>
            </div>
        </nav>
        
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 tabletadmin">
                    <!-- Admin panel -->
                    <div class='row'>
                        <div class="col-sm-3">
                            <p>&nbsp;</p>
                            <h4>Crear usuario</h4>
                            <form action="admin.php" method='post'>
                                <div class="form-group">
                                    <input type="text" name='createname' required placeholder='Nombre' class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="text" name='createlastname' required placeholder='Apellido' class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="email" name='createemail' required placeholder='E-mail' class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="text" name='createphone'required placeholder='Telefono' class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="text" maxlength="9" name='createdni' required placeholder='DNI' class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="text" name='createaddress' required placeholder='Domicilio' class="form-control">
                                </div>
                                <button type="submit" name='createsubmit' class="btn btn-success">Crear</button>
                            </form>
                        </div>
                        <div class="col-sm-3">
                        <p>&nbsp;</p>
                            <h4>Actualizar usuario</h4>
                            <form action="admin.php" method='post'>
                                <div class="form-group has-success">
                                    <input type="text" maxlength="9" name='actdni' required placeholder='DNI de usuario a actualizar' class="form-control form-control-success">
                                </div>
                                <div class="form-group">
                                    <input type="text" name='actphone' placeholder='Telefono' class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="email" name='actemail' placeholder='E-mail' class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="text" name='actaddress' placeholder='Domicilio' class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="text" name='actname' placeholder='Nombre' class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="text" name='actlastname' placeholder='Apellido' class="form-control">
                                </div>
                                <button type="submit" name='createsubmit' name='actsubmit' class="btn btn-success">Actualizar</button>
                            </form>
                        </div>
                        <div class="col-sm-3">                           
                            <div class='row'>
                                <p>&nbsp;</p>
                                <h4>Inhabilitar usuario</h4>
                                <form action="admin.php" method='post'>
                                    <div class="form-group">
                                        <input type="text" maxlength="9" name='unauthdni' required placeholder='DNI de usuario a deshabilitar' class="form-control">
                                    </div>
                                    <button type="submit" name='unauthsubmit' class="btn btn-danger">Inhabilitar</button>
                                </form>
                            </div>
                            <div class='row'>
                                <h4>Habilitar</h4>
                                <form action="admin.php" method='post'>
                                    <div class="form-group">
                                        <input type="text" maxlength="9" name='authdni' required placeholder='DNI de usuario a habilitar' class="form-control">
                                    </div>
                                    <button type="submit" name='authsubmit' class="btn btn-success">Habilitar</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-sm-3">
                        <p>&nbsp;</p>
                            <h4>Consultar usuario</h4>
                            <form action="admin.php" method='post'>
                                <div class="form-group">
                                    <input type="text" name='consultname' placeholder='Nombre' class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="text" name='consultlastname' placeholder='Apellido' class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="text" maxlength="9" name='consultdni' placeholder='DNI' class="form-control">
                                </div>
                                <button type="submit" name='consultsubmit' class="btn btn-success">Consultar</button>
                            </form>
                        </div>
                    </div>
                    <p>&nbsp;</p>
                    <!-- Calendar -->
                    <div class='row'>
                        <nav class="fakenavbar navbar-default" >
                            <div class="container-fluid" style='padding:0;'>
                                <div class="navbar-header">
                                    <a class="navbar-brand" >Mostrar:</a>
                                </div>
                                <ul class="nav navbar-nav">
                                    <li id='office1' class='active'><a ><strong>CONSULTORIO 1</strong></a></li>
                                    <li id='office2'><a ><strong>CONSULTORIO 2</strong></a></li>
                                </ul>
                            </div>
                        </nav>
                        <p>&nbsp;</p>
                        <div id='calendar'></div>
                    </div>
                </div>
                <!-- Response and month resume -->
                <div class="col-sm-6 tabletadmin tabletaside">
                    <p>&nbsp;</p>
                    <form id="getmonthform">
                        <h4>Consultar mes</h4>
                        <div class="form-group">
                            <input type="text" maxlength="9" name="monthdni" id="monthdni" placeholder='DNI' class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="monthselector">Seleccione un mes:</label>
                            <input type="month" name="monthselector" id="monthselector" class="form-control">
                        </div>
                        <button type="submit" id='monthselect' class="btn btn-success">Consultar mes</button>
                        <h4><div id='adminphp'></div></h4>
                    </form>
                    <?php
                        require '../scripts/admincore.php'
                    ?>
                    <p>&nbsp;</p>
                    <h4 id='selection'></h4>
                    <h4 id='selection2'></h4>
                    <div id="cancelevent" class="hidden">
                        <button type="button" class="btn btn-success" id="deleteevent">Borrar eventos</button>
                        <button type="button" class="btn btn-danger" id="cancelselection">Cancelar</button>
                    </div>
                    <div id="reservetext" class="hidden">
                        <h3>Descripcion de la reserva (Si no ingresa nada se usara por defecto su nombre y apellido).</h3>
                        <div class='form-group' >
                            <input type="text" id="titletext" class='form-control'>
                        </div>
                        <button type="button" class="btn btn-success" id="reserve">Reservar</button>
                        <button type="button" class="btn btn-danger" id="removeevents">Cancelar</button>
                    </div> 
                </div>
            </div>
        </div>

        <!-- Footer --> 
        <script src="../templates/footer.js"></script>
    </body>
</html>