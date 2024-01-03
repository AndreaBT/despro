<template>
    <section class="container-fluid mt-2">
        <div class="row mt-2">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <nav class="navbar navbar-breadcrumb navbar-expand-md bg-breadcrumb breadcrumb-borde">
                    <div class="mr-auto">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb clearfix pt-3">
                                <li class="breadcrumb-item"><a href="#">Despacho</a></li>
                                <li class="breadcrumb-item active">Calendario</li>
                            </ol>
                        </nav>
                    </div>
                </nav>
            </div>
        </div>
        <div class="row mb-3 mt-2">
            <div class="col-12 col-sm-12 col-md-10 col-lg-9 col-xl-9">
                <div class="card card-calendar">
                    <div class="card-body">
                        <FullCalendar
                            :locale="locale"
                            :contentHeight="600"
                            ref="fullCalendar"
                            defaultView="dayGridMonth"
                            :header="{
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
                            }"
                            eventColor="#FFFFFF"
                            :plugins="calendarPlugins"
                            :weekends="calendarWeekends"
                            :events="calendarEvents"
                            :eventLimit="4"
                            @dateClick="handleDateClick"
                            @eventClick="eventClick"

                        />

                        <Modal :Showbutton="false" :size="size" :Nombre="NameList">
                            <template slot="Form">
                                <Form></Form>
                            </template>
                        </Modal>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-2 col-lg-3 col-xl-3">
                <div class="card card-scroll">
                    <div class="card-body" >
                        <h5  class="card-title">{{(FechaActual)}} </h5>
                        <button @click="iradespacho" type="button" title="ver despacho" class="btn-fil-002 mt-4">
                            <i class="fas fa-shipping-fast"></i>
                        </button>
                        <table class="table table-striped table-hover align_middle">
                            <thead>
                                <tr>
                                    <th>SERVICIOS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in ServiciosDia" :key="index">
                                    <td @click="Editar(item.id)" :style=" 'border-left: 5px solid ' + item.color + ';' " >
                                        <tr>{{'Folio: '+item.Folio}}</tr>
                                        <tr>{{'Cliente: '+item.Cliente}}</tr>
                                        <tr>{{'Hora Inicio: '+item.HoraI.substring(0, 5)}}</tr>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import esLocale from "@fullcalendar/core/locales/es";
import Form from "@/views/modulos/servicios/Form.vue";
import Modal from "@/components/Cmodal.vue";

export default {
    components: {
        FullCalendar, // make the <FullCalendar> tag available
        Form,
        Modal,
    },
    data: function () {
        return {
            calendarPlugins: [
                // plugins must be defined in the JS
                dayGridPlugin,
                timeGridPlugin,
                interactionPlugin, // needed for dateClick
            ],
            calendarWeekends: true,
            calendarEvents: [
                // initial event data
            ],
            locale: esLocale,
            EsModal: true, //indica si es modal o no
            size: "modal-xl",
            NameList: "Servicios",
            ServiciosDia:[],
            FechaActual:'',
            FechaActual2:''
        };
    },
    methods: {
       async get_listcalendar() {
        await    this.$http.get("servicio/calendar").then((res) => {
                this.calendarEvents = [];

                const actividades = res.data.data.calendar;

                actividades.forEach((item, index) => {
                    this.calendarEvents.push({
                        start: `${item.start}`,
                        // title: 'Tecnico:'+item.Nombre +' \n'+' Cliente: '+ item.title+' \n'+' Inicio: '+ item.HoraI.substring(0,5) ,
                        // title: 'Folio: '+item.Folio +' \n'+' Cliente: '+ item.title+' \n' ,
                        title: "Folio: " + item.Folio + " \n",
                        allDay: false,
                        id: item.id,
                        //color: '#ff00ac',
                        backgroundColor: item.color,
                        color: item.color,
                        //eventTextColor: item.ColorLetra,
                        textColor: item.ColorLetra,
                        //borderColor:item.ColorLetra,
                        Servicio: item.Folio + "\n" + " Cliente: " + item.title,
                        Hora: item.HoraI.substring(0, 5),
                        Fecha: this.formatoFecha(item.FechaInicio),
                    });
                });
            });
        },
        async get_eventsday(FechaBusqueda) {
            await this.$http.get("servicio/calendarday",
                {
                params: {
                FechaBusqueda: FechaBusqueda,
                },
            })
            .then((res) => {
                this.ServiciosDia = res.data.data.calendar;
                this.FechaActual =  this.formatoFecha(res.data.data.Fecha);
                this.FechaActual2 =  res.data.data.Fecha;


            });
        },
        formatoFecha(texto) {
            let ArrTexto = texto.split(" ");
            let newformat = "";

            if (ArrTexto[0] !== "Hace") {
                newformat = ArrTexto[0].replace(
                /^(\d{4})-(\d{2})-(\d{2})$/g,
                "$3/$2/$1"
                );
            } else {
                newformat = texto;
            }

            return newformat;
        },
        toggleWeekends() {
            this.calendarWeekends = !this.calendarWeekends; // update a property
        },
        gotoPast() {
            let calendarApi = this.$refs.fullCalendar.getApi(); // from the ref="..."
            calendarApi.gotoDate("2000-01-01"); // call a method on the Calendar object
        },
        handleDateClick(arg) {
        /* if (
            confirm(
            //"Desea ir al calendario de la fecha seleccionada " +
            "Desea ver los servicios del día " +
                arg.dateStr +
                " ?"
            )
        ) {
            let data = { Fecha: arg.dateStr };
            this.get_eventsday(arg.dateStr);
        }*/

            let data = { Fecha: arg.dateStr };
            this.get_eventsday(arg.dateStr);
        },
        eventClick: function (calEvent, jsEvent, view) {
            this.Editar(calEvent.event.id);
            //this.get_eventsday(calEvent.FechaInicio);
            //list_serviciofecha(calEvent.FechaInicio);

            // change the border color just for fun
            //$(this).css('border-color', callEvent.color);
        },
        Editar(Id) {
            this.bus.$emit("Nuevo", null, Id);
            $("#ModalForm").modal("show");


        },
        iradespacho()
        {
            let data = { Fecha: this.FechaActual2 };
            this.$router.push({ name: "despacho", params: { objeto: data } });
        },

        Timer(){
            this.IntervalTime = setInterval(
				function() {
					this.get_listcalendar("");
				}.bind(this),10000
			);
        }
    },
    created() {
        this.get_eventsday();

    },

    mounted(){
        this.get_listcalendar();
        // this.Timer();
    },


    destroy() {
        //this.Timer();

    }
};
</script>

<style lang='scss'>
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
