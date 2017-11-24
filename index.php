<?php
	session_start();
?>
<!DOCTYPE HTML>
<HTML>
	<HEAD>
		<TITLE>Consultorios Villa Martina</TITLE>
		<meta charset="UTF-8">
		<link rel="shortcut icon" href="images/favicon.png" type="image/png">
		<link rel="stylesheet" href="css/index.css" type="text/css">
		<script src="scripts/jquery-3.2.1.min.js"></script>
		<SCRIPT src="scripts/index.js"></SCRIPT>
		<script>

			$(document).ready(function(){
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
	</HEAD>

	<BODY>
		<div class="container">
			<div class="header">
				<ul class="headerlist">
					<li>
						<a href="index.html">
							<img class="logo" src="images/vmlogo.png" height="60px" width="125px">
						</a>
					</li>
					<li>
						<h1 class="header-heading">Consultorios Villa Martina</h1>
					</li>
				</ul>
			</div>
		</div>
		<div class="nav-bar">
			<div class="container">
				<ul class="nav">
					<li>
						<a href="index.php">Inicio</a>
					</li>
					<li>
						<a href="pages/imagenes.html">Imagenes</a>
					</li>
					<li>
						<a href="pages/contacto.html">Contacto</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="content">
			<div class="container">
				<div class="main">
					<h3>Que es Consultorios Villa Martina?</h3>
					<p>
						Es un inmueble ubicado en la esquina de Colon y 3 de Febrero en
						la ciudad de Rosario. Esta destinado a 
						profesionales que buscan desarrollar sus actividades
						laborales de forma flexible y a pedido, con horarios 
						a eleccion y demanda segun su disponibilidad.
					</p>
					<h3>Como me pongo en contacto y/o hago una reserva?</h3>
					<p>
						Mediante este sitio web cada usuario puede verificar
						las oficinas disponibles y los horarios de las mismas
						como asi tambien realizar la reserva correspondiente
						a nombre del interesado.
					</p>
					<h3>Como funciona?</h3>
					<p>
						Una vez realizada la reserva y proporcionados los
						datos, nos contactaremos con usted para acordar
						la prestacion del servicio.
					</p>
					<h3>Que tipos de reservas puedo realizar?</h3>
					<p>
						Se puede reservar una o mas oficinas mediante las 
						siguientes modalidades:
						<ul class="reserv">
							<li>Por hora</li>
							<li>Por dia</li>
							<li>Por semana</li>
							<li>Por mes</li>
						</ul>
					</p>
					<hr>
					<table class="scroller">
						<tr>
							<td>
								<img  id="back" class="back" width="200" height="350" src="images/back2.png" onclick="scrollbackward()">
							</td>
							<td >
								<img class="mainimage" width="600" height="350" src="images/interior1.jpg" name="pic">
							</td>
							<td >
								<img id="forw" class="forward" width="200" height="350" src="images/forward2.png" onclick="scrollforward()">
							</td>
						</tr>
					</table>
				</div>
				<div class="aside">
					<div class="imgcontainer">
						<a href='pages/login.php'><img class="avatar" height="250" width="250" src="images/avatar2.png" alt="Avatar"></a>
					</div>
					<p></p>
					<?php 
						//Hide login panel if logged
						if(!isset($_SESSION['logged'])){
						echo '<form action="pages/login.php" method="post">
							<div class="logincontainer">
								<label><b>Usuario</b></label>
								<input type="text" maxlength="9" placeholder="Ingresar su DNI" name="logindni" required>

								<label><b>Contraseña</b> (No mas de 16 caracteres)</label>
								<input type="password" maxlength="16" placeholder="Ingresar contraseña" name="loginpass" required>

								<button class="btn" type="submit" >Ingresar</button>
							</div>
						</form>
						<p></p>
						<button class="btn"  id="recoverbtn" >Olvido su contraseña?</button>
						<p></p>
						<div id="ajaxsuccess"></div>
						<div class="hidden" id="recovertext">
							<input type="text" id="recoverdni" placeholder="Ingrese su DNI">
							<input type="email" id="recoveremail" placeholder="Ingrese su email">
							<button class="btn" id="sendrecbtn" >Enviar</button>
						</div>';
						}else{
							echo '<div align="center">
								<A class="btn" HREF = pages/login.php>Panel de usuario</A>
								<P></P>
								<A class="btn2" HREF = "scripts/logout.php">Cerrar sesion</A>
							</div>';
						}
					?>
					<p></p>
					<hr>
					<blockquote>
						<p id="quote">
							Siempre que te pregunten si puedes hacer un 
							trabajo, contesta que sí y ponte enseguida a 
							aprender como se hace.
						</p>
						<footer id="qfooter">Franklin D. Roosevelt</footer>	
					</blockquote>
					<hr>
				</div>
			</div>
		</div>
		<div class="footer">
			<div class="container">
				<img height="60px" width="125px" src="images/vmlogo.png"> 
			</div>
		</div>
	</BODY>
</HTML>
