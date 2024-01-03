<template>
	<div class="col-md-12 col-lg-6 mb-2">
		 <b-overlay :show="isOverlay" rounded="sm" spinner-variant="primary" class="h-100">
			<div class="row">
				<div class="col-12">
					<div class="card card-grafica h-100">
						<div class="card-body">
							<div class="filtro-grafic" id="grafica_002" v-if="isVisible">
								<div class="row justify-content-start">
									<div class="col-12 col-md-12 col-lg-12 col-xl-12">
										<form class="form-inline">
											<label class="mr-2">Año</label>
											<select @change="Lista" v-model="Grafica1.Anio" class="form-control form-control-sm mr-2">
												<option v-for="(item, index) in ListaAnios" :key="index" :value="item">{{item}}</option>
											</select>
											<label class="mr-2">Mes</label>
											<select @change="Lista" v-model="Grafica1.Mes" class="form-control form-control-sm">
												<option value="13">Acumulado</option>
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
										<button type="button" class="btn close abs_01" @click="Ocultar()"><i class="fal fa-times"></i></button>
									</div>
								</div>
							</div>
							<!--Filtro-->
							<div class="row">
								<div class="col-10 col-sm-10 col-md-8 col-lg-8">
									<h6 class="title-grafic side-l">Facturación Por Producto</h6>
								</div>
								<div class="col-2 col-sm-2 col-md-4 col-lg-4 text-right">
									<button type="button" class="btn-fil-002" title="Filtros"
										@click="mostrarfiltro()"><i
											class="fas fa-filter"></i></button>
								</div>
								<div class="col-12 col-sm-12 col-md-12 col-lg-12">
									<hr>
								</div>
							</div>
							<!--Titulo-->
							<div id="apx-02">
								<apexchart width="100%" height ="190%" :options="options" :series="series" ></apexchart>
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
  name: 'FacturacionTipoProd',
  data() {
    return {
        type: "bar3d",
        renderAt: "chart-3d",
        width: '100%',
        height: '300',
        dataFormat: "json",
        dataSource: '',
        //urlApi:'finanzasgraf/getfact',
        urlApi:'finanzasFactura/get',
        ListaAnios:[],
        Grafica1:{

            Anio:'2019',
            Mes:''
        },
        Facturacion:0,
        isVisible:false,


        options: {
            chart: {
                type: 'bar',
                height: 280,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '80%',
                    distributed: true,
                    dataLabels: {
                        position: 'top',
                    },
                },
            },
            dataLabels: {
                enabled: true,
                offsetX: 100,
                formatter: (value) => {
                    return "$" + this.numberto(value)
                    
                },
                style: {
                    fontSize: '13px',
                    colors: ['#000']
                }
            },
            stroke: {
                show: true,
                width: 1,
                colors: ['#fff']
            },
            tooltip: {
                custom: function ({
                    series,
                    seriesIndex,
                    dataPointIndex,
                    w
                }) {
                    return (
                        '<div class="arrow_box">' +
                        "<span>" +
                        w.globals.labels[dataPointIndex] +
                        ": " + "$"+
                        series[seriesIndex][dataPointIndex].toFixed(1)+
                        "</span>" +
                        "</div>"
                    );
                }
            },
            
            xaxis: {
                categories: ['Total','Mantenimiento', 'Servicio', 'Proyecto'],
                labels: {
                    show: true,
                    formatter: (value) => {
                        return "$" + this.numberto(value)
                    
                    },
                    style: {
                        colors: ['#0F3F87'],
                        fontSize: '13px',
                        fontFamily: 'Barlow, sans-serif',
                        fontWeight: 400,
                        cssClass: 'apexcharts-xaxis-label',
                    }
                },
                
            },
            colors: ['#FF640A','#0F3F87','#216CB8','#297DCA'],

            


            yaxis: [{
                labels: {
                    style: {
                        colors: ['#0F3F87'],
                        fontSize: '13px',
                        fontFamily: 'Barlow, sans-serif',
                        fontWeight: 400,
                    }
                }
            }]
        },
        series: [],
		isOverlay: true,
    }
  },created() {


    this.bus.$off('Ocultar2');
    this.bus.$on('Ocultar2',(data)=> 
    {
    this.Ocultar2(data);

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

    async Anios() {
		 

      await this.$http.get(
          'funciones/getanios',
      ).then( (res) => {
		 

          this.ListaAnios =res.data.ListaAnios;
          this.Grafica1.Anio = res.data.AnioActual;
          this.Grafica1.Mes = 13;
          this.Lista();
         
      }).catch((e) => {
		  
	  });
        
    },
    async Lista()
      {
          this.isOverlay = true;
          await this.$http.get(
              this.urlApi,
              {
                  params:{Mes:this.Grafica1.Mes,Anio:this.Grafica1.Anio}
              }
          ).then( (res) => {
                    this.isOverlay = false;
    
            const dataArray = res.data.dataapex;

            let newArray = [dataArray[3],dataArray[0],dataArray[1],dataArray[2]]

            this.series = [{
                data: newArray,
            }];

              
          }).catch((e) => {
              this.isOverlay = false;
          });
            
      },
      Ocultar2(data){
          this.isVisible = data;
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
      }
  },
}
</script>
