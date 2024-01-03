<template>
    <div >
        <div class="row mt-2">
            <div class="col-md-6 col-lg-6">
                <label> Trabajadores</label>
                <b-list-group>
                    <b-list-group-item v-for="(element, index) in servicios.Trabajadores" :key="index">
                        <b-avatar variant="info" :src="ruta +element.Foto2  " size="6em" class="mr-2"></b-avatar>  {{ element.Trabajador }}
                    </b-list-group-item>
                </b-list-group>
            </div>

            <div class="col-md-6 col-lg-6">
                <label>Vehículos</label>
                <b-list-group>
                    <b-list-group-item v-for="(element, index) in servicios.Vehiculos" :key="index" >{{ element.Categoria }}</b-list-group-item>
                </b-list-group>
            </div>
        </div>   
     
    </div>
</template>
<script>
export default {
    name:'Asignados',
    props:['servicios','ruta'],
    data() {
        return {
        }
    },
    methods: {
    },
}
</script>