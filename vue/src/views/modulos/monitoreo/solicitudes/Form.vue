<template>
  <div>
    <div class="card-body">
        <div class="row">  
            <div class="col-lg-12 form-group">      
                <label for="Nombre" class="labeltam">Nombre (Sucursal):</label>
                <input readonly type="text" v-model="ticket.Sucursal" class="form-control"
                        placeholder="Sucursal" />
                <Cvalidation v-if="this.errorvalidacion.Sucursal" :Mensaje="'Campo obligatorio'"></Cvalidation>
            </div>
            <div class="col-lg-12 form-group">      
                <label for="Nombre" class="labeltam">Contacto:</label>
                <input readonly type="text" v-model="ticket.Contacto" class="form-control"
                        placeholder="Contacto" />
                <Cvalidation v-if="this.errorvalidacion.Contacto" :Mensaje="'Campo obligatorio'"></Cvalidation>
            </div>
            <div class="col-lg-12 form-group">      
                <label for="Nombre" class="labeltam">Teléfono:</label>
                <input readonly type="text" v-model="ticket.Telefono" class="form-control"
                        placeholder="Teléfono" />
                <Cvalidation v-if="this.errorvalidacion.Telefono" :Mensaje="'Campo obligatorio'"></Cvalidation>
            </div>
            <div class="col-lg-12 form-group">      
                <label for="Nombre" class="labeltam">De:</label>
                <input  type="text" v-model="ticket.Correo" class="form-control"
                        placeholder="Teléfono" />
                <Cvalidation v-if="this.errorvalidacion.Correo" :Mensaje="errorvalidacion.Correo[0]"></Cvalidation>
            </div>
            <div class="col-lg-12 form-group">      
                <label for="Nombre" class="labeltam">Para:</label>
                <input type="text" v-model="ticket.Para" class="form-control" placeholder="Para" />
                <Cvalidation v-if="this.errorvalidacion.Para" :Mensaje="errorvalidacion.Para[0]"></Cvalidation>
            </div>
            <div class="col-lg-12 form-group">      
                <label for="Nombre" class="labeltam">Mensaje:</label>
                <textarea rows="3" v-model="ticket.Asunto" class="form-control" placeholder="Mensaje"></textarea>
                <Cvalidation v-if="this.errorvalidacion.Asunto" :Mensaje="errorvalidacion.Asunto[0]"></Cvalidation>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
export default {
    name:'Solicitud',
    props:['osucursal',"poBtnSave"],
    data() {
        return {
            errorvalidacion:[],
            ticket:{
                IdClienteS:0,
                IdCliente:0,
                Sucursal:'',
                Contacto:'',
                Telefono:'',
                Correo:'',
                Para:'',
                Asunto:'',
                Estado:'Cliente',
                Tipo:2,
            },
        }
    },
    methods: {
        async Guardar(){
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;
            await this.$http.post(
                'monitoreo/ticketadd',
                this.ticket
                ,
            ).then( (res) => {
               this.poBtnSave.disableBtn=false;  
                this.poBtnSave.toast=1;
                $('#ModalForm').modal('hide');
                this.Limpiar();
            }).catch( err => {
                this.poBtnSave.disableBtn=false;   
                this.errorvalidacion=err.response.data.message.errores;
                this.poBtnSave.toast=2; 
                /*  if(err.response.data.type==2){
                this.$toast.error(err.response.data.message);
                }else{
                this.errorvalidacion=err.response.data.message.errores;
                }*/
            });
        },
        Limpiar(){
            this.errorvalidacion=[];
            this.ticket.Asunto='';
        }
    },
    created() {
        this.bus.$off('Save');

        this.ticket.Sucursal=this.osucursal.Nombre;
        this.ticket.Contacto=this.osucursal.ContactoS;
        this.ticket.Telefono=this.osucursal.Telefono;
        this.ticket.Correo=this.osucursal.Correo;
        this.ticket.IdCliente=this.osucursal.IdCliente;
        this.ticket.IdClienteS=this.osucursal.IdClienteS;
    },
    mounted() {
        this.bus.$on('Save',()=>
        {   
           this.Guardar();
        });
    },
    destroyed() {
        this.Limpiar();
    },
}
</script>

<style>

</style>