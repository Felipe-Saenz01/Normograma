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

$numeroNorma = $_POST['numero_norma'];
$where="WHERE ";

if(empty($numeroNorma)){
    $consultaVista = $db->prepare("SELECT * FROM v_normativas WHERE visitas >=1 ORDER BY visitas DESC");
    $consultaVista->execute();
    $resultadoVista= $consultaVista->fetchAll(PDO::FETCH_OBJ);
    $db= null;   

}else{
    $consultaVista = $db->prepare("SELECT * FROM v_normativas WHERE numero_norma LIKE '%".$numeroNorma."%' ORDER BY visitas DESC");
    $consultaVista->execute();
    $resultadoVista= $consultaVista->fetchAll(PDO::FETCH_OBJ);
    $db= null;
}

		

}
// Funcion para limitar el texto y que no  se salga de la celda en caso de ser muy largo
function limitarTexto ($texto, $limite, $sufijo){
    if(strlen($texto)>$limite){
        return substr($texto,0,$limite).$sufijo;
    }else{
        return $texto;
    }
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
    $this->Cell(30,10,'INFORME NORMATIVAS MAS BUSCADAS',0,0,'C');
    // Salto de línea
    //$this->Ln(20);
    $this->ln(35);
    //$this->SetY(50);
    $this->SetX(12);
    $this->SetFont('Arial','B',12);
    $this->Cell(167,10,utf8_decode('Lista de Normativas'),1,0,'C',0);
    $this->Cell(20,10,utf8_decode('Vistas'),1,1,'C',0);
    
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



foreach( $resultadoVista as $datos ){
    $texto='Acuerdo '.$datos->nombre_dependencia.' N° '.$datos->numero_norma.' del '.$datos->dia_expedicion.'/'.$datos->mes_expedicion.'/'.$datos->anio_expedicion.'.';
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(167,10,utf8_decode(mb_strtolower(limitarTexto($texto,86,'...'))),1,0,'L',0);
    $pdf->Cell(20,10,$datos->visitas,1,1,'C',0);
}
date_default_timezone_set("America/Bogota");
$nombrepdf ='Informe_Vistas_Normativa_'.date("d_m_Y").'.pdf';

$pdf->Output($nombrepdf,'D');
?>

