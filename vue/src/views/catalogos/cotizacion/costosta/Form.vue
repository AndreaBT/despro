<template>
    <div>
   
                <div class="row justify-content-center">
                    
                    <div class="col-lg-8 ">
                        <span class="has-float-label">
                            <label for="Nombre" class="labeltam">Concepto </label>
                            <input type="text" readonly v-model="costosta.Concepto" class="form-control form-control-sm"
                                placeholder="Concepto" />
                        </span>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Concepto" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
                    </div>
                    <div class="col-lg-4 ">
                        <span class="has-float-label">
                            <label for="Nombre" class="labeltam">Costo </label>
                            <vue-numeric   :minus="false"  class="form-control form-control-sm "  currency="$" separator="," :precision="2" v-model="costosta.Costo"></vue-numeric>
                           
                        </span>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Costo" :Mensaje="'Campo obligatorio/Numerico'"></Cvalidation></label>
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
            costosta:{            
                IdCostosTA:0,
                Concepto:"",
                Costo:"",
                RegEstatus:"",
            },
            urlApi:"costosta/recovery",
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
                'costosta/post',
                this.costosta
                ,
            ).then( (res) => {
                 this.poBtnSave.disableBtn=false;  
                this.poBtnSave.toast=1;
                $('#ModalForm').modal('hide');
                this.bus.$emit('List'); 

            }).catch( err => {
                this.poBtnSave.disableBtn=false;
                    if(err.response.data.type==2){
                           this.poBtnSave.toast=3; 
                            this.poBtnSave.toastmsg(err.response.data.message);
                    }else{
                       this.errorvalidacion=err.response.data.message.errores;
                        
                     this.poBtnSave.toast=2;  
                    }

            });
       
        },
         Limpiar()
        {
            this.costosta.IdCostosTA=0;
            this.costosta.Concepto="";
            this.costosta.Costo="";
            this.costosta.RegEstatus="";
            this.errorvalidacion=[];
            
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdCostosTA: this.costosta.IdCostosTA}
                }
            ).then( (res) => {
                
                this.costosta=res.data.data.costosta;
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
                this.costosta.IdCostosTA=Id;
                this.get_one();
            }
            this.bus.$emit('Desbloqueo',false);
            
        });
       
    }
}
</script>