<?php
//ob_start();
/* if (strlen(session_id()) < 1) 
  session_start();

if (!isset($_SESSION["nombres"]))
{
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
}
else
{
if ($_SESSION['administrador']==1)
{ */

require('PDF_MC_Table.php');

$pdf = new PDF_MC_Table();
$pdf->AddPage();
$y_axis_initial = 25;

//$pdf->Image("../files/logo/slogan1.png",6,6,40,40);
$pdf->cell(80);
$pdf->Ln(20);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(40,6,'',0,0,'C');
$pdf->Cell(115,6,'REPORTE DE EQUIPOS',2,0,'C'); 
$pdf->Ln(25);

// Colores y fuente de encabezado
$pdf->SetFillColor(34,61,85); 
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial','B',10);

// Cabecera con anchos que suman 190
$pdf->Cell(50,6,utf8_decode('Código interno'),1,0,'C',1); 
$pdf->Cell(50,6,'Marca',1,0,'C',1); 
$pdf->Cell(40,6,'Modelo',1,0,'C',1);
$pdf->Cell(25,6,utf8_decode('Capacidad'),1,0,'C',1);
$pdf->Cell(25,6,'Estado',1,0,'C',1);

$pdf->Ln(10);
$pdf->SetTextColor(0,0,0);

require_once "../modelos/Equipos.php";
$equipos = new Equipos();

$rspta = $equipos->listar();

// Anchos de columnas y alineación
$pdf->SetWidths(array(50,50,40,25,25));
$pdf->SetAligns(array('C','C','C','C','C')); 

while($reg = $rspta->fetch_object())
{  
    $codigo_interno = $reg->codigo_interno;
    $marca = $reg->marca;
    $modelo = $reg->modelo;
    $capacidad = $reg->capacidad;
    $estado_equipo = ($reg->estado_equipo == 1) ? 'Activo' : 'Inactivo';
    
    $pdf->SetFont('Arial','',10);
    $pdf->Row(array(utf8_decode($codigo_interno), utf8_decode($marca), utf8_decode($modelo), $capacidad, $estado_equipo));
}

$pdf->Output();

?>
<?php
/* }
else
{
  echo 'No tiene permiso para visualizar el reporte';
}

}
ob_end_flush(); */
?>
