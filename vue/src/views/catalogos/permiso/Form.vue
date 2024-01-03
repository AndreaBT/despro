<template>
    <div>

  <form id="Formpermiso" class="form-horizontal" method="post" autocomplete="off">
     
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">

                    <div class="form-group form-row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <label for="Nombre">Nombre</label>
                            <input type="text" v-model="ListaPermiso.Nombre" class="form-control" placeholder="Ingresar Nombre" />
                        </div>
                    </div>
        
                    <div class="form-group form-row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <label for="Clave">Clave</label>
                            <input type="text" v-model="ListaPermiso.Clave" class="form-control" placeholder="Ingresar Clave" />
                        </div>
                    </div>
                    
                </div><!--fin col-12-->
            </div>
        </div>
    </form>
        </div>
   
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from '@/components/Cbtnsave'

export default {
    name:"Form",
    props:['IdPermiso','poBtnSave'],
    components:{
        Cbtnsave
    },
    data() {
        return {  
            Modal:true,//Sirve pra los botones de guardado
            FormName:'usuarios',//Sirve para donde va regresar
            ListaPermiso:{
                IdPermiso:0,
                Clave:"",
                Nombre:""
            },
            urlApi:"permiso/recovery",
        }
    },
    methods : {
        async Guardar()
        {
            //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;
            let formData = new FormData();
            formData.set('IdPermiso',this.ListaPermiso.IdPermiso);
            formData.set('Clave',this.ListaPermiso.Clave);
            formData.set('Nombre',this.ListaPermiso.Nombre);

            
            await this.$http.post(
               'permiso/post',
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
        async Guardar2() {
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;

            await this.$http.post(
                'permiso/post',
                this.ListaPermiso,
            ).then( (res) => {

                console.log("okey");
                this.poBtnSave.disableBtn=false;  
                this.poBtnSave.toast=1;
                $('#ModalForm').modal('hide');

                if(this.ListaPermiso.IdPermiso > 0){  

                    this.bus.$emit('Lista2'); 
                    
                }

                
            }).catch( err => {
                console.log("error");
                this.poBtnSave.disableBtn=false;
                this.poBtnSave.toast=2;  
            });
        },
        Limpiar() {
            this.ListaPermiso.IdPermiso=0;
            this.ListaPermiso.Nombre="";
            this.ListaPermiso.Clave="";
        },
        get_one() {
            console.log(1);
             this.$http.get(
                this.urlApi, {
                    params:{IdPermiso:this.ListaPermiso.IdPermiso}
                }
            ).then( (res) => {
                console.log(res.data);
                this.ListaPermiso=res.data.data.permisos;
            });
        }
    },
    created() {
        this.bus.$off('Nuevo');
        this.bus.$on('Nuevo',(data,Id)=> {     
            this.poBtnSave.disableBtn=false; 
            this.bus.$off('Save');
            this.bus.$on('Save',()=>  {
                this.Guardar();
            });
            this.Limpiar();
                // console.log(Id);
            if (Id>0)  {
                this.ListaPermiso.IdPermiso=Id;
                this.get_one();
            }
            this.bus.$emit('Desbloqueo',false);
        });

        if (this.Id!=undefined) {
            this.ListaPermiso.IdPermiso=this.Id;
            this.get_one();
        }
    }
}
</script>