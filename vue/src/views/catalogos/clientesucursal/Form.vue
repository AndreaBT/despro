<template>
	<div>
		<div class="row">
			<template v-if="showConten">
				<div class="modal-body form-cotizacion">
					<div class="form-group">
						<div class="form-check form-check-inline">
							<div class="checkbox">
								<label>
									<input
										type="checkbox"
										v-model="checked"
										@change="get_DatosCli"
										name="optionsCheckboxes"
									/><span class="checkbox-material-green"
										><span class="check"></span
									></span>
									Utilizar Datos Cliente
								</label>
							</div>
						</div>
						<div class="form-check form-check-inline">
							<div class="checkbox">
								<label>
									<input
										type="checkbox"
										v-model="checkedScanning"
										@change="get_scann"
										:checked="checkedScanning"
										name="optionsCheckboxes"
									/><span class="checkbox-material-green"
										><span class="check"></span
									></span>
									Usar Scanning
								</label>
							</div>
						</div>
						<hr />
					</div>
					<div class="form-group form-row">
						<div class="col-md-6 col-lg-6 col-xl-6">
							<label>Nombre</label>
							<input
								:disabled="disableHouseAccount"
								type="text"
								v-model="clientesucursal.Nombre"
								class="form-control form-control-sm"
							/>
							<label id="lblmsuser" style="color:red"
								><Cvalidation
									v-if="this.errorvalidacion.Nombre"
									:Mensaje="errorvalidacion.Nombre[0]"
								></Cvalidation
							></label>
						</div>
					</div>
					<div class="form-group form-row">
						<div class="col-md-9 col-lg-9 col-xl-9">
							<label>Dirección</label>
							<textarea
								v-model="clientesucursal.Direccion"
								class="form-control form-control-sm"
								rows="1"
							></textarea>
							<label id="lblmsuser" style="color:red"
								><Cvalidation
									v-if="this.errorvalidacion.Direccion"
									:Mensaje="errorvalidacion.Direccion[0]"
								></Cvalidation
							></label>
						</div>
						<div class="col-md-3 col-lg-3 col-xl-3">
							<label>Distancia Aproximada (KM)</label>
							<input
								type="text"
								v-model="clientesucursal.DistanciaAprox"
								class="form-control form-control-sm"
							/>
							<!--<vue-numeric    :minus="false" class="form-control form-control-sm"  currency="" separator="," :precision="0" v-model="clientesucursal.DistanciaAprox"></vue-numeric>--->
							<label id="lblmsuser" style="color:red"
								><Cvalidation
									v-if="this.errorvalidacion.DistanciaAprox"
									:Mensaje="errorvalidacion.DistanciaAprox[0]"
								></Cvalidation
							></label>
						</div>
					</div>
					<div class="form-group form-row">
						<div class="col-md-6 col-lg-6 col-xl-6">
							<label>Ciudad</label>
							<input
								type="text"
								v-model="clientesucursal.Ciudad"
								class="form-control form-control-sm"
							/>
							<label id="lblmsuser" style="color:red"
								><Cvalidation
									v-if="this.errorvalidacion.Ciudad"
									:Mensaje="errorvalidacion.Ciudad[0]"
								></Cvalidation
							></label>
						</div>
						<div class="col-md-6 col-lg-6 col-xl-6">
							<label>Teléfono</label>
							<input
								type="text"
								v-model="clientesucursal.Telefono"
								class="form-control form-control-sm"
							/>
							<label id="lblmsuser" style="color:red"
								><Cvalidation
									v-if="this.errorvalidacion.Telefono"
									:Mensaje="errorvalidacion.Telefono[0]"
								></Cvalidation
							></label>
						</div>
					</div>
					<div class="form-group form-row">
						<div class="col-md-6 col-lg-6 col-xl-6">
							<label>Contacto</label>
							<input
								type="text"
								v-model="clientesucursal.ContactoS"
								class="form-control form-control-sm"
							/>
							<label id="lblmsuser" style="color:red"
								><Cvalidation
									v-if="this.errorvalidacion.ContactoS"
									:Mensaje="errorvalidacion.ContactoS[0]"
								></Cvalidation
							></label>
						</div>
						<div class="col-md-6 col-lg-6 col-xl-6">
							<label>Correo</label>
							<input
								type="text"
								v-model="clientesucursal.Correo"
								class="form-control form-control-sm"
							/>
							<label id="lblmsuser" style="color:red"
								><Cvalidation
									v-if="this.errorvalidacion.Correo"
									:Mensaje="errorvalidacion.Correo[0]"
								></Cvalidation
							></label>
						</div>
					</div>
					<div class="form-group form-row">
						<div class="col-md-12 col-lg-12 col-xl-12">
							<label>Comentario</label>
							<textarea
								class="form-control form-control-sm"
								v-model="clientesucursal.Comentario"
								rows="3"
							></textarea>
						</div>
					</div>
					<div class="form-group form-row justify-content-center mt-2">
						<div class="col-md-6 col-lg-4 col-xl-4 text-center">
							<div class="circular_shadow">
								<img :src="Ruta + ImagenSelect" alt="" class=" img-fluid" />
							</div>
							<button
								@click="Find_IconoEmp"
								type="button"
								class="btn btn-03 search02 mt-2"
								data-toggle="modal"
								data-target="#Icono"
							>
								Buscar: {{ this.clientesucursal.IdIconoEmp }}
							</button>
						</div>
					</div>
					<!-- <div class="form-group mt-2">
						<hr />
						<button
							type="button"
							@click="Add_Contratos"
							class="btn btn-01 add mt-2 mb-2"
						>
							Añadir Contrato
						</button>
						<table class="table">
							<thead>
								<tr>
									<th class="tw-2">No. Contrato</th>
									<th>Comentario</th>
									<th class="tw-1">Acciones</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(item, index) in ListaContratos" :key="index">
									<td>
										<input
											type="text"
											v-model="item.NumeroC"
											class="form-control form-control-sm"
										/>
									</td>
									<td>
										<textarea
											class="form-control form-control-sm"
											rows="3"
											v-model="item.Comentario"
										></textarea>
									</td>
									<td class="text-center">
										<button
											@click="delete_ncontrato(index)"
											type="button"
											class="btn-icon-02"
										>
											<i class="fas fa-trash"></i>
										</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div> -->
				</div>
			</template>
			<template v-else>
				<div
					v-for="(item, index) in ListIconoEmp"
					:key="index"
					class="col-md-4 col-lg-3"
				>
					<div @click="Set_IcoEmp(index)" class="circular_shadow">
						<img :src="Ruta + item.Imagen" class="img-fluid" />
					</div>
					<h3 class="text-center equipo">{{ item.Nombre }}</h3>
				</div>

				<div class="col-md-4 col-lg-4">
					<div>
						<img width="100px" alt="" />
					</div>
					<h3 class=" equipo"></h3>
				</div>
			</template>

			<div class="col-lg-12 ">
				<h5>Selecciona tu ubicación</h5>
				<div class="col-12 col-sm-12 col-md-12 col-lg-12">
					<Search v-if="boleano" :oLatLng="oLatLng"></Search>
				</div>
			</div>
		</div>
		<!--Fin body del panel-->
	</div>
</template>

<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from "@/components/Cbtnsave.vue";
import Cvalidation from "@/components/Cvalidation.vue";
export default {
	name: "Form",
	props: ["IdCliente", "ocliente", "NameList", "poBtnSave"],
	data() {
		return {
			Modal: true, //Sirve pra los botones de guardado
			FormName: "cliente", //Sirve para donde va regresar
			clientesucursal: {
				IdClienteS: 0,
				IdCliente: 0,
				Nombre: "",
				Direccion: "",
				Telefono: "",
				Correo: "",
				Ciudad: "",
				IdSucursal: "",
				RegEstatus: "",
				ContactoS: "",
				Ncontrato: "",
				CheckCli: "",
				Tipo: "",
				IdVendedor: "",
				IdIconoEmp: "",
				DistanciaAprox: "",
				Comentario: "",
				Cargo: "",
				FechaMod: "",
				ListaContratos: [],
				Latitud: 0,
				Longitud: 0
			},
			checked: false,
			checkedScanning: false,
			urlApi: "clientesucursal/recovery",
			ListaContratos: [],
			showConten: true,
			ListIconoEmp: [],
			errorvalidacion: [],
			Ruta: "",
			ImagenSelect: "",
			disableHouseAccount: false,
			oLatLng: {
				Lat: 23.530927010615994,
				Lng: -102.015978125
			},
			boleano: true
		};
	},
	components: {
		Cbtnsave,
		Cvalidation
	},
	methods: {
		async Guardar() {
			this.clientesucursal.Latitud = this.oLatLng.Lat;
			this.clientesucursal.Longitud = this.oLatLng.Lng;
			//deshabilita botones
			this.poBtnSave.toast = 0;
			this.poBtnSave.disableBtn = true;
			this.clientesucursal.ListaContratos = JSON.stringify(this.ListaContratos);

			this.$http
				.post("clientesucursal/post", this.clientesucursal)
				.then(res => {
					this.poBtnSave.disableBtn = false;
					this.poBtnSave.toast = 1;
					$("#ModalForm").modal("hide");
					this.bus.$emit("List");
				})
				.catch(err => {
					this.errorvalidacion = err.response.data.message.errores;
					this.poBtnSave.disableBtn = false;
					this.poBtnSave.toast = 2;
				});
		},
		Limpiar() {
			(this.clientesucursal.IdClienteS = 0),
				(this.clientesucursal.Nombre = ""),
				(this.clientesucursal.Direccion = ""),
				(this.clientesucursal.Telefono = ""),
				(this.clientesucursal.Correo = ""),
				(this.clientesucursal.Ciudad = ""),
				(this.clientesucursal.IdSucursal = ""),
				(this.clientesucursal.RegEstatus = ""),
				(this.clientesucursal.ContactoS = ""),
				(this.clientesucursal.Ncontrato = ""),
				(this.clientesucursal.CheckCli = "0"),
				(this.clientesucursal.Tipo = ""),
				(this.clientesucursal.IdVendedor = ""),
				(this.clientesucursal.IdIconoEmp = ""),
				(this.clientesucursal.DistanciaAprox = ""),
				(this.clientesucursal.Comentario = ""),
				(this.clientesucursal.Cargo = ""),
				(this.clientesucursal.FechaMod = ""),
				(this.checkedScanning = false);
			this.errorvalidacion = [""];
			this.checked = false;
			this.ImagenSelect = "";

			this.clientesucursal.Latitud = 0;
			this.clientesucursal.Longitud = 0;

			this.oLatLng = {
				Lat: 23.530927010615994,
				Lng: -102.015978125
			};
		},
		get_one() {
			this.$http
				.get(this.urlApi, {
					params: { IdClienteS: this.clientesucursal.IdClienteS }
				})
				.then(res => {
					this.clientesucursal = res.data.data.Clientes;

					if (this.clientesucursal.Nombre == "House Account") {
						this.disableHouseAccount = true;
					}

					if (this.clientesucursal.Latitud == "0.0000000") {
						this.oLatLng = {
							Lat: 23.530927010615994,
							Lng: -102.015978125
						};
					} else {
						this.oLatLng.Lat = this.clientesucursal.Latitud;
						this.oLatLng.Lng = this.clientesucursal.Longitud;
					}

					this.bus.$emit("ActualC", "");

					this.ImagenSelect = this.clientesucursal.IdIconoEmp;
					this.checkedScanning = false;

					if (res.data.data.Clientes.CheckCli == 1) {
						this.checkedScanning = true;
					}

					//CONTRATOS
					this.ListaContratos = res.data.data.ListaContratos;
				});
		},
		get_IcoEquipos() {
			this.$http
				.get("iconosemp/get", {
					params: { IdClienteS: this.clientesucursal.IdClienteS }
				})
				.then(res => {
					//Equipos
					this.ListIconoEmp = res.data.data.iconosemp;
					this.Ruta = res.data.data.ruta;

					if (this.clientesucursal.IdClienteS == 0) {
						this.bus.$emit("actualCordenadas2");
					}
				});
		},
		get_DatosCli() {
			if (this.checked) {
				this.clientesucursal.IdCliente = this.ocliente.IdCliente;
				this.clientesucursal.Nombre = this.ocliente.Nombre;
				this.clientesucursal.Direccion = this.ocliente.Direccion;
				this.clientesucursal.Ciudad = this.ocliente.Ciudad;
				this.clientesucursal.ContactoS = this.ocliente.Contacto;
				this.clientesucursal.Telefono = this.ocliente.Telefono;
				this.clientesucursal.Correo = this.ocliente.Correo;
			} else {
				this.Limpiar();
			}
		},
		get_scann() {
			this.clientesucursal.CheckCli = 0;
			if (this.checkedScanning) {
				this.clientesucursal.CheckCli = 1;
			}
		},
		Add_Contratos() {
			this.ListaContratos.push({
				IdContrato: "",
				IdClienteS: this.clientesucursal.IdClienteS,
				NumeroC: "",
				Comentario: "",
				RegEstatus: "A"
			});
		},
		delete_ncontrato(index) {
			this.ListaContratos.splice(index, 1);
		},
		Find_IconoEmp() {
			this.showConten = false;
			this.$emit("titulomodal", "Selecciona la imagen");
		},
		ReturnConten() {
			this.$emit("titulomodal", "Sucursal del cliente");
			this.showConten = true;
		},
		Set_IcoEmp(index) {
			this.clientesucursal.IdIconoEmp = this.ListIconoEmp[index].Imagen;
			this.ImagenSelect = this.ListIconoEmp[index].Imagen;
			this.ReturnConten();
		}
	},
	created() {
		this.clientesucursal.IdCliente = this.ocliente.IdCliente;

		this.bus.$off("Nuevo");
		this.bus.$off("ReturnConten");

		//Este es para modal
		this.bus.$on("Nuevo", (data, Id) => {
			this.poBtnSave.disableBtn = false;
			this.bus.$off("Save");
			this.bus.$on("Save", () => {
				this.Guardar();
			});

			this.ListaContratos = [];
			this.get_IcoEquipos();
			this.Limpiar();
			if (Id > 0) {
				this.clientesucursal.IdClienteS = Id;
				this.get_one();
			}

			this.bus.$emit("Desbloqueo", false);
		});
		if (this.Id != undefined) {
			this.clientesucursal.IdClienteS = this.Id;
			this.get_one();
		}

		this.bus.$on("ReturnConten", () => {
			this.ReturnConten();
		});
	}
};
</script>
