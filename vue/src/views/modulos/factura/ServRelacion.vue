<template>
<div>

  <div class="row mt-2">
      <div class="col-12 col-sm-12 col-md-12 col-lg-12">
          <h4 class="titulo-04 color-02">Servicios Relacionados</h4>
          <hr class="hr">
      </div>
  </div>

  <div class="form-group form-row justify-content-center">
    <div class="col-12 col-sm-12 col-md-9 col-lg-9 text-right">
        <button type="button"  data-toggle="modal" data-target="#ModalForm"  data-backdrop="static" class="btn btn-01 search mb-2 mt-1">Buscar Servicio</button>
    </div>
    <div class="col-12 col-sm-12 col-md-9 col-lg-9">
        <table class="table-outline">
            <thead>
                <tr>
                    <th class="text-center tw-2">Eliminar</th>
                    <th>Folio</th>
                    <th>Comentario</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item,index) in objTemp.ListaServ" :key="index">
                   <td class="text-center tw-2">
                      <button v-b-tooltip.hover title="Eliminar" @click="Quitar(index,item.IdFactura)" v-if="index>0" class="btn btn-table-da pl-01 mr-1" type="button">
                        <i class="fas fa-times fa-fw-m"></i>  
                      </button>  
                    </td>
                    <td>
                        <b class="color-01">{{item.Folio}}</b>
                    </td>
                    <td>
                       {{item.Descripcion}}
                    </td>
                </tr>
            </tbody>
        </table>
      </div>
    </div>

  </div>
</template>
<script>
import Cvalidation from '@/components/Cvalidation.vue'

export default {
   name:'Cliente',
   props:['objTemp'],
   components:{
      Cvalidation
   },
   data() {
       return {
           
       }
   },methods: {
     Quitar (index,IdFactura)
     {
          this.objTemp.ListaServ.splice(index, 1);
     }
    
   },
}
</script>