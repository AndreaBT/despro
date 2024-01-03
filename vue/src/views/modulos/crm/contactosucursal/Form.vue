<template>
	<div>
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
			<hr />
		</div>

		<div class="row">
			<div class="col-lg-6 ">
				<label>Nombre </label>
				<input
					type="text"
					v-model="Clientes.Nombre"
					class="form-control form-control-sm"
					placeholder="Nombre"
					id="Nombre"
					name="Nombre"
				/>
				<Cvalidation
					v-if="this.errorvalidacion.Nombre"
					:Mensaje="errorvalidacion.Nombre[0]"
				></Cvalidation>
			</div>

			<div class="col-lg-6">
				<label>Contacto </label>
				<input
					type="text"
					v-model="Clientes.Contacto"
					class="form-control form-control-sm"
					placeholder="Contacto"
				/>
				<label id="lblmsuser" style="color:red"
					><Cvalidation
						v-if="this.errorvalidacion.ContactoS"
						:Mensaje="errorvalidacion.ContactoS[0]"
					></Cvalidation
				></label>
			</div>

			<div class="col-lg-12">
				<label>Dirección</label>
				<input
					type="text"
					v-model="Clientes.Direccion"
					class="form-control form-control-sm"
					placeholder="Dirección"
				/>
				<label id="lblmsuser" style="color:red"
					><Cvalidation
						v-if="this.errorvalidacion.Direccion"
						:Mensaje="errorvalidacion.Direccion[0]"
					></Cvalidation
				></label>
			</div>

			<div class="col-lg-4 ">
				<label>Ciudad </label>
				<input
					type="text"
					v-model="Clientes.Ciudad"
					class="form-control form-control-sm"
					placeholder="Ciudad"
				/>
				<label id="lblmsuser" style="color:red"
					><Cvalidation
						v-if="this.errorvalidacion.Ciudad"
						:Mensaje="errorvalidacion.Ciudad[0]"
					></Cvalidation
				></label>
			</div>

			<div class="col-lg-4">
				<label>Teléfono </label>
				<input
					type="text"
					v-model="Clientes.Telefono"
					class="form-control form-control-sm"
					placeholder="Teléfono"
				/>
				<label id="lblmsuser" style="color:red"
					><Cvalidation
						v-if="this.errorvalidacion.Telefono"
						:Mensaje="errorvalidacion.Telefono[0]"
					></Cvalidation
				></label>
			</div>

			<div class="col-lg-4">
				<label>Posicion </label>
				<input
					type="text"
					v-model="Clientes.Posicion"
					class="form-control form-control-sm"
					placeholder="Posicion"
				/>
				<label id="lblmsuser" style="color:red"
					><Cvalidation
						v-if="this.errorvalidacion.Posicion"
						:Mensaje="errorvalidacion.Posicion[0]"
					></Cvalidation
				></label>
			</div>

			<div class="col-lg-6 ">
				<label>Correo </label>
				<input
					v-model="Clientes.Correo"
					class="form-control form-control-sm"
					placeholder="Correo"
				/>
				<label id="lblmsuser" style="color:red"
					><Cvalidation
						v-if="this.errorvalidacion.Correo"
						:Mensaje="errorvalidacion.Correo[0]"
					></Cvalidation
				></label>
			</div>

			<div class="col-lg-6 ">
				<label>Estatus </label>
				<select v-model="Clientes.Tipo" class="form-control form-control-sm">
					<option value="">Seleccione una opción</option>
					<option :value="'Cliente'">Clientes</option>
					<option :value="'Prospecto'">Prospectos</option>
					<option :value="'Suspecto'">Suspecto</option>
					<option :value="'Inactivo'">Inactivo</option>
				</select>
				<label id="lblmsuser" style="color:red"
					><Cvalidation
						v-if="this.errorvalidacion.Estatus"
						:Mensaje="errorvalidacion.Estatus[0]"
					></Cvalidation
				></label>
			</div>

			<div class=" col-lg-6">
				<label>Comentarios</label>
				<textarea
					rows="5"
					class="form-control form-control-sm"
					v-model="Clientes.Dfac"
				></textarea>
				<label id="lblmsuser" style="color:red"
					><Cvalidation
						v-if="this.errorvalidacion.Dfac"
						:Mensaje="'Campo obligatorio'"
					></Cvalidation
				></label>
			</div>

			<div class="col-lg-12 ">
				<h5>Selecciona tu ubicación</h5>
				<div class="col-12 col-sm-12 col-md-12 col-lg-12">
					<Search v-if="boleano" :oLatLng="oLatLng"></Search>
				</div>
			</div>
			<!--fin col-6-->
		</div>
	</div>
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from "@/components/Cbtnsave.vue";
import Cvalidation from "@/components/Cvalidation.vue";

export default {
	name: "Form",
	props: ["ocliente", "poBtnSave"],
	data() {
		return {
			Modal: true, //Sirve pra los botones de guardado
			FormName: "cliente", //Sirve para donde va regresar
			Clientes: {
				IdClienteS: "",
				IdCliente: 0,
				Nombre: "",
				Telefono: "",
				Direccion: "",
				Correo: "",
				Ciudad: "",
				Pais: "",
				Estado: "",
				CP: "",
				IdSucursal: "",
				Contacto: "",
				Dfac: "",
				Tipo: "",
				Posicion: "",
				Latitud: 0,
				Longitud: 0
			},
			checked: false,
			oClienteP: {},
			urlApi: "crmsucursal/recovery",
			errorvalidacion: [],
			oLatLng: {
				Lat: 21.021320029601277,
				Lng: -89.58358390674591
			},
			boleano: true
		};
	},
	components: {
		Cbtnsave,
		Cvalidation
	},
	methods: {
		get_DatosCli() {
			if (this.checked) {
				this.Clientes.IdCliente = this.oClienteP.IdCliente;
				this.Clientes.Nombre = this.oClienteP.Nombre;
				this.Clientes.Direccion = this.oClienteP.Direccion;
				this.Clientes.Ciudad = this.oClienteP.Ciudad;
				this.Clientes.ContactoS = this.oClienteP.Contacto;
				this.Clientes.Telefono = this.oClienteP.Telefono;
				this.Clientes.Correo = this.oClienteP.Correo;
			} else {
				this.Limpiar();
			}
		},
		async Guardar() {
			//deshabilita botones
			this.Clientes.Latitud = this.oLatLng.Lat;
			this.Clientes.Longitud = this.oLatLng.Lng;
			this.poBtnSave.toast = 0;
			this.poBtnSave.disableBtn = true;

			let formData = new FormData();

			formData.set("IdCliente", this.Clientes.IdCliente);
			formData.set("Nombre", this.Clientes.Nombre);
			formData.set("Telefono", this.Clientes.Telefono);
			formData.set("Direccion", this.Clientes.Direccion);
			formData.set("Correo", this.Clientes.Correo);
			formData.set("Ciudad", this.Clientes.Ciudad);
			formData.set("Pais", this.Clientes.Pais);
			formData.set("Estado", this.Clientes.Estado);
			formData.set("CP", this.Clientes.CP);
			formData.set("ContactoS", this.Clientes.Contacto);
			formData.set("Comentario", this.Clientes.Dfac);
			formData.set("Tipo", this.Clientes.Tipo);
			formData.set("IdClienteS", this.Clientes.IdClienteS);
			formData.set("Posicion", this.Clientes.Posicion);
			formData.set("Latitud", this.oLatLng.Lat);
			formData.set("Longitud", this.oLatLng.Lng);
			formData.set("CheckCli", 0);

			await this.$http
				.post("crmsucursal/post", formData, {
					headers: {
						"Content-Type": "multipart/form-data"
					}
				})
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
			(this.Clientes.Nombre = ""),
				(this.Clientes.Telefono = ""),
				(this.Clientes.Direccion = ""),
				(this.Clientes.Correo = ""),
				(this.Clientes.Ciudad = "");
			(this.Clientes.Pais = ""),
				(this.Clientes.Estado = ""),
				(this.Clientes.CP = ""),
				(this.Clientes.Contacto = ""),
				(this.Clientes.Dfac = ""),
				(this.Clientes.IdCliente = 0),
				(this.Clientes.IdSucursal = ""),
				(this.errorvalidacion = [""]);
			this.Clientes.IdClienteS = "";
			this.Clientes.Tipo = "";
			this.Clientes.Posicion = "";
			this.Clientes.Latitud = 0;
			this.Clientes.Longitud = 0;
			this.oLatLng = {
				Lat: 19.43120339928868,
				Lng: -99.13450664719238
			};

			this.checked = false;
		},
		get_one() {
			this.$http
				.get(this.urlApi, {
					params: { IdClienteS: this.Clientes.IdCliente }
				})
				.then(res => {
					this.Clientes.IdClienteS = res.data.data.Clientes.IdClienteS;
					this.Clientes.IdCliente = res.data.data.Clientes.IdCliente;
					this.Clientes.Nombre = res.data.data.Clientes.Nombre;
					this.Clientes.Telefono = res.data.data.Clientes.Telefono;
					this.Clientes.Direccion = res.data.data.Clientes.Direccion;
					this.Clientes.Correo = res.data.data.Clientes.Correo;
					this.Clientes.Ciudad = res.data.data.Clientes.Ciudad;
					this.Clientes.Pais = res.data.data.Clientes.Pais;
					this.Clientes.Estado = res.data.data.Clientes.Estado;
					this.Clientes.CP = res.data.data.Clientes.CP;
					this.Clientes.IdSucursal = res.data.data.Clientes.IdSucursal;
					this.Clientes.Contacto = res.data.data.Clientes.Contacto;
					this.Clientes.Dfac = res.data.data.Clientes.Comentario;
					this.Clientes.Contacto = res.data.data.Clientes.ContactoS;
					this.Clientes.Tipo = res.data.data.Clientes.Tipo;
					this.Clientes.Posicion = res.data.data.Clientes.Cargo;
					this.Clientes.Latitud = res.data.data.Clientes.Latitud;
					this.Clientes.Longitud = res.data.data.Clientes.Longitud;
					this.oLatLng.Lat = this.Clientes.Latitud;
					this.oLatLng.Lng = this.Clientes.Longitud;

					this.bus.$emit("ActualC", "");

					//this.bus.$emit('actualCordenadas2');
				});
		},
		VerificarMap() {
			if (this.Clientes.IdClienteS == 0) {
				this.bus.$emit("actualCordenadas2");
			}
		}
	},
	created() {
		this.bus.$off("Nuevo");

		if (this.ocliente != undefined) {
			sessionStorage.setItem("IdSaved", JSON.stringify(this.ocliente));
		}

		this.oClienteP = JSON.parse(sessionStorage.getItem("IdSaved"));

		//Este es para modal
		this.bus.$on("Nuevo", (data, Id) => {
			this.VerificarMap();
			//alert(this.ocliente.IdCliente);
			this.poBtnSave.disableBtn = false;
			this.bus.$off("Save");
			this.bus.$on("Save", () => {
				this.Guardar();
			});

			this.Limpiar();

			this.Clientes.IdCliente = this.oClienteP.IdCliente;
			if (Id > 0) {
				this.Clientes.IdCliente = Id;
				this.get_one();
				this.Clientes.IdCliente = this.oClienteP.IdCliente;
			} else {
				//this.bus.$emit("ActualC",'');
				this.bus.$emit("actualCordenadas2");
			}
			this.bus.$emit("Desbloqueo", false);
		});
		if (this.Id != undefined) {
			this.Clientes.IdCliente = this.Id;
			this.get_one();
		}
	}
};
</script>
