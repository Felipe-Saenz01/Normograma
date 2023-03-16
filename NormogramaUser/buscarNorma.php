<!DOCTYPE html>
<html>
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
  
  switch($_POST['orden']){
    case 2:
      $_SESSION['orden'] = "nombre_dependencia";
      break;
    case 3:
      $_SESSION['orden'] = "nombre_clasificacion";
      break;
    case 4:
      $_SESSION['orden'] = "anio_expedicion";
      break;
    case 5:
      $_SESSION['orden'] = "nombre_estado"; 
      break;
    default:
      $_SESSION['orden'] = "anio_expedicion";
      break;
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
  $queryOrigen= $query;
	//se enviaron palabas claves
	if(!empty($_SESSION['palabrasClaves'])){
		$aKeyword = explode(", ", $_SESSION['palabrasClaves']);
		// si hay mas de 1 palabra clave
    $palabasClaveQuery = "";
    // hay mas de 1 palabra clave
		if(count($aKeyword)>1){
			$palabasClaveQuery = "WHERE palabras_claves LIKE '%".$aKeyword[0]."%'";
			for($i = 1; $i < count($aKeyword); $i++) {
				$palabasClaveQuery .= " OR palabras_claves LIKE '%".$aKeyword[$i]."%'";
      }
      $query .= $palabasClaveQuery;
		}else{ // hay solo 1 palabra clave
			$palabasClaveQuery = "WHERE palabras_claves LIKE '%".str_replace(',', '', $aKeyword[0])."%'";
      $query .= $palabasClaveQuery;
		}
    //Se agregan los demas parametros,
    $queryParametros = sentenciaParametros($_SESSION['dependencia'], $_SESSION['clasificacion'], $_SESSION['estado'], $_SESSION['anio']);
    if($queryParametros != 0){
      $query .= "AND ".$queryParametros;
    }
	 $query .= " ORDER BY :orden ASC";
      
  }else{//no se enviaron palabras clave
    $queryParametros = sentenciaParametros($_SESSION['dependencia'], $_SESSION['clasificacion'], $_SESSION['estado'], $_SESSION['anio']);
    if($queryParametros != 0){
      $query .= "AND ".$queryParametros;
    }
	$query .= " ORDER BY :orden ASC";

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
$sentencia->bindParam(':orden', $_SESSION['orden'], PDO::PARAM_STR);
$sentenciaTotal->bindParam(':orden', $_SESSION['orden'], PDO::PARAM_STR);

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
			text: "La norma que desea buscar no existe o no se ha registrado todavía.",
			icon: 'info',
			confirmButtonColor: '#037207',
			confirmButtonText: 'aceptar'
		}).then((result) => {
		    if (result.isConfirmed) {
			window.location.href = "index_copy.php";
		    }
		})
		</script>
<?php } ?>


<div class="container mt-5">
	<div class="row">
	    <div class="col-12 grid-margin">
			<div class="card">
		    	<div class="card-body">
		    		<h4 class="card-title">Buscador</h4>
					<!--  Formulario    --->
		    		<form id="form2" method="POST" action="buscarNorma.php">		
						<div class="mb-3">
							<input type="text" class="form-control" id="buscar" name="palabrasClave" placeholder="Ingrese las palabras que desea buscar, separándolas Con una coma." value="<?php if (!empty($_SESSION['palabrasClaves'])){echo $_SESSION['palabrasClaves'];}?>" >
						</div>

		    			<h4 class="card-title">Filtro de búsqueda</h4>  
		    		
						<table class="table">
			    		<thead>
							<tr class="filters">
				   			<th>
								<select id="assigned-tutor-filter" id="buscadependencia" name="dependencia" class="form-control mt-2" style="border: #bababa 1px solid; color:#000000;" >
					    			<option value="">- Dependencia del documento-</option>
									<?php
									$consulta = $db->prepare("SELECT * FROM dependencia_normativa ORDER BY nombre_dependencia ASC");
									$consulta->execute();
									$ejecutar = $consulta->fetchAll(PDO::FETCH_OBJ);
									foreach ($ejecutar as $datos){ ?> 
									<option value="<?php  echo $datos->nombre_dependencia ?>" <?php if($datos->nombre_dependencia == $_SESSION['dependencia']){ ?>selected <?php }?> ><?php echo $datos->nombre_dependencia; ?> </option>
									<?php } ?>
					    		</select>
				    		</th>

				    		<th>
								<select id="assigned-tutor-filter" id="buscaclasificasion" name="clasificacion" class="form-control mt-2" style="border: #bababa 1px solid; color:#000000;" >
					    			<option value="">- Tipo de documento -</option>
					    			<?php 
					    			$consulta = $db->prepare("SELECT * FROM clasificacion_normativa ORDER BY nombre_clasificacion ASC");
									$consulta->execute();
					    			$ejecutar = $consulta->fetchAll(PDO::FETCH_OBJ);
									foreach ($ejecutar as $datos){ ?> 
					    			<option value="<?php  echo $datos->nombre_clasificacion ?>" <?php if($datos->nombre_clasificacion == $_SESSION['clasificacion']){ ?>selected <?php }?>  > <?php  echo $datos->nombre_clasificacion ?></option>
					    			<?php }?>
								</select>
				    		</th>

				    		<th>
								<select id="assigned-tutor-filter" id="buscaestado" name="estado" class="form-control mt-2" style="border: #bababa 1px solid; color:#000000;" >
									<option value="">- Estado del documento -</option>
									<?php 
									$consulta = $db->prepare("SELECT * FROM estado_documento ORDER BY nombre_estado ASC");
									$consulta->execute();
									$ejecutar = $consulta->fetchAll(PDO::FETCH_OBJ);
									foreach ($ejecutar as $datos){ ?> 
									<option value="<?php  echo $datos->nombre_estado ?>" <?php if($datos->nombre_estado == $_SESSION['estado']){ ?>selected <?php }?>  > <?php  echo $datos->nombre_estado ?></option>
									<?php } ?>
								</select>
				    		</th>

				    		<th>
								<input class="form-control form-control" type="number" max="2040" min="1900" name="anio" value="<?php if(!empty($_SESSION['anio'])){echo $_SESSION['anio'];} ?>"  placeholder="Año expedición " >
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
										
									</th>
								</tr>
							</thead>
						</table>
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
									<td style="text-align: center;"><a class='btn btn-info' href="verDocumento.php?id=<?php echo $datos["codigo_gen"];?>&visita=1" title="Ver Documento" target="_BLANK"  ><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16">
										<path d="M23.821,11.181v0C22.943,9.261,19.5,3,12,3S1.057,9.261.179,11.181a1.969,1.969,0,0,0,0,1.64C1.057,14.739,4.5,21,12,21s10.943-6.261,11.821-8.181A1.968,1.968,0,0,0,23.821,11.181ZM12,19c-6.307,0-9.25-5.366-10-6.989C2.75,10.366,5.693,5,12,5c6.292,0,9.236,5.343,10,7C21.236,13.657,18.292,19,12,19Z"/>
										<path d="M12,7a5,5,0,1,0,5,5A5.006,5.006,0,0,0,12,7Zm0,8a3,3,0,1,1,3-3A3,3,0,0,1,12,15Z"/></svg></a>
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






<div>
	<center>
		<nav aria-label="Paginación Normativas">
			<ul class="pagination">
			  <?php
			  $totalPaginacion = ceil($totalNormas/$limit);
			  for($i=1;$i<=$totalPaginacion; $i++){
			  ?>	
			    <li class="page-item" aria-current="page">
			      <a class="page-link" href="buscarNorma.php?pag=<?php echo $i; ?>"><?php echo $i ?></a>
			    </li>
		      <?php } ?>
		  </ul>
		</nav>
	</center>
</div>





    




<?php
	include '../Models/footer.php'
?>

<?php $db =null; ?>

</body>
</html>