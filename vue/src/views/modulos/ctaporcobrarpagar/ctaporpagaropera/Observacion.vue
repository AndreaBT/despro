<template>
	<div>
		<div class="row justify-content-center">
			<div class="col-12 col-ms-12 col-md-12 col-lg-12">
				<div class="col-12 col-ms-12 col-md-12 col-lg-12 form-group">
					<h5 v-if="ctaporpagar.Estatus == 'NO'">
						¿Desea confirmar como Pagado?
					</h5>
					<h5 v-if="ctaporpagar.Estatus == 'SI'">
						Observaciones
					</h5>
					<br />
					<h5 class="" v-if="ctaporpagar.Estatus == 'NO'">
						Fecha:
					</h5>
					<v-date-picker
						v-model="ctaporpagar.FechaRealPago"
						:min-date="new Date()"
						:popover="{
							placement: 'bottom',
							visibility: 'click'
						}"
						:input-props="{
							class: 'col-md-6 form-control  calendar',
							style: 'cursor:pointer;background-color:#F9F9F9'
						}"
					/>
					<br />
					<textarea
						v-model="ctaporpagar.Observacion"
						v-if="ctaporpagar.Estatus == 'NO'"
						placeholder=" Coloque sus observaciones"
						class="form-control"
						cols="2"
						rows="3"
					></textarea>
					<textarea
						v-model="ctaporpagar.Observacion"
						v-if="ctaporpagar.Estatus == 'SI'"
						readonly
						placeholder=" Coloque sus observaciones"
						class="form-control"
						cols="2"
						rows="3"
					></textarea>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: "cuentasporpagaropera",
	props: ["poBtnSave"],
	data() {
		return {
			ctaporpagar: {
				IdCtaPagar: 0,
				Observacion: "",
				Estatus: "",
				FechaRealPago: null
			},
			date: ""
		};
	},
	methods: {
		Pay() {
			this.$http
				.post("ctaporpagar/changeestatus", this.ctaporpagar)
				.then(res => {
					$("#Observacion").modal("hide");
					this.bus.$emit("List");
					this.$toast.success("Información Actualizada");
					this.Limpiar();
				})
				.catch(err => {
					this.$toast.error("La infromación no pudo actualizarse");
				});
		},
		getObservation() {
			this.$http
				.get("ctaporpagar/recovery", {
					params: { IdCtaPagar: this.ctaporpagar.IdCtaPagar }
				})
				.then(res => {
					const objCta = res.data.data.ctaporpagar;

					this.ctaporpagar = objCta;
					
					if (objCta.FechaRealPago == null) {
						this.ctaporpagar.FechaRealPago = new Date();
					} else {
						var uno = objCta.FechaRealPago.replace(/-/g, "\/");
						this.ctaporpagar.FechaRealPago = new Date(uno);

						if (this.ctaporpagar.Estatus == "SI") {
							this.bus.$off("Save");
						}
					}
					
				});
		},
		Limpiar() {
			this.ctaporpagar = {
				IdCtaPagar: 0,
				Observacion: "",
				Estatus: ""
			};
			this.date = "";
		}
	},

	created() {
		this.bus.$off("UploadO");
		this.bus.$on("UploadO", id => {
			this.ctaporpagar.IdCtaPagar = id;

			this.bus.$off("Save");
			this.bus.$on("Save", () => {
				this.Pay();
			});

			if (id > 0) {
				this.getObservation();
			}

			this.bus.$emit("Desbloqueo", false);
		});
	}
};
</script>
