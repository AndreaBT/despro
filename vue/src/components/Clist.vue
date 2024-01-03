<template>
	<div>
		<input type="hidden" :disabled="validate" />

		<CHead :oHead="Head">
			<template slot="component">
				<slot name="botonextra"> </slot>

				<button
					v-if="btnCli2"
					data-toggle="modal"
					data-target="#ModalNewUser"
					data-backdrop="static"
					type="button"
					class="btn btn-pink mb-2 mr-1"
				>
					Añadir
				</button>
			</template>
		</CHead>

		<div class="row justify-content-center mt-2">
			<div class="col-12 col-sm-12 col-md-12 col-lg-12">
				<slot name="contoleradicional"></slot>

				<div class="card card-01">
					<div class="row" v-if="this.FiltroC.Show">

						<div :class="Head.isCuentas ? 'col-md-6 col-lg-6': 'col-md-12 col-lg-12' ">
							<form class="form-inline">

								<div class="form-group mr-2">
									<input v-on:keyup="Filtrar"
										autocomplete="off"
										v-model="FiltroC.Nombre"
										type="text"
										class="form-control lup"
										id="textBusqueda"
										:placeholder="validateFiltros"/>
								</div>

								<div class="form-group  mr-2">
									<label class="mr-1">Filas &nbsp;</label>
									<select @change="Filtrar" v-model="FiltroC.Entrada" class="form-control">
										<option :value="10">10</option>
										<option :value="50">50</option>
										<option :value="100">100</option>
									</select>
								</div>

								<slot name="Filtros"> </slot>
							</form>
						</div>

						<div class="col-md-6 col-lg-6 text-right" v-if="Head.isCuentas">
							<form>
								<slot name="botonCuentas"></slot>
							</form>
						</div>
					</div>
					<!-- Solo cuando es modulo de cuentas -->
					<div v-if="Head.isCuentas" class="row">
						<div :class="Head.verFiltroCuentas ? 'col-12 col-sm-12 col-md-9 multi-1 col-lg-10 col-xl-10': 'col-12 col-sm-12 col-md-9 multi-1 col-lg-12 col-xl-12' ">
							<div class="table-responsive">
								<CLoader :pConfigLoad="ConfigLoad">
									<template slot="BodyFormLoad">
										<table class="table-01 text-nowrap mt-2">
											<thead>
												<slot name="header"></slot>
											</thead>
											<tbody>
												<slot name="body"></slot>
												<slot name="botones"> </slot>
											</tbody>
										</table>
									</template>
								</CLoader>
							</div>
						</div>
						<div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-2" v-if="Head.verFiltroCuentas">
							<slot name="FiltroCuentas"></slot>
						</div>
					</div>

					<!-- Cuando es cualquier catalogo -->
					<div v-else class="table-responsive">
						<CLoader :pConfigLoad="ConfigLoad">
							<template slot="BodyFormLoad">
								<table class="table-01 text-nowrap mt-2">
									<thead>
										<slot name="header"></slot>
									</thead>
									<tbody>
										<slot name="body"></slot>
										<slot name="botones"> </slot>
									</tbody>
								</table>
							</template>
						</CLoader>
					</div>

					<Pagina
						:Filtro="Filtro"
						:Entrada="FiltroC.Entrada"
						@Pagina="Filtrar"
					></Pagina>
				</div>
			</div>
		</div>

		<!--Fin del conenido-->
	</div>
</template>
<script>
import Pagina from "@/components/Cpagina.vue";
import CLoader from "./CLoader";
export default {
	name: "Clist",
	props: [
		"regresar",
		"Nombre",
		"Pag",
		"Total",
		"isModal",
		"pShowBtnAdd",
		"PNameButonNuevo",
		"ShowHead",
		"Filtro",
		"NameReturn",
		"btnCli",
		"Cuentas",
		"pConfigLoad"
	],
	components: {
		Pagina,
		CLoader
	},

	data() {
		return {
			Entrada: 50,
			ShowBtnAdd: true,
			NameButonNuevo: "Nuevo",
			Head: {
				ShowHead: true,
				Title: "Datos",
				BtnNewShow: true,
				BtnNewName: "Nuevo",
				isreturn: true,
				isModal: true,
				isEmit: true,
				Url: "",
				ObjReturn: "",
				NameReturn: "Regresar",
				isCuentas:false,
				verFiltroCuentas:false,
			},
			FiltroC: {
				Nombre: "",
				Entrada: 10,
				Placeholder: "Buscar..",
				Show: true
			},
			btnCli2: false,
			ConfigLoad:{
				ShowLoader: true,
				ClassLoad:  true,
			},
			TimeOut: null,



		};
	},
	methods: {
		Nuevo() {
			if (this.isModal == true) {
				this.bus.$emit("Nuevo", true);
			} else {
				this.bus.$emit("Nuevo");
			}
		},
		Regresar() {
			this.bus.$emit("Regresar");
		},
		Filtrar() {
			if (this.FiltroC.Entrada != this.Filtro.Entrada) {
				this.Filtro.Pagina = 1;
			}

			this.Filtro.Nombre = this.FiltroC.Nombre;
			this.Filtro.Entrada = this.FiltroC.Entrada;

			if(this.FiltroC.Nombre != '') {
				clearTimeout(this.TimeOut);

				this.TimeOut = setTimeout(() => {
					this.$emit("FiltrarC");
				}, 1000);

			} else {
				this.$emit("FiltrarC");
			}



		}
	},
	created() {
		/*
        if(this.PNameButonNuevo!=undefined){
            this.NameButonNuevo=this.PNameButonNuevo;
        }*/
	},
	computed: {
		validate() {
			if (this.pShowBtnAdd != undefined) {
				this.ShowBtnAdd = this.pShowBtnAdd;
				this.Head.BtnNewShow = this.pShowBtnAdd;
			}
			if (this.Nombre != undefined) {
				this.Head.Title = this.Nombre;
			}
			if (this.isModal != undefined) {
				this.Head.isModal = this.isModal;
			}

			if (this.PNameButonNuevo != undefined) {
				this.Head.BtnNewName = this.PNameButonNuevo;
			}
			if (this.regresar != undefined) {
				this.Head.isreturn = this.regresar;
			}
			if (this.ShowHead != undefined) {
				this.Head.ShowHead = this.ShowHead;
			}

			if (this.NameReturn != undefined) {
				this.Head.NameReturn = this.NameReturn;
			}
			if (this.Cuentas !== undefined) {
				this.Head.isCuentas = this.Cuentas.isCuentas;
				this.Head.verFiltroCuentas = this.Cuentas.verFiltros;
			}

			if (this.btnCli != undefined) {
				this.btnCli2 = this.btnCli;
			}

			if(this.pConfigLoad != undefined && this.pConfigLoad.ShowLoader != undefined){
				this.ConfigLoad.ShowLoader = this.pConfigLoad.ShowLoader;
			}else {
				this.ConfigLoad.ShowLoader = false;
			}

			if(this.pConfigLoad != undefined && this.pConfigLoad.ClassLoad != undefined){
				this.ConfigLoad.ClassLoad = this.pConfigLoad.ClassLoad;
			}else{
				this.ConfigLoad.ClassLoad = false;
			}

			return this.isModal;
		},
		validateFiltros() {
			if (this.Filtro != undefined) {
				if (this.Filtro.Nombre != undefined) {
					this.FiltroC.Nombre = this.Filtro.Nombre;
				}
				if (this.Filtro.Placeholder != undefined) {
					this.FiltroC.Placeholder = this.Filtro.Placeholder;
				}
				if (this.Filtro.Show != undefined) {
					this.FiltroC.Show = this.Filtro.Show;
				}
				if (this.Filtro.Entrada != undefined) {
					this.FiltroC.Entrada = this.Filtro.Entrada;
				}
			}

			return this.FiltroC.Placeholder;
		}
	}
};
</script>
