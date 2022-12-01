<?php

include '../../Models/conexion.php';

if(isset($_POST['ocultoDependencia'])){
    $id = $_POST['id'];
    $nombre_dependencia = $_POST['nombre_dependencia'];

    $verificarSentencia = $db->prepare("SELECT count(UPPER(nombre_dependencia)) FROM dependencia_normativa WHERE nombre_dependencia LIKE :nombre_dependencia");
    $verificarSentencia->bindParam(':nombre_dependencia', $nombre_dependencia, PDO::PARAM_STR);
    $verificarSentencia->execute();
    $verificarParametro = $verificarSentencia->fetch(PDO::FETCH_ASSOC);

    if($verificarParametro['count(UPPER(nombre_dependencia))']>0){
	$db= null;
        echo "
        <!DOCTYPE html>
        <html>
        <body>
    
        <form id='myForm' action='Dependencia.php'>
        <input type='hidden' name='alert4' value='2'><br>
        </form>
    
        <script>
        document.getElementById('myForm').submit();
        </script>
    
        </body>
        </html>
        ";
    }else{
	$editar = $db->prepare("UPDATE dependencia_normativa SET nombre_dependencia = UPPER(:nombre_dependencia) WHERE codigo = :codigo;");
	$editar->bindParam(':nombre_dependencia',$nombre_dependencia, PDO::PARAM_STR);
	$editar->bindParam(':codigo',$id, PDO::PARAM_STR);
	$resultado = $editar->execute();

	
	if($resultado === TRUE){
	    $db= null;
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
	    $db= null;
	    echo "
	    <!DOCTYPE html>
	    <html>
	    <body>
    
	    <form id='myForm' action='Dependencia.php'>
	    <input type='hidden' name='alert5' value='2'><br>
	    </form>
    
	    <script>
	    document.getElementById('myForm').submit();
	    </script>
    
	    </body>
	    </html>
	    ";
	}
    }

    

}


if(isset($_POST['ocultoClasificacion'])){
    $id = $_POST['id'];
    $nombre_clasificacion = $_POST['nombre_clasificacion'];

    $verificarSentencia = $db->prepare("SELECT count(UPPER(nombre_clasificacion)) FROM clasificacion_normativa WHERE nombre_clasificacion LIKE :nombre_clasificacion");
    $verificarSentencia->bindParam(':nombre_clasificacion', $nombre_clasificacion, PDO::PARAM_STR);
    $verificarSentencia->execute();
    $verificarParametro = $verificarSentencia->fetch(PDO::FETCH_ASSOC);

    if($verificarParametro['count(UPPER(nombre_clasificacion))']>0){
	$db= null;
        echo "
        <!DOCTYPE html>
        <html>
        <body>
    
	<form id='myForm' action='Clasificacion.php'>
        <input type='hidden' name='alert4' value='2'><br>
        </form>
    
        <script>
        document.getElementById('myForm').submit();
        </script>
    
        </body>
        </html>
        ";
    }else{
	$editar = $db->prepare("UPDATE clasificacion_normativa SET nombre_clasificacion = UPPER(:nombre_clasificacion) WHERE codigo = :codigo;");
	$editar->bindParam(':nombre_clasificacion',$nombre_clasificacion,  PDO::PARAM_STR);
	$editar->bindParam(':codigo',$id,  PDO::PARAM_STR);
	$resultado = $editar->execute();

	if($resultado === TRUE){
	    $db= null;
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
	    $db= null;
	    echo "
	    <!DOCTYPE html>
	    <html>
	    <body>
    
	    <form id='myForm' action='Clasificacion.php'>
	    <input type='hidden' name='alert5' value='2'><br>
	    </form>
    
	    <script>
	    document.getElementById('myForm').submit();
	    </script>
    
	    </body>
	    </html>
	    ";
	}
    }
	
}

if(isset($_POST['ocultoEmisor'])){
    $id = $_POST['id'];
    $nombre_emisor = $_POST['nombre_emisor'];

    $verificarSentencia = $db->prepare("SELECT count(UPPER(nombre_emisor)) FROM quien_emite_normativa WHERE nombre_emisor LIKE :nombre_emisor");
    $verificarSentencia->bindParam(':nombre_emisor', $nombre_emisor, PDO::PARAM_STR);
    $verificarSentencia->execute();
    $verificarParametro = $verificarSentencia->fetch(PDO::FETCH_ASSOC);

    if($verificarParametro['count(UPPER(nombre_emisor))']>0){
	$db= null;
        echo "
        <!DOCTYPE html>
        <html>
        <body>
    
	<form id='myForm' action='Emisor.php'>
        <input type='hidden' name='alert4' value='2'><br>
        </form>
    
        <script>
        document.getElementById('myForm').submit();
        </script>
    
        </body>
        </html>
        ";
    }else{
	$editar = $db->prepare("UPDATE quien_emite_normativa SET nombre_emisor = UPPER(:nombre_emisor) WHERE codigo = :codigo;");
	$editar->bindParam(':nombre_emisor',$nombre_emisor,  PDO::PARAM_STR);
	$editar->bindParam(':codigo',$id, PDO::PARAM_STR );
	$resultado = $editar->execute();

	if($resultado === TRUE){
	    $db= null;
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
	    $db= null;
	    echo "
	    <!DOCTYPE html>
	    <html>
	    <body>
    
	    <form id='myForm' action='Emisor.php'>
	    <input type='hidden' name='alert5' value='2'><br>
	    </form>
    
	    <script>
	    document.getElementById('myForm').submit();
	    </script>
    
	    </body>
	    </html>
	    ";
	}
    }



}

if(isset($_POST['ocultoEstado'])){
    $id = $_POST['id'];
    $nombre_estado = $_POST['nombre_estado'];

    $verificarSentencia = $db->prepare("SELECT count(UPPER(nombre_estado)) FROM estado_documento WHERE nombre_estado LIKE :nombre_estado");
    $verificarSentencia->bindParam(':nombre_estado', $nombre_estado, PDO::PARAM_STR);
    $verificarSentencia->execute();
    $verificarParametro = $verificarSentencia->fetch(PDO::FETCH_ASSOC);

    if($verificarParametro['count(UPPER(nombre_estado))']>0){
	$db= null;
        echo "
        <!DOCTYPE html>
        <html>
        <body>
    
	<form id='myForm' action='Estado.php'>
        <input type='hidden' name='alert4' value='2'><br>
        </form>
    
        <script>
        document.getElementById('myForm').submit();
        </script>
    
        </body>
        </html>
        ";
    }else{
	$editar = $db->prepare("UPDATE estado_documento SET nombre_estado = UPPER(:nombre_estado) WHERE codigo = :codigo;");
	$editar->bindParam(':nombre_estado', $nombre_estado, PDO::PARAM_STR );
	$editar->bindParam(':codigo',$id,  PDO::PARAM_STR);
	$resultado = $editar->execute();

	if($resultado === TRUE){
	    $db= null;
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
	    $db= null;
	    echo "
	    <!DOCTYPE html>
	    <html>
	    <body>
    
	    <form id='myForm' action='Estado.php'>
	    <input type='hidden' name='alert5' value='2'><br>
	    </form>
    
	    <script>
	    document.getElementById('myForm').submit();
	    </script>
    
	    </body>
	    </html>
	    ";
	}
    }

	

    }
?>
