<template>
 
    <div >
 
        <!--Fin head del panel-->
            <div class="row">
          
                <div class="col-lg-12 form-group">
                          
                        <label >Nombre</label>
                        <input type="text" v-model="correo.Titulo" class="form-control"
                               placeholder="Nombre" id="Nombre"
                               name="Nombre" />
                        <Cvalidation v-if="this.errorvalidacion.Nombre" :Mensaje="'Campo obligatorio'"></Cvalidation>
                </div>

                   <div class="col-lg-12 form-group">
                          
                        <label >Leyenda</label>
                        <input type="text" v-model="correo.Leyenda" class="form-control"
                               placeholder="Leyenda" id="Leyenda"
                               name="Nombre" />
                        <Cvalidation v-if="this.errorvalidacion.Leyenda" :Mensaje="'Campo obligatorio'"></Cvalidation>
                </div>

                
                   <div class="col-lg-12 form-group">
                          
                        <label >Pie</label>
                        <input type="text" v-model="correo.Pie" class="form-control"
                               placeholder="Leyenda"  />
                        <Cvalidation v-if="this.errorvalidacion.Pie" :Mensaje="'Campo obligatorio'"></Cvalidation>
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
    props:['poBtnSave'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'caja',//Sirve para donde va regresar
           correo:{
               
            },
            errorvalidacion:[]
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
            this.$http.post(
                'correo/post',
                this.correo 
            ).then( (res) => {
               this.poBtnSave.disableBtn=false;  
                this.poBtnSave.toast=1;
               this.bus.$emit('List');
               $('#ModalForm').modal('hide');
                
            }).catch( err => {
               
                this.errorvalidacion=err.response.data.message.errores;
                 this.poBtnSave.disableBtn=false;
                    this.poBtnSave.toast=2;  
            });
        },
         Limpiar()
        {
                this.correo={},
                this.errorvalidacion=[]
        },
        get_one()
        {
            this.$http.get(
                'correo/recovery',
                {
                    params:{IdCorreo: this.correo.IdCorreo}
                }
            ).then( (res) => {
                this.correo =res.data.data.correo;
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
            this.correo.IdCorreo=Id;
            this.get_one();
            }
             this.bus.$emit('Desbloqueo',false);
            
        });

    }
}
</script>