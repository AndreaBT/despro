<template>
	<div class="col-md-12 col-lg-12 mb-2">
		<b-overlay :show="isOverlay" rounded="sm" spinner-variant="primary" style="h-100">
			<div class="card card-grafica">
				<div class="card-body">
					<div class="filtro-grafic" id="grafica_003" v-if="isVisible">
						<div class="row justify-content-start">
							<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<h6 class="title-grafic side-l">Proceso De Ventas</h6>
								<hr>
								<button type="button" class="btn close abs_01" @click="Ocultar()">
									<i class="fal fa-times"></i>
								</button>
							</div>

							<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<form class="form-inline">
									<label class="mr-2">Año</label>
									<select
										@change="Lista"
										v-model="Grafica1.Anio"
										class="form-control form-control-sm mr-2"
									>
										<option
											v-for="(item, index) in ListaAnios"
											:key="index"
											:value="item"
											>{{ item }}</option
										>
									</select>

									<select
										@change="listartipo(Grafica1.IdVendedor)"
										v-model="Grafica1.IdVendedor"
										class="form-control form-control-sm mr-2"
									>
										<option>Selec. Vendedor</option>
										<option
											v-for="(item, index) in Listavendedores"
											:key="index"
											:value="item.IdUsuario"
											>{{ item.NombreTrabajador }}</option
										>
									</select>
								
									<select
										@change="Lista()"
										class="form-control form-control-sm mr-2"
										v-model="Grafica1.IdConfigS"
									>
										<option value="1">Mantenimiento</option>
										<option value="2">Servicio</option>
										<option value="3">Proyecto</option>
									</select>
									<!---	<select class="form-control form-control-sm mr-2">
										<option></option>
									</select>-->
									
								</form>
								
							</div>
							
						</div>
					</div>

					<!--Filtro-->

					<div class="row">
						<div class="col-10 col-sm-10 col-md-8 col-lg-8">
							<h6 class="title-grafic side-l">Proceso De Ventas</h6>
						</div>
						<div class="col-2 col-sm-2 col-md-4 col-lg-4 text-right">
							<button
								type="button"
								class="btn-fil-002"
								title="Filtros"
								@click="mostrarfiltro()"
							>
								<i class="fas fa-filter"></i>
							</button>
						</div>
						<div class="col-12 col-sm-12 col-md-12 col-lg-12">
							<hr />
						</div>
					</div>

					<!--Titulo-->
					<div class="form-row">
						<div class="col-md-12 col-lg-4 col-xl 3">
							<div id="apx-05">
								<apexchart
									height="250"
									:options="options1"
									:series="seriesllamadas"
								></apexchart>
							</div>
						</div>
						<div class="col-md-12 col-lg-4 col-xl 3">
							<div id="apx-04">
								<apexchart
									
									height="250"
									:options="options2"
									:series="seriesReunion"
								></apexchart>
							</div>
						</div>
						<div class="col-md-12 col-lg-4 col-xl 3">
							<div id="apx-05">
								<apexchart
									
									height="250"
									:options="options3"
									:series="seriesProp"
								></apexchart>
							</div>
						</div>
						<div class="col-md-12 col-lg-4 col-xl 3">
							<div id="apx-06">
								<apexchart
									
									height="250"
									:options="options4"
									:series="series"
								></apexchart>
							</div>
						</div>
					</div>
				</div>
			</div>
		</b-overlay>
	</div>
</template>

<script>
export default {
	name: "app",
	data() {
		return {
			showGrahp: false,
            isOverlay: true,

			options1: {
				chart: {
					type: "area",
					height: 250,
					toolbar: {
						show: false
					},
				},
				stroke: {
					curve: "smooth"
				},
				title: {
					text: "No. Llamadas",
					style: {
						fontSize:  '16px',
						fontWeight:  700,
						fontFamily:  'Barlow, sans-serif',
						color:  '#0F3F87'
					},
				},
				dataLabels: {
					enabled: false
				},
				xaxis: {
					type: 'category',
					categories: [
						"Ene",
						"Feb",
						"Mar",
						"Abr",
						"May",
						"Jun",
						"Jul",
						"Ago",
						"Sep",
						"Oct",
						"Nov",
						"Dic"
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
			
				yaxis: [
					{	
						/*title: {
							text: 'Cantidades',
							style: {
								colors: [],
								fontSize: '12px',
								fontFamily: 'Barlow, sans-serif',
								fontWeight: 300,
								cssClass: 'apexcharts-xaxis-label',
							}
						},*/
						labels: {
							formatter: value => {
								return value.toFixed(0);
							},
							style: {
								colors: [],
								fontSize: "13px",
								fontFamily: "Barlow, sans-serif",
								fontWeight: 500,
								cssClass: "apexcharts-xaxis-label"
							}
						}
					}
				],
				markers: {
					size: 1,
				},
				colors:['#0F3F87', '#FF640A'],
				fill: {
					opacity: 0.9
				},
				
				tooltip: {
					enabled: true,
					enabledOnSeries: undefined,
					onDatasetHover: {
						highlightDataSeries: false,
					},
					x: {
						show: true,
						format: 'dd MMM',
						formatter: undefined,
					},
					y: {
						formatter: undefined,
						title: {
							formatter: (seriesName) => seriesName,
						},
					}
				}
			},
			seriesllamadas: [],

			options2: {
				chart: {
					height: 250,
					type: "area",
					toolbar: {
						show: false
					},
				},
				stroke: {
					curve: "smooth"
				},

				title: {
					text: "No. Reuniones",
					style: {
						fontSize:  '16px',
						fontWeight:  700,
						fontFamily:  'Barlow, sans-serif',
						color:  '#0F3F87'
					},
				},
				dataLabels: {
					enabled: false,
					enabledOnSeries: [1]
				},
				markers: {
					size: 1,
				},
				xaxis: {
					type: 'category',
					categories: [
						"Ene",
						"Feb",
						"Mar",
						"Abr",
						"May",
						"Jun",
						"Jul",
						"Ago",
						"Sep",
						"Oct",
						"Nov",
						"Dic"
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

				yaxis: [
					{	
						/*title: {
							text: 'Cantidades',
							style: {
								colors: [],
								fontSize: '12px',
								fontFamily: 'Barlow, sans-serif',
								fontWeight: 300,
								cssClass: 'apexcharts-xaxis-label',
							}
						},*/
						labels: {
							formatter: value => {
								return value.toFixed(0);
							},
						}
					}
				],
				
				colors:['#0F3F87', '#FF640A'],
				fill: {
					opacity: 0.9
				},
				
				
				tooltip: {
					x: {
						format: "dd/MM/yy HH:mm"
					}
				}
			},
			seriesReunion: [],

			options3: {
				chart: {
					type: "area",
					height: 250,
					toolbar: {
						show: false
					},
				},
				stroke: {
					curve: "smooth"
				},
				title: {
					text: '$ Propuesta Anual',
					style: {
						fontSize:  '16px',
						fontWeight:  700,
						fontFamily:  'Barlow, sans-serif',
						color:  '#0F3F87'
					},
				},
				dataLabels: {
					enabled: false,
					enabledOnSeries: [1]
				},
				markers: {
					size: 1,
				},
				xaxis: {
					type: 'category',
					categories: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
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
				yaxis: [
					{	
						/*title: {
							text: 'Montos',
							style: {
								colors: [],
								fontSize: '12px',
								fontFamily: 'Barlow, sans-serif',
								fontWeight: 300,
								cssClass: 'apexcharts-xaxis-label',
							}
						},*/
						labels: {
							formatter: value => {
								return "$" + this.numberto(parseFloat(value));
							},
						}
					}
				],
				
				colors: ["rgba(15, 63, 135, 0.9)", "rgba(255, 100, 10, 0.9)"],
				
				colors:['#0F3F87', '#FF640A'],
					fill: {
						opacity: 0.9
				},
			},
			seriesProp: [],

			options4: {
				chart: {
					type: "area",
					height: 250,
					toolbar: {
						show: false
					},
				},
				stroke: {
					curve: "smooth"
				},
				title: {
					text: 'No. de Cierres',
					style: {
						fontSize:  '16px',
						fontWeight:  700,
						fontFamily:  'Barlow, sans-serif',
						color:  '#0F3F87'
					},
				},
				dataLabels: {
					enabled: false,
					enabledOnSeries: [1]
				},
				markers: {
					size: 1
				},
				xaxis: {
					type: 'category',
					categories: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
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
				yaxis: [
					{
						/*title: {
							text: 'Cantidades',
							style: {
								colors: [],
								fontSize: '12px',
								fontFamily: 'Barlow, sans-serif',
								fontWeight: 300,
								cssClass: 'apexcharts-xaxis-label',
							}
						},*/
						labels: {
							formatter: value => {
								return value.toFixed(0);
							},
						}
					}
				],
				
				colors:['#0F3F87', '#FF640A'],
				fill: {
					opacity: 0.9
				}
				
				
			
			},
			series: [],


			type: "scrollbar2d",
			renderAt: "grafica05",
			width: "100%",
			height: "300",
			dataFormat: "json",
			dataSource: "",
			ListaAnios: [],
			ListaMeses: [],
			Listatipoproceso: [],
			Listavendedores: [],
			Listatipos: [],
			Grafica1: {
				Anio: "2019",
				Mes: "1",
				IdTipoProceso: "",
				IdVendedor: "",
				IdConfigS: ""
			},
			urlApi: "crmgraficacontador/get",
			urlApitipo: "crmtipoproceso/list",
			urlApivendedor: "trabajador/ListTrabRolQuery",
			urlApivendedorNuevo:"vendedores/get",
			isVisible: false
		};
	},
	created() {
		this.bus.$off("crmOcultar3");
		this.bus.$on("crmOcultar3", data => {
			this.Ocultar(data);
		});

		this.Anios();
	},
	methods: {
		mostrarfiltro() {
			this.isVisible = true;
		},

		Ocultar() {
			this.isVisible = false;
		},
		async Anios() {
			await this.$http.get("funciones/getanios").then(res => {
				this.ListaAnios = res.data.ListaAnios;
				this.Grafica1.Anio = res.data.AnioActual;
				this.ListaMeses = res.data.ListaMeses;
				this.Grafica1.Mes = res.data.MesActual;
				this.Vendedores();
			});
		},
		/*async Vendedores() {
			await this.$http
				.get(this.urlApivendedor, {
					params: {
						Nombre: "",
						RegEstatus: "A",
						Entrada: 200,
						pag: 1,
						Rol: JSON.stringify(["Vendedor", "Gerente de ventas"])
					}
				})
				.then(res => {
					this.Listavendedores = res.data.data.lista;
					this.Grafica1.IdVendedor = res.data.data.lista[0].IdTrabajador;
					this.listartipo(this.Grafica1.IdVendedor);
				});
		},*/

		async Vendedores() {
			await this.$http
				.get(this.urlApivendedorNuevo, {
					params: {
						
					}
				})
				.then(res => {
					//Rol=['Vendedor','Gerente de ventas']"
					this.Listavendedores = res.data.data.Vendedores;
					this.Grafica1.IdVendedor = res.data.data.Vendedores[0].IdUsuario;
                    this.listartipo(this.Grafica1.IdVendedor);
                       
				});
		},

		listartipo(Id) {
			if (Id > 0) {
				this.Listatipos = [];
				this.$http
					.get("crmprocesovendedor/listasig", {
						params: { IdTrabajador: Id }
					})
					.then(res => {
						this.Listatipos = res.data.data.asignados;
						//this.Grafica1.IdTipoProceso =res.data.data.asignados[0].IdTipoProceso;
						//this.Grafica1.IdConfigS = res.data.data.asignados[0].IdConfigS;
						this.Grafica1.IdConfigS = 1;
						//console.log(this.Grafica1.IdTipoProceso);
						this.Lista();
					});
			}
		},

		async Lista() {
			this.isOverlay = true;
			await this.$http
				.get(this.urlApi, {
					params: {
						IdVendedor: this.Grafica1.IdVendedor,
						//IdTipoProceso: this.Grafica1.IdTipoProceso,
						Anio: this.Grafica1.Anio,
						IdConfigS: this.Grafica1.IdConfigS
					}
				})
				.then(res => {
					const total = res.data.data.Real;
					const total2 = res.data.data.Plan;
					const propAnual = res.data.data.Actual;
					const planAnual = res.data.data.PropuestaAnualDinero;
					const planAualProp = res.data.data.PropActualPlanAnual;
					const reunionPlan = res.data.data.Reuniones;
					const reunionActual = res.data.data.ActualReuniones;
					const Llamadas = res.data.data.llamadasPlan;
					const llamadasActual = res.data.data.llamadasActual;
					this.series = [
						{
							name: "Plan",
							type: "area",
							data: total2
						},
						{
							name: "Actual",
							type: "area",
							data: total
						}
					];
					this.seriesProp = [
						{
							name: "Plan",
							type: "area",
							data: planAnual
						},
						{
							name: "Actual",
							type: "area",
							data: planAualProp
						}
					];
					this.seriesReunion = [
						{
							name: "Plan",
							type: "area",
							data: reunionPlan
						},
						{
							name: "Actual",
							type: "area",
							data: reunionActual
						}
					];
					this.seriesllamadas = [
						{
							name: "Plan",
							type: "area",
							data: Llamadas
						},
						{
							name: "Actual",
							type: "area",
							data: llamadasActual
						}
					];
					this.verapex4 = true;
					this.isOverlay = false;
				}).catch((e) => {
					this.isOverlay = false;
				});
		},
		Ocultar(data) {
			this.isVisible = data;
		},
		numberto(num){
			//value = value.toFixed(0);
			let fixed = 0;
			if (num === null) { return null; } // terminate early
			if (num === 0) { return '0'; } // terminate early
			fixed = (!fixed || fixed < 0) ? 0 : fixed; // number of decimal places to show
			var b = (num).toPrecision(2).split("e"), // get power
				k = b.length === 1 ? 0 : Math.floor(Math.min(b[1].slice(1), 14) / 3), // floor at decimals, ceiling at trillions
				c = k < 1 ? num.toFixed(0 + fixed) : (num / Math.pow(10, k * 3) ).toFixed(1 + fixed), // divide by power
				d = c < 0 ? c : Math.abs(c), // enforce -0 is 0
				e = d + ['', ' K', ' M', ' B', ' T'][k]; // append power
			return e;
		}
	}
};
</script>
