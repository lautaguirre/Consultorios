<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
  <title>Consultorios Villa Martina</title>
  <meta charset="utf-8">
  <META NAME="description" CONTENT="Consultorios Villa Martina: reserva de consultorios a pedido con horarios flexibles para todo tipo de profesionales que buscan desarrollar sus actividades.">
  <META NAME="Keywords" CONTENT="consultorios,villa martina,reservar,rosario">
  <link rel="shortcut icon" href="images/favicon.png" type="image/png">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/index2.css" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function () {
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

      //Fade-in icons when scrolling down
      $(window).scroll(function () {
        $(".slideanim").each(function () {
          var pos = $(this).offset().top;

          var winTop = $(window).scrollTop();
          if (pos < winTop + 600) {
            $(this).addClass("slide");
          }
        });
      });

      //Scroll to contact from <A>
      $('#aboutcontact').click(function(){
        $('#movecontact').click();
      });

      //Hide collapse after click
      $('#collapseitems, #movecontact').click(function(){
        $(".collapse").collapse('hide');
      });

      //Recover password 
      $('#recoverbtn').click(function(){
					$('#recoverbtn').hide();
					$('#ajaxsuccess').html('');
					$('#recovertext').removeClass('hidden');
				});

				$('#sendrecbtn').click(function(){
					dnitorecover=$('#recoverdni').val();
					emailtorecover=$('#recoveremail').val();
					$('#recoverbtn').show();
					$('#recovertext').addClass('hidden');

					$.post(
	          'scripts/recoverpass.php',
            {
							dni:dnitorecover,
							email:emailtorecover
        	  },
    	      function(data){
							$('#ajaxsuccess').html(data);
							$('#recoverdni').val('');
							$('#recoveremail').val('');
            }
          );
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
            <a href="#services" id='collapseitems'>SERVICIOS</a>
          </li>
          <li>
            <a href="#portfolio" id='collapseitems'>IMAGENES</a>
          </li>
          <li>
              <a href="#pricing" id='collapseitems'>PRECIOS</a>
          </li>
          <li>
            <a href="#contact" id="movecontact">CONTACTO</a>
          </li>
              <?php
              if(!isset($_SESSION['logged'])){
                echo '<li>
                <a href="#" id="collapseitems" class="alterlogo" data-toggle="modal" data-target="#loginmodal">
                  <span class="glyphicon glyphicon-log-in"></span>
                  INGRESAR
                </a>
                </li>';
              }else{
                echo '<li>
                  <a href="pages/login.php" id="collapseitems" class="alterlogo">
                    <span class="glyphicon glyphicon-user"></span>
                    PANEL DE USUARIO
                  </a>
                </li>
                <li>
                  <a class="alterlogo2" href="scripts/logout.php">
                    <span class="glyphicon glyphicon-log-out"></span>
                  </a>
                </li>';
              }
              ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Modal -->
  <div id="loginmodal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
  
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Ingrese su DNI y contraseña</h4>
        </div>
        <div class="modal-body">
          <form action="pages/login.php" method='post'>
            <div class="form-group">
              <input type="text" maxlength="9" name="logindni" required placeholder="DNI" class="form-control" id="logindni">
            </div>
            <div class="form-group">
              <input type="password" maxlength="16" name="loginpass" required placeholder="Contraseña" class="form-control" id="loginpass">
            </div>
            <button type="submit" class="btn btn-success">Ingresar</button>
          </form>
        </div>
        <div class='modal-footer'>
          <button class="btn btn-danger"  id="recoverbtn" >Olvido su contraseña?</button>
          <p><div id="ajaxsuccess"></div></p>
            <div class="hidden" id="recovertext">
              <div class="form-group">
                <input type="text" class="form-control" maxlength="9" id="recoverdni" placeholder="DNI">
              </div>
              <div class="form-group">
                <input type="email" class="form-control" id="recoveremail" placeholder="E-mail">
              </div>
              <button class="btn btn-danger" id="sendrecbtn" >Recuperar</button>
            </div>                                 
        </div>
      </div>
  
    </div>
  </div>

  <div class="jumbotron text-center">
    <img src="images/vmlogo.png" class="vmlogo img-circle" width="25%">
  </div>

  <!-- About Section -->
  <div id="about" class="container-fluid">
    <div class="row">
      <div class="col-sm-8">
        <h2>Que es Consultorios Villa Martina?</h2>
        <br>
        <h4>Es un inmueble ubicado en la esquina de Colon y 3 de Febrero en la ciudad de Rosario. Esta destinado a profesionales
            que buscan desarrollar sus actividades laborales de forma flexible y a pedido, con horarios a eleccion y demanda segun su disponibilidad.
        </h4>
        <br>
        <h2>Como me pongo en contacto y/o hago una reserva?</h2>
        <br>
        <h4>Mediante este sitio web cada usuario puede verificar las oficinas disponibles y los horarios de las mismas como asi tambien realizar la 
            reserva correspondiente a nombre del interesado. Recordando siempre que se necesita de una cuenta para dichas acciones.
        </h4>
      </div>
      <div class="col-sm-4">
        <span class="glyphicon glyphicon-education logo align-middle" style="float:right;"></span>
      </div>
    </div>
  </div>

  <div class="container-fluid bg-success">
    <div class="row">
      <div class="col-sm-4">
        <span class="glyphicon glyphicon-time logo slideanim"></span>
      </div>
      <div class="col-sm-8">
        <h2>Como consigo una cuenta?</h2>
        <br>
        <h4>Para que le sea asignada una cuenta, usted debe ponerse en contacto con los administradores del establecimiento (Puede hacerlo desde la seccion <a style="cursor:pointer;" id="aboutcontact">CONTACTO</a>), estos le solicitaran
          su informacion para poder ingresarlo en el sistema y automaticamente tendra acceso al sector de reservas.
        </h4>
        <br>
        <h2>Que tipos de reservas puedo realizar?</h2>
        <br>
        <h4>Disponemos de 2 oficinas en las cuales se pueden realizar reservas por horas. Tenga en cuenta que nuestros horarios de trabajo son de <strong>Lunes a Viernes
          (09hs a 13hs y 16hs a 20hs) y los Sabados (09hs a 13hs)</strong>.
        </h4>
      </div>
    </div>
  </div>

  <!-- Services Section -->
  <div id="services" class="container-fluid text-center">
    <h2>SERVICIOS</h2>
    <h4>Que ofrecemos</h4>
    <br>
    <div class="row slideanim">
      <div class="col-sm-4">
        <span class="glyphicon glyphicon-cutlery logo-small"></span>
        <h4>COCINA/ZONA DE ESPERA</h4>
        <p>Un sector donde los pacientes pueden esperar a ser atendidos.</p>
      </div>
      <div class="col-sm-4">
        <span class="glyphicon glyphicon-tint logo-small"></span>
        <h4>BAÑOS</h4>
        <p>Disponibles para los clientes y profesionales.</p>
      </div>
      <div class="col-sm-4">
        <span class="glyphicon glyphicon-lock logo-small"></span>
        <h4>SEGURIDAD</h4>
        <p>Camaras de vigilancia y monitoreo para garantizar la seguridad de los clientes.</p>
      </div>
    </div>
    <br>
    <br>
    <div class="row slideanim">
      <div class="col-sm-12">
        <span class="glyphicon glyphicon-thumbs-up logo-small"></span>
        <h4>COMPROMISO</h4>
        <p>Nos comprometemos a brindar la mejor atencion.</p>
      </div>
    </div>
  </div>

  <!-- Images -->
  <div id="portfolio" class="container-fluid text-center bg-success">
    <h2>IMAGENES</h2>
    <br>
    <h4>Como son nuestros consultorios</h4>
    <div class="row text-center slideanim">
      <div class="col-sm-4">
        <div class="thumbnail">
          <img src="images/interior1.jpg" class='flatbottomrounded' width="400" height="300">
          <p>
            <strong>Consultorio 1</strong>
          </p>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="thumbnail">
          <img src="images/interior2.jpeg" class='flatbottomrounded' width="400" height="300">
          <p>
            <strong>Consultorio 2</strong>
          </p>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="thumbnail">
          <img src="images/interior3.jpg" class='flatbottomrounded' width="400" height="300">
          <p>
            <strong>Consultorio 1</strong>
          </p>
        </div>
      </div>
    </div>
    <div class="row text-center slideanim">
      <div class="col-sm-4">
        <div class="thumbnail">
          <img src="images/interior4.jpg" class='flatbottomrounded' width="400" height="300">
          <p>
            <strong>Consultorio 1</strong>
          </p>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="thumbnail">
          <img src="images/interior5.png" class='flatbottomrounded' width="400" height="300">
          <p>
            <strong>Consultorio 2</strong>
          </p>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="thumbnail">
          <img src="images/interior6.jpg" class='flatbottomrounded' width="400" height="300">
          <p>
            <strong>Consultorio 1</strong>
          </p>
        </div>
      </div>
    </div>
    <br>
    
    <!-- Quotes section -->
    <div id="myCarousel" class="carousel slide text-center" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
      </ol>

      <!-- Slides -->
      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <h4>"Siempre que te pregunten si puedes hacer un trabajo, contesta que sí y ponte enseguida a aprender como se hace."
            <br>
            <span>- Franklin D. Roosevelt</span>
          </h4>
        </div>
        <div class="item">
          <h4>"Proceder con honestidad en aras de la dignidad del hombre es el compromiso más trascendente en nuestro corto paso por este mundo."
            <br>
            <span>- René Favaloro</span>
          </h4>
        </div>
        <div class="item">
          <h4>"La simplicidad es la maxima sofisticacion."
            <br>
            <span>- Leonardo Da Vinci</span>
          </h4>
        </div>
      </div>

      <!-- Left and right controls -->
      <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
  </div>

  <!-- Pricing Section -->
  <div id="pricing" class="container-fluid">
    <div class="text-center">
      <h2>PRECIOS</h2>
    </div>
    <div class="row slideanim">
      <div class="col-sm-4 col-xs-12">
        <div class="panel panel-default text-center">
          <div class="panel-heading">
            <h1>Basic</h1>
          </div>
          <div class="panel-body">
            <p>
              <strong>20</strong> Lorem</p>
            <p>
              <strong>15</strong> Ipsum</p>
            <p>
              <strong>5</strong> Dolor</p>
            <p>
              <strong>2</strong> Sit</p>
            <p>
              <strong>Endless</strong> Amet</p>
          </div>
          <div class="panel-footer">
            <h3>$19</h3>
            <h4>per month</h4>
            <button class="btn btn-lg">Sign Up</button>
          </div>
        </div>
      </div>
      <div class="col-sm-4 col-xs-12">
        <div class="panel panel-default text-center">
          <div class="panel-heading">
            <h1>Pro</h1>
          </div>
          <div class="panel-body">
            <p>
              <strong>50</strong> Lorem</p>
            <p>
              <strong>25</strong> Ipsum</p>
            <p>
              <strong>10</strong> Dolor</p>
            <p>
              <strong>5</strong> Sit</p>
            <p>
              <strong>Endless</strong> Amet</p>
          </div>
          <div class="panel-footer">
            <h3>$29</h3>
            <h4>per month</h4>
            <button class="btn btn-lg">Sign Up</button>
          </div>
        </div>
      </div>
      <div class="col-sm-4 col-xs-12">
        <div class="panel panel-default text-center">
          <div class="panel-heading">
            <h1>Premium</h1>
          </div>
          <div class="panel-body">
            <p>
              <strong>100</strong> Lorem</p>
            <p>
              <strong>50</strong> Ipsum</p>
            <p>
              <strong>25</strong> Dolor</p>
            <p>
              <strong>10</strong> Sit</p>
            <p>
              <strong>Endless</strong> Amet</p>
          </div>
          <div class="panel-footer">
            <h3>$49</h3>
            <h4>per month</h4>
            <button class="btn btn-lg">Sign Up</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Contact Section -->
  <div id="contact" class="container-fluid bg-success">
    <h2 class="text-center">CONTACTO</h2>
    <div class="row">
      <div class="col-sm-5">
        <p>Contactenos para solicitar una cuenta o cualquier otra consulta.</p>
        <p>
          <span class="glyphicon glyphicon-map-marker"></span> 3 de Febrero 210, Rosario, Santa fe, Argentina</p>
        <p>
          <span class="glyphicon glyphicon-phone"></span> 341-000-0000 </p>
        <p>
          <span class="glyphicon glyphicon-envelope"></span> villamartina@villamartinarosario.com </p>
      </div>
      <div class="col-sm-7 slideanim">
        <div class="row">
          <div class="col-sm-6 form-group">
            <input class="form-control" id="name" name="name" placeholder="Nombre" type="text" required>
          </div>
          <div class="col-sm-6 form-group">
            <input class="form-control" id="email" name="email" placeholder="Email" type="email" required>
          </div>
        </div>
        <textarea class="form-control" id="comments" name="comments" placeholder="Mensaje" rows="5"></textarea>
        <br>
        <div class="row">
          <div class="col-sm-12 form-group">
            <button class="btn btn-default pull-right" type="submit">Enviar</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Google Maps -->
  <div id="googleMap" style="height:400px;width:100%;"></div>
  <script>
    function myMap() {
      var myCenter = new google.maps.LatLng(-32.9548699, -60.6272028);
      var mapProp = { center: myCenter, zoom: 17, scrollwheel: true, draggable: true, mapTypeId: google.maps.MapTypeId.ROADMAP };
      var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
      var marker = new google.maps.Marker({ position: myCenter });
      marker.setMap(map);
    }
  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8V7i630Wpsdd2zAi78jB_gjeOKfNsolo&callback=myMap"></script>

  <!-- Footer -->
  <footer class="container-fluid text-center">
    <a href="#myPage" title="To Top">
      <span class="glyphicon glyphicon-chevron-up"></span>
    </a>
    <p>Diseño web: <a href="#">Lautaro Aguirre</a>
    </p>
  </footer>

</body>

</html>