<template>
    <div class="row  justify-content-center">
        <div class="col-lg-8">
            <label >Nombre</label>
            <input  class="form-control" v-model=" categoriavehiculo.Nombre" placeholder="Nombre">
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
        props:['IdCategoria','poBtnSave'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'categoriavehiculo',//Sirve para donde va regresar
         categoriavehiculo:{
            
                IdCategoria:0,
                Nombre:""

            },
            urlApi:"categoriavehiculo/recovery",
            errorvalidacion:[],
            virtualExiste:[]
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
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;
            let formData = new FormData();
            formData.set('IdCategoria',this.categoriavehiculo.IdCategoria);
            formData.set('Nombre',this.categoriavehiculo.Nombre);
            
            await this.$http.post(
                'categoriavehiculo/post',
                formData,
                {
                headers: {
                    'Content-Type': 'multipart/form-data'
                    
                }
                },
            ).then( (res) => {
                this.virtualExiste = res.data.data.categoriavehiculo;
                
                this.poBtnSave.disableBtn=false;  
                this.poBtnSave.toast=1;

                    $('#ModalForm').modal('hide');
                    this.bus.$emit('List'); 
                        
            }).catch( err => {
                this.errorvalidacion=err.response.data.message.errores;
                this.$toast.warning('El auto virtual solo se puede crear una vez');
                this.poBtnSave.disableBtn=false;
                // this.poBtnSave.toast=2;  
            });
       
        },
         Limpiar()
        {

            this.categoriavehiculo.IdCategoria= 0,
            this.categoriavehiculo.Nombre="",
            this.errorvalidacion=[""]
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdCategoria: this.categoriavehiculo.IdCategoria}
                }
            ).then( (res) => {
                this.categoriavehiculo.IdCategoria= res.data.data.categoriavehiculo.IdCategoria;
                this.categoriavehiculo.Nombre=res.data.data.categoriavehiculo.Nombre;
 
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
            this.categoriavehiculo.IdCategoria=Id;
            this.get_one();
            }
             this.bus.$emit('Desbloqueo',false);
            
        });
        if (this.Id!=undefined)
        {
            this.categoriavehiculo.IdCategoria=this.Id;
            this.get_one();
        }

    }
}
</script>