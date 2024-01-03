<template>
	<div class="container-fluid">
		<Clist
			:regresar="regresar"
			:Nombre="NameList"
			@FiltrarC="Lista"
			:Filtro="Filtro"
			:isModal="EsModal"
			:pShowBtnAdd="btnadd"
			:pConfigLoad="ConfigLoad"
		>
			<template slot="header">
				<tr>
					<th>Servicio</th>
					<th>Cliente</th>
					<th>Sucursal</th>
					<th>Fecha Inicio</th>
					<th>Fecha Término</th>
					<th>Tareas</th>
					<th>Acciones</th>
				</tr>
			</template>
			<template slot="body">
				<tr v-for="(lista, key, index) in ListaHistorial" :key="index">
					<td>{{ lista.Folio }}</td>
					<td>{{ $limitCharacters(lista.NomCli,45) }}</td>
					<td>{{ $limitCharacters(lista.Cliente,45) }}</td>
					<td><i class="fas fa-calendar-day"></i> {{ lista.Fecha_I }}</td>
					<td><i class="fas fa-calendar-day"></i> {{ lista.Fecha_F }}</td>
					<td class="table__td table__td--sm">{{ $limitCharacters(lista.Observaciones,50) }}</td>
					<td class="text-center tw-2">
						<button
							v-b-tooltip.hover.left
							@click="get_detalle(lista)"
							type="button"
							data-toggle="modal"
							data-target="#ModalForm"
							data-backdrop="static"
							data-keyboard="false"
							class="btn btn-table"
							title="Ver Detalle"
						>
							<i class="fas fa-eye fa-fw-m"></i>
						</button>
					</td>
				</tr>
			</template>
		</Clist>
		<Modal :size="size" :Nombre="NameForm" :Showbutton="false">
			<template slot="Form">
				<DetHistory :Equipo="Equipo"></DetHistory>
			</template>
		</Modal>
	</div>
</template>
<script>
import Modal from "../../../components/Cmodal";
import Clist from "@/components/Clist.vue";
import Cbtnaccion from "@/components/Cbtnaccion.vue";
import DetHistory from "@/views/modulos/monitoreo//DetHistory.vue";

export default {
	name: "listHistorialEquipo",
	props: ["ocliente", "oClienteSucursal", "oEquipo"],
	components: { Modal, Clist, Cbtnaccion, DetHistory },
	data() {
		return {
			NameForm: "Información",
			FormName: "Historial", //Por si no es modal y queremos ir a una vista declarada en el router
			EsModal: true, //indica si es modal o no,
			CloseModal: true, //indica si el modal cierra o de lo contrario asignarle un evento al boton
			size: "",
			NameList: "Historial de Servicios",
			ListaHistorial: [],
			ClienteSucursal: {},
			Cliente: {},
			Equipo: {},
			regresar: true,
			btnadd: false,
			carga: false,
			Filtro: {
				Nombre: "",
				Placeholder: "Nombre..",
				TotalItem: 0,
				Pagina: 1,
				Entrada: 10
			},
			ConfigLoad:{
				ShowLoader:true,
				ClassLoad:true,
			}
		};
	},
	methods: {
		async Lista() {
			this.ConfigLoad.ShowLoader = true;
			await this.$http
				.get("monitoreo/historyequi", {
					params: {
						IdEquipo: this.Equipo.IdEquipo,
						Nombre: this.Filtro.Nombre,
						Entrada: this.Filtro.Entrada,
						pag: this.Filtro.Pagina,
						RegEstatus: "A"
					}
				})
				.then(res => {
					this.ListaHistorial = res.data.historial;
					this.Filtro.Entrada = res.data.pagination.PageSize;
					this.Filtro.TotalItem = res.data.pagination.TotalItems;
				}).finally(()=>{
					this.ConfigLoad.ShowLoader = false;
				});
		},
		get_detalle(oDetalle) {
			this.bus.$emit("Recovery", oDetalle);
			this.carga = true;
		}
	},
	created() {
		this.bus.$off("Regresar");

		if (this.ocliente != undefined) {
			sessionStorage.setItem("ocliente", JSON.stringify(this.ocliente));
			sessionStorage.setItem(
				"oClienteSucursal",
				JSON.stringify(this.oClienteSucursal)
			);
			sessionStorage.setItem("oEquipo", JSON.stringify(this.oEquipo));
		}

		this.ClienteSucursal = JSON.parse(
			sessionStorage.getItem("oClienteSucursal")
		);
		this.Cliente = JSON.parse(sessionStorage.getItem("ocliente"));
		this.Equipo = JSON.parse(sessionStorage.getItem("oEquipo"));

		this.NameList = this.Equipo.Nequipo + " | " + this.NameList;

		this.bus.$on("Regresar", () => {
			this.$router.push({
				name: "mon_equipo",
				params: { obj: this.ClienteSucursal, objCliente: this.Cliente }
			});
		});
	},
	mounted() {
		this.Lista();
	},
	destroyed() {
		sessionStorage.removeItem("ocliente");
		sessionStorage.removeItem("oClienteSucursal");
		sessionStorage.removeItem("oEquipo");
	}
};
</script>
