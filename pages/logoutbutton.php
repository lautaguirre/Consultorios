<?php
    if(isset($_SESSION['logged'])){
		echo '<div class="container">
			<div class="header2">
				<ul class="headerlist">
					<li>
						<A class="btn2" HREF = "logout.php">Cerrar sesion</A>
                    </li>
				</ul>
			</div>
		</div>';
	}
?>