<template>
  <div>
      <section class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-12 col-md-12 col-lg-10">
                <div class="row justify-content-center mt-4">
                    <div class="col-12 col-sm-2 col-md-6 col-lg-6">
                        <div class="row justify-content-end">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <h3>Menú</h3>
                                <hr>
                            </div>
                        </div>
                        <div class="row justify-content-start">
                            <div class="col-12 col-sm-12 col-md-5 col-lg-5 text-center">
                                <router-link type="button" class="btn btn-01 btn-sub btn-block" :to="{ name: 'spend_dashboard',params:{}}"  >
                                    <img src="../../images/spend/spend_dashboard.png" class="img-fluid" alt="Desprosoft"><br>
                                    <span>YTD</span>
                                </router-link>
                            </div>
                            <div class="col-12 col-sm-12 col-md-5 col-lg-5 text-center">
                                <router-link type="button" class="btn btn-01 btn-sub btn-block" :to="{ name: 'spend_ordenc',params:{}}"  >
                                    <img src="../../images/spend/spend_bitacora.png" class="img-fluid" alt="Desprosoft"><br>
                                    <span>Orden de compra</span>
                                </router-link>
                            </div>
                            <!--<div class="col-12 col-sm-12 col-md-5 col-lg-5 text-center">
                                <router-link type="button" class="btn btn-01 btn-sub btn-block" :to="{ name: 'spend_hora',params:{}}"  >
                                    <img src="../../images/spend/spend_bitacora.png" class="img-fluid" alt="Desprosoft"><br>
                                    <span>Horas</span>
                                </router-link>
                            </div>-->
                        </div>
                    </div>
                    <div class="col-12 col-sm-2 col-md-6 col-lg-6">
                        <div class="row justify-content-end">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <h3>Configuración</h3>
                                <hr>
                            </div>
                        </div>
                        <div class="row justify-content-start">
                            <div class="col-12 col-sm-12 col-md-5 col-lg-5 text-center">
                                    <router-link type="button" class="btn btn-01 btn-sub btn-block" :to="{ name: 'spend_proyecto',params:{}}"  >

                                    <img src="../../images/spend/spend_nuevo.png" class="img-fluid" alt="Desprosoft"><br>
                                    <span>Proyecto</span>
                                    
                                    </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </section>
  </div>
</template>

<script>
export default {

}
</script>

<style>

</style>