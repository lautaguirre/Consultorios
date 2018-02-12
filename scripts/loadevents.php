<?php
    require 'connection.php';

    //Define timezone and today date
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $todaydate = date('Y-m-d H:i:s', time());
    $todaydate=substr_replace($todaydate,'T',10,1);

    $validation=true; //Check overlap

    //Load the events on calendar startup
    if($_POST['moment']=='onload' and isset($_POST['officenumber']) and isset($_POST['evdni'])){

        $arr=array(); //Set up events array

        //Load only events that ends after today
        $sql='SELECT title,start,end,dni,id FROM reservas WHERE (CAST(end AS DATETIME) >= CAST("'.$todaydate.'" AS DATETIME)) AND officenumber='.$_POST['officenumber'];
        $result=mysqli_query($conn,$sql);
        if(mysqli_num_rows($result)>0){
            while($row=mysqli_fetch_assoc($result)){
                if($_POST['evdni']==$row['dni']){
                    $obj=(object)[
                        'title'=>$row['title'],
                        'start'=>$row['start'],
                        'end'=>$row['end'],
                        'id'=>$row['id'],
                        'backgroundColor'=>'green',
                    ];
                }else{
                    $obj=(object)[
                        'title'=>'OCUPADO',
                        'start'=>$row['start'],
                        'end'=>$row['end'],
                        'id'=>$row['id'],
                        'backgroundColor'=>'red',
                    ];
                }
                array_push($arr,$obj);
            }
        }
        $json=json_encode($arr);

        echo $json;

    }

    //Insert reservation
    require 'insertevent.php';

    //Sanitize and validate
    function sanitizestring($stringtosanitize){
        global $conn;
        return mysqli_real_escape_string($conn,strip_tags($_POST[$stringtosanitize]));
    }

    function validateaddress($stringtovalidate){
        if (!preg_match("/^[a-zA-Z0-9 ]*$/",$stringtovalidate)){
            global $validation;
            $validation=false;
        }
    }

    //Avoid overlaping function
    function checkoverlap($sttime,$enTime,$checkstarttime,$checkendtime,$todayDate){
        $today=strtotime($todayDate);

        $startTime = strtotime($sttime);
        $endTime   = strtotime($enTime);

        $chkStartTime = strtotime($checkstarttime);
        $chkEndTime   = strtotime($checkendtime);

        if($chkStartTime > $startTime && $chkEndTime < $endTime)
        {
            return false;
        }elseif(($chkStartTime > $startTime && $chkStartTime < $endTime) || ($chkEndTime > $startTime && $chkEndTime < $endTime))
        {
            return false;
        }elseif($chkStartTime==$startTime || $chkEndTime==$endTime)
        {
            return false;
        }elseif($startTime > $chkStartTime && $endTime < $chkEndTime)
        {
            return false;
        }elseif($chkStartTime<$today)
        {
            return false;
        }else{
            return true;
        }
    }
?>
