
<template >

   <div class="auth-wrapper">
        <div class="container-fluid h-100">
            <div class="row flex-row h-100 bg-white">
                <div class="col-xl-8 col-lg-6 col-md-5 p-0 d-md-block d-lg-block d-sm-none d-none">
                    <div class="lavalite-bg">
                        <div class="lavalite-overlay"></div>
                        <div class="texto-banner">
                            <p class="text-center">
                                <img src="@/style/images/logo-login.png" alt="Desprosoft" class=" img-fluid">
                            </p>
                            <h1 class="text-center">El Software De Gestión Y Mantenimiento</h1>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-7 my-auto p-0">
                    <div class="authentication-form mx-auto">
                        <div class="logo-centered">
                            <p class="text-center"><img src="@/style/images/logo.png" alt="" class="img-fluid"></p>
                        </div>
                        <h3>Inicie sesión en Desprosoft</h3>
                        <p>¡Bienvenido de nuevo!</p>
                        <form action="../index.html">
                            <div class="form-group">
                           
                                    <input type="text" v-model="usuario.Usuario" v-on:keyup.enter="GetLogin" ref="username" class="form-control" placeholder="E-mail" required="" value="">
                                <i class="fal fa-user"></i>
                            </div>
                            <div class="form-group">
                                <input type="password"  v-model="usuario.Contrasenia" v-on:keyup.enter="GetLogin" ref="password" class="form-control" placeholder="Contraseña" required="" value="">
                                <i class="fal fa-lock-alt"></i>
                            </div>
                            <div class="row">
                                <div class="col-12 text-right">
                                      <router-link :to="{name:'recoveryaccount'}" >¿Se te olvidó tu contraseña ?</router-link>
                                </div>
                            </div>
                            <div class="sign-btn text-center">
                            
                                 <button :disabled="disabled"  @click="GetLogin" type="button"  class="btn btn-01 btn-block">
                                    <i v-show="disabled" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>
                                    Iniciar sesión
                                </button>
                            </div>
                        </form>
                        <div class="register">
                         <p>¿No tienes una cuenta? <a href="https://www.desprosoft.com/">Crea una cuenta</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name:'Login',
    data() {
        return {
            usuario:{
                Usuario: '',
                Contrasenia: '',
        },
        disabled:false,

    }
    },
    methods: {
        async GetLogin()
        {
            this.disabled =true;
            if (this.usuario.Usuario=='')
            {
                this.$refs.username.focus();
                this.disabled =false;
                return false;
            }
            if (this.usuario.Contrasenia=='')
            {
                this.$refs.password.focus();
                this.disabled =false;
                return false;
            }

            this.$http.post(
                'login/post',
                this.usuario
            ).then( (res) => {
                if (res.data.status==true)
                {
                    this.disabled =false;
                    this.$store.dispatch('login', res.data.data);   
                    let IdEmpresa = res.data.data.usuario.IdEmpresa;

                    //let Zona = res.data.data.Zona;
                    //sessionStorage.setItem('ZonaHoraria', Zona);
                    
                    if(IdEmpresa > 0)
                    {
                        this.$router.push({name: "AdminInicio",params:{despacho:1}});
                    }
                    else if (IdEmpresa == 0)
                    {
                        this.$router.push({name: "RootInicio"});
                    }
                    else if(IdCliente>0){
                        this.$router.push({name: "AdminInicio",params:{despacho:1}});
                    }
                } 
                else{
                    this.$toast.warning('Usuario o contraseña Incorrectos');
                } 
            }).catch( (err) => {
                this.disabled =false;
                this.$toast.warning(err.response.data.message);
                this.$store.commit('auth_error');
                this.$store.localStorage.removeItem('user_token');
                this.$store.reject(err);
            });
        }
    },
    created() {
         this.usuario = {
            Usuario: '',
            Contrasenia: '',
        };

       var datos = JSON.parse(sessionStorage.getItem('user'));
       
       if(datos!=null){
           this.$router.push({name: "AdminInicio",params:{despacho:1}});
       }  
    },
    mounted() {
       this.$refs.username.focus();
    },
}
</script>