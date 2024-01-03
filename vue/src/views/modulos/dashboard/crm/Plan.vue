<template>
	<div class="col-md-12 col-lg-6 mb-2">
		<div class="row">
			<div class="col-12">
				<b-overlay
					:show="isOverlay"
					rounded="sm"
					spinner-variant="primary"
					style="h-100"
				>
					<div class="card card-grafica crad-alto">
						<div class="card-body">
							<div class="filtro-grafic" id="grafica_001" v-if="isVisible">
								<div class="row justify-content-start">
									<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<h6 class="title-grafic side-l">Venta</h6>
										<hr />
										<button
											type="button"
											class="btn close abs_01"
											@click="Ocultar()"
										>
											<i class="fal fa-times"></i>
										</button>
									</div>

									<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<form class="form-inline">
											<label class="mr-2">Año</label>
											<select
												class="form-control form-control-sm mr-2"
												@change="Lista"
												v-model="Grafica1.Anio"
											>
												<option
													v-for="(item, index) in ListaAnios"
													:key="index"
													:value="item"
												>
													{{ item }}
												</option>
											</select>

											<label class="mr-2">Vendedor</label>
											<select
												@change="Lista()"
												v-model="Grafica1.IdVendedor"
												class="form-control form-control-sm mr-2"
											>
												<option
													v-for="(item, index) in Listavendedores"
													:key="index"
													:value="item.IdUsuario"
												>
													{{ item.NombreTrabajador }}
												</option>
											</select>

											<label class="mr-2">Proceso</label>
											<select
												@change="Lista()"
												class="form-control form-control-sm"
												v-model="Grafica1.IdConfigS"
											>
												<!-- <option :value="''">seleccionar un tipo</option>-->
												<option value="1">Mantenimiento</option>
												<option value="2">Servicio</option>
												<option value="3">Proyecto</option>
											</select>
										</form>
									</div>
								</div>
							</div>

							<!--Filtro-->
							<div class="row">
								<div class="col-10 col-sm-10 col-md-8 col-lg-8">
									<h6 class="title-grafic side-l">Venta</h6>
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

							<div id="apx-01" class="apx-crm-01" v-if="isCharged">
								<apexchart
									width="100%"
									height="300"
									:options="optionsChart"
									:series="series"
								></apexchart>
							</div>
						</div>
					</div>
				</b-overlay>
			</div>
		</div>
	</div>
	<!--Plan v/s Actual-->
</template>

<script>
export default {
	name: "app",
	data() {
		return {
			Grafica1: {
				Anio: "2020",
				Mes: "1",
				IdVendedor: "",
				IdConfigS: "1"
			},
			ListaAnios: [],
			ListaProceso: [],
			Listavendedores: [],
			urlApi: "crmgraficas/planvsact",
			urlApivendedor: "trabajador/ListTrabRolQuery",
			urlApivendedorNuevo: "vendedores/get",
			isVisible: false,
			isOverlay: true,
			isCharged: false,
			optionsChart: {
				chart: {
					height: 300,
					type: "line",
					toolbar: {
						show: false
					}
				},
				stroke: {
					width: [0, 4]
				},
				title: {
					text: ""
				},
				dataLabels: {
					enabled: false
				},
				markers: {
					size: 5
				},
				xaxis: {
					type: "category",
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
							fontSize: "13px",
							fontFamily: "Barlow, sans-serif",
							fontWeight: 400,
							cssClass: "apexcharts-xaxis-label"
						}
					}
				},
				yaxis: [
					{
						title: {
							style: {
								colors: [],
								fontSize: "13px",
								fontFamily: "Barlow, sans-serif",
								fontWeight: 300,
								cssClass: "apexcharts-xaxis-label"
							}
						},
						labels: {
							formatter: value => {
								return "$" + this.numberto(value);
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
				colors: ["#FF640A", "#0F3F87"],
				fill: {
					opacity: 0.9
				}
			},

			series: [
				{
					name: "Actual",
					type: "column",
					data: []
				},
				{
					name: "Plan",
					type: "line",
					data: []
				}
			]
		};
	},
	created() {
		this.bus.$off("crmOcultar1");
		this.bus.$on("crmOcultar1", data => {
			this.Ocultar(data);
		});
		this.Anios();
		this.Lista();
	},
	methods: {
		mostrarfiltro() {
			this.isVisible = true;
		},

		async Anios() {
			await this.$http.get("funciones/getanios").then(res => {
				this.ListaAnios = res.data.ListaAnios;
				this.Grafica1.Anio = res.data.AnioActual;
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
					//Rol=['Vendedor','Gerente de ventas']"
					this.Listavendedores = res.data.data.lista;
					this.Grafica1.IdVendedor = res.data.data.lista[0].IdTrabajador;
					this.Lista();
					//this.listartipo(this.Grafica1.IdVendedor);

				});
		},*/

		async Vendedores() {
			await this.$http
				.get(this.urlApivendedorNuevo, {
					params: {}
				})
				.then(res => {
					//Rol=['Vendedor','Gerente de ventas']"
					this.Listavendedores = res.data.data.Vendedores;
					this.Grafica1.IdVendedor = res.data.data.Vendedores[0].IdUsuario;
					this.Lista();
				});
		},

		/*listartipo(Id) {
			if (Id > 0) {
				this.ListaProceso = [];
				this.$http
					.get("crmprocesovendedor/listasig", {
						params: { IdTrabajador: Id }
					})
					.then(res => {
						console.log(res.data);
						this.ListaProceso = res.data.data.asignados;
						this.Grafica1.IdConfigS = res.data.data.asignados[0].IdConfigS;
						this.Lista();
					});
			}
		},*/

		async Lista() {
			if (parseInt(this.Grafica1.IdVendedor) > 0) {
				this.isOverlay = true;
				this.isCharged = false;
				await this.$http
					.get(this.urlApi, {
						params: {
							IdVendedor: this.Grafica1.IdVendedor,
							Anio: this.Grafica1.Anio,
							IdConfigS: this.Grafica1.IdConfigS
						}
					})
					.then(res => {
						this.series[0].data = res.data.data.Real;
						this.series[1].data = res.data.data.Plan;
						this.isOverlay = false;
						this.isCharged = true;
					})
					.catch(e => {
						this.isOverlay = false;
						this.isCharged = false;
					});
			}
		},
		Ocultar(data) {
			this.isVisible = data;
		},

		numberto(num) {
			//value = value.toFixed(0);
			let fixed = 0;
			if (num === null) {
				return null;
			} // terminate early
			if (num === 0) {
				return "0";
			} // terminate early
			fixed = !fixed || fixed < 0 ? 0 : fixed; // number of decimal places to show
			var b = num.toPrecision(2).split("e"), // get power
				k = b.length === 1 ? 0 : Math.floor(Math.min(b[1].slice(1), 14) / 3), // floor at decimals, ceiling at trillions
				c =
					k < 1
						? num.toFixed(0 + fixed)
						: (num / Math.pow(10, k * 3)).toFixed(1 + fixed), // divide by power
				d = c < 0 ? c : Math.abs(c), // enforce -0 is 0
				e = d + ["", " K", " M", " B", " T"][k]; // append power
			return e;
		}
	}
};
</script>
