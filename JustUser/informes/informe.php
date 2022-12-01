<?php

include '../../Models/conexion.php';

    session_start();

    if(!isset($_SESSION['rol'])){
        header('location: ../Login.php');
    }else{
        if($_SESSION['rol'] != 1){
            header('location: ../Login.php');
        }
    }


if(isset($_POST['ocultoinforme'])){


$dependencia = $_POST['dependencia_normativa'];
$clasificacion = $_POST['clasificacion_normograma'];
$anio = $_POST['anio_expedicion'];
$estado = $_POST['estado_documento'];
$emisor = $_POST['quien_emite_normativa'];
$where="WHERE ";

if(empty($dependencia) && empty($clasificacion) && empty($anio) && empty($estado) && empty($emisor)){
    
    $where="";

}else{

    $where="WHERE codigo_dependencia_normativa LIKE '%".$dependencia."%' AND codigo_clasificacion_normograma LIKE '%".$clasificacion."%' AND anio_expedicion LIKE '%".$anio."%' AND estado LIKE '%".$estado."%' AND codigo_quien_emite_normativa LIKE '%".$emisor."%'";
}

		
$consultaVista = $db->prepare("SELECT * FROM v_normativas $where");
$consultaVista->execute();
$resultadoVista= $consultaVista->fetchAll(PDO::FETCH_OBJ);
$db= null;
}




require('fpdf/fpdf.php');

class PDF extends FPDF{

// Cabecera de página
function Header()
{
    // Logo
    $this->Image('../img/bannerinforme.png',1,0,209);
    // Arial bold 15
    $this->SetFont('Arial','B',12);
    // Movernos a la derecha
    //$this->ln(10);
    $this->setXY(95,12);
    // Título
    $this->Cell(30,10,'INFORME NORMAS INSTITUCIONALES',0,0,'C');
    // Salto de línea
    //$this->Ln(20);
    $this->ln(35);
    //$this->SetY(50);
    $this->SetX(12);
    $this->SetFont('Arial','B',12);
    $this->Cell(187,10,utf8_decode('Lista de Normativas'),1,1,'C',0);
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-35);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página
    $this->Image('../img/PiedePagInforme.png',0,265,209);
    $this->Cell(0,10,utf8_decode('Página '.$this->PageNo().'/{nb}'),0,0,'C');
}
}

// Creación del objeto de la clase heredada
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(12,10,12);
$pdf->SetAutoPageBreak(true,35);
$pdf->SetX(12);

if(count($resultadoVista)==0){
    $pdf->Cell(187,30,utf8_decode('NO SE ENCONTRARON RESULTADOS PARA EL INFORME CON LOS PARÁMETROS BUSCADOS'),0,1,'C',0);
}else{
    foreach( $resultadoVista as $datos ){
	$pdf->SetFont('Arial','',10);
	$texto=$datos->nombre_dependencia.' N° '.$datos->numero_norma.' del '.$datos->dia_expedicion.'/'.$datos->mes_expedicion.'/'.$datos->anio_expedicion.'. '.$datos->asunto.' '.$datos->nombre_estado.'.';
	$pdf->MultiCell(187,10,utf8_decode("Acuerdo ".mb_strtolower($texto)),1,'J',0);

    }
}



date_default_timezone_set("America/Bogota");
$nombrepdf ='Informe_Normativas_'.date("d_m_Y").'.pdf';

$pdf->Output($nombrepdf,'D');
?>

