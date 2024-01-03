<template>
    <div>
        <section class="container-fluid mt-2">
            <Menu :pSitio="NombreSeccion">
                <template slot="BtnInicio">                   
                </template>
            </Menu>

            <div class="card card-01 mt-3">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-xl-12 mt-2">
                        <div class="form-group form-row">
                            <div class="col-md-3 col-lg-2">
                                <label >Vendedor</label>
                                <select @change="Filtrar" v-model="filtro.IdVendedor" class="form-control form-control-sm">
                                    <option value="">Seleccione una opción</option>
                                    <option v-for="(item, index) in Listatipoproceso" :key="index" :value="item.IdUsuario">{{item.NombreTrabajador}}</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-lg-2">
                                <label>Año</label>
                                <select @change="Filtrar" v-model="filtro.Anio" class="form-control form-control-sm">
                                    <option value="">Seleccione una opción</option>
                                    <option v-for="(item, index) in ListaAnios" :key="index" :value="item">{{item}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-12 col-xl-12 mt-2">
                        <div v-if="ListaFore.length>0" class="form-group form-row">
                            <div class="table-responsive">
                                <table class="table-01 text-nowrap mt-2">
                                    <thead>
                                        <th >Monto</th>
                                        <th >Porcentaje</th>
                                        <th ></th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in ListaFore" :key="index"> 
                                        <td><input type="text" class="form-control" v-model="item.Monto"></td>
                                        <td>{{item.Porcentaje}}</td>
                                        <td>{{item.Descripcion}}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div v-if="ListaFore.length > 0" class="f1-buttons mt-4">
                                    <button  @click="Guardar"  type="button" class="btn btn-01">
                                        <i  v-show="oBtnSave.disableBtn"  class="fa fa-spinner fa-pulse fa-1x fa-fw"></i><i class="fa fa-plus-circle"></i> {{txtSave}}
                                    </button>
                                </div>
                            </div>
                        </div>
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
import Form from '../tiposprocesos/form.vue';
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
            txtSave:'Guardar',
            Disablebtn:false,
            filtro:{
                IdVendedor:'',
                Anio:''
            },
            NombreSeccion: 'Forecast',
            FormName:'TipoUnidadForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"none",
            NameList:"Tipos de procesos",
            urlApi:"trabajador/ListTrabRolQuery",//?Rol=['Vendedor','Gerente de ventas']",
            urlApivendedorNuevo:"vendedores/get",
            Listatipoproceso:[],
            Listavendedores: [],
            ListaHeader:[],
            ListaAnios:[],
            TotalPagina:2,
            Pag:0,
              Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                TotalItem:0,
                Pagina:1,
                Entrada: 10
            },
            oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
            },
            TipoList:'',
            ListaFore:[]
        }
    },
    methods: {
        async Guardar()
        {
            //deshabilita botones
            this.oBtnSave.toast=0; 
            this.oBtnSave.disableBtn=true;
            let formData = new FormData();
            formData.set('IdVendedor',this.filtro.IdVendedor);
            formData.set('Anio',this.filtro.Anio);
            formData.set('Lista',JSON.stringify(this.ListaFore));
            
            await this.$http.post(
                'crmforecast/post',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                },
            ).then( (res) => {
                this.oBtnSave.disableBtn=false;  
                this.$toast.success('Información guardada');
                this.Filtrar();
                        
            }).catch( err => {
                this.errorvalidacion=err.response.data.message.errores;
                this.oBtnSave.disableBtn=false;
                this.oBtnSave.toast=2;  
            });
        },
        Regresar(){},
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
                showLoaderOnConfirm: true
            }).then((result) => {
                if(result.value) {
                   
                    this.$toast.success('Información eliminada');
                     
                    this.$http.delete(
                        'crmtipoproceso/' + Id
                    ).then( (res) => {
                            this.Lista();
                    });
                    
                } 
            });
        },
        /*async Lista()
        {
            await this.$http.get(
                this.urlApi,
                {
                    params:{Nombre:this.Filtro.Nombre,RegEstatus:'A',Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina,Rol:JSON.stringify(['Vendedor','Gerente de ventas'])}
                }
            ).then( (res) => {
                this. Listatipoproceso =res.data.data.lista;
                this.filtro.IdVendedor = res.data.data.lista[0].IdTrabajador;
                this.Filtrar();
            });
        },*/

        async Lista()
        {
            await this.$http.get(
                this. urlApivendedorNuevo,
                {
                    params:{}
                }
            ).then( (res) => {
                this. Listatipoproceso =res.data.data.Vendedores;
                this.filtro.IdVendedor = res.data.data.Vendedores[0].IdUsuario;
                this.Filtrar();
            });
        },
        async Anios()
        {
            await this.$http.get(
                'funciones/getanios',
            ).then( (res) => {
                this.ListaAnios =res.data.ListaAnios;
                this.filtro.Anio = res.data.AnioActual;
                this.Lista();
            });
        },
        Filtrar()
        {   
            if(this.filtro.Anio == '' || this.filtro.IdVendedor == ''){
                this.ListaFore = [];
                this.$toast.warning('Complete los campos');
            }else{
                //this.Disablebtn=true;

                this.$http.get(
                'crmforecast/list',
                {
                    params:{IdVendedor:this.filtro.IdVendedor,Anio:this.filtro.Anio}
                }
                ).then( (res) => {
                this.ListaFore = res.data.data;
                //this.Disablebtn=false;
                });
                //this.Disablebtn=false;
            }
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
        this.bus.$off('Delete');
        this.bus.$off('List');
        this.bus.$off('Regresar');
        
        this.Anios();
         
        this.bus.$on('Delete',(Id)=> 
        {
            this.Eliminar(Id);
        });
        this.bus.$on('List',()=> 
        {
            this.Lista();
            this.Anios();
        });
        this.bus.$on('Regresar',()=> 
        {
            this.$router.push({name:'submenucrm'});
        });
    }
}
</script>