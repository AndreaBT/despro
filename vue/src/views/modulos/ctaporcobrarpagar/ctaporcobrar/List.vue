<template>
    <div>
		<CHead :oHead="Head">
			<template slot="component">
				<button  class="btn btn-01 mb-2 mr-1" @click="FacturaLibre(IdFactura)" type="button" v-b-tooltip.hover title="Factura Libre">Factura Libre</button>
				<button v-if="!isVisible" @click="mostrarFiltros('open')" type="button" class="btn btn-01 mb-2 mr-1 filtro">Filtros</button>
				<button v-else @click="mostrarFiltros('close')" type="button" class="btn btn-01 mb-2 mr-1 salir">Filtros</button>
			</template>
		</CHead>


		<Clist :regresar="true" @FiltrarC="Lista" :pShowBtnAdd="false" :ShowHead="false"
			   :Filtro="Filtro" :Nombre="NameList" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal" :Cuentas="Cuentas" :pConfigLoad="ConfigLoad">

            <template slot="botonCuentas">
				<div class="form-inline justify-content-end" >
					<h1 class="naranja mr-2">${{sumaTotal}}</h1>
				</div>
            </template>

			<template slot="header">
				<tr >
					<th >Cliente</th>
					<th >Sucursal</th>
					<th >Fecha Facturado</th>
					<th >Fecha Estimada</th>
					<th v-show="TipoFiltro!=='NO'">Fecha Real</th>
					<th >Prefactura</th>
					<th >Factura</th>
					<th>Cobrado</th>
					<th>Estatus</th>
					<th >Monto</th>
					<th >Acciones</th>
				</tr>
			</template>
			<template slot="body">
				<tr v-for="(lista,index) in ListaFacturas" :key="index" >
					<td>{{lista.NombreCliente.substr(0, 20) }} </td>
					<td>{{lista.Sucursal.substr(0, 20) }} </td>
					<td class="tw-2" >
						<div v-b-tooltip.hover.rightbottom :title="getTooTip(lista)" >
							<i class="fas fa-calendar-day"></i>
							{{lista.FechaFacReal }}
						</div>
					</td>
					<td class="tw-2">
						<div v-b-tooltip.hover.rightbottom :title="getTooTip(lista)" >
							<i  class="fas fa-calendar-day"   >
							</i> {{formato(lista.FechaCobro) }}
						</div>
					</td>

					<td  v-show="TipoFiltro!='NO'" class="tw-2">
						<div v-if="lista.FechaRealCobro!=null" v-b-tooltip.hover.rightbottom :title="getTooTip(lista)" >
							<i  class="fas fa-calendar-day"   >
							</i> {{lista.FechaRealCobro }}
						</div>
						<div v-else>
							<i  class="fas fa-calendar-day"   >
							</i> Sin Cobrar
						</div>
					</td>

					<td ><b>{{lista.FolioFactura }}</b> </td>

					<td><b>{{lista.FolioFactReal }}</b> </td>
					<td><b>{{lista.Estatus }}</b> </td>
					<td v-if="lista.Vigencia=='Vencido'" class="badge badge-pill badge-Vigencia  mt-2">{{lista.Vigencia }} </td>
					<td v-if="lista.Vigencia!='Vencido'" class="badge badge-pill badge-primary mt-2">{{lista.Vigencia }}</td>

					<td >${{Number(lista.Monto).toLocaleString()}}</td>
					<td>

						<button v-if="lista.Estatus!=null && TipoFiltro=='NO' "  v-b-tooltip.hover.leftbottom    title="Cobrar" @click="UploadObservacion(lista.IdCtaCobrar, lista.IdServicio, lista.NoContrato, lista.FechaRealCobro,lista.IdFactura)" data-toggle="modal" data-target="#UploadObservacion"  data-backdrop="static" data-keyboard="false" type="button" class="btn-icon mr-2" ><i class="fas fa-hand-holding-usd"></i></button>
						<button v-if="TipoFiltro=='SI'"  v-b-tooltip.hover.leftbottom    title="Info" @click="UploadObservacionInfo(lista.IdCtaCobrar, lista.IdServicio, lista.NoContrato, lista.FechaRealCobro)" data-toggle="modal" data-target="#ObservacionInfo"  data-backdrop="static" data-keyboard="false" type="button" class="btn-icon mr-2" ><i class="fas fa-info-circle"></i></button>

						<!--NUEVO PDF-->
						<button  v-if="lista.Estatus=='NO'"  v-b-tooltip.hover.lefttop title="Cargar Archivo" @click="UploadInventario(lista.IdCtaCobrar)"  data-toggle="modal" data-target="#UploadFiles"  data-backdrop="static" data-keyboard="false" type="button" class="btn-icon mr-2">
							<i class="fas fa-file-upload" aria-hidden="true"></i>
						</button>
						<button v-show="lista.Archivo!=null && lista.Archivo !=''"  @click="DescargarPdfReal(lista.Archivo)" class="btn-icon mr-2" type="button" v-b-tooltip.hover title="Ver Documento" >
							<i class="fa fa-file-pdf"></i>
						</button>

						<!--PDF DE FACTURA-->
						<button v-if="lista.IdFactura!=null" @click="DescargarPdf(lista.IdFactura)" class="btn-icon mr-2" v-b-tooltip.hover title="Prefactura ">
							<i class="fas fa-file-pdf fa-fw-m"></i>
						</button>
						<button  @click="open_file(lista.ArchivoFactura)" class="btn-icon mr-2" v-b-tooltip.hover title="Factura Real ">
							<i class="fas fa-file-pdf fa-fw-m"></i>
						</button>


					</td>
				</tr>
				<CSinRegistros :pContIF="ListaFacturas.length" :pColspan="[TipoFiltro !== 'NO' ? 11 : 10]" />
            </template>



            <template slot="FiltroCuentas">
				<div id="filtro" class="card" v-if="isVisible==true">
					<div class="card-body">
						<h4>Filtros Avanzados</h4>
						<div class="form-group mr-2">
							<label >Selc. Rango Fecha</label>
							<v-date-picker
								mode="range"
								v-model="rangeDate"
								@input="Lista"
								:input-props="{
									class: 'form-control   calendar',
									placeholder: 'Selecciona un rango de fecha para buscar',
									readonly: true,
								}"
							/>
						</div>
						<div  class="form-group mr-2">
							<label >Estatus</label>
							<select @change="Lista" v-model="TipoFiltro" class="form-control" name="" id="">
								<option value="NO">No cobrado</option>
								<option value="SI">Cobrado</option>
							</select>
						</div>
						<div class="form-group mr-2" >
							<label >Vigencia</label>
							<select @change="Lista" v-model="VigenciaFiltro" class="form-control" name="" id="" >
							<!---  <option value="NA">Pendiente</option>-->
							<option value="0">Todos</option>
								<option value="Vencido">Vencido</option>
								<option value="No vencido">No Vencido</option>
							</select>
						</div>

						<div @click="LimpiarCMB" class="form-group mr-2" style="max-width: 15rem">
							<label >Empresa</label>
							<treeselect
								@input="Lista"
								:options="ListaNombres"
								:clearable="clearable"
								placeholder="Busque una Empresa..."
								v-model="IdCliente"

							/>
						</div>

						<div class="form-group mr-2" style="max-width: 15rem">
							<label >Sucursal</label>
							<treeselect
								@input="Lista"
								:options="ListaEmpSuc"
								placeholder="Busque una Sucursal..."
								v-model="IdClienteS"
							/>
						</div>

						<div class="form-group mr-2" style="max-width: 15rem">
							<label >N. Contrato</label>
							<treeselect
								@input="Lista"
								:options="ListaNContrato"
								placeholder="Busque una No. Contrato..."
								v-model="NoContrato"
							/>
						</div>
						<br>
						<div class="form-group form-group mr-2 text-center">
							<button @click="BtnLimpiar" type="button" class="btn btn-01" >
								<i class="fas fa-broom"></i> Limpiar
							</button>
						</div>

					</div>
				</div>
            </template>


        </Clist>


        <Modal :poBtnSave="oBtnSave" :size="size" :Nombre="NameList" >
            <template slot="Form">
                <Form :poBtnSave="oBtnSave"></Form>
            </template>
        </Modal>

        <Modal :NameModal="'UploadFiles'" :poBtnSave="oBtnSave3" :size="size2" :Nombre="'Cargar Archivo'"  >
            <template slot="Form">
                <UploadFiles :poBtnSave="oBtnSave3"></UploadFiles>
            </template>
        </Modal>

           <Modal :NameModal="'UploadObservacion'" :poBtnSave="oBtnSave2"  :size="size" :Nombre="'Observación'" >
            <template slot="Form">
                <UploadObservacion :poBtnSave="oBtnSave2" ></UploadObservacion>
            </template>
        </Modal>


           <Modal :Showbutton="false"  :NameModal="'ObservacionInfo'" :poBtnSave="oBtnSave4"  :size="size" :Nombre="'Observación'" >
            <template slot="Form">
                <ObservacionInfo :poBtnSave="oBtnSave4" ></ObservacionInfo>
            </template>
        </Modal>


    </div>
</template>


<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';
import Form from '@/views/modulos/ctaporcobrarpagar/ctaporcobrar/Form.vue';
import UploadFiles from '@/views/modulos/ctaporcobrarpagar/ctaporcobrar/UploadFiles.vue';
import ObservacionInfo from '@/views/modulos/ctaporcobrarpagar/ctaporcobrar/ObservacionInfo.vue';
import UploadObservacion from '@/views/modulos/ctaporcobrarpagar/ctaporcobrar/Observacion.vue'
import moment from 'moment';
import CSinRegistros from "../../../../components/CSinRegistros";

export default {
    name :'list',
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        Form,
        UploadFiles,
        UploadObservacion,
        ObservacionInfo,
		CSinRegistros
    },
    data() {
        return {
            FormName:'TipoUnidadForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"modal-md",
            size2 :"modal-sm",
            ShowF:false,
            NameList:"Cuentas Por Cobrar",
            urlApiNombreCliente:"ctaporpagar/NombreC",
            urlApiSucu:"ctaporcobrar/NombreS",
            ListaFacturas:[],
            ListaFacturas2:[],
            ListaHeader:[],
            ListaNombres: [],
            ListaNombres2: [],
            ListaSucursal:[],
            ListaEmpSuc:[],
            ListaNContrato:[],
            rangeDate:{},
            TotalPagina:2,
            sumaTotal:0,
            Pag:0,
            clearable: true,
            IdCliente:null,
            IdClienteS:null,
            NoContrato:null,
            Filtro:{
                Nombre:'',
                Placeholder:'Factura..',
                TotalItem:0,
                Pagina:1,
                Entrada: 10
            },
            ctporcobrar:{
                IdCliente:0,
                IdClienteS:0,
                IdFactura:0,
                IdSucursal:0,
                NombreCliente:'',
                Sucursal:'',
                Estatus:'',
            },
             oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
            },
            oBtnSave3:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
                nombreModal:'UploadFiles'
            },
            oBtnSave2:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                ShowF:false,
                toast:0,
                nombreModal:'UploadObservacion',
            },
            oBtnSave4:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                ShowF:false,
                toast:0,
                nombreModal:'ObservacionInfo',
            },
            data:{
                IdCtaCobrar:0,
                IdServicio:0
            },
            filtros:{
                IdFactura:0,
                IdCliente:0
            },
            TipoFiltro:'NO',
            VigenciaFiltro: '0',
            options:{
                isDisabled:true
            },
            isVisible:false,
            Cuentas:{
                isCuentas:true,
                verFiltros:false
            },
            IdFactura:0,
			Head: {
				ShowHead: true,
				Title: "Cuentas Por Cobrar",
				BtnNewShow: false,
				BtnNewName: "Nuevo",
				isreturn: true,
				isModal: true,
				isEmit: true,
				Url: "",
				ObjReturn: "",
				NameReturn: "Regresar",
				isCuentas: false,
				verFiltroCuentas: false
			},
			ConfigLoad:{
				ShowLoader:true,
				ClassLoad:true,
			}
        }
    },
    methods: {
        fechas(){
            var date = new Date(),
			y = date.getFullYear(),
			m = date.getMonth();
            var firstDay = new Date(y, m, 1);
            var lastDay = new Date(y, m + 1, 0);
            this.rangeDate = {
                start: firstDay,
                end: lastDay
		    };
        },
        ListaSinFecha(){
			this.ConfigLoad.ShowLoader = true;

			this.$http.get('ctaporcobrar/getSinFecha', {
				params:{
					Cliente:this.Filtro.Nombre,
					Entrada:this.Filtro.Entrada,
					pag:this.Filtro.Pagina,
					RegEstatus:'A',
					TipoFiltro:this.TipoFiltro,
					VigenciaFiltro:this.VigenciaFiltro,
					NombreCliente:this.IdCliente,
					Sucursal:this.IdClienteS,
					NoContrato:this.NoContrato,

				}
			}).then( (res) => {
				this.ListaFacturas=res.data.data.ctaporcobrar;
				this.Filtro.Entrada=res.data.data.pagination.PageSize;
				this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
				const sumas = res.data.data.sumaS;
				this.RutaFile= res.data.RutaFile;
				this.sumaTotal=this.numberto(Number(sumas));
				if(this.ListaFacturas.length>0){
					this.updateValidity();
				}
				this.ListaPFD();
				this.get_NombreClientConFecha();

			}).finally(()=>{
				 this.ConfigLoad.ShowLoader = false;
			});
        },
        ListaConFechaCobrado(){
			this.ConfigLoad.ShowLoader = true;

            this.$http.get('ctaporcobrar/get', {
				params: {
					Cliente:this.Filtro.Nombre,
					Entrada:this.Filtro.Entrada,
					pag:this.Filtro.Pagina,
					RegEstatus:'A',
					TipoFiltro:this.TipoFiltro,
					VigenciaFiltro:this.VigenciaFiltro,
					NombreCliente:this.IdCliente,
					Sucursal:this.IdClienteS,
					NoContrato:this.NoContrato,
					FechaI: this.rangeDate.start,
					FechaF: this.rangeDate.end,
				}

			}).then( (res) => {
                    this.ListaFacturas=res.data.data.ctaporcobrar;
                    this.Filtro.Entrada=res.data.data.pagination.PageSize;
                    this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
                    const sumas = res.data.data.suma;
                    this.RutaFile= res.data.RutaFile;
                    this.sumaTotal=this.numberto(Number(sumas));
                    if(this.ListaFacturas.length>0){
                        this.updateValidity();
                    }
                    this.ListaPFD();
                    this.get_NombreClientConFecha();

			}).finally(()=> {
				this.ConfigLoad.ShowLoader = false;
			});
        },
		ListaConFechaNoCobrado(){
			this.ConfigLoad.ShowLoader = true;

            this.$http.get('ctaporcobrarNoCobrado/get', {
				params:{
					Cliente:this.Filtro.Nombre,
					Entrada:this.Filtro.Entrada,
					pag:this.Filtro.Pagina,
					RegEstatus:'A',
					TipoFiltro:this.TipoFiltro,
					VigenciaFiltro:this.VigenciaFiltro,
					NombreCliente:this.IdCliente,
					Sucursal:this.IdClienteS,
					NoContrato:this.NoContrato,
					FechaI: this.rangeDate.start,
					FechaF: this.rangeDate.end,
				}
			}).then( (res) => {
				this.ListaFacturas=res.data.data.ctaporcobrar;
				this.Filtro.Entrada=res.data.data.pagination.PageSize;
				this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
				const sumas = res.data.data.suma;
				this.RutaFile= res.data.RutaFile;
				this.sumaTotal=this.numberto(Number(sumas));
				if(this.ListaFacturas.length>0){
					this.updateValidity();
				}
				this.ListaPFD();
				this.get_NombreClientConFecha();

			}).finally(()=>{
				this.ConfigLoad.ShowLoader = false;
			});
        },
        Lista(){
           if(this.rangeDate===null || this.rangeDate===''){
               return this.ListaSinFecha();
            }else if(this.rangeDate!=null && this.TipoFiltro=='NO'){
                return this.ListaConFechaNoCobrado();
            }else if(this.rangeDate!=null && this.TipoFiltro=='SI'){
                return this.ListaConFechaCobrado();
            }
        },

        get_NombreClientConFecha(){
            this.$http.get("ctaporcobrar/NombreClienteCobra",{
                params:{
                }
            })
            .then(res=> {
                //const nombres = res.data.data.Nombre;
                this.ListaNombres = res.data.data.NombreEmpresa.map(function(obj){
                    return{id:obj.IdCliente, label: obj.Empresa};
                });
                this.get_EmpresaSucConFecha()
                if(this.IdCliente===undefined){
                    this.IdClienteS=undefined;
                    this.NoContrato=undefined;
                }
            });
        },
        get_EmpresaSucConFecha(){
            if(this.IdCliente>0 || this.IdCliente!=undefined){
                this.$http.get("ctaporcobrar/EmpresaSucursal",{
                    params:{
                        NombreCliente:this.IdCliente,
                    }
                })
                .then(res=> {
                    //const nombres = res.data.data.Nombre;
                    this.ListaEmpSuc = res.data.data.EmpresaSucural.map(function(obj){
                        return{id:obj.IdClienteS, label: obj.Sucursal};
                    });
                    this.get_NumContratoConFecha();
                    if(this.IdClienteS===undefined){
                        this.NoContrato=undefined;
                    }
                });
            }else{
                this.NoContrato=undefined;
                this.IdClienteS=undefined;
                // this.IdCliente=undefined;
            }
        },
        get_NumContratoConFecha(){
             this.$http.get("ctaporcobrar/getContratos",{
                params:{
                    Sucursal:this.IdClienteS,
                }
            })
            .then(res=> {
                 this.ListaNContrato = res.data.data.NoContrato.map(function(obj){
                    return{id:obj.NoContrato, label: obj.NoContrato};
                });
            });
        },

        LimpiarCMB(){
            this.IdClienteS=undefined;
            this.NoContrato=undefined;
        },

        Abrir(IdFactura,Id,Folio) {
            this.bus.$emit('Abrir',IdFactura,Id,Folio);
        },

        DescargarPdf(IdFactura)
        {
            if (IdFactura !='' || IdFactura !=null)
            {
                   this.$http.get(
                'reporte/factura',
                {
                responseType: 'blob',
                params :{
                        IdFactura:IdFactura,
                    }
                }
            ).then( (response) => {
                    let pdfContent = response.data;
                     let file = new Blob([pdfContent], { type: 'application/pdf' });
                    let fileUrl = URL.createObjectURL(file);
                    window.open(fileUrl);
            });
            }
        },
        UploadInventario(Id)
        {
            this.bus.$emit('UploadP',Id);
        },
        UploadObservacion(Id,IdServ,NumContra, FechaRealCobro,IdFactura)
        {
            this.bus.$emit('UploadO',Id,IdServ,NumContra,FechaRealCobro,IdFactura);
        },
        UploadObservacionInfo(Id,IdServ,NumContra, FechaRealCobro)
        {
            this.bus.$emit('UploadOInfo',Id,IdServ,NumContra,FechaRealCobro);
        },
        async ListaPFD() {
            await this.$http.get(
                'factura/list',
                {
                    params:{AFacturar:this.AFacturar,Facturado:this.Facturado,FechaFacReal:this.FechaFacReal,TipoFiltro:this.TipoFiltro, RegEstatus:this.RegEstatus}
                }
            ).then((res) => {
                this.RutaFileOrg = res.data.RutaFileOrg;
            });
        },

        open_file(File){
            //window.open(this.RutaPdf+File , '_blank');
            let pdfWindow = window.open(this.RutaFileOrg+File);
            pdfWindow.document.write("<iframe width='100%' height='100%' src='" + this.RutaFileOrg+File +"'></iframe>");
        },
        DescargarPdfReal(File)
        {
            let pdfWindow = window.open(this.RutaFile+File);
            pdfWindow.document.write("<iframe width='100%' height='100%' src='" + this.RutaFile+File +"'></iframe>");
            /*var fileLink = document.createElement('a');
            fileLink.href = this.RutaFile+File;
            fileLink.setAttribute('download', 'Factura.pdf');
            fileLink.target="target_blank";
            document.body.appendChild(fileLink);
            fileLink.click();*/
        },
        formato(fechaCobro){
            let formato = moment(fechaCobro).format('DD-MM-YYYY');
            if(fechaCobro!=null){
                return formato;
            }
        },
        formatoRealCobro(FechaRealCobro){
            let formatoReal = moment(FechaRealCobro).format('DD-MM-YYYY');
            if(FechaRealCobro!=null){
                return formatoReal;
            }
        },
		async updateValidity() {
			await this.$http.post("ctaporcobrar/updatevalidity").then(res => {});
		},
		getTooTip(lista)
        {
            var html="";
            if (lista.Sucursal!='')
            {
                html = "Servicio : " + lista.FechaI2 +" Prefactura : " +lista.FechaReg;
            }
            return html;
        },
        numberto(num){
          //value = value.toFixed(0);
          let fixed = 0;
          if (num === null) { return null; } // terminate early
          if (num === 0) { return '0'; } // terminate early
          fixed = (!fixed || fixed < 0) ? 0 : fixed; // number of decimal places to show
          var b = (num).toPrecision(2).split("e"), // get power
              k = b.length === 1 ? 0 : Math.floor(Math.min(b[1].slice(1), 14) / 3), // floor at decimals, ceiling at trillions
              c = k < 1 ? num.toFixed(0 + fixed) : (num / Math.pow(10, k * 3) ).toFixed(1 + fixed), // divide by power
              d = c < 0 ? c : Math.abs(c), // enforce -0 is 0
              e = d + ['', ' K', ' M', ' B', ' T'][k]; // append power
          return e;
        },
        BtnLimpiar(){
            this.rangeDate='';
            this.IdCliente=undefined;
            this.IdClienteS=undefined;
            this.NoContrato=undefined;
            if(this.rangeDate===''){
                return this.Lista();
            }
        },
		mostrarFiltros(op) {
            this.isVisible = (op === 'open') ? true: false;
			this.Cuentas.verFiltros = this.isVisible;
        },
         FacturaLibre(Id){
             this.$router.push({name:'FacturaLibre',params:{Id:parseInt(Id)}})
        },
    },
    created()
    {
        this.bus.$off('Delete');
        this.bus.$off('List');
        this.bus.$off('Regresar');
        this.ListaSinFecha();
        this.updateValidity();
        this.bus.$on('Delete',(Id)=>
        {
            this.Eliminar(Id);
        });
        this.bus.$on('List',()=>
        {
            this.ListaSinFecha();
        });
        this.bus.$on('Regresar',()=>
        {
           this.$router.push({name:'menuctacobrarpagar'});
        });
    }
}
</script>
