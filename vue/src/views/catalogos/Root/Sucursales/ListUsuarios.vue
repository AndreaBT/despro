<template>
	<div>
		<Clist
				@FiltrarC="Lista"
				:Filtro="Filtro"
				:PNameButonNuevo="NameButonNuevo"
				:regresar="ShowButtons2"
				:Nombre="NameList"
				:isModal="EsModal"
				:pConfigLoad="ConfigLoad"
				:pShowBtnAdd="false"
				>
			<template slot="header">
				<tr>
					<th>Nombre</th>
					<th>Usuario</th>
					<th>Acciones</th>
					<!--<th>Acciones</th>-->
				</tr>
			</template>
			<template slot="body">
				<tr v-for="(lista, index) in ListaUsuarios" :key="index">
					<td>{{ lista.Nombre }} {{ lista.Apellido }}</td>
					<td>{{ lista.Candado }}</td>
					<td>
						<button
							type="button"
							@click="IrSESION(lista.IdUsuario)"
							v-b-tooltip.hover.right
							title="Iniciar Sesión"
							data-toggle="modal"
							data-target="#ModalForm"
							class="btn btn-table"
						>
							<span class="fa fa-id-card"></span>
						</button>
					</td>
				</tr>

				<CSinRegistros :pContIF="ListaUsuarios.length" :pColspan="4" ></CSinRegistros>
			</template>
		</Clist>
	</div>
</template>
<script>
import Modal from "@/components/Cmodal.vue";
import Clist from "../../../../components/Clist";
import Cbtnaccion from "@/components/Cbtnaccion.vue";
import ModalPaquetes from "@/views/catalogos/sucursal/Paquetes.vue";
import Form from "@/views/catalogos/sucursal/Form.vue";
import FormEmpresa from "@/views/catalogos/empresas/Form.vue";
import CSinRegistros from "../../../../components/CSinRegistros";

export default {
	props: ["IdSucursal", "PShowButtons", "IdEmpresa"],
	name: "list",
	components: {
		Modal,
		Clist,
		Cbtnaccion,
		Form,
		ModalPaquetes,
		FormEmpresa,
		CSinRegistros
	},
	data() {
		return {
			FormName: "TipoUnidadForm", //Por si no es modal y queremos ir a una vista declarada en el router
			EsModal: true, //indica si es modal o no
			size: "modal-lg",
			NameList: "Usuarios",
			urlApi: "sucursal/get",
			ListaUsuarios: [],
			NombrePaq: "Paquetes",
			ListaHeader: [],
			TotalPagina: 2,
			tipomodal: 1,
			Pag: 0,
			IdEmpresa2: 0,
			ShowButtons2: true,
			NameButonNuevo: "Nuevo",
			Filtro: {
				Entrada: 10,
				Nombre: "",
				Placeholder: "Usuario..",
				TotalItem: 0,
				Pagina: 1,
				Show: true
			},
			usuario: { IdUsuario: 0 },
			IdSucursal2: 0,
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
				.get("usuariosucursal/get", {
					params: {
						IdSucursal: this.IdSucursal2,
						Nombre: this.Filtro.Nombre,
						Entrada: this.Filtro.Entrada,
						pag: this.Filtro.Pagina,
						RegEstatus: "A"
					}
				})
				.then(res => {
					this.ListaUsuarios 		= res.data.data.usuarios;
					this.Filtro.Entrada 	= res.data.data.pagination.PageSize;
					this.Filtro.TotalItem 	= res.data.data.pagination.TotalItems;
					this.Filtro.Pagina 		= res.data.data.pagination.CurrentPage;

				}).finally(()=>{
					this.ConfigLoad.ShowLoader = false;
				});
		},

		IrSESION(Id) {
			//console.log('IrSESION');
			this.usuario.IdUsuario = Id;
			this.$http
				.post("loginroot/post", this.usuario)
				.then(res => {
					if (res.data.status == true) {
						this.$store.dispatch("login", res.data.data);
						let IdEmpresa = res.data.data.usuario.IdEmpresa;

						if (IdEmpresa > 0) {
							//console.log('Pasa Sesion');
							this.$router.push({ name: "despacho", params: {} });
							this.$router.go(0);

						}
					}
				})
				.catch(err => {
					this.$toast.info(err.response.data.message);
					this.$store.commit("auth_error");
					this.$store.localStorage.removeItem("user_token");
					this.$store.reject(err);
				});
		}
	},
	created() {
		this.bus.$off("Regresar");

		if (this.IdSucursal != undefined) {
			sessionStorage.setItem("IdSaved", this.IdSucursal);
		}
		if (this.IdEmpresa != undefined) {
			sessionStorage.setItem("IdSaved2", this.IdEmpresa);
		}

		this.IdSucursal2 = sessionStorage.getItem("IdSaved");
		this.IdEmpresa2 = sessionStorage.getItem("IdSaved2");
		this.Lista();

		this.bus.$on("Regresar", () => {
			this.$router.push({
				name: "sucursalroot",
				params: { Id: this.IdEmpresa2, PShowButtons: true }
			});
		});
	}
};
</script>
