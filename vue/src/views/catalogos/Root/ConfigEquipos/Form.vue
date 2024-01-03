<template>
 
    <div >
 
        <!--Fin head del panel-->

        <div class="card-body">
            <div class="row">
          
                <div class="col-lg-12 form-group">
                          
                    <span class="has-float-label">
                        <label for="Nombre" class="labeltam">Nombre</label>
                        <input type="text" v-model="equipamiento.Nombre" class="form-control"
                               placeholder="Nombre" id="Nombre"
                               name="Nombre" />
                        <Cvalidation v-if="this.errorvalidacion.Nombre" :Mensaje="'Campo obligatorio'"></Cvalidation>
                    </span>
                </div>
    
            </div>
            <!--Fin body del panel-->
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
           equipamiento:{
               
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
                'equipamiento/post',
                this.equipamiento 
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


                 this.equipamiento={},
             
                     this.errorvalidacion=[]
        },
        get_one()
        {
            this.$http.get(
                'equipamiento/recovery',
                {
                    params:{IdEquipamiento: this.equipamiento.IdEquipamiento}
                }
            ).then( (res) => {
                this.equipamiento =res.data.data.equipamiento;
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
            this.equipamiento.IdEquipamiento=Id;
            this.get_one();
            }
             this.bus.$emit('Desbloqueo',false);
            
        });

    }
}
</script>