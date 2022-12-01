<?php
    $contra='';
    $nusuario='root';
    $nombreDB='normograma';

    try{
	$db = new PDO(
	'mysql:host=localhost;
	dbname='.$nombreDB,
	    $nusuario,
	    $contra,
	    array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8")
	);
	

    }catch(Exception $e){
	echo 'Error de conexion '.$e->getMessage();
    }



?>
