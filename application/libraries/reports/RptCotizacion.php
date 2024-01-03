<?php
//session_start();

class RptCotizacion extends Cbasereport {

    #*****************LEER********************
    #*****************LEER********************
    #EN funciones helper se crea una tabla con header recibe parametros de (header,styleheader,detalle);
    
   
    function Header()
    {
        $this->HeaderG();
    }

    public function Footer()  
    {
       $this->FooterG('Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(),'Desprosoft 2018 ® ');
    } 

    function contenido()
    {   
        $this->EspacioHeaderG();
        
        $y=$this->GetY();
        $y2=$this->GetY();
        $x=$this->GetX();
        
        $this->SetFont('','B',11);
        $this->RoundedRect($x,$y,4,4,2,'0','F','',array(120,165,242));
        $this->SetXY($x+4,$y);
        $this->MultiCell(22,5,'Cliente',0,'L',false);
        
        $y=$this->GetY()+3;
        $this->SetFont('','',11);
        
        $this->SetXY($x,$y);
        $this->MultiCell(22,5,'Cliente:',0,'L',false);
        $this->SetXY($x+20,$y);
        $this->MultiCell(170,5,$this->getDatos()['clientes']->Nombre,'B','L',false);
        
        $y=$this->GetY()+1;
        $this->SetXY($x,$y);
        $this->MultiCell(22,5,'Sucursal:',0,'L',false);
        $this->SetXY($x+20,$y);
        $this->MultiCell(170,5,$this->getDatos()['clientesucursal']->Nombre,'B','L',false);
        
        
        $y=$this->GetY()+1;
        $this->SetXY($x,$y);
        $this->MultiCell(22,5,'Dirección:',0,'L',false);
        $this->SetXY($x+20,$y);
        $this->MultiCell(170,5,$this->getDatos()['clientesucursal']->Direccion,'B','L',false);
        
        $y=$this->GetY()+1;
        $y2=$this->GetY()+1;
        $this->SetXY($x,$y);
        $this->MultiCell(22,5,'Teléfono:',0,'L',false);
        $this->SetXY($x+20,$y);
        $this->MultiCell(70,5,$this->getDatos()['clientesucursal']->Telefono,'B','L',false);
        $y=$this->GetY()+1;
        
        $this->SetXY($x+88,$y2);
        $this->MultiCell(22,5,'Correo:',0,'R',false);
        $this->SetXY($x+112,$y2);
        $this->MultiCell(78,5,$this->getDatos()['clientesucursal']->Correo,'B','L',false);
        $y2=$this->GetY()+1;
        
        $this->SetY(max($y,$y2));
        
        $y=$this->GetY()+4;
        
        $this->SetFont('','B',11);
        $this->RoundedRect($x,$y,4,4,2,'0','F','',array(120,165,242));
        $this->SetXY($x+4,$y);
        $this->MultiCell(100,5,'Datos del sitio:',0,'L',false);
        
        $y=$this->GetY()+2;
        $y2=$this->GetY()+2;
        $y3=$this->GetY()+2;
        
        $this->SetFont('','',11);
        
        $this->SetXY($x,$y);
        $this->MultiCell(22,5,'Distancia:',0,'L',false);
        $this->SetXY($x+20,$y);
        $this->MultiCell(35,5,$this->getDatos()['cotizacion']->distancia.' km','B','L',false);
        $y=$this->GetY()+1;
        
        $this->SetXY($x+55,$y2);
        $this->MultiCell(45,5,'Velocidad promedio:',0,'L',false);
        $this->SetXY($x+100,$y2);
        $this->MultiCell(35,5,$this->getDatos()['cotizacion']->velocidad.'  km/h','B','L',false);
        $y2=$this->GetY()+1;
        
        $this->SetXY($x+135,$y3);
        $this->MultiCell(25,5,'Costo Km:',0,'L',false);
        $this->SetXY($x+155,$y3);
        $this->MultiCell(35,5,'$'.number_format($this->getDatos()['cotizacion']->costoKm,2,'.',''),'B','L',false);
        $y3=$this->GetY()+1;
        
        $this->SetY(max($y,$y2,$y3));
        
        $y=$this->GetY()+4;
        
        $this->SetFont('','B',11);
        $this->RoundedRect($x,$y,4,4,2,'0','F','',array(120,165,242));
        $this->SetXY($x+4,$y);
        $this->MultiCell(100,5,'Datos adicionales:',0,'L',false);
        
        $this->SetY($this->GetY()+2);
        $this->SetFont('','',10);
        $y=$this->GetY();
        $y2=$this->GetY();
        $y3=$this->GetY();
        $y4=$this->GetY();
        
        $this->SetXY($x,$y);
        $this->MultiCell(25,5,'Gross profit:',0,'L',false);
        $this->SetXY($x+20,$y);
        $this->MultiCell(15,5,$this->getDatos()['cotizacion']->GrossProfit.' %','B','L',false);
        $y=$this->GetY()+1;
        
        $this->SetXY($x+35,$y2);
        $this->MultiCell(40,5,'Factor Hora Extra:',0,'L',false);
        $this->SetXY($x+65,$y2);
        $this->MultiCell(15,5,$this->getDatos()['cotizacion']->factorHoraExtra.' %','B','L',false);
        $y2=$this->GetY()+1;
        
        $this->SetXY($x+80,$y3);
        $this->MultiCell(30,5,'Utilidad Aprox:',0,'L',false);
        $this->SetXY($x+105,$y3);
        $this->MultiCell(30,5,'$'.number_format($this->getDatos()['cotizacion']->utilidad,2,'.',''),'B','L',false);
        $y3=$this->GetY()+1;
        
        $this->SetXY($x+135,$y4);
        $this->MultiCell(35,5,'Gasto Operaciones:',0,'L',false);
        $this->SetXY($x+170,$y4);
        $this->MultiCell(20,5,$this->getDatos()['cotizacion']->gastoOperaciones.' %','B','L',false);
        $y4=$this->GetY()+1;


        //aqui vamos a poner lo nuevo//
        $this->SetY(max($y,$y2,$y3));
        
        $this->SetY($this->GetY()+2);
        $this->SetFont('','',10);
        $y=$this->GetY();
        $y2=$this->GetY();
        $y3=$this->GetY();
        $y4=$this->GetY();
        
        $this->SetFont('','B',11);
        $this->RoundedRect($x,$y,4,4,2,'0','F','',array(120,165,242));
        $this->SetXY($x+4,$y);
        $this->MultiCell(100,5,'Totales',0,'L',false);
        $y=$this->GetY()+4;
        
        $this->SetFont('','',11);
        $this->SetXY($x,$y);
        $this->MultiCell(75,5,'Mano de Obra:',0,'L',false);
        $this->SetXY($x+75,$y);
        $this->MultiCell(80,5,'$'.number_format($this->getDatos()['cotizacion']->totalManoDeObra,2,'.',','),0,'L',false);
        $y=$this->GetY();
      
        
        $this->SetXY($x,$y);
        $this->MultiCell(75,5,'Costo Desplazamiento:',0,'L',false);
        
        $this->SetXY($x+75,$y);
        $this->MultiCell(80,5,'$'.number_format($this->getDatos()['cotizacion']->TotalCostoKm,2,'.',','),0,'L',false);
        $y=$this->GetY();
     
        
        $this->SetXY($x,$y);
        $this->MultiCell(75,5,'Miscelaneos:',0,'L',false);
       
        
        $this->SetXY($x+75,$y);
        $this->MultiCell(80,5,'$'.number_format($this->getDatos()['cotizacion']->totalMiscelaneos,2,'.',','),0,'L',false);
         $y=$this->GetY();
        
        $this->SetXY($x,$y);
        $this->MultiCell(75,5,'Materiales:',0,'L',false);
        $this->SetXY($x+75,$y);
        $this->MultiCell(80,5,'$'.number_format($this->getDatos()['cotizacion']->totalMateriales,2,'.',','),0,'L',false);
         $y=$this->GetY();
        
        $this->SetXY($x,$y);
        $this->MultiCell(75,5,'Costo Mano de Obra y Desplazamiento:',0,'L',false);
      
        
        $this->SetXY($x+75,$y);
        $this->MultiCell(90,5,'$'.number_format($this->getDatos()['cotizacion']->CostoManoObraD,2,'.',','),0,'L',false);
        $y=$this->GetY();
        
        
        $this->SetXY($x,$y);
        $this->MultiCell(75,5,'Costo Burden:',0,'L',false);
        
        
        $this->SetXY($x+75,$y);
        $this->MultiCell(90,5,'$'.number_format($this->getDatos()['cotizacion']->CostoBurden,2,'.',','),0,'L',false);
        $y=$this->GetY();
        
        
        $this->SetXY($x,$y);
        $this->MultiCell(75,5,'Garantía:',0,'L',false);
        
        $this->SetXY($x+75,$y);
        $this->MultiCell(90,5,'$'.number_format($this->getDatos()['TotalGarantia'],2,'.',','),0,'L',false);
        $y=$this->GetY();
        
        
        
        $this->SetXY($x,$y);
        $this->MultiCell(75,5,'Costo Total:',0,'L',false);
        $this->SetXY($x+75,$y);
        $this->MultiCell(90,5,'$'.number_format($this->getDatos()['cotizacion']->totalGlobal,2,'.',','),0,'L',false);
        $y=$this->GetY();
        
        $this->SetXY($x,$y);
        $this->MultiCell(75,5,'Valor venta:',0,'L',false);
        $this->SetXY($x+75,$y);
        $this->MultiCell(90,5,'$'.number_format($this->getDatos()['cotizacion']->ValorVenta,2,'.',','),0,'L',false);

        $y=$this->GetY();
        //lo nuevo fin //
        
        
        $this->SetY(max($y,$y2,$y3,$y4));
        $y=$this->GetY()+4;
        
        $this->SetFont('','B',11);
        $this->RoundedRect($x,$y,4,4,2,'0','F','',array(120,165,242));
        $this->SetXY($x+4,$y);
        $this->MultiCell(100,5,'Miscelaneos',0,'L',false);
        
        $y=$this->GetY()+4;
        
        $array=array(['texto'=>'','w'=>35],['texto'=>'Cantidad','w'=>35,'a'=>'C'],['texto'=>'Valor','w'=>35,'a'=>'C']);
        $this->SetFillColor(82,134,223);
        $this->SetTextColor(255,255,255);
        $hh=5;$b=0;$f=true;
        $this->HeaderDetalleG($array,$x,$y,$hh,$b,$f);
        $rowmiselaneo=$this->getDatos()['miselaneo'];
        for($i=0;$i<count($rowmiselaneo);$i++){
            $this->SetFont('','',9);
            $this->SetTextColor(0,0,0);
            $Concepto=$rowmiselaneo[$i]->concepto;
            $Cantidad=$rowmiselaneo[$i]->cantidad;
            $valor =($rowmiselaneo[$i]->valor <=0) ? '$0' :'$'.number_format($rowmiselaneo[$i]->valor,2,'.',''); // $r is
            
            //datos
            $array=array(['texto'=>$Concepto,'w'=>35],['texto'=>$Cantidad,'w'=>35,'a'=>'C'],['texto'=>$valor,'w'=>35,'a'=>'C']);
          
            $this->DetalleG($array,$x,$this->GetY(),$hh,'1','','');
        }
        
        
        $y=$this->GetY()+4;
        
        $this->SetFont('','B',11);
        $this->RoundedRect($x,$y,4,4,2,'0','F','',array(120,165,242));
        $this->SetXY($x+4,$y);
        $this->MultiCell(100,5,'Mano de obra',0,'L',false);
        
        $this->SetFillColor(82,134,223);
        $this->SetTextColor(255,255,255);
        $this->SetFont('','B',10);
        $y=$this->GetY()+4;
        $this->SetXY($x,$y);
        //$this->MultiCell(30,8,'Categoría',0,'L',true);
        //$this->MultiCell(35,15,'Categoría',0,'L',true);
        $this->MultiCell(70, 13, 'Categoría', 0, 'C', 1, 0, '', '', true, 0, false, true, 13, 'M');

        $this->SetXY($x+70,$y);
        $this->MultiCell(35, 13, 'Costo M.O', 0, 'C', 1, 0, '', '', true, 0, false, true, 13, 'M');
        
        $this->SetXY($x+105,$y);
        $this->MultiCell(50,5,'Horas',0,'C',true,'','','',true,'','','',8,'M',true);
        $this->SetXY($x+105,$y+5);
        $this->MultiCell(25,5,'Normal',0,'C',true,'','','',true,'','','',8,'M',true);
        $this->SetXY($x+130,$y+5);
        $this->MultiCell(25,5,'Extra',0,'C',true,'','','',true,'','','',8,'M',true);
        
        $this->SetXY($x+155,$y);
        $this->MultiCell(35, 13, 'Total', 0, 'C', 1, 0, '', '', true, 0, false, true, 13, 'M');
        
        $this->SetY($this->GetY()+13);
        $hh=5;
        $rowmanoobra=$this->getDatos()['manoobra'];
        for($i=0;$i<count($rowmanoobra);$i++){
            $this->SetFont('','',9);
            $this->SetTextColor(0,0,0);
            $Categoria=$rowmanoobra[$i]->categoria;
            $CostoMO =($rowmanoobra[$i]->costoMO == '') ? '$0' : '$'.number_format($rowmanoobra[$i]->costoMO,2,'.',''); // $r is set to 'Yes'               
            $Normal=$rowmanoobra[$i]->horaNormal;
            $Extra=$rowmanoobra[$i]->horaExtra;
            $Total =($rowmanoobra[$i]->totalIndividual <=0) ? '$0' :'$'.number_format($rowmanoobra[$i]->totalIndividual,2,'.',''); // $r is set to 'Yes'
            
            
            
            //datos
            $array=array(['texto'=>$Categoria,'w'=>70],['texto'=>$CostoMO,'w'=>35,'a'=>'C'],['texto'=>$Normal,'w'=>25,'a'=>'C'],['texto'=>$Extra,'w'=>25,'a'=>'C'],['texto'=>$Total,'w'=>35,'a'=>'C']);
          
            $this->DetalleG($array,$x,$this->GetY(),$hh,'1','','');
        }
        
        $y=$this->GetY()+4;
        
        $this->SetFont('','B',11);
        $this->RoundedRect($x,$y,4,4,2,'0','F','',array(120,165,242));
        $this->SetXY($x+4,$y);
        $this->MultiCell(100,5,'Materiales',0,'L',false);
        
        $y=$this->GetY()+4;
        
        $array=array(['texto'=>'Descripción','w'=>75],['texto'=>'Cantidad','w'=>35,'a'=>'C'],['texto'=>'Costo unitario','w'=>40,'a'=>'C'],['texto'=>'Total','w'=>40,'a'=>'C']);
        $this->SetFillColor(82,134,223);
        $this->SetTextColor(255,255,255);
        $hh=5;$b=0;$f=true;
        $this->HeaderDetalleG($array,$x,$y,$hh,$b,$f);
        $rowmateriales=$this->getDatos()['materiales'];
        for($i=0;$i<count($rowmateriales);$i++){
            $this->SetFont('','',9);
            $this->SetTextColor(0,0,0);
            $Descripcion=$rowmateriales[$i]->NombreMat;
            $Cantidad=$rowmateriales[$i]->cantidad;
            $CostoUni='$'.number_format($rowmateriales[$i]->costoUnitario,2,'.','');
            $total='$'.number_format($rowmateriales[$i]->totalIndividual,2,'.','');
            
            //datos
        $array=array(['texto'=>$Descripcion,'w'=>75],['texto'=>$Cantidad,'w'=>35,'a'=>'C'],['texto'=>$CostoUni,'w'=>40,'a'=>'C'],['texto'=>$total,'w'=>40,'a'=>'C']);
          
            $this->DetalleG($array,$x,$this->GetY(),$hh,'1','','');
        }
        
        $y=$this->SetY($this->GetY()+5);
        $this->checkPageBreak(0,$y,true);
        
        //$y=$this->GetY();
        
        // $this->SetFont('','B',11);
        // $this->RoundedRect($x,$y,4,4,2,'0','F','',array(120,165,242));
        // $this->SetXY($x+4,$y);
        // $this->MultiCell(100,5,'Totales',0,'L',false);
        // $y=$this->GetY()+4;
        
        // $this->SetFont('','',11);
        // $this->SetXY($x,$y);
        // $this->MultiCell(75,5,'Mano de Obra:',0,'L',false);
        // $this->SetXY($x+75,$y);
        // $this->MultiCell(80,5,'$'.$this->getDatos()['cotizacion']->totalManoDeObra,0,'L',false);
        // $y=$this->GetY();
      
        
        // $this->SetXY($x,$y);
        // $this->MultiCell(75,5,'Costo Desplazamiento:',0,'L',false);
        
        // $this->SetXY($x+75,$y);
        // $this->MultiCell(80,5,'$'.$this->getDatos()['cotizacion']->TotalCostoKm,0,'L',false);
        // $y=$this->GetY();
     
        
        // $this->SetXY($x,$y);
        // $this->MultiCell(75,5,'Miscelaneos:',0,'L',false);
       
        
        // $this->SetXY($x+75,$y);
        // $this->MultiCell(80,5,'$'.$this->getDatos()['cotizacion']->totalMiscelaneos,0,'L',false);
        //  $y=$this->GetY();
        
        // $this->SetXY($x,$y);
        // $this->MultiCell(75,5,'Materiales:',0,'L',false);
        // $this->SetXY($x+75,$y);
        // $this->MultiCell(80,5,'$'.$this->getDatos()['cotizacion']->totalMateriales,0,'L',false);
        //  $y=$this->GetY();
        
        // $this->SetXY($x,$y);
        // $this->MultiCell(75,5,'Costo Mano de Obra y Desplazamiento:',0,'L',false);
      
        
        // $this->SetXY($x+75,$y);
        // $this->MultiCell(90,5,'$'.$this->getDatos()['cotizacion']->CostoManoObraD,0,'L',false);
        // $y=$this->GetY();
        
        
        // $this->SetXY($x,$y);
        // $this->MultiCell(75,5,'Costo Burden:',0,'L',false);
        
        
        // $this->SetXY($x+75,$y);
        // $this->MultiCell(90,5,'$'.$this->getDatos()['cotizacion']->CostoBurden,0,'L',false);
        // $y=$this->GetY();
        
        
        // $this->SetXY($x,$y);
        // $this->MultiCell(75,5,'Garantía:',0,'L',false);
        
        // $this->SetXY($x+75,$y);
        // $this->MultiCell(90,5,'$'.$this->getDatos()['TotalGarantia'],0,'L',false);
        // $y=$this->GetY();
        
        
        
        // $this->SetXY($x,$y);
        // $this->MultiCell(75,5,'Costo Total:',0,'L',false);
        // $this->SetXY($x+75,$y);
        // $this->MultiCell(90,5,'$'.$this->getDatos()['cotizacion']->totalGlobal,0,'L',false);
        // $y=$this->GetY();
        
        // $this->SetXY($x,$y);
        // $this->MultiCell(75,5,'Valor venta:',0,'L',false);
        // $this->SetXY($x+75,$y);
        // $this->MultiCell(90,5,'$'.$this->getDatos()['cotizacion']->ValorVenta,0,'L',false);
    
        
    } 
}

?>
