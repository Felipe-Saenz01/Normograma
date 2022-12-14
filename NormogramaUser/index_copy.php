<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Normmograma</title>
        <link rel="icon" href="img/favicon2.ico">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>





<header>
	<div class="container-border text-center">
		<img src="img/LOGO2.png" alt="banner" class="img-fluid">
	</div>

<nav class="navbar navbar-expand-lg navbar-dark  mb-4" style="background-color: #037207">
  <div class="container-fluid">
    <button class="navbar-toggler mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a href="https://www.unitropico.edu.co" class="btn btn-lg " role="button"  style="background-color: white;">Inicio</a>
      </div>
    </div>
  </div>
</nav>

</header>



<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.min.js"></script>



<?php 
include '../Models/conexion.php';

// Paginación
if (isset($_GET['pag'])){
	$pag = (int)$_GET['pag'];
}else{
	$pag = 1;
}

$limit = 10;
$offset=($pag-1)*$limit;



$sentencia = $db->prepare("SELECT Nom.directorio, Nom.codigo_gen, Nom.numero_norma, Nom.anio_expedicion, Nom.mes_expedicion, Nom.dia_expedicion, Nom.asunto, Nom.fecha_cargue,
Clasno.nombre_clasificacion, Estdoc.nombre_estado, Emis.nombre_emisor, Depen.nombre_dependencia 
FROM normativa Nom
INNER JOIN clasificacion_normativa Clasno ON Nom.codigo_clasificacion_normograma = Clasno.codigo
INNER JOIN estado_documento Estdoc ON Nom.estado = Estdoc.codigo
INNER JOIN quien_emite_normativa Emis ON Nom.codigo_quien_emite_normativa = Emis.codigo
INNER JOIN dependencia_normativa Depen ON Nom.codigo_dependencia_normativa = Depen.codigo ORDER BY anio_expedicion ASC LIMIT $offset, $limit");
$sentencia->execute();
$normativa = $sentencia->fetchAll(PDO::FETCH_ASSOC);

$sentenciaTotal = $db->query("SELECT Nom.directorio, Nom.codigo_gen, Nom.numero_norma, Nom.anio_expedicion, Nom.mes_expedicion, Nom.dia_expedicion, Nom.asunto, Nom.fecha_cargue,
Clasno.nombre_clasificacion, Estdoc.nombre_estado, Emis.nombre_emisor, Depen.nombre_dependencia 
FROM normativa Nom
INNER JOIN clasificacion_normativa Clasno ON Nom.codigo_clasificacion_normograma = Clasno.codigo
INNER JOIN estado_documento Estdoc ON Nom.estado = Estdoc.codigo
INNER JOIN quien_emite_normativa Emis ON Nom.codigo_quien_emite_normativa = Emis.codigo
INNER JOIN dependencia_normativa Depen ON Nom.codigo_dependencia_normativa = Depen.codigo ORDER BY anio_expedicion ASC");
$totalNormas = $sentenciaTotal->rowCount();

	
/*

*/

?>


<div class="container mt-5">
	<div class="row">
	    <div class="col-12 grid-margin">
			<div class="card">
		    	<div class="card-body">
		    		<h4 class="card-title">Buscador</h4>
					<!--  Formulario    --->
		    		<form id="form2" method="GET" action="index_copy_edit.php">		
						<div class="mb-3">
							<input type="text" class="form-control" id="buscar" name="buscar" placeholder="Ingrese las palabras que desea buscar, separándolas Con una coma." value="" >
						</div>

		    			<h4 class="card-title">Filtro de búsqueda</h4>  
		    		
						<table class="table">
			    		<thead>
							<tr class="filters">
				   			<th>
								<select id="assigned-tutor-filter" id="buscadependencia" name="buscadependencia" class="form-control mt-2" style="border: #bababa 1px solid; color:#000000;" >
					    			<option value="">- Dependencia del documento-</option>
									<?php
									$consulta = $db->prepare("SELECT * FROM dependencia_normativa ORDER BY nombre_dependencia ASC");
									$consulta->execute();
									$ejecutar = $consulta->fetchAll(PDO::FETCH_OBJ);
									foreach ($ejecutar as $datos){ ?> 
									<option value="<?php  echo $datos->nombre_dependencia ?>"> <?php echo $datos->nombre_dependencia ?> </option>
									<?php } ?>
					    		</select>
				    		</th>

				    		<th>
								<select id="assigned-tutor-filter" id="buscaclasificasion" name="buscaclasificasion" class="form-control mt-2" style="border: #bababa 1px solid; color:#000000;" >
					    			<option value="">- Tipo de documento -</option>
					    			<?php 
					    			$consulta = $db->prepare("SELECT * FROM clasificacion_normativa ORDER BY nombre_clasificacion ASC");
									$consulta->execute();
					    			$ejecutar = $consulta->fetchAll(PDO::FETCH_OBJ);
									foreach ($ejecutar as $datos){ ?> 
					    			<option value="<?php  echo $datos->nombre_clasificacion ?>"> <?php  echo $datos->nombre_clasificacion ?></option>
					    			<?php }?>
								</select>
				    		</th>

				    		<th>
								<select id="assigned-tutor-filter" id="buscaestado" name="buscaestado" class="form-control mt-2" style="border: #bababa 1px solid; color:#000000;" >
									<option value="">- Estado del documento -</option>
									<?php 
									$consulta = $db->prepare("SELECT * FROM estado_documento ORDER BY nombre_estado ASC");
									$consulta->execute();
									$ejecutar = $consulta->fetchAll(PDO::FETCH_OBJ);
									foreach ($ejecutar as $datos){ ?> 
									<option value="<?php  echo $datos->nombre_estado ?>"> <?php  echo $datos->nombre_estado ?></option>
									<?php } ?>
								</select>
				    		</th>

				    		<th>
								<input class="form-control form-control" type="number" max="2040" min="1900" name="buscaaniodocumento"  placeholder="Año expedición " >
				    		</th>
							</tr>
			    		</thead>
						</table>
		    	

						<h4 class="card-title">Ordenar por:</h4>
						<table class="table">
							<thead>
								<tr class="filters">
									<th>
										<select id="assigned-tutor-filter" id="orden" name="orden" class="form-control mt-2" style="border: #bababa 1px solid; color:#000000;" >
											<option value="">- SELECCIONE UN ORDEN -</option>
											<option value="4">AÑO</option>
											<option value="2">DEPENDENCIA</option>
											<option value="5">ESTADO</option>
											<option value="3">TIPO DE DOCUMENTO</option>
										</select>
									</th>

									<th>
										<input type="submit" class="btn " value="Buscar" style="margin-top: 38px; background-color: #037207; color: white;">
										<input type="hidden" name="oculto">
									</th>
								</tr>
							</thead>
						</table>
					</form>
					<p style="font-weight: bold; color:#037207;"><i class="mdi mdi-file-document"></i> <?php echo $totalNormas; ?> Resultados encontrados</p>
		    	
				<div class="pt-3 ">
					<div class="table-responsive">
						<table class="table-cell-vertical-align-padding-x">
							<thead>
								<tr style="background-color:#037207; color:#FFFFFF;">
									<th style=" text-align: center;"> Dependencia </th>
									<th style=" text-align: center;"> Tipo Documento </th>
									<th style=" text-align: center;"> Número </th>
									<th style=" text-align: center;"> Año </th>
									<th style=" text-align: center;"> Mes </th>
									<th style=" text-align: center;"> Día </th>
									<th style=" text-align: center;"> Emisor</th>
									<th style=" text-align: center;"> Asunto </th>
									<th style=" text-align: center;"> Estado </th>
									<th style=" text-align: center;"> Ver documento </th>
									</tr>
								</thead>
								<tbody>
								<?php 
								if($totalNormas>0){ 
								foreach($normativa as $datos) {   ?>
								<tr>
									<td style="text-align: center;"><?php echo $datos["nombre_dependencia"]; ?></td>
									<td style="text-align: center;"><?php echo $datos["nombre_clasificacion"]; ?></td>
									<td style="text-align: center;"><?php echo $datos["numero_norma"]; ?></td>
									<td style="text-align: center;"><?php echo $datos["anio_expedicion"]; ?></td>
									<td style="text-align: center;"><?php echo $datos["mes_expedicion"]; ?></td>
									<td style="text-align: center;"><?php echo $datos["dia_expedicion"]; ?></td>
									<td style="text-align: center;"><?php echo $datos["nombre_emisor"]; ?></td>
									<td style="text-align: justify;"><?php echo $datos["asunto"]; ?></td>
									<td style="text-align: center;"><?php echo $datos["nombre_estado"]; ?></td>
									<td style="text-align: center;"><a class='btn btn-info' href="verDocumento.php?id=<?php echo $datos["codigo_gen"];?>&visita=1" title="Ver Documento" target="_BLANK"  ><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16">
										<path d="M23.821,11.181v0C22.943,9.261,19.5,3,12,3S1.057,9.261.179,11.181a1.969,1.969,0,0,0,0,1.64C1.057,14.739,4.5,21,12,21s10.943-6.261,11.821-8.181A1.968,1.968,0,0,0,23.821,11.181ZM12,19c-6.307,0-9.25-5.366-10-6.989C2.75,10.366,5.693,5,12,5c6.292,0,9.236,5.343,10,7C21.236,13.657,18.292,19,12,19Z"/>
										<path d="M12,7a5,5,0,1,0,5,5A5.006,5.006,0,0,0,12,7Zm0,8a3,3,0,1,1,3-3A3,3,0,0,1,12,15Z"/></svg></a>
									</td>
											</tr>
								<?php } ?>
								</tbody>
						</table>
					</div>
				</div>
				
				
				
				</div>   
		    </div>
		</div>
	</div>

</div>


<?php if(isset($_GET['buscar'])){
?>
<nav aria-label="Paginación Normativas">
<ul class="pagination justify-content-center">
<?php
$totalPaginacion = ceil($totalNormas/$limit);
for($i=1;$i<=$totalPaginacion; $i++){
?>
<li class="page-item" aria-current="page">
	<a class="page-link text-success" href="<?php echo"index.php?buscar=".$_GET['buscar']."&buscadependencia=".$_GET['buscadependencia']."&buscaclasificasion=".$_GET['buscaclasificasion']."&buscaestado=".$_GET['buscaestado']."&buscaaniodocumento=".$_GET['buscaaniodocumento']."&orden=".$_GET['orden']."&pag=".$i; ?>"><?php echo $i ?></a>
</li>
<?php } ?>
</ul>
</nav>

<?php }
if (!isset($_GET['buscar'])){ ?>
<div class="container d-flex justify-content-center mt-2">
	<nav aria-label="Paginación Normativas">
		<ul class="pagination">
		<?php
		$totalPaginacion = ceil($totalNormas/$limit);
		for($i=1;$i<=$totalPaginacion; $i++){
		?>	
		<li class="page-item" aria-current="page">
		<a class="page-link" href="./index_copy.php?pag=<?php echo $i; ?>"><?php echo $i ?></a>
		</li>
	<?php } ?>
	</ul>
	</nav>
</div>

<?php } 
    }
?>



    




	
<footer class="bg-light text-center text-lg-start ">
  <div class="text-center p-2 mb-0 mt-4 text-light" style="background-color: black;">
    <em>Universidad sujeta a inspección y vigilancia por el Ministerio de Educación Nacional.</em>
    <br>
    <br>
    <p style="font-size: 12px;">
    Yopal, Casanare Carrera 19 No. 39-40 - Atención al Ciudadano 3213983917 - Bienestar Universitario 3213986406
    <a href="https://unitropico.edu.co/index.php/unitropico/contactanos" style="font-size: 12px;">Líneas de Contacto</a>
    </p>
    <p style="font-size: 12px;">Ventanilla Única vur@unitropico.edu.co - notificacionesjudiciales@unitropico.edu.co</p>
    <p style="font-size: 12px;">Derechos Reservados - Unitrópico - Copyright © 2019</p>
    <p style="font-size: 10px;">
 		<span>Designed by </span>
    <a href="https://www.unitropico.edu.co" style="font-size: 12px;">Sistemas de Información.</a> 		
  </div>
</footer>
<?php $db =null; ?>
</body>
</html>
