<template>
    <div>
                <div class="row justify-content-center">
                    
                    <div class="col-lg-4 ">
                        <span class="has-float-label">
                            <label for="Nombre" class="labeltam">Rango Inicial  </label>
                            <vue-numeric   :minus="false" placeholder="Rango Inicial" class="form-control form-control-sm "  currency="$" separator="," :precision="0" v-model="markup.Monto_I"></vue-numeric>
                         
                        </span>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Monto_I" :Mensaje="'Campo obligatorio/Numerico'"></Cvalidation></label>
                    </div>
                    <div class="col-lg-4 ">
                        <span class="has-float-label">
                            <label for="Nombre" class="labeltam">Rango Final  </label>
                            <vue-numeric placeholder="Rango Final"  :minus="false"  class="form-control form-control-sm "  currency="$" separator="," :precision="0" v-model="markup.Monto_F"></vue-numeric>                            
                        </span>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Monto_F" :Mensaje="'Campo obligatorio/Numerico'"></Cvalidation></label>
                    </div>
                    <div class="col-lg-4 ">
                        <span class="has-float-label">
                            <label for="Nombre" class="labeltam">Markup </label>
                            <vue-numeric placeholder="0.00"  :minus="false"  class="form-control form-control-sm "  separator="," :precision="2" v-model="markup.CostoM"></vue-numeric>
                          
                        </span>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.CostoM" :Mensaje="'Campo obligatorio/Numerico'"></Cvalidation></label>
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
            markup:{            
                IdMarkUp:0,
                Monto_I:"",
                Monto_F:"",
                CostoM:"",
                RegEstatus:""
            },
            urlApi:"markup/recovery",
            errorvalidacion:[],
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
                'markup/post',
                this.markup
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
            this.markup.IdMarkUp=0;
            this.markup.Monto_I="";
            this.markup.Monto_F="";
            this.markup.CostoM="";
            this.errorvalidacion=[];
            
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdMarkUp: this.markup.IdMarkUp}
                }
            ).then( (res) => {
                this.markup=res.data.data.markup;
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
                this.markup.IdMarkUp=Id;
                this.get_one();
            }
            
        });
       
    }
}
</script>