<template>
    <div>
		<CHead :oHead="Head"></CHead>
		<div class="row justify-content-center">
			<div class="col-md-6 col-lg-5">
				<Clist :ShowHead="false" :regresar="true" @FiltrarC="get_perfil" :Filtro="Filtro" :Nombre="NameList" :Pag="Pag"
					   :Total="TotalPagina" :isModal="EsModal" :pConfigLoad="ConfigLoad">
					<template slot="header">
						<tr>
							<th>Nombre</th>
							<th>Acciones</th>
						</tr>
					</template>
					<template slot="body">
						<tr v-for="(lista,index) in ListaPerfil" :key="index" >
							<td>{{lista.Nombre }}</td>
							<td >
								<Cbtnaccion :isModal="EsModal" :Id="lista.IdPerfil" :IrA="FormName" :pShowButtonDelete="false">
								</Cbtnaccion>
							</td>
						</tr>
						<CSinRegistros :pContIF="ListaPerfil.length" :pColspan="2" />
					</template>
				</Clist>
			</div>
		</div>


        <Modal  :poBtnSave="oBtnSave"  :size="size" :Nombre="NameList" >
            <template slot="Form">
                <Form :poBtnSave="oBtnSave" ></Form>
            </template>
        </Modal>

    </div>
</template>

<script>

import Modal      from '@/components/Cmodal.vue';
import Clist      from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';
import Form       from '@/views/catalogos/perfilesconfig/Form.vue'
import CSinRegistros from "../../../components/CSinRegistros";

export default {
    name :'listConfPermisosPerfil',
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        Form,
		CSinRegistros
    },
    data() {
        return {
            FormName:'',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            ShowModal:true,
            size :"modal-md",
            NameList:"Asignación de permisos por perfil",
            urlApi:"trabajador/get",
            TotalPagina:2,
            Pag:0,
            ListaTrabajador:[],
            ListaHeader:[],
            ListaPerfil:[],
            Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                TotalItem:0,
                Pagina:1,
				Show: false
            },
            oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
            },
			Head: {
				ShowHead: true,
				Title: "Asignación de permisos por perfil",
				BtnNewShow: false,

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
        Ir_a(item){
            this.$router.push({
                name:'asignarpermisos',
                params: {
                    Id:item.IdPerfil,
                    Nombre:item.Nombre
                }
            });
        },
        get_perfil()  {
			this.ConfigLoad.ShowLoader = true;

            this.$http.get('perfil/get', {
                    params:{}
                }
            ).then( (res) => {
                res.data.data.perfil.forEach(element => {
                    if (element.IdPerfil != 1 && element.IdPerfil != 2 && element.IdPerfil != 4){
                        this.ListaPerfil.push(element);
                    }
                });

            }).finally(()=>{
				this.ConfigLoad.ShowLoader = false;
			});
        },
    },
    mounted() {
       this.get_perfil();
    },

    created()
    {

        this.bus.$off('Regresar');
        this.bus.$on('Regresar',()=>
        {
           this.$router.push({name:'submenuadmon'});

        });
    }
}
</script>
