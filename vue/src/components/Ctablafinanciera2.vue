<template>
	<div>
		{{ calcularValores }}
		<div class="tab-content tab-content-table">
			<div
				class="tab-pane fade show active"
				id="nav-dato-01"
				role="tabpanel"
				aria-labelledby="dato-tab-01"
			>
				<div class="table-finanzas">
					<table class="table-fin-04">
						<thead>
							<tr>
								<th class="sticky marca"></th>
								<th></th>
								<th colspan="2" class="text-center blue-01">Mes Actual</th>
								<th colspan="4" class="text-center blue-02">
									Actualización Manual
								</th>
							</tr>
							<tr>
								<th class="sticky mediana text-center marca">
									<b v-if="Tipo==1">Costo G&A</b>
									<b v-if="Tipo==2 && Gasto==1 && Tabla1==0" >Gastos Directos</b>
									<b v-if="Tipo==2 && Tabla2==0">Gastos Indirectos</b>
									<b v-if="Tipo==3">Costo Depto. Oper.</b>
									<b v-if="Tipo==4">Costo Vehículo</b>
									<b v-if="Tipo==5 &&TablaFinan1==0">Ingresos</b>
									<b v-if="Tipo==5 && TablaFinan==0">Egresos</b>
								</th>
								<th class="mediana text-center">Cuenta</th>
								<th class="mediana text-center">Plan</th>
								<th class="blue-03 mediana text-center">Actual</th>

								<th class="blue-02 mediana text-center">Semana 1</th>
								<th class="blue-02 mediana text-center">Semana 2</th>
								<th class="blue-02 mediana text-center">Semana 3</th>
								<th class="blue-02 mediana text-center">Semana 4</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(item, index) in Lista" :key="index">
								<!-- CUENTA COLUMN -->
								<td class="sticky">
									<b>{{ item.Descripcion }}</b>
								</td>
								<td >
									<input
										type="text"
										readonly
										class="form-control form-finanza form-control-sm text-center"
										style="width:150px;"
										v-model="item.NumeroCuenta"
									/>
								</td>
								<!-- FIN CUENTA COLUMN -->

								<!-- MES ACTUAL COLUMNS -->
								<td>
									<vue-numeric
										disabled
										class="form-control form-finanza form-control-sm text-center"
										currency="$"
										separator=","
										:precision="2"
										v-model="item.PlanMes"
										placeholder="$ 0.00"
									>
									</vue-numeric>
								</td>
								<td>
									<vue-numeric
										disabled
										class="form-control	 form-finanza form-control-sm text-center"
										currency="$"
										separator=","
										:precision="2"
										v-model="item.ActualMes"
										placeholder="$ 0.00"
									></vue-numeric>
								</td>
								<!-- FIN MES ACTUAL COLUMNS -->

								<!-- ACUALIZACIÓN MANUAL COLUMNS -->
								<td>
									<vue-numeric
										class="form-control form-finanza form-control-sm text-center"
										currency="$"
										separator=","
										:precision="2"
										v-model="item.SemanaUno"
										placeholder="$ 0.00"
									></vue-numeric>
								</td>
								<td>
									<vue-numeric
										class="form-control form-finanza form-control-sm text-center"
										currency="$"
										separator=","
										:precision="2"
										v-model="item.SemanaDos"
										placeholder="$ 0.00"
									></vue-numeric>
								</td>
								<td>
									<vue-numeric
										class="form-control form-finanza form-control-sm text-center"
										currency="$"
										separator=","
										:precision="2"
										v-model="item.SemanaTres"
										placeholder="$ 0.00"
									></vue-numeric>
								</td>
								<td>
									<vue-numeric
										class="form-control form-finanza form-control-sm text-center"
										currency="$"
										separator=","
										:precision="2"
										v-model="item.SemanaCuatro"
										placeholder="$ 0.00"
									></vue-numeric>
								</td>
								<!-- FIN ACUALIZACIÓN MANUAL COLUMNS -->
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td class="color-02 bold text-center sticky">Totales</td>
								<td></td>
								<td>
									<vue-numeric
										disabled
										class="form-control form-finanza form-control-sm color-01 bold text-center"
										currency="$"
										separator=","
										:precision="2"
										v-model="TotalPlanMonth"
										placeholder="$ 0.00"
									></vue-numeric>
								</td>
								<td>
									<vue-numeric
										disabled
										class="form-control form-finanza form-control-sm color-01 bold text-center"
										currency="$"
										separator=","
										:precision="2"
										v-model="TotalActualMonth"
										placeholder="$ 0.00"
									></vue-numeric>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default {
	props: ["Lista", "Nombre", "ObjBurden","Tipo","Gasto","Tabla1","Tabla2","TablaFinan","TablaFinan1"],
	data() {
		return {
			// TAnioA: 0,
			// TotalActualMonthrimestre1: 0,
			// TotalActualMonthrimestre2: 0,
			TotalPlanMonth: 0,
			TotalActualMonth: 0
			// TotalActualMonthotalA: 0,
		};
	},
	methods: {},
	created() {},
	computed: {
		calcularValores() {
			this.TotalPlanMonth = 0;
			this.TotalActualMonth = 0;
			// this.TotalActualMonthotalA = 0;
			for (var i = 0; i < this.Lista.length; i++) {
				// var mont = this.Lista[i].AnioPasado;
				// if (this.Lista[i].AnioPasado != "") {
				// 	this.TAnioA += parseFloat(this.Lista[i].AnioPasado);
				// }
				// if (this.Lista[i].PlanAnio != "") {
				// 	this.TotalActualMonthrimestre1 += parseFloat(this.Lista[i].PlanAnio);
				// 	Uno = parseFloat(this.Lista[i].PlanAnio);
				// }
				// if (this.Lista[i].ActualAnio != "") {
				// 	this.TotalActualMonthrimestre2 += parseFloat(this.Lista[i].ActualAnio);
				// 	Dos = parseFloat(this.Lista[i].ActualAnio);
				// }

				if (this.Lista[i].PlanMes != "") {
					// console.log(this.Lista[i]);
					this.TotalPlanMonth += parseFloat(this.Lista[i].PlanMes);
					// PlanMonth = parseFloat(this.Lista[i].PlanMes);
				}
				if (this.Lista[i].ActualMes != "") {
					this.TotalActualMonth += parseFloat(this.Lista[i].ActualMes);
					// ActualMonth = parseFloat(this.Lista[i].ActualMes);
				}

				/////////////ESTO SIRVE
				// if (this.Lista[i].SemanaUno != "") {
				// 	// this.TotalActualMonth += parseFloat(this.Lista[i].SemanaUno);
				// 	this.Lista[i].ActualMes += parseFloat(this.Lista.SemanaUno);
				// 	// weekOne = parseFloat(this.Lista[i].SemanaUno);
				// }
				// if (this.Lista[i].SemanaDos != "") {
				// 	this.TotalActualMonth += parseFloat(this.Lista[i].SemanaDos);
				// 	// weekTwo = parseFloat(this.Lista[i].SemanaDos);
				// }
				// if (this.Lista[i].SemanaTres != "") {
				// 	this.TotalActualMonth += parseFloat(this.Lista[i].SemanaTres);
				// 	// weekThree = parseFloat(this.Lista[i].SemanaTres);
				// }
				// if (this.Lista[i].SemanaCuatro != "") {
				// 	this.TotalActualMonth += parseFloat(this.Lista[i].SemanaCuatro);
				// 	// weekFour = parseFloat(this.LÑista[i].SemanaCuatro);
				// }
				/////////////FIN ESTO SIRVE

				/*
                this.Lista[i].MontoAnual  = parseFloat(Uno) +parseFloat(Dos)+parseFloat(PlanMonth)+parseFloat(ActualMonth);
                if (this.Lista[i].MontoAnual !='')
            	{
                this.TotalActualMonthotalA += parseFloat(this.Lista[i].MontoAnual);
               	}
               */
			}
		}
	}
};
</script>
