<?php
    
include '../../Models/conexion.php';


if(isset($_POST['ocultoDependencia'])){
    $dependencia = $_POST['nombre_dependencia'];
    $sentenciaDep = $db->prepare("INSERT INTO dependencia_normativa(nombre_dependencia) VALUES (UPPER(:nombre_dependencia));");
    $sentenciaDep->bindParam(':nombre_dependencia',$dependencia, PDO::PARAM_STR);
    $resuDep = $sentenciaDep->execute();

    if($resuDep === TRUE){

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
        echo 'Error al insertar los datos';
    }


}


if (isset($_POST['ocultoClasificacion'])){
    $clasificacion = $_POST['nombre_clasificacion'];
    $sentenciaClas = $db->prepare("INSERT INTO clasificacion_normativa(nombre_clasificacion) VALUES (UPPER(:nombre_clasificacion));");
    $sentenciaClas->bindParam(':nombre_clasificacion',$clasificacion, PDO::PARAM_STR);
    $resuClas = $sentenciaClas->execute();

     
    if($resuClas === TRUE){

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
        echo 'Error al insertar los datos';
    }
       

}

if (isset($_POST['ocultoEmisor'])){
    $emisor = $_POST['nombre_emisor'];
    $sentenciaEmisor = $db->prepare("INSERT INTO quien_emite_normativa (nombre_emisor) VALUES (UPPER(:nombre_emisor));");
    $sentenciaEmisor->bindParam(':nombre_emisor',$emisor,PDO::PARAM_STR);
    $resuEmisor = $sentenciaEmisor->execute();

    if($resuEmisor === TRUE){

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
        echo 'Error al insertar los datos';
    }


}


if (isset($_POST['ocultoEstado'])){
    $estado = $_POST['nombre_estado'];
    $sentenciaEstado = $db->prepare("INSERT INTO estado_documento(nombre_estado) VALUES (UPPER(:nombre_estado));");
    $sentenciaEstado->bindParam(':nombre_estado',$estado,PDO::PARAM_STR );
    $resuEstado = $sentenciaEstado->execute();

    if($resuEstado === TRUE){

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
        echo 'Error al insertar los datos';
    }

}


?>
