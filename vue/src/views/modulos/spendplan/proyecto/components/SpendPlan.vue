<template>
  <div>
    <div class="row justify-content-center ">
    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
        
        <table class="table table-06">
            <thead>
                <tr>
                    <th>
                        Descripción
                    </th>
                    <th>
                        Monto
                    </th>
                    <th>
                        % Porcentaje
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in proyecto.Detalle" :key="index">
                    <td>
                        <b class="color-04">{{item.Concepto}}</b>
                    </td>
                    <td>
                        <vue-numeric @input="Calculo_Porcentaje();"   :minus="false" class="form-control form-control-sm text-right"  currency="$" separator="," :precision="2" v-model="item.Monto"></vue-numeric>
                        <!--<input @keyup="Calculo_Porcentaje();"  v-model="item.Monto" type="text" class="form-control form-control-sm dollar">-->
                    </td>
                    <td>
                        <!--<input  readonly v-model="item.Porcentaje" type="text" class="form-control form-control-sm porc">-->
                        <vue-numeric  disabled :minus="false" class="form-control form-control-sm text-right"  currency="%" separator="," :precision="2" v-model="item.Porcentaje"></vue-numeric>
                    </td>
                </tr>
            </tbody>
            <tfoot class="mt-1">
                <tr>
                    <td>
                        <b class="color-04">Costo Operacional</b>
                    </td>
                    <td>
                        <!--<input v-model="proyecto.CostoOperacional" type="text" class="form-control form-control-sm dollar form-sombra" readonly>-->
                        <vue-numeric  disabled :minus="false" class="form-control form-control-sm text-right"  currency="$" separator="," :precision="2" v-model="proyecto.CostoOperacional"></vue-numeric>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="errorvalidacion.CostoOperacional" :Mensaje="errorvalidacion.CostoOperacional[0]"></Cvalidation></label>
                    </td>
                    <td>
                        <!--<input v-model="proyecto.CostoOpePorc" type="text" class="form-control form-control-sm  form-sombra porc" readonly>-->
                        <vue-numeric  disabled :minus="false" class="form-control form-control-sm text-right"  currency="%" separator="," :precision="2" v-model="proyecto.CostoOpePorc"></vue-numeric>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="errorvalidacion.CostoOpePorc" :Mensaje="errorvalidacion.CostoOpePorc[0]"></Cvalidation></label>
                    </td>
                </tr>
                <tr>
                    <td>
                       <b class="color-04"> G&A/ Ventas</b>
                    </td>
                    <td>
                        <!--<input v-model="proyecto.GAVentas" type="text" class="form-control form-control-sm dollar form-sombra " readonly>-->
                        <vue-numeric  disabled :minus="false" class="form-control form-control-sm text-right"  currency="$" separator="," :precision="2" v-model="proyecto.GAVentas"></vue-numeric>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="errorvalidacion.GAVentas" :Mensaje="errorvalidacion.GAVentas[0]"></Cvalidation></label>
                    </td>
                    <td>
                        <!--<input  v-model="proyecto.GAVentasPorc" type="text" class="form-control form-control-sm  form-sombra porc" readonly>-->
                        <vue-numeric  disabled :minus="false" class="form-control form-control-sm text-right"  currency="$" separator="," :precision="2" v-model="proyecto.GAVentasPorc"></vue-numeric>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="errorvalidacion.GAVentasPorc" :Mensaje="errorvalidacion.GAVentasPorc[0]"></Cvalidation></label>
                    </td>
                </tr>
                <tr>
                    <td>
                       <b class="color-04"> Net profit</b>
                    </td>
                    <td>
                        <!--<input @keyup="Calculo_Porcentaje()" v-model="proyecto.NetProfit" type="text" class="form-control form-control-sm dollar " >-->
                        <vue-numeric @input="Calculo_Porcentaje()"  :minus="false" class="form-control form-control-sm text-right"  currency="$" separator="," :precision="2" v-model="proyecto.NetProfit"></vue-numeric>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="errorvalidacion.NetProfit" :Mensaje="errorvalidacion.NetProfit[0]"></Cvalidation></label>
                    </td>
                    <td>
                        <!--<input v-model="proyecto.NetProfitPorc" type="text" class="form-control form-control-sm  form-sombra porc" readonly>-->
                        <vue-numeric disabled @input="Calculo_Porcentaje()"  :minus="false" class="form-control form-control-sm text-right"  currency="%" separator="," :precision="2" v-model="proyecto.NetProfitPorc"></vue-numeric>
                        <label id="lblmsuser" style="color:red"><Cvalidation v-if="errorvalidacion.NetProfitPorc" :Mensaje="errorvalidacion.NetProfitPorc[0]"></Cvalidation></label>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="form-group form-row">
            <div class="col-md-6 col-lg-6 ">
                <label>Subir Firma</label>
                <div class="custom-file-input-image">
                    <input :disabled="DisablePDf" @change="uploadFile()" type="file" accept="application/pdf" ref="file">
                    <input type="text" v-model="NameFile" class="form-control">
                    <button :disabled="DisablePDf" type="button" class=""><i class="fas fa-paperclip"></i></button>
                    <label v-if="DisablePDf" style="color:red">Solo el administrador puede actualizar la firma</label>
                </div>
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="errorvalidacion.Archivo" :Mensaje="errorvalidacion.Archivo[0]"></Cvalidation></label>
            </div>
        </div>
        <div class="form-group">
            <label>Observaciones</label>
            <textarea v-model="proyecto.Observaciones" class="form-control" rows="3"></textarea>
        </div>
    </div>
</div>
  </div>
</template>

<script>
export default {
    name:'',
    props:['proyecto','errorvalidacion'],
    data() {
        return {
            NameFile:'Elejir archivo (5 MB)',
            DisablePDf:false,
        }
    },methods: {
        Calculo_Porcentaje(){
                        
            var Contador=0;
            
            var TotalFacturaPorcentaje=parseFloat(100);
            var Facturacion='';
            if(this.proyecto.Detalle.length>0){
                if(this.proyecto.Detalle[0].Monto!=''){
                    Facturacion=parseFloat(this.proyecto.Detalle[0].Monto);
                }
                this.proyecto.Detalle[0].Porcentaje=parseFloat(TotalFacturaPorcentaje);
            }
          
            
           
            var TotalCostoOperacional=0;
            var TotalPorcentaje=0;
            
            this.proyecto.Detalle.forEach(element => {
                var Monto=0;
                 if(element.Monto==''){
                    Monto=0;
                }else{
                    Monto= parseFloat(element.Monto);
                }
                
               
                if(Contador>0 && Facturacion>0){
                    var Porcentaje=(Monto*TotalFacturaPorcentaje)/Facturacion;
                    element.Porcentaje=parseFloat(Porcentaje).toFixed(0);
                    TotalCostoOperacional+=parseFloat(Monto);
                    TotalPorcentaje+=parseFloat(Porcentaje);
                }
                Contador ++;
            });
         
            //Costo operacional
            this.proyecto.CostoOperacional=parseFloat(TotalCostoOperacional).toFixed(0);
            this.proyecto.CostoOpePorc=parseFloat(TotalPorcentaje).toFixed(0);

            //G&A /VENTA
            this.proyecto.GAVentas=parseFloat(Facturacion-TotalCostoOperacional-this.proyecto.NetProfit).toFixed(0);

            //PORcentajes
            var GAVentasPorc=0;
            var NetProfitPorc=0;
            if(Facturacion>0){
                GAVentasPorc=parseFloat((100*this.proyecto.GAVentas)/Facturacion).toFixed(0)
                var NetProfitPorc=parseFloat((this.proyecto.NetProfit*100)/Facturacion).toFixed();
            }
            
            //G&A /VENTA
            this.proyecto.GAVentasPorc=parseFloat(GAVentasPorc).toFixed(0);
            //NEt profit
            this.proyecto.NetProfitPorc=parseFloat(NetProfitPorc).toFixed(0);
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

            this.proyecto.File=File;

            /*const reader = new FileReader();
            var img="";
            reader.readAsDataURL(image);
            reader.onload= e =>{
                this.Img = e.target.result;     
            }*/
        },GetFile(){

             let File=this.$refs.file.files[0];
            
            
        }
    },created() {
    
    },mounted() {
        var datos = JSON.parse(sessionStorage.getItem('user'));
       
        if(this.proyecto.IdProyecto > 0){
            if(datos.Perfil != "Administración" && datos.Perfil != "Admin"){
                this.DisablePDf=true; 
                alert('asdsa');
            }
        }
        
    },
    computed: {
       
    },
}
</script>

<style>

</style>