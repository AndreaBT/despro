<template>
    <div>
        <section class="container-fluid mt-2">
           <Menu :pSitio="NombreSeccion">
                <template slot="BtnInicio">
                    <button type="button" data-toggle="modal" data-target="#ModalForm"  data-backdrop="static" data-keyboard="false" class="btn btn-01 mr-2" @click="Nuevo()">Nuevo</button>
                </template>
            </Menu>
            <div class="row"> 
                <div class="col-md-12 col-lg-12 col-xl-12 mt-2">
                   <Clist :regresar="true" :ShowHead="false" @FiltrarC="Lista" :Filtro="Filtro" :Nombre="NameList" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal">
                    <template slot="header">
                        <tr>
                            <th>Nombre</th>
                            <th>Sucursal</th>
                            <th>Estado</th>
                            <th>Vendedor asignado</th>
                            <th>Fecha registro</th>
                            <th>Acciones</th>
                        </tr> 
                    </template>

                    <template slot="body">
                        <tr v-for="(lista,key,index) in  ListaOportunidades" :key="index" >
                            <td>{{lista.Nombre}}</td>
                            <td>{{lista.Sucursal}}</td>
                            <td>{{lista.Estado}}</td>
                            <td>{{lista.Vendedor}}</td>
                            <td>{{lista.FechaReg}}</td>
                            <td>
                                <Cbtnaccion  :isModal="EsModal" :Id="lista.IdOportunidad" :IrA="FormName" >
                                    <template slot="btnaccion">
                                
                                    </template>
                                </Cbtnaccion>     
                            </td>   
                        </tr>
                    </template>
                </Clist>

                <Modal :size="size" :Nombre="NameList" :poBtnSave="oBtnSave"  >
                    <template slot="Form">
                        <Form @Listar="ListaCliente" :poBtnSave="oBtnSave"></Form>
                    </template>
                </Modal>

                    <Ccliente :TipoModal='2'></Ccliente>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';
import Ccliente from '@/components/Ccliente.vue';
import Form from '../oportunidad/form.vue';
import Menu from "../indexMenu.vue";

export default {
    name :'list',
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        Form,
        Ccliente,
        Menu,
    },
    data() {
        return {
            NombreSeccion: 'Oportunidades',
            FormName:'TipoUnidadForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"modal-lg",
            NameList:"Oportunidad",
            urlApi:"crmoportunidad/list",
            ListaOportunidades:[],
            ListaHeader:[],
            TotalPagina:2,
            Pag:0,
              Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                TotalItem:0,
                Pagina:1,
                Entrada: 10
            },
            oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
            },
        }
    },
    methods: {
        Nuevo()
        {
            if (this.oBtnSave.isModal==true)
            { 
                this.bus.$emit('Nuevo',true);
            }
            else{
                this.bus.$emit('Nuevo');
            }
        },
        async ListaCliente()
        {
            this.bus.$emit('ListCcliente');  
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
                    this.$http.delete(
                        'crmoportunidad/' + Id
                    ).then( (res) => {
                        this.$toast.success('Información eliminada');
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
                    params:{Nombre:this.Filtro.Nombre,RegEstatus:'A',Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina}
                }
            ).then( (res) => {
                this. ListaOportunidades =res.data.data.oportunidades;
                this.Filtro.Entrada=res.data.data.pagination.PageSize;
                this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
            });
        },
        SeleccionarCliente(objeto)
        {
            this.oclientesuc=objeto;
            if (this.oclientesuc.Correo!='')
            {
                this.servicios.Para.push({ "text": this.oclientesuc.Correo});  
            }
            let distacia=0;
            if(objeto.DistanciaAprox !='')
            {
                distacia=objeto.DistanciaAprox;
            }
            this.servicios.IdCliente=objeto.IdCliente;
            this.servicios.IdClienteS=objeto.IdClienteS;
            this.servicios.Cliente=objeto.Nombre;
            this.servicios.Direccion=objeto.Direccion;
            this.servicios.Distancia=distacia;
            this.servicios.Velocidad=0;
            this.ListaNumContrato();
        },
    },
    created()
    {   
        this.bus.$off('SeleccionarCliente');

        this.bus.$on('SeleccionarCliente',(oSucursal)=>
        {
            //this.SeleccionarCliente(oSucursal);
            alert(JSON.stringify(oSucursal));
        });
 
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
            this.$router.push({name:'submenucrm'});
        });
    }
}
</script>