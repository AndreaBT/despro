<template>
    <div>
        <input 
            type="text"
            :class="clase"
            v-model="displayValue"
            :disabled="activo" 
            @blur="isInputActive = false" 
            @focus="isInputActive = true"
			:readonly="readonly"/>
    </div>
</template>

<script>
export default {

    props : ['value','clase', 'activo','readonly',],

    data(){
        return{

            isInputActive: false,

        }
    },

    created() {

        

    },

    computed: {
        displayValue: {
            get: function() {

                if (this.isInputActive) {
                    // Cursor is inside the input field. unformat display value for user
                    return this.value.toString()
                } else {

                    let valor = parseFloat(this.value);

                    // User is not modifying now. Format display value for user interface
                    //return "% " + valor.toFixed(2).replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1,")
                    let valor2 = valor.toFixed(2).replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1,")
                    return valor2 + " %";

                }
            },
            set: function(modifiedValue) {
                // Recalculate value after ignoring "$" and "," in user input
                let newValue = parseFloat(modifiedValue.replace(/[^\d\.]/g, ""))
                // Ensure that it is not NaN
                if (isNaN(newValue)) {
                    newValue = 0
                }
                // Note: we cannot set this.value as it is a "prop". It needs to be passed to parent component
                // $emit the event so that parent component gets it
                this.$emit('input', newValue)
            },
        }
    }
}
</script>

<style>

</style>
