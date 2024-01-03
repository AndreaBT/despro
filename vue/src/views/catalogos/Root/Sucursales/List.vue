<template>
    <div>
        <Clist @FiltrarC="Lista" :Filtro="Filtro" :PNameButonNuevo="NameButonNuevo"
			   :regresar="ShowButtons2" :Nombre="NameList" :isModal="EsModal" :pConfigLoad="ConfigLoad">
            <template slot="header">
                <tr>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Correo</th>
                    <th>Estado</th>
                    <th>Código postal</th>
                    <th>Acciones</th>
                </tr>
            </template>
            <template  slot="body">
                <tr v-for="(lista,key,index) in ListaSucursal" :key="index" >
                    <td>{{lista.Nombre }}</td>
                    <td>{{lista.Telefono }}</td>
                    <td>{{lista.Direccion}}</td>
                    <td>{{lista.Correo}}</td>
                    <td>{{lista.Estado }}</td>
                    <td>{{lista.CP }}</td>
                    <td>
                        <Cbtnaccion v-if="ShowButtons2" :isModal="EsModal" :Id="lista.IdSucursal" :IrA="FormName" >
                            <template    slot="btnaccion">
                                <button type="button" @click="paquetes(lista.IdSucursal)" v-b-tooltip.hover.right title="Paquetes" data-toggle="modal" data-target="#ModalP"  class="btn-icon mr-2"> <span class="fa fa-box"></span> </button>

                                <button type="button" @click="Users(lista.IdSucursal)" v-b-tooltip.hover.right title="Usuarios"  class="btn-icon mr-2">
                                    <i class="fad fa-users-cog"></i>
                                </button>
                            </template>
                        </Cbtnaccion>
                    </td>
                </tr>
				<CSinRegistros :pContIF="ListaSucursal.length" :pColspan="7" ></CSinRegistros>
            </template>
        </Clist>

        <Modal :size="size" :Nombre="NameList" :poBtnSave="oBtnSave">
            <template slot="Form" >
                <Form :IdEmpresa="IdEmpresa" :Empresa="Empresa"    :poBtnSave="oBtnSave"></Form>
            </template>
        </Modal>

        <Modal :size="'modal-dialog'" :NameModal="'ModalP'" :Nombre="'Paquetes'" :poBtnSave="oBtnSave2" >
            <template slot="Form" >
                <ModalPaquetes  :poBtnSave="oBtnSave2"></ModalPaquetes>
            </template>
        </Modal>
    </div>
</template>
<script>

import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';
import ModalPaquetes from '@/views/catalogos/Root/Sucursales/Paquetes.vue'
import Form from '@/views/catalogos/Root/Sucursales/Form.vue'
import FormEmpresa from '@/views/catalogos/Root/Empresa/Form.vue'
import CSinRegistros from "../../../../components/CSinRegistros";

export default {
    props:['Id','PShowButtons','item'],
    name :'list',
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        Form,
        ModalPaquetes,
        FormEmpresa,
		CSinRegistros
    },
    data() {
        return {
            FormName:'TipoUnidadForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            ShowButtons2:true,
            showPaquete:false,
            size :"modal-lg",
            NameList:"Sucursales",
            urlApi:"sucursal/get",
            NombrePaq:'Paquetes',
            NameButonNuevo:'Nuevo',
            IdSucursal:0,
            TotalPagina:2,
            tipomodal:1,
            Pag:0,
            IdEmpresa:0,
            ListaSucursal:[],
            ListaHeader:[],
            Empresa:{},
            Filtro:{
                Nombre:'',
                Placeholder:'Sucursal..',
                TotalItem:0,
                Pagina:1
            },
            oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
            },
            oBtnSave2:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
                nombreModal:'ModalP',
            },
			ConfigLoad:{
				ShowLoader:true,
				ClassLoad:true,
			}
        }
    },
    methods: {
        IrHorasLaborales(Id) {
            this.$router.push({
                name:'horaslaborales',
                params: {
                    IdSucursal:Id
                }
            })
        },
        paquetes(Id) {
            this.showPaquete=true;
            this.bus.$emit('AbrirP',Id);
        },
        Eliminar(Id) {
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
                    this.$http.delete('sucursal/' + Id).
                    then( (res) => {
                        this.$toast.success('Información eliminada');
                        this.Lista();
                    });
                }
            });
        },
        async Lista() {
			this.ConfigLoad.ShowLoader = true;

            await this.$http.get(this.urlApi, {
                params: {
                    IdEmpresa:this.IdEmpresa,
                    Nombre:this.Filtro.Nombre,
                    Entrada:this.Filtro.Entrada,
                    pag:this.Filtro.Pagina,
                    RegEstatus:'A'
                }
            })
            .then( (res) => {
                this.ListaSucursal    = res.data.data.sucursal;
                this.Filtro.Entrada   = res.data.data.pagination.PageSize;
                this.Filtro.TotalItem = res.data.data.pagination.TotalItems;
            }).finally(()=>{
				this.ConfigLoad.ShowLoader = false;
			});
        },

        Users(IdSucursal) {
            this.$router.push({
                name:'usersucursal',
                params: {
                    IdSucursal:IdSucursal,
                    IdEmpresa:this.IdEmpresa
                }
            });
        },
    },
    created() {

        if (this.Id!=undefined) {
            sessionStorage.setItem('IdSaved', this.Id);
        }

        this.IdEmpresa= sessionStorage.getItem('IdSaved');

        if (this.item!=undefined) {
            sessionStorage.setItem('IdSaved2', JSON.stringify(this.item));
        }

        this.Empresa= JSON.parse(sessionStorage.getItem('IdSaved2'));

        if (this.PShowButtons!=undefined) {
            sessionStorage.setItem('ShowButton2',this.PShowButtons);
        }
        if (sessionStorage.getItem('ShowButton2')=='true') {
            this.ShowButtons2=true;
            this.NameButonNuevo='Nuevo';
        } else {
            this.ShowButtons2=true;
            this.NameButonNuevo='Perfil';
        }

        this.bus.$off('Delete');
        this.bus.$off('Regresar');
        this.bus.$off('List');
        this.Lista();
         this.bus.$on('Delete',(Id)=> {
            this.Eliminar(Id);

        });

        this.bus.$on('Regresar',()=> {
            this.$router.push({
                name:'empresasroot',
                params: {
                    IdRegresar:0
                }
            });
        });

        this.bus.$on('List',()=>  {
            this.Lista();
        });
    }
}
</script>
