<template>
	<div class="modal-footer">
		<button
			:disabled="oBtnSave.disableBtn"
			v-if="oBtnSave.isModal"
			@click="Limpiar"
			type="button"
			class="btn btn-04 ban"
		>
			{{ oBtnSave.txtCancel }}
		</button>
		<button
			:disabled="oBtnSave.disableBtn"
			v-else
			@click="Atras"
			type="button"
			class="btn btn-04 ban"
		>
			{{ oBtnSave.txtCancel }}
		</button>
		<button
			:disabled="oBtnSave.disableBtn"
			type="button"
			class="btn btn-01"
			@click="Saved"
		>
			<i
				v-show="oBtnSave.disableBtn"
				class="fa fa-spinner fa-pulse fa-1x fa-fw"
			></i
			><i class="fa fa-plus-circle"></i> {{ nameBtnSave }}
		</button>
	</div>
</template>

<script>
export default {
	name: "btnsave",
	props: ["poBtnSave"],
	data() {
		return {
			oBtnSave: {
				isModal: true,
				nombreModal: "ModalForm",
				tipoModal: 1,
				regresarA: "",
				disableBtn: false,
				txtSave: "Guardar",
				txtCancel: "Cerrar"
			}
		};
	},
	methods: {
		Saved() {
			//this.$emit('ejecutar');
			this.bus.$emit("Save");
		},
		Atras() {
			this.$router.push({ name: this.oBtnSave.regresarA });
		},
		Limpiar() {
			this.bus.$emit("Limpiar");
			if (this.oBtnSave.tipoModal == 1) {
				$("#" + this.oBtnSave.nombreModal).modal("hide");
			} else {
				$("#" + this.oBtnSave.nombreModal).modal("hide");
				document.body.classList.add("modal-open");
			}
		}
	},
	created() {},
	computed: {
		nameBtnSave() {
			if (this.poBtnSave != undefined) {
				if (this.poBtnSave.isModal != undefined) {
					this.oBtnSave.isModal = this.poBtnSave.isModal;
				}

				if (this.poBtnSave.nombreModal != undefined) {
					this.oBtnSave.nombreModal = this.poBtnSave.nombreModal;
				}

				if (this.poBtnSave.tipoModal != undefined) {
					this.oBtnSave.tipoModal = this.poBtnSave.tipoModal;
				}

				if (this.poBtnSave.regresarA != undefined) {
					this.oBtnSave.regresarA = this.poBtnSave.regresarA;
				}

				if (this.poBtnSave.disableBtn != undefined) {
					this.oBtnSave.disableBtn = this.poBtnSave.disableBtn;
				}

				if (this.poBtnSave.txtSave != undefined) {
					this.oBtnSave.txtSave = this.poBtnSave.txtSave;
				}

				if (this.poBtnSave.txtCancel != undefined) {
					this.oBtnSave.txtCancel = this.poBtnSave.txtCancel;
				}

				if (this.poBtnSave.toast != undefined) {
					if (this.poBtnSave.toast == 1) {
						this.$toast.success("Información guardada");
					} else if (this.poBtnSave.toast == 2) {
						this.$toast.warning("Complete los campos");
					} else if (this.poBtnSave.toast == 3) {
						this.$toast.error(this.poBtnSave.toastmsg);
					}
				}
			}

			return this.oBtnSave.txtSave;
		}
	}
};
</script>
