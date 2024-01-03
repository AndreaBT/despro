<template>
    <div>
   
 
                <div class="row justify-content-center">
                    
                    <div class="col-lg-8">
                       
                            <label >Material </label>
                            <input type="text" v-model="material.NomMaterial" class="form-control"
                                placeholder="Nombre del Material" />
                      
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.NomMaterial" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
                    </div>
                    <div class="col-lg-4 ">
                        
                            <label >Precio </label>
                            <vue-numeric placeholder="$ 0.00"  :minus="false"  class="form-control "  currency="$" separator="," :precision="2" v-model="material.Precio"></vue-numeric>
                           
                      
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Precio" :Mensaje="this.errorvalidacion.Precio[1]"></Cvalidation></label>
                    </div>
                    <!--fin col-6-->
                </div>
                <!--Fin body del panel-->
            </div>
     
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from '@/components/Cbtnsave.vue'
import Clist from '@/components/Clist.vue';
import Cvalidation from '@/components/Cvalidation.vue'

export default {
    name:'Form',
    props:['NameList','poBtnSave'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'tipounidad',//Sirve para donde va regresar
            material:{            
                IdMaterial:0,
                NomMaterial:"",
                Precio:"",
            },
            urlApi:"cotizacion_material/recovery",
            errorvalidacion:[]
        }
    },
    components:{
        Cbtnsave,
        Clist,Cvalidation,
    },
    methods :
    {
    
       async Guardar()
        {
             //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;
            await this.$http.post(
                'cotizacion_material/post',
                this.material
                ,
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
            this.material.IdMaterial=0;
            this.material.NomMaterial="";
            this.material.Precio="";
            this.errorvalidacion=[];
            
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdMaterial: this.material.IdMaterial}
                }
            ).then( (res) => {
                this.material=res.data.data.material;
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
            this.poBtnSave.disableBtn=false; 
             this.Limpiar();
            if (Id>0)
            {
            this.material.IdMaterial=Id;
            this.get_one();
            }
            this.bus.$emit('Desbloqueo',false);
            
        });
       
    }
}
</script>