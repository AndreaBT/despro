<template>
    <div>
        <Clist :regresar="true" :Nombre="NameList" @FiltrarC="Lista" :Filtro="Filtro" :isModal="EsModal" :pConfigLoad="ConfigLoad">
            <template slot="header">
				<tr>
					<th>Nombre</th>
					<th>RFC</th>
					<th>Dirección</th>
					<th>Telefono</th>
					<th>Correo</th>
					<th>Ciudad</th>
					<th>Pais</th>
					<th>Desprosoft</th>
					<th>Acciones</th>
				</tr>
            </template>
            <template slot="body">
                <tr v-for="(lista,index) in ListaEmpresa" :key="index" >
                    <td>{{lista.Nombre }}</td>
                    <td>{{lista.Rfc}}</td>
                    <td>{{$limitCharacters(lista.Direccion,30)}}</td>
                    <td>{{lista.Telefono}}</td>
                    <td>{{lista.Correo}}</td>
                    <td>{{lista.Ciudad}}</td>
                    <td>{{lista.Pais}}</td>
					<td v-if="convertInt(lista.Version) == 4 " class="badge badge-pill badge-primary mt-2">Version 4</td>
					<td v-else class="badge badge-pill badge-Vigencia mt-2">Version 3</td>
                    <td>
                        <Cbtnaccion :isModal="EsModal" :Id="lista.IdEmpresa" :IrA="FormName" >
                            <template slot="btnaccion">
                                <button type="button" @click="IrSucursal(lista.IdEmpresa,lista)" v-b-tooltip.hover.right title="Sucursales" class="btn-icon mr-2">
                                    <span class="fa fa-home"></span>
                                </button>
                            </template>
                        </Cbtnaccion>
                    </td>
                </tr>
				<CSinRegistros :pContIF="ListaEmpresa.length" :pColspan="9" ></CSinRegistros>
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
import Form from '@/views/catalogos/Root/Empresa/Form.vue'
import CSinRegistros from "../../../../components/CSinRegistros";

export default {
    name :'EmpresasRoot',
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        Form,
		CSinRegistros
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
            Pag:0,
			Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                TotalItem:0,
				Pagina: 1,
				Entrada: 10
            },
			oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
            },
			ConfigLoad:{
				ShowLoader:true,
				ClassLoad:true,
			}
        }
    },
    methods: {
		async Lista() {
			this.ConfigLoad.ShowLoader = true;
			await this.$http.get(
				this.urlApi,
				{
					params:{Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina,root:'SI',RegEstatus:'A'}
				}
			).then( (res) => {
				this.ListaEmpresa 	= res.data.data.empresa;
				this.ListaHeader	= res.data.data.headers;
				this.Filtro.Entrada	= res.data.data.pagination.PageSize;
				this.Filtro.TotalItem = res.data.data.pagination.TotalItems;
				this.Filtro.Pagina = res.data.data.pagination.CurrentPage;
			}).finally(()=>{
				this.ConfigLoad.ShowLoader = false;
			});
		},

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
                    this.$http.delete(
                        'empresa/' + Id
                    ).then( (res) => {
                        this.$toast.success('Información eliminada');
                            this.Lista();

                    });
                }
            });
        },

        IrSucursal(Id,item){
            this.$router.push({name:'sucursalroot', params:{Id:Id,PShowButtons:true,item:item}})
        },
		convertInt($data){
			if($data !== null || $data !== undefined){
				return parseInt($data);
			}
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

        this.bus.$on('Regresar',()=>
        {
            this.$router.push({name:'MenusRoot'});
        });
    }
}
</script>
