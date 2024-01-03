<template>
<div>
    <div class="row mt-4">
            <div class="col-md-12 col-lg-12">
                <div class="card card-01">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <h5 class="mb-3 text-center">Configuración de Porcentaje de Sub-Tipos de Servicios</h5>
                            <hr>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                            <div v-for="(item,index) in ListaDetalle" :key="index" class="col-md-6 col-lg-4">
                                <div class="table-venta" >
                                    <table class="table-venta-02">
                                        <thead>
                                            <tr>
                                                <th colspan="2"> {{item.Nombre}}</th>
                                            </tr>
                                            <tr>
                                                <th scope="col">Sub-Tipos de Servicio</th>
                                                <th scope="col">%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item2,index2) in item.ListaSubtipos" :key="index2">
                                            <th placeholder="% 0" scope="row">{{item2.Concepto}}</th>
                                            <td>
                                                <vue-numeric placeholder="0%" @input="Porcentajes" currency="%"  currency-symbol-position="suffix"  class="form-control form-finanza form-control-sm text-center"  separator=","  v-model="item2.Data.Porcentaje"></vue-numeric>
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

export default {
    props:['ListaDetalle'],
    data() {
        return {

        }
    },
    methods: {
        Porcentajes ()
        {

            for ( var i =0;i<this.ListaDetalle.length;i++ )
            {
               var Porcentaje=0;
                for (var z=0;z <this.ListaDetalle[i].ListaSubtipos.length; z++)
                {
                    if (this.ListaDetalle[i].ListaSubtipos[z].Data.Porcentaje=='')
                    {
                        Porcentaje=0;
                    }
                    else{
                        Porcentaje +=parseFloat(this.ListaDetalle[i].ListaSubtipos[z].Data.Porcentaje);
                    }

                        if (Porcentaje>100)
                        {
                            this.ListaDetalle[i].ListaSubtipos[z].Data.Porcentaje= "";
                            alert ('Porcentaje No puede rebasar el 100 %');
                        }
                }
            }

        }
    },
    created() {

    },

    computed: {

    }
}
</script>
