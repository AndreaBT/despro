<template>
<div>

                <fieldset class="sin">
              <div class="form-group form-row mt-2">
              
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                  <label>Nombre </label>
                  <input type="text"  v-model="concepto.Nombre"   class="form-control" placeholder="Nombre">
                  <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Nombre" :Mensaje="errorvalidacion.Nombre[0]"></Cvalidation></label>
                </div>
                 <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                  <label>Valor de Concepto </label>
                  <input type="number"  v-model="concepto.Valor"  class="form-control" >
                  <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Valor" :Mensaje="errorvalidacion.Valor[0]"></Cvalidation></label>
                </div>
                 <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                  <label>Meses </label>
                  <input type="number"  v-model="concepto.Meses"  class="form-control" placeholder="Meses">
                  <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Meses" :Mensaje="errorvalidacion.Meses[0]"></Cvalidation></label>
                </div>
                
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                   <label>Añadir Archivo</label>
                   <div class="custom-file-input-image">
                        <input @change="uploadImage()" type="file" accept="image/*" ref="file" class="custom-file-input" id="validatedCustomFile" required>
                        <input type="text" v-model="NameFile" class="form-control">
                        <button type="button" class=""><i class="fas fa-paperclip"></i></button>
                   </div>
                </div>
              
              </div>

            </fieldset>

   </div>
</template>
<script>
import Cvalidation from '@/components/Cvalidation.vue'

export default {
   name:'Form',
   props:['IdEquipamiento','poBtnSave'],
   components:{
      Cvalidation
   },
   data() {
       return {
           concepto:{
               Valor:0,
               Meses:0,
               Nombre:'',
               Foto:'',

           },
           Img:null,
            NameFile:'Elejir archivo (5 MB)',
            loading:false,
            errorvalidacion:[]
       }
   },methods: {
        get_one()
        {
            this.$http.get(
                'concepto/recovery',
                {
                    params:{IdConcepto: this.concepto.IdConcepto}
                }
            ).then( (res) => {
                this.concepto=res.data.data.concepto;
            });
        },
       async Guardar()
        {
             let FilePrevious='';
            
            if (this.concepto.Foto!=undefined)
            {
                if (this.concepto.Foto!=null)
                {
                FilePrevious=this.concepto.Foto;
                }
            }
             //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;
            this.errorvalidacion =[];            
            let formData = new FormData();
            formData.set('IdEquipamiento',this.IdEquipamiento);
            formData.set('Valor',this.concepto.Valor);
            formData.set('Meses',this.concepto.Meses);
            formData.set('Nombre',this.concepto.Nombre);
            formData.set('IdConcepto',this.concepto.IdConcepto);
            formData.set('FilePrevious', FilePrevious);
            let file = this.$refs.file.files[0];
            formData.append('File',file);
            await this.$http.post(
                'concepto/post',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                },
            ).then( (res) => {
               this.poBtnSave.disableBtn=false;  
                this.poBtnSave.toast=1;
               this.bus.$emit('List');
                $('#ModalForm').modal('hide');
            }).catch( err => {
                this.errorvalidacion=err.response.data.message.errores;
                this.poBtnSave.disableBtn=false;
                 this.poBtnSave.toast=2;  
            });
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
                        
            var allowedExtensions = /(\.png|\.PNG\.jpg|\.JPG)$/i;
            if(!allowedExtensions.exec(image.name)){
                this.$toast.info('Extenciones permitidas IMAGEN');
                const  input  = this.$refs.file;
                input.type = 'text'
                input.type = 'file';
                this.NameFile='Elejir archivo (5 MB)';
                return false;
            }

            this.NameFile=image.name;
            /*
            const reader = new FileReader();
            var img="";
            reader.readAsDataURL(image);
            reader.onload= e =>{
                this.Img = e.target.result;     
            }*/
        },
        Limpiar()
        {
             this.concepto={
               IdEquipamiento:0,
               Valor:'',
               Meses:'',
               Nombre:'',
               Foto:'',
           }
           this.errorvalidacion=[];
             this.Img=null;
            this.NameFile='Elejir archivo (5 MB)';
        }
   },
   created() {
      

        this.bus.$off('Nuevo');

        //Este es para modal
        this.bus.$on('Nuevo',(data,Id)=> 
        {
            
            this.bus.$off('Save');
            this.bus.$on('Save',()=>
            {
            this.Guardar();
            });

             this.Limpiar();
             this.concepto.IdConcepto=0;
            if (Id>0)
            {
            this.concepto.IdConcepto= Id;
            this.get_one();
            }
            
        });

       
   },
}
</script>