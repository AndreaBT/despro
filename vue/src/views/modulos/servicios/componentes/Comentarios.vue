<template>
  <div >
  <div class="row mt-2">
              <div class="col-md-6 col-lg-6">
                <label> Tareas</label>
                <textarea v-model="servicios.Observaciones" class="form-control" rows="5"></textarea>
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="errorvalidacion.Tareas" :Mensaje="errorvalidacion.Tareas[0]"></Cvalidation></label>
              </div>
              <div class="col-md-6 col-lg-6">
                <label>Materiales</label>
                <textarea v-model="servicios.Materiales" class="form-control" rows="5"></textarea>
              </div>
            </div>   
        
    </div>
</template>

<script>
import Cvalidation from '@/components/Cvalidation.vue'
export default {
    name:'Comentarios',
    props:['servicios','errorvalidacion'],
    components:{
        Cvalidation
    },
    data() {
        return {
            
        }
    },
}
</script>