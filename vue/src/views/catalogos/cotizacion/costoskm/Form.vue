<template>
    <div>
   
    <form  autocomplete="off" id="FormCostosKM" class="form-horizontal" method="post" enctype="multipart/form-data">
        <div class="">
            <!--Fin head del panel-->
            <div class="card-body">
                <div class="row justify-content-center">
                    
                    <div class="col-lg-4 ">
                        <span class="has-float-label">
                            <label for="Nombre" class="labeltam">Rango Inicial/KM </label>
                            <vue-numeric   :minus="false" placeholder="Rango Inicial"  class="form-control form-control-sm"  currency="" separator="," :precision="0" v-model="costoskm.KMinicial"></vue-numeric>
                    
                        </span>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.KMinicial" :Mensaje="'Campo obligatorio/Numerico'"></Cvalidation></label>
                    </div>
                    <div class="col-lg-4 ">
                        <span class="has-float-label">
                            <label for="Nombre" class="labeltam">Rango Final/KM </label>
                            <vue-numeric placeholder="Rango Final"  :minus="false"  class="form-control form-control-sm "  currency="" separator="," :precision="0" v-model="costoskm.KMfinal"></vue-numeric>
                     
                        </span>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.KMfinal" :Mensaje="'Campo obligatorio/Numerico'"></Cvalidation></label>
                    </div>
                    <div class="col-lg-4 ">
                        <span class="has-float-label">
                            <label for="Nombre" class="labeltam">Costo/KM $ </label>
                            <vue-numeric placeholder="$ 0.00"  :minus="false"  class="form-control form-control-sm "  currency="$" separator="," :precision="2" v-model="costoskm.CostoKM"></vue-numeric>
  
                        </span>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.CostoKM" :Mensaje="'Campo obligatorio/Numerico'"></Cvalidation></label>
                    </div>
                    <!--fin col-6-->
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
            costoskm:{            
                IdCostosKM:0,
                KMinicial:"",
                KMfinal:"",
                CostoKM:"",
            },
            urlApi:"costoskm/recovery",
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
                'costoskm/post',
                this.costoskm
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
                    this.poBtnSave.toastmsg=err.response.data.message;
                    }else{
                        this.errorvalidacion=err.response.data.message.errores;
                        
                     this.poBtnSave.toast=2;  
                    }
                

            });
       
        },
         Limpiar()
        {
            this.costoskm.IdCostosKM=0;
            this.costoskm.KMinicial="";
            this.costoskm.KMfinal="";
            this.costoskm.CostoKM="";
            this.errorvalidacion=[];
            
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdCostosKM: this.costoskm.IdCostosKM}
                }
            ).then( (res) => {
                this.costoskm=res.data.data.costoskm;
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
                this.costoskm.IdCostosKM=Id;
                this.get_one();
            }
            
        });
       
    }
}
</script>