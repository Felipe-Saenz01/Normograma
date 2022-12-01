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
		//mensaje de error
		//.$e->getMessage();
		//alerta
		echo  "<script>
		Swal.fire({
			title: 'Fallo de conexión!',
			text: 'Existen problemas con la conexion, comuniquese con un administrador.',
			icon: 'info',
			allowOutsideClick: false,
			allowEscapeKey:false,
			allowEnterKey:false,
			confirmButtonColor: '#037207',
			confirmButtonText: 'Aceptar'
		})
		</script>";
    }



?>
