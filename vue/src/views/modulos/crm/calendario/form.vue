<template>
	<div>
		<div class="form-group form-row">
			<div class="col-lg-7 ">
				<label>Actividad </label>
				<input :disabled="CerradoEstatus" type="text" v-model="objproceso.Actividad" class="form-control form-control-sm" placeholder="Actividad" id="Actividad" name="Actividad"/>
				<Cvalidation v-if="this.errorvalidacion.Actividad" :Mensaje="errorvalidacion.Actividad[0]"/>
			</div>

			<div class="col-lg-5">
				<label>Vendedor</label>
				<select :disabled="CerradoEstatus" v-model="objproceso.IdTrabajador" class="form-control form-control-sm" >
					<option :value="''">Seleccione un vendedor</option>
					<option v-for="(item, index) in Listavendedor" :key="index" :value="item.IdUsuario" >
						{{ item.NombreTrabajador }}
					</option>
				</select>
				<Cvalidation v-if="this.errorvalidacion.Vendedor" :Mensaje="errorvalidacion.Vendedor[0]"/>
			</div>
		</div>

		<div class="form-group form-row">
			<div class="col-lg-9">
				<label>Oportunidad </label>
				<input type="text" readonly="readonly" v-model="objproceso.Oportunidad" class="form-control form-control-sm" placeholder="Oportunidad" id="Oportunidad" name="Oportunidad" />
				<Cvalidation v-if="this.errorvalidacion.Oportunidad" :Mensaje="errorvalidacion.Oportunidad[0]"/>
			</div>

			<div class="col-lg-3" v-if="objproceso.IdTrabajador>0">
				<div class="form-inline">
					<button :disabled="CerradoEstatus" @click="ListarOportunidad(objproceso.IdTrabajador)" data-toggle="modal" data-target="#ModalForm3" data-backdrop="static" type="button" class="btn btn-01 search mt-3b" >
						Buscar
					</button>
				</div>
			</div>
		</div>

		<div class="form-group form-row">
			<div class="col-lg-9">
				<label>Cliente </label>
				<input type="text" readonly="readonly" v-model="objproceso.Cliente" class="form-control form-control-sm" placeholder="Cliente" />
				<label id="lblmsuser" style="Cliente:red" v-if="this.errorvalidacion.Cliente"><Cvalidation :Mensaje="errorvalidacion.Cliente[0]" ></Cvalidation ></label>
			</div>

			<div class="col-lg-3">
				<label>Fecha</label>
				<v-date-picker v-model="objproceso.Fecha" :mode="typeCalendar" :min-date="new Date()"
					:popover="{
						placement: 'bottom',
						visibility: 'click'
					}"
					:input-props="{
						class: 'form-control form-control-sm cal-black',
						style: 'cursor:pointer;background-color:#F9F9F9',
						readonly: true
					}"
				/>
				<label id="lblmsuser" style="TipoProceso:red" v-if="this.errorvalidacion.Fecha" ><Cvalidation :Mensaje="errorvalidacion.Fecha[0]" ></Cvalidation ></label>
			</div>
		</div>

		<div class="form-group form-row">
			
			<div class="col-lg-3">
				<label>Hora inicio</label>
				<!-- <the-mask :disabled="CerradoEstatus" v-model="objproceso.HoraInicio" :mask="['##:##']" class="form-control form-control-sm" ></the-mask> -->
				<select  v-model="objproceso.HoraInicio" class="form-control form-control-sm" :disabled="CerradoEstatus">
					<option value="">Seleccionar</option>
					<option v-for="(item, index) in ListaHoras" :key="index" :value="item">{{item}}</option>
				</select>
				<label id="lblmsuser" style="TipoProceso:red" v-if="this.errorvalidacion.HoraInicio" ><Cvalidation :Mensaje="errorvalidacion.HoraInicio[0]" ></Cvalidation ></label>
			</div>

			<div class="col-lg-3">
				<label>Hora fin</label>
				<!-- <the-mask :disabled="CerradoEstatus" v-model="objproceso.HoraFin" :mask="['##:##']" class="form-control form-control-sm" ></the-mask> -->
				<select  v-model="objproceso.HoraFin" class="form-control form-control-sm" :disabled="CerradoEstatus">
					<option value="">Seleccionar</option>
					<option v-for="(item, index) in ListaHoras" :key="index" :value="item">{{item}}</option>
				</select>
				<label id="lblmsuser" style="TipoProceso:red" v-if="this.errorvalidacion.HoraFin" ><Cvalidation :Mensaje="errorvalidacion.HoraFin[0]" ></Cvalidation ></label>
			</div>

			<div class="col-lg-6">
				<label>Proceso </label>
				<input type="text" readonly="readonly" v-model="objproceso.TipoProceso" class="form-control form-control-sm" placeholder="TipoProceso" />
				<label id="lblmsuser" style="TipoProceso:red" v-if="this.errorvalidacion.TipoProceso" ><Cvalidation :Mensaje="errorvalidacion.TipoProceso[0]" ></Cvalidation ></label>
			</div>
		</div>

			<!-- {{objproceso.Fecha}}
                        {{objproceso.HoraInicio}}
                        {{objproceso.HoraFin}}


                        <HoraDia 
                        v-model="objproceso" 
                        :errors="errorvalidacion" 
                        /> -->

		<div class="form-group form-row">
			
			<div class="col-lg-6">
				<label>Etapa</label>
				<select :disabled="CerradoEstatus" @change="GetProceso" v-model="objproceso.IdProceso" class="form-control form-control-sm">
					<option value="">Seleccione una etapa</option>
					<option v-for="(item, index) in Listaprocesos" :key="index" :value="item.IdCrmProceso" >
						{{ item.Nombre }}
					</option>
				</select>
				<Cvalidation v-if="this.errorvalidacion.Etapa" :Mensaje="errorvalidacion.Etapa[0]" ></Cvalidation>
			</div>

			<div class="col-lg-6">
				<label>Estatus Oportunidad </label>
				<select id="DropDownEstatus" :disabled="Concluido" @change="GetEstatus" v-model="objproceso.Estatus" class="form-control form-control-sm">
					<option value="">Seleccione un estatus</option>
					<option :value="lista.label" v-for="(lista, key, index) in ListaEstatus" :key="index" >
						{{ lista.label }}
					</option>
					<option v-if="this.VendidoValue == true" value="Vendido">Vendido</option>
				</select>
				<label id="lblmsuser" style="Estatus:red" v-if="this.errorvalidacion.Estatus"><Cvalidation  :Mensaje="errorvalidacion.Estatus[0]" ></Cvalidation ></label>
			</div>
		</div>

		<div class="form-group form-row">
			<!-- MONTO PROPUESTA NUEVO CAMPO -->
			<div class="col-lg-4" v-show="MostrarArchivo == true">
				<label>Monto propuesta</label>
				<input :disabled="VendidoEstatus" type="text" v-model="objproceso.MontoPropuesta" class="form-control form-control-sm" placeholder="Monto propuesta" />
				<label id="lblmsuser" style="Proceso:red" v-if="this.errorvalidacion.MontoPropuesta" ><Cvalidation :Mensaje="errorvalidacion.MontoPropuesta[0]" ></Cvalidation></label>
			</div>

			<div class="col-lg-6" v-show="MostrarArchivo == true">
				<label>Evidencia</label>
				<div class="custom-file-input-image">
					<input :disabled="VendidoEstatus" @change="uploadImage()" type="file" ref="file" name="myfile" accept="application/pdf" class="custom-file-input" id="validatedCustomFile" required/>
					<input type="text" v-model="NameFile" class="form-control form-control-sm" />
					<button type="button" class="" style="height:31px !important;line-height:31px !important">
						<i class="fas fa-paperclip"></i>
					</button>
				</div>
			</div>

			<div class="col-lg-1" v-show="MostrarArchivo == true">
				<div v-if="objproceso.Archivo != ''">
					<button title="Archivo" @click="open_file()" type="button" class="btn-icon mr-4 mt-6" >
						<i class="fas fa-file-pdf"></i>
					</button>
				</div>
			</div>
		</div>

		<div class="form-group form-row">

			<template>
				<div class="col-lg-4" v-if="objproceso.Estatus == 'Vendido' || this.SinServicio == true">
					<label>Servicio </label>
					<select :disabled="SeteaIdConfig" v-model="objproceso.IdConfigS" class="form-control form-control-sm" >
						<option :value="''">Seleccionar un tipo</option>
						<option value="1">Mantenimiento</option>
						<option value="2">Servicio</option>
						<option value="3">Proyecto</option>
					</select>
					<label id="lblmsuser" style="Estatus:red" ><Cvalidation v-if="this.errorvalidacion.Servicio" :Mensaje="errorvalidacion.Servicio[0]" ></Cvalidation ></label>
				</div>

				<div class="col-lg-4" v-if="objproceso.Estatus == 'Vendido'">
					<label>Monto venta</label>
					<input :disabled="VendidoEstatus" type="text" v-model="objproceso.MontoP" class="form-control form-control-sm" placeholder="Monto venta" />
					<label id="lblmsuser" style="Proceso:red"> <Cvalidation v-if="this.errorvalidacion.Monto" :Mensaje="errorvalidacion.Monto[0]" ></Cvalidation ></label>
				</div>
			</template>

			<!--fin col-6-->
		</div>
		
		<div class="row">
			<div class="col-lg-12">
				<label>Observaciones</label>
				<textarea :disabled="Concluido" class="form-control form-control-sm" name="" v-model="objproceso.Comentarios" id="" cols="30" rows="3" ></textarea>
			</div>
		</div>

		<div class="f1-buttons mt-4">
			<button @click="Guardar" type="button" class="btn btn-01">
				<i v-show="Disablebtn" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>
				<i class="fa fa-plus-circle"></i> 
				{{ txtSave }}
			</button>
		</div>

		<Oportunidad :TipoModal="2"></Oportunidad>
	</div>
</template>
<script>
import moment from "moment"

import Modal from "@/components/Cmodal.vue";
import HoraDia from "../calendario/components/Hora";
import Oportunidad from "../calendario/components/Buscaroportunidad";
export default {
	name: "Form",
	props: ["ocliente", "poBtnSave"],
	components: {
		Modal,
		HoraDia,
		Oportunidad
	},
	data() {
		return {
			size: "none",
			NameList: "Asignar proceso",
			oBtnSave: {
				//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
				isModal: true,
				disableBtn: false,
				toast: 0
			},
			Modal: true, //Sirve pra los botones de guardado
			FormName: "cliente", //Sirve para donde va regresar
			objproceso: {
				Estatus: "",
				Cliente: "",
				Oportunidad: "",
				IdTrabajador: "",
				IdClienteSucursal: "",
				IdProceso: "",
				Actividad: "",
				MontoP: "",
				MontoPropuesta: "",
				IdConfigS: "",
				IdOportunidad: "",
				HoraInicio: "",
				HoraFin: "",
				Fecha: "",
				Estatus: "",
				IdTipoProceso: "",
				Comentarios: "",
				IdSeguimientoCliente: "",
				Archivo: ""
			},
			urlApi: "crmseguimiento/recovery",
			urlApivendedor: "trabajador/ListTrabRolQuery",
			urlApiVendedorNuevo:"vendedores/get",
			errorvalidacion: [],
			Listavendedor: [],
			Listaprocesos: [],
			Listaservicios: [],
			Disablebtn: false,
			MostrarArchivo: false,
			txtSave: "Guardar",
			NombreModal: "",
			HoraActividad: false,
			Concluido: false,
			SinServicio: false,
			SeteaIdConfig: true,
			CerradoEstatus: false,
			VendidoEstatus: false,
			VendidoValue: false,
			ListaEstatus: [
				{ id: "1", label: "Abierta" },
				{ id: "2", label: "Cerrada" }
			],
			Rol: ["Vendedor", "Gerente de ventas"],
			Ruta: "",
			NameFile: "Elegir archivo (5 MB)", 
			ListaHoras: [],
		};
	},
	methods: {
		Limpiar() {
			this.errorvalidacion = [];

			this.objproceso = {
				Estatus: "",
				Cliente: "",
				Oportunidad: "",
				IdTrabajador: "",
				IdClienteSucursal: "",
				IdProceso: "",
				Actividad: "",
				MontoP: "",
				MontoPropuesta: "",
				IdConfigS: "",
				IdOportunidad: "",
				HoraInicio: "",
				HoraFin: "",
				Fecha: "",
				Estatus: "",
				IdTipoProceso: "",
				Comentarios: "",
				IdSeguimientoCliente: "",
				Archivo: ""
			};
			this.SinServicio = false;
			this.SeteaIdConfig = true;
			this.VendidoValue = false;
			this.MostrarArchivo = false;
			this.Listaprocesos = [];
			this.CerradoEstatus = false;
			this.HoraActividad = false;
			this.VendidoEstatus = false;
			this.Concluido = false;
			this.NameFile = "Elegir archivo (5 MB)";

			if (localStorage.getItem("fechacalendario")) {
				let NuevaFecha = localStorage.getItem("fechacalendario");
				let formatedDate = NuevaFecha.replace(/-/g, "\/");
				this.objproceso.Fecha = new Date(formatedDate);
			}
		},
		/*async ListaVendedores() {
			await this.$http
				.get(this.urlApivendedor, {
					params: { Rol: JSON.stringify(this.Rol) }
				})
				.then(res => {
					this.Listavendedor = res.data.data.lista;
					this.Limpiar();
				});
		},*/
		async ListaVendedores() {
			await this.$http
				.get(this.urlApiVendedorNuevo, {
					params: { }
				})
				.then(res => {
					this.Listavendedor = res.data.data.Vendedores;
					this.Limpiar();
				});
		},
		ListarOportunidad(Id) {
			//this.NombreModal = 'Oportunidad';//Listoportunida
			this.bus.$emit("Listoportunida", Id);
		},
		get_revovy() {
			this.$http
				.get("crmseguimiento/recovery", {
					params: { IdSeguimientoCliente: this.objproceso.IdSeguimientoCliente }
				})
				.then(res => {
					this.procesosget(res.data.data.seguimiento.IdTipoProceso);
					const objes = res.data.data.seguimiento;

					let HIni = objes.HoraInicio;
					HIni = moment('2022-12-01 '+HIni).format('h:mm');

					let HFin = objes.HoraFin;
					HFin = moment('2022-12-01 '+HFin).format('h:mm');

					//this.objproceso =res.data.data.seguimiento;
					this.objproceso.IdProceso 			= objes.IdProceso;
					this.objproceso.IdTrabajador 		= objes.IdTrabajador;
					this.objproceso.IdClienteSucursal 	= objes.IdClienteSucursal;
					this.objproceso.Cliente 			= objes.ClienteSucursal;
					this.objproceso.Actividad			= objes.Actividad;
					this.objproceso.MontoP 				= objes.MontoP;
					this.objproceso.MontoPropuesta 		= objes.MontoPropuesta;
					this.objproceso.IdConfigS 			= objes.IdConfigS;
					this.objproceso.IdOportunidad 		= objes.IdOportunidad;
					this.objproceso.HoraInicio 			= HIni;
					this.objproceso.HoraFin 			= HFin;
					this.objproceso.IdTipoProceso 		= objes.IdTipoProceso;
					this.objproceso.IdSeguimientoCliente= objes.IdSeguimientoCliente;
					this.objproceso.Oportunidad 		= objes.Oportunidad;
					this.objproceso.Estatus 			= objes.Estatus;
					this.objproceso.Comentarios 		= objes.Comentarios;

					this.Ruta = res.data.ruta;

					if (objes.Archivo != "undefined" && objes.Archivo != "") {
						this.MostrarArchivo = true;
						this.NameFile = objes.Archivo;
						this.objproceso.Archivo = objes.Archivo;
					} else {
						this.MostrarArchivo = false;
					}

					if (objes.Estatus == "Cerrada") {
						this.CerradoEstatus = true;
						this.Concluido = true;
						this.VendidoEstatus = true;
					} else {
						if (objes.Estatus == "Abierta" || objes.Estatus == "") {
							this.CerradoEstatus = false;
						}
					}
					if (objes.Estatus == "Vendido") {
						this.VendidoEstatus = true;
						this.CerradoEstatus = true;
						this.Concluido = true;
					}

					//añade zeros (necesarios para comparar la hora)
					function addZero(i) {
						if (i < 10) {
							i = "0" + i;
						}
						return i;
					}
					//obtiene fecha, hora, minuto y segundo actual
					const current = new Date();
					let day = String(current.getDate()).padStart(2, "0");
					let month = String(current.getMonth() + 1).padStart(2, "0");
					let year = current.getFullYear();
					let hour = addZero(current.getHours());
					let min = addZero(current.getMinutes());
					let sec = addZero(current.getSeconds());
					let dateActual = year + "-" + month + "-" + day;
					let timeActual = hour + ":" + min + ":" + sec;
					/*si la horaFin es menor que la hora actual bloquea controles
					si la fecha de la actividad es diferente a la actual bloquea controles*/
					if (objes.Fecha <= dateActual && objes.HoraFin <= timeActual) {
						this.CerradoEstatus = true;
					}
					let formatedDate = objes.Fecha.replace(/-/g, "\/");
					this.objproceso.Fecha = new Date(formatedDate);
				});
		},
		Validar() {
			let bandera = true;
			// if (this.objproceso.Actividad=='')
			// {
			//     this.$toast.info('La actividad se encuentra vacio');
			//     bandera= false;
			// }

			// if (this.objproceso.IdOportunidad==0 ||this.objproceso.IdOportunidad=='')
			// {
			//     this.$toast.info('Debe seleccionar una oportunidad');
			//     bandera= false;
			// }
			// if (this.objproceso.IdProceso==0 ||this.objproceso.IdProceso=='')
			// {
			//     this.$toast.info('Debe seleccionar un proceso');
			//     bandera= false;
			// }

			return bandera;
		},
		async Guardar() {
			//alert('en proceso');

			let validacion = this.Validar();

			if (validacion == false) {
				return false;
			} else {
				let Fecha = "0000-00-00";
				if (this.objproceso.Fecha != "") {
					let day = this.objproceso.Fecha.getDate();
					let month = this.objproceso.Fecha.getMonth() + 1;
					let year = this.objproceso.Fecha.getFullYear();
					Fecha = year + "-" + month + "-" + day;
				}

				let formData = new FormData();
				formData.set(
					"IdSeguimientoCliente",
					this.objproceso.IdSeguimientoCliente
				);
				formData.set("IdTrabajador", this.objproceso.IdTrabajador);
				formData.set("IdClienteSucursal", this.objproceso.IdClienteSucursal);
				formData.set("HoraInicio", this.objproceso.HoraInicio);
				formData.set("HoraFin", this.objproceso.HoraFin);
				formData.set("IdProceso", this.objproceso.IdProceso);
				formData.set("Actividad", this.objproceso.Actividad);
				formData.set("MontoP", this.objproceso.MontoP);
				formData.set("MontoPropuesta", this.objproceso.MontoPropuesta);
				formData.set("IdConfigS", this.objproceso.IdConfigS);
				formData.set("IdOportunidad", this.objproceso.IdOportunidad);
				formData.set("Estatus", this.objproceso.Estatus);
				formData.set("IdTipoProceso", this.objproceso.IdTipoProceso);
				formData.set("Comentarios", this.objproceso.Comentarios);
				formData.set("Fecha", Fecha);
				formData.set("NombreFoto", this.objproceso.Archivo);
				if (this.objproceso.Archivo == undefined) {
					formData.set("NombreFoto", "");
				}

				let file = this.$refs.file.files[0];
				formData.append("File", file);

				this.Disablebtn = true;
				this.txtSave = " Espere...";

				this.$http
					.post("crmseguimiento/post", formData)
					.then(res => {
						this.Disablebtn = false;
						this.txtSave = " Guardar";
						$("#ModalForm").modal("hide");
						this.bus.$emit("ListAgendaS");
						this.$toast.success("Información guardada");
					})
					.catch(err => {
						this.Disablebtn = false;
						this.txtSave = " Guardar";
						this.errorvalidacion = err.response.data.message.errores;
						this.$toast.info("Complete la Información");
					});
			}
		},
		ObtenerOportunidad(obj) {
			//alert(JSON.stringify(obj));
			this.objproceso.Oportunidad = obj.Nombre;
			this.objproceso.Cliente = obj.Sucursal;
			this.objproceso.IdTipoProceso = obj.IdTipoP;
			this.objproceso.IdClienteSucursal = obj.IdClienteS;
			this.objproceso.IdOportunidad = obj.IdOportunidad;
			this.procesosget(obj.IdTipoP);
		},
		procesosget(Id) {
			this.$http
				.get("crmtipoandproceso/list", {
					params: {
						IdTipoProceso: Id,
						IdOportunidad: this.objproceso.IdOportunidad,
						IdTrabajador: this.objproceso.IdTrabajador
					}
				})
				.then(res => {
					const objProcessT = res.data.data.tipoproceso;
					const objActivity = res.data.data.actividad;

					if (objActivity.IdSeguimientoCliente > 0) {
						//Valores recuperados de una oportunidad si esta contiene información previa
						this.Listaprocesos = res.data.data.procesos;
						this.objproceso.TipoProceso = objProcessT.Nombre;
						this.objproceso.IdConfigS = objProcessT.IdConfigS;
						this.objproceso.IdProceso = objActivity.IdProceso;
						this.objproceso.Estatus = objActivity.Estatus;
						this.objproceso.MontoPropuesta = objActivity.MontoPropuesta;

						if (
							objActivity.Archivo != "undefined" &&
							objActivity.Archivo != ""
						) {
							this.MostrarArchivo = true;
							this.NameFile = objActivity.Archivo;
							this.objproceso.Archivo = objActivity.Archivo;
						}
					} else {
						//Valores de editar (clic en la lista de actividades del calendario)

						this.objproceso.TipoProceso = objProcessT.Nombre;
						this.Listaprocesos = res.data.data.procesos;
						this.objproceso.IdConfigS = objProcessT.IdConfigS;
					}

					if (this.objproceso.IdConfigS <= 0) {
						this.SeteaIdConfig = false;
						this.SinServicio = true;
					} else {
						this.SeteaIdConfig = true;
						this.SinServicio = false;
					}
					var buscar = this.Listaprocesos.filter(element => {
						if (this.objproceso.IdProceso == element.IdCrmProceso)
							return element;
					});
					if (buscar.length > 0) {
						if (buscar[0].Nombre == "Propuestas") {
							this.MostrarArchivo = true;
						} else {
							this.MostrarArchivo = false;
						}
					}
					if (buscar.length > 0) {
						if (buscar[0].Nombre == "Cierre") {
							this.VendidoValue = true;
						} else {
							this.VendidoValue = false;
						}
					}
				});
		},
		ConfigServicio() {
			this.$http
				.get("configservicio/get", {
					params: { pag: 1, Entrada: 100, Facturable: "S" }
				})
				.then(res => {
					this.Listaservicios = res.data.data.configservicio;
				});
		},
		GetProceso() {
			var busqueda = this.Listaprocesos.filter(element => {
				if (this.objproceso.IdProceso == element.IdCrmProceso) return element;
			});
			//Muestra MontoPropuesta, Archivo
			if (busqueda.length > 0) {
				if (busqueda[0].Nombre == "Propuestas") {
					this.MostrarArchivo = true;
				} else {
					this.MostrarArchivo = false;
				}
			}
			//Muestra Estatus vendido
			if (busqueda.length > 0) {
				if (busqueda[0].Nombre != "Cierre") {
					this.VendidoValue = false;
				} else {
					this.VendidoValue = true;
				}
			}
			////Oculta MontoPropuesta, Archivo if seleccionar
			if (this.objproceso.IdProceso == "") {
				this.MostrarArchivo = false;
			}
		},
		GetEstatus() {
			//Deshabilita los controles cuando el estatus es cerrado
			//No deshabilita monto propuesta y evidencia
			if (this.objproceso.Estatus == "Cerrada") {
				this.CerradoEstatus = true;
			} else {
				if (
					this.objproceso.Estatus == "Abierta" ||
					this.objproceso.Estatus == ""
				) {
					this.CerradoEstatus = false;
				}
			}
			/*Deshabilita monto propuesta y evidencia para que ingrese el
			monto venta que se verá reflejado en su gráfica*/
			if (this.objproceso.Estatus == "Vendido") {
				this.VendidoEstatus = false;
				this.CerradoEstatus = true;
			}
		},
		open_file() {
			//window.open(this.RutaPdf+File , '_blank');
			let pdfWindow = window.open(this.Ruta + this.objproceso.Archivo);
			pdfWindow.document.write(
				"<iframe width='100%' height='100%' src='" +
					this.Ruta +
					this.objproceso.Archivo +
					"'></iframe>"
			);
		},
		uploadImage() {
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
				this.NameFile = "Elegir archivo (5 MB)";
				return false;
			}

			this.NameFile = image.name;
			/*
            const reader = new FileReader();
            var img="";
            reader.readAsDataURL(image);
            reader.onload= e =>{
                this.Img = e.target.result;
            }*/
		},
		async get_horas()
        {
            await this.$http.get(
                'horaslaborales/horaslaborales',
                {
                    params:{}
                }
            ).then( (res) => {
                this.ListaHoras = res.data.data.horaslaborales;
            });
        },
	},
	created() {
		//this.Limpiar();
		this.get_horas();
		this.ListaVendedores();
		this.ConfigServicio();

		this.bus.$off("SeleccionarOportunidad");
		this.bus.$on("SeleccionarOportunidad", oOportunidad => {
			//alert(JSON.stringify(oOportunidad));
			this.ObtenerOportunidad(oOportunidad);
		});

		// console.log(sessionStorage.setItem('FechaSeleccionada'));
		// if(sessionStorage.setItem('FechaSeleccionada') != '')
		// {
		//    this.objproceso.Fecha = sessionStorage.setItem('FechaSeleccionada');
		// }

		this.bus.$off("Regresar");
		this.bus.$off("Nuevo");
		this.bus.$off("SeleccionarCliente");
		//this.ListaServ();

		this.bus.$on("SeleccionarCliente", oSucursal => {
			this.SeleccionarCliente(oSucursal);
		});

		this.bus.$on("Regresar", () => {
			//this.ListaCliente();
		});

		this.bus.$on("Nuevo", (data, Id) => {
			this.Limpiar();
			if (Id == undefined) {
				Id = 0;
			}
			this.ShowComponent = false;

			this.objproceso.IdSeguimientoCliente = Id;
			if (Id > 0) {
				//console.log('2');
				this.get_revovy();
			} else {
				//console.log('3');
				this.Limpiar();
				this.ShowComponent = true;
			}
			const input = this.$refs.file;
			input.type = "text";
			input.type = "file";
		});
	},
	computed: {
		isEstatus() {
			if (objproceso.Estatus == 3) {
				return true;
			}

			return false;
		},
		typeCalendar: function() {
			// if(this.tipo == 'guardia')
			//     return 'range';

			return "single";
		}
	}
};
</script>
