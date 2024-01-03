<template>
    <div>
        <CHead :oHead="Head"></CHead>
        
        <div class="row justify-content-start mt-3">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card card-01">

                    <div v-if="NocountV" class="row">
                        <div class="col-md-12 col-lg-12"><h5 class="mb-3 text-center" style="color:red">Se requiere un vendedor para poder crear el plan de venta</h5><hr></div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <form class="form-inline justify-content-center">
                                <label class="mr-1">Vendedor </label>
                                <select @change="get_listdata" v-model="IdVendedor"  class="form-control mr-2">
                                        <option v-for="(item, index) in ListaVendedores" :key="index" :value="item.IdUsuario">{{item.NombreTrabajador}}</option>
                                </select>
                                <label class="mr-1">Año </label>
                               
                                    <select :disabled="loading" @change="get_listdata"  v-model="Anio" class="form-control mr-2">
                                    <option v-for="(item,index) in ListaAnios" :key="index" :value="item">{{item}}</option>
                                </select>
                                 <button :disabled="loading" @click="Guardar" type="button"  class="btn btn-01">    
                                    <i v-show="loading" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i> 
                                    <i class="fa fa-plus-circle"></i>
                                    {{txtSave}}
                                </button>
                            </form>
                            <hr>
                        </div>
                    </div>
                    <!--tablas-->
                    <div class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-venta">
                                <Plan :ListaDetalle="ListaDetalle"></Plan>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!--siguientes tablas-->
        <Porcentaje :ListaDetalle="ListaDetalle"></Porcentaje>
        <PromedioC :ListaDetalle="ListaDetalle"></PromedioC>
           



    </div>
</template>
<script>
import Cbtnsave from '@/components/Cbtnsave.vue'
import Cvalidation from '@/components/Cvalidation.vue'
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';

import Plan from '@/views/modulos/finanzas/planventas/Plan.vue'
import Porcentaje from '@/views/modulos/finanzas/planventas/PorcentajeSubtipos.vue'
import PromedioC from '@/views/modulos/finanzas/planventas/PromedioContrato.vue'



export default {
    props:['Id'],
    components:{
        Plan,Porcentaje,PromedioC
        
    },
    data() {
        return {
            planventas:{},
               ListaTrabajadores:[],
               ListaVendedores:[],
               IdVendedor:0,
               ListaDetalle:[],
               ListaRol:["Vendedor","Gerente de ventas"],
         
            Head:{
                Title:'Plan de ventas',
                BtnNewShow:false,
                BtnNewName:'Nuevo',
                isreturn:true,
                isModal:false,                 
                isEmit:false,
                Url:'SubMenusFinanzas',
                ObjReturn:'',
             },
             Trimestre1:0,
             Anio:'2020',
              loading:false,
                txtSave:'Guardar',
            ListaAnios:[],
            NocountV:false,
    }
    },
    methods: {
            get_anios(){ 
             this.loading=true;          
             this.$http.get(
                'funciones/getanios',
                {
                    params:{}
                }
            ).then( (res) => {
              this.ListaAnios=res.data.ListaAnios;         
              this.Anio=res.data.AnioActual;
          
                this.get_listtrabajador();
                this.get_listVendedor();
            });                    
    },
         get_listtrabajador(){
        this.$http.get(
          'trabajador/ListTrabRolQuery',
          {
            params:{Rol:JSON.stringify(this.ListaRol)}
          }
        ).then( (res) => {
            
           this.ListaTrabajadores=res.data.data.lista;

           if (this.ListaTrabajadores.length>0)
           {
                this.IdVendedor=this.ListaTrabajadores[0].IdTrabajador;
                this.get_listdata();

                this.NocountV= false;
           }else{
               this.NocountV= true;
           }
           
        });
    },


    get_listVendedor(){
        this.$http.get(
          'vendedores/get',
          {
            params:{}
          }
        ).then( (res) => {
            
           this.ListaVendedores=res.data.data.Vendedores;

           if (this.ListaVendedores.length>0)
           {
                this.IdVendedor=this.ListaVendedores[0].IdUsuario;
                this.get_listdata();

                this.NocountV= false;
           }else{
               this.NocountV= true;
           }
           
        });
    },


      get_listdata(){
          
          if (this.IdVendedor !='' && this.IdVendedor >0)
          {
            this.$http.get(
            'Cplanventas/get',
            {
                params:{IdVendedor:this.IdVendedor,Anio:this.Anio}
            }
            ).then( (res) => {
                
                this.ListaDetalle=res.data.data.detalle;
                this.loading =false;

            });
          }
    },
        Guardar()
       {
        if (this.ListaDetalle.length>0)
                { 
              this.loading=true;
                    this.$http.post(
                        'planventas/post',
                        {Detalle:this.ListaDetalle,Anio:this.Anio,IdVendedor:this.IdVendedor}
                    ).then( (res) => {
                           this.$toast.success('Información Guardada');
                         this.get_listdata();
                         this.loading=false;
                        
                    }).catch( err => {
                        this.loading=false;
                           this.$toast.error('Ocurrio un error al agregar los datos');
                    });
                }
       }
    },
    created() {
         //recibiendo objetos
        /*if (this.Id!=undefined)
        {
            sessionStorage.setItem('IdSaved',JSON.stringify(this.Id));
        }
        this.Id2= sessionStorage.getItem('IdSaved');
        */
      this.get_anios();
        this.bus.$off('Regresar');
        this.bus.$on('Regresar',()=> 
        {
            this.$router.push({name:'SubMenusFinanzas'});
            
        });
    },
    mounted() {
        

        
    },
    computed: {
      
    },
}
</script>