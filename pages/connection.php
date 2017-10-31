<?php
$servername='localhost';
$username='lauta';
$password='password';
$db='testdb';

$conn= mysqli_connect($servername,$username,$password,$db);

if(!$conn){
    die('Error al conectar: '.mysqli_connect_error().'<BR>');
}else{
    echo 'Conexion exitosa<BR>';
}
?>