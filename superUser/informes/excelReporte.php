<?php

header("Content-Type: aplication/xls");
header("Content-Disposition: attachment; filename= Informe-Normativas.xls");
include '../../Models/conexion.php';

    session_start();

    if(!isset($_SESSION['rol'])){
        header('location: ../Login.php');
    }else{
        if($_SESSION['rol'] != 1){
            header('location: ../Login.php');
        }
    }

    $sentencia = $db->query("SELECT Nom.directorio, Nom.codigo_gen, Nom.numero_norma, Nom.anio_expedicion, Nom.mes_expedicion, Nom.dia_expedicion, Nom.asunto, Nom.fecha_cargue,
	Clasno.nombre_clasificacion, Estdoc.nombre_estado, Emis.nombre_emisor, Depen.nombre_dependencia 
	FROM normativa Nom
	INNER JOIN clasificacion_normativa Clasno ON Nom.codigo_clasificacion_normograma = Clasno.codigo
	INNER JOIN estado_documento Estdoc ON Nom.estado = Estdoc.codigo
	INNER JOIN quien_emite_normativa Emis ON Nom.codigo_quien_emite_normativa = Emis.codigo
	INNER JOIN dependencia_normativa Depen ON Nom.codigo_dependencia_normativa = Depen.codigo ORDER BY anio_expedicion ASC");
	$normativa = $sentencia ->fetchAll(PDO::FETCH_OBJ);
    //print_r($normativa);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Numero Normativa</th>
                <th>Tipo Documento</th>
                <th>Emisor</th>
                <th>Dependencia</th>
                <th>Asunto</th>
                <th>Fecha de Expedición</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($normativa as $datos){ ?>
            <tr>
                <td><?php echo $datos->numero_norma; ?></td> <!--- Numero de norma -->
                <td><?php echo $datos->nombre_clasificacion; ?></td> <!--- Tipo Documento -->
                <td><?php echo $datos->nombre_emisor; ?></td> <!--- Emisor -->
                <td><?php echo $datos->nombre_dependencia; ?></td> <!--- Dependencia -->
                <td><p><?php echo $datos->asunto; ?></p></td>
                <td><?php echo $datos->dia_expedicion."/".$datos->mes_expedicion."/".$datos->anio_expedicion; ?></td>
                <td><?php echo $datos->nombre_estado; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</body>
</html>

    

