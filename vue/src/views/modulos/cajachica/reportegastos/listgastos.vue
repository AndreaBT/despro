<template>
    <div>
        <Clist :regresar="true"  :pShowBtnAdd="false"  @FiltrarC="Lista" :Nombre="NameList"  :isModal="EsModal" :Filtro="Filtro" :pConfigLoad="ConfigLoad">
            <template slot="Filtros">
                <div class="form-group ml-2">
                    <label class="mr-1">Personal:</label>
                    <select v-model="IdTrabajador" @change="GetDatos" class="form-control"  style="max-width: 7rem" >
                        <option value="">Todos</option>
                        <option v-for="(item, index) in ListaTrabajador" :key="index" :value="item.IdTrabajador">{{item.Nombre}}</option>
                    </select>
                </div>
                <div class="form-group ml-3">
                    <!-- <label>Monto en Caja : ${{Number(cajachica.Monto).toLocaleString()}} </label> -->
                    <h3>Monto Viático :</h3>
                    <h3 class=" naranja mr-2 ml-1" > <b>$ {{CajaAsig}}</b>  </h3>

                </div>
                <div class="form-group ml-2">
                    <!-- <label>Asignado : ${{Number(cajaasig.MontoAsignado).toLocaleString()}} </label> -->
                    <h3>Asignado :</h3>
                    <h3 class="naranja mr-2 ml-1"><b>$ {{MontoAsig}}</b></h3>
                </div>
                    <div class="form-group ml-2">
                        <h3>Gastado :</h3>
                        <h3 class="naranja mr-2 ml-1"><b>$ {{TotalGasto}} </b></h3>
                    </div>
                    <div class="form-group ml-2">
                        <h3>Saldo :</h3>
                        <h3  class="naranja mr-2 ml-1"><b>$ {{Saldo}}</b></h3>
                    </div>
            </template>

             <template slot="header">
                <tr>
                    <th>Trabajador</th>
                    <th>Concepto</th>
                    <th>Fecha</th>
                    <th>Monto Fin</th>
                    <th>Acciones</th>
                </tr>
            </template>

            <template  slot="body">
                <tr v-for="(lista,index) in ListaG" :key="index" >
                    <td>{{lista.Trabajador }}</td>
                    <td>{{lista.Concepto }}</td>
                    <td> <i class="fas fa-calendar-day"></i> {{formato(lista.Fecha) }}</td>
                    <td> <b class="bold color-02">$ {{lista.Total}}</b></td>
                    <td>
                        <button title="Evidencias"  class="btn-icon mr-2" data-toggle="modal" data-target="#ModalForm"  data-backdrop="static" data-keyboard="false"  @click="VerEvidencias(lista.IdGasto)" type="button">
                            <i class="far fa-file-image"></i>
                        </button>
                    </td>
                </tr>
				<CSinRegistros :pContIF="ListaG.length" :pColspan="5" ></CSinRegistros>
            </template>
        </Clist>

        <Modal :Showbutton="false"   :size="size" :Nombre="NameList" >
            <template slot="Form" >
                <Form>
                </Form>
            </template>
        </Modal>
</div>

</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';
import Form from '@/views/modulos/cajachica/reportegastos/fotos.vue';
import moment from 'moment';
import CSinRegistros from "../../../../components/CSinRegistros";

export default {
    name :'listGastosViaticos',
    props:['IdCajaC'],
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
            urlApi:"gastoxtrabajador/getlist",
            ListaG:[],
            ListaHeader:[],
            IdTrabajador:'',
            ListaTrabajador:[],
            Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                TotalItem:0,
                Pagina:1,
                Entrada: 10
            },
            cajaasig:{
                MontoAsignado:0
            },
            TotalGastado:0,
            cajachica:{
            },
            IdCajaC2:0,
            rows:[],
            MontoAsig:0,
            CajaAsig:0,
            TotalGasto:0,
			ConfigLoad:{
				ShowLoader:true,
				ClassLoad:true,
			}
        }
    },
    methods: {
        async Lista()
        {
			this.ConfigLoad.ShowLoader = true;
            await this.$http.get(
                this.urlApi,
                {
                    params:{
                        IdCajaC:this.IdCajaC2,
                        Entrada:this.Filtro.Entrada,
                        RegEstatus:'A',
                        IdTrabajador:this.IdTrabajador
                    }
                }
            ).then( (res) => {
                this.ListaG=res.data.data.gastos;
                this.TotalGastado=res.data.data.TotalGastado;
                this.TotalGasto = this.numberto(Number(this.TotalGastado));
                this.Filtro.Entrada=res.data.data.pagination.PageSize;
                this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
                this.rows = res.data.data.rows;

            }).finally(()=>{
				this.ConfigLoad.ShowLoader = false;
			});
        },
        VerEvidencias(Id)
        {
           this.bus.$emit('AbrirFotos',Id);
        },
        ListarTrabajadores()
        {
            this.$http.get(
                'trabajador/get',
                {
                    params:{Rol:'Usuario APP',Rol:'Usuario APP',IdPerfil:4,Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina, RegEstatus:'A'}
                }
            ).then( (res) => {
                this.ListaTrabajador=res.data.data.trabajador;
            });
        },
        GetDatos()
        {
            this.$http.get(
                'asignacioncaja/recovery',
                {
                    params:{IdTrabajador:this.IdTrabajador,IdCajaC:this.IdCajaC2, RegEstatus:'A'}
                }
            ).then( (res) => {
                if (res.data.status)
                {
                    this.cajaasig=res.data.data.cajaasig2;
                    const asignado = res.data.data.cajaasig2.MontoAsignado;

                    this.MontoAsig = this.numberto(Number(asignado));
                }
                else{
                      this.cajaasig={
                        MontoAsignado:0
                    }
                    this.MontoAsig=0;
                }
                this.Lista();
            });
        },
        GetCajaChica()
        {
            this.$http.get(
                'cajachica/recovery',
                {
                    params:{IdCajaC:this.IdCajaC2, RegEstatus:'A'}
                }
            ).then( (res) => {
                this.cajachica=res.data.data.cajachica;
                const cajaMonto = res.data.data.cajachica.Monto;

                this.CajaAsig = this.numberto(Number(cajaMonto));
            });
        },
        formato(fecha){
            let formato = moment(fecha).format('DD-MM-YYYY');
            if(fecha!=null){
                return formato;
            }
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
    },
    computed: {
        Saldo()
        {
            let Saldo =0;
            Saldo=this.cajaasig.MontoAsignado - this.TotalGastado;
            return this.numberto(Number(Saldo));
        }
    },
    created()
    {
        if (this.IdCajaC!=undefined)
        {
            sessionStorage.setItem('IdSaved',this.IdCajaC);
        }

        this.IdCajaC2=sessionStorage.getItem('IdSaved');
        this.bus.$off('List');
        this.bus.$off('Regresar');
        this.GetDatos();
        this.ListarTrabajadores();
        this.GetCajaChica();

        this.bus.$on('List',()=>
        {
            this.Lista();
        });

        this.bus.$on('Regresar',()=>
        {
            this.$router.push({name:'reportegastos'});
        });
    }
}
</script>
