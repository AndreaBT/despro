<template>
    <div>

          <section class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-12 col-md-12 col-lg-10">
                <div class="row justify-content-center mt-4">
                    <div class="col-12 col-sm-2 col-md-6 col-lg-6">
                        <div class="row justify-content-end">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <h3>Cotización</h3>
                                <hr>
                            </div>
                        </div>
                        <div class="row justify-content-start">
                            <div class="col-12 col-sm-12 col-md-5 col-lg-5 text-center">
                                <router-link :to="{name:'cotizacion_list'}" class="btn btn-01 btn-sub btn-block" >
                                    <img src="../../images/cotizacion/cotizacion.png"  alt="Desprosoft"><br>
                                    <span class="text-btn">Cotización Servicio</span>
                                </router-link>
                            </div>
                            <div class="col-12 col-sm-12 col-md-5 col-lg-5 text-center">
                               
                            </div>
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
                                 <router-link :to="{name:'cotizacion_materiales', params: {Id:0}}" class="btn btn-01 btn-sub btn-block" >
                                    <img src="../../images/cotizacion/cofing_materiales.png" alt="Desprosoft"><br>
                                    <span class="text-btn">Config. Materiales</span>
                                </router-link>
                                
                            </div>
                            <div class="col-12 col-sm-12 col-md-5 col-lg-5 text-center">
                                 <router-link :to="{name:'cotizacion_costoskm'}" class="btn btn-01 btn-sub btn-block" >
                                    <img src="../../images/cotizacion/cofing_cotizacion.png" alt="Desprosoft"><br>
                                    <span class="text-btn">Confing. Costos KM</span>
                                </router-link>
                            </div>
                               <div class="col-12 col-sm-12 col-md-5 col-lg-5 text-center">
                                <router-link :to="{name:'cotizacion_costosta'}" class="btn btn-01 btn-sub btn-block">
                                    <img src="../../images/cotizacion/cofing_cotizacion.png" alt="Desprosoft"><br>
                                    <span class="text-btn">Config. Costos M/O</span>
                                </router-link>
                            </div>
                             <div class="col-12 col-sm-12 col-md-5 col-lg-5 text-center">
                               <router-link :to="{name:'cotizacion_markup'}" class="btn btn-01 btn-sub btn-block" >
                                    <img src="../../images/cotizacion/cofing_cotizacion.png" alt="Desprosoft"><br>
                                    <span class="text-btn">Config. Markup</span>
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