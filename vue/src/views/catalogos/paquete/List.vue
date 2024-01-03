<template>
    <div>  

    <Clist :regresar="true"  @FiltrarC="Lista" :Filtro="Filtro" :Nombre="NameList"  :isModal="EsModal">
        <template slot="Filtros">
            <div>
                <div class="form-group ml-2">
                    <label>Estatus &nbsp;</label>
                    <select @change="Lista" v-model="Filtro.Estado"  class="form-control">
                        <option :value="''">Todos</option>
                        <option :value="'Abierta'">Abiertas</option>
                        <option :value="'Cerrado'">Cerradas</option>
                    </select>
                </div>
            </div>
        </template>
             <template slot="header">
                
                   <tr >
                        <th>Nombre</th>
                        <th>Clave</th>
   
                        <th>Acciones</th>
                    </tr> 
                  
            </template>
             <template  slot="body">
                   <tr v-for="(lista,key,index) in ListaPaquetes" :key="index" >
                       <td>{{lista.Nombre}}</td>
                        <td>{{lista.Clave}}</td>
               
                        <td>
                        
                          <Cbtnaccion  :isModal="EsModal" :Id="lista.IdPaquete" :IrA="FormName" >
                              <template    slot="btnaccion">
                                  
                              </template>
                          </Cbtnaccion>     
                        </td>   
                   </tr>
                  
            </template>
        </Clist>
    

        <Modal :size="size" :Nombre="FormName" :poBtnSave="oBtnSave" >
        <template slot="Form">
        
          <Form  :poBtnSave="oBtnSave"></Form>
         
        </template>
        </Modal>



      
</div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';

import Form from '@/views/catalogos/paquete/Form.vue'

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
            size :"modal-lg",
            NameList:"Registros",
            urlApi:"paquetegeneral/get",
            ListaPaquetes:[],
            ListaHeader:[],
              Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                 TotalItem:0,
                Pagina:1,
                Estado:'Abierta'
            }, oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
            },
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
                   
                    this.$toast.success('Información eliminada');
                     
                     this.$http.delete(
                            'cajachica/' + Id
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
                    params:{RegEstatus:'A'}
                }
            ).then( (res) => {
              this.ListaPaquetes=res.data.data.Paquetes;
              this.Filtro.Entrada=res.data.data.pagination.PageSize;
              this.Filtro.TotalItem=res.data.data.pagination.TotalItems;

              
            });
              
        },
      
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
            this.$router.push({name:'cajachica'});
            
        });
    }
}
</script>