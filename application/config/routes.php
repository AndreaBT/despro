<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Csegurity/LoadLogin';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
#region rutas de las vistas
$route['principal'] = 'vista/Cadmin';
#enregion
#region RUTA MIGRACIONES
$route['database/migrate'] = 'Cmigration/Execute';
$route['database/migrate/(:num)'] = 'Cmigration/ExecuteVersion/$1';
#endregion
#region APP
$route['api/v.1/app/post'] = 'catalogos/Clogin/Loginapp'; //'app/Cservices/Loginapp'; // se cambiaron de ruta por el settimezone
$route['api/v.1/app/LoginCrm'] = 'catalogos/Clogin/LoginappCrm'; //'app/Cservices/LoginappCrm'; // se cambiaron de ruta por el settimezone

$route['api/v.1/app/listservicio'] = 'app/Cservices/Listservices';
$route['api/v.1/app/listteam'] = 'app/Cservices/ListEquipoTrab';
$route['api/v.1/app/getservicio'] = 'app/Cservices/RecoveryServ';
$route['api/v.1/app/updateestatus'] = 'app/Cservices/Serviciochange';
$route['api/v.1/app/firmapost'] = 'app/Cservices/firmaservicio';
$route['api/v.1/app/uploadfile'] = 'app/Cservices/ServicioFiles';
$route['api/v.1/app/getequipo'] = 'app/Cservices/RecoveryEquipo';
$route['api/v.1/app/equipocomentario'] = 'app/Cservices/equipocomentario';
$route['api/v.1/app/changestatusequip'] = 'app/Cservices/changestatusequipo';
$route['api/v.1/app/deleteequipo'] = 'app/Cservices/deleteequipo';
$route['api/v.1/app/ListaHistorialEq'] = 'app/Cservices/ListaHistorialEq';
$route['api/v.1/app/cancelserv'] = 'app/Cservices/CancelServicio';
//cajas
$route['api/v.1/app/listcajas'] = 'app/Cservices/Listcajas';
$route['api/v.1/app/postconceptos'] = 'app/Cservices/gastos';

//Contactos app
$route['api/v.1/app/listacontactos'] = 'app/Cservices/Listcontactos';

//Unicacion  app
$route['api/v.1/app/ubicacion'] = 'app/Cservices/ubicaciont';
$route['api/v.1/app/perfil'] = 'app/Cservices/get_Perfil';
//vendedor
$route['api/v.1/app/ubicacionvendedor'] = 'app/Cservices/ubicacionVen';
#endregion app

#region Login
$route['api/v.1/login/post'] = 'catalogos/Clogin/Login'; //'catalogos/Cusuarios/Login';// se cambiaron de controlador
$route['api/v.1/loginroot/post'] = 'catalogos/Clogin/LoginRoot'; //'catalogos/Cusuarios/LoginRoot';// se cambiaron de controlador
#endregion

#reset password
$route['api/v.1/tblResetPass/ComprobarUser'] = 'modulos/Cresetpassword/ComprobarUsuario';
$route['api/v.1/tblResetPass/CvalidarToken'] = 'modulos/Cresetpassword/ValidarTkn';
$route['api/v.1/tblResetPass/updatePass'] = 'modulos/Cresetpassword/UpdatePassword';
#endregion

#region Funciones
$route['api/v.1/funciones/getanios'] = 'catalogos/Cfunciones/findAll';
#endregion


//region RUTA Permiso
$route['api/v.1/permiso/get'] = 'catalogos/Cpermiso/Lista';
$route['api/v.1/permiso/recovery'] = 'catalogos/Cpermiso/Recovery';
$route['api/v.1/permiso/post'] = 'catalogos/Cpermiso/Nuevo';
$route['api/v.1/permiso/put'] = 'catalogos/Cpermiso/Actualizar';
$route['api/v.1/permiso/(:any)'] = 'catalogos/Cpermiso/Eliminar/$1';
#endpermiso

//region RUTA Panel
$route['api/v.1/panel/get'] = 'catalogos/Cpanel/Lista';
$route['api/v.1/panel/recovery'] = 'catalogos/Cpanel/Recovery';
$route['api/v.1/panel/post'] = 'catalogos/Cpanel/Nuevo';
$route['api/v.1/panel/put'] = 'catalogos/Cpanel/Actualizar';
$route['api/v.1/panel/(:any)'] = 'catalogos/Cpanel/Eliminar/$1';
#endpanel

#region RUTA Empresas
$route['api/v.1/empresa/get'] = 'catalogos/Cempresa/findAll';
$route['api/v.1/empresa/recovery'] = 'catalogos/Cempresa/findOne';
$route['api/v.1/empresa/post'] = 'catalogos/Cempresa/Add';
$route['api/v.1/empresa/(:num)'] = 'catalogos/Cempresa/Delete/$1';
#endregion

#region RUTA Sucursal
$route['api/v.1/sucursal/get'] = 'catalogos/Csucursal/findAll';
$route['api/v.1/sucursal/recovery'] = 'catalogos/Csucursal/findOne';
$route['api/v.1/sucursal/post'] = 'catalogos/Csucursal/Add';
$route['api/v.1/sucursal/(:num)'] = 'catalogos/Csucursal/Delete/$1';
#endregion

#region RUTA HorasLaborales
$route['api/v.1/horaslaborales/get'] = 'catalogos/Choraslaborales/findAll';
$route['api/v.1/horaslaborales/recovery'] = 'catalogos/Choraslaborales/findOne';
$route['api/v.1/horaslaborales/post'] = 'catalogos/Choraslaborales/Add';
$route['api/v.1/horaslaborales/horaslaborales'] = 'catalogos/Choraslaborales/ListaHoras';


#endregion

#region RUTA TipoServicio
$route['api/v.1/tiposervicio/get'] = 'catalogos/Ctiposervicio/findAll';
$route['api/v.1/tiposervicio/recovery'] = 'catalogos/Ctiposervicio/findOne';
$route['api/v.1/tiposervicio/post'] = 'catalogos/Ctiposervicio/Add';
$route['api/v.1/tiposervicio/(:num)'] = 'catalogos/Ctiposervicio/Delete/$1';
#endregion


#region RUTA CategoriaPersonal
$route['api/v.1/categoriapersonal/get'] = 'catalogos/Ccategoriapersonal/findAll';
$route['api/v.1/categoriapersonal/recovery'] = 'catalogos/Ccategoriapersonal/findOne';
$route['api/v.1/categoriapersonal/post'] = 'catalogos/Ccategoriapersonal/Add';
$route['api/v.1/categoriapersonal/(:num)'] = 'catalogos/Ccategoriapersonal/Delete/$1';
#endregion


#region RUTA Vehiculo
$route['api/v.1/vehiculo/get'] = 'catalogos/Cvehiculo/findAll';
$route['api/v.1/vehiculo/recovery'] = 'catalogos/Cvehiculo/findOne';
$route['api/v.1/vehiculo/post'] = 'catalogos/Cvehiculo/Add';
$route['api/v.1/vehiculo/(:num)'] = 'catalogos/Cvehiculo/Delete/$1';
#endregion

#region RUTA Trabajador
$route['api/v.1/trabajador/get'] = 'catalogos/Ctrabajador/findAll';
$route['api/v.1/trabajador/recovery'] = 'catalogos/Ctrabajador/findOne';
$route['api/v.1/trabajador/post'] = 'catalogos/Ctrabajador/Add';
$route['api/v.1/trabajador/(:num)'] = 'catalogos/Ctrabajador/Delete/$1';
$route['api/v.1/trabajador/ChangePass'] = 'catalogos/Ctrabajador/ChangePass';
$route['api/v.1/trabajador/getListUser'] = 'catalogos/Ctrabajador/ListTrabRol';
$route['api/v.1/trabajador/ListTrabRolQuery'] = 'catalogos/Ctrabajador/ListTrabRolQuery';
#endregion


#region RUTA Folio
$route['api/v.1/folio/get'] = 'catalogos/Cfolio/findAll';
$route['api/v.1/folio/recovery'] = 'catalogos/Cfolio/findOne';
$route['api/v.1/folio/post'] = 'catalogos/Cfolio/Add';
$route['api/v.1/folio/(:num)'] = 'catalogos/Cfolio/Delete/$1';
#endregion

#region RUTA Clientes
$route['api/v.1/clientes/get'] = 'catalogos/Cclientes/findAll';
$route['api/v.1/clientes/recovery'] = 'catalogos/Cclientes/findOne';
$route['api/v.1/clientes/post'] = 'catalogos/Cclientes/Add';
$route['api/v.1/clientes/houseaccount'] = 'catalogos/Cclientes/houseAccount';
$route['api/v.1/clientes/(:num)'] = 'catalogos/Cclientes/Delete/$1';
#endregion


#region RUTA TipoUnidad
$route['api/v.1/tipounidad/get'] = 'catalogos/Ctipounidad/findAll';
$route['api/v.1/tipounidad/recovery'] = 'catalogos/Ctipounidad/findOne';
$route['api/v.1/tipounidad/post'] = 'catalogos/Ctipounidad/Add';
$route['api/v.1/tipounidad/(:num)'] = 'catalogos/Ctipounidad/Delete/$1';
#endregion

#region RUTA Servicio
$route['api/v.1/servicio/get'] = 'modulos/despacho/Cservicio/findAll';
$route['api/v.1/servicio/recovery'] = 'modulos/despacho/Cservicio/findOne';
$route['api/v.1/servicio/post'] = 'modulos/despacho/Cservicio/Add';
$route['api/v.1/servicio/(:num)'] = 'modulos/despacho/Cservicio/Delete/$1';
$route['api/v.1/servicio/despacho'] = 'modulos/despacho/Cservicio/Despacho';
$route['api/v.1/servicio/fehcasdinamicas'] = 'modulos/despacho/Cservicio/fechasDinamicas';
$route['api/v.1/servicio/disponibles'] = 'modulos/despacho/Cservicio/DisponiblesAll';
$route['api/v.1/servicio/calendar'] = 'modulos/despacho/Cservicio/listcalendario';
$route['api/v.1/servicio/finalizados'] = 'modulos/despacho/Cservicio/listServFin';
$route['api/v.1/servicio/ValidacionSave'] = 'modulos/despacho/Cservicio/ValidacionSave';
$route['api/v.1/servicio/trabajadoresxserv'] = 'modulos/despacho/Cservicio/TrabajadoresxServ';
$route['api/v.1/servicio/calcularvalores'] = 'modulos/despacho/Cservicio/CalcularVal';
$route['api/v.1/servicio/SendFiles'] = 'modulos/despacho/Cservicio/SendFiles';

#endregion

#region Imagnes servicios y equipos
$route['api/v.1/imageneservicio/get'] = 'modulos/despacho/Cimagenes/List';
$route['api/v.1/imageneservicio/getequipos'] = 'modulos/despacho/Cimagenes/ListEquipo';
$route['api/v.1/imageneservicio/post'] = 'modulos/despacho/Cimagenes/Add';
$route['api/v.1/imageneservicio/postImages'] = 'modulos/despacho/Cimagenes/AddImages';

$route['api/v.1/imageneservicio/totales'] = 'modulos/despacho/Cimagenes/ListCounts';
#endregion

#Region Chat
$route['api/v.1/despacho/getchat'] = 'modulos/despacho/Cchat/List';
$route['api/v.1/despacho/postchat'] = 'modulos/despacho/Cchat/Add';
$route['api/v.1/despacho/notificationchat'] = 'modulos/despacho/Cchat/Getnotification';
#endregion

#region RUTA iconeequpi
$route['api/v.1/iconos_eq/get'] = 'catalogos/Ciconos_eq/Lista';
#endregion

#region RUTA Equipos
$route['api/v.1/equipos/get'] = 'catalogos/Cequipos/findAll';
$route['api/v.1/equipos/recovery'] = 'catalogos/Cequipos/findOne';
$route['api/v.1/equipos/post'] = 'catalogos/Cequipos/Add';
$route['api/v.1/equipos/(:num)'] = 'catalogos/Cequipos/Delete/$1';
$route['api/v.1/equipos/getqr'] = 'catalogos/Cequiposqr/conseguirArchivo';
#endregion

#region RUTA Equipamiento
$route['api/v.1/equipamiento/get'] = 'catalogos/Cequipamiento/findAll';
$route['api/v.1/equipamiento/recovery'] = 'catalogos/Cequipamiento/findOne';
$route['api/v.1/equipamiento/post'] = 'catalogos/Cequipamiento/Add';
$route['api/v.1/equipamiento/(:num)'] = 'catalogos/Cequipamiento/Delete/$1';
#endregion

#region RUTA Concepto
$route['api/v.1/concepto/get'] = 'catalogos/Cconcepto/findAll';
$route['api/v.1/concepto/recovery'] = 'catalogos/Cconcepto/findOne';
$route['api/v.1/concepto/post'] = 'catalogos/Cconcepto/Add';
$route['api/v.1/concepto/(:num)'] = 'catalogos/Cconcepto/Delete/$1';
#endregion

#region RUTA correo
$route['api/v.1/correo/get'] = 'catalogos/Ccorreo/findAll';
$route['api/v.1/correo/recovery'] = 'catalogos/Ccorreo/findOne';
$route['api/v.1/correo/post'] = 'catalogos/Ccorreo/Add';
$route['api/v.1/correo/(:num)'] = 'catalogos/Ccorreo/Delete/$1';
#endregion


#region RUTA iconos
$route['api/v.1/iconos/get'] = 'catalogos/Ciconos/findAll';
#endregion


#region RUTA Configservicio
$route['api/v.1/configservicio/get'] = 'catalogos/Cconfigservicio/findAll';
#endregion

#region RUTA CategoriaVehiculos
$route['api/v.1/categoriavehiculo/get'] = 'catalogos/Ccategoriavehiculo/findAll';
$route['api/v.1/categoriavehiculo/recovery'] = 'catalogos/Ccategoriavehiculo/findOne';
$route['api/v.1/categoriavehiculo/post'] = 'catalogos/Ccategoriavehiculo/Add';
$route['api/v.1/categoriavehiculo/(:num)'] = 'catalogos/Ccategoriavehiculo/Delete/$1';

$route['api/v.1/categoriavehi/get'] = 'catalogos/Ccategoriavehiculo/findAllCat';
#endregion

#region RUTA CPaquete
$route['api/v.1/paquete/get'] = 'catalogos/Cpaquete/findAll';
$route['api/v.1/paquetegeneral/get'] = 'catalogos/Cpaquete/findAllg';
$route['api/v.1/paquetexsucursal/get'] = 'catalogos/Cpaquete/PaquetexSucursal';
#endregion

#region RUTA CPaquetexsucursal
$route['api/v.1/paquetexsucursal/post'] = 'catalogos/Cpaquetexsucursal/Add';
#endregion

#region RUTA Cliente sucursal
$route['api/v.1/clientesucursal/get'] = 'catalogos/Cclientesucursal/List';
$route['api/v.1/clientesucursal/recovery'] = 'catalogos/Cclientesucursal/Recovery';
$route['api/v.1/clientesucursal/post'] = 'catalogos/Cclientesucursal/Add';
$route['api/v.1/clientesucursal/(:num)'] = 'catalogos/Cclientesucursal/Delete/$1';
#endregion

#region RUTA Cliente sucursal
$route['api/v.1/iconosemp/get'] = 'catalogos/Ciconosemp/List';
#endregion

#region RUTA usuario
$route['api/v.1/usuario/get'] = 'catalogos/Cusuarios/List';
$route['api/v.1/usuario/recovery'] = 'catalogos/Cusuarios/Recovery';
$route['api/v.1/usuario/post'] = 'catalogos/Cusuarios/Add';
$route['api/v.1/usuario/(:num)'] = 'catalogos/Cusuarios/Delete/$1';
$route['api/v.1/usuario/AddUsuMonitoreo'] = 'catalogos/Cusuarios/AddUsuMonitoreo';
$route['api/v.1/usuario/UpdateProfile'] = 'catalogos/Cusuarios/UpdateProfile';

$route['api/v.1/usuariosucursal/get'] = 'catalogos/Cusuarios/ListuserSucursal';


#endregion

#region RUTA pdfequipo
$route['api/v.1/pdfequipo/get'] = 'catalogos/Cpdfequipo/List';
$route['api/v.1/pdfequipo/recovery'] = 'catalogos/Cpdfequipo/Recovery';
$route['api/v.1/pdfequipo/post'] = 'catalogos/Cpdfequipo/Add';
$route['api/v.1/pdfequipo/(:num)'] = 'catalogos/Cpdfequipo/Delete/$1';
#endregion

#costosvarios
$route['api/v.1/costosvarios/get'] = 'catalogos/Ccostosvarios/List';
$route['api/v.1/costosvarios/recovery'] = 'catalogos/Ccostosvarios/Recovery';
$route['api/v.1/costosvarios/post'] = 'catalogos/Ccostosvarios/Add';
$route['api/v.1/costosvarios/(:num)'] = 'catalogos/Ccostosvarios/Delete/$1';

#costosvarios
$route['api/v.1/perfil/get'] = 'catalogos/Cperfil/List';
$route['api/v.1/perfil/recovery'] = 'catalogos/Cperfil/Recovery';


#region RUTA Cotizacion materiales
$route['api/v.1/cotizacion_material/get'] = 'catalogos/Cmaterial/List';
$route['api/v.1/cotizacion_material/recovery'] = 'catalogos/Cmaterial/Recovery';
$route['api/v.1/cotizacion_material/post'] = 'catalogos/Cmaterial/Add';
$route['api/v.1/cotizacion_material/(:num)'] = 'catalogos/Cmaterial/Delete/$1';
#endregion

#region RUTA Cotizacion costoskm
$route['api/v.1/costoskm/get'] = 'catalogos/Ccostoskm/List';
$route['api/v.1/costoskm/recovery'] = 'catalogos/Ccostoskm/Recovery';
$route['api/v.1/costoskm/post'] = 'catalogos/Ccostoskm/Add';
$route['api/v.1/costoskm/(:num)'] = 'catalogos/Ccostoskm/Delete/$1';
#endregion

#region RUTA Cotizacion costosta
$route['api/v.1/costosta/get'] = 'catalogos/Ccostosta/List';
$route['api/v.1/costosta/recovery'] = 'catalogos/Ccostosta/Recovery';
$route['api/v.1/costosta/post'] = 'catalogos/Ccostosta/Add';
$route['api/v.1/costosta/(:num)'] = 'catalogos/Ccostosta/Delete/$1';

#region RUTA Cotizacion markup
$route['api/v.1/markup/get'] = 'catalogos/Cmarkup/List';
$route['api/v.1/markup/recovery'] = 'catalogos/Cmarkup/Recovery';
$route['api/v.1/markup/post'] = 'catalogos/Cmarkup/Add';
$route['api/v.1/markup/(:num)'] = 'catalogos/Cmarkup/Delete/$1';

#region RUTA Cotizacion_Servicio
$route['api/v.1/cotizacion_servicio/get'] = 'modulos/Ccotizacion_servicio/List';
$route['api/v.1/cotizacion_servicio/recovery'] = 'modulos/Ccotizacion_servicio/Recovery';
$route['api/v.1/cotizacion_servicio/post'] = 'modulos/Ccotizacion_servicio/Add';
$route['api/v.1/cotizacion_servicio/(:num)'] = 'modulos/Ccotizacion_servicio/Delete/$1';
$route['api/v.1/cotizacion_servicio/listmo'] = 'modulos/Ccotizacion_servicio/ListMO';
$route['api/v.1/cotizacion_servicio/listmiscelaneo'] = 'modulos/Ccotizacion_servicio/ListMiscelaneo';


#region RUTA CajaChica
$route['api/v.1/cajachica/get'] = 'catalogos/Ccajachica/findAll';
$route['api/v.1/cajachica/recovery'] = 'catalogos/Ccajachica/findOne';
$route['api/v.1/cajachica/post'] = 'catalogos/Ccajachica/Add';
$route['api/v.1/cajachica/(:num)'] = 'catalogos/Ccajachica/Delete/$1';
#endregion

#region RUTA Caja
$route['api/v.1/caja/get'] = 'catalogos/Ccaja/findAll';
$route['api/v.1/caja/cajacajachica'] = 'catalogos/Ccaja/cajacajachica';
$route['api/v.1/caja/recovery'] = 'catalogos/Ccaja/findOne';
$route['api/v.1/caja/post'] = 'catalogos/Ccaja/Add';
$route['api/v.1/caja/(:num)'] = 'catalogos/Ccaja/Delete/$1';
#endregion

#region RUTA Equipamiento
$route['api/v.1/equipamiento/get'] = 'catalogos/Cequipamiento/findAll';
$route['api/v.1/equipamiento/recovery'] = 'catalogos/Cequipamiento/findOne';
$route['api/v.1/equipamiento/post'] = 'catalogos/Cequipamiento/Add';
$route['api/v.1/equipamiento/(:num)'] = 'catalogos/Cequipamiento/Delete/$1';
#endregion

#region RUTA Rol
$route['api/v.1/rol/get'] = 'catalogos/Crol/findAll';
#endregion

#region RUTA GastoAsignado
$route['api/v.1/asignacioncaja/list'] = 'catalogos/Casignacioncaja/findAll';
$route['api/v.1/asignacioncaja/recovery'] = 'catalogos/Casignacioncaja/findOne';
$route['api/v.1/asignacioncaja/post'] = 'catalogos/Casignacioncaja/Add';
$route['api/v.1/asignacioncaja/(:num)/(:num)'] = 'catalogos/Casignacioncaja/Delete/$1/$2';
#endregion

#region RUTA Gastoxsucursal
$route['api/v.1/gastoxtrabajador/getlist'] = 'modulos/cajachica/Cgastos/findAll';
$route['api/v.1/gastoxtrabajador/listevidencia'] = 'modulos/cajachica/Cgastos/findAllEvidencia';

#endregion

#region RUTA NumcontrarofindAll
$route['api/v.1/numcontrato/get'] = 'catalogos/Cnumcontrato/List';
$route['api/v.1/numcontrato/post'] = 'catalogos/Cnumcontrato/Add';
$route['api/v.1/numcontrato/recovery'] = 'catalogos/Cnumcontrato/Recovery';
$route['api/v.1/numcontrato/(:num)'] = 'catalogos/Cnumcontrato/Delete/$1';
#endregion

#region RUTA ubicacion de los tecnicos
$route['api/v.1/ubicacionmapa/get'] = 'modulos/despacho/Cubicaciones/findAll';
$route['api/v.1/ubicacionmapa2/get'] = 'modulos/despacho/Cubicaciones/findAll2';
$route['api/v.1/ubicacionmapa/getvendedor'] = 'modulos/despacho/Cubicaciones/findAllVendedor';
#endregion

#region SPENDPLAN
$route['api/v.1/spendpoject/get'] = 'catalogos/Cspend_proyecto/List';
$route['api/v.1/spendpoject/recovery'] = 'catalogos/Cspend_proyecto/Recovery';
$route['api/v.1/spendpoject/post'] = 'catalogos/Cspend_proyecto/Add';
$route['api/v.1/spendpoject/(:num)'] = 'catalogos/Cspend_proyecto/Delete/$1';

$route['api/v.1/spendpoject/conceptos'] = 'catalogos/Cspend_proyecto/Conceptos';

$route['api/v.1/spendpoject/listytd'] = 'catalogos/Cspend_proyecto/ListYTD';
$route['api/v.1/spendpoject/finish'] = 'catalogos/Cspend_proyecto/Finish';

#endregion

#region SPENDPLAN Orden compra
$route['api/v.1/spendoc/get'] = 'catalogos/Cspend_ordenc/List';
$route['api/v.1/spendoc/recovery'] = 'catalogos/Cspend_ordenc/Recovery';
$route['api/v.1/spendoc/post'] = 'catalogos/Cspend_ordenc/Add';
$route['api/v.1/spendoc/(:num)'] = 'catalogos/Cspend_ordenc/Delete/$1';
#endregion

#region SPENDPLAN horas
$route['api/v.1/spendhora/get'] = 'catalogos/Cspend_horas/List';
$route['api/v.1/spendhora/recovery'] = 'catalogos/Cspend_horas/Recovery';
$route['api/v.1/spendhora/post'] = 'catalogos/Cspend_horas/Add';
$route['api/v.1/spendhora/(:num)'] = 'catalogos/Cspend_horas/Delete/$1';
#endregion

#region DASHBOARD
#DESAPACHO

#region facturacion
$route['api/v.1/factura/list'] = 'modulos/facturacion/Cfactura/findAll';
$route['api/v.1/factura/servxfact'] = 'modulos/facturacion/Cfactura/listservfact';
$route['api/v.1/factura/recovery'] = 'modulos/facturacion/Cfactura/getFactura';
$route['api/v.1/factura/post'] = 'modulos/facturacion/Cfactura/Add';
$route['api/v.1/factura/(:num)'] = 'catalogos/Cspend_ordenc/Delete/$1';
$route['api/v.1/factura/Autorizar'] = 'modulos/facturacion/Cfactura/Autorizar';
$route['api/v.1/factura/Cancelar'] = 'modulos/facturacion/Cfactura/Cancelar';
$route['api/v.1/factura/ChangeFactura/post'] = 'modulos/facturacion/Cfactura/ChangeFactura';

$route['api/v.1/factura/facturaAnulada/post'] = 'modulos/facturacion/Cfactura/FacturaAnulada';
$route['api/v.1/factura/facturaLibre/post'] = 'modulos/facturacion/Cfactura/FacturaLibre';
$route['api/v.1/factura/facturaLibre/get'] = 'modulos/facturacion/Cfactura/ListFacturaLibre';
$route['api/v.1/factura/facturaLibreAutorize/get'] = 'modulos/facturacion/Cfactura/findFacrlibreAutorize';
$route['api/v.1/factura/facturaLibrerecovery/get'] = 'modulos/facturacion/Cfactura/getFacturaLibre';
$route['api/v.1/factura/recoveryFact'] = 'modulos/facturacion/Cfactura/recoveryFactura';
$route['api/v.1/factura/facturaxEstatusPendiente'] = 'modulos/facturacion/Cfactura/facturasxEstatusPendiente';
$route['api/v.1/factura/facturaxEstatusCancelada'] = 'modulos/facturacion/Cfactura/facturasxEstatusCancelada';
$route['api/v.1/factura/facturaxEstatusAnulada'] = 'modulos/facturacion/Cfactura/facturasxEstatusAnulada';
$route['api/v.1/factura/facturalibreAnulada'] = 'modulos/facturacion/Cfactura/facturaLibreAnulada';
$route['api/v.1/factura/facturalibreCancelada'] = 'modulos/facturacion/Cfactura/facturaLibreCancelada';

#endregion
$route['api/v.1/dashboard/horasp'] = 'dashboard/Cdespacho/HorasP';
$route['api/v.1/dashboard/servfac'] = 'dashboard/Cdespacho/ServFac';
$route['api/v.1/dashboard/ventaxvehiculo'] = 'dashboard/Cdespacho/VentaxVehiculo';
$route['api/v.1/dashboard/servicioxhora'] = 'dashboard/Cdespacho/ServicioxHora';

#******Monitoreo****
$route['api/v.1/monitoreo/custumers'] = 'catalogos/Cclientes/ListMonitoreo';
$route['api/v.1/monitoreo/equipment'] = 'catalogos/Cclientes/ListEquipo';
$route['api/v.1/monitoreo/historyequi'] = 'catalogos/Cequipos/Historial';
//Cotixaciones
$route['api/v.1/monitoreo/cotizaciones'] = 'modulos/monitoreo/Cpdfclientes/List';
$route['api/v.1/monitoreo/cotizacionesget'] = 'modulos/monitoreo/Cpdfclientes/Recovery';
$route['api/v.1/monitoreo/cotizacionesadd'] = 'modulos/monitoreo/Cpdfclientes/Add';
$route['api/v.1/monitoreo/cotizacion/(:num)'] = 'modulos/monitoreo/Cpdfclientes/Delete/$1';
//tiket seguimiento
$route['api/v.1/monitoreo/ticket'] = 'modulos/monitoreo/Cticket/List';
$route['api/v.1/monitoreo/ticketget'] = 'modulos/monitoreo/Cticket/Recovery';
$route['api/v.1/monitoreo/ticketadd'] = 'modulos/monitoreo/Cticket/Add';
$route['api/v.1/monitoreo/ticket/(:num)'] = 'modulos/monitoreo/Cticket/Delete/$1';

//tiket DETALLE seguimiento
$route['api/v.1/monitoreo/ticketseguimiento'] = 'modulos/monitoreo/Cticket_seguimiento/List';
$route['api/v.1/monitoreo/ticketseguimientoadd'] = 'modulos/monitoreo/Cticket_seguimiento/Add';

//Links de reportes
$route['api/v.1/reporte/servicio'] = 'modulos/reports/Cdespacho/servicio';
$route['api/v.1/reporte/servicioevidencia'] = 'modulos/reports/Cdespacho/servicioevidencia';
$route['api/v.1/reporte/Cotizacion'] = 'modulos/reports/Ccotizacion/Cotizacion';
$route['api/v.1/reporte/factura'] = 'modulos/reports/Cfactura/factura';

//************Modulo financiero******************

//Links de Plan de ventas
$route['api/v.1/Cplanventas/get'] = 'modulos/finanzas/Cplanventas/LisData';
$route['api/v.1/planventas/post'] = 'modulos/finanzas/Cplanventas/Add';
$route['api/v.1/reporte/factura'] = 'modulos/reports/Cfactura/factura';

//Links de base actual
$route['api/v.1/baseactual/get'] = 'modulos/finanzas/Cbaseactual/findAll';
$route['api/v.1/baseactual/getone'] = 'modulos/finanzas/Cbaseactual/getone';
$route['api/v.1/baseactual/post'] = 'modulos/finanzas/Cbaseactual/Add';

//Links de porcentajeoper
$route['api/v.1/porcentajeoper/get'] = 'modulos/finanzas/Cporcentajeoper/getData';
$route['api/v.1/porcentajeoper/getone'] = 'modulos/finanzas/Cporcentajeoper/getone';
$route['api/v.1/porcentajeoper/post'] = 'modulos/finanzas/Cporcentajeoper/Add';

$route['api/v.1/PlanMes/post'] = 'modulos/finanzas/Cporcentajeoper/PlanMensual';

//Links de Gastos Directo o Costo depto ventas
$route['api/v.1/costoventas/get'] = 'modulos/finanzas/Cgastosdirectos/findAll';
$route['api/v.1/costoventas/getall'] = 'modulos/finanzas/Cgastosdirectos/getAll';
$route['api/v.1/costoventas/post'] = 'modulos/finanzas/Cgastosdirectos/Add';

//Links de Costo depto operaciones
$route['api/v.1/costodeptooper/get'] = 'modulos/finanzas/Ccostodeptooper/findAll';
$route['api/v.1/costodeptooper/post'] = 'modulos/finanzas/Ccostodeptooper/Add';

//Links de Costo depto operaciones
$route['api/v.1/costovehope/get'] = 'modulos/finanzas/Ccostovehope/findAll';
$route['api/v.1/costovehope/post'] = 'modulos/finanzas/Ccostovehope/Add';

//Links de Costo depto operaciones
$route['api/v.1/costoga/get'] = 'modulos/finanzas/Ccostoga/findAll';
$route['api/v.1/costoga/post'] = 'modulos/finanzas/Ccostoga/Add';

//Links de Costo financiero
$route['api/v.1/costofinanciero/get'] = 'modulos/finanzas/Ccostofinanciero/findAll';
$route['api/v.1/costofinanciero/getall'] = 'modulos/finanzas/Ccostofinanciero/getAll';
$route['api/v.1/costofinanciero/post'] = 'modulos/finanzas/Ccostofinanciero/Add';

//Links Actualizacion Costos
$route['api/v.1/actualizacionCostos/get'] = 'modulos/finanzas/CactualizacionCostos/getData';
$route['api/v.1/actualizacionCostos/post'] = 'modulos/finanzas/CactualizacionCostos/Add';

//Links Actualizacion Costos Operativos
$route['api/v.1/actualizarCostOp/get'] = 'modulos/finanzas/CactualizarCostOp/findAll';
$route['api/v.1/actualizarCostOp/post'] = 'modulos/finanzas/CactualizarCostOp/Add';

//Links Estados Financieros
$route['api/v.1/estadosFinancieros/get'] = 'modulos/finanzas/CestadosFinancieros/getData';
$route['api/v.1/estadosFinancieros/getTodos'] = 'modulos/finanzas/CestadosFinancieros/getDataTodos';
$route['api/v.1/estadosFinancieros/post'] = 'modulos/finanzas/CestadosFinancieros/Add';

//Links de reportes Finanzas
$route['api/v.1/reporte/costosga'] = 'modulos/reports/Cfinanciero/Costoca';
$route['api/v.1/reporte/costoventa'] = 'modulos/reports/Cfinanciero/CostoVenta';
$route['api/v.1/reporte/costooperacion'] = 'modulos/reports/Cfinanciero/CostoOperacion';
$route['api/v.1/reporte/costovehiculo'] = 'modulos/reports/Cfinanciero/CostoVehiculo';
$route['api/v.1/reporte/costofinanciero'] = 'modulos/reports/Cfinanciero/CostoFinanciero';
$route['api/v.1/reporte/sueldospersonalOp'] = 'modulos/estadosfinancieros/Cpersonaloperativo/PdfSueldosPersonalOperativo';

//$route['api/v.1/reporte/estadofinanciero'] = 'modulos/reports/Cfinanciero/EstadoFinanciero';
$route['api/v.1/reporte/estadofinanciero'] = 'modulos/estadosfinancieros/CestadosfinancierosinfoPDF/getDataTodosInfo';



//!El parche
//$route['api/v.1/reporte/estadofinangral'] = 'modulos/reports/CfinancieroParchePDF/GetPdfParche';
//!El antiguo
//$route['api/v.1/reporte/estadofinangral'] = 'modulos/reports/Cfinanciero/EstadoFinGen';
//!El nuevo
$route['api/v.1/reporte/estadofinangral'] = 'modulos/estadosfinancieros/Cestadosfinancieros/getDataPDF';
$route['api/v.1/reporte/estadofinan2022'] = 'modulos/estadosfinancieros/Cestadosfinancieros/PDFprueba';

// //!Filtro en estados financieros general
// $route['api/v.1/estadofinanTodos/get'] = 'modulos/estadosfinancieros/CestadosFinanGenFiltro/getData';
// $route['api/v.1/estadofinanDeptoV/get'] = 'modulos/estadosfinancieros/CestadosFinanGenFiltro/getDespVenta';
// $route['api/v.1/estadofinanDeptoOpera/get'] = 'modulos/estadosfinancieros/CestadosFinanGenFiltro/getCostoDpetoOper';
// $route['api/v.1/estadofinanVehiculoOpera/get'] = 'modulos/estadosfinancieros/CestadosFinanGenFiltro/getCostosVehiOper';
$route['api/v.1/estadofinanCostosFiancieros/get'] = 'modulos/estadosfinancieros/CestadosFinanGenFiltro/getCostosFinancieros';

//Graficas Finanzas
$route['api/v.1/finanzasgraf/get'] = 'modulos/finanzas/Cgraficas/getplanvsact';
$route['api/v.1/finanzasgraf/getfact'] = 'modulos/finanzas/Cgraficas/getfacact';
$route['api/v.1/finanzasgraf/getgrossp'] = 'modulos/finanzas/Cgraficas/getGrossProf';
$route['api/v.1/finanzasgraf/facttiposerv'] = 'modulos/finanzas/Cgraficas/factxtiposerv';
$route['api/v.1/finanzasgraf/horasfact'] = 'modulos/finanzas/Cgraficas/horasfact';
//$route['api/v.1/finanzasgraf/porcentajecostos'] = 'modulos/finanzas/Cgraficas/porcentajecostos';

$route['api/v.1/finanzasgraf/porcentajecostos'] = 'modulos/finanzas/Cgraficas/porcentajecostos';
//$route['api/v.1/finanzasgraf/porcentajecostos'] = 'modulos/finanzas/Cgraficas/graficaopf';

//$route['api/v.1/finanzasgraf/grafica/get'] = 'modulos/finanzas/Cgraficas/graficaopf';
////////////////////RUTA DE GRAFICAS OPERATION PROFFIT
$route['api/v.1/finanzasgraf/graficgrossprof'] = 'catalogos/CgraficGrossProffit/graficaGross';








//***********CRM************

//Contactos
$route['api/v.1/crmcontactos/list'] = 'modulos/crm/Ccontactos/findAll';
$route['api/v.1/crmcontactos/recovery'] = 'modulos/crm/Ccontactos/findOne';
$route['api/v.1/crmcontactos/post'] = 'modulos/crm/Ccontactos/Add';
$route['api/v.1/crmcontactos/(:num)'] = 'modulos/crm/Ccontactos/Delete/$1';

//Contactos sucursal
$route['api/v.1/crmsucursal/list'] = 'modulos/crm/Csucursal/List';
$route['api/v.1/crmsucursal/recovery'] = 'modulos/crm/Csucursal/Recovery';
$route['api/v.1/crmsucursal/post'] = 'modulos/crm/Csucursal/Add';
$route['api/v.1/crmsucursal/(:num)'] = 'modulos/crm/Csucursal/Delete/$1';

//Oportunidades
$route['api/v.1/crmoportunidad/list'] = 'modulos/crm/Coportunidades/List';
$route['api/v.1/crmoportunidad/recovery'] = 'modulos/crm/Coportunidades/Recovery';
$route['api/v.1/crmoportunidad/post'] = 'modulos/crm/Coportunidades/Add';
$route['api/v.1/crmoportunidad/(:num)'] = 'modulos/crm/Coportunidades/Delete/$1';
$route['api/v.1/crmoportunidadvendedor/list'] = 'modulos/crm/Coportunidades/Listoportunidadvendedor';

//seguimiento
$route['api/v.1/crmseguimiento/list'] = 'modulos/crm/Cseguimientocliente/List';
$route['api/v.1/crmseguimiento/recovery'] = 'modulos/crm/Cseguimientocliente/Recovery';
$route['api/v.1/crmseguimiento/post'] = 'modulos/crm/Cseguimientocliente/Add';
$route['api/v.1/crmseguimiento/(:num)'] = 'modulos/crm/Cseguimientocliente/Delete/$1';

//crm tipo proceso
$route['api/v.1/crmtipoproceso/list'] = 'modulos/crm/Ccrmtipoproceso/List';
$route['api/v.1/crmtipoproceso/recovery'] = 'modulos/crm/Ccrmtipoproceso/Recovery';
$route['api/v.1/crmtipoproceso/post'] = 'modulos/crm/Ccrmtipoproceso/Add';
$route['api/v.1/crmtipoproceso/(:num)'] = 'modulos/crm/Ccrmtipoproceso/Delete/$1';
$route['api/v.1/crmtipoandproceso/list'] = 'modulos/crm/Ccrmtipoproceso/RecoveryTipoandproceso';


//crm  proceso
$route['api/v.1/crmprocesos/list'] = 'modulos/crm/Ccrmproceso/List';
$route['api/v.1/crmprocesos/recovery'] = 'modulos/crm/Ccrmproceso/Recovery';
$route['api/v.1/crmprocesos/post'] = 'modulos/crm/Ccrmproceso/Add';
$route['api/v.1/crmprocesos/(:num)'] = 'modulos/crm/Ccrmproceso/Delete/$1';
$route['api/v.1/crmprocesos/changeposition'] = 'modulos/crm/Ccrmproceso/changeposition';

$route['api/v.1/crmprocesos/oportunidadxProceso'] = 'modulos/crm/Ccrmproceso/oportunidadxProceso';
$route['api/v.1/crmprocesos/procesoxSucursal'] = 'modulos/crm/Ccrmproceso/procesoxSucursal';
$route['api/v.1/crmprocesos/procesoxSucursalCliente'] = 'modulos/crm/Ccrmproceso/procesoxClienteSucursal';
$route['api/v.1/crmprocesos/VendedorxProcesoxOportunidad'] = 'modulos/crm/Ccrmproceso/VendedorxProcesoxOportunidad';

//crm  proceso
$route['api/v.1/crmprocesovendedor/list'] = 'modulos/crm/Cprocesovendedor/List';
$route['api/v.1/crmprocesovendedor/listasig'] = 'modulos/crm/Cprocesovendedor/Listasignados';
$route['api/v.1/crmprocesovendedor/post'] = 'modulos/crm/Cprocesovendedor/asignarproceso';

//pipedrive
$route['api/v.1/crmseguimiento/pipedrive'] = 'modulos/crm/Cseguimientocliente/Listpipedrive';
$route['api/v.1/pipeDrive/get'] = 'modulos/crm/Cgraficas/graficaPipeDrive';
// //!PARCHE PIPE
$route['api/v.1/crmseguimiento/sucursales'] = 'modulos/crm/Cseguimientocliente/SucursalesPipe';

//Crm Forecast

$route['api/v.1/crmforecast/list'] = 'modulos/crm/Ccrmforecast/List';
$route['api/v.1/crmforecast/post'] = 'modulos/crm/Ccrmforecast/Add';

//Graficas
$route['api/v.1/crmgraficas/planvsact'] = 'modulos/crm/Cgraficas/getplanvsact';
$route['api/v.1/crmgraficas/ventas'] = 'modulos/crm/Cgraficas/getventas';
$route['api/v.1/crmgraficas/procesoventa'] = 'modulos/crm/Cgraficas/getprocesoventa';
$route['api/v.1/crmgraficas/forecast'] = 'modulos/crm/Cgraficas/getforecast';


$route['api/v.1/crmsuma/suma'] = 'modulos/finanzas/Cplanventas/sumasT';
$route['api/v.1/crmsumaAnual/suma'] = 'modulos/finanzas/Cplanventas/sumasAnual';
$route['api/v.1/crmgrafica/get'] = 'modulos/crm/Cgraficas/graficametas';
$route['api/v.1/crmgraficaVendida/get'] = 'modulos/crm/Cgraficas/graficametasVendidas';

$route['api/v.1/crmgraficacontador/get'] = 'modulos/crm/Cgraficas/graficaGross';
$route['api/v.1/crmgraficaGlobal/get'] = 'modulos/crm/Cgraficas/vendidasGlobales';
$route['api/v.1/porpuestaAnual/get'] = 'modulos/finanzas/Cplanventas/propAnual';

$route['api/v.1/vendedores/get'] = 'modulos/finanzas/Cplanventas/Vendedores';



//*******************************CUENTAS POR COBRAR y pagar*************************************************** */

#region RUTA proveedores
$route['api/v.1/ctaproveedores/get'] = 'modulos/ctaporpagar/Cproveedores/List';
$route['api/v.1/ctaproveedores/recovery'] = 'modulos/ctaporpagar/Cproveedores/Recovery';
$route['api/v.1/ctaproveedores/post'] = 'modulos/ctaporpagar/Cproveedores/Add';
$route['api/v.1/ctaproveedores/(:num)'] = 'modulos/ctaporpagar/Cproveedores/Delete/$1';
#endregion

#region RUTA cuentas por pagar
$route['api/v.1/ctaporpagar/get'] = 'modulos/ctaporpagar/Cctaporpagar/List';
$route['api/v.1/ctaporpagar/recovery'] = 'modulos/ctaporpagar/Cctaporpagar/Recovery';
$route['api/v.1/ctaporpagar/post'] = 'modulos/ctaporpagar/Cctaporpagar/Add';
$route['api/v.1/ctaporpagar/(:num)'] = 'modulos/ctaporpagar/Cctaporpagar/Delete/$1';
$route['api/v.1/ctaporpagar/changeestatus'] = 'modulos/ctaporpagar/Cctaporpagar/ChangeEstatus';
$route['api/v.1/ctaporpagar/updatevalidity'] = 'modulos/ctaporpagar/Cctaporpagar/UpdateValidity';


#endregion


#region RUTA cta por cobrar
$route['api/v.1/ctaporcobrar/get'] = 'modulos/ctaporpagar/Cctaporcobrar/List';
$route['api/v.1/ctaporcobrarNoCobrado/get'] = 'modulos/ctaporpagar/Cctaporcobrar/ListNoCobrado';

$route['api/v.1/ctaporcobrar/recovery'] = 'modulos/ctaporpagar/Cctaporcobrar/Recovery';
$route['api/v.1/ctaporcobrar/post'] = 'modulos/ctaporpagar/Cctaporcobrar/Add';
$route['api/v.1/ctaporcobrar/(:num)'] = 'modulos/ctaporpagar/Cctaporcobrar/Delete/$1';
$route['api/v.1/ctaporcobrar/changeestatus'] = 'modulos/ctaporpagar/Cctaporcobrar/ChangeEstatus';
$route['api/v.1/ctaporcobrar/updatevalidity'] = 'modulos/ctaporpagar/Cctaporcobrar/UpdateValidity';
$route['api/v.1/ctaporcobrar/addArchivo'] = 'modulos/ctaporpagar/Cctaporcobrar/addArchivo';

$route['api/v.1/ctaporcobrar/NombreClienteCobra'] = 'modulos/ctaporpagar/Cctaporcobrar/NombreCliente';
$route['api/v.1/ctaporcobrar/EmpresaSucursal'] = 'modulos/ctaporpagar/Cctaporcobrar/EmpresaSucursales';
$route['api/v.1/ctaporcobrar/getSinFecha'] = 'modulos/ctaporpagar/Cctaporcobrar/ListSinFecha';
$route['api/v.1/ctaporcobrar/getContratos'] = 'modulos/ctaporpagar/Cctaporcobrar/NumContrato';
//GRÁFICAS
$route['api/v.1/ctaporcobrar/getGraficaEstimadoGlobal'] = 'modulos/ctaporpagar/Cctaporcobrar/EstimadosCuentasGlobal';




#endregion

#region RUTA documentos del trabajador
$route['api/v.1/filestrabajador/post'] = 'catalogos/Cdocstrabajador/Add';
$route['api/v.1/filestrabajador/get'] = 'catalogos/Cdocstrabajador/List';

#region RUTA Levantamineto
$route['api/v.1/levantamiento/get'] = 'modulos/levantamiento/Clevantamiento/findAll';
$route['api/v.1/HorasDisponibles/get'] = 'modulos/levantamiento/Clevantamiento/HorasDisponibles';
$route['api/v.1/app/updateestatuslift'] = 'app/Cservices/ServiciochangeLevantamiento';
$route['api/v.1/levantamiento/updatestatus'] = 'modulos/levantamiento/Clevantamiento/UpdatestatusCot';


#region RUTA Menus
$route['api/v.1/menus/get'] = 'catalogos/Cmenus/List';
$route['api/v.1/permisoxpaquete/get'] = 'catalogos/Cmenus/Permisoxpaquete';
$route['api/v.1/menus/getsubmenuapartado'] = 'catalogos/Cmenus/getSubMenuApartado';
$route['api/v.1/menus/permisosxmenu']   = 'catalogos/Cmenus/ListPermisosxMenu';
$route['api/v.1/menus/addpermisoxmenu'] = 'catalogos/Cmenus/AddPermisoxmenu';
$route['api/v.1/menus/showpermisosxmenu'] = 'catalogos/Cmenus/showPermisosxMenu';

$route['api/v.1/permisos/permisosxmenu'] = 'catalogos/Cpermiso/ListPermisosxMenu';
$route['api/v.1/permisos/addpermisosxmenu'] = 'catalogos/Cpermiso/AddMenuxpermiso';

#RUTA DE ESTADOS FINANCIEROS
$route['api/v.1/financieroantiguo/get'] = 'modulos/estadosfinancieros/Cestadosfinancieros/getDataTodos';
//$route['api/v.1/pdfPOST/post'] = 'modulos/estadosfinancieros/Cestadosfinancieros/getDataPDF';

//RUTA DE PRUEBA FILTROS ESTADOS FINAN
// $route['api/v.1/CostoGA/get'] = 'modulos/estadosfinancieros/CestadosFinanTodos/CostoGA';
// $route['api/v.1/CostoDeptoVenta/get'] = 'modulos/estadosfinancieros/CestadosFinanTodos/getDeptoVenta';
// $route['api/v.1/CostoDeptoVehiculo/get'] = 'modulos/estadosfinancieros/CestadosFinanTodos/getCostosVehiOper';
// $route['api/v.1/CostoFinanciero/get'] = 'modulos/estadosfinancieros/CestadosFinanTodos/CostoFinanciero';
$route['api/v.1/CostoGA/get'] = 'modulos/estadosfinancieros/CestadosFinanGenFiltro/getCostoGA';
$route['api/v.1/CostoDeptoVenta/get'] = 'modulos/estadosfinancieros/CestadosFinanGenFiltro/getDeptoVenta';
$route['api/v.1/CostoDeptoVehiculo/get'] = 'modulos/estadosfinancieros/CestadosFinanGenFiltro/getCostosVehiculos';
$route['api/v.1/CostoFinanciero/get'] = 'modulos/estadosfinancieros/CestadosFinanGenFiltro/getCostosFinancieros';
$route['api/v.1/nuevo/get'] = 'modulos/estadosfinancieros/CestadosFinanGenFiltro/getDeptoOper';

//prueba
// $route['api/v.1/nuevo/get'] = 'modulos/estadosfinancieros/CestadosFinanTodos/getDespVentaNuevo';
//PDFS
$route['api/v.1/reporte/CostoGA'] = 'modulos/estadosfinancieros/CestadosFinancierosTodosPdf/CostoGA';
$route['api/v.1/reporte/CostoDeptoVenta'] = 'modulos/estadosfinancieros/CestadosFinancierosTodosPdf/getDeptoVenta';
$route['api/v.1/reporte/CostoVehiculoGen'] = 'modulos/estadosfinancieros/CestadosFinancierosTodosPdf/getCostosVehiOper';
$route['api/v.1/reporte/CostoFinan'] = 'modulos/estadosfinancieros/CestadosFinancierosTodosPdf/CostoFinanciero';
$route['api/v.1/reporte/DeptoOper'] = 'modulos/estadosfinancieros/CestadosFinancierosTodosPdf/getDespVentaNuevo';

#region RUTA Personal operativo
$route['api/v.1/personaloperativo/get'] = 'modulos/estadosfinancieros/Cpersonaloperativo/findAll';
$route['api/v.1/personaloperativo/post'] = 'modulos/estadosfinancieros/Cpersonaloperativo/Add';

#region RUTA Dashboard Despacho
$route['api/v.1/despachoone/post'] = 'modulos/dashboard/Cdashboradone/findAll';
$route['api/v.1/despachoonefactura/get'] = 'modulos/dashboard/Cdashboradone/Facturable';
$route['api/v.1/despachooneequipo/get'] = 'modulos/dashboard/Cdashboradone/HorasEquipo';
$route['api/v.1/despachooneproductividad/get'] = 'modulos/dashboard/Cdashboradone/operproductividad';
$route['api/v.1/finanzasone/get'] = 'modulos/dashboard/Cdashboradfinanzas/findAll';
$route['api/v.1/finanzasPlanFactura/get'] = 'modulos/dashboard/Cdashboradfinanzas/PlanFactura';
$route['api/v.1/finanzasFactura/get'] = 'modulos/dashboard/Cdashboradfinanzas/Factura';
$route['api/v.1/finanzasServicios/get'] = 'modulos/dashboard/Cdashboradfinanzas/Servicios';
$route['api/v.1/finanzasPorcentaje/get'] = 'modulos/dashboard/Cdashboradfinanzas/Porcentaje';
$route['api/v.1/finanzasGrossProfit/get'] = 'modulos/dashboard/Cdashboradfinanzas/GrossProfit';
$route['api/v.1/finanzasCuentasTotal/get'] = 'modulos/dashboard/Cdashboradfinanzas/cuentasTotal';


$route['api/v.1/converimgagen/get'] = 'modulos/reports/Cdespacho/createimage';

$route['api/v.1/cerrarsesion/post'] = 'app/Cservices/CerrarSesion';
$route['api/v.1/cerrarsesionven/post'] = 'app/Cservices/CerrarSesionVendedor';

$route['api/v.1/servicio/calendarday'] = 'modulos/despacho/Cservicio/listcalendarioday';

$route['api/v.1/estadosfinancierosudpatefact/get'] = 'modulos/estadosfinancieros/Cactualizarestados/getDataTodosFac';
$route['api/v.1/estadosfinancierosinfo/get'] = 'modulos/estadosfinancieros/Cestadosfinancierosinfo/getDataTodosInfo';
$route['api/v.1/estadosfinancierosUpt/post'] = 'modulos/estadosfinancieros/Csaveactualizarfinanzas/Add';
$route['api/v.1/PlanMensual2022/get'] = 'modulos/estadosfinancieros/Cestadosfinancierosinfo2022/facturacionServicios';
//!pdf
$route['api/v.1/PlanMensual2022PDF/get'] = 'modulos/estadosfinancieros/CestadosfinancierosinfoPDF/getDataPDF';
