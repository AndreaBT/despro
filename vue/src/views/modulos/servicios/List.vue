<template>
    <div>
        <Clist :regresar="false" :pShowBtnAdd="false" @FiltrarC="Lista" :Filtro="Filtro" :Nombre="NameList"  :isModal="EsModal" :pConfigLoad="ConfigLoad">
            <template slot="Filtros">
                <div class="form-group  mr-2">
                    <v-date-picker
                    mode='range'
                    v-model='rangeDate'
                    :input-props='{
                    class: "form-control   calendar",
                    placeholder: "Selecciona un rango de fecha para buscar",
                    readonly: true
                    }'/>
                </div>

                <div class="form-group  mr-2">
                    <select style="width: 150px;"  v-model="Filtro.IdTrabajador"  class="form-control">
                        <option :value="''">--Personal--</option>
                        <option v-for="(item, index) in ListaTrabajadores" :key="index" :value="item.IdTrabajador">{{item.Nombre}}</option>
                    </select>
                </div>

                <div class="form-group  mr-2">
                    <select style="width: 150px;"   v-model="Filtro.IdTipoServicio"  class="form-control">
                        <option :value="''" >--Tipo Servicio--</option>
                        <option v-for="(item, index) in ListaTipoServicio" :key="index" :value="item.IdTipoSer" >{{item.Concepto}}</option>
                    </select>
                </div>

                <div class="form-group  mr-2">
                    <select  v-model="Filtro.EstatusS" class="form-control">
                        <option :value="''">--Estatus --</option>
                        <option :value="'ABIERTA'">ABIERTA</option>
                        <option :value="'PENDIENTE'">PENDIENTE</option>
                        <option :value="'CERRADA'">CERRADA</option>
                        <option :value="'CANCELADA'">CANCELADA</option>
                    </select>
                </div>

                <div class="form-group  mr-2">
                    <button  :disabled="Disablebtn" v-b-tooltip.hover.leftbottom  @click="Lista()" title="Filtrar" type="button" class="btn btn-primary mr-1" ><i v-show="Disablebtn" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>Filtrar</button>
                </div>

            </template>


                <template slot="header">
                    <tr >
                        <th>Folio</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Sucursal</th>
                        <th>Tipo de servicio</th>
                        <th>Observaciones</th>
                        <th>Observaciones finales</th>
                        <th>Acciones</th>
                    </tr>
                </template>
                <template slot="body">
                    <tr v-for="(lista,key,index) in ListaServicio" :key="index" >
                        <td>{{lista.Folio }}</td>
                        <td> <i class="fas fa-calendar-day"></i> {{lista.FechaTrabajo}}</td>
                        <td>{{lista.NomCli }}</td>
                        <td>{{lista.Cliente }}</td>
                        <td>{{lista.Servicio }}</td>
                        <td>{{lista.Observaciones | subStr}}</td>
                        <td>{{lista.ComentarioFin | subStr}}</td>
                        <td>
                            <Cbtnaccion :isModal="EsModal" :Id="lista.IdServicio" :IrA="FormName" >
                                <template slot="btnaccion">

                                <button v-if="lista.EstadoS === 'CERRADA'" v-b-tooltip.hover.leftbottom  @click="Descargar(lista.IdServicio)" title="Orden de Servicio" type="button" class="btn-icon mr-2" ><i :id="'pdfOrden_' + lista.IdServicio" class="fas fa-file-pdf"></i></button>
                                <button v-if="lista.EstadoS === 'CERRADA'" v-b-tooltip.hover.leftbottom  @click="DescargarEvidencia(lista)" title="Evidencia" type="button" class="btn-icon mr-2" ><i :id="'pdfEvidencia_' + lista.IdServicio" class="fas fa-file-pdf"></i></button>
                                <button v-if="lista.EstadoS === 'CERRADA'" v-b-tooltip.hover.leftbottom  @click="OpenCorreo(lista.IdServicio)" title="Enviar Correo" type="button" class="btn-icon mr-2" data-toggle="modal" data-target="#ModalMail"  data-backdrop="static" data-keyboard="false"><i class="fas fa-envelope-open-text"></i></button>

                                </template>
                            </Cbtnaccion>
                        </td>
                    </tr>
					<CSinRegistros :pContIF="ListaServicio.length" :pColspan="8" ></CSinRegistros>
                </template>

        </Clist>

        <Modal  :size="size" :Showbutton="false"  :Nombre="NameList" >
            <template slot="Form">
                <Form ></Form>
            </template>
        </Modal>

        <Modal :NameModal="'ModalMail'" :size="size" :Showbutton="false"  :Nombre="'Mail'" >
            <template slot="Form">
                <Mail ></Mail>
            </template>
        </Modal>

</div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from "../../../components/Clist";
import Cbtnaccion from '@/components/Cbtnaccion.vue';
import SpinnerComponent from '@/components/SpinnerComponent.vue';
import CSinRegistros from "../../../components/CSinRegistros";

import Form from '@/views/modulos/servicios/Form.vue'
import Mail from '@/views/modulos/servicios/Mail.vue'

export default {
    name :'listConsultasDespacho',
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        Form,
        Mail,
        SpinnerComponent,
		CSinRegistros

    },
    data() {
        return {
            Disablebtn:false,
            SpinOrden: false,
            SpinEvidencia: false,
            DisableOrden: true,
            DisableEvidencia: true,
            FormName:'TipoUnidadForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"modal-xl",
            NameList:"Servicios",
            urlApi:"servicio/get",
            ListaServicio:[],
            Filtro:{
                Nombre:'',
                Placeholder:'Folio/Contrato/Cliente/Sucursal',
                TotalItem:0,
                Pagina:1,
                FechaI:'',
                FechaF:'',
                IdTrabajador:'',
                IdTipoServicio:'',
                EstatusS:''
            },
            rangeDate:{},
            ListaTrabajadores:[],
            ListaTipoServicio:[],

			ConfigLoad:{
				ShowLoader:true,
				ClassLoad:true,
			}
        }
    },
    methods: {
        Eliminar(Id)
        {

            this.$swal({
            title: 'Esta seguro que desea eliminar este dato?',
            text: 'No se podra revertir esta acción',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'No, mantener',
            showCloseButton: true,
            showLoaderOnConfirm: true
            }).then((result) => {
            if(result.value) {

                    this.$http.delete(
                        'servicio/' + Id
                    ).then( (res) => {
                        this.$toast.success('Información eliminada');
                            this.Lista();
                    });

                }
            });
        },

       async Lista()
        {
            //this.Disablebtn = true;
			this.ConfigLoad.ShowLoader = true;
            await this.$http.get(
                this.urlApi,
                {
                    params:
                    {
                        Nombre:this.Filtro.Nombre,
                        Entrada:this.Filtro.Entrada,
                        pag:this.Filtro.Pagina,
                        FechaI:this.rangeDate.start,
                        FechaF:this.rangeDate.end,
                        IdTrabajador:this.Filtro.IdTrabajador,
                        IdTipoServicio:this.Filtro.IdTipoServicio,
                        RegEstatus:'A',
                        EstatusS:this.Filtro.EstatusS
                    }
                }
            ).then( (res) => {

                this.ListaServicio =res.data.data.servicio;
                this.Filtro.Entrada=res.data.data.pagination.PageSize;
                this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
                //this.Disablebtn = false;

            }).finally(()=>{
				this.ConfigLoad.ShowLoader = false;
			});

        },

        Descargar(IdServicio)
        {
            let ActivarSpinner = document.getElementById(`pdfOrden_${IdServicio}`);
            ActivarSpinner.setAttribute('class','fa fa-spinner fa-pulse fa-1x fa-fw');

            this.$http.get(

                'reporte/servicio',
                {
                responseType: 'blob',
                params :
                    {
                        IdServicio:IdServicio,
                    }
                }
            ).then( (response) => {

                let pdfContent = response.data;
                let file = new Blob([pdfContent], { type: 'application/pdf' });
                let fileUrl = URL.createObjectURL(file);

                window.open(fileUrl);

                ActivarSpinner.setAttribute('class','fas fa-file-pdf');

                /*
                var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                var fileLink = document.createElement('a');
                fileLink.href = fileURL;
                fileLink.setAttribute('download', 'Servicio.pdf');
                document.body.appendChild(fileLink);
                fileLink.click();*/

            });
        },
        DescargarEvidencia(servicio)
        {
            let ActivarSpinner = document.getElementById(`pdfEvidencia_${servicio.IdServicio}`);
            ActivarSpinner.setAttribute('class','fa fa-spinner fa-pulse fa-1x fa-fw');

            this.$http.get(
                'reporte/servicioevidencia',
                {
                responseType: 'blob',
                params :
                    {
                        IdServicio:servicio.IdServicio,
                    }
                }
            ).then( (response) => {


				let pdfContent = response.data;
				let file = new Blob([pdfContent], { type: 'application/pdf' });
				let fileUrl = URL.createObjectURL(file);

				var fileLink = document.createElement('a');
				fileLink.href = fileUrl;
				fileLink.download = `EvidenciaServicio_${servicio.Folio}.pdf`;
				fileLink.click();


				//window.open(fileUrl);

				ActivarSpinner.setAttribute('class','fas fa-file-pdf');

                /*var fileLink = document.createElement('a');
                fileLink.href = fileURL;
                fileLink.setAttribute('download', 'Evidencias.pdf');
                document.body.appendChild(fileLink);
                fileLink.click();*/

            });
        },

        get_listtrabajador(){
            this.$http.get(
            'trabajador/get',
            {
                params:{Rol:'USUARIO APP',IdPerfil:4}
            }
            ).then( (res) => {
                this.ListaTrabajadores=res.data.data.trabajador;
            });
        },

        async ListaServ()
        {
            await this.$http.get(
            'tiposervicio/get',
            {
                params:{
                    Nombre:'',
                    Entrada:50,
                    pag:0,
                    RegEstatus:'A'
                }
            }
            ).then( (res) => {
                this.ListaTipoServicio =res.data.data.tiposervicio;
            });

        },

        OpenCorreo(Id)
        {
            this.bus.$emit('MailOpen',Id);
        }

    },

    filters: {
        subStr: function(string2) {
            let add = '';
            let string = (string2 == null || string2 == undefined)?'':string2;
            if(string !='')
                if(string.length>25)
                    add = '...';
            return string.substring(0,25)+add;
        }
    },
    created()
    {
        var date = new Date(), y = date.getFullYear(), m = date.getMonth();
        var firstDay = new Date(y, m, 1);
        var lastDay = new Date(y, m + 1, 0);

        this.rangeDate={
            start:firstDay,
            end:lastDay
        }

        this.get_listtrabajador();
        this.ListaServ();

        this.bus.$off('Delete');
        this.bus.$off('List');
        this.bus.$off('Regresar');
        this.Lista();

        this.bus.$on('Delete',(Id)=>
        {
            this.Eliminar(Id);
        });

        this.bus.$on('List',()=>
        {
            this.Lista();
        });

        this.bus.$on('Regresar',()=>
        {
           this.$router.push({name:'despacho'});
        });
    }
}
</script>
