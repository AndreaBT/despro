<template>
    <div>  

    <Clist :regresar="true" @FiltrarC="Lista" :Filtro="Filtro"  :Nombre="NameList" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal">
            <template slot="header">
                
                   <tr >
                        <th>Nombre</th>
                        <th>Valor</th>
                        <th>Mes</th>
                        <th>Foto</th>
    
                        <th>Acciones</th>
                    </tr> 
                  
            </template>
             <template  slot="body">
                   <tr v-for="(lista,key,index) in ListaConceptos" :key="index" >
                       <td>{{lista.Nombre }} </td>
                       <td>{{lista.Valor }} </td>
                       <td>{{lista.Meses }} </td>
                       <td>
                           <img :src="Ruta + lista.Foto" >
                        </td>
               
                        <td>
                        
                          <Cbtnaccion  :isModal="EsModal" :Id="lista.IdConcepto" :IrA="FormName" >
                              <template slot="btnaccion">
                              </template>
                          </Cbtnaccion>     
                        </td>   
                   </tr>
                  
            </template>
        </Clist>
        
        <Modal   :size="size" :Nombre="NameList" :poBtnSave="oBtnSave"  >
        <template slot="Form" >
            <Form :IdEquipamiento="IdEquipamiento2" :poBtnSave="oBtnSave" >
            </Form>
        </template>
        </Modal>  



      
</div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';

import Form from '@/views/catalogos/Root/Conceptos/Form.vue'

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
            FormName:'folioForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"None",
            NameList:"Conceptos",
            ListaConceptos:[],
            TotalPagina:2,
            IdEquipamiento2:0,
            Pag:0,
             Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                 TotalItem:0,
                Pagina:1
            },
            Ruta:'' 
            ,oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
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
                'concepto/get',
                {
                    params:{IdEquipamiento:this.IdEquipamiento2 ,Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina, RegEstatus:'A'}
                }
            ).then( (res) => {
              this.ListaConceptos=res.data.data.concepto;
              this.Filtro.Entrada=res.data.data.pagination.PageSize;
              this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
              this.Ruta=res.data.data.ruta;
                
            });
              
        }
    },
    created()
    {
         this.bus.$off('Delete');
         this.bus.$off('List');
         this.bus.$off('Regresar');
        if (this.IdEquipamiento!=undefined)
        {
         sessionStorage.setItem('IdSaved', this.IdEquipamiento);
        }
        this.IdEquipamiento2= sessionStorage.getItem('IdSaved');
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
            this.$router.push({name:'configequipamiento'});
            
        });
    }
}
</script>