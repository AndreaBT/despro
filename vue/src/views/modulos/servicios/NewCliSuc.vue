<template>
    <div>
        <div class="row">    
            <template v-if="showConten">
                <div class="col-lg-6" >
                    <label >Nombre </label>
                    <input type="text" v-model="clientesucursal.Nombre" class="form-control" placeholder="Nombre" name="Nombre"/>
                    <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Nombre" :Mensaje="errorvalidacion.Nombre[0]"></Cvalidation></label>
                </div>       

                <div class="col-lg-3">
                    <label >Utilizar Datos Cliente</label> <input  v-model="checked" @change="get_DatosCli" type="checkbox"  class=""  />
                </div>

                <div class="col-lg-3">
                    <label >Usar Scanning</label> <input  v-model="checkedScanning" @change="get_scann" :checked="checkedScanning"  type="checkbox"  class=""  />
                </div>

                <div class="col-lg-9" >
                    <label for="Nombre">Dirección </label>
                    <input type="text" v-model="clientesucursal.Direccion" class="form-control form-control-sm" placeholder="Direccion"  name="Direccion" />
                    <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Direccion" :Mensaje="errorvalidacion.Direccion[0]"></Cvalidation></label>
                </div>

                <div class="col-lg-3">
                    <label >Distancia Aproximada </label>
                    <input type="number" v-model="clientesucursal.DistanciaAprox" class="form-control form-control-sm" placeholder="Distancia aproximada" name="DistanciaAprox" />
                    <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.DistanciaAprox" :Mensaje="errorvalidacion.DistanciaAprox[0]"></Cvalidation></label>
                </div>

                <div class="col-lg-6" >
                    <label >Ciudad </label>
                    <input type="text" v-model="clientesucursal.Ciudad" class="form-control form-control-sm" placeholder="Ciudad"  name="Ciudad" />
                    <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Ciudad" :Mensaje="errorvalidacion.Ciudad[0]"></Cvalidation></label>
                </div>

                <div class="col-lg-6">
                    <label >Teléfono </label>
                    <input type="text" v-model="clientesucursal.Telefono" class="form-control form-control-sm" placeholder="Teléfono" name="Telefono" />
                    <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Telefono" :Mensaje="errorvalidacion.Telefono[0]"></Cvalidation></label>
                </div>

                <div class="col-lg-6">
                    <label >Contacto </label>
                    <input type="text" v-model="clientesucursal.ContactoS" class="form-control form-control-sm" placeholder="Contacto" name="ContactoS" />
                    <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.ContactoS" :Mensaje="errorvalidacion.ContactoS[0]"></Cvalidation></label>
                </div>

                <div class="col-lg-6">
                    <label >Correo </label>
                    <input type="text" v-model="clientesucursal.Correo" class="form-control form-control-sm" placeholder="Correo" name="Correo" />
                    <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Correo" :Mensaje="errorvalidacion.Correo[0]"></Cvalidation></label>
                </div>

                <div class="col-lg-8">
                    <label >Comentario </label>
                    <textarea  v-model="clientesucursal.Comentario" rows="3" class="form-control form-control-sm" placeholder="Comentario" name="Comentario"></textarea>
                </div>

                <div class="col-lg-2">
                    <button type="button" @click="Find_IconoEmp" class="btn btn-sm btn-warning">Elegir icono: {{this.clientesucursal.IdIconoEmp}}</button>
                    <img :src="Ruta + ImagenSelect" />
                </div>

                <div class="col-lg-2">
                    <button type="button" @click="Add_Contratos" class="btn btn-sm btn-warning">Añadir contrato</button>
                </div>

                <div class="col-lg-12">
                    <div class="row" v-for="(item, index) in ListaContratos" :key="index">
                        <div class="col-lg-3">
                            <label >Num. contrato </label>
                            <input type="text" class="form-control" v-model="item.NumeroC"  >
                        </div>

                        <div class="col-lg-7">
                            <label >Comentarios </label>
                            <textarea class="form-control" rows="3" v-model="item.Comentario"></textarea>
                        </div>

                        <div class="col-lg-2">
                            <button @click="delete_ncontrato(index)"  type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </template>

            <template v-else>
                <table>
                    <body>
                        <tr  v-for="(item, index) in ListIconoEmp" :key="index">
                            <td @click="Set_IcoEmp(index)" >
                                <img :src="Ruta + item.Imagen" alt=""> {{item.Nombre}}
                            </td>
                        </tr>
                    </body>
                </table>
            </template>
        </div>
        <!--Fin body del panel-->
 
        <div  class="modal-footer">
            <div class="col-3">
                <button :disabled="bandera" type="button" @click="Guardar" class="btn btn-block btn-success" >Guardar</button>
            </div>
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
    props:['IdCliente','ocliente','NameList','poBtnSave'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'cliente',//Sirve para donde va regresar
            clientesucursal:{
                IdClienteS:0,
                IdCliente:0,
                Nombre:"",
                Direccion:"",
                Telefono:"",
                Correo:"",
                Ciudad:"",
                IdSucursal:"",
                RegEstatus:"",
                ContactoS:"",
                Ncontrato:"",
                CheckCli:"",
                Tipo:"",
                IdVendedor:"",
                IdIconoEmp:"",
                DistanciaAprox:"",
                Comentario:"",
                Cargo:"",
                FechaMod:"",
                ListaContratos:[]
            },
            checked:false,
            checkedScanning:false,
            urlApi:"clientesucursal/recovery",
            ListaContratos:[],
            showConten:true,
            ListIconoEmp:[],
            errorvalidacion:[],
            Ruta:'',
            ImagenSelect:'',
            bandera:false,
        }
    },
    components:{
        Cbtnsave,
        Cvalidation
    },
    methods :
    {
        async Guardar()
        {
            //deshabilita botones
            this.bandera=true;
            this.clientesucursal.ListaContratos=JSON.stringify(this.ListaContratos);

            this.$http.post(
                'clientesucursal/post',
                this.clientesucursal
            )
            .then( (res) => {
                this.$toast.success('Información guardada');
                this.bandera=false;
                $('#ModalNewUser').modal('hide');
                this.bus.$emit('ListCliSuc',this.ocliente); 
            })
            .catch( err => {
                this.errorvalidacion=err.response.data.message.errores;
                this.bandera=false;
                this.$toast.warning('Complete los campos'); 
            });
        },
        Limpiar()
        {
            this.clientesucursal.IdClienteS=0,
            this.clientesucursal.Nombre="",
            this.clientesucursal.Direccion="",
            this.clientesucursal.Telefono="",
            this.clientesucursal.Correo="",
            this.clientesucursal.Ciudad="",
            this.clientesucursal.IdSucursal="",
            this.clientesucursal.RegEstatus="",
            this.clientesucursal.ContactoS="",
            this.clientesucursal.Ncontrato="",
            this.clientesucursal.CheckCli="0",
            this.clientesucursal.Tipo="",
            this.clientesucursal.IdVendedor="",
            this.clientesucursal.IdIconoEmp="",
            this.clientesucursal.DistanciaAprox="",
            this.clientesucursal.Comentario="",
            this.clientesucursal.Cargo="",
            this.clientesucursal.FechaMod="",
            this.checkedScanning=false;
            this.errorvalidacion=[""];
            this.checked=false;
            this.ImagenSelect='';
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdClienteS: this.clientesucursal.IdClienteS}
                }
            ).then( (res) => {
                this.clientesucursal =res.data.data.Clientes;
                
                this.ImagenSelect=this.clientesucursal.IdIconoEmp;
               this.checkedScanning=false;
                if(res.data.data.Clientes.CheckCli==1){
                        this.checkedScanning=true;
                }
                //CONTRATOS
                this.ListaContratos=res.data.data.ListaContratos;
            });
        },
        get_IcoEquipos()
        {
            this.$http.get(
                "iconosemp/get",
                {
                    params:{IdClienteS: this.clientesucursal.IdClienteS}
                }
            ).then( (res) => {
                //Equipos
                this.ListIconoEmp=res.data.data.iconosemp;
                this.Ruta=res.data.data.ruta;
            });
        },
        get_DatosCli(){
            if(this.checked){
                this.clientesucursal.IdCliente=this.ocliente.IdCliente;
                this.clientesucursal.Nombre=this.ocliente.Nombre;
                this.clientesucursal.Direccion=this.ocliente.Direccion;
                this.clientesucursal.Ciudad=this.ocliente.Ciudad;
                this.clientesucursal.ContactoS=this.ocliente.Contacto;
                this.clientesucursal.Telefono=this.ocliente.Telefono;
                this.clientesucursal.Correo=this.ocliente.Correo;
            }
            else{
                this.Limpiar();
            }
        },
        get_scann(){
            this.clientesucursal.CheckCli=0;
            if(this.checkedScanning){
                this.clientesucursal.CheckCli=1;
            }
        },
        Add_Contratos(){
            this.ListaContratos.push({"IdContrato":'',IdClienteS:this.clientesucursal.IdClienteS,"NumeroC":'','Comentario':'',"RegEstatus":"A"});
        },
        delete_ncontrato(index){
            this.ListaContratos.splice(index, 1);
        },
        Find_IconoEmp(){
            this.showConten=false;
            this.$emit('titulomodal','Selecciona la imagen');
        },
        ReturnConten(){
            this.$emit('titulomodal','Sucursal del cliente');
            this.showConten=true;
        },
        Set_IcoEmp(index){
            this.clientesucursal.IdIconoEmp=this.ListIconoEmp[index].Imagen;
            this.ImagenSelect= this.ListIconoEmp[index].Imagen;
            this.ReturnConten();
        }
    },
    created() {
        this.clientesucursal.IdCliente=this.ocliente.IdCliente;
        this.bus.$off('ReturnConten');
        this.get_IcoEquipos();
        //Este es para modal
    
        this.bus.$on('ReturnConten',()=> 
        {
            this.ReturnConten();
        });
    }
}
</script>