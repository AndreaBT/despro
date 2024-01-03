<template>
  <div>
    <div class="row mt-2">
      <div class="col-md-12 col-lg-12">
        <h4 class="titulo-04">OBSERVACIONES</h4>
      </div>
      <div class="col-md-12 col-lg-12">
        <hr>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col-md-6 col-lg-6">
        <label>Observaciones del técnico</label>
          <textarea v-model="servicios.Comentario" readonly class="form-control" rows="5"></textarea>
      </div>
      <div class="col-md-6 col-lg-6">
        <label>Observaciones finales</label>
          <textarea v-model="servicios.ComentarioFin" class="form-control" rows="5"></textarea>
      </div>
    </div>
    <!--End Observaciones-->

    <!--Costos-->
    <div class="row mt-2">
      <div class="col-md-12 col-lg-12">
        <h4>COSTOS</h4>
        <hr>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col-md-2 col-lg-2">
        <label>Equipos</label>
        <vue-numeric   :minus="false" class="form-control form-control-sm"  currency="$" separator="," :precision="2" v-model="servicios.EquiposD"></vue-numeric>
      </div>
      <div class="col-md-2 col-lg-2">
        <label>Materiales</label>
        <vue-numeric   :minus="false" class="form-control form-control-sm"  currency="$" separator="," :precision="2" v-model="servicios.MaterialesD"></vue-numeric>
      </div>
      <div class="col-md-2 col-lg-2">
        <label>Viaticos</label>
        <vue-numeric   :minus="false" class="form-control form-control-sm"  currency="$" separator="," :precision="2" v-model="servicios.ViaticosD"></vue-numeric>
      </div>
      <div class="col-md-2 col-lg-2">
        <label>Contratista</label>
        <vue-numeric   :minus="false" class="form-control form-control-sm"  currency="$" separator="," :precision="2" v-model="servicios.ContratistasD"></vue-numeric>
      </div>
      <div class="col-md-2 col-lg-2">
        <label>Costo Mano de Obra</label>
        <vue-numeric  disabled :minus="true" class="form-control form-control-sm"  currency="$" separator="," :precision="2" v-model="servicios.ManoObraT"></vue-numeric>
      </div>
      <div class="col-md-2 col-lg-2">
        <label>Burden</label>
        <vue-numeric  disabled  :minus="true" class="form-control form-control-sm"  currency="$" separator="," :precision="2" v-model="servicios.BurdenTotal"></vue-numeric>

      </div>
      <div class="col-md-2 col-lg-2">
        <label>Costo Vehiculos</label>
        <vue-numeric  disabled  :minus="true" class="form-control form-control-sm"  currency="$" separator="," :precision="2" v-model="servicios.CostoV"></vue-numeric>
      </div>
    </div>

  </div>
</template>
<script>
export default {
    name:'Costos',
    props:['servicios'],
    data() {
        return {
            monto:0,
            errorvalidacion:[]
        }
    },methods: {
        
     
    },created() {
        
    },
}
</script>