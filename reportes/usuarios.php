<?php
ob_start();
if (strlen(session_id()) < 1) 
    session_start();

if (!isset($_SESSION["nombre"])) {
    echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
    if ($_SESSION['administrador']==1) { 

        require('PDF_MC_Table.php');

        // Clase extendida para cabecera y pie de página
        class PDF extends PDF_MC_Table {
            function Header() {
                // Logo
                $this->Image("../public/img/imagenes/logototalcold.png",10,10,40,25);
                // Título
                $this->SetFont('Arial','B',16);
                $this->SetTextColor(3,43,90); // Color corporativo #032b5a
                $this->Cell(50);
                $this->Cell(90,10,'REPORTE DE ADMINISTRADORES',0,1,'C'); 
                $this->Ln(5);
                // Línea separadora
                $this->SetDrawColor(3,43,90);
                $this->SetLineWidth(0.8);
                $this->Line(10,40,200,40);
                $this->Ln(15);
            }

            function Footer() {
                $this->SetY(-15);
                $this->SetFont('Arial','I',8);
                $this->SetTextColor(128);
                $this->Cell(0,10,'Pagina '.$this->PageNo().' / {nb} - '.date('d/m/Y'),0,0,'C');
            }
        }

        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // Cabecera de tabla
        $pdf->SetFillColor(3,43,90); // Azul corporativo
        $pdf->SetTextColor(255,255,255);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('Arial','B',10);

        // Anchos ajustados a 190 mm
        $w = array(40,40,55,25,30); // 40+40+55+25+30 = 190
        $pdf->SetWidths($w);
        $pdf->SetAligns(array('C','C','C','C','C'));

        $header = array('Nombre','Apellido','Correo','Teléfono','Estado');
        for($i=0;$i<count($header);$i++){
            $pdf->Cell($w[$i],8,utf8_decode($header[$i]),1,0,'C',true);
        }
        $pdf->Ln();

        // Conexión a datos
        require_once "../modelos/Usuarios.php";
        $usuario = new Usuario();
        $rspta = $usuario->listar();

        // Contenido con alternancia de colores
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','',10);
        $fill = false;

        while($reg = $rspta->fetch_object()) {  
            $estado = ($reg->estado == 1) ? 'Activo' : 'Inactivo';

            // Alternar color de fila
            if ($fill) {
                $pdf->SetFillColor(220,220,220); // Gris claro
            } else {
                $pdf->SetFillColor(255,255,255); // Blanco
            }

            $pdf->Row(array(
                utf8_decode($reg->nombre),
                utf8_decode($reg->apellido),
                utf8_decode($reg->correo),
                $reg->telefono,
                $estado
            ), $fill);

            $fill = !$fill; // alternar
        }

        $pdf->Output();

    } else {
        echo 'No tiene permiso para visualizar el reporte';
    }
}
ob_end_flush();
?>
