<?php
    if($_POST['moment']=='onload'){

        $obj=(object)[
            'title'=>'event3',
            'start'=>'2018-01-09T12:30:00',
            'end'=>'2018-01-09T17:30:00',
        ];

        $obj2=(object)[
            'title'=>'event2',
            'start'=>'2018-01-05',
            'end'=>'2018-01-07',
        ];

        $arr=array();

        array_push($arr,$obj,$obj2);

        $json=json_encode($arr);

        echo $json;

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