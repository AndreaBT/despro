<template>
<div>
      <div class="row">
        <div class="col-lg-12">
          <div class="form-group">
            <label >Usuario</label>
            <input  class="form-control" v-model="trabajador.Correo"  placeholder="Nombre">
            <Cvalidation v-if="this.errorvalidacion.Correo" :Mensaje="errorvalidacion.Correo[0]"></Cvalidation>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="form-group">
            <label >Contraseña</label>
            <input v-model="trabajador.Pass" type="password"  class="form-control"   placeholder="Nombre">
            <Cvalidation v-if="this.errorvalidacion.Pass" :Mensaje="errorvalidacion.Pass[0]"></Cvalidation>
          </div>
        </div>

         <div class="col-lg-12">
          <div class="form-group">
            <label >Repetir Contraseña</label>
            <input v-model="trabajador.Pass2" type="password"  class="form-control"   placeholder="Nombre">
            <Cvalidation v-if="this.errorvalidacion.Password_Confirmacion" :Mensaje="errorvalidacion.Password_Confirmacion[0]"></Cvalidation>
            
          </div>
        </div>
    </div>

</div>
</template>
<script>
export default {
   name:'ChangePass',
    props:['poBtnSave'],
    data() {
        return {
            trabajador:{},
            errorvalidacion:[]
        }
    },
   methods: {
        get_one()
        {
            this.$http.get(
                'trabajador/recovery',
                {
                    params:{IdTrabajador: this.trabajador.IdTrabajador}
                }
            ).then( (res) => {
              this.trabajador=res.data.data.trabajador;
              this.trabajador.Pass2='';
              
            });
        },
          async Guardar()
        {
          let usuario='';
            let correo='';
            if (this.trabajador.Usuario!='')
            {
                usuario=this.trabajador.Usuario.trim();
            }
            if (this.trabajador.Correo!='')
            {
                correo=this.trabajador.Correo.trim();
            }
           //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;

            let formData = new FormData();

            formData.set('IdTrabajador',this.trabajador.IdTrabajador);
            formData.set('Usuario', usuario);
            formData.set('Pass', this.trabajador.Pass);
            formData.set('Pass2', this.trabajador.Pass2);
            formData.set('IdUsuario', this.trabajador.IdUsuario);
            formData.set('Correo', correo);

        await this.$http.post(
            'trabajador/ChangePass',
            formData,
            {
            headers: {
                'Content-Type': 'multipart/form-data'
                
            }
            },
        ).then( (res) => {
             this.poBtnSave.disableBtn=false;  
         this.poBtnSave.toast=1;
            $('#ModalChange').modal('hide');
            this.bus.$emit('List'); 

        }).catch( err => {
            this.poBtnSave.disableBtn=false;
           this.poBtnSave.toast=2;  
        this.errorvalidacion=err.response.data.message.errores;
      });
       
        },
        Limpiar()
        {
            this.errorvalidacion=[];
            this.trabajador={};

        }
       
   },
   created() {
       this.bus.$off('ChangeP');
      
           this.bus.$on('ChangeP',(Id)=> 
        {
            this.poBtnSave.disableBtn=false;  
           this.bus.$off('Save');
            this.bus.$on('Save',()=>
            {
              this.Guardar();
            });

            this.trabajador.IdTrabajador=Id;
            this.get_one();
            
        });

        
   },
}
</script>
