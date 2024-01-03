<?php
// require_once('../TCPDF-master/tcpdf.php');
class RptEstadoFinanciero2022 extends Cbasereport{
    function Header()
    {
        $ImgDefault = 'assets/FinancieroPdf/header.png';

        $y = 0;
        $x=$this->GetX();

      
        $this->Image($ImgDefault,0, $y,300,20);
        $y=$this->GetY()+10;
        $y2=$this->GetY()+10;

    }

    public function Footer()  
    {
        $ImgDefault = 'assets/FinancieroPdf/fooder.png';
        
        $y = 190;
        $x = 50;

        $this->SetXY($x,$y);
        $this->Image($ImgDefault,0, $y,120,20);
    } 

   
}
?>