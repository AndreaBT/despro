<template>
	<div class="col-md-12 col-lg-6 mb-2">
		<b-overlay
			:show="isOverlay"
			rounded="sm"
			spinner-variant="primary"
			class="h-100"
		>
			<div class="row">
				<div class="col-12">
					<div class="card card-grafica h-100">
						<div class="card-body">
							<div class="filtro-grafic" id="grafica_007" v-show="isVisible">
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
									<h6 class="title-grafic side-l">
										Cuentas Por Cobrar y Pagar
									</h6>
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
							<div id="apx-07">
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
	name: "realCuenta",
	// props: [
	//     'value'
	// ],
	data() {
		return {
			urlApi: "finanzasCuentasTotal/get",
			Grafica1: {
				Anio: "2022",
				Mes: "",
				IdConfigS: ""
			},
			ListaAnios: [],
			Listaservicios: [],
			isVisible: false,
			isOverlay: true,
			series: [],
			options: {
				chart: {
					type: "bar",
					height: 350,
					toolbar: {
						show: false
					}
				},
				plotOptions: {
					bar: {
						horizontal: false,
						columnWidth: "55%",
						endingShape: "rounded"
					}
				},
				dataLabels: {
					enabled: false
				},
				stroke: {
					show: true,
					width: 2,
					colors: ["transparent"]
				},
				yaxis: {
					labels: {
						formatter: value => {
							return value, (value = "$ " + this.numberTo(value));
						}
					}
				},
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
				colors: ["rgb(0, 227, 150)", "#FF1700"],
				fill: {
					opacity: 1
				},
				tooltip: {
					y: {
						formatter: value => {
							return value, (value = "$ " + this.numberTo(value));
						}
					}
				}
			}
		};
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
			});
		},

		async Lista() {
			this.isOverlay = true;
			await this.$http
				.get(this.urlApi, {
					params: {
						Anio: this.Grafica1.Anio
					}
				})
				.then(res => {
					this.series = [
						{
							name: "Ctas. Por Pagar",
							data: res.data.data.ctapagar
						},
						{
							name: "Ctas. Por Cobrar",
							data: res.data.data.ctacobrar
						}
					];
					this.isOverlay = false;
					this.isCharged = true;
				})
				.catch(e => {
					this.isOverlay = false;
				});
		},
		Ocultar(data) {
			this.isVisible = data;
		},
		numberTo(num, fixed) {
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
	},
	created() {
		this.bus.$off("Ocultar7");
		this.bus.$on("Ocultar7", data => {
			this.Ocultar7(data);
		});
		this.Lista();
		this.Anios();
	}
};
</script>
