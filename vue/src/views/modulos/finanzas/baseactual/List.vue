<template>
    <div>  
        <CHead :oHead="Head"></CHead>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <Clist :pShowBtnAdd="false" :regresar="true"  @FiltrarC="Lista" :Filtro="Filtro" :ShowHead="false"  :Nombre="NameList"  :isModal="EsModal">
                    <template slot="header">
                        <tr>
                            <th>Nombre</th>
                            <th class=" text-center">Acciones</th>
                        </tr> 
                    </template>
                    <template  slot="body">
                        <tr v-for="(lista,key,index) in Listabase" :key="index" >
                            <td>{{lista.Nombre }} </td>
                                <td class="text-center">
                                    <button title="Editar" @click="OpenModal(lista.IdConfigS)" type="button" data-toggle="modal" data-target="#ModalForm" data-backdrop="static" data-keyboard="false" class="btn btn-table"><i class="fas fa-pencil-alt fa-fw-m"></i></button>
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
        </div>
    </div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';

import Form from '@/views/modulos/finanzas/baseactual/Form.vue'

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
            FormName:'Base actual',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"None",
            NameList:"Base actual",
            Listabase:[],
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
             Head: {
				ShowHead: true,
				Title: "Base actual",
				BtnNewShow: false,
				BtnNewName: "Nuevo",
				isreturn: true,
				isModal: true,
				isEmit: true,
				Url: "",
				ObjReturn: "",
				NameReturn: "Regresar",
				isCuentas: false,
				verFiltroCuentas: false
			}
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
                            'caja/' + Id
                        ).then( (res) => {
                                this.Lista();
                        });
                        
                    } 
                });
        },
       async Lista()
        {
            await this.$http.get(
                'baseactual/get',
                {
                    params:{Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina, RegEstatus:'A',Facturable:'S'}
                }
            ).then( (res) => {
              this.Listabase=res.data.data.lista;
              this.Filtro.Entrada=res.data.data.pagination.PageSize;
              this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
                
            });
              
        },
        OpenModal(IdConfigS)
        {
            this.bus.$emit('AbrirBase',IdConfigS);
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
         this.bus.$off('Regresar');
        this.bus.$on('Regresar',()=> 
        {
            this.$router.push({name:'SubMenusFinanzas'});
            
        });
    }
}
</script>