<template>
    <div>
        <div class="row justify-content-center">
                <div class="col-12 col-ms-12 col-md-12 col-lg-12">
                    <div class="col-12 col-ms-12 col-md-12 col-lg-12 form-group">
                        <br>
                        <label>Observaciones</label>
                        <textarea v-model="ListaFacturas.ComentarioAnulada" readonly  placeholder=" Coloque sus Observaciones" class="form-control" cols="2" rows="3"></textarea>
                    </div>
                    
                </div>
        </div>
      <!--  <h1>cambio que no necesito jsjsj- ANDREA</h1>-->
       <!-- <pre>{{
            ListaFacturas
            }}</pre>--->
    </div>
</template>

<script>
import Cvalidation from '@/components/Cvalidation.vue'

export default {
   name:'Cliente',
   props:['factura','poBtnSave'],
   data() {
       return {
           //ListaFacturas:[],
           ListaFacturas:{
               IdFactura:0,
               ComentarioAnulada:''
           },
            errorvalidacion:[],
            ComentarioAnulada:'',
       }
   },
    components:{
      Cvalidation
   },
   methods: {
        async Lista()
        {
            await this.$http.get(
                'factura/list',
                {
                    params:{AFacturar:this.AFacturar,Facturado:this.Facturado,FechaFacReal:this.FechaFacReal,TipoFiltro:this.TipoFiltro,Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina, RegEstatus:this.RegEstatus}
                }
            ).then((res) => {
                $('#Comentario').modal('hide');
                this.bus.$emit('List');
                this.ListaFacturas=res.data.data.prefacturas;
                this.Filtro.Entrada=res.data.data.pagination.PageSize;
                this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
                this.RutaFileOrg = res.data.RutaFileOrg;
            });

        },
   },
   created() {

       this.bus.$off('AbrirCom');

        //Este es para modal
        this.bus.$on('AbrirCom',(Id,Comentario)=> 
        {
            this.ListaFacturas.IdFactura=Id;
            this.ListaFacturas.ComentarioAnulada=Comentario;
            


         });

       this.bus.$off('Limpiar');
       this.bus.$on('Limpiar',()=>
       {
           this.errorvalidacion=[];
       }
       );
       
   },
}

</script>

