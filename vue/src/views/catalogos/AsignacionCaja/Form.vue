<template>
	<div>
		<div class="row">
			<div class="col-lg-6 form-group">
				<div class="row pl-3">
					
					<b-avatar
						:variant="AvatarData().Color"
						:text="AvatarData().Nombre"
					></b-avatar>
					<!-- <b-avatar variant="info" src="https://placekitten.com/300/300"></b-avatar>-->
					<div class="pl-3">
						<h5> {{ trabajador.Nombre }} </h5>
						<b style="font-size: 1rem">{{ trabajador.Perfil }}</b>
					</div>
				</div>
				
			
			</div>
			
			<div class="col-lg-4 form-group">
				<select v-model="IndexSelec" class="form-control ">
					<option :value="-1">-- Seleccione Viático --</option>
					<option
						v-for="(item, index) in ListaCajas"
						:key="index"
						:value="index"
					>
						{{ item.Caja }}</option
					>
				</select>
			</div>
			<div class="col-lg-2 form-group text-right">
				<b-button @click="AgregarCaja" class="btn btn-sm btn-01 save"
					>Agregar</b-button
				>
			</div>
			<div class="col-lg-12 form-group">
				<table class="table-01">
					<thead>
						<tr>
							<th class="text-center">Víatico</th>
							<th class="text-center" scope="col">Monto</th>
							<th class="text-center" width="100" scope="col">Asignar</th>
							<th class="text-center" scope="col">Saldo</th>
							<th class="text-center" width="100" scope="col">Acción</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(item, index) in ListaAsignadaTemp" :key="index">
							<td class="table__td table__td--sm text-center">{{ item.Caja }}</td>
							<td class="text-center table__td table__td--sm">$ {{ Number(item.Utilizado).toLocaleString() }}</td>
							
							<td>
								<vue-numeric
									class="form-control"
									currency="$"
									separator=","
									:precision="2"
									placeholder="$ 0.00"
									:minus="false"
									@input="Asignar(index)"
									v-model="item.Asignar"
								></vue-numeric>
							</td>
							<td class="text-center">
								$ {{ Number(item.MontoActual).toLocaleString() }}
							</td>
							<td class="text-center">
								<button
									v-if="loading"
									class="btn-icon mr-2 float-left edit"
									type="button"
									disabled
									id="edit_button1"
									value="Edit"
									onclick="edit_row('1')"
								>
									<b-spinner small></b-spinner>
								</button>
								<button
									v-else
									@click="Guardar(index)"
									class="btn-icon mr-2 float-left edit"
									type="button"
									id="edit_button1"
									value="Guardar"
								>
									<i class="fas fa-save"></i>
								</button>
								<button
									class="btn-icon mr-2 float-left edit"
									type="button"
									@click="Eliminar(index)"
								>
									<i class="fas fa-trash fa-fw-m"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<!--Fin body del panel-->
	</div>
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from "@/components/Cbtnsave.vue";
import Cvalidation from "@/components/Cvalidation.vue";
export default {
	name: "Form",
	props: ["IdTrabajador"],
	data() {
		return {
			Modal: true, //Sirve pra los botones de guardado
			FormName: "caja", //Sirve para donde va regresar
			trabajador: {},
			asignacioncaja: {
				IdTrabajador: 0,
				MontoAsignado: ""
			},
			urlApi: "asignacioncaja/recovery",

			errorvalidacion: [],
			ListaCajas: [],
			ListaAsignada: [],
			ListaAsignadaTemp: [],
			IndexSelec: -1,
			objeto: { IdTrabajador: 0, IdCajaC: 0, MontoAsignado: 0, Asignado: 0 },
			loading: false,
			Tipo: ""
		};
	},
	components: {
		Cbtnsave,
		Cvalidation
	},
	methods: {
		async Guardar(index) {
			this.objeto.IdTrabajador = this.asignacioncaja.IdTrabajador;
			this.objeto.IdCajaC = this.ListaAsignadaTemp[index].IdCajaC;
			this.objeto.MontoAsignado = this.ListaAsignadaTemp[index].MontoActual;
			this.objeto.Asignado = this.ListaAsignadaTemp[index].Asignar;
			if (
				this.ListaAsignadaTemp[index].Asignar == "" ||
				this.ListaAsignadaTemp[index].Saldo == 0
			) {
				this.$toast.info("No hay nada por asignar");
				return false;
			}
			this.loading = true;

			this.$http
				.post("asignacioncaja/post", this.objeto)
				.then(res => {
					this.$toast.success("Información Guardada");

					this.ListaAsignada[index].UtilizadoActual = this.ListaAsignadaTemp[
						index
					].Utilizado;
					this.ListaAsignada[index].MontoActual = this.ListaAsignadaTemp[
						index
					].MontoActual;
					this.ListaAsignada[index].Actual = this.ListaAsignadaTemp[
						index
					].MontoActual;
					this.ListaAsignadaTemp[index].Actual = this.ListaAsignada[
						index
					].MontoActual;
					this.ListaAsignadaTemp[index].Asignar = "";
					this.loading = false;
				})
				.catch(err => {
					this.errorvalidacion = err.response.data.message.errores;
					this.loading = false;
				});
		},
		async Eliminar(index) {
			this.loading = true;

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
					this.$toast.success("Información eliminada");

					this.$http
						.delete(
							"asignacioncaja/" +
								this.trabajador.IdTrabajador +
								"/" +
								this.ListaAsignadaTemp[index].IdCajaC
						)
						.then(res => {
							this.get_list();
						});
					this.loading = false;
				} else {
					this.loading = false;
				}
			});
		},
		Limpiar() {
			this.ListaAsignada = [];
			this.ListaAsignadaTemp = [];
		},
		get_list() {
			this.$http
				.get("asignacioncaja/list", {
					params: { IdTrabajador: this.asignacioncaja.IdTrabajador }
				})
				.then(res => {
					this.ListaAsignada = res.data.data.asignados;
					this.ListaAsignadaTemp = res.data.data.asignados2;
				});
		},
		get_trabajador() {
			this.$http
				.get("trabajador/recovery", {
					params: { IdTrabajador: this.asignacioncaja.IdTrabajador }
				})
				.then(res => {
					this.trabajador = res.data.data.trabajador;
				});
		},
		get_cajas() {
			var f = new Date();
			this.$http
				.get("cajachica/get", {
					params: {
						Monto: "Mayor",
						Estado: "Abierta",
						FechaFin: f,
						Tipo: this.Tipo
					}
				})
				.then(res => {
					this.ListaCajas = res.data.data.cajachica;
				});
		},
		AgregarCaja() {
			if (this.IndexSelec != -1) {
				let found = this.ListaAsignadaTemp.find(
					element => element.IdCajaC == this.ListaCajas[this.IndexSelec].IdCajaC
				);

				if (found == undefined) {
					this.ListaAsignadaTemp.push({
						IdCajaC: this.ListaCajas[this.IndexSelec].IdCajaC,
						Caja: this.ListaCajas[this.IndexSelec].Caja,
						Utilizado: this.ListaCajas[this.IndexSelec].Utilizado,
						Asignar: 0,
						MontoActual: 0,
						Actual: 0
					});
					this.ListaAsignada.push({
						IdCajaC: this.ListaCajas[this.IndexSelec].IdCajaC,
						Caja: this.ListaCajas[this.IndexSelec].Caja,
						Utilizado: this.ListaCajas[this.IndexSelec].Utilizado,
						Asignar: 0,
						MontoActual: 0,
						Actual: 0,
						UtilizadoActual: this.ListaCajas[this.IndexSelec].Utilizado
					});
					this.$toast.info("Caja agregada");
				} else {
					this.$toast.info("Este viático ya está asigando");
				}
			} else {
				this.$toast.info("Seleccione un viático");
			}
		},
		Asignar(index) {
			if (
				this.ListaAsignadaTemp[index].Asignar != "" &&
				this.ListaAsignadaTemp[index].Asignar != undefined
			) {
				if (
					parseFloat(this.ListaAsignadaTemp[index].Asignar) >
					parseFloat(this.ListaAsignadaTemp[index].Utilizado)
				) {
					this.$toast.info("La asignación no puede ser mayor");
					this.ListaAsignadaTemp[index].Asignar = "";
					this.ListaAsignadaTemp[index].MontoActual = this.ListaAsignada[
						index
					].Actual;
					this.ListaAsignadaTemp[index].Utilizado = this.ListaAsignada[
						index
					].UtilizadoActual;
				} else {
					let monto = this.ListaAsignadaTemp[index].Actual;
					let asignar = this.ListaAsignadaTemp[index].Asignar;
					let utilizado = this.ListaAsignada[index].UtilizadoActual;

					this.ListaAsignadaTemp[index].MontoActual =
						parseFloat(monto) + parseFloat(asignar);
					this.ListaAsignadaTemp[index].Utilizado =
						parseFloat(utilizado) - parseFloat(asignar);
				}
			} else {
				this.ListaAsignadaTemp[index].MontoActual = this.ListaAsignada[
					index
				].Actual;
				this.ListaAsignadaTemp[index].Utilizado = this.ListaAsignada[
					index
				].UtilizadoActual;
			}
		},
		AvatarData() {
			var Arreglo = { Nombre: "", Color: "" };

			var name = this.trabajador.Nombre;
			if (name != undefined) {
				var nameSplit = name.split(" ");
				var initials;
				if (nameSplit.length > 1) {
					initials =
						nameSplit[0].charAt(0).toUpperCase() +
						nameSplit[1].charAt(0).toUpperCase();
				} else {
					initials = nameSplit[0].charAt(0).toUpperCase();
				}

				var colours = ["secondary", "secondary"];
				const randomMonth = colours[Math.floor(Math.random() * colours.length)];
				var Arreglo = { Nombre: initials, Color: randomMonth };
			}
			return Arreglo;
		}
	},
	created() {
		this.bus.$off("Save");
		this.bus.$off("Nuevo");
		this.bus.$on("Save", () => {
			this.Guardar();
		});

		//Este es para modal
		this.bus.$on("Nuevo", (Id, Tipo) => {
			this.Limpiar();
			if (Id > 0) {
				this.asignacioncaja.IdTrabajador = Id;
				this.Tipo = Tipo;
				this.get_list();
				this.get_trabajador();
				this.get_cajas();
			}
			this.bus.$emit("Desbloqueo", false);
		});
		if (this.Id != undefined) {
			this.asignacioncaja.IdTrabajador = this.Id;
			this.get_one();
		}
	}
};
</script>
