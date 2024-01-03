<template>
  <div>
    <div class="bg-login">
        <div class="container" style="z-index: 1;">
            <div class="row justify-content-center">
                <div class="col-10 col-sm-10 col-md-5 col-lg-4">
                    <div class="card card-darck">
                        <div class="card-body login-form">
                            <form role="form" method="post">
                                <div class="form-group form-row justify-content-center">
                                        <img src="@/style/images/logo.png">
                                        
                                </div>
                                <div class="form-group mt-3">
                                    <h4 class="text-center">Recuperar contraseña </h4>
                                </div>
                                <div class="form-group">
                                    <input v-model="usuario.Correo" type="text" placeholder="Correo electrónico (usuario)" name="Usuario" tabindex="0" class="form-control">
                                </div>
                                <div class="form-group">
                                    <span class="text-white text-muted" >*Introduce tu correo de usuario y recibiras un correo para recuperar tu contraseña</span>
                                </div>
                                <div class="form-group text-center">
                                    <button :disabled="bndbtn" @click="send_mail" type="button" class="btn btn-01 btn-block"> <i v-if="bndbtn" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>Enviar</button>
                                    <button :disabled="bndbtn" type="button" @click="Regresar()" class="btn btn-danger btn-block">Inicio</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
export default {
    name:'ressetpassword',
    props:[''],
    data() {
        return {
            usuario:{
                Correo:'',
            },
            bndbtn:false,
        }
    },methods: {
        send_mail(){
            if(this.usuario.Correo.trim()==''){
                 this.$toast.warning('Introduzca el correo');
                return false;
            }
            this.bndbtn=true;
            this.$http.post(
                'tblResetPass/ComprobarUser',
                this.usuario
            ).then( (res) => {
                 this.bndbtn=false;
                  this.$toast.info('Se ha enviado un correo para reestablecer su contraseña');
                  this.usuario.Correo='';
            }).catch( (err) => {
                this.bndbtn=false;
                if(err.response.data.typemsj==2){
                    this.$toast.info(JSON.stringify(err.response.data.message.errores.Correo[0]));    
                }else{
                    this.$toast.warning(err.response.data.message);
                }
              
            });
       
        },Regresar ()
        {
            this.$router.push({name:'Login', params:{}})
        },
    },created() {
        
    },mounted() {
        
    },destroyed() {

    },

}
</script>
