import vue from 'vue';
import loading from './Loading.vue';
import InputFormatoMoneda from './InputFormatoMoneda.vue';
import Cvalidation from './Cvalidation.vue';
import Ccliente from './Ccliente.vue';
import Cinputnumber from './Cinputnumerico.vue';
import Cbtnsave from './Cbtnsave.vue';
import Cbtnsave2 from './Cbtnsave2.vue';
import Cmapasearch from './Cmapasearch.vue';
import Cmoneda from './Cmoneda.vue'
import Cporcentaje from './Cporcentaje.vue'

//#region Cabecera para lista y formulario
import CHead from './CHead.vue';
import VueTagsInput from '@johmun/vue-tags-input';

vue.component('VueTagsInput', VueTagsInput);
vue.component('LoadingButton', loading);
vue.component('InputMoneda', InputFormatoMoneda);
vue.component('Cvalidation', Cvalidation);
vue.component('Ccliente', Ccliente);
vue.component('Cnumber', Cinputnumber);
vue.component('CHead', CHead);
vue.component('Cbtnsave', Cbtnsave);
vue.component('Cbtnsave2', Cbtnsave2);
vue.component('Search', Cmapasearch);
vue.component('Cmoneda', Cmoneda);
vue.component('Cporcentaje', Cporcentaje);
