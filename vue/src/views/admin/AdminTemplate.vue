<template>
	<div>
		<div class="top-bar">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-sm-12 col-md-3 col-lg-6">
						<h2 v-if="status" class="nombre-empresa">{{ Empresa }}</h2>
						<h2 v-else class="nombre-empresa">Desprosoft 4.0</h2>
					</div>

					<div class="col-12 col-sm-12 col-md-9 col-lg-6 text-right">
						<div class="form-inline justify-content-end">
							<div class="dropdown mr-2">
								<button
									@click="verAlertas()"
									class="btn btn-05 btn-07 dropdown-toggle"
									type="button"
									id="alertas"
									data-toggle="dropdown"
									aria-haspopup="true"
									aria-expanded="false"
								>
									<i class="fas fa-bell"></i>
									<span
										v-if="countNotifi > 0"
										class="new-indicator text-alert d-none d-lg-block"
									>
										<i class="fas fa-fw fa-circle"></i>
										<span class="number">{{ countNotifi }}</span>
									</span>
								</button>
								<alertas-component
									v-if="showAlertaComponent"
									ref="alertasComponent"
								/>
							</div>

							<div class="dropdown">
								<button
									class="btn btn-05 dropdown-toggle"
									type="button"
									id="dropdownMenuButton"
									data-toggle="dropdown"
									aria-haspopup="true"
									aria-expanded="false"
								>
									{{ this.Nombre }} &nbsp;
									<img :src="Foto" width="30" class="rounded-circle" />
								</button>
								<div
									class="dropdown-menu dropdown-menu-user dropdown-menu-right"
									aria-labelledby="dropdownMenuButton"
								>
									<div class="dropdown-menu-header">
										<div class="widget-content p-0">
											<div class="widget-content-wrapper">
												<div class="widget-content-left mr-3">
													<img
														width="42"
														class="rounded-circle"
														:src="Foto"
														alt=""
													/>
												</div>
												<div class="widget-content-left">
													<p class="widget-nombre">
														{{ this.Nombre }} <br />
														<span class="widget-puesto">{{ Perfil }}</span>
													</p>
												</div>
											</div>
										</div>
									</div>
									<a
										@click="OpenPerfil"
										data-toggle="modal"
										data-target="#ModalPerfil"
										data-backdrop="static"
										class="dropdown-item"
									>
										<i class="fas fa-user fa-fw-m"></i> Mi Perfil
									</a>
									<a
										v-if="IsAdmin"
										@click="VerCuenta"
										data-toggle="modal"
										data-target="#ModalCuenta"
										data-backdrop="static"
										class="dropdown-item"
									>
										<i class="fas fa-building"></i> Mi Cuenta
									</a>

									<div class="dropdown-divider"></div>
									<div class="grid-menu">
										<div v-if="IsAdmin" class="no-gutters row"></div>
									</div>
									<div class="dropdown-divider"></div>
									<div class="row grid-menu-mess">
										<div class="col-sm-12 text-center">
											<button
												type="button"
												@click="CerrarSession"
												class="btn btn-01"
											>
												<i class="fas fa-door-open fa-fw-m"></i> Salir
											</button>
										</div>
									</div>
								</div>
							</div>

							<!--Fin notificaciones y perfil-->
						</div>
					</div>
				</div>
			</div>

			<div class="icono-empresa">
				<img v-if="status" :src="LogoE" class="img-fluid" alt="" />
				<img v-else src="@/style/images/logo.png" class="img-fluid" alt="" />
			</div>
		</div>

		<nav class="navbar navbar-expand-xl bg-desprosoft">
			<div class="container-fluid h-100">
				<button
					class="navbar-toggler ml-auto mr-0"
					type="button"
					data-toggle="collapse"
					data-target="#navbarSupportedContent"
					aria-controls="navbarSupportedContent"
					aria-expanded="false"
					aria-label="Toggle navigation"
				>
					<i class="fas fa-bars tm-nav-icon"></i>
				</button>
				<!-- 
           class="collapse navbar-collapse justify-content-end"
          -->
				<div :class="ValidarVistas()" id="navbarSupportedContent">
					<ul class="navbar-nav h-100">
						<li
							v-for="(item, index) in ListaPaquetes"
							@click="ChangeClase(index)"
							:key="index"
							:class="validateclas(item.IdPaquete)"
						>
							<template v-if="item.IdPaquete == 1">
								<router-link
									:class="item.Clase"
									to="/despacho"
									v-b-tooltip.hover.topbottom
									:title="item.Nombre"
								>
									<i class="fas fa-shipping-fast"></i>
									{{ item.Nombre }}
								</router-link>
							</template>

							<template v-if="item.IdPaquete == 9">
								<router-link
									:class="item.Clase"
									:to="{ name: 'monitoreo_cli', params: {} }"
									v-b-tooltip.hover.topbottom
									:title="item.Nombre"
								>
									<i class="fas fa-search-location"></i>
									{{ item.Nombre }}
								</router-link>
							</template>

							<template v-if="item.IdPaquete == 8">
								<router-link
									:class="item.Clase"
									:to="{ name: 'clientes', params: { tipolistp: 'Scanning' } }"
									v-b-tooltip.hover.topbottom
									:title="item.Nombre"
								>
									<i class="fas fa-scanner-keyboard"></i>
									{{ item.Nombre }}
								</router-link>
							</template>

							<!-- <template v-if="item.IdPaquete == 25">
								<router-link
									:class="item.Clase"
									:to="{ name: 'spendplan' }"
									v-b-tooltip.hover.topbottom
									:title="item.Nombre"
								>
									<i class="fal fa-analytics"></i>
									Spend
								</router-link>
							</template> -->

							<template v-if="item.IdPaquete == 2">
								<router-link
									:class="item.Clase"
									:to="{ name: 'MenusFinanzas' }"
									v-b-tooltip.hover.topbottom
									:title="item.Nombre"
								>
									<i class="fas fa-chart-line"></i>
									{{ item.Nombre }}
								</router-link>
							</template>

							<template v-if="item.IdPaquete == 13">
								<router-link
									:class="item.Clase"
									:to="{ name: 'menuctacobrarpagar' }"
									v-b-tooltip.hover.topbottom
									:title="item.Nombre"
								>
									<i class="fas fa-file-certificate"></i>
									Cuentas
								</router-link>
							</template>

							<template v-if="item.IdPaquete == 21">
								<router-link
									:class="item.Clase"
									:to="'/cajachica'"
									v-b-tooltip.hover.topbottom
									title="Viáticos"
								>
									<i class="far fa-usd-square"></i>
									Viáticos
								</router-link>
							</template>

							<template v-if="item.IdPaquete == 17">
								<router-link
									:class="item.Clase"
									:to="{ name: 'submenufact' }"
									v-b-tooltip.hover.topbottom
									:title="item.Nombre"
								>
									<i class="fal fa-analytics"></i>
									{{ item.Nombre }}
								</router-link>
							</template>

							<template v-if="item.IdPaquete == 7">
								<router-link
									:class="item.Clase"
									:to="{ name: 'submenucrm' }"
									v-b-tooltip.hover.topbottom
									:title="item.Nombre"
								>
									<i class="fal fa-chart-network"></i>
									{{ item.Nombre }}
								</router-link>
							</template>

							<template v-if="item.IdPaquete == 3">
								<router-link
									:class="item.Clase"
									:to="{ name: 'submenucotizacion' }"
									v-b-tooltip.hover.topbottom
									:title="item.Nombre"
								>
									<i class="fas fa-file-invoice-dollar"></i>
									{{ item.Nombre }}
								</router-link>
							</template>

							<!-- <template v-if="item.IdPaquete == 15">
                <router-link
                  :class="item.Clase"
                  :to="{ name: 'menulevantamiento' }"
                  v-b-tooltip.hover.topbottom
                  :title="item.Nombre"
                >
                  <i class="fas fa-clipboard-list"></i>
                  {{ item.Nombre }}
                </router-link>
              </template>

              <template v-if="item.IdPaquete == 23">
                <a
                  class="nav-link disabled"
                  href="#"
                  v-b-tooltip.hover.topbottom
                  :title="item.Nombre"
                >
                  <i class="far fa-user-hard-hat"></i>
                  Contractor
                </a>
              </template>-->

							<template v-if="item.IdPaquete == 19">
								<a
									:class="item.Clase + ' dropdown-toggle'"
									data-toggle="dropdown"
									href="#"
									role="button"
									aria-haspopup="true"
									aria-expanded="false"
								>
									<i class="fas fa-chart-pie"></i>
									Dashboard
								</a>
								<div class="dropdown-menu dropdown-menu-right shadow">
									<router-link
										class="dropdown-item"
										:to="{ name: 'dashboarddespacho' }"
										>Despacho</router-link
									>

									<router-link
										class="dropdown-item"
										:to="{ name: 'dashboardfinanza' }"
										>Finanzas</router-link
									>
									<router-link
										class="dropdown-item"
										:to="{ name: 'dashboardcrm' }"
										>CRM</router-link
									>
								</div>
							</template>
						</li>

						<!-- <li class="nav-item dropdown laterar-r">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-chart-pie"></i>
              Dashboard
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow">
              <router-link class="dropdown-item" :to="{name:'dashboarddespacho'}" >Despacho</router-link>
              <router-link  class="dropdown-item" :to="{name:'dashboardfinanza'}" >Finanzas</router-link>
              <router-link class="dropdown-item" :to="{name:'dashboardcrm'}" >CRM</router-link>
            </div>
          </li> -->

						<li v-if="IsAdmin" class="nav-item">
							<router-link
								:class="estausClaseadmin"
								:to="{ name: 'submenuadmon' }"
								v-b-tooltip.hover.topbottom
								title="Administración"
							>
								<i class="fas fa-cogs"></i>
								Admin
							</router-link>
						</li>

						<!---MENU CLIENTES--->
						<li v-if="IsCliente" class="nav-item laterar-r">
							<router-link
								class="nav-link"
								:to="{ name: 'mon_sucursal', params: {} }"
							>
								<i class="fas fa-search-location"></i>
								Monitoreo
							</router-link>
						</li>

						<li v-if="IsCliente" class="nav-item laterar-r">
							<router-link
								class="nav-link"
								:to="{ name: 'mon_cotizacion', params: { pTipo: 1 } }"
							>
								<i class="fas fa-file-invoice-dollar"></i>
								Cotizaciones
							</router-link>
						</li>

						<li v-if="IsCliente" class="nav-item laterar-r">
							<router-link
								class="nav-link"
								:to="{ name: 'mon_reporte', params: { pTipo: 2 } }"
							>
								<i class="fas fa-paste"></i>
								Reportes
							</router-link>
						</li>

						<li v-if="IsCliente" class="nav-item laterar-r">
							<router-link class="nav-link" :to="{ name: 'mon_calendario' }">
								<i class="fas fa-calendar-day"></i>
								Calendario
							</router-link>
						</li>

						<li v-if="IsCliente" class="nav-item">
							<router-link class="nav-link" :to="{ name: 'mon_solicitudes' }">
								<!-- <i class="fas fa-file-invoice"></i>
              <i class="fas fa-file-alt"></i> -->
								<i class="fas fa-file-signature"></i>
								Solicitudes
							</router-link>
						</li>

						<!---MENU ROOT--->
						<li v-if="IsRoot" class="nav-item">
							<router-link class="nav-link" :to="{ name: 'MenusRoot' }">
								<i class="fas fa-cogs"></i>
								Admin
							</router-link>
						</li>

						<!-- <li v-if="IsRoot" class="nav-item">
            <router-link class="nav-link" :to="{ name: 'MenusRoot'}" >
              <i class="fas fa-cogs"></i>
              Admon
            </router-link>
          </li> -->

						<!-- <li class="nav-item">
            <a class="nav-link active" href="despacho.html">
              <i class="fas fa-shipping-fast"></i>
              Despacho
            </a>
          </li> -->

						<!-- <li class="nav-item">
            <a class="nav-link" href="monitoreo.html">
              <i class="fas fa-search-location"></i>
              Monitoreo
            </a>
          </li> -->
						<!-- <li class="nav-item">
            <a class="nav-link disabled" href="#">
              <i class="fas fa-scanner-keyboard"></i>
              Scanning
            </a>
          </li> -->
						<!-- <li class="nav-item laterar-r">
            <a class="nav-link" href="sub_menu_spend_plan.html">
              <i class="fal fa-analytics"></i>
              Spend plan
            </a>
          </li> -->

						<!-- <li class="nav-item">
            <a class="nav-link disabled" href="#">
              <i class="fas fa-chart-line"></i>
              Finanzas
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="submenu.html">
              <i class="fas fa-cogs"></i>
              Cuentas
            </a>
          </li> -->
						<!-- <li class="nav-item">
            <a class="nav-link" href="cajachica.html">
              <i class="far fa-usd-square"></i>
              Caja Chica
            </a>
          </li> -->
						<!-- <li class="nav-item laterar-r">
            <a class="nav-link" href="facturacion.html">
              <i class="fas fa-file-certificate"></i>
              Facturación
            </a>
          </li> -->

						<!-- <li class="nav-item">
            <a class="nav-link disabled" href="#">
              <i class="fal fa-chart-network"></i>
              CRM
            </a>
          </li> -->
						<!-- <li class="nav-item">
            <a class="nav-link" href="cotizacion.html">
              <i class="fas fa-file-invoice-dollar"></i>
              Cotización
            </a>
          </li>
          <li class="nav-item laterar-r">
            <a class="nav-link disabled" href="#">
              <i class="fal fa-clipboard-list-check"></i>
              Levantamiento
            </a>
          </li> -->

						<!-- <li class="nav-item laterar-r">
            <a class="nav-link disabled" href="#">
              <i class="fas fa-question"></i>
              Contractor
            </a>
          </li> -->

						<!-- <li class="nav-item dropdown laterar-r">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-chart-pie"></i>
              Dashboard
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow">
              <a class="dropdown-item" href="#">Despacho</a>
              <a class="dropdown-item" href="#">Finanzas</a>
              <a class="dropdown-item" href="#">CRM</a>
              <a class="dropdown-item" href="#">PM</a>
            </div>
          </li> -->

						<!-- <li class="nav-item laterar-r">
            <a class="nav-link" href="submenu.html">
              <i class="fas fa-cogs"></i>
              Admon
            </a>
          </li> -->
					</ul>
				</div>
			</div>
		</nav>

		<!---CONTENIDO DEl SISTEMA--->
		<div class="container-fluid">
			<Header></Header>
			<transition name="fade" mode="out-in">
				<router-view :key="$route.fullPath" />
			</transition>
		</div>
		<!--Fin del contenido---->

		<Modal
			:poBtnSave="oBtnSaveperf"
			:NameModal="'ModalPerfil'"
			:size="'none'"
			:Nombre="'Actualización de Perfil'"
		>
			<template slot="Form">
				<Perfil :oBtnSaveperf="oBtnSaveperf"></Perfil>
			</template>
		</Modal>
		<!-- MODAL DE MI CUENTA-->
		<Modal
			:poBtnSave="oBtnSaveEmpr"
			:NameModal="'ModalCuenta'"
			:size="'modal-lg'"
			:Nombre="'Mi Cuenta'"
		>
			<template slot="Form">
				<FormEmpresa :poBtnSave="oBtnSaveEmpr"></FormEmpresa>
			</template>
		</Modal>
	</div>
</template>

<script>
import { timer } from "rxjs";
import $ from "jquery";
import Header from "@/components/template/header.vue";
import Modal from "@/components/Cmodal.vue";
import Perfil from "@/views/catalogos/perfil/Perfil.vue";
import FormEmpresa from "@/views/catalogos/empresas/Form.vue";

import AlertasComponent from "@/components/alertas/AlertasComponent.vue";
export default {
	name: "AdminTemplate",
	props: ["despacho"],
	data() {
		return {
			blockSidebar: false,
			showAlertaComponent: false,
			estausClaseadmin: "nav-link",
			Nombre: "",
			Foto: "",
			IsAdmin: false,
			IsCliente: false,
			IsRoot: false,
			Empresa: "Desprosoft 4.0",
			LogoE: "",
			status: true,
			ListaPaquetes: [],
			esDescarte: true,
			activeSpenPlan: " disabled",
			oBtnSaveperf: {
				//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
				isModal: true,
				disableBtn: false,
				toast: 0,
				nombreModal: "ModalPerfil"
			},
			oBtnSaveEmpr: {
				//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
				isModal: true,
				disableBtn: false,
				toast: 0,
				nombreModal: "ModalCuenta"
			},
			Perfil: "",

			timer: null,
			countNotifi: 0,
			IntervalTime: []
		};
	},
	components: {
		Header,
		Modal,
		Perfil,
		FormEmpresa,
		AlertasComponent
	},

	methods: {
		validateclas(Id) {
			if (Id == 25 || Id == 17 || Id == 15 || Id == 23 || Id == 19) {
				return "nav-item laterar-r";
			} else {
				return "nav-item";
			}
		},

		ValidarVistas() {
			if (this.IsCliente) {
				return "collapse navbar-collapse justify-content-center";
			}

			if (this.IsAdmin) {
				return "collapse navbar-collapse justify-content-end";
			}

			if (this.IsRoot) {
				return "collapse navbar-collapse justify-content-center";
			}

			if (
				this.IsCliente == false &&
				this.IsAdmin == false &&
				this.IsRoot == false
			) {
				return "collapse navbar-collapse justify-content-end";
			}
		},

		set_interval_serv() {
			//console.log("PAsa");
			this.IntervalTime = setInterval(
				function() {
					this.listarAlertas("");
				}.bind(this),
				45000
			);
		},

		CerrarSession() {
			this.$store.dispatch("logout");
			this.$router.push({ name: "Login" });
		},

		obtenerdatos() {
			var datos = JSON.parse(sessionStorage.getItem("user"));
			this.Perfil = datos.Perfil;
			//console.log(datos);
			var ruta = sessionStorage.getItem("ruta");
			var rutaE = sessionStorage.getItem("rutaE");
			var empresa = JSON.parse(sessionStorage.getItem("empresa"));
			this.Foto = ruta + datos.Foto2;
			this.Nombre = datos.Nombre + " " + datos.Apellido;
			this.Empresa = empresa.Nombre;
			if (empresa.Logo != undefined) {
				this.LogoE = rutaE + empresa.Logo;
			} else {
				this.status = false;
			}

			if (datos.IdCliente > 0) {
				this.IsCliente = true;
				if (this.despacho != undefined) {
					this.$router.push({ name: "mon_sucursal", params: {} });
				}
			} else if (datos.IdCliente <= 0 && datos.IdEmpresa > 0) {
				this.IsAdmin = true;
				this.PaquetesSucursal(datos.IdSucursal);
				//mostramos despacho
				if (this.despacho != undefined) {
					this.$router.push({ name: "despacho", params: {} });
				}
			} else if (
				datos.IdCliente == 0 &&
				datos.IdEmpresa == 0 &&
				datos.IdSucursal == 0
			) {
				this.IsRoot = true;
				if (this.despacho != undefined) {
					this.$router.push({ name: "MenusRoot" });
				}
			}
		},

		get_to_MonxCot(Tipo) {
			this.$router.push({ name: "mon_cotizacion", params: { pTipo: Tipo } });
		},

		OpenPerfil() {
			var datos = JSON.parse(sessionStorage.getItem("user"));
			this.bus.$emit("EmitPerfil", datos.IdUsuario);
		},

		ChangeFoto(objeto) {
			var ruta = sessionStorage.getItem("ruta");
			this.Foto = ruta + objeto.Foto;
			this.Nombre = objeto.Nombre;
		},

		PaquetesSucursal(ID) {
			//console.log(IdPerfil);

			this.$http
				.get("paquetexsucursal/get", {
					params: { IdSucursal: ID }
				})
				.then(res => {
					this.ListaPaquetes = res.data.data.paquetexsucursal;

					if (
						this.Perfil == "Admin" ||
						this.Perfil == "ROOT" ||
						this.Perfil == "ADMIN"
					) {
						this.estausClaseadmin = "nav-link";
					} else {
						let findedMes = res.data.data.paquetexsucursal.find(
							item => item.IdPaquete == 27
						);

						if (findedMes.Estatus) {
							this.estausClaseadmin = "nav-link";
						} else {
							this.estausClaseadmin = "nav-link disabled";
						}
					}
				});
		},

		ChangeClase(index) {
			//console.log("clases");
			for (var i = 0; i < this.ListaPaquetes.length; i++) {
				if (this.ListaPaquetes[i].Estatus) {
					this.ListaPaquetes[i].Clase = "nav-link";
				}
			}

			if (this.ListaPaquetes[index].Estatus) {
				this.ListaPaquetes[index].Clase = "nav-link active";
			}
		},

		VerCuenta() {
			this.bus.$emit("NewCuenta");
		},

		verAlertas() {
			this.showAlertaComponent = !this.showAlertaComponent;
		},

		crearTimerAlertas() {
			const source = timer(1000, 5000);

			this.timer = source.subscribe(val => {
				this.listarAlertas();
				this.bus.$emit("verAlertas");
			});
		},

		listarAlertas() {
			this.loading = true;

			this.$http
				.get("despacho/notificationchat", {
					params: {
						pag: this.currentPage
					}
				})
				.then(res => {
					this.countNotifi = res.data.Total;

					this.loading = false;
				})
				.catch(err => {
					this.loading = false;
				});
		}
	},
	created() {
		this.bus.$off("ChangePerfil");
		this.bus.$on("ChangePerfil", objeto => {
			this.ChangeFoto(objeto);
		});

		this.bus.$off("countNotify");
		this.bus.$on("countNotify", () => {
			this.listarAlertas();
		});

		this.bus.$on("verAlertas", () => {
			this.verAlertas();
		});
	},
	mounted() {
		this.set_interval_serv();
		this.obtenerdatos();
		this.listarAlertas();
	}
};
</script>

<style>
.vl {
	border-left: 6px solid green;
	height: 500px;
	position: absolute;
	left: 50%;
	margin-left: -3px;
	top: 0;
}
</style>
