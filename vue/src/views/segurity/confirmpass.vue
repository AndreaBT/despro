<template>

    
        <div class="bg-login">
            <div class="container" style="z-index: 1;">
                <div class="row justify-content-center">
                    <div class="col-10 col-sm-10 col-md-5 col-lg-5">
                        <div class="card ">
                            <div class="card-body card-darck login-form">
                                <form role="form" action="dashboard.html" method="post" autocomplete="off">
                                    <div class="logo-centered">
                                        <p class="text-center"><img src="@/style/images/logo.png"alt="" class="img-fluid"></p>
                                    </div>
                                    
                                    <div v-if="usuarioAccount.visible">
                                        <div class="form-group mt-3">
                                            <h3>Restablecer Contraseña</h3>
                                        </div>

                                        <div class="form-group text-left">
                                            <label>Nueva Contraseña</label>
                                            <input  type="password" v-model="usuarioAccount.password" class="form-control" placeholder="Contraseña" name="password">
                                            <small id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.password" :Mensaje="errorvalidacion.password[0]"></Cvalidation></small>
                                        </div>

                                        <div class="form-group text-left">
                                            <label>Confirmar Contraseña</label>
                                            <input  type="password" v-model="usuarioAccount.confirmpassword" class="form-control" placeholder="Confirmar" name="confirmpassword">
                                            <small id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.confirmpassword" :Mensaje="'Campo requerido'"></Cvalidation></small>
                                        </div>
                                        
                                        <div class="text-center">
                                            <button type="button" @click="Guardar()" :disabled="oBoton.Disablebtn" class="btn btn-primary btn-block">{{oBoton.NameOk}}</button>
                                            <button type="button" @click="Regresar()" :disabled="oBoton.Disablebtn" class="btn btn-danger btn-block">Inicio</button>
                                        </div>
                                    </div>

                                    <div v-else>
                                        <div class="form-group">
                                            <br><h4>{{usuarioAccount.textDefault}}</h4>
                                        </div>

                                        <div class="text-center">
                                            <button type="button" @click="Regresar()" class="btn btn-danger btn-block">Inicio</button>
                                        </div>
                                    </div>
                                   
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
</template>

<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal

export default
{
    name:'FormConfirm',
    props:['user','tkn'],
    data() {
        return {
            Disablebtn:false,
            usuarioAccount:{            
                IdReset:0,
                IdUsuario:0,
                token: '',
                user: '',
                password: '',
                confirmpassword: '',
                tokenReq1: '',
                tokenReq2: '',
                visible: false,
                textDefault: 'Lo sentimos pero el link no es válido o ya expiro, por favor vuelva a intentarlo de nueva cuenta.'
            },
            errorvalidacion:[],
            oBoton:{
                Disablebtn:false,
                NameOk:'Guardar'
            }
        }
    },
    components:{
    },
    methods :
    {
        Guardar()
        {
            if(this.usuarioAccount.password.trim()==''){
                this.$toast.warning('Ingrese la contraseña');
                return false;
            }else if(this.usuarioAccount.confirmpassword.trim()==''){
                this.$toast.warning('Confirme la contraseña');
                return false;
            }

            this.bus.$emit('BloquearBtn',0);
            this.$http.post(
                'tblResetPass/updatePass',
                this.usuarioAccount,
            ).then( (res) => {
                this.bus.$emit('BloquearBtn',1);
                //this.usuarioAccount = res.data.data.tblResetPass;
            }).catch( err => {
                if(err.response.data.typemsj==2){
                    this.errorvalidacion=err.response.data.message.errores;
                }else{
                    this.$toast.error(err.response.data.message);
                }
                this.bus.$emit('BloquearBtn',2);
            });
        },
        Regresar ()
        {
            this.$router.push({name:'Login', params:{}})
        },
        BloquearBotones(acc){
            this.disable_buttons(false);
            if(acc==1){
                this.$swal({
                    title: '¡Exito!',
                    text: '¡Su contraseña ha sido reestablecida, ahora podra ingresar de nueva cuenta.!',
                    type: 'success',
                    showCancelButton: false,
                    confirmButtonText: '¡Aceptar!',
                    showCloseButton: false,
                    showLoaderOnConfirm: true,
                    icon:'success'
                }).then((result) => {
                    this.usuarioAccount.password = '';
                    this.usuarioAccount.confirmpassword = '';
                    this.usuarioAccount.visible = false;
                     this.usuarioAccount.textDefault = 'Contraseña Actualizada, Gracias.'
                });
            }
            else if(acc==2){
               //this.$toast.error('Complete los campos');
            }
            else{
                this.disable_buttons(true);
            }
        },
        disable_buttons(bnd){
            this.oBoton.Disablebtn = false;
            this.oBoton.NameOk = 'Guardar';
            if(bnd){
                this.oBoton.Disablebtn = true;
                this.oBoton.NameOk=' Espere...';
            }
        },
        validateTokenPass(token,user)
        {
            this.$http.get(
                'tblResetPass/CvalidarToken',
                {
                    params:{tkn:token,IdUser:user}
                }
            ).then((res) => {
                this.usuarioAccount = res.data.data.tblResetPass;
                this.usuarioAccount.visible = true;
                this.usuarioAccount.user = user;
            }).catch( err => {
                this.usuarioAccount.visible = false;
            });
        }
    },
    beforeCreate() {
    },
    created()
    {
        
        this.bus.$off('BloquearBtn');

        this.bus.$on('BloquearBtn',(bnd)=>
        {
           this.BloquearBotones(bnd);
        });

        this.tokenReq1 = this.tkn;
        this.tokenReq2 = this.user;

        this.validateTokenPass(this.tokenReq1,this.tokenReq2);
    },
    mounted() {
    },
}
</script>