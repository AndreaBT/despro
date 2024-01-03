<template>
	<div class="col-md-12 col-lg-6 mb-3">
		<b-overlay :show="isOverlay" rounded="sm" spinner-variant="primary" class="h-100">
			<div class="card card-grafica h-100">
				<div class="card-body">
					<div class="filtro-grafic" id="grafica_003" v-if="isVisible">
						<div class="row justify-content-start">
							<div class="col-12 col-md-12 col-lg-12 col-xl-12">
								<form class="form-inline mb-2">
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
									<label class="mr-2">Mes</label>
									<select
										@change="Lista"
										v-model="Grafica1.Mes"
										class="form-control form-control-sm"
									>
										<option value="13">YTD</option>
										<option value="1">Enero</option>
										<option value="2">Febrero</option>
										<option value="3">Marzo</option>
										<option value="4">Abril</option>
										<option value="5">Mayo</option>
										<option value="6">Junio</option>
										<option value="7">Julio</option>
										<option value="8">Agosto</option>
										<option value="9">Septiembre</option>
										<option value="10">Octubre</option>
										<option value="11">Noviembre</option>
										<option value="12">Diciembre</option>
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

					<div class="row">
						<div class="col-10 col-sm-10 col-md-8 col-lg-8">
							<h6 class="title-grafic side-l">Gross Profit</h6>
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
					<div class="form-row">
						<div class="col-md-4 col-lg-4 col-xl-4">
							<div v-if="verapex1" id="apx-03">
								<apexchart :options="optionsN" :series="series"></apexchart>
							</div>
						</div>
						<div class="col-md-4 col-lg-4 col-xl-4">
							<div v-if="verapex2" id="apx-03">
								<apexchart :options="options" :series="series2"></apexchart>
							</div>
						</div>
						<div class="col-md-4 col-lg-4 col-xl-4">
							<div v-if="verapex3" id="apx-03">
								<apexchart :options="optionsP" :series="series3"></apexchart>
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
	name: "GrossProfit",
	data() {
		return {
			isOverlay: true,

			verapex1: false,
			verapex2: false,
			verapex3: false,

			/******************** MANTENIMIENTO *********************/

			optionsN: {
				chart: {
					type: "donut",
					height: 280
				},
				dataLabels: {
					enabled: false
				},
				plotOptions: {
					pie: {
						donut: {
							labels: {
								show: true,
								name: {
									show: true,
									fontSize: "10px",
									fontFamily: "Rubik",
									color: "#000000",
									offsetY: -10
								},
								value: {
									show: true,
									fontSize: "45px",
									fontFamily: "Barlow, sans-serif",
									fontWeight: 600,
									color: "#0F3F87",
									offsetY: 0
								},
								total: {
									showAlways: true,
									show: true,
									label: "",
									fontFamily: "Barlow, sans-serif",
									fontSize: "12px",
									color: "#000000",
									final: 0,
									formatter: function(w) {
										return (
											w.globals.series.reduce((CO, GP) => {
												var f=Math.round(((100)-GP));
												return f;
											}, 0) + "%"
										);
									}
								}
							}
						}
					}
				},
				legend: {
					show: false
				},
				colors: ["#0F3F87", "#FF640A"],
				title: {
					text: "Mantenimiento",
					align: "center",
					style: {
						fontSize: "20px",
						fontWeight: 700,
						fontFamily: "Barlow, sans-serif",
						color: "#000000"
					}
				},
				tooltip: {
					enabled: false
				},
				responsive: [
					{
						breakpoint: 1920,
						options: {
							chart: {
								height: 250
							}
						}
					},
					{
						breakpoint: 1367,
						options: {
							chart: {
								height: 230
							}
						}
					},
					{
						breakpoint: 1025,
						options: {
							chart: {
								height: 180
							}
						}
					},
					{
						breakpoint: 769,
						options: {
							chart: {
								height: 240
							}
						}
					}
				]
			},
			series: [],

			/************************ SERVIVIO ************************/

			options: {
				chart: {
					type: "donut",
					height: 280
				},
				dataLabels: {
					enabled: false
				},
				plotOptions: {
					pie: {
						donut: {
							labels: {
								show: true,
								name: {
									show: true,
									fontSize: "10px",
									fontFamily: "Rubik",
									color: "#000000",
									offsetY: -10
								},
								value: {
									show: true,
									fontSize: "45px",
									fontFamily: "Barlow, sans-serif",
									fontWeight: 600,
									color: "#0F3F87",
									offsetY: 0
								},
								total: {
									showAlways: true,
									show: true,
									label: "",
									fontFamily: "Barlow, sans-serif",
									fontSize: "12px",
									color: "#000000",
									formatter: function(w) {
										return (
											w.globals.series.reduce((GOS, GPS) => {
												var f=Math.round(((100)-GPS));
												return f;
											}, 0) + "%"
										);
									}
								}
							}
						}
					}
				},
				legend: {
					show: false
				},
				colors: ["#216CB8", "#FF640A"],
				title: {
					text: "Servicio",
					align: "center",
					style: {
						fontSize: "20px",
						fontWeight: 700,
						fontFamily: "Barlow, sans-serif",
						color: "#000000"
					}
				},
				tooltip: {
					enabled: false
				},
				responsive: [
					{
						breakpoint: 1920,
						options: {
							chart: {
								height: 250
							}
						}
					},
					{
						breakpoint: 1367,
						options: {
							chart: {
								height: 230
							}
						}
					},
					{
						breakpoint: 1025,
						options: {
							chart: {
								height: 180
							}
						}
					},
					{
						breakpoint: 769,
						options: {
							chart: {
								height: 240
							}
						}
					}
				]
			},
			series2: [],

			/***************** PROYECTO ***********************/
			optionsP: {
				chart: {
					type: "donut",
					height: 280
				},
				dataLabels: {
					enabled: false
				},
				plotOptions: {
					pie: {
						donut: {
							labels: {
								show: true,
								name: {
									show: true,
									fontSize: "10px",
									fontFamily: "Rubik",
									color: "#000000",
									offsetY: -10
								},
								value: {
									show: true,
									fontSize: "45px",
									fontFamily: "Barlow, sans-serif",
									fontWeight: 600,
									color: "#0F3F87",
									offsetY: 0
								},
								total: {
									showAlways: true,
									show: true,
									label: "",
									fontFamily: "Barlow, sans-serif",
									fontSize: "12px",
									color: "#000000",
									formatter: function(w) {
										return (
											w.globals.series.reduce((GOS, GPP) => {
												var f=Math.round(((100)-GPP));
												return f;
											}, 0) + "%"
										);
									}
								}
							}
						}
					}
				},
				legend: {
					show: false
				},
				colors: ["#297DCA", "#FF640A"],
				title: {
					text: "Proyecto",
					align: "center",
					style: {
						fontSize: "20px",
						fontWeight: 700,
						fontFamily: "Barlow, sans-serif",
						color: "#000000"
					}
				},
				tooltip: {
					enabled: false
				},
				responsive: [
					{
						breakpoint: 1920,
						options: {
							chart: {
								height: 250
							}
						}
					},
					{
						breakpoint: 1367,
						options: {
							chart: {
								height: 230
							}
						}
					},
					{
						breakpoint: 1025,
						options: {
							chart: {
								height: 180
							}
						}
					},
					{
						breakpoint: 769,
						options: {
							chart: {
								height: 240
							}
						}
					}
				]
			},
			series3: [],

			type: "doughnut2d",
			renderAt: "dona",
			width: "100%",
			height: "300",
			dataFormat: "json",
			dataSource: "",
			dataSource2: "",
			dataSource3: "",
			//urlApi:'finanzasgraf/getgrossp',
			urlApi: "finanzasGrossProfit/get",
			ListaAnios: [],
			Grafica1: {
				Anio: "2019",
				Mes: ""
			},
			isVisible: false
		};
	},
	created() {
		this.bus.$off("Ocultar3");
		this.bus.$on("Ocultar3", data => {
			this.Ocultar3(data);
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


				let today = new Date();
				let month = new Array();
				month[0] = "1";
				month[1] = "2";
				month[2] = "3";
				month[3] = "4";
				month[4] = "5";
				month[5] = "6";
				month[6] = "7";
				month[7] = "8";
				month[8] = "9";
				month[9] = "10";
				month[10] = "11";
				month[11] = "12";
				month[12] = "13";

				// this.Grafica1.Mes = month[today.getMonth()];
				this.Grafica1.Mes = 13;
				this.Lista();
			});
		},


		async Lista() {
			this.isOverlay = true;
			
			if (parseInt(this.Grafica1.Mes) === 13) {
				await this.get_YTD(1);
				await this.get_YTD(2);
				await this.get_YTD(3);
			}else{
				await this.charger(1);
				await this.charger(2);
				await this.charger(3);
			}
		},


		async charger(tp) {
			
				await this.$http.get('estadosfinancierosinfo/get', {
					params: { 
						IdConfigS: tp,
						Mes: this.Grafica1.Mes, 
						Anio: this.Grafica1.Anio }
				})
				.then(res => {
					switch(tp){
						case 1:
							this.series = [];
							const GP = parseFloat(res.data.data.rowconfig.GPMesActualPorcen);
							const GO = parseFloat(res.data.data.rowconfig.COMesActualPorcen);
							this.series = [GP, GO];
							
							//console.log(this.series);
							this.verapex1 = true;
							break;

						case 2:
							this.series2 = [];
							const GPS = parseFloat(res.data.data.rowconfig.GPMesActualPorcen);
							const GOS = parseFloat(res.data.data.rowconfig.COMesActualPorcen);
							this.series2 = [GPS, GOS];
							//console.log(this.series2);
							this.verapex2 = true;
							break;

						case 3:
							this.series3 = [];
							const GPP = parseFloat(res.data.data.rowconfig.GPMesActualPorcen);
							const COP = parseFloat(res.data.data.rowconfig.COMesActualPorcen);
							this.series3 = [GPP, COP];
							//console.log(this.series3);
							this.verapex3 = true;
							this.isOverlay = false;
							break
					}
			
			
				});
			
				
		},

		async	get_YTD(tp){
			await this.$http.get('estadosfinancierosinfo/get', {
					params: { 
						IdConfigS: tp,
						Mes: 13, 
						Anio: this.Grafica1.Anio,
						isYTD: 1 }
				})
				.then(res => {
					switch(tp){
						case 1:
							this.series = [];
							const GP = parseFloat(res.data.data.rowconfig.GPAnioActualPorcen);
							const GO = parseFloat(res.data.data.rowconfig.COAnioActualPorcen);
							this.series = [GP, GO];

							
							
							//console.log(this.series);
							this.verapex1 = true;
							break;

						case 2:
							this.series2 = [];
							const GPS = parseFloat(res.data.data.rowconfig.GPAnioActualPorcen);
							const GOS = parseFloat(res.data.data.rowconfig.COAnioActualPorcen);
							this.series2 = [GPS, GOS];

							console.log(GP);
							//console.log(this.series2);
							this.verapex2 = true;
							break;

						case 3:
							this.series3 = [];
							const GPP = parseFloat(res.data.data.rowconfig.GPAnioActualPorcen);
							const COP = parseFloat(res.data.data.rowconfig.COAnioActualPorcen);
							this.series3 = [GPP, COP];
							//console.log(this.series3);
							this.verapex3 = true;
							this.isOverlay = false;
							break
					}
			
			
				});
				
		},


		Ocultar3(data) {
			this.isVisible = data;
		}
	}
};


</script>