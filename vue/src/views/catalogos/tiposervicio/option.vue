<template >

 <select  class="form-control">
  <option :value="lista.IdTipoSer"  v-for="(lista,key,index) in ListaTipoServicio" :key="index">
      {{lista.Concepto }}
      </option>
   </select>

  
    
</template>

<script>
export default {
    name:'option',
    data(){
         return {
            urlApi:"tiposervicio/get",
            ListaTipoServicio:[]
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
              this.ListaTipoServicio =res.data.data.tiposervicio;
        
            });
              
        }

    },
    created(){
          this.Lista();
          
    }

}
</script>

