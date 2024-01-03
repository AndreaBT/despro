<template>
    <div>
        <section class="container-fluid mt-3">
             
            <div class="row justify-content-center mt-4">
                <div class="col-12 col-sm-12 col-md-2 col-lg-2 text-center">
                    <router-link :to="{name:'sucursal', params: {Id:0,PShowButtons:false}}" class="btn btn-01 btn-sub btn-block" >
                        <img src="../../images/submenu/sucursales.png" alt="Desprosoft"><br>
                        <span>Sucursales</span>
                    </router-link>
                </div>

                <div class="col-12 col-sm-12 col-md-2 col-lg-2">
                    <router-link :to="{name:'horaslaborales'}" class="btn btn-01 btn-sub btn-block" >
                        <img src="../../images/submenu/horas_laborales.png" alt="Desprosoft"><br>
                        <span>Horas Laborales</span>
                    </router-link>
                </div>
                <div class="col-12 col-sm-12 col-md-2 col-lg-2 text-center">
                    <router-link :to="{name:'tiposervicio'}" class="btn btn-01 btn-sub btn-block">
                        <img src="../../images/submenu/tipo_de_servicio.png" alt="Desprosoft"><br>
                        <span>Tipo de Servicios</span>
                    </router-link>
                </div>
            
                <div class="col-12 col-sm-12 col-md-2 col-lg-2 text-center">
                    <router-link :to="{name:'categoriapersonal'}" class="btn btn-01 btn-sub btn-block" >
                        <img src="../../images/submenu/categoria_personal.png" alt="Desprosoft"><br>
                        <span>Categoría Personal</span>
                    </router-link>
                </div>
                
                <div class="col-12 col-sm-12 col-md-2 col-lg-2 text-center">
                    <router-link :to="{name:'personal'}" class="btn btn-01 btn-sub btn-block">
                        <img src="../../images/submenu/personal.png" alt="Desprosoft"><br>
                        <span>Personal</span>
                    </router-link>
                </div>
            </div>

            <div class="row justify-content-center mt-4">
                <div class="col-12 col-sm-12 col-md-2 col-lg-2 text-center">
                    <router-link :to="{name:'categoriavehiculo'}" class="btn btn-01 btn-sub btn-block" >
                        <img src="../../images/submenu/categoria_vehiculo.png" alt="Desprosoft"><br>
                        <span>Categorías Vehículos</span>
                    </router-link>
                </div>
                
                <div class="col-12 col-sm-12 col-md-2 col-lg-2 text-center">
                    <router-link :to="{name:'vehiculo'}" class="btn btn-01 btn-sub btn-block" >
                        <img src="../../images/submenu/vehiculo.png" alt="Desprosoft"><br>
                        <span>Vehículos</span>
                    </router-link>
                </div>
                
                <div class="col-12 col-sm-12 col-md-2 col-lg-2 text-center">
                    <router-link :to="{name:'folio'}" class="btn btn-01 btn-sub btn-block" >
                        <img src="../../images/submenu/folios.png" alt="Desprosoft"><br>
                        <span>Folios</span>
                    </router-link>
                </div>

                <div class="col-12 col-sm-12 col-md-2 col-lg-2 text-center">
                    <router-link :to="{ name: 'clientes', params: { tipolistp:'clientes'}}"  class="btn btn-01 btn-sub btn-block">
                        <img src="../../images/submenu/clientes.png" alt="Desprosoft"><br>
                        <span>Clientes</span>
                    </router-link>
                </div>
            
            
                <div class="col-12 col-sm-12 col-md-2 col-lg-2">
                    <router-link :to="{name:'tipounidad'}" class="btn btn-01 btn-sub btn-block" >
                        <img src="../../images/submenu/tipo_de_unidad.png" alt="Desprosoft"><br>
                        <span>Tipo de Unidad</span>
                    </router-link>
                </div>

            </div>

            <div class="row justify-content-center mt-4">

                <!-- <div class="col-12 col-sm-12 col-md-2 col-lg-2">
                    <router-link :to="{name:'cpermiso'}" class="btn btn-01 btn-sub btn-block" >
                    <img src="../../images/submenu/categoria_personal.png" alt="Desprosoft"><br>
                    <span>Permisos</span>
                    </router-link>
                </div> -->

                <div v-if="IsDemo" class="col-12 col-sm-12 col-md-2 col-lg-2">
                    <router-link :to="{name:'perfiles'}" class="btn btn-01 btn-sub btn-block" >
                        <img src="../../images/submenu/permisos.png" alt="Desprosoft"><br>
                        <span>Permisos</span>
                    </router-link>
                </div>

                <!-- <div class="col-12 col-sm-12 col-md-2 col-lg-2">
                    <router-link :to="{name:'paquetes'}" class="btn btn-01 btn-sub btn-block" >
                    <img src="../../images/submenu/tipo_de_unidad.png" alt="Desprosoft"><br>
                    <span>Paquetes</span>
                    </router-link>
                </div> -->
                
                <div class="col-12 col-sm-12 col-md-2 col-lg-2"></div>
                <div class="col-12 col-sm-12 col-md-2 col-lg-2"></div>
                <div class="col-12 col-sm-12 col-md-2 col-lg-2"></div>
                <div class="col-12 col-sm-12 col-md-2 col-lg-2"></div>
            </div>
            
        </section>
    </div>
</template>

<script>

export default {
    data() {
        return {
            IsAdmin:false,
            IsDemo:false
        }
    },
    methods: {
        ObtenerDatos() {
            
            var datos = JSON.parse( sessionStorage.getItem('user'));

            if(datos.Perfil == 'Admin' || datos.Perfil == 'ADMIN'){
                this.IsAdmin = true;
            }

            if(datos.Perfil === 'Admin' || datos.Perfil === 'ADMIN' || datos.Perfil === 'ROOT' || datos.Perfil === 'Root'){
                this.IsDemo = true;
            }

        }
    },
    mounted() {
        this.ObtenerDatos();
    },
}

</script>