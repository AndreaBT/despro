import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import axios from "axios";
import "./components/Index";
import VueSweetalert2 from "vue-sweetalert2";
import * as VueGoogleMaps from "vue2-google-maps";

import GmapCluster from "vue2-google-maps/dist/components/cluster"; // replace src with dist if you have Babel issues

Vue.component("GmapCluster", GmapCluster);

import VueIziToast from "vue-izitoast";
import VCalendar from "v-calendar";
import draggable from "vuedraggable";

import moment from "moment-timezone";

import { BootstrapVue, IconsPlugin } from "bootstrap-vue";
import "bootstrap-vue/dist/bootstrap-vue.css";

import VueNumeric from "vue-numeric";

import "@/style/plugin/bootstrap/css/bootstrap.min.css";
import "@/style/plugin/bootstrap/css/wizard.css";
import "@/style/plugin/font_awesome/css/all.css";
import "@/style/css/main.css";
import "@/style/css/custom_full_calendar.css";
import "sweetalert2/dist/sweetalert2.min.css";
import "izitoast/dist/css/iziToast.css";

import "@/assets/lib/popper/popper.min.js";
import "@/assets/lib/jquery/jquery-3.3.1.min.js";
import "@/assets/js/bootnavbar.js";


import VueTreeselect from "@riophae/vue-treeselect";
// import the styles
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
Vue.component("treeselect", VueTreeselect);

import SVGInjectorVue from "svginjector-vue";

import Antd from "ant-design-vue";
import VueTheMask from "vue-the-mask";

import "ant-design-vue/dist/antd.css";

import "vue-croppa/dist/vue-croppa.css";
import Croppa from "vue-croppa";

import GeneralFunctions from "./helpers/GeneralFunctions"

import VueApexCharts from "vue-apexcharts";
Vue.use(VueApexCharts);

Vue.component("apexchart", VueApexCharts);


Vue.use(GeneralFunctions, Vue.prototype.$http);
Vue.config.productionTip = false;
Vue.prototype.$http = axios;

Vue.prototype.$http.defaults.baseURL = "http://localhost/desprosoft4/api/v.1/"; // LOCALHOST
//Vue.prototype.$http.defaults.baseURL = 'https://desprosoft.lugarcreativo.com.mx/services/api/v.1/';  // SERVIDOR DE PRUEBAS
//Vue.prototype.$http.defaults.baseURL = 'https://desprosoft.online/services/api/v.1/';  // SERVIDOR DE PRODUCCION

Vue.prototype.bus = new Vue();
var token = sessionStorage.getItem("token_user");
Vue.prototype.$http.defaults.headers.common["Authorization"] = token;

var zonaH = sessionStorage.getItem("ZonaHoraria");
//console.log(zonaH);
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(Croppa);

//moment.tz.setDefault('Asia/Jakarta');
moment.tz.setDefault(zonaH);
Vue.prototype.$moment = moment;

Vue.use(VueGoogleMaps, {
	load: {
		key: "AIzaSyDaa7OKuZpbRBDa1WFaVNaAwZQXMFPVAHs",
		libraries: "places"
	}
});

const options = {
	confirmButtonColor: "#41b882",
	cancelButtonColor: "#ff7674"
};
Vue.use(VueSweetalert2, options);

Vue.use(VueIziToast, {
	position: "topCenter"
});
Vue.use(VCalendar);
Vue.component("draggable", draggable);
Vue.use(SVGInjectorVue);

Vue.component("VueNumeric", VueNumeric);
Vue.use(VueNumeric);
Vue.use(Antd);

Vue.use(VueTheMask);

new Vue({
	router,
	store,
	render: h => h(App)
}).$mount("#app");
