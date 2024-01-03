<template>
	<div class="row">
		<!-- <button class="form-control" type="btn" @click="Rotar(valor)">Rotar</button>
        
          <croppa 
          v-model="croppa"
          initial-image="https://zhanziyang.github.io/vue-croppa/static/500.jpeg"
          initial-size="natural"
          
          >
          </croppa> -->

		<div
			v-for="(item, index) in imagenes"
			:key="index"
			class="col-md-4 col-lg-4 mb-4"
		>
			<ImgRoate
				class="ajustar"
				:item="item"
				:ruta="Ruta"
				:img="item.FileEquipo"
			></ImgRoate>

			<!-- <div class="ajustar">
                <div class="float_checkbox">
                  <label class="check_box">
                    <input type="checkbox" v-model="item.Mostrar">
                    <span class="checkmark"></span>
                  </label>
                </div>
                <img :src="Ruta +item.Imagen" alt="..." class="img-thumbnail img-fluid">
               
              </div>-->
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
		<div class="col-md-12 col-lg-12 mt-2">
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
			croppa: {},
			position: "right bottom",
			valor: 1,

			imagenes: [],
			Ruta: "",
			Disablebtn: false,
			IdServicio: 0,
			txtSave: "Guardar"
		};
	},
	methods: {
		/*Rotar(val){
      
      this.valor = val + 1;

      if(this.valor == 6){
        this.valor = 1;
        val = 1;
      }
      

      if(val == 1){
        this.croppa.rotate();
      }else if(val == 2){
        this.croppa.rotate(2);
      }else if(val == 3){
        this.croppa.rotate(-1);
      }else if(val == 4){
        this.croppa.flipX();
      }else if(val == 5){
        this.croppa.flipY();
      }
      
    },*/

		getImagenes(IdServicio) {
			this.IdServicio = IdServicio;
			this.$http
				.get("imageneservicio/get", {
					params: { IdServicio: IdServicio }
				})
				.then(res => {
					//this.imagenes =res.data.data.imagenes;
					this.imagenes = [];
					res.data.data.imagenes.forEach(element => {
						this.imagenes.push({
							Contador: element.Contador,
							Descripcion: element.Descripcion,
							Descripcion2: element.Descripcion2,
							Fecha: element.Fecha,
							FileEquipo: element.FileEquipo,
							IdEquipo: element.IdEquipo,
							IdServicio: element.IdServicio,
							Imagen: element.Imagen,
							Mostrar: element.Mostrar,
							Posicion: 0,
							Tipo: element.Tipo,
							isRotated: 0
						});
					});
					this.Ruta = res.data.data.ruta;
				});
		},

		Guardar() {
			if (this.imagenes.length > 0) {
				this.Disablebtn = true;
				this.txtSave = " Espere...";
				this.$http
					.post("imageneservicio/postImages", {
						imagenes: this.imagenes,
						IdServicio: this.IdServicio
					})
					.then(res => {
						this.Disablebtn = false;
						this.txtSave = " Guardar";

						$("#ModalReporte1").modal("hide");
						document.body.classList.add("modal-open");
						this.$toast.success("Información guardada");
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
		this.bus.$off("OpenRepor1");
		this.bus.$on("OpenRepor1", IdServicio => {
			this.getImagenes(IdServicio);
		});
	}
};
</script>
