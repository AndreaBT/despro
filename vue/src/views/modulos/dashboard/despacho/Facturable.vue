<template>
    <div class="col-md-8 col-lg-6 col-xl-6 mb-2">
		<b-overlay :show="isOverlay" rounded="sm" spinner-variant="primary" class="h-100">
			<div class="row">
				<div class="col-12">
					<div class="card card-grafica h-100">
						<div class="card-body">
							<div class="filtro-grafic" id="grafica_002" v-if="isVisible">
								<div class="row justify-content-start">
									<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<form class="form-inline">
											<v-date-picker
												mode='range'
												v-model='rangeDate'
												@input="get_dataSource"
												:input-props='{
													class: "form-control form-control mb-2 mr-1",
													placeholder: "Selecciona un rango de fecha para buscar",
													readonly: true
												}'
											/>
										</form>
										<button type="button" class="btn close abs_01"
												@click="Ocultar()"><i
												class="fal fa-times"></i></button>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-10 col-sm-10 col-md-8 col-lg-8">
									<h6 class="title-grafic side-l">Servicios Facturables y No Facturables</h6>
								</div>
								<div class="col-2 col-sm-2 col-md-4 col-lg-4 text-right">
									<button type="button" class="btn-fil-002" title="Filtros" @click="mostrarfiltro()">
										<i class="fas fa-filter"></i>
									</button>
								</div>
								<div class="col-12 col-sm-12 col-md-12 col-lg-12">
									<hr>
								</div>
							</div>
							<div id="apx-01">
								<apexchart width="100%" height ="306" :options="optionsf" :series="optionsf.series"></apexchart>
							</div>
						</div>
					</div>
				</div>
			</div>
	   </b-overlay>
  </div>


</template>

<script>

const dataSource = {
    "chart": {
  "pieRadius": "130",
      "baseFontSize": "14",
       "subCaption": "",
       "paletteColors": "#27ae60,#c0392b",
       "bgColor": "#ffffff",
       "showBorder": "0",
       "use3DLighting": "0",
       "showShadow": "0",
       "enableSmartLabels": "0",
       "startingAngle": "10",
       "showPercentValues": "1",
       "showPercentInTooltip": "0",
       "decimals": "1",
       "captionFontSize": "14",
       "subcaptionFontSize": "14",
       "subcaptionFontBold": "0",
       "toolTipColor": "#ffffff",
       "toolTipBorderThickness": "0",
       "toolTipBgColor": "#000000",
       "toolTipBgAlpha": "80",
       "toolTipBorderRadius": "2",
       "toolTipPadding": "2",
       "showHoverEffect":"1",
       "showLegend": "1",
       "legendBgColor": "#ffffff",
       "legendBorderAlpha": '0',
       "legendShadow": '0',
       "legendItemFontSize": '14',
       "legendItemFontColor": '#666666',
        "theme": "fint",
        "labelDistance": "0",
        "startingAngle": "120",
    },
    "data": [{
        "label": "Facturable",
        "value": "300000"
    }, {
        "label": "No facturable",
        "value": "230000"
    }]
};

export default {
  name: 'ServiciosFacturables',
  data() {
    return {

      optionsf : {
        series: [0, 0],
        chart: {
            height: 300,
            type: 'pie',
        },
        labels: ['No Facturable', 'Facturable'],
        colors:['#FF640A', '#0F3F87'],
        grid: {
            show: true,
            padding: {
                top: -10,
                right: -10,
                bottom: -10,
                left: -10
            },  
        },
        plotOptions: {
            pie: {
              customScale: 1,
              size: 200
            }
          },
          legend: {
            position: 'right',
            fontSize: '14px',
            fontFamily: 'Barlow, sans-serif',
            fontWeight: 600
          },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    },


        isVisible:false,
        width: '100%',
        height: '215',
        type: 'pie3d',
        dataFormat: 'json',
        dataSource: dataSource,
        rangeDate:{},
		isOverlay:true,
    }
  },methods: {
    mostrarfiltro()
      {
          this.isVisible = true;
      },

      Ocultar(){
          this.isVisible = false;
      },
    verificarnumero(value){

      let val = value;
      if(value < 10){
        val ='0'+value;
      }
      return val;
    },
    async get_dataSource(){
		this.isOverlay = true;
      await this.$http.get(
        //'dashboard/servfac',
        'despachoonefactura/get',
        {
          params:{Fecha_I:this.rangeDate.start,Fecha_F:this.rangeDate.end}
        }
      ).then( (res) => {
		  this.isOverlay = false;

          this.dataSource.data[0].value=res.data.data.Facturable;
          this.dataSource.data[1].value=res.data.data.NoFacturable;
          //this.optionsf.series = [res.data.data.Facturable,res.data.data.NoFacturable];

          let optionsf = {
            series: [res.data.data.Facturable,res.data.data.NoFacturable],
            chart: {
                height: 300,
                type: 'pie',
            },
            labels: ['Facturable','No Facturable'],
            colors:[ '#0F3F87','#FF640A'],
            grid: {
                show: true,
                padding: {
                    top: -10,
                    right: -10,
                    bottom: -10,
                    left: -10
                },  
            },
            plotOptions: {
                pie: {
                  customScale: 1,
                  size: 200
                }
              },
              legend: {
                position: 'right',
                fontSize: '14px',
                fontFamily: 'Barlow, sans-serif',
                fontWeight: 600
              },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        this.optionsf = optionsf;

          var date = new Date(this.rangeDate.start);
          var year=date.getFullYear();
          var month=date.getMonth()+1 //getMonth is zero based;
          var day=date.getDate();
          var formatted=this.verificarnumero(day)+"-"+this.verificarnumero(month)+"-"+year;



          var datef = new Date(this.rangeDate.end);
          var yearf=datef.getFullYear();
          var monthf=datef.getMonth()+1 //getMonth is zero based;
          var dayf=datef.getDate();
          var formattedf=this.verificarnumero(dayf)+"-"+this.verificarnumero(monthf)+"-"+yearf;


          //this.dataSource.chart.caption = "Fecha: de " + formatted + " a " + formattedf;
      }).catch((e) => {
		  this.isOverlay = false;
	  });
    
    }
  },created() {
    var date = new Date(), y = date.getFullYear(), m = date.getMonth();
    var firstDay = new Date(y, m, 1);
    var lastDay = new Date(y, m + 1, 0);
    this.rangeDate={
      start:firstDay,
      end:lastDay
    }
    
  },mounted() {
    this.get_dataSource();
  },
}
</script>
