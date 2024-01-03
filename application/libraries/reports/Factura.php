<?php
//session_start();

class Factura extends Cbasereport {

    #*****************LEER********************
    #*****************LEER********************
    #EN funciones helper se crea una tabla con header recibe parametros de (header,styleheader,detalle);


    function Header()
    {
        $this->headerFactura2();
    }

    public function Footer()
    {
       $this->FooterG('Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(),'Desprosoft 2018 ® ');
    }

    function contenido() {

    	$NOMBRE_CLIENTE 	= $this->getDatos()['factura']->NombreCliente;
    	$NOM_SUCURSAL 		= $this->getDatos()['factura']->Sucursal;
    	$DIRECCION 			= $this->getDatos()['factura']->Direccion;
    	$CONTACTO 			= $this->getDatos()['factura']->Contacto;

		$FOLIO_SERVICIO 	= $this->getDatos()['factura']->NoContrato;
    	$FECHA_FACTURA 		= $this->getDatos()['factura']->FechaReg;
    	$TIPO_SERVICIO		= $this->getDatos()['factura']->Servicio;

    	$DATOS_FACTURACION 	= $this->getDatos()['factura']->DatosFact;
    	$FOLIO_BASE 		= $this->getDatos()['factura']->FolioServ;
    	$DESCRIPCION_BASE 	= $this->getDatos()['factura']->ComentarioServ;
		$TIPOFACTURA		=$this->getDatos()['factura']->TipoFactura;

		$SERVICIO_BASE 		= array();
		$SERVICIOS_ANIDADOS	= $this->getDatos()['rowfacser'];
		$CONCEPTOS_FACTURA	= $this->getDatos()['rowdetfac'];



		//$this->SetFont('','',10);
		//$this->SetXY($x+20,$y);
		//$this->RoundedRect($x+20,$y-1,120,6,2,'1111','F','',array(240,240,240));
		//$this->MultiCell(120,5,$this->getDatos()['factura']->Servicio,0,'L',false);



    	$x 	= 10;
        $y 	= $this->GetY()+23;


		$this->SetFillColor(150,133,202);
		$this->SetTextColor(15,63,135);


        $this->SetFont('','B',11);
        $this->SetXY($x,$y);
        $this->MultiCell(30,5,'FACTURAR A',0,'L',false);

        $y  = $this->GetY();
        $y2 = $this->GetY();
		$this->SetTextColor(0,0,0);

        $this->SetFont('','B',10);

        $this->SetXY($x,$y);
        $this->MultiCell(22,5,'Cliente:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+22,$y);
        $this->MultiCell(115,5,$NOMBRE_CLIENTE,'','L',false);

        $y = $this->GetY();
        $this->SetFont('','B',10);

        $this->SetXY($x,$y);
        $this->MultiCell(22,5,'Sucursal:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+22,$y);
        $this->MultiCell(115,5,$NOM_SUCURSAL,'','L',false);

        $y=$this->GetY();
        $this->SetFont('','B',10);

        $this->SetXY($x,$y);
        $this->MultiCell(22,5,'Dirección:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+22,$y);
        $this->MultiCell(115,5,$DIRECCION,'','L',false);

        $y = $this->GetY();
        $this->SetFont('','B',10);

        $this->SetXY($x,$y);
        $this->MultiCell(22,5,'Contacto:','','L',false);

        $this->SetFont('','',10);

        $this->SetXY($x+22,$y);
        $this->MultiCell(115,5,$CONTACTO,'','L',false);



        #***************Columna 2**************
		## SERVICIOS
		$this->SetFont('','B',10);
		$this->SetTextColor(15,63,135);
		$this->SetXY($x+132,$y2);
		$this->MultiCell(30,5,'No CONTRATO:',0,'R',false);

		$this->SetFont('','',10);
		$this->SetTextColor(0,0,0);
		$this->SetXY($x+161,$y2);
		$this->MultiCell(30,5,$FOLIO_SERVICIO,0,'R',false);

		## FECHAS
		$y2 = $this->GetY();
		$this->SetFont('','B',10);
		$this->SetTextColor(15,63,135);
		$this->SetXY($x+132,$y2);
		$this->MultiCell(30,5,'FECHA:',0,'R',false);

		$this->SetFont('','',10);
		$this->SetTextColor(0,0,0);
		$this->SetXY($x+171,$y2);
		$this->MultiCell(21,5,date('d/m/Y',strtotime($FECHA_FACTURA)),0,'L',false);

		## TIPO DE SERVICIO
		$y2 = $this->GetY();
		$this->SetFont('','B',10);
		$this->SetTextColor(15,63,135);
		$this->SetXY($x+132,$y2);
		$this->MultiCell(30,5,'TIPO SERVICIO:',0,'R',false);

		$this->SetFont('','',10);
		$this->SetTextColor(0,0,0);
		$this->SetXY($x+161,$y2);
		$this->MultiCell(31,5,$TIPO_SERVICIO,0,'R',false);


        $y=$this->GetY()+8;


        $this->SetFont('','B',10);
		$this->SetTextColor(15,63,135);

        $this->SetXY($x,$y);
		$this->MultiCell(48,5, 'DATOS DE FACTURACIÓN', '', 'L', false, 0, '', '', true, 0, false, false, 10, 'T');

		$y	= $this->GetY();
		$nH = $this->GetNewHeigth(190,5,$DATOS_FACTURACION);

        $this->SetFont('','',9);
        $this->SetTextColor(0,0,0);

        $this->SetXY($x,$y+5);
		$this->MultiCell(190,$nH,utf8_decode($DATOS_FACTURACION) , '', 'L', false, 0, '', '', true, 0, false, false, $nH, 'T');



		//************************************* TABLA DE SERVICIOS ***********************************************************************
		if ($TIPOFACTURA =='1' || $TIPOFACTURA==null) {
			$y  = $this->getY()+7;
			$this->SetXY($x,$y);
			$this->SetFillColor(120,158,120);
			$this->SetTextColor(15,63,135);

			$this->SetDrawColor(199,0,57);
			$this->SetLineWidth(0.5);
			$this->SetFont('','B',9);

			$w = array(35,155);
			$header = array('No de SERVICIO', 'DESCRIPCIÓN');
			$num_headers = count($header);

			for($i = 0; $i < $num_headers; ++$i) {
				$this->MultiCell($w[$i],5, $header[$i], 'TB', 'L', false, 0,'','',true,0,false,true,5,'M');


			}
			$this->Ln(5.5);



			$SERVICIO_BASE = array(
				array(
					'Folio' => $FOLIO_BASE,
					'Comentario' => $DESCRIPCION_BASE
				)
			);
			//print_r($array);
			foreach($SERVICIO_BASE as $row) {
				$this->SetX(10);
				$this->SetFont('','',9);
				$this->SetTextColor(0,0,0);
				//$this->SetDrawColor(1,113,122);


				#Cortador
				$ancho  = 148;
				$BsAltura = 6; //!empty($row['Comentario']) ? 8 : 5;
				$h = $this->GetNewHeigth($ancho,$BsAltura,$row['Comentario']);
				$y = $this->SheetBraker($this->GetY(),$h) +5;

				$this->MultiCell($w[0],$h, $row['Folio'], '', 'L', false, 0,'','',true,0,false,true,$h,'T');
				$this->MultiCell($w[1],$h, $row['Comentario'], '', 'L', false, 0,'','',true,0,false,true,$h,'T');

				$this->Ln($h);

			}
		}





		//print_r($rowfacser);

		foreach($SERVICIOS_ANIDADOS as $row) {
			$this->SetX(10);
			$this->SetFont('','',9);
			$this->SetTextColor(0,0,0);
			//$this->SetDrawColor(1,113,122);

			#Cortador
			$ancho  = 148;
			$BsAltura = 6; //!empty($row->Descripcion) ? 8 : 5;
			$h = $this->GetNewHeigth($ancho,$BsAltura,$row->Descripcion);
			$y = $this->SheetBraker($this->GetY(),$h) +5;

			$this->MultiCell($w[0],$h, $row->Folio, '', 'L', false, 0,'','',true,0,false,true,$h,'T');
			$this->MultiCell($w[1],$h, $row->Descripcion, '', 'L', false, 0,'','',true,0,false,true,$h,'T');

			$this->Ln($h);

		}




        /*$y=$this->GetY()+$nH+10;

        $array=array(['texto'=>'Fol. Servicio','w'=>35],['texto'=>'Descripción','w'=>155]);
        $this->SetFillColor(82,134,223);
        $this->SetTextColor(255,255,255);
        $hh=5;$b=0;$f=true;
        $this->HeaderDetalleG($array,$x,$y,$hh,$b,$f);


        //Aqui se añade el default que es del servicio
		$this->SetFont('','',9);
		$this->SetTextColor(0,0,0);
        $array=array(['texto'=>$this->getDatos()['factura']->FolioServ,'w'=>35],['texto'=>$this->getDatos()['factura']->ComentarioServ,'w'=>155]);
        $this->DetalleG($array,$x,$this->GetY(),$hh,'','','');

		$rowfacser=$this->getDatos()['rowfacser'];
        for($i=0;$i<count($rowfacser);$i++){
            $this->SetFont('','',9);
            $this->SetTextColor(0,0,0);
            $Folio=$rowfacser[$i]->Folio;
            $Descripcion=$rowfacser[$i]->Descripcion;

            //datos
            $array=array(['texto'=>$Folio,'w'=>35],['texto'=>$Descripcion,'w'=>155]);

            $this->DetalleG($array,$x,$this->GetY(),$hh,'','','');

        }*/

		//************************************* TABLA DE CONCEPTOS ***********************************************************************
		$y  = $this->getY()+7;
		$this->SetXY($x,$y);
		$this->SetFillColor(120,158,120);
		$this->SetTextColor(15,63,135);

		$this->SetDrawColor(199,0,57);
		$this->SetLineWidth(0.5);
		$this->SetFont('','B',9);

		$w = array(20,117,25,28);
		$header = array('CANTIDAD', 'DESCRIPCIÓN', 'PRECIO', 'IMPORTE');
		$num_headers = count($header);

		for($i = 0; $i < $num_headers; ++$i) {
			if($i < 2){
				$this->MultiCell($w[$i],5, $header[$i], 'TB', 'C', false, 0,'','',true,0,false,true,5,'M');
			}else {
				$this->MultiCell($w[$i],5, $header[$i], 'TB', 'R', false, 0,'','',true,0,false,true,5,'M');
			}

		}
		$this->Ln(5.5);






		foreach($CONCEPTOS_FACTURA as $row) {
			$this->SetX(10);
			$this->SetFont('','',9);
			$this->SetTextColor(0,0,0);
			//$this->SetDrawColor(1,113,122);

			#Cortador
			$ancho  = 148;
			$BsAltura = !empty($row->Descripcion) ? 6 : 5;
			$h = $this->GetNewHeigth($ancho,$BsAltura,$row->Descripcion);
			$y = $this->SheetBraker($this->GetY(),$h) +5;

			$this->MultiCell($w[0],$h, number_format($row->Cantidad, 2, '.', ','), '', 'C', false, 0,'','',true,0,false,true,$h,'T');
			$this->MultiCell($w[1],$h, $row->Descripcion, '', 'L', false, 0,'','',true,0,false,true,$h,'T');
			$this->MultiCell($w[2],$h, '$' . number_format($row->CostoUni, 2, '.', ','), '', 'R', false, 0,'','',true,0,false,true,$h,'T');
			$this->MultiCell($w[3],$h, '$' . number_format($row->Total, 2, '.', ','), '', 'R', false, 0,'','',true,0,false,true,$h,'T');

			$this->Ln($h);

		}

       /* for($i=0;$i<count($rowfacser);$i++){
            $this->SetFont('','',9);
            $this->SetTextColor(0,0,0);
            $Descripcion=$rowfacser[$i]->Descripcion;
            $Cantidad=$rowfacser[$i]->Cantidad;
            $CostoUni=$rowfacser[$i]->CostoUni;
            $Total=$rowfacser[$i]->Total;

            //datos
            $array=array(['texto'=>$Descripcion,'w'=>85],['texto'=>$Cantidad,'w'=>35,'a'=>'C'],['texto'=>'$'.$CostoUni,'w'=>35,'a'=>'C'],['texto'=>'$'.$Total,'w'=>35,'a'=>'R']);

            $this->DetalleG($array,$x,$this->GetY(),$hh,'','','');
        }*/


		$TOTAL_FACTURA = $this->getDatos()['factura']->Total;

        $this->SetFont('','B',12);
		$this->SetTextColor(15,63,135);

		$y=$this->GetY()+5;

		$this->SetXY($x+120,$y);
        $this->MultiCell(35,5,'TOTAL',0,'R',false);

        $this->SetFillColor(82,134,223);

        $this->SetXY($x+155,$y);
        $this->MultiCell(35,5,'$'.number_format($TOTAL_FACTURA,2,'.',','),0,'R',false);
    }

}

?>
