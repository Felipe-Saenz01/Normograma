<?php

 if(!isset($_GET['id'])){
	exit();
 }

$codigo = $_GET['id'];
include '../Models/conexion.php';

$sentencia = $db->prepare("DELETE FROM normativa WHERE codigo_gen =?;");
$resultado = $sentencia->execute([$codigo]);


eliminarDir('../Archivos/'.$codigo);

function eliminarDir($carpeta){
    foreach(glob($carpeta . "/*") as $archivos_carpeta ){
	if(is_dir($archivos_carpeta)){
	    eliminarDir($archivos_carpeta);
	}else{
	    unlink($archivos_carpeta);
	}
    }
    rmdir($carpeta);
}

if($resultado === TRUE){
$db= null;
    echo "
    <!DOCTYPE html>
    <html>
    <body>

    <form id='myForm' action='superUser.php'>
    <input type='hidden' name='alert1' value='2'><br>
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

    <form id='myForm' action='superUser.php'>
    <input type='hidden' name='Error3' value='2'><br>
    </form>

    <script>
    document.getElementById('myForm').submit();
    </script>

    </body>
    </html>
    ";
}


?>
