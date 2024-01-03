<template>
  <div class="col-md-12 col-lg-6 mb-2">
        <div class="row">
            <div class="col-12">
                <div class="card card-grafica crad-alto">
                    <b-overlay :show="isOverlay" rounded="sm" spinner-variant="primary" class="h-100">
                    <div class="card-body">
                        <div class="filtro-grafic" id="grafica_002" v-if="isVisible">
                            <div class="row justify-content-start">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <h6 class="title-grafic side-l">Meta de Ventas</h6>
                                    <hr>
                                    <button type="button" class="btn close abs_01" @click="Ocultar()">
                                        <i class="fal fa-times"></i>
                                    </button>
                                </div>

                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <form class="form-inline">
                                        <label class="mr-2">Año</label>
                                        <select @change="Lista"  v-model="Grafica1.Anio" class="form-control form-control-sm mr-2">
                                                <option  v-for="(item, index)  in ListaAnios" :key="index" :value="item">{{item}}</option>
                                        </select>

                                        <label class="mr-2">Mes</label>
                                        <select @change="Lista" v-model="Grafica1.Mes" class="form-control form-control-sm mr-2">
                                            <option v-for="(item, index) in ListaMeses" :key="index" :value="index + 1">{{item}}</option>
                                        </select>

                                        <select @change="listartipo()"  v-model="Grafica1.IdVendedor" class="form-control form-control-sm mr-2">
                                            <option v-for="(item, index) in Listavendedores" :key="index" :value="item.IdUsuario">{{item.NombreTrabajador}}</option>
                                        </select>

                                        <select @change="Lista()" v-model="Grafica1.IdConfigS" class="form-control form-control-sm mr-2">
                                            <option :value="0">Todos</option>
                                            <!---<option  v-for="(item, index)  in Listatipos"  :key="index" :value="item.IdTipoProceso">{{item.Nombre}}</option>-->
                                            <option value="1">Mantenimiento</option>
											<option value="2">Servicio</option>
											<option value="3">Proyecto</option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div><!--Filtro-->

                        <div class="row">
                            <div class="col-10 col-sm-10 col-md-8 col-lg-8">
                                <h6 class="title-grafic side-l">Meta de ventas</h6>
                            </div>
                            <div class="col-2 col-sm-2 col-md-4 col-lg-4 text-right">
                                <button type="button" class="btn-fil-002" title="Filtros" @click="mostrarfiltro()">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <hr>
                            </div>
                        </div> <!--Titulo-->

                        <div class="form-row justify-content-center">
                            <div class="col-md-7 col-lg-7">
                            
                                <div id="apx-02"  v-if="showGrahp">
                                    <apexchart width="100%" height="398" :options="optionsN" :series="series"></apexchart>
                                </div>
                            </div>

                            <div class="col-md-5 col-lg-5">
                                <div class="row">    
                                    <div class="col-md-12 col-lg-12 col-xl-12">
                                        <div class="card card-numer">
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="col mr-2">
                                                        <p class="titulo-dash-01">Oportunidades Abiertas</p>
                                                        <p class="titulo-dash-02">{{OpA}}</p> 
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="icon-dash">
                                                            <i class="fad fa-chart-pie"></i>
                                                         </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12 col-xl-12 mt-4">
                                        <div class="card card-numer">
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="col mr-2">
                                                        <p class="titulo-dash-01">Oportunidades Vendidas</p>
                                                        <p class="titulo-dash-02">{{OpV}}</p>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="icon-dash">
                                                            <i class="fad fa-chart-pie"></i>
                                                        </div>
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
            </div>
        </div>
</div><!--Ventas-->
</template>

<script>
import option from "../../../catalogos/tiposervicio/option.vue";

export default {
	components: { option },
	name: "app",
	data() {
		return {
			OpA: 0,
			OpV: 0,
			sumaV: 0,

			showGrahp: false,
            isVisible: false,
            isOverlay: true,
		
			optionsN: {
				chart: {
					height: 398,
					type: "radialBar",
					width: "100%",
					zoom: {
                        enabled: true,
                        type: 'x',  
                        autoScaleYaxis: false,  
                        zoomedArea: {
                        fill: {
                            color: '#90CAF9',
                            opacity: 1
                        },
                        stroke: {
                            color: '#0D47A1',
                            opacity: 0.4,
                            width: 1
                        }
                        }
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeout',
                        speed: 3000,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    } 
				},
                plotOptions: {
                    radialBar: {
                        inverseOrder: false,
                        startAngle: -135,
                        endAngle: 135,
                        track: {
                        background: '#e7e7e7',
                        startAngle: -135,
                        endAngle: 135,
                        },
                        dataLabels: {
                            name: {
                                show: false,
                            },
                        value: {
                            fontSize: "30px",
                            show: true
                        }
                        },
                        hollow: {
                            margin: 5,
                            size: "38%",
                            image: undefined,
                            imageWidth: 150,
                            imageHeight: 150,
                        }
                    }
                },
                fill: {
                    type: "solid",
                    opacity: 0.9,
                },
                stroke: {
                    lineCap: "butt"
                },
                colors:['#0F3F87', '#FF640A'],
                
                grid: { 
                    padding: {
                        top: -30,
                        right: 0,
                        bottom: 0,
                        left: 0
                    },  
                },

				title: {
					text: '$0',
					align: "center",
					style: {
                        fontSize:  '30px',
                        fontWeight:  700,
                        fontFamily:  'Barlow, sans-serif',
                        color:  '#FF640A'
                    }
				},

                

				
				
			},
			
			Grafica1: {
				Anio: "2019",
				Mes: "1",
				IdVendedor: "",
				IdTipoProceso: 0,
                IdConfigS: ""
			},
		
			ListaAnios: [],
			Listavendedores: [],
			Listatipos: [],

			urlApi: "crmgrafica/get",
			urlApi2: "crmgraficaVendida/get",
			urlApi3: "crmgraficaGlobal/get",
			urlApivendedor: "trabajador/ListTrabRolQuery",
            urlApivendedorNuevo: "vendedores/get",
			urlApiTotal: "crmsuma/suma",

			



            //Media Dona
            series: [],
            optionsChartDona: {
                chart: {
                    height: 398,
                    type: "radialBar",
                    width: '100%',
                    zoom: {
                        enabled: true,
                        type: 'x',  
                        autoScaleYaxis: false,  
                        zoomedArea: {
                        fill: {
                            color: '#90CAF9',
                            opacity: 1
                        },
                        stroke: {
                            color: '#0D47A1',
                            opacity: 0.4,
                            width: 1
                        }
                        }
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeout',
                        speed: 3000,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    } 
                },
                plotOptions: {
                    radialBar: {
                        inverseOrder: false,
                        startAngle: -135,
                        endAngle: 135,
                        track: {
                        background: '#e7e7e7',
                        startAngle: -135,
                        endAngle: 135,
                        },
                        dataLabels: {
                            name: {
                                show: false,
                            },
                        value: {
                            fontSize: "30px",
                            show: true
                        }
                        },
                        hollow: {
                            margin: 5,
                            size: "38%",
                            image: undefined,
                            imageWidth: 150,
                            imageHeight: 150,
                        }
                    }
                },
                fill: {
                type: "solid",
                opacity: 0.9,
                },
                stroke: {
                    lineCap: "butt"
                },
        
                colors:['#0F3F87', '#FF640A'],
                responsive: [
                    {
                    breakpoint: 1000,
                    options: {
                        plotOptions: {
                        bar: {
                            horizontal: false
                        }
                        },
                        legend: {
                        position: "bottom"
                        }
                    }
                    }
                ],
                grid: { 
                    padding: {
                        top: -30,
                        right: 0,
                        bottom: 0,
                        left: 0
                    },  
                },
                title: {
                    text: '$00',
                    align: 'center',
                    style: {
                        fontSize:  '30px',
                        fontWeight:  700,
                        fontFamily:  'Barlow, sans-serif',
                        color:  '#FF640A'
                    }
                }
            },
		};
	},
	created() {
		this.bus.$off("crmOcultar2");
		this.bus.$on("crmOcultar2", data => {
			this.Ocultar(data);
		});
        
		this.Anios();
        this.Vendedores();
	},
	methods: {
		mostrarfiltro() {
			this.isVisible = true;
		},

		/*Ocultar() {
			this.isVisible = false;
		},*/
		async Anios() {
			await this.$http.get("funciones/getanios").then(res => {
				this.ListaAnios     = res.data.ListaAnios;
				this.ListaMeses     = res.data.ListaMeses;

				this.Grafica1.Mes   = res.data.MesActual;
                this.Grafica1.Anio  = res.data.AnioActual;
			});
		},

		/*async  Vendedores() {
			await this.$http
				.get(this.urlApivendedor, {
					params: {
						Nombre: "",
						RegEstatus: "A",
						Entrada: 200,
						pag: 1,
						Rol: JSON.stringify(["Vendedor", "Gerente de ventas"])
					}
				})
				.then(res => {
					//Rol=['Vendedor','Gerente de ventas']"
					this.Listavendedores = res.data.data.lista;
					this.Grafica1.IdVendedor = res.data.data.lista[0].IdTrabajador;
                    this.listartipo();
                       
				});
		},*/

       async Vendedores() {
			await this.$http
				.get(this.urlApivendedorNuevo, {
					params: {
						
					}
				})
				.then(res => {
					//Rol=['Vendedor','Gerente de ventas']"
					this.Listavendedores = res.data.data.Vendedores;
					this.Grafica1.IdVendedor = res.data.data.Vendedores[0].IdUsuario;
                    this.listartipo();
                       
				});
		},

		async listartipo() {
			if (this.Grafica1.IdVendedor > 0) {
				this.Listatipos = [];
				await this.$http
					    .get("crmprocesovendedor/listasig", {
						    params: { IdTrabajador: this.Grafica1.IdVendedor }
					    })
					    .then(res => {
						    this.Listatipos             = res.data.data.asignados;
						    this.Grafica1.IdConfigS = 0;
                            //this.Grafica1.IdConfigS = res.data.data.asignados[0].IdConfigS;
                            this.globalData();
					    });
			}
		},

		Lista() {
			if (parseInt(this.Grafica1.IdConfigS) === 0) {
				this.globalData();
			} else {
				this.$http
					.get("crmgrafica/get", {
						params: {
							IdVendedor: this.Grafica1.IdVendedor,
							Anio: this.Grafica1.Anio,
							Mes: this.Grafica1.Mes,
							IdConfigS: this.Grafica1.IdConfigS
						}
					})
					.then(res => {
						const valores = res.data.data[0].value;
						this.OpA = valores;
						this.Lista2();
					});
			}
		},
		Lista2() {
			if (parseInt(this.Grafica1.IdConfigS) === 0) {
				this.globalData();
			} else {
                this.showGrahp = false;
                this.isOverlay = true;

				this.$http
					.get("crmgraficaVendida/get", {
						params: {
							IdVendedor: this.Grafica1.IdVendedor,
							Anio: this.Grafica1.Anio,
							Mes: this.Grafica1.Mes,
							IdConfigS: this.Grafica1.IdConfigS
						}
					})
					.then(res => {
						
						const valores2 = res.data.data[0].value;
						this.OpV = valores2;
						const suma = res.data.data2[0].value;

						this.sumaV = suma;
                        this.optionsN.title.text = '$0';
                        this.optionsN.title.text = '$'+this.numberto(parseFloat(this.sumaV));

                      

						const totalT = res.data.data3[0];
						var totalcompa = (this.sumaV / totalT) * 100;
						let total = parseFloat(totalcompa).toFixed(1);
						total = isNaN(total) ? 0 : total;
						this.series = [total];


						this.showGrahp = true;
                        this.isOverlay = false;
					}).catch((e) => {
                        this.showGrahp = true;
                        this.isOverlay = false;
                    });
			}
		},

		globalData() {
            this.showGrahp = false;
            this.isOverlay = true;

			this.$http
				.get("crmgraficaGlobal/get", {
					params: {
						IdVendedor: this.Grafica1.IdVendedor,
						Anio: this.Grafica1.Anio,
						Mes: this.Grafica1.Mes,
						IdConfigS: 0
					}
				})
				.then(res => {
					const abiertas = res.data.data[0].value;
					this.OpA = abiertas;
					const cerradas = res.data.data2[0].value;
					this.OpV = cerradas;
					const sumag = res.data.data3[0].value;

					this.sumaV = sumag;
                    this.optionsN.title.text = '$0';
                    this.optionsN.title.text = '$'+this.numberto(parseFloat(this.sumaV));

                    //console.log(this.optionsChartDona.title.text);


					const totalG = res.data.data4;
					var totalcompa = (this.sumaV / totalG) * 100;
					let total = parseFloat(totalcompa).toFixed(1);
					total = isNaN(total) ? 0 : total;
					this.series = [total];

                    this.showGrahp = true;
                    this.isOverlay = false;
				}).catch((e) => {
                    this.showGrahp = true;
                    this.isOverlay = false;
                });
		},

		Ocultar(data) {
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
	}
};
</script>
