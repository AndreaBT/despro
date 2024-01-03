<template>
    <div class="row  justify-content-center">
        <div class="col-lg-12">
            <label >Nombre</label>
            <input  class="form-control" v-model=" objoportunidad.Nombre" placeholder="Nombre">
            <Cvalidation v-if="this.errorvalidacion.Nombre" :Mensaje="errorvalidacion.Nombre[0]"></Cvalidation>
        </div>

        <div class="col-lg-10">
            <label >Sucursal</label>
            <input  class="form-control" readonly="readonly" v-model=" objoportunidad.Sucursal" placeholder="Sucursal">
            <Cvalidation v-if="this.errorvalidacion.Sucursal" :Mensaje="errorvalidacion.Sucursal[0]"></Cvalidation>
        </div>

        <div class="col-lg-2">
            <div class="form-inline">
                <button @click="ListarCli"  data-toggle="modal" data-target="#ModalForm3"  data-backdrop="static" type="button" class="btn btn-01 search mt-3b">Buscar</button>
            </div>
        </div>

        <div class="col-lg-6">
            <label>Vendedor </label>
            <select @change="listartipo(objoportunidad.IdVendedor)"  v-model="objoportunidad.IdVendedor" class="form-control form-control-sm">
                <option value="">Seleccione una opción</option>
                <option v-for="(item, index) in Listatipoproceso" :key="index" :value="item.IdUsuario">{{item.NombreTrabajador}}</option>
            </select>
            <Cvalidation v-if="this.errorvalidacion.Vendedor" :Mensaje="errorvalidacion.Vendedor[0]"></Cvalidation>
        </div>

        <div class="col-lg-6">
            <label>Tipos de Proceso</label>
            <select v-model=" objoportunidad.IdTipoP" class="form-control form-control-sm">
                <option value="">Seleccione una opción</option>
                <option v-for="(item, index) in Listatipos" :key="index" :value="item.IdTipoProceso">{{item.Nombre}}</option>
            </select>
            <Cvalidation v-if="this.errorvalidacion.Proceso" :Mensaje="errorvalidacion.Proceso[0]"></Cvalidation>
        </div>
    </div>
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from '@/components/Cbtnsave.vue'
import Cvalidation from '@/components/Cvalidation.vue'

export default {
    name:'Form',
        props:['IdOportunidad','poBtnSave'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'objoportunidad',//Sirve para donde va regresar
            objoportunidad:{
                Nombre:"",
                IdVendedor:"",
                IdClienteS:"",
                IdTipoP:"",
                IdOportunidad:'',
                Sucursal:''
            },
            urlApi:"crmoportunidad/recovery",
            urlApiVendedor:"trabajador/ListTrabRolQuery",
            urlApiVendedorNuevo:"vendedores/get",
            errorvalidacion:[],
            Listatipoproceso:[],
            Listatipos:[],
            perfil :['Vendedor','Gerente de ventas']
        }
    },
    components:{
        Cbtnsave,
        Cvalidation,
    },
    methods :
    {
       async Guardar()
        {
            //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;
            let formData = new FormData();
            formData.set('IdOportunidad',this.objoportunidad.IdOportunidad);
            formData.set('Nombre',this.objoportunidad.Nombre);
            formData.set('IdVendedor',this.objoportunidad.IdVendedor);
            formData.set('IdClienteS',this.objoportunidad.IdClienteS);
            formData.set('IdTipoP',this.objoportunidad.IdTipoP);

            await this.$http.post(
                'crmoportunidad/post',
                formData,
                {
                headers: {
                    'Content-Type': 'multipart/form-data'
                    
                }
                },
            ).then( (res) => {
                this.poBtnSave.disableBtn=false;  
                this.poBtnSave.toast=1;
                $('#ModalForm').modal('hide');
                this.bus.$emit('List'); 
            }).catch( err => {
                this.errorvalidacion=err.response.data.message.errores;
                this.poBtnSave.disableBtn=false;
                this.poBtnSave.toast=2;  
            });
        },
        Limpiar()
        {
            this.objoportunidad={   
                Nombre:"",
                IdVendedor:"",
                IdClienteS:"",
                IdTipoP:"",
                IdOportunidad:'',
                Sucursal:''
            };
            this.errorvalidacion=[""]
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdOportunidad: this.objoportunidad.IdOportunidad}
                }
            ).then( (res) => {
                this.objoportunidad.IdOportunidad= res.data.data.oportunidad.IdOportunidad;
                this.objoportunidad.Nombre=res.data.data.oportunidad.Nombre;
                this.objoportunidad.IdTipoP=res.data.data.oportunidad.IdTipoP;
                this.objoportunidad.IdVendedor=res.data.data.oportunidad.IdVendedor;
                this.listartipo(this.objoportunidad.IdVendedor);
                this.objoportunidad.IdClienteS=res.data.data.oportunidad.IdClienteS;
                this.objoportunidad.Sucursal=res.data.data.oportunidad.Sucursal;
            });
        },
        ListarCli()
        {   
            this.$emit('Listar');
        },
        SeleccionarCliente(objeto)
        {
            // this.oclientesuc=objeto;
            //   if (this.oclientesuc.Correo!='')
            //     {
            //        this.servicios.Para.push({ "text": this.oclientesuc.Correo});  
            //     }
            // let distacia=0;
            // if (objeto.DistanciaAprox !='')
            // {
            //      distacia=objeto.DistanciaAprox;
            // }
            // this.servicios.IdCliente=objeto.IdCliente;
            // this.servicios.IdClienteS=objeto.IdClienteS;
            //alert(JSON.stringify(objeto));
            this.objoportunidad.IdClienteS=objeto.IdClienteS;
            this.objoportunidad.Sucursal=objeto.Nombre;
            // this.servicios.Direccion=objeto.Direccion;
            // this.servicios.Distancia=distacia;
            // this.servicios.Velocidad=0;
            // this.ListaNumContrato();
        },
        /*ListaVendedor()
        {
            this.$http.get(
                this.urlApiVendedor,
                {
                    params:{ Rol: JSON.stringify(this.perfil)}
                }
            ).then( (res) => {
              this. Listatipoproceso =res.data.data.lista;
            });
        },*/


        ListaVendedor()
        {
            this.$http.get(
                this.urlApiVendedorNuevo,
                {
                    params:{ }
                }
            ).then( (res) => {
              this. Listatipoproceso =res.data.data.Vendedores;
            });
        },
        listartipo(Id)
        {   if (Id>0)
            {
                this.Listatipos = [];
                this.$http.get(
                    'crmprocesovendedor/listasig',
                    {
                        params:{IdTrabajador: Id}
                    }
                ).then( (res) => {
                    let Listatipos2= res.data.data.asignados;

                    Listatipos2.forEach(element => {
                        if(element.Estatus === 'true'){
                        this.Listatipos.push(element);
                        }
                    });
                });
            }
        }
    },
    created() {
        this.ListaVendedor();
        this.bus.$off('SeleccionarCliente');
        this.bus.$on('SeleccionarCliente',(oSucursal)=>
        {
            this.SeleccionarCliente(oSucursal);
            //alert(JSON.stringify(oSucursal));
        });
        this.bus.$off('Nuevo');

        //Este es para modal
        this.bus.$on('Nuevo',(data,Id)=> 
        {
            this.poBtnSave.disableBtn=false;  
            this.bus.$off('Save');
            this.bus.$on('Save',()=>
            {
                this.Guardar();
            });

            this.Limpiar();
            if (Id>0)
            {
                this.objoportunidad.IdOportunidad=Id;
                this.get_one();
            }
            this.bus.$emit('Desbloqueo',false);
        });

        if (this.Id!=undefined)
        {
            this.objoportunidad.IdOportunidad=this.Id;
            this.get_one();
        }
    }
}
</script>