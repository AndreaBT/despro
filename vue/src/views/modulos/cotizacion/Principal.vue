<template>
    <div>
        <b-overlay :show="this.isOverlay" spinner-variant="primary" >
			<CHead :oHead="oHead"></CHead>
				<div class="row justify-content-start mt-3">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<div class="card card-01">
							<div  v-if="ShowComponents" class="row justify-content-end mt-2">
								<div class="col-12 col-sm-12 col-md-12 col-lg-9">
									<div class="row">
										<div class="col-12 col-sm-12 col-md-12 col-lg-12">
											<div class="form-group form-row">
												<div class="col-12 col-sm-12 col-md-6 col-lg-5">
													<label>Cliente</label>
													<input placeholder="Cliente" type="text" class="form-control" v-model="Ocliente.Nombre"  readonly="true">
													<label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Cliente" :Mensaje="errorvalidacion.Cliente[0]"></Cvalidation></label>
												</div>

												<div class="col-12 col-sm-12 col-md-2 col-lg-2">
													<button type="button" :disabled="BtnBuscar" class="btn btn-01 search mx-sm-3 mt-6" @click="ListaCliente" data-toggle="modal" data-target="#ModalForm3"  data-backdrop="static">Buscar</button>
												</div>
											</div>
											<div class="form-group form-row">
												<div class="col-12 col-sm-12 col-md-6 col-lg-4">
													<label>Teléfono</label>
													<input placeholder="Teléfono"  v-model="Ocliente.Telefono"  type="text" class="form-control" readonly="true">
												</div>
												<div class="col-12 col-sm-12 col-md-6 col-lg-4">
													<label>Correo</label>
													<input placeholder="Correo" v-model="Ocliente.Correo"  type="text" class="form-control" readonly="true">
												</div>
												<div class="col-12 col-sm-12 col-md-8 col-lg-4">
													<label>Dirección</label>
													<input placeholder="Dirección" v-model="Ocliente.Direccion"  type="text" class="form-control" readonly="true">
												</div>

											</div>
										</div>
									</div>

									<div class="row mt-2">
										<div class="col-12 col-sm-12 col-md-12 col-lg-12">
											<nav>
												<div class="nav nav-tabs nav-tabs-table" id="nav-tab" role="tablist">
													<a class="nav-item nav-link active" id="dato-tab" data-toggle="tab" href="#nav-dato" role="tab" aria-controls="nav-dato" aria-selected="true">Datos</a>
													<a class="nav-item nav-link" id="obra-tab" data-toggle="tab" href="#nav-obra" role="tab" aria-controls="nav-obra" aria-selected="false">Mano de Obra</a>
													<a class="nav-item nav-link" id="material-tab" data-toggle="tab" href="#nav-material" role="tab" aria-controls="nav-material" aria-selected="false">Materiales</a>
													<a class="nav-item nav-link" id="miscelaneos-tab" data-toggle="tab" href="#nav-miscelaneos" role="tab" aria-controls="nav-miscelaneos" aria-selected="false">Misceláneos</a>
												</div>
											</nav>
											<div class="tab-content tab-content-table">
												<div class="tab-pane fade show active" id="nav-dato" role="tabpanel" aria-labelledby="dato-tab">
													<CDato :pcotizacion_servicio="cotizacion_servicio" :perrorvalidacion="errorvalidacion"></CDato>
												</div>

												<div class="tab-pane fade" id="nav-obra" role="tabpanel" aria-labelledby="obra-tab">
													<CManoO :pcotizacion_servicio="cotizacion_servicio"></CManoO>
												</div>

												<div class="tab-pane fade" id="nav-material" role="tabpanel" aria-labelledby="material-tab">
													<Material :pcotizacion_servicio="cotizacion_servicio" :Listamarkup="Listamarkup"></Material>
												</div>

												<div class="tab-pane fade" id="nav-miscelaneos" role="tabpanel" aria-labelledby="miscelaneos-tab">
													<CMiscelaneo :pcotizacion_servicio="cotizacion_servicio"></CMiscelaneo>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-12 col-sm-12 col-md-5 col-lg-3 mb-01">
									<div class="row">
										<div class="col-12 col-sm-12 col-md-12 col-lg-12">
											<CResultado :pcotizacion_servicio="cotizacion_servicio" :perrorvalidacion="errorvalidacion"></CResultado>
										</div>
									</div>
								</div>

								<div class="col-12 col-sm-12 col-md-12 col-lg-12 text-right">
									<Cbtnsave :IsModal="false" :RegresarA="oHead.Url"></Cbtnsave>
									<!--<hr>
									<button type="button" class="btn btn-04 ban" >Cancelar</button>
									&nbsp;&nbsp;
									<button  type="button" @click="Guardar" class="btn btn-01" ><i class="fa fa-plus-circle"></i> Guardar</button>
									-->
								</div>
							</div>
						</div>
					</div>
				</div>
        </b-overlay>

        <Modal  :size="size">
            <template slot="Form" >
                <Cmateriales :pTipo="2"></Cmateriales>
            </template>
        </Modal>

        <Ccliente :TipoModal='1'></Ccliente>

    </div>
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Clist from '@/components/Clist.vue';
import CDato from '@/views/modulos/cotizacion/Dato.vue'
import CManoO from '@/views/modulos/cotizacion/ManoObra.vue'
import Material from "./Material";
import CMiscelaneo from '@/views/modulos/cotizacion/Miscelaneo.vue'
import CResultado from '@/views/modulos/cotizacion/Resultado.vue'
import Modal from '@/components/Cmodal.vue';
import Cmateriales from '@/views/catalogos/cotizacion/material/List.vue'
import Ccliente from '@/components/Ccliente.vue'

export default {
    name:'FormCotizacion',
    props:['NameList','Id','objsucursal'],
	components:{
		Clist,CDato,CManoO,Material,CMiscelaneo,CResultado,Modal,Cmateriales,Ccliente
	},
    data() {
        return {
            BtnBuscar:false,
            Modal:true,//Sirve pra los botones de guardado
            FormName:'Cotizacion',//Sirve para donde va regresar
            size :"modal-xl",
            ListaClientes:[],
            ListaSucursal:[],
            Mostrar:true,
            Regresar:false,
            TituloLista:'Lista de Clientes',
            Ocliente:{IdClienteS:0,IdCliente:0,Nombre:'',Direccion:'',Telefono:'',Correo:''},
            cotizacion_servicio:{
                IdServicio:0,
                IdCotizacionServicio:0,
                IdCliente:0,
                GrossProfit:0,
                utilidad:0,
                gastoOperaciones:0,
                factorHoraExtra:0,
                totalMateriales:0,
                totalManoDeObra:0,
                totalMiscelaneos:0,
                costoKm:0,
                totalGlobal:0,
                distancia:0,
                velocidad:0,
                TotalCostoKm:0,
                Garantia:0,
                CostoBurden:0,
                CostoDesplazamiento:0,
                CostoManoObraD:0,
                ValorVenta:0,
                ListaManoObra:[],
                ListaMaterialCot:[],
                ListaMiscelaneCot:[]
            },
            ShowComponents:false,
            errorvalidacion:[],
            oHead:{//Encabezado
                Title:'Cotizacion de servicio',
                BtnNewShow:false,
                BtnNewName:'Nuevo',
                isreturn:true,
                isModal:false,
                isEmit:false,
                Url:'cotizacion_list',
                ObjReturn:'',
             },

            Listamarkup:[],
            isOverlay: true
        }
    },

    methods :
    {
		async get_ListaMarkup()
		{
			await this.$http.get(
				'markup/get',
				{
					params:{
						Nombre:'',
						Entrada:500,
						pag:0,
						RegEstatus:'A'
					}
				}
			).then( (res) => {
				this.Listamarkup =res.data.data.row;
			});
		},

		Get_Recovery(){
			//this.isOverlay = true;
			this.$http.get(
				'cotizacion_servicio/recovery',
				{
					params:{
						IdCotizacionServicio: this.cotizacion_servicio.IdCotizacionServicio}
				}
			).then((res) => {
				this.cotizacion_servicio	= res.data.data.cotizacion_servicio;
				this.Ocliente				= res.data.data.Ocliente;
				this.ShowComponents			= true;

			}).catch((e) => {
				// this.isOverlay = false;
			}).finally(()=>{
				// this.isOverlay = false;
			});
		},



        async Guardar()
        {
            // if(this.cotizacion_servicio.ListaMaterialCot.length<=0)
            // {
            //     this.$toast.error('Agregue materiales');
            //     return false;
            // }

            this.bus.$emit('BloquearBtn',0);
            await this.$http.post(
                'cotizacion_servicio/post',
                this.cotizacion_servicio
                ,
            ).then( (res) => {
                this.bus.$emit('BloquearBtn',1);
                this.$router.push({name:'cotizacion_list'});

            }).catch( err => {

                    if(err.response.data.type==2){
                        this.$toast.error(err.response.data.message);
                    }else{
                        this.errorvalidacion=err.response.data.message.errores;
                    }

                this.bus.$emit('BloquearBtn',2);
            });
        },
        ListaCliente(){
            this.bus.$emit('ListCcliente');
        },
        add_datos(oSucursal){
            this.Ocliente=oSucursal;
            this.cotizacion_servicio.IdCliente=this.Ocliente.IdClienteS;

            if(oSucursal.DistanciaAprox > 0){
                this.cotizacion_servicio.distancia = oSucursal.DistanciaAprox;
            }
        },
        add_datos2(oSucursal){
            this.oHead.Url = 'menulevantamiento';
            this.BtnBuscar = true;
            this.Ocliente=oSucursal;
            this.cotizacion_servicio.IdCliente=this.Ocliente.IdClienteS;
            this.cotizacion_servicio.IdServicio=this.Ocliente.IdServicio;
        },


    },
    created() {
        this.get_ListaMarkup();

        this.bus.$off('Save');
        this.bus.$off('Nuevo');
        this.bus.$off('SeleccionarCliente');

        this.bus.$on('Save',()=> {
           this.Guardar();
        });

        let id=0;
        if(this.Id!=undefined){
            sessionStorage.setItem('IdSaved',JSON.stringify(this.Id));
        }
        id=JSON.parse( sessionStorage.getItem('IdSaved'));

        if(id>0){
            this.cotizacion_servicio.IdCotizacionServicio=id;
            this.Get_Recovery();
            this.isOverlay = false;

        }else{
            this.ShowComponents=true;
            this.isOverlay = false;
        }

        this.bus.$on('SeleccionarCliente',(oSucursal)=>
        {
           this.add_datos(oSucursal);
        });

        if(this.objsucursal!=undefined){
            this.add_datos2(this.objsucursal);
        }
    },
    computed: {
    },
}
</script>
