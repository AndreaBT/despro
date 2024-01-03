<template>
<div>  
    {{chan_int}}
    <div class="row mt-2">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <nav class="navbar navbar-breadcrumb navbar-expand-md bg-breadcrumb breadcrumb-borde">
                <div class="mr-auto">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb clearfix pt-3">
                            <li class="breadcrumb-item"><a href="#">Despacho / {{FechaSelected}}</a></li>
                        </ol>
                    </nav>
                </div>
            
                <form class="form-inline d-none d-md-none d-lg-block">
                    <div class="form-group mt-n2">
                        <router-link class="btn btn-primary mr-1"  to="/listservicio">Consultas</router-link>

                        <button @click="Open_Modal(2)" type="button" data-toggle="modal" data-target="#ModalForm"  data-backdrop="static" class="btn btn-pink mr-3">
                            Nuevo
                        </button>
                        <label class="mr-1">Técnico</label>
                        <select @change="Buscar" v-model="Busqueda" class="form-control form-control-sm mr-2">
                            <option :value="''">seleccione un Trabajador</option>
                            <option v-for="(item, index) in ListaTrabajadores" :key="index" :value="item.IdTrabajador">{{item.Nombre}}</option>    
                        </select>
                        &ensp;<!--doble-->
                        &emsp;<!--Cuadruple-->

                        <button type="button" data-toggle="modal" data-target="#ModalForm4" :disabled="this.Finalizados.length == 0"  data-backdrop="static" class="btn btn-02 color-01 mr-2">
                            Terminados <span class="color-02">{{this.Finalizados.length}}</span>
                        </button>
                        &emsp;<!--Cuadruple-->

                        <button @click="irHoy" type="button" class="btn btn-02 color-01 mr-2">
                            <i class="fas fa-calendar-day"></i> Hoy
                        </button>

                        <button @click="IrCalendario()" type="button" class="btn btn-02 color-01 mr-2" id="button">
                            <i class="fas fa-calendar"></i> Calendario
                        </button>
                        <!-- <button @click="Open_Modal(1)" data-toggle="modal" data-target="#ModalForm2"  data-backdrop="static" data-keyboard="false" type="button" class="btn btn-02 color-01 mr-2"><i class="fas fa-map-marker-alt"></i>
                        Localización
                        </button> -->

                        <!-- <button @click="OpenOterView()" type="button" class="btn btn-02 color-01 mr-2" id="button"><i class="fas fa-calendar"></i> Otra Vista</button> -->
                
                        <!-- <div class="dropdown mr-2">
                            <button class="dropdown-toggle btn btn-02 color-01 mr-2 " type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Localización <i class="fas fa-map-marker-alt"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-button dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    
                                <a class="dropdown-item" @click="Open_Modal(1)" data-toggle="modal" data-target="#ModalForm2"  data-backdrop="static" data-keyboard="false" type="button">Vista Vista</a>
                                <a class="dropdown-item" @click="OpenOterView()">Nueva Actual</a>
                        
                            </div>
                        </div> -->

                        <div class="dropdown mr-2">
                            <button class="btn btn-02 color-01 btn-07 dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Localización <span class="ml-1"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-button dropdown-menu-right"
                                aria-labelledby="dropdownMenuButton">

                                <a class="dropdown-item" @click="Open_Modal(1)" data-toggle="modal" data-target="#ModalForm2"  data-backdrop="static" data-keyboard="false" type="button">
                                    Localización &nbsp;<i class="fas fa-map-marker-alt"></i>
                                </a>
                                <a class="dropdown-item" @click="OpenOterView()">
                                    Localización Amplia <i class="fas fa-map-marker-alt"></i>
                                </a>

                            </div>
                        </div>
                    </div>
                </form>
          
                <!-- <form class="form-inline d-none d-md-block d-lg-none">
                    <label>Técnico 2</label>
                    <select @change="Buscar" v-model="Busqueda" class="form-control form-control-sm">
                        <option :value="''">seleccione un Trabajador</option>
                        <option v-for="(item, index) in ListaTrabajadores" :key="index" :value="item.IdTrabajador">{{item.Nombre}}</option>
                    </select>
                    <div class="dropdown">
                        <button class="btn-06 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-button dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Terminados <span class="color-02">15</span></a>
                            <a @click="irHoy" class="dropdown-item" ><i class="fas fa-calendar-day"></i> Hoy</a>
                            <a @click="IrCalendario()" class="dropdown-item" href="javascript:open_modal();"><i class="fas fa-calendar"></i> Calendario</a>
                            <a @click="Open_Modal(1)" data-toggle="modal" data-target="#ModalForm2"  data-backdrop="static" data-keyboard="false" class="dropdown-item" href="#"><i class="fas fa-map-marker-alt"></i> Localización</a>
                            <a  @click="Open_Modal(2)" type="button"  data-toggle="modal" data-target="#ModalForm"  data-backdrop="static" class="dropdown-item" href="#Form_02" >Nuevo</a>
                        </div>
                    </div>
                </form> -->
            </nav>
        </div>
    </div>

    <!--INICIO CUERPO TABLA--->
    <div class="row mt-2">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="tab-gantt">
                        <table cellpadding="0" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>
                                        Personal
                                    </th>
                                    <th v-for="(item, index) in ListaHoras" :key="index" :class="item.class">
                                        {{item.hora}}
                                        <section v-if="item.class!=''" >
                                        <span style="display:none">{{Focus=true}}</span>
                                        <i class="fas fa-arrow-alt-down icon-arrow"></i>
                                        <input style="max-width:0;max-height:0;line-height:0;border:0;position:absolute;" id="timefocus" type="text"  ref="timefocus"/>
                                        </section>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item2, index2) in ListaServicios" :key="index2">
                                    <td :class="Busqueda == item2.IdTra ? 'bg-info' : ''">
                                        <b-avatar class="mr-1" size="1.5rem" :variant="AvatarData(item2.Trabajador).Color" :text="AvatarData(item2.Trabajador).Nombre"></b-avatar>
                                        <input style="width:0;height:0;border:0;position:absolute;background-color:transparent"  :id="'txt'+item2.IdTra" :ref="'txt'+item2.IdTra"  :value="item2.IdTra">
                                        {{item2.Trabajador}}
                                        <i :style="item2.message > 0 ? 'color:green' : 'color:blue'" class="far fa-comment-dots icon-gant" @click="GetChat(item2.IdUser,item2.Trabajador,item2.Foto)" v-b-toggle.sidebar-right></i>
                                    </td>

                                    <td  v-for="(item, index) in item2.Servicios" :key="index" >
                                        <div v-b-tooltip.hover.rightbottom :title="getTooTip(item)" ref="" @click="Open_Modal(2,item.IdServicio)" data-toggle="modal" data-target="#ModalForm"  data-backdrop="static" v-if="item.pintar" :class="item.class" :style="item.color"></div>
                                    </td>
                  
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!--Fin Cuerpo de la tabla--->
    <!-- <pre>
        {{ListaServicios}}
    </pre> -->

    <b-sidebar  body-class="modal-dialog-scrollable" id="sidebar-right" width="40em" backdrop right no-header shadow>
        <template v-slot:="{ hide }">
           <div  class="modal-content">
                <div class="modal-header bg-modal">
                    <h5 class="modal-title">  
                        <b-avatar :src="rutatrab+ImagenTab" size="3rem"></b-avatar> 
                        {{NombreTraba}}
                    </h5>
          
                    <!--<h5 class="modal-title"><img src="images/foto.jpg" width="30" class="round"> Haruko Takahashi</h5>-->
      
                    <button id="BtcCerrarsidebar"  @click="hide" type="button" class="close close-2" >
                        <i class="fad fa-times-circle"></i>
                    </button>
                </div>

                <div id="cuerpo"  class="modal-body" >
                    <p v-for="(item, index) in ListaChat" :key="index" :class="Chat.IdContacto !=item.IdUsuario ? 'globo-res': 'globo'">{{item.Mensaje}}<br> <b class="color-03">{{item.Fecha}}</b></p>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-inline">
                                <input v-model="Chat.Mensaje" ref="msj" v-on:keyup.enter="SaveChat" type="text" class="form-control mr-2 col-9"><button :disabled="LoadingChat"  @click="SaveChat" type="button" class="btn btn-01 send">{{txtSave}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- modal-content -->
        </template>
    </b-sidebar>  
    
    <Modal :NameModal="'ModalForm4'" :Showbutton="false"   :size="'modal-bg'" :Nombre="'Servicios Finalizados'" >
        <template slot="Form">
            <finalizados :Finalizados="Finalizados"></finalizados>
        </template>
    </Modal> 

    <Modal :NameModal="'ModalForm2'" :Showbutton="false" :size="'modal-lg'" :Nombre="'Ubicación'" >
        <template slot="Form">
            <Cmapa :Arreglo="markers" :rutatrab="rutatrab" ></Cmapa>
        </template>
    </Modal> 

    <Modal :NameModal="'ModalForm'" :Showbutton="false"  :size="size" :Nombre="NameList" >
        <template  slot="Form">
            <ServicioForm ></ServicioForm>
        </template>
    </Modal> 

</div>
</template>
<script>

import $ from 'jquery'
import Modal from '@/components/Cmodal.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';
import Cmapa from '@/components/Cmapa.vue';
import ServicioForm from '@/views/modulos/servicios/Form.vue';
import finalizados from '@/views/modulos/despacho/finalizados.vue'

export default {
    name :'list',
    props:['ocliente','objeto'],
    components :{
        Modal,
        Cbtnaccion,
        Cmapa,
        ServicioForm,
        finalizados
    },
    data() {
        return {
            FormName:'clientesForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"modal-xl",
            NameList:"Lista Clientes",
            NameForm:"Servicios",
            urlApi:'servicio/despacho',
            ListaHoras:[],
            ListaServicios:[],
            IntervalTime:[],
            HoraActual:'',
            Focus:false,
            Tipo:1,
            markers: [],
            FechaBusqueda:'',
            Finalizados :[],
            Chat:{
                IdContacto:0,
                Mensaje:'',
                Tipo:1
            },
            ListaChat:[],
            LoadingChat:false,
            txtSave:'Enviar',
            errorvalidacion:[],
            Busqueda:'',
            ListaTrabajadores:[],
            rutatrab:'',
            NombreTraba:'',
            ImagenTab:'',
            FechaSelected:' Hoy '
        }
    },
    methods: {
        irHoy()
        {
            if (this.FechaBusqueda!=''){
                this.FechaBusqueda='';
                this.FechaSelected='Hoy';
                this.get_horaslaborales();
            }
        },
        async get_horaslaborales()
        {
            if (this.FechaBusqueda=='')
            {
                var dateObj = new Date();
                var month = dateObj.getUTCMonth() + 1; //months from 1-12
                var day = dateObj.getUTCDate();
                var year = dateObj.getUTCFullYear();

                if(month < 10){
                    month = '0'+month;
                }

                if(day < 10){
                    day = '0'+day;
                }

                this.FechaBusqueda=year+"-"+month+"-"+day;
            }
            else
            {

                var desglose=this.FechaBusqueda.split("-");
                this.FechaSelected= desglose[2]+"-"+desglose[1]+"-"+desglose[0];
            }
      
            await this.$http.get(
                this.urlApi,
                {
                    params:{RegEstatus:'A',FechaBusqueda:this.FechaBusqueda}
                }
            )
            .then( (res) => {
                this.rutatrab=res.data.data.ruta;
                this.Focus=false;
                this.ListaHoras =res.data.data.horaslaborales;
                this.ListaServicios=res.data.data.servicios;
                this.HoraActual=res.data.data.horaactual;
                this.ServiciosFinalizados();

                if(this.IntervalTime!=null){
                    clearInterval(this.IntervalTime);
                    this.set_interval_serv();
                }
            });
        },
        set_interval_serv(){
            this.IntervalTime = setInterval(function(){
                this.get_horaslaborales('');
            }.bind(this), 45000);
        },
        Open_Modal(tipo,Id=0)
        {
            this.Tipo=tipo;

            if (this.Tipo==1)
            {
                this.bus.$emit('OpenModal');
            }
            else{
                this.bus.$emit('Nuevo',null,Id);         
            }
        },
        OpenOterView()
        {
            window.open("http://localhost:8080/#/Geolocalizacion");
            //window.open("https://desprosoft.lugarcreativo.com.mx/#/Geolocalizacion");
        },
        async getUbicaciones()
        {  
            await this.$http.get(
                // 'ubicacionmapa/get',
                'ubicacionmapa2/get',
                {
                    params:{}
                }
            ).then( (res) => {
                this.rutatrab=res.data.data.ruta;
                this.markers=[];
          
                res.data.data.ubicaciones.forEach(element => {
                    this.markers.push({
                        position: {lat: parseFloat( element.lat), lng: parseFloat(element.lng)},

                        //Nuevo
                        datos:{
                            Tipo:element.Tipo,
                            Tecnico:element.Nombre,
                            Cliente:element.Cliente,
                            Servicio:element.Folio,
                            TipoServicio:element.Concepto,
                            FechaI:element.Fecha_I,
                            FechaF:element.Fecha_F,
                            HoraI:element.HoraInicio,
                            HoraF:element.HoraFin,
                            Estatus:element.Estatus,
                            Foto2:element.Foto2,
                        }
                        //original
                        // datos:{
                        //   Tecnico:element.Nombre,
                        //   Cliente:element.Cliente,
                        //   Servicio:element.Folio,
                        //   TipoServicio:element.Concepto,
                        //   FechaI:element.Fecha_I,
                        //   FechaF:element.Fecha_F,
                        //   HoraI:element.HoraInicio,
                        //   HoraF:element.HoraFin,
                        //   Estatus:element.Estatus,
                        //   Foto2:element.Foto2
                        // }
                    });
                });

                sessionStorage.setItem('rutaMapa', this.rutatrab);
            });   
        },
        IrCalendario()
        {
            this.$router.push({name:'calendar',params:{}});
        },
        ServiciosFinalizados()
        {
            this.$http.get(
                'servicio/finalizados',
                {
                    params:{FechaBusqueda:this.FechaBusqueda}
                }
            ).then((res) => {
                this.Finalizados= res.data.data.finalizados;
            });
        },
        GetChat(IdContacto,Nombre,Foto)
        {
            this.Chat.IdContacto=0;
            this.Chat.Mensaje="";
            this.Chat.IdContacto=IdContacto;
            this.NombreTraba=Nombre;
            this.ImagenTab=Foto;

            this.$http.get(
                'despacho/getchat',
                {
                    params:{IdContacto:IdContacto,Tipo:1}
                }
            ).then( (res) => {

                this.ListaChat= res.data.Lista;
                this.$refs.msj.focus();
                setTimeout(this.scrollToEnd, 100);
                
                this.bus.$emit("countNotify");
            });
        },
        SaveChat()
        {
            if (this.Chat.Mensaje.trim()=='')
            {
                this.$refs.msj.focus();
                return false;
            }

            this.errorvalidacion=[];
            this.LoadingChat=true;
            this.txtSave=' Esperar';

            this.$http.post(
                'despacho/postchat',
                this.Chat
            ).then( (res) => {
                this.GetChat(this.Chat.IdContacto,this.NombreTraba,this.ImagenTab);
                this.LoadingChat=false;
                this.$refs.msj.focus();
                this.txtSave=' Enviar';
            }).catch( err => {
                this.$refs.msj.focus();
                this.LoadingChat=false;
                this.txtSave=' Enviar';
                this.errorvalidacion=err.response.data.message.errores;
            });
        },
        scrollToEnd: function() {    	
            /*var container = this.$el.querySelector("#cuerpo");
            var nuevo=1000;
            if (container.scrollHeight==undefined)
            {
            var nuevo =1000;
            }
            container.scrollTop = nuevo;*/
            //("#cuerpo").animate({"scrollTop": $("#cuerpo")[0].scrollHeight}, "slow");
            $('#cuerpo').scrollTop($('#cuerpo')[0].scrollHeight);
        },
        AvatarData(name)
        {
            var name = name;
            var  nameSplit = name.split(" ");
            var initials;

            if(nameSplit.length>1)
            {
                initials = nameSplit[0].charAt(0).toUpperCase() + nameSplit[1].charAt(0).toUpperCase();
            }
            else{
                initials = nameSplit[0].charAt(0).toUpperCase(); 
            }

            var colours = ["secondary", "secondary", "dark", "success", "danger", "warning", "info"];
            const randomMonth = colours[Math.floor(Math.random() * colours.length)];
            var Arreglo ={Nombre:initials,Color:randomMonth};

            return Arreglo;
        },
        Buscar()
        {
            var id='txt'+this.Busqueda;
            document.getElementById(id).focus();
            // this.$refs.id.focus();
        },
        get_listtrabajador()
        {
            this.$http.get(
                'trabajador/get',
                {
                params:{Rol:'USUARIO APP',IdPerfil:4}
                }
            ).then( (res) => {
                this.ListaTrabajadores=res.data.data.trabajador;
            });
        },
        getTooTip(item)
        {
            var html="";

            if (item.Folio!="0")
            {
                html = "Folio : " + item.Folio +"/n" +" Cliente : " +item.Cliente+"/n" +" Sucursal : " +item.Sucursal;
            }
            return html;
        },
    },
    created()
    {
        if (this.objeto != undefined)
        {
            this.FechaBusqueda=this.objeto.Fecha;
        }
        this.get_horaslaborales();
        this.set_interval_serv();
        this.bus.$off('ListDespacho');
        this.bus.$on('ListDespacho',(FechaBusqueda)=> 
        {
            this.get_horaslaborales();  
        });

        this.bus.$off('OpenModal');
        this.bus.$off('ListarUbicacion');
        this.bus.$on('ListarUbicacion',()=> 
        { 
            this.getUbicaciones();
        });
        this.get_listtrabajador();


        this.bus.$off('OpenChatD');
        this.bus.$on('OpenChatD',(ocliente)=> 
        {
            this.GetChat(ocliente.IdContacto,ocliente.Contacto,ocliente.Foto2);

            $("#sidebar-right").toggle("fast");
            $("#sidebar-right").toggleClass("active"); 
        });
    },
    computed: {
        chan_int(){
            if(this.Focus){
              document.getElementById('timefocus').focus();
            }
            return '';
        }
    },
    destroyed() {
      clearInterval(this.IntervalTime);
    },
    mounted(){
        if(this.ocliente != undefined){
            this.GetChat(this.ocliente.IdContacto,this.ocliente.Contacto,this.ocliente.Foto2);

            $("#sidebar-right").toggle("fast");
            $("#sidebar-right").toggleClass("active");
        }
    },
}
</script>
<style>
</style>