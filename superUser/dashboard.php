<?php

    include '../Models/conexion.php';

    session_start();

    if(!isset($_SESSION['rol'])){
        header('location: ../Login.php');
    }else{
        if($_SESSION['rol'] != 1){
            header('location: ../Login.php');
        }
    }

		
//----------------------------------------------------------------------
$sentenciaTotalNormativas = $db->query("SELECT * FROM v_normativas");
$totalNormativas= $sentenciaTotalNormativas->rowCount();
// Funcion obtener datos para las graficas
// Argumentos:
//	$parametros  	 -> esta variable es un array que contiene los nombres de los parametros que se buscarán
// 	$sentencia	 	 -> esta variable es el string de la sentencia para buscar la cantidad de normativas con el parametro
//	$contador	 	 -> esta variable es un string que es el parametro del array al realizar la consulta
//	$totalNormativas -> esta variable es el total de normativas en la base de datos
function obtenerDatos($parametros, $sentencia, $contador, $totalNormativas){
	include '../Models/conexion.php'; //conexion a la base de datos
	$datosGraficaFinal=""; // inicializacion del string final que irá a la libreria de las graficas
	foreach($parametros as $datos){
		$sentenciaFinal= $sentencia."LIKE '".$datos."'"; //se agrega la busqueda del parametro en la sentencia
		$sentenciaVistas = $db->query($sentenciaFinal); //se ejecuta la sentencia
		$resultadoSentenciaVistas = $sentenciaVistas->fetch(PDO::FETCH_ASSOC); // se guarda el array resultado de la sentencia
		// if para convertir en porcentaje la cantidad de normas con el parametro
		if($resultadoSentenciaVistas[$contador]==0){ //condicional para evitar la division en cero
			$porcentaje= 0;
		}else{
			$porcentaje = ($resultadoSentenciaVistas[$contador]*100)/$totalNormativas;
			$porcentaje = round($porcentaje,2);
		}
		$datosGraficaFinal=$datosGraficaFinal."{name: '".$datos."', y: ".$porcentaje."},";
		
	}
	$datosGraficaFinal = substr($datosGraficaFinal,0,-1);

	return $datosGraficaFinal;

}

// Obtener Datos Dependencias y sus porcentajes
//Inicializa variable del string para la grafica
$DatosGraficaDependencias="";
//sentencia para buscar todos las Dependencias y almacenarlas en el array por medio de un bucle
$SentenciaDep = $db->query('SELECT nombre_dependencia FROM dependencia_normativa');
$Depen = $SentenciaDep->fetchAll(PDO::FETCH_OBJ);    
$nombresDependencias =array();
foreach($Depen as $a){
    array_push($nombresDependencias,$a->nombre_dependencia);
}
$consultaDependencias = "SELECT count(nombre_dependencia) FROM v_normativas WHERE nombre_dependencia ";
$contador = "count(nombre_dependencia)";
$DatosGraficaDependencias = obtenerDatos($nombresDependencias,$consultaDependencias, $contador, $totalNormativas);

//----------- Obtener Datos Clasificaciones--------------------------------------------------
$datosGraficaClasificaciones="";
//sentencia para buscar todos las Dependencias y almacenarlas en el array por medio de un bucle
$SentenciaClas = $db->query('SELECT nombre_clasificacion FROM clasificacion_normativa');
$Clasi = $SentenciaClas->fetchAll(PDO::FETCH_OBJ);
$nombresClasificaciones =array();
foreach($Clasi as $a){
    array_push($nombresClasificaciones,$a->nombre_clasificacion);
}
$consultaClasificacion = "SELECT count(nombre_clasificacion) FROM v_normativas WHERE nombre_clasificacion ";
$contador = "count(nombre_clasificacion)";

$datosGraficaClasificaciones = obtenerDatos($nombresClasificaciones, $consultaClasificacion, $contador, $totalNormativas);



//----------- Obtener Datos Emisores--------------------------------------------------
$DatosGraficaEmisores="";
//sentencia para buscar todos las Dependencias y almacenarlas en el array por medio de un bucle
$SentenciaEmisor = $db->query('SELECT nombre_emisor FROM quien_emite_normativa');
$Emisor = $SentenciaEmisor->fetchAll(PDO::FETCH_OBJ);
$nombresEmisor =array();
foreach($Emisor as $a){
    array_push($nombresEmisor,$a->nombre_emisor);
}
$consultaEmisores = "SELECT count(nombre_emisor) FROM v_normativas WHERE nombre_emisor ";
$contador = "count(nombre_emisor)";

$DatosGraficaEmisores = obtenerDatos($nombresEmisor, $consultaEmisores, $contador, $totalNormativas);


//----------- Obtener Datos Estado--------------------------------------------------
$DatosGraficaEstados="";
//sentencia para buscar todos las Dependencias y almacenarlas en el array por medio de un bucle
$SentenciaEstados = $db->query('SELECT nombre_estado FROM estado_documento');
$Estados = $SentenciaEstados->fetchAll(PDO::FETCH_OBJ);
$nombresEstados =array();
foreach($Estados as $a){
    array_push($nombresEstados,$a->nombre_estado);
}
$consultaEstados = "SELECT count(nombre_estado) FROM v_normativas WHERE nombre_estado ";
$contador = "count(nombre_estado)";
$DatosGraficaEstados = obtenerDatos($nombresEstados, $consultaEstados, $contador, $totalNormativas);


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DashBoard</title>
        <link rel="icon" href="../img/favicon.ico">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<style type="text/css">
.highcharts-figure,
.highcharts-data-table table {
    min-width: 320px;
    max-width: 800px;
    margin: 1em auto;
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #ebebeb;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}

.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}

.highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
}

.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
    padding: 0.5em;
}

.highcharts-data-table thead tr,
.highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}

.highcharts-data-table tr:hover {
    background: #f1f7ff;
}

input[type="number"] {
    min-width: 50px;
}

</style>




<script src="../code/highcharts.js"></script>
<script src="../code/modules/exporting.js"></script>
<script src="../code/modules/export-data.js"></script>
<script src="../code/modules/accessibility.js"></script>
</head>

<body >

<header>
	<div class="container-border text-center">
		<img src="../img/LOGO2.png" alt="banner" class="img-fluid">
	</div>

<nav class="navbar navbar-expand-lg navbar-dark  mb-4" style="background-color: #037207">
    <div class="container-fluid">
	<a class="navbar-brand" href="dashboard.php"><?php echo "USUARIO: ".$_SESSION['usuarioname'] ?></a>
	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
	    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
	    <li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Normativa</a>
		<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
		<li><a class="dropdown-item" href="superUser.php">Cargar Norma</a></li>
		</ul>
	    </li>

	<li class="nav-item dropdown">
	    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Parametrización</a>
	    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
		<li><a class="dropdown-item" href="Parametrizacion/Clasificacion.php">Clasificación</a></li>
		<li><a class="dropdown-item" href="Parametrizacion/Dependencia.php">Dependencia</a></li>
		<li><a class="dropdown-item" href="Parametrizacion/Estado.php">Estado</a></li>
		<li><a class="dropdown-item" href="Parametrizacion/Emisor.php">Emisores</a></li>
		<li><a class="dropdown-item" href="Parametrizacion/Cuentas.php">Usuarios</a></li>	
	    </ul>
        </li>


	<li class="nav-item dropdown">
	    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Sesión</a>
	    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
			<li><a class="dropdown-item" href="salir.php">Cerrar sesión</a></li>
            <li><a class="dropdown-item" href="salir2.php">Salir</a></li>
	    </ul>
        </li>


    </div>
  </div>
</nav>

</header>

<!--graficas-->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<!--Boostrap5-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.min.js"></script>

<div class="container mt-5">
	
	<div class="col-12-">

		<div class="row">

			<div class="col-12 grid-margin">

				<div class="card">

					<div class="card-body">

						<h4 class="card-title fw-bold fs-3">Generar informe <button style="background-color: white;" type="button" class="btn btn-light" 
							data-bs-toggle="tooltip" data-bs-placement="top" 
							title="Al presionar el botón (Generar), se creará un reporte/informe con las normativas que cumplan con los parámetros especificados en los filtros."><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
								<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
								<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
							</svg></button></h4>
					<center>

						<form class="form-inline" method="POST" action="informes/informe.php" target="_BLANK" >

							<div class="col-11">
								<table class="table">
									<thead>
										<tr class="filters">

											<th>

											<label class="form-control-sm">Dependencia</label>
												<select class="form-control mt-2" name="dependencia_normativa">
													<option value="">- Seleccione Dependencia -</option>
													<?php
													$busqeda = $db->query("SELECT * FROM dependencia_normativa ORDER BY nombre_dependencia ASC");
													$dependencia = $busqeda->fetchAll(PDO::FETCH_OBJ);
													foreach($dependencia as $datos){
													?>
													<option  value="<?php echo $datos->codigo;?>"><?php echo $datos->nombre_dependencia;?></option>
													<?php
													}
													?>  
												</select> 
												
											</th>

											<th>

											<label class="form-control-sm">Tipo Documento</label>
												<select class="form-control mt-2" name="clasificacion_normograma">
												<option value="">- Seleccione Clasificacion -</option>
												<?php
												$busqeda = $db->query("SELECT * FROM clasificacion_normativa ORDER BY nombre_clasificacion ASC");
												$dependencia = $busqeda->fetchAll(PDO::FETCH_OBJ);
												foreach($dependencia as $datos){
												?>
												<option value="<?php echo $datos->codigo;?>" ><?php echo $datos->nombre_clasificacion;?></option>
												<?php
												}
												?>  
												</select> 

											</th>

											<th>

												<label class="form-control-sm">Año de Expedicion</label>
													<input class="form-control form-control mt-2" type="year" max="2030" min="2015" name="anio_expedicion" placeholder="Digite el año a buscar." value="">
								
											</th>

											
											

										</tr>
									</thead>
								</table>

							</div>

							<div class="col-11">
											
								<table class="table">
										<thead>
											<tr class="filters">
											<th>

												<label class="form-control-sm">Estado</label>
													<select class="form-control mt-2" name="estado_documento">
														<option value="">- Seleccione Estado -</option>
														<?php
														$busqeda = $db->query("SELECT * FROM estado_documento ORDER BY nombre_estado ASC");
														$dependencia = $busqeda->fetchAll(PDO::FETCH_OBJ);
														foreach($dependencia as $datos){
														?>
														<option value="<?php echo $datos->codigo;?>" ><?php echo $datos->nombre_estado;   ?></option>
														<?php
														}
														?>  
													</select> 

												</th>

												<th>

												<label class="form-control-sm">Emisor/Emitido por</label>
													<select class="form-control mt-2" name="quien_emite_normativa">
														<option value="">- Seleccione Emisor -</option>
														<?php
														$busqeda = $db->query("SELECT * FROM quien_emite_normativa ORDER BY nombre_emisor ASC");
														$dependencia = $busqeda->fetchAll(PDO::FETCH_OBJ);
														foreach($dependencia as $datos){
														?>
														<option value="<?php echo $datos->codigo;?>" ><?php echo $datos->nombre_emisor;   ?></option>
														<?php
														}
														?>  
													</select> 

												</th>

												<th>
													<div class="col-1">
													<input type="hidden" name="ocultoinforme" value="1">
													<input type="hidden" name="id" value="id">
													<input class="btn btn-sm btn-success mt-3" type="submit"  value="Generar" name="btn_create_normograma" style="margin-top: 38px; background-color: #037207; color: white;" >
													</br>
													</div>	
												</th>
											</tr>
									</thead>
								</table>
						
							</div>
						
							

					</div>
					
					
					</form>

					</div>
													
				</center>


<div class="container mt-5">
    <div class="col-12">
	<div class="row">
	    <div class="col-12 grid-margin">
		<div class="card">
		    <div class="card-body">
			<h4 class="card-title fw-bold fs-3">Generar informe vistas/Descargas normativas 
			    <button style="background-color: white;" type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="En este apartado se podrá generar un informe de las normativas más buscadas o las búsquedas de una norma en específico.">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
				    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
				    <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
				</svg>
			    </button>
			</h4>
			
			<form class="form-horizontal" method="POST" action="Informes/informeVistas.php" target="_BLANK" >
			    <div class="col-11 form-group">
				<table class="table">
				    <thead>
					<tr class="filters">


					    <th>
						<label class="form-control-sm"><b>Número de normativa</b></label>
						<input class="form-control form-control-sm" type="number" max="100000" min="0" name="numero_norma" placeholder="Digite el número de la norma" >
					    </th>
					    <th>
						<input type="hidden" name="ocultoinforme" value="1">
						<input class="btn btn-sm btn-success" type="submit" value="Generar" style="background-color:#037207;" >
					    </th>
					</tr>
				    </thead>
				</table>
			    </div>
			</form>
		    </div>
		</div>
	    </div>
	</div>
    </div>
</div>





<div class="container">

		

<div class="col-12 grid-margin">

<div class="card mt-3">

	<div class="row">
	<div class="col-md-3 ms-4">
	<div class="col-md-15 fw-bold fs-3">
	DashBoard<button style="background-color: white;" type="button" class="btn btn-light" 
	data-bs-toggle="tooltip" data-bs-placement="top" 
	title="Las gráficas representan la cantidad de normas que hay registradas según la parametrización establecida."><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
		<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
		<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
	</svg></button></h3>
	</div>
	</div>
	</div>
	
	


	<div class="row ms-2 me-2 mt-4">
	    <div class="col md-6">
		<div class="card card-primary">
		    <div class="col-xxl-3-responsive">
			<div class="mb-3" style=" max-width: 90rem; max-height: 90rem;">
			    <figure class="highcharts-figure">
				<div id="containerDependencias" style="width:100%; height:400px;"></div>
			    </figure>
			</div>
		    </div>
		</div>
	    </div>

	    <div class="col md-6">
		<div class="card card-info">
		    <div class="col-xxl-3-responsive">
			<div class="mb-3" style=" max-width: 90rem; max-height: 90rem;">
			    <figure class="highcharts-figure">
				<div id="containerClasificacion" style="width:100%; height:400px;"></div>
			    </figure>
			</div>
		    </div>
		</div>
	    </div>
	</div>

	<div class="row ms-2 me-2 mt-5">
	    <div class="col md-6">
		<div class="card card-primary">
		    <div class="col-xxl-3-responsive">
			<div class="mb-3" style=" max-width: 90rem; max-height: 90rem;">
			    <figure class="highcharts-figure">
				<div id="containerEmisor" style="width:100%; height:400px;"></div>
			    </figure>
			</div>
		    </div>
		</div>
	    </div>

	    <div class="col md-6">
		<div class="card card-info">
		    <div class="col-xxl-3-responsive">
			<div class="mb-3" style=" max-width: 90rem; max-height: 90rem;">
			    <figure class="highcharts-figure">
				<div id="containerEstados" style="width:100%; height:400px;"></div>
			    </figure>
			</div>
		    </div>
		</div>
	    </div>
	</div>	
								
			
</div>
</div>

</div>

				</div>

		
			</div>

		</div>

    </div>




<script type="text/javascript">
Highcharts.chart('containerDependencias', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Dependencias'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: ''
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
        }
    },
    series: [{
        name: 'Dependencia',
        colorByPoint: true,
	data: [<?php echo $DatosGraficaDependencias; ?>]
    }]
});
</script>



<script type="text/javascript">
Highcharts.chart('containerClasificacion', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Clasificación'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: ''
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
        }
    },
    series: [{
        name: 'Dependencia',
        colorByPoint: true,
	data: [<?php echo $datosGraficaClasificaciones; ?>]
    }]
});
</script>



<script type="text/javascript">
Highcharts.chart('containerEmisor', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Emisores'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: ''
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
        }
    },
    series: [{
        name: 'Dependencia',
        colorByPoint: true,
	data: [<?php echo $DatosGraficaEmisores; ?>]
    }]
});
</script>



<script type="text/javascript">
Highcharts.chart('containerEstados', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Estados'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: ''
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
        }
    },
    series: [{
        name: 'Dependencia',
        colorByPoint: true,
	data: [<?php echo $DatosGraficaEstados; ?>]
    }]
});
</script>



<script src="https://code.highcharts.com/highcharts.js"></script>

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
</body>
</html>
<?php $db=null; ?>
