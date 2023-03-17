<?php


    include '../Models/conexion.php';

    /* 
    * Se utilizará un condicionamiento para manejar el insertar normativas y editarlas en el mismo Formulario
    *  la variable $condicion se encargará de esto, esta se inicializa con 1 que quiere decir que es formulario de ingreso de Información
    *  si $condicion pasa a a ser 2 eso quiere decir que es formulario de edicion y actualizacion de la normativa y cambiará las propiedades
    *  de titulos y valores de los inputs del formulario.
    *
    *  para entrar a la fomra de edicion y actualizacion: se debe dar al boton de editar de alguna norma de la lista, la cual enviará el id
    *  de ese registro a este mismo archivo por get (JustUser.php?id='id de la norma') asi que en caso de que este archivo reciba este id
    *  por get la condicion pasará a ser 2, se realizará una consulta a la base de datos la cual traerá la informacion de ese registro y 
    *  las propiedades del formulario cambiarán con esa informacion.
    */
    $where="";
    if(isset($_GET['tipoDoc']) and isset($_GET['anio_expedicion']) and isset($_GET['numero_norma']) ){
	$tipoDoc = $_GET['tipoDoc'];
	$anio = $_GET['anio_expedicion'];
	$numero_norma = $_GET['numero_norma'];

	if(empty($tipoDoc) and empty($anio) and empty($numero_norma)){
	    header('location: JustUser.php');
	}else{
	    $where= "WHERE codigo_clasificacion_normograma LIKE '%".$tipoDoc."%' AND anio_expedicion LIKE '%".$anio."%' AND numero_norma LIKE '%".$numero_norma."%' ";
	}
    }

	//alertas de tipo success
	$alertaDel=1;
	$alertaGu=1;
	$alertaActu=1;
	//alertas de tupo error/fallo
	$AlertExed=1;
	$AlertErrorBorrarArchivo=1;
	$AlertErrorActualizarNorma=1;
	$Alertexis=1;

	$condicion=1; // Variable para condicionar formulario: 1 = Insertar y 2 = Actualizar/Editar.
	$archivoExistente=0;// variable para controlar el archivo en caso de editarse, inicia en 0 pero si existe un archivo pasa a 1.
	$rutaCompleta = "a";// inicializacion variable de ruta del archivo del registro a editar.

	//reciviendo alertas succes
	if(isset($_GET['alert'])){
		$alertaGu=2;
	}

	if(isset($_GET['alert1'])){
		$alertaDel=2;
	}

	if(isset($_GET['alert2'])){
		$alertaActu=2;
	}

	//reciviendo alerta errores

	if(isset($_GET['Error1'])){
		$AlertExed=2;
	}

	if(isset($_GET['Error2'])){
		$Alertexis=2;
	}
	if(isset($_GET['Error3'])){
		$AlertErrorBorrarArchivo=2;
	}
	if(isset($_GET['Error4'])){
	    $AlertErrorActualizarNorma=2;
	}

	if(isset($_GET['id'])){
	    $id=$_GET['id'];
	    $condicion=2;
	
	    $sentenciaBuscar = $db->prepare("SELECT Nom.directorio, Nom.codigo_gen, Nom.numero_norma, Nom.anio_expedicion, Nom.mes_expedicion, Nom.dia_expedicion, Nom.asunto, Nom.fecha_cargue, Nom.palabras_claves,
	    Clasno.nombre_clasificacion, Estdoc.nombre_estado, Emis.nombre_emisor, Depen.nombre_dependencia 
	    FROM normativa Nom
	    INNER JOIN clasificacion_normativa Clasno ON Nom.codigo_clasificacion_normograma = Clasno.codigo
	    INNER JOIN estado_documento Estdoc ON Nom.estado = Estdoc.codigo
	    INNER JOIN quien_emite_normativa Emis ON Nom.codigo_quien_emite_normativa = Emis.codigo
	    INNER JOIN dependencia_normativa Depen ON Nom.codigo_dependencia_normativa = Depen.codigo WHERE codigo_gen = ? ORDER BY anio_expedicion ASC;");
	    $sentenciaBuscar->execute([$id]);
	    $nornormaEdit = $sentenciaBuscar->fetch(PDO::FETCH_OBJ);

	    // busqueda de la ruta del archivo del registro que se esta actualizando
	    $path = "../Archivos/".$nornormaEdit->codigo_gen; //path o ruta donde esta el archivo
	    if(file_exists($path)){
		$directorioArch = opendir($path);
		while($archivo = readdir($directorioArch)){
		    if(!is_dir($archivo)){
		    $archivoExistente=1;    
		    }
		}
	    }

	}
	//Pagiación
    
	if (isset($_GET['pag'])){
	    $pag = (int)$_GET['pag'];
	}else{
	    $pag = 1;
	}
    
	$limit = 5;
	$offset=($pag-1)*$limit;
	// Sentencia limitada a la cantidad de registros a mostrar y se gestionarán por la Paginación
	$sentencia = $db->query("SELECT Nom.directorio, Nom.codigo_gen, Nom.numero_norma, Nom.anio_expedicion, Nom.mes_expedicion, Nom.dia_expedicion, Nom.asunto, Nom.fecha_cargue,
	Clasno.nombre_clasificacion, Estdoc.nombre_estado, Emis.nombre_emisor, Depen.nombre_dependencia 
	FROM normativa Nom
	INNER JOIN clasificacion_normativa Clasno ON Nom.codigo_clasificacion_normograma = Clasno.codigo
	INNER JOIN estado_documento Estdoc ON Nom.estado = Estdoc.codigo
	INNER JOIN quien_emite_normativa Emis ON Nom.codigo_quien_emite_normativa = Emis.codigo
	INNER JOIN dependencia_normativa Depen ON Nom.codigo_dependencia_normativa = Depen.codigo $where ORDER BY anio_expedicion ASC LIMIT $offset, $limit");


	$normativa = $sentencia ->fetchAll(PDO::FETCH_OBJ);
	//sentencia para buscar el total de registros
	$sentenciaTotal = $db->query("SELECT Nom.directorio, Nom.codigo_gen, Nom.numero_norma, Nom.anio_expedicion, Nom.mes_expedicion, Nom.dia_expedicion, Nom.asunto, Nom.fecha_cargue,
	Clasno.nombre_clasificacion, Estdoc.nombre_estado, Emis.nombre_emisor, Depen.nombre_dependencia 
	FROM normativa Nom
	INNER JOIN clasificacion_normativa Clasno ON Nom.codigo_clasificacion_normograma = Clasno.codigo
	INNER JOIN estado_documento Estdoc ON Nom.estado = Estdoc.codigo
	INNER JOIN quien_emite_normativa Emis ON Nom.codigo_quien_emite_normativa = Emis.codigo
	INNER JOIN dependencia_normativa Depen ON Nom.codigo_dependencia_normativa = Depen.codigo $where ORDER BY anio_expedicion ASC ");
	$totalNormas = $sentenciaTotal->rowCount();

	session_start();

    if(!isset($_SESSION['rol'])){
        header('location: ../Login.php');
    }else{
        if($_SESSION['rol'] != 3){
            header('location: ../Login.php');
        }
    }

	include './userInfo.php';
?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Normativas</title>
        <link rel="icon" href="../img/favicon.ico">
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
		<li><a class="dropdown-item" href="JustUser.php">Cargar Norma</a></li>
		</ul>
	    </li>

	<li class="nav-item dropdown">
	    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Sesión</a>
	    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
		<li><a class="dropdown-item" type="button"data-bs-toggle="modal" data-bs-target="#userInfo" >Cambiar Contraseña</a></li>
			<li><a class="dropdown-item" href="salir.php">Cerrar sesión</a></li>
            <li><a class="dropdown-item" href="salir2.php">Salir</a></li>
	    </ul>
        </li>


    </div>
  </div>
</nav>

</header>

<!---- Llamando alertas y cargando Script de las Alertas ------------->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../Models/Alertas.js"></script>
<!-------------------------------------------------------------------->

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.min.js"></script>


<?php

	if($alertaGu==2){
		?>
		<script>
		Swal.fire({
			title: 'Guardado',
			text: "La norma se ha guardado correctamte",
			icon: 'success',
			confirmButtonColor: '#037207',
			confirmButtonText: 'aceptar'
		}).then((result) => {
		    if (result.isConfirmed) {
			window.location.href = "JustUser.php";
		    }
		})
		</script>
	 <?php
	}

?>

<?php

	if($alertaDel==2){
		?>
		<script>
		Swal.fire({
			title: 'Eliminado',
			text: "Se ha eliminado la norma correctamente",
			icon: 'success',
			confirmButtonColor: '#037207',
			confirmButtonText: 'aceptar'
		}).then((result) => {
		    if (result.isConfirmed) {
			window.location.href = "JustUser.php";
		    }
		})	
		</script>
	 <?php
	}

?>

<?php

	if($alertaActu==2){
		?>
		<script>
		Swal.fire({
			title: 'Actualizado',
			text: "Se ha actualizado la norma correctamente",
			icon: 'success',
			confirmButtonColor: '#037207',
			confirmButtonText: 'aceptar'
		}).then((result) => {
		    if (result.isConfirmed) {
			window.location.href = "JustUser.php";
		    }
		})	
		</script>
	 <?php
	}

?>

<?php

	if($AlertExed==2){
		?>
		<script>
		Swal.fire({
			title: 'Error al cargar archivo!',
			text: "El archivo supera los 10MB o no corresponde a un archivo tipo pdf",
			icon: 'warning',
			confirmButtonColor: '#037207',
			confirmButtonText: 'aceptar'
		}).then((result) => {
		    if (result.isConfirmed) {
			window.location.href = "JustUser.php";
		    }
		})
		</script>
	 <?php
	}

?>

<?php

	if($Alertexis==2){
		?>
		<script>
		Swal.fire({
			title: 'Archivo ya existente!',
			text: "ya ha registrado un archivo con el mismo nombre",
			icon: 'warning',
			confirmButtonColor: '#037207',
			confirmButtonText: 'aceptar'
		}).then((result) => {
		    if (result.isConfirmed) {
			window.location.href = "JustUser.php";
		    }
		})
		</script>
	 <?php
	}

?>

<?php

	if($AlertErrorBorrarArchivo==2){
		?>
		<script>
		Swal.fire({
			title: 'Error al eliminar el archivo!',
			text: "Algo ocurrió y no se pudo guardar el archivo",
			icon: 'warning',
			confirmButtonColor: '#037207',
			confirmButtonText: 'aceptar'
		}).then((result) => {
		    if (result.isConfirmed) {
			window.location.href = "JustUser.php";
		    }
		})
		</script>
	 <?php
	}

?>

<?php

	if($AlertErrorActualizarNorma==2){
		?>
		<script>
		Swal.fire({
			title: 'Error al Actualizar la Normativa!',
			text: "Algo ocurrió y no se pudo actualizar la normativa",
			icon: 'warning',
			confirmButtonColor: '#037207',
			confirmButtonText: 'aceptar'
		}).then((result) => {
		    if (result.isConfirmed) {
			window.location.href = "JustUser.php";
		    }
		})
		</script>
	 <?php
	}

?>
<div class="container-fluid">
    <div class="col-12">
	<div class="row">
	    <div class="col-12 grid-margin">
		<div class="card">
		    <div class="card-body">
			<h4 class="card-title">Filtro Normativas 
			    <button style="background-color: white;" type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="En este apartado se podrán filtrar normativas según los parámetros seleccionados.">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
				    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
				    <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
				</svg>
			    </button>
			</h4>
			
			<form class="form-horizontal" method="GET" action="JustUserBusqueda.php">
			    <div class="col-11 form-group">
				<table class="table">
				    <thead>
					<tr class="filters">
					    <th>
						<label class="form-control-sm"><b>Tipo Documento</b></label>
						<select class="form-control mt-2" name="tipoDoc">
						    <option value="">- Seleccione Clasificación -</option>
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
						<label class="form-control-sm"><b>Año de Expedición</b></label>
						<input class="form-control form-control-sm" type="number" max="2030" min="1900" name="anio_expedicion" placeholder="Digite el año de expedición." >
					    </th>

					    <th>
						<label class="form-control-sm"><b>Número de Normativa</b></label>
						<input class="form-control form-control-sm" type="number" max="100000" min="0" name="numero_norma" placeholder="Digite el numero de la norma." >
					    </th>
					    <th>
						<input class="btn btn-sm btn-success" type="submit" style="background-color:#037207;" value="Buscar" >
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



<div class="container-fluid " >
    <div class="row">
	<div class="table-responsive col-md-8">
	    <h3>Lista Normativa <button style="background-color: white;" type="button" class="btn btn-light" 
		data-bs-toggle="tooltip" data-bs-placement="top" 
		title="La tabla contiene la lista de normativas institucionales"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
			<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
			<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
		</svg></button></h3>

<div class="table-responsive-xxl">

<table class="table">
	    <thead>
			<tr style="background-color:#037207; color:#FFFFFF;">

				<th style=" text-align: center;">Tipo Documento</th>
				<th style=" text-align: center;">Núm. Norma</th>
				<th style=" text-align: center;">Fecha Expedición</th>

				<th style=" text-align: center;">Asunto</th>
				<th style=" text-align: center;">Estado</th>
				<th style=" text-align: center;">Ver</th>
				<th style=" text-align: center;">Editar</th>
			</tr>   
		</thead>
	    
		<tbody>
	    <?php
	    if(count($normativa)>0){
		foreach($normativa as $datos){
	    ?>
			<tr>

				<td style=" text-align: center;"><?php echo $datos->nombre_clasificacion; ?></td>
				<td style=" text-align: center;"><?php echo $datos->numero_norma; ?></td>
				<td style=" text-align: center;"><?php echo $datos->dia_expedicion."/".$datos->mes_expedicion."/".$datos->anio_expedicion; ?></td>

				<td style=" text-align: justify;"><p><?php echo $datos->asunto; ?></p></td>
				<td style=" text-align: center;"><?php echo $datos->nombre_estado; ?></td>
				<!-- Elemento/Boton de Ver Documento   ------>
				<td style="text-align: center;"><a class='btn btn-info' href="verDocumento.php?id=<?php echo $datos->codigo_gen; ?>" title='Ver archivo adjunto' target="_BLANK" ><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16">
				    <path d="M23.821,11.181v0C22.943,9.261,19.5,3,12,3S1.057,9.261.179,11.181a1.969,1.969,0,0,0,0,1.64C1.057,14.739,4.5,21,12,21s10.943-6.261,11.821-8.181A1.968,1.968,0,0,0,23.821,11.181ZM12,19c-6.307,0-9.25-5.366-10-6.989C2.75,10.366,5.693,5,12,5c6.292,0,9.236,5.343,10,7C21.236,13.657,18.292,19,12,19Z"/>
				    <path d="M12,7a5,5,0,1,0,5,5A5.006,5.006,0,0,0,12,7Zm0,8a3,3,0,1,1,3-3A3,3,0,0,1,12,15Z"/></svg></a></td>
				<!-- Elemento/Boton de Editar normativa ----->
				<td style="text-align: center;"><a class="btn btn-warning" href="JustUser.php?id=<?php echo $datos->codigo_gen; ?>" title="Editar Normativa"  ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
				    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
				    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/></svg></a>
				</td>
			</tr> 
		</tbody>



	    <?php
		}
	    
	    ?>

	    
	    </table>
	    

</div>
	    <nav aria-label="Paginación Normativas">
		<ul class="pagination justify-content-center">
		    <?php
		    $totalPaginacion = ceil($totalNormas/$limit);
		    for($i=1;$i<=$totalPaginacion; $i++){
		    ?>
		    <li class="page-item" aria-current="page">
			<a class="page-link text-success" href="<?php echo "JustUserBusqueda.php?tipoDoc=".$tipoDoc."&anio_expedicion=".$anio."&numero_norma=".$numero_norma."&pag=".$i; ?>"><?php echo $i ?></a>
		    </li>
		<?php } ?>
		</ul>
	    </nav>

	    <?php  
	    }
	    ?>
	    <?php if(count($normativa)==0){ ?>
	    <script>
		Swal.fire({
			title: 'Resultado no encontrado!',
			text: "La norma que desea buscar no existe o no se ha registrado todavía.",
			icon: 'info',
			allowOutsideClick: false,
			allowEscapeKey:false,
			allowEnterKey:false,
			confirmButtonColor: '#037207',
			confirmButtonText: 'aceptar'
		}).then((result) => {
		    if (result.isConfirmed) {
			window.location.href = "JustUser.php";
		    }
		})
		</script>
	    <?php } ?>
</div>
<!---Formulario ingreso de datos ----------------------------------------------->

<div class="col-md-3 shadow"> <!--- Condicionamiento para titulo con la variable de condicion-->
        <hr><h4><?php if($condicion==1){ ?>Ingreso Nueva Normativa<?php }else{ ?> Editar Normativa <?php } ?></h4><hr>

    <form class="form-inline" method="POST" action="<?php if($condicion==1){ ?>insertar.php<?php }else{ ?>editarProceso.php<?php } ?>" enctype="multipart/form-data" >

<!--------- Lista desplegable dependencia_normativa  ---------------------------->

    <div class="form-group "> 
	<label class="form-control-sm">Dependencia</label>
	<select class="form-control mt-2" name="codigo_dependencia_normativa" required>
	    <option value="">- Seleccione Dependencia -</option>
	    <?php
	    $busqeda = $db->query("SELECT * FROM dependencia_normativa ORDER BY nombre_dependencia ASC");
	    $dependencia = $busqeda->fetchAll(PDO::FETCH_OBJ);
	    foreach($dependencia as $datos){
	    ?>
	    <option  value="<?php echo $datos->codigo;?>" <?php if($condicion==2 and $datos->nombre_dependencia == $nornormaEdit->nombre_dependencia){ ?> selected <?php } ?> required ><?php echo $datos->nombre_dependencia;?></option>
	    <?php
	    }
	    ?>  
	</select> 
    </div>

<!--------- Lista desplegable clasificacion_normativa  -------------------------->

    <div class="form-group ">
	<label class="form-control-sm">Tipo Documento</label>
	<select class="form-control mt-2" name="codigo_clasificacion_normograma" required>
	    <option value="">- Seleccione Clasificación -</option>
	    <?php
	    $busqeda = $db->query("SELECT * FROM clasificacion_normativa ORDER BY nombre_clasificacion ASC");
	    $dependencia = $busqeda->fetchAll(PDO::FETCH_OBJ);
	    foreach($dependencia as $datos){
	    ?>
	    <option value="<?php echo $datos->codigo;?>" <?php if($condicion==2 and $datos->nombre_clasificacion == $nornormaEdit->nombre_clasificacion){ ?> selected <?php } ?> required  ><?php echo $datos->nombre_clasificacion;?></option>
	    <?php
	    }
	    ?>  
	</select> 
    </div>

<!--------- Lista desplegable quien_emite_normativa  -------------------------->

    <div class="form-group ">
	<label class="form-control-sm">Emisor/Emitido por</label>
	<select class="form-control mt-2" name="codigo_quien_emite_normativa" required>
	    <option value="">- Seleccione Emisor -</option>
	    <?php
	    $busqeda = $db->query("SELECT * FROM quien_emite_normativa ORDER BY nombre_emisor ASC");
	    $dependencia = $busqeda->fetchAll(PDO::FETCH_OBJ);
	    foreach($dependencia as $datos){
	    ?>
	    <option value="<?php echo $datos->codigo;?>" <?php if($condicion==2 and $datos->nombre_emisor == $nornormaEdit->nombre_emisor){ ?> selected <?php } ?> required  ><?php echo $datos->nombre_emisor;   ?></option>
	    <?php
	    }
	    ?>  
	</select> 
    </div>

<!-------- Elemento numero de la Normativa  ------------------------------------->

    <div class="form-group ">
	<label class="form-control-sm">Número de la Normativa</label>
	<input class="form-control form-control-sm" type="number" max="100000" name="numero_norma" placeholder="Ingrese el número de la norma." value="<?php if($condicion==2){echo $nornormaEdit->numero_norma;} ?>"  required>
    </div>

<!-------- Elemento Año de Expedicion  ------------------------------------------>

    <div class="form-group ">
	<label class="form-control-sm">Año de Expedición</label>
	<input class="form-control form-control-sm" type="number" max="2030" min="1900" name="anio_expedicion" placeholder="Digite el año en que se expidió la norma." value="<?php if($condicion==2){echo $nornormaEdit->anio_expedicion;} ?>" required>
    </div>

<!-------- Elemento mes de Expedición  ----------------------------------------->

    <div class="form-group ">
	<label class="form-control-sm">Mes de Expedición</label>
	<input class="form-control form-control-sm" type="number" max="12" min="1" name="mes_expedicion" placeholder="Digite el mes en que se expidió la norma." value="<?php if($condicion==2){echo $nornormaEdit->mes_expedicion;} ?>" required>
    </div>

<!-------- Elemento dia de Expedición  ----------------------------------------->

    <div class="form-group ">
	<label class="form-control-sm">Día de Expedición</label>
	<input class="form-control form-control-sm" type="number" max="31" min="1" name="dia_expedicion" placeholder="Digite el día en que se expidió la norma." value="<?php if($condicion==2){echo $nornormaEdit->dia_expedicion;} ?>"  required>
    </div>

<!-------- Lista desplegable estado_documento  --------------------------------->

    <div class="form-group ">
	<label class="form-control-sm">Estado</label>
	<select class="form-control mt-2" name="codigo_estado_documento" required>
	    <option value="">- Seleccione Estado -</option>
	    <?php
	    $busqeda = $db->query("SELECT * FROM estado_documento ORDER BY nombre_estado ASC");
	    $dependencia = $busqeda->fetchAll(PDO::FETCH_OBJ);
	    foreach($dependencia as $datos){
	    ?>
	    <option value="<?php echo $datos->codigo;?>" <?php if($condicion==2 and $datos->nombre_estado == $nornormaEdit->nombre_estado){ ?> selected <?php } ?> required  ><?php echo $datos->nombre_estado;   ?></option>
	    <?php
	    }
	    ?>  
	</select> 
    </div>

<!-------- Elemento Asunto  ------------------------------------------------------>

    <div class="form-group ">
        <label class="form-control-sm">Asunto</label>
        <textarea class="form-control form-control-sm" type="text" name="asunto" value="" placeholder="Digite un breve resumen de la norma." required><?php if($condicion==2){echo $nornormaEdit->asunto;} ?></textarea>
    </div>

<!-------- Elemento Palabras Clave   --------------------------------------------->
        
    <div class="form-group ">
        <label class="form-control-sm">Palabras Clave</label>
        <textarea class="form-control form-control-sm" type="text" pattern="[A-Za-z0-9_-]{1,50}"  name="palabra_clave" value="" placeholder="Digite una serie de palabras clave divididas con coma." required><?php if($condicion==2){echo $nornormaEdit->palabras_claves;} ?></textarea>
    </div>

<!-------- Elemento Archivo  ------------------------------------------------------>
 
    <div>
	<label>archivo</label>
	<input class="form-control" type="file" name="archivo_norma"  accept="aplication/PDF, .pdf" <?php if($condicion==1 or $archivoExistente==0){ ?> required <?php } ?>>
    </div>
<!---------------------------------------------------------------------------------->
    <br/>

    <div>
    <?php // Script Ver archivo guardado
	if($condicion==2){
	    $path = "../Archivos/".$nornormaEdit->codigo_gen; //path o ruta donde esta el archivo
	    //$rutaCompleta= $path;
	    if(file_exists($path)){
		$directorioArch = opendir($path);

		while($archivo = readdir($directorioArch)){
		    if(!is_dir($archivo)){
			$archivoExistente=1;
			$rutaCompleta = $archivo;
			?>
			<center> Para actualizar el archivo debe eliminar el archivo existente y subir el archivo actualizado, Archivo existente:<br><?php echo $archivo;?>  </br> </br>
			<a class='btn btn-info' href='verDocumento.php?id=".$id."' title='Ver archivo adjunto' target='_BLANK'><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='16' height='16'>
			<path d='M23.821,11.181v0C22.943,9.261,19.5,3,12,3S1.057,9.261.179,11.181a1.969,1.969,0,0,0,0,1.64C1.057,14.739,4.5,21,12,21s10.943-6.261,11.821-8.181A1.968,1.968,0,0,0,23.821,11.181ZM12,19c-6.307,0-9.25-5.366-10-6.989C2.75,10.366,5.693,5,12,5c6.292,0,9.236,5.343,10,7C21.236,13.657,18.292,19,12,19Z'/>
			<path d='M12,7a5,5,0,1,0,5,5A5.006,5.006,0,0,0,12,7Zm0,8a3,3,0,1,1,3-3A3,3,0,0,1,12,15Z'/></svg></a>
			<a href="del_file.php?ruta=<?php echo $path;?>/<?php echo $archivo; ?>&id=<?php echo $id ;?>" class='btn btn-danger' title='Eliminar archivo adjunto'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16'>
			<path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z'/></svg> </a> </br> </br>
		<?php }
		}
	    }
			
	}    
    ?>
    </div>

<!-------- Elementos Ocultos Y Boton de Guardar/ Actualizar  ------------------------->

    <div class="d-grid gap-2 col-6 mx-auto">
	<input type="hidden" name="oculto" value="<?php if($condicion==1){ echo $contid;}else{ echo $rutaCompleta; } ?>">
	<?php if($condicion==2){ ?>
	<input type="hidden" name="id" value="<?php echo $id; ?>">
	
	<?php } ?>
	<input class="btn btn-sm btn-success" type="submit" <?php if($condicion==1){ echo "value='Guardar' onClick='alertGu()'";}else{echo "value='Actualizar'";}?> name="btn_create_normograma" style="background-color:#037207;" >
    </div>
    </form>
	</br>
	</div>


</div>
</div>


<?php
	include '../Models/footer.php'
?>

<?php $db=null; ?>


</body>

</html>

