<?php
//session_start();



class Cbasepdf extends TCPDF {

    public $oDato;

	function setDatos($oDato)
	{
		//Set the array of column widths
		$this->oDato=$oDato;
	}

	function getDatos(){
		return $this->oDato;
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
	public function SheetBraker($y, $hCell, $extraY = 5, $newY = 30, $newX = 10, $cutMaxHeight = 250) {
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

		$NumCaracteres = strlen($Txt) + 5;
		$RealLong = round((floatval($widthCell)/2),0,PHP_ROUND_HALF_EVEN);

		if($NumCaracteres > $RealLong) {
			$redondear = round(($NumCaracteres/$RealLong),0,PHP_ROUND_HALF_EVEN);
			$NewHeight = ($BaseHeightCell*$redondear);
			return (floatval($NewHeight) + floatval($ajuste));

		}else {
			return $BaseHeightCell + floatval($ajuste);

		}
	}

	public function headerFactura2() {

		$imgFile 	= '';
		$img64 		= '';
		$bnd64 		= false;
		$bndFile	= false;
		$RutaLogo	= 'assets/files/logo_empresa/';
		$FOLIO_FACTURA = '';
		$TITULO		='Reporte';


		$IdEmpresa=$this->getDatos()['IdEmpresa'];
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
		}

		if(!empty($imgFile)){
			$bndFile = true;
		}

		if(!empty($img64)){
			$bnd64 = true;
		}



		$x = 10;
		$y = $this->GetY();

		//$this->SetXY($x,$y+5);
		//$this->MultiCell(190,2,'',0,'L',true);  ## LINEA DE EJEMPLO DE MARGENES

		$this->SetFillColor(150,133,202);

		$this->SetFont('','B',24);
		$this->SetTextColor(15,63,135);

		$this->SetXY($x,$y+5);
		$this->MultiCell(100,10,mb_strtoupper($TITULO,'UTF-8'),0,'L',false);

		$this->SetFont('','B',13);
		$this->SetTextColor(0,0,0);

		$this->SetXY($x,$y+17);
		$this->MultiCell(15,5,'Folio:',0,'L',false);

		if(!empty($FOLIO_FACTURA)) {
			$this->SetXY($x + 13, $y + 17);
			$this->MultiCell(70, 5, mb_strtoupper($FOLIO_FACTURA, 'UTF-8'), 0, 'L', false);
			$this->lastPage(false);
		}

		if($bndFile){
			// IMAGEN DE ARCHIVO
			$Imagen=$RutaLogo.$imgFile;
			if(file_exists($Imagen)){
				$this->Image($Imagen,$x+165,$y+5,23,23);
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
			$this->SetXY($x+165,$y+5);
			$this->MultiCell(25, 10, $img, '', 'C', false, 0, '', '', true, 0, true, false, 10, 'T');

		}else {
			// IMAGEN DEFECTO

			$Imagen='assets/ImgBase/logo_default.png';
			if(file_exists($Imagen)){
				$this->Image($Imagen,$x+165,$y+5,23,23);
			}
		}

	}
























	//***************************************** FUNCIONES VIEJAS *************************************

    public function EspacioHeaderG(){
        $this->SetY(25);
        $this->SetX(10);
        //$this->SetFont('','',9);
    }

    public function EspacioHeaderGF(){
        $this->SetY(45);
        $this->SetX(10);
        //$this->SetFont('','',9);
    }

    function HeaderG()
    {
        $IdEmpresa=$this->getDatos()['IdEmpresa'];
        $IdSucursal=$this->getDatos()['IdSucursal'];
        $Titulo='Reporte';
        if(isset($this->getDatos()['Titulo'])){
            $Titulo=$this->getDatos()['Titulo'];
        }
        //$oMsucursal=new Msucursal();
        //$oMsucursal->IdSucursal=1;//$responsedes['data']->IdSucursal;
        //$ressuc=$oMsucursal->get_sucursal();


		$RutaLogo='assets/files/logo_empresa/';
		$x = 10;
		$y = $this->GetY();
		$imgFile 	= '';
		$img64 		= '';
		$bnd64 		= false;
		$bndFile	= false;

		$oMempresa=new Mempresa();
		$oMempresa->IdEmpresa = $IdEmpresa;
		$resemp = $oMempresa->get_empresa();

		if($resemp['status']) {
			$imgFile 	= $resemp['data']->Logo;
			$img64 		= $resemp['data']->Imagen;
		}

		if(!empty($imgFile)){
			//$bndFile = true;
		}

		if(!empty($img64)){
			//$bnd64 = true;
		}


		if($bndFile){
			// IMAGEN DE ARCHIVO
			$Imagen=$RutaLogo.$imgFile;
			if(file_exists($Imagen)){
				$this->Image($Imagen,7,0,20,20);
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
			$this->SetXY(5,0);
			$this->MultiCell(20, 20, $img, '', 'C', false, 0, '', '', true, 0, true, false, 10, 'T');

		}else {
			// IMAGEN DEFECTO

			$Imagen='assets/ImgBase/logo_default.png';
			if(file_exists($Imagen)){
				$this->Image($Imagen,7,0,20,20);
			}
		}

        $this->SetFillColor(95,133,202);
        $this->SetFont('','B',20);
        $this->SetXY(0,0);
        $this->MultiCell(210,20,'',0,'L',true);

        $this->SetTextColor(255,255,255);
        $y = 5;
        $x=$this->GetX();

        $this->SetXY($x+20,$y);
        $this->MultiCell(100,20,$Titulo,0,'L',false);

        if($this->getDatos()['Folio']!=''){
			//$this->SetFillColor(100,100,202);
            $this->SetFont('','',14);
            $this->writeHTMLCell(60,4,$x+135,$y+2,'Folio: <strong>'.$this->getDatos()['Folio'].'</strong>',0,0,false);
            $this->lastPage(true);
        }

        $this->SetTextColor(0,0,0);

    }

    public function FooterG($Pagina,$Titulo)
    {
        $this->SetFont('','',8);
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        // Page number}
        $y=$this->GetY()+4;
        $this->SetXY(90,$y);
        $this->MultiCell(35, 5,$Pagina, 0);

        /*
        $this->SetTextColor(80,100,137);
        $this->SetFont('','B',12);
        $this->SetXY(150,$y);
        $this->MultiCell(50, 5,$Titulo, 0,'R');*/

        //$this->SetAlpha(.1);
       //$this->Image('assets/img/imisa.png',20, 70,168,'', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    }

	public function FooterGF($Pagina,$Titulo)
	{
		$this->SetFont('','',8);
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		// Page number}
		$y=$this->GetY()+4;
		$this->SetXY(135,$y);
		$this->MultiCell(35, 5,$Pagina, 0);

		/*
		$this->SetTextColor(80,100,137);
		$this->SetFont('','B',12);
		$this->SetXY(150,$y);
		$this->MultiCell(50, 5,$Titulo, 0,'R');*/

		//$this->SetAlpha(.1);
		//$this->Image('assets/img/imisa.png',20, 70,168,'', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
	}

    //PARA FINANCIERO
    function HeaderGF()
    {
        $IdEmpresa=$this->getDatos()['IdEmpresa'];
        $IdSucursal=$this->getDatos()['IdSucursal'];
        $Titulo='Reporte';
        if(isset($this->getDatos()['Titulo'])){
            $Titulo=$this->getDatos()['Titulo'];
        }

        $Cliente='';
        if(isset($this->getDatos()['Cliente'])){
            $Cliente=$this->getDatos()['Cliente'];
        }

        $ClienteSucursal='';
        if(isset($this->getDatos()['ClienteSucursal'])){
            $ClienteSucursal=$this->getDatos()['ClienteSucursal'];
        }

        $Anio='';
        if(isset($this->getDatos()['Anio'])){
            $Anio=$this->getDatos()['Anio'];
        }

        $TipoServicio='';
        if(isset($this->getDatos()['TipoServicio'])){
            $TipoServicio=$this->getDatos()['TipoServicio'];
        }


        $oMempresa=new Mempresa();
        $oMempresa->IdEmpresa=$IdEmpresa;
        $resemp=$oMempresa->get_empresa();
        $Logo=$resemp['data']->Logo;

        $oMsucursal=new Msucursal();
        $oMsucursal->IdSucursal=$this->getDatos()['IdSucursal'];
        $ressuc=$oMsucursal->get_sucursal();
        $Sucursal=$ressuc['data']->Nombre;

        $RutaLogo='assets/files/logo_empresa/'.$IdEmpresa.'/';

        if($Logo!=''){
            $Imagen=$RutaLogo.$Logo;
            if(file_exists($Imagen)){
                $this->Image($Imagen,7,3,35,20);
            }
        }

        $this->SetFillColor(95,133,202);
        $this->SetFont('','B',20);
        $this->SetXY(0,0);
        $this->MultiCell(300,25,'',0,'L',true);

        $this->SetTextColor(255,255,255);
        $y = 5;
        $x=$this->GetX();

        $this->SetXY($x+30,$y);
        $this->MultiCell(230,5,$Logo,0,'C',false);

        $this->SetFont('','B',16);
        $y=$this->GetY();
        $this->SetXY($x+30,$y);
        $this->MultiCell(230,5,$Sucursal,0,'C',false);

        $this->SetTextColor(0,0,0);

        $y=$this->GetY()+10;
        $y2=$this->GetY()+10;

        $this->SetFont('','B',11);
        $this->RoundedRect($x,$y,4,4,2,'0','F','',array(120,165,242));
        $this->SetXY($x+5,$y);
        $this->MultiCell(22,5,'Cliente:',0,'L',false);

        $this->SetXY($x+26,$y);
        $this->MultiCell(150,5,$Cliente,0,'L',false);

        $y=$this->GetY()+2;
        $this->SetFont('','B',11);
        $this->RoundedRect($x,$y,4,4,2,'0','F','',array(120,165,242));
        $this->SetXY($x+5,$y);
        $this->MultiCell(22,5,'Sucursal:',0,'L',false);

        $this->SetXY($x+26,$y);
        $this->MultiCell(150,5,$ClienteSucursal,0,'L',false);


        $this->SetFont('','B',11);
        $this->SetXY($x+195,$y2);
        $this->MultiCell(80,5,$Anio,0,'R',false);
        $y2=$this->GetY()+2;

        $this->SetFont('','B',11);
        $this->SetXY($x+195,$y2);
        $this->MultiCell(80,5,$TipoServicio,0,'R',false);

    }

    //Para estatus de servicio
    function pintar_estatus($Estatus){
        if($Estatus=='Operando'){//VERDE
            $this->setFillColor(99,177,80);
        }else if($Estatus=='En Observacion'){//Amarrillo
            $this->setFillColor(231,235,85);
        }else if ($Estatus=='Fuera de Servicio') {//ROJO
            $this->setFillColor(230,47,47);
        }
        else  {//ROJO
            $this->setFillColor(156,156,156);
        }
    }


    public function dasto_servicioG(){

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
        $this->MultiCell(95,5,$this->getDatos()['servicio']->Client,0,'L',false);
        $y=$this->GetY();

        $this->SetFont('','B',10);
        $this->SetXY($x,$y);
        $this->MultiCell(20,5,'Sucursal:',0,'L',false);

        $this->SetFont('','',10);
        $this->SetXY($x+20,$y);
        $this->MultiCell(95,5,$this->getDatos()['sucursal']->Nombre,0,'L',false);
        $y=$this->GetY();

        $this->SetFont('','B',10);
        $this->SetXY($x,$y);
        $this->MultiCell(95,5,'Dirección:',0,'L',false);

        $this->SetFont('','',10);
        $this->SetXY($x+20,$y);
        $this->MultiCell(95,5,$this->getDatos()['servicio']->Direccion,0,'L',false);
        $y=$this->GetY();


        $this->SetFont('','B',10);
        $this->SetXY($x,$y);
        $this->MultiCell(20,5,'Contacto:',0,'L',false);

        $this->SetFont('','',10);
        $this->SetXY($x+20,$y);
        $this->MultiCell(95,5,$this->getDatos()['servicio']->Contacto,0,'L',false);
        $y=$this->GetY();

        #***************Columna 2**************
        $this->SetFont('','B',10);
        $this->SetXY($x+120,$y2);
        $this->MultiCell(20,5,'Fecha:',0,'L',false);

        $this->SetFont('','',10);
        $this->SetXY($x+135,$y2);
        $this->MultiCell(65,5,date('d-m-Y',strtotime($this->getDatos()['fechaservicio']->FechaInicio)),0,'L',false);
        $y2=$this->GetY();

        $this->SetFont('','B',10);
        $this->SetXY($x+120,$y2);
        $this->MultiCell(30,5,'Hora de inicio:',0,'L',false);

        $this->SetFont('','',10);
        $this->SetXY($x+145,$y2);
        $this->MultiCell(55,5,substr($this->getDatos()['fechaservicio']->HoraInicio,0,-3),0,'L',false);
        $y2=$this->GetY();

        #
        $this->SetFont('','B',10);
        $this->SetXY($x+120,$y2);
        $this->MultiCell(30,5,'Hora final:',0,'L',false);

        $this->SetFont('','',10);
        $this->SetXY($x+145,$y2);
        $this->MultiCell(55,5,substr($this->getDatos()['fechaservicio']->HoraFin,0,-3),0,'L',false);
        $y2=$this->GetY();


        $this->SetFont('','B',10);
        $this->SetXY($x+120,$y2);
        $this->MultiCell(30,5,'Responsable:',0,'L',false);

        $this->SetFont('','',10);
        $this->SetXY($x+145,$y2);
        $this->MultiCell(55,5,$this->getDatos()['personal']->Nombre,0,'L',false);
        $y2=$this->GetY();



        $this->SetFont('','B',10);
        $this->SetXY($x+120,$y2);
        $this->MultiCell(30,5,'Telefono:',0,'L',false);

        $this->SetFont('','',10);
        $this->SetXY($x+145,$y2);
        $this->MultiCell(55,5,$this->getDatos()['sucursal']->Telefono,0,'L',false);
        $y2=$this->GetY();

        $this->SetFont('','B',10);
        $this->SetXY($x+120,$y2);
        $this->MultiCell(30,5,'Contrato:',0,'L',false);

        $this->SetFont('','',10);
        $this->SetXY($x+145,$y2);
        $this->MultiCell(55,5,$this->getDatos()['servicio']->NumContrato,0,'L',false);
        $y2=$this->GetY();
        #

        // $this->SetFont('','B',10);
        // $this->SetXY($x+120,$y2);
        // $this->MultiCell(20,5,'Hora final:',0,'L',false);

        // $this->SetFont('','',10);
        // $this->SetXY($x+140,$y2);
        // $this->MultiCell(70,5,substr($this->getDatos()['fechaservicio']->HoraFin,0,-3),0,'L',false);
        // $y2=$this->GetY()+5;

        // $this->SetFont('','B',10);
        // $this->SetXY($x+120,$y2);
        // $this->MultiCell(20,5,'Teléfono :',0,'L',false);

        // $this->SetFont('','',10);
        // $this->SetXY($x+138,$y2);
        // $this->MultiCell(70,5,$this->getDatos()['sucursal']->Telefono,0,'L',false);
        // $y2=$this->GetY();

        $this->SetY(max($y,$y2));
    }

    public function HeaderDetalleG($array,$x,$y,$hh,$bg,$fg,$ag='L'){
        $b=$bg;$f=$fg;$a=$ag;
        foreach($array as $elemento){

            $texto=$elemento['texto'];
            $w=$elemento['w'];

            if(isset($elemento['a'])){
                $a=$elemento['a'];
            }

            if(isset($elemento['f'])){
                $f=$elemento['f'];
            }

            $this->SetXY($x,$y);
            $this->MultiCell($w,$hh,$texto,$b,$a,$f);

            $x=$x+$w;

            #Reestablecemos datos
            $b=$bg;$f=$fg;$a=$ag;
        }
    }

    public function DetalleG($array,$x,$y,$hh,$bg,$fg,$ag='L',$TypeBrinco=1){
        $AltoCell=$this->getMaxLineHG($array,$hh);
          // echo $AltoCell.'.---';

          $b=$bg;$f=$fg;$a=$ag;
        foreach($array as $elemento){

            $texto=$elemento['texto'];
            $w=$elemento['w'];

            if(isset($elemento['a'])){
                $a=$elemento['a'];
            }

            if(isset($elemento['f'])){
                $f=$elemento['f'];
            }


             $isreturn=$this->checkPageBreak($AltoCell,$y,true);
             if($isreturn){
                $ybrinco=0;
                if($TypeBrinco==1){
                    $this->EspacioHeaderG();
                }else{
                    $this->EspacioHeaderGF();
                    $ybrinco=5;
                }

                $x=$this->GetX();
                $y=$this->GetY()+$ybrinco;

             }

            if($b!=''){
                $this->Rect($x, $y, $w, $AltoCell,'','');
            }

            $this->SetXY($x,$y);
            $this->MultiCell($w,$hh,$texto,0,$a,$f);
            #Linea de separacion salto
            $this->SetY($y+$AltoCell);
            //$this->MultiCell(190,$hh,'',0,$a,$f);

            $x=$x+$w;

            #Reestablecemos datos
            $b=$bg;$f=$fg;$a=$ag;
        }
    }

    function getMaxLineHG($array,$hh){
        $hcell=array();
        foreach($array as $elemento){
            $NLine=0;
            $NLine=$this->getNumLines($elemento['texto'],$elemento['w']);
            $hcell[]=$NLine*$hh;
        }

        $htotal=max($hcell);

        if($htotal<$hh){
            $htotal=$hh;
        }

        return $htotal;
    }


    function HeaderDetalleF($Titulo){
        $y=$this->GetY()+4;
        $x=10;

        $array=array(['texto'=>'','w'=>100],['texto'=>'AÑO PASADO','w'=>35,'a'=>'C'],['texto'=>'AÑO ACTUAL','w'=>70,'a'=>'C'],['texto'=>'MES ACTUAL','w'=>70,'a'=>'C']);
        $this->SetFillColor(82,134,223);
        $this->SetTextColor(0,0,0);
        $hh=5;$b=0;$f=true;
        $this->HeaderDetalleG($array,$x,$y,$hh,$b,false);

        $y=$this->GetY();
        $array=array(['texto'=>$Titulo,'w'=>100]);
        $this->HeaderDetalleG($array,$x,$y,$hh,$b,false);

        $hh=7;
        $this->SetFillColor(0,137,123);
        $this->SetTextColor(255,255,255);
        $array=array(['texto'=>'Actual ','w'=>35]);
        $this->HeaderDetalleG($array,$x+100,$y,$hh,$b,true,'C');

        $this->SetFillColor(67,160,71);

        $array=array(['texto'=>'Plan','w'=>35]);
        $this->HeaderDetalleG($array,$x+135,$y,$hh,$b,true,'C');
        $array=array(['texto'=>'Actual','w'=>35]);
        $this->HeaderDetalleG($array,$x+170,$y,$hh,$b,true,'C');

        $this->SetFillColor(66,165,245);
        $array=array(['texto'=>'Plan','w'=>35]);
        $this->HeaderDetalleG($array,$x+205,$y,$hh,$b,true,'C');
        $array=array(['texto'=>'Actual','w'=>35]);
        $this->HeaderDetalleG($array,$x+240,$y,$hh,$b,true,'C');
    }


    function HeaderDetalleF2($Titulo){
        $y=$this->GetY()+4;
        $x=10;

        $array=array(['texto'=>'','w'=>55],['texto'=>'AÑO PASADO','w'=>45,'a'=>'C'],['texto'=>'AÑO ACTUAL','w'=>90,'a'=>'C'],['texto'=>'MES ACTUAL','w'=>90,'a'=>'C']);
        $this->SetFillColor(82,134,223);
        $this->SetTextColor(0,0,0);
        $hh=5;$b=0;$f=true;
        $this->HeaderDetalleG($array,$x,$y,$hh,$b,false);

        $y=$this->GetY();
        $array=array(['texto'=>$Titulo,'w'=>50]);
        $this->HeaderDetalleG($array,$x,$y,$hh,$b,false);

        $b=0;
        $hh=7;
        $this->SetFillColor(0,137,123);
        $this->SetTextColor(255,255,255);
        $array=array(['texto'=>'Actual ','w'=>30]);
        $this->HeaderDetalleG($array,$x+55,$y,$hh,$b,true,'C');

        $array=array(['texto'=>'% ','w'=>15]);
        $this->HeaderDetalleG($array,$x+85,$y,$hh,$b,true,'C');


        $this->SetFillColor(67,160,71);
        $array=array(['texto'=>'Plan','w'=>30]);
        $this->HeaderDetalleG($array,$x+100,$y,$hh,$b,true,'C');

        $array=array(['texto'=>'% ','w'=>15]);
        $this->HeaderDetalleG($array,$x+130,$y,$hh,$b,true,'C');


        $array=array(['texto'=>'Actual','w'=>30]);
        $this->HeaderDetalleG($array,$x+145,$y,$hh,$b,true,'C');

        $array=array(['texto'=>'% ','w'=>15]);
        $this->HeaderDetalleG($array,$x+175,$y,$hh,$b,true,'C');

        $this->SetFillColor(66,165,245);
        $array=array(['texto'=>'Plan','w'=>30]);
        $this->HeaderDetalleG($array,$x+190,$y,$hh,$b,true,'C');

        $array=array(['texto'=>'% ','w'=>15]);
        $this->HeaderDetalleG($array,$x+220,$y,$hh,$b,true,'C');

        $array=array(['texto'=>'Actual','w'=>30]);
        $this->HeaderDetalleG($array,$x+235,$y,$hh,$b,true,'C');

        $array=array(['texto'=>'% ','w'=>15]);
        $this->HeaderDetalleG($array,$x+265,$y,$hh,$b,true,'C');
    }
}

?>
