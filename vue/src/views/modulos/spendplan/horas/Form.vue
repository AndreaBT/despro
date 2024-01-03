<template>
  <div>
      
    <div class="from-group row">
        <div class="col-md-6 col-lg-4">
            <label>Cliente</label>
            <input v-model="Cliente.Nombre" type="text" class="form-control" readonly />
        </div>
        <div class="col-md-6 col-lg-2">
            <div class="form-inline">
                <button  @click="ListaCliente"  data-toggle="modal" data-target="#ModalForm3"  data-backdrop="static" type="button" class="btn btn-01 search mt-4">Buscar</button>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <label>Sucursal</label>
            <input  v-model="Sucursal.Nombre" type="text" class="form-control" readonly />    
        </div>
    </div>
    <div class="from-group row">
        <div class="col-lg-6">
            <label>Proyecto</label>
            <select class="form-control" v-model="oHoras.IdProyecto">
                <option value="0">--Seleccionar--</option>
                <option v-for="(item, index) in ListaProyectos" :key="index" :value="item.IdProyecto">{{item.Proyecto}}</option>
            </select>
            <Cvalidation v-if="this.errorvalidacion.IdProyecto" :Mensaje="errorvalidacion.IdProyecto[0]"></Cvalidation>
        </div>
        <div class="col-lg-6">
            <label>Horas</label>
            <input type="number" class="form-control" v-model="oHoras.Horas">
            <Cvalidation v-if="this.errorvalidacion.Horas" :Mensaje="errorvalidacion.Horas[0]"></Cvalidation>
        </div>

    </div>
    <div class="from-group">
        <label>Descripción</label>
        <textarea class="form-control" rows="2" v-model="oHoras.Descripcion"></textarea>
        <Cvalidation v-if="this.errorvalidacion.Descripcion" :Mensaje="errorvalidacion.Descripcion[0]"></Cvalidation>
    </div>
    
   <Ccliente :TipoModal='2'></Ccliente>
  </div>
</template>

<script>

import Ccliente from '@/components/Ccliente.vue'
import Cvalidation from '@/components/Cvalidation.vue'

export default {
    components:{Cvalidation,Ccliente},
    data() {
        return {
            oHoras:{
                IdSpendHora:0,
                IdProyecto:0,
                Descripcion:'',
                Horas:0,
            },
            errorvalidacion :[],
            ListaConceptos:[],
            FilePrevious:'',
            ListaProyectos:[],
            IdClienteS:0,
            Sucursal:{Nombre:''},
            Cliente:{Nombre:''},
        }
    },methods: {
        async Guardar()
        {
            /*
            if(this.FilePrevious==''){
                this.$toast.info('Seleccione un archivo');
                return false;
            }*/
            let formData = new FormData();

            formData.set('IdSpendHora',this.oHoras.IdSpendHora);
            formData.set('IdProyecto', this.oHoras.IdProyecto);
            formData.set('Descripcion', this.oHoras.Descripcion);
            formData.set('Horas', this.oHoras.Horas);
            
            this.bus.$emit('BloquearBtn',0);

            await this.$http.post(
                'spendhora/post',
                formData,
                {
                headers: {'Content-Type': 'multipart/form-data'}
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
                this.bus.$emit('BloquearBtn',2);
               this.errorvalidacion=err.response.data.message.errores;
            });
        },Limpiar(){
            this.errorvalidacion=[];
            this.Sucursal={};
            this.Cliente={};
            this.oHoras={  IdSpendHora:0,
                IdProyecto:0,
                Concepto:'',
                Descripcion:'',
                Horas:0,
                Archivo:'',};
                
        },async get_recovery(){
            await this.$http.get(
                'spendhora/recovery',
                {
                    params:{IdSpendHora:this.oHoras.IdSpendHora}
                }
            ).then( (res) => {
                this.oHoras =res.data.data.spend_horas;
                if(res.data.sucursal!=null){
                    this.Sucursal =res.data.sucursal;
                }
                if(res.data.cliente!=null){
                    this.Cliente =res.data.cliente;
                }
                  this.get_list_proyecto();
                
            }); 
        },ListaCliente(){
            this.bus.$emit('ListCcliente');
        },add_datos(oSucursal,oCliente){
            
            this.Cliente=oCliente;
            this.Sucursal=oSucursal;
            this.get_list_proyecto();
            
        },async get_list_proyecto(){
            await this.$http.get(
                'spendpoject/get',
                {
                    params:{IdClienteS:this.Sucursal.IdClienteS}
                }
            ).then( (res) => {
                this.ListaProyectos =res.data.proyecto;
            }); 
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
            this.Limpiar();
            if (Id>0)
            {
                this.oHoras.IdSpendHora=Id;
                this.get_recovery();
            }
             this.bus.$emit('Desbloqueo',false);
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