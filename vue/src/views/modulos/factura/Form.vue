<template>
    <div>
        <CHead :oHead="Head"></CHead>
        <div class="row mt-2">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <Cliente :servicios="servicios"></Cliente> 
                        <ServRelacion :objTemp="objTemp"></ServRelacion>
                        <Detalle :factura="factura" :objTemp="objTemp"></Detalle>
                          <div  v-if="factura.ComentarioCancel!='' && factura.ComentarioCancel!=null"  class="form-group form-row">
                            <div class="col-md-12 col-lg-12 ">
                                <hr>
                                <div class="col-md-12 col-lg-12 ">
                                    <h4> <label>Comentario Cancelación</label></h4>
                                    <textarea  readonly="" class="form-control" v-model="factura.ComentarioCancel" name="" id="" cols="30" rows="6"> </textarea>     
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group form-row">
                            <div class="col-md-12 col-lg-12 text-right">
                                <hr>
                                <div class="form-inline justify-content-end">
                                    <select v-model="factura.Facturado" class="form-control form-control-sm mr-2">
                                        <option  value="NO">Guardar</option>
                                        <option  value="SI">Guardar y Autorizar</option>
                                    </select>
                                    <router-link  :disabled="loading" :to="{name:'listaserfac',params:{Tipo:1}}" class="btn btn-04 ban mr-2">Cancelar</router-link >
                                    <button :disabled="loading" @click="Guardar" type="button"  class="btn btn-01 save">
                                        
                            <i v-show="loading" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i><i class="fa fa-plus-circle"></i> {{txtSave}}
                                        </button>
                                </div>
                            </div>
                        </div>
                  
                    </div>
                </div>
            </div>
        </div>

        <Modal :NameModal="'ModalForm'" :Showbutton="false"  :size="size" :Nombre="NameList" >
            <template  slot="Form">
            <ListaServ v-if="showList" :objTemp="objTemp" :servicios="servicios"  ></ListaServ>
            </template>
        </Modal>  
    </div>
</template>
<script>
import Cbtnsave from '@/components/Cbtnsave.vue'
import Cvalidation from '@/components/Cvalidation.vue'
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';

import Cliente from '@/views/modulos/factura/Cliente.vue'
import ServRelacion from '@/views/modulos/factura/ServRelacion.vue'
import Detalle from '@/views/modulos/factura/Detalle.vue'
import ListaServ from '@/views/modulos/factura/ListServ.vue'


export default {
    props:['Id'],
    components:{
        Cliente,
        ServRelacion,
        Detalle,
        Modal,
        ListaServ
        
    },
    data() {
        return {
            servicios:{},
            factura:{
            IdFactura:0,
            IdServicio:0,
            IdCliente:0,
            IdClienteS:0,
            IdContrato:0,
            FolioFactura:'',
            FolioServ:'',
            NombreCliente:'',
            Sucursal:'',
            Direccion:'',
            Contacto:'',
            Telefono:'',
            DatosFact:'',
            NoContrato:'',
            Servicio:'',
            ComentarioContrato:'',
            Total:0,
            Facturado:'NO',
            TipoFactura:'1',
            DetalleServ:[],
            Detalle:[]},
    
            size:'modal-xl',
            NameList:'Lista de servicios',
            Opciones:{
                Regresar:false,
                Nuevo:false,
                Acciones:false
            },
            objTemp:{
                ListaServ:[],
                ListaDetalle:[],
            },
            showList:false,
            errorvalidacion:[],
            loading:false,
            txtSave:'Guardar',
            Head:{
                Title:'Nueva Factura',
                BtnNewShow:false,
                BtnNewName:'Nuevo',
                isreturn:true,
                isModal:false,                 
                isEmit:false,
                Url:'listaserfac',
                ObjReturn:'',
             },
             Id2:'',
            
    }
    },
    methods: {
        get_revovy(){            
            this.$http.get(
                'factura/recovery',
                {
                    params:{IdServicio: this.Id2}
                }
            ).then( (res) => {
                this.servicios =res.data.data.servicio;
                if (res.data.data.factura.IdFactura !=undefined)
                {
                    this.factura =res.data.data.factura;
                    
                    this.objTemp.ListaServ.push({IdFactura:0,IdServicio:this.factura.IdServicio,Folio:this.factura.FolioServ,Descripcion:this.factura.ComentarioServ});
                    this.objTemp.ListaServ=this.objTemp.ListaServ.concat(res.data.data.Relacionados); 
                     this.objTemp.ListaDetalle=res.data.data.Detalle;
                     if (this.factura.Facturado=='Cancelado')
                     {
                         this.factura.Facturado='NO'
                     }
                }
                else{

                    this.factura.IdFactura=0;
                    this.factura.IdServicio=this.servicios.IdServicio;
                    this.factura.IdCliente=this.servicios.IdCliente;
                    this.factura.IdClienteS=this.servicios.IdClienteS;
                    this.factura.IdContrato=this.servicios.IdContrato;
                    this.factura.FolioServ=this.servicios.Folio;
                    this.factura.NombreCliente=this.servicios.Client;
                    this.factura.Sucursal=this.servicios.Sucursal;
                    this.factura.Direccion=this.servicios.DireccionCS;
                    this.factura.Contacto=this.servicios.Contacto;
                    this.factura.Telefono=this.servicios.TelCS;
                    this.factura.DatosFact=this.servicios.Dfac;
                    this.factura.NoContrato=this.servicios.NumContrato;
                    this.factura.Servicio=this.servicios.Servicio;
                    this.factura.ComentarioContrato=this.servicios.ComentarioNC;
                    this.objTemp.ListaServ.push({IdFactura:0,IdServicio:this.servicios.IdServicio,Folio:this.servicios.Folio,Descripcion:this.servicios.ComentarioFin});  
                }
                

                
                this.showList=true;
            });
        },
        Guardar()
       {
           this.factura.DetalleServ=this.objTemp.ListaServ;
           this.factura.Detalle=this.objTemp.ListaDetalle;
            this.loading=true;
            this.txtSave='Espera...';
            this.$http.post(
                'factura/post',
                this.factura 
            ).then( (res) => {
                this.loading=false;
                this.txtSave='Guardar';
                this.$toast.success('Información Guardada');
                this.$router.push({name:'listaserfac'});
                
            }).catch( err => {
                this.$toast.error('Agregue todos los campos');
                this.loading=false;
                this.txtSave='Guardar';
                this.errorvalidacion=err.response.data.message.errores;
            
            });
       }
    },
    created() {
         //recibiendo objetos
        if (this.Id!=undefined)
        {
            sessionStorage.setItem('IdSaved',JSON.stringify(this.Id));
        }
        this.Id2= sessionStorage.getItem('IdSaved');
        
        this.get_revovy();
    },
    mounted() {
        

        
    },
}
</script>