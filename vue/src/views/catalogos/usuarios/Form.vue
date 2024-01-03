<template>
    <div>
    
    <form autocomplete="off" id="FormUsuario" class="form-horizontal" method="post" enctype="multipart/form-data">
    
    <div class="card mb-3">
 
        <!--Fin head del panel-->

        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">

                    <div class="form-group row">
                        <div class="col-lg-6">
                            <span class="has-float-label">
                            <label for="Nombre" class="labeltam">Nombre </label>
                                <input type="text" v-model="Usuario.Nombre" class="form-control form-control-sm"
                                     placeholder="Nombre" id="Nombre"
                                    name="Nombre" />
                              
                            </span>
                        </div>
                        <div class="col-lg-6">
                            <span class="has-float-label">
                            <label for="Apellido"  class="labeltam">Apellido </label>
                                <input type="text" v-model="Usuario.Apellido" class="form-control form-control-sm"  placeholder="Apellido" />
                               
                            </span>
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-lg-6">
                            <span class="has-float-label">
                            <label for="Candado" class="labeltam">Usuario </label>
                                <input type="text" v-model="Usuario.Usuario"  class="form-control form-control-sm"
                                    
                                    placeholder="Usuario" />
                              
                            </span>
                            <label id="lblmsuser" style="color:red"></label>
                        </div>
                         <div class="col-lg-6">
                            <span class="has-float-label">
                            <label for="Seguridad" class="labeltam">Contrase&ntilde;a </label>
                                <input type="password" v-model="Usuario.Contrasenia"
                                    class="form-control form-control-sm"  placeholder="Contraseña" />
                                
                            </span>
                            <label id="lblmspass" style="color:red"></label>
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-lg-6">
                            <span class="has-float-label">
                            <label for="Correo" class="labeltam">Correo </label>
                                <input type="text" v-model="Usuario.Correo" class="form-control form-control-sm"
                                     placeholder="Correo" />
                             
                            </span>
                        </div>
                          <div class="col-lg-6" >
                            <input type="file" id="archivo"  name="archivo">
                        </div>
                    </div>

                    <div class="form-group row">
                      
                    </div>

                </div>
                <!--fin col-6-->

                <div class="col-sm-12">
                    <div class="form-group row">
                        <div class="col-sm-8">
                        </div>
                       
                        <div class="col-sm-4">
                          <btnsave :IsModal="Modal" :RegresarA="FormName"></btnsave>
                        </div>
                    </div>
                </div>

            </div>
            <!--Fin body del panel-->
        </div>
    </div>
</form>

    </div>
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from '@/components/Cbtnsave'
export default {
    name:"Form",
    props:['Id'],
    data() {
        return {  
            Modal:true,//Sirve pra los botones de guardado
            FormName:'usuarios',//Sirve para donde va regresar
            Usuario:{
                IdEmpleado:0,
                IdUsuario:0,
                Nombre:"",
                Apellido:"",
                Usuario:"",
                Contrasenia:"",
                Correo:"",
                File:"",
                Archivo:""
            },
            urlApi:"empleados/recovery.api"
        }
    },
    components:{
        Cbtnsave
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
       async Guardar()
        {
            console.log('Veces');
       let formData = new FormData();

            formData.set('IdEmpleado',this.Usuario.IdEmpleado);
             formData.set('IdUsuario',this.Usuario.IdUsuario);
            formData.set('Nombre', this.Usuario.Nombre);
            formData.set('Apellido',this.Usuario.Apellido);
            formData.set('RFC','');
            formData.set('Correo', this.Usuario.Correo);
            formData.set('FechaNac', this.Usuario.Telefono);
            formData.set('cbUsuario', 1);
            formData.set('Usuario', this.Usuario.Usuario);
            formData.set('Contrasenia', this.Usuario.Contrasenia);
       
 

     await this.$http.post(
        'empleados/post.api',
        formData,
        {
          headers: {
            'Content-Type': 'multipart/form-data'
            
          }
        },
      ).then( (res) => {
        
           this.$swal({
            toast: true,
            position: 'top-end',
            showConfirmButton: true,
            timer: 3000,
            type: 'succes',
            title: 'Informacion Guardada'
           
        });

          if (this.Id==undefined){
                $('#ModalForm').modal('hide');
                 this.bus.$emit('List'); 
                 
          }
          else{
              this.$router.push({name:this.FormName});
          }
       

      }).catch( err => {

        console.log('Error');

      });
       
        },
         Limpiar()
        {
              this.Usuario.Nombre="",
                this.Usuario.Apellido="",
                this.Usuario.Usuario="",
                this.Usuario.Contrasenia="",
                this.Usuario.Correo=""
              
              
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdEmpleado: this.Usuario.IdEmpleado}
                }
            ).then( (res) => {
              
                this.Usuario.IdUsuario =res.data.ousuario.IdUsuario;
                this.Usuario.Nombre =res.data.oempleado.Nombre;
                this.Usuario.Apellido =res.data.oempleado.Apellido;
                this.Usuario.Usuario =res.data.ousuario.Candado;
                this.Usuario.Correo =res.data.oempleado.Correo;
               
              
            });
        }
    },
    created() {
        
        this.bus.$off('Save');
        this.bus.$off('Nuevo');

        this.bus.$on('Save',()=>
        {
           this.Guardar();
        });
        
        //Este es para modal
        this.bus.$on('Nuevo',(data,Id)=> 
        {
             this.Limpiar();
            if (Id>0) 
            {
            this.Usuario.IdEmpleado=Id;
            this.get_one();
            }
            
        });
        if (this.Id!=undefined)
        {
            this.Usuario.IdEmpleado=this.Id;
            this.get_one();
        }

    }
}
</script>