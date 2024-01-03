<template>
<div>

    <CHead :oHead="oHead">
        <template slot="component">
            <button data-toggle="modal" data-target="#ModalForm" data-backdrop="static" data-keyboard="false" type="button" class="btn btn-03 mb-2" v-if="ShowBtnSolicitud">Solicitar servicio</button>
        </template>
    </CHead>

    <div class="row mt-2" >
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <form class="form-inline">
                                <div class="form-group mr-2">
                                    <input  v-model="Filtro.Nombre"  type="text" class="form-control lup"  placeholder="Equipo ...">
                                </div>
                                <div class="form-group mr-2">
                                    <label>Filas &nbsp;</label>
                                    <select @change="Lista" v-model="Filtro.Entrada" class="form-control">
                                        <option :value="50">50</option>
                                        <option :value="100">100</option>
                                        <option :value="150">150</option>
                                    </select>
                                </div>
                                <div class="form-group  mr-2">
                                    <button  :disabled="loading" @click="Lista()" type="button" class="btn btn-primary mr-1" ><i v-show="loading" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>Filtrar</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-12 col-lg-12">
                            <hr>
                        </div>
                    </div>

                    <div class="row " v-if="!loading">

                        <div v-for="(lista,key,index) in ListaEquipos" :key="index"  class="col-md-3 col-lg-2">
                           <div  @click="go_to_History(lista)"  :id="'button'+lista.IdEquipo" :class="lista.Status =='Fuera de Servicio' ?'circular_shadow borde-rojo2' :lista.Status=='En Observacion' ?'circular_shadow borde-amarillo':lista.Status=='Operando'?'circular_shadow borde-verde ': 'circular_shadow borde-gris'">
                                <svg-injector  v-show="loading == false && lista.ImgSvg != ''"  :class-name="lista.Status =='Fuera de Servicio' ?'svg-inject iconic-signal-weak' :lista.Status=='En Observacion' ?'svg-inject iconic-signal-medium':lista.Status=='Operando'?'svg-inject iconic-signal-strong ':'svg-inject iconic-signal-none'" :src="lista.ImgSvg"></svg-injector>
                                <b-tooltip custom-class="tooltip-des"  :target="'button'+lista.IdEquipo" placement="right">
                                    <span class="tiptext">
                                        <b>Nombre:</b>{{lista.Nequipo}}<br>
                                        <b>Marca:</b> {{lista.Marca}}<br>
                                        <b>Modelo:</b> {{lista.Modelo}}<br>
                                        <b>Unidad:</b> {{lista.Unidad}}<br>
                                        <b>Ubicación:</b>  {{lista.Ubicacion}}<br>
                                    </span>
                                </b-tooltip>

                            </div>
                            <h3 class="text-center equipo">{{lista.Nequipo}}</h3>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                           <Pagina :Filtro="Filtro" @Pagina="Lista" :Entrada="Filtro.Entrada" ></Pagina>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <Modal :poBtnSave="oBtnSave"  :size="size" :Nombre="NameList" >
        <template slot="Form" >
            <Form :poBtnSave="oBtnSave" :osucursal="oClienteSucursal">
            </Form>
        </template>
    </Modal>
</div>
</template>
<script>
import $$ from "jquery"
import Modal from '@/components/Cmodal.vue';
import Form from '@/views/modulos/monitoreo/solicitudes/Form.vue'
import SvgFiller from '@/vue-svg-filler'
import Pagina from '@/components/Cpagina.vue'
export default {
    name :'list',
    props:['obj','objCliente'],
    components :{Modal,Form,SvgFiller,Pagina},
    data() {
        return {
            Disablebtn:false,
            loading:false,
            FormName:'TipoUnidadForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"modal-lg",
            NameList:"Solicitud de Servicio",
            urlApi:"equipos/get",
            ListaEquipos:[],
            ListaHeader:[],
            TotalPagina:2,
            Pag:0,
            oClienteSucursal:{},
            oCliente:{},
            BndCopiar:true,
            RutaEquipo:'',
            oHead:{
                isreturn:true,
                Title:'Sucursales',
                Url:'monitoreo_cli',
                isEmit:true,
            },
            ShowBtnSolicitud:false,
            Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                 TotalItem:0,
                Pagina:1,
                Entrada:50
            },
            oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
            },
        }
    },
    methods: {
       async Lista()
        {
            this.loading = true;
            this.ListaEquipos = [];

            await this.$http.get(
                this.urlApi,
                {
                    params:{ RegEstatus:'A',IdClienteS:this.oClienteSucursal.IdClienteS,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina,Nombre:this.Filtro.Nombre}
                }
            ).then( (res) => {
                this.ListaEquipos = res.data.data.equipos;
                this.RutaEquipo = res.data.RutaEquipo;
                this.Filtro.Entrada=res.data.data.pagination.PageSize;
                this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
                this.loading = false;
            });

        },
        Regresar(){
            this.$router.push({name:'mon_sucursal', params:{ocliente:this.oCliente}})
        },
        go_to_History(item){
            item.RutaEquipo=this.RutaEquipo;
            this.$router.push({name:'mon_histequipo', params:{ocliente:this.oCliente,oClienteSucursal:this.oClienteSucursal,oEquipo:item}})
        }
    },
    created()
    {
        if (this.obj!=undefined)
        {
            sessionStorage.setItem('IdSaved',JSON.stringify(this.obj));
            sessionStorage.setItem('ssobjcliente',JSON.stringify(this.objCliente));
        }
        this.oClienteSucursal=JSON.parse(sessionStorage.getItem('IdSaved'));
        this.oCliente=JSON.parse( sessionStorage.getItem('ssobjcliente'));
        this.oHead.Title=this.oClienteSucursal.Nombre + ' | Equipos';

        var osucursalSession=JSON.parse( sessionStorage.getItem('clientelog'));
        if(osucursalSession!=null){//Datos desde login
            this.ShowBtnSolicitud=true;
        }

        this.bus.$off('Regresar');
        this.bus.$on('Regresar',()=>
        {
            this.Regresar();
        });
    },
    mounted() {
        this.Lista();
    },
    destroyed() {
        sessionStorage.removeItem('IdSaved');
        sessionStorage.removeItem('ssobjcliente');
    },
}

//$$('.svg-inject').svgInject();
</script>
