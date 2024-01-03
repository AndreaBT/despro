<template>
	<div class="row  justify-content-center">
		<div class="col-lg-8">
			<label>Nombre</label>
			<input
				v-model="contract.NumeroC"
				class="form-control"
				placeholder="Nombre"
			/>
			<label id="lblmsuser" style="color:red"
				><Cvalidation
					v-if="this.errorvalidacion.Numero_Contrato"
					:Mensaje="errorvalidacion.Numero_Contrato[0]"
				></Cvalidation
			></label>
		</div>
		<div class="col-lg-8">
			<label>Comentarios</label>
			<textarea
				v-model="contract.Comentario"
				placeholder=" Coloque sus comentarios"
				class="form-control"
				cols="2"
				rows="3"
			></textarea>
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
	props: ["IdContrato", "ocliente", "NameList", "poBtnSave"],
	data() {
		return {
			Modal: true, //Sirve pra los botones de guardado
			FormName: "cliente", //Sirve para donde va regresar
			contract: {
				IdContrato: 0,
				IdClienteS: 0,
				NumeroC: "",
				Comentario: "",
				IdProyectoSpend: 0
			},
			errorvalidacion: [],
			checked: false,
			checkedScanning: false,
			ListaContratos: []
		};
	},
	components: {
		Cbtnsave,
		Cvalidation
	},
	methods: {
		async Guardar() {
			//deshabilita botones
			this.poBtnSave.toast = 0;
			this.poBtnSave.disableBtn = true;
			this.$http
				.post("numcontrato/post", this.contract)
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
			this.contract = {
				IdContrato: 0,
				IdClienteS: 0,
				NumeroC: "",
				Comentario: "",
				IdProyectoSpend: 0
			};
			this.errorvalidacion = [""];
		},
		get_one() {
			this.$http
				.get("numcontrato/recovery", {
					params: { IdContrato: this.contract.IdContrato }
				})
				.then(res => {
					this.contract = res.data.data.contrato;
					console.log();
				});
		}
	},
	created() {
		this.bus.$off("Nuevo");

		//Este es para modal
		this.bus.$on("Nuevo", (data, Id) => {
			this.Limpiar();
			this.contract.IdClienteS = this.ocliente.IdClienteS;

			this.poBtnSave.disableBtn = false;
			this.bus.$off("Save");
			this.bus.$on("Save", () => {
				this.Guardar();
			});

			if (Id > 0) {
				this.contract.IdContrato = Id;
				this.get_one();
			}

			this.ListaContratos = [];

			this.bus.$emit("Desbloqueo", false);
		});
	}
};
</script>
