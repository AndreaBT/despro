<template>
    <div>

        <div class="row mt-4 mb-4">
            <div class="col-md-12 col-lg-12">
                <div class="card card-01">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <h5 class="mb-3 text-center">Configuración de Promedio y Valor Contrato</h5>
                            <hr>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-6 col-lg-4">
                            <div class="table-venta">
                                <table class="table-venta-02">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Valor Promedio Contrato</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item,index) in ListaDetalle" :key="index">
                                            <th placeholder="$ 0.0" scope="row">{{item.Nombre}}</th>
                                            <td>
                                                <vue-numeric placeholder="$ 0.00" class="form-control form-finanza form-control-sm text-center" currency="$" separator="," :precision="2"  v-model="item.Data.ValorPromedio"></vue-numeric>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                   <!---- <div class="col-md-6 col-lg-4">
                            <div class="table-venta">
                                <table class="table-venta-02">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Porcentaje de bateo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item,index) in ListaDetalle" :key="index">
                                            <th scope="row">{{item.Nombre}}</th>
                                            <td>
                                                <vue-numeric class="form-control form-finanza form-control-sm" currency="" separator=","  v-model="item.Data.BaseContrato"></vue-numeric>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>----->
                    </div>
                </div>
            </div>
        </div>

        <!--------NUEVO ROW DE BATEO---------->

    <div class="row mt-4 mb-4">
            <div class="col-md-12 col-lg-12">
                <div class="card card-01">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <h5 class="mb-3 text-center">Porcentaje de Efectividad</h5>
                            <hr>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                         <div class="col-md-12 col-lg-12">
                            <div class="table-venta">
                                <table class="table-venta-02">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Propuesta de Cierre de Venta</th>
                                            <th colspan="2">Primera Reunión de Propuesta</th>
                                            <th colspan="2">Llamada en Frío a Primera Reunión</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item,index) in ListaDetalle" :key="index">
                                            <!------Propuesta Cierre de venta------>
                                            <th scope="row">{{item.Nombre}}</th>
                                            <td>
                                                <vue-numeric placeholder="0%" class="form-control form-finanza form-control-sm text-center" currency="%" separator="," currency-symbol-position="suffix"  v-model="item.Data.PorcentajeBCierre"></vue-numeric>
                                            </td>
                                            <!------Primera reunión de propuesta------>
                                            <th scope="row">{{item.Nombre}}</th>
                                            <td>
                                                <vue-numeric placeholder="0%" class="form-control form-finanza form-control-sm text-center" currency="%" separator="," currency-symbol-position="suffix"  v-model="item.Data.PorcentajePrimeraR"></vue-numeric>
                                            </td>
                                            <!------Llamanda en Frío------>
                                            <th scope="row">{{item.Nombre}}</th>
                                            <td>
                                                <vue-numeric placeholder="0%" class="form-control form-finanza form-control-sm text-center" currency="%" separator="," currency-symbol-position="suffix"  v-model="item.Data.PorcentajeLlamadaF"></vue-numeric>
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


<!--
          <div class="row justify-content-center">
            <div class="col-12 col-sm-12 col-md-10 col-lg-10">
                <div class="row justify-content-center mt-4">
                    <div class="col-12 col-sm-2 col-md-6 col-lg-6">
                        <div class="row justify-content-end">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <h3>Valor Promedio Contrato</h3>
                                <hr>
                            </div>
                        </div>
                        <div class="row justify-content-start">
                            <div class="col-12 col-sm-12 col-md-10 col-lg-10 text-center">
                                <table class="table">
                                        <tbody>
                                      <tr v-for="(item,index) in ListaDetalle" :key="index">
                                            <th scope="row">{{item.Nombre}}</th>
                                            <td>
                                                <vue-numeric class="form-control" currency="" separator=","  v-model="item.Data.ValorPromedio"></vue-numeric>
                                               </td>
                                            </tr>
                                        </tbody>
                                </table>
                            </div>

                        </div>

                    </div>
                    <div class="col-12 col-sm-2 col-md-6 col-lg-6">
                        <div class="row justify-content-end">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <h3>Porcentaje de Bateo</h3>
                                <hr>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-12 col-sm-12 col-md-10 col-lg-10 text-center">
                                 <table class="table">

                                        <tbody>
                                           <tr v-for="(item,index) in ListaDetalle" :key="index">
                                            <th scope="row">{{item.Nombre}}</th>
                                            <td><vue-numeric class="form-control" currency="%" separator=","  v-model="item.Data.PorcentajeB"></vue-numeric></td>
                                            </tr>
                                        </tbody>
                                    </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>-->
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

    },
    created() {

    },
}
</script>
