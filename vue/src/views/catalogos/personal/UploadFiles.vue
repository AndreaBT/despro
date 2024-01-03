<template>
	<div>
		<div class="form-group">
			<button @click="agregarPago" type="button" class="btn btn-01">
				Subir más <i class="fa fa-plus-circle"></i>
			</button>

			<div class="justify-content-center">
				<div>
					<table class="table-01 text-nowrap mt-2">
						<tbody>
							<template v-for="(item, index) of form.pagos">
								<tr :key="index">
									<td class="text-center">
										<button
											@click="eliminarindex(index)"
											type="button"
											class="btn btn-01"
										>
											<i class="far fa-minus-circle"></i>
										</button>
									</td>
									<td
										class="table__td table__td--lg"
									>
										<div v-if="form.pagos[index].file">
											{{ form.pagos[index].titulo }}
										</div>

										<input
											v-if="!form.pagos[index].file"
											v-model="form.pagos[index].titulo"
											type="text"
											class="form-control"
											placeholder="Nombre Archivo"
											maxlength="150"
										/>
									</td>
									<td class="text-center">
										<div
											class="custom-file-input-image"
											v-if="!form.pagos[index].file"
										>
											<input
												class="custom-file-input"
												type="file"
												:ref="'element' + index"
												@change="uploadFile('element' + index, index)"
												accept="*"
											/>
											<input type="text" class="form-control" />
											<button type="button">
												<i class="fas fa-paperclip"></i>
											</button>
										</div>
										<div v-else>
											<template v-if="form.pagos[index].file">
												<a
													v-if="form.pagos[index].isNew > 0"
													:href="routefiles + form.pagos[index].file.name"
													target="__blank"
												>
													<span
														class="btn-danger btn  btn-sm disabled"
														style="border-radius: 100%"
													>
														<i class="far fa-file-download"></i>
													</span>
												</a>
												<span
													v-else
													class="btn-danger btn  btn-sm disabled"
													style="border-radius: 100%"
												>
													<i class="far fa-file-download"></i>
												</span>
											</template>
										</div>
									</td>
								</tr>
							</template>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import uniqid from "uniqid";

export default {
	name: "ChangePass",
	props: ["poBtnSave"],
	data() {
		return {
			routefiles: "",
			form: {
				pagos: [],
				oldfiles: []
			},
			trabajador: {},
			errorvalidacion: [],

			opcionesConceptosPago: [
				{ name: "enganche", label: "Enganche" },
				{ name: "fraccionado", label: "Pago fraccionado" },
				{ name: "pagounico", label: "Pago unico" }
			],

			baseUrl: ""
		};
	},
	methods: {
		openFileDialog(refPagoFile) {
			this.$refs[refPagoFile][0].click();
		},

		uploadFile(refPagoFile, index) {
			let file = this.$refs[refPagoFile][0].files[0];

			if (file) {
				this.form.pagos[index].file = {
					file,
					name: file.name
				};

				this.$emit("input", this.form);
			}
		},

		eliminarItemPago(idpago) {
			let index = this.form.pagos.findIndex(item => item.idpago == idpago);

			this.form.pagos.splice(index, 1);
		},

		eliminarPago(idpago) {
			this.eliminarItemPago(idpago);

			this.$emit("input", this.form);
		},

		eliminarindex(index) {
			this.form.pagos.splice(index, 1);
		},

		agregarPago() {
			//let idpago = this.form.pagos.length;
			let idArchivo = `__inventario-${uniqid()}`;

			this.form.pagos.push({
				isNew: 0,
				file: null,
				idArchivo,
				filename: "",
				titulo: ""
			});

			this.$emit("input", this.form);
		},
		get_list() {
			(this.form = {
				pagos: [],
				oldfiles: []
			}),
				this.$http
					.get("filestrabajador/get", {
						params: { IdTrabajador: this.trabajador.IdTrabajador, Tipo: 1 }
					})
					.then(res => {
						//res row trae lista de objetos completos
						res.data.data.row.forEach(element => {
							let idArchivo = `__inventario-${uniqid()}`;
							//pagos trae la lista con objetos completos
							this.form.pagos.push({
								isNew: 1,
								file: { name: element.File, file: {} },
								idArchivo,
								filename: element.File,
								titulo: element.Titulo
							});

							let tempFile = element.File;
							let tempTituloFile = element.Titulo;
							//oldfiles solo guarda datos cambiates (archivo y nombre)
							this.form.oldfiles.push({
								file: tempFile,
								titulo: tempTituloFile
							});
						});

						this.routefiles = res.data.routefiles;
					});
		},

		async Guardar() {
			if (this.form.pagos.length == this.form.oldfiles.length) {
				this.$toast.info("Debe agregar documentos o eliminar alguno");
			} else {
				this.poBtnSave.toast = 0;
				this.poBtnSave.disableBtn = true;

				let formData = new FormData();

				formData.set("IdTrabajador", this.trabajador.IdTrabajador);
				formData.set("Tipo", 1);

				let finalfiles = [];
				let finaltitulos = [];

				//por cada objeto en pagos
				for (let i = 0; i < this.form.pagos.length; i++) {
					if (
						this.form.pagos[i].isNew == 0 &&
						this.form.pagos[i].file != null
					) {
						let tempFile = this.form.pagos[i].file.file;
						let tempTitulo = this.form.pagos[i].titulo;
						formData.append("files[]", tempFile);
						formData.append("titulos[]", tempTitulo);
					} else {
						finalfiles.push(this.form.pagos[i].filename);
						finaltitulos.push(this.form.pagos[i].titulo);
					}
				}

				formData.set("finaltitulos", JSON.stringify(finaltitulos));
				formData.set("finalfiles", JSON.stringify(finalfiles));
				formData.set("oldfiles", JSON.stringify(this.form.oldfiles));

				await this.$http
					.post("filestrabajador/post", formData, {
						headers: {
							"Content-Type": "multipart/form-data"
						}
					})
					.then(res => {
						this.poBtnSave.disableBtn = false;
						this.poBtnSave.toast = 1;

						$("#UploadFiles").modal("hide");
						this.bus.$emit("List");
					})
					.catch(err => {
						this.poBtnSave.disableBtn = false;
						this.poBtnSave.toast = 2;
					});
			}
		},

		Limpiar() {
			this.errorvalidacion = [];
			this.trabajador = {};
		}
	},

	created() {
		(this.form = {
			pagos: [],
			oldfiles: []
		}),
			this.bus.$off("UploadP");
		this.bus.$on("UploadP", Id => {
			this.poBtnSave.disableBtn = false;
			this.bus.$off("Save");
			this.bus.$on("Save", () => {
				this.Guardar();
			});

			this.trabajador.IdTrabajador = Id;
			this.get_list();
		});
	}
};
</script>
