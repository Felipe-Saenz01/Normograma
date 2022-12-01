<?php

 if(!isset($_GET['id'])){
	exit();
 }

include '../../Models/conexion.php';

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



if($_GET['origen'] == 'dependencia'){
    $codigo = $_GET['id'];
    $resultado= False;
    //Sentencia para obtener todas las normas con este organo Institucional
    $pruebaSentencia= $db->prepare ("SELECT * FROM normativa WHERE codigo_dependencia_normativa = ?;");
    $pruebaSentencia->execute([$codigo]);
    $pruebaFinal= $pruebaSentencia->fetchAll(PDO::FETCH_OBJ);
    //Ciclo para eliminar todos las carpetas y archivos de las normas
    foreach($pruebaFinal as $datos){
	echo $datos->codigo_gen."<br>";
	eliminarDir('../../Archivos/'.$datos->codigo_gen);
	
    }
    
       
    // Eliminar Organo Institucional
    $sentencia = $db->prepare("DELETE FROM dependencia_normativa WHERE codigo =?;");
    $resultado = $sentencia->execute([$codigo]);

    if($resultado === TRUE){
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

if($_GET['origen'] == 'clasificacion'){
    $codigo = $_GET['id'];
    $resultado= False;
    //Sentencia para obtener todas las normas con esta clasificacion
    $pruebaSentencia= $db->prepare ("SELECT * FROM normativa WHERE codigo_clasificacion_normograma = ?;");
    $pruebaSentencia->execute([$codigo]);
    $pruebaFinal= $pruebaSentencia->fetchAll(PDO::FETCH_OBJ);
    //Ciclo para eliminar todos las carpetas y archivos de las normas
    foreach($pruebaFinal as $datos){
	echo $datos->codigo_gen."<br>";
	eliminarDir('../../Archivos/'.$datos->codigo_gen);
	
    }
    //Elimimnar las normas que tengan la clasificacion
    $eliminarRegistros= $db->prepare("DELETE FROM normativa WHERE codigo_clasificacion_normograma = ?;");
    $eliminarFinal = $eliminarRegistros->execute([$codigo]);

    $sentencia = $db->prepare("DELETE FROM clasificacion_normativa WHERE codigo =?;");
    $resultado = $sentencia->execute([$codigo]);

    if($resultado === TRUE){
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
        echo "Error";
    }
}

if($_GET['origen'] == 'emisor'){
    $codigo = $_GET['id'];
    $resultado= False;
    //Sentencia para obtener todas las normas con este organo Institucional
    $pruebaSentencia= $db->prepare ("SELECT * FROM normativa WHERE codigo_quien_emite_normativa= ?;");
    $pruebaSentencia->execute([$codigo]);
    $pruebaFinal= $pruebaSentencia->fetchAll(PDO::FETCH_OBJ);
    //Ciclo para eliminar todos las carpetas y archivos de las normas
    foreach($pruebaFinal as $datos){
	echo $datos->codigo_gen."<br>";
	eliminarDir('../../Archivos/'.$datos->codigo_gen);
	
    }
    //Elimimnar las normas que tengan el organo_institucional
    $eliminarRegistros= $db->prepare("DELETE FROM normativa WHERE codigo_quien_emite_normativa = ?;");
    $eliminarFinal = $eliminarRegistros->execute([$codigo]);

    $sentencia = $db->prepare("DELETE FROM quien_emite_normativa WHERE codigo =?;");
    $resultado = $sentencia->execute([$codigo]);

    if($resultado === TRUE){
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
        echo "Error";
    }

}

if($_GET['origen'] == 'estado'){
    $codigo = $_GET['id'];
    $resultado= False;
    //Sentencia para obtener todas las normas con este organo Institucional
    $pruebaSentencia= $db->prepare ("SELECT * FROM normativa WHERE estado = ?;");
    $pruebaSentencia->execute([$codigo]);
    $pruebaFinal= $pruebaSentencia->fetchAll(PDO::FETCH_OBJ);
    //Ciclo para eliminar todos las carpetas y archivos de las normas
    foreach($pruebaFinal as $datos){
	echo $datos->codigo_gen."<br>";
	eliminarDir('../../Archivos/'.$datos->codigo_gen);
	
    }
    //Elimimnar las normas que tengan el organo_institucional
    $eliminarRegistros= $db->prepare("DELETE FROM normativa WHERE estado = ?;");
    $eliminarFinal = $eliminarRegistros->execute([$codigo]);

    $sentencia = $db->prepare("DELETE FROM estado_documento WHERE codigo =?;");
    $resultado = $sentencia->execute([$codigo]);

    if($resultado === TRUE){
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
        echo "Error";
    }
}


?>
