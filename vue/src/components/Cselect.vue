<template>
    <option>{{opcion}}</option>

</template>
<script>
export default {
    props :['opcion'],
    name:"Cselect",
    data() {
        return {
              Modal:true,
            Tamanio:"",
             Titulo:""
        }
    }
}
</script>