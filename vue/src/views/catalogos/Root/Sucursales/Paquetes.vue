<template>
    <div class="modal-body form-cotizacion">

        <ul>
            <li v-for="(lista,key,index) in ListaPaquetes" :key="index" class="none-style mb-3">
                <div class="checkbox mb-n2">
                    <label class="color-01">
                        <input v-model="lista.Estatus" :checked="lista.Estatus" :value="lista.IdPaquete" :id="lista.IdPaquete" type="checkbox" name="optionsCheckboxes">
                        <span class="checkbox-material-green"><span class="check"></span></span>
                            {{lista.Nombre }} 
                    </label>
                </div>
            </li>
        </ul>

    </div>
</template>
<script>
export default {
    name:'Paquetes',
    props:['poBtnSave'],
    data() {  
        return{
            ListaPaquetes:[],
            ListaPaquetesxSucursal:"",
            urlApi:"paquetexsucursal/get",
            ids:0, 
            IdSucursal:0,    
            PaquetexSucursal:{
                IdSucursal:0,
                IdPaquete:0
            },
            oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                nombreModal:'ModalP'
            }
        }
    },
    methods: {    
        Lista() {
            this.$http.get(this.urlApi, {
                params:{IdSucursal:this.IdSucursal}
            })
            .then( (res) => {
                this.ListaPaquetes =res.data.data.paquetexsucursal;
            });              
        },
        Guardar() {
            
            // for (var i=0;i< this.ListaPaquetes.length;i++) {
            //     if (this.ListaPaquetes.Estatus==true)  {
            //     }
            // }

            //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;

            this.$http.post('paquetexsucursal/post', {
                ListaPaquetes:this.ListaPaquetes,
                IdSucursal:this.IdSucursal
            })
            
            .then( (res) => {
                //deshabilita botones
                this.poBtnSave.disableBtn=false;  
                this.poBtnSave.toast=1;
                $('#ModalP').modal('hide');
            })
            .catch( err => {
                //deshabilita botones
                this.poBtnSave.disableBtn=false;          

                if(err.response.data.typemsg=2){
                    this.errorvalidacion=err.response.data.message.errores;
                    this.poBtnSave.toast=2; 
                } else {
                    this.poBtnSave.toast=3; 
                    this.poBtnSave.toastmsg(err.response.data.message);
                }
            });
        },     
    },
    created() {
        // this.ListaPaquetes.IdSucursal = this.Id;
        this.bus.$off('AbrirP');
        
        this.bus.$on('AbrirP',(Id)=> {
            //deshabilita botones
            this.poBtnSave.disableBtn=false;       

            this.bus.$off('Save');

            this.bus.$on('Save',()=> {
                this.Guardar();
            });

            this.IdSucursal =Id;
            this.Lista();
        });

    }
}
</script>