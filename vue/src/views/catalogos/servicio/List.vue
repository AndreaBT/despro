<template>
    <div>  
        <Clist :regresar="false" :Nombre="NameList" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal">
            <template slot="header">
                   
                      <tr >
                    <th>Cliente</th>
                    <th>Folio</th>
                         <th>Estado</th>
                              <th>Acciones</th>
                    </tr> 
                  
                 
            </template>
             <template slot="body">
                   <tr v-for="(lista,key,index) in  ListaServicio" :key="index" >
                       <td>{{lista.Cliente}}</td>
                        <td>{{lista.Folio}}</td>
                        <td>{{lista.EstadoS}}</td>
                      
                        <td>
                          <Cbtnaccion :isModal="EsModal" :Id="lista.IdServicio" :IrA="FormName" >
                              <template slot="btnaccion">
                 
                              </template>
                          </Cbtnaccion>     
                        </td>   
                   </tr>
                  
            </template>
          
        </Clist>
        
        <Modal @ejecutar="GuardarDesdeList"  :size="size" :Nombre="NameList" >
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

import Form from '@/views/catalogos/servicio/Form.vue'

export default {
    name :'list',
      props:['IdServicio'],
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        Form
          
    },
    data() {
        return {
            FormName:'usuariosForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"modal-lg",
            NameList:"Servicio",
            urlApi:"servicio/get",
            ListaServicio:[],
            ListaHeader:[],
            TotalPagina:2,
            Pag:0
        }
    },
    methods: {
        GuardarDesdeList()
        {

        },
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
                   
                    this.$swal({showConfirmButton: true,timer: 1000,title: 'Información Eliminada'});
                     
                     this.$http.delete(
                            'servicio/' + Id
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
                    params:{Nombre:'',Entrada:50,pag:0, RegEstatus:'A'}
                }
            ).then( (res) => {
              this.ListaServicio=res.data.data.servicio;
            

                //this.TotalPagina=res.data.data.pagination.TotalItems;
                //this.Pag=res.data.data.pagination.CurrentPage;
                
            });
              
        }
    },
    created()
    {
         this.bus.$off('Delete');
          this.bus.$off('List');
         this.Lista();
  
          this.bus.$on('Delete',(Id)=> 
        {
            this.Eliminar(Id);
            
        });
         this.bus.$on('List',()=> 
        {
            this.Lista();
            
        });
    }
}
</script>