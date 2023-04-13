<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Normmograma</title>
	<!-- Favicon -->
    <link rel="icon" href="img/favicon2.ico">
	<!-- CDN Boostrap -->
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


<!-- CDN SwwetAlert (Libreria de alertas) -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- CDN JS Boostrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.min.js"></script>



<?php 
include '../Models/conexion.php';

session_start(); 
// Asignacion de valores de busqueda en variables de Session
if(!empty($_POST)){
  session_reset();
  //Dependencia vacia
  if(empty($_POST['dependencia'])){
    $_SESSION['dependencia'] = '';
  }else{//Dependencia con parametros
    $_SESSION['dependencia'] = $_POST['dependencia'];
  }
  //palabrasClaves vacia
  if(empty($_POST['palabrasClave'])){ 
    $_SESSION['palabrasClaves'] = '';
  }else{//palabrasClaves con parametros
    $_SESSION['palabrasClaves'] = $_POST['palabrasClave'];
  }
  //clasificacion vacia
  if(empty($_POST['clasificacion'])){
    $_SESSION['clasificacion'] = '';
  }else{// clasificacion con parametros
    $_SESSION['clasificacion'] = $_POST['clasificacion'];
  }
  //estado vacios
  if(empty($_POST['estado'])){
    $_SESSION['estado'] = '';
  }else{//estado con parametros
    $_SESSION['estado'] = $_POST['estado'];
  }
  //anio vacios
  if(empty($_POST['anio'])){
    $_SESSION['anio'] = '';
  }else{
    $_SESSION['anio'] = $_POST['anio'];
  }
  $_SESSION['Sentencia'] = '';
  
  if(empty($_POST['orden'])){
	$_SESSION['ordenValue'] = 0;
	$_SESSION['orden'] = "anio_expedicion";
  }else{
	switch($_POST['orden']){
	  case 2:
		$_SESSION['orden'] = "nombre_dependencia";
		$_SESSION['ordenValue'] = 2;
		break;
	  case 3:
		$_SESSION['orden'] = "nombre_clasificacion";
		$_SESSION['ordenValue'] = 3;
		break;
	  case 4:
		$_SESSION['orden'] = "anio_expedicion";
		$_SESSION['ordenValue'] = 4;
		break;
	  case 5:
		$_SESSION['orden'] = "nombre_estado";
		$_SESSION['ordenValue'] = 5; 
		break;
	}
  }

}


if(empty($_SESSION) && empty($_POST)){
  header("Location: index_copy.php");
}

//Funcion para limitar las palabras del asunto en la tabla
function limitarAsunto ($asunto, $limite, $sufijo){
	if(strlen($asunto)>$limite){
		return substr($asunto,0,$limite).$sufijo;
	}else{
		return $asunto;
	}
}

// funcion para generar parte de la query con los parametros (dependencia, clasificacion y estado) cuando estos no estan vacios
function sentenciaParametros($dependencia, $clasificacion, $estado, $anio){
   // nigun parametro
  if(empty($dependencia) && empty($clasificacion) && empty($estado) && empty($anio)){
    return 0;
  }
  //Agrega los paremetros que no estan vacios al array
  $queryParametros = array();
  if(!empty($dependencia)){
    $queryParametros[] = "nombre_dependencia = :nombre_dependencia";
  }
  if(!empty($clasificacion)){
    $queryParametros[] = "nombre_clasificacion = :nombre_clasificacion";
  }
  if(!empty($estado)){
    $queryParametros[] = "nombre_estado = :nombre_estado";
  }
  if(!empty($anio)){
    $queryParametros[] = "anio_expedicion = :anio_expedicion";
  }
  // pasa todos los elmentos del array a un string separados con el AND de la sentencia
  return implode(' AND ',$queryParametros);  
}

	
	//Base consulta sin condiciones o parametros
	$query ="SELECT Nom.numero_norma, Nom.anio_expedicion, Nom.codigo_gen, Nom.mes_expedicion, Nom.dia_expedicion, Nom.asunto, Nom.fecha_cargue,
	Clasno.nombre_clasificacion, Estdoc.nombre_estado, NomEmi.nombre_emisor, Depen.nombre_dependencia 
	FROM normativa Nom
	INNER JOIN clasificacion_normativa Clasno ON Nom.codigo_clasificacion_normograma = Clasno.codigo
	INNER JOIN estado_documento Estdoc ON Nom.estado = Estdoc.codigo
	INNER JOIN quien_emite_normativa NomEmi ON Nom.codigo_quien_emite_normativa = NomEmi.codigo
  	INNER JOIN dependencia_normativa Depen ON Nom.codigo_dependencia_normativa = Depen.codigo ";
	//se enviaron palabas claves
	if(!empty($_SESSION['palabrasClaves'])){
		$aKeyword = explode(", ", $_SESSION['palabrasClaves']);
		// si hay mas de 1 palabra clave
    $palabasClaveQuery = "";
    // hay mas de 1 palabra clave
		if(count($aKeyword)>1){
			$palabasClaveQuery = "WHERE palabras_claves LIKE '%".$aKeyword[0]."%'";
			$asuntoQuery = " OR asunto LIKE '%".$aKeyword[0]."%'";
			for($i = 1; $i < count($aKeyword); $i++) {
				$palabasClaveQuery .= " OR palabras_claves LIKE '%".$aKeyword[$i]."%'";
				$asuntoQuery .= " OR asunto LIKE '%".$aKeyword[$i]."%'";
      }
      $query .= $palabasClaveQuery;
      $query .= $asuntoQuery;
		}else{ // hay solo 1 palabra clave
			$palabasClaveQuery = "WHERE palabras_claves LIKE '%".str_replace(',', '', $aKeyword[0])."%'";
			$asuntoQuery = " OR asunto LIKE '%".str_replace(',', '', $aKeyword[0])."%'";
      $query .= $palabasClaveQuery;
      $query .= $asuntoQuery;
		}
    //Se agregan los demas parametros,
    $queryParametros = sentenciaParametros($_SESSION['dependencia'], $_SESSION['clasificacion'], $_SESSION['estado'], $_SESSION['anio']);
    if($queryParametros != 0){
      $query .= "AND ".$queryParametros;
    }
	 $query .= " ORDER BY ".$_SESSION['orden']." DESC";
      
  }else{//no se enviaron palabras clave
    $queryParametros = sentenciaParametros($_SESSION['dependencia'], $_SESSION['clasificacion'], $_SESSION['estado'], $_SESSION['anio']);
    if($queryParametros != 0){
      $query .= "AND ".$queryParametros;
    }
	$query .= " ORDER BY ".$_SESSION['orden']." DESC";

  }


// Paginación
if (isset($_GET['pag'])){
	$pag = (int)$_GET['pag'];
}else{
	$pag = 1;
}

$limit = 10;
$offset=($pag-1)*$limit;


$newquery = $query." LIMIT $offset, $limit";

$_SESSION['Sentencia'] = $newquery;
$sentencia = $db->prepare($_SESSION['Sentencia']);
$sentenciaTotal = $db->prepare($query);

// asignan los parametros con bindParam
if(!empty($_SESSION['dependencia'])){
  $sentencia->bindParam(':nombre_dependencia', $_SESSION['dependencia'], PDO::PARAM_STR);
  $sentenciaTotal->bindParam(':nombre_dependencia', $_SESSION['dependencia'], PDO::PARAM_STR);
}
if(!empty($_SESSION['clasificacion'])){
  $sentencia->bindParam(':nombre_clasificacion', $_SESSION['clasificacion'], PDO::PARAM_STR);
  $sentenciaTotal->bindParam(':nombre_clasificacion', $_SESSION['clasificacion'], PDO::PARAM_STR);
}
if(!empty($_SESSION['estado'])){
  $sentencia->bindParam(':nombre_estado', $_SESSION['estado'], PDO::PARAM_STR);
  $sentenciaTotal->bindParam(':nombre_estado', $_SESSION['estado'], PDO::PARAM_STR);
}
if(!empty($_SESSION['anio'])){
$sentencia->bindParam(':anio_expedicion', $_SESSION['anio'], PDO::PARAM_STR);
$sentenciaTotal->bindParam(':anio_expedicion', $_SESSION['anio'], PDO::PARAM_STR);
}
// $sentencia->bindParam(':orden', $_SESSION['orden'], PDO::PARAM_STR);
// $sentenciaTotal->bindParam(':orden', $_SESSION['orden'], PDO::PARAM_STR);

//Ejecutar la sentencia
$sentencia->execute();
$sentenciaTotal->execute();
$normativa = $sentencia->fetchAll(PDO::FETCH_ASSOC);

$totalNormas = $sentenciaTotal->rowCount();
?>
<?php if($totalNormas==0){ ?>
	    <script>
		Swal.fire({
			title: 'Resultado no encontrado!',
			text: "Los parametros buscados NO coinciden con alguna norma registrada.",
			icon: 'info',
			confirmButtonColor: '#037207',
			confirmButtonText: 'aceptar'
		}).then((result) => {
		    if (result.isConfirmed) {
			window.location.href = "index.php";
		    }
		})
		</script>
<?php } ?>


<div class="container mt-5">
	<div class="row">
	    <div class="col-12 grid-margin">
			<div class="card">
		    	<div class="card-body">
					<div class="row">
						<div class="col-md-10">
							<h4>Filtro de Búsqueda</h4>
						</div>
						<div class="col-md-2">
							<a href="index.php" class="btn btn-success" style=" background-color: #037207;">Limpiar Busqueda</a>
						</div>
					</div>
					<form method="post" action="buscarNorma.php" class=" pt-0 p-3">
					<hr>
					<div class="row">
						<!-- Filtro Palabras Claves -->
						<div class="form-group mb-3 col-4">
							<label for="palabasClave" class="mb-1">Palabras Clave</label>
							<input type="text" id="buscar" name="palabrasClave" class="form-control"  value="<?php if (!empty($_SESSION['palabrasClaves'])){echo $_SESSION['palabrasClaves'];}?>" placeholder="Ejemplo: Educacion, Estudiante, Reglamento..." style="border: #bababa 1px solid; color:#000000;">
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
											<option value="<?php  echo $datos->nombre_dependencia ?>" <?php if($datos->nombre_dependencia == $_SESSION['dependencia']){ ?>selected <?php }?> > <?php echo $datos->nombre_dependencia ?> </option>
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
											<option value="<?php  echo $datos->nombre_clasificacion ?>" <?php if($datos->nombre_clasificacion == $_SESSION['clasificacion']){ ?>selected <?php }?> > <?php  echo $datos->nombre_clasificacion ?></option>
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
											<option value="<?php  echo $datos->nombre_estado ?>" <?php if($datos->nombre_estado == $_SESSION['estado']){ ?>selected <?php }?> > <?php  echo $datos->nombre_estado ?></option>
										<?php } ?>
							</select>
						</div>
						<!-- Filtro Select Orden -->
						<div class="form-group mb-3 col-4">
							<label for="orden" class="mb-1">Orden Búsqueda</label>
							<select id="orden" name="orden" class="form-control" style="border: #bababa 1px solid; color:#000000;" >
								<option value="" <?php if($_SESSION['ordenValue'] == 0){ ?> selected <?php }?>>- Seleccione un Orden -</option>
								<option value="4" <?php if($_SESSION['ordenValue'] == 4){ ?> selected <?php }?> >AÑO</option>
								<option value="2" <?php if($_SESSION['ordenValue'] == 2){ ?> selected <?php }?> >DEPENDENCIA</option>
								<option value="5" <?php if($_SESSION['ordenValue'] == 5){ ?> selected <?php }?> >ESTADO</option>
								<option value="3" <?php if($_SESSION['ordenValue'] == 3){ ?> selected <?php }?> >TIPO DE DOCUMENTO</option>
							</select>
						</div>
						<!-- Filtro Año expedición -->
						<div class="form-group mb-3 col-3">
							<label for="clasificacion" class="mb-1">Año de Expedición</label>
							<input class="form-control form-control" type="number" max="2050" min="1900" name="anio"  placeholder="Ejemplo: <?php echo date("Y"); ?> " value="<?php if(!empty($_SESSION['anio'])){echo $_SESSION['anio'];} ?>" >
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
							<thead class="align-middle">
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
									<td style="text-align: center;">
										<?php 
											if($datos["nombre_clasificacion"] == 'CONSTITUCIÓN POLÍTICA'){ 
												echo "
												<a class='btn btn-light p-0'  style='background-color: #b09a60' href='verDocumento.php?id=".$datos['codigo_gen']."&visita=1' title='Ver Documento' target='_BLANK'  >
												<img src='./img/Flag_of_Colombia.png' width='45' alt='Bandera de Colombia'>";
											}else{ ?>
												<a class='btn btn-light'  style="background-color: #b09a60;" href="verDocumento.php?id=<?php echo $datos["codigo_gen"];?>&visita=1" title="Ver Documento" target="_BLANK"  >
												<?php
													include './img/view_icon.svg';
												?>
												</a>
												<?php
											} ?>
									</td>
								</tr>
								<?php } 
								}?>
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
				<a class='page-link text-success' href='buscarNorma.php?pag=$atras'>
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
				<a class='page-link text-white'href='buscarNorma.php?pag=$i'>$i</a>
			</li>";
			} else {
			echo "
			<li class='page-item' aria-current='page'>
				<a class='page-link text-success' href='buscarNorma.php?pag=$i'>$i</a>	
			</li>";
			}
		}

		// Imprimimos el botón de "Siguiente" si no estamos en la última página
		if ($pag < $totalPaginacion) {
			$siguiente = $pag+1;
			echo "
			<li class='page-item' aria-current='page'>
				<a class='page-link text-success' href='buscarNorma.php?pag=$siguiente'>
				<span aria-hidden='true'>&raquo;</span>
				</a>
			</li>";
		}

	    ?>
	    </ul>
	</nav>

</div>




    




<?php
	include '../Models/footer.php'
?>

<?php $db =null; ?>

</body>
</html>