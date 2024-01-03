<template>
    <div>
        <input type="hidden" :value="calcula_total">
        <div class="row justify-content-start">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <table class="table table-sm table-03 mt-3">
                    <thead>
                        <tr>
                            <th style="width: 20% !important;" class="colum02">Concepto</th>
                            <th  class="text-center colum02">Costo</th>
                            <!-- <th class="text-center colum02">Costo Total</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in cotizacion_servicio.ListaMiscelaneCot" :key="index">
                            <td>
                               <p v-if="item.concepto=='Contratistas'"> {{item.concepto}}</p> 
                               <p v-if="item.concepto=='Equipos'"> {{item.concepto}}</p> 
                                <input placeholder="Descripción" v-if="item.concepto!='Contratistas' && item.concepto!='Equipos'"  type="text" class="form-control form-control-sm" v-model="item.concepto">
                            </td>
                            <td >
                                <vue-numeric  @input="calcula(index)" placeholder="$ 0.00"  :minus="false" class="form-control form-control-sm  w-04"  currency="$" separator=","  :precision="2" v-model="item.cantidad"></vue-numeric>
                            </td>
                            <!-- <td>
                                <vue-numeric disabled   :minus="false" class="form-control form-control-sm"  currency="$" separator="," :precision="2" v-model="item.valor"></vue-numeric>
                            </td> -->
                        </tr>
                        <tr>
                            <td colspan="1">Total</td>
                            <td> <vue-numeric disabled placeholder="$ 0.00"  :minus="false" class="form-control form-control-sm  w-04"  currency="$" separator="," :precision="2" v-model="cotizacion_servicio.totalMiscelaneos"></vue-numeric> </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name:'manoobra',
    props:['pcotizacion_servicio'],
    data() {
        return {
            urlApi:"cotizacion_servicio/listmiscelaneo",
            cotizacion_servicio:{},
        }
    },
    components:{
        
    },
    methods :
    {
       async  carga_lista(){
            await this.$http.get(
                this.urlApi,
                {
                    params:{IdCotizacionServicio:this.cotizacion_servicio.IdCotizacionServicio,Entrada:50,pag:0, RegEstatus:'A'}
                }
            ).then( (res) => {
                this.cotizacion_servicio.ListaMiscelaneCot=res.data.data.row;
            });    
        },
        calcula(index){
            let Cantidad=this.cotizacion_servicio.ListaMiscelaneCot[index].cantidad;
            
            if(Cantidad==''){
                    Cantidad=0;
            }
            let result=parseFloat(Cantidad)*1.2;
            if(result==''){
                result=0;
            }
            
            this.cotizacion_servicio.ListaMiscelaneCot[index].valor=parseFloat(Cantidad.toFixed(2));
        }
    },
    created()
    {
        this.cotizacion_servicio=this.pcotizacion_servicio;
        this.carga_lista();
    },
    computed: {

        
        calcula_total(){
            var Total=0;
            this.cotizacion_servicio.ListaMiscelaneCot.forEach(element => {
                if(element.valor!=''){
                    Total+=parseFloat(element.valor);
                }
            });

            this.cotizacion_servicio.totalMiscelaneos=parseFloat(Total.toFixed(2));
            return Total.toFixed(0);
        }
    },
}
</script>