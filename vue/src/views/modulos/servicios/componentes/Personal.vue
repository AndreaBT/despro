<template>
    <div >
       <div class="row mt-2">
            <div class="col-md-12 col-lg-6">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <h4>Personal</h4>
                        <hr class="mb-2">
                    </div>
                    
                    <div class="col-md-6 col-lg-6">
                        <draggable @change="Cambiar" id="div1" class="droppable bg-feed" group="people"  :list="Consultado.ListaTrabajadores"  >
                            <div v-for="(element, index) in Consultado.ListaTrabajadores" :key="index">
                                {{ element.Nombre }}
                            </div>
                        </draggable>
                    </div>
                  <div class="col-md-6 col-lg-6">
                        <draggable  @change="Cambiar"  id="div2" class="droppable" :list="this.servicios.Trabajadores" group="people" >
                            <div v-for="(element, index) in this.servicios.Trabajadores" :key="index">
                                {{ element.Nombre }}<span v-if="element.RegEstatus=='B'"> (Usuario Eliminado)</span>
                                <input class="icon-eye" v-model="servicios.Personal" :value="element.IdTrabajador"  type="radio" name="radio1" id="radio1">
                            </div>
                        </draggable>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-6">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <h4>Vehículo</h4>
                        <hr class="mb-2">
                    </div>

                    <div class="col-md-6 col-lg-6">
                        <draggable  :move="checkMove"  id="div1" class="droppable bg-feed" group="vehiculo"  :list="Consultado.ListaVehiculos">
                            <div v-for="(element, index) in Consultado.ListaVehiculos" :key="index">
                                {{ element.Vehiculo+' '+element.Numero}}
                            </div>
                        </draggable>
                    </div>

                    <div class="col-md-6 col-lg-6">
                        <draggable id="div2" class="droppable" :list="this.servicios.Vehiculos" group="vehiculo">
                            <div v-for="(element, index) in this.servicios.Vehiculos" :key="index">
                                {{ element.Vehiculo+' '+element.Numero}} <span v-if="element.RegVehiculo=='B'"> (Vehiculo Eliminado)</span>
                            </div>
                        </draggable>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Cvalidation from '@/components/Cvalidation.vue'
export default {
    props:['Consultado','servicios','errorvalidacion'],
    name: "Personal",
    display: "Two Lists",
    order: 1,
    components: {
        Cvalidation
    },
    data() {
        return {
            Vehiculo: [
                { name: "Vehidulo", id: 1 },
                { name: "Carro", id: 2 },
                { name: "Moto", id: 3 },
                { name: "Taxi", id: 4 }
            ],
            status: '',
            IdTrabajador: 0,
        };
    },
    methods: {
        Cambiar() {
            for (var i=0;i<this.Consultado.ListaTrabajadores.length;i++)
            {
                if (this.Consultado.ListaTrabajadores[i].IdTrabajador==this.servicios.Responsable)
                {
                    this.servicios.Responsable=0;
                }
            }
        },
        CambiarVehiculo() {
            if (this.servicios.Vehiculos.length>1)
            {
                return false;
            }
        },
        checkMove: function(e) {
            if (this.servicios.Vehiculos.length>0)
            {
                return false;
            }
        }
    },
    created() {
    },
};
</script>