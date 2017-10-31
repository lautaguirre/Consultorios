<?php
    session_start();
    $logindni=$loginpass='';
    $validation=true;

    //DB connection
    require '../pages/connection.php';

    if(isset($_POST['changepasssubmit'])){
        //Validation
        $changepass=sanitizestring('changepass');
        $changepassdni=sanitizeint('changepassdni');
        $changepass2=sanitizestring('changepass2');

        validateint($changepassdni);

        if($validation){
            if($changepass==$changepass2 and $changepassdni==$_SESSION['logged']){
                $hashednewpass=password_hash($changepass, PASSWORD_DEFAULT);
                $sql='UPDATE clientes SET password="'.$hashednewpass.'" WHERE dni='.$changepassdni;
                if(mysqli_query($conn,$sql)){
                    echo 'Contraseña actualizada<BR>';
                }
            }
        }
    }
    
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
        <title>Login</title>
        <link rel='stylesheet' href='fullcalendar.css' />
        <script src='lib/jquery.min.js'></script>
        <script src='lib/moment.min.js'></script>
        <script src='fullcalendar.js'></script>
        <script src='locale/es.js'></script>
        <script>
            $(document).ready(function() {

                $('#calendar').fullCalendar({
                    minTime:"09:00:00",
                    maxTime:"20:00:00",
                    slotEventOverlap:false,
                    timeFormat:'HH(:mm)',
                    slotLabelFormat:'HH(:mm)A',
                    displayEventEnd:true,
                    navLinks:true,
                    selectable: true,
                    selectHelper:true,
                    selectOverlap:false,
                    select: function(start, end){
                        alert('Comienza: '+start.format()+'\nTermina: '+end.format());                     
                    },
                    businessHours:[ 
                       {
                            dow: [ 1, 2, 3, 4, 5], 
                            start: '09:00', 
                            end: '13:00' 
                        },
                        {
                            dow: [ 1, 2, 3, 4, 5],
                            start: '16:00', 
                            end: '20:00' 
                        }
                    ],  
                    header: {
				        left: 'prevYear,nextYear today',
				        center: 'prev title next',
				        right: 'month,agendaWeek,agendaDay listMonth'
			        },
                    dayClick: function(date) {
                        alert('Clicked on: ' + date.format());
                    },
                    eventClick:function(event){
                        alert('Titulo de evento: '+event.title);
                        $(this).css('border-color', 'red');
                        if(event.url){
                            window.open(event.url);
                            return false;
                        }
                    },
                    events: [
                        {
                            title  : 'GoToGoogle',
                            start  : '2018-01-01',
                            url: 'http://google.com/'
                        },
                        {
                            title  : 'event2',
                            start  : '2018-01-05',
                            end    : '2018-01-07'
                        },
                        {
                            title  : 'event3',
                            start  : '2018-01-09T12:30:00',
                            end  : '2018-01-09T17:30:00',
                            allDay : false // will make the time show
                        }
                    ]
                });

            });
        </script>
        <meta charset="UTF-8">
		<link rel="shortcut icon" href="../images/favicon.png" type="image/png">
		<link rel="stylesheet" href="../css/index.css">
    </head>

    <body>
        <?php
        //Check if logged for logout button
		if(isset($_SESSION['logged'])){
		echo '<div class="container">
			<div class="header2">
				<ul class="headerlist">
					<li>
						<A class="btn" HREF = logout.php>Cerrar sesion</A>
					</li>
				</ul>
			</div>
		</div>';
		}
		?>
        <script src="../templates/header.js"></script>
        <div class="content">
			<div class="container">  
                <div class='main'>
                    <div id='calendar'></div>
                </div>
                <div class='aside'>
                
                </div>
			</div>
		</div>
        <script src="../templates/footer.js"></script>
    </body>
</html>