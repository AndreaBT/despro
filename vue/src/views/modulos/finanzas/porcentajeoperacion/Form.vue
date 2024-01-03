<template>
	<div>
		{{ calcularValores }}
		<CHead :oHead="Head"></CHead>
		<div class="row justify-content-start mt-3">
			<div class="col-12 col-sm-12 col-md-12 col-lg-12">
				<div class="card card-01">
					<div class="row">
						<div class="col-md-12 col-lg-12">
							<form class="form-inline justify-content-center">
								<label class="mr-1">Servicio </label>
								<select
									@change="ListaSubtipo()"
									v-model="IdConfigS"
									class="form-control mr-2"
								>
									<option
										v-for="(item, index) in ListaServicios"
										:key="index"
										:value="item.IdConfigS"
										>{{ item.Nombre }}</option
									>
								</select>
								<label class="mr-1">Subtipo de Servicio </label>
								<select
									@change="get_listdata()"
									v-model="IdTipoSubservicio"
									class="form-control mr-2"
								>
									<option :value="''">Todos</option>
									<option
										v-for="(item, index) in ListaTipoServicio"
										:key="index"
										:value="item.IdTipoSer"
										>{{ item.Concepto }}</option
									>
								</select>
								<label class="mr-1">Año </label>

								<select
									:disabled="loading"
									@change="get_listdata"
									v-model="Anio"
									class="form-control mr-2"
								>
									<option
										v-for="(item, index) in ListaAnios"
										:key="index"
										:value="item"
										>{{ item }}</option
									>
								</select>
								<button
									:disabled="loading"
									@click="Guardar"
									type="button"
									class="btn btn-01"
								>
									<i
										v-show="loading"
										class="fa fa-spinner fa-pulse fa-1x fa-fw"
									></i>
									<i class="fa fa-plus-circle"></i>
									{{ txtSave }}
								</button>
							</form>
						</div>
					</div>
					<!--FIN FILTROS--->
					<div class="row mt-2">
						<div class="col-12 col-sm-12 col-md-12 col-lg-12">
							<div class="table-porcentaje">
								<table class="table-por-01">
									<thead>
										<tr>
											<th scope="col" class="sticky mediana marca">
												<b>Descripción</b>
											</th>
											<th scope="col" class="mediana text-center">
												Año Anterior
											</th>
											<th scope="col" class="blue-01 mediana text-center">
												Porcentaje
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
											<th scope="col" class="blue-02 mediana text-center">
												Porcentaje
											</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(item, index) in ListaDetalle" :key="index">
											<td class="sticky">
												<b>{{ item.Nombre }}</b>
											</td>

											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm text-center"
													:disabled="IdTipoSubservicio == '' ? true : false"
													style="width:150px;"
													currency="$"
													separator=","
													:precision="2"
													v-model="item.AnioAnterior"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm text-center"
													disabled
													currency="%"
													currency-symbol-position="suffix"
													separator=","
													:precision="2"
													v-model="item.PorcenAnioAnte"
													placeholder="0%"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm text-center"
													disabled
													currency="$"
													separator=","
													:precision="2"
													v-model="item.PrimerT"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm text-center"
													disabled
													currency="$"
													separator=","
													:precision="2"
													v-model="item.SegundoT"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm text-center"
													disabled
													currency="$"
													separator=","
													:precision="2"
													v-model="item.TercerT"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm text-center"
													disabled
													currency="$"
													separator=","
													:precision="2"
													v-model="item.CuartoT"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm text-center"
													disabled
													currency="$"
													separator=","
													:precision="2"
													v-model="item.TotalAnual"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm text-center"
													:disabled="
														IdTipoSubservicio == ''
															? true
															: false || item.PorcenAnual == '100'
															? true
															: false
													"
													style="width:150px;"
													currency="%"
													currency-symbol-position="suffix"
													separator=","
													:precision="2"
													v-model="item.PorcenAnual"
													placeholder="0% "
												></vue-numeric>
											</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td class="color-01 bold sticky marca">
												Costo Operacional
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm color-01 bold text-center"
													disabled
													currency="$"
													separator=","
													:precision="2"
													v-model="TotalAnioAntOp"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm color-01 bold text-center"
													disabled
													currency="%"
													currency-symbol-position="suffix"
													separator=","
													:precision="2"
													v-model="PorcenAnteriorOp"
													placeholder="% 0"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm color-01 bold text-center"
													disabled
													currency="$"
													separator=","
													:precision="2"
													v-model="Trimestre1Op"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm color-01 bold text-center"
													disabled
													currency="$"
													separator=","
													:precision="2"
													v-model="Trimestre2Op"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm color-01 bold text-center"
													disabled
													currency="$"
													separator=","
													:precision="2"
													v-model="Trimestre3Op"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm color-01 bold text-center"
													disabled
													currency="$"
													separator=","
													:precision="2"
													v-model="Trimestre4Op"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm color-01 bold text-center"
													disabled
													currency="$"
													separator=","
													:precision="2"
													v-model="TotalAnualOp"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm color-01 bold text-center"
													disabled
													currency="%"
													currency-symbol-position="suffix"
													separator=","
													:precision="2"
													v-model="PorcentajeAnualOp"
													placeholder="% 0"
												></vue-numeric>
											</td>
										</tr>

										<tr>
											<td class="color-01 bold sticky marca">Gross Profit</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm color-01 bold text-center"
													disabled
													currency="$"
													separator=","
													:precision="2"
													v-model="TotalAnioAntGp"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm color-01 bold text-center"
													disabled
													currency="%"
													currency-symbol-position="suffix"
													separator=","
													:precision="2"
													v-model="PorcenAnteriorGp"
													placeholder="% 0"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm color-01 bold text-center"
													disabled
													currency="$"
													separator=","
													:precision="2"
													v-model="Trimestre1Gp"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm color-01 bold text-center"
													disabled
													currency="$"
													separator=","
													:precision="2"
													v-model="Trimestre2Gp"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm color-01 bold text-center"
													disabled
													currency="$"
													separator=","
													:precision="2"
													v-model="Trimestre3Gp"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm color-01 bold text-center"
													disabled
													currency="$"
													separator=","
													:precision="2"
													v-model="Trimestre4Gp"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm color-01 bold text-center"
													disabled
													currency="$"
													separator=","
													:precision="2"
													v-model="TotalAnualGp"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-finanza form-control-sm color-01 bold text-center"
													disabled
													currency="%"
													currency-symbol-position="suffix"
													separator=","
													:precision="2"
													v-model="PorcentajeAnualGp"
													placeholder="% 0"
												></vue-numeric>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import Cbtnsave from "@/components/Cbtnsave.vue";
import Cvalidation from "@/components/Cvalidation.vue";
import Modal from "@/components/Cmodal.vue";
import Clist from "@/components/Clist.vue";

import Plan from "@/views/modulos/finanzas/planventas/Plan.vue";

export default {
	props: ["Id"],
	components: {
		Plan
	},
	data() {
		return {
			planventas: {},
			ListaServicios: [],
			ListaDetalle: [],
			ListaTipoServicio: [],

			Head: {
				Title: "Porcentaje de Operación",
				BtnNewShow: false,
				BtnNewName: "Nuevo",
				isreturn: true,
				isModal: false,
				isEmit: false,
				Url: "SubMenusFinanzas",
				ObjReturn: ""
			},
			IdConfigS: 0,
			IdTipoSubservicio: "",
			Anio: 2020,

			TotalAnioAntOp: "",
			PorcenAnteriorOp: "",
			Trimestre1Op: "",
			Trimestre2Op: "",
			Trimestre3Op: "",
			Trimestre4Op: "",
			TotalAnualOp: "",
			PorcentajeAnualOp: "",

			TotalAnioAntGp: "",
			PorcenAnteriorGp: "",
			Trimestre1Gp: "",
			Trimestre2Gp: "",
			Trimestre3Gp: "",
			Trimestre4Gp: "",
			TotalAnualGp: "",
			PorcentajeAnualGp: "",
			loading: false,
			txtSave: "Guardar",
			Decimal: 1,
			ListaAnios: []
		};
	},
	methods: {
		get_anios() {
			this.loading = true;
			this.$http
				.get("funciones/getanios", {
					params: {}
				})
				.then(res => {
					this.ListaAnios = res.data.ListaAnios;
					this.Anio = res.data.AnioActual;

					this.get_lisServicios();
					this.loading = false;
				});
		},
		get_lisServicios() {
			this.$http
				.get("baseactual/get", {
					params: { RegEstatus: "A", Facturable: "S" }
				})
				.then(res => {
					this.ListaServicios = res.data.data.lista;
					this.IdConfigS = this.ListaServicios[0].IdConfigS;
					this.ListaSubtipo();
				});
		},
		async ListaSubtipo() {
			if (this.IdConfigS > 0) {
				this.IdTipoSubservicio = "";
				await this.$http
					.get("tiposervicio/get", {
						params: {
							RegEstatus: "A",
							IdConfigS: this.IdConfigS,
							IdTipoServ: this.IdTipoServ
						}
					})
					.then(res => {
						this.ListaTipoServicio = res.data.data.tiposervicio;
						this.get_listdata();
					});
			}
		},
		get_listdata() {
			if (this.IdConfigS > 0) {
				this.$http
					.get("porcentajeoper/get", {
						params: {
							IdConfigS: this.IdConfigS,
							IdTipoServ: this.IdTipoSubservicio,
							Anio: this.Anio
						}
					})
					.then(res => {
						this.ListaDetalle = res.data.data.detalle;
					});
			}
		},
		async Guardar() {
			if (this.ListaDetalle.length > 0) {
				this.loading = true;
				this.$http
					.post("porcentajeoper/post", {
						ListaDetalle: this.ListaDetalle,
						IdConfigS: this.IdConfigS,
						IdTipoServ: this.IdTipoSubservicio,
						Anio: this.Anio
					})
					.then(res => {
						this.loading = false;
						this.$toast.success("Información Guardada");
						this.get_listdata();
					})
					.catch(err => {
						this.loading = false;
						this.$toast.error("Ocurrio un error al agregar los datos");
					});
			}
		}
	},
	created() {
		this.get_anios();
		this.bus.$off("Regresar");
		this.bus.$on("Regresar", () => {
			this.$router.push({ name: "SubMenusFinanzas" });
		});
	},
	mounted() {},
	computed: {
		calcularValores() {
			var TAnterior = 0;
			var TAnteriorP = 0;
			var TTrim1 = 0;
			var TTrim2 = 0;
			var TTrim3 = 0;
			var TTrim4 = 0;
			var TAnual = 0;
			var TPorcenA = 0;

			var Reg1 = 0;
			var Reg2 = 0;
			var Reg3 = 0;
			var Reg4 = 0;
			var Reg5 = 0;
			var Reg6 = 0;
			var Reg7 = 0;
			var Reg8 = 0;

			for (var i = 0; i < this.ListaDetalle.length; i++) {
				if (i > 0) {
					var porcentajeanual = this.ListaDetalle[i].PorcenAnual;
					var AnioAnterior = this.ListaDetalle[i].AnioAnterior;
					if (porcentajeanual == "") {
						porcentajeanual = 0;
					}
					if (AnioAnterior == "") {
						AnioAnterior = 0;
					}

					//Trimestres
					var Trimestre1 = (
						(parseFloat(porcentajeanual) * this.ListaDetalle[0].PrimerT) /
						100
					).toFixed(0);
					var Trimestre2 = (
						(parseFloat(porcentajeanual) * this.ListaDetalle[0].SegundoT) /
						100
					).toFixed(0);
					var Trimestre3 = (
						(parseFloat(porcentajeanual) * this.ListaDetalle[0].TercerT) /
						100
					).toFixed(0);
					var Trimestre4 = (
						(parseFloat(porcentajeanual) * this.ListaDetalle[0].CuartoT) /
						100
					).toFixed(0);

					var TotalAnual =
						parseFloat(Trimestre1) +
						parseFloat(Trimestre2) +
						parseFloat(Trimestre3) +
						parseFloat(Trimestre4);

					this.ListaDetalle[i].PrimerT = Trimestre1;
					this.ListaDetalle[i].SegundoT = Trimestre2;
					this.ListaDetalle[i].TercerT = Trimestre3;
					this.ListaDetalle[i].CuartoT = Trimestre4;
					this.ListaDetalle[i].TotalAnual = TotalAnual;

					//Anio Anterior
					var PorcentajeAnte = 0;
					if (
						this.ListaDetalle[0].AnioAnterior != "" &&
						this.ListaDetalle[0].AnioAnterior != "0" &&
						this.ListaDetalle[0].AnioAnterior != 0
					) {
						PorcentajeAnte =
							(parseFloat(AnioAnterior) * 100) / parseFloat(this.ListaDetalle[0].AnioAnterior);

							
					}

					this.ListaDetalle[i].PorcenAnioAnte = PorcentajeAnte.toFixed(1);

					//Total costo operacional
					TAnterior += parseFloat(AnioAnterior);
					TAnteriorP += parseFloat(PorcentajeAnte);
					TTrim1 += parseFloat(Trimestre1);
					TTrim2 += parseFloat(Trimestre2);
					TTrim3 += parseFloat(Trimestre3);
					TTrim4 += parseFloat(Trimestre4);
					TAnual += parseFloat(TotalAnual);
					TPorcenA += parseFloat(porcentajeanual);
				} else {
					Reg1 = this.ListaDetalle[i].AnioAnterior;
					if (Reg1 == "") {
						Reg1 = 0;
					}
					Reg2 = this.ListaDetalle[i].PorcenAnual;
					if (Reg2 == "") {
						Reg2 = 0;
					}
					Reg3 = this.ListaDetalle[0].PrimerT;
					if (Reg3 == "") {
						Reg3 = 0;
					}
					Reg4 = this.ListaDetalle[0].SegundoT;
					if (Reg4 == "") {
						Reg4 = 0;
					}
					Reg5 = this.ListaDetalle[0].TercerT;
					if (Reg5 == "") {
						Reg5 = 0;
					}
					Reg6 = this.ListaDetalle[0].CuartoT;
					if (Reg6 == "") {
						Reg6 = 0;
					}
					Reg7 = this.ListaDetalle[0].TotalAnual;
					if (Reg7 == "") {
						Reg7 = 0;
					}

					Reg8 = 100;
				}
			}
			

			//costo operacional
			this.TotalAnioAntOp = TAnterior;
			this.PorcenAnteriorOp = TAnteriorP;
			this.Trimestre1Op = TTrim1;
			this.Trimestre2Op = TTrim2;
			this.Trimestre3Op = TTrim3;
			this.Trimestre4Op = TTrim4;
			this.TotalAnualOp = TAnual;
			this.PorcentajeAnualOp = TPorcenA;

			this.TotalAnioAntGp = parseFloat(Reg1) - parseFloat(TAnterior);
			this.PorcenAnteriorGp = parseFloat(Reg8) - parseFloat(TAnteriorP);
			this.Trimestre1Gp = parseFloat(Reg3) - parseFloat(TTrim1);
			this.Trimestre2Gp = parseFloat(Reg4) - parseFloat(TTrim2);
			this.Trimestre3Gp = parseFloat(Reg5) - parseFloat(TTrim3);
			this.Trimestre4Gp = parseFloat(Reg6) - parseFloat(TTrim4);
			this.TotalAnualGp = parseFloat(Reg7) - parseFloat(TAnual);
			this.PorcentajeAnualGp = parseFloat(Reg8) - parseFloat(TPorcenA);
		}
	}
};
</script>
