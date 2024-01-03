<template>
    <div class="">
        {{TotalCostoDesplazamiento}}
        {{TotalCostoBurden}}
        {{totalGarantia}}
        {{cal_CostoTotal}}
        {{TotalValorVenta}}
        {{TotalMODesplazamiento}}
        <div class="card card-03">
            <div class="card-header">
                <h4>Folio: {{cotizacion_servicio.Folio}} | Resumen</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <label>Costo de Desplazamiento de Mano de Obra</label>
                        <vue-numeric disabled placeholder="$ 0.00"  :minus="true" class="form-control form-control-sm "  currency="$" separator="," :precision="2" v-model="cotizacion_servicio.CostoManoObraD"></vue-numeric>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.CostoManoObraD" :Mensaje="perrorvalidacion.CostoManoObraD[0]"></Cvalidation></label>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <label>Costo de Desplazamiento de Vehículos</label>
                        <vue-numeric disabled placeholder="$ 0.00"  :minus="true" class="form-control form-control-sm "  currency="$" separator="," :precision="2" v-model="cotizacion_servicio.TotalCostoKm"></vue-numeric>
                       <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.TotalCostoKm" :Mensaje="perrorvalidacion.TotalCostoKm[0]"></Cvalidation></label>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <label>Mano de Obra</label>
                        <vue-numeric disabled placeholder="$ 0.00"  :minus="true" class="form-control form-control-sm "  currency="$" separator="," :precision="2" v-model="cotizacion_servicio.totalManoDeObra"></vue-numeric>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.totalManoDeObra" :Mensaje="perrorvalidacion.totalManoDeObra[0]"></Cvalidation></label>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <label>Costo Burden</label>
                        <vue-numeric disabled placeholder="$ 0.00"  :minus="true" class="form-control form-control-sm "  currency="$" separator="," :precision="2" v-model="cotizacion_servicio.CostoBurden"></vue-numeric>
                       <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.CostoBurden" :Mensaje="perrorvalidacion.CostoBurden[0]"></Cvalidation></label>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <label>Materiales</label>
                         <vue-numeric disabled placeholder="$ 0.00"  :minus="true" class="form-control form-control-sm "  currency="$" separator="," :precision="2" v-model="cotizacion_servicio.totalMateriales"></vue-numeric>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.totalMateriales" :Mensaje="perrorvalidacion.totalMateriales[0]"></Cvalidation></label>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <label>Miscelaneos</label>
                        <vue-numeric disabled placeholder="$ 0.00"  :minus="true" class="form-control form-control-sm "  currency="$" separator="," :precision="2" v-model="cotizacion_servicio.totalMiscelaneos"></vue-numeric>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.totalMiscelaneos" :Mensaje="perrorvalidacion.totalMiscelaneos[0]"></Cvalidation></label>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <label>Garantía</label>
                        <vue-numeric disabled placeholder="$ 0.00"  :minus="true" class="form-control form-control-sm "  currency="$" separator="," :precision="2" v-model="TotalGarantia"></vue-numeric>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <hr class="total">
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <label class="lab-01">Costo Total</label>
                        
                        <vue-numeric disabled placeholder="$ 0.00"  :minus="true" class="form-control form-control-sm "  currency="$" separator="," :precision="2" v-model="cotizacion_servicio.totalGlobal"></vue-numeric>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.totalGlobal" :Mensaje="perrorvalidacion.totalGlobal[0]"></Cvalidation></label>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <label class="lab-01">Valor Venta</label>
                        <vue-numeric disabled placeholder="$ 0.00"  :minus="true" class="form-control form-control-sm numer border-yellow"  currency="$" separator="," :precision="2" v-model="cotizacion_servicio.ValorVenta"></vue-numeric>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.ValorVenta" :Mensaje="perrorvalidacion.ValorVenta[0]"></Cvalidation></label>
                    </div>
                </div>
            </div>

            <!--<div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <label>Materiales</label>
                         <vue-numeric disabled   :minus="true" class="form-control form-control-sm "  currency="$" separator="," :precision="0" v-model="cotizacion_servicio.totalMateriales"></vue-numeric>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.totalMateriales" :Mensaje="perrorvalidacion.totalMateriales[0]"></Cvalidation></label>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <label>Mano de Obra</label>
                        <vue-numeric disabled   :minus="true" class="form-control form-control-sm "  currency="$" separator="," :precision="0" v-model="cotizacion_servicio.totalManoDeObra"></vue-numeric>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.totalManoDeObra" :Mensaje="perrorvalidacion.totalManoDeObra[0]"></Cvalidation></label>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <label>Costo Desplazamiento</label>
                        
                        <vue-numeric disabled   :minus="true" class="form-control form-control-sm "  currency="$" separator="," :precision="0" v-model="cotizacion_servicio.TotalCostoKm"></vue-numeric>
                       <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.TotalCostoKm" :Mensaje="perrorvalidacion.TotalCostoKm[0]"></Cvalidation></label>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <label>Costo Mano de Obra y Desplazamiento</label>
                        <vue-numeric disabled   :minus="true" class="form-control form-control-sm "  currency="$" separator="," :precision="0" v-model="cotizacion_servicio.CostoManoObraD"></vue-numeric>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.CostoManoObraD" :Mensaje="perrorvalidacion.CostoManoObraD[0]"></Cvalidation></label>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <label>Miscelaneos</label>
                        <vue-numeric disabled   :minus="true" class="form-control form-control-sm "  currency="$" separator="," :precision="0" v-model="cotizacion_servicio.totalMiscelaneos"></vue-numeric>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.totalMiscelaneos" :Mensaje="perrorvalidacion.totalMiscelaneos[0]"></Cvalidation></label>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <label>Costo Burden</label>
                        <vue-numeric disabled   :minus="true" class="form-control form-control-sm "  currency="$" separator="," :precision="0" v-model="cotizacion_servicio.CostoBurden"></vue-numeric>
                       <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.CostoBurden" :Mensaje="perrorvalidacion.CostoBurden[0]"></Cvalidation></label>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <label>Garantía</label>
                        <vue-numeric disabled   :minus="true" class="form-control form-control-sm "  currency="$" separator="," :precision="0" v-model="TotalGarantia"></vue-numeric>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <hr class="total">
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <label class="lab-01">Costo Total</label>
                        
                        <vue-numeric disabled   :minus="true" class="form-control form-control-sm "  currency="$" separator="," :precision="0" v-model="cotizacion_servicio.totalGlobal"></vue-numeric>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.totalGlobal" :Mensaje="perrorvalidacion.totalGlobal[0]"></Cvalidation></label>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <label class="lab-01">Valor Venta</label>
                        <vue-numeric disabled   :minus="true" class="form-control form-control-sm numer border-yellow"  currency="$" separator="," :precision="0" v-model="cotizacion_servicio.ValorVenta"></vue-numeric>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.perrorvalidacion.ValorVenta" :Mensaje="perrorvalidacion.ValorVenta[0]"></Cvalidation></label>
                    </div>
                </div>
            </div>-->
        </div>
    </div>
</template>

<script>

export default {
    name:'resultado',
    props:['pcotizacion_servicio','perrorvalidacion'],
    data() {
        return {
            cotizacion_servicio:{},
            TotalGarantia:0
        }
    },
    methods: {        
    },
    created() {
        this.cotizacion_servicio=this.pcotizacion_servicio; 
    },
    computed: {
        TotalCostoDesplazamiento(){
            var Distancia=this.cotizacion_servicio.distancia;
            var CostoKM=this.cotizacion_servicio.costoKm; 
            var Valor=0;
            if(Distancia==''){Distancia=0;}
            if(CostoKM==''){CostoKM=0;}
          
            Valor = (parseFloat(Distancia)*2)*parseFloat(CostoKM);
            this.cotizacion_servicio.TotalCostoKm=parseFloat(Valor.toFixed(0));
           
           // return Valor;
        },
        TotalMODesplazamiento(){  
            var Distancia=parseFloat(this.cotizacion_servicio.distancia);
            var Velocidad=parseFloat(this.cotizacion_servicio.velocidad);
            /*Costo mano de obra desplazamiento =( (cantidad *2) /velocidad) * COSTO MO (TABLA MANO DE 
OBRA) donde se haya escrito hora normal .*/
            var total=0;
            this.cotizacion_servicio.ListaManoObra.forEach(element => {
                if(element.horaNormal!='' && element.horaNormal>0){
                   total+= ((Distancia*2)/Velocidad)*parseFloat(element.costoMO);
                }
            });

           this.cotizacion_servicio.CostoManoObraD=parseFloat(total.toFixed(0));
            //return total.toFixed(0);
        },
        TotalCostoBurden(){
            //Costo burden = nomral + extra * burden 
            var total=0;
            var cont=0;
            this.cotizacion_servicio.ListaManoObra.forEach(element => {
                if (cont<2)
                {
                    var HoraNorma=0;
                    var HoraExtra=0;
                    var Burden=0;
                    if(element.horaNormal!='' && element.horaNormal>0){
                        HoraNorma=element.horaNormal;
                    }
                    if(element.horaExtra!='' && element.horaExtra>0){
                        HoraExtra=element.horaExtra;
                    }

                    if(element.Burden!='' && element.Burden>0){
                        Burden=element.Burden;
                    }
                    total+=parseFloat(HoraNorma + HoraExtra) * parseFloat(Burden);
                    cont ++; 
                }
            });
            
           this.cotizacion_servicio.CostoBurden=parseFloat(total.toFixed(0));
            //return total.toFixed(0);
        },
        totalGarantia(){
            //Garantía = (Materiales + mano de obra + costo desplazamiento + costo mano de obra desplazamiento
            // + micelaneos + costo burden )*  (garantía/100) 
            var Materiales=this.cotizacion_servicio.totalMateriales;
            var totalManoDeObra=this.cotizacion_servicio.totalManoDeObra;
            var CostoDesplazamiento=this.cotizacion_servicio.TotalCostoKm;
            var CostoManoObraD=this.cotizacion_servicio.CostoManoObraD;
            var TotalMiscelaneo=this.cotizacion_servicio.totalMiscelaneos;
            var CostoBurden=this.cotizacion_servicio.CostoBurden;
            var Garantia=this.cotizacion_servicio.Garantia;

            if(Materiales==''){Materiales=0;}
            if(totalManoDeObra==''){totalManoDeObra=0;}
            if(CostoDesplazamiento==''){CostoDesplazamiento=0;}
            if(CostoManoObraD==''){CostoManoObraD=0;}
            if(TotalMiscelaneo==''){TotalMiscelaneo=0;}
            if(CostoBurden==''){CostoBurden=0;}
            if(Garantia==''){Garantia=0;}
            
            let Total = (parseFloat(Materiales) + parseFloat(totalManoDeObra) + parseFloat(CostoDesplazamiento) +parseFloat(CostoManoObraD) + parseFloat(TotalMiscelaneo) + parseFloat(CostoBurden) )*  (parseFloat(Garantia)/100); 
            this.TotalGarantia=Total.toFixed(0);
            //return Total.toFixed(0);
        },
        cal_CostoTotal(){
            //Costo total = (Materiales + mano de obra + costo desplazamiento + costo mano de obra desplazamiento + micelaneos + costo burden + Garantía)
            var Materiales=this.cotizacion_servicio.totalMateriales;
            var totalManoDeObra=this.cotizacion_servicio.totalManoDeObra;
            var CostoDesplazamiento=this.cotizacion_servicio.TotalCostoKm;
            var CostoManoObraD=this.cotizacion_servicio.CostoManoObraD;
            var TotalMiscelaneo=this.cotizacion_servicio.totalMiscelaneos;
            var CostoBurden=this.cotizacion_servicio.CostoBurden;
            var Garantia=this.cotizacion_servicio.Garantia;

            if(Materiales==''){Materiales=0;}
            if(totalManoDeObra==''){totalManoDeObra=0;}
            if(CostoDesplazamiento==''){CostoDesplazamiento=0;}
            if(CostoManoObraD==''){CostoManoObraD=0;}
            if(TotalMiscelaneo==''){TotalMiscelaneo=0;}
            if(CostoBurden==''){CostoBurden=0;}
            if(Garantia==''){Garantia=0;}
            
            let Total=(parseFloat(Materiales)+parseFloat(totalManoDeObra)+parseFloat(CostoDesplazamiento)+parseFloat(CostoManoObraD)+parseFloat(TotalMiscelaneo)+parseFloat(CostoBurden)+parseFloat(this.TotalGarantia));

            this.cotizacion_servicio.totalGlobal=parseFloat(Total.toFixed(0));
           // return Total.toFixed(0);
        },
        TotalValorVenta(){
            //COSTOTOTA*100 /GASTOS OPERACIONES;
            let CotosTotal=this.cotizacion_servicio.totalGlobal;
            let gastoOperaciones=this.cotizacion_servicio.gastoOperaciones;
            if(CotosTotal==''){CotosTotal=0;}
            if(gastoOperaciones==''){gastoOperaciones=0;}
            let Total=parseFloat(CotosTotal)*100/parseFloat(gastoOperaciones);

            if(isNaN(Total) || Total==Infinity){
                Total=0;
            }

            this.cotizacion_servicio.ValorVenta=parseFloat(Total.toFixed(0));
            //return Total.toFixed(0);
        }
    },
}
</script>