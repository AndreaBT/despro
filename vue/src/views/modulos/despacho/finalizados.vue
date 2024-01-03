<template>
  <div>
    <!-- <b-list-group >
      <b-list-group-item v-for="(item,index) in Finalizados" :key="index" class="d-flex align-items-center" >
        <span class="mr-auto">Folio : {{item.Folio}}</span>
        <span class="mr-auto">Inicio: {{item.HoraInicio}}</span>
        <span class="mr-auto">Finalizo: {{item.HoraInicio}}</span>
        <b-avatar  button @click="abrirModal(item.IdServicio)" data-toggle="modal" data-target="#ModalForm"  data-backdrop="static" text="Ver" variant="success"></b-avatar>
      </b-list-group-item>
    
    </b-list-group> -->
    <table class="table-01">
      <thead>
        <tr>
          <th>
            FOLIO
          </th>
          <th>
            HORA
          </th>
          <th class="text-center">
            ACCIÓN
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item,key,index) in Finalizados" :key="index">
          <td>
            <b class="text-primary">{{item.Folio}}</b>
          </td>
          <td>
            <b>{{'de '+item.HoraInicio.substr(0,5)+' a '+item.HoraFin.substr(0,5)}}</b>
          </td>
          <td class="text-center">
            <button type="button"  @click="abrirModal(item.IdServicio)" data-toggle="modal" data-target="#ModalForm"  data-backdrop="static" class="btn-icon">
              <i class="fas fa-eye"></i>
            </button>
          </td>
        </tr>
              <!--
              <tr>
                <td>
                  <b class="text-primary">FLUCTECZ9151</b>
                </td>
                <td>
                  <b>7:00:00</b>
                </td>
                <td class="text-center">
                  <button type="button" class="btn-icon">
                    <i class="fas fa-eye"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>
                  <b class="text-primary">FLUCTECZ9151</b>
                </td>
                <td>
                  <b>7:30:00</b>
                </td>
                <td class="text-center">
                  <button type="button" class="btn-icon">
                    <i class="fas fa-eye"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>
                  <b class="text-primary">FLUCTECZ9151</b>
                </td>
                <td>
                  <b>8:00:00</b>
                </td>
                <td class="text-center">
                  <button type="button" class="btn-icon">
                    <i class="fas fa-eye"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>
                  <b class="text-primary">FLUCTECZ9151</b>
                </td>
                <td>
                  <b>8:30:00</b>
                </td>
                <td class="text-center">
                  <button type="button" class="btn-icon">
                    <i class="fas fa-eye"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>
                  <b class="text-primary">FLUCTECZ9151</b>
                </td>
                <td>
                  <b>9:00:00</b>
                </td>
                <td class="text-center">
                  <button type="button" class="btn-icon">
                    <i class="fas fa-eye"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>
                  <b class="text-primary">FLUCTECZ9151</b>
                </td>
                <td>
                  <b>9:30:00</b>
                </td>
                <td class="text-center">
                  <button type="button" class="btn-icon">
                    <i class="fas fa-eye"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>
                  <b class="text-primary">FLUCTECZ9151</b>
                </td>
                <td>
                  <b>10:00:00</b>
                </td>
                <td class="text-center">
                  <button type="button" class="btn-icon">
                    <i class="fas fa-eye"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>
                  <b class="text-primary">FLUCTECZ9151</b>
                </td>
                <td>
                  <b>10:30:00</b>
                </td>
                <td class="text-center">
                  <button type="button" class="btn-icon">
                    <i class="fas fa-eye"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>
                  <b class="text-primary">FLUCTECZ9151</b>
                </td>
                <td>
                  <b>11:00:00</b>
                </td>
                <td class="text-center">
                  <button type="button" class="btn-icon">
                    <i class="fas fa-eye"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>
                  <b class="text-primary">FLUCTECZ9151</b>
                </td>
                <td>
                  <b>11:30:00</b>
                </td>
                <td class="text-center">
                  <button type="button" class="btn-icon">
                    <i class="fas fa-eye"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>
                  <b class="text-primary">FLUCTECZ9151</b>
                </td>
                <td>
                  <b>12:00:00</b>
                </td>
                <td class="text-center">
                  <button type="button" class="btn-icon">
                    <i class="fas fa-eye"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>
                  <b class="text-primary">FLUCTECZ9151</b>
                </td>
                <td>
                  <b>12:30:00</b>
                </td>
                <td class="text-center">
                  <button type="button" class="btn-icon">
                    <i class="fas fa-eye"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>
                  <b class="text-primary">FLUCTECZ9151</b>
                </td>
                <td>
                  <b>13:00:00</b>
                </td>
                <td class="text-center">
                  <button type="button" class="btn-icon">
                    <i class="fas fa-eye"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>
                  <b class="text-primary">FLUCTECZ9151</b>
                </td>
                <td>
                  <b>13:30:00</b>
                </td>
                <td class="text-center">
                  <button type="button" class="btn-icon">
                    <i class="fas fa-eye"></i>
                  </button>
                </td>
              </tr>
              -->
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
    name :'finalizados',
    props:['Finalizados'],
    data() {
        return {
            
        }
    },
    methods: {
        abrirModal(Id)
        {
             this.bus.$emit('Nuevo',null,Id);
        }
    },
}
</script>