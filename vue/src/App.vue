<template>
  <div id="app">
       <router-view/>
  </div>
</template>

<script>
import Header from '@/components/template/header.vue';
import Modal from '@/components/Cmodal.vue'
export default {
  components:{
    Header,
    Modal
  },
  
}
</script>

<style>

</style>
