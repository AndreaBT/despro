<template>
  <div>
      
    <div class="from-group row">
        <div class="col-md-6 col-lg-4">
            <label>Cliente</label>
            <input v-model="Cliente.Nombre" type="text" class="form-control" readonly />
            
        </div>
        <div class="col-md-6 col-lg-2">
            <div class="form-inline">
                <button :disabled="disableSearchCliente" @click="ListaCliente"  data-toggle="modal" data-target="#ModalForm3"  data-backdrop="static" type="button" class="btn btn-01 search mt-4">Buscar</button>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <label>Sucursal</label>
            <input  v-model="Sucursal.Nombre" type="text" class="form-control" readonly />    
        </div>
    </div>
    <div class="from-group row">
        <div class="col-lg-5">
            <label>Proyecto</label>
            <select class="form-control" v-model="ordenc.IdProyecto">
                <option value="0">--Seleccionar--</option>
                <option v-for="(item, index) in ListaProyectos" :key="index" :value="item.IdProyecto" >{{item.Proyecto}}</option>
            </select>
            <Cvalidation v-if="this.errorvalidacion.IdProyecto" :Mensaje="errorvalidacion.IdProyecto[0]"></Cvalidation>
        </div>
        <div class="col-lg-4">
            <label>Concepto</label>
            <select class="form-control" v-model="ordenc.Concepto">
                <option value="">--Seleccionar--</option>
                <option v-for="(item, index) in ListaConceptos" :key="index" :value="item.Concepto">{{item.Concepto}}</option>
            </select>
            <Cvalidation v-if="this.errorvalidacion.Concepto" :Mensaje="errorvalidacion.Concepto[0]"></Cvalidation>
        </div>
        <div class="col-lg-3">
            <label>Monto</label>
            <!--<input type="text" class="form-control dollar" v-model="ordenc.Monto">-->
            <vue-numeric  :minus="false" class="form-control form-control-sm text-right"  currency="$" separator="," :precision="2" v-model="ordenc.Monto"></vue-numeric>
            <Cvalidation v-if="this.errorvalidacion.Monto" :Mensaje="errorvalidacion.Monto[0]"></Cvalidation>
        </div>
    </div>
    <div class="from-group">
 
    </div>
    <div class="from-group">
        <label>Descripción</label>
        <textarea class="form-control" rows="2" v-model="ordenc.Descripcion"></textarea>
        <Cvalidation v-if="this.errorvalidacion.Descripcion" :Mensaje="errorvalidacion.Descripcion[0]"></Cvalidation>
    </div>
    <div class="form-group row mt-1">
         
        <div class="col-lg-5">
            <label class="">Folio Documento</label>
            <input type="text" class="form-control " v-model="ordenc.FolioArchivo">
            <Cvalidation v-if="this.errorvalidacion.FolioArchivo" :Mensaje="errorvalidacion.FolioArchivo[0]"></Cvalidation>
        </div>
        <div class="col-lg-7">
            <label>Subir Firma</label>
             <div class="custom-file-input-image">
                <input @change="uploadFile()" type="file" accept="application/pdf" ref="file" class="custom-file-input" id="customFile" required>
                <input type="text" v-model="NameFile" class="form-control">
                <button type="button" class=""><i class="fas fa-paperclip"></i></button>
            </div>
            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Archivo" :Mensaje="errorvalidacion.Archivo[0]"></Cvalidation></label>
           
        </div>
    </div>
    
   <Ccliente :TipoModal='2'></Ccliente>
  </div>
</template>

<script>

import Ccliente from '@/components/Ccliente.vue'
import Cvalidation from '@/components/Cvalidation.vue'

export default {
    props:['poBtnSave'],
    components:{Cvalidation,Ccliente},
    data() {
        return {
            ordenc:{
                IdOrdenCompra:0,
                IdProyecto:0,
                Concepto:'',
                Descripcion:'',
                FolioArchivo:'',
                Monto:0,
                Archivo:'',
            },
            errorvalidacion :[],
            ListaConceptos:[],
            FilePrevious:'',
            ListaProyectos:[],
            IdClienteS:0,
            Sucursal:{Nombre:''},
            Cliente:{Nombre:''},
            NameFile:'Elejir archivo (5 MB)',
            disableSearchCliente:false,
        }
    },methods: {
        async Guardar()
        {
            if(!this.ValProyecto()){
                this.$toast.info('No se pudo guardar, el proyecto fue cerrado');
                return false;
            }
          
            
            let formData = new FormData();

            formData.set('IdOrdenCompra',this.ordenc.IdOrdenCompra);
            formData.set('IdProyecto', this.ordenc.IdProyecto);
            formData.set('Concepto', this.ordenc.Concepto);
            formData.set('Descripcion', this.ordenc.Descripcion);
            formData.set('FolioArchivo', this.ordenc.FolioArchivo);
            formData.set('Monto', this.ordenc.Monto);
            formData.set('FilePrevious', this.ordenc.Archivo);
            
            let file = this.$refs.file.files[0];
            formData.append('File',file);

            //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;

            await this.$http.post(
                'spendoc/post',
                formData,
                {
                headers: {'Content-Type': 'multipart/form-data'}
                },
            ).then( (res) => {
            
                //deshabilita botones
                this.poBtnSave.disableBtn=false;  
                this.poBtnSave.toast=1;

                $('#ModalForm').modal('hide');
                this.bus.$emit('List'); 
               
            }).catch( err => {
                 //deshabilita botones
                this.poBtnSave.disableBtn=false;          

                if(err.response.data.typemsg=2){
                    this.errorvalidacion=err.response.data.message.errores;
                    this.poBtnSave.toast=2; 
                }else{
                    this.poBtnSave.toast=3; 
                    this.poBtnSave.toastmsg(err.response.data.message);
                }
            });
        },async get_conceptos(){
           
            await this.$http.get(
                'spendpoject/conceptos',
                {
                    params:{IdProyecto:0,Tipo:'2'}
                }
            ).then( (res) => {
                this.ListaConceptos =res.data.conceptos;
            });
        },Limpiar(){
            this.errorvalidacion=[];
            this.Sucursal={};
            this.Cliente={};
            this.ordenc={  IdOrdenCompra:0,
                IdProyecto:0,
                Concepto:'',
                Descripcion:'',
                FolioArchivo:'',
                Monto:0,
                Archivo:'',};
            this.NameFile='Elejir archivo (5 MB)';
            const  input  = this.$refs.file;
            if(input!=undefined){
                input.type = 'text';
                input .type = 'file';
            }

        },async get_recovery(){
            await this.$http.get(
                'spendoc/recovery',
                {
                    params:{IdOrdenCompra:this.ordenc.IdOrdenCompra}
                }
            ).then( (res) => {
                this.ordenc =res.data.data.ordencompra;
                if(res.data.sucursal!=null){
                    this.Sucursal =res.data.sucursal;
                }
                if(res.data.cliente!=null){
                    this.Cliente =res.data.cliente;
                }
                  this.get_list_proyecto();
                
            }); 
        },
        uploadFile()
        {
            const File = this.$refs.file.files[0];

                        var FileSize = File.size / 1024 / 1024; // in MB
            if (FileSize > 5) {
                this.$toast.info('Solo se puede subir archivos menores a 5 MB');
                const  input  = this.$refs.file;
                input .type = 'text'
                input .type = 'file';
                return false;
            }
                        
                        
            var allowedExtensions = /(\.pdf|\.PDF)$/i;
            if(!allowedExtensions.exec(File.name)){
                this.$toast.info('Solo se permiten Archivos PDF');
                const  input  = this.$refs.file;
                input.type = 'text'
                input.type = 'file';
                this.FilePrevious='';
                this.NameFile='Elejir archivo (5 MB)';
                return false;
            }

            this.NameFile=File.name;
            this.FilePrevious=File.name;

            /*const reader = new FileReader();
            var img="";
            reader.readAsDataURL(image);
            reader.onload= e =>{
                this.Img = e.target.result;     
            }*/
        },ListaCliente(){
            this.bus.$emit('ListCcliente');
        },add_datos(oSucursal,oCliente){
            this.Cliente=oCliente;
            this.Sucursal=oSucursal;
            this.get_list_proyecto();
            
        },async get_list_proyecto(){
            var EstatusFiltro="Abierto";
            if(this.ordenc.IdOrdenCompra>0 && this.Sucursal.IdSucursal==this.ordenc.IdSucursal){
                 EstatusFiltro="";
            }

            await this.$http.get(
                'spendpoject/get',
                {
                    params:{IdClienteS:this.Sucursal.IdClienteS,Estatus:EstatusFiltro}
                }
            ).then( (res) => {
                this.ListaProyectos =res.data.proyecto;
                this.ValProyecto();
            }); 
        }, ValProyecto(){
              let oprofind = this.ListaProyectos.filter(items => items.IdProyecto ==  this.ordenc.IdProyecto);
            
            if(oprofind.length>0){
                if(oprofind[0].Estatus=='Cerrado'){
                   this.disableSearchCliente=true;
                    return false;
                }
            }

            return true;
        }
    },created() {
        this.bus.$off('Save');
        this.bus.$off('Nuevo');

        this.bus.$off('SeleccionarCliente');
        this.bus.$on('SeleccionarCliente',(oSucursal,oCliente)=>
        {
           this.add_datos(oSucursal,oCliente);
        });

        this.bus.$on('Save',()=>
        {
           this.Guardar();
        });
        
        //Este es para modal
        this.bus.$on('Nuevo',(data,Id)=> 
        { 
            //deshabilita botones
            this.poBtnSave.disableBtn=false;    

            this.Limpiar();
            this.get_conceptos();
            this.disableSearchCliente=false;
            if (Id>0)
            {
                this.ordenc.IdOrdenCompra=Id;
                this.get_recovery();
            }
           
             
        });
        
    },mounted() {        
      
    },destroyed() {
        //this.ordenc={};
        this.Limpiar();
    },
}
</script>

<style>

</style>