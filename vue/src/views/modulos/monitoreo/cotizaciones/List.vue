<template>
	<div>
		<CHead :oHead="Head"></CHead>

		<div class="row justify-content-center">
			<div class="col-md-8 col-lg-8">
				<Clist
					:ShowHead="false"
					:regresar="regresar"
					@FiltrarC="Lista"
					:Filtro="Filtro"
					:Nombre="NameList"
					:isModal="EsModal"
					:pShowBtnAdd="btnadd"
					:pConfigLoad="ConfigLoad"
				>
					<template slot="header">
						<tr>
							<th>Título</th>
							<th class="text-center">Fecha Alta</th>
							<th>Archivo</th>
							<th v-show="PermisoAdmin.Perfil == 'Admin' || PermisoAdmin.Candado == 'admin'" v-if="ShowAcciones" class="text-center tw-2">Acciones</th>
						</tr>
					</template>
					<template slot="Filtros">
						<div class="form-group" style="max-width: 15rem">
							<treeselect
								@input="Lista()"
								:options="ListaSucursal"
								placeholder="Busque una sucursal..."
								v-model="sucursalesId"
							/>
						</div>
					</template>
					<template slot="body">
						<tr v-for="(lista, key, index) in ListaCotizaciones" :key="index">
							<td class="table__td table__td--lg">
								{{ lista.Titulo }}
							</td>
							<td class="text-center table__td table__td--sm">
								{{ lista.FechaAlta }}
							</td>
							<td class="text-center tw-1">
								<button v-if="lista.NombreArchivo !== '' " class="btn btn-table pl-01 mr-1" type="button" @click="open_file(lista)" v-b-tooltip.hover title="Ver Documento">
									<i class="fa fa-file-pdf"></i>
								</button>
							</td>
							<!-- Perfil "Admin" corresponde a IdPerfil 2 (después de normalizar), candado "admin" corresponde a Sofanor -->
								<td v-show="PermisoAdmin.Perfil == 'Admin' || PermisoAdmin.Candado == 'admin'" class="text-center tw-2" v-if="ShowAcciones">
									<button
										@click="Eliminar(lista.IdPdf)"
										v-b-tooltip.hover
										title="Eliminar"
										type="button"
										data-placement="top"
										class="btn btn-table"
									>
										<i class="fas fa-trash fa-fw-m"></i>
									</button>
								</td>
						</tr>
						<CSinRegistros :pContIF="ListaCotizaciones.length" :pColspan="4" ></CSinRegistros>
					</template>
				</Clist>
			</div>
		</div>

		<Modal :size="size" :Nombre="NameForm" :poBtnSave="oBtnSave">
			<template slot="Form">
				<Form :oCliente="getCliente" :poBtnSave="oBtnSave"></Form>
			</template>
		</Modal>
	</div>
</template>
<script>
import CHead from "@/components/CHead.vue";
import Modal from "@/components/Cmodal.vue";
import Clist from "@/components/Clist.vue";
import Form from "@/views/modulos/monitoreo/cotizaciones/Form.vue";
import CSinRegistros from "../../../../components/CSinRegistros";

export default {
	name: "list",
	props: ["ocliente", "pTipo"],
	components: { Modal, Clist, Form, CHead, CSinRegistros },
	data() {
		return {
			FormName: "Form", //Por si no es modal y queremos ir a una vista declarada en el router
			NameForm: "Nuevo",
			EsModal: true, //indica si es modal o no,
			CloseModal: true, //indica si el modal cierra o de lo contrario asignarle un evento al boton
			size: "modal-lg",
			NameList: "Cotizaciones",
			ListaCotizaciones: [],
			ClienteSucursal: {},
			Cliente: {},
			Equipo: {},
			regresar: true,
			btnadd: true,
			Tipo: 0,
			RutaPdf: "",
			rutaOld: "",

			ShowAcciones: true,
			sucursalesId: null,
			ListaSucursal: [],
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
				Title: "Cotizaciones",
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
			},
			PermisoAdmin: [],
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
					this.$http.delete("monitoreo/cotizacion/" + Id).then(res => {
						this.$swal({
							showConfirmButton: true,
							timer: 1000,
							title: "Inoformación Eliminada"
						});
						this.Lista();
					});
				}
			});
		},
		async Lista() {
			this.ConfigLoad.ShowLoader = true;
			await this.$http
				.get("monitoreo/cotizaciones", {
					params: {
						IdCliente: this.Cliente.IdCliente,
						IdClienteS: this.sucursalesId,
						Tipo: this.Tipo,
						Nombre: this.Filtro.Nombre,
						Entrada: this.Filtro.Entrada,
						pag: this.Filtro.Pagina
					}
				})
				.then(res => {
					this.ListaCotizaciones = res.data.row;
					this.RutaPdf = res.data.RutaPdf;
					this.rutaOld = res.data.rutaOld;
					this.Filtro.TotalItem = res.data.pagination.TotalItems;
				}).finally(()=>{
					this.ConfigLoad.ShowLoader = false;
				});
		},
		open_file(item) {
			//window.open(this.RutaPdf+File , '_blank');
			let rutaServer = '';

			if(parseInt(item.FileServer) > 0 ){
				rutaServer = this.RutaPdf;
			}else {
				rutaServer = this.rutaOld;
			}
			let documentoPath = rutaServer + item.NombreArchivo;
			//console.log(documentoPath);

			let pdfWindow = window.open(documentoPath);
			pdfWindow.document.write(
				"<iframe width='100%' height='100%' src='" + documentoPath + "'></iframe>"
			);
		},
		ListaSucursales() {
			this.$http
				.get("clientesucursal/get", {
					params: {
						IdCliente: this.Cliente.IdCliente
					}
				})
				.then(res => {
					this.ListaSucursal = res.data.data.clientesucursal.map(function(obj) {
						return { id: obj.IdClienteS, label: obj.Nombre };
					});
				});

		}
	},
	created() {
		this.bus.$off("Regresar");
		this.bus.$off("List");
		if (this.ocliente != undefined) {
			sessionStorage.setItem("ocliente", JSON.stringify(this.ocliente));
		}
		if (this.pTipo != undefined) {
			sessionStorage.setItem("pTipo", JSON.stringify(this.pTipo));
		}

		//sesión donde se obtiene permisos de administrador eliminar
		this.PermisoAdmin = JSON.parse(sessionStorage.getItem("user"));

		this.Cliente = JSON.parse(sessionStorage.getItem("ocliente"));
		this.Tipo = JSON.parse(sessionStorage.getItem("pTipo"));

		var osucursalSession = JSON.parse(sessionStorage.getItem("clientelog"));

		if (osucursalSession == null) {
			//Datos desde el admin
		} else {
			//datos desde login admin template
			//#region desde el login
			this.Cliente = JSON.parse(sessionStorage.getItem("clientelog"));
			this.regresar = false;
			this.btnadd = false;
			this.ShowAcciones = false;
		}

		if (this.Tipo == 2) {
			this.NameList = "Reportes";
			this.Head.Title = "Reportes";
		}

		this.NameList = this.Cliente.Nombre + " | " + this.NameList;
		this.Head.Title = this.Cliente.Nombre + " | " + this.Head.Title;

		this.bus.$on("Regresar", () => {
			this.$router.push({ name: "monitoreo_cli", params: {} });
		});

		this.ListaSucursales();
	},
	mounted() {
		this.Lista();

		this.bus.$on("List", () => {
			this.Lista();
		});
	},
	computed: {
		getCliente() {
			let data = {
				IdCliente: this.Cliente.IdCliente,
				Tipo: this.Tipo
			};
			return data;
		}
	},
	destroyed() {
		sessionStorage.removeItem("ocliente");
		sessionStorage.removeItem("pTipo");
	}
};
</script>
