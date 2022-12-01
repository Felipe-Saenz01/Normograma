<?php
    if (!isset($_POST['oculto'])){
        header('Location: ../Login.php');
    }

    include '../Models/conexion.php';
    
    $id = $_POST['id'];
    $dependencia_normativa = $_POST['codigo_dependencia_normativa']; //ok
    $clasificacion_normativa = $_POST['codigo_clasificacion_normograma'];//ok
    $emisor = $_POST['codigo_quien_emite_normativa'];//ok
    $estado_documento = $_POST['codigo_estado_documento'];//ok
    $numero_norma = $_POST['numero_norma'];//ok
    $anio_expedicion = $_POST['anio_expedicion'];//ok
    $mes_expedicion = $_POST['mes_expedicion'];//ok
    $dia_expedicion = $_POST['dia_expedicion'];//ok
    $asunto = $_POST['asunto'];//ok
    $palabras_clave = $_POST['palabra_clave'];//ok
    $nombre_Archivo = $_POST['oculto'];//ok    
    
    
    $nombre_Archivo =$_FILES['archivo_norma']['name'];
    $tam_Archivo =$_FILES['archivo_norma']['size'];
    $tipo_Archivo =$_FILES['archivo_norma']['type'];

    $sentencia = $db->prepare("INSERT INTO normativa(codigo_dependencia_normativa, codigo_clasificacion_normograma,
    numero_norma, anio_expedicion, mes_expedicion, dia_expedicion, codigo_quien_emite_normativa, asunto, palabras_claves, estado,
    directorio) VALUES (,,,,,
    ,,,,,,);");

    $sentencia = $db->prepare("UPDATE normativa SET codigo_dependencia_normativa = :codigo_dependencia_normativa,
    codigo_clasificacion_normograma =:codigo_clasificacion_normograma, numero_norma =:numero_norma, anio_expedicion = :anio_expedicion,
    mes_expedicion = :mes_expedicion, dia_expedicion = :dia_expedicion, codigo_quien_emite_normativa = :codigo_quien_emite_normativa, asunto = :Asunto,
    palabras_claves = :palabras_claves, estado = :estado, directorio = :directorio WHERE codigo_gen = :codigo_gen;");


    if($_FILES['archivo_norma']['error']>0){
    }else{
	$archivos_permitidos= array("application/pdf");
	$tamMax=10000;
    
    
    
	if(in_array($_FILES['archivo_norma']['type'], $archivos_permitidos) && $_FILES['archivo_norma']['size'] <= $tamMax*1024){

	    
        
	    $ruta = '../Archivos/'.$id.'/';
	    $destino =$ruta.$nombre_Archivo;
    
	    if(!file_exists($ruta)){
		mkdir($ruta);
	    }
	    if(!file_exists($destino)){
		$guardarArchivo = @move_uploaded_file($_FILES['archivo_norma']['tmp_name'],$destino);
		if($guardarArchivo){
		}else{
            $db= null;
            echo "
            <!DOCTYPE html>
            <html>
            <body>
        
            <form id='myForm' action='superUser.php'>
            <input type='hidden' name='Error2' value='2'><br>
            </form>
        
            <script>
            document.getElementById('myForm').submit();
            </script>
        
            </body>
            </html>
            ";
        }
	    
    }


	}else{
$db= null;
        echo "
        <!DOCTYPE html>
        <html>
        <body>
    
        <form id='myForm' action='superUser.php'>
        <input type='hidden' name='Error1' value='2'><br>
        </form>
    
        <script>
        document.getElementById('myForm').submit();
        </script>
    
        </body>
        </html>
        ";
    
    }
    }

    $sentencia->bindParam(':codigo_dependencia_normativa', $dependencia_normativa, PDO::PARAM_STR);
    $sentencia->bindParam(':codigo_clasificacion_normograma', $clasificacion_normativa, PDO::PARAM_STR);
    $sentencia->bindParam(':numero_norma', $numero_norma, PDO::PARAM_INT);
    $sentencia->bindParam(':anio_expedicion', $anio_expedicion, PDO::PARAM_INT);
    $sentencia->bindParam(':mes_expedicion', $mes_expedicion, PDO::PARAM_INT);
    $sentencia->bindParam(':dia_expedicion', $dia_expedicion, PDO::PARAM_INT);
    $sentencia->bindParam(':codigo_quien_emite_normativa', $emisor, PDO::PARAM_STR);
    $sentencia->bindParam(':Asunto', $asunto, PDO::PARAM_STR);
    $sentencia->bindParam(':palabras_claves', $palabras_clave, PDO::PARAM_STR);
    $sentencia->bindParam(':estado', $estado_documento, PDO::PARAM_STR);
    $sentencia->bindParam(':directorio', $nombre_Archivo, PDO::PARAM_STR);
    $sentencia->bindParam(':codigo_gen', $id, PDO::PARAM_STR);
	    
    $resultado = $sentencia->execute();


    if($resultado === TRUE){
$db= null;
        echo "
        <!DOCTYPE html>
        <html>
        <body>
    
        <form id='myForm' action='superUser.php'>
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
    
        <form id='myForm' action='superUser.php'>
        <input type='hidden' name='Error4' value='2'><br>
        </form>
    
        <script>
        document.getElementById('myForm').submit();
        </script>
    
        </body>
        </html>
        ";
    }
?>
