<template>
    <div>
        <div class="col-lg-12 row">
            <div  class="col-lg-4">
                    <label>Fecha</label>
                    <v-date-picker
                        v-model="form.Fecha"
                        @input="$emit('input', form)" 
                        :mode="typeCalendar"
                        :min-date="new Date()"
                        
                        :popover="{ 
                            placement: 'bottom',
                            visibility: 'click',                     
                        }"
                        :input-props='{
                            class:"form-control form-control-sm cal-black",
                            style:"cursor:pointer;background-color:#F9F9F9",
                            readonly: true,
                        }'
                    />
                    <m2-validator v-if="error.Fecha" :mensaje="error.Fecha[0]" />
            </div>
            
            <div   class="col-lg-4">
                <label>Hora inicio</label>
                <the-mask 
                    v-model="form.HoraInicio" 
                    @input="$emit('input', form)" 
                    :mask="['##:##']"
                    class="form-control form-control-sm" ></the-mask>
                <m2-validator v-if="error.HoraInicio" :mensaje="error.HoraInicio[0]" />
            </div>
            <div    class="col-lg-4">
                <label>Hora fin</label>
                <the-mask 
                    v-model="form.HoraFin"   
                    @input="$emit('input', form)" 
                    :mask="['##:##']"
                    class="form-control form-control-sm" ></the-mask>
                <m2-validator v-if="error.HoraFin" :mensaje="error.HoraFin[0]" />
            </div>
            
            
        </div>
    </div>
</template>

<script>
export default {

    props: [
        'value',
        'errors'
    ],

    data() {
        return {

            form: this.value,
            error: this.errors,

            rango: false,

        }
    },

    computed: {

        getThreeMonthsMax: function() {

            let currentDate = new Date();
            let maxRange = new Date(currentDate.setMonth(currentDate.getMonth() + 3));

            return maxRange;

        },
        
        disableHorasRango: function (){

            let classDiv = {
                'disabled-div': null
            };

            if(this.rango)
                classDiv['disabled-div'] = true;
            else
                classDiv['disabled-div'] = false;

            return classDiv;

        },

        typeCalendar: function() {

            // if(this.tipo == 'guardia')
            //     return 'range';

            return 'single';

        }

    }

}
</script>

<style>

</style>