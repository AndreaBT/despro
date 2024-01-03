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
                <input type="text" v-model="Clientes.Direccion"  class="form-control form-control-sm" placeholder="Dirección" />
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Direccion" :Mensaje="errorvalidacion.Direccion[0]"></Cvalidation></label>
            </div>

            <div class="col-lg-4 ">
                <label >Ciudad </label>
                <input type="text" v-model="Clientes.Ciudad" class="form-control form-control-sm"
                        placeholder="Ciudad" />
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Ciudad" :Mensaje="errorvalidacion.Ciudad[0]"></Cvalidation></label>
            </div>
                       
            <div class="col-lg-4">
                <label >Teléfono </label>
                    <input type="text" v-model="Clientes.Telefono" class="form-control form-control-sm"  placeholder="Teléfono" />
                    <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Telefono" :Mensaje="errorvalidacion.Telefono[0]"></Cvalidation></label>
            </div>

            <div class="col-lg-4 ">
                <label >Correo </label>
                <input  v-model="Clientes.Correo" class="form-control form-control-sm"  placeholder="Correo" />        
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Correo" :Mensaje="errorvalidacion.Correo[0]"></Cvalidation></label>
            </div>
                                       
            <div class=" col-lg-6">
                <label >Comentarios</label>
                <textarea rows="5" class="form-control form-control-sm" v-model="Clientes.Dfac"></textarea>
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Dfac" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
            </div>
            <!--fin col-6-->
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
            urlApi:"crmcontactos/recovery",
            errorvalidacion:[],
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
            //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;
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
            
            await this.$http.post('crmcontactos/post',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                },
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
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdCliente: this.Clientes.IdCliente}
                }
            ).then( (res) => {
                this.Clientes.IdCliente =res.data.data.Clientes.IdCliente;
                this.Clientes.Nombre =res.data.data.Clientes.Nombre;
                this.Clientes.Telefono =res.data.data.Clientes.Telefono;
                this.Clientes.Direccion =res.data.data.Clientes.Direccion;
                this.Clientes.Correo =res.data.data.Clientes.Correo;
                this.Clientes.Ciudad =res.data.data.Clientes.Ciudad;
                this.Clientes.Pais =res.data.data.Clientes.Pais;
                this.Clientes.Estado =res.data.data.Clientes.Estado;
                this.Clientes.CP =res.data.data.Clientes.CP;
                this.Clientes.IdSucursal =res.data.data.Clientes.IdSucursal;
                this.Clientes.Contacto =res.data.data.Clientes.Contacto;
                this.Clientes.Dfac =res.data.data.Clientes.Dfac;              
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
                this.Clientes.IdCliente=Id;
                this.get_one();
            }
            this.bus.$emit('Desbloqueo',false);
        });
        if (this.Id!=undefined)
        {
            this.Clientes.IdCliente=this.Id;
            this.get_one();
        }
    }
}
</script>