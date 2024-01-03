<template>
	<div class="col-12 col-md-12 col-lg-12 col-xl-12 mb-2">
		<b-overlay :show="isOverlay" rounded="sm" spinner-variant="primary">
			<div class="row">
				<div class="col-12">
					<div class="card card-grafica">
						<div class="card-body">
							<div class="filtro-grafic" id="grafica_005" v-if="isVisible">
								<div class="row justify-content-start">
									<div class="col-12 col-md-12 col-lg-12 col-xl-12">
										<form class="form-inline">
											<label class="mr-2">Año</label>
											<select
												@change="get_listdata"
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
												@change="get_listdata"
												v-model="Grafica1.Mes"
												class="form-control form-control-sm"
											>
												<option value="12">YTD</option>
												<option value="0">Enero</option>
												<option value="1">Febrero</option>
												<option value="2">Marzo</option>
												<option value="3">Abril</option>
												<option value="4">Mayo</option>
												<option value="5">Junio</option>
												<option value="6">Julio</option>
												<option value="7">Agosto</option>
												<option value="8">Septiembre</option>
												<option value="9">Octubre</option>
												<option value="10">Noviembre</option>
												<option value="11">Diciembre</option>
											</select>
										</form>
										<button
											type="button"
											class="btn close abs_01 mt-2"
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
										Estado de Resultados (Porcentajes de Costo)
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
							<!-- <div class="row">
                            <div class="col-md-4 col-lg-3 col-xl-2">
                                    <div class="card card-numer">
                                        <div class="card-body">
                                            <div class="form-row align-items-center">
                                                <div class="col mr-2">
                                                    <p class="titulo-dash-04">Facturación</p>
                                                    <p  class="titulo-dash-05 text-shadow">${{Facturacion}}</p>
                                                </div>
                                                <div class="col-auto text-right">
                                                    <div class="icon-dash"><i class="fad fa-coins"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>-->

							<div class="form-row">
								<div class="col-xs-15 col-sm-15 col-md-15 col-lg-15">
									<div class="card card-numer">
										<div class="card-body">
											<div class="form-row align-items-center">
												<div class="col mr-2">
													<p class="titulo-dash-04">Gross Profit</p>
													<p class="titulo-dash-03 text-shadow">
														{{ Groosprofit }}%
													</p>
												</div>
												<div class="col-auto">
													<div class="icon-dash">
														<i class="fad fa-badge-percent"></i>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-xs-15 col-sm-15 col-md-15 col-lg-15">
									<div class="card card-numer">
										<div class="card-body">
											<div class="form-row align-items-center">
												<div class="col mr-2">
													<p class="titulo-dash-04">G &amp; A</p>
													<p class="titulo-dash-03 text-shadow">{{ GyA }}%</p>
												</div>
												<div class="col-auto">
													<div class="icon-dash">
														<i class="fad fa-coin"></i>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-xs-15 col-sm-15 col-md-15 col-lg-15">
									<div class="card card-numer">
										<div class="card-body">
											<div class="form-row align-items-center">
												<div class="col mr-2">
													<p class="titulo-dash-04">Ventas</p>
													<p class="titulo-dash-03 text-shadow">
														{{ Ventas }}%
													</p>
												</div>
												<div class="col-auto">
													<div class="icon-dash">
														<i class="fad fa-chart-line"></i>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-xs-15 col-sm-15 col-md-15 col-lg-15">
									<div class="card card-numer">
										<div class="card-body">
											<div class="form-row align-items-center">
												<div class="col mr-2">
													<p class="titulo-dash-04">Costos Financieros</p>
													<p class="titulo-dash-03 text-shadow">
														{{ CostosFinancieros }}%
													</p>
												</div>
												<div class="col-auto">
													<div class="icon-dash">
														<i class="fad fa-chart-line-down"></i>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-xs-15 col-sm-15 col-md-15 col-lg-15">
									<div class="card card-numer">
										<div class="card-body">
											<div class="form-row align-items-center">
												<!---AQUÍ EMPIEZA OPERATION PROFIT GRÁFICA-->
												<div class="col mr-2">
													<p class="titulo-dash-04">Operation Profit (EBIT)</p>
													<p class="titulo-dash-03 text-shadow">
														{{ OperationProfit }}%
													</p>
												</div>
												<div class="col-auto">
													<div class="icon-dash">
														<i class="fad fa-coin"></i>
													</div>
												</div>
											</div>
										</div>
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
	name: "EstadoResultados",
	data() {
		return {
			//urlApi:'finanzasgraf/porcentajecostos',
			urlApi: "finanzasPorcentaje/get",
			Costos: {
				DiesTDV: "",
				DiesTGA: "",
				GrossP: ""
			},
			ListaAnios: [],
			Grafica1: {
				Anio: "2019",
				Mes: ""
			},
			isVisible: false,

			Facturacion: 0,
			Groosprofit: 0,
			GyA: 0,
			Ventas: 0,
			CostosFinancieros: 0,
			OperationProfit: 0,
			isOverlay: true
		};
	},
	created() {
		this.bus.$off("Ocultar5");
		this.bus.$on("Ocultar5", data => {
			this.Ocultar5(data);
		});

		this.Anios();
	},
	methods: {
		get_YTD() {
			this.isOverlay = true;
			this.$http
				.get(
					"financieroantiguo/get",
					{
						params: {
							IdConfigS: "",
							IdTipoServ: "",
							Anio: this.Grafica1.Anio,
							Mes: 12,
							IdContrato: "",
							Tipo: 2,
							isYTD: 1
						}
					}
				)
				.then(res => {
					this.isOverlay = false;

					const valores = res.data.data.rowconfig;

					let GA = parseFloat(valores.ga6).toFixed(1);
					GA = isNaN(GA) ? 0 : GA;

					let CF = parseFloat(valores.ie6).toFixed(1);
					CF = isNaN(CF) ? 0 : CF;
					CF = -1 * CF;

					let OP = parseFloat(valores.np6).toFixed(1);
					OP = isNaN(OP) ? 0 : OP;

					let GP = parseFloat(valores.GPAnioActualPorcen).toFixed(1);
					GP = isNaN(GP) ? 0 : GP;

					let CO = parseFloat(valores.cv6).toFixed(1);
					CO = isNaN(CO) ? 0 : CO;

					this.GyA                = GA;
					this.Groosprofit        = GP;
					this.Ventas             = CO;
					this.CostosFinancieros  = CF;
					this.OperationProfit    = OP;
				})
				.catch(e => {
					{
						this.isOverlay = false;
					}
				});
		},

		get_listdata() {
			if (parseInt(this.Grafica1.Mes) === 12) {
				this.get_YTD();
			} else {
				this.isOverlay = true;
				this.$http
					.get("financieroantiguo/get", {
						params: {
							IdConfigS: "",
							IdTipoServ: "",
							Anio: this.Grafica1.Anio,
							Mes: this.Grafica1.Mes,
							IdContrato: "",
							Tipo: 2
						}
					})
					.then(res => {
						this.isOverlay = false;

						const valores = res.data.data.rowconfig;

						///////////////////////////////////

						let GAE = parseFloat(valores.ga10).toFixed(1);
						GAE = isNaN(GAE) ? 0 : GAE;

						let CFE = parseFloat(valores.ie10).toFixed(1);
						CFE = isNaN(CFE) ? 0 : CFE;
						CFE = -1 * CFE;

						let OPE = parseFloat(valores.np10).toFixed(1);
						OPE = isNaN(OPE) ? 0 : OPE;

						let GPE = parseFloat(valores.GPMesActualPorcen).toFixed(1);
						GPE = isNaN(GPE) ? 0 : GPE;

						let COE = parseFloat(valores.cv10).toFixed(1);
						COE = isNaN(COE) ? 0 : COE;

						this.Groosprofit        = GPE;
						this.GyA                = GAE;
						this.Ventas             = COE;
						this.CostosFinancieros  = CFE;
						this.OperationProfit    = OPE;
					})
					.catch(e => {
						this.isOverlay = false;
					});
			}
		},

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
				this.Grafica1.Mes = 12;
				this.get_YTD();
			});
		},
		async Lista() {
			await this.$http
				.get(this.urlApi, {
					params: { Mes: this.Grafica1.Mes, Anio: this.Grafica1.Anio }
				})
				.then(res => {
					this.Costos = res.data.data;
				});
		},

		Ocultar5(data) {
			this.isVisible = data;
		}
	}
};
</script>

<style></style>
