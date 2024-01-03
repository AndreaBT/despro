<?php
//session_start();

class RptSueldosPersonalOp extends Cbasereport {

	#*****************LEER********************
	#*****************LEER********************
	#EN funciones helper se crea una tabla con header recibe parametros de (header,styleheader,detalle);


	function Header()
	{

		#********************* IMAGEN DE CABECERA ********************
		$ImgDefault = 'assets/FinancieroPdf/header.png';

		#********************* DATOS DEL CLIENTE - SUCURSAL ********************
		$IdEmpresa=$this->getDatos()['IdEmpresa'];
		$IdSucursal=$this->getDatos()['IdSucursal'];

		$datos=$this->getDatos()['HeadConfig'];

		$Anio 	= $datos['Anio'];
		$month 	= $datos['Mes'];

		$Mes='';
		switch ($month){
			case 1:
				$Mes="Enero";
				break;
			case 2:
				$Mes="Febrero";
				break;
			case 3:
				$Mes="Marzo";
				break;
			case 4:
				$Mes="Abril";
				break;
			case 5:
				$Mes="Mayo";
				break;
			case 6:
				$Mes="Junio";
				break;
			case 7:
				$Mes="Julio";
				break;
			case 8:
				$Mes="Agosto";
				break;
			case 9:
				$Mes="Sept";
				break;
			case 10:
				$Mes="Oct";
				break;
			case 11:
				$Mes="Nov";
				break;
			case 412:
				$Mes="Dic";
				break;
		}



		$oMempresa=new Mempresa();
		$oMempresa->IdEmpresa=$IdEmpresa;
		$resemp=$oMempresa->get_empresa();
		$Logo=$resemp['data']->Logo;
		$NombreEmpresa=$resemp['data']->Nombre;

		$oMsucursal=new Msucursal();
		$oMsucursal->IdSucursal=$this->getDatos()['IdSucursal'];
		$ressuc=$oMsucursal->get_sucursal();
		$Sucursal=$ressuc['data']->Nombre;

		//$RutaLogo='assets/files/logo_empresa/'.$IdEmpresa.'/';
		$RutaLogo='assets/files/logo_empresa/';

		$y = 0;
		$x=$this->GetX();



		#********************* MAQUETADO DEL PDF ********************


		$this->SetTextColor(255,255,255);

		#IMAGEN DE CABECERA
		$this->Image($ImgDefault,0, $y,300,27);

		#SUCURSAL
		$this->SetFont('','B',14);
		$this->SetXY($x+155,$y+6);
		$this->MultiCell(85,5,$Sucursal,0,'L',false);

		#LOGO DE LA SUCURSAL
		if(!empty($Logo)){
			$Imagen=$RutaLogo.$Logo;
			if(file_exists($Imagen)){
				$this->Image($Imagen,$x+210,0,502,20);
			}
		}


		#AÑO
		$this->SetFont('','B',14);
		$this->SetXY(5,$y+4);
		$this->MultiCell(70,5,'ESTADOS FINANCIEROS',0,'C',false);

		$this->SetXY($x+15,$y+4);
		$this->MultiCell(80,5,$Mes.' - '.$Anio,0,'R',false);




		$this->SetTextColor(0,0,0);

		$y=$this->GetY()+10;
		$y2=$this->GetY()+10;

		#SUCURSAL
		$this->SetFont('','B',11);
		$this->RoundedRect($x,$y,4,4,2,'0','F','',array(120,165,242));
		$this->SetXY($x+5,$y);
		$this->MultiCell(22,5,'Sucursal:',0,'L',false);

		$this->SetXY($x+26,$y);
		$this->MultiCell(150,5,$Sucursal,0,'L',false);






	}

    public function Footer()
    {
        // $ImgDefault = 'assets/FinancieroPdf/fooder.png';

        // $y = 190;
        // $x = 50;

        // $this->SetXY($x,$y);
        // $this->Image($ImgDefault,0, $y,120,20);
    }

	function contenido()
	{

		$this->EspacioHeaderGF();

		$y = $this->GetY();
		$y2 = $this->GetY();
		$x = $this->GetX();

		$hh = 5;
		$b = 0;
		$f = true;
		$this->SetFont('', 'B', 10);
		$this->HeaderSueldos('');


		$empleados=$this->getDatos()['empleados'];

		$TotaSuma = 0;
		for ($i = 0; $i < count($empleados); $i++) {
			$this->SetFont('', '', 9);
			$this->SetTextColor(0, 0, 0);
			$Personal = $empleados[$i]->Nombre;
			$Sueldo = $empleados[$i]->Sueldo;
			$Obligaciones = $empleados[$i]->Obligaciones;
			$Prestaciones = $empleados[$i]->Prestaciones;
			$Comisiones = $empleados[$i]->ComisionesBonos;
			$Horas = $empleados[$i]->Extras;
			$Descuentos = $empleados[$i]->Descuentos;
			$Total = $empleados[$i]->Total;

			$TotaSuma += $empleados[$i]->Total;


			//datos
			$array = array(
				['texto' => $Personal, 'w' => 33],
				['texto' =>	'$' . number_format($Sueldo, 2, '.', ','), 'w' => 35, 'a' => 'R'],
				['texto' =>	'$' . number_format($Obligaciones, 2, '.', ','), 'w' => 38, 'a' => 'R'],
				['texto' =>	'$' . number_format($Prestaciones, 2, '.', ','), 'w' => 30, 'a' => 'R'],
				['texto' =>	'$' . number_format($Comisiones, 2, '.', ','), 'w' => 41, 'a' => 'R'],
				['texto' =>	'$' . number_format($Horas, 2, '.', ','), 'w' => 30, 'a' => 'R'],
				['texto' =>	'$' . number_format($Descuentos, 2, '.', ','), 'w' => 30, 'a' => 'R'],
				['texto' =>	'$' . number_format($Total, 2, '.', ','), 'w' => 41, 'a' => 'R']
			);

			$this->DetalleG($array, $x, $this->GetY(), $hh, '', '', '', 2);
		}

		$this->SetFont('', 'B', 10);
		$y = $this->GetY();
		$array = array(
			['texto' => 'Total', 'w' => 243],
			['texto' => '$' . number_format($TotaSuma, 2, '.', ','), 'w' => 35, 'a' => 'R'],
		);


		$hh = 5;
		$b = 0;
		$f = true;
		$this->HeaderDetalleG($array,$x,$y,$hh,'T',false);



	}

	public function contenido2(){
		$this->EspacioHeaderGF();

		$y = $this->GetY();
		$y2 = $this->GetY();
		$x = $this->GetX();

		$hh = 5;
		$b = 0;
		$f = true;
		$this->HeaderProveedor('');


		$cuentas=$this->getDatos()['cuentas'];

		$TotaSuma = 0;
		for ($i = 0; $i < count($cuentas); $i++) {
			$this->SetFont('', '', 9);
			$this->SetTextColor(0, 0, 0);
			$Personal = $cuentas[$i]->Nombre;
			$Sueldo = $cuentas[$i]->Sueldo;
			$Obligaciones = $cuentas[$i]->Obligaciones;
			$Prestaciones = $cuentas[$i]->Prestaciones;
			$Comisiones = $cuentas[$i]->ComisionesBonos;
			$Horas = $cuentas[$i]->Extras;
			$Descuentos = $cuentas[$i]->Descuentos;
			$Total = $cuentas[$i]->Total;

			$TotaSuma += $cuentas[$i]->Total;


			//datos
			$array = array(
				['texto' => $Personal, 'w' => 33],
				['texto' =>	'$' . number_format($Sueldo, 2, '.', ','), 'w' => 35, 'a' => 'R'],
				['texto' =>	'$' . number_format($Obligaciones, 2, '.', ','), 'w' => 38, 'a' => 'R'],
				['texto' =>	'$' . number_format($Prestaciones, 2, '.', ','), 'w' => 30, 'a' => 'R'],
				['texto' =>	'$' . number_format($Comisiones, 2, '.', ','), 'w' => 41, 'a' => 'R'],
				['texto' =>	'$' . number_format($Horas, 2, '.', ','), 'w' => 30, 'a' => 'R'],
				['texto' =>	'$' . number_format($Descuentos, 2, '.', ','), 'w' => 30, 'a' => 'R'],
				['texto' =>	'$' . number_format($Total, 2, '.', ','), 'w' => 41, 'a' => 'R']
			);

			$this->DetalleG($array, $x, $this->GetY(), $hh, '', '', '', 2);
		}

		$this->SetFont('', 'B', 10);
		$y = $this->GetY();
		$array = array(
			['texto' => 'Total', 'w' => 243],
			['texto' => '$' . number_format($TotaSuma, 2, '.', ','), 'w' => 35, 'a' => 'R'],
		);


		$hh = 5;
		$b = 0;
		$f = true;
		$this->HeaderDetalleG($array,$x,$y,$hh,'T',false);

	}
}
