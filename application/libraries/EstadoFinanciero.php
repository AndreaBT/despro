<?php
//defined("BASEPATH") or die("El acceso al script no est� permitido");
defined('BASEPATH') or exit('No direct script access allowed');

class EstadoFinanciero
{
      public $porcentaje1=0;
      
    
      public  $PlanAnio=0;
      public $PlanMes=0;
      public $AnioPasado=0;
      
      //Restar Meses
      public $FacRes=0;
      public $MatRes=0;
      public $EquiRes=0;
      public $MonoORes=0;
      public $VehiRes=0;
      public $ContraRes=0;
      public $ViatRes=0;
      public $BurdRes=0;

      //Restar anio
       public $FacRes2=0;
      public $MatRes2=0;
      public $EquiRes2=0;
      public $MonoORes2=0;
      public $VehiRes2=0;
      public $ContraRes2=0;
      public $ViatRes2=0;
      public $BurdRes2=0;

      //Cuentas por pagar. 
      public $EquiposD;
      public $MaterialesD;
      public $ViaticosD;
      public $ContratistasD;


    public function __construct()
    {
         $CI =& get_instance();
         $CI->load->library('Finanzas');
         $CI->load->library('FinanzasActualizacion');
         $CI->load->model('Mservicio');
         $CI->load->model('finanzas/Mestadofinanciero');
         $CI->load->model('finanzas/Mestadofupdate');

         $CI->load->model('ctaporpagar/Mctaporpagar');
         
         
         
    }
    
    public function ActualizarCostos($tipo=1,$IdSucursal,$Anio,$IdConfigS,$IdSubtipoServ,$Mes)
    {
      //tipo 1 es igual a individual
      $this->FacRes=0;
      $this->MatRes=0;
      $this->EquiRes=0;
      $this->MonoORes=0;
      $this->VehiRes=0;
      $this->ContraRes=0;
      $this->ViatRes=0;
      $this->BurdRes=0;

      $this->FacRes2=0;
      $this->MatRes2=0;
      $this->EquiRes2=0;
      $this->MonoORes2=0;
      $this->VehiRes2=0;
      $this->ContraRes2=0;
      $this->ViatRes2=0;
      $this->BurdRes2=0;

      if ($tipo==1)
      {
        //se busca la lista de correcion (Actualizar costos operativos) 
        $oMestadofupdate = new Mestadofupdate();
        $oMestadofupdate->IdSucursal=$IdSucursal;
        $oMestadofupdate->Anio=$Anio;
        $oMestadofupdate->IdConfigServ=$IdConfigS;
        $oMestadofupdate->IdTipoServ=$IdSubtipoServ;
        $oMestadofupdate->Mes=$Mes;
        $row= $oMestadofupdate->get_listIndividual(1);
    
        $row2= $oMestadofupdate->get_listIndividual(2);
        
          if (count($row)>0)
          {
            
            //si hay lista se vacia los valores en las variables , las varibales seran restadas a cada uno de los item de mes actual
            $this->FacRes=$row[0]->Monto;//facturacion
            $this->MatRes=$row[1]->Monto;//materiales
            $this->EquiRes=$row[2]->Monto;//equipos
            $this->MonoORes=$row[3]->Monto;//mano de obra 
            $this->VehiRes=$row[4]->Monto;//vehiculos
            $this->ContraRes=$row[5]->Monto;//contratistas
            $this->ViatRes=$row[6]->Monto;//viaticos
            $this->BurdRes=$row[7]->Monto;//burden

            //este es lo mismo pero es para el año actual | actual 
            $this->FacRes2=$row2[0]->Monto;
            $this->MatRes2=$row2[1]->Monto;
            $this->EquiRes2=$row2[2]->Monto;
            $this->MonoORes2=$row2[3]->Monto;
            $this->VehiRes2=$row2[4]->Monto;
            $this->ContraRes2=$row2[5]->Monto;
            $this->ViatRes2=$row2[6]->Monto;
            $this->BurdRes2=$row2[7]->Monto;
          }
          
      }
      
      //esto es lo mismo que de arriba solo que para el general -- creo que se puede omitir esto
      if ($tipo==2)
      {
        //echo 'Pasa2';
        $oMestadofupdate = new Mestadofupdate();
        $oMestadofupdate->IdSucursal=$IdSucursal;
        $oMestadofupdate->Anio=$Anio;
        $oMestadofupdate->IdConfigServ=$IdConfigS;
        $oMestadofupdate->IdTipoServ=$IdSubtipoServ;
        $oMestadofupdate->Mes=$Mes;
        $row= $oMestadofupdate->get_listTodos(1);
        
        $row2= $oMestadofupdate->get_listTodos(2);

        //print_r($row);
        
        if (count($row)>0)
        {
          // $this->FacRes=$row[0]->Monto;
          // $this->MatRes=$row[1]->Monto;
          // $this->EquiRes=$row[2]->Monto;
          // $this->MonoORes=$row[3]->Monto;
          // $this->VehiRes=$row[4]->Monto;
          // $this->ContraRes=$row[5]->Monto;
          // $this->ViatRes=$row[6]->Monto;
          // $this->BurdRes=$row[7]->Monto;

          $this->BurdRes=$row[0]->Monto;
          $this->ContraRes=$row[1]->Monto;
          $this->EquiRes=$row[2]->Monto;
          $this->FacRes=$row[3]->Monto;
          $this->MonoORes=$row[4]->Monto;
          $this->MatRes=$row[5]->Monto;
          $this->VehiRes=$row[6]->Monto;
          $this->ViatRes=$row[7]->Monto;
        }
          
        if (count($row2)>0)
        {
          $this->FacRes2=$row2[0]->Monto;
          $this->MatRes2=$row2[1]->Monto;
          $this->EquiRes2=$row2[2]->Monto;
          $this->MonoORes2=$row2[3]->Monto;
          $this->VehiRes2=$row2[4]->Monto;
          $this->ContraRes2=$row2[5]->Monto;
          $this->ViatRes2=$row2[6]->Monto;
          $this->BurdRes2=$row2[7]->Monto;
        } 
      }   
    }
    

    public function GetEstadoFinanciero($IdSucursal,$Anio,$IdConfigS,$IdSubtipoServ,$Mes,$IdCliente,$IdClienteS,$IdContrato,$Type=1,$IdEmpresa)
    {
          $ofinanzas= new Finanzas();
          //obtiene los valores de porcentaje de operacion
          $row= $ofinanzas->PorcentajeOperacionCalculo($IdSucursal,$Anio,$IdConfigS,$IdSubtipoServ);
          // se obtienen lo que se restara de cada item por mes actual y año actual que se alamcenan en FactRes MatRes etc
          $this->ActualizarCostos(1,$IdSucursal,$Anio,$IdConfigS,$IdSubtipoServ,$Mes);
          $row = $this->Validacion($Mes,$row,$Anio,$IdSucursal,$IdConfigS,$IdSubtipoServ,$IdCliente,$IdClienteS,$IdContrato);
           
            //Se realiza la suma de la correcion Finanzas update
               if (count($row)>0)
               {
                    //mensual
                    $row[0]->ActualMes +=$this->FacRes;
                    $row[1]->ActualMes +=$this->MatRes;
                    $row[2]->ActualMes +=$this->EquiRes;
                    $row[3]->ActualMes +=$this->MonoORes;
                    $row[4]->ActualMes +=$this->VehiRes;
                    $row[5]->ActualMes +=$this->ContraRes;
                    $row[6]->ActualMes +=$this->ViatRes;
                    $row[7]->ActualMes +=$this->BurdRes;
                    
                    //Anual
                    $row[0]->ActualAnio +=$this->FacRes2;
                    $row[1]->ActualAnio +=$this->MatRes2;
                    $row[2]->ActualAnio +=$this->EquiRes2;
                    $row[3]->ActualAnio +=$this->MonoORes2;
                    $row[4]->ActualAnio +=$this->VehiRes2;
                    $row[5]->ActualAnio +=$this->ContraRes2;
                    $row[6]->ActualAnio +=$this->ViatRes2;
                    $row[7]->ActualAnio +=$this->BurdRes2;
               }
          
          $data['resultados']=$this->GetCostoOpGp($row,$Type,$IdSucursal,$Anio,$Mes,$IdEmpresa);
         
          
          return $data;
    }
    
    public function GetEstadoFinancieroTodos($IdSucursal,$Anio,$Mes,$IdCliente,$IdClienteS,$IdContrato,$Type=1,$IdEmpresa)
    {
      //si tipo =1 es todos si es 2 es general
      $ofinanzas= new Finanzas();
      $row=null;
      $this->ActualizarCostos(2,$IdSucursal,$Anio,"","",$Mes);

      for ($i=1;$i<6;$i++)
      {
        $rowget= $ofinanzas->PorcentajeOperacionCalculo($IdSucursal,$Anio,$i,"");
    
        $rowval = $this->Validacion($Mes,$rowget,$Anio,$IdSucursal,$i,"",$IdCliente,$IdClienteS,$IdContrato);
      
        if ($i>1)
        {
            for ($z=0;$z<count($rowval);$z++)
            {
            
            $row[$z]->ActualMes +=$rowval[$z]->ActualMes; 
            $row[$z]->ActualAnio +=$rowval[$z]->ActualAnio; 
            $row[$z]->AnioPasado +=$rowval[$z]->AnioPasado;
            $row[$z]->PlanAnio +=$rowval[$z]->PlanAnio;
            $row[$z]->PlanMes +=$rowval[$z]->PlanMes;
            }
        }
            
        if ($i==1)
        {
          $row=$rowval;
        } 
      }

      $MesBusqueda=$Mes;
      if ($Mes<10)
      {
        $MesBusqueda ="0".intval($Mes);
      }

      $ctaporpagar = new Mctaporpagar();
      // $ctaporpagar->IdConfigS = $IdServicio;
      $ctaporpagar->IdSucursal = $IdSucursal;
      // $ctaporpagar->Tipo_Serv = $IdServicio;
      $ctaporpagar->FechaRealPago = $Anio.'-'.$MesBusqueda;
      $ctaporpagar->IdClienteS = $IdClienteS;
      // $ctaporpagar->IdSubIndice = $IdTipoServ;
      $ctaporpagar->IdContrato = $IdContrato;

      $CPPMaterial = 0;
      $CPPEquipo   = 0;
      $CPPMContratista =0;
      $CPPVaitico =0;

      $ResMaterial = $ctaporpagar->get_listMaterial();
      $ResEquipo = $ctaporpagar->get_listEquipos();
      $ResContratista = $ctaporpagar->get_listContratista();
      $ResViatico = $ctaporpagar->get_listViaticos();

      $CPPMaterial     = $ResMaterial['data']->MontoMaterial;
      $CPPEquipo       = $ResEquipo['data']->MontoEquipo;
      $CPPMContratista = $ResContratista['data']->MontoContratista;
      $CPPVaitico      = $ResViatico['data']->MontoViaticos;


      
      //Se realiza la suma de la correcion Finanzas update
      if (count($row)>0)
      {
        $MatResM2=0;
        $EquiResM2=0;
        $ContraResM2=0;
        $ViatResM2=0;


          //echo $this->FacRes;
          //mensual
          $MatResM2 += $row[1]->ActualMes;
          // var_dump($MatResM2);
          $EquiResM2 +=$row[2]->ActualMes;
          $ContraResM2+=$row[5]->ActualMes;
          $ViatResM2+=$row[6]->ActualMes;

          $row[0]->ActualMes +=$this->FacRes;
          // $row[1]->ActualMes +=$this->MatRes;
          // $row[2]->ActualMes +=$this->EquiRes;
          $row[3]->ActualMes +=$this->MonoORes;
          $row[4]->ActualMes +=$this->VehiRes;
          // $row[5]->ActualMes +=$this->ContraRes;
          // $row[6]->ActualMes +=$this->ViatRes;
          $row[7]->ActualMes +=$this->BurdRes;

          if ($CPPMaterial!=null || $CPPMaterial!=0) {
            if ($CPPMaterial>$MatResM2) {
                
              $row[1]->ActualMes = $CPPMaterial;
            }else{
              $row[1]->ActualMes +=$this->MatRes;
            }
          }else{
            $row[1]->ActualMes +=$this->MatRes;
          }
    
          if ($CPPEquipo!=null || $CPPEquipo!=0) {
              if ($CPPEquipo> $EquiResM2) {
                  
                $row[2]->ActualMes = $CPPEquipo;
              }else{
                $row[2]->ActualMes +=$this->EquiRes;
              }
          }else{
            $row[2]->ActualMes +=$this->EquiRes;
          }
    
          if ($CPPMContratista!=null || $CPPMContratista!=0) {
              if ($CPPMContratista>$ContraResM2) {
                $row[5]->ActualMes =$CPPMContratista;
              }else{
                $row[5]->ActualMes +=$this->ContraRes;
              }
          }else{
            $row[5]->ActualMes +=$this->ContraRes;
          }
    
          if ($CPPVaitico!=null || $CPPVaitico!=0) {
              if ($CPPVaitico> $ViatResM2) {
                  
                $row[6]->ActualMes =$CPPVaitico;
              }else{
                $row[6]->ActualMes +=$this->ViatRes;
              }
          }else{
            $row[6]->ActualMes +=$this->ViatRes;
          }
          
          //Anual
          $row[0]->ActualAnio +=$this->FacRes2;
          $row[1]->ActualAnio +=$this->MatRes2;
          $row[2]->ActualAnio +=$this->EquiRes2;
          $row[3]->ActualAnio +=$this->MonoORes2;
          $row[4]->ActualAnio +=$this->VehiRes2;
          $row[5]->ActualAnio +=$this->ContraRes2;
          $row[6]->ActualAnio +=$this->ViatRes2;
          $row[7]->ActualAnio +=$this->BurdRes2;
      }
               
      $data['resultados']=$this->GetCostoOpGp($row,$Type,$IdSucursal,$Anio,$Mes,$IdEmpresa);

      // var_dump($data);

      return $data;
    }
    
    
    
    public function GetCostoOpGp($row,$Type,$IdSucursal,$Anio,$Mes,$IdEmpresa)
    {
        $UnoCp=0;$DosCp=0;$TresCp=0;$CuatroCp=0;$CincoCp=0;$SeisCp=0;$SieteCp=0;$OchoCp=0;$NueveCp=0;$DiezCp=0;
        $UnoGp=0;$DosGp=0;$TresGp=0;$CuatroGp=0;$CincoGp=0;$SeisGp=0;$SieteGp=0;$OchoGp=0;$NueveGp=0;$DiezGp=0;
        
        //Estos son los primeros valores del arreglo para poder sacar el gros proffit
        $Uno=0;$Dos=0;$Tres=0;$Cuatro=0;$Cinco=0;$Seis=0;$Siete=0;$Ocho=0;$Nueve=0;$Diez=0; 
       $count =0;
        //Porcentaje de cada columna
        foreach ($row as $element)
        {
            if ($count>0)
            {
                if ($element->AnioPasado!='')
                {
                  $UnoCp += $element->AnioPasado;
                  
                  $Porcentaje=0;
                  if ($row[0]->AnioPasado>0)
                  {
                  $Porcentaje = ($element->AnioPasado *100)/$row[0]->AnioPasado;
                  }
                  $element->PorcenAnioAnte= number_format($Porcentaje, 1, '.', '');
                    $DosCp +=number_format($Porcentaje, 1, '.', '');
                }
                 
                 if ($element->PlanAnio!='')
                {
                  $TresCp += $element->PlanAnio;
                  
                     $Porcentaje=0;
                  if ($row[0]->PlanAnio>0)
                  {
                  $Porcentaje = ($element->PlanAnio *100)/$row[0]->PlanAnio;
                  }
                  $element->PorcenPlanAnio=number_format($Porcentaje, 1, '.', '');
                    $CuatroCp +=number_format($Porcentaje, 1, '.', '');
                }
                 
                 if ($element->ActualAnio!='')
                {
                  $CincoCp += $element->ActualAnio;
                  
                   $Porcentaje=0;
                  if ($row[0]->ActualAnio>0)
                  {
                  $Porcentaje = ($element->ActualAnio *100)/$row[0]->ActualAnio;
                  }
                  $element->PorcentajeAnio=number_format($Porcentaje, 1, '.', '');
                    $SeisCp +=number_format($Porcentaje, 1, '.', '');
                }
                 
                if ($element->PlanMes!='')
                {
                  $SieteCp += $element->PlanMes;
                  
                  $Porcentaje=0;
                  if ($row[0]->PlanMes>0)
                  {
                  $Porcentaje = ($element->PlanMes *100)/$row[0]->PlanMes;
                  }
                  $element->PorcentajePlanMes=number_format($Porcentaje, 1, '.', '');
                    $OchoCp +=number_format($Porcentaje, 1, '.', '');
                }
                
                if ($element->ActualMes!='')
                {
                  $NueveCp += $element->ActualMes;
                  //Se saca el porcentaje por mes 
                  $PorcentajeMes=0;
                  if ($row[0]->ActualMes>0)
                  {
                  $PorcentajeMes = ($element->ActualMes *100)/$row[0]->ActualMes;
                  }
                  $element->PorcentajeMes=number_format($PorcentajeMes, 1, '.', '');
                    $DiezCp +=number_format($PorcentajeMes, 1, '.', '');
                }
                
            }
            else{
                if ($row[0]->AnioPasado!='')
                {
                $Uno=number_format($row[0]->AnioPasado, 1, '.', '');
                }
                $Dos=100;
                 if ($row[0]->PlanAnio!='')
                {
                $Tres=number_format($row[0]->PlanAnio, 1, '.', '');
                }
                $Cuatro=100;
                 if ($row[0]->ActualAnio!='')
                {
                $Cinco=number_format($row[0]->ActualAnio, 1, '.', '');
                }
                $Seis=100;
                 if ($row[0]->PlanMes!='')
                {
                $Siete=number_format($row[0]->PlanMes, 1, '.', '');
                }
                $Ocho=100;
                  if ($row[0]->ActualMes!='')
                {
                $Nueve=number_format($row[0]->ActualMes, 1, '.', '');
                }
                $Diez=100;
            }
            
           
           $count ++; 
        }
        
        //Cosotos Operacionales
        $data['UnoCp']=number_format($UnoCp, 1, '.', '');
        $data['DosCp']=number_format($DosCp, 1, '.', ''); 
        $data['TresCp']= number_format($TresCp, 1, '.', '');
        $data['CuatroCp']= number_format($CuatroCp, 1, '.', ''); 
        $data['CincoCp']=number_format($CincoCp, 1, '.', '');
        $data['SeisCp']= number_format($SeisCp, 1, '.', '');
        $data['SieteCp']= number_format($SieteCp, 1, '.', '');
        $data['OchoCp']=  number_format($OchoCp, 1, '.', ''); 
        $data['NueveCp']=number_format($NueveCp, 1, '.', ''); 
        $data['DiesCp']=number_format($DiezCp, 1, '.', '');
        
        //Costos Grossprofit
        $UnoGp=$Uno- number_format($UnoCp, 1, '.', '');
        $DosGp=$Dos- number_format($DosCp, 1, '.', '');
        $TresGp=$Tres- number_format($TresCp, 1, '.', '');
        $CuatroGp=$Cuatro- number_format($CuatroCp, 1, '.', '');
        $CincoGp=$Cinco- number_format($CincoCp, 1, '.', '');
        $SeisGp=$Seis- number_format($SeisCp, 1, '.', '');
        $SieteGp=$Siete- number_format($SieteCp, 1, '.', '');
        $OchoGp=$Ocho- number_format($OchoCp, 1, '.', '');
        $NueveGp=$Nueve- number_format($NueveCp, 1, '.', '');
        $DiesGp=$Diez- number_format($DiezCp, 1, '.', '');
        
        $data['UnoGp']= number_format($UnoGp, 1, '.', '');
        $data['DosGp']=  number_format($DosGp, 1, '.', ''); 
        $data['TresGp']=  number_format($TresGp, 1, '.', '');
        $data['CuatroGp']=number_format($CuatroGp, 1, '.', ''); 
        $data['CincoGp']= number_format($CincoGp, 1, '.', '');
        $data['SeisGp']=  number_format($SeisGp, 1, '.', '');
        $data['SieteGp']=  number_format($SieteGp, 1, '.', '');
        $data['OchoGp']= number_format($OchoGp, 1, '.', ''); 
        $data['NueveGp']=  number_format($NueveGp, 1, '.', ''); 
        $data['DiesGp']=  number_format($DiesGp, 1, '.', '');
        
        if ($Type==2)
        {   
            //*******DATOS G&A*****
             $oFinanzasActualizacion= new FinanzasActualizacion();
             $rowB= $oFinanzasActualizacion->ActualizacionCostos($IdSucursal,$Anio,$Mes,1,"",$IdEmpresa);
             
             $UnoTGA=$rowB['AnioPasadoT'];
             $TresTGA =$rowB['PlanAnioT'];
             $CincoTGA =$rowB['ActualAnioT'];
             $SieteTGA=$rowB['PlanMesT'];
             $ActualMesTGA=$rowB['ActualMesT'];
             
             //Porcentajes
             $DosTGA=0;
             if ($Uno>0)
             {
               $DosTGA= ($UnoTGA * 100) / $Uno;  
             }
              $CuatroTGA=0;
             if ($Tres>0)
             {
               $CuatroTGA= ($TresTGA * 100) / $Tres;  
             }
             
              $SeisTGA=0;
             if ($Cinco>0)
             {
               $SeisTGA= ($CincoTGA * 100) / $Cinco;  
             }
              $OchoTGA=0;
             if ($Siete>0)
             {
               $OchoTGA= ($SieteTGA * 100) / $Siete;  
             }
             $DiesTGA=0;
             if ($Nueve>0)
             {
               $DiesTGA= ($ActualMesTGA * 100) / $Nueve;  
             }
             
            
             
              $data ['UnoTGA']=$UnoTGA;
              $data ['DosTGA']=number_format($DosTGA, 1, '.', '');
              $data ['TresTGA']=$TresTGA;
              $data ['CuatroTGA']=number_format($CuatroTGA, 1, '.', '');
              $data ['CincoTGA']=$CincoTGA;
              $data ['SeisTGA']=number_format($SeisTGA, 1, '.', '');
              $data ['SieteTGA']=$SieteTGA;
              $data ['OchoTGA']=number_format($OchoTGA, 1, '.', '');
              $data ['ActualMesTGA']=$ActualMesTGA;
              $data ['DiesTGA']=number_format($DiesTGA, 1, '.', '');
            //********Datos Costo Depto venta******  
             $oFinanzasActualizacion= new FinanzasActualizacion();
             $rowB= $oFinanzasActualizacion->ActualizacionCostos($IdSucursal,$Anio,$Mes,2,1,$IdEmpresa);
             
             $UnoTDV=$rowB['AnioPasadoT'];
             $TresTDV =$rowB['PlanAnioT'];
             $CincoTDV =$rowB['ActualAnioT'];
             $SieteTDV=$rowB['PlanMesT'];
             $ActualMesTDV=$rowB['ActualMesT'];
             
              $oFinanzasActualizacion= new FinanzasActualizacion();
             $rowB= $oFinanzasActualizacion->ActualizacionCostos($IdSucursal,$Anio,$Mes,2,2,$IdEmpresa);
             
                $UnoTDV2=$rowB['AnioPasadoT'];
             $TresTDV2 =$rowB['PlanAnioT'];
             $CincoTDV2 =$rowB['ActualAnioT'];
             $SieteTDV2=$rowB['PlanMesT'];
             $ActualMesTDV2=$rowB['ActualMesT'];
             
             $UnoTDVT=$UnoTDV + $UnoTDV2;
             $TresTDVT=$TresTDV + $TresTDV2;
             $CincoTDVT=$CincoTDV + $CincoTDV2;
             $SieteTDVT=$SieteTDV + $SieteTDV2;
             $ActualMesTDVT=$ActualMesTDV + $ActualMesTDV2;
             
                  //Porcentajes
             $DosTDVT=0;
             if ($Uno>0)
             {
               $DosTDVT= ($UnoTDVT * 100) / $Uno;  
             }
              $CuatroTDVT=0;
             if ($Tres>0)
             {
               $CuatroTDVT= ($TresTDVT * 100) / $Tres;  
             }
             
              $SeisTDVT=0;
             if ($Cinco>0)
             {
               $SeisTDVT= ($CincoTDVT * 100) / $Cinco;  
             }
              $OchoTDVT=0;
             if ($Siete>0)
             {
               $OchoTDVT= ($SieteTDVT * 100) / $Siete;  
             }
             $DiesTDVT=0;
             if ($Nueve>0)
             {
               $DiesTDVT= ($ActualMesTDVT * 100) / $Nueve;  
             }
             
              $data ['UnoTDV']=$UnoTDVT;
              $data ['DosTDV']=number_format($DosTDVT, 1, '.', '');
              $data ['TresTDV']=$TresTDVT;
              $data ['CuatroTDV']=number_format($CuatroTDVT, 1, '.', '');
              $data ['CincoTDV']=$CincoTDVT;
              $data ['SeisTDV']=number_format($SeisTDVT, 1, '.', '');
              $data ['SieteTDV']=$SieteTDVT;
              $data ['OchoTDV']=number_format($OchoTDVT, 1, '.', '');
              $data ['ActualMesTDV']=$ActualMesTDVT;
              $data ['DiesTDV']=number_format($DiesTDVT, 1, '.', '');
              //********Datos Costo Operaciones******  
              
               $oFinanzasActualizacion= new FinanzasActualizacion();
             $rowB= $oFinanzasActualizacion->ActualizacionCostos($IdSucursal,$Anio,$Mes,3,"",$IdEmpresa);
             
             $UnoTCO=$rowB['AnioPasadoT'];
             $TresTCO =$rowB['PlanAnioT'];
             $CincoTCO =$rowB['ActualAnioT'];
             $SieteTCO=$rowB['PlanMesT'];
             $ActualMesTCO=$rowB['ActualMesT'];
             
             //Porcentajes
             $DosTCO=0;
             if ($Uno>0)
             {
               $DosTCO= ($UnoTCO * 100) / $Uno;  
             }
              $CuatroTCO=0;
             if ($Tres>0)
             {
               $CuatroTCO= ($TresTCO * 100) / $Tres;  
             }
             
              $SeisTCO=0;
             if ($Cinco>0)
             {
               $SeisTCO= ($CincoTCO * 100) / $Cinco;  
             }
              $OchoTCO=0;
             if ($Siete>0)
             {
               $OchoTCO= ($SieteTCO * 100) / $Siete;  
             }
             $DiesTCO=0;
             if ($Nueve>0)
             {
               $DiesTCO= ($ActualMesTCO * 100) / $Nueve;  
             }
             
              $data ['UnoTCO']=$UnoTCO;
              $data ['DosTCO']=number_format($DosTCO, 1, '.', '');
              $data ['TresTCO']=$TresTCO;
              $data ['CuatroTCO']=number_format($CuatroTCO, 1, '.', '');
              $data ['CincoTCO']=$CincoTCO;
              $data ['SeisTCO']=number_format($SeisTCO, 1, '.', '');
              $data ['SieteTCO']=$SieteTCO;
              $data ['OchoTCO']=number_format($OchoTCO, 1, '.', '');
              $data ['ActualMesTCO']=$ActualMesTCO;
              $data ['DiesTCO']=number_format($DiesTCO, 1, '.', '');
               //********Datos Costo Vehiculos******  
              
               $oFinanzasActualizacion= new FinanzasActualizacion();
             $rowB= $oFinanzasActualizacion->ActualizacionCostos($IdSucursal,$Anio,$Mes,4,"",$IdEmpresa);
             
             $UnoTCV=$rowB['AnioPasadoT'];
             $TresTCV =$rowB['PlanAnioT'];
             $CincoTCV =$rowB['ActualAnioT'];
             $SieteTCV=$rowB['PlanMesT'];
             $ActualMesTCV=$rowB['ActualMesT'];
             
                //Porcentajes
             $DosTCV=0;
             if ($Uno>0)
             {
               $DosTCV= ($UnoTCV * 100) / $Uno;  
             }
              $CuatroTCV=0;
             if ($Tres>0)
             {
               $CuatroTCV= ($TresTCV * 100) / $Tres;  
             }
             
              $SeisTCV=0;
             if ($Cinco>0)
             {
               $SeisTCV= ($CincoTCV * 100) / $Cinco;  
             }
              $OchoTCV=0;
             if ($Siete>0)
             {
               $OchoTCV= ($SieteTCV * 100) / $Siete;  
             }
             $DiesTCV=0;
             if ($Nueve>0)
             {
               $DiesTCV= ($ActualMesTCV * 100) / $Nueve;  
             }
             
             
              $data ['UnoTCV']=$UnoTCV;
              $data ['DosTCV']=number_format($DosTCV, 1, '.', '');
              $data ['TresTCV']=$TresTCV;
              $data ['CuatroTCV']=number_format($CuatroTCV, 1, '.', '');
              $data ['CincoTCV']=$CincoTCV;
              $data ['SeisTCV']=number_format($SeisTCV, 1, '.', '');
              $data ['SieteTCV']=$SieteTCV;
              $data ['OchoTCV']=number_format($OchoTCV, 1, '.', '');
              $data ['ActualMesTCV']=$ActualMesTCV;
              $data ['DiesTCV']=number_format($DiesTCV, 1, '.', '');
              
              //********Datos Ingresos y Egresos******  
              //iNGRESOS
              $oFinanzasActualizacion= new FinanzasActualizacion();
              $rowB= $oFinanzasActualizacion->ActualizacionCostos($IdSucursal,$Anio,$Mes,5,1,$IdEmpresa);

              $UnoTIE=$rowB['AnioPasadoT'];
              $TresTIE =$rowB['PlanAnioT'];
              $CincoTIE =$rowB['ActualAnioT'];
              $SieteTIE=$rowB['PlanMesT'];
              $ActualMesTIE=$rowB['ActualMesT'];
           
             
              //eGRESOS
              $oFinanzasActualizacion= new FinanzasActualizacion();
              $rowB= $oFinanzasActualizacion->ActualizacionCostos($IdSucursal,$Anio,$Mes,5,2,$IdEmpresa);

             $UnoTIE2=$rowB['AnioPasadoT'];
             $TresTIE2 =$rowB['PlanAnioT'];
             $CincoTIE2 =$rowB['ActualAnioT'];
             $SieteTIE2=$rowB['PlanMesT'];
             $ActualMesTIE2=$rowB['ActualMesT'];
             
             $UnoTIET=$UnoTIE - $UnoTIE2;
             $TresTIET=$TresTIE -$TresTIE2;
             $CincoTIET=$CincoTIE - $CincoTIE2;
             $SieteTIET=$SieteTIE -$SieteTIE2;
             $ActualMesTIET=$ActualMesTIE -$ActualMesTIE2;
             
               //Porcentajes
             $DosTIET=0;
             if ($Uno>0)
             {
               $DosTIET= ($UnoTCV * 100) / $Uno;  
             }
              $CuatroTIET=0;
             if ($Tres>0)
             {
               $CuatroTIET= ($TresTCV * 100) / $Tres;  
             }
             
              $SeisTIET=0;
             if ($Cinco>0)
             {
               $SeisTIET= ($CincoTCV * 100) / $Cinco;  
             }
              $OchoTIET=0;
             if ($Siete>0)
             {
               $OchoTIET= ($SieteTCV * 100) / $Siete;  
             }
             $DiesTIET=0;
             if ($Nueve>0)
             {
               $DiesTIET= ($ActualMesTCV * 100) / $Nueve;  
             }
             
              
              $data ['UnoTIE']=$UnoTIET;
              $data ['DosTIE']=number_format($DosTIET, 1, '.', '');
              $data ['TresTIE']=$TresTIET;
              $data ['CuatroTIE']=number_format($CuatroTIET, 1, '.', '');
              $data ['CincoTIE']=$CincoTIET;
              $data ['SeisTIE']=number_format($SeisTIET, 1, '.', '');
              $data ['SieteTIE']=$SieteTIET;
              $data ['OchoTIE']=number_format($OchoTIET, 1, '.', '');
              $data ['ActualMesTIE']=$ActualMesTIET;
              $data ['DiesTIE']=number_format($DiesTIET, 1, '.', '');
               //varianza BURDEN  
               //Costo departamento operaciones menos el bureden
            $UnoTVB= $UnoTCO - $row[7]->AnioPasado;
            $TresTVB= $TresTCO- $row[7]->PlanAnio;
            $CincoTVB= $CincoTCO - $row[7]->ActualAnio;
            $SieteTVB= $SieteTCO - $row[7]->PlanMes;
            $ActualMesTVB= $ActualMesTCO - $row[7]->ActualMes;
            
                 //Porcentajes
             $DosTVB=0;
             if ($Uno>0)
             {
               $DosTVB= ($UnoTVB * 100) / $Uno;  
             }
              $CuatroTVB=0;
             if ($Tres>0)
             {
               $CuatroTVB= ($TresTVB * 100) / $Tres;  
             }
             
              $SeisTVB=0;
             if ($Cinco>0)
             {
               $SeisTVB= ($CincoTVB * 100) / $Cinco;  
             }
              $OchoTVB=0;
             if ($Siete>0)
             {
               $OchoTVB= ($SieteTVB * 100) / $Siete;  
             }
             $DiesTVB=0;
             if ($Nueve>0)
             {
               $DiesTVB= ($ActualMesTVB * 100) / $Nueve;  
             }
             
            
            $data ['UnoTVB']=  $UnoTVB;
            $data ['DosTVB']=number_format($DosTVB, 1, '.', '');
            $data ['TresTVB']=$TresTVB;
            $data ['CuatroTVB']=number_format($CuatroTVB, 1, '.', '');
            $data ['CincoTVB']=$CincoTVB;
            $data ['SeisTVB']=number_format($SeisTVB, 1, '.', '');
            $data ['SieteTVB']=$SieteTVB;
            $data ['OchoTVB']=number_format($OchoTVB, 1, '.', '');
            $data ['ActualMesTVB']=$ActualMesTVB;
            $data ['DiesTVB']=number_format($DiesTVB, 1, '.', '');  
            //varianza VEHICULO
            $UnoTVV=$UnoTCV - $row[4]->AnioPasado;
            $TresTVV= $TresTCV- $row[4]->PlanAnio;
            $CincoTVV=$CincoTCV - $row[4]->ActualAnio;
            $SieteTVV=$SieteTCV - $row[4]->PlanMes;
            $ActualMesTVV=$ActualMesTCV - $row[4]->ActualMes;
            
                //Porcentajes
             $DosTVV=0;
             if ($Uno>0)
             {
               $DosTVV= ($UnoTVV * 100) / $Uno;  
             }
              $CuatroTVV=0;
             if ($Tres>0)
             {
               $CuatroTVV= ($TresTVV * 100) / $Tres;  
             }
             
              $SeisTVV=0;
             if ($Cinco>0)
             {
               $SeisTVV= ($CincoTVV * 100) / $Cinco;  
             }
              $OchoTVV=0;
             if ($Siete>0)
             {
               $OchoTVV= ($SieteTVV * 100) / $Siete;  
             }
             $DiesTVV=0;
             if ($Nueve>0)
             {
               $DiesTVV= ($ActualMesTVV * 100) / $Nueve;  
             }
            
            $data ['UnoTVV']=  $UnoTVV;
            $data ['DosTVV']= number_format($DosTVV, 1, '.', '');  
            $data ['TresTVV']=$TresTVV;
            $data ['CuatroTVV']=number_format($CuatroTVV, 1, '.', '');
            $data ['CincoTVV']=$CincoTVV;
            $data ['SeisTVV']=number_format($SeisTVV, 1, '.', '');
            $data ['SieteTVV']=$SieteTVV;
            $data ['OchoTVV']=number_format($OchoTVV, 1, '.', '');
            $data ['ActualMesTVV']=$ActualMesTVV;
            $data ['DiesTVV']=number_format($DiesTVV, 1, '.', '');
            
            //Net Proffit
        
            $UnoTNP=$UnoGp - $UnoTGA - $UnoTDVT - $UnoTVB -$UnoTVV + $UnoTIET ;
            $TresTNP= $TresGp - $TresTGA - $TresTDVT - $TresTVB -$TresTVV + $TresTIET ;
            $CincoTNP=$CincoGp - $CincoTGA - $CincoTDVT - $CincoTVB -$CincoTVV + $CincoTIET;
            $SieteTNP=$SieteGp - $SieteTGA - $SieteTDVT - $SieteTVB -$SieteTVV + $SieteTIET ;
            $ActualMesTNP=$NueveGp - $ActualMesTGA - $ActualMesTDVT - $ActualMesTVB -$ActualMesTVV  + $ActualMesTIET;
            
               //Porcentajes
             $DosTNP=0;
             if ($Uno>0)
             {
               $DosTNP= ($UnoTNP * 100) / $Uno;  
             }
              $CuatroTNP=0;
             if ($Tres>0)
             {
               $CuatroTNP= ($TresTNP * 100) / $Tres;  
             }
             
              $SeisTNP=0;
             if ($Cinco>0)
             {
               $SeisTNP= ($CincoTNP * 100) / $Cinco;  
             }
              $OchoTNP=0;
             if ($Siete>0)
             {
               $OchoTNP= ($SieteTNP * 100) / $Siete;  
             }
             $DiesTNP=0;
             if ($Nueve>0)
             {
               $DiesTNP= ($ActualMesTNP * 100) / $Nueve;  
             }
            
            $data ['UnoTNP']=  $UnoTNP;
            $data ['DosTNP']=number_format($DosTNP, 1, '.', ''); 
            $data ['TresTNP']=$TresTNP;
            $data ['CuatroTNP']=number_format($CuatroTNP, 1, '.', ''); 
            $data ['CincoTNP']=$CincoTNP;
            $data ['SeisTNP']=number_format($SeisTNP, 1, '.', '');
            $data ['SieteTNP']=$SieteTNP;
            $data ['OchoTNP']=number_format($OchoTNP, 1, '.', '');
            $data ['ActualMesTNP']=$ActualMesTNP;
            $data ['DiesTNP']=number_format($DiesTNP, 1, '.', '');
              
        }
        
        
         $data['row']=$row;
        
         
        return $data;
    }
    
    
    
    
    
    //Esta validacion es la que divide los trimestres 
     public function Validacion($Mes=8,$row,$anio,$IdSucursal,$IdConfigS,$IdSubtipoServ,$IdCliente="",$IdClienteS="",$IdContrato="")
    {

      $MesFalso=1;
      //Datos Facturacion
      $FacturacionMes="";
      $FacturacionAnual="";        
      //datos de los servicios
      $BurdenT="" ;
      $ViaticosT=""; 
      $ContratistaT="";
      $CostoV=""; 
      $ManoObraT="";
      $EquipoT=""; 
      $MaterialesT="";
      
        
      $MesBusqueda=$Mes;
      if ($Mes<10)
      {
        $MesBusqueda ="0".intval($Mes);
      }
        
      //se busca todo lo que se agenerado en servicios de la fecha actual mes y año  para compararlo con mes actual financiero
      $oMservicio = new Mservicio();
      $oMservicio->IdConfigS=$IdConfigS;
      $oMservicio->Tipo_Serv=$IdSubtipoServ;
      $oMservicio->IdSucursal=$IdSucursal;
      $oMservicio->Fecha_F=$anio."-".$MesBusqueda;
      $oMservicio->TypeFinanciero=1;
      $oMservicio->IdCliente=$IdCliente;
      $oMservicio->IdClienteS=$IdClienteS;
      $oMservicio->IdContrato=$IdContrato;
      $dataf1=  $oMservicio->get_costosfinancieros();
            
      $BurdenT=$dataf1['data']->BurdenT;
      $ViaticosT=$dataf1['data']->ViaticosT;
      $ContratistaT=$dataf1['data']->ContratistaT;
      $CostoV=$dataf1['data']->CostoV;
      $ManoObraT=$dataf1['data']->ManoObraT;
      $EquipoT=$dataf1['data']->EquipoT;
      $MaterialesT=$dataf1['data']->MaterialesT;
      //se busca todo lo que se agenerado en servicios de enero hasta el mes actual del presente año para comprarlo con año actual financiero
      $oMservicio->Fecha_I=$anio."-01-01";
      $oMservicio->Fecha_F=$anio."-".$MesBusqueda.'-31';
      $oMservicio->TypeFinanciero=2;
      $dataf2=  $oMservicio->get_costosfinancieros();
      
      $BurdenT2=$dataf2['data']->BurdenT;
      $ViaticosT2=$dataf2['data']->ViaticosT;
      $ContratistaT2=$dataf2['data']->ContratistaT;
      $CostoV2=$dataf2['data']->CostoV;
      $ManoObraT2=$dataf2['data']->ManoObraT;
      $EquipoT2=$dataf2['data']->EquipoT;
      $MaterialesT2=$dataf2['data']->MaterialesT;
            
      //se obtiene la facturacion actual año actual y la facturacion actual mes actual financiero
      $oMestadofinanciero= new Mestadofinanciero();
      $oMestadofinanciero->IdSucursal=$IdSucursal;
      $oMestadofinanciero->Anio=$anio;
      $oMestadofinanciero->Mes=$MesBusqueda;
      $oMestadofinanciero->IdConfigS=$IdConfigS;
      $oMestadofinanciero->IdTipoServ=$IdSubtipoServ;
      $oMestadofinanciero->IdCliente=$IdCliente;
      $oMestadofinanciero->IdClienteS=$IdClienteS;
      $oMestadofinanciero->IdContrato=$IdContrato;
      $datafinanciera= $oMestadofinanciero->get_estadofinanciero();
            
      $FacturacionMes= $datafinanciera['data']->FacturacionMes;
      $FacturacionAnual=$datafinanciera['data']->FacturacionCompleto;
          
      $FactMes=$FacturacionMes;

      if ($FactMes=="")
      {
        $FacturacionMes=0;   
      }
      $FactAnual=$FacturacionAnual;
      if ($FactAnual=="")
      {
        $FactAnual=0;   
      }
         
        
      if ($Mes==4){$MesFalso=1 ;} //es el trimestre el mes falso es decir 1 mer timertre 2 trimestre etc.  4 inicia el timestre en uno por que es abril (enero(1), febrero(2), marzo(3))
      if ($Mes==5){$MesFalso=2 ;}
      if ($Mes==6){$MesFalso=3 ;}
      if ($Mes==7){$MesFalso=1 ;}
      if ($Mes==8){$MesFalso=2 ;}
      if ($Mes==9){$MesFalso=3 ;}
      if ($Mes==10){$MesFalso=1 ;}
      if ($Mes==11){$MesFalso=2 ;}
      if ($Mes==12){$MesFalso=3 ;}
      
      $contador=0;

      foreach ($row as $element)
      {
        
        //se reinician los valores a 0 
        $this->AnioPasado=0;
        $this->PlanMes =0;
        
        $this->PlanAnio  =0;
        $this->AnioPasado +=$element->AnioAnterior;
            
        //De Enero al mes actual 
        if ($Mes<=3)
        {
            $Valor= $element->PrimerT /3;
            $this->PlanAnio += $Valor*$Mes;
            $this->PlanMes=$Valor;
        }

        if ($Mes>3 && $Mes <=6)
        {
          $Valor= $element->SegundoT /3;
          $this->PlanAnio +=($Valor*$MesFalso)+$element->PrimerT;
          $this->PlanMes=$Valor;
        }
        
        if ($Mes>6 && $Mes <=9)
        {
          $Valor= $element->TercerT /3;
          $this->PlanAnio +=($Valor*$MesFalso)+$element->SegundoT+$element->PrimerT;
          $this->PlanMes=$Valor;
        }
        
        if ($Mes>9 && $Mes <=12)
        {
          $Valor= $element->CuartoT /3;
          $this->PlanAnio += ($Valor*$MesFalso) + $element->TercerT + $element->SegundoT + $element->PrimerT;
          $this->PlanMes=$Valor;
        }
            
        $PorcentajePlanAnio=100;
        $PorcentajePlanMes=100;

        if ($contador>0)
        {
            $PorcentajePlanAnio=0;
            $PorcentajePlanMes=0;

            if ($row[0]->PlanAnio>0)
            {
                $PorcentajePlanAnio= (round($this->PlanAnio, 0, PHP_ROUND_HALF_UP) * 100)/$row[0]->PlanAnio;
            }  
            if ($row[0]->PlanMes>0)
            {
                $PorcentajePlanMes= (round($this->PlanMes, 0, PHP_ROUND_HALF_UP) * 100)/$row[0]->PlanMes;
            } 
        }

        if ($contador==0){
          $row[$contador]->ActualMes=round($FactMes, 0, PHP_ROUND_HALF_UP);
          $row[$contador]->PorcentajeMes=round(100, 0, PHP_ROUND_HALF_UP);  

          $row[$contador]->ActualAnio=round($FactAnual, 0, PHP_ROUND_HALF_UP);
          $row[$contador]->PorcentajeAnio=round(100, 0, PHP_ROUND_HALF_UP); 
        }

        if ($contador == 1)
        {
            //Porcentaje mesual
            $PorcenMes=0;

            if ($FactMes>0)
            {
              $PorcenMes= (round($MaterialesT, 0, PHP_ROUND_HALF_UP) * 100 )/round($FactMes, 0, PHP_ROUND_HALF_UP);
            }
            
            $row[$contador]->ActualMes=round($MaterialesT, 0, PHP_ROUND_HALF_UP);
            $row[$contador]->PorcentajeMes=round($PorcenMes, 0, PHP_ROUND_HALF_UP);
            
            //Porcentaje anual
            $PorcenMes2=0;
            if ($FactAnual>0)
            {
              $PorcenMes2= (round($MaterialesT2, 0, PHP_ROUND_HALF_UP) * 100 )/round($FactAnual, 0, PHP_ROUND_HALF_UP);
            }
            
            $row[$contador]->ActualAnio=round($MaterialesT2, 0, PHP_ROUND_HALF_UP);
            $row[$contador]->PorcentajeAnio=round($PorcenMes2, 0, PHP_ROUND_HALF_UP);
        }

        if ($contador==2)
        {
            
          
            //Porcentaje mesual
            $PorcenMes=0;

            if ($FactMes>0)
            {
              $PorcenMes= (round($EquipoT, 0, PHP_ROUND_HALF_UP) * 100 )/round($FactMes, 0, PHP_ROUND_HALF_UP);
            }
            
            $row[$contador]->ActualMes=round($EquipoT, 0, PHP_ROUND_HALF_UP) ;
            $row[$contador]->PorcentajeMes=round($PorcenMes, 0, PHP_ROUND_HALF_UP);
            
            //Porcentaje anual
            $PorcenMes2=0;
            if ($FactAnual>0)
            {
              $PorcenMes2= (round($EquipoT2, 0, PHP_ROUND_HALF_UP) * 100 )/round($FactAnual, 0, PHP_ROUND_HALF_UP);
            }
            
            $row[$contador]->ActualAnio=round($EquipoT2, 0, PHP_ROUND_HALF_UP) ;
            $row[$contador]->PorcentajeAnio=round($PorcenMes2, 0, PHP_ROUND_HALF_UP);
        }

        if ($contador==3)
        { 
          //Porcentaje mesual
            $PorcenMes=0;

            if ($FactMes>0)
            {
              $PorcenMes= (round($ManoObraT, 0, PHP_ROUND_HALF_UP) * 100 )/round($FactMes, 0, PHP_ROUND_HALF_UP);
            }
            
            $row[$contador]->ActualMes=round($ManoObraT, 0, PHP_ROUND_HALF_UP) ;
            $row[$contador]->PorcentajeMes=round($PorcenMes, 0, PHP_ROUND_HALF_UP);
            
                //Porcentaje anual
            $PorcenMes2=0;
            if ($FactAnual>0)
            {
              $PorcenMes2= (round($ManoObraT2, 0, PHP_ROUND_HALF_UP) * 100 )/round($FactAnual, 0, PHP_ROUND_HALF_UP);
            }
            
            $row[$contador]->ActualAnio=round($ManoObraT2, 0, PHP_ROUND_HALF_UP) ;
            $row[$contador]->PorcentajeAnio=round($PorcenMes2, 0, PHP_ROUND_HALF_UP);
        }

        if ($contador==4)
        {
            //Porcentaje mesual
            $PorcenMes=0;

            if ($FactMes>0)
            {
              $PorcenMes= (round($CostoV, 0, PHP_ROUND_HALF_UP) * 100 )/round($FactMes, 0, PHP_ROUND_HALF_UP);
            }
            
            $row[$contador]->ActualMes=round($CostoV, 0, PHP_ROUND_HALF_UP) ;
            $row[$contador]->PorcentajeMes=round(0, 0, PHP_ROUND_HALF_UP);
            
                  //Porcentaje anual
            $PorcenMes2=0;
            if ($FactAnual>0)
            {
              $PorcenMes2= (round($CostoV2, 0, PHP_ROUND_HALF_UP) * 100 )/round($FactAnual, 0, PHP_ROUND_HALF_UP);
            }
            
            $row[$contador]->ActualAnio=round($CostoV2, 0, PHP_ROUND_HALF_UP) ;
            $row[$contador]->PorcentajeAnio=round($PorcenMes2, 0, PHP_ROUND_HALF_UP);
        }
            
        if ($contador==5)
        {
          
            //Porcentaje mesual
            $PorcenMes=0;

            if ($FactMes>0)
            {
              $PorcenMes= (round($ContratistaT, 0, PHP_ROUND_HALF_UP) * 100 )/round($FactMes, 0, PHP_ROUND_HALF_UP);
            }
            
            $row[$contador]->ActualMes=round($ContratistaT, 0, PHP_ROUND_HALF_UP) ;
            $row[$contador]->PorcentajeMes=round(0, 0, PHP_ROUND_HALF_UP);
            
              //Porcentaje anual
            $PorcenMes2=0;
            if ($FactAnual>0)
            {
              $PorcenMes2= (round($ContratistaT2, 0, PHP_ROUND_HALF_UP) * 100 )/round($FactAnual, 0, PHP_ROUND_HALF_UP);
            }
            
            $row[$contador]->ActualAnio=round($ContratistaT2, 0, PHP_ROUND_HALF_UP);
            $row[$contador]->PorcentajeAnio=round($PorcenMes2, 0, PHP_ROUND_HALF_UP);
        }

        if ($contador==6)
        {
            //Porcentaje mesual
            $PorcenMes=0;

            if ($FactMes>0)
            {
              $PorcenMes= (round($ViaticosT, 0, PHP_ROUND_HALF_UP) * 100 )/round($FactMes, 0, PHP_ROUND_HALF_UP);
            }
            
            $row[$contador]->ActualMes=round($ViaticosT, 0, PHP_ROUND_HALF_UP) ;
            $row[$contador]->PorcentajeMes=round(0, 0, PHP_ROUND_HALF_UP); 
            
              //Porcentaje anual
            $PorcenMes2=0;
            if ($FactAnual>0)
            {
              $PorcenMes2= (round($ViaticosT2, 0, PHP_ROUND_HALF_UP) * 100 )/round($FactAnual, 0, PHP_ROUND_HALF_UP);
            }
            
            $row[$contador]->ActualAnio=round($ViaticosT2, 0, PHP_ROUND_HALF_UP) ;
            $row[$contador]->PorcentajeAnio=round($PorcenMes2, 0, PHP_ROUND_HALF_UP);
        }

        if ($contador==7)
        {
            $PorcenMes=0;
            if ($FactMes>0)
            {
              $PorcenMes= (round($BurdenT, 0, PHP_ROUND_HALF_UP) * 100 )/round($FactMes, 0, PHP_ROUND_HALF_UP);
            }
            
            
            $row[$contador]->ActualMes=round($BurdenT, 0, PHP_ROUND_HALF_UP) ;
            $row[$contador]->PorcentajeMes=round(0, 0, PHP_ROUND_HALF_UP); 
            
                //Porcentaje anual
            $PorcenMes2=0;
            if ($FactAnual>0)
            {
              $PorcenMes2= (round($BurdenT2, 0, PHP_ROUND_HALF_UP) * 100 )/round($FactAnual, 0, PHP_ROUND_HALF_UP);
            }
            
            $row[$contador]->ActualAnio=round($BurdenT2, 0, PHP_ROUND_HALF_UP) ;
            $row[$contador]->PorcentajeAnio=round($PorcenMes2, 0, PHP_ROUND_HALF_UP);
        }
            
            
        $row[$contador]->AnioPasado=round($this->AnioPasado, 0, PHP_ROUND_HALF_UP);
        $row[$contador]->PlanAnio=round($this->PlanAnio, 0, PHP_ROUND_HALF_UP);
        $row[$contador]->PorcenPlanAnio= number_format($PorcentajePlanAnio, 1, '.', '');
        $row[$contador]->PlanMes=round($this->PlanMes, 0, PHP_ROUND_HALF_UP);
        $row[$contador]->PorcentajePlanMes=number_format($PorcentajePlanMes, 1, '.', '');

        $contador ++;
        
      } 
       
      
       return $row;
    }
    
}
