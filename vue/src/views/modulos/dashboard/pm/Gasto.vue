<template>
  <div>
    <div id="chart-gasto">
      <fusioncharts
      :type="type"
      :width="width"
      :height="height"
      :dataformat="dataFormat"
      :dataSource="dataSource"
      >
      </fusioncharts>
    </div>
  </div>
</template>

<script>


const dataSource = {
   chart: {
    caption: "Gasto por Proyecto",
    subcaption: "",
    plottooltext: "$label won <b>$dataValue</b> medals in $seriesName",
    theme: "fusion"
  },
  categories: [
    {
      category: [
        {
          label: "USA"
        },
        {
          label: "United Kingdom"
        },
        {
          label: "China"
        },
        {
          label: "Russia"
        },
        {
          label: "Germany"
        }
      ]
    }
  ],
  dataset: [
    {
      seriesname: "Olympics",
      data: [
        {
          value: "121"
        },
        {
          value: "67"
        },
        {
          value: "70"
        },
        {
          value: "55"
        },
        {
          value: "42"
        }
      ]
    },
    {
      seriesname: "Paralympics",
      data: [
        {
          value: "115"
        },
        {
          value: "147"
        },
        {
          value: "239"
        },
        {
          value: ""
        },
        {
          value: "57"
        }
      ]
    }
  ]
  };

export default {
  name: 'app',
  data() {
    return {
      "type": "msbar3d",
      "renderAt": "chart-container",
        width: "100%",
        height: "400",
      "dataFormat": "json",
      dataSource
    }
  }
}
</script>
