import store from "../store/index";
import Vue from "vue";
import VueRouter from "vue-router";
import AdminTemplate from "../views/admin/AdminTemplate.vue";
import Home from "../views/Home.vue";
import Usuarios from "../views/catalogos/usuarios/list.vue";
import Clientes from "../views/catalogos/clientes/list.vue";
import Personal from "../views/catalogos/personal/List.vue";
import TipoUnidad from "../views/catalogos/tipounidad/List.vue";
import TipoServicio from "../views/catalogos/tiposervicio/List.vue";
import Folio from "../views/catalogos/folio/List.vue";
import Servicio from "../views/catalogos/servicio/List.vue";
import Equipos from "../views/catalogos/equipos/List.vue";
import Equipamiento from "../views/catalogos/equipamiento/List.vue";
import Concepto from "../views/catalogos/concepto/List.vue";
import Vehiculo from "../views/catalogos/vehiculo/List.vue";
import CategoriaVehiculo from "../views/catalogos/categoriavehiculo/List.vue";
import HorasLaborales from "../views/catalogos/horaslaborales/Form.vue";
import Sucursal from "../views/catalogos/sucursal/List.vue";
import CategoriaPersonal from "../views/catalogos/categoriapersonal/List.vue";
import FormUser from "../views/catalogos/usuarios/Form.vue";
import EmpresaLista from "../views/catalogos/empresas/List.vue";
import Login from "../views/segurity/Login.vue";
import ClienteSucursal from "../views/catalogos/clientesucursal/List.vue";
import ContratoClienteSucursal from "../views/catalogos/contratos/List.vue";
import ListUsu from "../views/catalogos/clientes/ListUsu.vue";
import Mapa from "../views/catalogos/mapa/Mapa.vue";
import PdfEquipo from "../views/catalogos/pdfequipo/List.vue";

import Despacho from "../views/modulos/despacho/despacho.vue";
import ListServicio from "../views/modulos/servicios/List.vue";
import calendar from "../views/modulos/despacho/calendar.vue";

import SubmenuAdmon from "../views/submenu/Admon.vue";
import CostosVarios from "../views/catalogos/costosvarios/List.vue";

import SubmenuCotizacion from "../views/submenu/Cotizacion.vue";
import Cotizacion_Materiales from "../views/catalogos/cotizacion/material/List.vue";
import Cotizacion_Costokm from "../views/catalogos/cotizacion/costoskm/List.vue";
import Cotizacion_Costota from "../views/catalogos/cotizacion/costosta/List.vue";
import Cotizacion_Markup from "../views/catalogos/cotizacion/markup/List.vue";
import Cotizacion_List from "../views/modulos/cotizacion/List.vue";
import Cotizacion_Principal from "../views/modulos/cotizacion/Principal.vue";

import CajaChica from "../views/modulos/cajachica/index.vue";
import Registro from "../views/catalogos/cajachica/List.vue";
import Caja from "../views/catalogos/caja/List.vue";
import AsignacionCaja from "../views/catalogos/AsignacionCaja/List.vue";
import ReporteGastos from "../views/modulos/cajachica/reportegastos/list.vue";
import listagastos from "../views/modulos/cajachica/reportegastos/listgastos.vue";

import SubMenSpendplan from "../views/submenu/Spendplan.vue";
import Spend_proyecto from "../views/modulos/spendplan/proyecto/List.vue";
import Spend_proform from "../views/modulos/spendplan/proyecto/Form.vue";
import Spend_ordenc from "../views/modulos/spendplan/ordenc/List.vue";
import Spend_dashboard from "../views/modulos/spendplan/dashboard/Dashboard.vue";
import Spend_Hora from "../views/modulos/spendplan/horas/List.vue";

import DashboardDespacho from "../views/modulos/dashboard/despacho/Principal.vue";
import DashboardFinanza from "../views/modulos/dashboard/finanzas/Principal.vue";
import DashboardCrm from "../views/modulos/dashboard/crm/Principal.vue";
import DashboardPm from "../views/modulos/dashboard/pm/Principal.vue";

import mnufacturacion from "../views/submenu/Facturacion.vue";
import listaserfac from "../views/modulos/factura/List.vue";
import formfact from "../views/modulos/factura/Form.vue";
import ListFact from "../views/modulos/factura/ListFactura.vue";
import FacturaLibre from "../views/modulos/factura/FacturaLibre.vue";


import crm from "../views/modulos/crm/index.vue";
import Contactos from "../views/modulos/crm/contactos/list.vue";
import Contactosucursal from "../views/modulos/crm/contactosucursal/List.vue";
import oportunidad from "../views/modulos/crm/oportunidad/List.vue";
import tipoproceso from "../views/modulos/crm/tiposprocesos/List.vue";
import procesos from "../views/modulos/crm/procesos/List.vue";
import vendedores from "../views/modulos/crm/vendedores/List.vue";
import pipedrive from "../views/modulos/crm/pipedrive/List.vue";
import forecast from "../views/modulos/crm/forecast/List.vue";

import Monitoreo_cli from "../views/modulos/monitoreo/Clientes.vue";
import Mon_Sucursal from "../views/modulos/monitoreo/Sucursales.vue";
import Mon_Equipo from "../views/modulos/monitoreo/Equipos.vue";
import Mon_Histequipo from "../views/modulos/monitoreo/HistEquipo.vue";
import Mon_Cotizacion from "../views/modulos/monitoreo/cotizaciones/List.vue";
import Mon_Calendario from "../views/modulos/monitoreo/Calendario.vue";
import Mon_Solicitudes from "../views/modulos/monitoreo/solicitudes/List.vue";

//Root
import MenusRoot from "../views/submenu/AdmonRoot.vue";
import empresasroot from "../views/catalogos/Root/Empresa/List.vue";
import sucursalroot from "../views/catalogos/Root/Sucursales/List.vue";
import usersucursal from "../views/catalogos/Root/Sucursales/ListUsuarios.vue";

import configequipamiento from "../views/catalogos/Root/ConfigEquipos/List.vue";
import configconceptos from "../views/catalogos/Root/Conceptos/List.vue";
import ConfigCorreos from "../views/catalogos/Root/Correo/List.vue";

//Finanzas
import MenusFinanzas from "../views/submenu/Finanzas.vue";
import SubMenusFinanzas from "../views/submenu/Finanzas2.vue";
//plan de ventas
import planventas from "../views/modulos/finanzas/planventas/Form.vue";
//Base actual
import baseactual from "../views/modulos/finanzas/baseactual/List.vue";
//Porcentaje operacion
import porcentajeoper from "../views/modulos/finanzas/porcentajeoperacion/Form.vue";
//Costo depto ventas
import costosventas from "../views/modulos/finanzas/costodeptoventas/Form.vue";
//Costo depto operaciones
import costooperaciones from "../views/modulos/finanzas/costodeptooper/Form.vue";
//Costo costovehope
import costovehope from "../views/modulos/finanzas/costovehope/Form.vue";
//Costo gostosga
import costoga from "../views/modulos/finanzas/costosga/Form.vue";
//Costo financieros
import costofinanciero from "../views/modulos/finanzas/costofinanciero/Form.vue";
//Costo financieros
import actcostadmin from "../views/modulos/finanzas/actcostadmin/Form.vue";
//Costo financieros
import estadofupdate from "../views/modulos/finanzas/estadofupdate/Form.vue";
//estadosfinancieros
import estadosfinancieros from "../views/modulos/finanzas/estadosfinancieros/Form.vue";
import estadosfgral from "../views/modulos/finanzas/estadosfinancieros/General.vue";

//reset password
import RecoveryAccount from "../views/segurity/RecoveryAccount.vue";
import ConfirmPassword from "../views/segurity/confirmpass.vue";

//Cuentas por cobra y pagar
import menuctacobrarpagar from "../views/submenu/CtaCobrarPagar.vue";
import ctaproveedores from "../views/modulos/ctaporcobrarpagar/proveedores/List.vue";
import ctacuentasporpagaradmin from "../views/modulos/ctaporcobrarpagar/ctaporpagaradmin/List.vue";
import ctacuentasporpagaropera from "../views/modulos/ctaporcobrarpagar/ctaporpagaropera/List.vue";
import ctacuentasporcobrar from "../views/modulos/ctaporcobrarpagar/ctaporcobrar/List.vue";
import pipedrive2022 from "../views/modulos/crm/pipedrive/pipedrive.vue";

//Permisos
import Cpermiso from "../views/catalogos/permiso/List.vue";
import Perfiles from "../views/catalogos/perfilesconfig/List.vue";
import AsignarPermisos from "../views/catalogos/perfilpermiso/List.vue";
import Listapaquetes from "../views/catalogos/paquete/List.vue";

import NewMapa from "../views/modulos/Geolocalizacion.vue";

//Levantamiento
import Levantamientolist from "../views/modulos/Levantamiento/List.vue";

Vue.use(VueRouter);

const routes = [
	{
		path: "/",
		name: "Login",
		component: Login,
		props: true
	},
	{
		path: "/recoveryaccount",
		name: "recoveryaccount",
		component: RecoveryAccount
	},
	{
		path: "/FormConfirm/:user/:tkn",
		name: "confirmpassword",
		component: ConfirmPassword,
		props: true
		//props:{ prop1: '' }
	},

	{
		path: "/admin",
		name: "AdminInicio",
		component: AdminTemplate,
		props: true,
		meta: {
			requiresAuth: true
		},
		children: [
			{
				path: "/usuarios",
				name: "usuarios",
				component: Usuarios,
				props: true
			},
			{
				path: "/asignacioncaja",
				name: "asignacioncaja",
				component: AsignacionCaja,
				props: true
			},
			{
				path: "/categoriavehiculo",
				name: "categoriavehiculo",
				component: CategoriaVehiculo,
				props: true
			},
			{
				path: "/caja",
				name: "caja",
				component: Caja,
				props: true
			},
			{
				path: "/reportegastos",
				name: "reportegastos",
				component: ReporteGastos,
				props: true
			},
			{
				path: "/listagastos",
				name: "listagastos",
				component: listagastos,
				props: true
			},
			{
				path: "/registro",
				name: "registro",
				component: Registro,
				props: true
			},
			{
				path: "/servicio",
				name: "servicio",
				component: Servicio
			},
			{
				path: "/cajachica",
				name: "cajachica",
				component: CajaChica,
				props: true
			},
			{
				path: "/equipamiento",
				name: "equipamiento",
				component: Equipamiento
			},
			{
				path: "/concepto",
				name: "concepto",
				component: Concepto,
				props: true
			},
			{
				path: "/equipos",
				name: "equipos",
				component: Equipos,
				props: true
			},
			{
				path: "/sucursal",
				name: "sucursal",
				component: Sucursal,
				props: true
			},
			{
				path: "/usuarios/form",
				name: "usuariosForm",
				component: FormUser,
				props: true
			},
			{
				path: "/clientes",
				name: "clientes",
				component: Clientes,
				props: true
			},
			{
				path: "/empresas",
				name: "empresas",
				component: EmpresaLista,
				props: true
			},
			{
				path: "/horaslaborales",
				name: "horaslaborales",
				component: HorasLaborales,
				props: true
			},
			{
				path: "/personal",
				name: "personal",
				component: Personal,
				props: true
			},
			{
				path: "/tipounidad",
				name: "tipounidad",
				component: TipoUnidad,
				props: true
			},
			{
				path: "/tiposervicio",
				name: "tiposervicio",
				component: TipoServicio,
				props: true
			},
			{
				path: "/folio",
				name: "folio",
				component: Folio,
				props: true
			},
			{
				path: "/categoriapersonal",
				name: "categoriapersonal",
				component: CategoriaPersonal,
				props: true
			},
			{
				path: "/vehiculo",
				name: "vehiculo",
				component: Vehiculo,
				props: true
			},
			{
				path: "/clientesucursal",
				name: "clientesucursal",
				component: ClienteSucursal,
				props: true
			},
			{
				path: "/contratoclientesucursal",
				name: "contratoclientesucursal",
				component: ContratoClienteSucursal,
				props: true
			},
			{
				path: "/listusu",
				name: "listusu",
				component: ListUsu,
				props: true
			},
			{
				path: "/pdfequipo",
				name: "pdfequipo",
				component: PdfEquipo,
				props: true
			},
			{
				path: "/despacho",
				name: "despacho",
				component: Despacho,
				props: true
			},
			{
				path: "/mapa",
				name: "Mapa",
				component: Mapa,
				props: true
			},
			{
				path: "/calendar",
				name: "calendar",
				component: calendar,
				props: true
			},
			{
				path: "/submenuadmon",
				name: "submenuadmon",
				component: SubmenuAdmon
			},
			{
				path: "/costosvarios",
				name: "costosvarios",
				component: CostosVarios,
				props: true
			},
			{
				path: "/submenucotizacion",
				name: "submenucotizacion",
				component: SubmenuCotizacion,
				props: true
			},
			{
				path: "/cotizacion_materiales",
				name: "cotizacion_materiales",
				component: Cotizacion_Materiales,
				props: true
			},
			{
				path: "/cotizacion_costoskm",
				name: "cotizacion_costoskm",
				component: Cotizacion_Costokm,
				props: true
			},
			{
				path: "/cotizacion_costosta",
				name: "cotizacion_costosta",
				component: Cotizacion_Costota,
				props: true
			},
			{
				path: "/cotizacion_markup",
				name: "cotizacion_markup",
				component: Cotizacion_Markup,
				props: true
			},
			{
				path: "/cotizacion_list",
				name: "cotizacion_list",
				component: Cotizacion_List,
				props: true
			},
			{
				path: "/cotizacion_principal",
				name: "cotizacion_principal",
				component: Cotizacion_Principal,
				props: true
			},
			{
				path: "/listservicio",
				name: "listservicio",
				component: ListServicio,
				props: true
			},
			{
				path: "/spendplan",
				name: "spendplan",
				component: SubMenSpendplan,
				props: true
			},
			{
				path: "/spend_proyecto",
				name: "spend_proyecto",
				component: Spend_proyecto,
				props: true
			},
			{
				path: "/spend_proform",
				name: "spend_proform",
				component: Spend_proform,
				props: true
			},
			{
				path: "/spend_ordenc",
				name: "spend_ordenc",
				component: Spend_ordenc,
				props: true
			},
			{
				path: "/spend_hora",
				name: "spend_hora",
				component: Spend_Hora,
				props: true
			},
			{
				path: "/spend_dashboard",
				name: "spend_dashboard",
				component: Spend_dashboard,
				props: true
			},
			{
				path: "/dashboarddespacho",
				name: "dashboarddespacho",
				component: DashboardDespacho,
				props: true
			},
			{
				path: "/dashboardfinanza",
				name: "dashboardfinanza",
				component: DashboardFinanza,
				props: true
			},
			{
				path: "/dashboardcrm",
				name: "dashboardcrm",
				component: DashboardCrm,
				props: true
			},
			{
				path: "/dashboardpm",
				name: "dashboardpm",
				component: DashboardPm,
				props: true
			},
			{
				path: "/monitoreo_cli",
				name: "monitoreo_cli",
				component: Monitoreo_cli,
				props: true
			},
			{
				path: "/mon_sucursal",
				name: "mon_sucursal",
				component: Mon_Sucursal,
				props: true
			},
			{
				path: "/mon_equipo",
				name: "mon_equipo",
				component: Mon_Equipo,
				props: true
			},
			{
				path: "/mon_histequipo",
				name: "mon_histequipo",
				component: Mon_Histequipo,
				props: true
			},
			{
				path: "/mon_cotizacion",
				name: "mon_cotizacion",
				component: Mon_Cotizacion,
				props: true
			},
			{
				path: "/mon_reporte",
				name: "mon_reporte",
				component: Mon_Cotizacion,
				props: true
			},
			{
				path: "/mon_calendario",
				name: "mon_calendario",
				component: Mon_Calendario,
				props: true
			},
			{
				path: "/mon_solicitudes",
				name: "mon_solicitudes",
				component: Mon_Solicitudes,
				props: true
			},
			//Factura
			{
				path: "/submenufact",
				name: "submenufact",
				component: mnufacturacion,
				props: true
			},
			{
				path: "/listaserfac",
				name: "listaserfac",
				component: listaserfac,
				props: true
			},
			{
				path: "/formfact",
				name: "formfact",
				component: formfact,
				props: true
			},
			{
				path: "/ListFacturas",
				name: "ListFacturas",
				component: ListFact,
				props: true
			},
			{
				path: "/FacturaLibre",
				name: "FacturaLibre",
				component: FacturaLibre,
				props: true
			},

			,
			//crm
			{
				path: "/submenucrm",
				name: "submenucrm",
				component: crm,
				props: true
			},
			{
				path: "/crmcontactos",
				name: "crmcontactos",
				component: Contactos,
				props: true
			},
			{
				path: "/crmcontactosucursal",
				name: "crmcontactosucursal",
				component: Contactosucursal,
				props: true
			},
			{
				path: "/crmoportunidad",
				name: "crmoportunidad",
				component: oportunidad
			},
			{
				path: "/crmtiposprocesos",
				name: "crmtiposprocesos",
				component: tipoproceso,
				props: true
			},
			{
				path: "/crmprocesos",
				name: "crmprocesos",
				component: procesos,
				props: true
			},
			{
				path: "/crmvendedores",
				name: "crmvendedores",
				component: vendedores,
				props: true
			},
			{
				path: "/crmpipedrive",
				name: "crmpipedrive",
				component: pipedrive2022,
				props: true
			},
			{
				path: "/crmforecast",
				name: "crmforecast",
				component: forecast,
				props: true
			},
			{
				path: "/pipedrive2022",
				name: "pipedrive2022",
				component: pipedrive2022,
				props: true
			},
			//MenusFinanzas
			{
				path: "/MenusFinanzas",
				name: "MenusFinanzas",
				component: MenusFinanzas,
				props: true
			},
			,
			{
				path: "/SubMenusFinanzas",
				name: "SubMenusFinanzas",
				component: SubMenusFinanzas,
				props: true
			},
			{
				path: "/planventas",
				name: "planventas",
				component: planventas,
				props: true
			},
			{
				path: "/baseactual",
				name: "baseactual",
				component: baseactual,
				props: true
			},
			,
			{
				path: "/porcentajeoper",
				name: "porcentajeoper",
				component: porcentajeoper,
				props: true
			},
			{
				path: "/costosventas",
				name: "costosventas",
				component: costosventas,
				props: true
			},
			{
				path: "/costooperaciones",
				name: "costooperaciones",
				component: costooperaciones,
				props: true
			},
			{
				path: "/costovehope",
				name: "costovehope",
				component: costovehope,
				props: true
			},
			{
				path: "/costoga",
				name: "costoga",
				component: costoga,
				props: true
			},
			{
				path: "/costofinanciero",
				name: "costofinanciero",
				component: costofinanciero,
				props: true
			},
			{
				path: "/actcostadmin",
				name: "actcostadmin",
				component: actcostadmin,
				props: true
			},
			{
				path: "/estadofupdate",
				name: "estadofupdate",
				component: estadofupdate,
				props: true
			},
			{
				path: "/estadosfinancieros",
				name: "estadosfinancieros",
				component: estadosfinancieros,
				props: true
			},
			{
				path: "/estadosfgral",
				name: "estadosfgral",
				component: estadosfgral,
				props: true
			},
			{
				path: "/menuctacobrarpagar",
				name: "menuctacobrarpagar",
				component: menuctacobrarpagar,
				props: true
			},
			{
				path: "/ctaproveedores",
				name: "ctaproveedores",
				component: ctaproveedores,
				props: true
			},
			{
				path: "/ctacuentasporcobrar",
				name: "ctacuentasporcobrar",
				component: ctacuentasporcobrar,
				props: true
			},
			{
				path: "/cpermiso",
				name: "cpermiso",
				component: Cpermiso,
				props: true
			},
			{
				path: "/menulevantamiento",
				name: "menulevantamiento",
				component: Levantamientolist,
				props: true
			},
			{
				path: "/permisos",
				name: "perfiles",
				component: Perfiles,
				props: true
			},
			{
				path: "/asignarpermisos",
				name: "asignarpermisos",
				component: AsignarPermisos,
				props: true
			},
			{
				path: "/paquetes",
				name: "paquetes",
				component: Listapaquetes,
				props: true
			},
			{
				path: "/Geolocalizacion",
				name: "Geolocalizacion",
				component: NewMapa,
				props: true
			}
		]
	},
	{
		path: "/root/",
		name: "RootInicio",
		component: AdminTemplate,
		meta: {
			requiresAuth: true
		},
		children: [
			{
				path: "/MenusRoot",
				name: "MenusRoot",
				component: MenusRoot,
				props: true
			},
			{
				path: "/empresasroot",
				name: "empresasroot",
				component: empresasroot,
				props: true
			},
			{
				path: "/sucursalroot",
				name: "sucursalroot",
				component: sucursalroot,
				props: true
			},
			{
				path: "/usersucursal",
				name: "usersucursal",
				component: usersucursal,
				props: true
			},
			{
				path: "/configequipamiento",
				name: "configequipamiento",
				component: configequipamiento,
				props: true
			},
			{
				path: "/configconceptos",
				name: "configconceptos",
				component: configconceptos,
				props: true
			},
			{
				path: "/ConfigCorreos",
				name: "ConfigCorreos",
				component: ConfigCorreos,
				props: true
			},
			{
				path: "/cuentasporpagaradmin",
				name: "cuentasporpagaradmin",
				component: ctacuentasporpagaradmin,
				props: true
			},
			{
				path: "/cuentasporpagaropera",
				name: "cuentasporpagaropera",
				component: ctacuentasporpagaropera,
				props: true
			}
		]
	}
];

const router = new VueRouter({
	mode: 'history',
	routes
});

router.beforeEach((to, from, next) => {
	if (to.matched.some(record => record.meta.requiresAuth)) {
		if (store.getters.isLoggedIn) {
			//console.log(to.name);
			var datos = JSON.parse(sessionStorage.getItem("user"));
			var listaPaquetes = datos.listaPaquetes;
			/*console.log(listaPaquetes);
            console.log(to.name);
            console.log(to);*/
			var keymenu = to.name;
			if (keymenu == "despacho") {
				keymenu = "Despacho";
			} else if (keymenu == "cajachica") {
				keymenu = "Caja Chica";
			} else if (keymenu == "clientes") {
				//este menu recipe un parametro para diferenciar de otro contenido
				var TipoList = JSON.parse(sessionStorage.getItem("IdSaved"));
				if (to.params.tipolistp == "Scanning") {
					keymenu = "Scan";
				} else if (TipoList == "Scanning") {
					keymenu = "Scan";
				}
				if (to.params.tipolistp == "clientes") {
					keymenu = "otro";
				} else if (TipoList == "clientes") {
					keymenu = "otro";
				}
			} else if (keymenu == "monitoreo_cli") {
				keymenu = "Monitoreo";
			} else if (keymenu == "submenucotizacion") {
				keymenu = "Cotizacion";
			} else if (keymenu == "submenufact") {
				keymenu = "Facturacion";
			} else if (keymenu == "submenucrm") {
				keymenu = "CRM";
			} //no existe aun
			else if (keymenu == "MenusFinanzas") {
				keymenu = "Finanzas";
			} else if (keymenu == "spendplan") {
				keymenu = "Spend Plan";
			} else {
				//es un submenu no tiene permisos
				keymenu = "otro";
			}

			var arrresul = listaPaquetes.filter(function(item) {
				return item.Paquete == keymenu;
			});

			if (arrresul.length > 0) {
				//tiene permiso al menu
				next();
			} else if (
				keymenu == "AdminInicio" ||
				keymenu == "submenuadmon" ||
				keymenu == "otro"
			) {
				next();
			}

			/*if(to.name=='despacho'){
                if(datos.Perfil=='Root'){
                  next();
                }
            }else{
              next();
            }*/
			//next();

			return;
		}
		next("/");
		console.log("debes iniciar session");
	} else {
		next();
	}
});

export default router;
