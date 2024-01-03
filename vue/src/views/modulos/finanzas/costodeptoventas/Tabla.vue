<template>
	<div>
		{{ calcularValores }}
		<div class="row mt-2">
			<div class="col-12 col-sm-12 col-md-12 col-lg-12">
				<div class="table-porcentaje">
					<table class="table-por-01">
						<thead>
							<tr>
								<th scope="col" class="sticky mediana marca">
									<b>{{ Nombre }}</b>
								</th>
								<th scope="col" class="mediana text-center">Numero Cuenta</th>
								<th scope="col" class="blue-01 mediana text-center">
									Año Anterior
								</th>
								<th scope="col" class="blue-03 mediana text-center">
									Trimestre 1
								</th>
								<th scope="col" class="blue-03 mediana text-center">
									Trimestre 2
								</th>
								<th scope="col" class="blue-03 mediana text-center">
									Trimestre 3
								</th>
								<th scope="col" class="blue-03 mediana text-center">
									Trimestre 4
								</th>
								<th scope="col" class="blue-02 mediana text-center">
									Total Anual
								</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(item, index) in Lista" :key="index">
								<td class="sticky">
									<input
										type="text"
										class="form-control sticky-input"
										style="width:150px;"
										v-model="item.Gasto"
									/>
								</td>
								<td>
									<input
										type="text"
										class="form-control form-finanza form-control-sm text-center"
										style="width:150px;"
										v-model="item.NumCuenta"
									/>
								</td>
								<td>
									<vue-numeric
										class="form-control form-finanza form-control-sm text-center"
										currency="$"
										separator=","
										:precision="2"
										v-model="item.MontoAnterior"
										placeholder="$ 0.00"
									></vue-numeric>
								</td>
								<td>
									<vue-numeric
										class="form-control form-finanza form-control-sm text-center"
										currency="$"
										separator=","
										:precision="2"
										v-model="item.UnoT"
										placeholder="$ 0.00"
									></vue-numeric>
								</td>
								<td>
									<vue-numeric
										class="form-control form-finanza form-control-sm text-center"
										currency="$"
										separator=","
										:precision="2"
										v-model="item.DosT"
										placeholder="$ 0.00"
									></vue-numeric>
								</td>
								<td>
									<vue-numeric
										class="form-control form-finanza form-control-sm text-center"
										currency="$"
										separator=","
										:precision="2"
										v-model="item.TresT"
										placeholder="$ 0.00"
									></vue-numeric>
								</td>
								<td>
									<vue-numeric
										class="form-control form-finanza form-control-sm text-center"
										currency="$"
										separator=","
										:precision="2"
										v-model="item.CuatroT"
										placeholder="$ 0.00"
									></vue-numeric>
								</td>
								<td>
									<vue-numeric
										disabled
										class="form-control form-finanza form-control-sm text-center"
										currency="$"
										separator=","
										:precision="2"
										v-model="item.MontoAnual"
										placeholder="$ 0.00"
									></vue-numeric>
								</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td><b>TOTALES</b></td>
								<td></td>
								<td>
									<vue-numeric
										disabled
										class="form-control form-finanza form-control-sm color-01 bold text-center"
										currency="$"
										separator=","
										:precision="2"
										v-model="TAnioA"
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
										v-model="TTrimestre1"
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
										v-model="TTrimestre2"
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
										v-model="TTrimestre3"
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
										v-model="TTrimestre4"
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
										v-model="TTotalA"
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
	props: ["Lista", "Nombre"],
	data() {
		return {
			TAnioA: 0,
			TTrimestre1: 0,
			TTrimestre2: 0,
			TTrimestre3: 0,
			TTrimestre4: 0,
			TTotalA: 0,
			Decimal: 1
		};
	},
	methods: {},
	created() {},
	computed: {
		calcularValores() {
			this.TAnioA = 0;
			this.TTrimestre1 = 0;
			this.TTrimestre2 = 0;
			(this.TTrimestre3 = 0), (this.TTrimestre4 = 0);
			this.TTotalA = 0;
			for (var i = 0; i < this.Lista.length; i++) {
				var Uno = 0,
					Dos = 0,
					Tres = 0,
					Cuatro = 0;
				var mont = this.Lista[i].MontoAnterior;
				if (this.Lista[i].MontoAnterior != "") {
					this.TAnioA += parseFloat(this.Lista[i].MontoAnterior);
				}
				if (this.Lista[i].UnoT != "") {
					this.TTrimestre1 += parseFloat(this.Lista[i].UnoT);
					Uno = parseFloat(this.Lista[i].UnoT);
				}
				if (this.Lista[i].DosT != "") {
					this.TTrimestre2 += parseFloat(this.Lista[i].DosT);
					Dos = parseFloat(this.Lista[i].DosT);
				}
				if (this.Lista[i].TresT != "") {
					this.TTrimestre3 += parseFloat(this.Lista[i].TresT);
					Tres = parseFloat(this.Lista[i].TresT);
				}
				if (this.Lista[i].CuatroT != "") {
					this.TTrimestre4 += parseFloat(this.Lista[i].CuatroT);
					Cuatro = parseFloat(this.Lista[i].CuatroT);
				}

				this.Lista[i].MontoAnual =
					parseFloat(Uno) +
					parseFloat(Dos) +
					parseFloat(Tres) +
					parseFloat(Cuatro);

				if (this.Lista[i].MontoAnual != "") {
					this.TTotalA += parseFloat(this.Lista[i].MontoAnual);
				}
			}

			return "";
		}
	}
};
</script>
