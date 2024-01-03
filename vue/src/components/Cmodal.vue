<template>
	<div>
		<!-- Modal -->

		<div
			class="modal"
			:id="NombreModal"
			data-backdrop="static"
			tabindex="-1"
			role="dialog"
			aria-labelledby="staticBackdropLabel"
			aria-hidden="true"
		>
			<div
				:class="'modal-dialog modal-dialog-centered modal-dialog ' + size"
				role="document"
			>
				<div class="modal-content">
					<div class="modal-header bg-modal">
						<h5 class="modal-title" id="exampleModalLabel">{{ showProps }}</h5>
						<button
							@click="Close_Modal"
							type="button"
							class="close close-2"
							aria-label="Close"
						>
							<i class="fad fa-times-circle"></i>
						</button>
					</div>

					<div class="modal-body">
						<slot name="Form"> </slot>
					</div>
					<div v-if="ShowF">
						<Cbtnsave2 :poBtnSave="poBtnSave"></Cbtnsave2>
						<!--  <Cbtnsave  :NombreModal="NombreModal" :IsModal="Modal" :TipoM="TipoModal"></Cbtnsave>-->
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import Cbtnsave from "@/components/Cbtnsave";
export default {
	props: [
		"size",
		"Nombre",
		"Nuevo",
		"NameModal",
		"TipoM",
		"Showbutton",
		"pShowF",
		"poBtnSave"
	],
	name: "Cmodal",
	data() {
		return {
			Modal: true,
			Tamanio: "",
			Titulo: "",
			CloseM: true,
			ShowF: true,
			NombreModal: "ModalForm",
			TipoModal: 1,
			showComponentFoot: false
		};
	},
	components: {
		Cbtnsave
	},
	methods: {
		Close_Modal() {
			if (this.CloseM) {
				if (this.TipoModal == 1) {
					$("#" + this.NombreModal).modal("hide");
				} else {
					$("#" + this.NombreModal).modal("hide");
					document.body.classList.add("modal-open");
				}
			} else {
				this.bus.$emit("ReturnConten");
			}
		},
		cambiar_CloseModal(bnd) {
			this.CloseM = bnd;
			this.ShowF = bnd;
		},
		show_footer(bnd) {
			this.ShowF = bnd;
		}
	},
	created() {
		if (this.size == undefined) {
			this.Tamanio = "";
		}
		if (this.NameModal == undefined) {
			this.NombreModal = "ModalForm";
		} else {
			this.NombreModal = this.NameModal;
		}

		if (this.TipoM != undefined) {
			this.TipoModal = this.TipoM;
		}

		if (this.Showbutton != undefined) {
			this.ShowF = this.Showbutton;
		}

		this.bus.$on("Nuevo", (data, Id) => {
			//this.bus.$off('BloquearBtn');
			//this.bus.$off('Desbloqueo');

			this.bus.$off("cambiar_CloseModal");
			this.bus.$off("Cambiar_Footer");
			//apagar acciones emit del boton guardar

			this.bus.$on("cambiar_CloseModal", bnd => {
				this.cambiar_CloseModal(bnd);
			});

			this.bus.$on("Cambiar_Footer", bnd => {
				this.show_footer(bnd);
			});

			if (data == true) {
				this.Titulo = "Agregar";
			} else {
				this.Titulo = "Editar";
			}
		});
	},
	computed: {
		showProps() {
			if (this.Nombre != undefined) {
				this.Titulo = this.Nombre;
			}
			if (this.TipoM != undefined) {
				this.TipoModal = this.TipoM;
			}
			return this.Titulo;
		}
	}
};
</script>
