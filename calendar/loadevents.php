<?php
    if($_POST['moment']=='onload'){

        $servername='localhost';
        $username='lauta';
        $password='password';
        $db='testdb';
        
        $conn= mysqli_connect($servername,$username,$password,$db);

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

        /*$obj=(object)[
            'title'=>'event3',
            'start'=>'2018-01-09T12:30:00',
            'end'=>'2018-01-09T17:30:00',
        ];

        $obj2=(object)[
            'title'=>'event2',
            'start'=>'2018-01-05',
            'end'=>'2018-01-07',
        ];*/

        /*$events=[
            {
                title  : 'GoToGoogle',
                start  : '2018-01-01',
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
        ]*/
    }
?>