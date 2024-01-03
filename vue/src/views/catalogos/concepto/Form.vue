<style>
.upload-btn-wrapper {
  position: relative;
  overflow: hidden;
  display: inline-block;
      cursor: pointer;
}

.btn-upload {
display: inline-block;
    font-weight: 400;
    color: #858796;
    text-align: center;
    vertical-align: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-color: transparent;
    border: 1px solid transparent;
    padding: .375rem .75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: .35rem;
    -webkit-transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
    color: #fff;
    background-color: #4e73df;
    border-color: #4e73df;
    cursor: pointer;
        padding: .25rem .5rem;
    font-size: .875rem;
    line-height: 1.5;
    border-radius: .2rem;
}

.upload-btn-wrapper input[type=file] {
  font-size: 100px;
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
      cursor: pointer;
}
</style>
<template>

    <form autocomplete="off" id="FormTrabajador" class="form-horizontal" method="post" enctype="multipart/form-data">
    <div class="row">
    <div class="col-lg-3">
        <div class="form-group">
            <img src="https://www.nato-pa.int/sites/default/files/default_images/default-image.jpg"  class="img-thumbnail">

        </div>
        <div class="form-group">
            <div class="upload-btn-wrapper">
  <button class="btn-upload btn-sm"><span class="fa fa-camera"></span> Examinar imagen</button>
  <input @change="cargarImagen()" id="file" ref="file" type="file"  style="cursor:pointer;" name="myfile" />
</div>
        </div>

  </div>
<div class="col-lg-9">
<div class="row">
       <div class="col-lg-6">
  <div class="form-group">
    <label for="exampleInputPassword1">Nombre</label>
    <input  class="form-control"   v-model="concepto.Nombre" placeholder="Nombre">
  </div>
  </div>
    <div class="col-lg-6">
  <div class="form-group">
    <label for="exampleInputPassword1">Valor  </label>
    <input  class="form-control"  v-model="concepto.Valor" placeholder="Valor">
  </div>
  </div>
     <div class="col-lg-6">
  <div class="form-group">
    <label for="exampleInputPassword1">Meses</label>
    <input  class="form-control"  v-model="concepto.Meses" placeholder="Meses">
  </div>
  
  </div>

   <div class="col-lg-6">
  <div class="form-group">
    <label for="exampleInputPassword1">IdConcepto</label>
    <input  class="form-control"  v-model="concepto.IdConcepto" placeholder="Meses">
  </div>
  
  </div>



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
    props:['IdEquipamiento'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'concepto',//Sirve para donde va regresar
         concepto:{
            
                IdConcepto:0,
              Nombre:"",
              IdEquipamiento:0,
              Valor:"",
              Meses:"",
              Foto:""

            },
            urlApi:"concepto/recovery"
        }
    },
    components:{
        Cbtnsave
    },
    methods :
    {
    cargarImagen(){

this.concepto.Foto = this.$refs.file.files[0];


},
       async Guardar()
        {
      
       let formData = new FormData();
           formData.set('IdConcepto',this.concepto.IdConcepto);
            formData.set('Nombre',this.concepto.Nombre);
              formData.set('IdEquipamiento',this.IdEquipamiento);
                   formData.set('Valor',this.concepto.Valor);
                        formData.set('Meses',this.concepto.Meses);
                             formData.set('Foto',this.concepto.Foto);
     await this.$http.post(
        'concepto/post',
        formData,
        {
          headers: {
            'Content-Type': 'multipart/form-data'
            
          }
        },
      ).then( (res) => {
        
           this.$swal({
            toast: true,
            position: 'top-end',
            showConfirmButton: true,
            timer: 3000,
            type: 'succes',
            title: 'Informacion Guardada'
           
        });

          if (this.Id==undefined){
                $('#ModalForm').modal('hide');
                 this.bus.$emit('List'); 
                 
          }
          else{
              this.$router.push({name:this.FormName});
          }
       

      }).catch( err => {

        console.log('Error');

      });
       
        },
         Limpiar()
        {

  this.concepto.IdConcepto= 0,
      this.concepto.Nombre="",
            this.concepto.IdEquipamiento="",
                  this.concepto.Valor="",
                        this.concepto.Meses="",
                              this.concepto.Foto=""

        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdConcepto: this.concepto.IdConcepto}
                }
            ).then( (res) => {
              
  
this.concepto.IdConcepto= res.data.data.concepto.IdConcepto;
this.concepto.Nombre=res.data.data.concepto.Nombre;

this.concepto.IdEquipamiento= res.data.data.concepto.IdEquipamiento;
this.concepto.Valor=res.data.data.concepto.Valor;

this.concepto.Meses= res.data.data.concepto.Meses;
this.concepto.Foto=res.data.data.concepto.Foto;
 
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
             this.Limpiar();
            if (Id>0)
            {
            this.concepto.IdConcepto=Id;
            this.get_one();
            }
            
        });
        if (this.Id!=undefined)
        {
            this.concepto.IdConcepto=this.Id;
            this.get_one();
        }

    }
}
</script>