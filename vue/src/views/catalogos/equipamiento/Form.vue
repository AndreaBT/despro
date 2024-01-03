<template>

    <form autocomplete="off" id="FormTrabajador" class="form-horizontal" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
  <div class="form-group">
    
    <label for="exampleInputPassword1">Nombre</label>
    <input  class="form-control"  v-model="equipamiento.Nombre" placeholder="Nombre">
  </div>
  </div>
    </div>
  

 
  
</form>

   
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from '@/components/Cbtnsave.vue'
export default {
    name:'Form',
     props:['IdEquipamiento','poBtnSave'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'equipamiento',//Sirve para donde va regresar
          equipamiento:{
            
                IdEquipamiento:0,
              Nombre:""

            },
            urlApi:"equipamiento/recovery"
        }
    },
    components:{
        Cbtnsave
    },
    methods :
    {
    
       async Guardar()
        {
         //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;
       let formData = new FormData();
           formData.set('IdEquipamiento',this.equipamiento.IdEquipamiento);
            formData.set('Nombre',this.equipamiento.Nombre);
     await this.$http.post(
        'equipamiento/post',
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
        this.poBtnSave.disableBtn=false;
        
          this.poBtnSave.toast=2;  
        

      });
       
        },
         Limpiar()
        {

  this.equipamiento.IdEquipamiento= 0,
      this.equipamiento.Nombre=""
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdEquipamiento: this.equipamiento.IdEquipamiento}
                }
            ).then( (res) => {
              
  
this.equipamiento.IdEquipamiento= res.data.data.equipamiento.IdEquipamiento;
      this.equipamiento.Nombre=res.data.data.equipamiento.Nombre;
 
      });
        }
    },
    created() {
        
        this.bus.$off('Save');
        this.bus.$off('Nuevo');

        this.bus.$on('Save',()=>
        {
           this.Guardar();
        });
        
        //Este es para modal
        this.bus.$on('Nuevo',(data,Id)=> 
        {
           this.poBtnSave.disableBtn=false; 
             this.Limpiar();
            if (Id>0)
            {
            this.equipamiento.IdEquipamiento=Id;
            this.get_one();
            }
            
        });
        if (this.Id!=undefined)
        {
            this.equipamiento.IdEquipamiento=this.Id;
            this.get_one();
        }

    }
}
</script>