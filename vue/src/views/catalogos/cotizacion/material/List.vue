<template>
<div>
    <CHead :oHead="Head"></CHead>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <Clist :regresar="regresar" :Nombre="NameList" @FiltrarC="Lista" :Filtro="Filtro" :isModal="EsModal" :pShowBtnAdd="btnadd" :ShowHead="false" :pConfigLoad="ConfigLoad">

				<template slot="header">
                    <tr>
                        <th>Material</th>
                        <th>Precio</th>
                        <th>Acciones</th>
					</tr>
                </template>

                <template slot="body">
                    <tr v-for="(lista,index) in ListaMaterial" :key="index" >
						<td>{{lista.NomMaterial.substr(0, 20) }}</td>
                        <td>$ {{Number(lista.Precio).toLocaleString() }}</td>
                            <td>
								<Cbtnaccion v-if="ShowBotons" :ShowButtonG="ShowBotons" :isModal="EsModal" :Id="lista.IdMaterial" :IrA="FormName" >
								</Cbtnaccion>
								<button v-else type="button" @click="Add_Material(lista)" class="btn btn-table" ><i class="fa fa-plus-circle"></i></button>
							</td>
                    </tr>
					<CSinRegistros :pContIF="ListaMaterial.length" :pColspan="3" ></CSinRegistros>
                </template>

            </Clist>

            <Modal  v-if="btnadd" :size="size" :Nombre="NameForm" :poBtnSave="oBtnSave" >
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
import Form from '@/views/catalogos/cotizacion/material/Form.vue';
import CHead from "@/components/CHead.vue";
import CSinRegistros from "../../../../components/CSinRegistros";

export default {
    name :'listCotizacionConfMaterial',
    props:["pTipo"],
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
            FormName:'Material',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no,
            CloseModal:true,//indica si el modal cierra o de lo contrario asignarle un evento al boton
            size :"none",
            NameList:"Materiales",
            NameForm:"Material",
            urlApi:"cotizacion_material/get",
            ListaMaterial:[],
            ListaHeader:[],

            Rutaicono:'',
            regresar:true,
            ShowBotons:true,
            btnadd:true,  ShowAcciones:true,
              Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                 TotalItem:0,
                Pagina:1
            },  oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
            },
            Head: {
				ShowHead: true,
				Title: "Materiales",
				BtnNewShow: true,
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
					params:{Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina, RegEstatus:'A'}
				}
			).then( (res) => {
				this.ListaMaterial =res.data.data.row;
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
                            'cotizacion_material/' + Id
                        ).then( (res) => {
                                this.Lista();
                        });

                    }
                });
        },

        Change(titulo)
        {
            this.NameForm=titulo;
            var bdn=true;
            if(titulo=='Iconos'){
                bdn=false;
            }
            this.bus.$emit('cambiar_CloseModal',bdn);
        },Limpiar(){
            this.bus.$emit('cambiar_CloseModal',true);
        },Add_Material(obj){

            this.bus.$emit('Add_Materiales',obj);
            $('#ModalForm').modal('hide');
        }
    },
    created()
    {

        this.bus.$off('Delete');
        this.bus.$off('List');
        this.bus.$off('Limpiar');
         this.bus.$off('Regresar');
        this.Lista();

        if(this.pTipo!=undefined){
           if(this.pTipo==2){
                this.regresar=false;
                this.ShowBotons=false;
                this.btnadd=false;
                this.bus.$emit('Cambiar_Footer',false);
           }
        }

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
