<template>
    <div>
        <Clist :regresar="true" :Nombre="NameList" @FiltrarC="Lista" :Filtro="Filtro" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal" :pConfigLoad="ConfigLoad">
            <template slot="header">
                <tr>
                    <th>Tipo de Unidad</th>
                    <th class="text-center">Icono</th>
                    <th>Acciones</th>
                </tr>
            </template>
            <template slot="body">
                <tr v-for="(lista,index) in ListaTipoUnidad" :key="index" >
                    <td>{{lista.Nombre }}</td>
                    <td >
                        <div  class="borde-gris" style="width: 55px;
                        height: 55px;
                        border-radius: 100px;
                        padding: 6px;
                        margin: auto;
                        background: #FFFFFF;
                        border: 3px solid;
                        -webkit-box-pack: center;
                        -ms-flex-pack: center;
                        justify-content: center;
                        /* align-items: center; */
                        display: -webkit-box;
                        display: -ms-flexbox;
                        display: flex;
                        cursor: pointer;
                        -webkit-box-shadow: rgb(0 0 0 / 50%) 0px 3px 3px 0px inset;
                        box-shadow: rgb(0 0 0 / 50%) 0px 3px 3px 0px inset;">
                            <svg-injector  :class-name="'svg-inject iconic-signal-none'" :src="Rutaicono+lista.Imagen"  ></svg-injector>
                        </div>
                    </td>

                    <td>
                        <Cbtnaccion :isModal="EsModal" :Id="lista.IdTipoU" :IrA="FormName" >
                        </Cbtnaccion>
                    </td>
                </tr>
				<CSinRegistros :pContIF="ListaTipoUnidad.length" :pColspan="3" />
            </template>

        </Clist>

        <Modal :poBtnSave="oBtnSave" :size="size" :Nombre="NameForm" >
            <template slot="Form">
                <Form :NameList="NameForm" :poBtnSave="oBtnSave" @titulomodal="Change" ></Form>
            </template>
        </Modal>
    </div>
</template>

<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';
import Form from '@/views/catalogos/tipounidad/Form.vue'
import svgcomponent from '@/views/catalogos/tipounidad/svgcomponent.vue'
import CSinRegistros from "../../../components/CSinRegistros";

export default {
    name :'listConfTipoUnidad',
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        Form,
		svgcomponent,
		CSinRegistros

    },
    data() {
        return {
            FormName:'TipoUnidadForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no,
            CloseModal:true,//indica si el modal cierra o de lo contrario asignarle un evento al boton
            size :"none",
            size2 :"modal-xl",
            NameList:"Tipo Unidad",
            NameForm:"Tipo Unidad",
            urlApi:"tipounidad/get",
            ListaTipoUnidad:[],
            ListaHeader:[],
            TotalPagina:2,
            Pag:0,
            Rutaicono:'',
            Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                TotalItem:0,
                Pagina:1,
                Entrada:10,
            },
            oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
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

			this.ListaTipoUnidad = [];
			await this.$http.get(
				this.urlApi,
				{
					params:{Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina, RegEstatus:'A'}
				}
			).then( (res) => {
				this.ListaTipoUnidad =res.data.data.tipounidad;
				this.Rutaicono=res.data.data.rutaicono;
				this.Filtro.Entrada=res.data.data.pagination.PageSize;
				this.Filtro.TotalItem=res.data.data.pagination.TotalItems;

			}).finally(()=>{
				this.ConfigLoad.ShowLoader = false;
			});
		},

        Eliminar(Id)
        {
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
                    this.$http.delete(
                        'tipounidad/' + Id
                    ).then( (res) => {
                            this.$toast.success('Información eliminada');
                            this.Lista();
                    });

                }
            });
        },

        Change(titulo)
        {
            this.NameForm=titulo;
            var bdn=true;
            if(titulo=='Iconos'){
                bdn=false;
               this.size="modal-xl";
            }
            else{
                this.size="none";
            }
            this.bus.$emit('cambiar_CloseModal',bdn);
        },
        Limpiar(){
            this.bus.$emit('cambiar_CloseModal',true);
        }
    },
    created()
    {
        this.bus.$off('Delete');
        this.bus.$off('List');
        this.bus.$off('Limpiar');
        this.bus.$off('Regresar');
        this.Lista();

        this.bus.$on('Delete',(Id)=>
        {
            this.Eliminar(Id);

        });
        this.bus.$on('List',()=>
        {
            this.Lista();
        });

        this.bus.$on('Limpiar',()=>
        {
            this.Limpiar();
        });
        this.bus.$on('Regresar',()=>
        {
            this.$router.push({name:'submenuadmon'});
        });
    }
}
</script>

<style>
  .thumb-green {fill: #A6A93C;}
</style>
<style type="text/css">


element1.black .svg-pathclass {
 fill:'black'
}

element1.white .svg-pathclass {
 fill: #ffffff;
}
</style>

<style>
    .cls-1{fill:#fff}
    .background{fill:#f00}
    .foreground{fill:#00f}
    .trim{fill:#0f0}


    /*Strong signal*/
.iconic-signal.iconic-signal-strong .iconic-signal-base {
    fill:#569e26;
}
.iconic-signal.iconic-signal-strong .iconic-signal-wave * {
    stroke:#569e26;
}

/*Medium signal*/
.iconic-signal.iconic-signal-medium .iconic-signal-base {
    fill:#efc411;
}
.iconic-signal.iconic-signal-medium .iconic-signal-wave * {
    stroke:#efc411;
}
.iconic-signal.iconic-signal-medium .iconic-signal-wave-outer {
    opacity:.3;
    stroke-width:.5;
}

/*Weak signal*/
.iconic-signal.iconic-signal-weak .iconic-signal-base {
    fill:#b52808;
}
.iconic-signal.iconic-signal-weak .iconic-signal-wave * {
    stroke:#b52808;
}
.iconic-signal.iconic-signal-weak .iconic-signal-wave-outer ,
.iconic-signal.iconic-signal-weak .iconic-signal-wave-middle {
    opacity:.3;
    stroke-width:.5;
}

/*No signal*/
.iconic-signal.iconic-signal-none .iconic-signal-base {
    fill:#848484;
}
.iconic-signal.iconic-signal-none .iconic-signal-wave * {
    stroke:#848484;
    opacity:.3;
    stroke-width:.5;
}
</style>
