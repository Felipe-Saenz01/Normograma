<?php

    if (isset($_POST['oculto'])){
        header('Location: Cuentas.php');
    }

    include '../../Models/conexion.php';
    
    



    if(!empty($_POST['clave'])){

        $id = $_POST['id'];
        $nombre_usuario = $_POST['nombre']; //ok
        $tipo_de_usuario = $_POST['tipo_de_usuario']; //ok
        $numero_documento = $_POST['numero_documento'];//ok
        $usuario = $_POST['usuario'];//ok
        $clave = $_POST['clave'];//ok
        $Estado = $_POST['Estado'];//ok

        $password_hash = password_hash($clave, PASSWORD_BCRYPT);

        $sentencia = $db->prepare("UPDATE usuarios SET nombre = UPPER(:nombre_usuario), tipo_de_usuario = :tipo_de_usuario, numero_documento = :numero_documento,
        usuario = :usuario, clave = :clave, estado = :estado WHERE codigo_Us = :codigo_Us;");
        $sentencia->bindParam(':nombre_usuario',$nombre_usuario, PDO::PARAM_STR);
        $sentencia->bindParam(':tipo_de_usuario',$tipo_de_usuario, PDO::PARAM_INT);
        $sentencia->bindParam(':numero_documento',$numero_documento,PDO::PARAM_INT);
        $sentencia->bindParam(':usuario',$usuario,PDO::PARAM_STR);
        $sentencia->bindParam(':clave',$password_hash, PDO::PARAM_STR);
        $sentencia->bindParam(':estado',$Estado, PDO::PARAM_INT);
        $sentencia->bindParam(':codigo_Us',$id, PDO::PARAM_INT);

	$resultado = $sentencia->execute();

    }else{
    
	$id = $_POST['id'];
	$nombre_usuario = $_POST['nombre']; //ok
	$tipo_de_usuario = $_POST['tipo_de_usuario']; //ok
	$numero_documento = $_POST['numero_documento'];//ok
	$usuario = $_POST['usuario'];//ok
	$Estado = $_POST['Estado'];//ok

	$sentencia = $db->prepare("UPDATE usuarios SET nombre = UPPER(:nombre_usuario), tipo_de_usuario = :tipo_de_usuario, numero_documento = :numero_documento,
	usuario = :usuario, estado = :estado WHERE codigo_Us = :codigo_Us;");
	$sentencia->bindParam(':nombre_usuario',$nombre_usuario, PDO::PARAM_STR);
	$sentencia->bindParam(':tipo_de_usuario',$tipo_de_usuario, PDO::PARAM_INT);
	$sentencia->bindParam(':numero_documento',$numero_documento,PDO::PARAM_INT);
	$sentencia->bindParam(':usuario',$usuario,PDO::PARAM_STR);
	$sentencia->bindParam(':estado',$Estado, PDO::PARAM_INT);
	$sentencia->bindParam(':codigo_Us',$id, PDO::PARAM_INT);

	$resultado = $sentencia->execute();
    }

    

    if($resultado === TRUE){
	$db= null;
	echo "
        <!DOCTYPE html>
        <html>
        <body>
    
        <form id='myForm' action='Cuentas.php'>
        <input type='hidden' name='alert2' value='2'><br>
        </form>
    
        <script>
        document.getElementById('myForm').submit();
        </script>
    
        </body>
        </html>
        ";
    }else{
	$db= null;
	echo 'Error al insertar los datos';
    }
?>
