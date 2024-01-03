<template>
    <div>
        <section class="container-fluid mt-3">
            <div class="row justify-content-center mt-4">
                <div class="col-12 col-sm-12 col-md-2 col-lg-2 text-center">
                    <router-link :to="{name:'empresasroot', params: {Id:0,PShowButtons:false}}" class="btn btn-01 btn-sub btn-block" >
                        <img src="../../images/submenu/sucursales.png" alt="Desprosoft"><br>
                        <span>Empresas</span>
                    </router-link>
                </div>
                <div class="col-12 col-sm-12 col-md-2 col-lg-2">
                    <router-link :to="{name:'configequipamiento'}" class="btn btn-01 btn-sub btn-block" >
                        <img src="../../images/submenu/tipo_de_servicio.png" alt="Desprosoft"><br>
                        <span>Configuración de Equipos</span>
                    </router-link>
                </div>
                <div class="col-12 col-sm-12 col-md-2 col-lg-2 text-center">
                    <router-link :to="{name:'ConfigCorreos'}" class="btn btn-01 btn-sub btn-block">
                        <img src="../../images/submenu/tipo_de_servicio.png" alt="Desprosoft"><br>
                        <span>Configuración de Correo</span>
                    </router-link>
                </div>
            </div>
        </section>
    </div>
</template>