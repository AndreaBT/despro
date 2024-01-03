<?php

class Servicio extends Cbasereport
{
    function Header()
    {
        $FOLIO_SERVICIO 	= $this->getDatos()['Folio'];
        $TITULO_DOCUMENTO  	= 'REPORTE DE SERVICIO';
        $imgFile 			= '';
        $img64 				= '';
        $bnd64 				= false;
        $bndFile			= false;
        $RutaLogo			= 'assets/files/logo_empresa/';


        $x = 0;
        $y = 0;

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

		if(!empty($img64)){
			$bnd64 = true;
		}


        if($bndFile){
            // IMAGEN DE ARCHIVO
            $Imagen=$RutaLogo.$imgFile;
            if(file_exists($Imagen)){
                $this->Image($Imagen,$x+2,$y+1,27,27, '', '', 'T', false, 300, '', false, false, 1, false, false, false);
                //$this->SetXY($x+2,$y+1);
                //$this->MultiCell(27,27,'','','L',true,1,'','',true,0,false,true,30,'M');
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
            $this->SetXY($x+1,$y+2);
            $this->MultiCell(25, 20, $img, '', 'C', false, 0, '', '', true, 0, true, false, 10, 'T');

        }else {
            // IMAGEN DEFECTO

            $Imagen='assets/ImgBase/logo_default.png';
            //$Imagen='img/logo2.png';
            if(file_exists($Imagen)){
                $this->Image($Imagen,$x+1,$y+2,25,25, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            }
        }


        $this->SetFillColor(7,27,140);
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(0.3);

        $this->SetTextColor(7,27,140);

        $this->SetFont('robotocondensed','',26);
        $this->SetXY($x+38,16.5);
        $this->MultiCell(104,14,$TITULO_DOCUMENTO,'','C',false,1,'','',true,0,false,true,14,'M');

        ## LINE VERTICAL
        $style2 = array('width' => 2.8, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(7,27,140));
        $this->Line($x+155.3, $y+1.4, $x+155.3, $y+24.4, $style2);

        #RECUADRO AZUL FOLIO
        $this->SetXY($x+162,0);
        $this->MultiCell(54,26,'','','L',true,1,'','',true,0,false,true,30,'M');

        #FOLIO
        $this->SetTextColor(255,255,255);
        $this->SetFont('robotocondensed','',18);

        $this->SetXY($x+167,4);
        $this->MultiCell(35,10,'FOLIO:','','L',false,1,'','',true,0,false,true,10,'M');

        $this->SetXY($x+167,12);
        $this->MultiCell(40,10,$FOLIO_SERVICIO,'','L',false,1,'','',true,0,false,true,10,'M');

        #LINEA SIMPLE INFERIOR
        $y = 26;
        $style = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(7,27,140));
        $this->Line($x, $y+3, 216, $y+3, $style);
    }

    function Footer (){
        $Pagina = $this->getAliasNumPage().' | '.$this->getAliasNbPages();

        $this->SetFont('','',8);
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        // Page number}
        $y=$this->GetY()+4;
        $this->SetXY(90,$y);
        $this->MultiCell(35, 5,$Pagina, 0);

    }

    function contenido(){

        $ID_EMPRESA		= $this->getDatos()['IdEmpresa'];
        $ID_SUCURSAL	= $this->getDatos()['IdSucursal'];
        $ID_SERVICIO	= $this->getDatos()['servicio']->IdServicio;
        $SERVICIO       = $this->getDatos()['servicio']->Servicio;
        $CLIENTE        = $this->getDatos()['servicio']->Client; 	//'FLUIDMASTER WATER TECHNOLOGY, S. DE R.L.DE C.V.';
        $SUCURSAL       = $this->getDatos()['sucursal']->Nombre;   //'FLUIDMASTER WATER TECHNOLOGY, S. DE R.L.DE C.V.'
        $DIRECCION      = $this->getDatos()['servicio']->Direccion; //'Av La Condesa Parque Industrial Nexxus'
        $CONTACTO       = $this->getDatos()['servicio']->Contacto;   //'Hugo Robles'

        $FECHA          = $this->getDatos()['fechaservicio']->FechaInicio;   //'13-08-2021'
        $HORA_INICIO    = $this->getDatos()['fechaservicio']->HoraInicio;   //'16:15:08'
        $HORA_FIN       = $this->getDatos()['fechaservicio']->HoraFin;     //'19:00:52'
        $RESPONSABLE    = $this->getDatos()['personal']->Nombre;   		  //'Brandon Cárdena sGarcía'
        $TELEFONO       = $this->getDatos()['sucursal']->Telefono;  	 //'1234657890'
        $CONTRATO       = $this->getDatos()['servicio']->NumContrato;   //'POL-210802-002-FMT'


		$TEAM_COMENTARIOS=$this->getDatos()['equipocomentario'];

        $TAREAS = $this->getDatos()['servicio']->ComentarioFin;

        $MATERIALES = $this->getDatos()['servicio']->Materiales;



        //print_r($TEAM_SERVICIOS);
        //print_r($GLOBAL_SERVICE);
        ///**************************** DOCUMENTO ******************************/

        $this->AddPage();
        $y              = 10;
        $x              = 6;
        $titleSize      = 13;
        $titleBoxSize   = 26;
        $XPlusText      = 29;
        $contentSize    = 10;
        $fontTitle      = 'robotocondensed';
        $fontText       = 'robotocondensedlight';

        $y=$this->GetY()+6;
        $y2=$this->GetY()+6;


        //$this->SetFillColor(239,241,249);
        $this->SetFillColor(235,237,248);
        $this->SetDrawColor(190,185,185);
        $this->SetLineWidth(0.3);

        $this->SetTextColor(7,27,140);
        $this->SetFont($fontTitle,'',$titleSize);

        $this->SetXY($x,$y);
        $this->MultiCell($titleBoxSize,10,'SERVICIO:','','L',true,1,'','',true,0,false,true,10,'M');


        $this->SetFont($fontText,'',$contentSize);
        $this->SetTextColor(0,0,0);

        $this->SetXY($x+$XPlusText,$y);
        $this->MultiCell(80,10,$SERVICIO,'','L',false,1,'','',true,0,false,true,10,'M');


        $y=$this->GetY()+0.5;

        $this->SetTextColor(7,27,140);
        $this->SetFont($fontTitle,'',$titleSize);

        $this->SetXY($x,$y);
        $this->MultiCell($titleBoxSize,10,'CLIENTE:','','L',true,1,'','',true,0,false,true,10,'M');

        $this->SetFont($fontText,'',$contentSize);
        $this->SetTextColor(0,0,0);

        $this->SetXY($x+$XPlusText,$y);
        $this->MultiCell(80,10,$CLIENTE,'','L',false,1,'','',true,0,false,true,10,'M');

        $y=$this->GetY()+0.5;

        $this->SetTextColor(7,27,140);
        $this->SetFont($fontTitle,'',$titleSize);

        $this->SetXY($x,$y);
        $this->MultiCell($titleBoxSize,10,'SUCURSAL:','','L',true,1,'','',true,0,false,true,10,'M');

        $this->SetFont($fontText,'',$contentSize);
        $this->SetTextColor(0,0,0);

        $this->SetXY($x+$XPlusText,$y);
        $this->MultiCell(80,10,$SUCURSAL,'','L',false,1,'','',true,0,false,true,10,'M');

        $y=$this->GetY()+0.5;

        $this->SetTextColor(7,27,140);
        $this->SetFont($fontTitle,'',$titleSize);

        $this->SetXY($x,$y);
        $this->MultiCell($titleBoxSize,10,'DIRECCIÓN:','','L',true,1,'','',true,0,false,true,10,'M');

        $this->SetFont($fontText,'',$contentSize);
        $this->SetTextColor(0,0,0);

        $this->SetXY($x+$XPlusText,$y);
        $this->MultiCell(80,10,$DIRECCION,'','L',false,1,'','',true,0,false,true,10,'M');

        $y=$this->GetY()+0.5;

        $this->SetTextColor(7,27,140);
        $this->SetFont($fontTitle,'',$titleSize);

        $this->SetXY($x,$y);
        $this->MultiCell($titleBoxSize,10,'CONTACTO:','','L',true,1,'','',true,0,false,true,10,'M');

        $this->SetFont($fontText,'',$contentSize);
        $this->SetTextColor(0,0,0);

        $this->SetXY($x+$XPlusText,$y);
        $this->MultiCell(80,10,$CONTACTO,'','L',false,1,'','',true,0,false,true,10,'M');

        $y=$this->GetY();



        #***************Columna 2**************
        $this->SetFont($fontTitle,'',$titleSize);
        $this->SetTextColor(7,27,140);

        $this->SetXY($x+112.5,$y2);
        $this->MultiCell(25,10,'FECHA:','','L',true,1,'','',true,0,false,true,10,'M');

        $this->SetFont($fontText,'',$contentSize);
        $this->SetTextColor(0,0,0);

        $this->SetXY($x+141,$y2);
        $this->MultiCell(20,10,date('d-m-Y',strtotime($FECHA)),'','L',false,1,'','',true,0,false,true,10,'M');


        $y2=$this->GetY()+0.5;

        $this->SetFont($fontTitle,'',$titleSize);
        $this->SetTextColor(7,27,140);

        $this->SetXY($x+112.5,$y2);
        $this->MultiCell(25,10,'HR INICIO:','','L',true,1,'','',true,0,false,true,10,'M');

        $this->SetFont($fontText,'',$contentSize);
        $this->SetTextColor(0,0,0);

        $this->SetXY($x+141,$y2);
        $this->MultiCell(18,10,substr($HORA_INICIO,0,-3),'','L',false,1,'','',true,0,false,true,10,'M');
        //$y2=$this->GetY();

        #
        $this->SetFont($fontTitle,'',$titleSize);
        $this->SetTextColor(7,27,140);

        $this->SetXY($x+161,$y2);
        $this->MultiCell(25,10,'HR FINAL:','','L',true,1,'','',true,0,false,true,10,'M');

        $this->SetFont($fontText,'',$contentSize);
        $this->SetTextColor(0,0,0);

        $this->SetXY($x+187,$y2);
        $this->MultiCell(15,10,substr($HORA_FIN,0,-3),'','C',false,1,'','',true,0,false,true,10,'M');

        $y2=$this->GetY()+0.5;


        $this->SetFont($fontTitle,'',$titleSize);
        $this->SetTextColor(7,27,140);

        $this->SetXY($x+112.5,$y2);
        //$this->MultiCell(30,5,'RESPONS:',0,'L',false);
        $this->MultiCell(25,10,'RESPONS:','','L',true,1,'','',true,0,false,true,10,'M');

        $this->SetFont($fontText,'',$contentSize);
        $this->SetTextColor(0,0,0);

        $this->SetXY($x+141,$y2);
        $this->MultiCell(62,10,$RESPONSABLE,'','L',false,1,'','',true,0,false,true,10,'M');

        $y2=$this->GetY()+0.5;



        $this->SetFont($fontTitle,'',$titleSize);
        $this->SetTextColor(7,27,140);

        $this->SetXY($x+112.5,$y2);
        //$this->MultiCell(30,5,'TELÉFONO:',0,'L',false);
        $this->MultiCell(25,10,'TELÉFONO:','','L',true,1,'','',true,0,false,true,10,'M');

        $this->SetFont($fontText,'',$contentSize);
        $this->SetTextColor(0,0,0);

        $this->SetXY($x+141,$y2);
        $this->MultiCell(45,10,$TELEFONO,'','L',false,1,'','',true,0,false,true,10,'M');
        $y2=$this->GetY()+0.5;

        $this->SetFont($fontTitle,'',$titleSize);
        $this->SetTextColor(7,27,140);

        $this->SetXY($x+112.5,$y2);
        //$this->MultiCell(30,5,'CONTRATO:',0,'L',false);
        $this->MultiCell(25,10,'CONTRATO:','','L',true,1,'','',true,0,false,true,10,'M');


        $this->SetFont($fontText,'',$contentSize);
        $this->SetTextColor(0,0,0);

        $this->SetXY($x+141,$y2);
        $this->MultiCell(62,10,$CONTRATO,'','L',false,1,'','',true,0,false,true,10,'M');

        $y2=$this->GetY()+5;

        $this->SetY($y2);

        //****************** TABLA DE SERVICIOS POR EQUIPO ******************************

        $this->SetDrawColor(7,27,140);
        $this->SetLineWidth(0.3);


        for($i=0; $i<count($TEAM_COMENTARIOS); $i++){
            $text   = $TEAM_COMENTARIOS[$i]->Comentario2;
            $Status = $TEAM_COMENTARIOS[$i]->Status;
            $Equipo = $TEAM_COMENTARIOS[$i]->Nequipo;

            $NLine  = $this->getNumLines($text,150);
            $hcell  = $NLine*5;

            $NLine2 = $this->getNumLines($Status.$Equipo,54);
            $hcel2  = $NLine2*5;

            $htotal = max($hcell,$hcel2);

            if($htotal<20){
                $htotal=20;
            }

            //SALTO DE PAGINA
            $this->checkPageBreak($htotal,$this->GetY()+1,true);
            $y=$this->GetY()+2;

            $this->SetTextColor(255,255,255);
            $this->pintar_estatus($Status);
            $this->SetFont($fontTitle,'B',10);
            $this->SetXY($x,$y);
            $this->MultiCell(54,$htotal,$Equipo."\n Estatus:".$Status,'TBLR','C',true,1,'','',true,0,false,true,$htotal,'M');


            $this->SetTextColor(0,0,0);
            $this->SetFont($fontText,'',10);
            $this->SetXY($x+54,$y);
            $this->MultiCell(150,$htotal,$text,'TBLR','L',false);


        }



        // ********************** TAREAS **********************************


        $NLine=$this->getNumLines($TAREAS,204);
        $hcell=$NLine*6;
        $this->checkPageBreak($hcell,$this->GetY()+10,true);

        $y = $this->GetY()+5;
        $this->SetTextColor(7,27,140);
        $this->SetFont($fontTitle,'',$titleSize);
        $this->SetXY($x,$y);
        $this->MultiCell(100,5,'Observaciones:',0,'L',false);


        $y = $this->GetY();

        $this->SetXY($x,$y);
        $this->SetTextColor(0,0,0);
        $this->SetFont($fontText,'',$contentSize);
        $this->MultiCell(204,5,$TAREAS,'TBLR','L',false);


        //*********************** MATERIALES ****************************



        $NLine=$this->getNumLines($MATERIALES,204);
        $hcell=$NLine*6;
        $this->checkPageBreak($hcell,$this->GetY()+10,true);

        $y = $this->GetY()+5;
        $this->SetTextColor(7,27,140);
        $this->SetFont($fontTitle,'',$titleSize);

        $this->SetXY($x,$y);
        $this->MultiCell(100,5,'Materiales:',0,'L',false);

		$y = $this->GetY();
		$this->SetY($y);
		$y = $this->GetY();

        $this->SetFont($fontText,'',$contentSize);
        $this->SetTextColor(0,0,0);


        $this->SetXY($x,$y);
        $this->MultiCell(204,5,$MATERIALES,'TBRL','L',false);


		$y = $this->GetY();
		$this->SetY($y+10);
		$y = $this->GetY();

		if($this->checkPageBreak(20,$y,true)) {
			$y = $this->GetY() + 5;
			$this->SetY($y);
			$y = $this->GetY();
		}

		//************************************** FIRMA *******************************************


		$nombreFirma = '';
		if($this->getDatos()['statusimg']) {

			$Firma=$this->getDatos()['firma'];

			$nombreFirma = $Firma->Nombre;

			if($Firma->Firma2 != ''){
				$this->Image('assets/files/firmas/'.$ID_EMPRESA.'/'.$ID_SUCURSAL.'/'.$Firma->Firma2,$x+75, $y,40,20);

				$y = $this->GetY();
				$this->SetY($y+15);


			}else{
				// IMAGEN BASE64
				$img_base64_encoded = 'data:image/png;base64,'.$Firma->Firma;
				$img = '<img width="100"  height="100" src="@' . preg_replace('#^data:image/[^;]+;base64,#', '', $img_base64_encoded) . '">';
				//$this->writeHTML($img, true, false, true, false, '');
				$this->setX(60);
				$this->setFillColor(99,177,80);
				$this->MultiCell(70, 20, $img, '', 'C', false, 0, '', '', true, 0, true, false, 10, 'T');

				$y = $this->GetY();
				$this->SetY($y+15);

			}
		}

		$y = $this->GetY();
		$this->SetY($y);
		$y = $this->GetY();

		if($this->checkPageBreak(5,$y,true)) {
			$y = $this->GetY() + 5;
			$this->SetY($y);
			$y = $this->GetY();
		}

        $this->SetX($x+65);

        $this->MultiCell(60,5,$nombreFirma,'0','C',false);

		$y = $this->GetY();
		$this->SetY($y);
		$y = $this->GetY();

		if($this->checkPageBreak(2,$y,true)) {
			$y = $this->GetY() + 5;
			$this->SetY($y);
			$y = $this->GetY();
		}

		// LINEA DE FIRMA
		$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(7,27,140));
		$this->Line($x+65, $y, 130, $y, $style);

        $this->SetXY($x+65,$y);
        $this->MultiCell(60,5,'Firma del Cliente','','C',false);


    }




    //Para estatus de servicio
    public function pintar_estatus($Estatus){
        switch ($Estatus) {
            case 'Operando':
                //VERDE
                $this->setFillColor(73,218,68);
                break;

            case 'En Observacion':
                //Amarrillo
                $this->setFillColor(231,235,85);
                $this->SetTextColor(0,0,0);
                break;

            case 'Fuera de Servicio':
                //ROJO
                $this->setFillColor(230,47,47);
                break;

            default:
                //ROJO
                $this->setFillColor(156,156,156);
                break;

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





