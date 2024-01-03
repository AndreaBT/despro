<template>
	<div>
		<CHead :oHead="Head">
			<template slot="component">
				<button v-if="!isVisible" @click="mostrarFiltros('open')" type="button" class="btn btn-01 mb-2 mr-1 filtro">Filtros</button>
				<button v-else @click="mostrarFiltros('close')" type="button" class="btn btn-01 mb-2 mr-1 salir">Filtros</button>
			</template>
		</CHead>

		<Clist :regresar="true" @FiltrarC="Lista" :pShowBtnAdd="true" :ShowHead="false"
			   :Filtro="Filtro" :Nombre="NameList" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal" :Cuentas="Cuentas" :pConfigLoad="ConfigLoad">


			<template slot="botonCuentas">
				<div class="form-inline justify-content-end" >
					<h1 class="naranja mr-2">${{sumAmounts}}</h1>
				</div>
			</template>

			<template slot="header">
				<tr >
					<th>Proveedor</th>
					<th>Cliente</th>
					<th>Fecha Factura</th>
					<th>Fecha Pago</th>
					<th v-show="TipoFiltro != 'NO'">F. Real Pago</th>
					<th>Monto</th>
					<th>Factura</th>
					<th>Crédito</th>
					<th>Pagado</th>
					<th>Estatus</th>
					<th>Acciones</th>
				</tr>
			</template>

			<template slot="body">
				<tr v-for="(lista, index) in ListaCtaPorPagar" :key="index">
					<td>{{ lista.Proveedor }}</td>
					<td>{{ lista.Cliente }}</td>
					<td>{{ lista.FechaFactura }}</td>
					<td>{{ lista.FechaPago }}</td>
					<td v-show="TipoFiltro != 'NO'">
						{{ lista.FechaRealPago }}
					</td>
					<td>$ {{ Number(lista.Monto).toLocaleString() }}</td>
					<td>{{ lista.NumFactura }}</td>
					<td>{{ lista.Credito }}</td>
					<td>
						<b>{{ lista.Estatus }}</b>
					</td>
					<td
							class="badge badge-pill badge-primary mt-2"
							v-if="lista.Vigencia == 'No vencido'"
					>
						{{ lista.Vigencia }}
					</td>
					<td
							class="badge badge-pill badge-Vigencia mt-2"
							v-if="lista.Vigencia == 'Vencido'"
					>
						{{ lista.Vigencia }}
					</td>
					<td>
						<Cbtnaccion
								
								:isModal="EsModal"
								:Id="lista.IdCtaPagar"
								:IrA="FormName"
						>
							<template slot="btnaccion">
								<button
										v-if="lista.Estatus == 'NO'"
										v-b-tooltip.hover.leftbottom
										title="Pagado"
										@click="Observation(lista.IdCtaPagar)"
										data-toggle="modal"
										data-target="#Observacion"
										data-backdrop="static"
										data-keyboard="false"
										type="button"
										class="btn-icon mr-2"
								>
									<i class="fas fa-dollar-sign"></i>
								</button>
								<button
										v-if="lista.Factura != ''"
										@click="openInvoice(lista)"
										v-b-tooltip.hover.top
										title="Factura"
										type="button"
										class="btn-icon mr-2"
								>
									<i class="fas fa-file-pdf"></i>
								</button>
								<button
										v-if="lista.ArchivoUno != ''"
										@click="openEvidenceOne(lista)"
										v-b-tooltip.hover.top
										title="Evidencia"
										type="button"
										class="btn-icon mr-2"
								>
									<i class="fas fa-file-pdf"></i>
								</button>
								<button
										v-if="lista.ArchivoDos != ''"
										@click="openEvidenceTwo(lista)"
										v-b-tooltip.hover.top
										title="Evidencia"
										type="button"
										class="btn-icon mr-2"
								>
									<i class="fas fa-file-pdf"></i>
								</button>
							</template>
						</Cbtnaccion>
						<template v-if="lista.Estatus != 'NO'">
							<button
									v-b-tooltip.hover.leftbottom
									title="Observación"
									@click="Observation(lista.IdCtaPagar)"
									data-toggle="modal"
									data-target="#Observacion"
									data-backdrop="static"
									data-keyboard="false"
									type="button"
									class="btn-icon mr-2"
							>
								<i class="fas fa-info-circle"></i>
							</button>
							<button
									v-if="lista.Factura != ''"
									@click="openInvoice(lista)"
									v-b-tooltip.hover.top
									title="Factura"
									type="button"
									class="btn-icon mr-2"
							>
								<i class="fas fa-file-pdf"></i>
							</button>
							<button
									v-if="lista.ArchivoUno != ''"
									@click="openEvidenceOne(lista)"
									v-b-tooltip.hover.top
									title="Evidencia"
									type="button"
									class="btn-icon mr-2"
							>
								<i class="fas fa-file-pdf"></i>
							</button>
							<button
									v-if="lista.ArchivoDos != ''"
									@click="openEvidenceTwo(lista)"
									v-b-tooltip.hover.top
									title="Evidencia"
									type="button"
									class="btn-icon mr-2"
							>
								<i class="fas fa-file-pdf"></i>
							</button>
						</template>
					</td>
				</tr>
				<CSinRegistros :pContIF="ListaCtaPorPagar.length" :pColspan="[TipoFiltro !== 'NO' ? 11 : 10]" />
			</template>

			<template slot="FiltroCuentas">
				<div id="filtro" class="card ">
					<div class="card-body">
						<h4>Filtros Avanzados</h4>
						<div class="form-group mr-2">
							<label >Selc. Rango Fecha</label>
							<v-date-picker
									@input="Lista()"
									mode="range"
									v-model="rangeDate"
									:input-props="{
										class: 'form-control   calendar',
										placeholder: 'Selecciona un rango de fecha para buscar',
										readonly: true
									}"
							/>
						</div>
						<div class="form-group mr-2">
							<label >Estatus</label>
							<select @change="Lista" v-model="TipoFiltro" class="form-control">
								<option value="NO">No Pagado</option>
								<option value="SI">Pagado</option>
							</select>
						</div>
						<div class="form-group mr-2">
							<label >Vigencia</label>
							<select @change="Lista" v-model="validity" class="form-control">
								<option value="0">Todos</option>
								<option value="No vencido">No vencido</option>
								<option value="Vencido">Vencido</option>
							</select>
						</div>
						<div class="form-group mr-2" style="max-width: 15rem">
							<label >Proveedores</label>
							<treeselect
									@input="Lista()"
									:options="SuppliersList"
									placeholder="Busque un proveedor..."
									v-model="proveedoresId"
							/>
						</div>
						<br>
						<div class="form-group form-group mr-2 text-center">
							<button
									@click="cleanFilter()"
									type="button"
									class="btn btn-01"
									style="font-size:1rem"
							>
								Limpiar <i class="fa fa-filter"></i>
							</button>
						</div>
						<div class="ml-2">

						</div>

					</div>
				</div>
			</template>


		</Clist>

		<Modal :poBtnSave="oBtnSave" :size="size" :Nombre="NameList">
			<template slot="Form">
				<Form :poBtnSave="oBtnSave"></Form>
			</template>
		</Modal>

		<Modal
			:NameModal="'Observacion'"
			:poBtnSave="oBtnSave2"
			:size="size"
			:Nombre="'Observación'"
		>
			<template slot="Form">
				<Observacion :poBtnSave="oBtnSave2"></Observacion>
			</template>
		</Modal>
	</div>
</template>
<script>
import Modal from "@/components/Cmodal.vue";
import Clist from "@/components/Clist.vue";
import Cbtnaccion from "@/components/Cbtnaccion.vue";
import Observacion from "@/views/modulos/ctaporcobrarpagar/ctaporpagaropera/Observacion.vue";
import Form from "@/views/modulos/ctaporcobrarpagar/ctaporpagaropera/Form.vue";
import CHead from "../../../../components/CHead";
import CSinRegistros from "../../../../components/CSinRegistros";

export default {
	name: "list",
	components: {
		Modal,
		Clist,
		Cbtnaccion,
		Form,
		Observacion,
		CHead,
		CSinRegistros
	},
	data() {
		return {
			TipoFiltro: "NO",
			validity: "0",
			FormName: "TipoUnidadForm", //Por si no es modal y queremos ir a una vista declarada en el router
			EsModal: true, //indica si es modal o no
			size: "modal-lg",
			NameList: "Operaciones Cuentas Por Pagar",
			ListaCtaPorPagar: [],
			ListaHeader: [],
			SuppliersList: [],
			rangeDate: {},
			TotalPagina: 2,
			Pag: 0,
			DateFilter: "",
			sumAmounts: 0,
			proveedoresId: null,
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
			oBtnSave2: {
				//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
				isModal: true,
				disableBtn: false,
				toast: 0,
				nombreModal: "Observacion"
			},
			data: {
				IdCtaPagar: 0
			},
			invoice: "",
			evidenceOne: "",
			evidenceTwo: "",
			isVisible:false,
			Cuentas:{
				isCuentas:true,
				verFiltros:false
			},
			Head: {
				ShowHead: true,
				Title: "Operaciones Cuentas Por Pagar",
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
		Observation(id) {
			this.bus.$emit("UploadO", id);
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

					this.$http.delete("ctaporpagar/" + Id).then(res => {
						this.Lista();
					});
				}
			});
		},
		async Lista() {
			this.ConfigLoad.ShowLoader = true;

			await this.$http
				.get("ctaporpagar/get", {
					params: {
						IdProveedor: this.proveedoresId,
						Nombre: this.Filtro.Nombre,
						Entrada: this.Filtro.Entrada,
						pag: this.Filtro.Pagina,
						RegEstatus: "A",
						TipoFiltro: this.TipoFiltro,
						Vigencia: this.validity,
						FechaI: this.rangeDate.start,
						FechaF: this.rangeDate.end,
						TipoCuenta: "OPERA"
					}
				})
				.then(res => {
					this.ListaCtaPorPagar = res.data.data.ctaporpagar;
					this.Filtro.Entrada = res.data.data.pagination.PageSize;
					this.Filtro.TotalItem = res.data.data.pagination.TotalItems;

					const objSuma = res.data.data.sumamonto.sumAmount;
					this.sumAmounts = this.numberTo(Number(objSuma));

					if (this.ListaCtaPorPagar.length > 0) {
						this.updateValidity();
					}
					this.get_Suppliers();

					this.Ruta = res.data.ruta;

				}).finally(()=>{
					this.ConfigLoad.ShowLoader = false;
				});
		},
		async updateValidity() {
			await this.$http.post("ctaporpagar/updatevalidity").then(res => {});
		},
		get_Suppliers() {
			this.$http
				.get("ctaproveedores/get", {
					params: {}
				})
				.then(res => {
					this.SuppliersList = res.data.data.proveedores.map(function(obj) {
						return { id: obj.IdProveedor, label: obj.Nombre };
					});
				});
		},
		openInvoice(obj) {
			let pdfFactura = window.open(this.Ruta + obj.Factura);
			pdfFactura.document.write(
				"<iframe width='100%' height='100%' src='" +
					this.Ruta +
					obj.Factura +
					"'></iframe>"
			);
		},
		openEvidenceOne(obj) {
			let pdfArchivoUno = window.open(this.Ruta + obj.ArchivoUno);
			pdfArchivoUno.document.write(
				"<iframe width='100%' height='100%' src='" +
					this.Ruta +
					obj.ArchivoUno +
					"'></iframe>"
			);
		},
		openEvidenceTwo(obj) {
			let pdfArchivoDos = window.open(this.Ruta + obj.ArchivoDos);
			pdfArchivoDos.document.write(
				"<iframe width='100%' height='100%' src='" +
					this.Ruta +
					obj.ArchivoDos +
					"'></iframe>"
			);
		},
		cleanFilter() {
			var date = new Date(),
				y = date.getFullYear(),
				m = date.getMonth();
			var firstDay = new Date(y, m, 1);
			var lastDay = new Date(y, m + 1, 0);

			this.rangeDate = {
				start: firstDay,
				end: lastDay
			};
			this.validity = 0;

			this.TipoFiltro = "NO";

			this.proveedoresId = null;

			this.Lista();
		},
		numberTo(num) {
			//value = value.toFixed(0);
			let fixed = 0;
			if (num === null) {
				return null;
			} // terminate early
			if (num === 0) {
				return "0";
			} // terminate early
			fixed = !fixed || fixed < 0 ? 0 : fixed; // number of decimal places to show
			var b = num.toPrecision(2).split("e"), // get power
				k = b.length === 1 ? 0 : Math.floor(Math.min(b[1].slice(1), 14) / 3), // floor at decimals, ceiling at trillions
				c =
					k < 1
						? num.toFixed(0 + fixed)
						: (num / Math.pow(10, k * 3)).toFixed(1 + fixed), // divide by power
				d = c < 0 ? c : Math.abs(c), // enforce -0 is 0
				e = d + ["", " K", " M", " B", " T"][k]; // append power
			return e;
		},

		mostrarFiltros(op) {
			this.isVisible = (op === 'open') ? true: false;
			this.Cuentas.verFiltros = this.isVisible;
		},
	},
	created() {
		var date = new Date(),
			y = date.getFullYear(),
			m = date.getMonth();
		var firstDay = new Date(y, m, 1);
		var lastDay = new Date(y, m + 1, 0);

		this.rangeDate = {
			start: firstDay,
			end: lastDay
		};
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
			this.$router.push({ name: "menuctacobrarpagar" });
		});
	}
};
</script>
