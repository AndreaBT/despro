<template>
    <div>  

        <Clist :regresar="true" :Filtro="Filtro" :Nombre="NameList" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal">
            <template slot="header">
                <tr>
                    <th>#</th>
                    <th>Descripción</th>
                    <th>Clave</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </template>
            <template slot="body">
                <tr v-for="(lista,key,index) in ListaPermiso" :key="index">
                    <td>{{lista.IdPermiso}}</td>
                    <td>{{lista.Nombre}}</td>
                    <td>{{lista.Clave}}</td>
                    <td class="text-center">
                        <Cbtnaccion :isModal="EsModal" :Id="lista.IdPermiso" :IrA="FormName">
                            <template slot="btnaccion"></template>
                        </Cbtnaccion>     
                    </td>   
                </tr>
            </template>
        </Clist>
        
        <Modal :poBtnSave="oBtnSave" :size="size" :Nombre="NameList">
            <template slot="Form">         
                <Form :poBtnSave="oBtnSave"></Form>
            </template>
        </Modal>  
      
    </div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';
import Form from '@/views/catalogos/permiso/Form.vue'

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
            FormName:'usuariosForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"modal-md",
            NameList:"Permisos",
            urlApi:"permiso/get",
            ListaPermiso:[],
            ListaHeader:[],
            TotalPagina:2,
            Pag:0,
            oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
            },
             Filtro: {
                Nombre:'',
                Filas:10,
                Placeholder:'Nombre..',
                Show:false
            },
        }
    },
    methods: {
        async Lista() {
            await this.$http.get(this.urlApi, {
                   params:{Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina, RegEstatus:'A'}
                }
            ).then( (res) => {
                this.ListaPermiso =res.data.row;
            });
        }
    },
    created() {
        this.bus.$off('Delete');
        this.bus.$off('Lista2');
        this.bus.$off('Regresar');
        this.Lista();
        this.bus.$on('Delete',(Id)=>  {
            this.Eliminar(Id);
        });
         this.bus.$on('Lista2',()=> {
            this.Lista();
        });
         this.bus.$on('Regresar',()=> 
        {
           this.$router.push({name:'submenuadmon'});
            
        });
    }
}
</script>