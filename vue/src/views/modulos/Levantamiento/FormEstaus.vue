<template>
    <div>
        <section class="container-fluid mt-3">
            {{cotizacion_servicio.Estatus}}

            <div class="row justify-content-center mt-">

                <div class="col-12 col-sm-12 col-md-2 col-lg-2"></div> 

                <div v-if="cotizacion_servicio.Estatus == '' || cotizacion_servicio.Estatus == null" @click="CambiarEstatus('PENDIENTE')" class="col-12 col-sm-12 col-md-2 col-lg-2">
                    <span class="btn btn-01 btn-sub btn-block" >
                    <img src="../../../images/finanzas/actualizar.png" alt="Desprosoft"><br>
                    <span>Pendiente</span>
                    </span>
                </div>

                <div v-if="cotizacion_servicio.Estatus == null || cotizacion_servicio.Estatus == 'PENDIENTE'" @click="CambiarEstatus('CERRADA')" class="col-12 col-sm-12 col-md-2 col-lg-2">
                    <span class="btn btn-01 btn-sub btn-block" >
                    <img src="../../../images/finanzas/actualizar.png" alt="Desprosoft"><br>
                    <span>Cerrar</span>
                    </span>
                </div>

                <div class="col-12 col-sm-12 col-md-2 col-lg-2"></div> 
            </div>
        </section>
    </div>
</template>
<script>
export default {
    data() {
        return {
            cotizacion_servicio:{
                IdCotizacionServicio:0
            },
            Ocliente:{
            }
        }
    },
    methods: {
        Get_Recovery(Id){
            this.$http.get(
                'cotizacion_servicio/recovery',
                {
                    params:{IdCotizacionServicio: this.cotizacion_servicio.IdCotizacionServicio}
                }
            ).then( (res) => {
                this.cotizacion_servicio= res.data.data.cotizacion_servicio;
                this.Ocliente=res.data.data.Ocliente;
                this.ShowComponents=true;
            });
        },
        CambiarEstatus(Tipo)
        { 
            this.$swal({
                title: 'Esta seguro que desea cambiar a '+Tipo+'?',
                text: 'No se podra revertir esta acción',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No, mantener',
                showCloseButton: true,
                showLoaderOnConfirm: true
            }).then((result) => {
                if(result.value) {

                    this.$http.get(
                        'levantamiento/updatestatus',
                        {
                            params:{IdCotizacionServicio: this.cotizacion_servicio.IdCotizacionServicio,Estatus:Tipo}
                        }
                    ).then( (res) => {
                        this.$toast.success('Información Actualizada');
                        $('#ModalEstatus').modal('hide');
                        this.bus.$emit('List');
                    });                    
                } 
            });
        },
    },
    mounted() {
        this.bus.$off('Nuevo');
        this.bus.$on('Nuevo',(data,Id)=>
        {
            if (Id==undefined)
            {
                Id=0;
            }

            //this.Limpiar();  
            //this.ShowComponent=false;
        
            //this.servicios.IdServicio=Id;
            if(Id>0)
            {
                this.cotizacion_servicio.IdCotizacionServicio = Id;
                this.Get_Recovery();
                //this.ContadoresImg();
            }
            else
            {
                //this.ShowComponent=true;
            }
        });
    },
}
</script>