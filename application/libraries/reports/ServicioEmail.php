<?php
//session_start();

class ServicioEmail extends Cbasereport {

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
        
        $this->SetFont('','B',10);
        $this->SetXY($x,$y);
        $this->MultiCell(20,5,'Servicio:',0,'L',false);
        
        $this->SetFont('','',10);
        $this->SetXY($x+20,$y);
        $this->MultiCell(95,5,$this->getDatos()['servicio']->Servicio,0,'L',false);
        $y=$this->GetY();
        
        $this->SetFont('','B',10);
        $this->SetXY($x,$y);
        $this->MultiCell(20,5,'Cliente:',0,'L',false);
        
        $this->SetFont('','',10);
        $this->SetXY($x+20,$y);
        $this->MultiCell(160,5,$this->getDatos()['servicio']->Client,0,'L',false);
        $y=$this->GetY();
        
        $this->SetFont('','B',10);
        $this->SetXY($x,$y);
        $this->MultiCell(20,5,'Sucursal:',0,'L',false);
        
        $this->SetFont('','',10);
        $this->SetXY($x+20,$y);
        $this->MultiCell(160,5,$this->getDatos()['sucursal']->Nombre,0,'L',false);
        $y=$this->GetY();
        
        $this->SetFont('','B',10);
        $this->SetXY($x,$y);
        $this->MultiCell(160,5,'Dirección:',0,'L',false);
        
        $this->SetFont('','',10);
        $this->SetXY($x+20,$y);
        $this->MultiCell(170,5,$this->getDatos()['servicio']->Direccion,0,'L',false);
        $y=$this->GetY();
        
       
        $this->SetFont('','B',10);
        $this->SetXY($x,$y);
        $this->MultiCell(20,5,'Contacto:',0,'L',false);
        
        $this->SetFont('','',10);
        $this->SetXY($x+20,$y);
        $this->MultiCell(95,5,$this->getDatos()['servicio']->Contacto,0,'L',false);
        $y=$this->GetY();
        
        $this->SetFont('','B',10);
        $this->SetXY($x+120,$y2);
        $this->MultiCell(20,5,'Teléfono :',0,'L',false);
        
        $this->SetFont('','',10);
        $this->SetXY($x+138,$y2);
        $this->MultiCell(70,5,$this->getDatos()['sucursal']->Telefono,0,'L',false);
        $y2=$this->GetY();
        
        $this->SetY(max($y,$y2));

        $x=$this->GetX();   
        $y=$this->GetY()+1;
        $this->SetLineStyle(array('width' =>.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(16,170,192)));
        $this->Line(0, $y, 210, $y);
        
        #DETALLE
        $this->SetY($y+5);
        
        $this->SetLineStyle(array('width' =>.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(16,170,192)));
        
        $x=10;
        $y=$this->GetY();
        $this->SetXY($x,$y);
        $this->SetFont('','B','10');
        $this->MultiCell(100,5,'Fechas y Horas',0,'L');
        
        $y=$this->GetY();
        
        $hh=5;
        $b=1;
        $array=array(['texto'=>'#','w'=>30],['texto'=>'Fecha','w'=>53],['texto'=>'Hora Inicio','w'=>53],['texto'=>'Hora Termino','w'=>53]);
        $this->SetFillColor(101,154,210);
        $this->SetTextColor(255,255,255);
        $this->HeaderDetalleG($array,$x,$y,$hh,$b,true);
        $this->SetTextColor(0,0,0);
       
       $y=$this->GetY();
       
       $FechasServicios = $this->getDatos()['fechaservicio'];
       
      for($i=0;$i<count($FechasServicios);$i++){
            $this->SetFont('','','10');
            $hh=7;
            $b=0;
            $Numero =$i+1;
            $Fecha=dateformatomx($FechasServicios[$i]->FechaInicio);
            $HoraI=substr($FechasServicios[$i]->HoraInicio,0,-3);
            $HoraF=substr($FechasServicios[$i]->HoraFin,0,-3);
            $this->SetFillColor(232,239,241);
            $array=array(['texto'=>$Numero,'w'=>30],['texto'=>$Fecha,'w'=>53],['texto'=>$HoraI,'w'=>53],['texto'=>$HoraF,'w'=>53]);
            $this->DetalleG($array,$x,$y,$hh,$b,true,'');  
            
            $y=$this->GetY();
            $this->SetXY($x,$y);
            $this->MultiCell(140,1,'',0,'L',false);
        }
        
        
         $this->SetFont('','',10);
        $Comentarios=$this->getDatos()['servicio']->Observaciones;
           
        $NLine=$this->getNumLines($Comentarios,190);
        $hcell=$NLine*6;
        $this->checkPageBreak($hcell,$this->GetY()+10,true);
         
        $y=$this->GetY()+5;
        $this->SetFont('','B',12);
        $this->SetXY($x,$y);
        $this->MultiCell(100,5,'Tareas:',0,'L',false);
        
         $this->SetFont('','',10);
        
        $y=$this->GetY();
        $this->SetXY($x,$y);
        $this->Rect($x, $y, 190, $hcell);
        $this->MultiCell(190,5,$Comentarios,0,'L',false);
        
        $this->SetFont('','',10);
        $Materiales=$this->getDatos()['servicio']->Materiales;
        $Firma=$this->getDatos()['firma'];
           
        $NLine=$this->getNumLines($Materiales,190);
        $hcell=$NLine*6;
        $this->checkPageBreak($hcell,$this->GetY()+10,true);
         
        $y=$this->GetY()+5;
        $this->SetFont('','B',12);
        $this->SetXY($x,$y);
        $this->MultiCell(100,5,'Materiales:',0,'L',false);
        
        $this->SetFont('','',10);
        $y=$this->GetY();
        $this->SetXY($x,$y);
        $this->Rect($x, $y, 190, $hcell);
        $this->MultiCell(190,5,$Materiales,0,'L',false);
        
      
    }   

}

?>