<template>
    <div class="form-row">
        <div class="col-lg-6 form-group">
            <label >Razón Social</label>
            <input type="text" placeholder="Razón Social" v-model="proveedores.Nombre" class="form-control" >
            <Cvalidation v-if="this.errorvalidacion.Nombre" :Mensaje="'Campo obligatorio'"></Cvalidation>
        </div>
        <div class="col-lg-6 form-group">
            <label >Contacto</label>
            <input type="text" placeholder="Contacto" v-model="proveedores.Contacto"  class="form-control" >
        </div>


        <div class="col-lg-4 form-group">
            <label >Número Fiscal</label>
            <input type="text" placeholder="Número Fiscal" v-model="proveedores.Rfc" class="form-control" >
            <Cvalidation v-if="this.errorvalidacion.Rfc" :Mensaje="'Campo olbigatorio'"></Cvalidation>
        </div>

         <div class="col-lg-4 form-group">
            <label >Días de Crédito</label>
            <input type="text" placeholder="Días de Crédito" v-model="proveedores.DiasCredito" class="form-control" >
        </div>

        <!--NUEVO-->
        
        <div class="col-lg-4 form-group">
            <label >Teléfono</label>
            <input type="text" placeholder="Teléfono" v-model="proveedores.Telefono"  class="form-control" >
        </div>

        <div class="col-lg-12 form-group">
            <label >Dirección</label>
            <input type="text" placeholder="Dirección" v-model="proveedores.Direccion"  class="form-control" >
        </div>

        <!--NUEVO 2-->
       

        <div class="col-lg-6 form-group">
            <label >Datos Bancarios</label>
            <textarea  placeholder=" Coloque sus Datos Bancarios" v-model="proveedores.DatosBancarios" class="form-control" cols="2" rows="3"></textarea>
        </div>

        <div class="col-lg-6 form-group">
            <label >Comentario</label>
            <textarea v-model="proveedores.Comentario"  placeholder=" Coloque su Comentario" class="form-control" cols="2" rows="3"></textarea>
          <!--  <input type="text" placeholder="Comentario"  v-model="proveedores.Comentario" class="form-control" >--->
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
    props:['IdProveedor','poBtnSave'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'vehiculo',//Sirve para donde va regresar
            ListaCategoria:[],
            proveedores:{
                IdProveedor:0,
            },
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
                'ctaproveedores/post',
                this.proveedores
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
            this.proveedores={
                IdProveedor:0,
            };
         
            this.errorvalidacion=[]
        },
        get_one()
        {
            this.$http.get(
               "ctaproveedores/recovery",
                {
                    params:{IdProveedor: this.proveedores.IdProveedor}
                }
            ).then( (res) => {            
                this.proveedores=res.data.data.proveedor;
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
                this.proveedores.IdProveedor=Id;
                this.get_one();
            }
            this.bus.$emit('Desbloqueo',false);
        });
    }
}
</script>