<?php
require('../fpdf181/fpdf.php');

class PDF_MC_Table extends FPDF
{
var $widths;
var $aligns;

function SetWidths($w)
{
	//Set the array of column widths
	$this->widths=$w;
}

// Función para dibujar texto rotado
function RotatedText($x, $y, $txt, $angle) {
	$this->StartTransform();
	$this->Rotate($angle, $x, $y);
	$this->Text($x, $y, $txt);
	$this->StopTransform();
}

// Función para iniciar una transformación
function StartTransform() {
	$this->_out('q');
}

protected $alpha = 1;

// Función para establecer la transparencia
function SetAlpha($alpha) {
	$this->alpha = $alpha;
	if ($this->pdfversion < '1.4') {
		$this->pdfversion = '1.4';
	}
	$this->_out(sprintf('/CA %.3F', $alpha));
	$this->_out(sprintf('/ca %.3F', $alpha));
}

// Sobrescribir el método _out para manejar la transparencia
function _out($s) {
	if ($this->alpha != 1) {
		$s = str_replace('/CA 1', '/CA ' . $this->alpha, $s);
		$s = str_replace('/ca 1', '/ca ' . $this->alpha, $s);
	}
	parent::_out($s);
}

// Función para rotar
function Rotate($angle, $x = -1, $y = -1) {
	if ($x == -1) {
		$x = $this->x;
	}
	if ($y == -1) {
		$y = $this->y;
	}
	$angle = deg2rad($angle);
	$c = cos($angle);
	$s = sin($angle);
	$cx = $x * $this->k;
	$cy = ($this->h - $y) * $this->k;
	$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
}

// Función para detener una transformación
function StopTransform() {
	$this->_out('Q');
}

function SetAligns($a)
{
	//Set the array of column alignments
	$this->aligns=$a;
}

function Row($data, $fill = false)
{
    // Calcular la altura de la fila
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    $this->CheckPageBreak($h);
    // Dibujar las celdas de la fila
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        $x=$this->GetX();
        $y=$this->GetY();
        // Rectángulo con relleno si $fill es true
        $this->Rect($x,$y,$w,$h,$fill ? 'DF' : 'D');
        $this->MultiCell($w,5,$data[$i],0,$a);
        $this->SetXY($x+$w,$y);
    }
    $this->Ln($h);
}



function CheckPageBreak($h)
{
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger)
		$this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
	//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
}
}
?>
