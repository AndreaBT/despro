<template>
    <form class="form-cotizacion">              
      <div class="card-body">
        <div class="row">  
            <div class="col-lg-9 form-group">      
                <label >Cliente:</label>
                <input readonly v-model="servicios.Cliente" type="text" class="form-control"
                        placeholder="Sucursal" />
                <Cvalidation v-if="this.errorvalidacion.Sucursal" :Mensaje="'Campo obligatorio'"></Cvalidation>
            </div>
            <div class="col-lg-3 form-group">      
                <label >Folio:</label>
                <input readonly type="text" v-model="servicios.Folio"  class="form-control"
                        placeholder="Contacto" />
                <Cvalidation v-if="this.errorvalidacion.Contacto" :Mensaje="'Campo obligatorio'"></Cvalidation>
            </div>
            <div class="col-lg-12 form-group">      
                <label >De:</label>
                <input  type="text" v-model="Mail.De" class="form-control"
                        placeholder="Teléfono" />
                <Cvalidation v-if="this.errorvalidacion.Correo" :Mensaje="errorvalidacion.Correo[0]"></Cvalidation>
            </div>
            <div class="col-lg-12 form-group">      
                <label >Para:</label>
                      <vue-tags-input v-model="tag" :tags="Mail.Para"
                        @tags-changed="newTags => Mail.Para = newTags"
                        placeholder="Para"
                        />
            </div>
            <div class="col-lg-12 form-group">      
                <label >Mensaje:</label>
                <textarea rows="3" class="form-control" v-model="Mail.Mensaje" placeholder="Mensaje"></textarea>
                <Cvalidation v-if="this.errorvalidacion.Asunto" :Mensaje="errorvalidacion.Asunto[0]"></Cvalidation>
            </div>
        </div>
        <div class="f1-buttons mt-4">
            <div class="checkbox">
                <label>
                    <input v-model="Mail.Img" type="checkbox" name="optionsCheckboxes">
                        <span class="checkbox-material-green">
                            <span class="check"></span></span> 
                            Enviar Reporte Evidencias
                </label>
            </div>
            <button type="button" @click="Cerrar" class="btn btn-04 ban">Cerrar</button>
            <button :disabled="Disablebtn" @click="Guardar"  type="button" class="btn btn-01">
            <i v-show="Disablebtn" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i><i class="fa fa-plus-circle"></i> {{txtSave}}
            </button>
        </div>
    </div>           
</form>
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from '@/components/Cbtnsave.vue'
import Cvalidation from '@/components/Cvalidation.vue'
import Modal from '@/components/Cmodal.vue';

export default {
    name:'Form',
    props:[''],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'vehiculo',//Sirve para donde va regresar,
            errorvalidacion:[],
            tag: '',
      tags: [],
      servicios:{},
         
         Mail:{
            De:'',
            Mensaje:'',
            Para:[],
            IdServicio:0,
            Img:false
         },
          Disablebtn:false,
            txtSave:'Enviar',

        }
    },
    components:{
     Cbtnsave,Cvalidation
    },
    methods :
    {
        get_revovy(){
            
            this.$http.get(
                'servicio/recovery',
                {
                    params:{IdServicio: this.servicios.IdServicio}
                }
            ).then( (res) => {
                this.servicios =res.data.data.servicio;

                if (this.servicios.CorreoCS!='')
                {
                    this.Mail.Para.push({ "text": this.servicios.CorreoCS});  
                }

                var datos = JSON.parse( sessionStorage.getItem('user'));
                this.Mail.De=datos.Candado;
            });
        },

        async Guardar()
        {
            this.Disablebtn=true;
            this.txtSave=' Espere...';

            this.$http.post(
                'servicio/SendFiles',
                this.Mail 
            ).then( (res) => {

                this.Disablebtn=false;
                this.txtSave='Enviar';
                $('#ModalMail').modal('hide');
                this.$toast.success('Información enviada');

            }).catch( err => {

                this.errorvalidacion=err.response.data.message.errores;
                this.Disablebtn=false;
                this.txtSave=' Enviar';
                this.$toast.info('Complete la Información');
            
            });
        },

        Cerrar(){
           $('#ModalMail').modal('hide'); 
        },

        Limpiar()
        {
            this.Mail={
                De:'',
                Mensaje:'',
                Para:[],
                IdServicio:0,
                Img:false
            }
        }
        
    },
    created() {
    
        this.bus.$off('MailOpen');
        this.bus.$on('MailOpen',(Id)=>
        {   
            this.Limpiar();
            this.servicios.IdServicio=Id;
            this.Mail.IdServicio=Id;
            this.get_revovy();
            
        });

    }
}
</script>

<style  scoped>

.tags-input .inputs {
  display: flex;
}

.tags-input .inputs i {
  font-size: 20px;
  cursor: pointer;
}

</style>