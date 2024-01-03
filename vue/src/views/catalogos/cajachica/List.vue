<template>
	<div>
		<Clist
			:regresar="true"
			@FiltrarC="Lista"
			:Filtro="Filtro"
			:Nombre="NameList"
			:isModal="EsModal"
            :pConfigLoad="ConfigLoad"
		>
			<template slot="Filtros">
				<div class="form-group ml-2">
					<label class="mr-1">Estatus:</label>
					<select @change="Lista" v-model="Filtro.Estado" class="form-control">
						<option value="">Todos</option>
						<option value="Abierta">Abiertas</option>
						<option value="Cerrado">Cerradas</option>
					</select>
				</div>
			</template>

			<template slot="header">
				<tr>
					<th>Nombre</th>
					<th>Fecha Inicio</th>
					<th>Fecha Fin</th>
					<th>Monto</th>
					<th>Estado</th>
					<th>Acciones</th>
				</tr>
			</template>

			<template slot="body">
				<tr v-for="(lista, key, index) in ListaCajaChica" :key="index">
					<td>{{ lista.Caja }}</td>
					<td><i class="fas fa-calendar-day"></i> {{ lista.FechaInicio }}</td>
					<td><i class="fas fa-calendar-day"></i> {{ lista.FechaFin }}</td>
					<td>$ {{ Number(lista.Monto).toLocaleString() }}</td>
					<td>{{ lista.Estado }}</td>
					<td>
						<Cbtnaccion :isModal="EsModal" :Id="lista.IdCajaC" :IrA="FormName">
							<template slot="btnaccion"> </template>
						</Cbtnaccion>
					</td>
				</tr>
                <CSinRegistros :pContIF="ListaCajaChica.length" :pColspan="6" ></CSinRegistros>
			</template>
		</Clist>

		<Modal :size="size" :Nombre="NameList" :poBtnSave="oBtnSave">
			<template slot="Form">
				<Form :poBtnSave="oBtnSave"> </Form>
			</template>
		</Modal>
	</div>
</template>
<script>
import Modal from "@/components/Cmodal.vue";
import Clist from "@/components/Clist.vue";
import Cbtnaccion from "@/components/Cbtnaccion.vue";
import Form from "@/views/catalogos/cajachica/Form.vue";
import CSinRegistros from "../../../components/CSinRegistros";

export default {
	name: "listRegistrosCajaChica",
	components: {
		Modal,
		Clist,
		Cbtnaccion,
		Form,
        CSinRegistros
	},
	data() {
		return {
			FormName: "folioForm", //Por si no es modal y queremos ir a una vista declarada en el router
			EsModal: true, //indica si es modal o no
			size: "modal-md",
			NameList: "Registros",
			urlApi: "cajachica/get",
			ListaCajaChica: [],
			ListaHeader: [],
			Filtro: {
				Nombre: "",
				Placeholder: "Nombre..",
				TotalItem: 0,
				Pagina: 1,
				Estado: "Abierta",
				Entrada: 10
			},
			oBtnSave: {
				//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
				isModal: true,
				disableBtn: false,
				toast: 0
			},
            ConfigLoad:{
                ShowLoader:true,
                ClassLoad:true,
            },
		};
	},
	methods: {
		GuardarDesdeList() {},
		Eliminar(Id) {
			this.$swal({
				title: "Esta seguro que desea eliminar este dato?",
				text: "No se podra revertir esta acción",
				type: "warning",
				showCancelButton: true,
				confirmButtonText: "Si",
				cancelButtonText: "No, mantener",
				showCloseButton: true,
				showLoaderOnConfirm: true
			}).then(result => {
				if (result.value) {
					this.$http.delete("cajachica/" + Id).then(res => {
						this.$toast.success("Información eliminada");
						this.Lista();
					});
				}
			});
		},
		async Lista() {
            this.ConfigLoad.ShowLoader = true;

			await this.$http
				.get(this.urlApi, {
					params: {
						Nombre: this.Filtro.Nombre,
						Entrada: this.Filtro.Entrada,
						pag: this.Filtro.Pagina,
						RegEstatus: "A",
						Estado: this.Filtro.Estado
					}
				})
				.then(res => {
					this.ListaCajaChica     = res.data.data.cajachica;
					this.Filtro.Entrada     = res.data.data.pagination.PageSize;
					this.Filtro.TotalItem   = res.data.data.pagination.TotalItems;
                    this.Filtro.Pagina 	    = res.data.data.pagination.CurrentPage;

				}).finally(()=>{
                    this.ConfigLoad.ShowLoader = false;
                });
		}
	},
	created() {
		this.bus.$off("Delete");
		this.bus.$off("List");
		this.bus.$off("Regresar");
		this.Lista();
		this.bus.$on("Delete", Id => {
			this.Eliminar(Id);
		});
		this.bus.$on("List", () => {
			this.Lista();
		});
		this.bus.$on("Regresar", () => {
			this.$router.push({ name: "cajachica" });
		});
	}
};
</script>
