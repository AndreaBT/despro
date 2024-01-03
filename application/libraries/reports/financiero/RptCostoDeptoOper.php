<?php
//session_start();

class RptCostoDeptoOper extends Cbasereport {

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
		$y2 = $this->GetY();
		$x = $this->GetX();

		$hh = 5;
		$b = 0;
		$f = true;
        $this->HeaderDetalleF2inGen('Descripcion');


        $row=$this->getDatos()['Lista'];

        $this->SetFont('','',9);
        $this->SetTextColor(0,0,0);
        $Fcturacion         ='Facturación';
        $AnoAnteriorMont    = $row[0]['AnoAnteriorMont'];
        $AnoAnteriorPorcent = 100;
        $PlanAnualFact      = $row[0]['PlanAnualFact'];
        $AnioActualFact     = $row[0]['AnioActualFact'];
        $PlanActualFact     = $row[0]['PlanActualFact'];
        $ActuaMesFact       = $row[0]['ActuaMesFact'];
        $array=array(
            ['texto'=>$Fcturacion,'w'=>40],
            ['texto'=>'$'.number_format($AnoAnteriorMont,2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($AnoAnteriorPorcent,2,'.',',').'%','w'=>20,'a'=>'R'],
            ['texto'=>'$'.number_format($PlanAnualFact,2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($AnoAnteriorPorcent,2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($AnioActualFact,2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($AnoAnteriorPorcent,2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($PlanActualFact,2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($AnoAnteriorPorcent,2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($ActuaMesFact,2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($AnoAnteriorPorcent,2,'.',',').'%','w'=>20,'a'=>'R'],
        );

        $this->DetalleG($array,$x,$this->GetY(),$hh,'','','',2);

        $TotalAnioPasado    = 0;
        $TotalPorcentAnioP  = 0;
        $TotalPlanAnioAct   = 0;
        $TotalPlanAnioActP  = 0;
        $TotalActualAnio    = 0;
        $TotalActualAnioP   = 0;
        $TotalMesPlan       = 0;
        $TotalMesPlanP      = 0;
        $TotalActualMes     = 0;
        $TotalActualMesP    = 0;

        for($i=0;$i<count($row);$i++){

                $this->SetFont('','',9);
                $this->SetTextColor(0,0,0);

                $Concepto=$row[$i]['Descripcion'];
                
                $AnioPasado=$row[$i]['AnioAnterior'];
                $AnioPasadoPorc=$row[$i]['AnioAnteriorPlan'];
                
                $AnioPlan=$row[$i]['PlanAnual'];
                $AnioPlanPorc=$row[$i]['PlanAnualPorcen'];
                
                $AnioActual=$row[$i]['AnioActual'];
                $AnioActualPorc=$row[$i]['AnioActualProcen'];
                
                $MesPlan=$row[$i]['PlanMes'];
                $MesPlanPorc=$row[$i]['PlanMesPorcen'];

                $MesActual=$row[$i]['MesActual'];
                $MesActualPorc=$row[$i]['MesActualPorce'];

                //TOTALES
                    $TotalAnioPasado    += $AnioPasado;
                    $TotalPorcentAnioP  += $AnioPasadoPorc;
                    $TotalPlanAnioAct   += $AnioPlan;
                    $TotalPlanAnioActP  += $AnioPlanPorc;
                    $TotalActualAnio    += $AnioActual;
                    $TotalActualAnioP   += $AnioActualPorc;
                    $TotalMesPlan       += $MesPlan;
                    $TotalMesPlanP      += $MesPlanPorc;
                    $TotalActualMes     += $MesActual;
                    $TotalActualMesP    += $MesActualPorc;

                //$valor =($rowmiselaneo[$i]->valor <=0) ? '$0' :'$'.number_format($rowmiselaneo[$i]->valor,2,'.',''); // $r is
                
                //datos
                $array=array(
                    ['texto'=>$Concepto,'w'=>40],
                    ['texto'=>'$'.number_format($AnioPasado,2,'.',','),'w'=>30,'a'=>'R'],
                    ['texto'=>''.number_format($AnioPasadoPorc,2,'.',',').'%','w'=>20,'a'=>'R'],
                    ['texto'=>'$'.number_format($AnioPlan,2,'.',','),'w'=>30,'a'=>'R'],
                    ['texto'=>''.number_format($AnioPlanPorc,2,'.',',').'%','w'=>15,'a'=>'R'],
                    ['texto'=>'$'.number_format($AnioActual,2,'.',','),'w'=>30,'a'=>'R'],
                    ['texto'=>''.number_format($AnioActualPorc,2,'.',',').'%','w'=>15,'a'=>'R'],
                    ['texto'=>'$'.number_format($MesPlan,2,'.',','),'w'=>30,'a'=>'R'],
                    ['texto'=>''.number_format($MesPlanPorc,2,'.',',').'%','w'=>15,'a'=>'R'],
                    ['texto'=>'$'.number_format($MesActual,2,'.',','),'w'=>30,'a'=>'R'],
                    ['texto'=>''.number_format($MesActualPorc,2,'.',',').'%','w'=>20,'a'=>'R']
                );
            
                $this->DetalleG($array,$x,$this->GetY(),$hh,'','','',2);
        }

        $hh=5;$b=0;$f=true;
        $this->SetFont('','B',8);
        $y = $this->GetY();
        $array=array(
            ['texto'=>'TOTALES','w'=>40],
            ['texto'=>'$'.number_format($TotalAnioPasado,2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($TotalPorcentAnioP,2,'.',',').'%','w'=>20,'a'=>'R'],
            ['texto'=>'$'.number_format($TotalPlanAnioAct,2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($TotalPlanAnioActP,2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($TotalActualAnio,2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($TotalActualAnioP,2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($TotalMesPlan,2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($TotalMesPlanP,2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($TotalActualMes,2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($TotalActualMesP,2,'.',',').'%','w'=>20,'a'=>'R'],
        );
        $this->HeaderDetalleG($array,$x,$y,$hh,'T',false);
       
        
    } 

    
}