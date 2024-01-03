<?php
//session_start();

class RptCostoventa extends Cbasereport
{

	#*****************LEER********************
	#*****************LEER********************
	#EN funciones helper se crea una tabla con header recibe parametros de (header,styleheader,detalle);


	function Header()
    {
        //!ANTES
            // $this->HeaderGF();
          
        #********************* IMAGEN DE CABECERA ********************
            $ImgDefault = 'assets/FinancieroPdf/header.png';

        #********************* DATOS DEL CLIENTE - SUCURSAL ********************
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

            $Mes='';
            if(isset($this->getDatos()['Mes'])){
                $Mes=$this->getDatos()['Mes'];
            }

            $TipoServicio='';
            if(isset($this->getDatos()['TipoServicio'])){
                $TipoServicio=$this->getDatos()['TipoServicio'];
            }

            switch ($Mes) {
                case '1':
                    $Mes="Enero";
                    break;
                case '2':
                    $Mes="Feb";
                    break;
                case '3':
                    $Mes="Marzo";
                    break;
                case '4':
                    $Mes="Abril";
                    break;
                case '5':
                    $Mes="Mayo";
                    break;
                case '6':
                    $Mes="Junio";
                    break;
                case '7':
                    $Mes="Julio";
                    break;
                case '8':
                    $Mes="Agosto";
                    break;
                case '9':
                    $Mes="Sept";
                    break;
                case '10':
                    $Mes="Oct";
                    break;
                case '11':
                    $Mes="Nov";
                break;
                case '12':
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

            $RutaLogo='assets/files/logo_empresa/'.$IdEmpresa.'/';

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
            if($Logo!=''){
                $Imagen=$RutaLogo.$Logo;
                if(file_exists($Imagen)){
                    $this->Image($Imagen,$x+210,0,50,20);
                }
            }

            #AÑO
                $this->SetFont('','B',14);
                $this->SetXY($x,$y+4);
                $this->MultiCell(70,5,'ESTADOS FINANCIEROS',0,'C',false);

                $this->SetXY($x+1,$y+4);
                $this->MultiCell(80,5,$Mes,0,'R',false);

                $this->SetXY($x+48,$y+4);
                $this->MultiCell(70,5,'-',0,'C',false);

                $this->SetXY($x+15,$y+4);
                $this->MultiCell(80,5,$Anio,0,'R',false);
            
                
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
		$x = $this->GetX();

		$hh = 5;
		$b = 0;
		$f = true;
		$this->FinanzasActualizacionCostos('Gastos Directos');

		$row = $this->getDatos()['Lista']['row'];


		// $TotalAnioPasado=0;
		// $TotalAnioPlan=0;
		// $TotalAnioActual=0;
		$TotalMesPlan = 0;
		$TotalMesActual = 0;
		for ($i = 0; $i < count($row); $i++) {
			$this->SetFont('', '', 9);
			$this->SetTextColor(0, 0, 0);
			$Concepto = $row[$i]->Descripcion;
			// $AnioPasado=$row[$i]->AnioPasado;
			// $AnioPlan = $row[$i]->PlanAnio;
			// $AnioActual = $row[$i]->ActualAnio;
			$MesPlan = $row[$i]->PlanMes;
			$MesActual = $row[$i]->ActualMes;
			$SemanaUno = $row[$i]->SemanaUno;
			$SemanaDos = $row[$i]->SemanaDos;
			$SemanaTres = $row[$i]->SemanaTres;
			$SemanaCuatro = $row[$i]->SemanaCuatro;

			// $TotalAnioPasado +=$AnioPasado;
			// $TotalAnioPlan += $AnioPlan;
			// $TotalAnioActual += $AnioActual;
			$TotalMesPlan += $MesPlan;
			$TotalMesActual += $MesActual;
			//$valor =($rowmiselaneo[$i]->valor <=0) ? '$0' :'$'.number_format($rowmiselaneo[$i]->valor,2,'.',''); // $r is

			//datos
			$array = array(
				['texto' => $Concepto, 'w' => 65],/*['texto'=>$AnioPasado,'w'=>35,'a'=>'R'], ['texto' => $AnioPlan, 'w' => 35, 'a' => 'R'], ['texto' => $AnioActual, 'w' => 35, 'a' => 'R'],*/
				['texto' =>	'$' . number_format($MesPlan, 2, '.', ','), 'w' => 35, 'a' => 'R'],
				['texto' =>	'$' . number_format($MesActual, 2, '.', ','), 'w' => 35, 'a' => 'R'],
				['texto' =>	'$' . number_format($SemanaUno, 2, '.', ','), 'w' => 35, 'a' => 'R'],
				['texto' =>	'$' . number_format($SemanaDos, 2, '.', ','), 'w' => 35, 'a' => 'R'],
				['texto' =>	'$' . number_format($SemanaTres, 2, '.', ','), 'w' => 35, 'a' => 'R'],
				['texto' =>	'$' . number_format($SemanaCuatro, 2, '.', ','), 'w' => 35, 'a' => 'R']
			);
			$this->DetalleG($array, $x, $this->GetY(), $hh, '', '', '', 2);
		}

		$this->SetFont('', 'B', 12);
		$y = $this->GetY();
		$array = array(
			['texto' => 'Total', 'w' => 65],/*['texto'=>$TotalAnioPasado,'w'=>35,'a'=>'R'], ['texto' => $TotalAnioPlan, 'w' => 35, 'a' => 'R'], ['texto' => $TotalAnioActual, 'w' => 35, 'a' => 'R'],*/
			['texto' => '$' . number_format($TotalMesPlan, 2, '.', ','), 'w' => 35, 'a' => 'R'],
			['texto' => '$' . number_format($TotalMesActual, 2, '.', ','), 'w' => 35, 'a' => 'R']
		);

		$hh = 5;
		$b = 0;
		$f = true;
		$this->HeaderDetalleG($array,$x,$y,$hh,'T',false);
		#********************GASTOS INDIRECTOS

		$y = $this->GetY();

		$this->SetFont('', '', 12);
		$hh = 5;
		$b = 0;
		$f = true;
		$this->FinanzasActualizacionCostos('Gastos Indirectos');

		$row2 = $this->getDatos()['Lista2']['row'];
		// $TotalAnioPasado=0;
		// $TotalAnioPlan=0;
		// $TotalAnioActual=0;
		$TotalMesPlan = 0;
		$TotalMesActual = 0;
		for ($i = 0; $i < count($row2); $i++) {
			$this->SetFont('', '', 9);
			$this->SetTextColor(0, 0, 0);
			$Concepto = $row2[$i]->Descripcion;
			// $AnioPasado=$row[$i]->AnioPasado;
			// $AnioPlan = $row[$i]->PlanAnio;
			// $AnioActual = $row[$i]->ActualAnio;
			$MesPlan = $row2[$i]->PlanMes;
			$MesActual = $row2[$i]->ActualMes;
			$SemanaUno = $row2[$i]->SemanaUno;
			$SemanaDos = $row2[$i]->SemanaDos;
			$SemanaTres = $row2[$i]->SemanaTres;
			$SemanaCuatro = $row2[$i]->SemanaCuatro;

			// $TotalAnioPasado +=$AnioPasado;
			// $TotalAnioPlan += $AnioPlan;
			// $TotalAnioActual += $AnioActual;
			$TotalMesPlan += $MesPlan;
			$TotalMesActual += $MesActual;
			//$valor =($rowmiselaneo[$i]->valor <=0) ? '$0' :'$'.number_format($rowmiselaneo[$i]->valor,2,'.',''); // $r is

			//datos
			$array = array(['texto' => $Concepto, 'w' => 65],/*['texto'=>$AnioPasado,'w'=>35,'a'=>'R'], ['texto' => $AnioPlan, 'w' => 35, 'a' => 'R'], ['texto' => $AnioActual, 'w' => 35, 'a' => 'R'],*/ ['texto' => number_format($MesPlan, 2, '.', ','), 'w' => 35, 'a' => 'R'], ['texto' =>  number_format($MesActual, 2, '.', ','), 'w' => 35, 'a' => 'R'], ['texto' => number_format($SemanaUno, 2, '.', ','), 'w' => 35, 'a' => 'R'], ['texto' => number_format($SemanaDos, 2, '.', ','), 'w' => 35, 'a' => 'R'], ['texto' => number_format($SemanaTres, 2, '.', ','), 'w' => 35, 'a' => 'R'], ['texto' => number_format($SemanaCuatro, 2, '.', ','), 'w' => 35, 'a' => 'R']);

			$this->DetalleG($array, $x, $this->GetY(), $hh, '', '', '', 2);
		}

		$this->SetFont('', 'B', 12);
		$y = $this->GetY();
		$array = array(['texto' => 'Total', 'w' => 65],/*['texto'=>$TotalAnioPasado,'w'=>35,'a'=>'R'], ['texto' => $TotalAnioPlan, 'w' => 35, 'a' => 'R'], ['texto' => $TotalAnioActual, 'w' => 35, 'a' => 'R'],*/ ['texto' => number_format($TotalMesPlan, 2, '.', ','), 'w' => 35, 'a' => 'R'], ['texto' => number_format($TotalMesActual, 2, '.', ','), 'w' => 35, 'a' => 'R']);

		$hh = 5;
		$b = 0;
		$f = true;
		$this->HeaderDetalleG($array,$x,$y,$hh,'T',false);
	}
}
