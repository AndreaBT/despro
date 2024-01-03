<template>
    <div>
        <CHead :oHead="Head"></CHead>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <Clist :regresar="true" :Nombre="NameList" :Filtro="Filtro" :isModal="EsModal" :pShowBtnAdd="false" :ShowHead="false" :pConfigLoad="ConfigLoad">
                    <template slot="header">

                        <tr >
                            <th>Concepto</th>
                            <th>Costo</th>
                            <th>Acciones</th>
                            </tr>

                    </template>
                    <template slot="body">
                        <tr v-for="(lista,index) in Listacostosta" :key="index">
                                <td v-if="lista.Concepto != 'MATERIALES MISC. DIA' && lista.Concepto != 'FACTOR M.O. T+A' ">{{lista.Concepto }}</td>
                                <td v-if="lista.Concepto != 'MATERIALES MISC. DIA' && lista.Concepto != 'FACTOR M.O. T+A' ">$ {{lista.Costo }}</td>
                                <td v-if="lista.Concepto != 'MATERIALES MISC. DIA' && lista.Concepto != 'FACTOR M.O. T+A' ">
                                    <Cbtnaccion :PBtndelete="false" :isModal="EsModal" :Id="lista.IdCostosTA" :IrA="FormName"></Cbtnaccion>
                                </td>
                        </tr>
						<CSinRegistros :pContIF="Listacostosta.length" :pColspan="3" ></CSinRegistros>
                    </template>

                </Clist>

                <Modal  :size="size" :Nombre="NameForm" :poBtnSave="oBtnSave"  >
                    <template slot="Form">
                    <Form :NameList="NameForm"  :poBtnSave="oBtnSave" ></Form>
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
import Form from '@/views/catalogos/cotizacion/costosta/Form.vue';
import CHead from "@/components/CHead.vue";
import CSinRegistros from "../../../../components/CSinRegistros";

export default {
    name :'listCotizacionCostoMO',
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        Form,
        CHead,
		CSinRegistros

    },
    data() {
        return {
            FormName:'costosta',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no,
            CloseModal:true,//indica si el modal cierra o de lo contrario asignarle un evento al boton
            size :"none",
            NameList:"Costos Personal",
            NameForm:"Costos Personal",
            urlApi:"costosta/get",
            Listacostosta:[],
            ListaHeader:[],
            Rutaicono:'',
              Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                 TotalItem:0,
                Pagina:1,
                Show:false
            },oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
            },
            Head: {
				ShowHead: true,
				Title: "Costos Personal",
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
					params:{Nombre:'',Entrada:50,pag:0, RegEstatus:'A'}
				}
			).then( (res) => {
				this.Listacostosta =res.data.data.row;

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
                            'costosta/' + Id
                        ).then( (res) => {
                                this.Lista();
                        });

                    }
                });
        },
       Limpiar(){
            this.bus.$emit('cambiar_CloseModal',true);
        }
    },
    created()
    {

        this.bus.$off('Delete');
        this.bus.$off('List');
        this.bus.$off('Limpiar');
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

        this.bus.$on('Limpiar',()=>
        {
            this.Limpiar();
        });
         this.bus.$on('Regresar',()=>
        {
            this.$router.push({name:'submenucotizacion'});
        });
    }
}
</script>
