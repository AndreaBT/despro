<template>
    <div>
    
            <div class="row">
                <div class="col-lg-12 ">
                    <label >Nombre </label>
                        <input type="text" v-model="Usuario.Nombre" class="form-control form-control-sm"
                                placeholder="Nombre" id="Nombre"
                            name="Nombre" />
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Nombre" :Mensaje="errorvalidacion.Nombre[0]"></Cvalidation></label>
                </div>                  
                <div class="col-lg-12">
                    <label >Apellido</label>
                        <input type="text" v-model="Usuario.Apellido"  class="form-control form-control-sm" placeholder="Apellido" />
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Apellido" :Mensaje="errorvalidacion.Apellido[0]"></Cvalidation></label>
                    
                    <label  style="color:red"></label>
                </div>
                <div class="col-lg-12">
                    <label >Correo </label>
                        <input type="text" v-model="Usuario.Candado" class="form-control form-control-sm"
                                placeholder="Correo"  name="Correo" />
                                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Candado" :Mensaje="this.errorvalidacion.Candado[0]"></Cvalidation></label>
                 
                </div>                  
                <div class="col-lg-12 ">
                   
                    <label >Contraseña</label>
                        <input type="password" v-model="Usuario.Seguridad"  class="form-control form-control-sm" placeholder="Contraseña" />
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Seguridad" :Mensaje="errorvalidacion.Seguridad[0]"></Cvalidation></label>
                  
                    <label  style="color:red"></label>
                </div>
            </div>
            <!--Fin body del panel-->
            

    </div>
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from '@/components/Cbtnsave.vue'
import Cvalidation from '@/components/Cvalidation.vue'
export default {
    name:'Form',
    props:['IdCliente','objcliente','poBtnSave'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'Usuario',//Sirve para donde va regresar
            Usuario:{
                IdUsuario:0,
                Nombre:"",
                Apellido:"",
                Candado:"",
                Seguridad:"",
                IdCliente:"",
                Foto:"",
            },
            urlApi:"usuario/recovery",
            errorvalidacion:[],
        }
    },
    components:{
        Cbtnsave,
        Cvalidation
    },
    methods :
    {
       async Guardar()
        {   
           //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;

            await this.$http.post(
                'usuario/AddUsuMonitoreo',
                this.Usuario,
            ).then( (res) => {
                 this.poBtnSave.disableBtn=false;  
         this.poBtnSave.toast=1;
                $('#ModalForm').modal('hide');
                this.bus.$emit('List'); 
               
            }).catch( err => {
                this.errorvalidacion=err.response.data.message.errores;
                this.poBtnSave.disableBtn=false;
                this.poBtnSave.toast=2;  
            });
        },
         Limpiar()
        {
            this.Usuario.IdUsuario=0;
            this.Usuario.Candado="";
            this.Usuario.Seguridad="";
            this.Usuario.Nombre="";
            this.Usuario.Apellido="";
            this.Usuario.Foto="";
            this.errorvalidacion=[""];
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdUsuario: this.Usuario.IdUsuario}
                }
            ).then( (res) => {
                this.Usuario=res.data.data.Usuario;
            });
        }
    },
    created() {
        this.bus.$off('Nuevo');
        
        //Este es para modal
        this.bus.$on('Nuevo',(data,Id)=> 
        {
              this.poBtnSave.disableBtn=false; 
             this.bus.$off('Save');
              this.bus.$on('Save',()=>
                {
                this.Guardar();
                });
            
            this.Limpiar();
            this.Usuario.IdUsuario=Id;
        
            this.Usuario.IdCliente=this.objcliente.IdCliente;
            if (Id>0)
            {
               this.get_one();
            }
        });
      
    }
}
</script>