<template>
    <div class="col-md-12 col-lg-6 mb-3">
		<b-overlay :show="isOverlay" rounded="sm" spinner-variant="primary" class="h-100">
			<div class="row">
				<div class="col-12">
					<div class="card card-grafica h-100">
						<div class="card-body ">
							<div class="row">
								<div class="col-12 col-md-12 col-lg-12">
									<h6 class="title-grafic side-l">Flujo de Caja</h6>
								</div>
								<div class="col-12 col-sm-12 col-md-12 col-lg-12">
									<hr />
								</div>
							</div>
							<!--Titulo-->
							<div class="row">
								<div class="col-12">
									<div id="apx-06" >
										<apexchart 
											type="line" 
											height="270" 
											:options="options" 
											:series="series"></apexchart>
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
	name: "GraficaGlobal",
	data() {
		return {
			isVisible:false,
          options: {
				chart: {
					type: "area",
					height: 265,
					toolbar: {
						show: false
					}
				},
				dataLabels: {
					enabled: false
				},
				stroke: {
					curve: "straight"
				},
				xaxis: {
					type: "category",
					categories: [
						"Ene",
						"Feb",
						"Mar",
						"Abr",
						"May",
						"Jun",
						"Jul",
						"Ago",
						"Sep",
						"Oct",
						"Nov",
						"Dic"
					],
					axisBorder: {
						show: false
					},
					axisTicks: {
						show: false
					}
				},
				yaxis: {
					tickAmount: 4,
					floating: false,
					labels: {
						style: {
							colors: "#8e8da4"
						},
						offsetY: -7,
						offsetX: 0
					},
					axisBorder: {
						show: false
					},
					axisTicks: {
						show: false
					},
					labels: {
						formatter: value => {
							return "$" + this.numberto(value);
						}
					}
				},
				markers: {
					size: 5
				},
				fill: {
					opacity: 0.5
				},
				tooltip: {
					x: {
						format: "yyyy"
					},
					fixed: {
						enabled: false,
						position: "topRight"
					}
				},
				colors: ["#0F3F87", "#FF640A"],
				grid: {
					yaxis: {
						lines: {
							offsetX: -30
						}
					},
					padding: {
						left: 20
					}
				}
			},
          series:[],
		  isOverlay: true,

          UrlApi: "ctaporcobrar/getGraficaEstimadoGlobal",
          
		};
	},

	methods: {
		async Lista(){
			 this.isOverlay = true;
      await this.$http
        .get(this.UrlApi,{
          params:{}
        }).then(res=>{
			this.isOverlay = false;
          const FlujoCajaEstimado = res.data.data.FlujoCajaEstimado;
          const FlujoCajaActual = res.data.data.FlujoCajaActual;
          this.series=[
            {
              name: "Estimado",
              data:FlujoCajaEstimado
            },
            {
              name: "Actual",
              data:FlujoCajaActual
            }
           
          ];
        }).catch((e) => {
              this.isOverlay = false;
          });
    },
    numberto(num) {
			//value = value.toFixed(0);
			let fixed = 0;
			if (num === null) {
				return null;
			} // terminate early
			if (num === 0) {
				return "0";
			} // terminate early
			fixed = !fixed || fixed < 0 ? 0 : fixed; // number of decimal places to show
			var b = num.toPrecision(2).split("e"), // get power
				k = b.length === 1 ? 0 : Math.floor(Math.min(b[1].slice(1), 14) / 3), // floor at decimals, ceiling at trillions
				c =
					k < 1
						? num.toFixed(0 + fixed)
						: (num / Math.pow(10, k * 3)).toFixed(1 + fixed), // divide by power
				d = c < 0 ? c : Math.abs(c), // enforce -0 is 0
				e = d + ["", " K", " M", " B", " T"][k]; // append power
			return e;
		},

	},

	created() {
		this.Lista();
	}
};
</script>
