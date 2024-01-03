<template>
	<div>
		<section class="container-fluid mt-2">
			<Menu :pSitio="NombreSeccion">
                <template slot="BtnInicio">
                    <button type="button" data-toggle="modal" data-target="#ModalForm"  data-backdrop="static" data-keyboard="false" class="btn btn-01 mr-2" @click="Nuevo()">Nuevo</button>
                </template>
            </Menu>
			
			<div class="row justify-content-center mt-3">
				<div class="col-md-6 col-lg-5">
					<div class="">
						<Clist :regresar="false" :ShowHead="false" @FiltrarC="Lista" :Filtro="Filtro" :pShowBtnAdd="false" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal" :pConfigLoad="ConfigLoad" >
							<template slot="header">
								<tr>
									<th>Nombre</th>
									<th>Acciones</th>
								</tr>
							</template>
							<template slot="body">
								<tr v-for="(lista, key, index) in Listatipoproceso" :key="index" >
									<td>{{ lista.Nombre }}</td>
									<td>
										<Cbtnaccion :isModal="EsModal" :Id="lista.IdTipoProceso" :IrA="FormName" >
											<template slot="btnaccion">
												<button @click="go_to_procesos(lista)" type="button" v-b-tooltip.hover.Top title="Procesos" class="btn-icon mr-2" >
													<i class="fas fa-project-diagram"></i>
												</button>
											</template>
										</Cbtnaccion>
									</td>
								</tr>
							</template>
						</Clist>

						<Modal :size="size" :Nombre="NameList" :poBtnSave="oBtnSave">
							<template slot="Form">
								<Form :poBtnSave="oBtnSave"></Form>
							</template>
						</Modal>
					</div>
				</div>
			</div>
		</section>
	</div>
</template>
<script>
import Modal from "@/components/Cmodal.vue";
import Clist from "@/components/Clist.vue";
import Cbtnaccion from "@/components/Cbtnaccion.vue";
import Form from "../tiposprocesos/form.vue";
import Menu from "../indexMenu.vue";

export default {
	name: "list",
	components: {
		Modal,
		Clist,
		Cbtnaccion,
		Form,
		Menu,
	},
	data() {
		return {
			NombreSeccion: 'Lista Procesos',
			FormName: "TipoUnidadForm", //Por si no es modal y queremos ir a una vista declarada en el router
			EsModal: true, //indica si es modal o no
			size: "none",
			NameList: "Tipo de Proceso",
			urlApi: "crmtipoproceso/list",
			Listatipoproceso: [],
			ListaHeader: [],
			TotalPagina: 2,
			Pag: 0,
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
			TipoList: "",
			Head: {
				ShowHead: true,
				Title: "Datos",
				BtnNewShow: true,
				BtnNewName: "Nuevo",
				isreturn: true,
				isModal: true,
				isEmit: true,
				Url: "",
				ObjReturn: "",
				NameReturn: "Regresar"
			},
			ConfigLoad:{
				ShowLoader:true,
				ClassLoad:true,
			}
		};
	},
	methods: {
		Nuevo() {
			if (this.oBtnSave.isModal == true) {
				this.bus.$emit("Nuevo", true);
			} else {
				this.bus.$emit("Nuevo");
			}
		},
		go_to_procesos(objcliente) {
			this.$router.push({
				name: "crmprocesos",
				params: { ocliente: objcliente, tipolistp: this.TipoList }
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
					this.$http.delete("crmtipoproceso/" + Id).then(res => {
						this.$toast.success("Información eliminada");
						this.Lista();
					});
				}
			});
		},
		async Lista() {
			this.ConfigLoad.ShowLoader = true;
			await this.$http.get(this.urlApi, {
					params: {
						Nombre: this.Filtro.Nombre,
						RegEstatus: "A",
						Entrada: this.Filtro.Entrada,
						pag: this.Filtro.Pagina
					}
				})
			.then(res => {
				this.Listatipoproceso = res.data.data.tipoproceso;
				this.Filtro.Entrada = res.data.data.pagination.PageSize;
				this.Filtro.TotalItem = res.data.data.pagination.TotalItems;
			}).finally(()=>{
				this.ConfigLoad.ShowLoader = false;
			});
		}
	},
	created() {
		//Obligatorio pasar el tipolist
		if (this.tipolistp != undefined) {
			sessionStorage.setItem("IdSaved", JSON.stringify(this.tipolistp));
		}

		this.TipoList = JSON.parse(sessionStorage.getItem("IdSaved"));

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
			this.$router.push({ name: "submenucrm" });
		});
	}
};
</script>
