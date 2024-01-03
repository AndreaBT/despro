<template>
	<div class="col-md-12 col-lg-6 mb-2">
		<b-overlay :show="isOverlay" rounded="sm" spinner-variant="primary" class="h-100">
			<div class="row">
				<div class="col-12">
					<div class="card card-grafica h-100">
						<div class="card-body">
							<div class="filtro-grafic" id="grafica_001" v-show="isVisible">
								<div class="row justify-content-start">
									<div class="col-12 col-md-12 col-lg-12 col-xl-12">
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
												>
													{{ item }}
												</option>
											</select>
											<label class="mr-2">T. Servicio</label>

											<select
												@change="Lista"
												v-model="Grafica1.IdConfigS"
												class="form-control form-control-sm"
											>
												<option
													:value="lista.IdConfigS"
													v-for="(lista, key, index) in Listaservicios"
													:key="index"
												>
													{{ lista.Nombre }}
												</option>
											</select>
										</form>
										<button
											type="button"
											class="btn close abs_01"
											@click="Ocultar()"
										>
											<i class="fal fa-times"></i>
										</button>
									</div>
								</div>
							</div>
							<!--Filtro-->
							<div class="row">
								<div class="col-10 col-sm-10 col-md-8 col-lg-8">
									<h6 class="title-grafic side-l">Facturación</h6>
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
							<div id="apx-01">
								<apexchart
									width="100%"
									height="190%"
									:options="options"
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
	name: "Facturacion",
	// props: [
	//     'value'
	// ],
	data() {
		return {
			options: {
				chart: {
					height: 280,
					type: "line",
					toolbar: {
						show: false
					}
				},
				chart: {
					height: 280,
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
					enabled: false,
					enabledOnSeries: [1]
				},
				markers: {
					size: 5
				},

				yaxis: [
					{
						title: {
							text: "",
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
								return value, (value = "$ " + this.numberto(value));
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
				xaxis: {
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
					]
				},
				colors: ["#FF640A", "#0F3F87"],
				fill: {
					opacity: 0.9
				}
			},
			series: [],
			//form: this.value,
			//type: "mscolumnline3d",
			//type: "scrollcombidy2d",
			type: "mscolumnline3d",
			renderAt: "linea1",
			width: "100%",
			height: "300",
			dataFormat: "json",
			dataSource: "",
			//urlApi:'finanzasgraf/get',
			urlApi: "finanzasone/get",
			Grafica1: {
				Anio: "2019",
				Mes: "",
				IdConfigS: ""
			},
			ListaAnios: [],
			Listaservicios: [],
			isVisible: false,
			isOverlay: true,
		};
	},
	created() {
		this.bus.$off("Ocultar");
		this.bus.$on("Ocultar", data => {
			this.Ocultar(data);
		});
		this.ConfigServicio();
		this.Anios();
	},
	methods: {
		mostrarfiltro() {
			this.isVisible = true;
		},

		Ocultar() {
			this.isVisible = false;
		},

		async ConfigServicio() {
			await this.$http
				.get("configservicio/get", {
					params: { pag: 1, Entrada: 100, Facturable: "S" }
				})
				.then(res => {
					this.Listaservicios = res.data.data.configservicio;
					this.Grafica1.IdConfigS = res.data.data.configservicio[0].IdConfigS;
				});
		},

		async Anios() {
			await this.$http.get("funciones/getanios").then(res => {
				this.ListaAnios = res.data.ListaAnios;
				this.Grafica1.Anio = res.data.AnioActual;
				this.Lista();
			});
		},

		async Lista() {
			this.isOverlay = true;
			await this.$http
				.get(this.urlApi, {
					params: {
						IdServicio: this.Grafica1.IdConfigS,
						Anio: this.Grafica1.Anio
					}
				})
				.then(res => {
					//console.log(res.data.dataapexactual);
					this.isOverlay = false;
					this.series = [
						{
							name: "Actual",
							type: "column",
							data: res.data.dataapexactual
						},
						{
							name: "Plan",
							type: "line",
							data: res.data.dataapex
						}
					];
				}).catch((e) => {
					this.isOverlay = false;
				});
		},
		Ocultar(data) {
			this.isVisible = data;
		},

		formatNumber(num, prefix) {
			if (num !== null) {
				num = Math.round(parseFloat(num) * Math.pow(10, 2)) / Math.pow(10, 2);
				prefix = prefix || "";
				num += "";
				let splitStr = num.split(".");
				let splitLeft = splitStr[0];
				let splitRight = splitStr.length > 1 ? "." + splitStr[1] : ".00";
				splitRight = splitRight + "00";
				splitRight = splitRight.substr(0, 3);
				let regx = /(\d+)(\d{3})/;
				while (regx.test(splitLeft)) {
					splitLeft = splitLeft.replace(regx, "$1" + "," + "$2");
				}
				return prefix + splitLeft + splitRight;
			} else {
				return prefix + "0.00";
			}
		},
		numberto(num, fixed) {
			//value = value.toFixed(0);
			fixed = 0;
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
