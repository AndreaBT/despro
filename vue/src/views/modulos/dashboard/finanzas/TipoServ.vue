<template>
	<div class="col-md-12 col-lg-6 mb-3">
        <b-overlay :show="isOverlay" rounded="sm" spinner-variant="primary" class="h-100">
            <div class="row">
                <div class="col-12">
                    <div class="card card-grafica h-100">
                        <div class="card-body ">
							<div class="filtro-grafic" id="grafica_008" v-if="isVisible">
								<div class="row justify-content-start">
									<div class="col-12 col-md-12 col-lg-12 col-xl-12">
										<form class="form-inline">
											<label class="mr-2">Año</label>
											<select
												@change="get_dataSource"
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
										<button type="button" class="btn close abs_01" @click="Ocultar()"><i class="fal fa-times"></i></button>
									</div>
								</div>
							</div>
                            <div class="row">
                                <div class="col-10 col-sm-10 col-md-8 col-lg-8">
                                    <h6 class="title-grafic side-l">Operation Profit (EBIT)</h6>
                                </div>
								<div class="col-2 col-sm-2 col-md-4 col-lg-4 text-right">
									<button type="button" class="btn-fil-002" title="Filtros"
										@click="mostrarfiltro()"><i
											class="fas fa-filter"></i></button>
								</div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <hr />
                                </div>
                            </div>
                            <!--Titulo-->
                            <div class="row">
                                <div class="col-12">
                                    <div id="apx-06">
                                        <apexchart
                                            width="100%"
                                            height="200%"
                                            :options="options"
                                            :series="series"
                                        ></apexchart>
                                    </div>
                                </div>
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
	name: "OperationProfit",
	data() {
		return {
			series: [],
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

			type: "mscolumnline3d",
			renderAt: "linea1",
			width: "100%",
			height: "300",
			dataFormat: "json",
			dataSource: "",
			ListaAnios: [],
			Grafica1: {
				Anio: "2022",
				Mes: "",
			},
			isVisible: false,
            isOverlay: true
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
				this.get_dataSource();
			});
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
		},

		async get_dataSource() {
            this.isOverlay = true;
			await this.$http.get("finanzasgraf/graficgrossprof", {
                params:{Anio: this.Grafica1.Anio }
            }
			).then(res => {
                this.isOverlay = false;
				const info = res.data.data;
				// this.series.push({
				// 	name: "Plan",
				// 	data: info.Plan
				// });


				// this.series.push({
				// 	name: "Actual",
				// 	data: info.Actual
				// });

				this.series = [
					
					{
						name: "Actual",
						type: "column",
						data: info.Actual
					},
					{
						name: "Plan",
						type: "line",
						data: info.Plan
					}
				];
			}).catch((e) => {
                this.isOverlay = false;
            });
		},

		// Ocultar4(data) {
		// 	this.isVisible = data;
		// },

		Ocultar8(data){
        this.isVisible = data;
    },
	},

	

	created() {
		this.bus.$off('Ocultar8');
		this.bus.$on('Ocultar8', (data) => {

			this.Ocultar8(data);
		});
		this.get_dataSource();
		this.Anios();
	}
};
</script>
