<template>
	<div>
		<CHead :oHead="Head"></CHead>
		<div class="row justify-content-center">
			<div class="col-md-6 col-lg-5">
				<Clist :ShowHead="false" :regresar="true" :Filtro="Filtro" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal" :pConfigLoad="ConfigLoad">

					<template slot="header">
						<tr>
							<th>Serie</th>
							<th>Número</th>
							<th>Tipo</th>
							<th>Acciones</th>
						</tr>
					</template>

					<template slot="body">
						<tr v-for="(lista,index) in ListaFolio" :key="index">
							<td>{{ lista.Serie }}</td>
							<td>{{ lista.Numero }}</td>
							<td>{{ lista.Tipo }}</td>

							<td>
								<Cbtnaccion :PBtndelete="false" :isModal="EsModal" :Id="lista.IdFolio" :IrA="FormName">
									<template slot="btnaccion">
									</template>
								</Cbtnaccion>
							</td>
						</tr>
						<CSinRegistros :pContIF="ListaFolio.length" :pColspan="4" />
					</template>

				</Clist>
			</div>
		</div>

		<Modal :size="size" :Nombre="NameList" :poBtnSave="oBtnSave">
			<template slot="Form">
				<Form :poBtnSave="oBtnSave"></Form>
			</template>
		</Modal>
	</div>
</template>
<script>
import CHead from "@/components/CHead.vue";
import Modal from "@/components/Cmodal.vue";
import Clist from "@/components/Clist.vue";
import Cbtnaccion from "@/components/Cbtnaccion.vue";

import Form from "@/views/catalogos/folio/Form.vue";
import CSinRegistros from "../../../components/CSinRegistros";

export default {
	name: "listConfFolios",
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
			FormName: "folioForm", //Por si no es modal y queremos ir a una vista declarada en el router
			EsModal: true, //indica si es modal o no
			size: "modal-lg",
			NameList: "Folio",
			urlApi: "folio/get",
			ListaFolio: [],
			ListaHeader: [],
			TotalPagina: 2,
			Pag: 0,
			Filtro: {
				Nombre: "",
				Filas: 10,
				Placeholder: "Nombre..",
				Show: false
			},
			oBtnSave: {
				//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
				isModal: true,
				disableBtn: false,
				toast: 0
			},
			Head: {
				ShowHead: true,
				Title: "Folio",
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
		async Lista() {
			this.ConfigLoad.ShowLoader = true;

			await this.$http.get(this.urlApi, {
					params: {
						Nombre: "",
						Entrada: 50,
						pag: 0,
						RegEstatus: "A"
					}
				})
				.then(res => {
					this.ListaFolio = res.data.data.folio;
					//this.TotalPagina=res.data.data.pagination.TotalItems;
					//this.Pag=res.data.data.pagination.CurrentPage;

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

					this.$http.delete("folio/" + Id).then(res => {
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
