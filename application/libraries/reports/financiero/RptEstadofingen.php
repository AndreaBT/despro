<?php
//session_start();

class RptEstadofingen extends Cbasereport {

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
        $this->HeaderDetalleF2inGen('Descripcion');


        $row=$this->getDatos()['Lista']['row'];
        $data=$this->getDatos()['Lista']['rowconfig'];
        
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

                $AnioPasado=$row[$i]['AnioAnteriorMonto'];
                $AnioPasadoPorc=$row[$i]['AnioAnteriorPorcen'];
                
                $AnioPlan=$row[$i]['AnioActualPlan'];
                $AnioPlanPorc=$row[$i]['AnioActualPlanPorcent'];
                
                $AnioActual=$row[$i]['AnioActualMonto'];
                $AnioActualPorc=$row[$i]['AnioActualPorcen'];
                
                $MesPlan=$row[$i]['MesActualPlan'];
                $MesPlanPorc=$row[$i]['MesActualPlanPorcen'];
                
                if ($i==0)
                {
                    $MesActual=$row[$i]['MesActualMonto'];
                    $MesActualPorc=$row[$i]['MesActualPorcen'];
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
                        $MesActualPorc= number_format(($row[$i]['MesActualMonto'] *100) /$FactMes, 2, '.', ''); 
                        $MesActualPorcTotal +=$MesActualPorc;
                    }
                
                
                }
                //$valor =($rowmiselaneo[$i]->valor <=0) ? '$0' :'$'.number_format($rowmiselaneo[$i]->valor,2,'.',''); // $r is
                
                //datos
                $array=array(
                    ['texto'=>$Concepto,'w'=>40],
                    ['texto'=>'$'.number_format($AnioPasado,2,'.',','),'w'=>30,'a'=>'R'],
                    ['texto'=>''.number_format($AnioPasadoPorc,2,'.',',').'%','w'=>20,'a'=>'R'],
                    ['texto'=>'$'.number_format($AnioPlan,2,'.',','),'w'=>30,'a'=>'R'],
                    ['texto'=>''.number_format($AnioPlanPorc,2,'.',',').'%','w'=>15,'a'=>'R'],
                    ['texto'=>'$'.number_format($AnioActual,2,'.',','),'w'=>28,'a'=>'R'],
                    ['texto'=>''.number_format($AnioActualPorc,2,'.',',').'%','w'=>17,'a'=>'R'],
                    ['texto'=>'$'.number_format($MesPlan,2,'.',','),'w'=>30,'a'=>'R'],
                    ['texto'=>''.number_format($MesPlanPorc,2,'.',',').'%','w'=>15,'a'=>'R'],
                    ['texto'=>'$'.number_format($MesActual,2,'.',','),'w'=>30,'a'=>'R'],
                    ['texto'=>''.number_format($MesActualPorc,2,'.',',').'%','w'=>20,'a'=>'R']);
            
                $this->DetalleG($array,$x,$this->GetY(),$hh,'','','',2);
        }

        //Validaciones COSTO OPERACIONAL 
            $COAnioAnteriorPorcen = 0;
            if($data['COAnioAnteriorPorcen']>=100){
                
                $COAnioAnteriorPorcen=100;
            }else{
                $COAnioAnteriorPorcen = $data['COAnioAnteriorPorcen'];
            }

            $COAnioActualPlanPorcen = 0;
            if($data['COAnioActualPlanPorcen']>=100){
                
                $COAnioActualPlanPorcen=100;
            }else{
                $COAnioActualPlanPorcen = $data['COAnioActualPlanPorcen'];
            }

            $COAnioActualPorcen=0;
            if($data['COAnioActualPorcen']>=100){
                
                $COAnioActualPorcen=100;
            }else{
                $COAnioActualPorcen = $data['COAnioActualPorcen'];
            }

            $COMesActualPlanPorcen = 0;
            if($data['COMesActualPlanPorcen']>=100){
                
                $COMesActualPlanPorcen=100;
            }else{
                $COMesActualPlanPorcen = $data['COMesActualPlanPorcen'];
            }

            $COMesActualPorcen = 0;
            if($data['COMesActualPorcen']>=100){
                
                $COMesActualPorcen=100;
            }else{
                $COMesActualPorcen = $data['COMesActualPorcen'];
            }
        
        $hh=5;$b=0;$f=true;
        $this->SetFont('','B',8);
        $y = $this->GetY();
        $array=array(
            ['texto'=>'TOTAL COSTO OPER.','w'=>40],
            ['texto'=>'$'.number_format($data['COAnioAnteriorMonto'],2,'.',','),'w'=>30,'a'=>'R'],
            // ['texto'=>'%'.number_format($data['COAnioAnteriorPorcen'],2,'.',','),'w'=>20,'a'=>'R'],
            ['texto'=>''.number_format( $COAnioAnteriorPorcen,2,'.',',').'%','w'=>20,'a'=>'R'],
            ['texto'=>'$'.number_format($data['COAnioActualPlan'],2,'.',','),'w'=>30,'a'=>'R'],
            // ['texto'=>'%'.number_format($data['COAnioActualPlanPorcen'],2,'.',','),'w'=>15,'a'=>'R'],
            ['texto'=>''.number_format($COAnioActualPlanPorcen,2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($data['COAnioActualMonto'],2,'.',','),'w'=>28,'a'=>'R'],
            // ['texto'=>'%'.number_format($data['COAnioActualPorcen'],2,'.',','),'w'=>15,'a'=>'R'],
            ['texto'=>''.number_format($COAnioActualPorcen,2,'.',',').'%','w'=>17,'a'=>'R'],
            ['texto'=>'$'.number_format($data['COMesActualPlan'],2,'.',','),'w'=>30,'a'=>'R'],
            // ['texto'=>'%'.number_format($data['COMesActualPlanPorcen'],2,'.',','),'w'=>15,'a'=>'R'],
            ['texto'=>''.number_format($COMesActualPlanPorcen,2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($data['COMesActualMonto'],2,'.',','),'w'=>30,'a'=>'R'],
            // ['texto'=>'%'.number_format($data['COMesActualPorcen'],2,'.',','),'w'=>20,'a'=>'R']
            ['texto'=>''.number_format($COMesActualPorcen,2,'.',',').'%','w'=>20,'a'=>'R']
        );

        //Validaciones GROSS PROFIT 
            $GPAnioAnteriorPorcen = 0;
            if($data['GPAnioAnteriorPorcen']>=100){
                
                $GPAnioAnteriorPorcen=100;
            }else{
                $GPAnioAnteriorPorcen = $data['GPAnioAnteriorPorcen'];
            }

            $GPAnioActualPlanPorcen = 0;
            if($data['GPAnioActualPlanPorcen']>=100){
                
                $GPAnioActualPlanPorcen=100;
            }else{
                $GPAnioActualPlanPorcen = $data['GPAnioActualPlanPorcen'];
            }

            $GPAnioActualPorcen=0;
            if($data['GPAnioActualPorcen']>=100){
                
                $GPAnioActualPorcen=100;
            }else{
                $GPAnioActualPorcen = $data['GPAnioActualPorcen'];
            }

            $GPMesActualPlanPorcen = 0;
            if($data['GPMesActualPlanPorcen']>=100){
                
                $GPMesActualPlanPorcen=100;
            }else{
                $GPMesActualPlanPorcen = $data['GPMesActualPlanPorcen'];
            }

            $GPMesActualPorcen = 0;
            if($data['GPMesActualPorcen']>=100){
                
                $GPMesActualPorcen=100;
            }else{
                $GPMesActualPorcen = $data['GPMesActualPorcen'];
            }
        
    
        $PorcenGrossMes=100-$MesActualPorcTotal;
        $this->HeaderDetalleG($array,$x,$y,$hh,'T',false);
        
        $y = $this->GetY();

        $array=array(
            ['texto'=>'GROSS PROFIT','w'=>40],
            ['texto'=>'$'.number_format($data['GPAnioAnteriorMonto'],2,'.',','),'w'=>30,'a'=>'R'],
            // ['texto'=>'%'.number_format($data['GPAnioAnteriorPorcen'],2,'.',','),'w'=>20,'a'=>'R'],
            ['texto'=>''.number_format($GPAnioAnteriorPorcen,2,'.',',').'%','w'=>20,'a'=>'R'],
            ['texto'=>'$'.number_format($data['GPAnioActualPlan'],2,'.',','),'w'=>30,'a'=>'R'],
            // ['texto'=>'%'.number_format($data['GPAnioActualPlanPorcen'],2,'.',','),'w'=>15,'a'=>'R'],
            ['texto'=>''.number_format($GPAnioActualPlanPorcen,2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($data['GPAnioActualMonto'],2,'.',','),'w'=>28,'a'=>'R'],
            // ['texto'=>'%'.number_format($data['GPAnioActualPorcen'],2,'.',','),'w'=>15,'a'=>'R'],
            ['texto'=>''.number_format($GPAnioActualPorcen,2,'.',',').'%','w'=>17,'a'=>'R'],
            ['texto'=>'$'.number_format($data['GPMesActualPlan'],2,'.',','),'w'=>30,'a'=>'R'],
            // ['texto'=>'%'.number_format($data['GPMesActualPlanPorcen'],2,'.',','),'w'=>15,'a'=>'R'],
            ['texto'=>''.number_format($GPMesActualPlanPorcen,2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($data['GPMesActualMonto'],2,'.',','),'w'=>30,'a'=>'R'],
            // ['texto'=>'%'.number_format($data['GPMesActualPorcen'],2,'.',','),'w'=>20,'a'=>'R']
            ['texto'=>''.number_format($GPMesActualPorcen,2,'.',',').'%','w'=>20,'a'=>'R']
        );
        
        $this->HeaderDetalleG($array,$x,$y,$hh,$b,false);
        
        
        $this->SetY($this->GetY()+5);
        
        for($i=0;$i<6;$i++){
            $this->SetFont('','',9);
            $this->SetTextColor(0,0,0);
            
            if ($i==0)
            {
                $Concepto='Costos G & A';
                $AnioPasado=$data['ga1'];
                $AnioPasadoPorc=$data['ga2'];
                $AnioPlan=$data['ga3'];
                $AnioPlanPorc=$data['ga4'];
                $AnioActual=$data['ga5'];
                $AnioActualPorc=$data['ga6'];
                $MesPlan=$data['ga7'];
                $MesPlanPorc=$data['ga8'];
                $MesActual=$data['ga9'];
                $MesActualPorc=$data['ga10'];
            }
            
             if ($i==1)
            {
                

                
                $Concepto='Costos Financieros';
                $AnioPasado=$data['ie1'];
                $AnioPasadoPorc=$data['ie2'];
                $AnioPlan=$data['ie3'];
                $AnioPlanPorc=$data['ie4'];
                $AnioActual=$data['ie5'];
                $AnioActualPorc=$data['ie6'];
                $MesPlan=$data['ie7'];
                $MesPlanPorc=$data['ie8'];
                $MesActual=$data['ie9'];
                $MesActualPorc=$data['ie10'];
            }
            
            //  if ($i==2)
            // {
            //     $Concepto='Costos Operaciones';
            //     $AnioPasado=$data['UnoTCO'];
            //     $AnioPasadoPorc=$data['DosTCO'];
            //     $AnioPlan=$data['TresTCO'];
            //     $AnioPlanPorc=$data['CuatroTCO'];
            //     $AnioActual=$data['CincoTCO'];
            //     $AnioActualPorc=$data['SeisTCO'];
            //     $MesPlan=$data['SieteTCO'];
            //     $MesPlanPorc=$data['OchoTCO'];
            //     $MesActual=$data['ActualMesTCO'];
            //     $MesActualPorc=$data['DiesTCO'];
            // }
            
            // if ($i==3)
            // {
            //     $Concepto='Costos Vehículo Operación';
            //     $AnioPasado=$data['UnoTCV'];
            //     $AnioPasadoPorc=$data['DosTCV'];
            //     $AnioPlan=$data['TresTCV'];
            //     $AnioPlanPorc=$data['CuatroTCV'];
            //     $AnioActual=$data['CincoTCV'];
            //     $AnioActualPorc=$data['SeisTCV'];
            //     $MesPlan=$data['SieteTCV'];
            //     $MesPlanPorc=$data['OchoTCV'];
            //     $MesActual=$data['ActualMesTCV'];
            //     $MesActualPorc=$data['DiesTCV'];
            // }
            
             if ($i==2)
            {
                

                $Concepto='Costos Depto. Venta';
                $AnioPasado=$data['cv1'];
                $AnioPasadoPorc=$data['cv2'];
                $AnioPlan=$data['cv3'];
                $AnioPlanPorc=$data['cv4'];
                $AnioActual=$data['cv5'];
                $AnioActualPorc=$data['cv6'];
                $MesPlan=$data['cv7'];
                $MesPlanPorc=$data['cv8'];
                $MesActual=$data['cv9'];
                $MesActualPorc=$data['cv10'];

            }
            
               if ($i==3)
            {
                $Concepto='Varianza Burden';
                $AnioPasado=$data['vb1'];
                $AnioPasadoPorc=$data['vb2'];
                $AnioPlan=$data['vb3'];
                $AnioPlanPorc=$data['vb4'];
                $AnioActual=$data['vb5'];
                $AnioActualPorc=$data['vb6'];
                $MesPlan=$data['vb7'];
                $MesPlanPorc=$data['vb8'];
                $MesActual=$data['vb9'];
                $MesActualPorc=$data['vb10'];

               
            }
            
            if ($i==4)
            {
                $Concepto='Varianza Vehículo';
                $AnioPasado=$data['vv1'];
                $AnioPasadoPorc=$data['vv2'];
                $AnioPlan=$data['vv3'];
                $AnioPlanPorc=$data['vv4'];
                $AnioActual=$data['vv5'];
                $AnioActualPorc=$data['vv6'];
                $MesPlan=$data['vv7'];
                $MesPlanPorc=$data['vv8'];
                $MesActual=$data['vv9'];
                $MesActualPorc=$data['vv10'];

                
            }

            if ($i==5)
            {
                $Concepto='Varizanza Almacén';
                $AnioPasado=$data['varianzaAlmacen1'];
                $AnioPasadoPorc=$data['varianzaAlmacen2'];
                $AnioPlan=$data['varianzaAlmacen3'];
                $AnioPlanPorc=$data['varianzaAlmacen4'];
                $AnioActual=$data['varianzaAlmacen5'];
                $AnioActualPorc=$data['varianzaAlmacen6'];
                $MesPlan=$data['varianzaAlmacen7'];
                $MesPlanPorc=$data['varianzaAlmacen8'];
                $MesActual=$data['varianzaAlmacen9'];
                $MesActualPorc=$data['varianzaAlmacen10'];
            }
            //$valor =($rowmiselaneo[$i]->valor <=0) ? '$0' :'$'.number_format($rowmiselaneo[$i]->valor,2,'.',''); // $r is
            
            //datos
            $array=array(
                ['texto'=>$Concepto,'w'=>40],
                ['texto'=>'$'.number_format($AnioPasado,2,'.',','),'w'=>30,'a'=>'R'],
                ['texto'=>''.number_format($AnioPasadoPorc,2,'.',',').'%','w'=>20,'a'=>'R'],
                ['texto'=>'$'.number_format($AnioPlan,2,'.',','),'w'=>30,'a'=>'R'],
                ['texto'=>''.number_format($AnioPlanPorc,2,'.',',').'%','w'=>15,'a'=>'R'],
                ['texto'=>'$'.number_format($AnioActual,2,'.',','),'w'=>28,'a'=>'R'],
                ['texto'=>''.number_format($AnioActualPorc,2,'.',',').'%','w'=>17,'a'=>'R'],
                ['texto'=>'$'.number_format($MesPlan,2,'.',','),'w'=>30,'a'=>'R'],
                ['texto'=>''.number_format($MesPlanPorc,2,'.',',').'%','w'=>15,'a'=>'R'],
                ['texto'=>'$'.number_format($MesActual,2,'.',','),'w'=>30,'a'=>'R'],
                ['texto'=>''.number_format($MesActualPorc,2,'.',',').'%','w'=>20,'a'=>'R']
            );
          
            $this->DetalleG($array,$x,$this->GetY(),$hh,'','','',2);
        }
        
            $AnioPasado=$data['np1'];
            $AnioPasadoPorc=$data['np2'];
            $AnioPlan=$data['np3'];
            $AnioPlanPorc=$data['np4'];
            $AnioActual=$data['np5'];
            $AnioActualPorc=$data['np6'];
            $MesPlan=$data['np7'];
            $MesPlanPorc=$data['np8'];
            $MesActual=$data['np9'];
            $MesActualPorc=$data['np10'];
        
        $hh=5;$b='T';$f=true;
        $this->SetFont('','B',8);
        $y = $this->GetY();
        $array=array(
            ['texto'=>'Net Profit','w'=>40],
            ['texto'=>'$'.number_format($AnioPasado,2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($AnioPasadoPorc,2,'.',',').'%','w'=>20,'a'=>'R'],
            ['texto'=>'$'.number_format($AnioPlan,2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($AnioPlanPorc,2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($AnioActual,2,'.',','),'w'=>28,'a'=>'R'],
            ['texto'=>''.number_format($AnioActualPorc,2,'.',',').'%','w'=>17,'a'=>'R'],
            ['texto'=>'$'.number_format($MesPlan,2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($MesPlanPorc,2,'.',',').'%','w'=>15,'a'=>'R'],
            ['texto'=>'$'.number_format($MesActual,2,'.',','),'w'=>30,'a'=>'R'],
            ['texto'=>''.number_format($MesActualPorc,2,'.',',').'%','w'=>20,'a'=>'R']
        );
        
        $this->HeaderDetalleG($array,$x,$y,$hh,$b,false);
        
    } 
}

?>
