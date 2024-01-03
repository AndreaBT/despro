
<template>
    <div>
        <div class="row mt-2">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <nav class="navbar navbar-breadcrumb navbar-expand-md bg-breadcrumb breadcrumb-borde">
                    <div class="mr-auto">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb clearfix pt-3">
                                <li class="breadcrumb-item active">Dashboard CRM</li>
                            </ol>
                        </nav>
                    </div>
                </nav>
            </div>
        </div>
        <div class="form-row justify-content-center mt-2">
            
            <CPlan></CPlan>
            <CVentas></CVentas>
            <CProceso></CProceso>   
        </div>
    </div>
</template>

<script>

import Vue from 'vue';
import VueFusionCharts from 'vue-fusioncharts';
import FusionCharts from 'fusioncharts';
import Column2D from 'fusioncharts/fusioncharts.charts';
import FusionTheme from 'fusioncharts/themes/fusioncharts.theme.fusion';
import Widgets from 'fusioncharts/fusioncharts.widgets'

Vue.use(VueFusionCharts, FusionCharts, Column2D,Widgets, FusionTheme);

import CPlan from '@/views/modulos/dashboard/crm/Plan.vue'
import CVentas from '@/views/modulos/dashboard/crm/Ventas.vue'
import CProceso from '@/views/modulos/dashboard/crm/Proceso.vue'
//import CForecast from '@/views/modulos/dashboard/crm/Forecast.vue'

export default {
    name:'',
    props:[''],
    components: { CPlan,CVentas,CProceso,/*CForecast*/},
    data() {
        return {
            Head:{
                Title:'Dashboard CRM',
                BtnNewShow:false,
                BtnNewName:'Nuevo',
                isreturn:false,
                isModal:false,                 
                isEmit:false,
                Url:'',
                ObjReturn:'',
            },
            isVisible:true,
        }
    },
    provide: {
       
    },
    methods: {

        Filtros(){
            //this.bus.$emit('Ocultar',true);
            this.bus.$emit('crmOcultar1',this.isVisible);
            this.bus.$emit('crmOcultar2',this.isVisible);
            this.bus.$emit('crmOcultar3',this.isVisible);
            this.bus.$emit('crmOcultar4',this.isVisible);


            if(this.isVisible){
                 this.isVisible = false;
            }else{
                 this.isVisible = true;
            }
           

        }
        
    },created() {
        
    },mounted() {
        
    },destroyed() {
        
    },
}

</script>

<style >
svg > g[class^="raphael-group-"] > text{
    display: none;
}
</style>