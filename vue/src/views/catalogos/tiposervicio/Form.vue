<template>
    <div>
    <!--Fin head del panel-->

        <div class="card-body">
            <div class="row">
                <div class="col-lg-4">
                    <span class="has-float-label">
                        <label for="Nombre" class="labeltam">Concepto</label>
                        <input
                        type="text"
                        v-model="tiposervicio.Concepto"
                        class="form-control form-control-sm"
                        placeholder="Concepto"
                        id="Nombre"
                        name="Nombre"
                        />
                        <label id="lblmsuser" style="color: red">
                            <Cvalidation v-if="this.errorvalidacion.Concepto" :Mensaje="errorvalidacion.Concepto[0]" ></Cvalidation>
                        </label>
                    </span>
                </div>

                <div class="col-lg-4">
                    <span class="has-float-label">
                        <label for="CP" class="labeltam">Gross Margin</label>
                        <vue-numeric :minus="false" class="form-control form-control-sm" currency="" separator="," :precision="2" v-model="tiposervicio.GrossM" ></vue-numeric>
                        <label id="lblmsuser" style="color: red">
                            <Cvalidation v-if="this.errorvalidacion.GrossM" :Mensaje="errorvalidacion.GrossM[0]"></Cvalidation>
                        </label>
                    </span>
                </div>

                <div class="col-lg-4">
                    <span class="has-float-label">
                        <label for="CP" class="labeltam">Color</label>
                        <input type="color" v-model="tiposervicio.Color" class="form-control form-control-sm"/>
                        <label id="lblmsuser" style="color: red">
                            <Cvalidation v-if="this.errorvalidacion.Color" :Mensaje="errorvalidacion.Color[0]"></Cvalidation>
                        </label>
                    </span>
                </div>

                <div class="col-lg-4">
                    <span class="has-float-label">
                        <label for="CP" class="labeltam">Facturar al Cliente</label>

                        <select v-model="tiposervicio.Ingresos" class="form-control">
                        <option :value="''">Seleccione una opción</option>
                        <option value="s">Facturable</option>
                        <option value="n">No Facturable</option>
                        </select>
                        <label id="lblmsuser" style="color: red">
                            <Cvalidation v-if="this.errorvalidacion.Factura" :Mensaje="'Campo obligatorio'"></Cvalidation>
                        </label>
                    </span>
                </div>

                <div class="col-lg-4">
                    <span class="has-float-label">
                        <label for="CP" class="labeltam">Tipo de Servicio</label>

                        <select name="" v-model="tiposervicio.IdConfigS" class="form-control" id="">
                            <option :value="''">Seleccione una opción</option>
                            <option :value="lista.IdConfigS" v-for="(lista, key, index) in ListaConfigServicios" :key="index">
                                {{ lista.Nombre }}
                            </option>
                        </select>
                        <label id="lblmsuser" style="color: red">
                            <Cvalidation v-if="this.errorvalidacion.IdConfigS" :Mensaje="'Campo obligatorio'"></Cvalidation>
                        </label>
                    </span>
                </div>

                <div class="col-lg-4">
                    <span class="has-float-label">
                        <label for="CP" class="labeltam">Color Letra</label>
                        <!-- <div class="form__label" style="border:solid:red">
                        <v-swatches :swatches="swatches" v-model="color" inline></v-swatches>
                        </div> -->

                        <!-- ` <div class="" >
                        <v-swatches
                        :class="'border: 2px solid red;border-radius: 25px;'"
                        :style="'border: 2px solid red;border-radius: 25px;'"
                        :swatches="swatches"
                        v-model="color"
                        
                        fallback-input-type="color"
                        shapes="circles"
                        ></v-swatches>
                        </div>` -->

                        <div class="form__input">
                            <v-swatches v-model="tiposervicio.ColorLetra" popover-x="left"></v-swatches>
                        </div>

                        <label id="lblmsuser" style="color: red">
                            <Cvalidation v-if="this.errorvalidacion.Color" :Mensaje="errorvalidacion.Color[0]"></Cvalidation>
                        </label>
                    </span>
                </div>

                <div class="col-lg-12">
                    <h3>Iconos de Herramientas</h3>
                </div>
                <div class="col-lg-12">
                    <a :value="lista.IdIcono" v-for="(lista, key, index) in Iconos" :key="index" href="#" class="btn btn-default btn-circle btn-lg">
                        <!--    <i class="fab fa-facebook-f"></i>-->
                        <img style="width: 80%" :src="ruta + lista.Imagen2" />
                        <input type="radio" name="gridRadios" :value="lista.IdIcono" class="form-group" v-model="tiposervicio.IdIcono"/>
                    </a>
                    <label id="lblmsuser" style="color: red">
                        <Cvalidation v-if="this.errorvalidacion.Icono" :Mensaje="errorvalidacion.Icono[0]"></Cvalidation>
                    </label>
                </div>
            </div>
            <!--Fin body del panel-->
        </div>
    </div>
</template>
<script>

import VSwatches from 'vue-swatches'
// Import the styles too, typically in App.vue or main.js
import 'vue-swatches/dist/vue-swatches.css'

//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from "@/components/Cbtnsave.vue";
import Icon from "@/views/catalogos/tiposervicio/Iconos.vue";
import Cvalidation from "@/components/Cvalidation.vue";

export default {
    name: "Form",
    props: ["IdTipoSer", "poBtnSave"],
    data() {
        return {
            Modal: true, //Sirve pra los botones de guardado
            FormName: "tiposervicio", //Sirve para donde va regresar
            Iconos: [],
            ListaConfigServicios: [],


            color: '#000000',
            swatches: ['#000000','#FFFFFF'],

            tiposervicio: {
                IdTipoSer: 0,
                Concepto: "",
                IdSucursal: "",
                GrossM: "",
                Color: "",
                Ingresos: "",
                IdIcono: "",
                Tipo: "",
                IdConfigS: "",
                ColorLetra:'#000000'
            },
            urlApi: "tiposervicio/recovery",
            urlApiIconos: "iconos/get",
            urlCongiservicios: "configservicio/get",
            errorvalidacion: [],
            ruta: "",
        };
    },
    components: {
        VSwatches ,
        Cbtnsave,
        Icon,
        Cvalidation,
    },
    methods: {
        async Guardar() {
            //deshabilita botones
            this.poBtnSave.toast = 0;
            this.poBtnSave.disableBtn = true;

            let formData = new FormData();

            formData.set("IdTipoSer", this.tiposervicio.IdTipoSer);
            formData.set("Concepto", this.tiposervicio.Concepto);
            formData.set("IdSucursal", this.tiposervicio.IdSucursal);
            formData.set("GrossM", this.tiposervicio.GrossM);
            formData.set("Color", this.tiposervicio.Color);
            formData.set("ColorLetra", this.tiposervicio.ColorLetra);
            formData.set("Ingresos", this.tiposervicio.Ingresos);
            formData.set("IdIcono", this.tiposervicio.IdIcono);
            formData.set("Tipo", this.tiposervicio.Tipo);
            formData.set("IdConfigS", this.tiposervicio.IdConfigS);
            
            await this.$http.post("tiposervicio/post", formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            })
            .then((res) => {
                this.poBtnSave.disableBtn = false;
                this.poBtnSave.toast = 1;

                $("#ModalForm").modal("hide");
                this.bus.$emit("List");
            })
            .catch((err) => {
                this.poBtnSave.disableBtn = false;
                this.errorvalidacion = err.response.data.message.errores;
                this.poBtnSave.toast = 2;
            });
        },
        Limpiar() {
            (this.tiposervicio.IdTipoSer = 0),
            (this.tiposervicio.Concepto = ""),
            (this.tiposervicio.IdSucursal = ""),
            (this.tiposervicio.GrossM = ""),
            (this.tiposervicio.Color = ""),
            (this.tiposervicio.Ingresos = "s"),
            (this.tiposervicio.IdIcono = ""),
            (this.tiposervicio.Tipo = ""),
            (this.errorvalidacion = []),
            (this.tiposervicio.IdConfigS = "");
            (this.tiposervicio.ColorLetra = "#000000");
        },
        get_one() {
            this.$http.get(this.urlApi, {
                    params: { IdTipoSer: this.tiposervicio.IdTipoSer },
            })
            .then((res) => {
                this.tiposervicio.IdTipoSer = res.data.data.tiposervicio.IdTipoSer;
                this.tiposervicio.Concepto = res.data.data.tiposervicio.Concepto;
                this.tiposervicio.IdSucursal = res.data.data.tiposervicio.IdSucursal;
                this.tiposervicio.GrossM = res.data.data.tiposervicio.GrossM;
                this.tiposervicio.Color = res.data.data.tiposervicio.Color;
                this.tiposervicio.ColorLetra = res.data.data.tiposervicio.ColorLetra;
                this.tiposervicio.Ingresos = res.data.data.tiposervicio.Ingresos;
                this.tiposervicio.IdIcono = res.data.data.tiposervicio.IdIcono;
                this.tiposervicio.Tipo = res.data.data.tiposervicio.Tipo;
                this.tiposervicio.IdConfigS = res.data.data.tiposervicio.IdConfigS;
            });
        },
        listaIconos() {
            this.$http.get(this.urlApiIconos, {
                params: { RegEstatus: "A" },
            })
            .then((res) => {
                this.Iconos = res.data.data.iconos;
                this.ruta = res.data.data.ruta;
            });
        },
        listaconfigservicio() {
            this.$http.get(this.urlCongiservicios, {
                params: { RegEstatus: "A" },
            })
            .then((res) => {
                this.ListaConfigServicios = res.data.data.configservicio;
            });
        },
    },
    created() {
        this.bus.$off("Nuevo");

        this.listaIconos();
        this.listaconfigservicio();

        //Este es para modal
        this.bus.$on("Nuevo", (data, Id) => {
            this.poBtnSave.disableBtn = false;
            this.bus.$off("Save");
            this.bus.$on("Save", () => {
                this.Guardar();
            });

            this.Limpiar();
            if (Id > 0) {
                this.tiposervicio.IdTipoSer = Id;
                this.get_one();
            }
            this.bus.$emit("Desbloqueo", false);
        });

        if (this.Id != undefined) {
            this.tiposervicio.IdTipoSer = this.Id;
            this.get_one();
        }
    },
};
</script>