<template>
    <div  class="modal-footer">
        <button :disabled="Disablebtn" v-if="IsModal"  @click="Limpiar" type="button" class="btn btn-04 ban">Cerrar</button>
         <button :disabled="Disablebtn" v-else @click="Atras" type="button" class="btn btn-04 ban" >Cerrar</button>
        <button :disabled="Disablebtn" type="button" class="btn btn-01" @click="Saved"><i v-show="Disablebtn" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i><i class="fa fa-plus-circle"></i> {{txtSave}} </button>
    </div>
</template>

<script>
export default {
    name:'btnsave',
    props:['IsModal','RegresarA','NombreModal','TipoM'],
    data() {
        return {
            Disablebtn:false,
            txtSave:'Guardar',
        }
    },
    methods: {
       
        Saved ()
        {
                //this.$emit('ejecutar');
              this.bus.$emit('Save');
        },
        Atras ()
        {
             this.$router.push({name:this.RegresarA}); 
        },Limpiar(){
            this.bus.$emit('Limpiar');
            if (this.TipoM==1)
            {
                $('#'+this.NombreModal).modal('hide');
            }
            else{
                 $('#'+this.NombreModal).modal('hide'); 
                document.body.classList.add("modal-open");
            }
            
        },BloquearBotones(acc){
            this.disable_buttons(false);
            if(acc==1){
                this.$toast.success('Información guardada');
            }else if(acc==2){
                this.$toast.info('Complete los campos');
            }else if(acc==3){
                this.disable_buttons(false);
            }else{
                this.disable_buttons(true);
            }
            
        },disable_buttons(bnd){
            this.Disablebtn=false;
            this.txtSave='Guardar';
            if(bnd){
                this.Disablebtn=true;
                this.txtSave=' Espere...';
            }
        }
    },created() {
 
        this.bus.$off('BloquearBtn');
        this.bus.$off('Desbloqueo');
       
       this.bus.$on('BloquearBtn',(bnd)=>
        {
           this.BloquearBotones(bnd);
        });
        this.bus.$on('Desbloqueo',(bnd)=>
        {
           this.disable_buttons(bnd);
        });
        
      
    },
    
}
</script>