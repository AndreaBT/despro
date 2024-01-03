<template>
    <div>
        <!-- <input type="hidden" :value="validate_cotizacion"> -->
        <input type="hidden" :value="ValorKM">
        <input type="hidden" :value="GastoOperaciones">
         <input type="hidden" :value="UtildaAprox">
        <div class="row mt-4">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <h4>Datos de Sitio</h4>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <label>Distancia KM</label>
                <vue-numeric placeholder="0 Km"  :minus="false" class="form-control w-03"  currency="" separator="," :precision="1" v-model="cotizacion_servicio.distancia"></vue-numeric>
                <!-- <Cmoneda   
                :activo="false" 
                :clase="'form-control w-03'"  
                :readonly="false"     
                :minus="true"        
                :currency="''" 
                separator="," 
                :precision="1" 
                v-model="cotizacion_servicio.distancia">
                </Cmoneda> -->
                
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.distancia" :Mensaje="perrorvalidacion.distancia[0]"></Cvalidation></label>
            </div>

            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <label>Velocidad Prom. KM/H</label>
                <vue-numeric placeholder="0 Km/h"  :minus="false" class="form-control w-03"  currency="" separator="," :precision="0" v-model="cotizacion_servicio.velocidad"></vue-numeric>
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.velocidad" :Mensaje="perrorvalidacion.velocidad[0]"></Cvalidation></label>
            </div>

            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <label>Costo KM</label>
                <vue-numeric placeholder="$ 0.00"  disabled :minus="false" class="form-control w-03"  currency="$" separator="," :precision="0" v-model="cotizacion_servicio.costoKm"></vue-numeric>
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.costoKm" :Mensaje="perrorvalidacion.costoKm[0]"></Cvalidation></label>
            </div>

            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <label>Garantía %</label>
                <vue-numeric placeholder="0 %"  :minus="false" class="form-control w-03"  currency="" separator="," :precision="0" v-model="cotizacion_servicio.Garantia"></vue-numeric>
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.Garantia" :Mensaje="perrorvalidacion.Garantia[0]"></Cvalidation></label>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <h4>Datos Adicionales</h4>
                <hr>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <label>Gross Profit %</label>
                <vue-numeric placeholder="0 %"  :minus="false" @input="ValidarGross" class="form-control w-03"  currency="" separator="," :precision="0" v-model="cotizacion_servicio.GrossProfit"></vue-numeric>
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.GrossProfit" :Mensaje="perrorvalidacion.GrossProfit[0]"></Cvalidation></label>
            </div>

            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <label>Factor Hora Extra %</label>
                <vue-numeric placeholder="0 %"  :minus="false" @blur="ValidarGross" class="form-control w-03"  currency="" separator="," :precision="0" v-model="cotizacion_servicio.factorHoraExtra"></vue-numeric>
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.factorHoraExtra" :Mensaje="perrorvalidacion.factorHoraExtra[0]"></Cvalidation></label>
            </div>

            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <label>Utilidad Aprox. %</label>
                <vue-numeric placeholder="0 %" disabled :minus="false" @input="ValidarGross" class="form-control w-03"  currency="" separator="," :precision="0" v-model="cotizacion_servicio.utilidad"></vue-numeric>
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.utilidad" :Mensaje="perrorvalidacion.utilidad[0]"></Cvalidation></label>
            </div>
            
            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <label>Gasto Operaciones %</label>
                <vue-numeric placeholder="0 %" disabled :minus="false" @input="ValidarGross" class="form-control w-03"  currency="" separator="," :precision="0" v-model="cotizacion_servicio.gastoOperaciones"></vue-numeric>
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.gastoOperaciones" :Mensaje="perrorvalidacion.gastoOperaciones[0]"></Cvalidation></label>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <label>Observaciones</label>
                <textarea placeholder="Observaciones"  class="form-control" v-model="cotizacion_servicio.Observaciones" cols="10" rows="3"></textarea>
            </div>
        </div>

    </div>
</template>

<script>

export default {
    name:'dato',
    props:["pcotizacion_servicio","perrorvalidacion"],
    components:{},
    data() {
        return {
            urlApi:"costoskm/get",
            Listacostoskm:[],
            cotizacion_servicio:{},
            ValorKMData:0,
        }
    },
    methods: {
        async Lista()
        {
            await this.$http.get(
                this.urlApi,
                {
                    params:{Nombre:'',Entrada:50,pag:0, RegEstatus:'A'}
                }
            ).then( (res) => {
                this.Listacostoskm =res.data.data.row;
            });
        } ,
        ValidarGross()
        {
            if (this.cotizacion_servicio.GrossProfit !='' && this.cotizacion_servicio.GrossProfit != null)
            {  
                if (parseFloat (this.cotizacion_servicio.GrossProfit)>100)
                {
                this.cotizacion_servicio.GrossProfit =100;  
                }
            }

            this.bus.$emit('OperacionFactor');
        }
    },
    created() {
        this.Lista();
        this.cotizacion_servicio=this.pcotizacion_servicio;
    },
    computed: {
        // validate_cotizacion(){
        //     this.cotizacion_servicio=this.pcotizacion_servicio;
        //     return this.cotizacion_servicio;
        // },
        ValorKM(){       
            var Distancia= this.cotizacion_servicio.distancia;
            var valObj = this.Listacostoskm.filter(function(elem){
                if(parseFloat(elem.KMinicial)<=parseFloat(Distancia) && parseFloat(elem.KMfinal)>=parseFloat(Distancia)){
                        return elem;
                }
            });

            var Costo=0;
            if(valObj.length>0){
                Costo=valObj[0].CostoKM;
            }
            this.cotizacion_servicio.costoKm=parseFloat(Costo);
            return  this.cotizacion_servicio.costoKm;
            
        },
        GastoOperaciones(){
            var Valor=0;
            if(this.cotizacion_servicio.GrossProfit<=100 && this.cotizacion_servicio.GrossProfit!=""){
                Valor= 100- parseFloat(this.cotizacion_servicio.GrossProfit);
                this.cotizacion_servicio.gastoOperaciones=parseFloat(Valor.toFixed(0));
                return this.cotizacion_servicio.gastoOperaciones.toFixed(0);
            }  
        },
        UtildaAprox(){
            var Valor=0;
            if(this.cotizacion_servicio.GrossProfit>0 && this.cotizacion_servicio.GrossProfit!=""){
                Valor=  parseFloat(this.cotizacion_servicio.GrossProfit)-25;
                this.cotizacion_servicio.utilidad=parseFloat(Valor.toFixed(0));
                return this.cotizacion_servicio.utilidad.toFixed(0);
                // console.log();
            }
        }
    },
}
</script>