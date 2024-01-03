<template>
	<div>
		<Clist :pShowBtnAdd="EmployeeLimit" :regresar="true" :Nombre="NameList" :isModal="EsModal"
			   :Pag="Pag" :Total="TotalPagina" @FiltrarC="Lista" :Filtro="Filtro" :pConfigLoad="ConfigLoad">
			<template slot="header">
				<tr>
					<th>Nombre</th>
					<th>Correo</th>
					<th>Teléfono</th>
					<th>Profesión</th>
					<th>Perfil</th>
					<th>Acciones</th>
				</tr>
			</template>
			<template slot="body">
				<tr v-for="(lista, index) in ListaTrabajador" :key="index">
					<td>{{ lista.Nombre }}</td>
					<td>{{ lista.Correo }}</td>
					<td>{{ lista.Telefono }}</td>
					<td>{{ lista.Profesion }}</td>
					<td>{{ lista.Perfil }}</td>

					<td>
						<Cbtnaccion :isModal="EsModal" :Id="lista.IdTrabajador" :IrA="FormName">
							<template slot="btnaccion">
								<button
									v-b-tooltip.hover.lefttop
									title="Cambiar Contraseña"
									@click="ChangeP(lista.IdTrabajador)"
									data-toggle="modal"
									data-target="#ModalChange"
									data-backdrop="static"
									data-keyboard="false"
									type="button"
									class="btn-icon mr-2"
								>
									<i class="fa fa-key" aria-hidden="true"></i>
								</button>
							</template>

							<template slot="btnaccion">
								<button
									v-b-tooltip.hover.lefttop
									title="Historial"
									@click="UploadInventario(lista.IdTrabajador)"
									data-toggle="modal"
									data-target="#UploadFiles"
									data-backdrop="static"
									data-keyboard="false"
									type="button"
									class="btn-icon mr-2"
								>
									<i class="fas fa-file-upload" aria-hidden="true"></i>
								</button>
								<button
									v-b-tooltip.hover.lefttop
									title="Cargar Historias"
									@click="UploadHistory(lista.IdTrabajador)"
									data-toggle="modal"
									data-target="#history"
									data-backdrop="static"
									data-keyboard="false"
									type="button"
									class="btn-icon mr-2"
								>
									<i class="fas fa-folder-open" aria-hidden="true"></i>
								</button>
							</template>
						</Cbtnaccion>
					</td>
				</tr>
				<CSinRegistros :pContIF="ListaTrabajador.length" :pColspan="6" ></CSinRegistros>
			</template>
		</Clist>

		<Modal :size="size" :Nombre="NameList" :poBtnSave="oBtnSave">
			<template slot="Form">
				<Form @titulomodal="Change" :poBtnSave="oBtnSave"></Form>
			</template>
		</Modal>

		<Modal
			:NameModal="'ModalChange'"
			:poBtnSave="oBtnSave2"
			:size="'none'"
			:Nombre="'Cambio de Credenciales'"
		>
			<template slot="Form">
				<Change :poBtnSave="oBtnSave2"></Change>
			</template>
		</Modal>

		<Modal
			:NameModal="'UploadFiles'"
			:poBtnSave="oBtnSave3"
			:size="size"
			:Nombre="'Cargar documentos inventario'"
		>
			<template slot="Form">
				<UploadFiles :poBtnSave="oBtnSave3"></UploadFiles>
			</template>
		</Modal>

		<Modal
			:NameModal="'history'"
			:poBtnSave="oBtnSave4"
			:size="size"
			:Nombre="'Cargar documentos historial'"
		>
			<template slot="Form">
				<history :poBtnSave="oBtnSave4"></history>
			</template>
		</Modal>
	</div>
</template>
<script>
import Modal from "@/components/Cmodal.vue";
import Clist from "@/components/Clist.vue";
import Cbtnaccion from "@/components/Cbtnaccion.vue";

import Form from "@/views/catalogos/personal/Form.vue";

import Change from "@/views/catalogos/personal/ChangePass.vue";
import Credenciales from "@/views/catalogos/personal/Credenciales.vue";

import UploadFiles from "@/views/catalogos/personal/UploadFiles.vue";
import history from "@/views/catalogos/personal/history.vue";
import CSinRegistros from "../../../components/CSinRegistros";

export default {
	name: "listCofPersonal",
	components: {
		Modal,
		Clist,
		Cbtnaccion,
		Form,
		Change,
		UploadFiles,
		history,
		CSinRegistros
	},
	data() {
		return {
			FormName: "trabajadorForm", //Por si no es modal y queremos ir a una vista declarada en el router
			EsModal: true, //indica si es modal o no
			size: "modal-lg",
			NameList: "Trabajador",
			urlApi: "trabajador/get",
			ListaTrabajador: [],
			TotalPagina: 2,
			Pag: 0,
			ShowModal: true,

			Filtro: {
				Entrada: 10,
				Nombre: "",
				Placeholder: "Nombre..",
				TotalItem: 0,
				Pagina: 1
			},
			oBtnSave: {
				//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
				isModal: true,
				disableBtn: false,
				toast: 0
			},
			oBtnSave2: {
				//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
				isModal: true,
				disableBtn: false,
				toast: 0,
				nombreModal: "ModalChange"
			},

			oBtnSave3: {
				//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
				isModal: true,
				disableBtn: false,
				toast: 0,
				nombreModal: "UploadFiles"
			},

			oBtnSave4: {
				//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
				isModal: true,
				disableBtn: false,
				toast: 0,
				nombreModal: "history"
			},
			EmployeeLimit: true,
			ConfigLoad:{
				ShowLoader:true,
				ClassLoad:true,
			}
		};
	},
	methods: {
		async Lista() {
			this.ConfigLoad.ShowLoader = true;
			await this.$http
				.get(this.urlApi, {
					params: {
						Nombre: this.Filtro.Nombre,
						Entrada: this.Filtro.Entrada,
						pag: this.Filtro.Pagina,
						RegEstatus: "A"
					}
				})
				.then(res => {

					this.ListaTrabajador = res.data.data.trabajador;
					this.Filtro.Entrada = res.data.data.pagination.PageSize;
					this.Filtro.TotalItem = res.data.data.pagination.TotalItems;
					//Si la cantidad de usuarios es mayor o igual al paquete establecido
					//bloquea el botón de "Nuevo"
					if (this.Filtro.TotalItem >= res.data.data.sucursal.PaqueteU) {
						this.EmployeeLimit = false;
					} else {
						this.EmployeeLimit = true;
					}
				}).finally(()=>{
					this.ConfigLoad.ShowLoader = false;
				});
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
					this.$http.delete("trabajador/" + Id).then(res => {
						this.Lista();
					});
				}
			});
		},

		Change(titulo) {
			this.NameForm = titulo;
			this.ShowModal = true;
			var bdn = true;
			if (titulo == "Calculadora") {
				this.ShowModal = false;
				var bdn = false;
			}

			this.bus.$emit("cambiar_CloseModal", bdn);
		},
		ChangeP(Id) {
			this.bus.$emit("ChangeP", Id);
		},

		UploadInventario(Id) {
			this.bus.$emit("UploadP", Id);
		},

		UploadHistory(Id) {
			this.bus.$emit("HistoryP", Id);
		}
	},
	created() {
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
			this.$router.push({ name: "submenuadmon" });
		});
	}
};
</script>
