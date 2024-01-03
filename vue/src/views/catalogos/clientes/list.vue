<template>
	<div>
		<Clist
			:pShowBtnAdd="ShowBtns"
			@FiltrarC="Lista"
			:Filtro="Filtro"
			:regresar="ShowBtns"
			:Nombre="NameList"
			:isModal="EsModal"
			:pConfigLoad="ConfigLoad"
		>
			<template slot="botonextra">
				<button
					v-if="disableHouseAccount"
					@click="houseAccount()"
					data-toggle="modal"
					data-target="#ModalNewUser"
					data-backdrop="static"
					type="button"
					class="btn btn-01 mb-2 mr-1"
				>
					House Account
				</button>
			</template>
			<template slot="header">
				<tr>
					<th>Cliente</th>
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
					<td>
						<Cbtnaccion
							v-show="lista.Nombre != 'House Account'"
							:ShowButtonG="ShowBtns"
							:isModal="EsModal"
							:Id="lista.IdCliente"
							:IrA="FormName"
						>
							<template slot="btnaccion">
								<button
									v-b-tooltip.hover.lefttop
									title="Sucursales"
									@click="go_to_sucursal(lista)"
									type="button"
									class="btn-icon mr-2"
								>
									<i class="fa fa-building" aria-hidden="true"></i>
								</button>
								&nbsp;
								<button
									v-b-tooltip.hover.lefttop
									v-if="ShowBtns"
									@click="go_to_usuarios(lista)"
									title="Usuario monitoreo"
									type="button"
									class="btn-icon mr-2"
								>
									<i class="fa fa-user-plus" aria-hidden="true"></i>
								</button>
							</template>
						</Cbtnaccion>
						<button
							v-show="lista.Nombre == 'House Account'"
							v-b-tooltip.hover.lefttop
							title="Sucursales"
							@click="go_to_sucursal(lista)"
							type="button"
							class="btn-icon mr-2"
						>
							<i class="fa fa-building" aria-hidden="true"></i>
						</button>
					</td>
				</tr>
				<CSinRegistros :pContIF="ListaClientes.length" :pColspan="6" />
			</template>
		</Clist>

		<Modal :size="size" :Nombre="NameList" :poBtnSave="oBtnSave">
			<template slot="Form">
				<Form :poBtnSave="oBtnSave"> </Form>
			</template>
		</Modal>
	</div>
</template>
<script>
import Modal from "@/components/Cmodal.vue";
import Clist from "@/components/Clist.vue";
import Cbtnaccion from "@/components/Cbtnaccion.vue";

import Form from "@/views/catalogos/clientes/form.vue";
import CSinRegistros from "../../../components/CSinRegistros";

export default {
	name: "listConfClientes",
	props: ["tipolistp"],
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
			NameList: "Lista Clientes",
			urlApi: "clientes/get",
			ListaClientes: [],
			ListaHeader: [],
			TotalPagina: 2,
			Pag: 0,
			ShowBtns: true,
			TipoList: "",
			Filtro: {
				Nombre: "",
				Placeholder: "Nombre..",
				TotalItem: 0,
				Pagina: 1
			},
			disableHouseAccount: true,
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

			await this.$http.get(this.urlApi, {
					params: {
						Nombre: this.Filtro.Nombre,
						Entrada: this.Filtro.Entrada,
						pag: this.Filtro.Pagina,
						RegEstatus: "A"
					}
				})
				.then(res => {
					this.ListaClientes = res.data.data.clientes;
					this.ListaHeader = res.data.headers;
					this.Filtro.Entrada = res.data.data.pagination.PageSize;
					this.Filtro.TotalItem = res.data.data.pagination.TotalItems;

					this.ListaClientes.forEach(item => {
						if (item.Nombre == "House Account") {
							this.disableHouseAccount = false;
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
					this.$http.delete("clientes/" + Id).then(res => {
						this.Lista();
					});
				}
			});
		},

		go_to_sucursal(objcliente) {
			this.$router.push({
				name: "clientesucursal",
				params: { ocliente: objcliente, tipolistp: this.TipoList, TipoM: 0 }
			});
		},
		go_to_usuarios(objcliente) {
			this.$router.push({ name: "listusu", params: { obj: objcliente } });
		},
		houseAccount() {
			this.$swal({
				title: "Se registrará una cuenta House Account",
				text: "No se podra revertir esta acción",
				type: "warning",
				showCancelButton: true,
				confirmButtonText: "Si",
				cancelButtonText: "No, cancelar",
				showCloseButton: true,
				showLoaderOnConfirm: true
			}).then(result => {
				if (result.value) {
					this.$toast.success("Información eliminada");
					// this.$http.delete("clientes/" + Id).then(res => {

					// });
					this.$http
						.post("clientes/houseaccount", {
							params: {}
						})
						.then(res => {
							this.Lista();
						});
				}
			});
		}
	},
	created() {
		//Obligatorio pasar el tipolist
		if (this.tipolistp != undefined) {
			sessionStorage.setItem("IdSaved", JSON.stringify(this.tipolistp));
		}

		this.TipoList = JSON.parse(sessionStorage.getItem("IdSaved"));

		if (this.TipoList == "Scanning") {
			this.ShowBtns = false;
		}

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
