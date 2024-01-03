<template>
	<div>
		<CHead :oHead="Head"></CHead>

		<div class="row justify-content-center">
			<div class="col-md-6 col-lg-5">
				<Clist
					:ShowHead="false"
					:regresar="true"
					:Nombre="NameList"
					@FiltrarC="Lista"
					:Filtro="Filtro"
					:isModal="EsModal"
				>
					<template slot="header">
						<tr>
							<th>Título</th>
							<th>Acciones</th>
						</tr>
					</template>
					<template slot="body">
						<tr v-for="(lista, key, index) in ListaPdf" :key="index">
							<td>{{ lista.Titulo }}</td>
							<td>
								<Cbtnaccion
									:isModal="EsModal"
									:Id="lista.IdPdf"
									:IrA="FormName"
								>
									<template slot="btnaccion">
										<button
											v-b-tooltip.hover.lefttop
											v-if="!lista.NombreArchivo == ''"
											@click="get_pdfequipo(lista.NombreArchivo)"
											title="Ver PDF"
											type="button"
											class="btn-icon mr-2"
										>
											<i class="fa fa-file-pdf"></i>
										</button>
									</template>
								</Cbtnaccion>
							</td>
						</tr>
					</template>
				</Clist>
			</div>
		</div>

		<Modal :size="size" :Nombre="NameForm" :poBtnSave="oBtnSave">
			<template slot="Form">
				<Form :NameList="NameForm" :oEquipoP="oEquipo" :poBtnSave="oBtnSave">
				</Form>
			</template>
		</Modal>
	</div>
</template>
<script>
import CHead from "@/components/CHead.vue";
import Modal from "@/components/Cmodal.vue";
import Clist from "@/components/Clist.vue";
import Cbtnaccion from "@/components/Cbtnaccion.vue";
import Form from "@/views/catalogos/pdfequipo/Form.vue";

export default {
	name: "list",
	props: ["oEquipoP"],
	components: {
		Modal,
		Clist,
		Cbtnaccion,
		Form,
		CHead
	},
	data() {
		return {
			FormName: "clientesForm", //Por si no es modal y queremos ir a una vista declarada en el router
			EsModal: true, //indica si es modal o no
			size: "none",
			NameList: "PDF´S del Equipo : ",
			urlApi: "pdfequipo/get",
			ListaPdf: [],
			ListaHeader: [],
			oEquipo: {},
			NameForm: "PDF del Equipo : ",
			UrlFile: "",
			Filtro: {
				Nombre: "",
				Placeholder: "Nombre..",
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
				Title: "PDF´S del Equipo :",
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
					this.$http.delete("pdfequipo/" + Id).then(res => {
						this.$toast.success("Información eliminada");
						this.Lista();
					});
				}
			});
		},
		async Lista() {
			await this.$http
				.get(this.urlApi, {
					params: {
						Titulo: "",
						IdEquipo: this.oEquipo.IdEquipo,
						Nombre: this.Filtro.Nombre,
						Entrada: this.Filtro.Entrada,
						pag: this.Filtro.Pagina,
						RegEstatus: "A"
					}
				})
				.then(res => {
					this.ListaPdf = res.data.data.pdfequipo;
					this.UrlFile = res.data.data.UrlFile;
					this.Filtro.Entrada = res.data.data.pagination.PageSize;
					this.Filtro.TotalItem = res.data.data.pagination.TotalItems;
				});
		},
		Regresar() {
			this.$router.push({ name: "equipos" });
		},
		get_pdfequipo(FileName) {
			let archivo = this.UrlFile + FileName;
			window.open(
				archivo,
				"_blank",
				"toolbar=1, scrollbars=1, resizable=1, width=" +
					1015 +
					", height=" +
					800
			);
		}
	},
	created() {
		if (this.oEquipoP != undefined) {
			sessionStorage.setItem("oEquipo", JSON.stringify(this.oEquipoP));
		}
		this.oEquipo = JSON.parse(sessionStorage.getItem("oEquipo"));

		this.NameList = this.NameList + " " + this.oEquipo.Nequipo;
		this.NameForm = "Pdf del Equipo: " + this.oEquipo.Nequipo;

		this.Head.Title = this.Head.Title + " " + this.oEquipo.Nequipo;

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
