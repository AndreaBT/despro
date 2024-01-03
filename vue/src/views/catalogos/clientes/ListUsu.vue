<template>
    <div>
        <Clist :regresar="true" :Filtro="Filtro" :Nombre="NameList" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal" :pShowBtnAdd="bndShowBtnNew" :pConfigLoad="ConfigLoad">
            <template slot="header">
				<tr>
					<th>Nombre</th>
					<th>Correo</th>
					<th>Acciones</th>
				</tr>
            </template>
             <template slot="body">
                   <tr v-for="(lista,index) in ListaUsuarios" :key="index" >
                       <td>{{lista.Nombre+" "+lista.Apellido}}</td>
                        <td>{{lista.Candado}}</td>
                        <td>
                           <Cbtnaccion :isModal="EsModal" :Id="lista.IdUsuario" :IrA="FormName" >
                          </Cbtnaccion>
                        </td>
                   </tr>
				 <CSinRegistros :pContIF="ListaUsuarios.length" :pColspan="6" />
            </template>
        </Clist>
        <Modal  :size="size" :Nombre="NameList" :poBtnSave="oBtnSave"  >
        <template slot="Form">

              <Form :objcliente="objcliente"  :poBtnSave="oBtnSave" >

              </Form>

        </template>
        </Modal>
</div>
</template>
<script>
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';
import Form from '@/views/catalogos/clientes/FormUsu.vue'
import Modal from '@/components/Cmodal.vue';
import CSinRegistros from "../../../components/CSinRegistros";

export default {
    name :'listUsuariosMonitoreo',
    props:['obj'],
    components :{
        Clist,
        Cbtnaccion,
        Form,
        Modal,
		CSinRegistros
    },
    data() {
        return {
            FormName:'usuariosForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"none",
            NameList:"Usuarios del cliente:",
            urlApi:"usuario/get",
            ListaUsuarios:[],
            ListaHeader:[],
            TotalPagina:2,
            Pag:0,
            IdCliente:0,
            objcliente:{},
            bndShowBtnNew:true,
              Filtro:{
                Nombre:'',
                Filas:10,
                Placeholder:'Nombre..',
                Show:false,
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
					params:{Nombre:'',IdCliente:this.objcliente.IdCliente,Entrada:50,pag:0}
				}
			).then( (res) => {
				this.ListaUsuarios =res.data.data.usuarios;
				this.ListaHeader=res.data.data.headers;

				this.TotalPagina=res.data.data.TotalPag;
				this.Pag=res.data.data.Pagina;
				this.bndShowBtnNew=true;
				if(this.ListaUsuarios.length>=3){
					this.bndShowBtnNew=false;
				}

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
                            'usuario/' + Id
                        ).then( (res) => {
                                this.Lista();
                        });

                    }
                });
        },

    },
    created()
    {

        if (this.obj!=undefined)
        {
            sessionStorage.setItem('IdSaved2',JSON.stringify(this.obj));
        }
        this.objcliente=JSON.parse( sessionStorage.getItem('IdSaved2'));

        this.IdCliente=this.objcliente.IdCliente;
       this.NameList=this.NameList+' '+this.objcliente.Nombre;

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
             this.$router.push({name:'clientes'})
        });
    },
}
</script>
