<template>
	<div>
        <!-- <svg-injector   svg-injector  :class-name="'svg-inject iconic-signal-none'" :src="Imagen"></svg-injector> -->
        <img  svg-injector style="fill='#9C92AC'"  :class="'svg-inject iconic-signal-none'" :src="Imagen">
    </div>
</template>

<script>
	export default {
    props:['item'],
    name: 'app'
  }
</script>

<style>
	.cls-1{fill:#fff}
	.background{fill:#f00}
	.foreground{fill:#00f}
	.trim{fill:#0f0}
</style>