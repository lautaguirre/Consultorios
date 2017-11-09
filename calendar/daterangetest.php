<?php
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $date = date('Y-m-d h:i:s', time());
    echo $date.'<BR>';

    $date=substr_replace($date,'T',10,1);

    echo $date;
?>