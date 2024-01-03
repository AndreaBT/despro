<template>
    <div>
        <div class="row">
            <div class="col-lg-6 ">
                <label >Nombre </label>
                <input type="text" v-model="Clientes.Nombre" class="form-control form-control-sm" placeholder="Nombre" id="Nombre" name="Nombre" />
                <Cvalidation v-if="this.errorvalidacion.Nombre" :Mensaje="errorvalidacion.Nombre[0]"></Cvalidation>
            </div>

            <div class="col-lg-6">
                <label >Contacto </label>
                <input type="text" v-model="Clientes.Contacto" class="form-control form-control-sm" placeholder="Contacto" />
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Contacto" :Mensaje="errorvalidacion.Contacto[0]"></Cvalidation></label>
            </div>

            <div class="col-lg-12">
                <label >Dirección</label>
                <input type="text" v-model="Clientes.Direccion"  class="form-control form-control-sm" placeholder="Direccion" />
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Direccion" :Mensaje="errorvalidacion.Direccion[0]"></Cvalidation></label>
            </div>

            <div class="col-lg-4 ">
                <label >Ciudad </label>
                <input type="text" v-model="Clientes.Ciudad" class="form-control form-control-sm" placeholder="Ciudad" />
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Ciudad" :Mensaje="errorvalidacion.Ciudad[0]"></Cvalidation></label>
            </div>
                       
            <div class="col-lg-4">
                <label >Teléfono </label>
                <input type="text" v-model="Clientes.Telefono" class="form-control form-control-sm"  placeholder="Telefono" />
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Telefono" :Mensaje="errorvalidacion.Telefono[0]"></Cvalidation></label>
            </div>

            <div class="col-lg-4 ">
                <label >Correo </label>
                <input  v-model="Clientes.Correo" class="form-control form-control-sm"  placeholder="Correo" />        
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Correo" :Mensaje="errorvalidacion.Correo[0]"></Cvalidation></label>
            </div>
                                       
            <div class=" col-lg-6">
                <label >Datos de Facturación</label>
                <textarea rows="5" class="form-control form-control-sm" v-model="Clientes.Dfac"></textarea>
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Dfac" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
            </div>
            <!--fin col-6--> 
        </div>

        <div class="modal-footer">
            <div class="col-3">
                <button :disabled="bandera" type="button" @click="Guardar" class="btn btn-block btn-success" >Guardar</button>
            </div>
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
    props:['IdCliente','poBtnSave'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'cliente',//Sirve para donde va regresar
            Clientes:{
                IdCliente:0,
                Nombre:"",
                Telefono:"",
                Direccion:"",
                Correo:"",
                Ciudad:"",
                Pais:"",
                Estado:"",
                CP:"", 
                IdSucursal:"",
                Contacto:"",
                Dfac:""
            },
            urlApi:"clientes/recovery",
            errorvalidacion:[],
            bandera:false,
        }
    },
    components:{
        Cbtnsave,
        Cvalidation
    },
    methods :
    {
        async Guardar()
        {
            let formData = new FormData();

            formData.set('IdCliente',this.Clientes.IdCliente);
            formData.set('Nombre',this.Clientes.Nombre);
            formData.set('Telefono', this.Clientes.Telefono);
            formData.set('Direccion',this.Clientes.Direccion);
            formData.set('Correo',this.Clientes.Correo);
            formData.set('Ciudad', this.Clientes.Ciudad);
            formData.set('Pais', this.Clientes.Pais);
            formData.set('Estado', this.Clientes.Estado);
            formData.set('CP', this.Clientes.CP);
            formData.set('Contacto', this.Clientes.Contacto);
            formData.set('Dfac', this.Clientes.Dfac);
            //deshabilita botones

            this.bandera=true;
            await this.$http.post(
                'clientes/post',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                        
                    }
                },
            ).then( (res) => {
                this.bandera=false;
                this.$toast.success('Información guardada');
                $('#ModalNewUser').modal('hide');
                this.bus.$emit('ListCcliente');
            }).catch( err => {
                this.bandera=false;
                this.errorvalidacion=err.response.data.message.errores;
                this.$toast.warning('Complete los campos'); 
            });
        },
        Limpiar()
        {
            this.Clientes.Nombre="",
            this.Clientes.Telefono="",
            this.Clientes.Direccion="",
            this.Clientes.Correo="",
            this.Clientes.Ciudad=""
            this.Clientes.Pais="",
            this.Clientes.Estado="",
            this.Clientes.CP="",
            this.Clientes.Contacto="",
            this.Clientes.Dfac="",
            this.Clientes.IdCliente=0,
            this.Clientes. IdSucursal="",
            this.errorvalidacion=[""]
        },
    },
    created() {
        this.Clientes.IdCliente=0;
    }
}
</script>