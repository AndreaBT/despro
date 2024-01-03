<template>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="row intro" style="display: block;">
                <div class="col-12">
                    <form class="form-inline">
                    </form>
                </div>
            </div>

            <div class="card card-grafica">
                <div class="card-header">
                    <h1 class="card-title">PipeDrive</h1>
                </div>
                <div class="card-body">
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
</template>

<script>


// STEP 2: Prepare the data
const dataSource = {
    chart: {
        caption: "Seguimiento de Clientes",
        subcaption: "",
        theme: "fusion",
        is2d: "0",
        ishollow: "0",
        usesameslantangle: "0",
        plottooltext: "$label: <b>$percentValue ($dataValue)</b>",
        showvalues: "1",
        decimals: "1"
    },
    data: [
        {
            label: "Visitas",
            value: "50"
        },
        {
            label: "Llamada en frio",
            value: "40"
        },
        {
            label: "Prospección",
            value: "30"
        },
        {
            label: "Propuesta",
            value: "20"
        },
        {
            label: "Cierre",
            value: "10"
        }
    ]
};

export default {
    name: 'app',
    data() {
        return {
            type: "funnel",
            width: "100%",
            height: "100%",
            dataFormat: "json",
            height:500,
            width:400,
            dataSource,
            TipoVehiculo:''
        }
    },
    methods: {
        async get_dataSource(){
        }
    },
    created() {
    },
    mounted() {
    },
}
</script>