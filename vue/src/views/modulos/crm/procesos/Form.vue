<template>
	<div>
		<div class="row">
			<div class="col-lg-6 ">
				<label>Nombre </label>
				<input
					:readonly="
						objproceso.Nombre == 'Cierre'
							? true
							: false || objproceso.Nombre == 'Propuestas'
							? true
							: false
					"
					type="text"
					v-model="objproceso.Nombre"
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
			<div class="col-lg-4">
				<label>Color </label>
				<input
					type="color"
					v-model="objproceso.Color"
					class="form-control form-control-sm"
					placeholder="Color"
				/>
				<label id="lblmsuser" style="color:red"
					><Cvalidation
						v-if="this.errorvalidacion.Color"
						:Mensaje="errorvalidacion.Color[0]"
					></Cvalidation
				></label>
			</div>

			<div class="col-lg-2 form__input">
				<label>Color Letra</label>
				<v-swatches
					v-model="objproceso.ColorLetra"
					popover-x="left"
				></v-swatches>
				<label id="lblmsuser" style="color:red"
					><Cvalidation
						v-if="this.errorvalidacion.Color_Letra"
						:Mensaje="errorvalidacion.Color_Letra[0]"
					></Cvalidation
				></label>
			</div>
			<!--fin col-6-->
		</div>
	</div>
</template>
<script>
import VSwatches from "vue-swatches";

// Import the styles too, typically in App.vue or main.js
import "vue-swatches/dist/vue-swatches.css";

//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from "@/components/Cbtnsave.vue";
import Cvalidation from "@/components/Cvalidation.vue";

export default {
	name: "Form",
	props: ["ocliente", "poBtnSave"],
	data() {
		return {
			swatches: ["#000000", "#FFFFFF"],
			Modal: true, //Sirve pra los botones de guardado
			FormName: "cliente", //Sirve para donde va regresar
			objproceso: {
				IdProceso: "",
				IdTipoProceso: 0,
				Nombre: "",
				Color: "",
				ColorLetra: ""
			},
			urlApi: "crmprocesos/recovery",
			errorvalidacion: []
		};
	},
	components: {
		VSwatches,
		Cbtnsave,
		Cvalidation
	},
	methods: {
		async Guardar() {
			//deshabilita botones
			this.poBtnSave.toast = 0;
			this.poBtnSave.disableBtn = true;
			let formData = new FormData();

			formData.set("IdCrmProceso", this.objproceso.IdCrmProceso);
			formData.set("Nombre", this.objproceso.Nombre);
			formData.set("Color", this.objproceso.Color);
			formData.set("ColorLetra", this.objproceso.ColorLetra);
			formData.set("IdTipoProceso", this.ocliente.IdTipoProceso);

			await this.$http
				.post("crmprocesos/post", formData, {
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
			(this.objproceso.IdCrmProceso = ""),
				(this.objproceso.Nombre = ""),
				(this.objproceso.ColorLetra = ""),
				(this.objproceso.Color = ""),
				(this.objproceso.IdTipoProceso = ""),
				(this.errorvalidacion = [""]);
		},
		get_one() {
			this.$http
				.get(this.urlApi, {
					params: { IdCrmProceso: this.objproceso.IdCrmProceso }
				})
				.then(res => {
					this.objproceso = res.data.data.procesos;
				});
		}
	},
	created() {
		this.bus.$off("Nuevo");

		//Este es para modal
		this.bus.$on("Nuevo", (data, Id) => {
			//alert(this.data.IdCrmProceso);
			this.poBtnSave.disableBtn = false;
			this.bus.$off("Save");
			this.bus.$on("Save", () => {
				this.Guardar();
			});

			this.Limpiar();
			if (Id > 0) {
				this.objproceso.IdCrmProceso = Id;
				this.get_one();
			}
			this.bus.$emit("Desbloqueo", false);
		});
		if (this.Id != undefined) {
			this.objproceso.IdCrmProceso = this.Id;
			this.get_one();
		}
	}
};
</script>
