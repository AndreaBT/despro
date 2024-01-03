<template>
    <div>
        <CHead :oHead="Head"></CHead>
        <div class="row justify-content-center mt-4">
            <div class="col-md-10 col-lg-10">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-2 col-lg-2 text-center">
                        <router-link :to="{name:'planventas'}" class="btn btn-01 btn-sub btn-block" >
                        <img src="../../images/finanzas/plan_vtas.png"  alt="Plan de Ventas"><br>
                        <span>Plan de Ventas</span>
                        </router-link>
                    </div>
                    <div class="col-12 col-sm-12 col-md-2 col-lg-2">
                        <router-link :to="{name:'baseactual'}" class="btn btn-01 btn-sub btn-block" >
                        <img src="../../images/finanzas/tiposservicios.png"  alt="Base Actual Ventas"><br>
                        <br>
                        <span>Base Actual Ventas</span>
                        </router-link>
                    </div>
                    <div class="col-12 col-sm-12 col-md-2 col-lg-2 text-center">
                        <router-link :to="{name:'porcentajeoper'}" class="btn btn-01 btn-sub btn-block">
                        <img src="../../images/finanzas/porcentajes.png"  alt="Porcentaje de Operación"><br>
                        <span>Porcentaje de Operación</span>
                        </router-link>
                    </div>
                
                    <div class="col-12 col-sm-12 col-md-2 col-lg-2 text-center">
                        <router-link :to="{name:'costosventas'}" class="btn btn-01 btn-sub btn-block" >
                        <img src="../../images/finanzas/costoventas.png"  alt="Costo Depto. Ventas"><br>
                        <span>Costo Depto. Ventas</span>
                        </router-link>
                    </div>
                    <div class="col-12 col-sm-12 col-md-2 col-lg-2 text-center">
                        <router-link :to="{name:'costooperaciones'}" class="btn btn-01 btn-sub btn-block">
                        <img src="../../images/finanzas/costooperaciones.png"  alt="Costo Depto. Operaciones"><br>
                        <span>Costo Depto. Operaciones</span>
                        </router-link>
                    </div>
                    <div class="col-12 col-sm-12 col-md-2 col-lg-2 text-center">
                        <router-link :to="{name:'costovehope'}" class="btn btn-01 btn-sub btn-block" >
                        <img src="../../images/finanzas/costovehiculoso.png"  alt="Costo Vehículos Operacionales"><br>
                        <span>Costo Vehículos Operacionales</span>
                        </router-link>
                    </div>
                    
                    <div class="col-12 col-sm-12 col-md-2 col-lg-2 text-center">
                        <router-link :to="{name:'costoga'}" class="btn btn-01 btn-sub btn-block" >
                        <img src="../../images/finanzas/costosga.png"  alt="Costos G & A"><br>
                        <span>Costos G & A</span>
                        </router-link>
                    </div>
                    <div class="col-12 col-sm-12 col-md-2 col-lg-2 text-center">
                        <router-link :to="{name:'costofinanciero'}" class="btn btn-01 btn-sub btn-block" >
                            <img src="../../images/finanzas/costosfianncieros.png"  alt="Costos Financieros"><br>
                            <span>Costos Financieros</span>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
import Pagina from '@/components/Cpagina.vue'
export default {
    name:"Clist",
    props:['ShowHead','Filtro','NameReturn'],
    components:{
        Pagina
         
    },

    data() {
        return {
            
            Head:{
                ShowHead:true,
                Title:'Crear Plan de Negocios',
                BtnNewShow:false,
                BtnNewName:'Nuevo',
                isreturn:true,
                isModal:true,                 
                isEmit:true,
                Url:'',
                ObjReturn:'',
                NameReturn:'Regresar',
            },
            
        }
    },
    methods :{
     
    },created() {
        /*
        if(this.PNameButonNuevo!=undefined){
            this.NameButonNuevo=this.PNameButonNuevo;
        }*/
         this.bus.$off('Regresar');
              this.bus.$on('Regresar',()=> 
        {
            this.$router.push({name:'MenusFinanzas'});
            
        });

    },computed: {
        validate(){
            if(this.pShowBtnAdd!=undefined){
                this.ShowBtnAdd=this.pShowBtnAdd;
                this.Head.BtnNewShow=this.pShowBtnAdd;
            }
            if(this.Nombre!=undefined){
                this.Head.Title=this.Nombre;
            }
            if(this.isModal!=undefined){
                this.Head.isModal=this.isModal;
            }
            
            if(this.PNameButonNuevo!=undefined){
                this.Head.BtnNewName=this.PNameButonNuevo;
            }
            if(this.regresar!=undefined){
                this.Head.isreturn=this.regresar;
            }
             if(this.ShowHead!=undefined){
                this.Head.ShowHead=this.ShowHead;
            }

            if(this.NameReturn!=undefined){
                this.Head.NameReturn=this.NameReturn;
            }

            
            
            return this.isModal;
        },
          validateFiltros(){
              if (this.Filtro !=undefined)
              {
                if(this.Filtro.Nombre!=undefined){
                    this.FiltroC.Nombre=this.Filtro.Nombre;
                }
                if(this.Filtro.Placeholder!=undefined){
                    this.FiltroC.Placeholder=this.Filtro.Placeholder;
                }
                 if(this.Filtro.Show!=undefined){
                    this.FiltroC.Show=this.Filtro.Show;
                }
                 if(this.Filtro.Entrada!=undefined){
                    this.FiltroC.Entrada=this.Filtro.Entrada;
                }
              }
            
            return this.FiltroC.Placeholder;
        },
         
    },

}
</script>