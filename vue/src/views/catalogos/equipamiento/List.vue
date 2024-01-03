<template>
    <div>  
        <Clist :regresar="false" :Nombre="NameList" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal">
            <template slot="header">
                
                   <tr >
                    <th>Nombre</th>
                    <th>Acciones</th>
                    </tr> 
                  
            </template>
             <template slot="body">
                   <tr v-for="(lista,key,index) in   ListaEquipamiento" :key="index" >
                       <td>{{lista.Nombre}}</td>
                    
                        <td>
                          <Cbtnaccion :isModal="EsModal" :Id="lista.IdEquipamiento" :IrA="FormName" >
                              <template slot="btnaccion">
                                <button type="button" title="Sucursales"  @click="IrConcepto(lista.IdEquipamiento)" class="btn btn-info btn-sm"> <span class="fa fa-cubes"></span></button>
                              </template>
                          </Cbtnaccion>     
                        </td>   
                   </tr>
                  
            </template>
          
        </Clist>
        
 <Modal @ejecutar="GuardarDesdeList"  :size="size" :Nombre="NameList" :poBtnSave="oBtnSave" >
        <template slot="Form">
        
          <Form :poBtnSave="oBtnSave" ></Form>
         
        </template>
        </Modal>
      
</div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';

import Form from '@/views/catalogos/equipamiento/Form.vue'

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
            NameList:"Configuración de equipo",
            urlApi:"equipamiento/get",
            ListaEquipamiento:[],
            ListaHeader:[],
            TotalPagina:2,
            Pag:0, oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
            },
        }
    },
    methods: {
        IrConcepto(Id){
            this.$router.push({name:'concepto', params:{IdEquipamiento:Id}})
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
                   
                    this.$swal({showConfirmButton: true,timer: 1000,title: 'Inoformación Eliminada'});
                     
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
                this.urlApi,
                {
                    params:{ RegEstatus:'A'}
                }
            ).then( (res) => {
              this.ListaEquipamiento =res.data.data.equipamiento;
            
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