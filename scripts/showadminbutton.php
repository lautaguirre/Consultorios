<?php
    if(isset($_SESSION['admin'])){
        echo '<li>
            <a href="admin.php" id="collapseitems" class="alterlogo">
                <span class="glyphicon glyphicon-eye-open"></span>
                ADMIN
            </a>
        </li>';
    }
?>