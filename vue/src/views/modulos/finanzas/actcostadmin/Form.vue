<template>
	<div>
		{{ calcula_total }}
		<CHead :oHead="Head"></CHead>
		<div class="row justify-content-start mt-3">
			<div class="col-12 col-sm-12 col-md-12 col-lg-12">
				<div class="card card-01">
					<div class="row">
						<div class="col-md-12 col-lg-12">
							<form class="form-inline justify-content-end">
								<label class="mr-1">Año</label>
								<select
									:disabled="Disabled"
									@change="get_Lista1(), get_Lista2()"
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

								<label class="mr-1">Mes</label>
								<select
									class="form-control mr-2"
									:disabled="Disabled"
									@change="get_Lista1(), get_Lista2(), get_Lista3()"
									v-model="Mes"
								>
									<option :value="1">Enero</option>
									<option :value="2">Febrero</option>
									<option :value="3">Marzo</option>
									<option :value="4">Abril</option>
									<option :value="5">Mayo</option>
									<option :value="6">Junio</option>
									<option :value="7">Julio</option>
									<option :value="8">Agosto</option>
									<option :value="9">Septiembre</option>
									<option :value="10">Octubre</option>
									<option :value="11">Noviembre</option>
									<option :value="12">Diciembre</option>
								</select>

								<button
									@click="Descargar"
									type="button"
									class="btn btn-bradcrumb btn-01 print mr-2 mt-1"
								>
									Imprimir
								</button>
								<button
									v-if="Tipo != 6"
									:disabled="Disabled"
									@click="Guardar"
									type="button"
									class="btn btn-bradcrumb btn-01 save mt-1"
								>
									<i
										v-show="Disabled"
										class="fa fa-spinner fa-pulse fa-1x fa-fw"
									></i>
									{{ txtSave }}
								</button>

								<button
									v-if="Tipo == 6"
									:disabled="Disabled"
									@click="GuardarSueldos"
									type="button"
									class="btn btn-bradcrumb btn-01 save mt-1"
								>
									<i
										v-show="Disabled"
										class="fa fa-spinner fa-pulse fa-1x fa-fw"
									></i>
									{{ txtSave }}
								</button>

								<button
									v-if="Tipo == 6"
									@click="NuevoEmpleado"
									type="button"
									class="btn btn-bradcrumb btn-01 mr-2 mt-1"
								>
									Nuevo Empleado
								</button>
							</form>
							<hr />
						</div>
					</div>

					<div class="row mt-2">
						<div class="col-12 col-sm-12 col-md-12 col-lg-12">
							<nav>
								<div class="nav nav-tabs nav-tabs-table">
									<b-tabs>
										<b-tab
											@click="get_Type(data.Tipo)"
											v-for="(data, index) in ListaFinanciera"
											:key="index"
											:title="data.Nombre"
										>
											<div class="tab-content tab-content-table">
												<div
													class="tab-pane fade show active"
													id="nav-dato"
													role="tabpanel"
													aria-labelledby="dato-tab"
												>
													<div class="row" v-if="data.Tipo != 6">
														<Tabla :Lista="Lista" :Nombre="data.Nombre" :Tipo="data.Tipo" :Gasto="GastosDirect" :Tabla1="Tabla1 " :TablaFinan1="TablaFinan1" ></Tabla>

														<Tabla
															v-show="data.Tipo == 2 || data.Tipo == 5"
															:Lista="Lista2"
															:Nombre="data.Nombre"
															:Tipo="data.Tipo"
															:Tabla2="Tabla2"
															:TablaFinan="TablaFinan"
														></Tabla>
													</div>
												</div>
											</div>
										</b-tab>
									</b-tabs>
								</div>
							</nav>
						</div>
					</div>

					<!--INICIO la tabla de los trabajadores-->

					<div v-if="Tipo == 6" class="tab-content tab-content-table">
						<div
							class="tab-pane fade show active"
							id="nav-dato-01"
							role="tabpanel"
							aria-labelledby="dato-tab-01"
						>
							<div class="table-finanzas mt-4">
								<b>Sueldo Personal Manual</b>
								<table class="table-fin-01">
									<!-- <div v-if="Tipo == 6" class="table-fixed-02">
                                <table class="table table-007"> -->
									<thead>
										<tr>
											<th class="sticky mediana text-center marca">
												<b>Personal</b>
											</th>
											<th class="mediana text-center">Sueldo Base</th>
											<th class="mediana text-center">Obligaciones (Ley)</th>
											<th class="blue-01 mediana text-center">Prestaciones</th>
											<th class="blue-01 mediana text-center">
												Comisiones y Bonos
											</th>
											<th class="blue-02 mediana text-center">Horas Extras</th>
											<th class="blue-02 mediana text-center">Descuento</th>
											<th class="blue-02 mediana text-center">
												Total Integrado
											</th>
											<th class="blue-02 mediana text-center">Eliminar</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(item, index) in ListaEmpleados" :key="index">
											<td class="sticky" style="width: 70%; height: 110%">
												<input
													type="text"
													class="form-control form-control-sm text-center"
													v-model="item.Nombre"
													placeholder="Nombre"
												/>
											</td>
											<td>
												<vue-numeric
													class="form-control form-control-sm text-center"
													currency="$"
													separator=","
													@input="operacion"
													:precision="2"
													v-model="item.Sueldo"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-control-sm text-center"
													currency="$"
													separator=","
													@input="operacion"
													:precision="2"
													v-model="item.Obligaciones"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-control-sm text-center"
													currency="$"
													separator=","
													@input="operacion"
													:precision="2"
													v-model="item.Prestaciones"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-control-sm text-center"
													currency="$"
													separator=","
													@input="operacion"
													:precision="2"
													v-model="item.ComisionesBonos"
													placeholder="$ 0.00"
												>
												</vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-control-sm text-center"
													currency="$"
													separator=","
													@input="operacion"
													:precision="2"
													v-model="item.Extras"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-control-sm text-center"
													currency="$"
													separator=","
													@input="operacion"
													:precision="2"
													v-model="item.Descuentos"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td class="text-center">
												<b
													><vue-numeric
														class="form-control form-control-sm text-center"
														currency="$"
														separator=","
														@input="operacion"
														:precision="2"
														v-model="item.Total"
														:readOnly="true"
														placeholder="$ 0.00"
													></vue-numeric
												></b>
											</td>
											<td class="text-center">
												<button
													@click="delete_mat(index)"
													type="button"
													class="btn-icon-02"
												>
													<i class="fas fa-trash"></i>
												</button>
											</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td class="color-01 bold sticky marca">Total General</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td class="text-center">
												<b>$ {{ TotalGeneral }}</b>
											</td>
											<td></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>

					<!--FIN la tabla de los trabajadores-->

					<!--INICIO la tabla de los trabajadores 2-->

					<div v-if="Tipo == 6" class="tab-content tab-content-table">
						<div
							class="tab-pane fade show active"
							id="nav-dato-02"
							role="tabpanel"
							aria-labelledby="dato-tab-02"
						>
							<div class="table-finanzas mt-4">
								<b>Sueldo Personal Cuentas por Pagar</b>
								<table class="table-fin-01">
									<!-- <div v-if="Tipo == 6" class="table-fixed-02">
                                <table class="table table-007"> -->
									<thead>
										<tr>
											<th class="sticky mediana text-center marca">
												<b>Proveedor</b>
											</th>
											<th class="mediana text-center">Sueldo Base</th>
											<th class="mediana text-center">Obligaciones (Ley)</th>
											<th class="blue-01 mediana text-center">Prestaciones</th>
											<th class="blue-01 mediana text-center">
												Comisiones y Bonos
											</th>
											<th class="blue-02 mediana text-center">Horas Extras</th>
											<th class="blue-02 mediana text-center">Descuento</th>
											<th class="blue-02 mediana text-center">
												Total Integrado
											</th>
										</tr>
									</thead>
									<tbody>
										<tr
											v-for="(item, index) in ListaEmpleadosCuentas"
											:key="index"
										>
											<td class="sticky" style="width: 70%; height: 110%">
												<input
													type="text"
													class="form-control form-control-sm text-center"
													v-model="item.Nombre"
													:disabled="true"
												/>
											</td>
											<td>
												<vue-numeric
													class="form-control form-control-sm text-center "
													currency="$"
													separator=","
													@input="operacion"
													:precision="2"
													v-model="item.Sueldo"
													:readOnly="true"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-control-sm text-center"
													currency="$"
													separator=","
													@input="operacion"
													:precision="2"
													v-model="item.Obligaciones"
													:readOnly="true"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-control-sm text-center"
													currency="$"
													separator=","
													@input="operacion"
													:precision="2"
													v-model="item.Prestaciones"
													:readOnly="true"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-control-sm text-center"
													currency="$"
													separator=","
													@input="operacion"
													:precision="2"
													v-model="item.ComisionesBonos"
													:readOnly="true"
													placeholder="$ 0.00"
												>
												</vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-control-sm text-center"
													currency="$"
													separator=","
													:precision="2"
													v-model="item.Extras"
													:readOnly="true"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td>
												<vue-numeric
													class="form-control form-control-sm text-center"
													currency="$"
													separator=","
													:precision="2"
													v-model="item.Descuentos"
													:readOnly="true"
													placeholder="$ 0.00"
												></vue-numeric>
											</td>
											<td class="text-center">
												<b
													><vue-numeric
														class="form-control form-control-sm text-center"
														currency="$"
														separator=","
														:precision="2"
														v-model="item.Total"
														:readOnly="true"
														placeholder="$ 0.00"
													></vue-numeric
												></b>
											</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td class="color-01 bold sticky marca ">Total General</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td class="text-center">
												<b>$ {{ TotalGeneralCuentas }}</b>
											</td>
											<td></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>

					<!--FIN la tabla de los trabajadores 2-->
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

import Tabla from "@/components/Ctablafinanciera2.vue";

export default {
	props: ["Id"],
	components: {
		Tabla
	},
	data() {
		return {
			Decimal: 1,
			planventas: {},
			Lista: [],
			Lista2: [],
			Head: {
				Title: "Actualizar Costos Admin",
				BtnNewShow: false,
				BtnNewName: "Nuevo",
				isreturn: true,
				isModal: false,
				isEmit: false,
				Url: "MenusFinanzas",
				ObjReturn: ""
			},
			IdConfigS: 0,
			IdTipoSubservicio: 0,
			Anio: 2020,
			Mes: 1,
			ListaAnios: [],
			ListaFinanciera: [
				{ Nombre: "Costo G & A", Tipo: 1 },
				{ Nombre: "Costo Depto. Venta", Tipo: 2 },
				{ Nombre: "Costo Depto. Oper.", Tipo: 3 },
				{ Nombre: "Costo Vehículo Oper.", Tipo: 4 },
				{ Nombre: "Costos Financieros", Tipo: 5 },
				{ Nombre: "Sueldos Personal Operativo", Tipo: 6 }
			],
			Tipo: 1,
			Disabled: false,
			Disabledsave: false,
			txtSave: "Guardar",

			ListaEmpleados: [],
			ListaEmpleadosCuentas: [],
			TotalGeneral: 0,
			TotalGeneralCuentas: 0,
			GastosDirect:0,
			Tabla1:0,
			Tabla2:0,
			TablaFinan:0,
			TablaFinan1:0
		};
	},
	methods: {
		operacion() {
			let TotalCnt = 0;
			let TotalCuentasCnt = 0;

			this.ListaEmpleados.forEach(element => {
				if (element.Total > 0) {
					element.Total = 0;
				}
				let TotalInd = 0;

				TotalInd =
					parseFloat(element.Sueldo) +
					parseFloat(element.Obligaciones) +
					parseFloat(element.Prestaciones) +
					parseFloat(element.ComisionesBonos) +
					parseFloat(element.Extras) -
					parseFloat(element.Descuentos);
				element.Total = parseFloat(TotalInd).toFixed(2);

				TotalCnt += element.Total;
			});

			this.ListaEmpleadosCuentas.forEach(element => {
				if (element.Total > 0) {
					element.Total = 0;
				}
				let TotalCuentas = 0;

				TotalCuentas =
					parseFloat(element.Sueldo) +
					parseFloat(element.Obligaciones) +
					parseFloat(element.Prestaciones) +
					parseFloat(element.ComisionesBonos) +
					parseFloat(element.Extras) -
					parseFloat(element.Descuentos);
				element.Total = parseFloat(TotalCuentas).toFixed(2);

				TotalCuentasCnt += element.Total;
			});

			this.TotalGeneralCuentas = parseFloat(TotalCuentasCnt).toFixed(2);
			this.TotalGeneral = parseFloat(TotalCnt).toFixed(2);
		},
		delete_mat(index) {
			this.ListaEmpleados.splice(index, 1);
		},
		NuevoEmpleado() {
			let arrMul = {
				Nombre: "",
				Sueldo: 0,
				Obligaciones: 0,
				Prestaciones: 0,
				ComisionesBonos: 0,
				Extras: 0,
				Descuentos: 0,
				Total: 0
			};

			this.ListaEmpleados.push(arrMul);
		},
		get_anios() {
			this.Disabled = true;
			this.$http
				.get("funciones/getanios", {
					params: {}
				})
				.then(res => {
					this.ListaAnios = res.data.ListaAnios;
					this.Anio = res.data.AnioActual;
					this.Mes = parseInt(res.data.MesActual);
					this.get_Lista1();
					this.get_Lista2();
					this.get_Lista3();
				});
		},
		get_Lista1() {
			this.Disabled = true;
			if(this.Tipo != 6){
				this.$http
				.get("actualizacionCostos/get", {
					params: {
						Anio: this.Anio,
						Mes: this.Mes,
						Tipo: this.Tipo,
						TipoBusqueda: 1
					}
				})
				.then(res => {
					this.Lista = res.data.data.detalle.row;
					this.Disabled = false;
				});
			}
		},
		get_Lista2() {
			if (this.Tipo == 2 || this.Tipo == 5) {
				this.Disabled = true;
				this.$http
					.get("actualizacionCostos/get", {
						params: {
							Anio: this.Anio,
							Mes: this.Mes,
							Tipo: this.Tipo,
							TipoBusqueda: 2
						}
					})
					.then(res => {
						this.Lista2 = res.data.data.detalle.row;
						this.Disabled = false;

						if(this.Tipo==2){
							this.GastosDirect=1;
						}
					});
			}
		},
		get_Type(Type) {
			if (this.Disabled == false) {
				if (Type != 6) {
					this.Tipo = Type;
					this.get_Lista1();
					this.get_Lista2();
				} else {
					this.Tipo = Type;
				}
			}
		},
		Guardar() {
			if (this.Lista.length > 0) {
				if (this.Tipo != 2 && this.Tipo != 5) {
					this.Lista2 = [];
				}
				this.Disabled = true;
				this.Disabledsave = true;
				this.$http
					.post("actualizacionCostos/post", {
						Detalle: this.Lista,
						Detalle2: this.Lista2,
						Anio: this.Anio,
						Tipo: this.Tipo,
						Mes: this.Mes
					})
					.then(res => {
						this.Disabled = false;
						this.Disabledsave = false;

						this.get_Lista1();
						this.get_Lista2();
						this.$toast.success("Información Guardada");
					})
					.catch(err => {
						this.Disabled = false;
						this.$toast.error("Ocurrio un error al agregar los datos");
					});
			}
		},
		GuardarSueldos() {
			if (this.ListaEmpleados.length == 0) {
				this.$toast.warning("Debe agregar al menos a 1 personal");
				return false;
			}

			this.Disabled = true;
			this.Disabledsave = true;
			let falta = 0;

			this.ListaEmpleados.forEach(element => {
				if (element.Nombre === "") {
					falta = 1;
					this.$toast.warning("Complete todos los campos para poder guardar");
					this.Disabled = false;
					this.Disabledsave = false;
					return false;
				}
			});

			if (falta == 0) {
				this.$http
					.post("personaloperativo/post", {
						Detalle: this.ListaEmpleados,
						Anio: this.Anio,
						Mes: this.Mes
					})
					.then(res => {
						this.Disabled = false;
						this.Disabledsave = false;
						this.get_Lista3();
						this.$toast.success("Información Guardada");
					})
					.catch(err => {
						this.Disabled = false;
						this.$toast.error("Ocurrio un error al agregar los datos");
					});
			}
		},
		get_Lista3() {
			this.ListaEmpleados = [];
			this.Disabled = true;
			this.$http
				.get("personaloperativo/get", {
					params: {
						Anio: this.Anio,
						Mes: this.Mes
					}
				})
				.then(res => {
					this.ListaEmpleados = res.data.data.empleados;
					this.ListaEmpleadosCuentas = res.data.data.empleadoscuentas;
					this.Disabled = false;
				});
		},
		operacioninicial() {
			this.TotalGeneral = 0;

			this.ListaEmpleados.forEach(element => {
				this.TotalGeneral += element.Total;
			});
		},
		Descargar() {
			var url = "costosga";
			if (this.Tipo == 2) {
				url = "costoventa";
			} else if (this.Tipo == 3) {
				url = "costooperacion";
			} else if (this.Tipo == 4) {
				url = "costovehiculo";
			} else if (this.Tipo == 5) {
				url = "costofinanciero";
			} else if (this.Tipo == 6) {
				url = "sueldospersonalOp";
			}

			this.$http
				.get("reporte/" + url, {
					responseType: "arraybuffer",
					params: {
						key: 1,
						Anio: this.Anio,
						Mes: this.Mes
					}
				})
				.then(response => {
					let pdfContent = response.data;
					let file = new Blob([pdfContent], { type: "application/pdf" });
					var fileUrl = URL.createObjectURL(file);

					window.open(fileUrl);
				});
		}
	},
	created() {
		this.get_anios();

		this.bus.$off("Regresar");
		this.bus.$on("Regresar", () => {
			this.$router.push({ name: "MenusFinanzas" });
		});
	},
	mounted() {},
	computed: {
		calcula_total() {
			var Total = 0;
			var TotalCuentas = 0;

			this.ListaEmpleados.forEach(element => {
				if (element.Total != "") {
					Total += parseFloat(element.Total);
				}
			});

			this.ListaEmpleadosCuentas.forEach(element => {
				if (element.Total != "") {
					TotalCuentas += parseFloat(element.Total);
				}
			});

			this.TotalGeneral = Total.toFixed(2);
			this.TotalGeneralCuentas = TotalCuentas.toFixed(2);
			//return Total.toFixed(0);
		}
	}
};
</script>
