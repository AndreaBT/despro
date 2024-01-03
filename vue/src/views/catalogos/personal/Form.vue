<style>
.upload-btn-wrapper {
	position: relative;
	overflow: hidden;
	display: inline-block;
	cursor: pointer;
}

.upload-btn-wrapper input[type="file"] {
	font-size: 100px;
	position: absolute;
	left: 0;
	top: 0;
	opacity: 0;
}
</style>

<template>
	<div>
		<div class="modal-body form-cotizacion">
			<div class="form-group  row justify-content-center">
				<div class="col-md-12 col-lg-8 grid-r">
					<div class="form-group form-row">
						<div class="col-md-5 col-lg-5">
							<div class="avatar-upload">
								<div class="avatar-edit">
									<input
										id="file"
										ref="file"
										type="file"
										name="myfile"
										@change="uploadImage()"
										accept=".png, .jpg, .jpeg"
									/>
									<label for="file"></label>
								</div>
								<div class="avatar-preview">
									<div
										id="imagePreview"
										style="background-image: url(Img);"
									></div>
								</div>
							</div>
							<label>Recomendada 400 x 400</label>
						</div>
						<div class="col-md-7 col-lg-7">
							<div class="form-group">
								<label>Nombre</label>
								<input
									type="text"
									v-model="trabajador.Nombre"
									class="form-control"
									placeholder="Nombre"
								/>
								<Cvalidation v-if="this.errorvalidacion.Nombre" :Mensaje="errorvalidacion.Nombre[0]"></Cvalidation>
								
							</div>
							<div class="form-group">
								<label>Categoría</label>
								<select v-model="trabajador.IdCategoria" class="form-control">
									<option value="">--Selecccionar Categoría--</option>
									<option
										:value="lista.IdCategoria"
										v-for="(lista, key, index) in ListaCategoria"
										:key="index"
										>{{ lista.Nombre }}</option
									>
								</select>
								<Cvalidation v-if="this.errorvalidacion.Categoria" :Mensaje="errorvalidacion.Categoria[0]"></Cvalidation>
							</div>

							<div class="form-group">
								<label>Perfil</label>
								<select v-model="trabajador.IdPerfil" class="form-control">
									<option :value="''">--Seleccionar Perfil--</option>
									<option
										:value="item.IdPerfil"
										v-for="(item, index) in ListaPerfil"
										:key="index"
										>{{ item.Nombre }}</option
									>
								</select>
								<Cvalidation v-if="this.errorvalidacion.Perfil" :Mensaje="errorvalidacion.Perfil[0]"></Cvalidation>
							</div>
						</div>
					</div>
					<div class="form-group form-row">
						<div class="col-md-6 col-lg-6">
							<div class="form-group">
								<label>Profesión</label>
								<input
									v-model="trabajador.Profesion"
									type="text"
									class="form-control"
									placeholder="Profesión"
								/>
								<Cvalidation v-if="this.errorvalidacion.Profesion" :Mensaje="errorvalidacion.Profesion[0]"></Cvalidation>
							</div>
						</div>
						<div class="col-md-6 col-lg-6">
							<div class="form-group">
								<label>Teléfono</label>
								<input
									type="text"
									v-model="trabajador.Telefono"
									class="form-control"
									placeholder="Teléfono"
								/>
								<Cvalidation
									v-if="this.errorvalidacion.Telefono"
									:Mensaje="errorvalidacion.Telefono[0]"
								></Cvalidation>
							</div>
						</div>
					</div>
					<div class="form-group form-row">
						<div class="col-md-6 col-lg-12">
							<div class="form-group">
								<label>Correo</label>
								<input
									type="text"
									:readonly="Readonly"
									v-model="trabajador.Correo"
									class="form-control"
									placeholder="Ejem. email@email.com"
								/>
								<Cvalidation
									v-if="this.errorvalidacion.Correo"
									:Mensaje="errorvalidacion.Correo[0]"
								></Cvalidation>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 col-lg-12 d-none d-md-block d-lg-none">
					<hr />
				</div>
				<div class="col-md-6 col-lg-4">
					<div class=" form-group text-center mt-2">
						<button
							type="button"
							@click="get_calculadora"
							data-toggle="modal"
							data-target="#ModalCalculadora"
							data-backdrop="static"
							class="btn btn-01 cal"
						>
							Calculadora
						</button>
					</div>

					<div class="form-group form-row">
						<div class="col-md-6 col-lg-6">
							<label class="small">Mano de Obra</label>
							<vue-numeric
								:minus="false"
								class="form-control  "
								currency=""
								separator=","
								:precision="0"
								v-model="trabajador.CostoHora"
							></vue-numeric>
							<Cvalidation
								v-if="this.errorvalidacion.CostoHora"
								:Mensaje="'Campo obligatorio'"
							></Cvalidation>
						</div>

						<div class="col-md-6 col-lg-6">
							<label class="small">Burden/Hrs.</label>
							<vue-numeric
								:minus="false"
								class="form-control  "
								currency=""
								separator=","
								:precision="0"
								v-model="trabajador.CostoAnual"
							></vue-numeric>
							<Cvalidation
								v-if="this.errorvalidacion.CostoAnual"
								:Mensaje="'Campo obligatorio'"
							></Cvalidation>
						</div>
					</div>
					<div class="form-group form-row">
						<div class="col-md-6 col-lg-6">
							<label class="small">Hors. Trab./Semanal</label>
							<vue-numeric
								:minus="false"
								class="form-control  "
								currency=""
								separator=","
								:precision="0"
								v-model="trabajador.HorasTS"
							></vue-numeric>
							<Cvalidation
								v-if="this.errorvalidacion.HorasTS"
								:Mensaje="'Campo obligatorio'"
							></Cvalidation>
						</div>
						<div class="col-md-6 col-lg-6">
							<label class="small">Hrs. Produc./Semanal</label>
							<vue-numeric
								:minus="false"
								class="form-control  "
								currency=""
								separator=","
								:precision="0"
								v-model="trabajador.HorasPS"
							></vue-numeric>
							<Cvalidation
								v-if="this.errorvalidacion.HorasPS"
								:Mensaje="'Campo obligatorio'"
							></Cvalidation>
						</div>
					</div>
				</div>
				<div class="col-md-12 col-lg-12">
					<hr />
				</div>
			</div>
			<div v-if="trabajador.IdTrabajador == 0" class="form-group form-row">
				<div class="col-md-6 col-lg-6">
					<label>Usuario</label>
					<input
						type="text"
						readonly
						v-model="trabajador.Correo"
						class="form-control"
						placeholder=""
					/>
					<Cvalidation
						v-if="this.errorvalidacion.Usuario"
						:Mensaje="errorvalidacion.Usuario[0]"
					></Cvalidation>
				</div>
				<div class="col-md-3 col-lg-3">
					<label>Contraseña</label>
					<input
						v-model="trabajador.Pass"
						type="password"
						class="form-control"
						placeholder=""
					/>
					<Cvalidation
						v-if="this.errorvalidacion.Pass"
						:Mensaje="'Campo obligatorio'"
					></Cvalidation>
				</div>
				<div class="col-md-3 col-lg-3">
					<label>Confirmar Contraseña</label>
					<input
						type="password"
						v-model="trabajador.Pass2"
						class="form-control"
						placeholder=""
					/>
					<Cvalidation
						v-if="this.errorvalidacion.Password_Confirmacion"
						:Mensaje="'Campo obligatorio'"
					></Cvalidation>
				</div>
			</div>
			<div class="form-group form-row">
				<div class="col-md-6 col-lg-6">
					<label>Comentarios</label>
					<textarea
						class="form-control"
						v-model="trabajador.Observaciones"
						rows="2"
						placeholder="Escribir Comentario..."
					></textarea>
				</div>
				<div class="col-md-6 col-lg-6">
					<label>Inventario</label>
					<textarea
						class="form-control"
						v-model="trabajador.Inventario"
						rows="2"
						placeholder="Escribir Inventario..."
					></textarea>
				</div>
			</div>
		</div>

		<Modal
			:TipoM="2"
			:size="'modal-sm'"
			:Showbutton="false"
			:NameModal="'ModalCalculadora'"
			:Nombre="'Calculadora'"
		>
			<template slot="Form">
				<Calculo :Calculadora="Calculadora" :trabajador="trabajador"></Calculo>
			</template>
		</Modal>
	</div>
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import $$ from "jquery";
import Cbtnsave from "@/components/Cbtnsave.vue";
import Cvalidation from "@/components/Cvalidation.vue";
import Modal from "@/components/Cmodal.vue";
import ImagenDefault from "@/images/foto.png";

import Calculo from "@/views/catalogos/personal/Calculo.vue";
export default {
	name: "Form",
	props: ["IdTrabajador", "poBtnSave"],
	data() {
		return {
			Modal: true, //Sirve pra los botones de guardado
			FormName: "Form Personal", //Sirve para donde va regresar
			ListaCategoria: [],
			errorvalidacion: [],
			previewImage: null,
			trabajador: {
				IdTrabajador: 0,
				Nombre: "",
				Telefono: "",
				Profesion: "",
				Categoria: "",
				CostoHora: "",
				HorasTS: "",
				HorasPS: "",
				CostoAnual: "",
				IdSucursal: "",
				Usuario: "",
				Pass: "",
				Observaciones: "",
				Perfil: "",
				IdCategoria: "",
				IdRol: "",
				IdUsuario: "",
				Correo: "",
				Estatus: "",
				Token: "",
				EstadoChat: "",
				IdTipoProceso: "",
				UpdateApp: "",
				GastoAsignado: "",
				IdCajaC: "",
				Inventario: "",
				Imagen: "",
				Foto2: "",
				IdPerfil: "",
				Pass2: ""
			},
			urlApi: "trabajador/recovery",
			urlApiCategoria: "categoriapersonal/get",
			Img: null,
			ListaPerfil: [],
			Calculadora: { MO: "", HTS: "", HPS: "" },
			Readonly: false
		};
	},
	components: {
		Cbtnsave,
		Cvalidation,
		Modal,
		Calculo
	},
	methods: {
		uploadImage() {
			this.trabajador.Imagen = this.$refs.file.files[0];
			const image = this.$refs.file.files[0];
			const reader = new FileReader();
			var img = "";
			reader.readAsDataURL(image);

			reader.onload = e => {
				this.Img = e.target.result;
				this.readURL(this.Img);
			};
		},
		cargarImagen() {},
		listaCategoria() {
			this.$http
				.get(this.urlApiCategoria, {
					params: { RegEstatus: "A" }
				})
				.then(res => {
					this.ListaCategoria = res.data.data.categoriapersonal;
				});
		},
		async Guardar() {
			let usuario = "";
			let correo = "";
			if (this.trabajador.Usuario != "") {
				usuario = this.trabajador.Usuario.trim();
			}
			if (this.trabajador.Correo != "") {
				correo = this.trabajador.Correo.trim();
			}
			//deshabilita botones
			this.poBtnSave.toast = 0;
			this.poBtnSave.disableBtn = true;

			let formData = new FormData();

			formData.set("IdTrabajador", this.trabajador.IdTrabajador);
			formData.set("Nombre", this.trabajador.Nombre);
			formData.set("Telefono", this.trabajador.Telefono);
			formData.set("Profesion", this.trabajador.Profesion);
			formData.set("Categoria", this.trabajador.Categoria);
			formData.set("CostoHora", this.trabajador.CostoHora);
			formData.set("CostoAnual", this.trabajador.CostoAnual);
			formData.set("IdSucursal", this.trabajador.IdSucursal);
			formData.set("Usuario", usuario);
			formData.set("Pass", this.trabajador.Pass);
			formData.set("Pass2", this.trabajador.Pass2);
			formData.set("Observaciones", this.trabajador.Observaciones);
			formData.set("Perfil", this.trabajador.Perfil);
			formData.set("HorasTS", this.trabajador.HorasTS);
			formData.set("HorasPS", this.trabajador.HorasPS);
			formData.set("IdCategoria", this.trabajador.IdCategoria);
			formData.set("IdPerfil", this.trabajador.IdPerfil);
			formData.set("IdUsuario", this.trabajador.IdUsuario);
			formData.set("Correo", correo);
			formData.set("Estatus", this.trabajador.Estatus);
			formData.set("Token", this.trabajador.Token);
			formData.set("EstadoChat", this.trabajador.EstadoChat);
			formData.set("IdTipoProceso", this.trabajador.IdTipoProceso);
			formData.set("UpdateApp", this.trabajador.UpdateApp);
			formData.set("GastoAsignado", this.trabajador.GastoAsignado);
			formData.set("IdCajaC", this.trabajador.IdCajaC);
			formData.set("Inventario", this.trabajador.Inventario);
			formData.set("NombreFoto", this.trabajador.Foto2);
			let file = this.$refs.file.files[0];
			formData.append("File", file);

			await this.$http
				.post("trabajador/post", formData, {
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
					this.poBtnSave.disableBtn = false;
					this.poBtnSave.toast = 2;
					this.errorvalidacion = err.response.data.message.errores;
				});
		},
		Limpiar() {
			this.Img = "";
			(this.trabajador.IdTrabajador = 0),
				(this.trabajador.Nombre = ""),
				(this.trabajador.Telefono = ""),
				(this.trabajador.Profesion = ""),
				(this.trabajador.Categoria = ""),
				(this.trabajador.CostoAnual = ""),
				(this.trabajador.IdSucursal = ""),
				(this.trabajador.Usuario = ""),
				(this.trabajador.Pass = ""),
				(this.trabajador.Pass2 = ""),
				(this.trabajador.Observaciones = ""),
				(this.trabajador.Perfil = ""),
				(this.trabajador.HorasTS = ""),
				(this.trabajador.HorasPS = ""),
				(this.trabajador.CostoHora = ""),
				(this.trabajador.IdCategoria = ""),
				(this.trabajador.IdPerfil = ""),
				(this.trabajador.IdUsuario = ""),
				(this.trabajador.Correo = ""),
				(this.trabajador.Estatus = ""),
				(this.trabajador.Token = ""),
				(this.trabajador.EstadoChat = ""),
				(this.trabajador.IdTipoProceso = ""),
				(this.trabajador.UpdateApp = ""),
				(this.trabajador.GastoAsignado = ""),
				(this.trabajador.IdCajaC = ""),
				(this.trabajador.Inventario = ""),
				(this.trabajador.Imagen = "");
			this.trabajador.Imagen2 = "";
			this.errorvalidacion = [""];
			const input = this.$refs.file;
			(input.type = "text"), (input.type = "file");
			this.readURL(this.Img);
			this.Readonly = false;
		},
		get_one() {
			this.$http
				.get(this.urlApi, {
					params: { IdTrabajador: this.trabajador.IdTrabajador }
				})
				.then(res => {
					this.trabajador = res.data.data.trabajador;
					this.trabajador.Pass2 = "";

					let imagn = res.data.data.trabajador.Imagen;
					let Fotgn = res.data.data.trabajador.Foto;
					let Fot2gn = res.data.data.trabajador.Foto2;
					let defImg = "";

					if (imagn != "" && imagn != null) {
						this.Img = "data:image/png;base64," + imagn;
					} else if (Fotgn != "" && Fotgn != null) {
						this.Img = res.data.data.UrlFoto + this.trabajador.Foto2;
					} else if (Fot2gn != "" && Fot2gn != null) {
						this.Img = res.data.data.UrlFoto + this.trabajador.Foto2;
					} else {
						this.Img = ImagenDefault;
					}

					//this.Img= res.data.data.UrlFoto+ this.trabajador.Foto2;
					this.readURL(this.Img);

					if (this.trabajador.IdTrabajador > 0) {
						this.Readonly = true;
					}
				});
		},
		get_calculadora() {
			//this.$emit('titulomodal','Calculadora');
		},
		get_perfil() {
			this.$http
				.get("perfil/get", {
					params: {}
				})
				.then(res => {
					this.ListaPerfil = res.data.data.perfil;
					this.ListaPerfil.shift(); //shift() remueve el primer elemento del array
				});
		},
		readURL(input) {
			$$("#imagePreview").css("background-image", "url(" + this.Img + ")");
			$$("#imagePreview").hide();
			$$("#imagePreview").fadeIn(650);
		}
	},
	created() {
		this.bus.$off("Nuevo");
		this.bus.$off("GetCalculo");
		this.listaCategoria();
		this.get_perfil();

		this.bus.$on("GetCalculo", obj => {
			this.get_datacalculo(obj);
		});

		//Este es para modal
		this.bus.$on("Nuevo", (data, Id) => {
			this.poBtnSave.disableBtn = false;
			this.bus.$off("Save");
			this.bus.$on("Save", () => {
				this.Guardar();
			});

			this.Limpiar();
			if (Id > 0) {
				this.trabajador.IdTrabajador = Id;
				this.get_one();
			}
		});
		if (this.Id != undefined) {
			this.trabajador.IdTrabajador = this.Id;
			this.get_one();
		}
	},
	computed: {}
};
</script>
