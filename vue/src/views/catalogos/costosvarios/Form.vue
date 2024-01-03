<template>
    <div>
        <form autocomplete="off" id="FromCostosVarios" class="form-horizontal" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-12 ">
                    <label for="Nombre" class="labeltam">Concepto </label>
                    <input type="text" v-model="costosvarios.Concepto" class="form-control" placeholder="Concepto"  />
                    <Cvalidation v-if="this.errorvalidacion.Concepto" :Mensaje="errorvalidacion.Concepto[0]"></Cvalidation>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from '@/components/Cbtnsave.vue'
import Cvalidation from '@/components/Cvalidation.vue'
export default {
    name:'Form',
    props:[''],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'costosvarios',//Sirve para donde va regresar
            costosvarios:{
                IdCostosV:0,
                Concepto:"",
            },
            urlApi:"costosvarios/recovery",
            errorvalidacion:[],
        }
    },
    components:{
        Cbtnsave,Cvalidation
    },
    methods :
    {
    
        async Guardar()
        {
            let formData = new FormData();

            formData.set('IdCostosV',this.costosvarios.IdCostosV);
            formData.set('Concepto',this.costosvarios.Concepto);
            this.bus.$emit('BloquearBtn',0);
            await this.$http.post(
                'costosvarios/post',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                },
            ).then( (res) => {
                this.bus.$emit('BloquearBtn',1);
                if (this.Id==undefined){
                    $('#ModalForm').modal('hide');
                        this.bus.$emit('List'); 
                }
                else{
                    this.$router.push({name:this.FormName});
                }
            }).catch( err => {
                this.errorvalidacion=err.response.data.message.errores;
                this.bus.$emit('BloquearBtn',2);
            });
        },
         Limpiar()
        {
            this.costosvarios.IdCostosV=0,
            this.costosvarios.Concepto="",   
            this.errorvalidacion=[""];
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdCostosV: this.costosvarios.IdCostosV}
                }
            ).then( (res) => {
                this. costosvarios.IdCostosV=res.data.data.costosvarios.IdCostosV;
                this. costosvarios.Concepto=res.data.data.costosvarios.Concepto;
              
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
             this.Limpiar();
            if (Id>0)
            {
                this.costosvarios.IdCostosV=Id;
                this.get_one();
            }
            
        });
        if (this.Id!=undefined)
        {
            this.costosvarios.IdCostosV=this.Id;
            this.get_one();
        }

    }
}
</script>