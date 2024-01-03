<template>
    <div>
        <div class="row mt-4">
            <div class="col-md-12 col-lg-12">
                <h4>Levantamiento</h4>
                <hr>
            </div>
        </div>

        <div v-if="servicios.IdConfigS == 6" class="row mt-2">

            <div class="col-md-2 col-lg-2">
                <label>Num. Personal</label>
                <input type="number" name="" id="" disabled class="form-control form-control-sm" v-model="servicios.NumPersonal">
            </div>

            <div class="col-md-2 col-lg-2">
                <label>Num. Vehiculos</label>
                <input type="number" name="" id="" disabled class="form-control form-control-sm" v-model="servicios.NumVehiculos">
            </div>

            <div class="col-md-2 col-lg-2">
                <label>Tiempo Acceso</label>
                <input type="number" name="" id="" disabled class="form-control form-control-sm" v-model="servicios.TiempoAcceso">
            </div>

            <div class="col-md-2 col-lg-2">
                <label>Tiempo Salida</label>
                <input type="number" name="" id="" disabled class="form-control form-control-sm" v-model="servicios.TiempoSalida">
            </div>

            <div class="col-md-2 col-lg-2">
                <label>Prueba Exam. y Médicos</label>
                <input type="number" name="" id="" disabled class="form-control form-control-sm" v-model="servicios.TiempoEM">
            </div>

            <div class="col-md-2 col-lg-2">
                <label>Tiempo de capacitación</label>
                <input type="number" name="" id="" disabled class="form-control form-control-sm" v-model="servicios.TiempoCapacitacion">
            </div>

        </div>

    </div>
</template>

<script>
export default {
    props:['servicios'],
    data() {
        return {
            horaslaborales:[],

            HoraI:'',
            HoraF:''
        }
    },

    methods: {
        ValidarFechas(item,index)
        {   
            //this.servicios.TiempoLevantamientoF = '08:30';

            if (item.TiempoLevantamiento !='' && item.TiempoLevantamientoF !='')
            {
                var HoraI =item.TiempoLevantamiento.split(':');
                var HoraF =item.TiempoLevantamientoF.split(':');
            
                if (parseInt(HoraI[0])==parseInt(HoraF[0]))
                {
                //console.log('Pasa1');   
                    if (parseInt(HoraI[1]) >= parseInt(HoraF[1]))
                    {

                        //console.log(parseInt(HoraI[1]) + parseInt(HoraF[1]));
                        //console.log(parseInt(HoraI[0]) +'-'+ parseInt(HoraF[0]));

                        if(parseInt(HoraI[0]) == 0 && parseInt(HoraF[0]) == 0){
                            this.$toast.warning('La hora final del levantamiento debe ser mayor que la inicial');
                            this.servicios.TiempoLevantamientoF="";
                            return false;
                        }

                        if(parseInt(HoraF[1]) == 0){
                        //return true;
                        }else{
                            this.$toast.warning('La hora final del levantamiento debe ser mayor que la inicial');
                            this.servicios.TiempoLevantamientoF="";
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

                        this.servicios.TiempoLevantamientoF="";
                        this.$toast.warning('La hora final del levantamiento debe ser mayor que la inicial');
                        return false;
                    
                    }

                        if(parseInt(HoraF[1]) == 0){
                            //return true;
                        }     
                        else
                        {

                            this.servicios.TiempoLevantamientoF="";
                            this.$toast.warning('La hora final del levantamiento debe ser mayor que la inicial');
                            return false;
                        }
                    }
                }
              
                
            } 
            else
            {
                console.log('Pasa aqui');
            }
        },

        
        async ListaHoras()
        {
            await this.$http.get(
                'HorasDisponibles/get',
                {
                    params:{Nombre:'',Entrada:50,pag:0, RegEstatus:'A'}
                }
            ).then( (res) => {

                //console.log(res.data.data.horaslaborales);
                //this.horaslaborales =res.data.data.horaslaborales;

                res.data.data.horaslaborales.forEach(element => {
                    let si = element.hora;

                    if(parseInt(element.hora) < 10){

                        si = '0'+element.hora;
                    }



                   this.horaslaborales.push( { "hora": si,"horan": element.hora, "class": "" });
                });

            });
              
        },
    },
    
    mounted() {

    },

    created() {

        this.ListaHoras();

        this.bus.$off('LimpiarCompoenetsL');
        this.bus.$on('LimpiarCompoenetsL',()=>
        {
            this.ListaHoras();

        });
    },
}
</script>