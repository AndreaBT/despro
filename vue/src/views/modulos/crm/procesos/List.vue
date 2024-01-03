<template>
	<div>
		<section class="container-fluid mt-2">
			<Menu :pSitio="NombreSeccion" :pSitioAtras="SeccionAtras" :pRegresar="IrAtrasNormal">
                <template slot="BtnInicio">
                    <button type="button" data-toggle="modal" data-target="#ModalForm"  data-backdrop="static" data-keyboard="false" class="btn btn-01 mr-2" @click="Nuevo()">Nuevo</button>
                </template>
            </Menu>

			<!--vue draggable-->
			<div class="row justify-content-center mt-3">
				<div class="col-md-12 col-lg-12">
					<ul class="">
						<draggable @change="Cambiar" v-model="Listaprocesos" :list="Listaprocesos" :move="checkMove" tag="" class="pro-barra-pm2" style="background: white;" >
							<li :style="'background-color: ' + lista.Color + ';'" v-for="(lista, key, index) in Listaprocesos" :key="index" >
								{{ lista.Nombre }}
								<div style="margin-left:4px" class="arrow" :style="'background-color: ' + lista.Color + ';'" >
									<i class="fas fa-arrow-right" aria-hidden="true"></i>
								</div>
								<button v-show="
										lista.Nombre != 'Propuestas' &&
										lista.Nombre != 'Prospectar' &&
										lista.Nombre != 'Cierre' &&
										lista.Nombre != 'Llamada en frio' &&
										lista.Nombre != 'Reunion de ventas'"
									type="button"
									class="btn-icon-proce"
									title="Editar"
									@click="Editar(lista.IdCrmProceso)"
									data-toggle="modal"
									data-target="#ModalForm"
									data-backdrop="static"
									data-keyboard="false"
								>
									<i class="fas fa-pencil"></i>
								</button>
								<button
									v-show="
										lista.Nombre != 'Propuestas' &&
										lista.Nombre != 'Prospectar' &&
										lista.Nombre != 'Cierre' &&
										lista.Nombre != 'Llamada en frio' &&
										lista.Nombre != 'Reunion de ventas'"
									type="button"
									title="Eliminar"
									@click="Eliminar(lista.IdCrmProceso)"
									class="btn-icon-proce-2"
								>
									<i class="fas fa-trash-alt"></i>
								</button>
							</li>
						</draggable>
					</ul>
				</div>
			</div>
		</section>

		<Modal :size="size" :Nombre="'Proceso'" :poBtnSave="oBtnSave">
			<template slot="Form">
				<Form :NameList="NameForm" :ocliente="ocliente" @titulomodal="Change" :poBtnSave="oBtnSave" >
				</Form>
			</template>
		</Modal>
	</div>
</template>
<script>
import Modal from "@/components/Cmodal.vue";
import Clist from "@/components/Clist.vue";
import Cbtnaccion from "@/components/Cbtnaccion.vue";
import Form from "../procesos/Form.vue";
import Menu from "../indexMenu.vue";

export default {
	//name :'list',
	//name: "Personal",
	name: "functional",
	display: "Functional third party",
	order: 17,
	props: ["ocliente", "tipolistp"],
	components: {
		Modal,
		Clist,
		Cbtnaccion,
		Form,
		Menu,
	},
	data() {
		return {
			hover: false,
			enabled: true,
			rows: [
				{
					index: 1,
					items: [
						{
							title: "item 1"
						}
					]
				},
				{
					index: 2,
					items: [
						{
							title: "item 2"
						},
						{
							title: "item 3"
						}
					]
				}
			],
			NombreSeccion: 'Etapas del Proceso',
			SeccionAtras: 'Procesos',
			IrAtrasNormal: false,
			FormName: "clientesForm", //Por si no es modal y queremos ir a una vista declarada en el router
			EsModal: true, //indica si es modal o no
			size: "modal-lg",
			NameList: "Tipo de proceso :",
			urlApi: "crmprocesos/list",
			Listaprocesos: [],
			numero: 0,
			ListaHeader: [],
			TotalPagina: 2,
			Pag: 0,
			oClienteP: {},
			NameForm: "Tipo de proceso : ",
			TipoList: "",
			ShowBtns: true,
			Filtro: {
				Nombre: "",
				Placeholder: "Nombre..",
				TotalItem: 0,
				Pagina: 1,
				Entrada: 10,
			},
			oBtnSave: {
				//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
				isModal: true,
				disableBtn: false,
				toast: 0
			},
			EsModal2: true, //indica si es modal o no,
			disabled: true
		};
	},
	methods: {
		checkMove: function(evt) {
			if (evt.draggedContext.element.Nombre == "Cierre") {
				return false;
			}

			return true;
		},
		validate(name) {
			if (name == "Cierre" || name == "Cierre") {
				this.disabled = true;
			} else {
				this.disabled = false;
			}
		},
		Substraer(Nombre) {
			let name = "";
			if (Nombre != null && Nombre != "") {
				name = Nombre.substr(0, 23);
			}
			return name;
		},
		async Cambiar() {
			let formData = new FormData();
			formData.set("Lista", JSON.stringify(this.Listaprocesos));

			await this.$http
				.post("crmprocesos/changeposition", formData, {
					headers: {
						"Content-Type": "multipart/form-data"
					}
				})
				.then(res => {})
				.catch(err => {});
		},
		Contactos() {
			this.$router.push({ name: "crmcontactos", params: {} });
		},
		Oportunidad() {
			this.$router.push({ name: "crmoportunidad", params: {} });
		},
		Procesos() {
			this.$router.push({ name: "crmtiposprocesos", params: {} });
		},
		vendedores() {
			this.$router.push({ name: "crmvendedores", params: {} });
		},
		pipedrive() {
			this.$router.push({ name: "crmpipedrive", params: {} });
		},
		forecast() {
			this.$router.push({ name: "crmforecast", params: {} });
		},
		Nuevo() {
			if (this.oBtnSave.isModal == true) {
				this.bus.$emit("Nuevo", true);
			} else {
				this.bus.$emit("Nuevo");
			}
		},
		Editar(Id) {
			if (this.EsModal2 == true) {
				this.bus.$emit("Nuevo", false, Id);
			} else {
				//this.$root.$emit('Nuevo',false,Id);
				this.$router.push({ name: this.IrA, params: { Id: Id } });
			}
		},
		Eliminar(Id) {
			this.$swal({
				title: "Esta seguro que desea eliminar este dato?",
				text: "No se podra revertir esta acción",
				type: "warning",
				showCancelButton: true,
				confirmButtonText: "Si",
				cancelButtonText: "No, mantener",
				showCloseButton: true,
				showLoaderOnConfirm: true
			}).then(result => {
				if (result.value) {
					this.$toast.success("Información eliminada");
					this.$http.delete("crmprocesos/" + Id).then(res => {
						this.Lista();
					});
				}
			});
		},
		async Lista() {
			await this.$http
				.get(this.urlApi, {
					params: {
						Nombre: this.Filtro.Nombre,
						IdSucursa: this.oClienteP.IdSucursal,
						IdTipoProceso: this.oClienteP.IdTipoProceso,
						Entrada: this.Filtro.Entrada,
						pag: this.Filtro.Pagina,
						RegEstatus: "A"
					}
				})
				.then(res => {
					this.Listaprocesos = res.data.data.procesos;
					this.numero = res.data.data.procesos.length;
					this.ListaHeader = res.data.headers;
					this.Filtro.Entrada = res.data.data.pagination.PageSize;
					this.Filtro.TotalItem = res.data.data.pagination.TotalItems;
				});
		},
		Regresar() {
			this.$router.push({
				name: "crmtiposprocesos",
				params: { tipolistp: this.TipoList }
			});
		},
		Change(titulo) {
			var bdn = true;
			if (titulo == "Selecciona la imagen") {
				bdn = false;
			} else {
				titulo = titulo + " : " + this.oClienteP.Nombre;
			}
			this.NameForm = titulo;

			this.bus.$emit("cambiar_CloseModal", bdn);
		},
		go_to_equipo_sucursal(objsucursal) {
			this.$router.push({
				name: "equipos",
				params: { obj: objsucursal, objCliente: this.oClienteP }
			});
		}
	},
	created() {
		if (this.tipolistp != undefined) {
			sessionStorage.setItem("TipoList", JSON.stringify(this.tipolistp));
		}

		this.TipoList = JSON.parse(sessionStorage.getItem("TipoList"));

		if (this.TipoList == "Scanning") {
			this.ShowBtns = false;
		}

		//recibiendo objetos
		if (this.ocliente != undefined) {
			sessionStorage.setItem("IdSaved", JSON.stringify(this.ocliente));
		}
		this.oClienteP = JSON.parse(sessionStorage.getItem("IdSaved"));

		this.NameList = this.NameList + " " + this.oClienteP.Nombre;
		this.NameForm = "Equipos de la sucursal: " + this.oClienteP.Nombre;

		this.bus.$off("Delete");
		this.bus.$off("List");
		this.bus.$off("Regresar");
		this.Lista();
		this.bus.$on("Delete", Id => {
			this.Eliminar(Id);
		});

		this.bus.$on("List", () => {
			this.Lista();
		});

		this.bus.$on("Regresar", () => {
			this.Regresar();
		});
	}
};
</script>
<style scoped>
.buttons {
	margin-top: 35px;
}
.row-v {
	height: 150px;
	width: 200px;
}
.ghost {
	opacity: 0.5;
	background: #c8ebfb;
}
</style>
