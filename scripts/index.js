//Images scroll
var p1=new Image();
var p2=new Image();
var p3=new Image();
var p4=new Image();
var p5=new Image();

p1.src='images/interior1.jpg';
p2.src='images/interior2.jpeg';
p3.src='images/interior3.jpg';
p4.src='images/interior4.jpg';
p5.src='images/interior5.png';

var arr=new Array(p1,p2,p3,p4,p5);
var end=arr.length-1;
cont=0;

function scrollbackward(){
    if(cont==0){
        cont=end;
    }else{
        cont--;
    }
    document.pic.src=arr[cont].src;
}

function scrollforward(){
    if(cont==end){
        cont=0;
    }else{
        cont++;
    }
    document.pic.src=arr[cont].src;
}

$(document).ready(function(){
    $('#back').click(function(){
        $("#back").animate({left:'40px'},50).animate({left:'50px'},50);
    });
    $('#forw').click(function(){
        $("#forw").animate({right:'40px'},50).animate({right:'50px'},50);
    });
});

// Quotes scroll
var quotes=['Proceder con honestidad en aras de la dignidad del hombre es el compromiso más trascendente en nuestro corto paso por este mundo.',
'La simplicidad es la maxima sofisticacion.',
'Siempre que te pregunten si puedes hacer un trabajo, contesta que sí y ponte enseguida a aprender como se hace.'
];
var quotefooter=['René Favaloro',
'Leonardo Da Vinci',
'Franklin D. Roosevelt'
];

setInterval(scrollquotes,10000);
var contquotes=0;

function scrollquotes(){

    $('#qfooter').fadeOut(750);
    $('#quote').fadeOut(750,function(){
        $('#quote').html(quotes[contquotes]);
        $('#qfooter').html(quotefooter[contquotes]);
    });

    $('#quote').fadeIn(750);
    $('#qfooter').fadeIn(750);

    contquotes++;
    if(contquotes==quotes.length){
        contquotes=0;
    }
}