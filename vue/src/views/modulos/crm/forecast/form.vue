<template>
    <div class="row  justify-content-center">
        <div class="col-lg-8">
            <label >Nombre</label>
            <input  class="form-control" v-model=" objtipoproceso.Nombre" placeholder="Nombre">
            <Cvalidation v-if="this.errorvalidacion.Nombre" :Mensaje="errorvalidacion.Nombre[0]"></Cvalidation>
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
        props:['IdTipoProceso','poBtnSave'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'objtipoproceso',//Sirve para donde va regresar

         objtipoproceso:{
            
                IdTipoProceso:0,
                Nombre:""

            },
            urlApi:"crmtipoproceso/recovery",
            errorvalidacion:[],
        }
    },
    components:{
        Cbtnsave,
        Cvalidation,
    },
    methods :
    {
    
       async Guardar()
        {
            //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;
            let formData = new FormData();
            formData.set('IdTipoProceso',this.objtipoproceso.IdTipoProceso);
            formData.set('Nombre',this.objtipoproceso.Nombre);
            
            await this.$http.post(
                'crmtipoproceso/post',
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

            this.objtipoproceso.IdTipoProceso= 0,
            this.objtipoproceso.Nombre="",
            this.errorvalidacion=[""]
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdTipoProceso: this.objtipoproceso.IdTipoProceso}
                }
            ).then( (res) => {
              
  
            this.objtipoproceso.IdTipoProceso= res.data.data.tipoproceso.IdTipoProceso;
            this.objtipoproceso.Nombre=res.data.data.tipoproceso.Nombre;
 
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
            this.objtipoproceso.IdTipoProceso=Id;
            this.get_one();
            }
             this.bus.$emit('Desbloqueo',false);
            
        });
        if (this.Id!=undefined)
        {
            this.objtipoproceso.IdTipoProceso=this.Id;
            this.get_one();
        }

    }
}
</script>