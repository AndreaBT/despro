<template>
	<div class="col-md-6 col-lg-6">
		<b-overlay :show="isOverlay" rounded="sm" spinner-variant="primary" class="h-100">
			<div class="row">
				<div class="col-12">
					<div class="card card-grafica h-100 mb-3">
						<div class="card-body">
							<div class="filtro-grafic" id="grafica_004" v-if="isVisible">
								<div class="row justify-content-start">
									<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<form class="form-inline">
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
									<h6 class="title-grafic side-l">
										Horas Por Tipo de Servicio
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
							<div id="apx-03">
								<apexchart
									width="100%"
									height="380"
									:options="options3"
									:series="options3.series"
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
	name: "HorasTipoServicio",
	data() {
		return {
			type: "bar3d",
			renderAt: "chart-container",
			width: "100%",
			height: "400",
			dataFormat: "json",
			rangeDate: "",
			heightwindows: "0",
			isVisible: false,

			options3: {
				series: [
					{
						data: [1]
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
                    offsetX: 32,
                    formatter: value => {
                        return value + "hr";
                    },
                                style: {
                                    fontSize: "13px",
                                    colors: ["#363636"]
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
							"hr</span>" +
							"</div>"
						);
					}
				},
				xaxis: {
					categories: ["Cargando"],
					labels: {
						show: true,
						formatter: value => {
							return value + "";
						},
						style: {
							fontSize: "13px",
							fontFamily: "Barlow, sans-serif",
							fontWeight: 600
						}
					}
				},
				colors: [
					"#1abc9c",
					"#3498db",
					"#9b59b6",
					"#34495e",
					"#f1c40f",
					"#e67e22",
					"#e74c3c",
					"#f39c12"
				],
				yaxis: [
					{
						labels: {
							style: {
								fontSize: "13px",
								fontFamily: "Barlow, sans-serif",
								fontWeight: 600
							}
						}
					}
				]
			},

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
					//'dashboard/servicioxhora',
					"despachooneequipo/get",
					{
						params: {
							Fecha_I: this.rangeDate.start,
							Fecha_F: this.rangeDate.end
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



					let options3 = {
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
									"hr" +
									"</span>" +
									"</div>"
								);
							}
						},
						xaxis: {
							categories: res.data.datacategory,
							labels: {
								show: true,
								formatter: value => {
									return value.toFixed(0) + "hr";
								},
								style: {
									fontSize: "13px",
									fontFamily: "Barlow, sans-serif",
									fontWeight: 600
								}
							}
						},
						colors: res.data.datacolors,
						yaxis: [
							{
								labels: {
									style: {
										fontSize: "13px",
										fontFamily: "Barlow, sans-serif",
										fontWeight: 600
									}
								}
							}
						]
					};

					this.options3 = options3;
				})
				.catch(e => {
					this.isOverlay = false;
				});
		},
		onResize() {
			this.heightwindows = window.innerHeight;

			var elmnt = document.getElementsByClassName("top-bar")[0];
			var HeigTop = elmnt.offsetHeight;

			var elmnt = document.getElementsByClassName(
				"navbar navbar-expand-xl bg-desprosoft"
			)[0];
			var HeighNav = elmnt.offsetHeight;

			var heigtFinal = (this.heightwindows - HeigTop - HeighNav - 200) / 2;
			this.height = heigtFinal;
		}
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
		//this.heightwindows = window.innerHeight
		this.get_dataSource();

		/*
    this.$nextTick(() => {
      window.addEventListener('resize', this.onResize);
    });*/
	},
	destroyed() {} /*watch: {
    heightwindows(newHeight, oldHeight) {
      //this.txt = `it changed to ${newHeight} from ${oldHeight}`;
      //alert(newHeight);
    },
  },*/,
	beforeDestroy() {
		window.removeEventListener("resize", this.onResize);
	}
};
</script>
