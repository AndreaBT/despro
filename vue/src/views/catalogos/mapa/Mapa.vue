<template>
    <div>
        <!--<h2>Search and add a pin</h2>
      <label>
        <gmap-autocomplete
          @place_changed="setPlace">
        </gmap-autocomplete>
        <button >Add</button>
      </label>
      <br/>-->
 
       <Modal   :size="size" :Nombre="NameList" >
        <template slot="Form">
        
          <Cmapa :Arreglo="markers"></Cmapa>
         
        </template>
        </Modal>  
        <button  @click="OpenModal" data-toggle="modal" data-target="#ModalForm"  data-backdrop="static" data-keyboard="false"  type="button" class="btn btn-01 mb-2 mx-sm-3">Nuevo</button>
    </div>
</template>

<script>
import Modal from '@/components/Cmodal.vue';
import Cmapa from '@/components/Cmapa.vue';
export default {
    data() {
        return {
            FormName:'usuariosForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"modal-xl",
            NameList:"Lista Clientes",
            markers: [{
                position: {lat: 8.9968109, lng: -84.1360626}
                }, {
                position: {lat: 10.0584478, lng: -84.1360626}
                }, {
                position: {lat: 31.6167736, lng: -106.4357834}
                }],
            
               infoContent :'',
           
        }
    },
    components:{
      Modal,
      Cmapa,
    },
    methods: {
      OpenModal()
      {
        this.bus.$emit('OpenModal');
      }
    },
    created() {
    
    },mounted() {
     
  },
  computed: {
   
  },
}
</script>