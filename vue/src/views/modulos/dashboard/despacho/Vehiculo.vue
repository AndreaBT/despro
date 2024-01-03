<template>
	<div class="col-md-6 col-lg-6">
		<b-overlay
			:show="isOverlay"
			rounded="sm"
			spinner-variant="primary"
			class="h-100"
		>
			<div class="row">
				<div class="col-12">
					<div class="card card-grafica h-100 mb-3">
						<div class="card-body">
							<div class="filtro-grafic" id="grafica_003" v-if="isVisible">
								<div class="row justify-content-start">
									<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<form class="form-inline">
											<h6 class="title-grafic side-l mr-3">
												Km Vendidos Por Vehículo
											</h6>
											<v-date-picker
												mode="range"
												v-model="rangeDate"
												@input="get_dataSource"
												:input-props="{
													class: 'form-control form-control-sm mr-2 calendar',
													placeholder:
														'Selecciona un rango de fecha para buscar',
													readonly: true
												}"
											/>
											<!--<select
											selected
											@change="get_dataSource"
											v-model="TipoVehiculo"
											class="form-control form-control-sm mr-2"
											>
											<option value="Vendidos">Kilometros Vendidos</option>

											</select>-->
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
							<div class="row">
								<div class="col-10 col-sm-10 col-md-8 col-lg-8">
									<h6 class="title-grafic side-l">Km Vendidos Por Vehículo</h6>
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
							<div id="apx-02">
								<apexchart
									width="100%"
									height="380"
									:options="options"
									:series="options.series"
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
	name: "KilometrosVendidos",
	data() {
		return {
			options: {
				series: [
					{
						data: [0]
					}
				],
				chart: {
					type: "bar",
					height: 278,
					toolbar: {
						show: false
					},
					stacked: true
				},
				plotOptions: {
					bar: {
						horizontal: true,
						barHeight: "80%",
						distributed: true,
						dataLabels: {
							position: "top"
						}
					},
				},

				dataLabels: {
					enabled: true,
					offsetX: 30,
					position: "top",
					formatter: function(value) {
						return value.toFixed(0);
					},
					style: {
						fontSize: "13px",
						colors: ['#FF640A']
					},
				},

				stroke: {
					show: true,
					width: 1,
					colors: ["#fff"]
				},
				tooltip: {
					custom: function({ series, seriesIndex, dataPointIndex, w }) {
						return (
							'<div class="arrow_box">' +
							"<span>" +
							w.globals.labels[dataPointIndex] +
							": " +
							series[seriesIndex][dataPointIndex] +
							"Km</span>" +
							"</div>"
						);
					}
				},
				xaxis: {
					categories: [""],
					labels: {
						show: true,
						formatter: function(value) {
							return value.toFixed(1) + "Km";
						},
						style: {
							fontSize: "13px",
							fontFamily: "Barlow, sans-serif",
							fontWeight: 400,
							cssClass: "apexcharts-xaxis-label"
						}
					}
				},
				colors: ["#0F3F87"],
				yaxis: [
					{
						labels: {
							style: {
								fontSize: "13px",
								fontFamily: "Barlow, sans-serif",
								fontWeight: 400
							}
						}
					}
				]
			},

			type: "bar3d",
			renderAt: "grafica04",
			width: "100%",
			height: "400",
			dataFormat: "json",
			TipoVehiculo: "Vendidos",
			isVisible: false,
			isOverlay: false
		};
	},
	methods: {
		mostrarfiltro() {
			this.isVisible = true;
		},

		Ocultar() {
			this.isVisible = false;
		},
		verificarnumero(value) {
			let val = value;
			if (value < 10) {
				val = "0" + value;
			}
			return val;
		},

		async get_dataSource() {
			this.isOverlay = true;
			await this.$http
				.get(
					//'dashboard/ventaxvehiculo',
					"despachooneproductividad/get",
					{
						params: {
							Fecha_I: this.rangeDate.start,
							Fecha_F: this.rangeDate.end,
							TipoVehiculo: this.TipoVehiculo
						}
					}
				)
				.then(res => {

					this.isOverlay = false;


					var date = new Date(this.rangeDate.start);
					var year = date.getFullYear();
					var month = date.getMonth() + 1; //getMonth is zero based;
					var day = date.getDate();
					var formatted =
						this.verificarnumero(day) +
						"-" +
						this.verificarnumero(month) +
						"-" +
						year;

					var datef = new Date(this.rangeDate.end);
					var yearf = datef.getFullYear();
					var monthf = datef.getMonth() + 1; //getMonth is zero based;
					var dayf = datef.getDate();
					var formattedf =
						this.verificarnumero(dayf) +
						"-" +
						this.verificarnumero(monthf) +
						"-" +
						yearf;


					this.options.xaxis.categories = res.data.datacategory;

					let options = {
						series: [
							{
								data: res.data.dataseries
							}
						],
						chart: {
							type: "bar",
							height: 278,
							toolbar: {
								show: false
							},
							stacked: true
						},
						plotOptions: {
							bar: {
								horizontal: true,
								barHeight: "80%",
								distributed: true,
								dataLabels: {
									position: "top"
								}
							}
						},
						dataLabels: {
							enabled: true,
							offsetX: 35,
							position: "top",
							formatter: function(value) {
								let num = value;
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
							style: {
								fontSize: "13px",
								colors: ["#FF640A"]
							},
						},

						stroke: {
							show: true,
							width: 1,
							colors: ["#fff"]
						},
						tooltip: {
							custom: function({ series, seriesIndex, dataPointIndex, w }) {
								return (
									'<div class="arrow_box">' +
									"<span>" +
									w.globals.labels[dataPointIndex] +
									": " +
									series[seriesIndex][dataPointIndex].toFixed(0) +
									" Km</span>" +
									"</div>"
								);
							}
						},
						xaxis: {
							categories: res.data.datacategory,
							labels: {
								show: true,
								formatter: function(value) {
									return value.toFixed(0) + "Km";
								},
								style: {
									fontSize: "13px",
									fontFamily: "Barlow, sans-serif",
									fontWeight: 400,
									cssClass: "apexcharts-xaxis-label"
								}
							}
						},
						colors: ["#0F3F87"],
						yaxis: [
							{
								labels: {
									style: {
										fontSize: "13px",
										fontFamily: "Barlow, sans-serif",
										fontWeight: 400
									}
								}
							}
						]
					};

					this.options = options;
				})
				.catch(e => {
					this.isOverlay = false;
				});
		},


	},
	created() {
		var date = new Date(),
			y = date.getFullYear(),
			m = date.getMonth();
		var firstDay = new Date(y, m, 1);
		var lastDay = new Date(y, m + 1, 0);
		this.rangeDate = {
			start: firstDay,
			end: lastDay
		};
	},
	mounted() {
		this.get_dataSource();
	}
};
</script>
