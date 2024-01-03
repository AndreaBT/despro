<template>
<!--
  <div class="row">
    <div class="col-md-6 col-lg-6">
      <div class="row intro" style="display: block">
        <div class="col-12">
          <form class="form-inline">
            <select
              id="Trabajador"
              @change="get_dataSource"
              class="form-control form-control-sm mb-2 mr-1 w-02"
              v-model="IndexTrabajador"
            >
              <option
                selected
                v-for="(item, index) in ListaTrabajadores"
                :key="index"
                :value="index"
              >
                {{ item.Nombre }}
              </option>
            </select>
            &nbsp;
            <v-date-picker
              mode="range"
              v-model="rangeDate"
              @input="get_dataSource"
              :input-props="{
                class: 'form-control form-control-sm mb-2 w-03 calendar',
                placeholder: 'Selecciona un rango de fecha para buscar',
                readonly: true,
              }"
            />
          </form>
        </div>
      </div>

      <div class="card card-grafica">
        <div class="card-header">
          <h1 class="card-title">Horas Productivas Semanales</h1>
        </div>
        <div class="card-body">
          <div id="chart-containergau">
            <fusioncharts
              :type="type"
              :width="width"
              :height="height"
              :dataFormat="dataFormat"
              :dataSource="dataSource"
            ></fusioncharts>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-md-6 col-lg-6">
      <div class="row intro" style="display: block">
        <div class="col-12">
          <form class="form-inline">
            <select
              @change="get_dataSource"
              class="form-control form-control-sm mb-2 mr-2 w-02"
              v-model="IndexTrabajador2"
            >
              
              <option
                v-for="(item, index) in ListaTrabajadores"
                :key="index"
                :value="index"
              >
                {{ item.Nombre }}
              </option>
            </select>
          </form>
        </div>
      </div>

      <div class="card card-grafica">
        <div class="card-header">
          <h1 class="card-title">Horas Productivas Semanales</h1>
        </div>
        <div class="card-body">
          <div id="chart-containergau2">
            <fusioncharts
              :type="type"
              :width="width"
              :height="height"
              :dataFormat="dataFormat"
              :dataSource="dataSourcetwo"
            ></fusioncharts>
          </div>
        </div>
      </div>
    </div>
  </div>
  -->
  <div class="col-md-12 col-lg-6 mb-2">
        <div class="card card-grafica grafica-fize-01 h-100">
          <div class="card-body">
            <div class="filtro-grafic" id="grafica_001" v-if="isVisible">
              <div class="row justify-content-start">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                  <form class="form-inline">

                    <select
                      id="Trabajador"
                      @change="get_dataSource"
                      class="form-control form-control-sm  mr-2 w-01"
                      v-model="IndexTrabajador"
                    >
                      <option
                        selected
                        v-for="(item, index) in ListaTrabajadores"
                        :key="index"
                        :value="index"
                      >
                        {{ item.Nombre }}
                      </option>
                    </select>
                    
                    <v-date-picker
                      mode="range"
                      v-model="rangeDate"
                      @input="get_dataSource"
                      :input-props="{
                        class:'form-control form-control-sm mr-2 calendar',
                        placeholder: 'Selecciona un rango de fecha para buscar',
                        readonly: true,
                      }"
                    />

                    <select
                    @change="get_dataSource"
                   class="form-control form-control-sm  mr-2 w-01"
                    v-model="IndexTrabajador2"
                    >

                    <option
                    v-for="(item, index) in ListaTrabajadores"
                    :key="index"
                    :value="index"
                    >
                    {{ item.Nombre }}
                    </option>
                    </select>
                  </form>
                  <button type="button" class="btn close abs_01" @click="Ocultar()"><i class="fal fa-times"></i></button>
                </div>
              </div>
            </div><!--Filtro-->
            <div class="row">
              <div class="col-10 col-sm-10 col-md-8 col-lg-8">
                <h6 class="title-grafic side-l">Horas Productivas Semanales</h6>
              </div>
              <div class="col-2 col-sm-2 col-md-4 col-lg-4 text-right">
                <button type="button" class="btn-fil-002" title="Filtros" @click="mostrarfiltro()"><i class="fas fa-filter"></i></button>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <hr>
              </div>
            </div><!--Titulo-->
            <div class="row">
              <div class="col-md-6 col-lg-6">
                 <h5 class="text-center tile-grafic-02">Individual</h5>
                <div id="grafica01">
                  <fusioncharts
                  :type="type"
                  :width="width"
                  :height="height"
                  :dataFormat="dataFormat"
                  :dataSource="dataSource"
                ></fusioncharts>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 ">
                <h5 class="text-center tile-grafic-02">Comparativo</h5>
                <div id="grafica02">
                  <fusioncharts
                  :type="type"
                  :width="width"
                  :height="height"
                  :dataFormat="dataFormat"
                  :dataSource="dataSourcetwo"
                  ></fusioncharts>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
</template>

<script>



const dataSource = {
  chart: {
    "lowerLimit": "0",
         "upperLimit": "100",
         "gaugeFillMix": "{dark-40},{light-40},{dark-20}",
         "numberSuffix": "hr",
         "gaugeOuterRadius": "105",
         "gaugeInnerRadius": "70",

         "pivotRadius": "7",
         "pivotBorderColor": "#333333",
         "pivotBorderAlpha": "100",
         "pivotFillMix": " ",
         "pivotFillColor": "#FFFFFF",
         "pivotFillAlpha": "100",
         "pivotFillType": "linear",
         "showPivotBorder": "1",
         "showValue": "1",
         "valueBelowPivot": "0",
         
         
         "showborder": "0",
         "bgColor": "#FFFFFF",
         "BaseFont": "Arial",
         "BaseFontSize": "11",
         "BaseFontWeight:":"bold",
         "labelFontBold": "1",
         "tickValueStep": "10",
         "gaugeBorderThickness": "1",
         "ticksBelowGauge": "0",
         "adjustTM": "0",
         "majorTMNumber": "5",
         "minorTMNumber": "2",
         "gaugeFillRatio": "5",
         "autoAlignTickValues": "1",
         "manageValueOverLapping": "1",
         "tickValueDistance": "5",
         "placeTicksInside":"0",
         "placeValuesInside": "1",
         "theme": "fint"
  },
  colorRange: {
    "color": [{
        "minValue": "0",
        "maxValue": "50",
        "label": "Low",
        "code": "#e74c3c"
      },
      {
        "minValue": "50",
        "maxValue": "75",
        "label": "Moderate",
        "code": "#f1c40f"
      },
      {
        "minValue": "75",
        "maxValue": "100",
        "label": "High",
        "code": "#2ecc71"
      }
    ]
  },
  dials: {
    dial: [
      {
        value: "32",
        radius: "120",
        baseWidth: "15",
        tooltext: "Horas trabajdas: $value",
        rearExtension: "0",
        bgColor: "#1D6ABA",
      },
    ],
  },
  trendpoints: {
    point: [
      {
        startvalue: "30",
        displayvalue: "30 hr",
        thickness: "2",
        color: "#E15A26",
        usemarker: "1",
        markerbordercolor: "#E15A26",
        markertooltext: "30 hr",
      },
      {
        startvalue: "35",
        thickness: "2",
        color: "#E15A26",
        usemarker: "1",
        markerbordercolor: "#E15A26",
        markertooltext: "35 hr",
        displayvalue: "35 hr",
      },
      {
        startvalue: "45",
        displayvalue: "45 hr",
        thickness: "2",
        color: "#E15A26",
        usemarker: "1",
        markerbordercolor: "#E15A26",
        markertooltext: "45 hr",
      },
    ],
  },
};

const dataSourcetwo = {
  chart: {
     "lowerLimit": "0",
         "upperLimit": "100",
         "gaugeFillMix": "{dark-40},{light-40},{dark-20}",
         "numberSuffix": "hr",
         "gaugeOuterRadius": "105",
         "gaugeInnerRadius": "70",

         "pivotRadius": "7",
         "pivotBorderColor": "#333333",
         "pivotBorderAlpha": "100",
         "pivotFillMix": " ",
         "pivotFillColor": "#FFFFFF",
         "pivotFillAlpha": "100",
         "pivotFillType": "linear",
         "showPivotBorder": "1",
         "valueBelowPivot": "1",

         "showborder": "0",
         "bgColor": "#FFFFFF",
         "BaseFont": "Arial",
         "BaseFontSize": "11",
         "BaseFontWeight:":"bold",
         "labelFontBold": "1",
         "tickValueStep": "10",
         "gaugeBorderThickness": "1",
         "ticksBelowGauge": "0",
         "adjustTM": "0",
         "majorTMNumber": "5",
         "minorTMNumber": "2",
         "gaugeFillRatio": "5",
         "autoAlignTickValues": "1",
         "manageValueOverLapping": "1",
         "tickValueDistance": "5",
         "placeTicksInside":"0",
         "placeValuesInside": "1",
         "theme": "fint"
  },
  colorRange: {
         "color": [{
             "minValue": "0",
             "maxValue": "50",
             "label": "Low",
             "code": "#e74c3c"
           },
           {
             "minValue": "50",
             "maxValue": "75",
             "label": "Moderate",
             "code": "#f1c40f"
           },
           {
             "minValue": "75",
             "maxValue": "100",
             "label": "High",
             "code": "#2ecc71"
           }
         ]
  },
  dials: {
    dial: [
      {
        value: "32",
        radius: "120",
        baseWidth: "15",
        tooltext: "Horas trabajdas: $value",
        rearExtension: "0",
        bgColor: "#1D6ABA",
      },
    ],
  },
  trendpoints: {
    point: [
      {
        startvalue: "30",
        displayvalue: "30 hr",
        thickness: "2",
        color: "#e74c3c",
        usemarker: "1",
        markerbordercolor: "#e74c3c",
        markertooltext: "30 hr",
      },
      {
        startvalue: "35",
        thickness: "2",
        color: "#f1c40f",
        usemarker: "1",
        markerbordercolor: "#f1c40f",
        markertooltext: "35 hr",
        displayvalue: "35 hr",
      },
      {
        startvalue: "45",
        displayvalue: "45 hr",
        thickness: "2",
        color: "#2ecc71",
        usemarker: "1",
        markerbordercolor: "#2ecc71",
        markertooltext: "45 hr",
      },
    ],
  },
};

export default {
  name: "app",
  data() {
    return {
      isVisible:false,
      width: '100%',
      height: '200',
      type: "angulargauge",
      dataFormat: "json",
      dataSource: dataSource,
      dataSourcetwo: dataSourcetwo,
      ListaTrabajadores: [],
      rangeDate: {},
      IndexTrabajador: 0,
      IndexTrabajador2:0,
      Trabajador: {},
      Trabajador2: {},

    };
  },
  methods: {
    mostrarfiltro()
      {
          this.isVisible = true;
      },

      Ocultar(){
          this.isVisible = false;
      },
    verificarnumero(value) {
      let val = value;
      if (value < 10) {
        val = "0" + value;
      }
      return val;
    },

    async get_dataSource() {
      if (this.ListaTrabajadores.length > 0) {
        this.Trabajador = this.ListaTrabajadores[this.IndexTrabajador];
        this.Trabajador2 = this.ListaTrabajadores[this.IndexTrabajador2];

  

        await this.$http
          .get(
            //"dashboard/horasp",
            "despachoone/get",
            {
            params: {
              IdTrabajador: this.Trabajador.IdTrabajador,
              HorasTS: this.Trabajador.HorasTS,
              HorasPS: this.Trabajador.HorasPS,
              numTrabajadores: this.ListaTrabajadores.length,
              IdTrabajador2:  this.Trabajador2.IdTrabajador,
              HorasTS2:this.Trabajador2.HorasTS,
              HorasPS2: this.Trabajador2.HorasPS,
              Fecha_I: this.rangeDate.start,
              Fecha_F: this.rangeDate.end,
            },
          })
          .then((res) => {
            this.create_FirstGraph(res);
            this.create_SecondGraph(res);
          });
      }
    },
    create_FirstGraph(res) {
      
      /*
    "HoraTrabajoSemanal": 50,
    "IdTrabajador": "968",
    "HoraPSmenos": -5,
    "horasT": 0,
      */
      this.dataSource.chart.upperLimit = res.data.data.HoraTrabajoSemanal;

      this.dataSource.colorRange.color[0].minValue = 0;
      this.dataSource.colorRange.color[0].maxValue =res.data.data.HoraPSmenos;

      this.dataSource.colorRange.color[1].minValue =res.data.data.HoraPSmenos;
      this.dataSource.colorRange.color[1].maxValue = res.data.data.HoraPS;

      this.dataSource.colorRange.color[2].minValue = res.data.data.HoraPS;
      this.dataSource.colorRange.color[2].maxValue =res.data.data.HoraTrabajoSemanal;

      this.dataSource.dials.dial[0].value = parseFloat(res.data.data.horasT);

      this.dataSource.trendpoints.point[0].startValue =res.data.data.HoraPSmenos;
      this.dataSource.trendpoints.point[0].markertooltext =res.data.data.HoraPSmenos + "hr";
      this.dataSource.trendpoints.point[0].displayvalue =res.data.data.HoraPSmenos + "hr";

      this.dataSource.trendpoints.point[1].startValue =parseFloat(res.data.data.HoraPSmenos) + 5;
      this.dataSource.trendpoints.point[1].markertooltext =parseFloat(res.data.data.HoraPSmenos) + 5 + "hr";
      this.dataSource.trendpoints.point[1].displayvalue =parseFloat(res.data.data.HoraPSmenos) + 5 + "hr";

      this.dataSource.trendpoints.point[2].startValue =res.data.data.horasT;
      this.dataSource.trendpoints.point[2].markertooltext =parseFloat(res.data.data.horasT) + "hr";
      this.dataSource.trendpoints.point[2].displayvalue =parseFloat(res.data.data.horasT) + "hr";

      //this.dataSource.chart.caption = "Empleado: " + this.ListaTrabajadores[this.IndexTrabajador].Nombre;

      var date = new Date(this.rangeDate.start);
      var year = date.getFullYear();
      var month = date.getMonth() + 1; //getMonth is zero based;
      var day = date.getDate();
      var formatted =
        this.verificarnumero(day) +
        "-" +
        this.verificarnumero(month) +
        "-" +
        year;

      var datef = new Date(this.rangeDate.end);
      var yearf = datef.getFullYear();
      var monthf = datef.getMonth() + 1; //getMonth is zero based;
      var dayf = datef.getDate();
      var formattedf =
        this.verificarnumero(dayf) +
        "-" +
        this.verificarnumero(monthf) +
        "-" +
        yearf;

      //this.dataSource.chart.subCaption ="Fecha: de " + formatted + " a " + formattedf;
    },
    create_SecondGraph(res) {
      this.dataSourcetwo.chart.upperLimit =
        res.data.data.HoraTrabajoSemanal2;

      this.dataSourcetwo.colorRange.color[0].minValue = 0;
      this.dataSourcetwo.colorRange.color[0].maxValue =
        res.data.data.HoraPSmenos2;

      this.dataSourcetwo.colorRange.color[1].minValue =res.data.data.HoraPSmenos2;
      this.dataSourcetwo.colorRange.color[1].maxValue =res.data.data.HoraPS2;

      this.dataSourcetwo.colorRange.color[2].minValue =res.data.data.HoraPS2;
      this.dataSourcetwo.colorRange.color[2].maxValue = res.data.data.HoraTrabajoSemanal2;

      this.dataSourcetwo.dials.dial[0].value = parseFloat(res.data.data.horasT2);

      this.dataSourcetwo.trendpoints.point[0].startValue =res.data.data.HoraPSmenos2;
      this.dataSourcetwo.trendpoints.point[0].markertooltext =res.data.data.HoraPSmenos2 + "hr";
      this.dataSourcetwo.trendpoints.point[0].displayvalue =res.data.data.HoraPSmenos2 + "hr";

      this.dataSourcetwo.trendpoints.point[1].startValue =parseFloat(res.data.data.HoraPSmenos2) + 5;
      this.dataSourcetwo.trendpoints.point[1].markertooltext =parseFloat(res.data.data.HoraPSmenos2) + 5 + "hr";
      this.dataSourcetwo.trendpoints.point[1].displayvalue =parseFloat(res.data.data.HoraPSmenos2) + 5 + "hr";

      this.dataSourcetwo.trendpoints.point[2].startValue =res.data.data.horasT2;
      this.dataSourcetwo.trendpoints.point[2].markertooltext =parseFloat(res.data.data.horasT2) + "hr";
      this.dataSourcetwo.trendpoints.point[2].displayvalue =parseFloat(res.data.data.horasT2) + "hr";

      //this.dataSourcetwo.chart.subCaption = this.Trabajador2.Nombre;
    },
    get_listtrabajador() {
      this.$http
        .get("trabajador/get", {
          params: { Rol: "USUARIO APP", IdPerfil: 4 },
        })
        .then((res) => {
          this.ListaTrabajadores = res.data.data.trabajador;
          this.get_dataSource();
        });
    },
  },
  created() {
    var date = new Date(),
      y = date.getFullYear(),
      m = date.getMonth();
    var firstDay = new Date(y, m, 1);
    var lastDay = new Date(y, m + 1, 0);
    this.rangeDate = {
      start: firstDay,
      end: lastDay,
    };
  },
  mounted() {
    this.get_listtrabajador();
  },
  destroyed() {},
};
</script>
