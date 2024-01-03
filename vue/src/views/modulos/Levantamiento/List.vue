<template>
    <div>  
        <Clist :regresar="false" :pShowBtnAdd="false" @FiltrarC="Lista" :Filtro="Filtro" :Nombre="NameList"  :isModal="EsModal">
            <template slot="Filtros">
                <div class="form-group  mr-2">
                    <v-date-picker
                    mode='range'
                    v-model='rangeDate'
                    @input="Lista"
                    :input-props='{
                    class: "form-control   calendar",
                    placeholder: "Selecciona un rango de fecha para buscar",
                    readonly: true
                    }'/>
                </div>

                <div class="form-group  mr-2">
                    <select style="width: 150px;" @change="Lista" v-model="Filtro.IdTrabajador"  class="form-control">
                        <option :value="''">--Personal--</option>
                        <option v-for="(item, index) in ListaTrabajadores" :key="index" :value="item.IdTrabajador">{{item.Nombre}}</option>
                    </select>
                </div>

            </template>
            
            <template slot="header">
                <tr >
                    <th>Folio servicio</th>
                    <th>Folio cotización</th>
                    <th>Cliente</th>
                    <th>Sucursal</th>
                    <th>Materiales</th>
                    <th>Mano obra</th>
                    <th>Micelaneos</th>
                    <th>Costo KM</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr> 
            </template>
            <template slot="body">
                <tr v-for="(lista,key,index) in ListaServicio" :key="index" >
                    <td>{{lista.Folio }}</td>
                    <td>{{lista.FolioC }}</td>
                    <td>{{lista.NomCli }}</td>
                    <td>{{lista.Cliente }}</td>
                    <td>{{lista.totalMateriales}}</td>
                    <td>{{lista.totalManoDeObra }}</td>
                    <td>{{lista.totalMiscelaneos }}</td>
                    <td>{{lista.costoKm }}</td>
                    <td>{{lista.totalGlobal }}</td>
                    <td><i class="fas fa-calendar-day"></i> {{lista.FechaTrabajo}}</td>
                    <td>{{lista.Estatus }}</td>
                    <td>
                        <Cbtnaccion :pShowButtonEdit="false" :ShowButtonG="false" :isModal="EsModal" :Id="lista.IdServicio" :IrA="FormName" >
                            <template slot="btnaccion">                               
                                <button v-b-tooltip.hover.leftbottom  @click="Descargar(lista.IdServicio)" title="Orden de Servicio" type="button" class="btn-icon mr-2" >  <i class="fas fa-file-pdf"></i></button>
                                <button v-b-tooltip.hover.leftbottom  @click="DescargarEvidencia(lista.IdServicio)" title="Evidencia" type="button" class="btn-icon mr-2" >  <i class="fas fa-file-pdf"></i></button>
                                <!-- <button v-b-tooltip.hover.leftbottom  @click="OpenCorreo(lista.IdServicio)" title="Enviar Correo" type="button" class="btn-icon mr-2" data-toggle="modal" data-target="#ModalMail"  data-backdrop="static" data-keyboard="false"><i class="fas fa-envelope-open-text"></i></button> -->
                                <!-- Cotizar -->
                                <button v-if="lista.IdCotizacionServicio == 0 || lista.IdCotizacionServicio == null" v-b-tooltip.hover.leftbottom  @click="Cotizar(lista.IdServicio)" title="Cotizar" type="button" class="btn-icon mr-2" ><i class="fas fa-search-dollar"></i></button>

                                <button v-if="lista.IdCotizacionServicio > 0"  v-b-tooltip.hover.leftbottom  @click="EditarC(lista.IdCotizacionServicio)" title="Editar cotización" type="button" class="btn-icon mr-2" ><i class="fas fa-edit"></i></button>

                                <button  v-b-tooltip.hover.leftbottom  @click="DescargarCot(lista.IdCotizacionServicio)" title="Descargar cotización" type="button" class="btn-icon mr-2" ><i class="fas fa-file-download"></i></button>

                                <!--Fin Cotizar-->

                                <button v-if="lista.IdCotizacionServicio > 0 && lista.Estatus != 'CERRADA'" v-b-tooltip.hover.leftbottom  @click="CambiarEstatus(lista.IdCotizacionServicio)" title="Cambiar Estatus" type="button" class="btn-icon mr-2" data-toggle="modal" data-target="#ModalEstatus"  data-backdrop="static" data-keyboard="false"><i class="fas fa-wrench"></i></button>
                            </template>
                        </Cbtnaccion>  
                    </td>   
                </tr>
            </template>
        </Clist>

        <Modal :NameModal="'ModalEstatus'" :size="size" :Showbutton="false"  :Nombre="'Estatus'" >
            <template slot="Form">
                <FomE ></FomE>
            </template>
        </Modal> 
      
    </div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';
import FomE from '@/views/modulos/Levantamiento/FormEstaus.vue'

export default {
    name :'list',
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        FomE
        
    },
    data() {
        return {
            FormName:'TipoUnidadForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"modal-xl",
            NameList:"Servicios de Levantamiento",
            urlApi:"levantamiento/get",

            urlApirecovery:'servicio/recovery',
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
            ListaTipoServicio:[]
        }
    },
    methods: {
        DescargarCot(IdCotizacion)
        {
            this.$http.get(
                'reporte/Cotizacion',
                {
                responseType: 'blob',
                params :{
                        key:IdCotizacion,
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
                fileLink.setAttribute('download', 'Servicio.pdf');
                document.body.appendChild(fileLink);
                fileLink.click();*/
            });
        },
        CambiarEstatus(Id){
            //this.bus.$emit('Nuevo',Id);
            this.bus.$emit('Nuevo',false,Id);
        },
        EditarC(Id){
            this.$router.push({name:'cotizacion_principal', params: { tipolistp:this.TipoList,Id:Id,objsucursal:{}}});
        },
        Cotizar(IdServicio){
            let Ocliente={
                IdClienteS:0,IdCliente:0,Nombre:'',Direccion:'',Telefono:'',Correo:'',IdServicio:''
            }
            this.$http.get(
                this.urlApirecovery,
                {
                    params:{IdServicio: IdServicio}
                }
            ).then( (res) => {
                Ocliente.IdClienteS = res.data.data.clientesuc.IdClienteS;
                Ocliente.IdCliente = res.data.data.clientesuc.IdCliente;
                Ocliente.Nombre = res.data.data.clientesuc.Nombre;
                Ocliente.Correo = res.data.data.clientesuc.Correo;
                Ocliente.Telefono = res.data.data.clientesuc.Telefono;
                Ocliente.Direccion = res.data.data.clientesuc.Direccion;
                Ocliente.IdServicio = IdServicio;

                this.$router.push({name:'cotizacion_principal', params: { tipolistp:this.TipoList,Id:0,objsucursal:Ocliente}});
            });
        },
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
            }).then( (res) => {
                this.ListaServicio =res.data.data.servicio;
                this.Filtro.Entrada=res.data.data.pagination.PageSize;
                this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
            });
        },
        Descargar(IdServicio)
        {
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
                /*
                var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                var fileLink = document.createElement('a');
                fileLink.href = fileURL;
                fileLink.setAttribute('download', 'Servicio.pdf');
                document.body.appendChild(fileLink);
                fileLink.click();*/
            });
        },
        DescargarEvidencia(IdServicio)
        {
            this.$http.get(
                'reporte/servicioevidencia',
                {
                responseType: 'blob',
                params :
                    {
                        IdServicio:IdServicio,
                    }
                }
            ).then( (response) => {
                // var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                let pdfContent = response.data;
                let file = new Blob([pdfContent], { type: 'application/pdf' });
                let fileUrl = URL.createObjectURL(file);
                window.open(fileUrl);
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