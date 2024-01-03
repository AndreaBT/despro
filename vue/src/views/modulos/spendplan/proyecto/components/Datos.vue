<template>
  <div>
    <form onsubmit="return false;">
        
        <div class="form-group form-row">
            <div class="col-md-6 col-lg-6">
                <label>Nombre del Proyecto</label>
                <input type="text" class="form-control" v-model="proyecto.Proyecto">
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Proyecto" :Mensaje="errorvalidacion.Proyecto[0]"></Cvalidation></label>
            </div>
            <div class="col-md-2 col-lg-2">
                <label>Fecha Inicio</label>
                <v-date-picker
                    :min-date="new Date()"
                    v-model="proyecto.FechaI"
                />
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.FechaI" :Mensaje="errorvalidacion.FechaI[0]"></Cvalidation></label>
            </div>
            <div class="col-md-2 col-lg-2">
                <label>Fecha Termino</label>
                <select class="form-control" v-model="proyecto.FechaTermino" >
                    <option>--Seleccionar--</option>
                    <option value="Dia">Dia(s)</option>
                    <option value="Semana">Semana(s)</option>
                    <option value="Mes">Mes(s)</option>
                </select>
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="errorvalidacion.FechaTermino" :Mensaje="errorvalidacion.FechaTermino[0]"></Cvalidation></label>
            </div>
            <div class="col-md-2 col-lg-2">
                <label>Cantidad</label>
                <!--<input type="text" class="form-control" v-model="proyecto.CantidadTermino" >-->
                <!--<vue-numeric   :minus="false" class="form-control form-control-sm"  currency="$" separator="," :precision="0" v-model="proyecto.CantidadTermino"></vue-numeric>-->
                <vue-numeric  v-bind:max="1000" :minus="false" class="form-control form-control-sm"  currency="" separator="''" :precision="0" v-model="proyecto.CantidadTermino"></vue-numeric>
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="errorvalidacion.CantidadTermino" :Mensaje="errorvalidacion.CantidadTermino[0]"></Cvalidation></label>
            </div>
        </div>

        <div class="form-group form-row">
            <div class="col-md-6 col-lg-4">
                <label>Cliente</label>
                <input type="text" class="form-control" v-model="proyecto.Cliente" readonly />
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.IdCliente" :Mensaje="errorvalidacion.IdCliente[0]"></Cvalidation></label>
            </div>
            <div class="col-md-6 col-lg-2">
                <div class="form-inline">
                    <button  @click="ListaCliente"  data-toggle="modal" data-target="#ModalForm3"  data-backdrop="static" type="button" class="btn btn-01 search mt-4">Buscar</button>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <label>Sucursal</label>
                <textarea v-model="proyecto.Sucursal"  class="form-control" rows="1" readonly=""></textarea>
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.IdClienteS" :Mensaje="errorvalidacion.IdClienteS[0]"></Cvalidation></label>
            </div>
            <div class="col-md-6 col-lg-3">
                <label>Dirección</label>
                <textarea v-model="proyecto.Direccion"  class="form-control" rows="1" readonly=""></textarea>
            </div>
        </div>

        <div class="form-group form-row">
            <div class="col-md-6 col-lg-4">
                <label>Contacto</label>
                <input type="text" class="form-control" v-model="proyecto.ContactoS" readonly>
            </div>
            <div class="col-md-3 col-lg-4">
                <label>E-Mail</label>
                <input type="text" class="form-control" v-model="proyecto.Correo" readonly>
            </div>
            <div class="col-md-2 col-lg-2">
                <label>Teléfono</label>
                <input type="text" class="form-control" v-model="proyecto.Telefono" readonly>
            </div>
            <div class="col-md-2 col-lg-2">
                <label>Ciudad</label>
                <input type="text" class="form-control" v-model="proyecto.Ciudad" readonly>
            </div>
        </div>

        <div class="form-group form-row justify-content-end">
            <div class="col-md-2 col-lg-2">
                <label>Hora Hombre</label>
                <!--<input type="text" class="form-control dollar " v-model="proyecto.ValorHora">-->
                <vue-numeric   :minus="false" class="form-control form-control-sm"  currency="$" separator="," :precision="0" v-model="proyecto.ValorHora"></vue-numeric>
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.ValorHora" :Mensaje="errorvalidacion.ValorHora[0]"></Cvalidation></label>
            </div>
            <div class="col-md-2 col-lg-2">
                <label>Burden</label>
                <!--<input type="text" class="form-control dollar" v-model="proyecto.ValorBurden">-->
                <vue-numeric   :minus="false" class="form-control form-control-sm"  currency="$" separator="," :precision="2" v-model="proyecto.ValorBurden"></vue-numeric>
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.ValorBurden" :Mensaje="errorvalidacion.ValorBurden[0]"></Cvalidation></label>
            </div>
        </div>
    </form>
    <Ccliente :TipoModal='1'>

    </Ccliente>
  </div>
</template>

<script>
import Ccliente from '@/components/Ccliente.vue'

export default {
    name:'Datos',
    props:['proyecto','errorvalidacion'],
    components:{Ccliente},
    data() {
        return {
            
        }
    },methods: {
        ListaCliente(){
            this.bus.$emit('ListCcliente');
        },add_datos(oSucursal,oCliente){

            this.proyecto.IdCliente=oCliente.IdCliente;
            this.proyecto.Cliente=oCliente.Nombre;

            this.proyecto.Sucursal=oSucursal.Nombre;
            this.proyecto.IdClienteS=oSucursal.IdClienteS;
            this.proyecto.Direccion=oSucursal.Direccion;
            this.proyecto.ContactoS=oSucursal.ContactoS;
            this.proyecto.Correo=oSucursal.Correo;
            this.proyecto.Telefono=oSucursal.Telefono;
            this.proyecto.Ciudad=oSucursal.Ciudad;
        }
    },created() {
        this.bus.$off('SeleccionarCliente');

        this.bus.$on('SeleccionarCliente',(oSucursal,oCliente)=>
        {
           this.add_datos(oSucursal,oCliente);
        });
    },
}
</script>

<style>

</style>