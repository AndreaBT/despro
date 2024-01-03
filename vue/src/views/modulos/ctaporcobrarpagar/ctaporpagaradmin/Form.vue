<template>
	<div class="form-row">
		<div class="col-lg-4 form-group">
			<label>Departamentos</label>
			<select
				v-if="this.disable!='si'"
				:maxHeight="200"
				v-model="ctaporpagar.TipoSelect"
				@change="filtrarcuentas()"
				class="form-control"
			>
				<option value="0">Seleccione un departamento</option>
				<option value="1">Ventas</option>
				<option value="4">G&A</option>
				<option value="5">Costos Financieros</option>
			</select>

			<select
				v-if="this.disable=='si'"
				:disabled="true"
				:maxHeight="200"
				v-model="ctaporpagar.TipoSelect"
				@change="filtrarcuentas()"
				class="form-control"
			>
				<option value="0">Seleccione un departamento</option>
				<option value="1">Ventas</option>
				<option value="4">G&A</option>
				<option value="5">Costos Financieros</option>
			</select>
			<label id="lblmsuser" style="Proceso:red">
				<Cvalidation
					v-if="this.errorvalidacion.Departamento"
					:Mensaje="errorvalidacion.Departamento[0]"
				></Cvalidation
			></label>
		</div>

		<div class="col-lg-6 form-group">
			<label>Cuentas</label>
			<treeselect
				v-if="this.disable!='si'"
				:maxHeight="180"
				:options="ListaCuentas"
				placeholder="Busque una cuenta..."
				v-model="cuentasId"
			/>

			<treeselect
				v-if="this.disable=='si'"
				:disabled="true"
				:maxHeight="180"
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

		<div class="col-lg-4 form-group">
			<label> Monto (sin IVA) </label>
			<vue-numeric
				v-if="this.disable!='si'"
				class="form-control "
				currency="$"
				separator=","
				:precision="2"
				v-model="ctaporpagar.Monto"
				placeholder="$0.00"
			></vue-numeric>

			<vue-numeric
				v-if="this.disable=='si'"
				class="form-control  "
				currency="$"
				separator=","
				:precision="2"
				v-model="ctaporpagar.Monto"
				placeholder="$0.00"
				:disabled="true"
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
			/>

			<treeselect
				v-if="this.disable=='si'"
				:maxHeight="100"
				@input="get_proveedores(proveedoresId)"
				:options="ListaProveedores"
				placeholder="Busque un proveedor..."
				v-model="proveedoresId"
				:disabled="true"
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
				placeholder="NumFactura"
				class="form-control"
			/>

			<input
				v-if="this.disable=='si'"
				v-model="ctaporpagar.NumFactura"
				type="text"
				placeholder="NumFactura"
				class="form-control"
				:disabled="true"
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
				class="form-control"
				:precision="0"
				v-model="ctaporpagar.Credito"
				placeholder="0"
				:disabled="true"
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
					disabled:true
				}"
			/>
			<label id="lblmsuser" style="Proceso:red">
				<Cvalidation
					v-if="this.errorvalidacion.Fecha_Pago"
					:Mensaje="errorvalidacion.Fecha_Pago[0]"
				></Cvalidation
			></label>
		</div>

		<!-- ESTO TODAVÍA NO FUNCIONA -->
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
					:disabled="true"
					@change="uploadInvoice()"
					type="file"
					ref="file"
					name="myfile"
					accept="application/pdf"
					class="custom-file-input"
					id="validatedCustomFile"
					required
				/>
				<input type="text" v-model="invoice" class="form-control" />
				<button type="button" class="">
					<i class="fas fa-paperclip"></i>
				</button>
			</div>
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
		<!--{{ctaporpagar}}-->
	</div>
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from "@/components/Cbtnsave.vue";
import Cvalidation from "@/components/Cvalidation.vue";

export default {
	name: "Form",
	props: ["IdCtaPagar", "poBtnSave"],
	data() {
		return {
			Modal: true, //Sirve pra los botones de guardado
			FormName: "ctaporpagaradmin", //Sirve para donde va regresar
			ListaCategoria: [],
			ListaCuentas: [],
			ListaProveedores: [],
			proveedoresId: null,
			cuentasId: null,
			ctaporpagar: {
				IdCtaPagar: 0,
				TipoSelect: 0,
				FechaFactura: "",
				FechaPago: "",
				Credito: "",
				NumFactura: "",
				Monto: 0,
				Factura: "",
				ArchivoUno: "",
				ArchivoDos: ""
			},
			Anio: "",
			Ruta: "",
			invoice: "Elegir archivo (5 MB)",
			evidenceOne: "Elegir archivo (5 MB)",
			evidenceTwo: "Elegir archivo (5 MB)",
			errorvalidacion: [],
			disable:''
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
			formData.set("TipoCuenta", "ADMIN");
			formData.set("FechaFactura", this.ctaporpagar.FechaFactura.toISOString());
			formData.set("FechaPago", this.ctaporpagar.FechaPago.toISOString());
			formData.set("NumFactura", this.ctaporpagar.NumFactura);
			formData.set("Credito", this.ctaporpagar.Credito);
			formData.set("Monto", this.ctaporpagar.Monto);

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

			this.$http
				.post("ctaporpagar/post", formData)
				.then(res => {
					this.poBtnSave.disableBtn = false;
					this.poBtnSave.toast = 1;
					$("#ModalForm").modal("hide");
					this.bus.$emit("List");
					this.Limpiar();
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
				ArchivoDos: ""
			};
			this.proveedoresId = null;
			this.cuentasId = null;
			this.invoice = "Elegir archivo (5 MB)";
			this.evidenceOne = "Elegir archivo (5 MB)";
			this.evidenceTwo = "Elegir archivo (5 MB)";
			this.errorvalidacion = []
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

					this.ctaporpagar.TipoSelect = objCta.TipoSelect;
					this.cuentasId = objCta.IdAsociado;
					this.ctaporpagar.Monto = objCta.Monto;
					this.proveedoresId = objCta.IdProveedor;
					this.ctaporpagar.NumFactura = objCta.NumFactura;
					this.ctaporpagar.Credito = objCta.Credito;

					this.get_cuentas();

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
			this.ctaporpagar.IdAsociado = "0";
			this.get_cuentas();
			if (this.ctaporpagar.IdCtaPagar == 0) {
				this.cuentasId = null;
				this.ListaCuentas = [];
			}
		},
		get_cuentas() {
			var url = "";

			if (this.ctaporpagar.TipoSelect == 4) {
				url = "costoga/get";

				this.$http
					.get(url, {
						params: { Anio: this.Anio }
					})
					.then(res => {
						this.ListaCuentas = res.data.data.lista.map(function(obj) {
							return {
								id: obj.IdCostoGA,
								label: obj.NumeroCuenta + " - " + obj.Descripcion
							};
						});
					});
			}
			if (this.ctaporpagar.TipoSelect == 1) {
				url = "costoventas/getall";

				this.$http
					.get(url, {
						params: { Anio: this.Anio }
					})
					.then(res => {
						this.ListaCuentas = res.data.data.listagastoctpp.map(function(obj) {
							return {
								id: obj.IdGasto,
								label: obj.NumCuenta + " - " + obj.Gasto
							};
						});
					});
			}
			if (this.ctaporpagar.TipoSelect == 5) {
				url = "costofinanciero/getall";

				this.$http
					.get(url, {
						params: { Anio: this.Anio }
					})
					.then(res => {
						this.ListaCuentas = res.data.data.listacostoctpp.map(function(obj) {
							return {
								id: obj.IdCostoFinanciero,
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
