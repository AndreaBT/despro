<template>
    <div>
        <section class="container-fluid mt-2">
            <Menu :pSitio="NombreSeccion">
                <template slot="BtnInicio">                   
                </template>
            </Menu>

            <div class="row justify-content-center mt-3">
                <div class="col-md-6 col-lg-5">
                    <div class="">
                        <Clist :regresar="true" :ShowHead="false" @FiltrarC="Lista" :pShowBtnAdd="false" :Filtro="Filtro" :Nombre="NameList" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal" :pConfigLoad="ConfigLoad">
                            <template slot="header">
                                <tr >
                                    <th>Nombre</th>
                                    <th>Acciones</th>
                                </tr>  
                            </template>
                            <template slot="body">
                                <tr v-for="(lista,key,index) in  Listatipoproceso" :key="index" >
                                    <td>
                                        <b-avatar button  @click="GetChat(lista.IdUsuario,lista.Nombre,lista.Foto2)" :variant="AvatarData(lista.Nombre).Color" v-b-toggle.sidebar-right  size="1.5rem" :text="AvatarData(lista.Nombre).Nombre" class="align-baseline"></b-avatar>
                                       
                                        <!-- <b-avatar class="mr-1" size="1.5rem" :variant="AvatarData(lista.Color)" :text="AvatarData(lista.Nombre)">{{lista.Nombre}}</b-avatar> -->
                                        <input style="width:0;height:0;border:0;position:absolute;background-color:transparent"  :id="'txt'+lista.IdUsuario" :ref="'txt'+lista.IdUsuario"  :value="lista.IdUsuario">
                                        {{lista.NombreTrabajador}}
                                    </td>
                                    <td>
                                        <button v-b-tooltip.hover.Top title="Asignar Proceso" @click="Editar(lista)" type="button" class="btn-icon mr-2" data-toggle="modal" data-target="#ModalForm"  data-backdrop="static" data-keyboard="false"><i class="fas fa-tasks"></i></button>  
                                    </td>   
                                </tr>
                            </template>
                        </Clist>

            <b-sidebar  body-class="modal-dialog-scrollable" id="sidebar-right" width="40em" backdrop right no-header shadow>
              <template v-slot:="{ hide }">
           <div  class="modal-content">

        <div class="modal-header bg-modal">
          <h5 class="modal-title">  
            <b-avatar :src="rutatrab+ImagenTab" size="3rem"></b-avatar> 
           {{NombreTraba}}
            
          </h5>
          
          <!--<h5 class="modal-title"><img src="images/foto.jpg" width="30" class="round"> Haruko Takahashi</h5>-->
      
          <button  @click="hide" type="button" class="close close-2" >
            <i class="fad fa-times-circle"></i>
          </button>
         
        </div>

        <div id="cuerpo"  class="modal-body" >
            <p v-for="(item, index) in ListaChat" :key="index" :class="Chat.IdContacto !=item.IdUsuario ? 'globo-res': 'globo'">{{item.Mensaje}}<br> <b class="color-03">{{item.Fecha}}</b></p>
        </div>

        <div class="card-footer">
            <div class="row">
              <div class="col-12">
                <div class="form-inline">
                  <input v-model="Chat.Mensaje" ref="msj" v-on:keyup.enter="SaveChat" type="text" class="form-control mr-2 col-9"><button :disabled="LoadingChat"  @click="SaveChat" type="button" class="btn btn-01 send">{{txtSave}}</button>
                </div>
              </div>
            </div>
        </div>
      </div><!-- modal-content -->
        
       
      </template>
        </b-sidebar>  

                        <Modal  :size="size" :Nombre="NameList" :poBtnSave="oBtnSave"  >
                            <template slot="Form">

                                <Form :poBtnSave="oBtnSave" ></Form>
                                
                            </template>
                        </Modal>

                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';
import Form from '../vendedores/form.vue';
import Menu from "../indexMenu.vue";

export default {
    name :'list',
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        Form,
        Menu,
    },
    data() {
        return {
            NombreSeccion: 'Lista de vendedores',
            FormName:'TipoUnidadForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"none",
            NameList:"Asignar Proceso",
            urlApi:"trabajador/ListTrabRolQuery",//?Rol=['Vendedor','Gerente de ventas']",
            urlApiVendedor:"vendedores/get",
            Listatipoproceso:[],
            ListaHeader:[],
            TotalPagina:2,
            Pag:0,
            Filtro:{
                Nombre: '',
                Placeholder: 'Nombre..',
                TotalItem: 0,
                Pagina: 1,
                Entrada: 10,
            },
            oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
            },
            TipoList:'',
            NombreVendedor:'',
            EsModal2:true,//indica si es modal o no
            ListaTrabajadores:[],
            rutatrab:'',
            NombreTraba:'',
            ImagenTab:'',
            Finalizados :[],
            Chat:{
              IdContacto:0,
              Mensaje:'',
              Tipo:1
            },
            ListaChat:[],
            LoadingChat:false,
            txtSave:'Guardar',
            ConfigLoad:{
				ShowLoader:true,
				ClassLoad:true,
			}
        }
    },
    methods: {
        Editar(Id)
        {
            if (this.EsModal2==true)
            {
                this.bus.$emit('Nuevo',false,Id);
            }
            else{
                //this.$root.$emit('Nuevo',false,Id);
                this.$router.push({name:this.IrA,params:{Id:Id}})   
            }
        },
        go_to_procesos(objcliente){
            this.$router.push({name:'crmprocesos', params:{ocliente:objcliente,tipolistp:this.TipoList}})
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
            }).then((result) => {
                if(result.value) { 
                     this.$http.delete(
                        'crmtipoproceso/' + Id
                    ).then( (res) => {
                        this.$toast.success('Información eliminada');
                        this.Lista();
                    });      
                } 
            });
        },
        /*async Lista()
        {
            await this.$http.get(this.urlApi,
                {
                    params:{Nombre:this.Filtro.Nombre,RegEstatus:'A',Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina,Rol:JSON.stringify(['Vendedor','Gerente de ventas'])}
                }
            ).then( (res) => {
                //Rol=['Vendedor','Gerente de ventas']"
                this. Listatipoproceso =res.data.data.lista;
                //  this.Filtro.Entrada=res.data.data.pagination.PageSize;
                // this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
            });
        },*/

        async Lista()
        {
            this.ConfigLoad.ShowLoader = true;
            await this.$http.get(this.urlApiVendedor,
                {
                    params:{}
                }
            ).then( (res) => {
                //Rol=['Vendedor','Gerente de ventas']"
                this. Listatipoproceso =res.data.data.Vendedores;
                //  this.Filtro.Entrada=res.data.data.pagination.PageSize;
                // this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
            }).finally(()=>{
				this.ConfigLoad.ShowLoader = false;
			});
        },
        AvatarData(name)
        {
            var name = name;
            var  nameSplit = name;
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
        },
        GetChat(IdContacto,Nombre,Foto)
        {
            this.Chat.IdContacto=0;
            this.Chat.Mensaje="";
            this.Chat.IdContacto=IdContacto;
            this.NombreTraba=Nombre;
            this.ImagenTab=Foto;

            this.$http.get('despacho/getchat',
                {
                    params:{IdContacto:IdContacto,Tipo:1}
                }
            ).then( (res) => {
                this.ListaChat= res.data.Lista;
                this.$refs.msj.focus();
                setTimeout(this.scrollToEnd, 100);  
            });
        },
        SaveChat()
        {
            if (this.Chat.Mensaje.trim()=='')
            {
                this.$refs.msj.focus();
                return false;
            }

            this.errorvalidacion=[];
            this.LoadingChat=true;
            this.txtSave=' Esperar';
            this.$http.post('despacho/postchat',
                this.Chat
            ).then( (res) => {
                this.GetChat(this.Chat.IdContacto,this.NombreTraba,this.ImagenTab);
                this.LoadingChat=false;
                this.$refs.msj.focus();
                this.txtSave=' Enviar';
            }).catch( err => {
                this.$refs.msj.focus();
                this.LoadingChat=false;
                this.txtSave=' Enviar';
                this.errorvalidacion=err.response.data.message.errores;
            });
        },
        scrollToEnd: function() {    	
            /*var container = this.$el.querySelector("#cuerpo");
            var nuevo=1000;
            if (container.scrollHeight==undefined)
            {
            var nuevo =1000;
            }
            container.scrollTop = nuevo;*/
            //("#cuerpo").animate({"scrollTop": $("#cuerpo")[0].scrollHeight}, "slow");
            $('#cuerpo').scrollTop($('#cuerpo')[0].scrollHeight);
        },   
    },
    created()
    {
        //Obligatorio pasar el tipolist
        if (this.tipolistp!=undefined)
        {
            sessionStorage.setItem('IdSaved',JSON.stringify(this.tipolistp));       
        }

        this.TipoList=JSON.parse( sessionStorage.getItem('IdSaved'));

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