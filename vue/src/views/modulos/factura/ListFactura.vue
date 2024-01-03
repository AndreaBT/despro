<template>
    <div>

    <Clist :regresar="true" :pShowBtnAdd="false" :Nombre="Head.Title"
		   @FiltrarC="ListaPrincipal" :Filtro="Filtro"  :isModal="EsModal" :oHead="Head"
		   :pConfigLoad="ConfigLoad">

        <template slot="Filtros">
             <div  class="ml-2">
                <select @change="ListaPrincipal" v-model="TipoFactura" class="form-control" name="">
                <option value="1">Factura</option>
                <option value="2">Factura Libre</option>
                </select>
            </div>
            <div v-if="Tipo2==2" class="ml-2">
                <select @change="ListaPrincipal" v-model="TipoFiltro" class="form-control" name="">
                <option value="1">No Facturado</option>
                <option value="2">Facturado</option>
                </select>
            </div>
        </template>
            <template slot="header">
                   <tr >
                        <th>Cliente</th>
                        <th>Sucursal</th>
                        <th>Fecha Servicio</th>
                        <th>Fecha Prefactura</th>
                        <th v-if="Tipo2==2 || Tipo2!=3" v-show="TipoFiltro!='1'">Fecha Facturado</th>
                        <th v-if="Tipo2==3">Fecha de Anulación</th>
                        <th style="text-align:center;"  >No. Prefactura</th>
                        <th   style="text-align:center;">Folio</th>

                        <th style="text-align:center;"  v-if="TipoFiltro!=1">Folio Factura Real</th>
                        <th>Monto</th>
                        <th class="text-center tw-2">Acciones</th>
                    </tr>

            </template>
             <template  slot="body">
                   <tr v-for="(lista,index) in ListaFacturas" :key="index" >
                       <td>{{lista.NombreCliente.substr(0, 22) }} </td>
                       <td>{{lista.Sucursal.substr(0, 25) }} </td>
                       <td class="tw-2"><i class="fas fa-calendar-day"></i> {{lista.FechaI2 }} </td>
                       <td class="tw-2"><i class="fas fa-calendar-day"></i> {{lista.FechaReg }} </td>
                       <!--AQUÍ-->
                       <td class="tw-2" v-if="Tipo2==2 && TipoFiltro!='1'"><i class="fas fa-calendar-day"></i> {{lista.FechaFacReal }} </td>
                       <td class="tw-2" v-if="Tipo2==3 "><i class="fas fa-calendar-day"></i> {{lista.FechaAnulado }} </td>
                       <td style="text-align:center;" ><b >{{lista.FolioFactura }}</b> </td>
                       <td style="text-align:center;"><b>{{lista.FolioServ }}</b> </td>
                       <td  style="text-align:center;" v-if="TipoFiltro!=1"><b>{{lista.FolioFactReal }}</b> </td>
                       <td v-if="TipoFiltro == '1'">${{Number( lista.Total).toLocaleString()   }} </td>
                       <td v-else-if="TipoFiltro == '2'">${{Number(lista.Monto).toLocaleString() }} </td>


                        <td class="text-center tw-2">
                            <template v-if="Tipo2==1">
                                <div>
                                    <button type="button" @click="Autorizar(lista.IdFactura)" class="btn btn-table pl-01 mr-1" v-b-tooltip.hover title="Autorizar Factura">
                                        <i class="fas fa-file-check fa-fw-m"></i>
                                    </button>
                                    <button class="btn btn-table pl-01 mr-1"  @click="OpenCancel(index)"  data-toggle="modal" data-target="#ModalForm4"  data-backdrop="static" v-b-tooltip.hover title="Cancelar Factura" >
                                        <i class="fas fa-file-times fa-fw-m"></i>
                                    </button>
                                    <button @click="DescargarPdf(lista.IdFactura)" class="btn btn-table pl-01" v-b-tooltip.hover title="PDF Prefactura">
                                        <i class="fas fa-file-pdf fa-fw-m"></i>
                                    </button>
                                </div>
                            </template>
                            <template v-if="Tipo2==2 || Tipo2==3">
                              <div>
                               <button v-b-tooltip.hover title="Editar" class="btn btn-table pl-01 mr-1" v-if="Tipo2==2"  @click="OpenFactura(index,lista.IdFactura,lista.DiasCredito,lista.Observacion,lista.FechaFacReal,lista.IdServicio)" type="button" data-toggle="modal" data-target="#ModalForm"  data-backdrop="static"  >
                                    <i class="fas fa-pencil-alt fa-fw-m"></i>
                                </button>
                                <button @click="DescargarPdf(lista.IdFactura)" class="btn btn-table pl-01 mr-1" type="button" v-b-tooltip.hover title="Prefactura"><i class="fas fa-file-pdf fa-fw-m"></i></button>
                                <button v-show="lista.ArchivoFactura!=null && lista.ArchivoFactura !=''" @click="DescargarPdfReal(lista.ArchivoFactura)" class="btn btn-table pl-01 mr-1" type="button" v-b-tooltip.hover title="Factura" v-if="Tipo2==2" ><i class="fas fa-file-pdf fa-fw-m"></i></button>
                                <!---COMENTARIO DE FACTURAS ANULADAS-->
                                <button v-if=" Tipo2==3"  v-b-tooltip.hover.lefttop title="Comentario"  class="btn btn-table pl-01 mr-1" @click="OpenComenario(lista.IdFactura, lista.ComentarioAnulada)"  data-toggle="modal" data-target="#Comentario"  data-backdrop="static" data-keyboard="false" type="button" >
                                    <i class="far fa-comments-alt" aria-hidden="true"></i>
                                </button>
                              </div>
                          </template>
                         <!-- <template  v-if=" Tipo2==3">
                              <div>
                                <button  v-b-tooltip.hover.lefttop title="Comentario"  class="btn btn-table pl-01 mr-1" @click="OpenComenario()"  data-toggle="modal" data-target="#UploadFiles"  data-backdrop="static" data-keyboard="false" type="button" >
                                    <i class="fas fa-coments" aria-hidden="true"></i>
                                </button>
                              </div>
                          </template>-->
                        </td>
                   </tr>
				 <CSinRegistros :pContIF="ListaFacturas.length" :pColspan="rowSinregistro" ></CSinRegistros>

            </template>
        </Clist>
    <Modal :NameModal="'ModalForm4'" :Showbutton="false"   :size="'None'" :Nombre="'Cancelación de Factura'" >
      <template slot="Form">
         <fieldset class="sin">
              <div class="row mt-2">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                  <h4 class="titulo-04">Datos de la Factura</h4>
                </div>
              </div>

              <div class="form-group form-row mt-2">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                  <label>Folio de la Factura</label>
                  <input type="text" readonly v-model="factura.Folio" class="form-control" placeholder="Folio">
                </div>
                   <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                  <label>Motivo de Cancelación</label>
                  <textarea class="form-control" v-model="factura.Motivo" name="" id="" cols="6" rows="3"></textarea>
                  <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Motivo" :Mensaje="errorvalidacion.Motivo[0]"></Cvalidation></label>
                </div>
              </div>
        </fieldset>
        <div >
            <div class="modal-footer modal-footer-form ">
                <button :disabled="loading" @click="closeCancel" type="button" class="btn btn-04 ban mr-2">Cancelar</button>
                <button :disabled="loading" @click="Cancelar" class="btn btn-01 save">  <i v-show="loading" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i> {{txtSave}}</button>
            </div>
        </div>


        <!------Comentando nada más para subir---->

      </template>
    </Modal>

         <Modal :NameModal="'ModalForm'"   :poBtnSave="oBtnSave" :size="'modal-lg'" :Nombre="'Editar Información'" >
      <template slot="Form">
         <FormChange @Listar="Lista" :poBtnSave="oBtnSave" :factura="factura">
         </FormChange>

      </template>
    </Modal>

    <Modal :NameModal="'Comentario'" :poBtnSave="oBtnSave3" :size="size2" :Nombre="'Observación'"  >
        <template slot="Form">
            <ComentFacAnulada :poBtnSave="oBtnSave3"></ComentFacAnulada>
        </template>
    </Modal>

</div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';

import Form from '@/views/modulos/factura/Form.vue'
import FormChange from '@/views/modulos/factura/FormChange.vue'
import ComentFacAnulada from '@/views/modulos/factura/ComentFacAnulada.vue';
import CSinRegistros from "../../../components/CSinRegistros";

export default {
    props:['Tipo'],
    name :'list',
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        Form,
        FormChange,
        ComentFacAnulada,
		CSinRegistros

    },
    data() {
        return {
            FormName:'formfact',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"None",
            size2 :"modal-md",
            urlApi:"caja/get",
            ListaFacturas:[],
            ListFechas:[],
            ListaHeader:[],
            NameList:'',
              Head:{
                Title:'Autorizar Prefactura',
                BtnNewShow:false,
                BtnNewName:'Nuevo',
                isreturn:true,
                isModal:false,
                isEmit:false,
                Url:'submenufact',
                ObjReturn:'',
             },
            AFacturar:'',
            FechaFacReal:'',
            Facturado:'',
            IdServicio:0,
            TipoFiltro:1,
            RegEstatus:'A',
            Anulado:'',
            factura: {
                IdFactura:0,
                IdServicio:0,
                Motivo:'',
                Folio:'',
                Monto:'',
                RegEstatus:'A',
                FolioFactReal:'',
                FechaFacReal:'',
                File:'',
                FilePrevious:'',
                ComentarioAnulada:'',
                DiasCredito:'',
                FolioFactura:'',
                Observacion:'',
                FechaCobro:'',
                FechaAnulado:'',
                Facturado:''
            },

            txtSave:'Guardar',
            loading:false,
            Disablebtn:false,
             Filtro:{
                Nombre:'',
                Placeholder:'Cliente/Sucursal ...',
                 TotalItem:0,
                Pagina:1,
            },
            Tipo2:'',
            errorvalidacion:[],
             oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
                nombreModal:'ModalForm'
            },
             oBtnSave3:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
                nombreModal:'Comentario'
            },
            RutaFileOrg: '',
             TipoFactura:'1',
			ConfigLoad:{
				ShowLoader:true,
				ClassLoad:true,
			},
			rowSinregistro: 0,
        }
    },
    methods: {

        OpenCancel(index)
        {
            this.factura.IdFactura=this.ListaFacturas[index].IdFactura;
            this.factura.IdServicio=this.ListaFacturas[index].IdServicio;
            this.factura.Folio=this.ListaFacturas[index].FolioFactura;
            this.factura.Monto=this.ListaFacturas[index].Monto;
            this.errorvalidacion=[];
        },
        closeCancel()
        {
            $('#ModalForm4').modal('hide');
        },
        Cancelar()
        {
            this.factura.Motivo = this.factura.Motivo.trim();
            this.loading=true;
                this.$http.post(
                        'factura/Cancelar',
                        this.factura
                    ).then( (res) => {
                         $('#ModalForm4').modal('hide');
                        this.loading=false;
                        this.$toast.success('Información Guardada');
                        this.Limpiar();
                        this.Lista();
                    }).catch( err => {
                        this.loading=false;
                        this.errorvalidacion=err.response.data.message.errores;
                this.$toast.error('No se pudo actulizar la información');

                });

        },
        Autorizar(Id)
        {
            this.factura.IdFactura=Id;

                    this.$swal({
                title: 'Esta seguro que desea autorizar esta prefactura?',
                text: '',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No, mantener',
                showCloseButton: true,
                showLoaderOnConfirm: true
                }).then((result) => {
                if(result.value) {

                      this.$http.post(
                        'factura/Autorizar',
                        this.factura
                    ).then( (res) => {
                        this.$toast.success('Información Guardada');
                        this.Lista();
                        this.Limpiar();
                    }).catch( err => {
                    this.$toast.error('No se pudo actulizar la información');

                    });

                    }
                });
        },
        async Lista() {
			this.ConfigLoad.ShowLoader = true;

            await this.$http.get(
                'factura/list',
                {
                    params:{
						AFacturar:this.AFacturar,
						Facturado:this.Facturado,
						FechaFacReal:this.FechaFacReal,
						TipoFiltro:this.TipoFiltro,
						Nombre:this.Filtro.Nombre,
						Entrada:this.Filtro.Entrada,
						pag:this.Filtro.Pagina,
						RegEstatus:this.RegEstatus,
                        Anulado:this.Anulado,
					}
                }
            ).then((res) => {
                this.ListaFacturas		= res.data.data.prefacturas;
                this.Filtro.Entrada		= res.data.data.pagination.PageSize;
                this.Filtro.TotalItem	= res.data.data.pagination.TotalItems;
				this.Filtro.Pagina 		= res.data.data.pagination.CurrentPage;
                this.RutaFileOrg 		= res.data.RutaFileOrg;

            }).finally(()=>{
				this.ConfigLoad.ShowLoader = false;
			});

        },

        ListaFactLibreAutorizada() {
			this.ConfigLoad.ShowLoader = true;
            this.$http.get(
                'factura/facturaLibreAutorize/get',
                {
                    params:{
						AFacturar:this.AFacturar,
						Facturado:this.Facturado,
						FechaFacReal:this.FechaFacReal,
						TipoFiltro:this.TipoFiltro,
						Nombre:this.Filtro.Nombre,
						Entrada:this.Filtro.Entrada,
						pag:this.Filtro.Pagina,
						RegEstatus:this.RegEstatus,
						TipoFactura:this.TipoFactura}
                }
            ).then((res) => {
                this.ListaFacturas		= res.data.data.prefacturaslibreAutorizada;
                this.Filtro.Entrada		= res.data.data.pagination.PageSize;
                this.Filtro.TotalItem	= res.data.data.pagination.TotalItems;
				this.Filtro.Pagina 		= res.data.data.pagination.CurrentPage;
                this.RutaFileOrg 		= res.data.RutaFileOrg;

            }).finally(()=>{
				this.ConfigLoad.ShowLoader = false;
			});
        },

        ListaPrincipal(){
            if(this.TipoFactura==='1'){
                this.Lista();
            }else if(this.TipoFactura==='2'){
                this.ListaFactLibreAutorizada();
            }
        },
        Limpiar()
        {
             this.factura.IdFactura=0;
             this.factura.IdServicio=0;
             this.factura.Motivo='';
             this.factura.Folio='';
             this.factura.Folio='';
             this.factura.Monto='';
              this.factura.RegEstatus='A';
              this.factura.FolioFactReal='';
              this.factura.FechaFacReal='';
              this.factura. File='';
              this.factura.FilePrevious='';
              this.factura.DiasCredito='';
              this.factura.Observacion='';
              this.factura.FechaCobro='';
              this.factura.FechaAnulado='';
              //this.factura.DiasCredito=0;
        },
        OpenFactura(index, IdFactura,DiasCredito,Observacion,FechaFacReal,IdServicio)
        {
            this.Limpiar();

            this.factura.IdFactura=this.ListaFacturas[index].IdFactura;
            this.factura.IdServicio=this.ListaFacturas[index].IdServicio;
            this.factura.RegEstatus=this.ListaFacturas[index].RegEstatus;
            this.factura.Monto=this.ListaFacturas[index].Monto;
            this.factura.FolioFactReal=this.ListaFacturas[index].FolioFactReal;
               let formatedDate = this.ListaFacturas[index].FechaFacReal.replace(/-/g,'\/')
                this.factura.FechaFacReal = new Date(formatedDate);
            this.factura.FilePrevious=this.ListaFacturas[index].ArchivoFactura;

            this.factura.FolioFactura=this.ListaFacturas[index].FolioFactura;
            this.factura.DiasCredito=this.ListaFacturas[index].DiasCredito;
            this.factura.Observacion=this.ListaFacturas[index].Observacion;
            this.factura.FechaCobro=this.ListaFacturas[index].FechaCobro;
            this.factura.FechaAnulado=this.ListaFacturas[index].FechaAnulado;
            this.bus.$emit('EditarCance',this.oBtnSave,IdFactura,DiasCredito,Observacion,FechaFacReal,IdServicio);
            this.bus.$emit('Limpiar');

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

                   /*
                  var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                     var fileLink = document.createElement('a');
                     fileLink.href = fileURL;
                     fileLink.setAttribute('download', 'Factura.pdf');
                     document.body.appendChild(fileLink);
                     fileLink.click();
                     */

            });
            }
        },

        DescargarPdfReal(nombre)
        {
            /*var fileLink = document.createElement('a');
            fileLink.href = this.RutaFileOrg+nombre;//"http://localhost/desprosoft/assets/files/factura/27/41/" +nombre;
            fileLink.setAttribute('download', 'Factura.pdf');
            fileLink.target="target_blank";
            document.body.appendChild(fileLink);
            fileLink.click();*/

            let pdfWindow = window.open(this.RutaFileOrg+nombre);
            pdfWindow.document.write("<iframe width='100%' height='100%' src='" + this.RutaFileOrg+nombre +"'></iframe>");
        },

        OpenComenario(Id,Comentario){
            this.bus.$emit('AbrirCom',Id,Comentario)
        }

    },
    created()
    {
         if (this.Tipo!=undefined)
        {
            sessionStorage.setItem('IdSaved',JSON.stringify(this.Tipo));
        }
        this.Tipo2 = sessionStorage.getItem('IdSaved');



        this.bus.$off('Regresar');
            if (this.Tipo2==1)
            {
                // this.RegEstatus='A';
                this.AFacturar='NO';
                //this.EstadoFactura='NO';
                // this.FechaFacReal='0000-00-00';
                this.Facturado='SI';
				this.rowSinregistro = 7;

            }
             if (this.Tipo2==2)
            {
                // this.RegEstatus='A';
                 this.AFacturar='SI';
                this.FechaFacReal='0000-00-00';
                this.Facturado='SI';
                this.RegEstatus='A';
                this.Head.Title='Prefacturas Autorizadas';
				this.rowSinregistro = 9;
            }
            if (this.Tipo2==3)
            {
                this.Head.Title='Facturas Anuladas';
                this.RegEstatus='A';
                // this.AFacturar='SI';
                // this.FechaFacReal='0000-00-00';
                // this.Facturado='Anulada';
                this.Anulado='SI';
                this.TipoFiltro=2;
				this.rowSinregistro = 10;
            }

            this.Lista();


         this.bus.$on('Regresar',()=>
        {
            this.$router.push({name:'submenufact'});

        });


    }
}
</script>
