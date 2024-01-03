<template>
 <form class="form-cotizacion">

      <div class="form-group form-row">
            <div class="col-md-3 col-lg-3">
            <label>Tipo de servicio *</label>
            <select class="form-control form-control-sm" v-model="servicios.Tipo_Serv" >
                <option :value="''" >seleccione una opcion</option>
                <option v-for="(item, index) in ListaTipoServicio" :key="index" :value="item.IdTipoSer" >{{item.Concepto}}</option>
            </select>
            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.TipoServ" :Mensaje="errorvalidacion.TipoServ[0]"></Cvalidation></label>
            </div>
            
            <div class="col-md-7 col-lg-7"></div>
            <div class="col-md-2 col-lg-2">
                <label>Folio</label>
                <input type="text" class="form-control form-control-sm bold color-02" v-model="servicios.Folio" readonly="">
            </div>
        </div>
    
        <Cliente :Tipo="false"   v-if="ShowComponent" :Consultado="Consultado" :servicios="servicios" :oclientesuc="oclientesuc" :errorvalidacion="errorvalidacion"> </Cliente>
        <Comentarios  v-if="ShowComponent" :servicios="servicios" :errorvalidacion="errorvalidacion"></Comentarios>
        <Asigados  v-if="ShowComponent" :ruta="ruta"  :servicios="servicios" > </Asigados>
 
       
</form>
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from '@/components/Cbtnsave.vue'
import Cvalidation from '@/components/Cvalidation.vue'
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Ccliente from '@/components/Ccliente.vue'

import Cliente from '@/views/modulos/servicios/componentes/Cliente.vue'
import Comentarios from '@/views/modulos/servicios/componentes/Comentarios.vue'
import Asigados from '@/views/modulos/monitoreo/Asignados.vue'




export default {
    name:'Form',
    props:[''],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'vehiculo',//Sirve para donde va regresar
            PasoActual:1,
            ListaCategoria:[],
            servicios:{
                IdServicio:0,
                IdCliente:'',
                IdClienteS:'',
                Cliente:'',
                Direccion:'',
                Contacto:'',
                Telefono:'',
                Correo:'',
                Distancia:0,
                Velocidad:0,
                Tipo_Serv:'',
                Trabajadores:[],
                Vehiculos:[],
                Personal:0,
                Fecha_I:'',
                Fecha_F:'',
                Observaciones:'',
                EstadoS:'ABIERTA',
                IdVehiculo:'',
                EquiposD:0,
                MaterialesD:0,
                ViaticosD:0,
                ContratistasD:0,
                ManoObraT:0,
                BurdenTotal:0,
                CostoV:0,
                IdContrato:0,
                NumContrato:'',
                Paso:1,
                FechasHoras:[],
                Econtacto:'',
                Contacto:'',
                Para:[],
                tag:'',
                Enviar:false,
            },
            Consultado:{ 
                ListaTrabajadores:[],
                ListaVehiculos:[],
                ListaNumc:[]
            },
            ListaClientes:[],
            ListaSucursal:[],
            Mostrar:true,
            Regresar:false,
            TituloLista:'Lista de Clientes',
            errorvalidacion:[],
            urlApi:'servicio/recovery',
            ShowComponent:false,
            oclientesuc:{},
            ListaTipoServicio:[],
            Pasos:[
                { Nombre:'Cliente' ,Paso:1,Estado:true,Selec:true},
                { Nombre:'Fechas' ,Paso:2,Estado:false,Selec:false},
                { Nombre:'Asignar' ,Paso:3,Estado:false,Selec:false},
                { Nombre:'Tareas y Materiales' ,Paso:4,Estado:false,Selec:false},
                { Nombre:'Observaciones y Costos' ,Paso:5,Estado:false,Selec:false},
                { Nombre:'Fotos' ,Paso:6,Estado:false,Selec:false}
            ],
            Disablebtn:false,
            txtSave:'Guardar',
            contadores: {
                Observaciones: 0,
                Imagenes: 0
            },
            ruta:''
        }
    },
    components:{
       Clist, Cbtnsave,Cvalidation,Cliente,Comentarios,Modal,Asigados
    },
    methods :
    {
        async ListaServ()
        {
            await this.$http.get(
                'tiposervicio/get',
                {
                    params:{Nombre:'',Entrada:50,pag:0, RegEstatus:'A'}
                }
            ).then( (res) => {
              this.ListaTipoServicio =res.data.data.tiposervicio;
            });
        },
        async ListaNumContrato()
        {
            await this.$http.get(
                'numcontrato/get',
                {
                    params:{IdClienteS:this.servicios.IdClienteS}
                }
            ).then( (res) => {
                this.Consultado.ListaNumc =res.data.data.row;
            });
        },
        Limpiar()
        {
            this.PasoActual=1;
            this.Consultado.ListaNumc=[];
            this.servicios.Folio='';
            this.servicios.Paso=1;
            this.servicios.IdCliente='';
            this.servicios.IdClienteS='';
            this.servicios.Cliente='';
            this.servicios.Direccion='';
            this.servicios.Contacto='';
            this.servicios.Telefono='';
            this.servicios.Correo='';
            this.servicios.Distancia=0;
            this.servicios.Velocidad=0;
            this.servicios.TipoServ='';
            this.servicios.Trabajadores=[];
            this.servicios.Personal=0;
            this.servicios.Fecha_I='';
            this.servicios.Fecha_F='';
            this.servicios.Observaciones='';
            this.servicios.EstadoS='ABIERTA';
            this.servicios.IdVehiculo='';
            this.servicios.IdContrato=0;
            this.servicios.Materiales='';
            this.servicios.Tipo_Serv='';
            this.servicios.Para=[];
            this.servicios.tag='';
            this.servicios.Enviar=false;
            this.servicios.Econtacto='';
            this.servicios.Contacto='';

            this.Consultado.ListaTrabajadores=[];
            this.Consultado.ListaVehiculos=[];
            this.ListaClientes=[];
            this.ListaSucursal=[];
            this.errorvalidacion=[];
            this.oclientesuc={};
            this.Pasos=[
                { Nombre:'Cliente' ,Paso:1,Estado:true,Selec:true},
                { Nombre:'Fechas' ,Paso:2,Estado:false,Selec:false},
                { Nombre:'Asignar' ,Paso:3,Estado:false,Selec:false},
                { Nombre:'Tareas y Materiales' ,Paso:4,Estado:false,Selec:false},
                { Nombre:'Observaciones y Costos' ,Paso:5,Estado:false,Selec:false},
                { Nombre:'Fotos' ,Paso:6,Estado:false,Selec:false}
            ];
            this.bus.$emit('LimpiarCompoenets');
        },
        get_revovy(){
            this.$http.get(
                this.urlApi,
                {
                    params:{IdServicio: this.servicios.IdServicio}
                }
            ).then( (res) => {
                this.servicios =res.data.data.servicio;    
                var formatedDate = this.servicios.Fecha_I.replace(/-/g,'\/')
                this.servicios.Fecha_I = new Date(formatedDate);
                var  formatedDate2 = this.servicios.Fecha_F.replace(/-/g,'\/')
                this.servicios.Fecha_F = new Date(formatedDate2);
                

                this.servicios.Paso=1;
                if(this.servicios.EquiposD==0){
                    this.servicios.EquiposD=0; 
                }
                if(this.servicios.MaterialesD==0){
                    this.servicios.MaterialesD=0; 
                }
                if(this.servicios.ViaticosD==0){
                    this.servicios.ViaticosD=0; 
                }
                if(this.servicios.ContratistasD==0){
                    this.servicios.ContratistasD=0; 
                }
                if(this.servicios.ManoObraT==0){
                    this.servicios.ManoObraT=0; 
                }
                if(this.servicios.BurdenTotal==0){
                    this.servicios.BurdenTotal=0; 
                }
                if(this.servicios.CostoV==0){
                    this.servicios.CostoV=0; 
                }
                if(this.servicios.EstadoS==''){
                    this.servicios.EstadoS='ABIERTA'; 
                }
                              
                if(this.servicios.Econtacto!='' && this.servicios.Econtacto!=null)
                {
                    this.servicios.Para=[];
                    this.servicios.Para.push({ "text": this.servicios.Econtacto});  
                }

                this.oclientesuc=res.data.data.clientesuc;
                this.ObtenerTrabajadores();
            });
        },
        ObtenerTrabajadores()
        {
            this.$http.get(
                'servicio/trabajadoresxserv',
                 {
                    params:{IdServicio: this.servicios.IdServicio}
                },
            ).then( (res) => {
                this.servicios.Trabajadores = res.data.rows;
                this.servicios.Vehiculos = res.data.rowsv;
                this.ShowComponent=true;
                this.ruta=res.data.ruta;
            }).catch( err => {
                console.log(err.response.data.message); 
            });
        },
    },
    created() {
      
        this.bus.$off('Regresar');
        this.bus.$off('Nuevo');
        this.bus.$off('SeleccionarCliente');
        this.ListaServ();
        
        this.bus.$on('SeleccionarCliente',(oSucursal)=>
        {
           this.SeleccionarCliente(oSucursal);
        });

        this.bus.$on('Regresar',()=>
        {
        });

        this.bus.$on('Nuevo',(data,Id)=>
        {
            if (Id==undefined)
            {
                Id=0;
            }
            this.Limpiar();  
            this.ShowComponent=false;
        
            this.servicios.IdServicio=Id;
            if(Id>0)
            {
                this.get_revovy();
            }
            else
            {
                this.ShowComponent=true;
            }
        });
    }
}
</script>