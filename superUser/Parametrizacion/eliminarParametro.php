<?php

 if(!isset($_GET['id'])){
	exit();
 }

include '../../Models/conexion.php';




if($_GET['origen'] == 'dependencia'){
    $codigo = $_GET['id'];
    $resultado= False;
    //busqueda de normativas con esta dependendencia
    $sentenciaBusqueda = $db->query("SELECT count(codigo_dependencia_normativa) FROM normativa WHERE codigo_dependencia_normativa LIKE '%".$codigo."%'");
    $sentenciaBusqueda->execute();
    $ejecutarBusqueda=$sentenciaBusqueda->fetch(PDO::FETCH_ASSOC);
    //si existen registros con esta dependencia se devolverá el error
    //de lo contrario se podrá eliminar el parametro
    if($ejecutarBusqueda['count(codigo_dependencia_normativa)']>0){

	$db=null;

	echo "
            <!DOCTYPE html>
            <html>
            <body>
        
            <form id='myForm' action='Dependencia.php'>
            <input type='hidden' name='alert3' value='2'><br>
            </form>
        
            <script>
            document.getElementById('myForm').submit();
            </script>
        
            </body>
            </html>
    ";
    
    }else{
	$eliminarRegistros= $db->prepare("DELETE FROM dependencia_normativa WHERE codigo = ?;");
	$eliminarFinal = $eliminarRegistros->execute([$codigo]);


	if($eliminarFinal === TRUE){
	    $db=null;
	    echo "
		<!DOCTYPE html>
		<html>
		<body>
        
		<form id='myForm' action='Dependencia.php'>
		<input type='hidden' name='alert1' value='2'><br>
		</form>
        
		<script>
		document.getElementById('myForm').submit();
		</script>
        
		</body>
		</html>
	    ";
	}else{
	    $db=null;
	    echo "
		<!DOCTYPE html>
		<html>
		<body>
        
		<form id='myForm' action='Dependencia.php'>
		<input type='hidden' name='alert3' value='2'><br>
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


if($_GET['origen'] == 'clasificacion'){
    $codigo = $_GET['id'];
    $resultado= False;
    //busqueda de normativas con esta dependendencia
    $sentenciaBusqueda = $db->query("SELECT count(codigo_clasificacion_normograma) FROM normativa WHERE codigo_clasificacion_normograma LIKE '%".$codigo."%'");
    $sentenciaBusqueda->execute();
    $ejecutarBusqueda=$sentenciaBusqueda->fetch(PDO::FETCH_ASSOC);
    //si existen registros con esta dependencia se devolverá el error
    //de lo contrario se podrá eliminar el parametro
    if($ejecutarBusqueda['count(codigo_clasificacion_normograma)']>0){

	$db=null;

	echo "
            <!DOCTYPE html>
            <html>
            <body>
        
            <form id='myForm' action='Clasificacion.php'>
            <input type='hidden' name='alert3' value='2'><br>
            </form>
        
            <script>
            document.getElementById('myForm').submit();
            </script>
        
            </body>
            </html>
    ";
    
    }else{
	$eliminarRegistros= $db->prepare("DELETE FROM clasificacion_normativa WHERE codigo = ?;");
	$eliminarFinal = $eliminarRegistros->execute([$codigo]);


	if($eliminarFinal === TRUE){
	    $db=null;
	    echo "
		<!DOCTYPE html>
		<html>
		<body>
        
		<form id='myForm' action='Clasificacion.php'>
		<input type='hidden' name='alert1' value='2'><br>
		</form>
        
		<script>
		document.getElementById('myForm').submit();
		</script>
        
		</body>
		</html>
	    ";
	}else{
	    $db=null;
	    echo "
		<!DOCTYPE html>
		<html>
		<body>
        
		<form id='myForm' action='Clasificacion.php'>
		<input type='hidden' name='alert3' value='2'><br>
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

if($_GET['origen'] == 'emisor'){
    $codigo = $_GET['id'];
    $resultado= False;
    //busqueda de normativas con esta dependendencia
    $sentenciaBusqueda = $db->query("SELECT count(codigo_quien_emite_normativa) FROM normativa WHERE codigo_quien_emite_normativa LIKE '%".$codigo."%'");
    $sentenciaBusqueda->execute();
    $ejecutarBusqueda=$sentenciaBusqueda->fetch(PDO::FETCH_ASSOC);
    //si existen registros con esta dependencia se devolverá el error
    //de lo contrario se podrá eliminar el parametro
    if($ejecutarBusqueda['count(codigo_quien_emite_normativa)']>0){

	$db=null;

	echo "
            <!DOCTYPE html>
            <html>
            <body>
        
            <form id='myForm' action='Emisor.php'>
            <input type='hidden' name='alert3' value='2'><br>
            </form>
        
            <script>
            document.getElementById('myForm').submit();
            </script>
        
            </body>
            </html>
    ";
    
    }else{
	$eliminarRegistros= $db->prepare("DELETE FROM quien_emite_normativa WHERE codigo = ?;");
	$eliminarFinal = $eliminarRegistros->execute([$codigo]);


	if($eliminarFinal === TRUE){
	    $db=null;
	    echo "
		<!DOCTYPE html>
		<html>
		<body>
        
		<form id='myForm' action='Emisor.php'>
		<input type='hidden' name='alert1' value='2'><br>
		</form>
        
		<script>
		document.getElementById('myForm').submit();
		</script>
        
		</body>
		</html>
	    ";
	}else{
	    $db=null;
	    echo "
		<!DOCTYPE html>
		<html>
		<body>
        
		<form id='myForm' action='Emisor.php'>
		<input type='hidden' name='alert3' value='2'><br>
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

if($_GET['origen'] == 'estado'){
    $codigo = $_GET['id'];
    $resultado= False;
    //busqueda de normativas con esta dependendencia
    $sentenciaBusqueda = $db->query("SELECT count(estado) FROM normativa WHERE estado LIKE '%".$codigo."%'");
    $sentenciaBusqueda->execute();
    $ejecutarBusqueda=$sentenciaBusqueda->fetch(PDO::FETCH_ASSOC);
    //si existen registros con esta dependencia se devolverá el error
    //de lo contrario se podrá eliminar el parametro
    if($ejecutarBusqueda['count(estado)']>0){

	$db=null;

	echo "
            <!DOCTYPE html>
            <html>
            <body>
        
            <form id='myForm' action='Estado.php'>
            <input type='hidden' name='alert3' value='2'><br>
            </form>
        
            <script>
            document.getElementById('myForm').submit();
            </script>
        
            </body>
            </html>
    ";
    
    }else{
	$eliminarRegistros= $db->prepare("DELETE FROM estado_documento WHERE codigo = ?;");
	$eliminarFinal = $eliminarRegistros->execute([$codigo]);


	if($eliminarFinal === TRUE){
	    $db=null;
	    echo "
		<!DOCTYPE html>
		<html>
		<body>
        
		<form id='myForm' action='Estado.php'>
		<input type='hidden' name='alert1' value='2'><br>
		</form>
        
		<script>
		document.getElementById('myForm').submit();
		</script>
        
		</body>
		</html>
	    ";
	}else{
	    $db=null;
	    echo "
		<!DOCTYPE html>
		<html>
		<body>
        
		<form id='myForm' action='Estado.php'>
		<input type='hidden' name='alert3' value='2'><br>
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
