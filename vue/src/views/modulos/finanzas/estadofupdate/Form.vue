<template>
    <div>
        <CHead :oHead="Head"></CHead>

        <div class="row justify-content-start mt-3">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card card-01">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <form class="form-inline justify-content-center">
                                <label class="mr-1">Año </label>
                                <select :disabled="Disabled" @change="get_Lista1()"  v-model="Anio" class="form-control mr-2">
                                        <option v-for="(item,index) in ListaAnios" :key="index" :value="item">{{item}}</option>
                                </select>
                                <label class="mr-1">Mes </label>
                                <select :disabled="Disabled" @change="get_Lista1()"  v-model="Mes" class="form-control mr-2">
                                        <option  :value="1">Enero</option>
                                        <option  :value="2">Febrero</option>
                                        <option  :value="3">Marzo</option>
                                        <option  :value="4">Abril</option>
                                        <option  :value="5">Mayo</option>
                                        <option  :value="6">Junio</option>
                                        <option  :value="7">Julio</option>
                                        <option  :value="8">Agosto</option>
                                        <option  :value="9">Septiembre</option>
                                        <option  :value="10">Octubre</option>
                                        <option  :value="11">Noviembre</option>
                                        <option  :value="12">Diciembre</option>
                                </select>
                                <label class="mr-1">Servicio </label>
                                <select @change="ListaSubtipo();" v-model="IdConfigS"  class="form-control mr-2">
                                        <option v-for="(item, index) in ListaServicios" :key="index" :value="item.IdConfigS">{{item.Nombre}}</option>
                                </select>
                                <label class="mr-1">Subtipo de Servicio </label>
                                <select   v-model="IdTipoSubservicio" @change="get_Lista1()"   class="form-control mr-2">
                                        <option v-for="(item, index) in ListaTipoServicio" :key="index" :value="item.IdTipoSer">{{item.Concepto}}</option>
                                </select>
                                
                                <button :disabled="Disabled" @click="Guardar" type="button"  class="btn btn-01 mr-1">    
                                    <i v-show="Disabled" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i> 
                                    <i class="fa fa-plus-circle"></i>
                                    {{txtSave}}
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-finanzas">
                                <table class="table-fin-01 mb-3">
                                    <thead>
                                        <tr>
                                            <th >Descripción</th>
                                            <th class="text-center">Actual</th>      
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item,index) in Lista" :key="index">          
                                            <td > <b>{{item.Descripcion}}  </b>  </td>
                                            <td> 
                                                <vue-numeric  :minus="true" class="form-control form-finanza form-control-sm text-center" placeholder="$ 0.00"  currency="$" separator="," :precision="2" v-model="item.Monto"></vue-numeric>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
import Cbtnsave from '@/components/Cbtnsave.vue'
import Cvalidation from '@/components/Cvalidation.vue'
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';

import Tabla from '@/components/CtablaFinanciera.vue';

export default {
    props:['Id'],
    components:{
        Tabla,
    },
    data() {
        return {
            planventas:{},
            Lista:[],
            Head:{
                Title:'Actualizar Costos Operativos',
                BtnNewShow:false,
                BtnNewName:'Nuevo',
                isreturn:true,
                isModal:false,                 
                isEmit:false,
                Url:'MenusFinanzas',
                ObjReturn:'',
             },
            IdConfigS:0,
            IdTipoSubservicio:0,
            Anio:2020,
            ListaAnios:[],
            Disabled:false,
            Mes:1,
            ListaTipoServicio:[],
            ListaServicios:[],
            txtSave:'Guardar',
        }
    },
    methods: {
        get_lisServicios(){           
             this.$http.get(
                'baseactual/get',
                {
                    params:{ RegEstatus:'A',Facturable:'S'}
                }
            ).then( (res) => {
                this.ListaServicios=res.data.data.lista;
                this.IdConfigS=this.ListaServicios[0].IdConfigS;
                this.ListaSubtipo();   
            });                    
        },
        async ListaSubtipo()
        {
            if (this.IdConfigS>0)
            {
                await this.$http.get(
                    'tiposervicio/get',
                    {
                        params:{ RegEstatus:'A',IdConfigS:this.IdConfigS,IdTipoServ:this.IdTipoServ}
                    }
                ).then( (res) => {
                    this.ListaTipoServicio =res.data.data.tiposervicio;
                    if (this.ListaTipoServicio.length>0)
                    {
                        this.IdTipoSubservicio=this.ListaTipoServicio[0].IdTipoSer;
                        this.get_Lista1();
                    }
                    else{
                        this.IdTipoSubservicio="";
                    }     
                });
            }  
        },
         get_anios(){ 
             this.Disabled=true;          
             this.$http.get(
                'funciones/getanios',
                {
                    params:{}
                }
            ).then( (res) => {
                this.ListaAnios=res.data.ListaAnios;         
                this.Anio=res.data.AnioActual;
                this.Mes= parseInt( res.data.MesActual);
                this.get_lisServicios();
            });                    
        },
        get_Lista1(){  
            //alert(this.Mes);
            this.Disabled=true;             
            this.$http.get(
                'actualizarCostOp/get',
                {
                    params:{ Mes:this.Mes,Anio:this.Anio,Tipo:1,IdConfigS:this.IdConfigS,IdTipoServ:this.IdTipoSubservicio}
                }
            ).then( (res) => {
                this.Lista=res.data.data.lista;    
                this.Disabled=false;              
            });                    
        },
        Guardar()
        {
            if (this.Lista.length>0 )
            { 
                this.Disabled=true;  
                this.$http.post(
                    'actualizarCostOp/post',
                    {Detalle:this.Lista,Anio:this.Anio,Mes:this.Mes,IdConfigS:this.IdConfigS,IdTipoServ:this.IdTipoSubservicio}
                ).then( (res) => {
                    this.Disabled=false;  
                    this.$toast.success('Información Guardada');
                    this.get_Lista1();
                }).catch( err => {
                    this.Disabled=false;  
                    this.$toast.error('Ocurrio un error al agregar los datos');
                });
            }
        }
    },
    created() {
        this.get_anios();
        this.bus.$off('Regresar');
        this.bus.$on('Regresar',()=> 
        {
            this.$router.push({name:'MenusFinanzas'});
        });
    },
    mounted() {
    },
    computed: {
    },
}
</script>