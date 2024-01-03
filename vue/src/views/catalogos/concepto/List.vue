<template>
    <div>  
        <Clist :regresar="false" :Nombre="NameList" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal">
            <template slot="header">
                
                   <tr >
                           <th>Foto</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                    </tr> 
                  
            </template>
             <template slot="body">
                   <tr v-for="(lista,key,index) in ListaConcepto" :key="index" >
                       <td>
                           <img :src="lista.Foto"  style="width:50px;height:50px">

                           
                         </td>
                       <td>{{lista.Nombre}}</td>
                    
                        <td>
                          <Cbtnaccion :isModal="EsModal" :Id="lista.IdConcepto" :IrA="FormName" >
                              <template slot="btnaccion">
                           
                              </template>
                          </Cbtnaccion>     
                        </td>   
                   </tr>
                  
            </template>
          
        </Clist>
        
 <Modal @ejecutar="GuardarDesdeList"  :size="size" :Nombre="NameList" >
        <template slot="Form">
        
          <Form :IdEquipamiento="IdEquipamiento"></Form>
         
        </template>
        </Modal>
      
</div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';

import Form from '@/views/catalogos/concepto/Form.vue'

export default {
    name :'list',
    props:['IdEquipamiento'],
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
            size :"modal-lg",
            NameList:"Conceptos",
            urlApi:"concepto/get",
            ListaConcepto:[],
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
                   
                    this.$swal({showConfirmButton: true,timer: 1000,title: 'Inoformación Eliminada'});
                     
                     this.$http.delete(
                            'concepto/' + Id
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
                    params:{ RegEstatus:'A', IdEquipamiento:this.IdEquipamiento}
                }
            ).then( (res) => {
              this.ListaConcepto =res.data.data.concepto;
            
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