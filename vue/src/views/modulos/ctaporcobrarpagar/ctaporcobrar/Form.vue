<template>


  <div class="form-row">

      <div class="col-12 col-ms-12 col-md-6  col-lg-6 form-group">
        <label >Folio Factura</label>
        <input  type="text" placeholder="Factura" readonly v-model="Folio"   class="form-control" >
     </div>

  
       <div class="col-12 col-ms-12 col-md-6  col-lg-6 form-group">
      <label > Fecha Cobro </label>
 
                    <v-date-picker 
                    v-model="ctaporcobrar.FechaCobro"
                   
                    :popover="{ 
                        placement: 'bottom',
                        visibility: 'click',
                    }"
                   
                    :input-props='{
                        class:"form-control  calendar",
                        style:"cursor:pointer;background-color:#F9F9F9",
                        readonly: true,
                      
                    }'
                /> 
                <Cvalidation v-if="this.errorvalidacion.FechaCobro" :Mensaje="'Campo obligatorio'"></Cvalidation>
        </div>

   

     <div class="col-12 col-ms-12 col-md-12 col-lg-12 form-group">
      <label >Comentario</label>
        <textarea v-model="ctaporcobrar.Comentario" class="form-control" cols="2" rows="3"></textarea>
        <Cvalidation v-if="this.errorvalidacion.Comentario" :Mensaje="'Campo obligatorio'"></Cvalidation>
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
    props:['IdCtaPagar','poBtnSave'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'ctaporcobrar',//Sirve para donde va regresar
            ListaCategoria:[],
            ListaCuentas:[],
            ListaProveedores :[],
            ctaporcobrar:{
             IdCtaCobrar:0,
             IdFactura:0,
             

            },
           Folio:'',
            errorvalidacion:[]
        }
    },
    components:{
        Cbtnsave,Cvalidation,
    },
    methods :
    {
    
       async Guardar()
        {
             //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;

            await this.$http.post(
                'ctaporcobrar/post',
                this.ctaporcobrar
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
           this.ctaporcobrar={
               IdCtaCobrar:0,
                

            };
            Folio:'',
          this.errorvalidacion=[]
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
            var uno =this.ctaporcobrar.FechaCobro.replace(/-/g,'\/');
            this.ctaporcobrar.FechaCobro =new Date(uno);
           /*
            this.ctaporpagar.FechaFactura = new Date(dos);
            */
            });
        }
         
    },
    created() {
           
        this.bus.$off('Abrir');
  
        //Este es para moda
        this.bus.$on('Abrir',(IdFactura,Id,Folio)=> 
        {
      
            this.Limpiar();
            this.ctaporcobrar.IdFactura=IdFactura;
            this.Folio=Folio;
            
            this.poBtnSave.disableBtn=false;  
             this.bus.$off('Save');
              this.bus.$on('Save',()=>
              {
                this.Guardar();
              });

            
      
            if (Id>0)
            {
            this.ctaporcobrar.IdCtaCobrar=Id;
         
            this.get_one();
           
            }
             this.bus.$emit('Desbloqueo',false);
            
        });
    
    }
}
</script>