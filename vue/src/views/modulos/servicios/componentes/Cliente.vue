<template>
    <div>
        <div class="row mt-2">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <h4 class="titulo-04">Datos del Cliente</h4>
                <hr>
            </div>
        </div>

        <div class="form-group form-row mt-2">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                <label>Nombre *</label>
                <input type="text" readonly v-model="servicios.Cliente"  class="form-control form-control-sm" placeholder="Nombre Cliente">
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Cliente" :Mensaje="errorvalidacion.Cliente[0]"></Cvalidation></label>
            </div>

            <div v-show="Tipo" class="col-12 col-sm-12 col-md-6 col-lg-6">
                <div class="form-inline">
                    <button @click="ListarCli"  data-toggle="modal" data-target="#ModalForm3"  data-backdrop="static" type="button" class="btn btn-01 search mt-3b">Buscar</button>
                </div>
            </div>
        </div>

        <div class="form-group form-row">
            <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                <label>Dirección</label>
                <input type="text" readonly  v-model="servicios.Direccion"  class="form-control form-control-sm" placeholder="Dirección Conocida">
            </div>

            <div class="col-12 col-sm-12 col-md-3 col-lg-2">
                <label>Distancia Km</label>
                <vue-numeric   :minus="false" @blur="CalcularDatos" class="form-control form-control-sm"  currency="" separator="," :precision="0" v-model.number="servicios.Distancia"></vue-numeric>
                <label  style="color:red"><Cvalidation v-if="this.errorvalidacion.Distancia" :Mensaje="errorvalidacion.Distancia[0]"></Cvalidation></label>
            </div>

            <div class="col-12 col-sm-12 col-md-3 col-lg-2">
                <label>Velocidad Km/H</label>
                <vue-numeric   :minus="false" @blur="CalcularDatos" class="form-control form-control-sm"  currency="" separator="," :precision="0" v-model.number="servicios.Velocidad"></vue-numeric>
                <label  style="color:red"><Cvalidation v-if="this.errorvalidacion.Velocidad" :Mensaje="errorvalidacion.Velocidad[0]"></Cvalidation></label>
            </div>
        </div>

        <div class="form-group form-row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                <label>Contacto</label>
               
                <input type="text" readonly  v-model="oclientesuc.ContactoS" class="form-control form-control-sm" placeholder="Nombre del Contacto">
            </div>

            <div class="col-12 col-sm-12 col-md-3 col-lg-3" v-show="Tipo">
                <label>No. de Contrato</label>
                <select v-model="servicios.IdContrato" name="" id="" class="form-control form-control-sm">
                    <option value="0">Seleccione Un Numero de Contrato</option>
                    <option v-for="(item,index) in Consultado.ListaNumc" :key="index" :value="item.IdContrato"> 
                        {{item.NumeroC}}
                    </option>
                </select>
            </div>

            <div v-show="!Tipo" class="col-12 col-sm-12 col-md-3 col-lg-3">
                <label>Teléfono</label>
                <input type="text" readonly  v-model="oclientesuc.Telefono" class="form-control form-control-sm" placeholder="">
            </div>
        </div>

        <div class="form-group form-row">
            <div v-show="Tipo" class="col-12 col-sm-12 col-md-6 col-lg-6">
                <label>Correo Electrónico</label>
                <vue-tags-input v-model="servicios.tag"  :tags="servicios.Para" @tags-changed="newTags => servicios.Para = newTags" placeholder="Para"/>
            </div>

            <div v-show="Tipo" class="col-12 col-sm-12 col-md-2 col-lg-2">
                <input type="checkbox"  v-model="servicios.Enviar" > Enviar Correo
            </div>

            <div  v-show="Tipo" class="col-12 col-sm-12 col-md-3 col-lg-3">
                <label>Teléfono</label>
                <input type="text" readonly  v-model="oclientesuc.Telefono" class="form-control form-control-sm" placeholder="">
            </div>
        </div>
    </div>
</template>
<script>
import Cvalidation from '@/components/Cvalidation.vue'

export default {
    name:'Cliente',
    props:['servicios','errorvalidacion','oclientesuc','Consultado','Tipo'],
    components:{
      Cvalidation
    },
    data() {
       return {
            tags: [],
       }
    },
    methods: {
        ListarCli()
        {
            this.$emit('Listar');
        },
        async CalcularDatos()
        {
            if (this.servicios.IdServicio>0)
            {
                await this.$http.get(
                    'servicio/calcularvalores',
                    {
                        params:{IdServicio:this.servicios.IdServicio,Distancia:this.servicios.Distancia,Velocidad:this.servicios.Velocidad}
                    }
                ).then((res) => {
                
                    this.servicios.BurdenTotal = res.data.data.valores.Burden;
                    this.servicios.ManoObraT = res.data.data.valores.CostoMO;
                    this.servicios.CostoV = res.data.data.valores.CostoVH;
                });
            }
        }
    },
}
</script>