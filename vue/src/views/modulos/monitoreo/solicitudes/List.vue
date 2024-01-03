<template>
    <div>
        <Clist @FiltrarC="Lista"
				:Filtro="Filtro"
				:regresar="regresar"
			   	:pShowBtnAdd="btnadd"
				:Nombre="NameList"
				:isModal="EsModal"
				:pConfigLoad="ConfigLoad"
		>
            <template slot="header">
            <tr >
                <th># Folio</th>
                <th>Contacto</th>
                <th>Teléfono</th>
                <th >Correo</th>
                <th >Registro</th>
                <th class="text-center tw-2" >Seguimiento</th>
            </tr>
            </template>
            <template slot="body">
                <tr   v-for="(lista,key,index) in ListaSolicitudes" :key="index" >
                    <td>
                       {{lista.IdTiket }}
                    </td>
                    <td>{{lista.Contacto }}</td>
                    <td>{{lista.Telefono }}</td>
                    <td>{{lista.Correo }}</td>
                    <td>{{formato(lista.FechaReg)}}</td>
                    <td class="text-center tw-2">
                        <button v-b-tooltip.hover title="Abrir Chat" @click="get_lista_chat(lista)" v-b-toggle.sidebar-right type="button" class="btn btn-table relative" >
                            <i v-if="Cliente.IdCliente==0" :class="lista.Estado=='Empresa'? 'fas fa-comment-dots' :'far fa-comment-dots'"></i>
                            <i v-else :class="lista.Estado=='Cliente'? 'fas fa-comment-dots' :'far fa-comment-dots'"></i>
                        </button>
                    </td>
                </tr>
				<CSinRegistros :pContIF="ListaSolicitudes.length" :pColspan="6" ></CSinRegistros>
            </template>
        </Clist>

        <Modal  :size="size" :Nombre="NameForm"  >
            <template slot="Form">
                <Form ></Form>
            </template>
        </Modal>

        <b-sidebar body-class=" modal-dialog-scrollable" class="" id="sidebar-right" width="35em" backdrop right no-header shadow>
            <template v-slot:="{ hide }">
                <div class="modal-content ">

                    <div class="modal-header bg-modal">
                        <h4 class="modal-title">
                            <b-avatar class="mr-1" size="2rem" :variant="AvatarData(oChat.Cliente).Color" :text="AvatarData(oChat.Cliente).Nombre"></b-avatar>
                            {{oChat.Cliente}}  Seguimiento de la Solicitud # {{oChat.IdTiket}}
                        </h4>

                        <button  :disabled="DisableBtnSend"  @click="hide" type="button" class="close close-2" >
                            <i class="fad fa-times-circle"></i>
                        </button>
                    </div>

                    <div class="modal-body ">
                        <span v-if="Cliente.IdCliente>0"><!---Este id lo recibe desde ADMIN------>
                        <p  v-for="(item, index2) in ListaChat" :key="index2" :class="item.Tipo=='2' ? 'globo': 'globo-res' " >
                            {{item.Comentario}}
                            <br>
                            <b class="color-03">{{item.Fecha}} {{item.Hora}}</b>
                        </p>
                        </span>
                        <span v-else><!---No recibe IdCLiente, es desde API------>
                            <p  v-for="(item, index2) in ListaChat" :key="index2" :class="item.Tipo=='1' ? 'globo': 'globo-res' " >
                            {{item.Comentario}}
                            <br>
                            <b class="color-03">{{item.Fecha}} {{item.Hora}}</b>
                        </p>
                        </span>
                        <!--<p class="globo-res">Anim pariatur cliche reprehenderit, enim eiusmod...
                            <br><b class="color-03">08/06/2020 17:05</b>
                        </p>-->
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-inline">
                                    <input  type="text" v-model="oChat.Comentario" class="form-control mr-2 col-9"><button :disabled="DisableBtnSend" @click="send_chat" type="button" class="btn btn-01 send"><span v-if="!DisableBtnSend">Enviar </span><i v-if="DisableBtnSend" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="card-footer">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-inline">
                                    <input v-model="oChat.Comentario" type="text" class="form-control mr-2 col-9">
                                    <button @click="send_chat" :disabled="DisableBtnSend" type="button" class="btn btn-01 send"><span v-if="!DisableBtnSend">Enviar </span><i v-if="DisableBtnSend" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i></button>
                                    <Cvalidation v-if="errorvalidacion.Comentario" :Mensaje="errorvalidacion.Comentario[0]"></Cvalidation>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex bg-dark text-light align-items-center px-3 py-2">
                                <strong class="mr-auto"></strong><button :disabled="DisableBtnSend" @click="hide" type="button" class="btn btn-secondary btn-sm">Cerrar</button>
                            </div>
                        </div>
                    </div>-->
                </div>

            </template>
        </b-sidebar>
    </div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';
import Form from '@/views/modulos/monitoreo/cotizaciones/Form.vue';
import CSinRegistros from "../../../../components/CSinRegistros";
import moment from 'moment';

export default {
    name :'listSolicitudesCliente',
    props:["ocliente"],
    components :{
		Modal,
		Clist,
		Cbtnaccion,
		Form,
		CSinRegistros,
        moment
	},
    data() {
        return {
            FormName:'Form',//Por si no es modal y queremos ir a una vista declarada en el router
            NameForm:'Nuevo',
            EsModal:true,//indica si es modal o no,
            CloseModal:true,//indica si el modal cierra o de lo contrario asignarle un evento al boton
            size :"modal-xl",
            NameList:"Listado de Solicitudes",
            ListaSolicitudes:[],
            TotalPagina:1,
            Pag:0,
            regresar:true,
            btnadd:false,
            Cliente:{},
            ListaChat:[],
            oChat:{
                Comentario:'',
                IdTiket:0,
                IdTrabajador:0,
                Tipo:1,
                IdClienteS:0,
                Cliente:'',
                Contacto:''
            },
            DisableBtnSend:false,
            errorvalidacion:[],
			ConfigLoad:{
				ShowLoader:true,
				ClassLoad:true,
			},
			Filtro: {
				Nombre: "",
				Placeholder: "Folio..",
				TotalItem: 0,
				Pagina: 1,
				Entrada: 10
			},
        }
    },
    methods: {
        async Lista() {
			this.ConfigLoad.ShowLoader = true;
            await this.$http.get(
                'monitoreo/ticket',
                {
                    params:{
						IdCliente:this.Cliente.IdCliente,
						Nombre: this.Filtro.Nombre,
						Entrada: this.Filtro.Entrada,
						pag: this.Filtro.Pagina,
					}
                }
            ).then( (res) => {
                this.ListaSolicitudes 	= res.data.row;
				this.Filtro.Entrada 	= res.data.pagination.PageSize;
				this.Filtro.TotalItem 	= res.data.pagination.TotalItems;

            }).finally(()=>{
				this.ConfigLoad.ShowLoader = false;
			});
        },
        async get_lista_chat(item){
            this.Limpiar();
            this.oChat.IdTiket=item.IdTiket;
            this.oChat.IdClienteS=item.IdClienteS;
            this.oChat.IdCliente=item.IdCliente;
            this.oChat.Cliente=item.Contacto;
            this.oChat.Contacto=item.Contacto;

            await this.$http.get(
                'monitoreo/ticketseguimiento',
                {
                    params:{IdTiket:this.oChat.IdTiket,IdCliente:this.oChat.IdCliente}
                }
            ).then( (res) => {
                this.ListaChat =res.data.row;
                setTimeout(this.scrollToEnd, 100);
            });
        },
        async send_chat(){

            this.oChat.IdClienteSucursal=this.oChat.IdClienteS;
            this.DisableBtnSend=true;

            await this.$http.post(
                'monitoreo/ticketseguimientoadd',
                this.oChat
                ,
            ).then( (res) => {
                this.DisableBtnSend=false;
                this.get_lista_chat(this.oChat);
            }).catch( err => {
                this.DisableBtnSend=false;
                if(err.response.data.type==2){
                    this.$toast.error(err.response.data.message);
                }else{
                    this.errorvalidacion=err.response.data.message.errores;
                }
            });
        },
        Limpiar(){
            this.oChat.Comentario='';
            this.errorvalidacion=[];
        },
        scrollToEnd: function() {
            $('#cuerpo').scrollTop($('#cuerpo')[0].scrollHeight);
        },
        AvatarData(name)
        {
            if (name != undefined)
            {
                var name = name;
                var  nameSplit = name.split(" ");
                var initials;

                if (nameSplit.length>1)
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
            }else{
                var Arreglo ={Nombre:'',Color:''};
                return Arreglo;
            }
        },
        formato(fecha){
            let formato = moment(fecha).format('DD-MM-YYYY hh:mm');
            if(fecha!=null){
                return formato;
            }
        },
    },
    created()
    {
        this.bus.$off('Regresar');
        if (this.ocliente!=undefined)
        {
            sessionStorage.setItem('ocliente',JSON.stringify(this.ocliente));
        }

        this.Cliente=JSON.parse( sessionStorage.getItem('ocliente'));
        var osucursalSession=JSON.parse( sessionStorage.getItem('clientelog'));

        if(osucursalSession==null){//Datos desde el admin
            this.NameList=this.Cliente.Nombre+' | '+this.NameList;
        }else{//datos desde login admin template
            //#region desde el login
            //this.Cliente=JSON.parse( sessionStorage.getItem('clientelog'));
            this.Cliente={IdCliente:0};
            this.regresar=false;
            this.btnadd=false;
            this.ShowAcciones=false;
            this.oChat.Tipo=2;
        }
    },
    mounted() {
        this.Lista();

        this.bus.$on('Regresar',()=>
        {
            this.$router.push({name:'monitoreo_cli',params:{}});
        });
    },
    computed: {
    },
    destroyed() {
    },
}
</script>
