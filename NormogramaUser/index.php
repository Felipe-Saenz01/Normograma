<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Normmograma</title>
        <link rel="icon" href="img/favicon2.ico">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<style>
.linkFooter{
	color: #778a9e;
	text-decoration: none;
}

.linkFooter:hover{
	text-decoration: none;
	color: #fb7c3c;
}

</style>

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
//Funcion para limitar las palabras del asunto en la tabla
function limitarAsunto ($asunto, $limite, $sufijo){
	if(strlen($asunto)>$limite){
		return substr($asunto,0,$limite).$sufijo;
	}else{
		return $asunto;
	}
}
// Paginación
if (isset($_GET['pag'])){
	$pag = (int)$_GET['pag'];
}else{
	$pag = 1;
}

$limit = 1;
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
				<form method="post" action="buscarNorma.php" class=" p-3">
					<div class="row">
						<h4>Filro de Búsqueda</h4>
					</div>
					<hr>
					<div class="row">
						<!-- Filtro Palabras Claves -->
						<div class="form-group mb-3 col-4">
							<label for="palabasClave" class="mb-1">Palabras Clave</label>
							<input type="text" id="buscar" name="palabrasClave" class="form-control"  value="" placeholder="Ejemplo: Educacion, Estudiante, Reglamento..." style="border: #bababa 1px solid; color:#000000;">
						</div>
						<!-- Filtro Select Dependencia -->
						<div class="form-group mb-3 col-4">
							<label for="dependencia" class="mb-1">Dependencia</label>
							<select id="dependencia" name="dependencia" class="form-control" style="border: #bababa 1px solid; color:#000000;" >
								<option value="">- Seleccione Dependencia -</option>
									<?php
										$consulta = $db->prepare("SELECT * FROM dependencia_normativa ORDER BY nombre_dependencia ASC");
										$consulta->execute();
										$ejecutar = $consulta->fetchAll(PDO::FETCH_OBJ);
										foreach ($ejecutar as $datos){ ?> 
											<option value="<?php  echo $datos->nombre_dependencia ?>"> <?php echo $datos->nombre_dependencia ?> </option>
										<?php } ?>
							</select>
						</div>
						<!-- Filtro Select Clasificacion -->
						<div class="form-group mb-3 col-4">
							<label for="clasificacion" class="mb-1">Clasificación</label>
							<select  id="clasificacion" name="clasificacion" class="form-control" style="border: #bababa 1px solid; color:#000000;" >
								<option value="">- Seleccione Tipo de Documento -</option>
									<?php 
										$consulta = $db->prepare("SELECT * FROM clasificacion_normativa ORDER BY nombre_clasificacion ASC");
										$consulta->execute();
										$ejecutar = $consulta->fetchAll(PDO::FETCH_OBJ);
										foreach ($ejecutar as $datos){ ?> 
											<option value="<?php  echo $datos->nombre_clasificacion ?>"> <?php  echo $datos->nombre_clasificacion ?></option>
										<?php }?>
							</select>
						</div>
					</div>
					<div class="row">
						<!-- Filtro Select Estado -->
						<div class="form-group mb-3 col-4">
							<label for="estado" class="mb-1">Estado</label>
							<select  id="estado" name="estado" class="form-control" style="border: #bababa 1px solid; color:#000000;" >
								<option value="">- Seleccione Estado del Documento -</option>
									<?php 
										$consulta = $db->prepare("SELECT * FROM estado_documento ORDER BY nombre_estado ASC");
										$consulta->execute();
										$ejecutar = $consulta->fetchAll(PDO::FETCH_OBJ);
										foreach ($ejecutar as $datos){ ?> 
											<option value="<?php  echo $datos->nombre_estado ?>"> <?php  echo $datos->nombre_estado ?></option>
										<?php } ?>
							</select>
						</div>
						<!-- Filtro Select Orden -->
						<div class="form-group mb-3 col-4">
							<label for="orden" class="mb-1">Orden Búsqueda</label>
							<select id="orden" name="orden" class="form-control" style="border: #bababa 1px solid; color:#000000;" >
								<option value="">- Seleccione un Orden -</option>
								<option value="4">AÑO</option>
								<option value="2">DEPENDENCIA</option>
								<option value="5">ESTADO</option>
								<option value="3">TIPO DE DOCUMENTO</option>
							</select>
						</div>
						<!-- Filtro Año expedición -->
						<div class="form-group mb-3 col-3">
							<label for="clasificacion" class="mb-1">Año de Expedición</label>
							<input class="form-control form-control" type="number" max="2050" min="1900" name="anio"  placeholder="Ejemplo: <?php echo date("Y"); ?> " >
						</div>
						<div class="col-md-1 mt-4">
							<input type="submit" class="btn text-white " value="Buscar" style=" background-color: #037207;">
							<input type="hidden" name="oculto">
						</div>
					</div>
				</form>
				<p style="font-weight: bold; color:#037207;"><i class="mdi mdi-file-document"></i> <?php echo $totalNormas; ?> Resultados encontrados</p>
		    	
				<div class="table-ressnsive alling-items-center">
					<div class="table-responsive">
						<table class="table table-hover align-middle">
							<thead>
								<tr style="background-color:#037207; color:#FFFFFF;">
									<th style=" text-align: center;"> Emisor</th>
									<th style=" text-align: center;"> Tipo Documento </th>
									<th style=" text-align: center;"> Número </th>
									<th style=" text-align: center;"> Año </th>
									<th style=" text-align: center;"> Mes </th>
									<th style=" text-align: center;"> Día </th>
									<th style=" text-align: center;"> Dependencia </th>
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
									<td style="text-align: center;"><?php echo $datos["nombre_emisor"]; ?></td>
									<td style="text-align: center;"><?php echo $datos["nombre_clasificacion"]; ?></td>
									<td style="text-align: center;"><?php echo $datos["numero_norma"]; ?></td>
									<td style="text-align: center;"><?php echo $datos["anio_expedicion"]; ?></td>
									<td style="text-align: center;"><?php echo $datos["mes_expedicion"]; ?></td>
									<td style="text-align: center;"><?php echo $datos["dia_expedicion"]; ?></td>
									<td style="text-align: center;"><?php echo $datos["nombre_dependencia"]; ?></td>
									<td style="text-align: justify;"><?php echo limitarAsunto($datos["asunto"],170,'...'); ?></td>
									<td style="text-align: center;"><?php echo $datos["nombre_estado"]; ?></td>
									<td style="text-align: center;"><a class='btn btn-info' href="verDocumento.php?id=<?php echo $datos["codigo_gen"];?>&visita=1" title="Ver Documento" target="_BLANK"  ><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16">
										<path d="M23.821,11.181v0C22.943,9.261,19.5,3,12,3S1.057,9.261.179,11.181a1.969,1.969,0,0,0,0,1.64C1.057,14.739,4.5,21,12,21s10.943-6.261,11.821-8.181A1.968,1.968,0,0,0,23.821,11.181ZM12,19c-6.307,0-9.25-5.366-10-6.989C2.75,10.366,5.693,5,12,5c6.292,0,9.236,5.343,10,7C21.236,13.657,18.292,19,12,19Z"/>
										<path d="M12,7a5,5,0,1,0,5,5A5.006,5.006,0,0,0,12,7Zm0,8a3,3,0,1,1,3-3A3,3,0,0,1,12,15Z"/></svg></a>
									</td>
											</tr>
								<?php }
								} ?>
								</tbody>
						</table>
					</div>
				</div>
				
				
				
				</div>   
		    </div>
		</div>
	</div>

</div>

<!-- Paginacion -->
</div class="container mt-3" >
	</br>
	<nav aria-label="Paginación Normativas">
	    <ul class="pagination justify-content-center">
	    <?php
	    $totalPaginacion = ceil($totalNormas/$limit);
		//numero de botones de la paginacion a mostrar
		$num_pages = 5;
		// calcular mitad del número de páginas que se van a mostrar
		$half_num_pages = floor($num_pages / 2);
		// Calculamos el primer y último número de página que se van a mostrar
		if ($pag - $half_num_pages > 0) {
			$start_page = $pag - $half_num_pages;
		} else {
			$start_page = 1;
		}
		  
		if ($pag + $half_num_pages < $totalPaginacion) {
			$end_page = $pag + $half_num_pages;
		} else {
			$end_page = $totalPaginacion;
		}
		// Imprimimos el botón de "Anterior" si no estamos en la primera página
		if ($pag > 1) {
			$atras = $pag-1;
			echo "
			<li class='page-item' aria-current='page'>
				<a class='page-link text-success' href='index.php?pag=$atras'>
				<span aria-hidden='true'>&laquo;</span>
				</a>
			</li>";
 		}
		// Imprimimos los botones de página
		for ($i = $start_page; $i <= $end_page; $i++) {
			// Imprimimos el número de página actual en negrita
			if ($i == $pag) {
			echo "
			<li class='page-item active' aria-current='page'>
				<a class='page-link text-white'href='index.php?pag=$i'>$i</a>
			</li>";
			} else {
			echo "
			<li class='page-item' aria-current='page'>
				<a class='page-link text-success' href='index.php?pag=$i'>$i</a>	
			</li>";
			}
		}

		// Imprimimos el botón de "Siguiente" si no estamos en la última página
		if ($pag < $totalPaginacion) {
			$siguiente = $pag+1;
			echo "
			<li class='page-item' aria-current='page'>
				<a class='page-link text-success' href='index.php?pag=$siguiente'>
				<span aria-hidden='true'>&raquo;</span>
				</a>
			</li>";
		}

	    ?>
	    </ul>
	</nav>

</div>


<?php
	include '../Models/footer.php';
?>
<?php $db =null; ?>
</body>
</html>
