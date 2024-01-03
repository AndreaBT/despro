<template>
    <div>

        <ul>
            <li v-for="(lista,key,index) in ListaMenus" :key="index" class="none-style mb-3">
                <div class="checkbox mb-n2">
                    <label class="color-01">
                        <input type="checkbox" name="optionsCheckboxes"  :checked="lista.Estatus" v-model="lista.Estatus"  :value="lista.IdPaquete" :id="lista.IdPaquete"><span
                            class="checkbox-material-green"><span class="check"></span></span>
                            {{lista.Nombre }} 
                    </label>
                </div>
            </li>
        </ul>
        


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
            FormName:'vehiculo',//Sirve para donde va regresar
        }
    },

    methods: {
        regresar(){
            this.$router.push({name:'perfiles'});
        },
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

        async Guardar()
        {
            //this.poBtnSave.disableBtn=true;
            
            let formData = new FormData();

            formData.set('IdPerfil',this.Id);
            formData.set('Paquetes',JSON.stringify(this.ListaMenus));

            await this.$http.post(
                'menus/addpermisoxmenu',
                formData,
                {
                    headers: {'Content-Type': 'multipart/form-data'}
                },
                ).then( (res) => {
                    
                // this.poBtnSave.disableBtn=false;  
                this.poBtnSave.toast=1;

                    this.$router.push({name:'perfiles'});
                        
            }).catch( err => {

                // this.errorvalidacion=err.response.data.message.errores;
                // this.poBtnSave.disableBtn=false;
                this.poBtnSave.toast=2;  
            });
       
        },

        async Menus()
        {
            await this.$http.get(
                'menus/get',
                {
                    params:{TxtBusqueda:'',Entrada:'',pag:'',TypeList:0,IdPerfil:this.Id}
                }
            ).then( (res) => {
                this.ListaMenus =res.data.menus;                
            });
        },

    },

    mounted() {
        
         this.bus.$on('Nuevo',(data,Id)=> 
        {
            
            this.bus.$off('Save');
            this.bus.$on('Save',()=>
            {
            this.Guardar();
            });

            
            
            this.Id=Id;
            this.Menus();
            
            
        });

    },
}
</script>