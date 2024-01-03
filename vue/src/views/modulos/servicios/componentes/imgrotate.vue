<template>
    <div>
        {{functionstyle}}
            <button type="button" @click="GirarImg()" class="btn btn-01" >Girar <span class="badge badge-light"></span></button>
            <br>
            
            <div class="float_checkbox">
            <label class="check_box">
            <input type="checkbox" v-model="item.Mostrar">
            <span class="checkmark"></span>
            </label>
            </div>

            <div class="ajustar">
            
            <img :style="stylerotate" :src="item.FileEquipo" alt="..." class="img-thumbnail img-fluid">

            </div>

    </div>
</template>
<script>
export default {
    props:['item','ruta','img'],
    data() {
        return {
            valor:this.item.Posicion,
            stylerotate:'transform: rotate(0deg)',
            coordinates: {
				width: 300,
				height: 200,
				left: 0,
                top: 0,
                style:'border-style: dotted; height: 208px; width: 308px;',
                placeholder:'Subir imagen'
			},
        }
    },created() {
        
    },mounted() {
        
    },
    methods: {
        
        GirarImg(){
            console.log('execute_rotate');
            this.item.Posicion = this.item.Posicion + 1;
            this.item.isRotated = 1;

            if(this.item.Posicion >= 5){
                this.item.Posicion = 1;
            }

        },
    },computed:{

        functionstyle: function () {
            
            if(this.item.Posicion == 1){
                this.stylerotate='transform: rotate(-90deg)';
            }else if(this.item.Posicion == 2){
                this.stylerotate='transform: rotate(-180deg)';
            }else if(this.item.Posicion == 3){
                this.stylerotate='transform: rotate(-270deg)';
            }else if(this.item.Posicion == 4){
                this.stylerotate='transform: rotate(-360deg)';
            }else{
                this.stylerotate='transform: rotate(0deg)';
            }

            return '';

        }

    }

    
}
</script>