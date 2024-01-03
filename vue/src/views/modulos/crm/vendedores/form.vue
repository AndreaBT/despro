<template>
    <div>      
        <div class="modal-body">
            <div class="form-group">
                <h3>
                    <input style="width:0;height:0;border:0;position:absolute;background-color:transparent"  :id="'txt'+NombreVendedor" :ref="'txt'+NombreVendedor"  :value="NombreVendedor">
                    {{NombreVendedor}}
                </h3> 
                <hr>
            </div>

            <div class="form-group" v-for="(Col,index) of ListaAsignados" :key="index">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="optionsCheckboxes" :id="'Col'+index" :value="Col" v-model="AddNewColums">
                        <span class="checkbox-material-green">
                            <span class="check"></span>
                        </span>
                        {{Col.Nombre}}
                    </label>
                </div>
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
        props:['IdTipoProceso','poBtnSave','nombre'],
    data() {
        return {
            ok : this.nombre,
            Modal:true,//Sirve pra los botones de guardado
            FormName:'objtipoproceso',//Sirve para donde va regresar
            objtipoproceso:{
                IdTipoProceso:0,
                Nombre:"",
                IdUsuario:0,
            },
            urlApi:"crmprocesovendedor/list",
            errorvalidacion:[],
            ListaAsignados:[],
            AddNewColums:[],
            NombreVendedor:''
        }
    },
    components:{
        Cbtnsave,
        Cvalidation,
    },
    methods :
    {
        async Guardar()
        {   
            let Valores = [];
            this.AddNewColums.forEach(element => {
                element.Estatus = true;
                Valores.push(element);
            });

            //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;
            let formData = new FormData();
            formData.set('Lista',JSON.stringify(Valores));
            formData.set('IdTrabajador',this.objtipoproceso.IdUsuario);
            
            await this.$http.post(
                'crmprocesovendedor/post',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                },
            ).then( (res) => {
                this.poBtnSave.disableBtn=false;  
                this.poBtnSave.toast=1;
                this.AddNewColums = [];
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
            this.objtipoproceso.IdUsuario= 0,
            this.objtipoproceso.Nombre="",
            this.errorvalidacion=[""]
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdTrabajador: this.objtipoproceso.IdUsuario}
                }
            ).then( (res) => {
                this.ListaAsignados= res.data.data.asignados;
                this.ListaAsignados.forEach(element => {
                    if(element.Estatus === 'true'){
                        this.AddNewColums.push(element);
                    }
                });
            });
        }
    },
    created() {
        this.bus.$off('Nuevo');
        //Este es para modal
        this.bus.$on('Nuevo',(data,Id)=> 
        {    

            //alert(JSON.stringify(Id));
            this.poBtnSave.disableBtn=false;  
            this.bus.$off('Save');
            this.bus.$on('Save',()=>
            {
                this.Guardar();
            });

            this.Limpiar();
            if(Id !=undefined){
               if (Id.IdUsuario>0)
                {
                    this.objtipoproceso.IdUsuario=Id.IdUsuario;
                    this.NombreVendedor = Id.Nombre;
                    this.get_one();
                }
            }
             this.bus.$emit('Desbloqueo',false);
            
        });
        // if (this.Id.IdTrabajador!=undefined)
        // {
        //     this.objtipoproceso.IdTipoProceso=this.Id.IdTrabajador;
        //     this.get_one();
        // }
    }
}
</script>