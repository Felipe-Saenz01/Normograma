<?php

include '../../Models/conexion.php';

if(isset($_POST['ocultoDependencia'])){
	$id = $_POST['id'];
	$nombre_dependencia = $_POST['nombre_dependencia'];
	$editar = $db->prepare("UPDATE dependencia_normativa SET nombre_dependencia = UPPER(:nombre_dependencia) WHERE codigo = :codigo;");
	$editar->bindParam(':nombre_dependencia',$nombre_dependencia, PDO::PARAM_STR);
	$editar->bindParam(':codigo',$id, PDO::PARAM_STR);
	$resultado = $editar->execute();

	
    if($resultado === TRUE){
        echo "
        <!DOCTYPE html>
        <html>
        <body>
    
        <form id='myForm' action='Dependencia.php'>
        <input type='hidden' name='alert2' value='2'><br>
        </form>
    
        <script>
        document.getElementById('myForm').submit();
        </script>
    
        </body>
        </html>
        ";
    }else{
	echo 'Error al insertar los datos';
    }

}


if(isset($_POST['ocultoClasificacion'])){
	$id = $_POST['id'];
	$nombre_clasificacion = $_POST['nombre_clasificacion'];
	$editar = $db->prepare("UPDATE clasificacion_normativa SET nombre_clasificacion = UPPER(:nombre_clasificacion) WHERE codigo = :codigo;");
	$editar->bindParam(':nombre_clasificacion',$nombre_clasificacion,  PDO::PARAM_STR);
	$editar->bindParam(':codigo',$id,  PDO::PARAM_STR);
	$resultado = $editar->execute();

	if($resultado === TRUE){
        echo "
        <!DOCTYPE html>
        <html>
        <body>
    
        <form id='myForm' action='Clasificacion.php'>
        <input type='hidden' name='alert2' value='2'><br>
        </form>
    
        <script>
        document.getElementById('myForm').submit();
        </script>
    
        </body>
        </html>
        ";
    }else{
	echo 'Error al insertar los datos';
    }
	
}

if(isset($_POST['ocultoEmisor'])){
	$id = $_POST['id'];
	$nombre_emisor = $_POST['nombre_emisor'];
	$editar = $db->prepare("UPDATE quien_emite_normativa SET nombre_emisor = UPPER(:nombre_emisor) WHERE codigo = :codigo;");
	$editar->bindParam(':nombre_emisor',$nombre_emisor,  PDO::PARAM_STR);
	$editar->bindParam(':codigo',$id, PDO::PARAM_STR );
	$resultado = $editar->execute();

	if($resultado === TRUE){
        echo "
        <!DOCTYPE html>
        <html>
        <body>
    
        <form id='myForm' action='Emisor.php'>
        <input type='hidden' name='alert2' value='2'><br>
        </form>
    
        <script>
        document.getElementById('myForm').submit();
        </script>
    
        </body>
        </html>
        ";
    }else{
	echo 'Error al insertar los datos';
    }

}

if(isset($_POST['ocultoEstado'])){
	$id = $_POST['id'];
	$nombre_estado = $_POST['nombre_estado'];
	$editar = $db->prepare("UPDATE estado_documento SET nombre_estado = UPPER(:nombre_estado) WHERE codigo = :codigo;");
	$editar->bindParam(':nombre_estado', $nombre_estado, PDO::PARAM_STR );
	$editar->bindParam(':codigo',$id,  PDO::PARAM_STR);
	$resultado = $editar->execute([$nombre_estado, $id]);

	if($resultado === TRUE){
        echo "
        <!DOCTYPE html>
        <html>
        <body>
    
        <form id='myForm' action='Estado.php'>
        <input type='hidden' name='alert2' value='2'><br>
        </form>
    
        <script>
        document.getElementById('myForm').submit();
        </script>
    
        </body>
        </html>
        ";
    }else{
	echo 'Error al insertar los datos';
    }

    }
?>
