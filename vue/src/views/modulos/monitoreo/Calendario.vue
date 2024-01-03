<template>
<div>
    <b-overlay :show="this.isOverlay" spinner-variant="primary" >
        <!-- HEADER -->
        <section class="container-fluid mt-2">
            <div class="row mt-2">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                    <nav class="navbar navbar-breadcrumb navbar-expand-md bg-breadcrumb breadcrumb-borde">
                        <div class="mr-auto">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb clearfix pt-3">
                                    <li class="breadcrumb-item active">Calendario</li>
                                </ol>
                            </nav>
                        </div>
                        <!-- FILTRO -->
                        <div class="form-group mr-1" style="max-width: 15rem;">
                            <treeselect 
                                style="margin-bottom:0.3rem !important"
                                :max-height="200"
                                @input="get_listcalendar()"
                                :options="ListaClientes"
                                placeholder="Busque una sucursal..."
                                v-model="IdClienteS"
                            />
                        </div>
                        <!--FIN FILTRO -->
                    </nav>
                </div>
            </div>

            <div class="row mb-3 mt-2">
                <div  class="col-12 col-sm-12 col-md-10 col-lg-9 col-xl-9">
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
                                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                                }"
                                eventColor="#FFFFFF"
                                :plugins="calendarPlugins"
                                :weekends="calendarWeekends"
                                :events="calendarEvents"
                                :eventLimit="4"
                                @dateClick="handleDateClick"
                                @eventClick="eventClick"
                            />
                            <Modal :Showbutton="false"  :size="size" :Nombre="NameList" >
                                <template slot="Form">
                                <Form ></Form>
                                </template>
                            </Modal>  
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-2 col-lg-3 col-xl-3">
                    <div class="card card-scroll">
                        <div class="card-body" >
                            <h5  class="card-title">{{(FechaActual)}} </h5>
                            <!-- <button  type="button" class="btn-fil-002 mt-4">
                                <i class="fas fa-shipping-fast"></i>
                            </button> -->
                            <table class="table table-striped table-hover align_middle">
                                <thead>
                                    <tr>
                                        <th>SERVICIOS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in ServiciosDia" :key="index">
                                        <td @click="Editar(item.id)" :style=" 'border-left: 5px solid ' + item.color + '; '  " >
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
       
     </b-overlay>
</div>
    
</template>

<script>
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import esLocale from '@fullcalendar/core/locales/es'
import Form from '@/views/modulos/monitoreo/FormServ.vue'
import Modal from '@/components/Cmodal.vue';

export default {
    props:['ocliente'],
    components: {
        FullCalendar, // make the <FullCalendar> tag available
        Form,
        Modal,
    },
    data: function() {
        return {
            oClienteP:{},
            calendarPlugins: [ // plugins must be defined in the JS
                dayGridPlugin,
                timeGridPlugin,
                interactionPlugin // needed for dateClick
            ],
            calendarWeekends: true,
            calendarEvents: [ // initial event data    
            ],
            locale: esLocale,
            EsModal:true,//indica si es modal o no
            size :"modal-xl",
            NameList:"Servicios",
            sucursalesId: null,
            ListaClientes: [],
            urlApi:"clientesucursal/get",
            IdClienteS:null,
            isOverlay: true,
            ServiciosDia:[],
            FechaActual:'',
            FechaActual2:''
           
        }
    },
    methods: {
        get_listcalendar()
        {
            this.isOverlay = true;
            this.$http.get(
                'servicio/calendar',
                {
                    params:{IdClienteS:this.IdClienteS}
                }
            ).then( res => {
                 this.isOverlay = false;
                this.calendarEvents = [];
                const actividades = res.data.data.calendar;
                
                actividades.forEach( (item, index) =>  {
                    this.calendarEvents.push({
                        start:`${item.start}`,
                        title: "Folio: " + item.Folio + " \n",
                        // title: 'Tecnico:'+item.Nombre +' \n'+' Cliente: '+ item.title+' \n'+' Inicio: '+ item.HoraI.substring(0,5) ,
                        allDay: false,
                        id: item.id,
                        color  : item.color,
                        textColor: item.ColorLetra,
                        //borderColor:item.ColorLetra,
                        Servicio: item.Folio + "\n" + " Cliente: " + item.title,
                        Hora: item.HoraI.substring(0, 5),
                        Fecha: this.formatoFecha(item.FechaInicio),
                    });
                });
            });
        },
        toggleWeekends() {
            this.calendarWeekends = !this.calendarWeekends // update a property
        },
        gotoPast() {
            let calendarApi = this.$refs.fullCalendar.getApi() // from the ref="..."
            calendarApi.gotoDate('2000-01-01') // call a method on the Calendar object
        },
        handleDateClick(arg) {
        },
        eventClick: function(calEvent, jsEvent, view) {
            this.Editar(calEvent.event.id);
            //list_serviciofecha(calEvent.FechaInicio);
            // change the border color just for fun
            //$(this).css('border-color', callEvent.color);
        },
        Editar(Id)
        {
            this.bus.$emit('Nuevo',null,Id);
            $("#ModalForm").modal('show');
        },

        async Lista()
        {
            await this.$http.get(
                this.urlApi,
                {
                    params:{IdSucursa:this.oClienteP.IdSucursal,IdCliente:this.oClienteP.IdCliente, RegEstatus:'A'}
                }
            ).then( (res) => {
                // this.ListaClientes =res.data.data.clientesucursal;
                this.ListaClientes = res.data.data.clientesucursal.map(function(obj) {
						return { id: obj.IdClienteS, label: obj.Nombre };
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

       
    },
    created() {
        this.Lista();
         this.get_eventsday();
        if (this.ocliente!=undefined)
        {
            sessionStorage.setItem('IdSaved',JSON.stringify(this.ocliente));
        }
        this.oClienteP=JSON.parse( sessionStorage.getItem('IdSaved'));
        
        var osucursalSession=JSON.parse( sessionStorage.getItem('clientelog'));
    
        if(osucursalSession==null){//Datos desde el admin
        }else{//datos desde login admin template
            //#region desde el login
            this.oClienteP=JSON.parse( sessionStorage.getItem('clientelog'));
        }

        this.get_listcalendar();
    },
    mounted() {
        this.Lista();
    },
}
</script>

<style lang='scss'>
// you must include each plugins' css
// paths prefixed with ~ signify node_modules
@import '~@fullcalendar/core/main.css';
@import '~@fullcalendar/daygrid/main.css';
@import '~@fullcalendar/timegrid/main.css';
// .demo-app {
//   font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
//   font-size: 14px;
// }
// .demo-app-top {
//   margin: 0 0 3em;
// }
// .demo-app-calendar {
//   margin:  auto;
//   max-width: auto;
// }
// .fc-content .fc-time{
//    display: none;
// }
.fc-more {
  font-size: 14px;
  font-weight: bold;
}
.fc-more-cell {
  text-align: center !important;
}

</style>