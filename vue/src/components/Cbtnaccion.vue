<template>
	<div>
		<span v-if="ShowButtonGeneral">
			<span v-if="ShowButtonEdit">
				<button
					v-b-tooltip.hover.right
					v-if="Esmodal"
					title="Editar"
					@click="Editar(Id, Esmodal)"
					type="button"
					class="btn-icon mr-2"
					data-toggle="modal"
					data-target="#ModalForm"
					data-backdrop="static"
					data-keyboard="false"
				>
					<i class="fas fa-pencil-alt"></i>
				</button>
				<button
					v-b-tooltip.hover.right
					v-else
					title="Editar"
					@click="Editar(Id, Esmodal)"
					type="button"
					class="btn-icon mr-2"
					data-backdrop="static"
					data-keyboard="false"
				>
					<i class="fas fa-pencil-alt"></i>
				</button>
			</span>
		</span>
		<slot name="btnaccion"> </slot>
		<!-- Perfil "Admin" corresponde a IdPerfil 2 (después de normalizar), candado "admin" corresponde a Sofanor -->
		<span v-if="PermisoAdmin.Perfil == 'Admin' || PermisoAdmin.Candado == 'admin'">
			<span v-if="ShowButtonGeneral">
				<span v-if="ShowButtonDelete">
					<button
						v-b-tooltip.hover.right
						title="Eliminar"
						v-if="this.Btndelete"
						@click="Delete(Id)"
						type="button"
						class="btn-icon-02 mr-2"
					>
						<i class="fas fa-trash"></i>
					</button>
				</span>
			</span>
		</span>
		

		<!--
    <a-dropdown :trigger="['click']">
        <a class=" far fa-ellipsis-v fa-2x color-pantone-01" @click="e => e.preventDefault()">
      
    </a>
    <a-menu slot="overlay">
       <a-menu-item   key="0">
         
                                                        
      </a-menu-item>
      <a-menu-item v-if="ShowButtonGeneral" key="1">
           
      </a-menu-item>
      <a-menu-divider />
      
    </a-menu>
  </a-dropdown>-->
	</div>
</template>

<script>
export default {
	name: "Cbtnaccion",
	props: [
		"isModal",
		"Id",
		"IrA",
		"ShowButtonG",
		"PBtndelete",
		"pShowButtonEdit",
		"pShowButtonDelete"
	],
	data() {
		return {
			Esmodal: this.isModal,
			ShowButtonGeneral: true,
			Btndelete: true,
			ShowButtonEdit: true,
			ShowButtonDelete: true,
			PermisoAdmin: []
		};
	},
	methods: {
		Editar(Id) {
			if (this.Esmodal == true) {
				this.bus.$emit("Nuevo", false, Id);
			} else {
				//this.$root.$emit('Nuevo',false,Id);
				this.$router.push({ name: this.IrA, params: { Id: Id } });
			}
		},
		Delete(Id) {
			this.bus.$emit("Delete", Id);
		}
	},
	created() {
		//sesión donde se obtiene permisos de administrador eliminar
		this.PermisoAdmin = JSON.parse(sessionStorage.getItem("user"));

		if (this.ShowButtonG != undefined) {
			this.ShowButtonGeneral = this.ShowButtonG;
		}

		if (this.PBtndelete != undefined) {
			this.Btndelete = this.PBtndelete;
		}
		if (this.pShowButtonEdit != undefined) {
			this.ShowButtonEdit = this.pShowButtonEdit;
		}
		if (this.pShowButtonDelete != undefined) {
			this.ShowButtonDelete = this.pShowButtonDelete;
		}
	}
};
</script>
