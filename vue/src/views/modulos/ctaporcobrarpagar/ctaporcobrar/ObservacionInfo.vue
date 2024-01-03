<template>
    <div>
        <div class="row justify-content-center">
                <div class="col-12 col-ms-12 col-md-12 col-lg-12">
                    <div class="col-12 col-ms-12 col-md-12 col-lg-12 form-group">
                        <h5 >Información de la&nbsp; <b>Cuenta Cobrada</b></h5>
                        <br>
                        <label>Fecha Real de Cobro</label>
                            <v-date-picker
                            v-if="ctaporcobrar.Estatus=='NO'"
                            v-model="ctaporcobrar.FechaRealCobro"
                            :popover="{ 
                                placement: 'right',
                                visibility: 'click',
                                
                            }"
                        
                            :input-props='{
                                class:"form-control calendar",
                                style:"cursor:pointer;background-color:#F9F9F9",
                                readonly: true
                            
                            }'
                        /> 
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.FechaRealCobro" :Mensaje="errorvalidacion.FechaRealCobro[0]"></Cvalidation></label>
                        <input v-if="ctaporcobrar.Estatus=='SI'" class="form-control" readonly v-model="FechaRealCobro">
                        
                        <br>
                        <label>No. Contrato</label>
                        <input readonly v-model="NumContrato" class="form-control" />
                        <br>
                        <label>Observaciones</label>
                        <div v-if="ctaporcobrar.Estatus=='NO'">
                            <textarea v-model="ctaporcobrar.Observacion"  v-if="ctaporcobrar.Estatus=='NO'" placeholder=" Coloque sus Observaciones" class="form-control" cols="2" rows="3"></textarea>
                            <Cvalidation v-if="this.errorvalidacion.Observacion" :Mensaje="'Campo obligatorio'"></Cvalidation>
                        </div>
                        
                        <textarea v-model="ctaporcobrar.Observacion" v-if="ctaporcobrar.Estatus=='SI'" readonly placeholder="Coloque sus Observaciones" class="form-control" cols="2" rows="3"></textarea>
                        
                    </div>
                    
                </div>
        </div>
        <!--<pre>{{
            ctaporcobrar
            }}</pre>-->
    </div>
</template>

<script>
import Cbtnsave from '@/components/Cbtnsave.vue'
import Cvalidation from '@/components/Cvalidation.vue'

export default {
   name:'cuentasporcobrar',
    props:['poBtnSave'],
     components:{
        
        Cbtnsave,Cvalidation
    },
    data() {
        return {
            
            ListaFacturas:[],
            cobrar:{},
            ctaporcobrar:{
                
                IdCtaCobrar:0,
                IdFactura:0,
                IdServicio:0,
                NumContrato:'',
                Archivo:'',
                Estatus:'',
                FechaRealCobro:''
               
            },
            //IdContrato:0,
            NumContrato:'',
            FechaRealCobro:'',
            errorvalidacion:[],
           
            
        }
    },
   
   methods: {
      
        Cobrado()
        { 
            this.poBtnSave.$toast=0;
            this.poBtnSave.disableBtn=true;
                this.$http.post(
                'ctaporcobrar/changeestatus',
                this.ctaporcobrar
                ,
            ).then( (res) => {
                this.poBtnSave.disableBtn=false;
                this.poBtnSave.toast=1;
                $('#ObservacionInfo').modal('hide');
                this.bus.$emit('List');
                //this.$toast.success('Información Actualizada');
            }).catch( err => {
                //this.$toast.error('La infromación no pudo actualizarse');
                this.errorvalidacion=err.response.data.message.errores;
                this.poBtnSave.disableBtn=false;
                this.poBtnSave.toast=2;
            });
               
        },
       
        
        get_one()
        {
          
            this.$http.get(
               "ctaporcobrar/recovery",
                {
                    params:{IdCtaCobrar: this.ctaporcobrar.IdCtaCobrar}
                }
            ).then( (res) => {
            
            this.ctaporcobrar=res.data.data.ctaporcobrar;
            //console.log(this.ctaporcobrar);
                if(this.ctaporcobrar.Estatus=='SI'){
                    this.bus.$off('Save',()=>
                    {
                        //this.Cobrado();
                    });
            }else if(this.ctaporcobrar.Estatus=='NO'){
                this.bus.$on('Save',()=>
                {
                    
                    this.Cobrado();
                });
            }
           /*
            this.ctaporpagar.FechaFactura = new Date(dos);
            */
            });
        },
       

   


      Limpiar(){

        this.errorvalidacion=[];

      }
       
   },

    created() {

       

      this.bus.$off('UploadOInfo');
      this.bus.$on('UploadOInfo',(Id, IdServ, NumContra,FechaRealCobro)=> 
      {
        this.Limpiar();
        this.ctaporcobrar.IdServicio=IdServ;
        this.FechaRealCobro=FechaRealCobro;
        //this.IdContrato=IdContra;
        this.NumContrato=NumContra;
        this.poBtnSave.disableBtn=false;  
        this.bus.$off('Save');
        /*this.bus.$on('Save',()=>
        {
          this.Cobrado();
        });*/

       

        if (Id>0)
        {
            this.ctaporcobrar.IdCtaCobrar=Id;
         
            this.get_one();
           
        }
            this.bus.$emit('Desbloqueo',false);
       

      });
     

    },
}
</script>