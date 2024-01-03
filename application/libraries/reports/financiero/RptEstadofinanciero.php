<?php
//session_start();

class RptEstadofinanciero extends Cbasereport {

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
        
        $y=$this->GetY();
        $y2=$this->GetY();
        $x=$this->GetX();

        $hh=5;$b=0;$f=true;
        $this->HeaderDetalleF2finanzas('Descripcion');
        

        $row=$this->getDatos()['Lista']['row'];
        $data=$this->getDatos()['Lista']['rowconfig'];

        $row2=$this->getDatos()['Lista2']['row'];
        $data2=$this->getDatos()['Lista2']['rowconfig'];
    
        
        $TotalAnioPasado=0;
        $TotalAnioPlan=0;
        $TotalAnioActual=0;
        $TotalMesPlan=0;
        $TotalMesActual=0;
        $MesActualPorcTotal=0;
       for($i=0;$i<count($row);$i++){
            
            $FactMes=$row[0]['MesActualMonto'];
        
            $this->SetFont('','',9);
            $this->SetTextColor(0,0,0);
            $Concepto=$row[$i]['Descripcion'];
            // $AnioPasado=$row[$i]->AnioPasado;
            // $AnioPasadoPorc=$row[$i]->PorcenAnioAnte;
            
            // $AnioPlan=$row[$i]->PlanAnio;
            // $AnioPlanPorc=$row[$i]->PorcenPlanAnio;
            
            // $AnioActual=$row[$i]->ActualAnio;
            // $AnioActualPorc=$row[$i]->PorcentajeAnio;

            
            $MesPlan=$row2[0]['FacturacionPlan'];
            $MesPlanPorc=$row2[0]['AnioActualPorcen'];

            $MesPlan1=$row2[1]['Materiales'];
            $MesPlanPorc1=$row2[1]['AnioActualPorcen'];

            $MesPlan2=$row2[2]['Equipos'];
            $MesPlanPorc2=$row2[2]['AnioActualPorcen'];

            $MesPlan3=$row2[3]['ManoDeObra'];
            $MesPlanPorc3=$row2[3]['AnioActualPorcen'];

            $MesPlan4=$row2[4]['Vehiculos'];
            $MesPlanPorc4=$row2[4]['AnioActualPorcen'];

            $MesPlan5=$row2[5]['Contratistas'];
            $MesPlanPorc5=$row2[5]['AnioActualPorcen'];

            $MesPlan6=$row2[6]['Viaticos'];
            $MesPlanPorc6=$row2[6]['AnioActualPorcen'];

            $MesPlan7=$row2[7]['Burden'];
            $MesPlanPorc7=$row2[7]['AnioActualPorcen'];
            
            
            
            
            if ($i==0)
            {
            $MesActual=$row[$i]['MesActualMonto'];
            $MesActualPorc=100;
            }
            else
            {
               $MesActual=$row[$i]['MesActualMonto'];
               if ($FactMes=='0' || $FactMes=='')
               {
                $MesActualPorc=0;
               }
               else
               {
                  $MesActualPorc= number_format(($row[$i]['MesActualMonto'] *100) /$FactMes, 1, '.', ''); 
                  $MesActualPorcTotal +=$MesActualPorc;
               }
               
              
            }
            //$valor =($rowmiselaneo[$i]->valor <=0) ? '$0' :'$'.number_format($rowmiselaneo[$i]->valor,2,'.',''); // $r is
            
            //datos
            
        }
        $array=array(
            ['texto'=>$row2[0]['Descripcion'],'w'=>55],
            ['texto'=>'$'.number_format($row2[0]['FacturacionPlan'],2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($row2[0]['AnioActualPorcen'],2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($row[0]['MesActualMonto'],2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($row[0]['MesActualPorcen'],2,'.',',').'%','w'=>15,'a'=>'R']
        );
        $array1=array(
            ['texto'=>$row2[1]['Descripcion'],'w'=>55],
            ['texto'=>'$'.number_format($row2[1]['Materiales'],2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($row2[1]['AnioActualPorcen'],2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($row[1]['MesActualMonto'],2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($row[1]['MesActualPorcen'],2,'.',',').'%','w'=>15,'a'=>'R']
        );
        $array2=array(
            ['texto'=>$row2[2]['Descripcion'],'w'=>55],
            ['texto'=>'$'.number_format($row2[2]['Equipos'],2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($row2[2]['AnioActualPorcen'],2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($row[2]['MesActualMonto'],2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($row[2]['MesActualPorcen'],2,'.',',').'%','w'=>15,'a'=>'R']
        );
        $array3=array(
            ['texto'=>$row2[3]['Descripcion'],'w'=>55],
            ['texto'=>'$'.number_format($row2[3]['ManoDeObra'],2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($row2[3]['AnioActualPorcen'],2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($row[3]['MesActualMonto'],2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($row[3]['MesActualPorcen'],2,'.',',').'%','w'=>15,'a'=>'R']
        );
        $array4=array(
            ['texto'=>$row2[4]['Descripcion'],'w'=>55],
            ['texto'=>'$'.number_format($row2[4]['Vehiculos'],2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($row2[4]['AnioActualPorcen'],2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($row[4]['MesActualMonto'],2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($row[4]['MesActualPorcen'],2,'.',',').'%','w'=>15,'a'=>'R']
        );
        $array5=array(
            ['texto'=>$row2[5]['Descripcion'],'w'=>55],
            ['texto'=>'$'.number_format($row2[5]['Contratistas'],2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($row2[5]['AnioActualPorcen'],2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($row[5]['MesActualMonto'],2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($row[5]['MesActualPorcen'],2,'.',',').'%','w'=>15,'a'=>'R']
        );
        $array6=array(
            ['texto'=>$row2[6]['Descripcion'],'w'=>55],
            ['texto'=>'$'.number_format($row2[6]['Viaticos'],2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($row2[6]['AnioActualPorcen'],2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($row[6]['MesActualMonto'],2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($row[6]['MesActualPorcen'],2,'.',',').'%','w'=>15,'a'=>'R']);
        $array7=array(
            ['texto'=>$row2[7]['Descripcion'],'w'=>55],
            ['texto'=>'$'.number_format($row2[7]['Burden'],2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($row2[7]['AnioActualPorcen'],2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($row[7]['MesActualMonto'],2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($row[7]['MesActualPorcen'],2,'.',',').'%','w'=>15,'a'=>'R']
        );
          
        $this->DetalleG($array,$x,$this->GetY(),$hh,'','','',2);
        $this->DetalleG($array1,$x,$this->GetY(),$hh,'','','',2);
        $this->DetalleG($array2,$x,$this->GetY(),$hh,'','','',2);
        $this->DetalleG($array3,$x,$this->GetY(),$hh,'','','',2);
        $this->DetalleG($array4,$x,$this->GetY(),$hh,'','','',2);
        $this->DetalleG($array5,$x,$this->GetY(),$hh,'','','',2);
        $this->DetalleG($array6,$x,$this->GetY(),$hh,'','','',2);
        $this->DetalleG($array7,$x,$this->GetY(),$hh,'','','',2);



        $hh=5;$b=0;$f=true;
        $this->SetFont('','B',8);
        $y = $this->GetY(); 
        $array=array(
            ['texto'=>'TOTAL COSTO OPER.','w'=>55],
            ['texto'=>'$'.number_format($data2['COMesPlan'],2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($data2['COAnioActualPorcen'],2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($data['COMesActualMonto'],2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($data['COMesActualPorcen'],2,'.',',').'%','w'=>15,'a'=>'R']
        );
        
    
        $PorcenGrossMes=100-$MesActualPorcTotal;
        $this->HeaderDetalleG($array,$x,$y,$hh,'T',false);
        
        $y = $this->GetY();

        $array=array(
            ['texto'=>'GROSS PROFIT','w'=>55],
            ['texto'=>'$'.number_format($data2['GPMesActualMonto'],2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($data2['GPAnioActualPlanPorcen'],2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($data['GPMesActualMonto'],2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($data['GPMesActualPorcen'],2,'.',',').'%','w'=>15,'a'=>'R']
        );
        
        $this->HeaderDetalleG($array,$x,$y,$hh,'T',false);
        
    } 
}

?>
