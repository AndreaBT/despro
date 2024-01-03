<template>
  <div>
      <CHead :oHead="Head"></CHead>
      <div class="row">
        <div class="col-lg-6">
           <CGasto></CGasto>
        </div>
        <div class="col-lg-6">
            <CAvance></CAvance>
        </div>
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

import CGasto from '@/views/modulos/dashboard/pm/Gasto.vue'
import CAvance from '@/views/modulos/dashboard/pm/Avance.vue'
export default {
    name:'',
    props:[''],
    components: { CGasto,CAvance},
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
        }
    },
    provide: {
       
    },
    methods: {
        
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