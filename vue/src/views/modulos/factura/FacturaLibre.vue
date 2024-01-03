<template>
<div>
    {{Totales}}
    <div class="row mt-2">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <h4 class="titulo-04 color-02">Factura Libre - Datos del Cliente</h4>
                            <hr class="hr">
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-2 col-lg-2">
                            <label>Folio</label>
                            <input v-if="this.Id==0"  type="text" class="form-control" readonly  placeholder="No. de Folio">
                            <input v-else  v-model="factura.FolioFactura" type="text" class="form-control" readonly  placeholder="No. de Folio">
                        </div>
                        <div class="col-md-5 col-lg-3">
                            <label>Cliente</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <input v-if="this.Id==0" type="text" class="form-control" readonly  v-model="cliente.Empresa" placeholder="Nombre del Cliente">
                                    <input v-else  type="text" class="form-control" readonly  v-model="factura.NombreCliente" placeholder="Nombre del Cliente">
                                </div>
                                <div class="col-md-0 mt-1">
                                        <button @click="ListaCliente"  data-toggle="modal" data-target="#ModalForm3"  data-backdrop="static" type="button" class="btn btn-01 search mr-2">Cliente</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 col-lg-3">
                            <label>Sucursal</label>
                            <input  v-if="this.Id==0"  type="text" class="form-control"  v-model="cliente.Nombre"  readonly  placeholder="Nombre de la Sucursal">
                            <input v-else  type="text" class="form-control"  v-model="factura.Sucursal"  readonly  placeholder="Nombre de la Sucursal">
                        </div>
                        <div class="col-md-7 col-lg-4">
                            <label>Dirección</label>
                            <textarea  v-if="this.Id==0" class="form-control" rows="1"  readonly v-model="cliente.Direccion"   placeholder="Dirección"></textarea>
                            <textarea  v-else  class="form-control" rows="1"  readonly v-model="factura.Direccion"   placeholder="Dirección"></textarea>
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-2 col-lg-2">
                            <label>No. Contrato</label>
                            <select  v-model="factura.NoContrato"  name="" id=""  class="form-control form-control-sm mr-2">
                                    <option :value="''">Seleccione Un Numero de Contrato</option>
                                    <option v-for="(item,index) in ListaNumc" :key="index" :value="item.NumeroC">
                                    {{item.NumeroC}}
                                    </option>
                            </select>
                            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.NoContrato" :Mensaje="errorvalidacion.NoContrato[0]"></Cvalidation></label>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <label>Contacto</label>
                            <input  v-if="this.Id==0" type="text" class="form-control" readonly  v-model="cliente.ContactoS"   placeholder="Nombre del Contacto">
                            <input v-else type="text" class="form-control" readonly  v-model="factura.Contacto"   placeholder="Nombre del Contacto">
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <label>E-mail</label>
                            <input  v-if="this.Id==0"  type="text" class="form-control" readonly  v-model="cliente.Correo"   placeholder="ejemplo@email.com">
                            <input v-else  type="text" class="form-control" readonly  v-model="factura.Correo"   placeholder="ejemplo@email.com">
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <label>Teléfono</label>
                            <input v-if="this.Id==0" type="text" class="form-control" readonly v-model="cliente.Telefono"  placeholder="+00 0000 000 000"  >
                            <input v-else type="text" class="form-control" readonly v-model="factura.Telefono"  placeholder="+00 0000 000 000"  >
                        </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6 col-lg-6">
                            <label>Datos de Facturación</label>
                            <textarea v-if="this.Id==0"  v-model="cliente.Dfac" readonly class="form-control"  placeholder="Datos de Facturación"></textarea>
                            <textarea v-else v-model="factura.DatosFact" readonly class="form-control"  placeholder="Datos de Facturación"></textarea>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <label>Comentarios del Contrato</label>
                            <textarea v-if="this.Id==0"  v-model="cliente.Comentario" readonly   class="form-control" placeholder="Comentario..."></textarea>
                            <textarea v-else  v-model="factura.ComentarioContrato" readonly   class="form-control" placeholder="Comentario..."></textarea>
                        </div>
                    </div><!--Fin datos del ciente-->
                    <div>
                

                    <div class="row mt-4">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <h4 class="titulo-04 color-02">Detalles de Factura</h4>
                            <hr class="hr">
                        </div>
                    </div>

                    <div class="form-group form-row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 text-right">
                            <button  @click="AgregarItem" type="button" class="btn btn-01 add mb-2 mt-1">Agregar</button>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <table class="table-01">
                                <thead>
                                    <tr>
                                        <th class="tw-1 text-center">Eliminar</th>
                                        <th>Descripción</th>
                                        <th class="tw-1">Cantidad</th>
                                        <th class="tw-3">Costo uni.</th>
                                        <th class="tw-3">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item,index) in ListaDetalle" :key="index">
                                        <td class="text-center">
                                            <button v-b-tooltip.hover title="Eliminar" @click="Quitar(index,item.IdFactura)" class="btn btn-table-da pl-01 mr-1" type="button">
                                                    <i class="fas fa-times fa-fw-m"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <textarea v-model="item.Descripcion" class="form-control form-control-sm" rows="1" placeholder="Descripción"></textarea>
                                        </td>
                                        <td>
                                            <vue-numeric    :minus="false" :precision="2" class="form-control form-control-sm "  currency="" separator=","  v-model="item.Cantidad"></vue-numeric>
                                        </td>
                                        <td>
                                            <vue-numeric    :minus="false" :precision="2" class="form-control form-control-sm "  currency="$" separator=","  v-model="item.CostoUni"></vue-numeric>
                                        </td>
                                        <td>
                                            <vue-numeric  disabled  :minus="false" :precision="2" class="form-control form-control-sm "  currency="$" separator=","  v-model="item.Total"></vue-numeric>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><b>Total Final</b></td>
                                        <td>
                                            <vue-numeric  disabled  :minus="false" :precision="2" class="form-control form-control-sm "  currency="$" separator=","  v-model="factura.Total"></vue-numeric>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
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
                                <router-link v-if="this.Factura!=1"  :to="{name:'ctacuentasporcobrar'}" class="btn btn-04 ban mr-2">Cancelar</router-link >
                                <router-link v-else  :to="{name:'listaserfac',params:{Tipo:1}}" class="btn btn-04 ban mr-2">Cancelar</router-link >
                                
                                <button  @click="Guardar"  type="button"  class="btn btn-01 save">
                                    <i class="fa-pulse fa-1x fa-fw"></i><i class="fa fa-plus-circle"></i> {{txtSave}}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <Ccliente :TipoModal='1'></Ccliente>
</div>
</template>
<script>
import Cvalidation from '@/components/Cvalidation.vue'
import Ccliente from '@/components/Ccliente.vue'

export default {
   name:'FacturaLibre',
    props:['Id','Factura'],
   components:{
      Cvalidation,
      Ccliente
   },
   data() {
       return {
           FormName:'FacturaLibre',
            cliente:{
                Nombre:'',
                IdCliente: '',
                IdClienteS: '',
            },
             Contaro:{
                NumeroC:'',
                
            },
            ListaNumc:[],
            ListaDetalle:[],
            IdContrato:'',
            NoContrato:'',
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
                Detalle:[],
                Correo:''
            },
            
            txtSave:'Guardar',
            errorvalidacion:[],
            Id2:'',
            fcatura:[]
            
            
       }
   },methods:{
       get_revovy(){    
           if (this.Id==0 || this.Id==null) {
               
           } else{
               this.$http.get(
                'factura/facturaLibrerecovery/get',
                {
                    params:{IdFactura: this.Id2}
                }
            ).then( (res) => {
                this.servicios =res.data.data.servicio;
                if (res.data.data.factura.IdFactura !=undefined)
                {
                    this.factura =res.data.data.facturaLibre;
                    this.fcatura=res.data.data.facturaLibre;
                    this.IdContrato=this.factura.IdContrato;
                    this.NoContrato=this.ListaNumc.NumeroC;
                    
                    
                     this.ListaDetalle=res.data.data.Detalle;
                    if (this.factura.Facturado=='Cancelado')
                    {
                        this.factura.Facturado='NO'
                    }
                     this.ListaNumContrato();
                }
                else{

                    this.factura.IdFactura=0;
                    // this.factura.IdServicio=this.servicios.IdServicio;
                    this.factura.IdCliente=this.cliente.IdCliente;
                    this.factura.IdClienteS=this.cliente.IdClienteS;
                    this.factura.IdContrato=this.cliente.IdContrato;
                    
                    this.factura.FolioServ=this.servicios.Folio;
                    this.factura.NombreCliente=this.cliente.Empresa;
                    this.factura.Sucursal=this.servicios.Sucursal;
                    this.factura.Direccion=this.cliente.Direccion;
                    this.factura.Contacto=this.cliente.ContactoS;
                    this.Correo=this.cliente.Correo;
                    this.factura.ComentarioContrato=this.cliente.Comentario;
                     this.ListaNumContrato();
                   
                }
            
            });
           }       
            
        },
       
        Guardar()
        {
           this.factura.Detalle=this.ListaDetalle;
           
          
            if(this.Id==0){
                this.factura.IdCliente=this.cliente.IdCliente;
                this.factura.IdClienteS=this.cliente.IdClienteS;
                this.factura.NombreCliente=this.cliente.Empresa;
                 this.factura.Sucursal=this.cliente.Nombre;
                this.factura.Direccion=this.cliente.Direccion;
                this.factura.DatosFact=this.cliente.Dfac;
                this.factura.Contacto=this.cliente.ContactoS;
                this.factura.Telefono=this.cliente.Telefono;
                this.factura.ComentarioContrato=this.cliente.Comentario;
                this.factura.NoContrato = this.factura.NoContrato;
               
            }else{
                this.factura.IdCliente=this.factura.IdCliente;
                this.factura.IdClienteS=this.factura.IdClienteS;
                this.factura.NombreCliente=this.factura.NombreCliente;
                this.factura.Sucursal=this.factura.Sucursal;
                this.factura.Direccion=this.factura.Direccion;
                this.factura.IdContrato=this.factura.IdContrato;
                this.factura.Contacto=this.factura.Contacto;
                this.factura.Telefono=this.factura.Telefono;
                this.factura.ComentarioContrato=this.factura.ComentarioContrato;
                this.factura.DatosFact=this.factura.DatosFact;
            }
           
           
            this.txtSave='Espera...';
            this.$http.post(
                'factura/facturaLibre/post',
                this.factura 
            ).then( (res) => {
                this.txtSave='Guardar';
                this.$toast.success('Información Guardada');
                if (this.Factura!=1) {
                     
                     this.$router.push({name:'ctacuentasporcobrar'});
                }else{
                    this.$router.push({name:'listaserfac'});
                }
                
                
            }).catch( err => {
                this.$toast.error('Agregue todos los campos');
                this.txtSave='Guardar';
                this.errorvalidacion=err.response.data.message.errores;
            
            });
       },

       async ListaCliente()
        {
            this.bus.$emit('ListCcliente');
        },
        SeleccionarCliente(objeto)
        {
            this.cliente=objeto;
            // console.log(this.cliente);
             this.ListaNumContrato();
        },
        async ListaNumContrato()
        {
            await this.$http.get(
                'numcontrato/get',
                {
                    params:{IdClienteS:this.cliente.IdClienteS}
                }
            ).then( (res) => {
              this.ListaNumc =res.data.data.row;

            });
        },
        AgregarItem()
        {
           this.ListaDetalle.push({IdFactura:0,Descripcion:'',Cantidad:0,CostoUni:0,Total:0});
        },
        Quitar (index,IdFactura)
        {
          this.ListaDetalle.splice(index, 1); 
        }
   },
   created() {
        

        this.bus.$off('SeleccionarCliente');
        this.bus.$on('SeleccionarCliente',(oSucursal)=>
        {
           this.SeleccionarCliente(oSucursal);
        });

         if (this.Id!=undefined)
        {
            sessionStorage.setItem('IdSaved',JSON.stringify(this.Id));
            
        }
        this.Id2= sessionStorage.getItem('IdSaved');
        this.get_revovy();
        
        
    },
    mounted() {
    },
    computed: {
       
       Totales()
       {
           let Total=0;
           for ( var i=0;i<this.ListaDetalle.length;i++)
           {
               let importe=0;
               if (this.ListaDetalle[i].Cantidad!='' && this.ListaDetalle[i].CostoUni)
               {
                importe = this.ListaDetalle[i].Cantidad *this.ListaDetalle[i].CostoUni;
               }
                this.ListaDetalle[i].Total=importe;
                Total +=importe;
           }
           this.factura.Total=Total;
           //return Total;
       }
    
   },
}
</script>