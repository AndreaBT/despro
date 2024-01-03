<template>
    <div>
        <div class="row justify-content-center">
                <div class="col-12 col-ms-12 col-md-12 col-lg-12">
                    <div class="col-12 col-ms-12 col-md-12 col-lg-12 form-group">
                        <br>
                        <label>Comentario</label>
                        <textarea readonly v-model="ListaPipe.Comentarios"  class="form-control" cols="2" rows="3"></textarea>
                    </div>
                    
                </div>
        </div>
     
    </div>
</template>

<script>
import Cvalidation from '@/components/Cvalidation.vue'

export default {
   name:'Cliente',
   props:['poBtnSave'],
   data() {
       return {
           //ListaFacturas:[],
           ListaPipe:{
               Comentarios:''
           },
            errorvalidacion:[],
            ComentarioAnulada:'',
       }
   },
    components:{
      Cvalidation
   },
   methods: {
        
   },
   created() {

       this.bus.$off('AbrirCom');

        //Este es para modal
        this.bus.$on('AbrirCom',(Comentario)=> 
        {
            this.ListaPipe.Comentarios=Comentario;
            


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