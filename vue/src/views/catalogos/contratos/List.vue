<template>
	<div>
		<Clist
			@FiltrarC="Lista"
			:Filtro="Filtro"
			:regresar="true"
			:Nombre="NameList"
			:Pag="Pag"
			:Total="TotalPagina"
			:isModal="EsModal"
		>
			<template slot="header">
				<tr>
					<th>Numero C</th>
					<th>Cliente Sucursal</th>
					<th>Comentario</th>
					<th>Acciones</th>
				</tr>
			</template>
			<template slot="body">
				<tr v-for="(lista, key, index) in ListaContratos" :key="index">
					<td>{{ lista.NumeroC }}</td>
					<td>{{ lista.ClienteSucursal }}</td>
					<td>{{ lista.Comentario }}</td>
					<td v-if="ShowBtns">
						<Cbtnaccion
							:ShowButtonG="ShowBtns"
							:isModal="EsModal"
							:Id="lista.IdContrato"
							:IrA="FormName"
						>
						</Cbtnaccion>
					</td>
				</tr>
			</template>
		</Clist>

		<Modal :size="size" :Nombre="'Contrato'" :poBtnSave="oBtnSave">
			<template slot="Form">
				<Form :NameList="NameForm" :ocliente="oClienteP" :poBtnSave="oBtnSave">
				</Form>
			</template>
		</Modal>
	</div>
</template>
<script>
import Modal from "@/components/Cmodal.vue";
import Clist from "@/components/Clist.vue";
import Cbtnaccion from "@/components/Cbtnaccion.vue";
import Form from "@/views/catalogos/contratos/Form.vue";

export default {
	name: "list",
	props: ["ocliente", "idBranchCustomer", "tipolistp", "TipoM"],
	components: {
		Modal,
		Clist,
		Cbtnaccion,
		Form
	},
	data() {
		return {
			FormName: "contratosForm", //Por si no es modal y queremos ir a una vista declarada en el router
			EsModal: true, //indica si es modal o no
			size: "modal-md",
			NameList: "Sucursales del Cliente : ",
			urlApi: "numcontrato/get",
			ListaContratos: [],
			ListaHeader: [],
			TotalPagina: 2,
			Pag: 0,
			oClienteP: {},
			NameForm: "Sucursal del Cliente : ",
			TipoList: "",
			ShowBtns: true,
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
			}
		};
	},
	methods: {
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
					this.$http.delete("numcontrato/" + Id).then(res => {
						this.Lista();
					});
				}
			});
		},
		async Lista() {
			await this.$http
				.get(this.urlApi, {
					params: {
						pag: this.Filtro.Pagina,
						Nombre: this.Filtro.Nombre,
						Entrada: this.Filtro.Entrada,
						IdClienteS: this.idBranchCustomer,
						IdCliente: this.oClienteP.IdCliente,
						IdSucursa: this.oClienteP.IdSucursal,

						RegEstatus: "A"
					}
				})
				.then(res => {
					this.ListaContratos = res.data.data.contractlist;
					this.ListaHeader = res.data.headers;
					this.Filtro.Entrada = res.data.data.pagination.PageSize;
					this.Filtro.TotalItem = res.data.data.pagination.TotalItems;
				});
		},
		Regresar() {
			this.$router.push({
				name: "clientesucursal",
				params: { tipolistp: this.TipoList }
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
			sessionStorage.setItem(
				"IdBranchCustomerP",
				JSON.stringify(this.ocliente)
			);
		}

		this.oClienteP = JSON.parse(sessionStorage.getItem("IdBranchCustomerP"));

		this.NameList =
			this.NameList + " " + this.oClienteP.Nombre + " : Contratos";
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
