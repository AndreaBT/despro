<template>
    <div>
           <div class="modal-body form-cotizacion">
                    <div class="form-group form-row justify-content-center">
                         <div class="col-md-6 col-lg-6 image-usuario">
                            <div class="avatar-upload">
                                <div class="avatar-edit">
                                    <input id="file"  @change="uploadImage()"  ref="file"  type="file" name="myfile"  accept=".png, .jpg, .jpeg">
                                    <label for="file"></label>
                                </div>
                                <div class="avatar-preview">
                                    <div id="imagePreview" style="background-image: url(images/avatar2.jpg);">
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6 col-lg-6">
                            <label>Nombre</label>
                            <input :readonly="ReadOnly" type="text" v-model="usuario.Nombre"  class="form-control" placeholder="Escribir Nombre">
                            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Nombre" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <label>Apellido</label>
                            <input type="text" v-model="usuario.Apellido" class="form-control" placeholder="Escribir Apellido">
                            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Apellido" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
                        </div>
                    </div>

                    <div class="form-group form-row mt-2 ">
                        <div class="col-md-12 col-lg-12 mb-2"><hr></div>
                    </div>
                   
                    <div class="form-group form-row">
                        <div class="col-md-6 col-lg-6">
                            <label>Usuario</label>
                            <input type="text" v-model="usuario.Candado"  class="form-control" placeholder="Escribir Usuario">
                            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Candado" :Mensaje="this.errorvalidacion.Candado[0]"></Cvalidation></label>
                        </div>
                        <div class="col-md-6 col-lg-6"></div>
                    </div>
                    <div class="form-group form-row">
                        <div class="col-md-6 col-lg-6">
                            <label>Contraseña</label>
                            <input type="password" v-model="usuario.Seguridad" class="form-control" placeholder="Escribir Contraseña">
                            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Seguridad" :Mensaje="this.errorvalidacion.Seguridad[0]"></Cvalidation></label>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <label>Confirmar contraseña</label>
                            <input type="password" v-model="usuario.Seguridad2" class="form-control" placeholder="Confirmar Contraseña">
                            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Confirmacion" :Mensaje="this.errorvalidacion.Confirmacion[0]"></Cvalidation></label>
                        </div>
                    </div>
                </div>
    
        
            <!--Fin body del panel-->
        </div>
   
</template>
<script>
import $$ from "jquery"
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from '@/components/Cbtnsave'
import Cvalidation from '@/components/Cvalidation.vue'

export default {
    name:"Perfil",
      props:['oBtnSaveperf'],
    data() {
        return {  
            Modal:true,//Sirve pra los botones de guardado
            FormName:'usuarios',//Sirve para donde va regresar
            usuario:{
                IdUsuario:0,
                Nombre:"",
                Apellido:"",
                Candado:"",
                Seguridad:"",
                Correo:"",
                Seguridad2:"",
                Imagen:"Imagen.png",
                Type:".png",
                Logo:'',

            },
            urlApi:"usuario/recovery",
            ReadOnly:false,
            Img:null,
            errorvalidacion:[],
        }
    },
    components:{
        Cbtnsave,Cvalidation
    },
    methods :
    {
         cargarimagen(tipo)
        {
            if (tipo==1)
            {
                this.File=this.$refs.file.files[0];
            }
            else if (tipo==2)
            {
              this.FileFirma=this.$refs.file2.files[0];   
            }
        },
         uploadImage(){
                
                const image = this.$refs.file.files[0];
                const reader = new FileReader();
                var img="";
                reader.readAsDataURL(image);
                reader.onload= e =>{
                this.Img = e.target.result;  
                this.readURL();   
            }
        },
       async GuardarPerfil()
        {
       let formData = new FormData();
            formData.set('IdUsuario',this.usuario.IdUsuario);
             formData.set('Nombre',this.usuario.Nombre);
             formData.set('Apellido',this.usuario.Apellido);
             formData.set('Candado',this.usuario.Candado);
             formData.set('Seguridad2',this.usuario.Seguridad2);
             formData.set('Seguridad',this.usuario.Seguridad);
            formData.set('NombreFoto', this.usuario.Foto2);
            let file = this.$refs.file.files[0];
            formData.append('File',file);
       //deshabilita botones
            this.oBtnSaveperf.toast=0; 
            this.oBtnSaveperf.disableBtn=true;
     await this.$http.post(
        'usuario/UpdateProfile',
        formData,
        {
          headers: {
            'Content-Type': 'multipart/form-data'
            
          }
        },
      ).then( (res) => {
            
            this.oBtnSaveperf.disableBtn=false;  
                this.oBtnSaveperf.toast=1;
            let nombrecompleto=this.usuario.Nombre +' '+this.usuario.Apellido;
            let objeto ={Foto:res.data.data.usuario.Foto2,Nombre:nombrecompleto}
            this.bus.$emit('ChangePerfil',objeto);
                $('#ModalPerfil').modal('hide');
      }).catch( err => {
          this.errorvalidacion=err.response.data.message.errores;
           this.oBtnSaveperf.disableBtn=false;
           this.oBtnSaveperf.toast=2; 
      });
       
        },
         Limpiar()
        {
              this.Img='';
            this.usuario={
                IdUsuario:0,
                Nombre:"",
                Apellido:"",
                Candado:"",
                Seguridad:"",
                Correo:"",
                Seguridad2:"",
                Imagen:"",
                Type:"",
                Logo:'',

            },
           this.readURL();  
            this.errorvalidacion=[];
              
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdUsuario:this.usuario.IdUsuario}
                }
            ).then( (res) => {
              this.usuario=res.data.data.Usuario;
              this.usuario.Seguridad='';
              this.usuario.Seguridad2='';
              this.Img= res.data.data.UrlFoto+ this.usuario.Foto2;
              this.readURL();
            });
        },
       
         readURL() {
              $$('#imagePreview').css('background-image', 'url(' + this.Img + ')');
                $$('#imagePreview').hide();
                $$('#imagePreview').fadeIn(650);
        }
    },
    created() {
        
        
        this.bus.$off('EmitPerfil');

        
        

        //Este es para modal
        this.bus.$on('EmitPerfil',(Id)=> 
        {
             this.oBtnSaveperf.disableBtn=false; 
            this.Limpiar();
            this.bus.$off('Save');
            this.bus.$on('Save',()=>
            {
            this.GuardarPerfil();
            });

            if (Id>0) 
            {
                this.usuario.IdUsuario=Id;
                this.get_one();
            }
           
        });
      

    }
}
</script>