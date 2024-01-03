<template>
<div>

      <div class="row">
          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
              <h4 class="titulo-04 color-02">Datos del Cliente</h4>
              <hr class="hr">
          </div>
      </div>
      <div class="form-group form-row">
          <div class="col-md-2 col-lg-2">
              <label>Folio</label>
              <input v-model="servicios.Folio" type="text" class="form-control" readonly="" placeholder="No. de Folio">
          </div>
          <div class="col-md-5 col-lg-3">
              <label>Cliente</label>
              <input  v-model="servicios.Client" type="text" class="form-control" readonly="" placeholder="Nombre del Cliente">
          </div>
          <div class="col-md-5 col-lg-3">
              <label>Sucursal</label>
              <input  v-model="servicios.Sucursal" type="text" class="form-control" readonly="" placeholder="Nombre de la Sucursal">
          </div>
          <div class="col-md-7 col-lg-4">
              <label>Dirección</label>
              <textarea v-model="servicios.DireccionCS"  class="form-control" rows="1" readonly="" placeholder="Dirección"></textarea>
          </div>
      </div>
      <div class="form-group form-row">
          <div class="col-md-2 col-lg-2">
              <label>No. Contrato</label>
              <input v-model="servicios.NumContrato" type="text" class="form-control" readonly="" placeholder="Número de Contrato">
          </div>
          <div class="col-md-6 col-lg-4">
              <label>Contacto</label>
              <input v-model="servicios.Contacto" type="text" class="form-control" readonly="" placeholder="Nombre del Contacto">
          </div>
          <div class="col-md-4 col-lg-3">
              <label>E-mail</label>
              <input v-model="servicios.CorreoCS" type="text" class="form-control" readonly placeholder="ejemplo@email.com">
          </div>
          <div class="col-md-4 col-lg-3">
              <label>Teléfono</label>
              <input v-model="servicios.TelCS" type="text" class="form-control" readonly placeholder="+00 0000 000 000"  >
          </div>
      </div>
      <div class="form-group form-row">
          <div class="col-md-6 col-lg-6">
              <label>Datos de Facturación</label>
              <textarea  v-model="servicios.Dfac" class="form-control" readonly=""></textarea>
          </div>
          <div class="col-md-6 col-lg-6">
              <label>Comentarios del Contrato</label>
              <textarea    v-model="servicios.ComentarioNC" readonly="" class="form-control" placeholder="Comentario..."></textarea>
          </div>
      </div><!--Fin datos del ciente-->

</div>
</template>
<script>
import Cvalidation from '@/components/Cvalidation.vue'

export default {
   name:'Cliente',
   props:['servicios'],
   components:{
      Cvalidation
   },
   data() {
       return {
           
       }
   },methods: {
      
   },
}
</script>