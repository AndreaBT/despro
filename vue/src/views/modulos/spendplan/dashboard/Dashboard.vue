<template>
  <div>
      <section class="container-fluid">
         
        <CHead :oHead="oHead">
            <template slot="component" >
                    

            </template>
        </CHead>

         <div class="row mt-2 bg-dash justify-content-right">
            <div class="col-md-12 col-lg-12">
                <form class="form-inline">
                <div @click="change_typeview(1)" style="cursor:pointer" v-if="this.TipoVista==2" class="col-2">
                    <i class="fa fa-th-large mr-2" aria-hidden="true"></i> <b>Ver Proyectos</b>
                </div>
               
                <div class="col-2">
                    <a-select
                        class="aselect"
                        show-search
                        :allowClear="true"
                        size="large"
                        :show-arrow="true"
                        :value="IdCliente"
                        placeholder="Buscar por Clientes"
                        :filter-option="false"
                        :not-found-content="fetching ? undefined : null"
                        @search="Get_Clientes"
                        @change="handleChange"
                    >
                        <a-spin v-if="fetching" slot="notFoundContent" size="small" />
                          <a-select-option  v-for="d in ListaClientes" :key="d.IdCliente">
                                {{ d.Nombre }}
                        </a-select-option>
                    </a-select>
                </div>
                <div class="col-2">
                    <a-select
                        show-search
                        :allowClear="true"
                        size="large"
                        :show-arrow="true"
                        :value="IdClienteS"
                        placeholder="Buscar por Sucursales"
                        :filter-option="false"
                        :not-found-content="fetchingSuc ? undefined : null"
                        @search="Get_ClienteSuc"
                        @change="handleChangeSuc"
                    >
                        <a-spin v-if="fetchingSuc" slot="notFoundContent" size="small" />
                          <a-select-option  v-for="d in ListaClienteSuc" :key="d.IdClienteS">
                                {{ d.Nombre }}
                        </a-select-option>
                    </a-select>
                </div>
               
                <div class="col-2">
                    <a-select
                        show-search
                        :allowClear="true"
                        size="large"
                        :show-arrow="true"
                        :value="IdProyecto"
                        placeholder="Buscar por Proyecto"
                        :filter-option="false"
                        :not-found-content="fetchingPro ? undefined : null"
                        @search="Get_Proyectos"
                        @change="handleChangePro"
                    >
                        <a-spin v-if="fetchingPro" slot="notFoundContent" size="small" />
                          <a-select-option  v-for="d in ListaProyectos" :key="d.IdProyecto">
                              {{ d.Folio }}  {{ d.Proyecto }}
                        </a-select-option>
                    </a-select>
                </div>
                </form>
            </div>
         </div>

       
        
        <div v-if="TipoVista==1">
            <div v-for="(item, index) in ListaAllProyec" :key="index" class="row mt-2 bg-dash justify-content-center">
                <div class="col-md-12 col-lg-12">
                    <div class="row justify-content-center">
                        <div  class="col-10">
                            <h3 @click="view_oneproyect(item)" style="cursor:pointer" class="color-01"><i  class="fa fa-th-large" aria-hidden="true"></i> {{item.Proyecto}}</h3>
                            <hr class="dash">
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-2">
                    <div class="card card-dashboard">
                        <div class="card-header card-header-success card-header-icon">
                            <p class="card-category">Valor Venta</p>
                            <h3 class="card-title">${{item.ValorVenta}}</h3>
                            <div class="progress mt-1">
                                <div :class="'progress-bar bg-success'" role="progressbar" :style="'width:'+item.ValorVentaPorc+'%;'" aria-valuenow="13.9" aria-valuemin="0" aria-valuemax="100">{{item.ValorVentaPorc}}%</div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="fas fa-arrow-up fa-lg text-success"></i>
                                <span class="text-secondary">Ganancia</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-2">
                    <div class="card card-dashboard">
                        <div class="card-header card-header-daguer card-header-icon">
                            <p class="card-category"> Facturación Actual</p>
                            <h3 class="card-title">$ {{item.FacturacionActual}}</h3>
                            <div class="progress mt-1">
                                <div :class="'progress-bar bg-success'" role="progressbar" :style="'width:'+item.FacturacionActPorc+'%;'" aria-valuenow="13.9" aria-valuemin="0" aria-valuemax="100">{{item.FacturacionActPorc}}%</div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="fas fa-arrow-down fa-lg text-danger"></i>
                                <span class="text-secondary">Pérdida</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-2 mt-dash-01">
                    <div class="card card-dashboard">
                        <div class="card-header card-header-warning card-header-icon">
                            <p class="card-category">Gasto Actual</p>
                            <h3 class="card-title">$ {{item.GastoActual}}</h3>
                            <div class="progress mt-1">
                                <div class="progress-bar bg-success" role="progressbar" :style="'width:'+item.GastoActPorc+'%;'" aria-valuenow="89.9" aria-valuemin="0" aria-valuemax="100">{{item.GastoActPorc}}%</div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="fas fa-sort-alt fa-lg text-warning"></i>
                                <span class="text-secondary">Neutral</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-2 mt-dash-01">
                    <div class="card card-dashboard">
                        <div class="card-header card-header-warning card-header-icon">
                            <p class="card-category"> Net Profit Actual</p>
                            <h3 class="card-title">$ {{item.NetProfitAct}}</h3>
                            <div class="progress mt-1">
                                <div class="progress-bar bg-success" role="progressbar" :style="'width:'+item.NetProfitActPorc+'%;'" aria-valuenow="89.9" aria-valuemin="0" aria-valuemax="100">{{item.NetProfitActPorc}}%</div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="fas fa-sort-alt fa-lg text-warning"></i>
                                <span class="text-secondary">Neutral</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-6 col-lg-2 mt-dash-01">
                    <div class="card card-dashboard">
                        <div class="card-header card-header-warning card-header-icon">
                            <p class="card-category">% Del Proyecto Actual</p>
                            <h3 class="card-title">% {{item.AvanceProyPorc}}</h3>
                            <div class="progress mt-1">
                                <div class="progress-bar bg-success" role="progressbar" :style="'width:'+item.AvanceProyPorc+'%;'" aria-valuenow="89.9" aria-valuemin="0" aria-valuemax="100">{{item.AvanceProyecto}} dias</div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                                <i class="fas fa-sort-alt fa-lg text-warning"></i>
                                <span class="text-secondary">Neutral</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-1 mb-3">
                <div class="col-md-10 col-lg-10">
                    <Pagina :Filtro="Filtro" :Entrada="10" @Pagina="list_allproyect" ></Pagina>
                </div>
            </div>
            
        </div>

        <div v-if="TipoVista==2" class="row mt-2 bg-dash justify-content-center">
            <div class="col-md-12 col-lg-12">
                <div class="row justify-content-center">
                    <div class="col-10">
                        <h3 class="color-01">{{proyecto.Proyecto}}</h3>
                        <hr class="dash">
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-6 col-lg-2">
                <div class="card card-dashboard">
                    <div class="card-header card-header-success card-header-icon">
                        <p class="card-category">Valor Venta</p>
                        <h3 class="card-title">${{oDatosGenerales.ValorVenta}}</h3>
                        <div class="progress mt-1">
                            <div :class="'progress-bar bg-success'" role="progressbar" :style="'width:'+oDatosGenerales.ValorVentaPorcentaje+'%;'" aria-valuenow="13.9" aria-valuemin="0" aria-valuemax="100">{{oDatosGenerales.ValorVentaPorcentaje}}%</div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="fas fa-arrow-up fa-lg text-success"></i>
                            <span class="text-secondary">Ganancia</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-6 col-lg-2">
                <div class="card card-dashboard">
                    <div class="card-header card-header-daguer card-header-icon">
                        <p class="card-category"> Facturación Actual</p>
                        <h3 class="card-title">$ {{oDatosGenerales.CostoOperacionalAct}}</h3>
                        <div class="progress mt-1">
                            <div :class="'progress-bar bg-success'" role="progressbar" :style="'width:'+oDatosGenerales.CostoOperacionalPorcAct+'%;'" aria-valuenow="13.9" aria-valuemin="0" aria-valuemax="100">{{oDatosGenerales.CostoOperacionalPorcAct}}%</div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="fas fa-arrow-down fa-lg text-danger"></i>
                            <span class="text-secondary">Pérdida</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-6 col-lg-2 mt-dash-01">
                <div class="card card-dashboard">
                    <div class="card-header card-header-warning card-header-icon">
                        <p class="card-category">Gasto Actual</p>
                        <h3 class="card-title">$ {{oDatosGenerales.GrossActual}}</h3>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-success" role="progressbar" :style="'width:'+oDatosGenerales.GrossActualPorc+'%;'" aria-valuenow="89.9" aria-valuemin="0" aria-valuemax="100">{{oDatosGenerales.GrossActualPorc}}%</div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="fas fa-sort-alt fa-lg text-warning"></i>
                            <span class="text-secondary">Neutral</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-6 col-lg-2 mt-dash-01">
                <div class="card card-dashboard">
                    <div class="card-header card-header-warning card-header-icon">
                        <p class="card-category"> Net Profit Actual</p>
                        <h3 class="card-title">$ {{oDatosGenerales.NetProfitActual}}</h3>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-success" role="progressbar" :style="'width:'+oDatosGenerales.NetProfitPorcAct+'%;'" aria-valuenow="89.9" aria-valuemin="0" aria-valuemax="100">{{oDatosGenerales.NetProfitPorcAct}}%</div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="fas fa-sort-alt fa-lg text-warning"></i>
                            <span class="text-secondary">Neutral</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-6 col-lg-2 mt-dash-01">
                <div class="card card-dashboard">
                    <div class="card-header card-header-warning card-header-icon">
                        <p class="card-category">% Del Proyecto Actual</p>
                        <h3 class="card-title">% {{oDatosGenerales.PorcentajeDias}}</h3>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-success" role="progressbar" :style="'width:'+oDatosGenerales.PorcentajeDias+'%;'" aria-valuenow="89.9" aria-valuemin="0" aria-valuemax="100">{{oDatosGenerales.DiasTranscurridos}} dias</div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="fas fa-sort-alt fa-lg text-warning"></i>
                            <span class="text-secondary">Neutral</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="TipoVista==2" class="row justify-content-center">
            <div class="col-12 col-sm-12 col-md-10 col-lg-10">
                <div class="row mt-3">
                    <div class="col-12 col-sm-12 col-md-8 col-lg-9">
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="card card-table">
                                    <h3 class="mb-2">Spen Plan</h3>
                                    <div class="table-responsive">
                                        <table class="table-07 text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        DESCRIPCIÓN
                                                    </th>
                                                    <th class="text-right">
                                                        MONTO
                                                    </th>
                                                    <th class="text-center">
                                                        %
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in ListaConceptos" :key="index">
                                                    <td>
                                                        {{item.Concepto}}
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="text-primary">${{item.Monto}}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="text-secondary">{{item.Porcentaje}}%</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="card card-table">
                                    <h3 class="mb-2">Actual  {{FechaActual}}</h3>
                                    <div class="table-responsive">
                                        <table class="table-08 text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        DESCRIPCIÓN
                                                    </th>
                                                    <th class="text-right">
                                                        YTD
                                                    </th>
                                                    <th class="text-center">
                                                        %YTD
                                                    </th>
                                                    <th class="text-right">
                                                        DISP. YTD
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in ListaConceptos" :key="index">
                                                    <td>
                                                        <span v-if="item.Concepto=='Valor Venta'">Facturación</span>
                                                        <span v-else>{{item.Concepto}}</span>
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="text-primary">${{item.MontoActual}}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        
                                                        <span v-if="item.Color=='Ganando'" class="text-success"><i class="fas fa-arrow-up"></i>{{item.PorcentajeAct}}% </span>
                                                        <span v-if="item.Color=='Perdiendo'" class="text-danger"><i class="fas fa-arrow-down"></i>{{item.PorcentajeAct}}% </span>
                                                        <span v-if="item.Color=='Neutro'" class="text-warning"><i class="fas fa-sort-alt"></i>{{item.PorcentajeAct}}% </span>
                                                    </td>
                                                    <td class="text-right"><span class="text-primary">${{item.DispYTD}}</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 mb-4">
                                    <div class="card card-dashboard">
                                        <div class="card-header card-header-success card-header-icon">
                                            <p class="card-category">Net Profit</p>
                                            <h3 class="card-title">${{proyecto.NetProfit}}</h3>
                                            <div class="progress mt-1">
                                                <div class="progress-bar bg-success" role="progressbar" :style="'width:'+proyecto.NetProfitPorc+'%;'" aria-valuenow="89.9" aria-valuemin="0" aria-valuemax="100">{{proyecto.NetProfitPorc}} %</div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="stats">
                                            </div>
                                        </div>
                                    </div>
                            </div>

                            <div class="col-md-12 col-lg-12">
                                <div class="card card-table">
                                    <div class="table-responsive">
                                        <table class="table-07 text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" colspan="2">
                                                        FECHAS PROYECTO
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Inicio
                                                    </td>
                                                    <td>
                                                        <i class="fas fa-calendar-day"></i> {{proyecto.FechaI}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Termino
                                                    </td>
                                                    <td>
                                                        <i class="fas fa-calendar-day"></i>  {{FechaTermino}} \  {{proyecto.CantidadTermino}} {{proyecto.FechaTermino}} (s)
                                                    </td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

    </section>
  </div>
</template>

<script>
import Pagina from '@/components/Cpagina.vue'
export default {
    name:'',
    props:[''],
    components:{Pagina},
    data() {
        return {
            ListaConceptos:[],
            ListaAllProyec:[],
            Filtro:{
                TotalItem:0,
                Pagina:1,
                IdCliente:0,
                IdClienteS:0,
                IdProyecto:0,
            },
            IdProyecto:0,
            FechaActual:'',
          
            proyecto:{
                FechaI:'',
                FechaF:'',
            },
            oHead:{
                Title:'YTD',
                isreturn:true,
                Url:'spendplan',
            },
            ListaClientes: [],
            ListaClienteSuc: [],
            ListaProyectos: [],
            IdCliente:'Clientes',
            IdClienteS:'Sucursales',
            IdProyecto:'Proyectos',
            oDatosGenerales:{
                CostoOperacionalAct:0,
                CostoOperacionalPorcAct:0,
                GrossActual:0,
                GrossActualPorc:0,
                NetProfitActual:0,
                NetProfitPorcAct:0,
                ValorVenta:'',
                ValorVentaPorcentaje:0,
                DiasTranscurridos:0,
                PorcentajeDias:0,
            },
            FechaTermino:'',
            TipoVista:1,
            fetching:false,
            fetchingSuc:false,
            fetchingPro:false,
        }
    },methods: {
        async get_conceptos(){
            await this.$http.get(
                'spendpoject/conceptos',
                {
                    params:{IdProyecto:this.IdProyecto}
                }
            ).then( (res) => {
                if(this.IdProyecto>0){
                    this.ListaConceptos =res.data.conceptos;
                    this.FechaActual=res.data.FechaActual;
                    this.FechaTermino=res.data.FechaTermino;
                    this.proyecto=res.data.proyecto;
                    this.oDatosGenerales={
                    CostoOperacionalAct:res.data.CostoOperacionalAct,
                    CostoOperacionalPorcAct:res.data.CostoOperacionalPorcAct,
                    GrossActual:res.data.GrossActual,
                    GrossActualPorc:res.data.GrossActualPorc,
                    NetProfitActual:res.data.NetProfitActual,
                    NetProfitPorcAct:res.data.NetProfitPorcAct,
                    ValorVenta:res.data.ValorVenta,
                    ValorVentaPorcentaje:res.data.ValorVentaPorcentaje,
                    DiasTranscurridos:res.data.DiasTranscurridos,
                    PorcentajeDias:res.data.PorcentajeDias,
                    }
                }else{
                    this.proyecto={Proyecto:'',Fechatermino:'',FechaI:''};
                    this.ListaConceptos ='';
                    this.FechaActual='';
                     this.oDatosGenerales={
                    CostoOperacionalAct:0,
                    CostoOperacionalPorcAct:0,
                    GrossActual:0,
                    GrossActualPorc:0,
                    NetProfitActual:0,
                    NetProfitPorcAct:0,
                    ValorVenta:0,
                    ValorVentaPorcentaje:0,
                    DiasTranscurridos:0,
                    PorcentajeDias:0,
                    }
                }
               
            });
        },
        Get_Clientes(search) {
            
             this.fetching=true; 
            this.$http.get(
                'clientes/get',
                {
                    params:{Nombre:search,Entrada:15,pag:0, RegEstatus:'A'}
                }
            ).then( (res) => {
                this.fetching=false; 
                this.ListaClientes=res.data.data.clientes;
                
            });
        },async Get_ClienteSuc(search, loading,type=1)
        {   
             this.fetchingSuc=true;
            await this.$http.get(
                'clientesucursal/get',
                {
                    params:{Nombre:search,Entrada:15,IdCliente:this.IdCliente, RegEstatus:'A'}
                }
            ).then( (res) => {
                this.fetchingSuc=false;
                this.ListaClienteSuc =res.data.data.clientesucursal;
            });
              
        },async Get_Proyectos(search, loading,type=1)
        {   
            
            this.fetchingPro=true;
            
            await this.$http.get(
                'spendpoject/get',
                {
                    params:{Nombre:search,Entrada:15,IdCliente:this.IdCliente,IdClienteS:this.IdClienteS, RegEstatus:'A'}
                }
            ).then( (res) => {
                this.fetchingPro=false;
                this.ListaProyectos =res.data.proyecto;
            });
        },Get_Substring(value){
            if( value!=undefined){
               value= value.substring(0,18) 
            }
            return value;
        },async list_allproyect(){
            
            if(this.Filtro.IdProyecto=='Proyectos'){
                this.Filtro.IdProyecto=0;
            }
            
            await this.$http.get(
                'spendpoject/listytd',
                {
                    params:{IdProyecto:this.Filtro.IdProyecto,IdCliente:this.Filtro.IdCliente,IdClienteS:this.Filtro.IdClienteS,pag:this.Filtro.Pagina}
                }
            ).then( (res) => {
              
               this.ListaAllProyec=res.data.proyecto;
                this.Filtro.Entrada=res.data.pagination.PageSize;
                this.Filtro.TotalItem=res.data.pagination.TotalItems;
               
            });
        },view_oneproyect(item){
            this.ListaProyectos=[{IdProyecto:item.IdProyecto,Proyecto:item.Folio+" "+item.Proyecto}];
            this.TipoVista=2;
            this.IdProyecto=item.IdProyecto;
            this.get_conceptos();
        },change_typeview(tipo){
            this.IdProyecto='';
            
            this.TipoVista=tipo;
            
        },handleChange(selectedItems){
            this.IdCliente = selectedItems;
        },
        handleChangeSuc(selectedItems){
            this.IdClienteS = selectedItems;
        },
        handleChangePro(selectedItems){
            this.IdProyecto = selectedItems;
        },
    },created() {
        
    },mounted() {
        this.list_allproyect();
    },destroyed() {},
    watch: {
    // whenever question changes, this function will run
    IdCliente: function (NewValue, OldValue) {
        this.IdClienteS='Sucursales';
        this.IdProyecto='Proyectos';
        this.ListaClienteSuc=[];
        this.ListaProyectos=[];

        if(NewValue<=0 ){
             this.ListaClientes=[];
             this.IdCliente='Clientes';
        }else{
            this.IdCliente=NewValue;
            //this.Get_ClienteSuc("",null,2);
            //this.Get_Proyectos("",null,2);
        }
        this.Filtro.IdCliente=this.IdCliente;
        //this.Get_Proyectos("",null,2);
        
        if(this.TipoVista==1){
            this.list_allproyect();
        }
    },
    IdClienteS: function (NewValue, OldValue) {
        this.IdProyecto='Proyectos';
        this.ListaProyectos=[];
        
        if(NewValue<=0 ){
            this.ListaClienteSuc=[];
            this.IdClienteS='Sucursales';
        }else{
            this.IdClienteS=NewValue;    
            //this.Get_Proyectos("",null,2);
        }
        this.Filtro.IdClienteS=this.IdClienteS;
       //this.Get_Proyectos("",null,2);
       
        if(this.TipoVista==1){
            this.list_allproyect();
        }
    }, IdProyecto: function (NewValue, OldValue) {
        this.IdProyecto='Proyectos';
        if(NewValue<=0 ){
            this.IdProyecto='Proyectos';
            this.ListaProyectos=[];
        }else{
            this.IdProyecto=NewValue;    
        }
        this.Filtro.IdProyecto=this.IdProyecto;
        this.get_conceptos();

        if(this.TipoVista==1){
            this.list_allproyect();
        }
       
    }
    
  },
}
</script>


<style scoped>
.ant-select-selection--single{
    height: 39px;
    padding-right: 10px;
}

.col{
    padding-right: 1px;
    padding-left: 1px;
    
    max-width: 340px;
}

</style>