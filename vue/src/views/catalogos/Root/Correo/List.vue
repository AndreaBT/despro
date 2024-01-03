<template>
    <div>  

    <Clist :pShowBtnAdd="false"  :regresar="true" @FiltrarC="Lista" :Filtro="Filtro"  :Nombre="NameList" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal">
            <template slot="header">
                
                   <tr >
                        <th>Titulo</th>
    
                        <th>Acciones</th>
                    </tr> 
                  
            </template>
             <template  slot="body">
                   <tr v-for="(lista,key,index) in ListaCorreo" :key="index" >
                       <td>{{lista.Titulo }} </td>

               
                        <td>
                        
                          <Cbtnaccion  :isModal="EsModal" :Id="lista.IdCorreo" :IrA="FormName" >
                              <template slot="btnaccion">
                                <!--<button type="button" @click="IrConceptos(lista.IdEquipamiento)" class="btn btn-table">
                                  <i class="fas fa-clipboard-list"></i>

                                </button>-->
                              </template>
                          </Cbtnaccion>     
                        </td>   
                   </tr>
                  
            </template>
        </Clist>
        
        <Modal   :size="size" :Nombre="NameList"  :poBtnSave="oBtnSave">
        <template slot="Form" >
            <Form  :poBtnSave="oBtnSave">
            </Form>
        </template>
        </Modal>  



      
</div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';

import Form from '@/views/catalogos/Root/Correo/Form.vue'

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
            FormName:'folioForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"None",
            NameList:"Correo",
            ListaCorreo:[],
            TotalPagina:2,
            Pag:0,
             Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                 TotalItem:0,
                Pagina:1
            },oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
            },
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
                            'equipamiento/' + Id
                        ).then( (res) => {
                                this.Lista();
                        });
                        
                    } 
                });
        },
       async Lista()
        {
            await this.$http.get(
                'correo/get',
                {
                    params:{Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina, RegEstatus:'A'}
                }
            ).then( (res) => {
              this.ListaCorreo=res.data.data.correo;
              this.Filtro.Entrada=res.data.data.pagination.PageSize;
              this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
                
            });
              
        },
        IrConceptos(Id)
        {
            this.$router.push({name:'configconceptos',params:{IdEquipamiento:Id}});  
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
            this.$router.push({name:'MenusRoot'});
            
        });
    }
}
</script>