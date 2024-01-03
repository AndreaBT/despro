<?php
//require_once('../TCPDF-master/tcpdf.php');

class ServicioEvidencia extends Cbasereport
{
	function Header()
	{
		$FOLIO_SERVICIO 	= ''; //'SERV-XXXX';
		$TITULO_DOCUMENTO  	= 'REPORTE FOTOGRÁFICO';
		$imgFile 			= '';
		$img64 				= '';
		$bnd64 				= false;
		$bndFile			= false;
		$RutaLogo			= 'assets/files/logo_empresa/';

		if($this->getDatos()['Folio']!=''){
			$FOLIO_SERVICIO = $this->getDatos()['Folio'];
		}

		$IdEmpresa=$this->getDatos()['IdEmpresa'];

		$oMempresa=new Mempresa();
		$oMempresa->IdEmpresa = $IdEmpresa;
		$resemp = $oMempresa->get_empresa();

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

		$x = 0;
		$y = 0;


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
			if(file_exists($Imagen)){
				$this->Image($Imagen,$x+1,$y+2,25,25, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
			}
		}





		$this->SetFillColor(7,27,140);
		$this->SetDrawColor(0,0,0);
		$this->SetLineWidth(0.3);

		$this->SetTextColor(7,27,140);

		$this->SetFont('robotocondensed','',26);
		$this->SetXY($x+44,16.5);
		$this->MultiCell(105,14,$TITULO_DOCUMENTO,'','C',false,1,'','',true,0,false,true,14,'M');

		## LINE VERTICAL
		$style2 = array('width' => 2.8, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(7,27,140));
		$this->Line($x+155, $y+1.4, $x+155, $y+24.4, $style2);

		#RECUADRO AZUL FOLIO
		$this->SetXY($x+162.5,0);
		$this->MultiCell(53.5,25.5,'','','L',true,1,'','',true,0,false,true,30,'M');

		#FOLIO
		$this->SetTextColor(255,255,255);
		$this->SetFont('robotocondensed','',18);

		$this->SetXY($x+167,4);
		$this->MultiCell(35,10,'FOLIO:','','L',false,1,'','',true,0,false,true,10,'M');

		$this->SetXY($x+167,12);
		$this->MultiCell(40,10,$FOLIO_SERVICIO,'TB','L',false,1,'','',true,0,false,true,10,'M');

		#LINEA SIMPLE INFERIOR
		$y = 26;
		$style = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(7,27,140));
		$this->Line($x, $y+3, 216, $y+3, $style);
	}

	function Footer (){


		$Pagina = $this->getAliasNumPage().' | '.$this->getAliasNbPages();

		$this->SetFont('','',8);
		// Position at 10 mm from bottom
		$this->SetY(-10);
		/*$y=$this->GetY();
		$this->SetXY(0,$y);
		$this->MultiCell(200,30,'','T','L',false,1,'','',true,0,false,true,10,'M');*/

		$y=$this->GetY();
		$this->SetXY(0,$y);
		//$this->MultiCell(35, 5,$Pagina, 0);
		$this->MultiCell(216,10,$Pagina,'','C',false,1,'','',true,0,false,true,10,'M');

	}

	function contenido(){

		$ID_EMPRESA		= $this->getDatos()['IdEmpresa'];
		$ID_SUCURSAL	= $this->getDatos()['IdSucursal'];
		$ID_SERVICIO	= $this->getDatos()['servicio']->IdServicio;
		$SERVICIO       = $this->getDatos()['servicio']->Servicio;   //'Tiempo muerto';
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
		$TEAM_SERVICIOS = $this->getDatos()['row'];
		$GLOBAL_SERVICE = $this->getDatos()['row2'];

		//print_r($TEAM_SERVICIOS);
		//print_r($GLOBAL_SERVICE);
		///**************************** DOCUMENTO ******************************/

		$this->AddPage();
		$y = 10;
		$x = 6;
		$titleSize = 13;
		$titleBoxSize = 26;
		$XPlusText = 29;
		$contentSize = 10;
		$fontTitle = 'robotocondensed';
		$fontText = 'robotocondensedlight';

		$y=$this->GetY();
		$y2=$this->GetY();


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
		$this->MultiCell(70,10,$SERVICIO,'','L',false,1,'','',true,0,false,true,10,'M');


		$y=$this->GetY()+0.5;

		$this->SetTextColor(7,27,140);
		$this->SetFont($fontTitle,'',$titleSize);

		$this->SetXY($x,$y);
		$this->MultiCell($titleBoxSize,10,'CLIENTE:','','L',true,1,'','',true,0,false,true,10,'M');

		$this->SetFont($fontText,'',$contentSize);
		$this->SetTextColor(0,0,0);

		$this->SetXY($x+$XPlusText,$y);
		$this->MultiCell(70,10,$CLIENTE,'','L',false,1,'','',true,0,false,true,10,'M');

		$y=$this->GetY()+0.5;

		$this->SetTextColor(7,27,140);
		$this->SetFont($fontTitle,'',$titleSize);

		$this->SetXY($x,$y);
		$this->MultiCell($titleBoxSize,10,'SUCURSAL:','','L',true,1,'','',true,0,false,true,10,'M');

		$this->SetFont($fontText,'',$contentSize);
		$this->SetTextColor(0,0,0);

		$this->SetXY($x+$XPlusText,$y);
		$this->MultiCell(70,10,$SUCURSAL,'','L',false,1,'','',true,0,false,true,10,'M');

		$y=$this->GetY()+0.5;

		$this->SetTextColor(7,27,140);
		$this->SetFont($fontTitle,'',$titleSize);

		$this->SetXY($x,$y);
		$this->MultiCell($titleBoxSize,10,'DIRECCIÓN:','','L',true,1,'','',true,0,false,true,10,'M');

		$this->SetFont($fontText,'',$contentSize);
		$this->SetTextColor(0,0,0);

		$this->SetXY($x+$XPlusText,$y);
		$this->MultiCell(70,10,$DIRECCION,'','L',false,1,'','',true,0,false,true,10,'M');

		$y=$this->GetY()+0.5;

		$this->SetTextColor(7,27,140);
		$this->SetFont($fontTitle,'',$titleSize);

		$this->SetXY($x,$y);
		$this->MultiCell($titleBoxSize,10,'CONTACTO:','','L',true,1,'','',true,0,false,true,10,'M');

		$this->SetFont($fontText,'',$contentSize);
		$this->SetTextColor(0,0,0);

		$this->SetXY($x+$XPlusText,$y);
		$this->MultiCell(70,10,$CONTACTO,'','L',false,1,'','',true,0,false,true,10,'M');

		$y=$this->GetY();



		#***************Columna 2**************
		$this->SetFont($fontTitle,'',$titleSize);
		$this->SetTextColor(7,27,140);

		$this->SetXY($x+112,$y2);
		$this->MultiCell(25,10,'FECHA:','','L',true,1,'','',true,0,false,true,10,'M');

		$this->SetFont($fontText,'',$contentSize);
		$this->SetTextColor(0,0,0);

		$this->SetXY($x+141,$y2);
		$this->MultiCell(20,10,date('d-m-Y',strtotime($FECHA)),'','L',false,1,'','',true,0,false,true,10,'M');


		$y2=$this->GetY()+0.5;

		$this->SetFont($fontTitle,'',$titleSize);
		$this->SetTextColor(7,27,140);

		$this->SetXY($x+112,$y2);
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

		$this->SetXY($x+183,$y2);
		$this->MultiCell(25,10,substr($HORA_FIN,0,-3),'','C',false,1,'','',true,0,false,true,10,'M');

		$y2=$this->GetY()+0.5;


		$this->SetFont($fontTitle,'',$titleSize);
		$this->SetTextColor(7,27,140);

		$this->SetXY($x+112,$y2);
		//$this->MultiCell(30,5,'RESPONS:',0,'L',false);
		$this->MultiCell(25,10,'RESPONS:','','L',true,1,'','',true,0,false,true,10,'M');

		$this->SetFont($fontText,'',$contentSize);
		$this->SetTextColor(0,0,0);

		$this->SetXY($x+141,$y2);
		$this->MultiCell(60,10,$RESPONSABLE,'','L',false,1,'','',true,0,false,true,10,'M');

		$y2=$this->GetY()+0.5;



		$this->SetFont($fontTitle,'',$titleSize);
		$this->SetTextColor(7,27,140);

		$this->SetXY($x+112,$y2);
		//$this->MultiCell(30,5,'TELÉFONO:',0,'L',false);
		$this->MultiCell(25,10,'TELÉFONO:','','L',true,1,'','',true,0,false,true,10,'M');

		$this->SetFont($fontText,'',$contentSize);
		$this->SetTextColor(0,0,0);

		$this->SetXY($x+141,$y2);
		$this->MultiCell(45,10,$TELEFONO,'','L',false,1,'','',true,0,false,true,10,'M');
		$y2=$this->GetY()+0.5;

		$this->SetFont($fontTitle,'',$titleSize);
		$this->SetTextColor(7,27,140);

		$this->SetXY($x+112,$y2);
		//$this->MultiCell(30,5,'CONTRATO:',0,'L',false);
		$this->MultiCell(25,10,'CONTRATO:','','L',true,1,'','',true,0,false,true,10,'M');


		$this->SetFont($fontText,'',$contentSize);
		$this->SetTextColor(0,0,0);

		$this->SetXY($x+141,$y2);
		$this->MultiCell(60,10,$CONTRATO,'','L',false,1,'','',true,0,false,true,10,'M');

		$y2=$this->GetY();


		//$this->Image('img/tecnico.jpg', $x, $y2, 48, 48, '', '', 'T', false, 300, '', false, false, 1, false, false, false);

		if(count($TEAM_SERVICIOS) == 0 && count($GLOBAL_SERVICE) == 0){
			$this->SetXY(0,$y2+10);
			$this->MultiCell(210,10,'NO EXISTE EVIDENCIAS DEL SERVICIO PARA MOSTRAR.','','C',false,1,'','',true,0,false,true,10,'M');
		}


		//desprosoft_4.0
		$ruta = 'assets/files/servicios/'.$ID_EMPRESA.'/'.$ID_SUCURSAL.'/'.$ID_SERVICIO.'/'; //$rutaF1

		//busca en ndlsoft
		#//$ruta2 = 'https://ndlsoft.com/control/reportefoto/'.$ID_SERVICIO.'/'; // $rutaF2
		$ruta2 = returnLinkFotosServicios().$ID_SERVICIO.'/'; // $rutaF2


		/**************************************** SERVICIOS POR EQUIPO **************************************/

		$this->SetY($this->GetY()+3);

        $y = $this->GetY();
        $this->SetY($y);
        $y = $this->GetY();

		for($i=0; $i < count($TEAM_SERVICIOS); $i++)
        {


           ## INFORMACION DEL EQUIPO
           $y1 = $y;

           if($y1 > 250) {
               if($this->checkPageBreak($y1+11, $y1,true)) {
                   $this->SetY(35);
                   $this->SetX(6);
                   $y1 = 35;
               }
           }


            ##NOMBRE DE EQUIPO
            $this->pintar_estatus($TEAM_SERVICIOS[$i]->Status);
            $this->SetFont($fontTitle, '', 20);
            $this->SetTextColor(255, 255, 255);
            $this->SetXY($x + 17, $y1 + 1);
            $this->MultiCell(100, 10, $TEAM_SERVICIOS[$i]->Nequipo, '', 'C', true, 1, '', '', true, 0, false, true, 10, 'M');

            $y = $this->GetY();
            $this->SetXY(6,$y+5);
            $y2 = $this->GetY();


            ## LISTADO DE TODAS LAS IMAGENES
            $IMAGENES   = $TEAM_SERVICIOS[$i]->Imagenes;
            $contCards 	= 1;

            $Img1 = '';
            $Img2 = '';
            $Img3 = '';

            $Des1 = '';
            $Des2 = '';
            $Des3 = '';
            $ContFinImgs = count($IMAGENES);

            for ($z = 0; $z < count($IMAGENES); $z++) {


                $y2 = $y2;
                $this->SetY($y2);
                $y2 = $this->GetY();

                //$url = 'img/'.$IMAGENES[$z]['Imagen'];

                $url = 'assets/files/iconemp/Casa.png';

                // Buscar en carpeta de archivos locales
                if ($IMAGENES[$z]->Tipo == 1) { $url = $ruta . $IMAGENES[$z]->Imagen; }

                // si no existe en local busque en ndlsfot
                if ($IMAGENES[$z]->Tipo == 0) { $url = $ruta2 . $IMAGENES[$z]->Imagen; }


                switch ($contCards) {
                    case 1:
                        $Img1 = $url;
                        $Des1 = $IMAGENES[$z]->Descripcion2;
                        break;
                    case 2:
                        $Img2 = $url;
                        $Des2 = $IMAGENES[$z]->Descripcion2;
                        break;
                    case 3:
                        $Img3 = $url;
                        $Des3 = $IMAGENES[$z]->Descripcion2;
                        break;
                }

                $showContent = false;

                if ( $z == ($ContFinImgs - 1) && $contCards < 3 ) {

                    $showContent = true;

                }else if ($contCards == 3) {

                    $showContent = true;

                } else {

                    $contCards++;
                }

                if($showContent) {
                        $html = '<table style="margin: auto;">

                            <tr>
                                <td style="width: 200px;">
                                    <img src="'.$Img1.'" alt="" style="width: 300px; height: 300px; border: 5px solid #071B8C;">
                                </td>
                                <td style="width: 200px;">
                                    <img src="'.$Img2.'" alt="" style="width: 300px; height: 300px; border: 5px solid #071B8C;">
                                </td>
                                <td style="width: 200px;">
                                    <img src="'.$Img3.'" alt="" style="width: 300px; height: 300px; border: 5px solid #071B8C;">
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; text-align: left; font-size: 12px; color: #071B8C;">'.$Des1.'</td>
                                <td style="vertical-align: top; text-align: left; font-size: 12px; color: #071B8C;">'.$Des2.'</td>
                                <td style="vertical-align: top; text-align: left; font-size: 12px; color: #071B8C;">'.$Des3.'</td>
                            </tr>
                
                        </table>
                        <br>';

                    //$this->writeHTML($html, true, false, true, false, '');
                    $this->SetFont($fontText,'',$contentSize);
                    $this->SetFillColor(100,100,100);
                    $this->SetXY($x + 12, $y2);
                    $this->MultiCell(174, 10, $html, '', 'C', false, 1, '', '', true, 0, true, true);


                    $y2 = $this->GetY();
                    $this->SetY($y2);
                    $y2 = $this->GetY();


                    $Img1 = '';
                    $Img2 = '';
                    $Img3 = '';

                    $Des1 = '';
                    $Des2 = '';
                    $Des3 = '';
                    $contCards = 1;
                    $html = '';
                }
            }

            $y = $this->GetY();
            $this->SetXY(6,$y);

            $y = $this->GetY();

        }





		/**************************************** SERVICIOS **************************************/

		//echo '<pre>';
		//print_r($GLOBAL_SERVICE);
		//echo '</pre>';


		$y = $this->GetY();
        $this->SetY($y);
        $y = $this->GetY();

        if($y > 250){
            if($this->checkPageBreak($y+11, $y,true)){
                $this->SetY(35);
                $this->SetX(6);
                $y = 35;
            }
        }

		if (count($GLOBAL_SERVICE) > 0){
        ## TITULO GENERICO DE LA SECCION
        $this->setFillColor(185,185,185); // GRIS
        $this->SetFont($fontTitle, '', 20);
        $this->SetTextColor(255, 255, 255);
        $this->SetXY($x + 17, $y + 1);
        $this->MultiCell(90, 10, 'IMAGENES DEL SERVICIO', '', 'C', true, 1, '', '', true, 0, false, true, 10, 'M');



        $y = $this->GetY();
        $this->SetY($y+5);
        $y = $this->GetY();

        $contCards = 1;

        $Img1 = '';
        $Img2 = '';
        $Img3 = '';

        $Des1 = '';
        $Des2 = '';
        $Des3 = '';
        $ContFinImgs = count($GLOBAL_SERVICE);


        for($i=0; $i < count($GLOBAL_SERVICE); $i++)
        {
           ## INFORMACION DEL EQUIPO
           $y1 = $y;

           if($y1 > 250){
               if($this->checkPageBreak($y1+11, $y1,true)){
                   $this->SetY(35);
                   $this->SetX(6);
                   $y1 = 35;
               }
           }

            $y1 = $this->GetY();
            $this->SetXY(6,$y1);
            $y1 = $this->GetY();




            $url = 'assets/files/iconemp/Casa.png';

            if ($GLOBAL_SERVICE[$i]->Tipo == 1) { $url = $ruta . $GLOBAL_SERVICE[$i]->Imagen; }

            if ($GLOBAL_SERVICE[$i]->Tipo == 0) { $url = $ruta2 . $GLOBAL_SERVICE[$i]->Imagen; }

            switch ($contCards) {
                case 1:
                    $Img1 = $url;
                    $Des1 = $GLOBAL_SERVICE[$i]->Descripcion2;
                    break;

                case 2:
                    $Img2 = $url;
                    $Des2 = $GLOBAL_SERVICE[$i]->Descripcion2;
                    break;

                case 3:
                    $Img3 = $url;
                    $Des3 = $GLOBAL_SERVICE[$i]->Descripcion2;
                    break;
            }


                $showContent =  false;

                if ( $i == ($ContFinImgs - 1) && $contCards < 3 ) {

                    $showContent =  true;
                }else if ($contCards == 3) {

                    $showContent =  true;
                } else {

                    $contCards++;
                }

                if ($showContent) {
                    $html = '<table style="margin: auto;">

                                <tr>
                                    <td style="width: 200px;">
                                        <img src="'.$Img1.'" alt="" style="width: 300px; height: 300px; border: 5px solid #071B8C;">
                                    </td>
                                    <td style="width: 200px;">
                                        <img src="'.$Img2.'" alt="" style="width: 300px; height: 300px; border: 5px solid #071B8C;">
                                    </td>
                                    <td style="width: 200px;">
                                        <img src="'.$Img3.'" alt="" style="width: 300px; height: 300px; border: 5px solid #071B8C;">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: top; text-align: left; font-size: 12px; color: #071B8C;">'.$Des1.'</td>
                                    <td style="vertical-align: top; text-align: left; font-size: 12px; color: #071B8C;">'.$Des2.'</td>
                                    <td style="vertical-align: top; text-align: left; font-size: 12px; color: #071B8C;">'.$Des3.'</td>
                                </tr>
                    
                            </table>
                            <br>';

                    //$this->writeHTML($html, true, false, true, false, '');
					$this->SetFont($fontText,'',$contentSize);
                    $this->SetFillColor(100,100,100);
                    $this->SetXY($x + 12, $y1);
                    $this->MultiCell(174, 10, $html, '', 'C', false, 1, '', '', true, 0, true, true);


                    $y1 = $this->GetY();
                    $this->SetY($y1);
                    $y = $this->GetY();


                    $Img1 = '';
                    $Img2 = '';
                    $Img3 = '';

                    $Des1 = '';
                    $Des2 = '';
                    $Des3 = '';
                    $contCards = 1;
                    $html = '';

                    //echo '<pre>';
                    //echo $html;
                    //echo '</pre>';
                }


        }
		}



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



