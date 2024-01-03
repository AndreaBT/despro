<template>
    <div>
        <fieldset class="sin">
            <div class="form-row">
                <div  class="col-lg-10 form-group">
                    <h4 class="titulo-04">Factura</h4>
                </div>
                <div  class="col-lg-2 form-group  mt-2">
                    <h4 class="titulo-04" style="color:#104690"><b>{{factura.FolioFactura}}</b></h4>
                </div>

                <div v-if="factura.RegEstatus=='Anulada'" hidden class="col-lg-4 form-group">
                    <label>Fecha de Anulación</label>

                        <v-date-picker

                            v-model="factura.FechaAnulado"
                            :popover="{
                                placement: 'right',
                                visibility: 'click',

                            }"
                            :input-props='{
                                class:"form-control calendar",
                                style:"cursor:pointer;background-color:#F9F9F9"

                            }'
                        />




                </div>
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Dias_de_Credito" :Mensaje="errorvalidacion.Dias_de_Credito[0]"></Cvalidation></label>

                <!-- <div v-if="factura.RegEstatus=='Anulada'"  class="col-lg-4 form-group">
                     <label>Fecha Facturación</label>
                    <input @input="SumCredito()" type="text"  v-model="FechaFacReal" class="form-control" >
                    <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Fecha_Facturacion" :Mensaje="errorvalidacion.Fecha_Facturacion[0]"></Cvalidation></label>
                </div> -->

                <div  class="col-lg-4 form-group">
                    <label>Fecha Facturación</label>

                        <v-date-picker

                            @input="SumCredito()"

                            v-model="factura.FechaFacReal"
                            :popover="{
                                placement: 'right',
                                visibility: 'click',

                            }"
                            :input-props='{
                                class:"form-control calendar",
                                style:"cursor:pointer;background-color:#F9F9F9"

                            }'
                        />

                        <!-- <input  v-else-if="factura.FolioFactReal!=''" @input="SumCredito()" type="text"  v-model="FechaFacReal" readonly  class="form-control" > -->
                    <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Fecha_Facturacion" :Mensaje="errorvalidacion.Fecha_Facturacion[0]"></Cvalidation></label>
                </div>



                <div  class="col-lg-4 form-group">
                    <label > Días de crédito </label>
                    <input    @input="SumCredito()" type="text" v-model="factura.DiasCredito"  class="form-control" placeholder="10">
                    <!-- <input v-else-if="factura.FolioFactReal!=''"  @input="SumCredito()" type="text" v-model="factura.DiasCredito" readonly  class="form-control" placeholder="10"> -->
                    <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Dias_de_Credito" :Mensaje="errorvalidacion.Dias_de_Credito[0]"></Cvalidation></label>
                </div>

                <div v-if="factura.RegEstatus!='Anulada'" class="col-lg-4 form-group">
                    <label > Fecha Cobro </label>
                    <v-date-picker
                        v-model="ctaporcobrar.FechaCobro"
                        :popover="{
                            placement: 'bottom',
                            visibility: 'click',
                        }"
                        :input-props='{
                            class:"form-control  calendar",
                            style:"cursor:pointer;background-color:#F9F9F9",

                        }'
                    />

                </div>

                <div class="col-lg-4 form-group">
                    <label>Folio </label>
                    <input type="text"  v-model="factura.FolioFactReal"    class="form-control" placeholder="Folio">
                    <!-- <input type="text" v-else-if="factura.FolioFactReal!=''"  v-model="factura.FolioFactReal" readonly   class="form-control" placeholder="Folio"> -->
                    <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Folio_Factura" :Mensaje="errorvalidacion.Folio_Factura[0]"></Cvalidation></label>
                </div>


                <div class="col-lg-4 form-group">
                    <label>Monto</label>
                    <input   type="text"  v-model="factura.Monto"    class="form-control" placeholder="Monto">
                    <!-- <input v-else-if="factura.FolioFactReal!=''"   readonly type="text"  v-model="factura.Monto"    class="form-control" placeholder="Monto"> -->
                    <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Monto" :Mensaje="errorvalidacion.Monto[0]"></Cvalidation></label>
                </div>

                <div class="col-lg-4 form-group">
                    <label>Estatus</label>
                        <select v-model="factura.Facturado"   class="form-control">
                            <option value="A">Vigente</option>
                            <option value="Anulada">Anular</option>
                        </select>
                </div>

                <div v-if="factura.Facturado!='Anulada'"  class="col-lg-12 form-group">
                    <label>Añadir Archivo</label>
                    <div  class="custom-file-input-image">
                        <input   @change="uploadImage()" type="file" accept="application/pdf" ref="file" class="custom-file-input" id="validatedCustomFile" required>
                        <input  type="text" v-model="NameFile" class="form-control">
                        <button type="button" class=""><i class="fas fa-paperclip"></i></button>
                    </div>

                </div>





                <div v-if="factura.Facturado!='Anulada'" class="col-lg-12 form-group">
                    <label>Observación</label>
                    <textarea  v-model="factura.Observacion"  placeholder=" Coloque sus Observaciones" class="form-control" cols="12" rows="3"></textarea>
                    <!-- <textarea v-else-if="factura.FolioFactReal!=''" v-model="factura.Observacion" readonly  placeholder=" Coloque sus Observaciones" class="form-control" cols="12" rows="3"></textarea> -->
                </div>

                <!-- <div v-else-if="factura.RegEstatus=='Anulada'" class="col-lg-6 form-group">
                    <label>Observación</label>
                    <textarea v-model="factura.Observacion" readonly  placeholder=" Coloque sus Observaciones" class="form-control" cols="12" rows="3"></textarea>
                </div> -->

                <div v-if="factura.Facturado=='Anulada'" class="col-lg-6 form-group">
                    <label>Motivo de Anulación </label>
                    <textarea v-model="factura.ComentarioAnulada"  placeholder=" Coloque el motivo de la Anulación" class="form-control" cols="12" rows="3"></textarea>
                </div>


            </div>



        </fieldset>

           <!--
            <div class="modal-footer modal-footer-form">
                <button type="button" class="btn btn-04 ban mr-2">Cancelar</button>
                <button type="button" :disabled="loading" @click="ChangeFactura"  class="btn btn-01 save">Guardar</button>
            </div>-->
   </div>
</template>
<script>
import Cvalidation from '@/components/Cvalidation.vue'
import { isDateSpansEqual } from '@fullcalendar/core';

export default {
   name:'Cliente',
   props:['factura','poBtnSave','fechas'],
   components:{
      Cvalidation
   },
   data() {
       return {
           Img:null,
            NameFile:'Elejir archivo (5 MB)',
            loading:false,
            ctaporcobrar:{
             IdCtaCobrar:0,
             IdFactura:0,
             FechaCobro:'',
             DiasCredito:0,


            },

            errorvalidacion:[],
            FechaFacReal:'',
            FechaCobro:'',
            DiasCredito:0,
            Observacion:'',
            IdServicio:0,
            facturaxServ:[]
       }
   },methods: {


       ChangeFactura()
        {
            this.errorvalidacion =[];
            let FechaI='0000-00-00';
            if (this.factura.FechaFacReal !='')
            {
                let day = this.factura.FechaFacReal.getDate();
                let month = this.factura.FechaFacReal.getMonth() + 1;
                let year = this.factura.FechaFacReal.getFullYear();
                FechaI=year+'-'+month+'-'+day;
            }


             let FolioFactReal='';
            let FilePrevious='';
            let Monto ='';
            let ComentarioAnulada='';
            let Observacion=''
            if (this.factura.FolioFactReal!=null)
            {
                FolioFactReal=this.factura.FolioFactReal;
            }
            if (this.factura.Monto!=null)
            {
                Monto=this.factura.Monto;
            }
            if (this.factura.ComentarioAnulada!=null)
            {
                ComentarioAnulada=this.factura.ComentarioAnulada;
            }
            if (this.factura.ArchivoFactura!=null)
            {
                FilePrevious=this.factura.ArchivoFactura;
            }
            if (this.factura.FilePrevious!='' && this.factura.FilePrevious!=null)
            {
                FilePrevious =this.factura.FilePrevious;
            }
            if(this.factura.Observacion!=null){
                Observacion = this.factura.Observacion;
            }

            this.loading=true;
            let formData = new FormData();
            formData.set('IdFactura',this.factura.IdFactura);
            formData.set('FechaFacReal',FechaI);
            formData.set('FolioFactReal',FolioFactReal);
            formData.set('RegEstatus',this.factura.RegEstatus);
            formData.set('Monto',this.factura.Monto);
            formData.set('DiasCredito',this.factura.DiasCredito);
            formData.set('ComentarioAnulada',this.factura.ComentarioAnulada);
            formData.set('Observacion',this.factura.Observacion);
            formData.set('FilePrevious', FilePrevious);
            let file = this.$refs.file.files[0];
            formData.append('File',file);
             //deshabilita botones
            this.poBtnSave.toast=0;
            this.poBtnSave.disableBtn=true;


           this.$http.post(
                'factura/ChangeFactura/post',
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
                this.$emit('Listar');
                this.SumCredito();



            }).catch( err => {
                this.poBtnSave.disableBtn=false;
                this.poBtnSave.toast=2;
                this.errorvalidacion=err.response.data.message.errores;
            });

        },

        //CUENTAS POR COBRAR
        async GuardarCPC()
        {
             //deshabilita botones


            await this.$http.post(
                'ctaporcobrar/post',
                this.ctaporcobrar
                ,
            ).then( (res) => {


            }).catch( err => {
            });

        },
        //CUENTAS POR COBRAR

       UpdateFacturas(){
            let formData = new FormData();
            formData.set('IdServicio',this.IdServicio);
            formData.set('Facturado',this.factura.Facturado);
            formData.set('ComentarioAnulada',this.factura.ComentarioAnulada);
            formData.set('FechaAnualdo',this.factura.FechaAnulado);


            this.poBtnSave.toast=0;
            this.poBtnSave.disableBtn=true;
            this.$http.post(
                'factura/facturaAnulada/post',
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
                this.$emit('Listar');


            }).catch( err => {
                this.poBtnSave.disableBtn=false;
                this.poBtnSave.toast=2;
                this.errorvalidacion=err.response.data.message.errores;
            });

        },

        FacturaActualizar(){
            if(this.factura.Facturado==='Anulada'){
                this.UpdateFacturas();
                this.recovery();

            }else{
                this.ChangeFactura();
            }
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

        SumCredito(){

            let fechaFactura= this.factura.FechaFacReal;
            let diasCredito = this.factura.DiasCredito;

            if(diasCredito>0){
                var date = new Date(fechaFactura);
                date.setDate(fechaFactura.getDate()+ parseInt(diasCredito));
                this.ctaporcobrar.FechaCobro=date;

            }

        },

        recovery(){



             this.$http.get(
               "factura/recoveryFact",
                {
                    params:{IdServicio: this.IdServicio,IdFactura:this.factura.IdFactura}
                },this.factura,
            ).then( (res) => {

                this.facturaxServ=res.data.data.facturaxserv;
            });
        }


   },
   created() {

       this.bus.$off('EditarCance');

        //Este es para modal
        this.bus.$on('EditarCance',(data,IdFactura,DiasCredito,Observacion,FechaFacReal,IdServicio)=>
        {
            this.NameFile='Seleccione un archivo';
                this.$refs.file.value='';
              this.poBtnSave.disableBtn=false;
              this.ctaporcobrar.IdFactura=IdFactura;
              this.DiasCredito=DiasCredito;
              this.Observacion=Observacion;
              this.FechaFacReal=FechaFacReal;
              this.IdServicio=IdServicio;

            //   if (this.IdServicio>0) {
            //       this.recovery();
            //   }
            //   console.log(IdServicio);

            this.bus.$off('Save');
            this.bus.$on('Save',()=>
            {
                this.FacturaActualizar();
                this.GuardarCPC();
            });







         });

       this.bus.$off('Limpiar');
       this.bus.$on('Limpiar',()=>
       {
           this.errorvalidacion=[];
           this.factura.DiasCredito='';
       }
       );

   },
}
</script>
