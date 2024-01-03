<template>
  <div>
      <section class="container-fluid">
        <CHead :oHead="oHead"></CHead>
        <div class="row mt-2">
            
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card card-01">
                    <nav>
                        <div class="nav nav-tabs nav-tabs-table" role="tablist">
                            <a class="nav-link active" id="dato-tab" data-toggle="tab" href="#nav-dato" role="tab" aria-controls="nav-dato" aria-selected="true"><i class="fad fa-project-diagram fa-lg mr-2"></i> Proyecto</a>
                            <a class="nav-link" id="obra-tab" data-toggle="tab" href="#nav-obra" role="tab" aria-controls="nav-obra" aria-selected="false"> <i class="fad fa-clipboard-list-check fa-lg mr-2"></i> Spend Plan</a>
                        </div>
                    </nav>
                    <div class="tab-content tab-content-table">
                        <div class="tab-pane fade show active" id="nav-dato" role="tabpanel" aria-labelledby="dato-tab">
                            <CDatos  :proyecto="proyecto" :errorvalidacion="errorvalidacion"></CDatos>
                        </div>
                        <div class="tab-pane fade" id="nav-obra" role="tabpanel" aria-labelledby="obra-tab">
                            <CSpendPlan :proyecto="proyecto" :errorvalidacion="errorvalidacion"></CSpendPlan>
                        </div>
                    </div>
                    <Cbtnsave2  :poBtnSave="oBtnSave"></Cbtnsave2>
                </div>
            </div>
        </div>
    </section>
  </div>
</template>

<script>
import CDatos from '@/views/modulos/spendplan/proyecto/components/Datos.vue';
import CSpendPlan from '@/views/modulos/spendplan/proyecto/components/SpendPlan.vue';

export default {
    name:'Dato',
    props:['Id'],
    components:{CDatos,CSpendPlan},
    data() {
        return {
            proyecto:{
                IdProyecto:0,
                IdCliente:0,
                IdClienteS:0,
                Proyecto:'',
                FechaI:'',
                FechaTermino:'',
                CantidadTermino:'',
                ValorHora:0,
                ValorBurden:0,
                Tc:0,
                CostoOperacional:'',
                GAVentas:'',
                NetProfit:'',
                CostoOpePorc:'',
                GAVentasPorc:'',
                NetProfitPorc:'',
                Observaciones:'',
                Cliente:'',
                Sucursal:'',
                Direccion:'',
                ContactoS:'',
                Correo:'',
                Telefono:'',
                Ciudad:'',
                Archivo:'',
                Detalle:[],
                File:{},
            },
            errorvalidacion:[],
            RegresarA:'spend_proyecto',
            oHead:{
                Title:'Proyecto',
                isreturn:true,
                Url:'spend_proyecto',
            },
            Ruta:'',
            oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:false,
                disableBtn:false,
                toast:0,
                regresarA:'spend_proyecto'
            }
        }
    },methods: {
       async get_conceptos(){
            await this.$http.get(
                'spendpoject/conceptos',
                {
                    params:{IdProyecto:this.proyecto.IdProyecto}
                }
            ).then( (res) => {
                this.proyecto.Detalle =res.data.conceptos;
            });
        },async Save(){
            var event = new Date(this.proyecto.FechaI);
            let FechaI = JSON.stringify(event);
            FechaI = FechaI.slice(1,11);

            if(this.proyecto.Detalle.length<=0){
                this.$toast.info('No hay conceptos para guardar');
                return false;
            }
            if(this.proyecto.Estatus=='Cerrado'){
                this.$toast.info('No se puede modificar, el proyecto ha sido cerrado');
                return false;
            }

            let formData = new FormData();
            formData.set('IdProyecto',this.proyecto.IdProyecto);
            formData.set('IdCliente',this.proyecto.IdCliente);
            formData.set('IdClienteS',this.proyecto.IdClienteS);
            formData.set('FechaI',FechaI);
            formData.set('FechaTermino',this.proyecto.FechaTermino);
            formData.set('CantidadTermino',this.proyecto.CantidadTermino);
            formData.set('Proyecto',this.proyecto.Proyecto);
            formData.set('ValorHora',this.proyecto.ValorHora);
            formData.set('ValorBurden',this.proyecto.ValorBurden);
            formData.set('CostoOperacional',this.proyecto.CostoOperacional);
            formData.set('GAVentas',this.proyecto.GAVentas);
            formData.set('NetProfit',this.proyecto.NetProfit);
            formData.set('CostoOpePorc',this.proyecto.CostoOpePorc);
            formData.set('GAVentasPorc',this.proyecto.GAVentasPorc);
            formData.set('NetProfitPorc',this.proyecto.NetProfitPorc);
            formData.set('Archivo',this.proyecto.Archivo);
            formData.set('Observaciones',this.proyecto.Observaciones);
            formData.set('Detalle',JSON.stringify(this.proyecto.Detalle));

            formData.append('File',this.proyecto.File);

            //deshabilita botones
            this.oBtnSave.toast=0; 
            this.oBtnSave.disableBtn=true;

            await this.$http.post(
                'spendpoject/post',
                formData,
                {
                headers: {'Content-Type': 'multipart/form-data'}
                },
            ).then( (res) => {
                //deshabilita botones
                this.oBtnSave.disableBtn=false;  
                this.oBtnSave.toast=1;

                this.$router.push({name:'spend_proyecto'});
            }).catch( err => {
                //deshabilita botones
              
                this.oBtnSave.disableBtn=false;  
                if(err.response.data.typemsg==2){
                    this.errorvalidacion=err.response.data.message.errores;
                    this.oBtnSave.toast=2; 
                }else{
                    this.oBtnSave.toast=3; 
                    this.oBtnSave.toastmsg(err.response.data.message);
                }
            });
        },async get_recovery(){
              await this.$http.get(
                'spendpoject/recovery',
                {
                    params:{IdProyecto: this.proyecto.IdProyecto}
                }
            ).then( (res) => {
                
                this.proyecto =res.data.data.proyecto;
                this.Ruta =res.data.Ruta;

                let formatedDate = this.proyecto.FechaI.replace(/-/g,'\/')
                this.proyecto.FechaI = new Date(formatedDate);

            });
        },
         
    },created() {
        //deshabilita botones
        this.oBtnSave.disableBtn=false;   

        if(this.Id!=undefined){
               sessionStorage.setItem('IdSaved',JSON.stringify(this.Id));
        }
        this.proyecto.IdProyecto=JSON.parse( sessionStorage.getItem('IdSaved'));

        
    },mounted() {
        this.bus.$off('Save');

        this.bus.$on('Save',()=>
        {
           this.Save();
        });

        if(this.proyecto.IdProyecto>0){
            this.get_recovery();
        }

        
        this.get_conceptos();
       
        
    },destroyed() {
        sessionStorage.removeItem('IdSaved');
    }
}
</script>

<style>

</style>