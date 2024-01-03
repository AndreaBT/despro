<template>
    <div>

        <ul>
            <li v-for="(lista,key,index) in ListaMenus" :key="index" class="none-style mb-3">

                <div class="checkbox mb-n2">
                    <label class="color-01">
                        <input v-model="lista.Estatus" :checked="lista.Estatus" :value="lista.IdPaquete" :id="lista.IdPaquete" type="checkbox" name="optionsCheckboxes">
                        <span class="checkbox-material-green"><span class="check"></span></span>
                            {{lista.Nombre }} 
                    </label>
                </div>

            </li>
        </ul>
        <!-- <pre> {{ ListaMenus }} </pre> -->
        <!-- <pre> {{  }} </pre> -->
    </div>
</template>
<script>
export default {
    name:'modalconfig',
    props:['poBtnSave'],
    data() {
        return {
            ListaMenus:[],
            Modal:true,//Sirve pra los botones de guardado
            IdPerfil: 0,
            Estatus:'',
            FormName:'vehiculo',//Sirve para donde va regresar
        }
    },
    methods: {
        async Guardar() {
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;
            
            // let formData = new FormData();

            // formData.set('IdPerfil',this.IdPerfil);
            // formData.set('Paquetes',JSON.stringify(this.ListaMenus));

            await this.$http.post('menus/addpermisoxmenu', {
                ListaMenus:this.ListaMenus,
                IdPerfil:this.IdPerfil
            })
            .then( (res) => {    
                this.poBtnSave.disableBtn=false;  
                this.poBtnSave.toast=1;
                $('#ModalForm').modal('hide');
                this.bus.$emit('List');     
            })
            .catch( err => {
                this.poBtnSave.disableBtn=false;
                this.poBtnSave.toast=2; 
            });
        },
        async Menus() {
            this.ListaMenus=[];

            await this.$http.get('menus/get', {
                    params: {
                        TxtBusqueda:'',
                        Entrada:'',
                        pag:'',
                        TypeList:0,
                        IdPerfil:this.IdPerfil,
                        Estatus:this.Estatus
                    }
                }
            ).then( (res) => {
                this.ListaMenus = res.data.menus;            
            });

        },
        activarMenuNuevo(accion,Id) {

            var txtOrigen = this.buttonActiveMenu.txtButton;
            var icoOrigen = this.buttonActiveMenu.icono;

            this.disable_buttonsMenu(true,txtOrigen,icoOrigen);

            this.$http.get('menus/addpermisoxmenu', {
                    params:{IdPaquete:Id,IdPerfil:this.permisoxpuesto.IdPerfil,BtnAccion:accion,Tipo:'Menu'}
                }
            ).then((res) =>{

                this.disable_buttonsMenu(false,txtOrigen,icoOrigen);

                if(res.data.exist>0) {
                    if(accion == 0){
                        this.statusButtonMenu(1);
                    }else{
                        this.statusButtonMenu(0);
                    }
                }else{
                    this.$toast.error(err.response.data.message);
                }
            });
        },
        regresar() {
            this.$router.push({name:'perfiles'});
        },
    },
    mounted() {  
        this.bus.$off('Nuevo');

        this.bus.$on('Nuevo',(data,Id)=> {
            this.bus.$off('Save');

            this.bus.$on('Save',()=> {
                this.Guardar();
            });
            
            this.IdPerfil = Id;
            this.Menus();            
        });

    },
}
</script>