<template>
    <div>
        <Clist :regresar="true" :Nombre="NameList" @FiltrarC="Lista" :Filtro="Filtro" :isModal="EsModal" :pConfigLoad="ConfigLoad">
			<template slot="header">
                <tr >
                <th>Folio</th>
                <th>Cliente</th>
                <th>Sucursal</th>
                <th>Materiales</th>
                <th>Mano obra</th>
                <th>Misceláneos</th>
                <th>Costo KM</th>
                <th>Total</th>
                <th>Fecha</th>
                <th>Acciones</th>
                </tr>
            </template>
			<template slot="body">
				<tr v-for="(lista,index) in Listacotizacion_servicio" :key="index" >
					<td>{{lista.Folio }}</td>
                    <td>{{lista.Cliente.substr(0, 20) }}</td>
                    <td>{{lista.Sucursal.substr(0, 20) }}</td>
                    <td>${{Number(lista.totalMateriales).toLocaleString() }}</td>
                    <td>${{Number(lista.totalManoDeObra).toLocaleString()}}</td>
                    <td>${{Number(lista.totalMiscelaneos).toLocaleString()}}</td>
                    <td>${{Number(lista.costoKm ).toLocaleString()}}</td>
                    <td>${{Number(lista.totalGlobal).toLocaleString() }}</td>
                    <td><i class="fas fa-calendar-day"></i>  {{lista.fechaCotiServicio }}</td>
                    <td>
                        <Cbtnaccion :isModal="EsModal" :Id="lista.IdCotizacionServicio" :IrA="FormName" >
                        <template slot="btnaccion">
                            <button v-b-tooltip.hover.lefttop  title="Descargar" @click="Descargar(lista.IdCotizacionServicio)"  type="button" class="btn-icon mr-2">   <i class="fas fa-file-pdf"></i> </button>
                        </template>
                        </Cbtnaccion>
                    </td>
                </tr>
				<CSinRegistros :pContIF="Listacotizacion_servicio.length" :pColspan="10" ></CSinRegistros>
            </template>
        </Clist>
    </div>
</template>
<script>

import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';
import CSinRegistros from "../../../components/CSinRegistros";

export default {
    name :'listCotizaciones',
    components :{
        Clist,
		Cbtnaccion,
		CSinRegistros
    },
    data() {
        return {
            FormName:'cotizacion_principal',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:false,//indica si es modal o no,
            CloseModal:true,//indica si el modal cierra o de lo contrario asignarle un evento al boton
            size :"modal-xl",
            NameList:"Lista de Cotizaciones de Servicios",
            NameForm:"Lista de Cotizaciones de Servicios",
            urlApi:"cotizacion_servicio/get",
            Listacotizacion_servicio:[],
            ListaHeader:[],
            Rutaicono:'',
            Filtro:{
                Nombre:'',
                Placeholder:'Folio ...',
				TotalItem:0,
                Pagina:1
            },
			ConfigLoad:{
				ShowLoader:true,
				ClassLoad:true,
			}
        }
    },
    methods: {
		async Lista() {
			this.ConfigLoad.ShowLoader = true;

			await this.$http.get(
				this.urlApi,
				{
					params:{Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina, RegEstatus:'A'}
				}
			).then( (res) => {
				this.Listacotizacion_servicio =res.data.data.row;
				this.Filtro.Entrada=res.data.data.pagination.PageSize;
				this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
			}).finally(()=>{
				this.ConfigLoad.ShowLoader = false;
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
                        'cotizacion_servicio/' + Id
                    ).then( (res) => {
                        this.$toast.success('Información eliminada');
                        this.Lista();
                    });
                }
            });
        },

        Change(titulo)
        {
            this.NameForm=titulo;
            var bdn=true;
            if(titulo=='Iconos'){
                bdn=false;
            }
            this.bus.$emit('cambiar_CloseModal',bdn);
        },
        Limpiar(){
            this.bus.$emit('cambiar_CloseModal',true);
        },
        get_form(){
            this.$router.push({name:'cotizacion_principal', params: { tipolistp:this.TipoList,Id:0}});
        },
        Descargar(IdCotizacion)
        {
            this.$http.get('reporte/Cotizacion',
            {
                responseType: 'blob',
                params :{
                        key:IdCotizacion,
                    }
            }).then( (response) => {
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
    },
    created()
    {
        this.bus.$off('Delete');
        this.bus.$off('List');
        this.bus.$off('Limpiar');
        this.bus.$off('Regresar');
        this.bus.$off('Nuevo');
        this.Lista();

        this.bus.$on('Nuevo',()=>
        {
            this.get_form();
        });

        this.bus.$on('Delete',(Id)=>
        {
            this.Eliminar(Id);

        });
        this.bus.$on('List',()=>
        {
            this.Lista();
        });

        this.bus.$on('Limpiar',()=>
        {
            this.Limpiar();
        });
        this.bus.$on('Regresar',()=>
        {
            this.$router.push({name:'submenucotizacion'});
        });
    }
}
</script>
