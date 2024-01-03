<template>
    <div>
    
            <div class="row">
                <div class="col-lg-12">

                    <div class="form-group row">
                        <div class="col-lg-8">
                            <span class="has-float-label">
                            <label for="Nombre" class="labeltam">Nombre </label>
                                <input :readonly="true" type="text" v-model="empresa.Nombre" class="form-control form-control-sm"
                                     placeholder="Nombre" id="Nombre"
                                    name="Nombre" />
                              <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Nombre" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
                            </span>
                        </div>
                        <div class="col-lg-4">
                            <span class="has-float-label">
                            <label for="RFC"  class="labeltam">Número Fiscal </label>
                                <input type="text" v-model="empresa.RFC" class="form-control form-control-sm"  placeholder="Número Fiscal" />
                               <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.RFC" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
                            </span>
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-lg-8">
                            <span class="has-float-label">
                            <label for="Direccion" class="labeltam">Dirección </label>
                                <input type="text" v-model="empresa.Direccion"  class="form-control form-control-sm"
                                    placeholder="Direccion" />
                            </span>
                            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Direccion" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
                        </div>
                         <div class="col-lg-4">
                            <span class="has-float-label">
                            <label for="Telefono" class="labeltam">Teléfono </label>
                                <input type="text" v-model="empresa.Telefono"
                                    class="form-control form-control-sm"  placeholder="Telefono" />
                                
                            </span>
                            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Telefono" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
                        </div>
                    </div>
                    
                      <div class="form-group row">
                           <div class="col-lg-4">
                            <span class="has-float-label">
                            <label for="Pais" class="labeltam">País </label>
                                <input type="text" v-model="empresa.Pais"
                                    class="form-control form-control-sm"  placeholder="Pais" />
                                
                            </span>
                          <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Pais" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
                        </div>
                        
                        <div class="col-lg-4">
                            <span class="has-float-label">
                            <label for="Estado" class="labeltam">Estado </label>
                                <input type="text" v-model="empresa.Estado"  class="form-control form-control-sm"
                                    placeholder="Estado" />
                            </span>
                            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Estado" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
                        </div>

                        <div class="col-lg-4">
                            <span class="has-float-label">
                            <label for="Ciudad" class="labeltam">Ciudad </label>
                                <input type="text" v-model="empresa.Ciudad"  class="form-control form-control-sm"
                                    
                                    placeholder="Ciudad" />
                              
                            </span>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Ciudad" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
                        </div>
                        
                        
                    </div>


                    <div class="form-group row">
                        <div class="col-lg-6">
                            <span class="has-float-label">
                            <label for="Correo" class="labeltam">Correo </label>
                                <input type="text" v-model="empresa.Correo" class="form-control form-control-sm"
                                     placeholder="Correo" />
                             
                            </span>
                            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Correo" :Mensaje="this.errorvalidacion.Correo[0]"></Cvalidation></label>
                        </div>
                        <div class="col-lg-6">
                            <span class="has-float-label">
                            <label for="CP" class="labeltam">Código Postal </label>
                                <input type="text" v-model="empresa.CP"
                                    class="form-control form-control-sm"  placeholder="CP" />
                                
                            </span>
                          <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.CP" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
                        </div>
                        <div class="col-lg-12">
                            <h4>Zona horaria</h4>
                            <span class="text-muted">Seleccione la zona de la sucursal</span>
                        </div>
                        <div class="col-lg-6">
                            <select class="form-control" v-model="ZonaHoraria" >
                                <option v-for="(item, index) in this.$moment.tz.names()" :key="index" :value="item">{{item}}</option>
                            </select>
                        </div>
                    </div>

                       <div class="form-group form-row justify-content-center">
                         <div class="col-md-6 col-lg-6 image-usuario">
                            <div class="avatar-upload">
                                <div class="avatar-edit">
                                    <input id="archivo"  @change="uploadImage()" ref="file"  type="file" name="archivo"  accept=".png, .jpg, .jpeg">
                                    <label for="archivo"></label>
                                </div>
                                <div class="avatar-preview">
                                    <div id="imagePreview2" style="background-image: url(images/avatar2.jpg);">
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>

                <!--    <div class="justify-content-center row">
                         <div class="col-lg-4" >
                             
                            <img  class="img-thumbnail" :src="Img">
                            <input  type="file" id="archivo"  name="archivo" ref="file">
                        </div>
                         
                    </div>-->
                </div>
                <!--fin col-6-->

                <div class="col-sm-12">
                    <div class="form-group row">
                        <div class="col-sm-8">
                        </div>
                       
                        <div class="col-sm-4">
                        <!--  <Cbtnsave :IsModal="Modal" :RegresarA="FormName"></Cbtnsave>-->
                        </div>
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
    name:"Form",
    props:['poBtnSave'],
    data() {
        return {  
            Modal:true,//Sirve pra los botones de guardado
            FormName:'usuarios',//Sirve para donde va regresar
            empresa:{
                IdEmpresa:0,
                Nombre:"",
                RFC:"",
                Direccion:"",
                Telefono:"",
                Correo:"",
                Ciudad:"",
                Pais:"",
                Suspendido:"",
                IdPuesto:"1",
                Imagen:"Imagen.png",
                Type:".png",
                CotMant:0,
                ContServ:0,
                Estado:'',
                CP:"",
                Logo:'',
            },
            urlApi:"empresa/recovery",
            ReadOnly:false,
            Img:null,
            errorvalidacion:[],
            Id:0,
            ZonaHoraria: '',
            ZonaOriginal: '',
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
        uploadImage()
        {
            const image = this.$refs.file.files[0];
            const reader = new FileReader();
            var img="";
            reader.readAsDataURL(image);
            reader.onload= e =>{
                this.Img = e.target.result;   
                this.readURL();   
                /* const image = this.$refs.file.files[0];
                const reader = new FileReader();
                var img="";
                reader.readAsDataURL(image);
                reader.onload= e =>{
                this.Img = e.target.result;     */
            }
        },
        readURL() {
            $$('#imagePreview2').css('background-image', 'url(' + this.Img + ')');
            $$('#imagePreview2').hide();
            $$('#imagePreview2').fadeIn(650);
        },
        async Guardar()
        {
            let formData = new FormData();
            formData.set('IdEmpresa',this.empresa.IdEmpresa);
            formData.set('Nombre',this.empresa.Nombre);
            formData.set('RFC', this.empresa.RFC);
            formData.set('Direccion',this.empresa.Direccion);
            formData.set('Telefono',this.empresa.Telefono);
            formData.set('Correo', this.empresa.Correo);
            formData.set('Ciudad', this.empresa.Ciudad);
            formData.set('Pais', this.empresa.Pais);
            formData.set('Suspendido', this.empresa.Suspendido);
            formData.set('IdPuesto', this.empresa.IdPuesto);
            formData.set('NombreFoto', this.empresa.Logo);
            formData.set('Type', this.empresa.Type);
            formData.set('CotMant', this.empresa.CotMant);
            formData.set('CotServ', this.empresa.ContServ);
            formData.set('Estado', this.empresa.Estado);
            formData.set('CP', this.empresa.CP);
            formData.set('ZonaHoraria', this.ZonaHoraria);
            
            let file = this.$refs.file.files[0];
            formData.append('File',file);
            //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;

            await this.$http.post(
                'empresa/post',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                },
            ).then( (res) => {
                this.poBtnSave.disableBtn=false;  
                this.poBtnSave.toast=1;
                //sessionStorage.setItem('ZonaHoraria', this.ZonaHoraria);
                $('#ModalCuenta').modal('hide');
                
                if(this.ZonaHoraria != this.ZonaOriginal)
                {
                    this.$toast.info('La zona horaria ha cambiado, reiniciaremos su sesión');
                    this.getNewlog();
                }
            }).catch( err => {
                this.errorvalidacion=err.response.data.message.errores;
                this.poBtnSave.disableBtn=false;
                this.poBtnSave.toast=2;  
            });
        },
        Limpiar()
        {
            this.Img='';
            this.empresa.IdEmpresa=0;
            this.empresa.Nombre="";
            this.empresa.RFC="";
            this.empresa.Direccion="";
            this.empresa.Telefono="";
            this.empresa.Correo="";
            this.empresa.Ciudad="";
            this.empresa.Pais="";
            this.empresa.Suspendido="";
            this.empresa.IdPuesto="1";
            this.empresa.Imagen="Imagen.png";
            this.empresa.Type=".png";
            this.empresa.CotMant=0;
            this.empresa.ContServ=0;
            this.empresa.Estado='';
            this.empresa.CP="";
            const  input  = this.$refs.file;
            this.errorvalidacion=[""];
            this.readURL();
            this.ZonaOriginal = '';
        },
        get_one()
        {
            let user = JSON.parse(sessionStorage.getItem('user'));
            
            this.$http.get(
                this.urlApi,
                {
                    params:{}
                }
            ).then( (res) => {
                this.empresa = res.data.data.empresa;
                this.Img = res.data.data.RutaFile+this.empresa.Logo;
                this.ZonaHoraria = res.data.ZonaHoraria;
                this.ZonaOriginal = res.data.ZonaHoraria;
                this.readURL();
            });
        },
        getNewlog()
        {
            let user = JSON.parse(sessionStorage.getItem('user'));
            
            let objUser = {
                IdUsuario: user.IdUsuario
            }
            this.$http.post(
                'loginroot/post',
                objUser
            ).then((res) => {
                if (res.data.status==true)
                {
                    this.$store.dispatch('login', res.data.data);   
                    let IdEmpresa = res.data.data.usuario.IdEmpresa;
                    
                    if(IdEmpresa > 0)
                    {
                        this.$router.push({name:'despacho',params:{}});
                        this.$router.go('/despacho');
                    }
                }   
            }).catch( (err) => {
                this.$toast.info(err.response.data.message);
                this.$store.commit('auth_error');
                this.$store.localStorage.removeItem('user_token');
                this.$store.reject(err);
            });
        }
    },
    created()
    {
        var empresa = JSON.parse(sessionStorage.getItem('empresa'));
        this.Id=empresa.IdEmpresa;
        this.bus.$off('NewCuenta');
        this.bus.$on('NewCuenta',()=>
        {
            this.poBtnSave.disableBtn=false; 
            this.bus.$off('Save');
            this.bus.$on('Save',()=>
            {
                this.Guardar();
            });

            this.Limpiar();

            if (this.Id>0) 
            {
                this.empresa.IdEmpresa=this.Id;
                this.get_one();
            }else{
                this.get_one();
                this.ReadOnly=true;
            }
        });
    }
}
</script>