<template>
    <div>
    {{operacion}}
    {{ calcularValores }}
    {{ CalCularBurdenRate }}
        <div v-if="this.ObjBurden != undefined" class="form-group form-row">
            <div class="col-md-3 col-lg-2">
                <label>Núm. Trabajadores</label>
                <vue-numeric class="form-control text-center" placeholder="Número de Trabajadores" currency="" separator="," :precision="0" v-model="ObjBurden.NumTrab" ></vue-numeric>
            </div>

            <div class="col-md-3 col-lg-2">
                <label>Semanas Productivas</label>
                <vue-numeric class="form-control text-center" placeholder="Semanas Productivas" currency="" separator="," :precision="0" v-model="ObjBurden.SemProd" ></vue-numeric>
            </div>

            <div class="col-md-3 col-lg-2">
                <label>Hor. Semanales Productivas</label>
                <vue-numeric class="form-control text-center" placeholder="Horas Semanales Productivas" currency="" separator="," :precision="0" v-model="ObjBurden.HorasSemProd" ></vue-numeric>
            </div>

            <div class="col-md-3 col-lg-2">
                <label>Total Anual</label>
                <vue-numeric disabled class="form-control text-center" placeholder="Total Anual" currency="$" separator="," :precision="2" v-model="ObjBurden.TotalAnual" ></vue-numeric>
            </div>

            <div class="col-md-3 col-lg-2">
                <label>Burden Rate</label>
                <!--<vue-numeric disabled class="form-control text-right" placeholder="Burden Rate" currency="" separator="," :precision="0" v-model="ObjBurden.BurdenRate" ></vue-numeric>-->

                <input type="text" disabled class="form-control text-center" placeholder="Burden Rate" currency="" separator="," :precision="2" v-model="ObjBurden.BurdenRate">
            </div>

            <div class="col-md-3 col-lg-2">
                <label>Burden Rate Corregido</label>
                <!-- <vue-numeric class="form-control form-finanza" placeholder="Burden Rate Corregido" currency="" separator="," :precision="0" v-model="ObjBurden.BurdenRateC" ></vue-numeric> -->

                <input @change="conDecimalBurden()" type="text" class="form-control text-center" placeholder="Burden Rate Corregido" :precision="2"  v-model="ObjBurden.BurdenRateC">
            </div>
        </div>

        <div v-if="this.ObjCVO != undefined" class="form-inline justify-content-center my-2">
            <div class="col-md-3 col-lg-2">
                <label>Núm. Vehiculos</label>
                <vue-numeric class="form-control text-center" placeholder="Número de Vehiculos" v-model="ObjCVO.NumVehiculos" ></vue-numeric>
            </div>

            <div class="col-md-3 col-lg-2">
                <label>Km Productivos Totales </label>
                <vue-numeric class="form-control text-center" placeholder="Km productos" v-model="ObjCVO.kmproductivo" ></vue-numeric>
            </div>

            <div class="col-md-3 col-lg-2">
                <label>Total Km Anuales</label>
                <vue-numeric class="form-control text-center" placeholder="Total Anual" disabled v-model="ObjCVO.TotalAnual" ></vue-numeric>
            </div>

            <div class="col-md-3 col-lg-2">
                <label>$/Km</label>
                <!-- <vue-numeric class="form-control text-right" disabled placeholder="$/Km" v-model="ObjCVO.TotalFinal" ></vue-numeric> -->
                <input type="text" class="form-control text-center" disabled placeholder="$/Km" v-model="ObjCVO.TotalFinal">
            </div>

            <div class="col-md-3 col-lg-2">
                <label>$/Km corregido</label>
                <!-- <vue-numeric class="form-control text-right" placeholder="$/Km corregido" v-model="ObjCVO.TotalCorregido" ></vue-numeric> -->
                <input @change="conDecimal()" type="text" class="form-control text-center" placeholder="$/Km" v-model="ObjCVO.TotalCorregido">
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <div class="table-porcentaje">
                    <table class="table-por-01">
                        <thead>
                            <tr>
                                <th scope="col" class="sticky mediana marca"><b>Descripción</b></th>
                                <th scope="col" class="mediana text-center">Numero Cuenta</th>
                                <th scope="col" class="blue-01 mediana text-center">Año Anterior</th>
                                <th scope="col" class="blue-03 mediana text-center">Trimestre 1</th>
                                <th scope="col" class="blue-03 mediana text-center">Trimestre 2</th>
                                <th scope="col" class="blue-03 mediana text-center">Trimestre 3</th>
                                <th scope="col" class="blue-03 mediana text-center">Trimestre 4</th>
                                <th scope="col" class="blue-02 mediana text-center">Total Anual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in Lista" :key="index">
                                <td class="sticky">
                                    <input v-if="item.Descripcion != 'Almacén'"  type="text" class="form-control sticky-input" style="width:150px;" v-model="item.Descripcion" />
									<input v-if="item.Descripcion == 'Almacén'" readonly="true"  type="text" class="form-control sticky-input" style="width:150px;" v-model="item.Descripcion" />
								</td>

                                <td>
                                    <input type="text" class="form-control form-finanza form-control-sm text-center" style="width:150px;" v-model="item.NumeroCuenta" />
                                </td>
                                <td>
                                    <vue-numeric placeholder="$ 0.00" class="form-control form-finanza form-control-sm text-center" currency="$" separator="," :precision="2" v-model="item.AnioAnterior" ></vue-numeric>
                                </td>
                                <td>
                                    <vue-numeric placeholder="$ 0.00" class="form-control form-finanza form-control-sm text-center" currency="$" separator="," :precision="2" v-model="item.PrimerT" ></vue-numeric>
                                </td>
                                <td>
                                    <vue-numeric placeholder="$ 0.00" class="form-control form-finanza form-control-sm text-center" currency="$" separator="," :precision="2" v-model="item.SegundoT" ></vue-numeric>
                                </td>
                                <td>
                                    <vue-numeric placeholder="$ 0.00" class="form-control form-finanza form-control-sm text-center" currency="$" separator="," :precision="2" v-model="item.TercerT" ></vue-numeric>
                                </td>
                                <td>
                                    <vue-numeric placeholder="$ 0.00" class="form-control form-finanza form-control-sm text-center" currency="$" separator="," :precision="2" v-model="item.CuartoT" ></vue-numeric>
                                </td>
                                <td>
                                    <vue-numeric placeholder="$ 0.00" disabled class="form-control form-finanza form-control-sm text-center" currency="$" separator="," :precision="2" v-model="item.MontoAnual" ></vue-numeric>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="color-01 bold sticky marca">TOTALES</td>
                                <td></td>
                                <td>
                                    <vue-numeric placeholder="$ 0.00" disabled class="form-control form-finanza form-control-sm color-01 bold text-center" currency="$" separator="," :precision="2" v-model="TAnioA" ></vue-numeric>
                                </td>
                                <td>
                                    <vue-numeric placeholder="$ 0.00" disabled class="form-control form-finanza form-control-sm color-01 bold text-center" currency="$" separator="," :precision="2" v-model="TTrimestre1" ></vue-numeric>
                                </td>
                                <td>
                                    <vue-numeric placeholder="$ 0.00" disabled class="form-control form-finanza form-control-sm color-01 bold text-center" currency="$" separator="," :precision="2" v-model="TTrimestre2" ></vue-numeric>
                                </td>
                                <td>
                                    <vue-numeric placeholder="$ 0.00" disabled class="form-control form-finanza form-control-sm color-01 bold text-center" currency="$" separator="," :precision="2" v-model="TTrimestre3" ></vue-numeric>
                                </td>
                                <td>
                                    <vue-numeric placeholder="$ 0.00" disabled class="form-control form-finanza form-control-sm color-01 bold text-center" currency="$" separator="," :precision="2" v-model="TTrimestre4" ></vue-numeric>
                                </td>
                                <td>
                                    <vue-numeric placeholder="$ 0.00" disabled class="form-control form-finanza form-control-sm color-01 bold text-center" currency="$" separator="," :precision="2" v-model="TTotalA" ></vue-numeric>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["Lista", "Nombre", "ObjBurden", "ObjCVO"],
    data() {
        return {
            TAnioA: 0,
            TTrimestre1: 0,
            TTrimestre2: 0,
            TTrimestre3: 0,
            TTrimestre4: 0,
            TTotalA: 0,
            Decimal: 1
        };
    },
    methods: {
        conDecimal(){
            let valor = parseFloat(this.ObjCVO.TotalCorregido);
            this.ObjCVO.TotalCorregido = valor.toFixed(2);
        },
        conDecimalBurden(){
            let valor = parseFloat(this.ObjBurden.BurdenRateC);
            this.ObjBurden.BurdenRateC = valor.toFixed(2);
        },
    },
    created() {},
    computed: {
        calcularValores() {
            this.TAnioA = 0;
            this.TTrimestre1 = 0;
            this.TTrimestre2 = 0;
            (this.TTrimestre3 = 0), (this.TTrimestre4 = 0);
            this.TTotalA = 0;
            for (var i = 0; i < this.Lista.length; i++) {
                var Uno = 0,
                Dos = 0,
                Tres = 0,
                Cuatro = 0;

                var mont = this.Lista[i].AnioAnterior;
                if(this.Lista[i].AnioAnterior != "") {
                    this.TAnioA += parseFloat(this.Lista[i].AnioAnterior);
                }
                if(this.Lista[i].PrimerT != "") {
                    this.TTrimestre1 += parseFloat(this.Lista[i].PrimerT);
                    Uno = parseFloat(this.Lista[i].PrimerT);
                }
                if(this.Lista[i].SegundoT != "") {
                    this.TTrimestre2 += parseFloat(this.Lista[i].SegundoT);
                    Dos = parseFloat(this.Lista[i].SegundoT);
                }
                if(this.Lista[i].TercerT != "") {
                    this.TTrimestre3 += parseFloat(this.Lista[i].TercerT);
                    Tres = parseFloat(this.Lista[i].TercerT);
                }
                if(this.Lista[i].CuartoT != "") {
                    this.TTrimestre4 += parseFloat(this.Lista[i].CuartoT);
                    Cuatro = parseFloat(this.Lista[i].CuartoT);
                }

                this.Lista[i].MontoAnual =
                parseFloat(Uno) +
                parseFloat(Dos) +
                parseFloat(Tres) +
                parseFloat(Cuatro);

                if (this.Lista[i].MontoAnual != "") {
                    this.TTotalA += parseFloat(this.Lista[i].MontoAnual);
                }
            }

            if (this.ObjBurden != undefined) {
                this.ObjBurden.TotalAnual = this.TTotalA;
            }
            if (this.ObjCVO != undefined) {
                this.ObjCVO.TotalAnual = this.TTotalA;
            }
        },
        CalCularBurdenRate() {
            if (this.ObjBurden != undefined) {
                var Numtrab = this.ObjBurden.NumTrab;
                var SemProd = this.ObjBurden.SemProd;
                var HorasSemProd = this.ObjBurden.HorasSemProd;

                if (Numtrab == "") {
                    Numtrab = 0;
                }
                if (SemProd == "") {
                    SemProd = 0;
                }
                if (HorasSemProd == "") {
                    HorasSemProd = 0;
                }

                var calcmulti = parseFloat(this.ObjBurden.TotalAnual) / (parseFloat(Numtrab) * parseFloat(SemProd) * parseFloat(HorasSemProd));

                if (calcmulti == "Infinity") {
                    calcmulti = "0";
                }

                if (calcmulti > 0) {
                    this.ObjBurden.BurdenRate = calcmulti.toFixed(2);//Math.round(calcmulti);
                } else {
                    this.ObjBurden.BurdenRate = "0";
                }
            }

            if (this.ObjCVO != undefined) {
                var NumVehiculos = this.ObjCVO.NumVehiculos;
                var kmproductivo = this.ObjCVO.kmproductivo;
                var TotalAnual = this.ObjCVO.TotalAnual;
                var TotalFinal = this.ObjCVO.TotalFinal;
                var TotalCorregido = this.ObjCVO.TotalCorregido;

                if (NumVehiculos == "") {
                    NumVehiculos = 0;
                }
                if (kmproductivo == "") {
                    kmproductivo = 0;
                }
                if (TotalAnual == "") {
                    TotalAnual = 0;
                }
                if (TotalFinal == "") {
                    TotalFinal = 0;
                }
                if (TotalCorregido == "") {
                    TotalCorregido = 0;
                }
            }

            return "";
        },
        operacion() {
            // lef = ObjCVO.TotalFinal = this.ObjCVO.TotalAnual / this.ObjCVO.NumVehiculos / this.ObjCVO.kmproductivo;
            // this.$emit("vue-numeric", this.ObjCVO);
            // return "";

            if (this.ObjCVO != undefined) {
                var NumVehiculos    = this.ObjCVO.NumVehiculos;
                var kmproductivo    = this.ObjCVO.kmproductivo;
                var TotalAnual      = this.ObjCVO.TotalAnual;
                var TotalFinal      = this.ObjCVO.TotalFinal;

                if (NumVehiculos == "") {
                    NumVehiculos = 0;
                }
                if (kmproductivo == "") {
                    kmproductivo = 0;
                }
                if (TotalAnual == "") {
                    TotalAnual = 0;
                }
                if (TotalFinal == "") {
                    TotalFinal = 0;
                }

                let v1 = TotalAnual/NumVehiculos/kmproductivo;
                var operacionfinanza = v1;
                let v2 = this.ObjCVO.TotalAnual/this.ObjCVO.NumVehiculos/this.ObjCVO.kmproductivo;

                var calcmulti =  (parseFloat(this.ObjCVO.TotalAnual)/parseFloat(this.ObjCVO.NumVehiculos) /parseFloat(this.ObjCVO.kmproductivo));

                if (calcmulti == "Infinity") {
                    calcmulti = "0";
                }

                if (calcmulti > 0) {
                    this.ObjCVO.TotalFinal = calcmulti.toFixed(2);
                } else {
                    this.ObjCVO.TotalFinal = "0";
                }

                var corregido = parseFloat(this.ObjCVO.TotalFinal);
                this.ObjCVO.TotalFinal = corregido.toFixed(1);
            }
            return "";
        }
    }
};
</script>
