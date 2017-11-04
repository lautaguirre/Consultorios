<?php
    $servername='localhost';
    $username='lauta';
    $password='password';
    $db='testdb';
    
    $conn= mysqli_connect($servername,$username,$password,$db);

    if($_POST['moment']=='onload'){

        $arr=array();

        $sql='SELECT title,start,end FROM reservas';
        $result=mysqli_query($conn,$sql);
        if(mysqli_num_rows($result)>0){
            while($row=mysqli_fetch_assoc($result)){
                $obj=(object)[
                    'title'=>$row['title'],
                    'start'=>$row['start'],
                    'end'=>$row['end'],
                ];
                array_push($arr,$obj);
            }
            $json=json_encode($arr);
            
            echo $json;
        }
    }else if($_POST['moment']=='reserve'){
        $sql='INSERT INTO reservas (title,start,end) VALUES ("'.$_POST['titleev'].'","'.$_POST['startev'].'","'.$_POST['endev'].'")';
        if(mysqli_query($conn,$sql)){        
            echo 'Nueva reserva creada<BR>';
        }else{
            echo 'Error creando reserva<BR>';
        }
    }
?>