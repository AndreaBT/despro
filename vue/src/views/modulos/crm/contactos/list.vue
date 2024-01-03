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
                   <Clist :pShowBtnAdd="ShowBtns" :ShowHead="false" @FiltrarC="Lista" :Filtro="Filtro" :regresar="false"  :Nombre="NameList"  :isModal="EsModal" :pConfigLoad="ConfigLoad">
                    
                    <template slot="Filtros"> 
                        <label class="mr-2">Tipos</label>
                        <select @change="Lista" v-model="Tipo" class="form-control form-control">
                            <option value="">Seleccione una opción</option>
                            <option :value="'Cliente'">Cliente</option>
                            <option :value="'Prospecto'">Prospecto</option>
                            <option :value="'Suspecto'">Suspecto</option>
                            <option :value="'Inactivo'">Inactivo</option>
                        </select>
                    </template>

                    <template slot="header">
                        <tr>
                            <th>Cliente</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Correo</th>
                            <th>Ciudad</th>
                            <th>Acciones</th>
                        </tr> 
                    </template>
                    
                    <template slot="body">
                        <tr v-for="(lista,key,index) in ListaContactos" :key="index" >
                            <td>{{lista.Nombre }}</td>
                            <td>{{lista.Telefono }}</td>
                            <td>{{lista.Direccion}}</td>
                            <td>{{lista.Correo}}</td>
                            <td>{{lista.Ciudad}}</td>
                            <td>
                                <Cbtnaccion :ShowButtonG="ShowBtns" :isModal="EsModal" :Id="lista.IdCliente" :IrA="FormName" >
                                    <template slot="btnaccion">
                                        <button v-b-tooltip.hover.lefttop title="Sucursales" @click="go_to_sucursal(lista)"  type="button" class="btn-icon mr-2"> <i class="fa fa-building" aria-hidden="true"></i></button>
                                    </template>
                                </Cbtnaccion>     
                            </td>   
                        </tr>
                    </template>
                </Clist>

                <Modal :size="size" :Nombre="NameList" :poBtnSave="oBtnSave" >
                    <template slot="Form">
                        <Form :poBtnSave="oBtnSave" >
                        </Form>
                    </template>
                </Modal> 


                </div>
            </div>
        </section>
    </div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';
import Form from '@/views/modulos/crm/contactos/form.vue'
import Menu from "../indexMenu.vue";

export default {
    name :'list',
    props:['tipolistp'],
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        Form,
        Menu,
    },
    data() {
        return {
            NombreSeccion: 'Contactos',
            Tipo:'',
            FormName:'clientesForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"modal-lg",
            NameList:"Contacto",
            urlApi:"crmcontactos/list",
            ListaContactos:[],
            ListaHeader:[],
            TotalPagina:2,
            Pag:0,
            ShowBtns:true,
            TipoList:'',
            Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                TotalItem:0,
                Pagina:1,
                Entrada:10
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
                        'crmcontactos/' + Id
                    ).then( (res) => {
                        this.$toast.success('Información eliminada');
                        this.Lista();
                    });
                } 
            });
        },
        async Lista()
        {
            this.ConfigLoad.ShowLoader = true;
            await this.$http.get(
                this.urlApi,
                {
                    params:{Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina, RegEstatus:'A',Tipo:this.Tipo}
                }
            ).then( (res) => {
     
                this.ListaContactos =res.data.data.clientes;
                this.ListaHeader=res.data.headers;
                this.Filtro.Entrada=res.data.data.pagination.PageSize;
                this.Filtro.TotalItem=res.data.data.pagination.TotalItems; 
            }).finally(()=>{
				this.ConfigLoad.ShowLoader = false;
			});
        },
        go_to_sucursal(objcliente){
            this.$router.push({name:'crmcontactosucursal', params:{ocliente:objcliente,tipolistp:this.TipoList}})
        },
        go_to_usuarios(objcliente){
            this.$router.push({name:'listusu', params:{obj:objcliente}})
        }
    },
    created()
    {   
        //Obligatorio pasar el tipolist
        if(this.tipolistp!=undefined)
        {
            sessionStorage.setItem('IdSaved',JSON.stringify(this.tipolistp));       
        }

        this.TipoList=JSON.parse( sessionStorage.getItem('IdSaved'));
        
        if(this.TipoList=='Scanning'){
            this.ShowBtns=false;
        }
        
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