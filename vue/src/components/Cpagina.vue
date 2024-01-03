<template>
    <div>           
                  <!--<b-pagination   class="later-derecho mt-4 pagination justify-content-end" v-model="currentPage"  total-rows="TotalItems" perPage="1"  ></b-pagination>-->
            <b-pagination  class="later-derecho mt-3 pagination justify-content-end"  v-model="currentPage" :total-rows="validate" :perPage="Entrada"  size="sm"></b-pagination>
    </div>
</template>
<script>
import { filterEventStoreDefs } from '@fullcalendar/core';

export default {
    name:"Cpagina",
    props:['Entrada','TotalItems','Filtro'],
    data() {
        return {
            TotalPag:0,
            PrevioBloq:0,
            NextBloq:0,
            Contador:0,
            paraSig:0,
            Pagina:0,
            ListaPaginas:[],
            currentPage:1,
            FiltroP:{
                Entrada:10,
                TotalItem:'',
                Pagina:1,
            }
        }
    },
    methods: {
        
        pagination()
        {
            
            
        },
    },
    created() {
    },computed: {
        validate()
        {
              if (this.Filtro !=undefined)
              {
                if(this.Filtro.Entrada!=undefined){
                    this.FiltroP.Entrada=this.Filtro.Entrada;
                   
                }
                 if(this.Filtro.TotalItem!=undefined){
                    this.FiltroP.TotalItem=this.Filtro.TotalItem;
                }
                if(this.Filtro.Pagina!=undefined){
                    //this.currentPage=this.Filtro.Pagina;
                }
              }
            
            return this.FiltroP.TotalItem;
        }
       
    },watch: { currentPage(newPage) { 
        this.Filtro.Pagina=newPage;
        this.$emit('Pagina');
       
    }
    }
    
}
</script>