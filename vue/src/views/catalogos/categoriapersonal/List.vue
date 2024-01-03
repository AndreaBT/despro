<template>
	<div>
		<CHead :oHead="Head"></CHead>
		<div class="row justify-content-center">
			<div class="col-md-6 col-lg-5">
				<Clist :ShowHead="false" :regresar="true" @FiltrarC="Lista" :Filtro="Filtro" :Nombre="NameList" :Pag="Pag"
					   :Total="TotalPagina" :isModal="EsModal" :pConfigLoad="ConfigLoad">

					<template slot="header">
						<tr>
							<th>Categoría</th>
							<th>Acciones</th>
						</tr>
					</template>

					<template slot="body">
						<tr v-for="(lista,index) in ListaCategoriaPersonal" :key="index">
							<td>{{ lista.Nombre }}</td>
							<td>
								<Cbtnaccion :isModal="EsModal" :Id="lista.IdCategoria" :IrA="FormName">
									<template slot="btnaccion"></template>
								</Cbtnaccion>
							</td>
						</tr>
						<CSinRegistros :pContIF="ListaCategoriaPersonal.length" :pColspan="2" ></CSinRegistros>
					</template>

				</Clist>
			</div>
		</div>

		<Modal
			:poBtnSave="oBtnSave"
			:size="size"
			:Nombre="NameList"
		>
			<template slot="Form">
				<Form :poBtnSave="oBtnSave"></Form>
			</template>
		</Modal>
	</div>
</template>
<script>
import CHead from "@/components/CHead.vue";
import Clist from "@/components/Clist.vue";
import Modal from "@/components/Cmodal.vue";
import Cbtnaccion from "@/components/Cbtnaccion.vue";

import Form from "@/views/catalogos/categoriapersonal/Form.vue";
import CSinRegistros from "../../../components/CSinRegistros";

export default {
	name: "listConfCatPersonal",
	components: {
		Modal,
		Clist,
		Cbtnaccion,
		Form,
		CHead,
		CSinRegistros
	},
	data() {
		return {
			FormName: "TipoUnidadForm", //Por si no es modal y queremos ir a una vista declarada en el router
			EsModal: true, //indica si es modal o no
			size: "modal-dialog",
			NameList: "Categoría Personal",
			urlApi: "categoriapersonal/get",
			ListaCategoriaPersonal: [],
			ListaHeader: [],
			TotalPagina: 2,
			Pag: 0,
			Filtro: {
				Nombre: "",
				Placeholder: "Categoria..",
				TotalItem: 0,
				Pagina: 1,
				Entrada: 10
			},
			oBtnSave: {
				//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
				isModal: true,
				disableBtn: false,
				toast: 0
			},
			Head: {
				ShowHead: true,
				Title: "Categoría Persona",
				BtnNewShow: true,
				BtnNewName: "Nuevo",
				isreturn: true,
				isModal: true,
				isEmit: true,
				Url: "",
				ObjReturn: "",
				NameReturn: "Regresar",
				isCuentas: false,
				verFiltroCuentas: false
			},
			ConfigLoad:{
				ShowLoader:true,
				ClassLoad:true,
			}
		};
	},
	methods: {
		GuardarDesdeList() {},
		async Lista() {
			this.ConfigLoad.ShowLoader = true;

			await this.$http.get(this.urlApi, {
					params: {
						Nombre: this.Filtro.Nombre,
						Entrada: this.Filtro.Entrada,
						pag: this.Filtro.Pagina,
						RegEstatus: "A"
					}
			}).then(res => {
					this.ListaCategoriaPersonal = res.data.data.categoriapersonal;
					this.Filtro.Entrada = res.data.data.pagination.PageSize;
					this.Filtro.TotalItem = res.data.data.pagination.TotalItems;

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

					this.$http.delete("categoriapersonal/" + Id).then(res => {
						this.Lista();
					});
				}
			});
		},

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
