<template>
    <div class="row">
        <h1 class="Centrar" v-if="this.Validador==1">No contiene Imágenes</h1>
        <div class="col-md-4 col-lg-3 mb-2" v-for="(item , index) in Lista" :key="index">
            <div class="ajustar">
                <img  :src="ruta+item.Imagen2" alt="..." class="img-thumbnail img-fluid">
            </div>
        </div>
    </div>
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from '@/components/Cbtnsave.vue'
import Cvalidation from '@/components/Cvalidation.vue'
export default {
    name:'Form',
    props:[''],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'caja chica',//Sirve para donde va regresar
            Lista:[],
            IdConcepto:0,
            url:"gastoxtrabajador/listevidencia",
            errorvalidacion:[],
            ruta:'',
            Validador:0
        }
        
    },
    components:{
        Cbtnsave,Cvalidation
    },
    methods :
    {
        listafotos(){
            this.$http.get(
                this.url,
                {
                    params:{IdConcepto:this.IdConcepto}
                }
            ).then( (res) => {
              this.Lista=res.data.data.gastos;
              if (this.Lista.lenght>0 ||this.Lista =='' ) {
                  this.Validador=1
              }else{
                  this.Validador=2
              }
              this.ruta=res.data.data.ruta;
        
            });                                      
        },
    },
    created() {
        this.bus.$off('AbrirFotos');
        //Este es para modal
        this.bus.$on('AbrirFotos',(Id)=> 
        {
            this.IdConcepto=Id;
            this.listafotos(); 
        });
    }

}
</script>
<style>
    .Centrar{
        margin-left: 35%;
    }
</style>