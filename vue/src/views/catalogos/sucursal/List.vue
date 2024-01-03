<template>
	<div>
		<Clist @FiltrarC="Lista" :Filtro="Filtro" :pShowBtnAdd="false" :PNameButonNuevo="NameButonNuevo" :regresar="ShowButtons2"
			   :Nombre="NameList" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal" :pConfigLoad="ConfigLoad">
            <template slot="header">
                   <tr>
					   <th>Nombre</th>
					   <th>Teléfono</th>
					   <th>Dirección</th>
					   <th>Correo</th>
					   <th>Estado</th>
					   <th>Código Postal</th>
                       <!-- <th>Acciones</th>-->
                    </tr>
            </template>

             <template  slot="body">
				 <tr v-for="(lista,index) in ListaSucursal" :key="index" >
					 <td>{{lista.Nombre }}</td>
					 <td>{{lista.Telefono }}</td>
					 <td>{{lista.Direccion}}</td>
					 <td>{{lista.Correo}}</td>
					 <td>{{lista.Estado }}</td>
					 <td>{{lista.CP }}</td>
                        <!--<td  >
                          <Cbtnaccion v-if="ShowButtons2" :ShowButtonG="false" :isModal="EsModal" :Id="lista.IdSucursal" :IrA="FormName" >
                              <template    slot="btnaccion">
                                <button type="button" @click="paquetes(lista.IdSucursal)"  data-toggle="modal" data-target="#ModalForm"  class="btn btn-table"> <span class="fa fa-box"></span> </button>
                              </template>
                          </Cbtnaccion>
                        </td>-->
				 </tr>
				 <CSinRegistros :pContIF="ListaSucursal.length" :pColspan="6" ></CSinRegistros>
            </template>

        </Clist>
	</div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';
import ModalPaquetes from '@/views/catalogos/sucursal/Paquetes.vue'
import Form from '@/views/catalogos/sucursal/Form.vue'
import CSinRegistros from "../../../components/CSinRegistros";

export default {
	name :'listConfSucursales',
    props:['Id','PShowButtons'],
    components :{
        Modal,
        Clist,
		Cbtnaccion,
		Form,
		ModalPaquetes,
		CSinRegistros
    },
    data() {
        return {
            FormName:'TipoUnidadForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"modal-lg",
            NameList:"Sucursales",
            urlApi:"sucursal/get",
            ListaSucursal:[],
            IdSucursal:0,
            NombrePaq:'Paquetes',
            ListaHeader:[],
            TotalPagina:2,
                tipomodal:1,
            Pag:0,
            IdEmpresa:0,
            ShowButtons2:true,
            NameButonNuevo:'Nuevo',
            Filtro:{
                Nombre:'',
                Placeholder:'Sucursal..',
                TotalItem:0,
                Pagina:1

            },
			ConfigLoad:{
				ShowLoader:true,
				ClassLoad:true,
			}

        }
    },
    methods: {
		async Lista() {
			this.ConfigLoad.ShowLoader = true;

			await this.$http.get(
				this.urlApi,
				{
					params:{Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina, RegEstatus:'A'}
				}
			).then( (res) => {
				this.ListaSucursal =res.data.data.sucursal;
				this.Filtro.Entrada=res.data.data.pagination.PageSize;
				this.Filtro.TotalItem=res.data.data.pagination.TotalItems;

			}).finally(()=>{
				this.ConfigLoad.ShowLoader = false;
			});

		},

		IrHorasLaborales(Id) {
			this.$router.push({name:'horaslaborales', params:{IdSucursal:Id}})
        },

		paquetes(Id) {
			// this.bus.$emit('Nuevo',false,Id);
			this.IdSucursal=Id;this.tipomodal=2;
			this.size ="modal-dialog";
			this.NameList="Paquetes";
        },

        Eliminar(Id) {

			this.$swal({
                title: 'Esta seguro que desea eliminar este dato?',
                text: 'No se podra revertir esta acción',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No, mantener',
                showCloseButton: true,
                showLoaderOnConfirm: true
                }).then((result) => {
                if(result.value) {

                    this.$swal({showConfirmButton: true,timer: 1000,title: 'Inoformación Eliminada'});

                     this.$http.delete(
                            'sucursal/' + Id
                        ).then( (res) => {
                                this.Lista();
                        });

                    }
                });
        },

    },
    created()
    {

        if (this.Id!=undefined)
        {
         sessionStorage.setItem('IdSaved', this.Id);
        }
        this.IdEmpresa= sessionStorage.getItem('IdSaved');

        if(this.PShowButtons!=undefined){
            sessionStorage.setItem('ShowButton2',this.PShowButtons);
        }
        if(sessionStorage.getItem('ShowButton2')=='true'){
            this.ShowButtons2=true;
            this.NameButonNuevo='Nuevo';
        }else{
            this.ShowButtons2=true;
            this.NameButonNuevo='Perfil';
        }

         this.bus.$off('Delete');
         this.bus.$off('Regresar');
         this.Lista();
          this.bus.$on('Delete',(Id)=>
        {
            this.Eliminar(Id);

        });
          this.bus.$on('Regresar',()=>
        {
            if(this.NameButonNuevo=='Perfil'){
                this.$router.push({name:'submenuadmon'});
            }else{
                this.$router.push({name:'empresas',params:{IdRegresar:0}});
            }

        });
    }
}
</script>
