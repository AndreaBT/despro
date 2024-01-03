<template>
      <div class="row">
        <div class="col-lg-12">
          <div class="form-group">
            <label >Usuario</label>
            <input  class="form-control" v-modal="datos.Correo"   placeholder="Nombre">
          </div>
        </div>
        <div class="col-lg-12">
          <div class="form-group">
            <label >Contraseña</label>
            <input type="password"  class="form-control"   placeholder="Nombre">
          </div>
        </div>

         <div class="col-lg-12">
          <div class="form-group">
            <label >Repetir Contraseña</label>
            <input type="password"  class="form-control"   placeholder="Nombre">
            
          </div>
        </div>

    </div>
</template>
<script>
export default {
   data() {
       return {
           errorvalidacion:[],
           datos:{}
         

       }
   },
   methods: {
        get_one()
        {
            this.$http.get(
                'trabajador/recovery',
                {
                    params:{IdTrabajador: this.trabajador2.IdTrabajador}
                }
            ).then( (res) => {
              this.trabajador2=res.data.data.trabajador;
              this.trabajador2.Pass2='';
              
            });
        }
       
   },
   created() {
       this.bus.$off('ChangeP');
           this.bus.$on('ChangeP',(Id)=> 
        {
            this.datos.IdTrabajador=Id;
            //this.get_one();
            
        });
   },

}
</script>