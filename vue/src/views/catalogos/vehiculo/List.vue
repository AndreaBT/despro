<template>
    <div>
        <Clist :regresar="true" @FiltrarC="Lista" :Filtro="Filtro" :Nombre="NameList" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal" :pConfigLoad="ConfigLoad">
            <template slot="header">
				<tr>
					<th>Descripción</th>
					<th>Vehículo</th>
					<th>Marca</th>
					<th>Modelo</th>
					<th>Año</th>
					<th>Placa</th>
					<th>Acciones</th>
				</tr>
            </template>

             <template slot="body">
                   <tr v-for="(lista,index) in ListaVehiculo" :key="index" >
                        <td>{{lista.Categoria }}</td>
                        <td>{{lista.Vehiculo}}</td>
                        <td>{{lista.Marca }}</td>
                        <td>{{lista.Modelo }}</td>
                        <td>{{lista.Ano }}</td>
                        <td>{{lista.Placa }}</td>
                        <td >
                            <Cbtnaccion :isModal="EsModal" :Id="lista.IdVehiculo" :IrA="FormName" v-if="lista.Categoria!='VIRTUAL'" >
                                <template slot="btnaccion">
                                </template>
                            </Cbtnaccion>
                        </td>
                   </tr>
				 <CSinRegistros :pContIF="ListaVehiculo.length" :pColspan="7" ></CSinRegistros>
            </template>

        </Clist>

        <Modal :poBtnSave="oBtnSave"  :size="size" :Nombre="NameList" >
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

import Form from '@/views/catalogos/vehiculo/Form.vue'
import CSinRegistros from "../../../components/CSinRegistros";

export default {
    name :'listConfVehiculos',
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        Form,
		CSinRegistros
    },
    data() {
        return {
            FormName:'TipoUnidadForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"modal-lg",
            NameList:"Vehículos",
            urlApi:"vehiculo/get",
            ListaVehiculo:[],
            ListaHeader:[],
            TotalPagina:2,
            Pag:0,
			Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                TotalItem:0,
                Pagina:1
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
					params:{
						Nombre:this.Filtro.Nombre,
						Entrada:this.Filtro.Entrada,
						pag:this.Filtro.Pagina,
						RegEstatus:'A'
					}
				}
			).then( (res) => {
				this.ListaVehiculo =res.data.data.vehiculo;
				this.Filtro.Entrada=res.data.data.pagination.PageSize;
				this.Filtro.TotalItem=res.data.data.pagination.TotalItems;

			}).finally(()=>{
				this.ConfigLoad.ShowLoader = false;
			});

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
                            'vehiculo/' + Id
                        ).then( (res) => {
                                this.Lista();
                        });

                    }
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
           this.$router.push({name:'submenuadmon'});

        });
    }
}
</script>
