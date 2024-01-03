<template>
    <div>
   
        <!--Fin head del panel-->

       
            <div class="row">

                <div class="col-lg-12 ">
                        <label >Nombre</label>
                        <input type="text" v-model="categoriapersonal.Nombre" class="form-control"
                               placeholder="Nombre" id="Nombre"
                               name="Nombre" />
                        <Cvalidation v-if="this.errorvalidacion.Nombre" :Mensaje="errorvalidacion.Nombre[0]"></Cvalidation>
        </div>
    </div>

    </div>
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from '@/components/Cbtnsave.vue'
import Cvalidation from '@/components/Cvalidation.vue'
export default {
    name:'Form',
    props:['IdTipoU','poBtnSave'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'tipounidad',//Sirve para donde va regresar
         categoriapersonal:{
            
                IdCategoria:0,
                Nombre:"",
              
               IdSucursal:"",
        
            

            },
            urlApi:"categoriapersonal/recovery",
            errorvalidacion:[],
        }
    },
    components:{
        Cbtnsave,Cvalidation
    },
    methods :
    {
    
       async Guardar()
        {
            //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;
       let formData = new FormData();

            formData.set('IdCategoria',this.categoriapersonal.IdCategoria);
            formData.set('Nombre',this.categoriapersonal.Nombre);
            formData.set('IdSucursal', this.categoriapersonal.IdSucursal);
            
     await this.$http.post(
        'categoriapersonal/post',
        formData,
        {
          headers: {
            'Content-Type': 'multipart/form-data'
            
          }
        },
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
            this.categoriapersonal.IdCategoria=0,
            this.categoriapersonal.Nombre="",   
            this. categoriapersonal.IdSucursal=""
            this.errorvalidacion=[""];
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdCategoria: this.categoriapersonal.IdCategoria}
                }
            ).then( (res) => {
                this. categoriapersonal.IdCategoria=res.data.data. categoriapersonal.IdCategoria;
                this. categoriapersonal.Nombre=res.data.data. categoriapersonal.Nombre;
               this. categoriapersonal.IdSucursal=res.data.data. categoriapersonal.IdSucursal;
              
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
            if (Id>0)
            {
            this. categoriapersonal.IdCategoria=Id;
            this.get_one();
            }
            this.bus.$emit('Desbloqueo',false);
            
            
        });
        if (this.Id!=undefined)
        {
            this. categoriapersonal.IdCategoria=this.Id;
            this.get_one();
        }

    }
}
</script>