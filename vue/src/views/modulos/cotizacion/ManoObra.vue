<template>
    <div>
        <input type="hidden" :value="calculaTotalMO">
        <table class="table table-sm table-02 mt-4">
            <thead>
                <tr>
                    <th class="text-center colum01">Categoría</th>
                    <th class="text-center colum01">Costo M.O</th>
                    <th class="text-center colum01">Hr. Normal</th>
                    <th class="text-center colum01">Hr. Extra</th>
                    <th class="text-center colum01">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in cotizacion_servicio.ListaManoObra" :key="index">
                    <td>
                        {{item.categoria}}
                    </td>
                    <td>
                        <p v-if="item.Input" >
                            <vue-numeric  @input="cal_costoMO(index)" placeholder="$ 0.00" :minus="false"  class="form-control form-control-sm"  currency="$" separator="," :precision="2" v-model="item.costoMO"></vue-numeric>
                            <!-- <Cmoneda  @blur="cal_costoMO(index)"  :activo="false" :clase="'form-control form-finanza form-control-sm text-left'"  :readonly="false"     :minus="true"        currency="$" separator="," :precision="Decimal" v-model="item.costoMO"></Cmoneda> -->
                        </p>
                        <p v-else>
                            $ {{item.costoMO}}
                        </p>
                    </td>
                    <td>
                        <vue-numeric  @input="cal_costoMO(index)" placeholder="$ 0.00" :minus="false"  class="form-control form-control-sm  w-04"  currency="" separator="," :precision="2" v-model="item.horaNormal"></vue-numeric>
                        <!-- <Cmoneda  @input="cal_costoMO(index)"  :activo="false" :clase="'form-control form-finanza form-control-sm text-left'"  :readonly="false"     :minus="true"        currency="$" separator="," :precision="Decimal" v-model="item.horaNormal"></Cmoneda> -->
                    </td>
                    <td>
                        <vue-numeric  @input="cal_costoMO(index)" placeholder="$ 0.00" :minus="false"  class="form-control form-control-sm  w-04"  currency="" separator="," :precision="2" v-model="item.horaExtra"></vue-numeric>
                        <!-- <Cmoneda  @input="cal_costoMO(index)"  :activo="false" :clase="'form-control form-finanza form-control-sm text-left'"  :readonly="false"     :minus="true"        currency="$" separator="," :precision="Decimal" v-model="item.horaExtra"></Cmoneda> -->
                    </td>
                    <td>
                        <vue-numeric  disabled  :minus="false" placeholder="$ 0.00" class="form-control form-control-sm"  currency="$" separator="," :precision="2" v-model="item.totalIndividual"></vue-numeric>
                        <!-- <Cmoneda  @input="cal_costoMO(index)"  :activo="true" :clase="'form-control form-finanza form-control-sm text-left'"  :readonly="true"     :minus="true"        currency="$" separator="," :precision="Decimal" v-model="item.totalIndividual"></Cmoneda> -->
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right">
                            Total 
                    </td>
                    <td colspan="1">
                        <vue-numeric  disabled placeholder="$ 0.0"  :minus="false" class="form-control form-control-sm"  currency="$" separator="," :precision="2" v-model="cotizacion_servicio.totalManoDeObra"></vue-numeric>
                        <!-- <Cmoneda  :activo="true" :clase="'form-control form-finanza form-control-sm text-left'"  :readonly="true"     :minus="true"        currency="$" separator="," :precision="Decimal" v-model="cotizacion_servicio.totalManoDeObra"></Cmoneda> -->
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name:'manoobra',
    props:['pcotizacion_servicio'],
    data() {
        return {
            urlApi:"cotizacion_servicio/listmo",
            cotizacion_servicio:{},
            ListManoObraCot:[],
            Burden:0,
            Disabled:false,
            Decimal:1,
        }
    },
    components:{
    },
    methods :
    {
        async get_list()
        {
           await this.$http.get(
                this.urlApi,
                {
                    params:{IdCotizacionServicio:this.cotizacion_servicio.IdCotizacionServicio,Entrada:50,pag:0, RegEstatus:'A'}
                }
            ).then( (res) => {
                this.cotizacion_servicio.ListaManoObra=res.data.data.row;
                this.Burden=res.data.data.Burden;
            });
        },
        cal_costoMO(index){
            var costMO=this.cotizacion_servicio.ListaManoObra[index].costoMO;
            var burden=this.cotizacion_servicio.ListaManoObra[index].Burden;
            var normal=this.cotizacion_servicio.ListaManoObra[index].horaNormal;
            var factor=this.cotizacion_servicio.factorHoraExtra;
            var extra=this.cotizacion_servicio.ListaManoObra[index].horaExtra;

            if(costMO==''){costMO=0;}
            if(burden==''){burden=0;}
            if(normal==''){normal=0;}
            if(factor==''){factor=0;}
            if(extra==''){extra=0;}
            if (index<2)
            {
                //var resultado = eval (  (parseFloat(costMO) + parseFloat(burden) ) * (parseFloat(normal)) +(  parseFloat(costMO) +(parseFloat(costMO)*(factor/100) )) *  extra + parseFloat(burden) * extra ); 
                //PRIMERO // var resultado = eval (  (parseFloat(costMO) ) * (parseFloat(normal)) +(  parseFloat(costMO) +(parseFloat(costMO)*(factor/100) )) *  extra * extra ); 
                //PRIMERO // this.cotizacion_servicio.ListaManoObra[index].totalIndividual=resultado.toFixed(0);

                //Poner decimales
                var resultado = eval (  parseFloat(costMO ) * parseFloat(normal) + (parseFloat( costMO) +( parseFloat(costMO)*(parseFloat(factor)/100) )) *  parseFloat(extra) * parseFloat(extra) ); 
           
                this.cotizacion_servicio.ListaManoObra[index].totalIndividual= resultado.toFixed(2);
            }
            else{
                //var resultado = eval (  (parseFloat(costMO) + (parseFloat(factor) /100) * (parseFloat(normal)) +(  parseFloat(extra) ) * (parseFloat(factor)/100 )));
                //PRIMERO // resultado=eval(Number((parseFloat(costMO) + parseFloat(factor/100))*(parseFloat(normal)+parseFloat((extra*(factor/100))))));
                //PRIMERO // this.cotizacion_servicio.ListaManoObra[index].totalIndividual=resultado.toFixed(0);

                //Poner decimales
                //resultado=eval(Number((costMO + factor/100)*(normal+(extra*(factor/100)))));
                resultado=(parseFloat(costMO) + parseFloat(factor)/100)*(parseFloat(normal)+(parseFloat(extra)*(parseFloat(factor)/100)));
                this.cotizacion_servicio.ListaManoObra[index].totalIndividual=resultado.toFixed(2);
                
            }
        },

        AgregarFactorExtra(){
            let Contador = 0;

            this.cotizacion_servicio.ListaManoObra.forEach(element => {
                this.cal_costoMO(Contador);
                Contador++;
            });
        }
    },
    created() {
        this.bus.$off('OperacionFactor');

        this.bus.$on('OperacionFactor',()=>
        {
           //alert('Pasa');
           this.AgregarFactorExtra();
        });

        this.cotizacion_servicio=this.pcotizacion_servicio;
        this.get_list();        
    },
    computed: {

        
        calculaTotalMO(){
            var Total=0;
            this.cotizacion_servicio.ListaManoObra.forEach(element => {
                if(element.totalIndividual!=''){
                    Total+=parseFloat(element.totalIndividual);
                }
            });
            // console.log(this.cotizacion_servicio.ListaManoObra);

            this.cotizacion_servicio.totalManoDeObra= Total.toFixed(2);
            return Total.toFixed(0);
        }
    },
}
</script>