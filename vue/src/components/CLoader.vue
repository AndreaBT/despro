<template>
    <div>
        <input type="hidden" :disabled="ValidateLoad">
        <b-overlay :show="ConfigLoad.ShowLoader" rounded="sm" :class="(ConfigLoad.ClassLoad)?'heightCardLoader':''">
            <slot name="BodyFormLoad"></slot>
            <template #overlay>
                <div class="text-center">
                    <span aria-hidden="true" class="spinner-border"></span>
                    <p id="cancel-label"><b>Cargando Por Favor Espere...</b></p>
                </div>
            </template>
        </b-overlay>
    </div>
</template>

<script>

export default {
    name:  "CLoader",
    props: ["pConfigLoad"],
    data() {
        return {
            ConfigLoad:{
                ShowLoader: true,
                ClassLoad:  true,
            }
        }
    },
    computed: {
        ValidateLoad()
        {
            if (this.pConfigLoad.ShowLoader != undefined) {
                this.ConfigLoad.ShowLoader  = this.pConfigLoad.ShowLoader;
            }
            if (this.pConfigLoad.ClassLoad != undefined) {
                this.ConfigLoad.ClassLoad  = this.pConfigLoad.ClassLoad;
            }
        }
    }
}
</script>