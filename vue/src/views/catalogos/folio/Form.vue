<template>
    <div>
        <div class="row">
            <div class="col-lg-4 ">
                <label >Serie</label>
                <input type="text" v-model="folio.Serie" class="form-control form-control-sm" placeholder="Nombre" id="Nombre" name="Nombre" />
                <Cvalidation v-if="this.errorvalidacion.Serie" :Mensaje="'Campo obligatorio'"></Cvalidation>
            </div>
            
            <div class=" col-lg-4">
                <label >Número</label>
                <vue-numeric :minus="false" class="form-control  form-control-sm"  currency="" separator="," :precision="0" v-model="folio.Numero"></vue-numeric>
                <Cvalidation v-if="this.errorvalidacion.Numero" :Mensaje="'Campo obligatorio'"></Cvalidation>
            </div>

            <div class=" col-lg-4">
                <label >Tipo</label>
                <select class="form-control form-control-sm"  v-model="folio.Tipo">
                    <option value="">Seleccione una opción</option>
                    <option value="DESPACHO">DESPACHO</option>
                    <option value="COTIZACION SERVICIO"> COTIZACION SERVICIO</option>
                    <option value="COTIZACION MANTENIMIENTO">COTIZACION MANTENIMIENTO</option>
                    <option value="FACTURACION">FACTURACION</option>
                    <option value="SPEND PLAN PROYECTO">SPEND PLAN PROYECTO</option>
                </select>
                <Cvalidation v-if="this.errorvalidacion.Tipo" :Mensaje="this.errorvalidacion.Tipo[0]"></Cvalidation>
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
import Cvalidation from '@/components/Cvalidation.vue'
export default {
    name:'Form',
    props:['IdFolio','poBtnSave'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'folio',//Sirve para donde va regresar
            folio:{
                IdFolio:0,
                Serie:"",
                Numero:"",
                Tipo:"",
                IdSucursal:""
            },
            urlApi:"folio/recovery",
            errorvalidacion:[]
        }
    },
    components:{
        Cbtnsave,Cvalidation
    },
    methods :
    {
        async Guardar()
        {
            //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;
            let formData = new FormData();

            formData.set('IdFolio',this.folio.IdFolio);
            formData.set('Serie',this.folio.Serie);
            formData.set('IdSucursal', this.folio.IdSucursal);
            formData.set('Numero', this.folio.Numero);
            formData.set('Tipo', this.folio.Tipo);

            await this.$http.post(
                'folio/post',
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
            this.folio.IdFolio=0,
            this.folio.Numero="",   
            this.folio.IdSucursal="",
            this.folio.Serie="",
            this.folio.Tipo="";
            this.errorvalidacion=[];
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdFolio: this.folio.IdFolio}
                }
            ).then( (res) => {
                this.folio.IdFolio=res.data.data.folio.IdFolio;
                this.folio.Numero=res.data.data.folio.Numero;
                this.folio.Serie=res.data.data.folio.Serie;
                this.folio.Tipo=res.data.data.folio.Tipo;
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
                this.folio.IdFolio=Id;
                this.get_one();
            }
            this.bus.$emit('Desbloqueo',false);
        });
        if (this.Id!=undefined)
        {
            this.folio.IdFolio=this.Id;
            this.get_one();
        }
    }
}
</script>