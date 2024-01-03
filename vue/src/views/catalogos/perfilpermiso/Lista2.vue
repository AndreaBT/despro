<template>
    <div>

        <h1>{{Nombre}}</h1>
        
        <ul>
            <li v-for="(lista,key,index) in ListaMenus" :key="index" class="none-style mb-3">
                <div class="checkbox mb-n2">
                    <label class="color-01">
                        <input type="checkbox" name="optionsCheckboxes"  :checked="lista.Estatus" v-model="lista.IdPaquete" @click="activarMenuNuevo(buttonActiveMenu.btnAccion,lista.IdPaquete);"  :value="lista.IdPaquete" :id="lista.IdPaquete"><span
                            class="checkbox-material-green"><span class="check"></span></span>
                            {{lista.Nombre }} 
                    </label>
                </div>
                <!-- <ul>
                    <li v-for="(item2,index2) in lista.Submenu" :key="index2">
                            {{item2.Nombre}}
                    </li>
                </ul> -->
            </li>
        </ul>

        
            <div class="row justify-content-center mt-3">
                <div   div class="col-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card card-darck">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-9 col-lg-9">
                                    <form class="form-inline">
                                        <!--   v-model="permisoxpuesto.IdPaquete"  -->
                                        <select v-model="permisoxpuesto.IdPaquete" @change="getActiveButtonMenu();" class="form-control  mr-3">
                                            <option value="0">Seleccione un Paquete</option>
                                            <option v-for="data in ListaMenus" :value="data.IdPaquete" :key="data.IdPaquete">{{data.Nombre}}</option>
                                        </select>
                                    </form>
                                </div>
                                <div class="col-12 col-sm-12 col-md-3 col-lg-3 text-right">
                                    <button  @click="Regresar()" type="button" class="btn btn-bradcrumb btn-primary mr-1"><i class="fas fa-chevron-left"></i> Perfiles</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body  text-left">
                        <div class="form-group row" v-show="showActiveButton">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <button type="button" @click="activarMenu(buttonActiveMenu.btnAccion);" :class="buttonActiveMenu.class" :disabled="disabledMenu">
                                    <i :class="buttonActiveMenu.icono"></i> {{buttonActiveMenu.txtButton}}
                                </button>
                            </div>
                        </div>
                        <hr>

                        <!--
                        <div class="row" v-show="showActiveSubMenu">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6" v-show="showDivSubMenu">
                                <h4>Menus</h4>
                                <div class="row" v-for="(lista,key,index) in ListaSubmenusApartados" :key="index">
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                        <label class="col-form-label">{{lista.Nombre}}</label>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                        <span v-show="lista.btnArray.Existe">
                                        <button type="button" @click="showPermisosxMenu(lista.IdPaquete);" class=" btn btn-success btn-action tooltip-02 top-02 ml-2">
                                            <i class="far fa-eye"></i>
                                            <span class="tiptext">Permisos</span>
                                        </button>
                                        </span>&nbsp;&nbsp;

                                        <button type="button" @click="activaSubMenu(lista.btnArray,lista.IdPaquete);" :class="lista.btnArray.btnStyle" :disabled="disabledSMAP">
                                            <i :class="lista.btnArray.btnIcono"></i> {{lista.btnArray.btnTexto}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <h4>Permisos</h4>
                                <div class="row" v-for="(data,key,index) in ListaPermisosxMenu" :key="index">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <label class="col-form-label">
                                            <input type="checkbox" v-model="ListaPermisosChecked" :value="data.IdPermiso"> {{data.Nombre}}
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="row" v-if="ListaPermisosxMenu.length>0">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 text-right">
                                        <button type="button" @click="savePermisosxPuesto(ListaPermisosxMenu[0].IdPaquete);" class="btn btn-success" :disabled="buttonSavePermiso.disabledSave">
                                            <i :class="buttonSavePermiso.icono"></i> {{buttonSavePermiso.txtButton}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        -->
                    </div>
                    </div>
                </div>
            </div>
    </div>
</template>
<script>
export default {
    props:['Id','Nombre'],
    data() {
        return {
            ListaMenus:[],
            permisoxpuesto:
            {
                IdPaquete: 0,
                IdPerfil: this.Id,
            },
            buttonActiveMenu:
            {
                class: 'btn btn-success',
                icono: 'fa fa-check',
                txtButton: 'Activar Paquete',
                btnAccion: 1, // 1 para activar boton, 0 para desactivar boton
            },
            buttonSavePermiso:
            {
                txtButton: 'Guardar',
                icono: '',
                disabledSave: false,
            },
            showActiveButton: false,
            showActiveSubMenu: false,
            showDivSubMenu: true,
            disabledMenu: false,
            disabledSMAP: false,
            ListaSubmenusApartados: [],
            ListaPermisosxMenu:[],
            ListaPermisosChecked:[],
            OList:{
                Titulo:'Permisos',
                Entrada:50,
                Pag:1,
                TotalItems:0,
                PageSize:0,
                TxtBusqueda:'',
                Title:'Permisos',
                regresar:true,
                NameBtnReturn: 'Regresar',
                Obj:{}//sirve para enviar el objeto departamento al fomr
            },
            segurity:{},
            oDepartamento:{},
        }
    },

    methods: {

        activarMenuNuevo(accion,Id)
        {
            var txtOrigen = this.buttonActiveMenu.txtButton;
            var icoOrigen = this.buttonActiveMenu.icono;
            this.disable_buttonsMenu(true,txtOrigen,icoOrigen);

            this.$http.get('menus/addpermisoxmenu',
                {
                    params:{IdPaquete:Id,IdPerfil:this.permisoxpuesto.IdPerfil,BtnAccion:accion,Tipo:'Menu'}
                }
            ).then( (res) =>{
                this.disable_buttonsMenu(false,txtOrigen,icoOrigen);
                if(res.data.exist>0)
                {
                    if(accion == 0){
                        this.statusButtonMenu(1);
                    }
                    else{
                        this.statusButtonMenu(0);
                    }
                }
                else{
                    this.$toast.error(err.response.data.message);
                }
            });
        },


        getActiveButtonMenu()
        {
            this.showActiveButton = false;
            if(this.permisoxpuesto.IdPaquete>0)
            {
                this.showActiveButton = true;
                this.$http.get('permisoxpaquete/get',
                    {
                        params:{IdPaquete:this.permisoxpuesto.IdPaquete,IdPerfil:this.permisoxpuesto.IdPerfil}
                    }
                ).then( (res) => {

                    if(res.data.exist>0)
                    {
                        this.statusButtonMenu(0);
                    }
                    else
                    {
                        this.statusButtonMenu(1);
                    }
                    //this.ListaMenus =res.data.menus;                
                });
            }
            else
            {
                this.ListaSubmenusApartados = [];
                this.limpiarPermisos();
                this.showActiveSubMenu = false;
            }
        },


        statusButtonMenu(Tipo)
        {
            this.limpiarPermisos();
            // 1 PARA CUANDO SEA ACTIVAR EL BOTON Y 0 PARA CUANDO SEA DESACTIVAR EL BOTON
            if(Tipo==0)
            {
                this.buttonActiveMenu.class = 'btn btn-danger';
                this.buttonActiveMenu.icono = 'fa fa-ban';
                this.buttonActiveMenu.txtButton = 'Desactivar Paquete';
                this.buttonActiveMenu.btnAccion = 0;
                this.showActiveSubMenu = true;
            }
            else
            {
                this.buttonActiveMenu.class = 'btn btn-success';
                this.buttonActiveMenu.icono = 'fa fa-check';
                this.buttonActiveMenu.txtButton = 'Activar Paquete';
                this.buttonActiveMenu.btnAccion = 1;
                this.showActiveSubMenu = false;
            }
            this.getSubMenuApartados();
        },

        limpiarPermisos()
        {
            this.ListaPermisosxMenu = [];
            this.ListaPermisosChecked = [];
        },

        // OBTENEMOS TODOS LOS SUBMENUS Y APARTADOS DEL MENU
        getSubMenuApartados()
        {
            this.showDivSubMenu = true;
            this.$http.get('menus/getsubmenuapartado',
                {
                    params:{IdPaquete:this.permisoxpuesto.IdPaquete,IdPerfil:this.permisoxpuesto.IdPerfil}
                }
            ).then( (res) =>{
                this.ListaSubmenusApartados = res.data.menus;
                if(res.data.menus.length==0){
                    this.showDivSubMenu = false;
                    this.showPermisosxMenu(this.permisoxpuesto.IdPaquete);
                }
            });
        },

        // MANDAMOS A GUARDAR EL MENU EN EL PUESTO
        activarMenu(accion)
        {
            var txtOrigen = this.buttonActiveMenu.txtButton;
            var icoOrigen = this.buttonActiveMenu.icono;
            this.disable_buttonsMenu(true,txtOrigen,icoOrigen);

            this.$http.get('menus/addpermisoxmenu',
                {
                    params:{IdPaquete:this.permisoxpuesto.IdPaquete,IdPerfil:this.permisoxpuesto.IdPerfil,BtnAccion:accion,Tipo:'Menu'}
                }
            ).then( (res) =>{
                this.disable_buttonsMenu(false,txtOrigen,icoOrigen);
                if(res.data.exist>0)
                {
                    if(accion == 0){
                        this.statusButtonMenu(1);
                    }
                    else{
                        this.statusButtonMenu(0);
                    }
                }
                else{
                    this.$toast.error(err.response.data.message);
                }
            });
        },

        disable_buttonsMenu(bnd,txt,ico){
            this.disabledMenu = false;

            this.buttonActiveMenu.txtButton = txt;
            this.buttonActiveMenu.icono = ico;

            if(bnd){
                this.disabledMenu = true;
                this.buttonActiveMenu.txtButton = ' Espere...';
                this.buttonActiveMenu.icono = 'fa fa-spinner fa-pulse';
            }
        },
        disable_buttonsSubMenu(bnd,txt,ico,obj){
            this.disabledSMAP = false;

            obj.btnTexto = txt;
            obj.btnIcono = ico;

            if(bnd){
                this.disabledSMAP = true;
                obj.btnTexto = ' Espere...';
                obj.btnIcono = 'fa fa-spinner fa-pulse';
            }
        },
        disable_buttonsSave(bnd){
            this.buttonSavePermiso.disabledSave = false;

            this.buttonSavePermiso.txtButton = 'Guardar';
            this.buttonSavePermiso.icono = '';

            if(bnd){
                this.buttonSavePermiso.disabledSave = true;
                this.buttonSavePermiso.txtButton = ' Espere...';
                this.buttonSavePermiso.icono = 'fa fa-spinner fa-pulse';
            }
        },

        // MANDAMOS A GUARDAR EL SUB MENU Y APARTADOS EN EL PUESTO Y CAMBIAMOS ESTATUS DE LOS BOTONES
        activaSubMenu(obj,IdPaquete)
        {
            this.limpiarPermisos();
            var txtOrigen = obj.btnTexto;
            var icoOrigen = obj.btnIcono;
            this.disable_buttonsSubMenu(true,txtOrigen,icoOrigen,obj);

            this.$http.get('menus/addpermisoxmenu',
                {
                    params:{IdPaquete:IdPaquete,IdPerfil:this.permisoxpuesto.IdPerfil,BtnAccion:obj.btnActivo,Tipo:'SubMenu'}
                }
            ).then( (res) =>{
                this.disable_buttonsSubMenu(false,txtOrigen,icoOrigen,obj);
                if(res.data.exist>0)
                {
                    if(obj.btnActivo == 1)
                    {
                        obj.btnTexto = 'Desactivar';
                        obj.btnIcono = 'fa fa-ban';
                        obj.btnStyle = 'btn btn-danger btn-action';
                        obj.Existe = true;
                        obj.btnActivo = 0;
                    }
                    else
                    {
                        obj.btnTexto = 'Activar';
                        obj.btnIcono = 'fa fa-lock';
                        obj.btnStyle = 'btn btn-success btn-action';
                        obj.Existe = false;
                        obj.btnActivo = 1;
                    }
                }
                else{
                    this.$toast.error(err.response.data.message);
                }
            });
        },

        // MOSTRAMOS EL LISTADO DE PERMISOS DE CADA SUBMENU O APARTADO
        showPermisosxMenu(IdPaquete)
        {
            this.ListaPermisosxMenu = [];
            // LIMPIAMOS LOS SELECCIONADOS PARA Q NO SE QUEDEN ALMACENADOS.
            //this.ListaPermisosChecked = [];
            this.limpiarPermisos();
            this.$http.get('menus/showpermisosxmenu',
                {
                    params:{IdPaquete:IdPaquete,IdPerfil:this.permisoxpuesto.IdPerfil}
                }
            ).then( (res) =>{
                this.ListaPermisosxMenu = res.data.menuxpermiso;

                res.data.permisosxpuesto.forEach((value, index) => {
                    this.ListaPermisosChecked.push(value.IdPermiso)
                });
            });
        },


        Regresar(){

        },

        PaquetesSucursal(ID)
        {
            this.$http.get(
                'paquetexsucursal/get',
                {
                    params:{IdSucursal:ID}
                }
            ).then( (res) => {
                this.ListaPaquetes =res.data.data.paquetexsucursal;
            });        
        },

        async Menus()
        {
            await this.$http.get(
                'menus/get',
                {
                    params:{TxtBusqueda:'',Entrada:'',pag:'',TypeList:0}
                }
            ).then( (res) => {
                this.ListaMenus =res.data.menus;                
            });
        },

    },

    mounted() {
        this.Menus();
    },
}
</script>