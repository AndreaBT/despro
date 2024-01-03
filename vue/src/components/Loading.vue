<template>
    <button
    :class="clase" 
    :disabled="loading"
    @click="click()"
    >
    <slot></slot>
    <template v-if="loading">
        <span class="ml-1 spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        <span ></span>
    </template>
    </button>
</template>

<script>
export default {
    
    props : [
        'clase',
        'loading',

    ],

    methods : {

        click(){
            this.$emit('click');
        }

    }

}
</script>
