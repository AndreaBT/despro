<template>
    <div>
		<input type="hidden" :value="blockValidate">

        <div class="row mt-2">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <h4 class="titulo-04">Fechas</h4>
                <hr>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <label>Fecha de Inicio *</label>

                <v-date-picker

                    v-model="servicios.Fecha_I"
                    @input="ListarFechas"
                    :min-date="fechaMinima()"
                    :popover="{
                        placement: 'bottom',
                        visibility: 'click',
                    }"
                    :input-props='{
                        class:"form-control form-control-sm calendar",
                        style:"cursor:pointer;background-color:#F9F9F9",
                        readonly: true,
                        disabled: localBlocker.BlockFecha
                    }'
                />

                <label style="color:red">
					<Cvalidation v-if="errorvalidacion.Fecha_Inicio" :Mensaje="errorvalidacion.Fecha_Inicio[0]"/>
				</label>
            </div>

            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <label>Fecha para Finalizar *</label>

                <v-date-picker

                    @input="ListarFechas"
                    v-model="servicios.Fecha_F"
                    :min-date="servicios.Fecha_I"
                    :popover="{
                        placement: 'bottom',
                        visibility: 'click',
                    }"
                    :input-props='{
                        class:" form-control form-control-sm calendar",
                        style:"cursor:pointer;background-color:#F9F9F9",
                        readonly: true,
                        disabled: localBlocker.BlockFecha
                    }'
                    :timezone="timezone"
                />

                <label  style="color:red">
					<Cvalidation v-if="errorvalidacion.Fecha_Fin" :Mensaje="errorvalidacion.Fecha_Fin[0]"/>
				</label>
            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <hr>
            </div>
        </div>

        <div class="row mt-2">
            <div v-for="(item, index) in LitaFechas" :key="index" class="col-12 col-sm-12 col-md-4 col-lg-3">
                <div class="box-01">
                    <div class="form-row">
                        <div class="col-md-4 col-lg-4">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <span class="dia">{{item.Dia}} </span><br>
                                    <span class="mes">{{item.Mes}}</span>
                                </div>

                                <div class="col-12"></div>
                            </div>
                        </div>

                        <div class="col-md-8 col-lg-8">
                            <div class="row">

                                <div class="col-12" >
                                    <select :disabled="localBlocker.BlockHoraI" @change="ValidarFechas(servicios.FechasHoras[index],index)" v-model="servicios.FechasHoras[index].HoraI"  class="form-control border-verde">
                                        <option value="">--Hora Inicio--</option>
                                        <option v-for="(item2 ,index2 ) in servicios.FechasHoras[index].horaslaborales" :key="index2" :value="item2">{{item2}}</option>
                                    </select>
                                </div>

                                <div class="col-12 mt-2">
                                    <select :disabled="localBlocker.BlockHoraF" @change="ValidarFechas(servicios.FechasHoras[index],index)" v-model="servicios.FechasHoras[index].HoraF" class="form-control border-rojo">
                                        <option value="">--Hora Termino--</option>
                                        <option v-for="(item2 ,index2 ) in servicios.FechasHoras[index].horaslaborales " :key="index2" :value="item2">{{item2}}</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <pre>
            {{FechaMin}}
        </pre> -->
    </div>
</template>

<script>
import Cvalidation from '@/components/Cvalidation.vue'
import moment from 'moment';

const zonaH = sessionStorage.getItem('ZonaHoraria');

export default {
    props:['Consultado','errorvalidacion','servicios','pBloker'],
    name:'',
    components:{
        Cvalidation
    },
    data() {
        return {
            Fecha:'',
            Fecha2:'',
            LitaFechas:[],
            ListaHoras:[],
            FechaMin: '',
            timezoneIndex: 0,
            timezones: [
                zonaH
            ],
			localBlocker: {
				BlockFecha:true,
				BlockHoraI:false,
				BlockHoraF:false,
                FechaAyer:''
			},




		}
    },
    methods: {
        async ListarFechas()
        {
            //falta validar que las fechas no sean mayores que otras
            if (this.servicios.Fecha_I !='' && this.servicios.Fecha_F !=''  && this.servicios.Fecha_I !=null && this.servicios.Fecha_F !=null  )
            {
                await this.$http.get('servicio/fehcasdinamicas', {
					params:{
						FechaI:this.servicios.Fecha_I,
						FechaF:this.servicios.Fecha_F,
						IdServicio:this.servicios.IdServicio
					}
				}).then((res) => {

                    // if ( res.data.data.Fechas.length>1 && this.servicios.IdServicio>0)
                    // {
                    //     this.$toast.warning('No se puede seleccionar un rango mayor a 2 días');
                    //     this.servicios.Fecha_F = this.servicios.Fecha_I;
                    // }
                    // else
                    // {
                        this.servicios.FechasHoras =[];
                        this.LitaFechas = res.data.data.Fechas;
                        //Este atributo Fechas horas solo funciona para un dia de fecha si se quiere mas de una fecha eliminarlo y atras vez de fechas.Horas laborales ya regresa los valores que tiene
                        this.servicios.FechasHoras = res.data.data.Fechas;

                        if(parseInt(this.servicios.IdServicio) > 0) {


                            this.ObtenerTrabajadores();
                        }
                    // }
                });
            }
        },
        ValidarFechas(item,index) {
            if (item.HoraI !='' && item.HoraF !='')
            {
                var HoraI =item.HoraI.split(':');
                var HoraF =item.HoraF.split(':');

                if(parseInt(HoraI[0])==parseInt(HoraF[0]))
                {
                    //console.log('Pasa1');
                    if(parseInt(HoraI[1]) >= parseInt(HoraF[1]))
                    {
                        //console.log(parseInt(HoraI[1]) + parseInt(HoraF[1]));
                        //console.log(parseInt(HoraI[0]) +'-'+ parseInt(HoraF[0]));

                        if(parseInt(HoraI[0]) == 0 && parseInt(HoraF[0]) == 0){
                            this.$toast.warning('La hora final debe ser mayor que la inicial');
                            this.servicios.FechasHoras[index].HoraF="";
                            return false;
                        }

                        if(parseInt(HoraF[1]) == 0){
                            //return true;
                        }else{
                            this.$toast.warning('La hora final debe ser mayor que la inicial');
                            this.servicios.FechasHoras[index].HoraF="";
                            return false;
                        }
                    }
                }
                else
                {
                    //console.log('Pasa2');
                    if (parseInt(HoraI[0])>parseInt(HoraF[0]))
                    {
                        //console.log(parseInt(HoraI[1]) +'-'+ parseInt(HoraF[1]));
                        //console.log(parseInt(HoraI[0]) +'-'+ parseInt(HoraF[0]));

                        if(parseInt(HoraI[0]) > parseInt(HoraF[0])){
                            this.servicios.FechasHoras[index].HoraF="";
                            this.$toast.warning('La hora final debe ser mayor que la inicial');
                            return false;
                        }

                        if(parseInt(HoraF[1]) == 0){
                            //return true;
                        }else{

                            this.servicios.FechasHoras[index].HoraF="";
                            this.$toast.warning('La hora final debe ser mayor que la inicial');
                            return false;
                        }
                    }
                }

                this.ObtenerTrabajadores();

            }else{
                //console.log('Pasa aqui');
            }
        },
        async ObtenerTrabajadores()
        {
            await this.$http.post(
            'servicio/disponibles',
            {
                Fechas:this.servicios.FechasHoras,IdServicio:this.servicios.IdServicio
            },
            ).then( (res) => {

                this.Consultado.ListaTrabajadores=res.data.data.Trabajadores;
                this.Consultado.ListaVehiculos=res.data.data.Vehiculos;
                this.servicios.Trabajadores=res.data.data.TrabajadorOcupado;
                this.servicios.Vehiculos=res.data.data.VehiculosOCupado;
                this.servicios.IdVehiculo=res.data.data.IdVehiculo;

                if (this.servicios.Vehiculos[0].Vehiculo == '') {
                    this.Consultado.ListaVehiculos.splice(0,1);
                }else if(this.servicios.Vehiculos[0].Vehiculo == 'VIRTUAL'){
                    this.Consultado.ListaVehiculos=res.data.data.Vehiculos.filter(function(el){
                        return el.Vehiculo !="VIRTUAL"
                    });
                }



                // if (this.servicios.Vehiculos[0].Vehiculo == 'VIRTUAL') {
                //     this.Consultado.ListaVehiculos=res.data.data.Vehiculos.filter(function(el){
                //         return el.Vehiculo !="VIRTUAL"
                //     });
                // }else{
                //    this.Consultado.ListaVehiculos=res.data.data.Vehiculos;
                // }

            }).catch( err => {
                console.log(err.response.data.message);
            });
        },
        Limpiar()
        {
            this.Fecha='';
            this.Fecha2='';
            this.LitaFechas=[];
            this.ListaHoras=[];
            this.servicios.FechasHoras=[];
            this.servicios.Vehiculos=[];
			this.localBlocker = {
				BlockFecha: true,
				BlockHoraI: false,
				BlockHoraF: false,
                FechaAyer:''
			}
        },

		fechaMinima() {
			if(this.localBlocker.BlockFecha) {
				// console.log('return null');
				return null
			}else if(this.localBlocker.BlockFecha===null){
                // console.log('return ola');
                let fecha = moment(this.localBlocker.FechaAyer).add(1,'day').format('YYYY-MM-DD');
				fecha = new Date(fecha);
        
				return fecha;
				// return null
            }
            else{
				let fecha = moment(new Date()).add(1,'day').format('YYYY-MM-DD');
				fecha = new Date(fecha);
				// console.log(this.localBlocker.FechaAyer);
				return fecha;
			}
		},


    },
    created() {

        this.bus.$off('LimpiarCompoenets');
        this.bus.$on('LimpiarCompoenets',()=>
        {
            this.Limpiar();
        });



    },
	mounted() {

		if(this.servicios.IdServicio>0)
		{
			this.ListarFechas();
		}

	},
	computed: {
        timezone() {
            return this.timezones[this.timezoneIndex];
        },

		blockValidate() {
			if(this.pBloker !== undefined){
				this.localBlocker = this.pBloker;
			}
			return this.localBlocker;
		}
    },
}
</script>
