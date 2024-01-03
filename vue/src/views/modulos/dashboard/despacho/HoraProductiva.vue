<template>
	<div class="col-md-12 col-lg-6 col-xl-6 mb-2">
		<b-overlay
			:show="isOverlay"
			rounded="sm"
			spinner-variant="primary"
			class="h-100"
		>
			<div class="card card-grafica h-100">
				<div class="card-body">
					<div class="filtro-grafic" id="grafica_001" v-if="isVisible">
						<div class="row justify-content-start">
							<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<form class="form-inline">
									<label class="title-grafic side-l mr-3"
										>Hrs. Prod. Semanales</label
									>

									<select
										style="width: 140px"
										id="Trabajador"
										@change="get_dataSource()"
										class="form-control form-control-sm mb-2 mr-1"
										v-model="IdTrabajador1"
									>
										<option
											v-for="(item, index) in ListaTrabajadores"
											:key="index"
											:value="item.IdTrabajador"
										>
											{{ item.Nombre }}
										</option>
									</select>

									&nbsp;

									<select
										style="width: 140px"
										id="Trabajador2"
										@change="get_dataSource()"
										class="form-control form-control-sm mb-2 mr-2"
										v-model="IdTrabajador2"
									>
										<option value="0">Todos</option>
										<option
											v-for="(item, index) in ListaTrabajadores"
											:key="index"
											:value="item.IdTrabajador"
										>
											{{ item.Nombre }}
										</option>
									</select>

									&nbsp;

									<v-date-picker v-model="startDate" :masks="masks" :popover="{ visibility: 'focus' }" locale="es" @input="calculateRange()">
										<template v-slot="{ inputValue, inputEvents }">
											<input
												class="form-control form-control-sm mb-2 mr-1 calendar"
												:value="inputValue"
												v-on="inputEvents"
												readonly
											/>
										</template>
									</v-date-picker>

									<input v-model="finishDate" class="form-control form-control-sm mb-2 calendar" type="text" disabled="disabled"/>

									<!--<v-date-picker
										style="width: 140px"
										mode="range"
										v-model="rangeDate"
										@input="calculateRange()"
										:input-props="{
											class: 'form-control form-control-sm mb-2 calendar',
											placeholder: 'Selecciona un rango de fecha para buscar',
											readonly: true
										}"
									/>-->
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
							<h6 class="title-grafic side-l">Horas Productivas Semanales</h6>
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

					<div class="row">
						<div class="col-md-6 col-lg-6">
							<div id="grafica01">
								<fusioncharts
									:type="type"
									:width="width"
									:height="height"
									:dataFormat="dataFormat"
									:dataSource="dataSource"
								></fusioncharts>
							</div>
							<h5 class="text-center tile-grafic-02">Individual</h5>
						</div>

						<div class="col-md-6 col-lg-6 ">
							<div id="grafica02">
								<fusioncharts
									:type="type"
									:width="width"
									:height="height"
									:dataFormat="dataFormat"
									:dataSource="dataSourcetwo"
								></fusioncharts>
							</div>
							<h5 class="text-center tile-grafic-02">Comparativo</h5>
						</div>
					</div>
				</div>
			</div>
		</b-overlay>
	</div>
</template>

<script>
import moment from "moment";
export default {
	name: "HorasProductivas",
	data() {
		return {
			masks: {
				input: "DD/MM/YYYY"
			},
			finishDate: "",
			realFinishDate: "",
			startDate: "",

			isVisible: false,
			width: "100%",
			height: "271",
			type: "angulargauge",
			dataFormat: "json",
			dataSource: {
				chart: {
					lowerLimit: "0",
					upperLimit: "100",
					numberSuffix: "hr",

					pivotRadius: "9",
					pivotBorderColor: "#333333",
					pivotBorderAlpha: "100",
					pivotFillMix: " ",
					pivotFillColor: "#FFFFFF",
					pivotFillAlpha: "100",
					pivotFillType: "linear",
					showPivotBorder: "1",
					showValue: "1",
					valueBelowPivot: "0",

					showborder: "0",
					bgColor: "#FFFFFF",
					BaseFont: "Arial",
					BaseFontSize: "14",
					BaseFontWeight: "bold",
					labelFontBold: "1",
					tickValueStep: "10",
					gaugeBorderThickness: "1",
					ticksBelowGauge: "0",
					adjustTM: "0",
					majorTMNumber: "5",
					minorTMNumber: "2",
					gaugeFillRatio: "5",
					autoAlignTickValues: "1",
					manageValueOverLapping: "1",
					tickValueDistance: "5",
					placeTicksInside: "0",
					placeValuesInside: "1",

					chartLeftMargin: "5",
					chartTopMargin: "0",
					chartRightMargin: "5",
					chartBottomMargin: "5",
					canvasPadding: "0",
					theme: "ocean"
				},
				colorRange: {
					color: [
						{
							minValue: "0",
							maxValue: "50",
							code: "#e74c3c"
						},
						{
							minValue: "50",
							maxValue: "75",
							code: "#f1c40f"
						},
						{
							minValue: "75",
							maxValue: "100",
							code: "#2ecc71"
						}
					]
				},
				dials: {
					dial: [
						{
							value: "67",
							alpha: "100",
							showValue: "0",
							radius: "195",
							rearExtension: "1",
							borderThickness: "1",
							borderAlpha: "0",
							tooltext: "Horas trabajadas: $value",
							bgColor: "#0F3F87",
							baseWidth: "15"
						}
					]
				},
				trendPoints: {
					point: [
						{
							startValue: 0,
							color: "#0075c2",
							dashed: 0,
							//radius: 185,
							alpha: 100,
							valueInside: 0
						},
						{
							startValue: 50,
							color: "#0075c2",
							dashed: 0,
							//radius: 190,
							valueInside: 0,
							alpha: 100
						},
						{
							startValue: 75,
							color: "#0075c2",
							dashed: 0,
							//radius: 190,
							valueInside: 0,
							alpha: 100
						}
					]
				}
			},

			dataSourcetwo: {
				chart: {
					lowerLimit: "0",
					upperLimit: "100",
					numberSuffix: "hr",

					pivotRadius: "9",
					pivotBorderColor: "#333333",
					pivotBorderAlpha: "100",
					pivotFillMix: "",
					pivotFillColor: "#FFFFFF",
					pivotFillAlpha: "100",
					pivotFillType: "linear",
					showPivotBorder: "1",
					showValue: "1",
					valueBelowPivot: "0",

					showborder: "0",
					bgColor: "#FFFFFF",
					BaseFont: "Arial",
					BaseFontSize: "14",
					BaseFontWeight: "bold",
					labelFontBold: "1",
					tickValueStep: "10",
					gaugeBorderThickness: "1",
					ticksBelowGauge: "0",
					adjustTM: "0",
					majorTMNumber: "5",
					minorTMNumber: "2",
					gaugeFillRatio: "5",
					autoAlignTickValues: "1",
					manageValueOverLapping: "1",
					tickValueDistance: "5",
					placeTicksInside: "0",
					placeValuesInside: "1",

					chartLeftMargin: "5",
					chartTopMargin: "0",
					chartRightMargin: "5",
					chartBottomMargin: "5",
					canvasPadding: "0",
					theme: "ocean"
				},
				colorRange: {
					color: [
						{
							minValue: "0",
							maxValue: "50",
							code: "#e74c3c"
						},
						{
							minValue: "50",
							maxValue: "75",
							code: "#f1c40f"
						},
						{
							minValue: "75",
							maxValue: "100",
							code: "#2ecc71"
						}
					]
				},
				dials: {
					dial: [
						{
							value: "80",
							alpha: "100",
							showValue: "0",
							radius: "195",
							rearExtension: "0",
							borderThickness: "1",
							borderAlpha: "0",
							tooltext: "Horas trabajadas: $value",
							bgColor: "#0F3F87",
							baseWidth: "15"
						}
					]
				},
				trendPoints: {
					point: [
						{
							startValue: 0,
							color: "#0075c2",
							dashed: 0,
							//radius: 185,
							valueInside: 0,
							alpha: 100
						},
						{
							startValue: 50,
							color: "#0075c2",
							dashed: 0,
							//radius: 190,
							valueInside: 0,
							alpha: 100
						},
						{
							startValue: 75,
							color: "#0075c2",
							dashed: 0,
							//radius: 190,
							valueInside: 0,
							alpha: 100
						}
					]
				}
			},

			ListaTrabajadores: [],
			rangeDate: {},
			IdTrabajador1: 0,
			IdTrabajador2: 0,

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

		verificarnumero(value) {
			let val = value;
			if (value < 10) {
				val = "0" + value;
			}
			return val;
		},

		calculateRange() {
			this.finishDate 	= "";
			this.finishDate = moment(this.startDate)
				.add(6, "days")
				.format("DD/MM/YYYY");

			this.realFinishDate 	= "";
			this.realFinishDate = moment(this.startDate)
				.add(6, "days")
				.format("YYYY-MM-DD");

			this.get_dataSource();
		},

		setWeekValue() {
			//this.startDate = new Date(),
			//this.finishDate	=  moment(this.startDate).add(6, 'days').format('YYYY-MM-DD');

			this.startDate = moment()
				.startOf("week")
				.add(1, "days")
				.toDate();

			let endOfWeek = moment()
				.endOf("week")
				.toDate();

			this.finishDate = moment(endOfWeek)
				.add(1, "days")
				.format("DD/MM/YYYY");


			this.realFinishDate = moment(endOfWeek)
				.add(1, "days")
				.format("YYYY-MM-DD");
		},

		/**
		 * RECUPERA LOS VALORES INICIALES PARA LAS GRAFICAS EN VALORES BASE
		 *
		 */
		async get_dataSource() {
			this.isOverlay = true;
			if (this.ListaTrabajadores.length > 0) {
				/**
				 * Cargar inicialmente los datos
				 */

				//console.log(this.startDate)
				//console.log(this.realFinishDate);


				let newRequest = {};

				if (parseInt(this.IdTrabajador1) > 0 && parseInt(this.IdTrabajador2) == 0) {
					newRequest = {
						IdTrabajador: this.IdTrabajador1,
						IdTrabajador2: 0,

						Fecha_I: moment(this.startDate).format('YYYY-MM-DD'),
						Fecha_F: this.realFinishDate
					};
				} else {
					newRequest = {
						IdTrabajador: this.IdTrabajador1,
						IdTrabajador2: this.IdTrabajador2,

						Fecha_I: moment(this.startDate).format('YYYY-MM-DD'),
						Fecha_F: this.realFinishDate
					};
				}


				await this.$http
					.post("despachoone/post", newRequest)
					.then(res => {
						this.isOverlay = false;
						//console.log(res.data.data);
						this.create_FirstGraph(res.data.data.Trabajador1);
						this.create_SecondGraph(res.data.data.Trabajador2);
					})
					.catch(() => {

					}).finally(() => {
						this.isOverlay = false;
					});
			}
		},

		create_FirstGraph(Trabajador1) {
			this.dataSource.chart.upperLimit = Trabajador1.HoraTrabajoSemanal;

			// COLOR ROJO
			this.dataSource.colorRange.color[0].minValue = 0;
			this.dataSource.colorRange.color[0].maxValue = Trabajador1.HoraPSmenos;

			// COLOR AMARILLO
			this.dataSource.colorRange.color[1].minValue = Trabajador1.HoraPSmenos;
			this.dataSource.colorRange.color[1].maxValue = Trabajador1.HoraPS;

			// COLOR VERDE
			this.dataSource.colorRange.color[2].minValue = Trabajador1.HoraPS;
			this.dataSource.colorRange.color[2].maxValue =
				Trabajador1.HoraTrabajoSemanal;

			this.dataSource.dials.dial[0].value = parseFloat(
				Trabajador1.horasT
			).toFixed(1);

			//Primera Marca
			this.dataSource.trendPoints.point[0].startValue = parseFloat(
				Trabajador1.HoraPSmenos
			).toFixed(1);
			this.dataSource.trendPoints.point[0].markertooltext =
				parseFloat(Trabajador1.HoraPSmenos).toFixed(1) + "hr";
			this.dataSource.trendPoints.point[0].displayvalue =
				parseFloat(Trabajador1.HoraPSmenos).toFixed(1) + "hr";

			//Segunda Marca
			this.dataSource.trendPoints.point[1].startValue = parseFloat(
				Trabajador1.HoraPS
			).toFixed(1);
			this.dataSource.trendPoints.point[1].markertooltext =
				parseFloat(Trabajador1.HoraPS).toFixed(1) + "hr";
			this.dataSource.trendPoints.point[1].displayvalue =
				parseFloat(Trabajador1.HoraPS).toFixed(1) + "hr";

			//Aguja del Marcador
			this.dataSource.trendPoints.point[2].startValue = parseFloat(
				Trabajador1.horasT
			).toFixed(1);
			this.dataSource.trendPoints.point[2].markertooltext =
				parseFloat(Trabajador1.horasT).toFixed(1) + "hr";
			this.dataSource.trendPoints.point[2].displayvalue =
				parseFloat(Trabajador1.horasT).toFixed(1) + "hr";
		},

		create_SecondGraph(Trabajador2) {
			//var numero_01 = Math.round(20.7777777 * 100) / 100;

			this.dataSourcetwo.chart.upperLimit = Trabajador2.HoraTrabajoSemanal;

			//COLOR ROJO
			this.dataSourcetwo.colorRange.color[0].minValue = 0;
			this.dataSourcetwo.colorRange.color[0].maxValue = Trabajador2.HoraPSmenos;

			// COLOR AMARILLO
			this.dataSourcetwo.colorRange.color[1].minValue = Trabajador2.HoraPSmenos;
			this.dataSourcetwo.colorRange.color[1].maxValue = Trabajador2.HoraPS;

			// COLOR VERDE
			this.dataSourcetwo.colorRange.color[2].minValue = Trabajador2.HoraPS;
			this.dataSourcetwo.colorRange.color[2].maxValue =
				Trabajador2.HoraTrabajoSemanal;

			this.dataSourcetwo.dials.dial[0].value = parseFloat(
				Trabajador2.horasT
			).toFixed(2);

			//Primera Marca - Horas Productivas que tiene asignado el tecnico - 5hr
			this.dataSourcetwo.trendPoints.point[0].startValue = parseFloat(
				Trabajador2.HoraPSmenos
			).toFixed(1);
			this.dataSourcetwo.trendPoints.point[0].markertooltext =
				parseFloat(Trabajador2.HoraPSmenos).toFixed(1) + "hr";
			this.dataSourcetwo.trendPoints.point[0].displayvalue =
				parseFloat(Trabajador2.HoraPSmenos).toFixed(1) + "hr";

			//Segunda Marca - Horas Productivas que tiene asignado el tecnico
			this.dataSourcetwo.trendPoints.point[1].startValue = parseFloat(
				Trabajador2.HoraPS
			).toFixed(1);
			this.dataSourcetwo.trendPoints.point[1].markertooltext =
				parseFloat(Trabajador2.HoraPS).toFixed(1) + "hr";
			this.dataSourcetwo.trendPoints.point[1].displayvalue =
				parseFloat(Trabajador2.HoraPS).toFixed(1) + "hr";

			//Aguja del Marcador
			this.dataSourcetwo.trendPoints.point[2].startValue = parseFloat(
				Trabajador2.horasT
			).toFixed(1);
			this.dataSourcetwo.trendPoints.point[2].markertooltext =
				parseFloat(Trabajador2.horasT).toFixed(1) + "hr";
			this.dataSourcetwo.trendPoints.point[2].displayvalue =
				parseFloat(Trabajador2.horasT).toFixed(1) + "hr";
		},

		/** LISTADO DE LOS TABAJADORES  */
		get_listtrabajador() {
			this.$http
				.get("trabajador/get", {
					params: {
						Rol: "USUARIO APP",
						IdPerfil: 4
					}
				})
				.then(res => {
					this.ListaTrabajadores = res.data.data.trabajador;
					this.IdTrabajador1 = this.ListaTrabajadores[0].IdTrabajador;
					this.get_dataSource();
				});
		}
	},

	created() {
		this.setWeekValue();
	},

	mounted() {
		this.get_listtrabajador();
	},
	destroyed() {}
};
</script>
