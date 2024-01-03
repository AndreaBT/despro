<template>
    <tr>
        <td v-if="pContIF == 0" :colspan="pColspan">
            <h6 class="text-center">No contiene registros</h6>
        </td>
    </tr>
</template>

<script>

export default {
    name:  "CSinRegistros",
    props: ["pContIF","pColspan"],
    data() {
        return {
        }
    },
}
</script>