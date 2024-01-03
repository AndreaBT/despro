<template>
    <div>
        <Clist :regresar="true" @FiltrarC="Lista" :Filtro="Filtro" :pShowBtnAdd="false" :Nombre="NameList" :isModal="EsModal" :pConfigLoad="ConfigLoad">
            <template slot="Filtros">
                <div>
                    <div class="form-group ml-2">
                        <label class="mr-1">Estatus: </label>
                        <select @change="Lista" v-model="Filtro.Tipo"  class="form-control">
                            <option value="Tecnico">Técnico</option>
                            <option value="Vendedor">Vendedor</option>
                        </select>
                    </div>
                </div>
            </template>

            <template slot="header">
                <tr>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Profesión</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </template>

            <template slot="body">
                <tr v-for="(lista,key,index) in ListaTrabajador" :key="index" >
                    <td><b-avatar :variant="AvatarData(lista.Nombre).Color" :text="AvatarData(lista.Nombre).Nombre" size="28" ></b-avatar> {{lista.Nombre }}</td>
                    <td>{{lista.Telefono}} </td>
                    <td>{{lista.Usuario}} </td>
                    <td>{{lista.Profesion}} </td>
                    <td class="text-center">
                        <button type="button" @click="Editar(lista.IdTrabajador,Filtro.Tipo)" class="btn-icon mr-2" >
                            <i class="fas fa-cash-register"></i>
                        </button>
                    </td>
                </tr>
				<CSinRegistros :pContIF="ListaTrabajador.length" :pColspan="5" ></CSinRegistros>
            </template>
        </Clist>

        <Modal :Showbutton="false" :size="size" :Nombre="NameList" >
            <template slot="Form" >
                <Form></Form>
            </template>
        </Modal>
    </div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';
import Form from '@/views/catalogos/AsignacionCaja/Form.vue'
import CSinRegistros from "../../../components/CSinRegistros";

export default {
    name :'list',
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        Form,
		CSinRegistros
    },
    data() {
        return {
            FormName:'folioForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"modal-lg",
            NameList:"Asignación de Viáticos",
            urlApi:"trabajador/get",
            urlApiRol:"trabajador/ListTrabRolQuery",
            ListaTrabajador:[],
            ListaRol:[],
            ListaHeader:[],
            Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                TotalItem:0,
                Pagina:1,
                Tipo:'Tecnico',
                Entrada: 10,
            },
            RolesTec:["Usuario APP"],
            RolesVen:["Vendedor","Gerente de ventas"],
			ConfigLoad:{
				ShowLoader:true,
				ClassLoad:true,
			},
        }
    },
    methods: {
        async Lista() {
			this.ConfigLoad.ShowLoader = true;

            var filtrorol=this.RolesTec;
            if (this.Filtro.Tipo=='Vendedor')
            {
                filtrorol=this.RolesVen;
            }

            this.$http.get(
                "trabajador/ListTrabRolQuery",
                {
                    params:{Rol:JSON.stringify(filtrorol),Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina, RegEstatus:'A'}
                }
            ).then( (res) => {
                this.ListaTrabajador	= res.data.data.lista;
                this.Filtro.Entrada		= res.data.data.pagination.PageSize;
                this.Filtro.TotalItem	= res.data.data.pagination.TotalItems;
				this.Filtro.Pagina 		= res.data.data.pagination.CurrentPage;
            }).finally(()=>{
				this.ConfigLoad.ShowLoader = false;
			});
        },
        Editar(Id,Tipo)
        {
            this.bus.$emit('Nuevo',Id,Tipo);
            $("#ModalForm").modal('show');
        },
        AvatarData(name)
        {
            var name = name;
            var nameSplit = name.split(" ");
            var initials;

            if (nameSplit.length>1)
            {
                initials = nameSplit[0].charAt(0).toUpperCase() + nameSplit[1].charAt(0).toUpperCase();
            }
            else{
               initials = nameSplit[0].charAt(0).toUpperCase();
            }

            var colours = ["secondary", "secondary", "dark", "success", "danger", "warning", "info"];
            const randomMonth = colours[Math.floor(Math.random() * colours.length)];
            var Arreglo ={Nombre:initials,Color:randomMonth};
            return Arreglo;
        }
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
            this.$router.push({name:'cajachica'});
        });
    }
}
</script>
