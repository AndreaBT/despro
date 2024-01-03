<template>
	<div>
		<!--Fin head del panel-->

		<div class="card-body">
			<div class="row">
				<div class="col-lg-12 form-group">
					<span class="has-float-label">
						<label for="Nombre" class="labeltam">Nombre</label>
						<input
							type="text"
							v-model="caja.Nombre"
							class="form-control"
							placeholder="Nombre"
							id="Nombre"
							name="Nombre"
						/>
						<Cvalidation
							v-if="this.errorvalidacion.Nombre"
							:Mensaje="'Campo obligatorio'"
						></Cvalidation>
					</span>
				</div>

				<div class="col-lg-12 form-group">
					<label>Tipo</label>
					<select name="" class="form-control" v-model="caja.Tipo">
						<option value="">Seleccione un Tipo</option>
						<option :value="'Tecnico'">Técnico </option>
						<option :value="'Vendedor'">Vendedor </option>
					</select>
					<Cvalidation
						v-if="this.errorvalidacion.Tipo"
						:Mensaje="'Campo obligatorio'"
					></Cvalidation>
				</div>
			</div>
			<!--Fin body del panel-->
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
	props: ["IdCaja", "poBtnSave"],
	data() {
		return {
			Modal: true, //Sirve pra los botones de guardado
			FormName: "caja", //Sirve para donde va regresar
			ListaRol: [],
			caja: {
				IdCaja: 0,
				Nombre: "",
				IdSucursal: 0,
				Estado: "",
				Tipo: "Tecnico"
			},
			urlApi: "caja/recovery",
			urlApiRol: "rol/get",
			errorvalidacion: []
		};
	},
	components: {
		Cbtnsave,
		Cvalidation
	},
	methods: {
		listarol() {
			this.$http
				.get(this.urlApiRol, {
					params: {}
				})
				.then(res => {
					this.ListaRol = res.data.data.rol;
				});
		},
		async Guardar() {
			//deshabilita botones
			this.poBtnSave.toast = 0;
			this.poBtnSave.disableBtn = true;
			let formData = new FormData();
			formData.set("IdCaja", this.caja.IdCaja);
			formData.set("Nombre", this.caja.Nombre);
			formData.set("Estado", "Abierto");
			formData.set("Tipo", this.caja.Tipo);

			await this.$http
				.post("caja/post", formData, {
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
			(this.caja.IdCaja = 0),
				(this.caja.Nombre = ""),
				(this.caja.IdSucursal = 0),
				(this.caja.Estado = ""),
				(this.caja.Tipo = "Tecnico"),
				(this.errorvalidacion = []);
		},
		get_one() {
			this.$http
				.get(this.urlApi, {
					params: { IdCaja: this.caja.IdCaja }
				})
				.then(res => {
					this.caja = res.data.data.caja;
				});
		}
	},
	created() {
		this.bus.$off("Nuevo");
		this.listarol();

		//Este es para modal
		this.bus.$on("Nuevo", (data, Id) => {
			this.poBtnSave.disableBtn = false;

			this.bus.$off("Save");
			this.bus.$on("Save", () => {
				this.Guardar();
			});

			this.Limpiar();
			if (Id > 0) {
				this.caja.IdCaja = Id;
				this.get_one();
			}
			this.bus.$emit("Desbloqueo", false);
		});
		if (this.Id != undefined) {
			this.caja.IdCaja = this.Id;
			this.get_one();
		}
	}
};
</script>
