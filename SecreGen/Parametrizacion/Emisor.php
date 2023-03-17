<?php


    include '../../Models/conexion.php';
    $sentencia = $db->query("SELECT * FROM quien_emite_normativa ORDER BY nombre_emisor ASC");
    $normativa = $sentencia ->fetchAll(PDO::FETCH_OBJ);

         	//alertas de tipo success
           $alertaDel=1;
           $alertaGu=1;
           $alertaActu=1;
       
         if(isset($_GET['alert'])){
           $alertaGu=2;
         }
       
         if(isset($_GET['alert1'])){
           $alertaDel=2;
         }
       
         if(isset($_GET['alert2'])){
           $alertaActu=2;
         }
    

    $condicion=1;
    $id=1;

    if(isset($_GET['id'])){
	$id= $_GET['id'];
	$condicion=2;

	$busqedaDependencia = $db->prepare("SELECT * FROM quien_emite_normativa WHERE codigo = ? ORDER BY nombre_emisor ASC;");
	$busqedaDependencia->execute([$id]);
	$editEmisor= $busqedaDependencia->fetch(PDO::FETCH_OBJ);
    }

	
    session_start();

    if(!isset($_SESSION['rol'])){
      header('location: ../../Login.php');
    }else{
        if($_SESSION['rol'] != 2){
            header('location: ../../Login.php');
        }
    }

	include './userInfo.php';

?>


<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Emisores</title>
        <link rel="icon" href="../../img/favicon.ico">
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

<body class="d-flex flex-column min-vh-100">

<header>
	<div class="container-border text-center">
		<img src="../../img/LOGO2.png" alt="banner" class="img-fluid">
	</div>

<nav class="navbar navbar-expand-lg navbar-dark  mb-4" style="background-color: #037207">
<div class="container-fluid">
    <a class="navbar-brand" href="../dashboard.php"><?php echo "USUARIO: ".$_SESSION['usuarioname'] ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
		  	Normativa
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">

            <li><a class="dropdown-item" href="../../Login.php">Cargar Norma</a></li>
            
          </ul>
        </li>

		<li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
		  	Parametrización
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
	    <li><a class="dropdown-item" href="Clasificacion.php">Clasificación</a></li>
	    <li><a class="dropdown-item" href="Dependencia.php">Dependencia</a></li>
	    <li><a class="dropdown-item" href="Estado.php">Estado</a></li>
	    <li><a class="dropdown-item" href="Emisor.php">Emisores</a></li>
	  </ul>
        </li>

		<li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
		  	Sesión
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
		  <li><a class="dropdown-item" type="button"data-bs-toggle="modal" data-bs-target="#userInfo" >Cambiar Contraseña</a></li>
		  	<li><a class="dropdown-item" href="../salir.php">Cerrar sesión</a></li>
            <li><a class="dropdown-item" href="../salir2.php">Salir</a></li>

          </ul>
        </li>

        
      </ul>

    </div>
  </div>
</nav>

</header>



<!---- Llamando alertas y cargando Script de las Alertas ------------->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../Models/Alertas.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.min.js"></script>


<?php

	if($alertaGu==2){
		?>
		<script>
		Swal.fire({
			title: 'Guardado',
			text: "Se ha guardado el emisor correctamte",
			icon: 'success',
			confirmButtonColor: '#037207',
			confirmButtonText: 'aceptar'
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
			text: "Se ha eliminado el emisor",
			icon: 'success',
			confirmButtonColor: '#037207',
			confirmButtonText: 'aceptar'
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
			text: "Se ha actualizado el emisor",
			icon: 'success',
			confirmButtonColor: '#037207',
			confirmButtonText: 'aceptar'
		})	
		</script>
	 <?php
	}

?>



<div class="container-fluid" >
    <div class="row">

	<div class="table-responsive col-md-8">
    <h3>Lista de Emisores<button style="background-color: white;" type="button" class="btn btn-light" 
		data-bs-toggle="tooltip" data-bs-placement="top" 
		title="La tabla contiene una lista de los diferentes emisores de las normas institucionales."><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
			<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
			<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
		</svg></button></h3>

	<table class="table">
        <thead class="table-succes table-striped">
        <tr style="background-color:#037207; color:#FFFFFF;">
			<td style="text-align: center;">Emisores</td>
			<td style="text-align: center;">Editar</td>
			<td style="text-align: center;">Eliminar</td>
		</tr>    

		<?php
			foreach($normativa as $datos){
			?>
		<tr>
			<td style="text-align: center;"><?php echo $datos->nombre_emisor; ?></td>
			<td style="text-align: center;"><a class="btn btn-warning" href="Emisor.php?id=<?php echo $datos->codigo; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
			<path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
			<path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
			</svg></a></td>
			<td style="text-align: center;"><a class="btn btn-danger" onClick="alertEmisor(<?php echo $datos->codigo; ?>)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
			<path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
			</svg></a></td>
		</tr> 
			
			<?php
			}
		?>

    


    </table>

	</div>
    <!---Formulario ingreso de datos --->
	<div class="col-md-3 shadow h-110 d-inline-block" style="Height: 230px">
        <hr><h4><?php if($condicion==1){echo "Nuevo Emisor"; }else{echo "Editar Emisor";}?></h4><hr>

    <form class="form-inline" method="POST" action="<?php if($condicion==1){echo "insertarParametro.php";}else{echo "editarParametro.php";} ?>" >

    <div class="form-group ">
	<label class="form-control-sm">Emisor</label>
	<input class="form-control form-control-sm" type="text" name="nombre_emisor" placeholder="Ingrese el emisor que desea incluir." value="<?php if($condicion==2){echo $editEmisor->nombre_emisor;} ?>" required>
    </div>

    <div class="d-grid gap-2 col-6 mx-auto">
	<input type="hidden" name="ocultoEmisor" value="1">
	<input type="hidden" name="id" value="<?php echo $id; ?>">
	<input class="btn btn-sm btn-success mt-3" type="submit" value="<?php if($condicion==1){echo "Guardar";}else{echo "Actualizar";} ?>" name="btn_create_normograma" style="background-color:#037207;" >
    </div>
    </form>
	<br>
	</div>


</div>
</div>


<?php
	include '../../Models/footer.php'
?>
<?php $db=null; ?>

</body>

</html>

