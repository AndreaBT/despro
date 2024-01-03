<template>
	<div>
		<Clist :regresar="true" :pShowBtnAdd="false"  :Nombre="'Servicios para Facturar'"
		   @FiltrarC="ListaPrincipal" :Filtro="Filtro" :isModal="EsModal" :oHead="Head" :pConfigLoad="ConfigLoad">

			<template slot="Filtros">
				<label >Clasificación: &nbsp;</label>
				<select @change="ListaPrincipal" v-model="TipoFactura" class="form-control" name="" id="" >
					<option :value="1">Factura</option>
					<option :value="2">Factura Libre</option>
				</select>

                <label  class="ml-2">Estatus: &nbsp;</label>
				<select @change="ListaPrincipal" v-model="EstatusFact" class="form-control ml-2" name="" id="" >
					<option v-if="TipoFactura==2" :value="1">Seleccione un Estatus</option>
                    <option v-else :value="1">Sin Factura</option>
					<option :value="2">Pendientes</option>
                    <option :value="3">Canceladas</option>
                    <option :value="4">Anuladas</option>
				</select>

				<button class="btn btn-01 ml-2"  @click="FacturaLibre(IdFactura,Factura)" type="button" v-b-tooltip.hover title="Factura Libre"  >Factura Libre</button>
			</template>

            <template slot="header">
                   <tr>
					   <th>Folio</th>
					   <th>Fecha</th>
					   <th>Cliente</th>
					   <th>Sucursal</th>
					   <th v-if="TipoFactura=='1'">Servicio</th>
                       <th v-if="TipoFactura=='1'|| TipoFactura=='2'">Estatus</th>
					   <!-- <th v-if="TipoFactura=='1'">Contrato</th> -->
					   <th class="text-center tw-2">Acciones</th>
                    </tr>
            </template>

             <template  slot="body">
                   <tr :class="lista.ComentarioCancel !='' && lista.ComentarioCancel!=null || lista.Facturado=='Anulada' ? 'bg-danger':''"  v-for="(lista,index) in ListaServ" :key="index" >
                       <template v-if="TipoFactura==1">
                           <td><b class="bold">{{lista.Folio }} </b></td>
                           <td class="tw-2"><i class="fas fa-calendar-day"></i> <b class="bold">{{lista.FechaI2 }}</b> </td>
                            <td>{{Cortador(lista.ClienteP)}} </td>
                             <td >{{Cortador(lista.Sucursal)}} </td>
                            <td>{{lista.TipoServ }} </td>
                            <td v-if="EstatusFact==1 && lista.Facturado=='NO'"> <b>Pendiente</b>  </td>
                            <td v-if="EstatusFact==1 && lista.Facturado==null "> <b>Sin Factura</b>  </td>
                            <td v-if="EstatusFact==1 && lista.Facturado=='Cancelado'"> <b>{{lista.Facturado }}</b>  </td>
                            <td v-if="EstatusFact==1 && lista.Facturado=='Anulada'"> <b>{{lista.Facturado }}</b>  </td>
                            <td v-if="EstatusFact==2 && lista.Facturado=='NO'"> <b>Pendiente</b>  </td>
                            <td v-if="EstatusFact==3 && lista.Facturado=='Cancelado'"> <b> {{lista.Facturado }}</b></td>
                            <td v-if="EstatusFact==4 && lista.Facturado=='Anulada'"> <b>{{lista.Facturado }}</b> </td>
                       </template>
                       <template v-else>
                           <td><b class="bold">{{lista.FolioFactura }} </b></td>
                           <td><i class="fas fa-calendar-day"></i> <b class="bold">{{lista.FechaReg}}</b> </td>
                           <td>{{Cortador(lista.NombreCliente) }} </td>
                            <td >{{Cortador(lista.Sucursal)}} </td>


                            <td v-if="EstatusFact==2 && lista.Facturado=='NO'"> <b>Pendiente</b>  </td>
                            <td v-if="EstatusFact==3 && lista.Facturado=='Cancelado'"> <b> {{lista.Facturado }}</b></td>
                            <td v-if="EstatusFact==4 && lista.Facturado=='Anulada'"> <b>{{lista.Facturado }}</b> </td>
                       </template>


                       <!-- <td v-if="TipoFactura=='1'">{{lista.NumeroC }} </td> -->
                       <!-- <td v-if="TipoFactura=='1'">{{lista.ComentarioC.substr(0, 20) }} </td> -->
                        <td class="text-center tw-2">
                            <button v-if="TipoFactura==1" @click="Go_To_FacturaForm(lista.IdServicio)" class="btn btn-table pl-01 mr-1" v-b-tooltip.hover title="Crear Factura" >
                                <i class="fas fa-plus-square fa-fw-m"></i>
                            </button>
                             <button v-if="TipoFactura==2" @click="FacturaLibre(lista.IdFactura,Factura)" class="btn btn-table pl-01 mr-1" v-b-tooltip.hover title="Crear Factura" >
                                <i class="fas fa-plus-square fa-fw-m"></i>
                            </button>
                            <button v-if="lista.IdFactura!=null && lista.Facturado!='Anulada'" @click="DescargarPdf(lista.IdFactura)" class="btn btn-table pl-01" v-b-tooltip.hover title="PDF Factura">
                                <i class="fas fa-file-pdf fa-fw-m"></i>
                            </button>

                        </td>
                   </tr>
				 <CSinRegistros :pContIF="ListaServ.length" :pColspan="[TipoFactura === '1' ? 6 : 5]" ></CSinRegistros>
                  <!---<tr @change="listaFactAnulada" v-for="(lista2,key,index) in ListaAnuladas" :key="index" >
                        <td ><b class="bold">{{lista2.Folio }} </b></td>
                        <td class="tw-2"><i class="fas fa-calendar-day"></i> <b class="bold">{{lista2.FechaI2 }}</b> </td>
                        <td>{{lista2.ClienteP.substr(0, 20) }} </td>
                        <td>{{lista2.Sucursal }} </td>
                        <td>{{lista2.TipoServ }} </td>
                        <td>{{lista2.NumeroC }} </td>
                        <td >{{lista2.ComentarioC}} </td>
                            <td class="text-center tw-2">
                                <button @click="Go_To_FacturaForm(lista2.IdServicio)" class="btn btn-table pl-01 mr-1" v-b-tooltip.hover title="Crear Factura" >
                                    <i class="fas fa-plus-square fa-fw-m"></i>
                                </button>
                               <button v-if="lista2.IdFactura!=null" @click="DescargarPdf(lista2.IdFactura)" class="btn btn-table pl-01" v-b-tooltip.hover title="PDF Factura">
                                    <i class="fas fa-file-pdf fa-fw-m"></i>
                                </button>
                            </td>

                    </tr>--->

            </template>

        </Clist>

</div>

</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Form from '@/views/modulos/factura/Form.vue'
import FacturaLibre from '@/views/modulos/factura/FacturaLibre.vue'
import CSinRegistros from "../../../components/CSinRegistros";

export default {
    props:[''],
    name :'list',
    components :{
        Modal,
        Clist,
        Form,
        FacturaLibre,
		CSinRegistros

    },
    data() {
        return {
            EsModal:true,//indica si es modal o no
            size :"None",
            urlApi:"caja/get",
            ListaServ:[],
            ListaFactLibre:[],
            ListaAnuladas:[],
            ListaHeader:[],
            TotalPagina:2,
            NameList:'',
            Pag:0,
              Head:{
                Title:'Servicios para Facturar',
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
              Filtro:{
                Nombre:'',
                Placeholder:'Folio ...',
                 TotalItem:0,
                Pagina:1
            },
            TipoFactura:1,
            IdFactura:0,
            Factura:1,
            isOverlay: true,

			ConfigLoad:{
				ShowLoader:true,
				ClassLoad:true,
			},

            EstatusFact:1,
        }
    },
    methods: {
        async ListaPrincipal() {
			this.ConfigLoad.ShowLoader = true;

			if(this.TipoFactura==1 && this.EstatusFact==1){

				await this.$http.get(
                    'factura/servxfact',
                    {
                        params:{Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina, RegEstatus:'A',TipoFactura:this.TipoFactura}
                    }
                ).then( (res) => {

                    this.ListaServ			= res.data.data.servxfact;
                    this.Filtro.Entrada		= res.data.data.pagination.PageSize;
                    this.Filtro.TotalItem	= res.data.data.pagination.TotalItems;
					this.Filtro.Pagina 		= res.data.data.pagination.CurrentPage;



                }).catch((e) => {

                }).finally(()=>{
					this.ConfigLoad.ShowLoader = false;

                });

            }else if(this.TipoFactura==1 && this.EstatusFact==2)  {

                    await  this.$http.get(
                        'factura/facturaxEstatusPendiente',
                        {
                            params:{Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina,Facturado:'SI'}
                        }
                        ).then( (res) => {
                            this.ListaServ=res.data.data.Pendientes;
                            this.Filtro.Entrada=res.data.data.pagination.PageSize;
                            this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
                            this.Filtro.Pagina 		= res.data.data.pagination.CurrentPage;


                        }).catch((e) => {

                        }).finally(()=>{
                        this.ConfigLoad.ShowLoader = false;
                        });
            }else if(this.TipoFactura==1 && this.EstatusFact==3)  {

                await  this.$http.get(
                    'factura/facturaxEstatusCancelada',
                    {
                        params:{Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina,Facturado:'Cancelado'}
                    }
                    ).then( (res) => {
                        this.ListaServ=res.data.data.Canceladas;
                        this.Filtro.Entrada=res.data.data.pagination.PageSize;
                        this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
                        this.Filtro.Pagina 		= res.data.data.pagination.CurrentPage;


                    }).catch((e) => {

                    }).finally(()=>{
                    this.ConfigLoad.ShowLoader = false;
                    });
            }else if(this.TipoFactura==1 && this.EstatusFact==4)  {

                await  this.$http.get(
                    'factura/facturaxEstatusAnulada',
                    {
                        params:{Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina,RegEstatus:'A',Facturado:'Anulada'}
                    }
                    ).then( (res) => {
                        this.ListaServ=res.data.data.Anuladas;
                        this.Filtro.Entrada=res.data.data.pagination.PageSize;
                        this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
                        this.Filtro.Pagina 		= res.data.data.pagination.CurrentPage;


                    }).catch((e) => {

                    }).finally(()=>{
                    this.ConfigLoad.ShowLoader = false;
                    });
            }else if(this.TipoFactura==2 && this.EstatusFact==1)  {

               this.ListaServ=[];
               this.ConfigLoad.ShowLoader = false;
            }else if(this.TipoFactura==2 && this.EstatusFact==2)  {

                    await  this.$http.get(
                        'factura/facturaLibre/get',
                        {
                            params:{Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina,TipoFactura:this.TipoFactura}
                        }
                        ).then( (res) => {
                            this.ListaServ=res.data.data.facturaLibre;
                            this.Filtro.Entrada=res.data.data.pagination.PageSize;
                            this.Filtro.TotalItem=res.data.data.pagination.TotalItems;


                        }).catch((e) => {

                        }).finally(()=>{
                        this.ConfigLoad.ShowLoader = false;
                        });
            }else if(this.TipoFactura==2 && this.EstatusFact==3)  {

                    await  this.$http.get(
                        'factura/facturalibreCancelada',
                        {
                            params:{Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina,TipoFactura:this.TipoFactura}
                        }
                        ).then( (res) => {
                            this.ListaServ=res.data.data.facturaLibreCancelada;
                            this.Filtro.Entrada=res.data.data.pagination.PageSize;
                            this.Filtro.TotalItem=res.data.data.pagination.TotalItems;


                        }).catch((e) => {

                        }).finally(()=>{
                        this.ConfigLoad.ShowLoader = false;
                        });
            }else if(this.TipoFactura==2 && this.EstatusFact==4)  {

                    await  this.$http.get(
                        'factura/facturalibreAnulada',
                        {
                            params:{Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina,TipoFactura:this.TipoFactura}
                        }
                        ).then( (res) => {
                            this.ListaServ=res.data.data.facturaLibreAnulada;
                            this.Filtro.Entrada=res.data.data.pagination.PageSize;
                            this.Filtro.TotalItem=res.data.data.pagination.TotalItems;


                        }).catch((e) => {

                        }).finally(()=>{
                        this.ConfigLoad.ShowLoader = false;
                        });
            }


        },


        FacturaLibre(Id,Factura){
             this.$router.push({name:'FacturaLibre',params:{Id:parseInt(Id),Factura:parseInt(Factura)}})
        },
        Go_To_FacturaForm(Id)
        {
            this.$router.push({name:'formfact',params:{Id:parseInt(Id)}})
        },
       Lista()
        {

        },
         ListaFacturaLibre(){


        },

        Cortador(lista){
            if (lista!='' && lista!=undefined) {
                return lista.substr(0, 20);
            }
        },



        /*async listaFactAnulada(){
           await this.$http.get(
                'factura/facturaAnulada',
                {
                    params:{Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina,RegEstatus:'Anulada'}
                }
            ).then( (res) => {
              this.ListaAnuladas=res.data.data.facturaAnulada;
               this.Filtro.Entrada=res.data.data.pagination.PageSize;
              this.Filtro.TotalItem=res.data.data.pagination.TotalItems;

            });
        },*/
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
    },
    created()
    {
        this.bus.$off('List');
        this.bus.$off('Regresar');
        this.ListaPrincipal();
        //this.listaFactAnulada();

         this.bus.$on('List',()=>
        {
            this.ListaPrincipal();
            //this.listaFactAnulada();
        });

         this.bus.$on('Regresar',()=>
        {
            this.$router.push({name:'submenufact'});
        });
    }
}
</script>
