<template>
    <div>
        <CHead :oHead="Head"></CHead>

        <div class="row justify-content-start mt-3">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card card-01">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <form class="form-inline justify-content-center">
                                <label class="mr-1">Año</label>
                                <select :disabled="Disabled" @change="get_Lista1()"  v-model="Anio" class="form-control mr-2">
                                    <option v-for="(item,index) in ListaAnios" :key="index" :value="item">{{item}}</option>
                                </select>
                                <button :disabled="Disabled" @click="Guardar" type="button"  class="btn btn-01">    
                                    <i v-show="Disabled" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i> 
                                    <i class="fa fa-plus-circle"></i>
                                    {{txtSave}}
                                </button>
                            </form>
                        </div>
                    </div>
                    <!---Fin de filtros-->
                    <Tabla :Lista="Lista" :Nombre="'Costos Operacionales'" :ObjCVO="ObjCVO"></Tabla>
                </div>
            </div>
        </div>

        <!-- <div class="row mt-2">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body"> 
                        <form class="form-inline d-none d-md-none d-lg-block">
                            <div class="form-group mt-n2">
                                <label>Año </label>
                                <select :disabled="Disabled" @change="get_Lista1()"  v-model="Anio" class="form-control form-control-sm">
                                    <option v-for="(item,index) in ListaAnios" :key="index" :value="item">{{item}}</option>
                                </select>
                            </div>
                                <button :disabled="Disabled" @click="Guardar" type="button"  class="btn btn-01">
                                    <i v-show="Disabled" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i> 
                                    <i class="fa fa-plus-circle"></i>
                                    {{txtSave}}
                                </button>
                        </form>
                        <Tabla :Lista="Lista" :Nombre="'Costos Operacionales'" ></Tabla>
                    </div>
                </div>
            </div>
        </div> -->

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
                Title:'Costo Vehículos Operacionales',
                BtnNewShow:false,
                BtnNewName:'Nuevo',
                isreturn:true,
                isModal:false,                 
                isEmit:false,
                Url:'SubMenusFinanzas',
                ObjReturn:'',
            },
            ObjCVO:{
                NumVehiculos:0,
                kmproductivo:0,
                TotalAnual:0,
                TotalFinal:0,
                TotalCorregido:0,
             },
            IdConfigS:0,
            IdTipoSubservicio:0,
            Anio:2020,
            ListaAnios:[],
            Disabled:false,
            txtSave:'Guardar',
        }
    },
    methods: {
        get_anios(){      
            this.Disabled=true;     
            this.$http.get(
            'funciones/getanios', {
                params:{}
            }
            ).then( (res) => {
                this.ListaAnios=res.data.ListaAnios;         
                this.Anio=res.data.AnioActual;
                this.get_Lista1();
            });                    
        },
        get_Lista1(){
            this.Disabled=true;             
            this.$http.get(
            'costovehope/get', {
                params:{ Anio:this.Anio,Tipo:1}
            }
            ).then( (res) => {
                this.Lista                      = res.data.data.lista;    
                this.Disabled                   = false;
                this.ObjCVO.NumVehiculos        = res.data.data02.NumVehiculos;
                this.ObjCVO.kmproductivo        = res.data.data02.kmproductivo;
                this.ObjCVO.TotalFinal          = res.data.data02.TotalFinal;
                this.ObjCVO.TotalCorregido      = res.data.data02.TotalCorregido;
            });                    
        },
        Guardar() {
            if (this.Lista.length > 0 ) { 
                if (this.Validar() == 0) {
                    this.Disabled = true;
                    this.$http.post("costovehope/post", {
                        Detalle:    this.Lista,
                        Anio:       this.Anio,
                        ObjCVO:     this.ObjCVO
                    }
                ).then( (res) => {
                    this.Disabled=false;
                    this.$toast.success('Información Guardada');
                    this.get_Lista1();                    
                }).catch( err => {
                    this.Disabled=false;
                    this.$toast.error('Ocurrio un error al agregar los datos');
                });
                } else {
                    this.$toast.warning('Número de  Cuenta no pueda ser vacio o 0');
                }
            }
       },
       Validar() {
            var valor=0;
            this.Lista.forEach(element => {
                if (element.NumeroCuenta=='' || element.NumeroCuenta==0 && element.Descripcion!='') {
                    valor=1;
                }
            });
            return valor;
        }
    },
    created() {
        this.get_anios();
        this.bus.$off('Regresar');
        this.bus.$on('Regresar',()=> {
            this.$router.push({name:'SubMenusFinanzas'});
        });
    },
    mounted() {},
    computed: {},
}
</script>