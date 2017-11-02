<?php
    if($_POST['moment']=='onload'){

        $obj=new stdClass;
        $obj->title='event1';
        $obj->start='2018-01-09T12:30:00';
        $obj->end='2018-01-09T17:30:00';

        $arr=array(
            [1]=>$obj
        );

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