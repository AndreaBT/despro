<template>
    <div class="col-md-12 col-lg-6 mt-1">
        <b-overlay :show="isOverlay" rounded="sm" spinner-variant="primary">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="contenedor-dhas">
                                <div class="filtro-grafic alto" id="grafica_005" v-if="isVisible">
                                    <form class="form-inline mb-2">
                                            <label class="mr-2">Año</label>
                                            <select @change="Lista" v-model="Grafica1.Anio" class="form-control form-control-sm mr-2">
                                                <option v-for="(item, index) in ListaAnios" :key="index" :value="item">{{item}}</option>
                                            </select>
                                            <label class="mr-2">Mes</label>
                                            <select @change="Lista" v-model="Grafica1.Mes" class="form-control form-control-sm">
                                                <option value="1">Enero</option>
                                                <option value="2">Febrero</option>
                                                <option value="3">Marzo</option>
                                                <option value="4">Abril</option>
                                                <option value="5">Mayo</option>
                                                <option value="6">Junio</option>
                                                <option value="7">Julio</option>
                                                <option value="8">Agosto</option>
                                                <option value="9">Septiembre</option>
                                                <option value="10">Octubre</option>
                                                <option value="11">Noviembre</option>
                                                <option value="12">Diciembre</option>
                                            </select>
                                    </form>
                                    <button type="button" class="btn close abs_01 mt-2" @click="Ocultar()"><i class="fal fa-times"></i></button>
                                </div><!--Filtro-->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-10 col-sm-10 col-md-8 col-lg-8">
                            <h6 class="title-grafic side-l">Horas Facturadas y No Facturadas</h6>
                        </div>
                        <div class="col-2 col-sm-2 col-md-4 col-lg-4 text-right">
                            <button type="button" class="btn-fil-002" title="Filtros" @click="mostrarfiltro()"><i class="fas fa-filter"></i></button>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <hr>
                        </div>
                    </div><!--Titulo-->
                    <div class="form-row">
                        <div class="col-md-4 col-lg-4">
                            <div class="card border-left-primary card-small">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="font-weight-bold text-fin-03 mb-1"><span class="text-xs">Facturadas</span></div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{Math.round(Horas.Facturadas)}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon-fin-03">
                                                <i class="fas fa-file-invoice-dollar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="card border-left-success card-small">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success mb-1">
                                                No Facturadas</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{Math.round(Horas.NoFacturadas)}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon-fin-02">
                                                <i class="fas fa-file-invoice-dollar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="card border-left-info card-small">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info mb-1">Facturada Por Persona</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{Math.round(Horas.FacXPersona)}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon-fin">
                                                <i class="fas  fa-file-user"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </b-overlay>
    </div>
</template>

<script>
export default {
  data() {
    return {
      //urlApi:'finanzasgraf/horasfact',
        isOverlay: true,
      urlApi:'finanzasPlanFactura/get',
      Horas:{
        FacXPersona: 0,
        Facturadas: 0,
        NoFacturadas: 0
      },
      ListaAnios:[],
      Grafica1:{

          Anio:'2019',
          Mes:''
      },

      isVisible:false
    }
  },created() {
    this.bus.$off('Ocultar6');
    this.bus.$on('Ocultar6',(data)=> 
    {
    this.Ocultar6(data);

    });


    this.Anios();
  },methods: {
        mostrarfiltro()
        {
            this.isVisible = true;
        },

        Ocultar(){
            this.isVisible = false;
        },
    async Anios()
      {
      await this.$http.get(
          'funciones/getanios',
      ).then( (res) => {
          this.ListaAnios =res.data.ListaAnios;
          this.Grafica1.Anio = res.data.AnioActual;
          this.Grafica1.Mes = 1;
          this.Lista();
      });
        
    },
    async Lista(){
        this.isOverlay = true;
          await this.$http.get(
              this.urlApi,
              {
                  params:{Mes:this.Grafica1.Mes,Anio:this.Grafica1.Anio}
              }
          ).then( (res) => {
              this.isOverlay = false;
                //console.log(res.data.data);
                if(res.data.data != undefined){
                    this.Horas = res.data.data;
                }
                
              
          }).catch((e) => {
              this.isOverlay = false;
          });
            
      },
      Ocultar6(data){
          this.isVisible = data;
      }
  },
}
</script>

<style>

</style>