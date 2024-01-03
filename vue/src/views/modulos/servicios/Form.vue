<template>
<div>
    <div v-show="loader" class="row mt-5">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 text-center">
            <h1><i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i> Cargando Por Favor Espere...</h1>
        </div>
    </div>
    <form v-show="loader==false" class="form-cotizacion">
        <div class="form-group form-row">
            <div class="col-md-3 col-lg-3">
                <label>Tipo de Servicio *</label>
                <select id="TipoServicio" class="form-control form-control-sm" v-model="servicios.Tipo_Serv" @change="GetServicio();">
                    <option value="">Seleccione una Opcion</option>
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

        <Cliente :Tipo="true" @Listar="ListaCliente"  v-if="ShowComponent" :Consultado="Consultado" :servicios="servicios" :oclientesuc="oclientesuc" :errorvalidacion="errorvalidacion"> </Cliente>
        <Fechas v-if="ShowComponent" :Consultado="Consultado" :servicios="servicios" :errorvalidacion="errorvalidacion" :pBloker="BlockerDates" ></Fechas>
        <Personal v-if="ShowComponent" :Consultado="Consultado" :servicios="servicios" :errorvalidacion="errorvalidacion">></Personal>
        <Comentarios v-if="ShowComponent" :servicios="servicios" :errorvalidacion="errorvalidacion"></Comentarios>
        <Costos v-if="servicios.IdServicio>0" :servicios="servicios"></Costos>

        <!--End Levantamiento-->
        <!-- <Levantamiento v-if="servicios.IdServicio>0" :servicios="servicios"></Levantamiento> -->
        <!--End Levantamiento-->

        <div class="row mt-4">
            <div class="col-md-12 col-lg-12">
                <h4>Estatus y Facturación</h4>
                <hr>
            </div>
        </div>

        <!--End Costos-->
        <div class="row mt-2">
            <div v-show="servicios.IdServicio>0"  class="col-md-2 col-lg-2">
                <label>Estatus *</label>
                <select v-model="servicios.EstadoS" class="form-control form-control-sm">
                    <option :value="''">Seleccione una Opción</option>
                    <option :value="'ABIERTA'">ABIERTA</option>
                    <option v-if="servicios.EstadoS=='PENDIENTE'" :value="'PENDIENTE'">PENDIENTE</option>
                    <option v-if="servicios.EstadoS=='REALIZADA'" :value="'REALIZADA'">PENDIENTE</option>
                    <option :value="'CERRADA'">CERRADA</option>
                    <option v-if="BlockerDates.BlockFecha !== 'Si' " :value="'CANCELADA'">CANCELADA</option>
                </select>
                <label style="color:red"><Cvalidation v-if="this.errorvalidacion.Estado_servicio" :Mensaje="errorvalidacion.Estado_servicio[0]"></Cvalidation></label>
            </div>
            <div class="col-md-2 col-lg-2">
                <label>Factura *</label>
                <select v-model="servicios.Factura" class="form-control form-control-sm">
                    <option  :value="'n'" >No Facturar</option>
                    <option v-if="this.TipoServicioFact =='s'"  :value="'s'" >Facturar</option>
                </select>
            </div>
        </div>
        <!--End Costos-->

        <!--Imagenes-->
        <div class="row mt-2">
            <div class="col-md-12 col-lg-12">
                <h4 class="mt-2">Anexos</h4>
                <hr>
            </div>
            <div class="col-md-12 col-lg-12 mt-2">
                <button type="button" @click="OpenReporte2" class="btn btn-01 mr-3" data-toggle="modal" data-target="#ModalReporte2"  data-backdrop="static" data-keyboard="false">Observaciones <span class="badge badge-light">{{contadores.Observaciones}}</span></button>
                <button type="button"  @click="OpenReporte1"  class="btn btn-01 mr-3"  data-toggle="modal" data-target="#ModalReporte1"  data-backdrop="static" data-keyboard="false">Fotografías <span class="badge badge-light">{{contadores.Imagenes}}</span></button>
            </div>
        </div>
        <!--End Imagenes-->

        <div class="f1-buttons mt-4">
            <button :disabled="Disablebtn" @click="Validacion"  type="button" class="btn btn-01">
                <i v-show="Disablebtn" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i><i class="fa fa-plus-circle"></i> {{txtSave}}
            </button>
        </div>

        <!--Modal Cliente general-->
        <Ccliente :TipoModal='2'></Ccliente>

        <Modal :NameModal="'ModalReporte1'" :size="'modal-lg'" :Showbutton="false" :TipoM="2"  :Nombre="'Elegir las imagenes para el reporte'" >
            <template slot="Form">
                <Reporte1></Reporte1>
            </template>
        </Modal>

        <Modal :NameModal="'ModalReporte2'" :size="'modal-lg'" :Showbutton="false" :TipoM="2"  :Nombre="'Elegir observaciones e imagenes para el reporte'" >
            <template slot="Form">
                <Reporte2 ></Reporte2>
            </template>
        </Modal>
    </form>
</div>
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
import Fechas from '@/views/modulos/servicios/componentes/Fechas.vue'
import Personal from '@/views/modulos/servicios/componentes/Personal.vue'
import Comentarios from '@/views/modulos/servicios/componentes/Comentarios.vue'
import Costos from '@/views/modulos/servicios/componentes/Costos.vue'
import Reporte2 from '@/views/modulos/servicios/Reporteequipo.vue'
import Reporte1 from '@/views/modulos/servicios/Imagenes.vue'
import NewCliente from '@/views/modulos/servicios/NewCliente.vue'
import Levantamiento from '@/views/modulos/servicios/componentes/DatosLevantamiento.vue'
import moment from 'moment';

export default {
    name:'Form',
    props:[''],
	components:{
		Clist,
		Cbtnsave,
		Cvalidation,
		Cliente,
		Fechas,
		Personal,
		Comentarios,
		Costos,
		Modal,
		Reporte2,
		Reporte1,
		NewCliente,
		Levantamiento
	},
    data() {
        return {
            Modal: true,//Sirve pra los botones de guardado
            FormName: 'vehiculo',//Sirve para donde va regresar
            PasoActual: 1,
            ListaCategoria: [],
            servicios: {
                IdServicio: 0,
                IdCliente: '',
                IdClienteS: '',
                Cliente: '',
                Direccion: '',
                Telefono: '',
                Correo: '',
                Distancia: 0,
                Velocidad: 0,
                Tipo_Serv: '',
                Trabajadores: [],
                Vehiculos: [],
                Personal: 0,
                Fecha_I: '',
                Fecha_F: '',
                Observaciones: '',
                EstadoS: 'ABIERTA',
                IdVehiculo: '',
                EquiposD: 0,
                MaterialesD: 0,
                ViaticosD: 0,
                ContratistasD: 0,
                ManoObraT: 0,
                BurdenTotal: 0,
                CostoV: 0,
                IdContrato: 0,
                NumContrato: '',
                Paso: 1,
                FechasHoras: [],
                Econtacto: '',
                Contacto: '',
                Para: [],
                tag: '',
                Enviar: false,
                Factura: 'n',
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
            ListaTipoServicio2:[],
           /* Pasos:[
                {
                    Nombre:'Cliente',
                    Paso:1,
                    Estado:true,
                    Selec:true
                },
                {
                    Nombre:'Fechas',
                    Paso:2,
                    Estado:false,
                    Selec:false
                },
                {
                    Nombre:'Asignar',
                    Paso:3,
                    Estado:false,
                    Selec:false
                },
                {
                    Nombre:'Tareas y Materiales',
                    Paso:4,
                    Estado:false,
                    Selec:false
                },
                {
                    Nombre:'Observaciones y Costos',
                    Paso:5,
                    Estado:false,
                    Selec:false
                },
                {
                    Nombre:'Fotos',
                    Paso:6,
                    Estado:false,
                    Selec:false
                }
            ],*/
            Disablebtn:false,
            txtSave:'Guardar',
            contadores: {
                Observaciones: 0,
                Imagenes: 0
            },
            loader: true,
            IdTipo:0,
            TipoServicioFact:'',
            HoraInicio: '',
            HoraActual:moment().format('HH:mm:ss'),
            FechaActual:moment().format('YYYY-MM-DD'),
           
            HoraValida:'',
            FechaValida:'',
            DisableHI:'',
            DisbaleT:'',
			BlockerDates:{
				BlockFecha:true,
				BlockHoraI:false,
				BlockHoraF:false,
                FechaAyer:'',
			}


        }
    },

    methods :
    {
        Validacion(){
            if (this.servicios.Factura=="n" && this.servicios.EstadoS =="CERRADA") {
                    this.$swal({
                    title: "¿Está seguro que desea guardar este servicio como NO facturado?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si",
                    cancelButtonText: "No, mantener",
                    showCloseButton: true,
                    showLoaderOnConfirm: true
                }).then(result => {
                    if (result.value) {

                        this.Guardar();

                    } else {

                    }
                });
            }else{
                this.Guardar();
            }

        },
        async ListaCliente()
        {
            this.bus.$emit('ListCcliente');
        },
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
        SeleccionarCliente(objeto)
        {
            this.oclientesuc=objeto;
            if (this.oclientesuc.Correo!='')
            {
                this.servicios.Para.push({ "text": this.oclientesuc.Correo});
            }

            let distacia=0;
            if (objeto.DistanciaAprox !='')
            {
                distacia=objeto.DistanciaAprox;
            }

            this.servicios.IdCliente=objeto.IdCliente;
            this.servicios.IdClienteS=objeto.IdClienteS;
            this.servicios.Cliente=objeto.Nombre;
            this.servicios.Direccion=objeto.Direccion;
            this.servicios.Distancia=distacia;
            this.servicios.Velocidad=0;
            this.ListaNumContrato();
        },
        Validar()
        {
            let bandera= true;
            if (this.servicios.FechasHoras.length==0)
            {
                this.$toast.info('No hay horas seleccionadas');
                bandera= false;
            }

            for(var i=0;i<this.servicios.FechasHoras.length;i++)
            {
                if (this.servicios.FechasHoras[i].HoraI=='' || this.servicios.FechasHoras[i].HoraF=='')
                {
                    this.$toast.info('Seleccione todas las horas');
                    bandera= false;
                }
            }

            if (this.servicios.Trabajadores.length==0)
            {
                this.$toast.info('Debe seleccionar un técnico');
                bandera= false;
            }

            if (this.servicios.Vehiculos.length==0)
            {
                this.$toast.info('Debe seleccionar un vehículo');
                bandera= false;
            }
            if (this.servicios.Personal==0)
            {
                this.$toast.info('Seleccione un responsable');
                bandera= false;
            }

            return bandera;
        },
        async Guardar()
        {
            let validacion= this.Validar();
            if (validacion==false)
            {
                return false;
            }
            else
            {
                this.servicios.Econtacto=this.oclientesuc.Correo;
                this.servicios.Contacto=this.oclientesuc.ContactoS;
                this.servicios.EquiposD=parseInt(this.servicios.EquiposD);
                this.servicios.MaterialesD=parseInt(this.servicios.MaterialesD);
                this.servicios.ViaticosD=parseInt(this.servicios.ViaticosD);
                this.servicios.ContratistasD=parseInt(this.servicios.ContratistasD);
                this.servicios.ManoObraT=parseInt(this.servicios.ManoObraT);
                this.servicios.BurdenTotal=parseInt(this.servicios.BurdenTotal);
                this.servicios.CostoV=parseInt(this.servicios.CostoV);
                this.servicios.Velocidad=parseInt(this.servicios.Velocidad);
                this.servicios.Distancia=parseInt(this.servicios.Distancia);

                this.Disablebtn=true;
                this.txtSave=' Espere...';

                this.$http.post(
                    'servicio/post',
                    this.servicios
                ).then( (res) => {

                    this.Disablebtn=false;
                    this.txtSave=' Guardar';
                    $('#ModalForm').modal('hide');
                    this.bus.$emit('ListDespacho');
                    this.$toast.success('Información guardada');

                }).catch( err => {
                    this.Disablebtn=false;
                    this.txtSave=' Guardar';
                    this.errorvalidacion=err.response.data.message.errores;
                    this.$toast.info('Complete la Información');
                });
            }
        },

        Limpiar() {
            this.PasoActual=1;

			this.servicios = {
				IdServicio: 0,
				IdCliente: '',
				IdClienteS: '',
				Cliente: '',
				Direccion: '',
				Telefono: '',
				Correo: '',
				Distancia: 0,
				Velocidad: 0,
				Tipo_Serv: '',
				Trabajadores: [],
				Vehiculos: [],
				Personal: 0,
				Fecha_I: '',
				Fecha_F: '',
				Observaciones: '',
				EstadoS: 'ABIERTA',
				IdVehiculo: '',
				EquiposD: 0,
				MaterialesD: 0,
				ViaticosD: 0,
				ContratistasD: 0,
				ManoObraT: 0,
				BurdenTotal: 0,
				CostoV: 0,
				IdContrato: 0,
				NumContrato: '',
				Paso: 1,
				FechasHoras: [],
				Econtacto: '',
				Contacto: '',
				Para: [],
				tag: '',
				Enviar: false,
				Factura: 'n',
				Folio:''
			};

			this.Consultado = {
				ListaTrabajadores:[],
				ListaVehiculos:[],
				ListaNumc:[]
			};


            this.ListaClientes=[];
            this.ListaSucursal=[];
            this.errorvalidacion=[];
            this.oclientesuc={};

			this.BlockerDates = {
				BlockFecha: true,
				BlockHoraI: false,
				BlockHoraF: false,
                FechaAyer:''
			}

            /*this.Pasos=[
                {
                    Nombre:'Cliente' ,
                    Paso:1,
                    Estado:true,
                    Selec:true
                },
                {
                    Nombre:'Fechas',
                    Paso:2,
                    Estado:false,
                    Selec:false
                },
                {
                    Nombre:'Asignar',
                    Paso:3,
                    Estado:false,
                    Selec:false
                },
                {
                    Nombre:'Tareas y Materiales',
                    Paso:4,
                    Estado:false,
                    Selec:false
                },
                {
                    Nombre:'Observaciones y Costos',
                    Paso:5,
                    Estado:false,
                    Selec:false
                },
                {
                    Nombre:'Fotos',
                    Paso:6,
                    Estado:false,
                    Selec:false
                }
            ];*/

           this.bus.$emit('LimpiarCompoenets');
           this.bus.$emit('LimpiarCompoenetsL');


        },
        recovery(){
            this.$http.get(this.urlApi, {
				params:{
					IdServicio: this.servicios.IdServicio
				}

			}).then((res) => {
                
                this.servicios 	= res.data.data.servicio;
                this.HoraInicio = this.servicios.HoraInicio;
                
                var FechaA = moment(new Date());
                FechaA = FechaA.subtract(1,"days");
                FechaA = FechaA.format('YYYY-MM-DD');

                
               this.BlockerDates.FechaAyer=FechaA;
                console.log(this.BlockerDates.FechaAyer);
				
                if (this.BlockerDates.FechaAyer===this.servicios.Fecha_I) {
                //    console.log(this.FechaAyer);
                        this.BlockerDates.BlockFecha = null;
					    this.BlockerDates.BlockHoraI = false;
					    this.BlockerDates.BlockHoraF = false;
                   
                   // PASADO
                }else if (this.FechaActual > this.servicios.Fecha_I) {
                    this.BlockerDates.BlockFecha = true;
					this.BlockerDates.BlockHoraI = true;
					this.BlockerDates.BlockHoraF = true;

                     //PRESENTE
                }
                // else if(this.FechaActual===this.servicios.Fecha_I) {

                //     if (this.HoraActual >= this.servicios.HoraInicio) {

				// 		this.BlockerDates.BlockFecha = true;
				// 		this.BlockerDates.BlockHoraI = true;
                //         this.BlockerDates.BlockHoraF = this.HoraActual >= this.servicios.HoraFin;

                //     }else{
				// 		this.BlockerDates.BlockFecha = false;
				// 		this.BlockerDates.BlockHoraI = false;
				// 		this.BlockerDates.BlockHoraF = false;
                //     }

					//FUTURO
                else if(this.FechaActual < this.servicios.Fecha_I) {
					this.BlockerDates.BlockFecha = false;
					this.BlockerDates.BlockHoraI = false;
					this.BlockerDates.BlockHoraF = false;
                }

				//console.log(res.data.data.servicio.Fecha_I);
				//console.log(res.data.data.servicio.Fecha_F);

                var formatedDate = this.servicios.Fecha_I.replace(/-/g,'\/');
                this.servicios.Fecha_I = new Date(formatedDate);

                var formatedDate2 = this.servicios.Fecha_F.replace(/-/g,'\/');
                this.servicios.Fecha_F = new Date(formatedDate2);

				//console.log(formatedDate);
				//console.log(formatedDate2);

                if(this.servicios.Factura ==''){
                    this.servicios.Factura ='n';
                }

                if(this.servicios.Factura ==null){
                    this.servicios.Factura ='n';
                }

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

                this.ShowComponent=true;
                this.oclientesuc=res.data.data.clientesuc;
                this.TipoServicioFact = this.servicios.Tipo_Serv;
                this.ListaNumContrato();
                this.ListaServ3();




            });
        },
       /* Avanzar()
        {
            let siguiente=0;

            if (this.PasoActual ==4 && this.servicios.IdServicio==0)
            {
                return false;
            }

            for (var i=0;i<this.Pasos.length;i++ ) {
                if (this.Pasos[i].Selec==true)
                {
                    this.Pasos[i].Selec=false;
                    this.Pasos[i].Estado=false;

                    if (this.Pasos[i].Paso<6)
                    {
                        siguiente =this.Pasos[i].Paso+1;
                    }
                    else{
                        siguiente=6
                    }
                }
                if (this.Pasos[i].Paso==siguiente)
                {
                    this.Pasos[i].Selec=true;
                    this.Pasos[i].Estado=true;
                    this.PasoActual=this.Pasos[i].Paso;
                    this.servicios.Paso=this.PasoActual;
                }
            }
        },
        Retroceder()
        {
            let index = this.PasoActual-2;
            let index2 = this.PasoActual-1;
            this.Pasos[index2].Selec=false;
            this.Pasos[index2].Estado=false;
            this.Pasos[index].Selec=true;
            this.Pasos[index].Estado=true;
            this.PasoActual=this.Pasos[index].Paso;
            this.servicios.Paso=this.PasoActual;
        },*/
        OpenReporte2()
        {
            this.bus.$emit('OpenRepor2',this.servicios.IdServicio);
        },
        OpenReporte1()
        {
            this.bus.$emit('OpenRepor1',this.servicios.IdServicio);
        },
        async ContadoresImg()
        {
            await this.$http.get(
                'imageneservicio/totales',
                {
                    params:{IdServicio:this.servicios.IdServicio}
                }
            ).then( (res) => {
                this.contadores =res.data.data;
            });
        },
        habilitaa()
        {
            this.loader = false;
        },

        async ListaServ2()
        {
            await this.$http.get(
                'tiposervicio/get',
                {
                    params:{IdTipoSer:this.IdTipo,Entrada:50,pag:0, RegEstatus:'A'}
                }
            ).then( (res) => {
                this.ListaTipoServicio2 =res.data.data.tiposervicio;
                this.TipoServicioFact = this.ListaTipoServicio2[0].Ingresos;
            });
        },

        async ListaServ3()
        {
            await this.$http.get(
                'tiposervicio/get',
                {
                    params:{IdTipoSer:this.TipoServicioFact,Entrada:50,pag:0, RegEstatus:'A'}
                }
            ).then( (res) => {
                this.ListaTipoServicio2 =res.data.data.tiposervicio;
                this.TipoServicioFact = this.ListaTipoServicio2[0].Ingresos;
            });
        },

        GetServicio()
		{
			var cod = document.getElementById("TipoServicio").value;
			this.IdTipo=cod;
            this.ListaServ2();

		},

        Timer(){
            setTimeout(function() {
				this.habilitaa();
			}.bind(this), 5000);
        }
    },
    created() {
        this.bus.$off('Regresar');
        this.bus.$off('Nuevo');
        this.bus.$off('SeleccionarCliente');
        this.ListaServ();
        

        this.bus.$on('SeleccionarCliente',(oSucursal) => {
           this.SeleccionarCliente(oSucursal);
        });

        this.bus.$on('Regresar',() => {
            this.ListaCliente();
        });

        
    },
    mounted() {
        this.Timer();
		this.bus.$on('Nuevo',(data,Id)=> {
			if (Id === undefined) {
				Id=0;
			}

			this.Limpiar();
			this.ShowComponent=false;

			if(Id>0) {

				this.servicios.IdServicio = Id;
				this.recovery();
				this.ContadoresImg();

			} else {
				this.BlockerDates.BlockFecha = false;
				this.servicios.IdServicio = 0;
				this.ShowComponent=true;
			}

			
		});

    },
    
    destroy() {
        this.Timer();
    }
}
</script>
