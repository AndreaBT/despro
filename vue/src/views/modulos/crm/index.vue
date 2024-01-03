<template>
	<div>
		<section class="container-fluid mt-2">
			<Menu :pSitio="NombreSeccion" :pTxtDefault="TxtDefault" :pShowNext="ShowNext">
				<template slot="Localizac">
					<div class="dropdown mr-1">
						<button @click="Open_Ubicacion()" data-toggle="modal" data-target="#ModalForm2" data-backdrop="static" data-keyboard="false" type="button" class="btn btn-02 color-01 mr-1">
							<i class="fas fa-map-marker-alt"></i>
							Localización
						</button>
					</div>
				</template>

                <template slot="BtnInicio">
                    <button type="button" data-toggle="modal" data-target="#ModalForm"  data-backdrop="static" data-keyboard="false" class="btn btn-01 mr-2" @click="Nuevo">Nuevo</button>
                </template>
            </Menu>
			

			<div class="row mt-2">
				<div class="col-md-12 col-lg-9 col-xl-9">
					<!--<div class="card card-calendar card-scroll-2">-->
					<div class="card">
						<div class="card-body">
							<div id="calendario" class="full-calendar">
								<FullCalendar
									:locale="locale"
									:contentHeight="500"
									ref="fullCalendar"
									defaultView="dayGridMonth"
									:header="{
										left: 'prev,next today',
										center: 'title',
										right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
									}"
									:plugins="calendarPlugins"
									:weekends="calendarWeekends"
									:events="calendarEvents"
									:eventLimit="true"
									@dateClick="handleDateClick"
									@eventClick="eventClick"
									:class="'full-calendar '"
								/>

								<Modal :Showbutton="false" :size="size" :Nombre="NameList">
									<template slot="Form">
										<Form></Form>
									</template>
								</Modal>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-12 col-lg-3 col-xl-3">
					<div class="card card-scroll">
						<div class="card-body">
							<h5 class="card-title">{{ FechaSeleccionada }}</h5>
							<table class="table table-sm table-striped table-hover align_middle">
								<thead>
									<tr>
										<th>Tareas</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="(item, index) in ListaTareas" :key="index">
										<td @click="Editar(item.IdSeguimientoCliente)">
											{{
												item.Actividad+" - " +"Proceso: "+item.Proceso+". Hora: "+item.HoraInicio
											}}
										</td>
										<td>
											<button v-show="item.Status" :id="'btn_' + item.IdSeguimientoCliente" type="button" @click="Eliminar(item.IdSeguimientoCliente)" class="btn-icon-02">
												<i class="fas fa-trash-alt"></i>
											</button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</section>
		<Modal :NameModal="'ModalForm2'" :Showbutton="false" :size="'modal-lg'" :Nombre="'Ubicación'" >
			<template slot="Form">
				<Cmapa2 :Arreglo="markers" :rutatrab="rutatrab"></Cmapa2>
			</template>
		</Modal>
	</div>
</template>

<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import esLocale from "@fullcalendar/core/locales/es";
import Form from "../crm/calendario/form";
import Modal from "@/components/Cmodal.vue";
import Cmapa2 from "@/components/Cmapa2.vue";
import Menu from "../crm/indexMenu.vue";

export default {
	components: {
		FullCalendar, // make the <FullCalendar> tag available
		Form,
		Modal,
		Cmapa2,
		Menu
	},
	data: function() {
		return {
			calendarPlugins: [
				// plugins must be defined in the JS
				dayGridPlugin,
				timeGridPlugin,
				interactionPlugin // needed for dateClick
			],
			calendarWeekends: true,
			calendarEvents: [
				// initial event data
			],
			locale: esLocale,
			EsModal: true, //indica si es modal o no
			size: "modal-md",
			NameList: "Seguimiento",
			ListaTareas: [],
			FechaSeleccionada: "",
			markers: [],
			rutatrab: "",
			NombreSeccion: '',
			TxtDefault: 'Calendario CRM',
			ShowNext: false,
		};
	},
	methods: {
		Nuevo() {
			this.bus.$emit("Nuevo", true);
		},
		//   Nuevo(){
		//       this.bus.$emit('LimpiarAgenda');
		//   },
		get_listcalendar() {
			this.$http.get("crmseguimiento/list").then(res => {
				this.calendarEvents = [];

				const actividades = res.data.data.seguimiento;

				actividades.forEach((item, index) => {
					this.calendarEvents.push({
						start: item.start,
						end: item.end,
						title: "Título: " + item.Actividad, //+' \n'+' Cliente: '+ item.ClienteSucursal+' \n'+' Inicio: '+ item.HoraInicio.substring(0,5) ,
						allDay: false,
						id: item.IdSeguimientoCliente,
						color: item.Color,
						background: item.Color,
						textColor: item.ColorLetra
					});
				});
			});
		},
		get_listtareas(Fecha) {
			this.$http
				.get("crmseguimiento/list", {
					params: { Fecha: Fecha }
				})
				.then(res => {
					this.ListaTareas = res.data.data.seguimiento;

					//añade zeros (para comparar la hora)
					function addZero(i) {
						if (i < 10) {
							i = "0" + i;
						}
						return i;
					}
					//obtiene fecha, hora, minuto y segundo actual
					const current = new Date();
					let day = String(current.getDate()).padStart(2, "0"); //añade zeros (para comparar la fecha)
					let month = String(current.getMonth() + 1).padStart(2, "0");
					let year = current.getFullYear();
					let hour = addZero(current.getHours());
					let min = addZero(current.getMinutes());
					let sec = addZero(current.getSeconds());
					let dateActual = year + "-" + month + "-" + day;
					let timeActual = hour + ":" + min + ":" + sec;
					//añade Status a cada objeto y valida si la hora y fecha han pasado para ocultar el botón de eliminar (v-show)
					this.ListaTareas.forEach((item, index) => {
						var bandera = true;
						if (item.Fecha <= dateActual && item.HoraFin <= timeActual) {
							bandera = false;
						}
						item.Status = bandera;
					});
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

					this.$http.delete("crmseguimiento/" + Id).then(res => {
						this.bus.$emit("ListAgendaS");
					});
				}
			});
		},
		toggleWeekends() {
			this.calendarWeekends = !this.calendarWeekends; // update a property
		},
		gotoPast() {
			let calendarApi = this.$refs.fullCalendar.getApi(); // from the ref="..."
			calendarApi.gotoDate("2000-01-01"); // call a method on the Calendar object
		},
		handleDateClick(arg) {
			let data = { Fecha: arg.dateStr };
			this.FechaSeleccionada = data.Fecha;
			localStorage.setItem("fechacalendario", data.Fecha);//

			let dias = [
				"Lunes",
				"Martes",
				"Miercoles",
				"Jueves",
				"Viernes",
				"Sabado",
				"Domingo"
			];
			let meses = [
				"Enero",
				"Febrero",
				"Marzo",
				"Abril",
				"Mayo",
				"Junio",
				"Julio",
				"Agosto",
				"Septiembre",
				"Octubre",
				"Noviembre",
				"Diciembre"
			];

			let date = new Date(data.Fecha);
			var fechaNum = date.getDate() + 1;
			var mes_name = date.getMonth();

			this.FechaSeleccionada =
				dias[date.getDay()] +
				" " +
				fechaNum +
				" de " +
				meses[mes_name] +
				" de " +
				date.getFullYear();

			this.get_listtareas(data.Fecha);
			// if (confirm('Desea ir al calendario de la fecha seleccionada ' + arg.dateStr + ' ?')) {
			//   let data={Fecha:arg.dateStr}
			//   this.$router.push({name:'despacho',params:{objeto:data}});
			// }
		},
		eventClick: function(calEvent, jsEvent, view) {
			this.Editar(calEvent.event.id);
			//list_serviciofecha(calEvent.FechaInicio);

			// change the border color just for fun
			//$(this).css('border-color', callEvent.color);
		},
		Editar(Id) {
			this.bus.$emit("Nuevo", null, Id);
			$("#ModalForm").modal("show");
		},
		Contactos() {
			this.$router.push({ name: "crmcontactos", params: {} });
		},
		Oportunidad() {
			this.$router.push({ name: "crmoportunidad", params: {} });
		},
		Procesos() {
			this.$router.push({ name: "crmtiposprocesos", params: {} });
		},
		vendedores() {
			this.$router.push({ name: "crmvendedores", params: {} });
		},
		pipedrive() {
			this.$router.push({ name: "crmpipedrive", params: {} });
		},
		forecast() {
			this.$router.push({ name: "crmforecast", params: {} });
		},
		Open_Ubicacion() {
			this.bus.$emit("OpenModal");
		},
		async getUbicaciones() {
			await this.$http
				.get("ubicacionmapa/getvendedor", {
					params: {}
				})
				.then(res => {
					this.rutatrab = res.data.data.ruta;
					this.markers = [];
					res.data.data.ubicaciones.forEach(element => {
						this.markers.push({
							position: {
								lat: parseFloat(element.lat),
								lng: parseFloat(element.lng)
							},
							datos: {
								Tecnico: element.Nombre,
								Cliente: element.ClienteSucursal,
								Actividad: element.Actividad,
								Direccion: element.Direccion,
								FechaI: "",
								FechaF: "",
								HoraI: element.HoraInicio,
								HoraF: element.HoraFin,
								Estatus: "",
								Foto2: element.Foto2,
								Tipo: "Vendedor"
							}
						});
					});

					res.data.data.clientes.forEach(element => {
						this.markers.push({
							position: {
								lat: parseFloat(element.lat),
								lng: parseFloat(element.lng)
							},
							datos: {
								Tecnico: element.Nombre,
								Cliente: "",
								Actividad: element.Telefono,
								Direccion: element.Direccion,
								FechaI: "",
								FechaF: "",
								HoraI: "",
								HoraF: "",
								Estatus: "",
								Foto2: element.IdIconoEmp,
								Tipo: "Cliente"
							}
						});
					});
				});
		}
	},
	created() {
		var f = new Date();
		var FF = f.getFullYear() + "-" + (f.getMonth() + 1) + "-" + f.getDate();

		let dias = [
			"Lunes",
			"Martes",
			"Miercoles",
			"Jueves",
			"Viernes",
			"Sabado",
			"Domingo"
		];
		let meses = [
			"Enero",
			"Febrero",
			"Marzo",
			"Abril",
			"Mayo",
			"Junio",
			"Julio",
			"Agosto",
			"Septiembre",
			"Octubre",
			"Noviembre",
			"Diciembre"
		];

		let date = new Date(FF);

		var fechaNum = date.getDate();
		var mes_name = date.getMonth();

		if (dias[fechaNum] != undefined) {
			var date2 = new Date(date);
		} else {
			var date2 = new Date();
		}
		let options = {
			weekday: "long",
			year: "numeric",
			month: "long",
			day: "numeric"
		};

		var FF = f.getFullYear() + "-" + (f.getMonth() + 1) + "-" + f.getDate();
		let Dia = f.getDate();
		let Mes = f.getMonth() + 1;
		let Anio = f.getFullYear();

		let Dia2 = Dia;

		if (Dia < 10) {
			Dia2 = "0" + Dia;
		}

		let Mes2 = Mes;
		if (Mes < 10) {
			Mes2 = "0" + Mes;
		}

		let FechaN = Anio + "-" + Mes2 + "-" + Dia2;

		let NombreFecha = date2.toLocaleDateString("es-MX", options);

		localStorage.setItem("fechacalendario", FechaN);
		//localStorage.setItem("FechaSeleccionada", NombreFecha);

		this.FechaSeleccionada = NombreFecha;
		//alert(FF);

		this.get_listcalendar();
		this.get_listtareas(FF);

		this.bus.$off("ListAgendaS");
		this.bus.$on("ListAgendaS", () => {
			this.get_listcalendar();
			this.get_listtareas(FF);
		});

		this.bus.$off("ListarUbicacion");
		this.bus.$on("ListarUbicacion", () => {
			this.getUbicaciones();
		});
	}
};
</script>

<style lang="scss">
// you must include each plugins' css
// paths prefixed with ~ signify node_modules
@import "~@fullcalendar/core/main.css";
@import "~@fullcalendar/daygrid/main.css";
@import "~@fullcalendar/timegrid/main.css";

.fc-more {
	font-size: 14px;
	font-weight: bold;
}
.fc-more-cell {
	text-align: center;
}
</style>
