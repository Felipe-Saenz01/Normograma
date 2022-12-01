<?php

if(!isset($_GET['id'])){
    header('Location: index.php');
}


$codigo = $_GET['id'];
include '../Models/conexion.php';
$sentencia = $db->prepare("SELECT Nom.directorio, Nom.codigo_gen, Nom.numero_norma, Nom.anio_expedicion, Nom.mes_expedicion, Nom.dia_expedicion, Nom.asunto, Nom.fecha_cargue, Nom.palabras_claves,
    Clasno.nombre_clasificacion, Estdoc.nombre_estado, Emis.nombre_emisor, Depen.nombre_dependencia 
    FROM normativa Nom
    INNER JOIN clasificacion_normativa Clasno ON Nom.codigo_clasificacion_normograma = Clasno.codigo
    INNER JOIN estado_documento Estdoc ON Nom.estado = Estdoc.codigo
    INNER JOIN quien_emite_normativa Emis ON Nom.codigo_quien_emite_normativa = Emis.codigo
    INNER JOIN dependencia_normativa Depen ON Nom.codigo_dependencia_normativa = Depen.codigo WHERE codigo_gen = ?;");
$sentencia->execute([$codigo]);
$resultado = $sentencia->fetch(PDO::FETCH_OBJ);
//print_r($resultado);

?>



<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Tipo documento</title>
        <link rel="icon" href="img/favicon2.ico">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body >
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.min.js"></script>
    <center>  <!---- Ver Documento --->

    <div class="ratio ratio-16x9">
    <h1> Ver Documento </h1>
    
    <iframe class="embed-responsive-item" src="../Archivos/<?php echo $codigo."/".$resultado->directorio; ?>" width="1500" height="1200" allow="autoplay"></iframe>
    </div>

    </center>
</body>
</html>
