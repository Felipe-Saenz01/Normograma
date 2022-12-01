<?php
    
include '../../Models/conexion.php';


if(isset($_POST['ocultoDependencia'])){
    $dependencia = $_POST['nombre_dependencia'];

    $verificarSentencia = $db->prepare("SELECT count(UPPER(nombre_dependencia)) FROM dependencia_normativa WHERE nombre_dependencia LIKE :nombre_dependencia");
    $verificarSentencia->bindParam(':nombre_dependencia', $dependencia, PDO::PARAM_STR);
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
	$sentenciaDep = $db->prepare("INSERT INTO dependencia_normativa(nombre_dependencia) VALUES (UPPER(:nombre_dependencia));");
	$sentenciaDep->bindParam(':nombre_dependencia',$dependencia, PDO::PARAM_STR);
	$resuDep = $sentenciaDep->execute();

	if($resuDep === TRUE){
	    $db= null;
	    echo "
	    <!DOCTYPE html>
	    <html>
	    <body>
    
	    <form id='myForm' action='Dependencia.php'>
	    <input type='hidden' name='alert' value='2'><br>
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


if (isset($_POST['ocultoClasificacion'])){
    $clasificacion = $_POST['nombre_clasificacion'];

    $verificarSentencia = $db->prepare("SELECT count(UPPER(nombre_clasificacion)) FROM clasificacion_normativa WHERE nombre_clasificacion LIKE :nombre_clasificacion");
    $verificarSentencia->bindParam(':nombre_clasificacion', $clasificacion, PDO::PARAM_STR);
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
	$sentenciaClas = $db->prepare("INSERT INTO clasificacion_normativa(nombre_clasificacion) VALUES (UPPER(:nombre_clasificacion));");
	$sentenciaClas->bindParam(':nombre_clasificacion',$clasificacion, PDO::PARAM_STR);
	$resuClas = $sentenciaClas->execute();

	if($resuClas === TRUE){
	    $db= null;
	    echo "
	    <!DOCTYPE html>
	    <html>
	    <body>
    
	    <form id='myForm' action='Clasificacion.php'>
	    <input type='hidden' name='alert' value='2'><br>
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

if (isset($_POST['ocultoEmisor'])){
    $emisor = $_POST['nombre_emisor'];

    $verificarSentencia = $db->prepare("SELECT count(UPPER(nombre_emisor)) FROM quien_emite_normativa WHERE nombre_emisor LIKE :nombre_emisor");
    $verificarSentencia->bindParam(':nombre_emisor', $emisor, PDO::PARAM_STR);
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
	$sentenciaEmisor = $db->prepare("INSERT INTO quien_emite_normativa (nombre_emisor) VALUES (UPPER(:nombre_emisor));");
	$sentenciaEmisor->bindParam(':nombre_emisor',$emisor,PDO::PARAM_STR);
	$resuEmisor = $sentenciaEmisor->execute();

	if($resuEmisor === TRUE){
	    $db= null;
	    echo "
	    <!DOCTYPE html>
	    <html>
	    <body>
    
	    <form id='myForm' action='Emisor.php'>
	    <input type='hidden' name='alert' value='2'><br>
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


if (isset($_POST['ocultoEstado'])){
    $estado = $_POST['nombre_estado'];

    $verificarSentencia = $db->prepare("SELECT count(UPPER(nombre_estado)) FROM estado_documento WHERE nombre_estado LIKE :nombre_estado");
    $verificarSentencia->bindParam(':nombre_estado', $estado, PDO::PARAM_STR);
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
	$sentenciaEstado = $db->prepare("INSERT INTO estado_documento(nombre_estado) VALUES (UPPER(:nombre_estado));");
	$sentenciaEstado->bindParam(':nombre_estado',$estado,PDO::PARAM_STR );
	$resuEstado = $sentenciaEstado->execute();

	if($resuEstado === TRUE){
	    $db= null;
	    echo "
	    <!DOCTYPE html>
	    <html>
	    <body>
    
	    <form id='myForm' action='Estado.php'>
	    <input type='hidden' name='alert' value='2'><br>
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
