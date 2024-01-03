<template>
    <div class="modal-body">
        <div v-if="sucursal.IdSucursal==0" class="form-group form-row">
            <div class="col-md-12 col-lg-12">
                <div class="checkbox">
                    <label>
                        <input v-model="Check" type="checkbox" @click="UsarDatos" name="optionsCheckboxes"><span
                            class="checkbox-material-green"><span class="check"></span></span>
                        Usar Datos De La Empresa
                    </label>
                </div>
                <hr>
            </div>
        </div>

        <div class="form-group form-row">
            <div class="col-md-6 col-lg-6">
                <label>Nombre Empresa</label>
                <input type="text"  v-model="sucursal.Nombre" class="form-control" placeholder="Escribir Nombre De La Emp.">

            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Nombre" :Mensaje="'Campo obligatorio'"></Cvalidation></label>             
            </div>
            <div class="col-md-6 col-lg-6">
                <label>Dirección</label>
                <textarea class="form-control" v-model="sucursal.Direccion" rows="2" placeholder="Escribir dirección"></textarea>
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Direccion" :Mensaje="this.errorvalidacion.Direccion[0]"></Cvalidation></label>
            </div>
        </div>

        <div class="form-group form-row">
            <div class="col-md-7 col-lg-7">
                <label>Correo</label>
                <input type="text"  v-model="sucursal.Correo" class="form-control" placeholder="email@email.com">
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Correo" :Mensaje="this.errorvalidacion.Correo[0]"></Cvalidation></label> 
            </div>
            <div class="col-md-3 col-lg-3">
                <label>Teléfono</label>
                <input type="text" v-model="sucursal.Telefono" class="form-control" placeholder="+00 0000 000 000">
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Telefono" :Mensaje="'Campo obligatorio'"></Cvalidation></label>             
            </div>
            <div class="col-md-2 col-lg-2">
                <label>C.P.</label>
                <input type="number"  v-model="sucursal.CP" class="form-control" placeholder="Codigo postal">
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.CP" :Mensaje="this.errorvalidacion.CP[0]"></Cvalidation></label>             
            </div>
        </div>

        <div class="form-group form-row">
            <div class="col-md-3 col-lg-3">
                <label>Estado</label>
                <input type="text" v-model="sucursal.Estado" class="form-control" placeholder="Escribir Estado">
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Estado" :Mensaje="this.errorvalidacion.Estado[0]"></Cvalidation></label>             
            </div>
            <div class="col-md-3 col-lg-3">
                <label>Ciudad</label>
                <input type="text" v-model="sucursal.Ciudad" class="form-control" placeholder="Escribir Ciudad">
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Ciudad" :Mensaje="this.errorvalidacion.Ciudad[0]"></Cvalidation></label>             
            </div>
            
            <div class="col-md-3 col-lg-3">
                <label>Fecha Facturación</label>
                <v-date-picker 
                v-model="sucursal.Fecha_Fac"
                    :popover="{ 
                        placement: 'bottom',
                        visibility: 'click',
                    }"
                    :input-props='{
                        class:"form-control calendar",
                        style:"cursor:pointer;background-color:#F9F9F9",
                        readonly: true,
                    }'
                /> 
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Fecha_Fac" :Mensaje="this.errorvalidacion.Fecha_Fac[0]"></Cvalidation></label>             
            </div>
            <div class="col-md-3 col-lg-3">
                <label>Plazo De Facturación</label>
                <select v-model="sucursal.Plazo" class="form-control" name="" id="">
                    <option selected value="">-- Seleccione una Opción --</option>
                    <option value="1">1 Mes</option>
                    <option value="2">2 Meses</option>
                    <option value="3">3 Meses</option>
                    <option value="4">4 Meses</option>
                    <option value="5">5 Meses</option>
                    <option value="6">6 Meses</option>
                    <option value="7">7 Meses</option>
                    <option value="8">8 Meses</option>
                    <option value="9">9 Meses</option>
                    <option value="10">10 Meses</option>
                </select>
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Plazo" :Mensaje="this.errorvalidacion.Plazo[0]"></Cvalidation></label>             
            </div>
        </div>

        <div class="form-group form-row">
            <div class="col-md-4 col-lg-4">
                <label>Paquete</label>
                <select v-model="sucursal.PaqueteU" class="form-control" >
                    <option selected value="">-- Seleccione una Opción --</option>
                    <option value="10">10 Usuarios</option>
                    <option value="20">20 Usuarios</option>
                    <option value="30">30 Usuarios</option>
                    <option value="40">40 Usuarios</option>
                    <option value="50">50 Usuarios</option>
                    <option value="60">60 Usuarios</option>
                    <option value="70">70 Usuarios</option>
                    <option value="80">80 Usuarios</option>
                    <option value="90">90 Usuarios</option>
                    <option value="100">100 Usuarios</option>
                </select>
                <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.PaqueteU" :Mensaje="this.errorvalidacion.PaqueteU[0]"></Cvalidation></label>                      
            </div>
            <div class="col-md-8 col-lg-8">
                <label>Comentarios</label>
                <textarea class="form-control" v-model="sucursal.Comentario"  rows="1" placeholder="Escribir Comentario"></textarea>
            </div>
        </div>

        <template v-if="sucursal.IdSucursal==0">
            <div class="form-group form-row mt-2">
                <div class="col-md-12 col-lg-12">
                    <h4 class="color-01">Datos De Usuario</h4>
                    <hr>
                </div>
            </div>
            <div class="form-group form-row">
                <div class="col-md-4 col-lg-4">
                    <label>Nombre</label>
                    <input type="text" v-model="sucursal.NombreU" class="form-control" placeholder="Escribir Nombre">
                    <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.NombreU" :Mensaje="'Campo requerido'"></Cvalidation></label>             
                </div>
                <div class="col-md-4 col-lg-4">
                    <label>Apellido</label>
                    <input type="text" v-model="sucursal.ApellidoU" class="form-control" placeholder="Escribir Apellido">
                    <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.ApellidoU" :Mensaje="'Campo requerido'"></Cvalidation></label>             
                </div>
            </div>
            <div class="form-group form-row">
                <div class="col-md-4 col-lg-4">
                    <label>Usuario</label>
                    <input type="text" v-model="sucursal.Usuario" class="form-control" placeholder="Escribir Nombre De Usuario">
                    <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Usuario" :Mensaje="this.errorvalidacion.Usuario[0]"></Cvalidation></label>             
                </div>
                <div class="col-md-4 col-lg-4">
                    <label>Contraseña</label>
                    <input type="password" v-model="sucursal.Pass" class="form-control" placeholder="Contraseña">
                    <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Pass" :Mensaje="this.errorvalidacion.Pass[0]"></Cvalidation></label>
                </div>
                <div class="col-md-4 col-lg-4">
                    <label>Confirmar contraseña</label>
                    <input type="password" v-model="sucursal.Pass2" class="form-control" placeholder="Confrimar Contraseña">
                    <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Password_Confirmacion" :Mensaje="this.errorvalidacion.Password_Confirmacion[0]"></Cvalidation></label> 
                </div>
            </div>  
        </template>
    </div>
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal

import Cvalidation from '@/components/Cvalidation.vue'
export default {
    name:'Form',
    props:['IdSucursal','IdEmpresa','Empresa','poBtnSave'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'sucursal',//Sirve para donde va regresar
           sucursal:{
            },
            urlApi:"sucursal/recovery",
            errorvalidacion:[],
            Check:false,
        }
    },
    components:{
        Cvalidation
    },
    methods :
    {
        Guardar()
       {
           this.sucursal.IdEmpresa=this.IdEmpresa;
           this.sucursal.Usuario=this.sucursal.Usuario.trim();
           this.errorvalidacion=[];
            
            //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;

            this.$http.post(
                'sucursal/post',
                this.sucursal 
            ).then((res) => {
                //deshabilita botones
                this.poBtnSave.disableBtn=false;  
                this.poBtnSave.toast=1;
                
                this.bus.$emit('List');
                $('#ModalForm').modal('hide');
                
            }).catch( err => {
                //deshabilita botones
                this.poBtnSave.disableBtn=false;          

                if(err.response.data.typemsg=2){
                    this.errorvalidacion=err.response.data.message.errores;
                    this.poBtnSave.toast=2; 
                }else{
                    this.poBtnSave.toast=3; 
                    this.poBtnSave.toastmsg(err.response.data.message);
                }
            });
        },
        Limpiar()
        {
            this.sucursal={PaqueteU:'',Plazo:''};  
            this.sucursal.Pass='';
            this.sucursal.Pass2='';
            this.sucursal.Usuario='';
            this.sucursal.NombreU='';
            this.sucursal.ApellidoU='';
            this.errorvalidacion=[];
            this.Check=false;
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdSucursal: this.sucursal.IdSucursal}
                }
            ).then( (res) => {
                this.sucursal=res.data.data.sucursal;
                this.sucursal.Pass='';
                this.sucursal.Pass2='';
                this.sucursal.Usuario='';
                this.sucursal.NombreU='';
                this.sucursal.ApellidoU='';

                let formatedDate = this.sucursal.Fecha_Fac.replace(/-/g,'\/')
                this.sucursal.Fecha_Fac = new Date(formatedDate);
            });
        },
        UsarDatos()
        {
            if (!this.Check)
            {
                this.sucursal={
                    Nombre:this.Empresa.Nombre,
                    Direccion:this.Empresa.Direccion,
                    Telefono:this.Empresa.Telefono,
                    Correo:this.Empresa.Correo,
                    Ciudad:this.Empresa.Ciudad,
                    Pais:this.Empresa.Pais,
                    IdSucursal:0,
                    Pass:'',
                    Pass2:'',
                    Usuario:'',
                    NombreU:'',
                    ApellidoU:'',
                    PaqueteU:'',
                    Plazo:''  
                }
            }
            else
            {
                this.sucursal={
                    IdSucursal:0,
                    Pass:'',
                    Pass2:'',
                    Usuario:'',
                    NombreU:'',
                    ApellidoU:'',
                    PaqueteU:'',
                    Plazo:''
                }
            }
        }
    },
    created() {
        this.bus.$off('Nuevo');

        //Este es para modal
        this.bus.$on('Nuevo',(data,Id)=> 
        {
            //deshabilita botones
            this.poBtnSave.disableBtn=false;    

            this.bus.$off('Save');
            this.bus.$on('Save',()=>
            {
                this.Guardar();
            });

            this.Limpiar();
            this.sucursal.IdSucursal=0;
            if (Id>0)
            {
                this.sucursal.IdSucursal= Id;
                this.get_one();
            }
        });
    },
    destroyed(){
    },
}
</script>