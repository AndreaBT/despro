<template>
    <div>
    <CHead :oHead="oHead"></CHead>
        <div class="row justify-content-start mt-3">
            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
            </div>
            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                <div class="card card-01">
                    <div class="row justify-content-end mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group form-row">
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label >Hora Inicio</label>
                                            <select  v-model="horaslaborales.Hora_I" class="form-control">
                                                <option value="">--Seleccionar--</option>
                                                <option v-for="(item, index) in ListaHoras" :key="index" :value="item">{{item}}</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label>Hora Final</label>
                                            <select  v-model="horaslaborales.Hora_F" class="form-control">
                                                <option value="">--Seleccionar--</option>
                                                <option v-for="(item, index) in ListaHoras" :key="index" :value="item">{{item}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                         <div class="col-12 col-sm-12 col-md-12 col-lg-12 text-right">
                            <button @click="Regresar" type="button" class="btn btn-04 ban">Cancelar</button>
                             <button @click="Guardar" type="button" class="btn btn-01">Guardar</button>&nbsp;
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
            </div>
        </div>

    </div>
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from '@/components/Cbtnsave.vue'
export default {
    name:'FormConfHorasLaborares',
    props:['IdHorasL'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'horaslaborales',//Sirve para donde va regresar
           horaslaborales:{
                IdHorasL:0,
                Hora_I:"",
                Hora_F:"",
                IdSucursal:"",
                Intervalo:"",
            },
            urlApi:"horaslaborales/recovery",
            ListaHoras:[],
            oHead:{//Encabezado
                Title:'Configuración de Horas',
                BtnNewShow:false,
                BtnNewName:'Nuevo',
                isreturn:true,
                isModal:false,
                isEmit:false,
                Url:'submenuadmon',
                ObjReturn:'',
            }
        }
    },
    components:{
        Cbtnsave
    },
    methods :
    {
        async Guardar()
        {
            let formData = new FormData();

            formData.set('IdHorasL',this.horaslaborales.IdHorasL);
            formData.set('Hora_I',this.horaslaborales.Hora_I);
            formData.set('Hora_F', this.horaslaborales.Hora_F);
            await this.$http.post(
                'horaslaborales/post',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                },
            ).then( (res) => {
                this.$toast.success('Información Guardada');
                this.get_one();
            }).catch( err => {
                this.$toast.error('Agregue todos los campos');
            });
        },
        Limpiar()
        {
            this.horaslaborales.IdHorasL=0,
            this.horaslaborales.Hora_I="",
            this.horaslaborales.Hora_F="",
            this.horaslaborales.IdSucursal="",
            this.horaslaborales.Intervalo=""
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{}
                }
            ).then( (res) => {
                this.horaslaborales.IdHorasL=res.data.data.horaslaborales.IdHorasL;
                this.horaslaborales.Hora_I=res.data.data.horaslaborales.Hora_I;
                this.horaslaborales.Hora_F=res.data.data.horaslaborales.Hora_F;
             });
        },
        async get_horas()
        {
            await this.$http.get(
                'horaslaborales/horaslaborales',
                {
                    params:{}
                }
            ).then( (res) => {
                this.ListaHoras=res.data.data.horaslaborales;
                //this.TotalPagina=res.data.data.pagination.TotalItems;
                //this.Pag=res.data.data.pagination.CurrentPage;
            });
        },
        Regresar()
        {
            this.$router.push({name:'submenuadmon'});
        }
    },
    created() {
        this.bus.$off('Nuevo');
        this.get_horas();
        this.Limpiar();
        this.get_one();
    }
}
</script>
