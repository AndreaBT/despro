<template>
	<div>
		{{ TotalNuevo }}
		<div class="row">
			<div class="col-lg-12  form-group">
				<label>Caja</label>
				<select
					name=""
					class="form-control form-control-sm"
					v-model="cajachica.IdCaja"
				>
					<option value="0">Seleccione una Caja</option>
					<option
						:value="lista.IdCaja"
						v-for="(lista, key, index) in ListaCaja"
						:key="index"
					>
						{{ lista.Nombre }}
					</option>
				</select>
				<Cvalidation
					v-if="this.errorvalidacion.Caja"
					:Mensaje="'Campo obligatorio'"
				></Cvalidation>
			</div>

			<div style="display:none" class="col-lg-12 form-group">
				<label>Nombre</label>
				<input
					type="text"
					v-model="cajachica.Nombre"
					class="form-control form-control-sm"
					placeholder="Nombre"
					id="Nombre"
					name="Nombre"
				/>
				<Cvalidation
					v-if="this.errorvalidacion.Nombre"
					:Mensaje="'Campo obligatorio'"
				></Cvalidation>
			</div>
			<div class="col-lg-6  form-group ">
				<label>Fecha Inicio</label>
				<v-date-picker
					v-model="cajachica.FechaInicio"
					:min-date="new Date()"
					:popover="{
						placement: 'bottom',
						visibility: 'click'
					}"
					:input-props="{
						class: 'form-control  calendar',
						style: 'cursor:pointer;background-color:#F9F9F9',
						readonly: true
					}"
				/>
				<Cvalidation
					v-if="this.errorvalidacion.FechaInicio"
					:Mensaje="'Campo obligatorio'"
				></Cvalidation>
			</div>
			<div class="col-lg-6  form-group">
				<label>Fecha Final</label>
				<v-date-picker
					v-model="cajachica.FechaFin"
					:min-date="new Date()"
					:popover="{
						placement: 'bottom',
						visibility: 'click'
					}"
					:input-props="{
						class: 'form-control  calendar',
						style: 'cursor:pointer;background-color:#F9F9F9',
						readonly: true
					}"
				/>

				<Cvalidation
					v-if="this.errorvalidacion.FechaFin"
					:Mensaje="'Campo obligatorio'"
				></Cvalidation>
			</div>
			<div class="col-lg-6 form-group">
				<label>Monto Asignado</label>
				<vue-numeric
					class="form-control"
					currency="$"
					separator=","
					:precision="2"
					placeholder="$ 0.00"
					:disabled="cajachica.IdCajaC > 0 ? true : false"
					:minus="false"
					v-model="cajachica.Monto"
				></vue-numeric>
				<Cvalidation
					v-if="this.errorvalidacion.Monto"
					:Mensaje="'Campo obligatorio'"
				></Cvalidation>
			</div>

			<div v-if="cajachica.IdCajaC > 0" class="col-lg-6 form-group">
				<label>Aumentar</label>
				<vue-numeric
					class="form-control"
					currency="$"
					separator=","
					:precision="2"
					placeholder="$ 0.00"
					:minus="false"
					v-model="Aumentar"
				></vue-numeric>
			</div>

			<div v-if="cajachica.IdCajaC > 0" class="col-lg-6  form-group">
				<label>Actual</label>
				<vue-numeric
					disabled
					:minus="false"
					class="form-control "
					currency="$"
					separator=","
					:precision="2"
					placeholder="$ 0.00"
					v-model="cajachica.Utilizado"
				></vue-numeric>
			</div>

			<div v-if="cajachica.IdCajaC > 0" class="col-lg-6  form-group">
				<label>Estado</label>
				<select name="" class="form-control " v-model="cajachica.Estado">
					<option value="Cerrado">Cerrado</option>
					<option value="Abierta">Abierta</option>
				</select>
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
	props: ["IdCajaC", "poBtnSave"],
	data() {
		return {
			Modal: true, //Sirve pra los botones de guardado
			FormName: "caja chica", //Sirve para donde va regresar
			ListaCaja: [],
			cajachica: {
				IdCajaC: 0,
				IdCaja: 0,
				Nombre: "",
				FechaInicio: "",
				FechaFin: "",
				Monto: 0,
				Estado: ""
			},
			urlApi: "cajachica/recovery",
			urlApiCaja: "caja/get",
			errorvalidacion: [],
			Aumentar: 0,
			Actual: 0
		};
	},
	components: {
		Cbtnsave,
		Cvalidation
	},
	methods: {
		listacaja() {
			this.$http
				.get(this.urlApiCaja, {
					params: {}
				})
				.then(res => {
					this.ListaCaja = res.data.data.caja;
				});
		},
		async Guardar() {
			if (this.cajachica.IdCajaC == 0) {
				this.cajachica.Estado = "Abierta";
			}
			let FechaI = "";
			let FechaF = "";
			if (this.cajachica.FechaInicio != "") {
				let day = this.cajachica.FechaInicio.getDate();
				let month = this.cajachica.FechaInicio.getMonth() + 1;
				let year = this.cajachica.FechaInicio.getFullYear();
				FechaI = year + "-" + month + "-" + day;
			}
			if (this.cajachica.FechaFin != "") {
				let day2 = this.cajachica.FechaFin.getDate();
				let month2 = this.cajachica.FechaFin.getMonth() + 1;
				let year2 = this.cajachica.FechaFin.getFullYear();
				FechaF = year2 + "-" + month2 + "-" + day2;
			}
			let formData = new FormData();
			formData.set("IdCajaC", this.cajachica.IdCajaC);
			formData.set("IdCaja", this.cajachica.IdCaja);
			formData.set("Nombre", this.cajachica.Nombre);
			formData.set("FechaInicio", FechaI);
			formData.set("FechaFin", FechaF);
			formData.set("Monto", this.cajachica.Monto);
			formData.set("Utilizado", this.cajachica.Utilizado);
			formData.set("Estado", this.cajachica.Estado);
			formData.set("Aumentar", this.Aumentar);
			//deshabilita botones
			this.poBtnSave.toast = 0;
			this.poBtnSave.disableBtn = true;
			await this.$http
				.post("cajachica/post", formData, {
					headers: {
						"Content-Type": "multipart/form-data"
					}
				})
				.then(res => {
					$("#ValidacionFechaInicioMayor").text(null);
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
			(this.cajachica.IdCajaC = 0),
				(this.cajachica.IdCaja = 0),
				(this.cajachica.Nombre = ""),
				(this.cajachica.FechaInicio = ""),
				(this.cajachica.FechaFin = ""),
				(this.cajachica.Monto = 0),
				(this.cajachica.Estado = ""),
				(this.errorvalidacion = []),
				(this.Aumentar = 0);
		},
		get_one() {
			this.$http
				.get(this.urlApi, {
					params: { IdCajaC: this.cajachica.IdCajaC }
				})
				.then(res => {
					this.cajachica = res.data.data.cajachica;
					let formatedDate = this.cajachica.FechaInicio.replace(/-/g, "\/");
					this.cajachica.FechaInicio = new Date(formatedDate);

					let formatedDate2 = this.cajachica.FechaFin.replace(/-/g, "\/");
					this.cajachica.FechaFin = new Date(formatedDate2);

					this.Actual = res.data.data.cajachica.Utilizado;
				});
		}
	},
	created() {
		this.bus.$off("Nuevo");
		this.listacaja();

		//Este es para modal
		this.bus.$on("Nuevo", (data, Id) => {
			this.poBtnSave.disableBtn = false;

			this.bus.$off("Save");
			this.bus.$on("Save", () => {
				this.Guardar();
			});

			this.Limpiar();

			this.cajachica.FechaInicio = new Date();
			this.cajachica.FechaFin = new Date();

			if (Id > 0) {
				this.cajachica.IdCajaC = Id;
				this.get_one();
			}
			this.bus.$emit("Desbloqueo", false);
		});
		if (this.Id != undefined) {
			this.cajachica.IdCajaC = this.Id;
			this.get_one();
		}
	},
	computed: {
		TotalNuevo() {
			this.cajachica.Utilizado = this.Actual;
			if (this.Aumentar != "") {
				this.cajachica.Utilizado =
					parseFloat(this.Aumentar) + parseFloat(this.cajachica.Utilizado);
			} else {
				this.cajachica.Utilizado = this.Actual;
			}
		}
	}
};
</script>
