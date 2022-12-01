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

<body >





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
	if(isset($_GET['buscar']) && isset($_GET['buscadependencia']) && isset($_GET['buscaclasificasion']) && isset($_GET['buscaestado']) && isset($_GET['buscaaniodocumento']) && isset($_GET['orden']) ){
	    if(empty($_GET['buscar']) && empty($_GET['buscadependencia']) && empty($_GET['buscaclasificasion']) && empty($_GET['buscaestado']) && empty($_GET['buscaaniodocumento']) && empty($_GET['orden'])){
	    header("Location: index.php");
	    }
	}
	

if (!isset($_GET['buscar'])){$_GET['buscar'] = '';}
if (!isset($_GET['buscaclasificasion'])){$_GET['buscaclasificasion'] = '';}
if (!isset($_GET['buscapalabraclave'])){$_GET['buscapalabraclave'] = '';}
if (!isset($_GET['buscaaniodocumento'])){$_GET['buscaaniodocumento'] = '';}
if (!isset($_GET['buscaasunto'])){$_GET['buscaasunto'] = '';}
if (!isset($_GET['buscaestado'])){$_GET['buscaestado'] = '';}
if (!isset($_GET['buscadependencia'])){$_GET['buscadependencia'] = '';}
if (!isset($_GET['buscaquienemite'])){$_GET['buscaquienemite'] = '';}
if (!isset($_GET["orden"])){$_GET["orden"] = '';}


?>






<div class="container mt-5">
    <div class="col-12">
	<div class="row">
	    <div class="col-12 grid-margin">
		<div class="card">
		    <div class="card-body">

		    <h4 class="card-title">Buscador</h4>
		    <form id="form2" name="form2" method="GET" action="index.php">
		    <div class="col-12 row">
			<div class="mb-3">
			    <input type="text" class="form-control" id="buscar" name="buscar" placeholder="Ingrese las palabras que desea buscar, separándolas Con una coma." value="<?php echo $_GET["buscar"] ?>" >
			</div>

		    <h4 class="card-title">Filtro de búsqueda</h4>  
		    <div class="col-11">
			<table class="table">
			    <thead>
				<tr class="filters">
				    <th>
					<select id="assigned-tutor-filter" id="buscadependencia" name="buscadependencia" class="form-control mt-2" style="border: #bababa 1px solid; color:#000000;" >
					    
					    <option value="">- Dependencia del documento-</option>
					    
					    <?php
					    $consulta = $db->query("SELECT * FROM dependencia_normativa ");
					    $ejecutar = $consulta->fetchAll(PDO::FETCH_ASSOC);
					    ?>
					    <?php foreach ($ejecutar as $opciones): ?> 
					    <option value="<?php  echo $opciones["nombre_dependencia"] ?>"><?php  echo $opciones["nombre_dependencia"] ?></option>
					    <?php endforeach ?>
					    </select>
				    </th>

				    <th>
					<select id="assigned-tutor-filter" id="buscaclasificasion" name="buscaclasificasion" class="form-control mt-2" style="border: #bababa 1px solid; color:#000000;" >
					    
					    <option value="">- Tipo de documento -</option>
					    <?php 
					    $consulta = $db->query("SELECT * FROM clasificacion_normativa ");
					    $ejecutar = $consulta->fetchAll(PDO::FETCH_ASSOC);
					    ?>
					    <?php foreach ($ejecutar as $opciones): ?> 
					    <option value="<?php  echo $opciones["nombre_clasificacion"] ?>"><?php  echo $opciones["nombre_clasificacion"] ?></option>
					    <?php endforeach ?>
					</select>
				    </th>

				    <th>
					<select id="assigned-tutor-filter" id="buscaestado" name="buscaestado" class="form-control mt-2" style="border: #bababa 1px solid; color:#000000;" >
										<option value="">- Estado del documento -</option>
					<?php 
					$consulta = $db->query("SELECT * FROM estado_documento ");
					$ejecutar = $consulta->fetchAll(PDO::FETCH_ASSOC);
					?>
					<?php foreach ($ejecutar as $opciones): ?> 
					<option value="<?php  echo $opciones["nombre_estado"] ?>"><?php  echo $opciones["nombre_estado"] ?></option>
					<?php endforeach ?>
					</select>
				    </th>

				    <th>
					<input class="form-control form-control" type="number" max="100000" min="0" name="buscaaniodocumento" <?PHP if ($_GET["buscaaniodocumento"] != ''){ echo "value='$_GET[buscaaniodocumento]'"; }?> placeholder="Año expedición " >
				    </th>
				</tr>
			    </thead>
			</table>
		    </div>

		    <h4 class="card-title">Ordenar por:</h4>  
		    <div class="col-11">
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
		    </div>   
		    </div>
        <?php 
        /*FILTRO de busqueda////////////////////////////////////////////*/
        if ($_GET['buscar'] == ''){ $_GET['buscar'] = ' ';}
        $aKeyword = explode(" ", $_GET['buscar']);
        
       
					if ($_GET["buscar"] == '' AND $_GET['buscaclasificasion'] == '' AND $_GET['buscapalabraclave'] == '' AND $_GET['buscaaniodocumento'] == '' AND $_GET['buscaasunto'] == '' AND $_GET['buscaestado'] == '' AND $_GET['buscadependencia'] == '' AND $_GET['buscaquienemite'] == '' ){ 
                $query ="SELECT Nom.numero_norma, Nom.anio_expedicion, Nom.codigo_gen, Nom.mes_expedicion, Nom.dia_expedicion, Nom.asunto, Nom.fecha_cargue,
                                Clasno.nombre_clasificacion, Estdoc.nombre_estado, Emis.nombre_emisor, Depen.nombre_dependencia 
                                FROM normativa Nom
                                INNER JOIN clasificacion_normativa Clasno ON Nom.codigo_clasificacion_normograma = Clasno.codigo
                                INNER JOIN estado_documento Estdoc ON Nom.estado = Estdoc.codigo
                                INNER JOIN quien_emite_normativa Emis ON Nom.codigo_quien_emite_normativa = Emis.codigo
                                INNER JOIN dependencia_normativa Depen ON Nom.codigo_dependencia_normativa = Depen.codigo ";
        }else{


                $query ="SELECT Nom.numero_norma, Nom.anio_expedicion, Nom.codigo_gen, Nom.mes_expedicion, Nom.dia_expedicion, Nom.asunto, Nom.fecha_cargue,
                Clasno.nombre_clasificacion, Estdoc.nombre_estado, NomEmi.nombre_emisor, Depen.nombre_dependencia 
                FROM normativa Nom
                INNER JOIN clasificacion_normativa Clasno ON Nom.codigo_clasificacion_normograma = Clasno.codigo
                INNER JOIN estado_documento Estdoc ON Nom.estado = Estdoc.codigo
                INNER JOIN quien_emite_normativa NomEmi ON Nom.codigo_quien_emite_normativa = NomEmi.codigo
                INNER JOIN dependencia_normativa Depen ON Nom.codigo_dependencia_normativa = Depen.codigo ";

                //$query ="SELECT * FROM normativa ";////

        if ($_GET["buscar"] != '' ){ 
                $query .= "WHERE (nombre_clasificacion LIKE LOWER('%".$aKeyword[0]."%') OR asunto LIKE LOWER('%".$aKeyword[0]."%') OR anio_expedicion LIKE LOWER('%".$aKeyword[0]."%') OR palabras_claves LIKE LOWER('%".$aKeyword[0]."%') OR nombre_emisor LIKE LOWER('%".$aKeyword[0]."%') OR nombre_dependencia LIKE LOWER('%".$aKeyword[0]."%')) ";
        
        for($i = 1; $i < count($aKeyword); $i++) {
           if(!empty($aKeyword[$i])) {
               $query .= " OR nombre_clasificacion LIKE '%" . $aKeyword[$i] . "%' OR asunto LIKE '%" . $aKeyword[$i] . "%' OR anio_expedicion LIKE '%" . $aKeyword[$i] . "%' OR palabras_claves LIKE '%" . $aKeyword[$i] . "%' OR nombre_emisor LIKE '%" . $aKeyword[$i] . "%' OR nombre_dependencia LIKE '%" . $aKeyword[$i] . "%'";
           }
         }

        }

        if ($_GET["buscadependencia"] != '' ){
                $query .= " AND nombre_dependencia = '".$_GET['buscadependencia']."' ";
         }

         if ($_GET["buscaclasificasion"] != '' ){
                $query .= " AND nombre_clasificacion = '".$_GET['buscaclasificasion']."' ";
         }
               

        if ($_GET["buscaestado"] != '' ){
            $query .= " AND nombre_estado = '".$_GET['buscaestado']."' ";
        }

        if ($_GET["buscaaniodocumento"] != '' ){
            $query .= " AND anio_expedicion = '".$_GET['buscaaniodocumento']."' ";
        }


        if ($_GET["orden"] == '2' ){
                $query .= " ORDER BY nombre_dependencia ASC ";
         }

         if ($_GET["orden"] == '3' ){
                $query .= " ORDER BY nombre_clasificacion ASC ";
         }

         if ($_GET["orden"] == '4' ){
                $query .= " ORDER BY anio_expedicion ASC ";
         }

         if ($_GET["orden"] == '5' ){
            $query .= " ORDER BY nombre_estado ASC ";
     }
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
	$sentencia = $db->query($newquery);
	$normativa = $sentencia->fetchAll(PDO::FETCH_ASSOC);

	$sentenciaTotal = $db->query($query);
        $totalNormas = $sentenciaTotal->rowCount();

        ?>
        <p style="font-weight: bold; color:#037207;"><i class="mdi mdi-file-document"></i> <?php echo $totalNormas; ?> Resultados encontrados</p>
</form>


<div class="table-ressnsive">
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
			    <path d="M12,7a5,5,0,1,0,5,5A5.006,5.006,0,0,0,12,7Zm0,8a3,3,0,1,1,3-3A3,3,0,0,1,12,15Z"/></svg></a></td>
                        </tr>
               
               <?php } ?>
                </tbody>
        </table>






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
<center>
    <nav aria-label="Paginación Normativas">
        <ul class="pagination">
        <?php
        $totalPaginacion = ceil($totalNormas/$limit);
        for($i=1;$i<=$totalPaginacion; $i++){
        ?>
	    <li class="page-item" aria-current="page">
		<a class="page-link" href="index.php?pag=<?php echo $i; ?>"><?php echo $i ?></a>
	    </li>
	<?php } ?>
	</ul>
    </nav>
</center>
<?php } 
    }
?>

<?php if($totalNormas==0){ ?>
	    <script>
		Swal.fire({
			title: 'Resultado no encontrado!',
			text: "La norma que desea buscar no existe o no se ha registrado todavía.",
			icon: 'info',
			confirmButtonColor: '#037207',
			confirmButtonText: 'aceptar'
		})
		</script>
	    <?php } ?>

    	    </div>
		</div>
	    </div>
	</div>
    </div>
</div>







	
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
