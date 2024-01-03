<template>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div :class="Equipo.Status =='Fuera de Servicio' ?'circular_shadow borde-rojo2' :Equipo.Status=='En Observacion' ?'circular_shadow borde-amarillo':Equipo.Status=='Operando'?'circular_shadow borde-verde ': 'circular_shadow borde-gris'">
                <svg-injector   :class-name="Equipo.Status =='Fuera de Servicio' ?'svg-inject iconic-signal-weak' :Equipo.Status=='En Observacion' ?'svg-inject iconic-signal-medium':Equipo.Status=='Operando'?'svg-inject iconic-signal-strong ':'svg-inject iconic-signal-none'" :src="Equipo.RutaEquipo+Equipo.Imagen"></svg-injector>
            </div>
            <h3 class="text-center equipo"><b>No. Serie:</b> {{Equipo.Serie}}</h3>
        </div>

        <div class="col-md-12 col-lg-12 text-center">
          <button v-b-tooltip.hover.top title="Reporte de Servicio" @click="Descargar(oDetalle.IdServicio)" class="btn btn-table pl-01 mr-2" data-toggle="tooltip" data-placement="top"  data-original-title="Ver orden de servicio"><i :id="'pdfOrden_' + oDetalle.IdServicio" class="fas fa-file-pdf fa-fw-m"></i></button>
          <button v-b-tooltip.hover.top title="Reporte Fotográfico " @click="DescargarEvidencia(oDetalle.IdServicio)" class="btn btn-table pl-01" data-toggle="tooltip" data-placement="top" ><i :id="'pdfEvidencia_' + oDetalle.IdServicio" class="fas fa-file-pdf fa-fw-m"></i></button>


        </div>
        <div class="col-md-12 col-lg-12 mt-3">
            <p>
                <b>Fecha Inicio:</b> {{oDetalle.Fecha_I}}<b>, Fecha Término:</b> {{oDetalle.Fecha_F}}
            </p>

            <p>
                <b>Tecnico(s):</b>
            </p>
            <ul v-for="(item, index) in ListaTrabajadores" :key="index">
                <li class="circulo"> {{item.Trabajador}}</li>
            </ul>
            <p>
                <b>Servicio:</b> {{oDetalle.Servicio}}<br><br>
            </p>
            <p>
                <b>Tareas:</b> {{oDetalle.Observaciones}} <br><br>
            </p>
			<p>
				<b>Observaciones de Equipo:</b> <br>{{oDetalle.Comentario2}} <br><br>
			</p>
        </div>
  </div>
</template>

<script>
export default {
    props:['Equipo'],
    data() {
        return {
            oDetalle:{},
            ListaTrabajadores:[],
        }
    },
    methods: {
        async get_listatrabajadores(){
            await this.$http.get(
                'servicio/trabajadoresxserv',
                {
                    params:{IdServicio:this.oDetalle.IdServicio}
                }
            ).then( (res) => {
              this.ListaTrabajadores =res.data.rows;

            });
        },
        Descargar(IdServicio)
        {
            let ActivarSpinner = document.getElementById(`pdfOrden_${IdServicio}`);
            ActivarSpinner.setAttribute('class','fa fa-spinner fa-pulse fa-1x fa-fw');

            this.$http.get('reporte/servicio',
            {
                responseType: 'blob',
                params :
                    {
                        IdServicio:this.oDetalle.IdServicio,
                    }
            }).then( (response) => {

                let pdfContent = response.data;
                let file = new Blob([pdfContent], { type: 'application/pdf' });
                let fileUrl = URL.createObjectURL(file);
                window.open(fileUrl);
                ActivarSpinner.setAttribute('class','fas fa-file-pdf fa-fw-m');
                /*
                var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                var fileLink = document.createElement('a');
                fileLink.href = fileURL;
                fileLink.setAttribute('download', 'Servicio.pdf');
                document.body.appendChild(fileLink);
                fileLink.click();*/

            });
        },
        DescargarEvidencia(IdServicio)
        {
            let ActivarSpinner = document.getElementById(`pdfEvidencia_${IdServicio}`);
            ActivarSpinner.setAttribute('class','fa fa-spinner fa-pulse fa-1x fa-fw');

            this.$http.get('reporte/servicioevidencia',
            {
                responseType: 'blob',
                params :
                    {
                        IdServicio:this.oDetalle.IdServicio,IdEquipo:this.Equipo.IdEquipo,
                    }
            }).then( (response) => {
                // var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                let pdfContent = response.data;
                let file = new Blob([pdfContent], { type: 'application/pdf' });
                let fileUrl = URL.createObjectURL(file);

                window.open(fileUrl);
                ActivarSpinner.setAttribute('class','fas fa-file-pdf fa-fw-m');
            });
        },
    },
    created() {
        this.bus.$off('Recovery');
        this.bus.$on('Recovery',(obj)=>
        {
            this.oDetalle=obj;
            this.get_listatrabajadores();
        });
    },
    mounted() {
    },
}
</script>
<style>
</style>
