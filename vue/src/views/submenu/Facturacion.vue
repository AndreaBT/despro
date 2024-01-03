<template>
    <div>

        <div class="row justify-content-center">
            <div class="col-12 col-sm-12 col-md-12 col-lg-10">
                <div class="row justify-content-center mt-4">
                    <div class="col-12 col-sm-2 col-md-11 col-lg-8">
                        <div class="row justify-content-end">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <h3>Menú</h3>
                                <hr>
                            </div>
                        </div>
                        <div class="row justify-content-start">
                            <div class="col-12 col-sm-12 col-md-3 col-lg-3 text-center">
                                <router-link :to="{name:'listaserfac'}" class="btn btn-01 btn-sub btn-block" >
                                    <img src="../../images/facturacion/factura.png" class="img-fluid" alt="Desprosoft"><br>
                                    <span class="small bold">Servicios para Facturar</span>
                                </router-link>
                            </div>
                            <div class="col-12 col-sm-12 col-md-3 col-lg-3 text-center">
                                <router-link :to="{name:'ListFacturas',params:{Tipo:1}}" class="btn btn-01 btn-sub btn-block" >
                                    <img src="../../images/facturacion/factura_autorizar.png" class="img-fluid" alt="Desprosoft"><br>
                                    <span class="small bold">Autorizar Prefactura</span>
                                </router-link>
                            </div>
                            <div class="col-12 col-sm-12 col-md-3 col-lg-3 text-center">
                                <router-link :to="{name:'ListFacturas',params:{Tipo:2}}" class="btn btn-01 btn-sub btn-block" >
                                    <img src="../../images/facturacion/factura_preautorizar.png" class="img-fluid" alt="Desprosoft"><br>
                                    <span class="small bold">Prefactura Autorizadas</span>
                                </router-link>
                            </div>
                            <div class="col-12 col-sm-12 col-md-3 col-lg-3 text-center">
                                <router-link :to="{name:'ListFacturas',params:{Tipo:3}}" class="btn btn-01 btn-sub btn-block" >
                                    <img src="../../images/facturacion/factura_cancelar.png" class="img-fluid" alt="Desprosoft"><br>
                                    <span class="small bold">Prefacturas Anuladas</span>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>