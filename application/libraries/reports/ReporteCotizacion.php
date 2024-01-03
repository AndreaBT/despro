<?php

class ReporteCotizacion extends Cbasereport{

    public function Header()
    {
        $FOLIO 	            = $this->getDatos()['Folio'];//'SERV-XXXX';
        $TITULO_DOCUMENTO  	= 'COTIZACIÓN';
        $imgFile 			= '';
        $img64 				= '';
        $bnd64 				= false;
        $bndFile			= false;
        $RutaLogo			= 'assets/files/logo_empresa/';


        /*$IdEmpresa=$this->getDatos()['IdEmpresa'];
        //$IdSucursal=$this->getDatos()['IdSucursal'];

        if(isset($this->getDatos()['Titulo'])){
            $TITULO=$this->getDatos()['Titulo'];
        }

        $oMempresa=new Mempresa();
        $oMempresa->IdEmpresa = $IdEmpresa;
        $resemp = $oMempresa->get_empresa();

        if($resemp['status']) {
            $imgFile 	= $resemp['data']->Logo;
            $img64 		= $resemp['data']->Imagen;
        }
        if($this->getDatos()['FolioFactura']!=''){
            $FOLIO_FACTURA = $this->getDatos()['FolioFactura'];
        }*/
		
		$oMempresa=new Mempresa();
		$oMempresa->IdEmpresa = $this->getDatos()['IdEmpresa'];
		$resemp = $oMempresa->get_empresa();
		//print_r($resemp);
		if($resemp['status']) {
			$imgFile 	= $resemp['data']->Logo;
			$img64 		= $resemp['data']->Imagen;
		}
		
		if(!empty($imgFile)){
			$bndFile = true;
		}

        if(!empty($imgFile)){
            $bndFile = true;
        }

        if(!empty($img64)){
            $bnd64 = true;
        }



        $x = 8;
        $y = $this->GetY();

        //$this->SetXY($x,$y+5);
        //$this->MultiCell(190,2,'',0,'L',true);  ## LINEA DE EJEMPLO DE MARGENES

        $this->SetFillColor(150,133,202);

        $this->SetFont('','B',24);
        $this->SetTextColor(15,63,135);

        $this->SetXY($x,$y+5);
        $this->MultiCell(100,10,mb_strtoupper($TITULO_DOCUMENTO,'UTF-8'),0,'L',false);

        $this->SetFont('','B',13);
        $this->SetTextColor(0,0,0);

        $this->SetXY($x,$y+17);
        $this->MultiCell(15,5,'Folio:',0,'L',false);

        //if(!empty($FOLIO)) {
            $this->SetXY($x + 13, $y + 17);
            $this->MultiCell(70, 5, mb_strtoupper($FOLIO, 'UTF-8'), 0, 'L', false);
            $this->lastPage(false);
        //}

        if($bndFile){
            // IMAGEN DE ARCHIVO
            $Imagen=$RutaLogo.$imgFile;
            if(file_exists($Imagen)){
                $this->Image($Imagen,$x+172,$y+5,23,23);
            }

        }else if($bnd64) {
            // BASE 64 IMAGEN
            //$img_base64_encoded = 'data:image/png;base64,'.$img64;
            //$img = '<img width="100" src="@' . preg_replace('#^data:image/[^;]+;base64,#', '', $img_base64_encoded) . '">';


            $img_base64_encoded = 'data:image/png;base64,'.$img64;
            $imageContent 		= file_get_contents($img_base64_encoded);
            $path 				= tempnam(sys_get_temp_dir(), 'prefix');
            file_put_contents ($path, $imageContent);


            $img = '<img width="100px" src="' . $path . '">';
            $this->SetXY($x+172,$y+5);
            $this->MultiCell(25, 10, $img, '', 'C', false, 0, '', '', true, 0, true, false, 10, 'T');

        }else {
            // IMAGEN DEFECTO

            //$Imagen='assets/ImgBase/logo_default.png';
            $Imagen='img/logo2.png';
            if(file_exists($Imagen)){
                $this->Image($Imagen,$x+172,$y+5,23,23);
            }
        }
    }

    public function Footer()
    {
        //$Pagina = $this->getAliasNumPage().' | '.$this->getAliasNbPages();
        $Pagina = 'Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages();


        $this->SetFont('','',8);
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        // Page number}
        $y=$this->GetY()+4;
        $this->SetXY(90,$y);
        $this->MultiCell(35, 5,$Pagina, 0);
    }

    public function Documento() {

        $NOMBRE_CLIENTE 	= $this->getDatos()['clientes']->Nombre;//$this->getDatos()['factura']->NombreCliente;
        $NOM_SUCURSAL 		= $this->getDatos()['clientesucursal']->Nombre;//$this->getDatos()['factura']->Sucursal;
        $DIRECCION 			= $this->getDatos()['clientesucursal']->Direccion;//$this->getDatos()['factura']->Direccion;
        $CONTACTO 			= $this->getDatos()['clientes']->Contacto;
		$TELEFONO 			= $this->getDatos()['clientesucursal']->Telefono;
        $CORREO 			= $this->getDatos()['clientesucursal']->Correo;


        $DISTANCIA          = $this->getDatos()['cotizacion']->distancia;
        $VEL_PROMEDIO       = $this->getDatos()['cotizacion']->velocidad;
        $COSTO_KM           = $this->getDatos()['cotizacion']->costoKm;

        $GROSS_PROFIT       = $this->getDatos()['cotizacion']->GrossProfit;
        $FACTOR_HR_EXTRA    = $this->getDatos()['cotizacion']->factorHoraExtra;
        $UTILIDAD_APROX     = $this->getDatos()['cotizacion']->utilidad;
        $GASTOS_OPERACION   = $this->getDatos()['cotizacion']->gastoOperaciones;

        $MANO_OBRA          = $this->getDatos()['cotizacion']->totalManoDeObra;
        $COSTO_DEZPLAMTO    = $this->getDatos()['cotizacion']->TotalCostoKm;
        $MISCELANEOS        = $this->getDatos()['cotizacion']->totalMiscelaneos;
        $MATERIALES         = $this->getDatos()['cotizacion']->totalMateriales;
        $COSTO_MANO_DEZ     = $this->getDatos()['cotizacion']->CostoManoObraD;
        $COSTO_BURDEN       = $this->getDatos()['cotizacion']->CostoBurden;
        $GARANTIA           = $this->getDatos()['TotalGarantia'];
        $COSTO_TOTAL        = $this->getDatos()['cotizacion']->totalGlobal;
        $VALOR_VENTA        = $this->getDatos()['cotizacion']->ValorVenta;
        $COMENTARIOS        = $this->getDatos()['cotizacion']->Observaciones;//'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore doloribus hic impedit nobis odio quas, reprehenderit sequi unde.';
		

        ##****************************************

        $this->AddPage();
        $x 	= 8;
        $y 	= $this->GetY()+5;
        $tittleSize = 13;


        $this->SetFillColor(150,133,202);
        $this->SetTextColor(15,63,135);


        $this->SetFont('','B',$tittleSize);
        $this->SetXY($x,$y);
        $this->MultiCell(30,5,'Cliente',0,'L',false);

        $this->SetXY($x+140,$y);
        $this->MultiCell(35,5,'Datos del Sitio',0,'L',false);


        $y  = $this->GetY()+2;
        $y2 = $this->GetY()+2;
        $this->SetTextColor(0,0,0);

        $this->SetFont('','B',10);

        $this->SetXY($x,$y);
        $this->MultiCell(22,5,'Cliente:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+22,$y);
        $this->MultiCell(114,5,$NOMBRE_CLIENTE,'','L',false);

        $y = $this->GetY();
        $this->SetFont('','B',10);

        $this->SetXY($x,$y);
        $this->MultiCell(22,5,'Sucursal:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+22,$y);
        $this->MultiCell(114,5,$NOM_SUCURSAL,'','L',false);

        $y=$this->GetY();
        $this->SetFont('','B',10);

        $this->SetXY($x,$y);
        $this->MultiCell(22,5,'Dirección:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+22,$y);
        $this->MultiCell(114,5,$DIRECCION,'','L',false);

        $y = $this->GetY();
        $this->SetFont('','B',10);

        $this->SetXY($x,$y);
        $this->MultiCell(22,5,'Teléfono:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+22,$y);
        $this->MultiCell(114,5,$TELEFONO,'','L',false);
		
		/*
		$this->SetXY($x,$y);
        $this->MultiCell(22,5,'Telefono:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+22,$y);
        $this->MultiCell(114,5,$TELEFONO,'','L',false);
		
		$this->SetXY($x,$y);
        $this->MultiCell(22,5,'Correo:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+22,$y);
        $this->MultiCell(114,5,$CORREO,'','L',false);
		*/



        #***************Columna 2**************
        ## DISTANCIA
        $this->SetFont('','B',10);
        $this->SetTextColor(0,0,0);
        $this->SetXY($x+140,$y2);
        $this->MultiCell(38,5,'Distancia:',0,'L',false);

        $this->SetFont('','',10);
        $this->SetTextColor(0,0,0);
        $this->SetXY($x+179,$y2);
        $this->MultiCell(21,5,$DISTANCIA.' km',0,'L',false);

        ## VELOCIDAD PROMEDIO
        $y2 = $this->GetY();
        $this->SetFont('','B',10);
        $this->SetTextColor(0,0,0);
        $this->SetXY($x+140,$y2);
        $this->MultiCell(38,5,'Velocidad Promedio:',0,'L',false);

        $this->SetFont('','',10);
        $this->SetTextColor(0,0,0);
        $this->SetXY($x+179,$y2);
        $this->MultiCell(21,5,$VEL_PROMEDIO.' km/h',0,'L',false);

        ## COSTO POR KM
        $y2 = $this->GetY();
        $this->SetFont('','B',10);
        $this->SetTextColor(0,0,0);
        $this->SetXY($x+140,$y2);
        $this->MultiCell(38,5,'Costo por Km:',0,'L',false);

        $this->SetFont('','',10);
        $this->SetTextColor(0,0,0);
        $this->SetXY($x+179,$y2);
        $this->MultiCell(21,5,'$' . number_format(floatval($COSTO_KM), 2, '.', ','),0,'L',false);

        ##******************************************* DATOS ADICIONALES *****************************************************
        $y 	= $this->GetY()+10;

        $this->SetFillColor(150,133,202);
        $this->SetTextColor(15,63,135);


        $this->SetFont('','B',$tittleSize);
        $this->SetXY($x,$y);
        $this->MultiCell(50,5,'Datos Adicionales',0,'L',false);

        $y  = $this->GetY()+2;
        $y2 = $this->GetY()+2;
        $this->SetTextColor(0,0,0);

        $this->SetFont('','B',10);

        $this->SetXY($x,$y);
        $this->MultiCell(42,5,'Gross Profit:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+50,$y);
        $this->MultiCell(30,5,$GROSS_PROFIT.' %','','R',false);

        $y = $this->GetY();
        $this->SetFont('','B',10);

        $this->SetXY($x,$y);
        $this->MultiCell(42,5,'Factor Hr. Extra:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+50,$y);
        $this->MultiCell(30,5,$FACTOR_HR_EXTRA.' %','','R',false);

        $y=$this->GetY();
        $this->SetFont('','B',10);

        $this->SetXY($x,$y);
        $this->MultiCell(42,5,'Utilidad Aproximada:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+50,$y);
        $this->MultiCell(30,5, number_format(floatval($UTILIDAD_APROX), 2, '.', ',').'%','','R',false);

        $y = $this->GetY();
        $this->SetFont('','B',10);

        $this->SetXY($x,$y);
        $this->MultiCell(42,5,'Gastos Operacionales:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+50,$y);
        $this->MultiCell(30,5,$GASTOS_OPERACION.' %','','R',false);



        ##******************************************* Spen Plan *****************************************************

        $y 	= $this->GetY()+5;

        $this->SetFillColor(150,133,202);
        $this->SetTextColor(15,63,135);


        $this->SetFont('','B',$tittleSize);
        $this->SetXY($x,$y);
        $this->MultiCell(50,5,'Spend Plan',0,'L',false);

        $y  = $this->GetY()+2;

        $this->SetTextColor(0,0,0);

        $this->SetFont('','B',10);

        $this->SetXY($x,$y);
        $this->MultiCell(78,5,'Costo de Desplazamiento de Mano de Obra:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+82,$y);
        $this->MultiCell(30,5,'$' . number_format(floatval($COSTO_MANO_DEZ), 2, '.', ','),'','R',false);

        $y = $this->GetY();
        $this->SetFont('','B',10);

        $this->SetXY($x,$y);
        $this->MultiCell(78,5,'Costo de Desplazamiento de vehículos:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+82,$y);
        $this->MultiCell(30,5,'$' . number_format(floatval($COSTO_DEZPLAMTO), 2, '.', ','),'','R',false);

        $y=$this->GetY();
        $this->SetFont('','B',10);

        $this->SetXY($x,$y);
        $this->MultiCell(78,5,'Mano de Obra:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+82,$y);
        $this->MultiCell(30,5,'$' . number_format(floatval($MANO_OBRA), 2, '.', ','),'','R',false);

        $y = $this->GetY();
        $this->SetFont('','B',10);

        $this->SetXY($x,$y);
        $this->MultiCell(78,5,'Burden:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+82,$y);
        $this->MultiCell(30,5,'$' . number_format(floatval($COSTO_BURDEN), 2, '.', ','),'','R',false);

        $y = $this->GetY();
        $this->SetFont('','B',10);

        $this->SetXY($x,$y);
        $this->MultiCell(78,5,'Materiales:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+82,$y);
        $this->MultiCell(30,5,'$' . number_format(floatval($MATERIALES), 2, '.', ','),'','R',false);

        $y = $this->GetY();
        $this->SetFont('','B',10);

        $this->SetXY($x,$y);
        $this->MultiCell(78,5,'Miscelaneos:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+82,$y);
        $this->MultiCell(30,5,'$' . number_format(floatval($MISCELANEOS), 2, '.', ','),'','R',false);

        $y = $this->GetY();
        $this->SetFont('','B',10);

        $this->SetXY($x,$y);
        $this->MultiCell(78,5,'Garantia:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+82,$y);
        $this->MultiCell(30,5,'$' . number_format(floatval($GARANTIA), 2, '.', ','),'','R',false);


        $y 	= $this->GetY()+5;
        $this->SetTextColor(15,63,135);
        $this->SetFont('','B',$tittleSize-1);

        $this->SetXY($x,$y);
        $this->MultiCell(50,5,'Costo Total',0,'L',false);

        $this->SetFont('','B',$tittleSize-1);
        $this->SetTextColor(0,0,0);


        $this->SetXY($x+78,$y);
        $this->MultiCell(35,5,'$' . number_format(floatval($COSTO_TOTAL), 2, '.', ','),'','R',false);

        $y 	= $this->GetY();
        $this->SetTextColor(15,63,135);
        $this->SetFont('','B',$tittleSize-1);

        $this->SetXY($x,$y);
        $this->MultiCell(50,5,'Valor Venta',0,'L',false);

        $this->SetFont('','B',$tittleSize-1);
        $this->SetTextColor(0,0,0);

        $this->SetXY($x+78,$y);
        $this->MultiCell(35,5,'$' . number_format(floatval($VALOR_VENTA), 2, '.', ','),'','R',false);

        $y 	= $this->GetY()+7;

        $this->SetFillColor(150,133,202);
        //$this->SetTextColor(31,56,100); #AZUL ENVIADO EN WORD
        $this->SetTextColor(15,63,135);



        $this->SetFont('','B',$tittleSize);
        $this->SetXY($x,$y);
        $this->MultiCell(50,5,'Observaciones',0,'L',false);


        $h = $this->GetNewCellHeight($COMENTARIOS,200,5);
        $y = $this->SheetBraker($this->GetY(),$h,5,35,8) + 5;

        $y = $this->GetY()+3;

        $this->SetXY($x,$y);
        $this->SetDrawColor(199,0,57);
        $this->SetLineWidth(0.5);
        $this->SetTextColor(0,0,0);
        $this->SetFont('','',10);
        $this->MultiCell(200,5,$COMENTARIOS,'TBLR','L',false);




        $this->hojaTablas();


    }


    public function hojaTablas()
    {   
        //print_r($this->getDatos()['miselaneo']);
        $MISCELANEOS_LISTA  = $this->getDatos()['miselaneo'];
		/*array(
            array("concepto" => 'Contratistas', "cantidad" => 2, "valor" => 12.50),
            array("concepto" => 'Equipos', "cantidad" => 2, "valor" => 12.50),
        );*/
        $MANO_OBRA_LISTA    = $this->getDatos()['manoobra'];
		/*array(
            array("categoria" => 'Tecnico',                 "costoMO" => 200, "horaNormal" => 1, "horaExtra" => 1, "totalIndividual" => 200),
            array("categoria" => 'Tecnico + Ayu',           "costoMO" => 300, "horaNormal" => 0, "horaExtra" => 0, "totalIndividual" => 0),
            array("categoria" => 'Técnico Especializados',  "costoMO" => 0, "horaNormal" => 0, "horaExtra" => 0, "totalIndividual" => 0),
            array("categoria" => 'Ingeniería',              "costoMO" => 0, "horaNormal" => 0, "horaExtra" => 0, "totalIndividual" => 0),
            array("categoria" => 'Supervisión',             "costoMO" => 0, "horaNormal" => 0, "horaExtra" => 0, "totalIndividual" => 0),
        );
		*/
        $MATERIALES_LISTA  = $this->getDatos()['materiales'];
		/*array(
            array("NombreMat" => 'cinta', "cantidad" => 2, "costoUnitario" => 12.50, "totalIndividual" => "25"),
            array("NombreMat" => 'cable', "cantidad" => 2, "costoUnitario" => 12.50, "totalIndividual" => "25"),
            array("NombreMat" => 'aislante', "cantidad" => 2, "costoUnitario" => 12.50, "totalIndividual" => "25"),
        );*/


        ##****************************************

        $this->AddPage();
        $x 	= 8;
        $y 	= $this->GetY()+5;
        $tittleSize = 13;
        //************************************* TABLA DE MISCELANEOS ***********************************************************************

        $y 	= $this->GetY()+7;

        $this->SetFillColor(150,133,202);
        //$this->SetTextColor(31,56,100); #AZUL ENVIADO EN WORD
        $this->SetTextColor(15,63,135);
        
        
        
        $this->SetFont('','B',$tittleSize);
        $this->SetXY($x,$y);
        $this->MultiCell(50,5,'Misceláneos',0,'L',false);
        
        $y  = $this->getY()+3;
        $this->SetXY($x,$y);
        $this->SetFillColor(120,158,120);
        $this->SetTextColor(15,63,135);
        
                $this->SetDrawColor(199,0,57);
                $this->SetLineWidth(0.5);
                $this->SetFont('','B',$tittleSize-1);
        
                $w = array(67,66,67);
                $header = array('Descripción', 'Costo', 'Valor Venta');
                $num_headers = count($header);
        
                for($i = 0; $i < $num_headers; ++$i) {
                    if($i < 1){
                        $this->MultiCell($w[$i],8, $header[$i], 'TB', 'L', false, 0,'','',true,0,false,true,8,'M');
                    }else {
                        $this->MultiCell($w[$i],8, $header[$i], 'TB', 'C', false, 0,'','',true,0,false,true,8,'M');
                    }
        
                }
                $this->Ln(9);
        
        
                foreach($MISCELANEOS_LISTA as $row) {
                    
                    $valorN  = 0;
                        
                    if($row->valor > 0)
                    {
                        $valorN  = $row->valor;
                    }
        
                    #Cortador
                    $ancho  = 66;
                    $h = $this->GetNewCellHeight($row->concepto,$ancho,5);
                    $y = $this->SheetBraker($this->GetY(),$h,5,37,8)+5;
        
                    $this->SetX(8);
                    $this->SetFont('','',10);
                    $this->SetTextColor(0,0,0);
                    //$this->SetDrawColor(1,113,122);
                    $Costo1 = !empty($valorN) ? $valorN : 0;
                    $val1 = empty($row->cantidad) ? 0 : $row->cantidad;
        
                    $this->MultiCell($w[0],$h, $row->concepto, '', 'L', false, 0,'','',true,0,false,true,$h,'T');
                    $this->MultiCell($w[1],$h, $val1, '', 'C', false, 0,'','',true,0,false,true,$h,'T');
                    $this->MultiCell($w[2],$h, '$'.number_format(floatval($Costo1),2,'.',''), '', 'R', false, 0,'','',true,0,false,true,$h,'T');
        
                    $this->Ln($h+1);
        
                }
        
        
        
                //************************************* TABLA DE MANO DE OBRA ***********************************************************************
        
                //$y 	= $this->GetY()+7;
                $exY = 0;
                if($this->PageNo() > 1){
                    $exY = 7;
                }
                $h = $this->GetNewCellHeight('Mano de Obra',50,5);
                $y = $this->SheetBraker($this->GetY()+$exY,$h,5,37,8)+5;
        
                $this->SetFillColor(150,133,202);
                //$this->SetTextColor(31,56,100); #AZUL ENVIADO EN WORD
                $this->SetTextColor(15,63,135);
        
        
        
                $this->SetFont('','B',$tittleSize);
                $this->SetXY($x,$y);
                $this->MultiCell(50,5,'Mano de Obra',0,'L',false);
        
                $y  = $this->getY()+3;
                $this->SetXY($x,$y);
                $this->SetFillColor(120,158,120);
                $this->SetTextColor(15,63,135);
        
                $this->SetDrawColor(199,0,57);
                $this->SetLineWidth(0.5);
                $this->SetFont('','B',$tittleSize-1);
        
                $w = array(40,40,40,40,40);
                $header = array('Categoria', 'Costo M.O/hr', 'Hora Normal', 'Hora Extra', 'Total');
                $num_headers = count($header);
        
                $yControl = $this->GetY();
                for($i = 0; $i < $num_headers; ++$i) {
                    if($i < 1){
                        $this->MultiCell($w[$i],8, $header[$i], 'TB', 'L', false, 0,'','',true,0,false,true,8,'M');
                    }else {
                        $this->MultiCell($w[$i],8, $header[$i], 'TB', 'C', false, 0,'','',true,0,false,true,8,'M');
                    }
        
                }
                $this->Ln(9);
        
                $yControl2 = $this->GetY();
                $n = $yControl2 - $yControl;
                $h = $this->GetNewCellHeight($n,$ancho,5);
                $y = $this->SheetBraker($this->GetY(),$h,5,39,8) + 5;
        
        
                foreach($MANO_OBRA_LISTA as $row) {
        
                    #Cortador
                    $ancho  = 40;
                    $h = $this->GetNewCellHeight($row->categoria,$ancho,5);
                    $y = $this->SheetBraker($this->GetY(),$h,5,37,8) + 5;
        
                    $this->SetX(8);
                    $this->SetFont('','',10);
                    $this->SetTextColor(0,0,0);
                    //$this->SetDrawColor(1,113,122);
        
                    $Costo1 = !empty($row->costoMO) ? $row->costoMO : 0;
                    $Costo2 = !empty($row->totalIndividual) ? $row->totalIndividual : 0;
                    $val1 = !empty($row->horaNormal) ? $row->horaNormal : 0;
                    $val2 = !empty($row->horaExtra) ? $row->horaExtra : 0;
        
                    $this->MultiCell($w[0],$h, $row->categoria, '', 'L', false, 0,'','',true,0,false,true,$h,'T');
                    $this->MultiCell($w[1],$h, '$'.number_format(floatval($Costo1),2,'.',''), '', 'R', false, 0,'','',true,0,false,true,$h,'T');
                    $this->MultiCell($w[2],$h, $val1, '', 'C', false, 0,'','',true,0,false,true,$h,'T');
                    $this->MultiCell($w[3],$h, $val2, '', 'C', false, 0,'','',true,0,false,true,$h,'T');
                    $this->MultiCell($w[4],$h, '$'.number_format(floatval($Costo2),2,'.',''), '', 'R', false, 0,'','',true,0,false,true,$h,'T');
        
                    $this->Ln($h+1);
        
                }
        
                //************************************* TABLA DE MATERIALES ***********************************************************************
        
                $y 	= $this->GetY()+7;
                $h = $this->GetNewCellHeight('Materiales',50,5);
                $y = $this->SheetBraker($this->GetY()+7,$h,5,37,8)+5;
        
                $this->SetFillColor(150,133,202);
                //$this->SetTextColor(31,56,100); #AZUL ENVIADO EN WORD
                $this->SetTextColor(15,63,135);
        
        
                $this->SetFont('','B',$tittleSize);
                $this->SetXY($x,$y);
                $this->MultiCell(50,5,'Materiales',0,'L',false);
        
                $y  = $this->getY()+3;
                $this->SetXY($x,$y);
                $this->SetFillColor(120,158,120);
                $this->SetTextColor(15,63,135);
        
                $this->SetDrawColor(199,0,57);
                $this->SetLineWidth(0.5);
                $this->SetFont('','B',$tittleSize-1);
        
                $w = array(50,50,50,50);
                $header = array('Descripción', 'Cantidad', 'Costo Unitario', 'Total');
                $num_headers = count($header);
        
                for($i = 0; $i < $num_headers; ++$i) {
                    if($i < 1){
                        $this->MultiCell($w[$i],8, $header[$i], 'TB', 'L', false, 0,'','',true,0,false,true,8,'M');
                    }else {
                        $this->MultiCell($w[$i],8, $header[$i], 'TB', 'C', false, 0,'','',true,0,false,true,8,'M');
                    }
        
                }
                $this->Ln(9);
        
        
                foreach($MATERIALES_LISTA as $row) {
                    $this->SetX(8);
                    $this->SetFont('','',10);
                    $this->SetTextColor(0,0,0);
                    //$this->SetDrawColor(1,113,122);
        
                    #Cortador
                    $ancho  = 50;
                    $h = $this->GetNewCellHeight($row->NombreMat,$ancho,5);
                    $y = $this->SheetBraker($this->GetY(),$h,5,37,8) + 5;
        
                    $Costo1 = !empty($row->costoUnitario) ? $row->costoUnitario : 0;
                    $Costo2 = !empty($row->totalIndividual) ? $row->totalIndividual : 0;
        
                    $this->MultiCell($w[0],$h, $row->NombreMat, '', 'L', false, 0,'','',true,0,false,true,$h,'T');
                    $this->MultiCell($w[1],$h, $row->cantidad, '', 'C', false, 0,'','',true,0,false,true,$h,'T');
                    $this->MultiCell($w[2],$h, '$'.number_format(floatval($Costo1),2,'.',''), '', 'R', false, 0,'','',true,0,false,true,$h,'T');
                    $this->MultiCell($w[2],$h, '$'.number_format(floatval($Costo2),2,'.',''), '', 'R', false, 0,'','',true,0,false,true,$h,'T');
        
                    $this->Ln($h+1);
        
                }
    }


    /**
     * @function SheetBraker - cortador de elementos de la hoja, determina si el el contenido ya supera un limite
     * establecido y determina que la informacion sea enviada a otra hoja
     * @param $y float posición actual de Y en el documento
     * @param $hCell int | float alto del elemento
     * @param $extraY int | float aumento extra para el inicio de la altura en la nueva hoja.
     * @param $newY int altura donde iniciara Y si se crea una nueva hoja.
     * @param $newX int posicion en horizontal donde iniciara X si se crea una nueva hoja.
     * @param $cutMaxHeight int limite de largo vertical para realizar el corte de hoja, por defecto 250
     * @return float valor de la nueva posicion de Y en la hoja
     */
    public function SheetBraker($y, $hCell, $extraY = 5, $newY = 60, $newX = 10, $cutMaxHeight = 250) {
        if ($y > $cutMaxHeight){
            $isBreak = $this->checkPageBreak($hCell,$y+$extraY,true);
            if($isBreak) {
                #$this->AddPage();
                $this->SetY($newY);
                $this->SetX($newX);
                return $this->GetY();
            }
        }else {
            return  $y;
        }
    }

    /**
     * @function GetNewHeigth - Determina la altura que necesita un texto.
     * @param $widthCell int | float Ancho de la celda.
     * @param $BaseHeightCell int Altura base o minima que tendra la celda
     * @param $Txt String Texto al cual se desea determinar su altura final
     * @param $ajuste int Incremento extra de altura opcional al crecer la celda.
     * @return float | int devuelve la altura que ocupa el texto deseado.
     *
     */
    public function GetNewHeigth($widthCell,$BaseHeightCell,$Txt,$ajuste = 0){

        $NumCaracteres = strlen($Txt) + 10;
        $RealLong = round((floatval($widthCell)/2),0,PHP_ROUND_HALF_EVEN);

        if($NumCaracteres > $RealLong) {
            $redondear = round(($NumCaracteres/$RealLong),0,PHP_ROUND_HALF_EVEN);
            $NewHeight = ($BaseHeightCell*$redondear);
            return (floatval($NewHeight) + floatval($ajuste));

        }else {
            return $BaseHeightCell + floatval($ajuste);

        }
    }

    public function GetNewCellHeight($texto,$widthCell = 10,$multiplicativo = 5){
        $NLine  = $this->getNumLines($texto,$widthCell);
        return $NLine*$multiplicativo;
    }

}



