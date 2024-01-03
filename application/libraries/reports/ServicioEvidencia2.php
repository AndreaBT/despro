<?php
//session_start();

class ServicioEvidencia2 extends Cbasereport {


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
        $x=$this->GetX();
        
        $y=$this->GetY()+1;
        $this->SetLineStyle(array('width' =>.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(191, 190, 190)));
        $this->Line(0, $y, 210, $y);
        
        #DETALLE
        $this->SetY($y+5);
        $this->SetLineStyle(array('width' =>.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(191, 190, 190)));
        $contador=0;
        
        $row = $this->getDatos()['row'];
        $row2 = $this->getDatos()['row2'];
        $IdEmpresa = $this->getDatos()['IdEmpresa'];
        $IdSucursal = $this->getDatos()['IdSucursal'];
        $servicio = $this->getDatos()['servicio'];
        $height = 0;
        $width = 0;

        /* $row = array(
            array(
            
              "IdEquipo"=> "0",
              "Imagen"=> "20f0ef2c.png",
              "Fecha"=> "2021-07-22",
              "Descripcion"=> "Recarga de R-22 y toma de parámetros presión-amperaje, equipo de Site",
              "IdServicio"=> "60259",
              "Mostrar"=> "s",
              "Contador"=> "20f0ef2c",
              "Descripcion2"=> "Recarga de R-22 y toma de parámetros presión-amperaje, equipo de Site",
              "Posicion"=> "0"
            ),
            array(
              "IdEquipo"=> "0",
              "Imagen"=> "36c6b9c3.png",
              "Fecha"=> "2021-07-22",
              "Descripcion"=> "lectura antes de recarga !!!!",
              "IdServicio"=> "60259",
              "Mostrar"=> "s",
              "Contador"=> "36c6b9c3",
              "Descripcion2"=> "lectura antes de recarga !!!!",
              "Posicion"=> "0"
            ),
            array(
              "IdEquipo"=> "0",
              "Imagen"=> "a60abdee.png",
              "Fecha"=> "2021-07-22",
              "Descripcion"=> "amperaje antes de recarga !!!!",
              "IdServicio"=> "60259",
              "Mostrar"=> "s",
              "Contador"=> "a60abdee",
              "Descripcion2"=> "amperaje antes de recarga !!!!",
              "Posicion"=> "0"
            ),
            array(
              "IdEquipo"=> "0",
              "Imagen"=> "df21d0d7.png",
              "Fecha"=> "2021-07-22",
              "Descripcion"=> "lectura después de la recarga de R-22 !!!!",
              "IdServicio"=> "60259",
              "Mostrar"=> "s",
              "Contador"=> "df21d0d7",
              "Descripcion2"=> "lectura después de la recarga de R-22 !!!!",
              "Posicion"=> "0"
            ),
            array(
              "IdEquipo"=> "0",
              "Imagen"=> "79853418.png",
              "Fecha"=> "2021-07-22",
              "Descripcion"=> "lectura después de la recarga de R-22 !!!!",
              "IdServicio"=> "60259",
              "Mostrar"=> "s",
              "Contador"=> "79853418",
              "Descripcion2"=> "lectura después de la recarga de R-22 !!!!",
              "Posicion"=> "0"
            ),
            array(
              "IdEquipo"=> "0",
              "Imagen"=> "e1534e86.png",
              "Fecha"=> "2021-07-22",
              "Descripcion"=> "se coloca tapón de bronce en válvula de servicio de baja !!!!",
              "IdServicio"=> "60259",
              "Mostrar"=> "s",
              "Contador"=> "e1534e86",
              "Descripcion2"=> "se coloca tapón de bronce en válvula de servicio de baja !!!!",
              "Posicion"=> "0"
            )
        ); */

        //$row2 = $row;

        //$rutaF = 'https://ndlsoft.com/control/reportefoto/60259/';
        $ruta = 'assets/files/servicios/'.$IdEmpresa.'/'.$IdSucursal.'/'.$servicio->IdServicio.'/';
        //busca en ndlsoft
        $ruta2 = returnLinkFotosServicios().$servicio->IdServicio.'/';

        /*$this->SetXY(5,$y+5);
        $this->MultiCell(200,200,'',1,'L',false);
        $this->SetXY(5,$y+5);
        $this->MultiCell(100,100,'',1,'L',false); */

        $altoCol1 = 0;
        $altoCol2 = 0;
		$loop = 0;
      
        for($i=0;$i<count($row);$i++)
        {
			if($loop == 4)
			{
				$this->AddPage();
				$this->SetXY($x,25);
				$loop = 0;
			}
			
            //echo $i;
            $text = $row[$i]->Descripcion2;
            $Status= $row[$i]->Status;
            $Imagen = $ruta.$row[$i]->Imagen;

            if(!file_exists($Imagen))
            {
                //si no existe que busque y se iguale a ndlsfot
                $Imagen=$ruta2.$row[$i]->Imagen;
                if(file_exists($Imagen))
                {
                    $Imagen='assets/files/iconemp/Casa.png';
                }
            }

            $rutaFinal = $Imagen;
            
            if(($posicion = $this->imagen_VH($rutaFinal)) == 'H'){
                $width = 180;
                $height = $this->nuevaMedida($rutaFinal,$posicion,$width); //---si es horizontal obtenemos su alto proporcional
            }
            else if(($posicion = $this->imagen_VH($rutaFinal)) == 'V'){
                $height = 180;
                $width = $this->nuevaMedida($rutaFinal,$posicion,$height);  //---si es vertical obtenemos su ancho proporcional	
            }
            else if(($posicion = $this->imagen_VH($rutaFinal))=='I'){
                $height = 180;
                $width = 180;
            }
                
            $Equipo2 = $row[$i]->Equipo;    
            
            // OBTENEMOS EL NUMERO DE LINEAS QUE TIENE LA DESCRIPCION, 140 ES EL ANCHO DE LA CELDA Y LO * POR EL ALTO
            $this->SetFont('','',9.5);
            $NLine = $this->getNumLines($text,95);
            $hcell = $NLine*5;
            $htotal = $hcell;
            
            // SI EL ALTO TOTAL ES MENOR A 20 SE SETEA UN ALTO FIJO
            if($htotal<20){
                $htotal=20;
            }
            
            //SALTO DE FILA
            if($contador==0){
                $y = $this->GetY()+3; 
            }
            
            if($this->checkPageBreak($hcell,$y+60,false)){
                $this->AddPage();
                $y = $this->GetY()+4; 
            }
            
       
            $this->pintar_estatus($Status);
            $this->SetFont('','B',12);
            $this->SetTextColor(255,255,255);
            $this->SetXY($x+115,$y);
            $this->MultiCell(70,5,$Equipo2,0,'L',true);
            $y2 = $this->GetY()+3;

            
 			$this->writeHTMLCell(100,60,$x,$y2,'<img src="'.$Imagen.'"  height="'.$height.'" width="'.$width.'">',0,0,false,true,'C');
			$this->lastPage(true);
			$this->Ln();
            $y3 = $this->GetY();
            
            $this->SetFont('','',9.5);
            $this->SetTextColor(0,0,0);
            $this->SetXY($x,$y3);
            $this->MultiCell(95,5,$text,1,'L',false);
            
            //$this->Image('assets/files/iconos/facebook.png',$x,$y,40,40);
            $contador++;
			
            if($contador == 1){
                $altoCol1 = $this->GetY();
            }
            else if($contador == 2){
                $altoCol2 = $this->GetY();
            }
			
            $x = 105;
            if($contador==2){
                $x = 5;
                $contador = 0;
            }


            $masAlto = max($altoCol1,$altoCol2);
            $this->SetXY($x,$masAlto);
			
			$loop++;
        }

		$loop2 = 0;
        for($i=0;$i<count($row2);$i++)
        {
			if($loop2 == 4)
			{
				$this->AddPage();
				$this->SetXY($x,25);
				$loop2 = 0;
			}
			
            $text=$row2[$i]->Descripcion2;
            $Status="";
            $Imagen = $ruta.$row2[$i]->Imagen;

            if(!file_exists($Imagen)){
                $Imagen='assets/files/iconemp/Casa.png';
                
            }

            $rutaFinal = $Imagen;
            
            if(($posicion = $this->imagen_VH($rutaFinal)) == 'H'){
                $width = 180;
                $height = $this->nuevaMedida($rutaFinal,$posicion,$width); //---si es horizontal obtenemos su alto proporcional
            }
            else if(($posicion = $this->imagen_VH($rutaFinal)) == 'V'){
                $height = 180;
                $width = $this->nuevaMedida($rutaFinal,$posicion,$height);  //---si es vertical obtenemos su ancho proporcional	
            }
            else if(($posicion = $this->imagen_VH($rutaFinal))=='I'){
                $height = 180;
                $width = 180;
            }

            

            $Equipo2 ='Imagen del servicio';
            $this->SetFont('','',9.5);
            $NLine = $this->getNumLines($text,95);
            $hcell = $NLine*5;
            
            $htotal=$hcell;
            
            if($htotal<20){
                $htotal=20;
            }
            
            //SALTO DE PAGINA
            if($contador==0){
                $y=$this->GetY()+3; 
            }
            
            if($this->checkPageBreak($hcell,$y+60,false)){
                $this->AddPage();
                $y=$this->GetY()+4; 
            }
            
        
            $this->pintar_estatus($Status);
            $this->SetFont('','B',12);
            $this->SetTextColor(255,255,255);
            $this->SetXY($x+120,$y);
            $this->MultiCell(70,5,$Equipo2,0,'L',true);
            $y2=$this->GetY()+3;
            
            $this->writeHTMLCell(60,60,$x+15,$y2,'<img src="'.$Imagen.'" height="'.$height.'" width="'.$width.'">',0,0,false,true,'C');
            $this->lastPage(true);
            $this->Ln();
            $y3=$this->GetY();
            
            $this->SetFont('','',9.5);
            $this->SetTextColor(0,0,0);
            $this->SetXY($x,$y3);
            $this->MultiCell(95,5,$text,1,'L',false);
            
            //$this->Image('assets/files/iconos/facebook.png',$x,$y,40,40);
            $contador++;
            
			
			if($contador == 1){
                $altoCol1 = $this->GetY();
            }
            else if($contador == 2){
                $altoCol2 = $this->GetY();
            }
			
            $x=105;
            if($contador==2){
                $x=5;
                $contador =0;
            }

            

            $masAlto = max($altoCol1,$altoCol2);
            $this->SetXY($x,$masAlto);
			
			$loop2++;
        }
    }

    function imagen_VH($imagen)
    {
        $fila = getimagesize($imagen);

        $anchura = $fila[0]; 
        $altura = $fila[1]; 
        
        if ($anchura < $altura) { 
            $HV= "V"; 
        }
        else if($anchura == $altura) {
            $HV='I';
        }
        else { 
            $HV= "H"; 
        }
        return $HV;  

    }

    function nuevaMedida($imagen,$posicion,$tamanio) 
    {
        $fila = getimagesize($imagen);
        $ancho_original = $fila[0]; 
        $alto_original = $fila[1]; 

        if($posicion=='H')
        {
            $ancho_final = $tamanio;
            $alto_final = ($ancho_final / $ancho_original) * $alto_original;
            $medida=$alto_final;
        }
        else if($posicion=='V')
        {
            $alto_final = $tamanio;
            $ancho_final = ($alto_final / $alto_original) * $ancho_original;
            $medida=$ancho_final;
        }
        return $medida;
    }
}

?>