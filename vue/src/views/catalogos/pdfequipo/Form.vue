<template>

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label >Título</label>
                <input  class="form-control"  v-model="PdfEquipo.Titulo" placeholder="Nombre">
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Titulo" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
            </div>
        </div>
        <div class="col-lg-12">
            <label >Archivo</label>
            <div class="custom-file-input-image">
                <input @change="uploadImage()" type="file" accept="application/pdf" ref="file" class="custom-file-input" id="validatedCustomFile" required>
                <input type="text" v-model="NameFile" class="form-control">
                <button type="button" class=""><i class="fas fa-paperclip"></i></button>
            </div>
            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.NombreArchivo" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
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
    props:['oEquipoP','poBtnSave'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'PDF',//Sirve para donde va regresar
            PdfEquipo:{
              IdPdf:0,
              IdEquipo:0,
              Titulo:"",
              NombreArchivo:"",
            },
            urlApi:"pdfequipo/recovery",
            oEquipo:{},
            errorvalidacion:[],
            NameFile:'Elegir archivo (5 MB)',
        }
    },
    components:{
        Cbtnsave,Cvalidation
    },
    methods :
    {
        async Guardar()
        {
            let file = this.$refs.file.files[0];
            let formData = new FormData();
            formData.set('IdPdf',this.PdfEquipo.IdPdf);
            formData.set('IdEquipo',this.oEquipo.IdEquipo);
            formData.set('Titulo',this.PdfEquipo.Titulo);
            formData.set('NombreArchivo',this.PdfEquipo.NombreArchivo);
            formData.append('File',file);

            //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;
            await this.$http.post(
                'pdfequipo/post',
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
            this.PdfEquipo.IdPdf=0;
            this.PdfEquipo.IdEquipo= 0;
            this.PdfEquipo.Titulo="";
            this.PdfEquipo.NombreArchivo="";
            const  input  = this.$refs.file;
            input .type = 'text'
            input .type = 'file';
            this.errorvalidacion='';
            this.NameFile='Elegir archivo (5 MB)';
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdPdf: this.PdfEquipo.IdPdf}
                }
            ).then( (res) => {
                this.PdfEquipo=res.data.data.PdfEquipo;
                this.PdfEquipo.IdEquipo=this.oEquipo.IdEquipo;
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
    },
    created()
    {
        this.bus.$off('Nuevo');       

        if (this.oEquipoP!=undefined)
        {
            sessionStorage.setItem('oEquipo',JSON.stringify(this.oEquipoP));
        }
        this.oEquipo=JSON.parse(sessionStorage.getItem('oEquipo'));
    
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
                this.PdfEquipo.IdPdf=Id;
                this.get_one();
            }
        });
    }
}
</script>