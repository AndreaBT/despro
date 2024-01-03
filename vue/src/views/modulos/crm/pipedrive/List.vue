<template>
	<div>
		<section class="container-fluid mt-2">
			<Menu :pSitio="NombreSeccion">
                <template slot="BtnInicio"></template>
            </Menu>
			<div class="row">
				<div class="col-md-12 col-lg-12 col-xl-12 mt-2">
					<div class="form-group form-row">
						<div class="col-md-3 col-lg-2">
							<label class="mr-2">Sucursal</label>
							<select @change="Lista()"  class="form-control form-control-sm" >
								<option v-for="(item, index) in Sucursales" :key="index" :value="item.IdSucursal" >
                                    {{ item.Nombre }}
                                </option >
								
							</select>
						</div>
						<div class="col-md-3 col-lg-2">
							<label class="mr-2">Vendedor</label>
							<select @change="Lista()" v-model="filtros.IdTrabajador" class="form-control form-control-sm" >
								<option v-for="(item, index) in Listavendedor" :key="index" :value="item.IdUsuario" >
                                    {{ item.NombreTrabajador }}
                                </option >
							</select>
						</div>

						<div class="col-md-3 col-lg-2">
							<label class="mr-2">Proceso</label>
							<select @change="Lista()" v-model="filtros.IdTipoProceso" class="form-control form-control-sm" >
								<option value="0">Seleccione un Proceso</option>
								<option v-for="(item, index) in ListaAsignados" :key="index" :value="item.IdTipoProceso" >
                                    {{ item.Nombre }}
                                    </option>
                            </select>
						</div>
						<div class="col-md-4 col-lg-2">
							<label class="mr-2">Oportunidades</label>
							<select @change="Lista()" v-model="filtros.IdOportunidad" class="form-control form-control-sm" >
								<option value="">Seleccione una Oportunidad</option>
								<option v-for="(item, index) in ListaOportunidades" :key="index" :value="item.IdOportunidad" >
                                    {{ item.Nombre }}
                                </option>
							</select>
						</div>
						<div class="col-md-2 col-lg-1">
							<label>Año</label>
							<select @change="Lista()" v-model="filtros.Anio" class="form-control form-control-sm" >
								<option value="">Seleccionar</option>
								<option v-for="(item, index) in ListaAnios" :key="index" :value="item">
                                    {{ item }}
                                </option>
							</select>
						</div>

						<div class="col-md-2 col-lg-1">
							  <router-link   :to="{name:'pipedrive2022'}" class="btn btn-04 ban mr-2">pipedrive maqueta</router-link >
						</div>
					</div>
				</div>
				<div class="col-md-12 col-lg-8 col-xl-8 mt-2">
					<div id="accordion" class="accordion">
						<div v-if="Listatipoproceso.length > 0">
							<div class="card card-acordion" v-for="(item, index) in Listatipoproceso" :key="index" >
								<div :class="{ 'card-header header-proceso': index == 0, 'card-header header-proceso collapsed': index > 0 }" data-toggle="collapse" :href="'#collapseOne' + index" :style=" 'color: ' + item.Color + '; border-bottom: 1px solid ' + item.Color + ';' " >
									<a class="card-title-2">{{ item.Nombre }}</a>
								</div>

								<div :id="'collapseOne' + index" :class="{ 'card-body collapse show': index == 0, 'collapse card-body': index > 0 }" data-parent="#accordion" >
									<div class=" table-responsive">
										<table class="table-01" v-if="item.historial.length > 0">
											<thead>
												<tr>
													<th>Fecha</th>
													<th>Cliente</th>
													<th>Oportunidad</th>
													<th>Comentario</th>
													<th v-if="item.historial[0].MontoP > 0">Monto</th>
													<th v-if="item.historial[0].MontoPropuesta > 0">
														Monto Propuesta
													</th>
												</tr>
											</thead>
											<tbody>
												<tr v-for="(item2, index2) in item.historial" :key="index2" >
													<td>{{ item2.Fecha }}</td>
													<td>{{ item2.NombreCliente }}</td>
													<td>{{ item2.Oportunidad.substr(0, 30) }}</td>
													<td>{{ item2.Comentarios.substr(0, 30) }}</td>
													<td v-if="item2.MontoP > 0">{{ item2.MontoP }}</td>
													<td v-if="item2.MontoPropuesta > 0">
														{{ item2.MontoPropuesta }}
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
                        <div class="card card-accordion" v-if="Listatipoproceso.length == 0">
                            <div class="row mt-2">
                                <div class="col-12 text-center">
                                    <h1>Sin Contenido</h1>
                                </div>
                            </div>
                        </div>
					</div>

					<!--<Grafica></Grafica>-->
				</div>

				<div class="col-md-12 col-lg-4 col-xl-4 mt-2">
					<div class="card card-grafica">
						<!--<div class="card-header">
							<h1 class="title-grafic side-l"></h1>
						</div>-->
						<div class="card-body">
							<div class="row">
								<div class="col-10 col-sm-10 col-md-8 col-lg-8">
									<h6 class="title-grafic side-l">Cono de Ventas - {{filtros.Anio}}</h6>
								</div>
								<div class="col-2 col-sm-2 col-md-4 col-lg-4 text-right">

								</div>
								<div class="col-12 col-sm-12 col-md-12 col-lg-12">
									<hr />
								</div>
							</div>

							<div id="chart-container">
								<div id="chart">
									<apexchart
										type="bar"
										height="350"
										:options="chartOptions"
										:series="series"
									></apexchart>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</template>
<script>

import Modal from "@/components/Cmodal.vue";
import Clist from "@/components/Clist.vue";
import Cbtnaccion from "@/components/Cbtnaccion.vue";
import Form from "../tiposprocesos/form.vue";
import Grafica from "../pipedrive/Grafic.vue";
import Menu from "../indexMenu.vue";

export default {
	name: "list",
	components: {
		Modal,
		Clist,
		Cbtnaccion,
		Grafica,
        Menu,
        Form,
	},
	data() {
		return {
			chartOptions: {
				chart: {
					type: "bar",
					height: 350,
					toolbar: {
						show: false
					}
				},
				plotOptions: {
					bar: {
						borderRadius: 4,
						horizontal: true
					}
				},
				dataLabels: {
					enabled: false
				},
				xaxis: {
					type: 'category',
					categories: [
						"Prospectar",
						"Llamada en Frio",
						"Reunión de Ventas",
						"Propuestas",
						"Cierre"
					],
					labels: {
						show: true,
						rotate: -45,
						style: {
							colors: [],
							fontSize: '13px',
							fontFamily: 'Barlow, sans-serif',
							fontWeight: 400,
							cssClass: 'apexcharts-xaxis-label',
						}
					}
				},
				yaxis: [{
					title: {

						style: {
							colors: [],
							fontSize: '13px',
							fontFamily: 'Barlow, sans-serif',
							fontWeight: 300,
							cssClass: 'apexcharts-xaxis-label',
						}
					},
					labels: {
						style: {
							colors: [],
							fontSize: '13px',
							fontFamily: 'Barlow, sans-serif',
							fontWeight: 500,
							cssClass: 'apexcharts-xaxis-label',
						}
					}
				}],
				colors:['#0F3F87']
			},
			series: [],
			//fin gráfica


            NombreSeccion: 'Pipedrive',
			FormName: "TipoUnidadForm", //Por si no es modal y queremos ir a una vista declarada en el router
			EsModal: true, //indica si es modal o no
			size: "none",
			NameList: "Tipos de procesos",
			urlApi: "crmseguimiento/pipedrive",//API DE LA LISTA DEL LADO IZQUIERDO DE LA PANTALLA 
			urlApivendedor: "trabajador/ListTrabRolQuery",
			urlApiVendedorNuevo:"vendedores/get",
			urlApirecovery: "crmprocesovendedor/listasig",
			urlApiPipeDrive: "pipeDrive/get",
			Listatipoproceso: [],
			ListaHeader: [],
			TotalPagina: 2,
			Pag: 0,
			Filtro: {
				Nombre: "",
				Placeholder: "Nombre..",
				TotalItem: 0,
				Pagina: 1
			},
			oBtnSave: {
				//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
				isModal: true,
				disableBtn: false,
				toast: 0
			},
			TipoList: "",

			filtros: {
				IdTrabajador: "",
				IdTipoProceso: 0,
				Anio: "",
				IdOportunidad: "",
				IdSucursal:0,
			},
			Listavendedor: [],
			ListaAsignados: [],
			ListaAnios: [],
			rol: ["Vendedor", "Gerente de ventas"],
			ListaOportunidades: [],
			Sucursales:[],
		};
	},
	methods: {
		ListaSucursales(){
			this.$http
			.get("crmseguimiento/sucursales", {
				params: { }
			})
			.then(res => {
				this.Sucursales = res.data.data.Sucursales;
				this.ListaVendedor();
				this.Lista();
			});
		},
		get_oneprs(Id) {
		if (Id > 0) {
			this.$http.get(this.urlApirecovery, {
				params: { IdTrabajador: Id }
			})
			.then(res => {
				this.ListaAsignados = res.data.data.asignados;
				
			});
		} else {
			this.ListaAsignados = [];
			this.Listatipoproceso = [];
			this.filtros.IdTrabajador = "";
			this.filtros.IdTipoProceso = "";
		}
		},
		// Proceso() {
		// 	this.$http.get(this.urlApi, {
        //         params: {
        //             IdTrabajador: this.filtros.IdTrabajador,
        //             IdTipoProceso: this.filtros.IdTipoProceso,
        //             Anio: this.filtros.Anio,
        //             IdOportunidad: this.filtros.IdOportunidad
        //         }
        //     })
        //     .then(res => {
        //         this.Listatipoproceso = res.data.data.tipoproceso;
        //     });
		// },
		async Lista() {
			await this.$http.get(this.urlApi, {
                params: {
                    IdTrabajador: this.filtros.IdTrabajador,
                    IdTipoProceso: this.filtros.IdTipoProceso,
                    Anio: this.filtros.Anio,
                    IdOportunidad: this.filtros.IdOportunidad
                }
            })
            .then(res => {
                this.Listatipoproceso = res.data.seguimiento;
				this.get_oneprs(this.filtros.IdTrabajador);
                this.pipeDrive();
            });
		},
		/*async ListaVendedor() {
			await this.$http.get(this.urlApivendedor, {
                params: {
                    Nombre: this.Filtro.Nombre,
                    RegEstatus: "A",
                    Entrada: this.Filtro.Entrada,
                    pag: this.Filtro.Pagina,
                    Rol: JSON.stringify(this.rol)
                }
            })
            .then(res => {
                this.Listavendedor = res.data.data.lista;
                this.filtros.IdTrabajador = res.data.data.lista[0].IdTrabajador;
                this.get_oneprs(this.filtros.IdTrabajador);
                //this.Filtro.Entrada=res.data.data.pagination.PageSize;
                //this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
            });
		},*/

		async ListaVendedor() {
			await this.$http.get(this.urlApiVendedorNuevo, {
                params: {
                    
                }
            })
            .then(res => {
                this.Listavendedor = res.data.data.Vendedores;
				
                this.filtros.IdTrabajador = res.data.data.Vendedores[0].IdUsuario;
                // this.get_oneprs(this.filtros.IdTrabajador);
            }).finally(() => {
			});
		},
		async Anios() {
			await this.$http.get("funciones/getanios").then(res => {
				this.ListaAnios = res.data.ListaAnios;
				this.filtros.Anio = res.data.AnioActual;
			});
		},
		ListOportunidad() {
			this.$http
				.get("crmoportunidad/list", {
					params: { Nombre: "", RegEstatus: "A", Entrada: 100, pag: 1 }
				})
				.then(res => {
					this.ListaOportunidades = res.data.data.oportunidades;
				});
		},
		async pipeDrive() {
			await this.$http.get(this.urlApiPipeDrive, {
                params: {
                    IdTrabajador: this.filtros.IdTrabajador,
                    IdTipoProceso: this.filtros.IdTipoProceso,
                    Anio: this.filtros.Anio,
                    IdOportunidad: this.filtros.IdOportunidad
                }
            })
            .then(res => {
                const arrayPipe = res.data.acumulado;

                let datos = [
                    arrayPipe[0],
                    arrayPipe[1],
                    arrayPipe[2],
                    arrayPipe[3],
                    arrayPipe[4]
                ];

                this.series = [
                    {
                        data: datos
                    }
                ];
            });
		},

		//Regresar al calendario
		go_to_procesos(objcliente) {
			this.$router.push({
				name: "crmprocesos",
				params: { ocliente: objcliente, tipolistp: this.TipoList }
			});
		},
	},
	created() {
		this.Anios();
		this.ListOportunidad();
		this.ListaSucursales();
		//Obligatorio pasar el tipolist
		if (this.tipolistp != undefined) {
			sessionStorage.setItem("IdSaved", JSON.stringify(this.tipolistp));
		}

		this.TipoList = JSON.parse(sessionStorage.getItem("IdSaved"));

		this.bus.$off("Delete");
		this.bus.$off("List");
		this.bus.$off("Regresar");
		// this.ListaVendedor();

		this.bus.$on("Delete", Id => {
		});
		this.bus.$on("List", () => {
			this.Lista();
		});
		this.bus.$on("Regresar", () => {
			this.$router.push({ name: "submenucrm" });
		});
	}
};
</script>
