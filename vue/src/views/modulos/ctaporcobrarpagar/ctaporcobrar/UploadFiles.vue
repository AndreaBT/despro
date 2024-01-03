<template>
    <div>
        <div class="row justify-content-center">
                <div class="col-12 col-ms-12 col-md-12 col-lg-12">
                    <div class="custom-file-input-image" >
                        <input class="custom-file-input" @change="uploadImage()" ref="file" type="file"   accept="*" />
                        <input type="text" v-model="NameFile" class="form-control">
                        <button type="button"><i class="fas fa-paperclip"></i></button>
                    </div>
                </div>
        </div>
    </div>
</template>

<script>

import Cbtnsave from '@/components/Cbtnsave.vue'
import Cvalidation from '@/components/Cvalidation.vue'
export default {
   name:'cuentasporcobrar',
    props:['poBtnSave','IdCtaCobrar'],
    data() {
        return {
            cobrar:{},
            ctaporcobrar:{
                
                IdCtaCobrar:0,
                FilePrevious:'',
                Archivo:''
               
            },
            routefiles:'',
            NameFile:'Elegir archivo (5 MB)',
            errorvalidacion:[],
            baseUrl:""
        }
    },
    components:{
        Cbtnsave,Cvalidation,
    },
    methods:{
      
        async Guardar()
        {
           this.poBtnSave.toast=0;
           this.poBtnSave.disableBtn=true;
           let formData = new FormData();
           formData.set('IdCtaCobrar', this.ctaporcobrar.IdCtaCobrar);
           formData.set('FilePrevious',this.ctaporcobrar.Archivo);

            let file = this.$refs.file.files[0];
            formData.append('File',file);
            //this.formData.IdCtaCobrar=Id;
            await this.$http.post(
                'ctaporcobrar/addArchivo',
                formData,{
                    headers:{
                        'Content-Type': 'multipart/form-data'
                    }
                },
            ).then((res)=>{
                this.poBtnSave.disableBtn=false;  
                this.poBtnSave.toast=1;
                $('#UploadFiles').modal('hide');
                this.bus.$emit('List');
            }).catch( err => {
                //this.$toast.error('La infromación no pudo actualizarse');
                this.errorvalidacion=err.response.data.message.errores;
                this.poBtnSave.disableBtn=false;
                this.poBtnSave.toast=2;
            });


        },
        Limpiar()
        {   this.errorvalidacion=[];
            

            this.ctaporcobrar={
                
                IdCtaCobrar:0,
                FilePrevious:'',
                Archivo:''
               
            };

            this.NameFile='Elegir archivo (5 MB)';
            this.FilePrevious='';
            this.Archivo='';
            this.$refs.file.value = "";
        },
        uploadImage()
        {
            const image = this.$refs.file.files[0];

            var FileSize = image.size / 1024 / 1024; // in MB
            if (FileSize > 5) {
                this.$toast.info('Solo se puede subir archivos menores a 5 MB');
                const  input  = this.$refs.file;
                input .type = 'text'
                input .type = 'file';
                return false;
            }
                        
            var allowedExtensions = /(\.pdf|\.PDF)$/i;
            if(!allowedExtensions.exec(image.name)){
                this.$toast.info('Extenciones permitidas .pdf');
                const  input  = this.$refs.file;
                input.type = 'text'
                input.type = 'file';
                this.NameFile='Elegir archivo (5 MB)';
                return false;
            }

            this.NameFile=image.name;
            
        },

        get_one()
        {
          
            this.$http.get(
               "ctaporcobrar/recovery",
                {
                    params:{IdCtaCobrar: this.ctaporcobrar.IdCtaCobrar}
                }
            ).then( (res) => {
            this.ctaporcobrar=res.data.data.ctaporcobrar;
            
           /*
            this.ctaporpagar.FechaFactura = new Date(dos);
            */
            });
        },

        
        
       
    },

    created() {

        this.form={
            pagos:[],
            oldfiles:[],
        },
        
      this.bus.$off('UploadP');
      this.bus.$on('UploadP',(Id)=> 
      {
          
        //this.ctaporcobrar.IdCtaCobrar=Id;
        this.poBtnSave.disableBtn=false;  
        this.bus.$off('Save');
        this.bus.$on('Save',()=>
        {
          this.Guardar();
        });
        this.Limpiar();
        
        if (Id>0)
        {
            this.ctaporcobrar.IdCtaCobrar=Id;
         
            this.get_one();
            this.Limpiar();
            this.FilePrevious='';
            this.Archivo='';
           
        }
       

      });
     

    },
}
</script>