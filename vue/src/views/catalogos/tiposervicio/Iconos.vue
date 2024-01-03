<template>
<div  class="row">
<div  class="card col-lg-1" :value="lista.IdIcono"  v-for="(lista,key,index) in ListaIconos" :key="index" style="width: 18rem;">
  <img class="card-img-top" :src="lista.Imagen" >
<div class="card-body">
    <input type="checkbox"/>
</div>
</div>
</div>
</template>
<script>
export default {
    name:'iconos',

    data(){
         return {
            urlApi:"iconos/get",
            ListaIconos:[]
        }

    },
    methods:{
   async Lista()
        {
            await this.$http.get(
                this.urlApi,
                {
                    params:{RegEstatus:'A'}
                }
            ).then( (res) => {
              this.ListaIconos=res.data.data.iconos;
        
            });
              
        }

    },
    created(){
          this.Lista();
          
    }

}
</script>
