<?php
//defined("BASEPATH") or die("El acceso al script no está permitido");
defined('BASEPATH') or exit('No direct script access allowed');

class Bitacora
{
     public $TablaPadre;
     public $Categoria;
     public $RegEstatus;
     public $Alias;
     public $contadorpadre;
     public $Arreglo;
     public $Tipo;
   
      public $Mensaje;
     public function __construct()
    {
         $CI =& get_instance();
         $CI->load->model('Mbitacora');
         $CI->load->model('Mclientes');
         
         $this->TablaPadre='';
         $this->Categoria='';
         $this->RegEstatus='';
         $this->Alias='';
         $this->contadorpadre='';
         $this->Arreglo=array();
         $this->Tipo='';
          $this->Mensaje='';

    }
    
    function CreateBitacora()
    {
      
        foreach ($this->Arreglo['Tablas'] as $element =>$key)
        {
             
            if ($this->TablaPadre=='')
            {
                $this->TablaPadr=$key['Nombre'];
                $this->Alias = $key['Alias'];
                
                if (isset($key['Categoria']))
                {
                  $this->Categoria =$key['Categoria']; 
                }
            }
              $this->Tipo =$key['Tipo'];
              $IdTabla =$key['Id'];
              $Campo =$key['CampoId'];
              $oMbitacora= new Mbitacora();
              $oMbitacora->Id=$IdTabla;
              $oMbitacora->Tabla=$key['Nombre'];
               if (isset($key['RegEstatus']))
                {
                  $oMbitacora->RegEstatus =$key['RegEstatus']; 
                }
              $oMbitacora->Campo=$Campo;
              $row= $oMbitacora->get_recovery_Data();
          
            $contadorh=0;
            //El tipo N es normal que es una tabla unica 
             if ($this->Tipo=='N')
                    {
                         
                  foreach ($row as $bd)
                  {
              
                      $contador=0;
                     // print_r($key['Valores'][0]->IdRecepcionCalibracion);
                     foreach ($key['Campos'] as $campo)
                    {  
                        $NombreCampo=$key['Campos2'][$contador];
                        if ($NombreCampo =='')
                        {
                            $NombreCampo =$key['Campos'][$contador];
                        }
                        if ($bd-> $campo !=$key['Valores'][0]-> $campo)
                        {
                             $this->Mensaje .=$NombreCampo.' fue modificado de '.$bd-> $campo.' a '.$key['Valores'][0]-> $campo.', ';   
                        }
                          $contador ++;
                    }
                    if (isset($key['Foraneas']))
                    {
                        
                    
                         foreach ($key['Foraneas'] as $data )
                        { 
                                 $NombreCam=  $data['Nombre'];
                              
                                $IdCamp=$data['CampoId'];
                                $BuscarBD=$data['BuscarBD'];
                       
                                  $Id=$data['Id'];   
                   
                              if ($bd-> $IdCamp !=$Id)
                                {
                                //Esta es el que busca de la base de datos antiguo
                                $oMbitacora= new Mbitacora();
                                $oMbitacora->Id=$bd-> $IdCamp;
                              
                                $oMbitacora->Tabla=$data['Tabla'];
                                $oMbitacora->Campo=$data['CampoId'];
                                if (isset($data['CampoId2']))
                                {
                                 $oMbitacora->Campo2=$data['CampoId2'];
                                 $oMbitacora->Id2=$data['Id2'];
                                }
                                $row2= $oMbitacora->get_recovery_Data();
                                
                                $NombreAntiguo=$row2[0]->$BuscarBD;
                                
                                //Esta es el que busca en base al id nuevo que se esta enviando
                                $oMbitacora= new Mbitacora();
                                 $oMbitacora->Tabla=$data['Tabla'];
                                $oMbitacora->Id=$Id;
                                 $oMbitacora->Campo=$data['CampoId'];
                                 if (isset($data['CampoId2']))
                                  {                        
                                   $oMbitacora->Campo2=$data['CampoId2'];
                                   $oMbitacora->Id2=$data['Id2'];
                                  }
                                $row2= $oMbitacora->get_recovery_Data();
                                
                                 $NombreActual=$row2[0]->$BuscarBD;
                                  
                                  $this->Mensaje .=$NombreCam.' fue modificado de '.$NombreAntiguo.' a '.$NombreActual.', ';   
                              } 
                            
                        }
                    }
       
                  }
              }
              else if ($this->Tipo=='D')
              {//El tipo D es detalle 
                /*
                      foreach ($key['Campos'] as $campo)
                    {
                        
                      $count=0;
                           foreach ($row as $bd)
                        { 
                            $NombreCampo=$key['Campos2'][0][$count];
                             $NombreCampo=$campo;
                                if ($NombreCampo !='')
                                {
                                    $NombreCampo =$campo;
                                }
                            $Fila=$count +1; 
                             if (isset($key['Valores'][0][$count]->$campo ))
                             {
                               if ($bd-> $campo != $key['Valores'][0][$count]->$campo)
                                {     
                                    $Mensaje .=$NombreCampo.' fue modificado de '.$bd-> $campo.' a '.$key['Valores'][0][$count]-> $campo.' en el detalle '.$Alias.' en la fila '.$Fila.', ';
                                }
                            }
                            else
                            {//Si entra en el else esque ya no existe en la lista enviada pero si el la bd
                                  $Mensaje .=' la Fila '.$Fila.' fue eliminada del detalle '.$Alias.' , ';    
                            }
                            $count ++;
                         } 
                    }
                  
                    if (count($key['Valores'][0])>count($row))
                    {
                        $anadidas=count($key['Valores'][0])-count($row);
                       $Mensaje .=' se añadieron '.$anadidas.' filas en el  detalle '.$Alias.' ,';     
                    }
                  */
              }
      
              $this->contadorpadre ++;
        }
        if ( $this->Mensaje!='')
        {
            echo   $this->Mensaje;
          /*
              $oMusuario = new Musuario();
              $oMusuario->IdUsuario=$IdUsuario;
              $oMusuario->get_recovery_usuario();
              $Mensaje .=' de la tabla  '.$Categoria;
            
             $oMbitacora= new Mbitacora();
             $oMbitacora->Id=$key['Id'];
             $oMbitacora->Tabla=$TablaPadre;
             $oMbitacora->Fecha=date('Y-m-d');
             $oMbitacora->Hora=date('H:i:s');
             $oMbitacora->Usuario=$oMusuario->Nombre.' '.$oMusuario->Apellido;
             $oMbitacora->Detalle=$Mensaje;
             $oMbitacora->Tipo=$Categoria;
             $oMbitacora->FechaDate=date('Y-m-d H:i:s');
             $oMbitacora->set_insert();
           */ 
        }
      }

    
    
}
