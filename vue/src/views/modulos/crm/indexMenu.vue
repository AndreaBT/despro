<template>
	<div class="row">
		<input type="hidden" :disabled="validate">
		<div class="col-12 col-sm-12 col-md-12 col-lg-12">
			<nav class="navbar navbar-breadcrumb navbar-expand-md bg-breadcrumb breadcrumb-borde">
				<div class="mr-auto">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb clearfix pt-3">
							<li class="breadcrumb-item">
								<a v-if="Retornar" @click="iracalendario">{{TxtDefault}}</a>
								<a v-else @click="Atras">{{MenuAtras}}</a>
							</li>
							<li v-if="ShowNext" class="breadcrumb-item active">{{Seccion}}</li>
						</ol>
					</nav>
				</div>

				<form class="form-inline justify-content-end">
					<div class="form-group mt-n1">
						<slot name="Localizac"></slot>
						<div class="dropdown mr-2">
							<button class="btn btn-02 btn-07 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
								Menú
							</button>
							<div class="dropdown-menu dropdown-menu-button dropdown-menu-right" aria-labelledby="dropdownMenuButton" >
								<a class="dropdown-item" @click="Contactos">Contactos</a>
								<a class="dropdown-item" @click="Oportunidad">Oportunidad</a>
								<a class="dropdown-item" @click="pipedrive">Pipedrive</a>
								<a class="dropdown-item" @click="forecast">Forecast</a>
							</div>
						</div>

						<div class="dropdown mr-2">
							<button class="btn btn-02 btn-07 dropdown-toggle" type="button" id="dropdownConfigButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
								Configuración
							</button>
							<div class="dropdown-menu dropdown-menu-button dropdown-menu-right" aria-labelledby="dropdownConfigButton" >
								<a class="dropdown-item" @click="vendedores">Vendedores</a>
								<a class="dropdown-item" @click="Procesos">Procesos</a>
							</div>
						</div>
						<slot name="BtnInicio"></slot>
					</div>
				</form>
			</nav>
		</div>
	</div>
</template>

<script>


export default {
	name: "Menu",
	props:['pSitio','pSitioAtras','pRegresar','pTxtDefault','pShowNext'],
	components: {
	},
	data: function() {
		return {
			TxtDefault: 'Calendario',
			Seccion: 'Menu',
			MenuAtras: 'Menu',
			Retornar: true,
			ShowNext: true,
		};
	},
	methods: {
		iracalendario() {
			this.$router.push({ name: "submenucrm" });
		},
		Contactos() {
			this.$router.push({ name: "crmcontactos", params: {} });
		},
		Oportunidad() {
			this.$router.push({ name: "crmoportunidad", params: {} });
		},
		pipedrive() {
			this.$router.push({ name: "crmpipedrive", params: {} });
		},
		forecast() {
			this.$router.push({ name: "crmforecast", params: {} });
		},
		vendedores() {
			this.$router.push({ name: "crmvendedores", params: {} });
		},
		Procesos() {
			this.$router.push({ name: "crmtiposprocesos", params: {} });
		},
		Atras()
		{
			this.bus.$emit('Regresar');
		},
	},
	created() {
	},
	computed: {
        validate(){
            if(this.pSitio!=undefined){
                this.Seccion = this.pSitio;
            }
			if(this.pSitioAtras!=undefined){
                this.MenuAtras = this.pSitioAtras;
            }
			if(this.pRegresar!=undefined){
                this.Retornar = this.pRegresar;
            }
			if(this.pTxtDefault!=undefined){
                this.TxtDefault = this.pTxtDefault;
            }
			if(this.pShowNext!=undefined){
                this.ShowNext = this.pShowNext;
            }

			return this.Seccion;
		}
	},
};
</script>
