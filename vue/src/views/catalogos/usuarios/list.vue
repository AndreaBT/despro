<template>
    <div>  
        <Clist :regresar="false" :Nombre="NameList" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal">
            <template slot="header">
                <th v-for="(lista,key,index) in ListaHeader" :key="index">
                    {{lista}}
                </th> 
            </template>
            <template slot="body">
                <tr v-for="(lista,key,index) in ListaUsuarios" :key="index" >
                    <td>{{lista.Nombre + " "+lista.Apellido}}</td>
                    <td>{{lista.Usuario}}</td>
                    <td>{{lista.Correo}}</td>
                    <td>{{lista.FechaNac}}</td>
                    <td>
                        <Cbtnaccion :isModal="EsModal" :Id="lista.IdEmpleado" :IrA="FormName" >
                            <template slot="btnaccion">
                            <button type="button" class="btn btn-info">Info</button>
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
import Form from '@/views/catalogos/usuarios/Form.vue'

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
            size :"modal-xl",
            NameList:"Lista Clientes",
            urlApi:"empleados/get.api?Nombre=Misael",
            ListaUsuarios:[],
            ListaHeader:[],
            TotalPagina:2,
            Pag:0,
            
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
                   
                    this.$swal({showConfirmButton: true,timer: 1000,title: 'Inoformación Eliminada'});
                     
                     this.$http.delete(
                            'empleados/' + Id
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
                    params:{Nombre:'Misael',Entrada:50,pag:0}
                }
            ).then( (res) => {
                this.ListaUsuarios =res.data.row;
                this.ListaHeader=res.data.headers;
                
                this.TotalPagina=res.data.TotalPag;
                this.Pag=res.data.Pagina; 
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