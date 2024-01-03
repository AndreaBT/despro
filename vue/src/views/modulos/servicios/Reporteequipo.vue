<template>
	<div class="card mb-3">
		<div v-for="(item, index) in equipos" :key="index" class="card-body">
			<div class="row">
				<div class="col-md-12 col-lg-12">
					<div class="checkbox">
						<label>
							<input
								v-model="item.Permitir"
								type="checkbox"
								name="optionsCheckboxes"
							/><span class="checkbox-material-green"
								><span class="check"></span
							></span>
							{{ item.Nequipo }}
						</label>
					</div>
					<hr />
				</div>
				<div class="col-md-12 col-lg-12 mb-1"></div>
				<div class="col-md-6 col-lg-6">
					<label>Observación original</label>
					<textarea
						class="form-control"
						v-model="item.Comentario"
						rows="2"
						readonly
					></textarea>
				</div>
				<div class="col-md-6 col-lg-6">
					<label>Editar observación</label>
					<textarea
						class="form-control"
						v-model="item.Comentario2"
						rows="2"
					></textarea>
				</div>
			</div>
			<div class="row mt-3">
				<div class="col-md-12 col-lg-12">
					<h4>Fotos equipo {{ item.Equipo }}</h4>
					<hr />
				</div>
			</div>
			<div class="row">
				<div
					v-for="(item, index) in item.Imagenes"
					:key="index"
					class="col-md-4 col-lg-4 mb-4"
				>
					<ImgRoate
						class="ajustar"
						:item="item"
						:ruta="Ruta"
						:img="item.FileEquipo"
					></ImgRoate>

					<label>Observación original</label>
					<textarea
						v-model="item.Descripcion"
						disabled
						class="form-control"
						rows="2"
					></textarea>

					<label>Editar observación</label>
					<textarea
						v-model="item.Descripcion2"
						maxlength="289"
						placeholder="Observación"
						class="form-control"
						rows="2"
					></textarea>
				</div>
			</div>
		</div>
		<div class="f1-buttons mt-4">
			<button
				type="button"
				:disabled="Disablebtn"
				class="btn btn-01"
				@click="Guardar"
			>
				<i class="fa fa-spinner fa-pulse fa-1x fa-fw" v-show="Disablebtn"></i
				><i class="fa fa-plus-circle"></i> {{ txtSave }}
			</button>
		</div>
	</div>
</template>
<script>
import ImgRoate from "@/views/modulos/servicios/componentes/imgrotate.vue";

export default {
	components: {
		ImgRoate
	},
	data() {
		return {
			position: "right bottom",
			valor: 1,

			equipos: [],
			Ruta: "",
			Disablebtn: false,
			IdServicio: 0,
			txtSave: "Guardar"
		};
	},
	filters: {
		truncate(text, stop, clamp) {
			return text.slice(0, stop) + (stop < text.length ? clamp || "..." : "");
		}
	},
	methods: {
		
		validateimagen(item) {
			if (item.Tipo == 1) {
				return this.ruta1 + item.Imagen;
			}

			if (item.Tipo == 0) {
				return this.ruta2 + item.Imagen;
			}
		},

		getImagenes(IdServicio) {
			this.IdServicio = IdServicio;
			this.$http
				.get("imageneservicio/getequipos", {
					params: { IdServicio: IdServicio }
				})
				.then(res => {
					this.equipos = [];

					res.data.data.equipos.forEach(element => {
						
						let Imgs = [];
						element.Imagenes.forEach(item => {

							Imgs.push({
								Contador: item.Contador,
								Descripcion: item.Descripcion,
								Descripcion2: item.Descripcion2,
								Equipo: item.Equipo,
								Fecha: item.Fecha,
								FileEquipo: item.FileEquipo,
								IdEquipo: item.IdEquipo,
								IdServicio: item.IdServicio,
								Imagen: item.Imagen,
								Mostrar: item.Mostrar,
								Posicion: 0,
								Status: item.Status,
								Tipo: item.Tipo,
								isRotated: 0,
							});
						});


						this.equipos.push({
							Comentario: element.Comentario,
							Comentario2: element.Comentario2,
							IdEquipo: element.IdEquipo,
							Nequipo: element.Nequipo,
							Permitir: element.Permitir,
							Status: element.Status,
							Imagenes:Imgs,
						})

						
					});


					console.log(this.equipos);

					this.Ruta = res.data.data.ruta;
					this.ruta1 = res.data.data.ruta1;
					this.ruta2 = res.data.data.ruta2;
				});
		},

		Guardar() {
			if (this.equipos.length > 0) {
				this.Disablebtn = true;
				this.txtSave = " Espere...";
				this.$http
					.post("imageneservicio/post", {
						equipos: this.equipos,
						IdServicio: this.IdServicio
					})
					.then(res => {
						this.Disablebtn = false;
						this.txtSave = " Guardar";

						$("#ModalReporte2").modal("hide");
						document.body.classList.add("modal-open");
						this.$toast.info("Información guardada");
					})
					.catch(err => {
						this.Disablebtn = false;
						this.txtSave = " Guardar";
						this.$toast.warning("Complete la Información");
					});
			} else {
				this.$toast.info("No hay Información por Guardar");
			}
		}
	},
	created() {
		this.bus.$off("OpenRepor2");
		this.bus.$on("OpenRepor2", IdServicio => {
			this.getImagenes(IdServicio);
		});
	}
};
</script>
