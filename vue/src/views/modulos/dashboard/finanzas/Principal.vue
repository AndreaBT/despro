<template>
	<div>
		<div class="row mt-2">
			<div class="col-12 col-sm-12 col-md-12 col-lg-12">
				<nav
					class="navbar navbar-breadcrumb navbar-expand-md bg-breadcrumb breadcrumb-borde"
				>
					<div class="mr-auto">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb clearfix pt-3">
								<li class="breadcrumb-item active">Dashboard Finanzas</li>
							</ol>
						</nav>
					</div>
				</nav>
			</div>
		</div>

		<div class="form-row justify-content-center mt-2">
			<CPlan/>
			<CAcumulado/>
			<CCostos/>
			<CGrossProfit/>
			<CTipoServ/>
			<CGlobalCuentas/>
			<CRealCuentas />
			<!-- <CHoras></CHoras> -->
		</div>
	</div>
</template>

<script>
import Vue from "vue";
import VueFusionCharts from "vue-fusioncharts";
import FusionCharts from "fusioncharts";
import Column2D from "fusioncharts/fusioncharts.charts";
import FusionTheme from "fusioncharts/themes/fusioncharts.theme.fusion";
import Widgets from "fusioncharts/fusioncharts.widgets";

import GammelTheme from "fusioncharts/themes/fusioncharts.theme.gammel";
import CandyTheme from "fusioncharts/themes/fusioncharts.theme.candy";
import ZuneTheme from "fusioncharts/themes/fusioncharts.theme.zune";
import OceanTheme from "fusioncharts/themes/fusioncharts.theme.ocean";
import CarbonTheme from "fusioncharts/themes/fusioncharts.theme.carbon";

Vue.use(
	VueFusionCharts,
	FusionCharts,
	Column2D,
	Widgets,
	FusionTheme,
	GammelTheme,
	CandyTheme,
	ZuneTheme,
	OceanTheme,
	CarbonTheme
);

import CPlan from "@/views/modulos/dashboard/finanzas/Plan.vue";
import CGrossProfit from "@/views/modulos/dashboard/finanzas/GrossProfit.vue";
import CAcumulado from "@/views/modulos/dashboard/finanzas/Acumulado.vue";
import CTipoServ from "@/views/modulos/dashboard/finanzas/TipoServ.vue";
import CHoras from "@/views/modulos/dashboard/finanzas/Horas.vue";
import CCostos from "@/views/modulos/dashboard/finanzas/Costos.vue";
import CGlobalCuentas from "@/views/modulos/dashboard/finanzas/GlobalCuentas.vue";
import CRealCuentas from "@/views/modulos/dashboard/finanzas/RealCuentas.vue";

export default {
	name: "",
	props: [""],
	components: { CPlan, CGrossProfit, CAcumulado, CTipoServ, CHoras, CCostos,CGlobalCuentas,CRealCuentas },
	data() {
		return {
			Head: {
				Title: "Dashboard Finanzas",
				BtnNewShow: false,
				BtnNewName: "Nuevo",
				isreturn: false,
				isModal: false,
				isEmit: false,
				Url: "",
				ObjReturn: ""
			},
			isVisible: true
		};
	},
	provide: {},
	methods: {
		Filtros() {
			//this.bus.$emit('Ocultar',true);
			this.bus.$emit("Ocultar", this.isVisible);
			this.bus.$emit("Ocultar2", this.isVisible);
			this.bus.$emit("Ocultar3", this.isVisible);
			this.bus.$emit("Ocultar4", this.isVisible);
			this.bus.$emit("Ocultar5", this.isVisible);
			this.bus.$emit("Ocultar6", this.isVisible);
			this.bus.$emit("Ocultar7", this.isVisible);
			this.bus.$emit("Ocultar8", this.isVisible);
			
			if (this.isVisible) {
				this.isVisible = false;
			} else {
				this.isVisible = true;
			}
		}
	},
	created() {},
	mounted() {},
	destroyed() {}
};
</script>

<style>
svg > g[class^="raphael-group-"] > text {
	display: none;
}
</style>
