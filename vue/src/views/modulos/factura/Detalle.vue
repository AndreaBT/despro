<template>
<div>
    {{Totales}}

    <div class="row mt-4">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <h4 class="titulo-04 color-02">Detalles de Factura</h4>
            <hr class="hr">
        </div>
    </div>

    <div class="form-group form-row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 text-right">
            <button  @click="AgregarItem" type="button" class="btn btn-01 add mb-2 mt-1">Agregar</button>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <table class="table-01">
                <thead>
                    <tr>
                        <th class="tw-1 text-center">Eliminar</th>
                        <th>Descripción</th>
                        <th class="tw-1">Cantidad</th>
                        <th class="tw-3">Costo uni.</th>
                        <th class="tw-3">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,index) in objTemp.ListaDetalle" :key="index">
                        <td class="text-center">
                            <button v-b-tooltip.hover title="Eliminar" @click="Quitar(index,item.IdFactura)" class="btn btn-table-da pl-01 mr-1" type="button">
                                    <i class="fas fa-times fa-fw-m"></i>
                            </button>
                        </td>
                        <td>
                            <textarea v-model="item.Descripcion" class="form-control form-control-sm" rows="1" placeholder="Descripción"></textarea>
                        </td>
                        <td>
                            <vue-numeric    :minus="false" :precision="2" class="form-control form-control-sm "  currency="" separator=","  v-model="item.Cantidad"></vue-numeric>
                        </td>
                        <td>
                            <vue-numeric    :minus="false" :precision="2" class="form-control form-control-sm "  currency="$" separator=","  v-model="item.CostoUni"></vue-numeric>
                        </td>
                        <td>
                            <vue-numeric  disabled  :minus="false" :precision="2" class="form-control form-control-sm "  currency="$" separator=","  v-model="item.Total"></vue-numeric>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right"><b>Total Final</b></td>
                        <td>
                            <vue-numeric  disabled  :minus="false" :precision="2" class="form-control form-control-sm "  currency="$" separator=","  v-model="factura.Total"></vue-numeric>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
</template>
<script>
import Cvalidation from '@/components/Cvalidation.vue'

export default {
   name:'Cliente',
   props:['objTemp','factura'],
   components:{
      Cvalidation
   },
   data() {
       return {
           
       }
   },methods: {
       AgregarItem()
       {
           this.objTemp.ListaDetalle.push({IdFactura:0,Descripcion:'',Cantidad:0,CostoUni:0,Total:0});
       },
         Quitar (index,IdFactura)
     {
          this.objTemp.ListaDetalle.splice(index, 1); 
     }
   },
   computed: {
       
       Totales()
       {
           let Total=0;
           for ( var i=0;i<this.objTemp.ListaDetalle.length;i++)
           {
               let importe=0;
               if (this.objTemp.ListaDetalle[i].Cantidad!='' && this.objTemp.ListaDetalle[i].CostoUni)
               {
                importe = this.objTemp.ListaDetalle[i].Cantidad *this.objTemp.ListaDetalle[i].CostoUni;
               }
                this.objTemp.ListaDetalle[i].Total=importe;
                Total +=importe;
           }
           this.factura.Total=Total;
           //return Total;
       }
    
   },
}
</script>