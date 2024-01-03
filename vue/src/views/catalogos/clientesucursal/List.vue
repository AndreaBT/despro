<template>
	<div>
		<Clist
			:pShowBtnAdd="btnAdd"
			@FiltrarC="Lista"
			:Filtro="Filtro"
			:regresar="true"
			:Nombre="NameList"
			:Pag="Pag"
			:Total="TotalPagina"
			:isModal="EsModal"
			:pConfigLoad="ConfigLoad"
		>
			<template slot="header">
				<tr>
					<th>Sucursal</th>
					<th>Teléfono</th>
					<th>Dirección</th>
					<th>Correo</th>
					<th>Ciudad</th>
					<th>Acciones</th>
				</tr>
			</template>
			<template slot="body">
				<tr v-for="(lista, index) in ListaClientes" :key="index">
					<td>{{ lista.Nombre }}</td>
					<td>{{ lista.Telefono }}</td>
					<td>{{ lista.Direccion }}</td>
					<td>{{ lista.Correo }}</td>
					<td>{{ lista.Ciudad }}</td>
					<td v-if="ShowBtns">
						<Cbtnaccion
							:ShowButtonG="ShowBtns"
							:pShowButtonDelete="btnDelete"
							:isModal="EsModal"
							:Id="lista.IdClienteS"
							:IrA="FormName"
						>
							<template slot="btnaccion">
								<button
									v-if="lista.ArchivoDos != ''"
									@click="goToContract(lista, lista.IdClienteS)"
									v-b-tooltip.hover.top
									title="Contratos"
									type="button"
									class="btn-icon mr-2"
								>
									<i class="fas fa-file-contract"></i>
								</button>
							</template>
						</Cbtnaccion>
					</td>
					<td v-else>
						<button
							v-b-tooltip.hover.lefttop
							v-if="TipoList == 'Scanning'"
							@click="go_to_equipo_sucursal(lista)"
							title="Agregar equipos"
							type="button"
							class="btn-icon mr-2"
						>
							<i class="fas fa-file-contract"></i>
						</button>
					</td>
				</tr>
				<CSinRegistros :pContIF="ListaClientes.length" :pColspan="6" />
			</template>
		</Clist>

		<Modal :size="size" :Nombre="'Sucursal'" :poBtnSave="oBtnSave">
			<template slot="Form">
				<Form
					:NameList="NameForm"
					:ocliente="oClienteP"
					@titulomodal="Change"
					:poBtnSave="oBtnSave"
				>
				</Form>
			</template>
		</Modal>
	</div>
</template>
<script>
import Modal from "@/components/Cmodal.vue";
import Clist from "@/components/Clist.vue";
import Cbtnaccion from "@/components/Cbtnaccion.vue";

import Form from "@/views/catalogos/clientesucursal/Form.vue";
import CSinRegistros from "../../../components/CSinRegistros";

export default {
	name: "list",
	props: ["ocliente", "tipolistp", "TipoM"],
	components: {
		Modal,
		Clist,
		Cbtnaccion,
		Form,
		CSinRegistros
	},
	data() {
		return {
			FormName: "clientesForm", //Por si no es modal y queremos ir a una vista declarada en el router
			EsModal: true, //indica si es modal o no
			size: "modal-lg",
			NameList: "Sucursales del Cliente : ",
			urlApi: "clientesucursal/get",
			ListaClientes: [],
			ListaHeader: [],
			TotalPagina: 2,
			Pag: 0,
			oClienteP: {},
			NameForm: "Sucursal del Cliente : ",
			TipoList: "",
			ShowBtns: true,
			btnAdd: true,
			btnDelete: true,
			Filtro: {
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
						IdSucursa: this.oClienteP.IdSucursal,
						IdCliente: this.oClienteP.IdCliente,
						Entrada: this.Filtro.Entrada,
						pag: this.Filtro.Pagina,
						RegEstatus: "A"
					}
				})
				.then(res => {
					this.ListaClientes = res.data.data.clientesucursal;
					this.ListaHeader = res.data.headers;
					this.Filtro.Entrada = res.data.data.pagination.PageSize;
					this.Filtro.TotalItem = res.data.data.pagination.TotalItems;

					if (this.TipoM > 0) {
						this.TipoM = 0;

						$("#ModalForm").modal("show");
						this.bus.$emit("Nuevo", true);
					}

					this.ListaClientes.forEach(item => {
						if (item.Nombre == "House Account") {
							this.btnAdd = false;
							this.btnDelete = false;
						}
					});

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
					this.$http.delete("clientesucursal/" + Id).then(res => {
						this.Lista();
					});
				}
			});
		},

		Regresar() {
			this.$router.push({
				name: "clientes",
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
		},
		goToContract(objCliente, idBranchCustomer) {
			this.$router.push({
				name: "contratoclientesucursal",
				params: {
					ocliente: objCliente,
					idBranchCustomer: idBranchCustomer,
					objCliente: this.oClienteP
				}
			});
		}
	},
	created() {
		if (this.tipolistp != undefined) {
			sessionStorage.setItem("TipoList", JSON.stringify(this.tipolistp));
		}

		if (this.TipoM != undefined) {
			sessionStorage.setItem("TipoM", this.TipoM);
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

		this.bus.$off("opensucursal");
		this.bus.$on("opensucursal", () => {});
	},
	mounted() {}
};
</script>
