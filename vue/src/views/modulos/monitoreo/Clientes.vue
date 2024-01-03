<template>
	<div>
		<Clist

			@FiltrarC="Lista"
			:Filtro="Filtro"
			:regresar="ShowBtns"
			:pShowBtnAdd="ShowBtns"
			:Nombre="NameList"
			:isModal="EsModal"
			:pConfigLoad="ConfigLoad"
		>
			<template slot="header">
				<tr>
					<th>Cliente</th>
					<th>Teléfono</th>
					<th>Dirección</th>
					<th>Correo</th>
					<th>Ciudad</th>
					<th class="text-center tw-1">Sucursales</th>
					<th class="text-center tw-2">Acciones</th>
				</tr>
			</template>

			<template slot="body">
				<tr v-for="(lista, key, index) in ListaClientes" :key="index">
					<td class="table__td table__td--md">{{ lista.Nombre }}</td>
					<td class="table__td table__td--sm">{{ lista.Telefono }}</td>
					<td class="table__td table__td--sm">{{ lista.Direccion }}</td>
					<td class="table__td table__td--sm">{{ lista.Correo }}</td>
					<td class="table__td table__td--sm">
						{{ lista.Ciudad }} {{ lista.Incidencia }} {{ lista.Chat }}
					</td>
					<td class="text-center tw-1">
						<button
							@click="go_to_sucursal(lista)"
							class="btn btn-table pl-01 relative"
							v-b-tooltip.hover
							title="Sucursales"
						>
							<i class="fas fa-building fa-fw-m"></i>
							<span :class=" lista.Incidencia > 0 ? 'noti-01 bg-warning' : 'noti-01 bg-success'"></span>
						</button>
					</td>
					<td class="text-center tw-2">
						<button
							@click="go_to_cotizacion(lista, 1)"
							class="btn btn-table pl-01 mr-1"
							v-b-tooltip.hover
							title="Cotizaciones"
						>
							<i class="fas fa-file-invoice-dollar fa-fw-m"></i>
						</button>
						<button
							@click="go_to_cotizacion(lista, 2)"
							class="btn btn-table pl-01 mr-1"
							v-b-tooltip.hover
							title="Reportes"
						>
							<i class="fas fa-file-alt fa-fw-m"></i>
						</button>
						<button
							@click="got_to_seguimiento(lista)"
							class="btn btn-table relative"
							v-b-tooltip.hover
							title="Seguimiento"
						>
							<i class="fas fa-comments-alt fa-fw-m"></i>
							<span class="noti-01 bg-danger">
								<span class="number">{{ lista.Chat }}</span>
							</span>
						</button>
					</td>
				</tr>
				<CSinRegistros :pContIF="ListaClientes.length" :pColspan="7" ></CSinRegistros>
			</template>
		</Clist>

		<Modal :size="size" :Nombre="NameList">
			<template slot="Form"> </template>
		</Modal>
	</div>
</template>
<script>
import Modal from "@/components/Cmodal.vue";
import Clist from "@/components/Clist.vue";
import CSinRegistros from "../../../components/CSinRegistros";
export default {
	name: "list",
	props: ["tipolistp"],
	components: {
		Modal,
		Clist,
		CSinRegistros
	},
	data() {
		return {
			FormName: "clientesForm", //Por si no es modal y queremos ir a una vista declarada en el router
			EsModal: true, //indica si es modal o no
			size: "modal-xl",
			NameList: "Lista Clientes",
			urlApi: "monitoreo/custumers",
			ListaClientes: [],
			ShowBtns: false,
			TipoList: "",
			Filtro: {
				Nombre: "",
				Placeholder: "Cliente..",
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
				.get(this.urlApi, {
					params: {
						Nombre: this.Filtro.Nombre,
						Entrada: this.Filtro.Entrada,
						pag: this.Filtro.Pagina,
						RegEstatus: "A"
					}
				})
				.then(res => {
					this.ListaClientes = res.data.data.clientes;
					this.Filtro.Entrada = res.data.data.pagination.PageSize;
					this.Filtro.TotalItem = res.data.data.pagination.TotalItems;
				}).finally(()=>{
					this.ConfigLoad.ShowLoader = false;
				});
		},
		go_to_sucursal(objcliente) {
			this.$router.push({
				name: "mon_sucursal",
				params: { ocliente: objcliente }
			});
		},
		go_to_cotizacion(objcliente, Tipo) {
			this.$router.push({
				name: "mon_cotizacion",
				params: { ocliente: objcliente, pTipo: Tipo }
			});
		},
		got_to_seguimiento(objcliente) {
			this.$router.push({
				name: "mon_solicitudes",
				params: { ocliente: objcliente }
			});
		}
	},
	created() {
		this.bus.$off("List");
		this.bus.$off("Regresar");
		this.Lista();

		this.bus.$on("List", () => {
			this.Lista();
		});
	}
};
</script>
