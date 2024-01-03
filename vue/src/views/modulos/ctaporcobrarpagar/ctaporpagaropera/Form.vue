<template>
	<div class="">
		<div class="row">
			<div class="col-lg-4 form-group">
				<label>Departamentos</label>
				<select
					v-if="this.disable!='si'"
					v-model="ctaporpagar.TipoSelect"
					@change="filtrarcuentas()"
					class="form-control"
				>
					<!-- NO VAYAN A CAMBIAR ESTOS ID -->
					<option value="0">Seleccione un departamento</option>
					<option value="2">Burden</option>
					<option value="3">Vehículos</option>
					<!-- -------------------------- -->
					<option value="6">Materiales</option>
					<option value="7">Equipos</option>
					<option value="8">Contratistas</option>
					<option value="9">Viáticos</option>
					<option value="10">Mano de Obra Directa</option>
					<option value="11">Mano de Obra Leyes Sociales</option>
					<option value="12">Mano de Obra Otros</option>
				</select>

				<select
					v-if="this.disable=='si'"
					:disabled="true"
					v-model="ctaporpagar.TipoSelect"
					@change="filtrarcuentas()"
					class="form-control"
				>
					<!-- NO VAYAN A CAMBIAR ESTOS ID -->
					<option value="0">Seleccione un departamento</option>
					<option value="2">Burden</option>
					<option value="3">Vehículos</option>
					<!-- -------------------------- -->
					<option value="6">Materiales</option>
					<option value="7">Equipos</option>
					<option value="8">Contratistas</option>
					<option value="9">Viáticos</option>
					<option value="10">Mano de Obra Directa</option>
					<option value="11">Mano de Obra Leyes Sociales</option>
					<option value="12">Mano de Obra Otros</option>
				</select>


				<label id="lblmsuser" style="Proceso:red">
					<Cvalidation
						v-if="this.errorvalidacion.Departamento"
						:Mensaje="errorvalidacion.Departamento[0]"
					></Cvalidation
				></label>
			</div>

			<div v-if="this.showClient == true && this.disable!='si'" class="col-lg-8 form-group">
				<label>Cliente</label>
				<treeselect
					@input="branchOfficeList()"
					:options="ListaClientes"
					placeholder="Busque un cliente..."
					v-model="clientesId"
					noResultsText="Resultados no encontrados"
					noOptionsText="Opciones no disponibles"
				/>
				<label id="lblmsuser" style="Proceso:red">
					<Cvalidation
						v-if="this.errorvalidacion.Cliente"
						:Mensaje="errorvalidacion.Cliente[0]"
					></Cvalidation
				></label>
			</div>

			<div v-if="this.showClient == true && this.disable=='si'" class="col-lg-8 form-group">
				<label>Cliente</label>
				<treeselect
					:disabled="true"
					@input="branchOfficeList()"
					:options="ListaClientes"
					placeholder="Busque un cliente..."
					v-model="clientesId"
					noResultsText="Resultados no encontrados"
					noOptionsText="Opciones no disponibles"
				/>
				<label id="lblmsuser" style="Proceso:red">
					<Cvalidation
						v-if="this.errorvalidacion.Cliente"
						:Mensaje="errorvalidacion.Cliente[0]"
					></Cvalidation
				></label>
			</div>
		
			<div v-if="this.showClient == true  && this.disable!='si'" class="col-lg-4 form-group">
				<label>Cliente Sucursal</label>
				<treeselect
					:maxHeight="200"
					@input="contractList()"
					:options="ListaSucursales"
					placeholder="Busque una sucursal..."
					v-model="sucursalesId"
					noResultsText="Resultados no encontrados"
					noOptionsText="Opciones no disponibles"
				/>
				<label id="lblmsuser" style="Proceso:red">
					<Cvalidation
						v-if="this.errorvalidacion.Sucursal"
						:Mensaje="errorvalidacion.Sucursal[0]"
					></Cvalidation
				></label>
			</div>

			<div v-if="this.showClient == true  && this.disable=='si'" class="col-lg-4 form-group">
				<label>Cliente Sucursal</label>
				<treeselect
					:disabled="true"
					:maxHeight="200"
					@input="contractList()"
					:options="ListaSucursales"
					placeholder="Busque una sucursal..."
					v-model="sucursalesId"
					noResultsText="Resultados no encontrados"
					noOptionsText="Opciones no disponibles"
				/>
				<label id="lblmsuser" style="Proceso:red">
					<Cvalidation
						v-if="this.errorvalidacion.Sucursal"
						:Mensaje="errorvalidacion.Sucursal[0]"
					></Cvalidation
				></label>
			</div>

			<div v-if="this.showClient == true  && this.disable!='si'" class="col-lg-4 form-group">
				<label>No. Contrato</label>
				<treeselect
					:maxHeight="200"
					:options="ListaContratos"
					placeholder="Busque un contrato..."
					v-model="contratosId"
					noResultsText="Resultados no encontrados"
					noOptionsText="Opciones no disponibles"
				/>
				<label id="lblmsuser" style="Proceso:red">
					<Cvalidation
						v-if="this.errorvalidacion.Contrato"
						:Mensaje="errorvalidacion.Contrato[0]"
					></Cvalidation
				></label>
			</div>

			<div v-if="this.showClient == true  && this.disable=='si'" class="col-lg-4 form-group">
				<label>No. Contrato</label>
				<treeselect
					:disabled="true"
					:maxHeight="200"
					:options="ListaContratos"
					placeholder="Busque un contrato..."
					v-model="contratosId"
					noResultsText="Resultados no encontrados"
					noOptionsText="Opciones no disponibles"
				/>
				<label id="lblmsuser" style="Proceso:red">
					<Cvalidation
						v-if="this.errorvalidacion.Contrato"
						:Mensaje="errorvalidacion.Contrato[0]"
					></Cvalidation
				></label>
			</div>

			<div v-show="this.showAccout == true && this.disable!='si'" class="col-lg-4 form-group">
				<label>No. Cuenta</label>
				<treeselect
					:maxHeight="200"
					:options="ListaCuentas"
					placeholder="Busque una cuenta..."
					v-model="cuentasId"
				/>
				<label id="lblmsuser" style="Proceso:red">
					<Cvalidation
						v-if="this.errorvalidacion.Cuentas"
						:Mensaje="errorvalidacion.Cuentas[0]"
					></Cvalidation
				></label>
			</div>

			<div v-show="this.showAccout == true && this.disable=='si'" class="col-lg-4 form-group">
				<label>No. Cuenta</label>
				<treeselect
					:disabled="true"
					:maxHeight="200"
					:options="ListaCuentas"
					placeholder="Busque una cuenta..."
					v-model="cuentasId"
				/>
				<label id="lblmsuser" style="Proceso:red">
					<Cvalidation
						v-if="this.errorvalidacion.Cuentas"
						:Mensaje="errorvalidacion.Cuentas[0]"
					></Cvalidation
				></label>
			</div>

			<template v-if="this.showServiceBilling == true && this.disable!='si'" >
				<div class="border-top my-3 col-lg-12"></div>

				<div class="col-lg-4 form-group">
					<label>Servicio</label>
					<select
						v-model="ctaporpagar.IdConfigS"
						@change="listServicesType()"
						class="form-control"
					>
						<!-- NO VAYAN A CAMBIAR ESTOS ID -->
						<option :value="0">Seleccione un Servicio</option>
						<option
							v-for="(item, index) in ListaServicios"
							:key="index"
							:value="item.IdConfigS"
						>
							{{ item.Nombre }}
						</option>
					</select>
					<label id="lblmsuser" style="Proceso:red">
						<Cvalidation
							v-if="this.errorvalidacion.Servicio"
							:Mensaje="errorvalidacion.Servicio[0]"
						></Cvalidation
					></label>
				</div>

				<div class="col-lg-4 form-group">
					<label>Tipo Servicio</label>
					<select v-model="ctaporpagar.IdTipoServicio" class="form-control">
						<!-- NO VAYAN A CAMBIAR ESTOS ID -->
						<option :value="0">Seleccione un Tipo Servicio</option>
						<option
							v-for="(item, index) in ListaTipoServicio"
							:key="index"
							:value="item.IdTipoSer"
						>
							{{ item.Concepto }}
						</option>
					</select>
					<label id="lblmsuser" style="Proceso:red">
						<Cvalidation
							v-if="this.errorvalidacion.Tipo_Servicio"
							:Mensaje="errorvalidacion.Tipo_Servicio[0]"
						></Cvalidation
					></label>
				</div>

				<div class="border-top my-3 col-lg-12"></div>
			</template>

			<template v-if="this.showServiceBilling == true && this.disable=='si'">
				<div class="border-top my-3 col-lg-12"></div>

				<div class="col-lg-4 form-group">
					<label>Servicio</label>
					<select
					:disabled="true"
						v-model="ctaporpagar.IdConfigS"
						@change="listServicesType()"
						class="form-control"
					>
						<!-- NO VAYAN A CAMBIAR ESTOS ID -->
						<option :value="0">Seleccione un Servicio</option>
						<option
							v-for="(item, index) in ListaServicios"
							:key="index"
							:value="item.IdConfigS"
						>
							{{ item.Nombre }}
						</option>
					</select>
					<label id="lblmsuser" style="Proceso:red">
						<Cvalidation
							v-if="this.errorvalidacion.Servicio"
							:Mensaje="errorvalidacion.Servicio[0]"
						></Cvalidation
					></label>
				</div>

				<div class="col-lg-4 form-group">
					<label>Tipo Servicio</label>
					<select v-model="ctaporpagar.IdTipoServicio" class="form-control" :disabled="true">
						<!-- NO VAYAN A CAMBIAR ESTOS ID -->
						<option :value="0">Seleccione un Tipo Servicio</option>
						<option
							v-for="(item, index) in ListaTipoServicio"
							:key="index"
							:value="item.IdTipoSer"
						>
							{{ item.Concepto }}
						</option>
					</select>
					<label id="lblmsuser" style="Proceso:red">
						<Cvalidation
							v-if="this.errorvalidacion.Tipo_Servicio"
							:Mensaje="errorvalidacion.Tipo_Servicio[0]"
						></Cvalidation
					></label>
				</div>

				<div class="border-top my-3 col-lg-12"></div>
			</template>
		</div>
		<div class="row">
			<div class="col-lg-4 form-group">
				<label> Monto (sin IVA) </label>
				<vue-numeric
					v-if="this.disable!='si'"
					class="form-control form-finanza "
					currency="$"
					separator=","
					:precision="2"
					v-model="ctaporpagar.Monto"
					placeholder="$0.00"
				></vue-numeric>
				<vue-numeric
					v-if="this.disable=='si'"
					:disabled="true"
					class="form-control  "
					currency="$"
					separator=","
					:precision="2"
					v-model="ctaporpagar.Monto"
					placeholder="$0.00"
				></vue-numeric>
				<label id="lblmsuser" style="Proceso:red">
					<Cvalidation
						v-if="this.errorvalidacion.Monto"
						:Mensaje="errorvalidacion.Monto[0]"
					></Cvalidation
				></label>
			</div>

			<div class="col-lg-4 form-group">
				<label>Proveedores</label>
				<treeselect
					v-if="this.disable!='si'"
					:maxHeight="100"
					@input="get_proveedores(proveedoresId)"
					:options="ListaProveedores"
					placeholder="Busque un proveedor..."
					v-model="proveedoresId"
					noResultsText="Resultados no encontrados"
					noOptionsText="Opciones no disponibles"
				/>

				<treeselect
					v-if="this.disable=='si'"
					:disabled="true"
					:maxHeight="100"
					@input="get_proveedores(proveedoresId)"
					:options="ListaProveedores"
					placeholder="Busque un proveedor..."
					v-model="proveedoresId"
					noResultsText="Resultados no encontrados"
					noOptionsText="Opciones no disponibles"
				/>
				<label id="lblmsuser" style="Proceso:red">
					<Cvalidation
						v-if="this.errorvalidacion.Proveedor"
						:Mensaje="errorvalidacion.Proveedor[0]"
					></Cvalidation
				></label>
			</div>

			<div class="col-lg-4 form-group">
				<label>Número Factura o Recibo</label>
				<input
					v-if="this.disable!='si'"
					v-model="ctaporpagar.NumFactura"
					type="text"
					placeholder="Número Factura"
					class="form-control"
				/>

				<input
					v-if="this.disable=='si'"
					:disabled="true"
					v-model="ctaporpagar.NumFactura"
					type="text"
					placeholder="Número Factura"
					class="form-control"
				/>
				<label id="lblmsuser" style="Proceso:red">
					<Cvalidation
						v-if="this.errorvalidacion.Numero_Factura"
						:Mensaje="errorvalidacion.Numero_Factura[0]"
					></Cvalidation
				></label>
			</div>

			<div class="col-lg-4 form-group">
				<label> Fecha Factura o Recibo </label>

				<v-date-picker
					v-if="this.disable!='si'"
					@input="sumDayPay()"
					v-model="ctaporpagar.FechaFactura"
					:popover="{
						placement: 'bottom',
						visibility: 'click'
					}"
					:input-props="{
						class: 'form-control  calendar',
						style: 'cursor:pointer;background-color:#F9F9F9',
						readonly: true
					}"
				/>

				<v-date-picker
					v-if="this.disable=='si'"
					@input="sumDayPay()"
					v-model="ctaporpagar.FechaFactura"
					:popover="{
						placement: 'bottom',
						visibility: 'click'
					}"
					:input-props="{
						class: 'form-control  calendar',
						style: 'cursor:pointer;background-color:#F9F9F9',
						readonly: true,
						disabled:true
						
					}"
				/>
				<label id="lblmsuser" style="Proceso:red">
					<Cvalidation
						v-if="this.errorvalidacion.Fecha_Factura"
						:Mensaje="errorvalidacion.Fecha_Factura[0]"
					></Cvalidation
				></label>
			</div>

			<div class="col-lg-4 form-group">
				<label> Credito (días) </label>
				<input
					v-if="this.disable!='si'"
					@input="sumDayPay()"
					class="form-control form-finanza"
					:precision="0"
					v-model="ctaporpagar.Credito"
					placeholder="0"
					
				/>

				<input
					v-if="this.disable=='si'"
					@input="sumDayPay()"
					class="form-control "
					:precision="0"
					v-model="ctaporpagar.Credito"
					placeholder="0"
					:disabled="true"
					type="text"
				/>
			</div>

			<div class="col-lg-4 form-group">
				<label> Fecha Pago </label>

				<v-date-picker
				v-if="this.disable!='si'"
					@input="dayPay()"
					v-model="ctaporpagar.FechaPago"
					:popover="{
						placement: 'bottom',
						visibility: 'click'
					}"
					:input-props="{
						class: 'form-control  calendar',
						style: 'cursor:pointer;background-color:#F9F9F9'
					}"
				/>

				<v-date-picker
				v-if="this.disable=='si'"
					@input="dayPay()"
					v-model="ctaporpagar.FechaPago"
					:popover="{
						placement: 'bottom',
						visibility: 'click'
					}"
					:input-props="{
						class: 'form-control  calendar',
						style: 'cursor:pointer;background-color:#F9F9F9',
						readonly: true,
						disabled:true
					}"
				/>
			</div>

			<div class="col-lg-4 form-group">
				<label> Factura </label>
				<div class="custom-file-input-image">
					<input
						v-if="this.disable!='si'"
						@change="uploadInvoice()"
						type="file"
						ref="file"
						name="myfile"
						accept="application/pdf"
						class="custom-file-input"
						id="validatedCustomFile"
						required
					/>

					<input
						v-if="this.disable=='si'"
						@change="uploadInvoice()"
						type="file"
						ref="file"
						name="myfile"
						accept="application/pdf"
						class="custom-file-input"
						id="validatedCustomFile"
						required
						:disabled="true"
					/>
					<input type="text" v-model="invoice" class="form-control" />
					<button type="button" class="">
						<i class="fas fa-paperclip"></i>
					</button>
				</div>
				<label id="lblmsuser" style="Proceso:red">
					<Cvalidation
						v-if="this.errorvalidacion.Fecha_Pago"
						:Mensaje="errorvalidacion.Fecha_Pago[0]"
					></Cvalidation
				></label>
			</div>
			<div class="col-lg-4 form-group">
				<label> Evidencia 1 </label>
				<div class="custom-file-input-image">
					<input
						v-if="this.disable!='si'"
						@change="uploadEvidenceOne()"
						type="file"
						ref="file2"
						name="myfile"
						accept="application/pdf"
						class="custom-file-input"
						id="validatedCustomFile"
						required
					/>

					<input
						v-if="this.disable=='si'"
						@change="uploadEvidenceOne()"
						type="file"
						ref="file2"
						name="myfile"
						accept="application/pdf"
						class="custom-file-input"
						id="validatedCustomFile"
						required
						:disabled="true"
					/>
					<input type="text" v-model="evidenceOne" class="form-control" />
					<button type="button" class="">
						<i class="fas fa-paperclip"></i>
					</button>
				</div>
			</div>
			<div class="col-lg-4 form-group">
				<label> Evidencia 2 </label>
				<div class="custom-file-input-image">
					<input
						v-if="this.disable!='si'"
						@change="uploadEvidenceTwo()"
						type="file"
						ref="file3"
						name="myfile"
						accept="application/pdf"
						class="custom-file-input"
						id="validatedCustomFile"
						required
					/>

					<input
						v-if="this.disable=='si'"
						@change="uploadEvidenceTwo()"
						type="file"
						ref="file3"
						name="myfile"
						accept="application/pdf"
						class="custom-file-input"
						id="validatedCustomFile"
						required
						:disabled="true"
					/>
					<input type="text" v-model="evidenceTwo" class="form-control" />
					<button type="button" class="">
						<i class="fas fa-paperclip"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from "@/components/Cbtnsave.vue";
import Cvalidation from "@/components/Cvalidation.vue";

export default {
	name: "Form",
	props: ["IdCtaPagar", "poBtnSave","TipoFiltro"],
	data() {
		return {
			Modal: true, //Sirve pra los botones de guardado
			FormName: "ctaporpagaropera", //Sirve para donde va regresar
			ListaCategoria: [],
			ListaCuentas: [],
			ListaProveedores: [],
			ListaClientes: [],
			clientesId: null,
			proveedoresId: null,
			cuentasId: null,
			sucursalesId: null,
			contratosId: null,
			showAccout: false,
			showServiceBilling: false,
			showClient: false,
			ListaContratos: [],
			ListaSucursales: [],
			ListaTipoServicio: [],
			ListaServicios: [],
			ListaDetakke: [],
			ctaporpagar: {
				IdCtaPagar: 0,
				TipoSelect: 0,
				FechaFactura: "",
				FechaPago: "",
				Credito: "",
				NumFactura: "",
				Monto: "",
				Factura: "",
				ArchivoUno: "",
				ArchivoDos: "",
				IdConfigS: 0,
				IdTipoServicio: 0
			},
			Anio: "",
			Ruta: "",
			invoice: "Elegir archivo (5 MB)",
			evidenceOne: "Elegir archivo (5 MB)",
			evidenceTwo: "Elegir archivo (5 MB)",
			errorvalidacion: [],
			disable:""
		};
	},
	components: {
		Cbtnsave,
		Cvalidation
	},
	methods: {
		async Guardar() {
			//deshabilita botones
			this.poBtnSave.toast = 0;
			this.poBtnSave.disableBtn = true;

			let formData = new FormData();

			formData.set("IdCtaPagar", this.ctaporpagar.IdCtaPagar);
			formData.set("TipoSelect", this.ctaporpagar.TipoSelect);
			formData.set("IdAsociado", this.cuentasId);
			formData.set("IdProveedor", this.proveedoresId);
			formData.set("TipoCuenta", "OPERA");
			formData.set("FechaFactura", this.ctaporpagar.FechaFactura.toISOString());
			formData.set("FechaPago", this.ctaporpagar.FechaPago.toISOString());
			formData.set("NumFactura", this.ctaporpagar.NumFactura);
			formData.set("Credito", this.ctaporpagar.Credito);
			formData.set("Monto", this.ctaporpagar.Monto);
			formData.set("IdCliente", this.clientesId);
			formData.set("IdSucursalCliente", this.sucursalesId);
			formData.set("IdContrato", this.contratosId);
			formData.set("IdConfigS", this.ctaporpagar.IdConfigS);
			formData.set("IdTipoServicio", this.ctaporpagar.IdTipoServicio);

			formData.set("Factura", this.ctaporpagar.Factura);
			if (this.ctaporpagar.Factura == undefined) {
				formData.set("Factura", "");
			}
			formData.set("ArchivoUno", this.ctaporpagar.ArchivoUno);
			if (this.ctaporpagar.ArchivoUno == undefined) {
				formData.set("ArchivoUno", "");
			}
			formData.set("ArchivoDos", this.ctaporpagar.ArchivoDos);
			if (this.ctaporpagar.ArchivoDos == undefined) {
				formData.set("ArchivoDos", "");
			}

			let file = this.$refs.file.files[0];
			formData.append("File", file);

			let file2 = this.$refs.file2.files[0];
			formData.append("File2", file2);

			let file3 = this.$refs.file3.files[0];
			formData.append("File3", file3);

			await this.$http
				.post("ctaporpagar/post", formData)
				.then(res => {
					this.poBtnSave.disableBtn = false;
					this.poBtnSave.toast = 1;
					$("#ModalForm").modal("hide");
					this.bus.$emit("List");
				})
				.catch(err => {
					this.errorvalidacion = err.response.data.message.errores;
					this.poBtnSave.disableBtn = false;
					this.poBtnSave.toast = 2;
				});
		},
		Limpiar() {
			this.ctaporpagar = {
				IdCtaPagar: 0,
				TipoSelect: 0,
				FechaFactura: "",
				FechaPago: "",
				Credito: "",
				NumFactura: "",
				Monto: "",
				Factura: "",
				ArchivoUno: "",
				ArchivoDos: "",
				IdConfigS: 0,
				IdTipoServicio: 0
			};
			this.showServiceBilling = false;
			this.showClient = false;
			this.showAccout = false;
			this.clientesId = null;
			this.proveedoresId = null;
			this.cuentasId = null;
			this.sucursalesId = null;
			this.contratosId = null;
			this.ListaClientes = [];
			this.ListaContratos = [];
			this.ListaSucursales = [];
			this.invoice = "Elegir archivo (5 MB)";
			this.evidenceOne = "Elegir archivo (5 MB)";
			this.evidenceTwo = "Elegir archivo (5 MB)";
			this.errorvalidacion = [];
			this.$refs.file.value = "";
			this.$refs.file2.value = "";
			this.$refs.file3.value = "";
			this.disable='';
		},
		uploadInvoice() {
			const image = this.$refs.file.files[0];
			var FileSize = image.size / 1024 / 1024; // in MB
			if (FileSize > 5) {
				this.$toast.info("Solo se puede subir archivos menores a 5 MB");
				const input = this.$refs.file;
				input.type = "text";
				input.type = "file";
				return false;
			}
			var allowedExtensions = /(\.pdf|\.PDF)$/i;
			if (!allowedExtensions.exec(image.name)) {
				this.$toast.info("Extenciones permitidas .pdf");
				const input = this.$refs.file;
				input.type = "text";
				input.type = "file";
				this.invoice = "Elegir archivo (5 MB)";
				return false;
			}
			this.invoice = image.name;
		},
		uploadEvidenceOne() {
			const image2 = this.$refs.file2.files[0];
			var FileSize2 = image2.size / 1024 / 1024; // in MB
			if (FileSize2 > 5) {
				this.$toast.info("Solo se puede subir archivos menores a 5 MB");
				const input2 = this.$refs.file2;
				input2.type = "text";
				input2.type = "file";
				return false;
			}
			var allowedExtensions = /(\.pdf|\.PDF)$/i;
			if (!allowedExtensions.exec(image2.name)) {
				this.$toast.info("Extenciones permitidas .pdf");
				const input2 = this.$refs.file2;
				input2.type = "text";
				input2.type = "file";
				this.evidenceOne = "Elegir archivo (5 MB)";
				return false;
			}
			this.evidenceOne = image2.name;
		},
		uploadEvidenceTwo() {
			const image3 = this.$refs.file3.files[0];
			var FileSize3 = image3.size / 1024 / 1024; // in MB
			if (FileSize3 > 5) {
				this.$toast.info("Solo se puede subir archivos menores a 5 MB");
				const input3 = this.$refs.file3;
				input3.type = "text";
				input3.type = "file";
				return false;
			}
			var allowedExtensions = /(\.pdf|\.PDF)$/i;
			if (!allowedExtensions.exec(image3.name)) {
				this.$toast.info("Extenciones permitidas .pdf");
				const input3 = this.$refs.file3;
				input3.type = "text";
				input3.type = "file";
				this.evidenceTwo = "Elegir archivo (5 MB)";
				return false;
			}
			this.evidenceTwo = image3.name;
		},
		sumDayPay() {
			let days = this.ctaporpagar.Credito;
			let dateParam = this.ctaporpagar.FechaFactura;

			if (days > 0) {
				var date = new Date(dateParam);
				date.setDate(dateParam.getDate() + parseInt(days)); //sumamos días a la fecha recibida
				this.ctaporpagar.FechaPago = date; //fecha pago = fecha factura + días de crédito
			}
		},
		dayPay() {
			let dateInvoice = this.ctaporpagar.FechaFactura;
			let datePay = this.ctaporpagar.FechaPago;
			let day = 1000 * 60 * 60 * 24;
			let credit = Math.round((datePay - dateInvoice) / day) + 1;
			this.ctaporpagar.Credito = credit;
		},
		get_one() {
			this.$http
				.get("ctaporpagar/recovery", {
					params: { IdCtaPagar: this.ctaporpagar.IdCtaPagar }
				})
				.then(res => {
					const objCta = res.data.data.ctaporpagar;

					//importante el órden de los métodos

					this.ClientList();

					this.branchOfficeList(objCta.IdCliente);

					this.contractList(objCta.IdSucursalCliente);

					this.listServices();

					this.listServicesType(objCta.IdConfigS);

					//FIN importante el órden de los métodos

					this.ctaporpagar.TipoSelect = objCta.TipoSelect;
					this.clientesId = objCta.IdCliente;
					this.sucursalesId = objCta.IdSucursalCliente;
					this.contratosId = objCta.IdContrato;
					this.cuentasId = objCta.IdAsociado;
					this.ctaporpagar.IdConfigS = objCta.IdConfigS;
					this.ctaporpagar.IdTipoServicio = objCta.IdTipoServicio;
					this.ctaporpagar.Monto = objCta.Monto;
					this.proveedoresId = objCta.IdProveedor;
					this.ctaporpagar.NumFactura = objCta.NumFactura;
					this.ctaporpagar.Credito = objCta.Credito;

					this.get_cuentas(this.ctaporpagar.TipoSelect);

					var uno = objCta.FechaPago.replace(/-/g, "\/");
					var dos = objCta.FechaFactura.replace(/-/g, "\/");

					this.ctaporpagar.FechaPago = new Date(uno);
					this.ctaporpagar.FechaFactura = new Date(dos);

					if (objCta.Factura != "undefined" && objCta.Factura != "") {
						this.invoice = objCta.Factura;
						this.ctaporpagar.Factura = objCta.Factura;
					}
					if (objCta.ArchivoUno != "undefined" && objCta.ArchivoUno != "") {
						this.evidenceOne = objCta.ArchivoUno;
						this.ctaporpagar.ArchivoUno = objCta.ArchivoUno;
					}
					if (objCta.ArchivoDos != "undefined" && objCta.ArchivoDos != "") {
						this.evidenceTwo = objCta.ArchivoDos;
						this.ctaporpagar.ArchivoDos = objCta.ArchivoDos;
					}

					if (objCta.Estatus=="SI") {
						this.disable='si';
					}
				});
		},
		filtrarcuentas() {
			this.get_cuentas();
			if (this.ctaporpagar.IdCtaPagar == 0) {
				this.clientesId = null;
			}
			this.showAccouts();
			this.showServicesBilling();
		},
		ClientList() {
			this.$http
				.get("clientes/get", {
					params: {}
				})
				.then(res => {
					this.ListaClientes = res.data.data.clientes.map(function(obj) {
						return { id: obj.IdCliente, label: obj.Nombre };
					});
				});
		},
		branchOfficeList(id) {
			if (typeof id != "undefined") {
				this.clientesId = id;
				this.showClient = true;
			}
			if (this.ctaporpagar.IdCtaPagar == 0) {
				this.sucursalesId = null;
				this.contratosId = null;
			}
			this.$http
				.get("clientesucursal/get", {
					params: {
						IdCliente: this.clientesId
					}
				})
				.then(res => {
					this.ListaSucursales = res.data.data.sucursal.map(function(obj) {
						return { id: obj.IdClienteS, label: obj.Nombre };
					});
				});
		},
		contractList(id) {
			if (typeof id != "undefined") {
				this.sucursalesId = id;
			}
			this.$http
				.get("numcontrato/get", {
					params: { IdClienteS: this.sucursalesId }
				})
				.then(res => {
					this.ListaContratos = res.data.data.contractlist.map(function(obj) {
						return { id: obj.IdContrato, label: obj.NumeroC };
					});
				});
		},
		get_cuentas(id) {
			var url = "";
			if (id == 2 || id == 3) {
				this.showAccout = true;
				console.log("solo recovery")
			}
			if (this.ctaporpagar.TipoSelect == 2) {
				url = "costodeptooper/get";

				this.$http
					.get(url, {
						params: { Anio: this.Anio }
					})
					.then(res => {
						this.ListaCuentas = res.data.data.lista.map(function(obj) {
							return {
								id: obj.IdCostoDeptoVenta,
								label: obj.NumeroCuenta + " - " + obj.Descripcion
							};
						});
					});
			}

			if (this.ctaporpagar.TipoSelect == 3) {
				url = "costovehope/get";

				this.$http
					.get(url, {
						params: { Anio: this.Anio }
					})
					.then(res => {
						this.ListaCuentas = res.data.data.lista.map(function(obj) {
							return {
								id: obj.IdCostoVehOpe,
								label: obj.NumeroCuenta + " - " + obj.Descripcion
							};
						});
					});
			}
		},
		get_proveedores(id) {
			this.$http
				.get("ctaproveedores/get", {
					params: {}
				})
				.then(res => {
					this.ListaProveedores = res.data.data.proveedores.map(function(obj) {
						return { id: obj.IdProveedor, label: obj.Nombre };
					});
					if (this.ctaporpagar.IdCtaPagar == 0) {
						res.data.data.proveedores.filter(obj => {
							if (obj.IdProveedor === id)
								this.ctaporpagar.Credito = obj.DiasCredito;
							return obj;
						});
					}
					this.sumDayPay();
				});
		},
		showAccouts() {
			this.showAccout = false;
			this.cuentasId = null;
			if (
				this.ctaporpagar.TipoSelect == 2 ||
				this.ctaporpagar.TipoSelect == 3
			) {
				this.showAccout = true;
			}
		},
		showServicesBilling() {
			this.showServiceBilling = false;
			this.showClient = false;

			this.clientesId = null;
			this.proveedoresId = null;
			this.sucursalesId = null;

			this.ctaporpagar.IdConfigS = 0;
			this.ctaporpagar.IdTipoServicio = 0;
			if (
				this.ctaporpagar.TipoSelect == 6 ||
				this.ctaporpagar.TipoSelect == 7 ||
				this.ctaporpagar.TipoSelect == 8 ||
				this.ctaporpagar.TipoSelect == 9
			) {
				this.listServices();
				this.showServiceBilling = true;
				this.showClient = true;
			}
		},
		listServices() {
			this.$http
				.get("baseactual/get", {
					params: {
						RegEstatus: "A",
						Entrada: ""
					}
				})
				.then(res => {
					this.ListaServicios = res.data.data.lista;
				});
		},
		listServicesType(id) {
			if (id > 0) {
				this.ctaporpagar.IdConfigS = id;
				this.showServiceBilling = true;
			}
			this.$http
				.get("tiposervicio/get", {
					params: {
						RegEstatus: "A",
						IdConfigS: this.ctaporpagar.IdConfigS,
						IdTipoServ: this.IdTipoServ,
						Entrada: ""
					}
				})
				.then(res => {
					if (typeof id == "undefined") {
						this.ctaporpagar.IdTipoServicio = 0;
					}
					this.ListaTipoServicio = res.data.data.tiposervicio;
				});
		}
	},
	created() {
		this.bus.$off("Nuevo");

		//Este es para moda
		this.bus.$on("Nuevo", (data, Id) => {
			var today = new Date();
			var yyyy = today.getFullYear();
			this.Anio = yyyy;

			this.get_proveedores();
			this.poBtnSave.disableBtn = false;
			this.bus.$off("Save");
			this.bus.$on("Save", () => {
				this.Guardar();
			});

			this.Limpiar();

			this.ClientList();

			this.ctaporpagar.FechaFactura = new Date();
			this.ctaporpagar.FechaPago = new Date();

			if (Id > 0) {
				this.ctaporpagar.IdCtaPagar = Id;
				this.get_one();
			}
			this.bus.$emit("Desbloqueo", false);
		});
	}
};
</script>
