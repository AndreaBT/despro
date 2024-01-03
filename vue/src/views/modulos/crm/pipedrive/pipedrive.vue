<template>
    <div>
        <section class="container-fluid mt-2">
            <Menu :pSitio="NombreSeccion">
                <template slot="BtnInicio"></template>
            </Menu>
            <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-12 mt-2">
                    <div class="form-group form-row">
						<div class="col-md-3 col-lg-2" style="max-width: 10rem" >
							<label class="mr-2">Vendedor</label>
							<select @change="Lista()" v-model="filtros.IdTrabajador" class="form-control form-control-sm"  >
                                <!-- <option value="">Seleccione un Vendedor</option> -->
								<option v-for="(item, index) in Listavendedor" :key="index" :value="item.IdUsuario" >
                                    {{ item.NombreTrabajador }}
                                </option >
							</select>
						</div>

						<div class="col-md-3 col-lg-2" style="max-width: 12rem">
							<label class="mr-2">Proceso</label>
							<select @change="Lista()" v-model="filtros.IdTipoProceso" class="form-control form-control-sm"  >
								<option value="">Seleccione un Proceso</option>
								<option  v-for="(item, index) in ListaAsignados" :key="index" :value="item.IdTipoProceso" >
                                    {{ item.Nombre }}
                                    </option>
                            </select>
						</div>

                        <div class="col-md-3 col-lg-2" style="max-width: 15rem">
							<label class="mr-2">Cliente</label>
							<select @change="Lista()" v-model="filtros.IdCliente" class="form-control form-control-sm" >
                                <option value="">Seleccione un Cliente</option>
								<option v-for="(item, index) in SucursalxProceso" :key="index" :value="item.IdCliente" >
                                    {{ item.Sucursal }}
                                    </option>
                            </select>
						</div>

						<div class="col-md-3 col-lg-2" style="max-width: 15rem">
                            <label class="mr-2">Sucursal</label>
							<select @change="Lista()" v-model="filtros.IdClienteSucursal" class="form-control form-control-sm" >
								<option value="">Seleccione una Sucursal</option>
								<option v-for="(item, index) in SucursalClientexProceso" :key="index" :value="item.IdClienteSucursal" >
                                    {{ item.Nombre }}
                                    </option>
                            </select>
						</div>


						<div class="col-md-4 col-lg-2">
							<label class="mr-2">Oportunidades</label>
							<select @change="Lista()" v-model="filtros.IdOportunidad" class="form-control form-control-sm" >
								<option value="">Seleccione una Oportunidad</option>
								<option  v-for="(item, index) in VxProcesoxOportunidad" :key="index" :value="item.IdOportunidad" >
                                    {{ item.Oportunidad }}
                                </option>
							</select>
						</div>
						<div class="col-md-2 col-lg-1">
							<label>Año</label>
							<select @change="Lista()" v-model="filtros.Anio" class="form-control form-control-sm" >
								<option value="">Seleccionar</option>
								<option v-for="(item, index) in ListaAnios" :key="index" :value="item">
                                    {{ item }}
                                </option>
							</select>
						</div>
                    </div>
                    
                    <div class="card mt-2">
                        <div class="card-body">
                             <h4 style="text-align:center; color:#FF640A" v-if="Listatipoproceso.length>10"><b> El proceso seleccionado cuenta con más de 10 etapas, elimine las sobrantes para continuar.</b></h4>
                            <div class="form-group form-row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <ul   class="nav nav-tabs tabs-pipe" role="tablist">
                                        <li   v-for="(item, index) in Listatipoproceso" :key="index"   class="nav-item"   >
                                            <a v-if="Listatipoproceso.length<=10"  @click="GetServicio(item.IdCrmProceso);" :class="item.Nombre=='Prospectar' ? 'nav-link active':  'nav-link'"  id="tab1-tab" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true"  > 
                                               {{item.Nombre.substr(0,18 )}} &nbsp;
                                               {{item.Total}}
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane tabs-pipeii fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class=" table-responsive">
                                                        <table class="table table-hover table-nota">
                                                            <thead>
                                                                <tr>
                                                                    <th>Fecha</th>
                                                                    <th>Cliente</th>
                                                                    <th>Oportunidad</th>
                                                                    <th>Actividad</th>
                                                                    <th v-if="filtros.IdTipoProceso!=0 && ValidacionMonto!='NO'">Monto</th>
                                                                    <th>Comentario</th>
                                                                    <th>Acciones</th>
                                                                    
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="(lista, key, index) in OportunidadxProceso" :key="index" >
                                                                    <td  style="width: 7px">{{formato(lista.Fecha ) }}</td>
                                                                    <td >{{ $limitCharacters(lista.Nombre,22)}}</td>
                                                                    <td>{{ lista.Oportunidad.substr(0, 22)}}</td>
                                                                    <td>{{ lista.Actividad}}</td>
                                                                     <td v-if="filtros.IdTipoProceso!=0 && ValidacionMonto!='NO'">${{Number(lista.MontoPropuesta).toLocaleString()}}</td>
                                                                    <td>{{  $limitCharacters(lista.Comentarios, 22)}} </td>
                                                                    <td>
                                                                        <button v-if="lista.Comentario!=''" class="btn btn-table pl-01"  v-b-tooltip.hover.lefttop title="Comentario"  @click="OpenComenario( lista.Comentarios)"  data-toggle="modal" data-target="#Comentario"  data-backdrop="static" data-keyboard="false" type="button">
                                                                            <i class="fas fa-eye"></i>
                                                                         </button>
                                                                    </td>
                                                                   
                                                                </tr>
                                                            </tbody>
                                                            <CSinRegistros :pContIF="OportunidadxProceso.length" :pColspan="[filtros.IdTipoProceso !== 0 ? 5 : 4]" />
                                                        </table>
                                                    </div>
                                                    <Pagina
                                                        :Filtro="Filtro"
                                                        :Entrada="Filtro.Entrada"
                                                        @Pagina="Filtrar"
                                                    ></Pagina>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <Modal :Showbutton="false" :NameModal="'Comentario'" :poBtnSave="oBtnSave3" :size="size2" :Nombre="'Comentario'"  >
            <template slot="Form">
                <ComentPipeDrive :poBtnSave="oBtnSave3"></ComentPipeDrive>
            </template>
        </Modal>
    </div>
</template>

<script>

import Modal from "@/components/Cmodal.vue";
import Clist from "@/components/Clist.vue";
import Cbtnaccion from "@/components/Cbtnaccion.vue";
import Form from "../tiposprocesos/form.vue";
import Grafica from "../pipedrive/Grafic.vue";
import Menu from "../indexMenu.vue";
import Pagina from "@/components/Cpagina.vue";
import CSinRegistros from "../../../../components/CSinRegistros";
import moment from 'moment';

import ComentPipeDrive from '@/views/modulos/crm/pipedrive/Comentario.vue';

export default {
	name: "list",
	components: {
		Modal,
		Clist,
		Cbtnaccion,
		Grafica,
        Menu,
        Form,
        Pagina,
        CSinRegistros,
        moment,
        ComentPipeDrive
	},
	data() {
		return {
            NombreSeccion: 'Pipedrive',
			FormName: "TipoUnidadForm", //Por si no es modal y queremos ir a una vista declarada en el router
			EsModal: true, //indica si es modal o no
			size: "none",
            size2 :"modal-md",
			NameList: "Tipos de procesos",
			urlApi: "crmseguimiento/pipedrive",//API DE LA LISTA DEL LADO IZQUIERDO DE LA PANTALLA 
			urlApivendedor: "trabajador/ListTrabRolQuery",
			urlApiVendedorNuevo:"vendedores/get",
			urlApirecovery: "crmprocesovendedor/listasig",
			urlApiPipeDrive: "pipeDrive/get",
			Listatipoproceso: [],
			ListaHeader: [],
			TotalPagina: 2,
			Pag: 0,
			Filtro: {
				Nombre: "",
				Placeholder: "Nombre..",
				TotalItem: 0,
				Pagina: 1
			},
			oBtnSave: {
				//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
				isModal: true,
				disableBtn: false,
				toast: 0
			},
			TipoList: "",

			filtros: {
				IdTrabajador: 0,
				IdTipoProceso: "",
				Anio: "",
				IdOportunidad: "",
				IdSucursal:0,
                IdCrmProceso:"",
                IdClienteSucursal:"",
                IdCliente:""
			},
			Listavendedor: [],
			ListaAsignados: [],
			ListaAnios: [],
            ProcesoxVendedor:[],
            OportunidadxProceso:[],
            SucursalClientexProceso:[],
            SucursalxProceso:[],
            VxProcesoxOportunidad:[],
			rol: ["Vendedor", "Gerente de ventas"],
			ListaOportunidades: [],
			Sucursales:[],
            Entrada: 50,
            FiltroC: {
				Nombre: "",
				Entrada: 10,
				Placeholder: "Buscar..",
				Show: true
			},
            ValidacionMonto:"",
            oBtnSave3:{
                isModal:true,
                disableBtn:false,
                // ShowF:false,
                toast:0,
                nombreModal:'Comentario'
            },
		};
	},
	methods: {
		get_oneprs(Id) {
		if (Id > 0) {
			this.$http.get(this.urlApirecovery, {
				params: { IdTrabajador: Id }
			})
			.then(res => {
				this.ListaAsignados = res.data.data.asignados;

                // this.filtros.IdTipoProceso = res.data.data.asignados[0].IdTipoProceso;
                // this.ListaProcesoxVendedor();
				
			});
		} else {
			this.ListaAsignados = [];
			this.Listatipoproceso = [];
			this.filtros.IdTrabajador = "";
			this.filtros.IdTipoProceso = "";
		}
		},
		async Lista() {
			await this.$http.get(this.urlApi, {
                params: {
                    IdTrabajador: this.filtros.IdTrabajador,
                    IdTipoProceso: this.filtros.IdTipoProceso,
                    Anio: this.filtros.Anio,
                    IdOportunidad: this.filtros.IdOportunidad
                }
            })
            .then(res => {
                this.Listatipoproceso = res.data.seguimiento;
                this.filtros.IdCrmProceso=this.Listatipoproceso[0].IdCrmProceso;
				this.get_oneprs(this.filtros.IdTrabajador);
                this.pipeDrive();
                this.ListaProcesoxVendedor();
            });
		},
		async ListaVendedor() {
			await this.$http.get(this.urlApiVendedorNuevo, {
                params: {
                    
                }
            })
            .then(res => {
                this.Listavendedor = res.data.data.Vendedores;
				
                this.filtros.IdTrabajador = res.data.data.Vendedores[0].IdUsuario;
                this.get_oneprs(this.filtros.IdTrabajador);
            }).finally(() => {
			});
		},
		async Anios() {
			await this.$http.get("funciones/getanios").then(res => {
				this.ListaAnios = res.data.ListaAnios;
				this.filtros.Anio = res.data.AnioActual;
			});
		},
		// ListOportunidad() {
		// 	this.$http
		// 		.get("crmoportunidad/list", {
		// 			params: { Nombre: "", RegEstatus: "A", Entrada: 100, pag: 1 }
		// 		})
		// 		.then(res => {
		// 			this.ListaOportunidades = res.data.data.oportunidades;
		// 		});
		// },
		async pipeDrive() {
			await this.$http.get(this.urlApiPipeDrive, {
                params: {
                    IdTrabajador: this.filtros.IdTrabajador,
                    IdTipoProceso: this.filtros.IdTipoProceso,
                    Anio: this.filtros.Anio,
                    IdOportunidad: this.filtros.IdOportunidad
                }
            })
            .then(res => {
                const arrayPipe = res.data.acumulado;

                let datos = [
                    arrayPipe[0],
                    arrayPipe[1],
                    arrayPipe[2],
                    arrayPipe[3],
                    arrayPipe[4]
                ];

                this.series = [
                    {
                        data: datos
                    }
                ];
            });
		},

		//Regresar al calendario
		go_to_procesos(objcliente) {
			this.$router.push({
				name: "crmprocesos",
				params: { ocliente: objcliente, tipolistp: this.TipoList }
			});
		},

        //Nuevo PIPEDRIVE. 
        async ListaProcesoxVendedor() {
			await this.$http.get("crmprocesos/list", {
                params: {
                    IdTrabajador: this.filtros.IdTrabajador,
                    IdTipoProceso: this.filtros.IdTipoProceso,
                    IdClienteSucursal:this.filtros.IdClienteSucursal,
                    IdOportunidad:this.filtros.IdOportunidad,
                    IdCliente:this.filtros.IdCliente,
                }
            })
            .then(res => {
                this.ProcesoxVendedor = res.data.data.procesoxvendedor;

                if (this.Listatipoproceso.length>10) {
                    this.Validacion();
                }

                this.getOportunidadxProceso();
                this.ProcesoxSucursal();
                this.VendedorxProcesoxOportunidad();
               
            });
		},

        GetServicio(obj)
		{
			let cod = obj
			this.filtros.IdCrmProceso=cod;

            this.getOportunidadxProceso();

		},

       async getOportunidadxProceso(){
            await this.$http.get("crmprocesos/oportunidadxProceso", {
                params: {
                    IdTrabajador: this.filtros.IdTrabajador,
                    IdTipoProceso: this.filtros.IdTipoProceso,
                    IdProceso: this.filtros.IdCrmProceso,
                    IdClienteSucursal:this.filtros.IdClienteSucursal,
                    IdCliente:this.filtros.IdCliente,
                    IdOportunidad:this.filtros.IdOportunidad,
                    Fecha:this.filtros.Anio,
                    Entrada:this.Filtro.Entrada,
                    pag:this.Filtro.Pagina
                    
                }
            })
            .then(res => {
                this.OportunidadxProceso = res.data.data.oportunidadxProceso;
                this.Filtro.Entrada=res.data.data.pagination.PageSize;
				this.Filtro.TotalItem=res.data.data.pagination.TotalItems;

                if (this.OportunidadxProceso.length>0) {
                    if (this.OportunidadxProceso[0].MontoPropuesta==0) {
                    this.ValidacionMonto="NO"; //NO TIENE MONTO
                }else{
                    this.ValidacionMonto="SI"; // SI TIENE MONTO
                }
                }

                
            });
        },

        async ProcesoxSucursal(){
            await this.$http.get("crmprocesos/procesoxSucursal", {
                params: {
                    IdTipoProceso: this.filtros.IdTipoProceso,
                     IdTrabajador: this.filtros.IdTrabajador,
                }
            })
            .then(res => {
                this.SucursalxProceso = res.data.data.SucursalxProceso;
                this.ProcesoxSucursalCliente();
            });
        },

        async ProcesoxSucursalCliente(){
            await this.$http.get("crmprocesos/procesoxSucursalCliente", {
                params: {
                    IdTipoProceso: this.filtros.IdTipoProceso,
                    IdCliente:this.filtros.IdCliente,
                     IdTrabajador: this.filtros.IdTrabajador,
                }
            })
            .then(res => {
                this.SucursalClientexProceso = res.data.data.ClienteSucursalxProceso;
            });
        },

        async VendedorxProcesoxOportunidad(){
            await this.$http.get("crmprocesos/VendedorxProcesoxOportunidad", {
                params: {
                    IdTipoProceso: this.filtros.IdTipoProceso,
                    IdClienteSucursal:this.filtros.IdClienteSucursal,
                    IdTrabajador: this.filtros.IdTrabajador,
                }
            })
            .then(res => {
                this.VxProcesoxOportunidad = res.data.data.VendedorxProcesoxOportunidad;
                
            });
        },

        Filtrar() {
			if (this.FiltroC.Entrada != this.Filtro.Entrada) {
				this.Filtro.Pagina = 1;
			}

			this.Filtro.Nombre = this.FiltroC.Nombre;
			this.Filtro.Entrada = this.FiltroC.Entrada;

			if(this.FiltroC.Nombre != '') {
				clearTimeout(this.TimeOut);

				this.TimeOut = setTimeout(() => {
					this.$emit("FiltrarC");
				}, 1000);

			} else {
				this.$emit("FiltrarC");
			}

            this.getOportunidadxProceso();


		},

        Validacion(){
                this.$swal({
                title: "El límite de Etapas por Proceso es de 10",
                type: "warning",
                showCancelButton: false,
                confirmButtonText: "Entendido",
                showCloseButton: true,
                showLoaderOnConfirm: true
            }).then(result => {
            });
        },

        formato(Fecha){
            let formato = moment(Fecha).format('DD-MM-YYYY');
            if(Fecha!=null){
                return formato;
            }
        },

        OpenComenario(Comentario){
            this.bus.$emit('AbrirCom',Comentario)
        }
        

	},
	created() {
		this.Anios();
		// this.ListOportunidad();
        this.ListaVendedor();
        // this.get_oneprs();
		//Obligatorio pasar el tipolist
		if (this.tipolistp != undefined) {
			sessionStorage.setItem("IdSaved", JSON.stringify(this.tipolistp));
		}

		this.TipoList = JSON.parse(sessionStorage.getItem("IdSaved"));

		this.bus.$off("Delete");
		this.bus.$off("List");
		this.bus.$off("Regresar");
		// this.ListaVendedor();

		this.bus.$on("Delete", Id => {
		});
		this.bus.$on("List", () => {
			this.Lista();
		});
		this.bus.$on("Regresar", () => {
			this.$router.push({ name: "submenucrm" });
		});
	}
};
</script>