<template>
    <div>
        <Clist :regresar="true" @FiltrarC="Lista" :Filtro="Filtro" :pShowBtnAdd="false"  :Nombre="NameList"  :isModal="EsModal" :pConfigLoad="ConfigLoad">
            <template slot="Filtros">
                <div class="form-group ml-2">
                    <label class="mr-1">Estatus:</label>
                    <select @change="Lista" v-model="Filtro.Estado"  class="form-control">
                        <option value="">Todos</option>
                        <option value="Abierta">Abiertas</option>
                        <option value="Cerrado">Cerradas</option>
                    </select>
                </div>
            </template>

            <template slot="header">
                <tr>
                    <th>Nombre</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </template>

            <template slot="body">
                <tr v-for="(lista,index) in ListaCajaChica" :key="index" >
                    <td>{{lista.Caja }}</td>
                    <td>
                        <i class="fas fa-calendar-day"></i>
                        {{lista.FechaInicio }}</td>
                    <td>
                        <i class="fas fa-calendar-day"></i>
                        {{lista.FechaFin}}</td>
                    <td>
                        <b class="bold color-02">${{Number(lista.Monto).toLocaleString()}} </b>
                    </td>
                    <td>
                        <span :class="lista.Estado =='Abierta' ?  'badge badge-success':' badge badge-danger'">{{lista.Estado }}</span>
                    </td>
                    <td>
                        <button title="Gastos" class="btn-icon mr-2" @click="IrGastos(lista.IdCajaC)" type="button">
                            <i class="far fa-file-image"></i>
                        </button>
                    </td>
                </tr>
				<CSinRegistros :pContIF="ListaCajaChica.length" :pColspan="6" ></CSinRegistros>
            </template>
        </Clist>
    </div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';

import Form from '@/views/catalogos/cajachica/Form.vue'
import CSinRegistros from "../../../../components/CSinRegistros";

export default {
    name :'list',
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        Form,
		CSinRegistros
    },
    data() {
        return {
            FormName:'folioForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"modal-xl",
            NameList:"Registros",
            urlApi:"cajachica/get",
            ListaCajaChica:[],
            ListaHeader:[],
            Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                TotalItem:0,
                Pagina:1,
                Estado:'Abierta',
                Entrada: 10
            },
			ConfigLoad:{
				ShowLoader:true,
				ClassLoad:true,
			},
        }
    },
    methods: {
        async Lista() {
			this.ConfigLoad.ShowLoader = true;

            await this.$http.get(
                this.urlApi,
                {
                    params:{
						Nombre:this.Filtro.Nombre,
						Entrada:this.Filtro.Entrada,
						pag:this.Filtro.Pagina,
						Estado:this.Filtro.Estado,
						RegEstatus:'A'
					}
                }
            ).then( (res) => {
                this.ListaCajaChica		= res.data.data.cajachica;
                this.Filtro.Entrada		= res.data.data.pagination.PageSize;
                this.Filtro.TotalItem	= res.data.data.pagination.TotalItems;
				this.Filtro.Pagina 		= res.data.data.pagination.CurrentPage;
            }).finally(()=>{
				this.ConfigLoad.ShowLoader = false;
			});
        },
        IrGastos(Id)
        {
            this.$router.push({name:'listagastos',params:{IdCajaC:Id}});
        }
    },
    created()
    {
        this.bus.$off('List');
        this.bus.$off('Regresar');
        this.Lista();

        this.bus.$on('List',()=>
        {
            this.Lista();
        });
        this.bus.$on('Regresar',()=>
        {
            this.$router.push({name:'cajachica'});
        });
    }
}
</script>
