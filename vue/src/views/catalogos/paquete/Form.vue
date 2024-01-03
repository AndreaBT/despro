<template>

    <form class="form-horizontal">
        <div class="row">
            <div class="col-lg-12">

                <div class="row" v-for="(data,key,index) in listPermisos" :key="index">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <label class="col-form-label">
                            <input type="checkbox" v-model="permisoxmenu.arrayPermisos" :value="{IdPermiso:data.IdPermiso}"> {{data.Nombre}}
                        </label>
                    </div>
                </div>
            </div>
            
        </div>
    </form>
    
</template>
<script>
import Cbtnsave from '@/components/Cbtnsave.vue'
import Cvalidation from '@/components/Cvalidation.vue'
export default {
    props:['poBtnSave'],
    components:{
        Cbtnsave,Cvalidation
        
    },
    data() {
        return {
            permisoxmenu:{            
                IdPaquete:0,
                arrayPermisos:[]
            },
            urlApi:"permisos/permisosxmenu",
            boleano:false,
            errorvalidacion:[],
            listPermisos:[],  
        }
    },methods: {
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdPaquete: this.permisoxmenu.IdPaquete}
                }
            ).then( (res) => {
                this.listPermisos = res.data.permisos;
                //this.permisoxmenu.arrayPermisos = res.data.menuxpermiso;

                res.data.menuxpermiso.forEach((value, index) => {
                    this.permisoxmenu.arrayPermisos.push({IdPermiso:value.IdPermiso})
                });

                this.boleano =true;
            });
        },

         async Guardar()
        {
            this.bus.$emit('BloquearBtn',0);
            await this.$http.post(
                'permisos/addpermisosxmenu',
                this.permisoxmenu
                ,
            ).then( (res) => {
                this.bus.$emit('BloquearBtn',1);
                this.bus.$emit('CloseModal2');
            }).catch( err => {
                
                    if(err.response.data.typemsj==2){
                        this.errorvalidacion=err.response.data.message.errores;
                    }else{
                        this.$toast.error(err.response.data.message);
                    }
                
                this.bus.$emit('BloquearBtn',2);
            });
        },

        Limpiar()
        {
            this.listPermisos = [];
        },

    },created() {
        this.bus.$off('Nuevo');

        //Este es para modal
        this.bus.$on('Nuevo',(data,Id)=> 
        {
            //this.poBtnSave.disableBtn=false; 

            this.bus.$off('Save');
             this.bus.$on('Save',()=>
            {
                this.Guardar();
            });

            if (Id>0)
            {
                this.Limpiar();
                this.permisoxmenu.IdPaquete = Id;
                this.get_one();
            }
            //this.bus.$emit('Desbloqueo',false);
            
        });
    },
}
</script>