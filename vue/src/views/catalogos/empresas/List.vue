<template>
    <div>
        <Clist :regresar="false" :Nombre="NameList"  :Pag="Pag" :Total="TotalPagina" :isModal="EsModal">
            <template slot="header">
                   <th v-for="(lista,key,index) in ListaHeader" :key="index">
                    {{lista}}
                    </th>
            </template>
             <template slot="body">
                   <tr v-for="(lista,key,index) in ListaEmpresa" :key="index" >
                       <td>{{lista.Nombre }}</td>
                        <td>{{lista.Rfc}}</td>
                        <td>{{lista.Direccion}}</td>
                        <td>{{lista.Telefono}}</td>
                        <td>{{lista.Correo}}</td>
                        <td>{{lista.Ciudad}}</td>
                        <td>{{lista.Pais}}</td>
                        <td>
                          <Cbtnaccion :isModal="EsModal" :Id="lista.IdEmpresa" :IrA="FormName" >
                              <template slot="btnaccion">
                                <button type="button" @click="IrSucursal(lista.IdEmpresa)" class="btn btn-table">
                                    <span class="fa fa-home"></span>

                                </button>
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

import Form from '@/views/catalogos/empresas/Form.vue'

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
            NameList:"Empresas",
            urlApi:"empresa/get",
            ListaEmpresa:[],
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
                    params:{Nombre:'',Entrada:50,pag:0}
                }
            ).then( (res) => {
              this.ListaEmpresa =res.data.data.empresa;
               this.ListaHeader=res.data.data.headers;

                //this.TotalPagina=res.data.data.pagination.TotalItems;
                //this.Pag=res.data.data.pagination.CurrentPage;

            });

        },

        IrSucursal(Id){
            this.$router.push({name:'sucursal', params:{Id:Id,PShowButtons:true}})
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
