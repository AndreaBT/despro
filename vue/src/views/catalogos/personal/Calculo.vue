<template>
    <div >
        {{calculo}}
        <div class="form-group form-row justify-content-center ">
            <div class="col-8">
            <label>Sueldo Anual Integrado</label>
            <vue-numeric    :minus="false" class="form-control  "  currency="$" separator="," :precision="0" v-model="SueldoAnual"></vue-numeric>
            </div>
        </div>
        <div class="form-group form-row justify-content-center">
              <div class="col-8">
            <label>Semanas Productivas</label>
            <vue-numeric    :minus="false" class="form-control  "  currency="" separator="," :precision="0" v-model="SemanaProductiva"></vue-numeric>            
              </div>
        </div>
        <div class="form-group form-row justify-content-center">
              <div class="col-8">
            <label>Horas De Trabajo Semanal</label>
            <vue-numeric    :minus="false" class="form-control  reloj"  currency="" separator="," :precision="0" v-model="HTS"></vue-numeric>
            
              </div>
        </div>
        <div class="form-group form-row justify-content-center">
              <div class="col-8">
            <label>Horas Productivas Semanales</label>
            <vue-numeric    :minus="false" class="form-control  reloj"  currency="" separator="," :precision="0" v-model="HPS"></vue-numeric>
            
              </div>
        </div>
        <div class="form-group form-row justify-content-center">
              <div class="col-8">
            <label>Mano De Obra</label>
            <vue-numeric  disabled  :minus="false" class="form-control  reloj"  currency="" separator="," :precision="0" v-model="MO"></vue-numeric>
              </div>
        </div> 

       <div class="form-group form-row justify-content-center mt-4">
            <button type="button" @click="Save" class="btn btn-04 ban">Cerrar</button>
            <button type="button" @click="Save" class="btn btn-01">
                <i class="fa fa-plus-circle"></i> Guardar
                </button> 
       </div>
    </div>
</template>
<script>
export default {
    name:'Calculo',
    props:['Calculadora','trabajador'],
    data() {
        return {
            SueldoAnual:'',
            SemanaProductiva:'',
            HTS:'',
            HPS:'',
            MO:'',
        }
    },methods: {
        
       Save(){
           
              this.trabajador.HorasTS =this.HTS;
            this.trabajador.HorasPS=this.HPS;
            this.trabajador.CostoHora=this.MO;
            this.limpiar();
            $('#ModalCalculadora').modal('hide'); 
            document.body.classList.add("modal-open");
        },limpiar(){
            this.HPS='';
            this.MO='';
            this.HTS='';
            this.SueldoAnual='';
            this.SemanaProductiva='';
        }
    },created() {
       

        
    },computed: {
        
        calculo(){
            
            var SA=this.SueldoAnual;
            var HPS=this.HPS;
            if(SA==''){
                SA=0;
            }
            if(HPS==''){
                HPS=0;
            }

            if(SA<=0 || HPS<=0){
                this.MO=0;    
            }else{
                this.MO=((parseFloat(SA)/parseFloat(this.SemanaProductiva))/parseFloat(HPS)).toFixed(0);
            }

            //return this.MO;
        }
    },
}
</script>