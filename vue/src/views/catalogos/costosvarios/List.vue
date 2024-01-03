<template>
    <div>  
        <Clist :regresar="true" :Nombre="NameList" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal">
            <template slot="header">
                
                   <tr >
                    <th>Concepto</th>
                    <th>Acciones</th>
                    </tr> 
                  
            </template>
             <template slot="body">
                   <tr v-for="(lista,key,index) in  ListaCostosVarios" :key="index" >
                       <td>{{lista.Concepto }}</td>
                    
                        <td>
                          <Cbtnaccion :isModal="EsModal" :Id="lista.IdCostosV" :IrA="FormName" >
                              <template slot="btnaccion">
                        
                              </template>
                          </Cbtnaccion>     
                        </td>   
                   </tr>
                  
            </template>
          
        </Clist>
        
        <Modal :size="size" :Nombre="NameList" >
        <template slot="Form">
        
          <Form ></Form>
         
        </template>
        </Modal>  
      
</div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';

import Form from '@/views/catalogos/costosvarios/Form.vue'

export default {
    name :'list',
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        Form
        
    },
    data() {
        return {
            FormName:'TipoUnidadForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"modal-dialog",
            NameList:"Costos",
            urlApi:"costosvarios/get",
            ListaCostosVarios:[],
            ListaHeader:[],
            TotalPagina:2,
            Pag:0
        }
    },
    methods: {
        Eliminar(Id)
        {
           
                    this.$swal({
                title: 'Esta seguro que desea eliminar este dato?',
                text: 'No se podra revertir esta acción',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No, mantener',
                showCloseButton: true,
                showLoaderOnConfirm: true
                }).then((result) => {
                if(result.value) {
                   
                    this.$toast.success('Información eliminada');
                     
                     this.$http.delete(
                            'costosvarios/' + Id
                        ).then( (res) => {
                                this.Lista();
                        });
                        
                    } 
                });
        },
       async Lista()
        {
            await this.$http.get(
                this.urlApi,
                {
                    params:{Concepto:'',Entrada:50,pag:0, RegEstatus:'A'}
                }
            ).then( (res) => {
                this.ListaCostosVarios =res.data.data.costosvarios;
            
                //this.TotalPagina=res.data.data.pagination.TotalItems;
                //this.Pag=res.data.data.pagination.CurrentPage;
                
            });
              
        }
    },
    created()
    {
        this.bus.$off('Delete');
        this.bus.$off('List');
        this.bus.$off('Regresar');
        this.Lista();
        
        this.bus.$on('Delete',(Id)=> 
        {
            this.Eliminar(Id);
        });

        this.bus.$on('List',()=> 
        {
            this.Lista();
        });

        this.bus.$on('Regresar',()=> 
        {
            this.$router.push({name:'submenuadmon'});
            
        });
    }
}
</script>