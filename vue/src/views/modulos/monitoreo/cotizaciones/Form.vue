<template>
	<form class="form-horizontal">
		<div class="row">
			<div class="col-lg-4">
				<div class="form-group">
					<label>Sucursal</label>
					<select class="form-control" v-model="pdfclientes.IdClienteS">
						<option :value="''">Seleccione una opción</option>
						<option
							v-for="(item, index) in ListaSucursales"
							:key="index"
							:value="item.IdClienteS"
							>{{ item.Nombre }}</option
						>
					</select>
					<label id="lblmsuser" style="color:red"
						><Cvalidation
							v-if="this.errorvalidacion.IdClienteS"
							:Mensaje="errorvalidacion.IdClienteS[0]"
						></Cvalidation
					></label>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="form-group">
					<label>Nombre del Archivo</label>
					<input
						type="text"
						class="form-control"
						v-model="pdfclientes.Titulo"
					/>
					<label id="lblmsuser" style="color:red"
						><Cvalidation
							v-if="this.errorvalidacion.Titulo"
							:Mensaje="errorvalidacion.Titulo[0]"
						></Cvalidation
					></label>
				</div>
			</div>

			<div class="col-lg-4">
				<label>Buscar Documento</label>
				<div class="custom-file-input-image">
					<input
						@change="uploadImage()"
						type="file"
						accept="application/pdf"
						ref="file"
						class="custom-file-input"
						id="validatedCustomFile"
						required
					/>
					<input type="text" v-model="NameFile" class="form-control" />
					<button type="button" class="">
						<i class="fas fa-paperclip"></i>
					</button>
				</div>
				<label id="lblmsuser" style="color:red"
					><Cvalidation
						v-if="this.errorvalidacion.Archivo"
						:Mensaje="errorvalidacion.Archivo[0]"
					></Cvalidation
				></label>
			</div>
		</div>
	</form>
</template>

<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal

export default {
	name: "Form",
	props: ["oCliente", "poBtnSave"],
	data() {
		return {
			pdfclientes: {
				IdPdf: 0,
				NombreArchivo: "",
				Titulo: "",
				IdCliente: 0,
				IdClienteS: "",
				Tipo: 0
			},
			urlApi: "categorias/recovery",
			errorvalidacion: [],
			Img: null,
			NameFile: "Elegir archivo (5 MB)",
			ListaSucursales: []
		};
	},
	components: {},
	methods: {
		async Guardar() {
			//deshabilita botones
			this.poBtnSave.toast = 0;
			this.poBtnSave.disableBtn = true;
			let formData = new FormData();
			formData.set("IdPdf", this.pdfclientes.IdPdf);
			formData.set("IdCliente", this.pdfclientes.IdCliente);
			formData.set("IdClienteS", this.pdfclientes.IdClienteS);
			formData.set("Titulo", this.pdfclientes.Titulo);
			formData.set("Tipo", this.pdfclientes.Tipo);
			formData.set("FilePrevious", this.pdfclientes.NombreArchivo);
			let file = this.$refs.file.files[0];
			formData.append("File", file);
			await this.$http
				.post("monitoreo/cotizacionesadd", formData, {
					headers: {
						"Content-Type": "multipart/form-data"
					}
				})
				.then(res => {
					//this.bus.$emit('BloquearBtn',1);
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
			this.pdfclientes.IdPdf = 0;
			this.pdfclientes.NombreArchivo = "";
			this.pdfclientes.Titulo = "";

			// const input = this.$refs.file;
			// input.type = "text";
			// input.type = "file";
			this.NameFile='Elegir archivo (5 MB)';
            this.FilePrevious='';
            this.Archivo='';
		},
		// get_one() {
		// 	this.$http
		// 		.get("monitoreo/cotizacionesget", {
		// 			params: { IdPdf: this.pdfclientes.IdPdf }
		// 		})
		// 		.then(res => {
		// 			this.pdfclientes = res.data.data.pdfclientes;
		// 			//this.Img = res.data.data.RutaPdf+this.pdfclientes.NombreArchivo;
		// 		});
		// },
		get_ListSucursal() {
			this.$http
				.get("clientesucursal/get", {
					params: { IdCliente: this.pdfclientes.IdCliente }
				})
				.then(res => {
					this.ListaSucursales = res.data.data.clientesucursal;
					//this.Img = res.data.data.RutaPdf+this.pdfclientes.NombreArchivo;
				});
		},
		uploadImage() {
			const image = this.$refs.file.files[0];

			var FileSize = image.size / 1024 / 1024; // in MB
			if (FileSize > 5) {
				this.$toast.info("Solo se puede subir archivos menores a 5 MB");
				const input = this.$refs.file;
				input.type = "text";
				input.type = "file";
				return false;
			}

			var allowedExtensions = /(\.pdf|\.PDF)$/i;
			if (!allowedExtensions.exec(image.name)) {
				this.$toast.info("Extenciones permitidas .pdf");
				const input = this.$refs.file;
				input.type = "text";
				input.type = "file";
				this.NameFile = "Elegir archivo (5 MB)";
				return false;
			}

			this.NameFile = image.name;
			/*
            const reader = new FileReader();
            var img="";
            reader.readAsDataURL(image);
            reader.onload= e =>{
                this.Img = e.target.result;     
            }*/
		}
	},
	beforeCreate() {},
	created() {
		this.bus.$off("Save");
		this.bus.$off("Nuevo");
		this.bus.$on("Save", () => {
			this.Guardar();
		});
		 this.Limpiar();
	},
	mounted() {
		this.bus.$on("Nuevo", () => {
			this.poBtnSave.disableBtn = false;
			this.pdfclientes.IdCliente = this.oCliente.IdCliente;
			this.pdfclientes.Tipo = this.oCliente.Tipo;
			this.get_ListSucursal();
			this.bus.$emit("BloquearBtn", 3);
			this.Limpiar();
		});
	}
};
</script>
