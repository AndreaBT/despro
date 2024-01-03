<template>
  <div>
    <div class="row mt-2">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <nav class="navbar navbar-breadcrumb navbar-expand-md bg-breadcrumb breadcrumb-borde">
                <div class="mr-auto">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb clearfix pt-3">
                            <li class="breadcrumb-item active">Dashboard Despacho</li>
                        </ol>
                    </nav>
                </div>
            </nav>
        </div>
    </div>

    <div class="form-row justify-content-center mt-2">
      <CHoraPro></CHoraPro>
      <CFacturable></CFacturable>
      <CVehiculo></CVehiculo>
      <CTipoServ></CTipoServ>
    </div>


  </div>
  
</template>

<script>

import Vue from 'vue';
import $$ from 'jquery'
import VueFusionCharts from 'vue-fusioncharts';
import FusionCharts from 'fusioncharts';
import Column2D from 'fusioncharts/fusioncharts.charts';
import FusionTheme from 'fusioncharts/themes/fusioncharts.theme.fusion';
import GammelTheme from 'fusioncharts/themes/fusioncharts.theme.gammel';
import CandyTheme from 'fusioncharts/themes/fusioncharts.theme.candy';
import ZuneTheme from 'fusioncharts/themes/fusioncharts.theme.zune';
import OceanTheme from 'fusioncharts/themes/fusioncharts.theme.ocean';
import CarbonTheme from 'fusioncharts/themes/fusioncharts.theme.carbon';
import Widgets from 'fusioncharts/fusioncharts.widgets'

Vue.use(VueFusionCharts, FusionCharts, Column2D,Widgets, FusionTheme, GammelTheme, CandyTheme, ZuneTheme, OceanTheme, CarbonTheme);

import CHoraPro from '@/views/modulos/dashboard/despacho/HoraProductiva.vue'
import CFacturable from '@/views/modulos/dashboard/despacho/Facturable.vue'
import CVehiculo from '@/views/modulos/dashboard/despacho/Vehiculo.vue'
import CTipoServ from '@/views/modulos/dashboard/despacho/TipoServ.vue'

export default {
    name:'',
    props:[''],
    components: { CHoraPro,CFacturable,CVehiculo,CTipoServ},
    data() {
        return {
            Head:{
                Title:'Dashboard Despacho',
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
       OcultaMuestra(){
            $$(".intro").each(function () {
                var displaying = $(this).css("display");
                if (displaying == "block") {
                    $$(this).fadeOut('slow', function () {
                        $(this).css("display", "none");
                    });
                } else {
                    $$(this).fadeIn('slow', function () {
                        $(this).css("display", "block");
                    });
                }
            });
       } 
    },created() {
       
        
    },mounted() {
        this.OcultaMuestra();
    },destroyed() {
        
    },
}

</script>

<style >
svg > g[class^="raphael-group-"] > text{
    display: none;
}
</style>