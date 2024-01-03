<template>
    <div>
   
    <form autocomplete="off" id="FormTrabajador" class="form-horizontal" method="post" enctype="multipart/form-data">
   
            <div class="row">

                <div class="col-lg-4 ">
                      <!--<input type="text" v-model="sucursal.IdSucursal" class="form-control form-control-sm"
                               placeholder="Nombre" id="Nombre"
                               name="Nombre" />-->
                    <div class="form-group">
                        <label for="Nombre" class="labeltam">Nombre </label>
                        <input type="text" v-model="sucursal.Nombre" class="form-control"
                               placeholder="Nombre" id="Nombre"
                               name="Nombre" />

                    </div>
                </div>
            
                <div class=" col-lg-4">
                    <div class="form-group">
                        <label for="Estado" class="labeltam">Teléfono</label>
                        <input type="text" v-model="sucursal.Telefono" class="form-control"
                          placeholder="Teléfono"     />

                    </div>
                </div>

                <div class=" col-lg-4">
                    <div class="form-group">
                        <label for="CP" class="labeltam">Dirección</label>
                        <input type="text" v-model="sucursal.Direccion" class="form-control"
                               placeholder="Dirección" />

                    </div>
                </div>
   <div class=" col-lg-4">
                    <div class="form-group">
                        <label for="CP" class="labeltam">Correo</label>
                        <input type="text" v-model="sucursal.Correo" class="form-control"
                               placeholder="correo" />

                    </div>
                </div>
                  <div class=" col-lg-4">
                    <div class="form-group">
                        <label for="CP" class="labeltam">Ciudad</label>
                        <input type="text" v-model="sucursal.Ciudad" class="form-control"
                               placeholder="Usuario" />

                    </div>
                </div>
   <div class=" col-lg-4">
                <div class="form-group">
                        <label for="CP" class="labeltam">Estado</label>
                        <input type="text" v-model="sucursal.Estado" class="form-control"
                               placeholder="Estado" />

                    </div>
                </div>
   <div class=" col-lg-4">
                     <div class="form-group">
                        <label for="CP" class="labeltam">Código Postal</label>
                        <input type="text" v-model="sucursal.CP" class="form-control"
                               placeholder="Código Postal" />

                    </div>
                </div>

                   <div class=" col-lg-4">
                    <div class="form-group">
                        <label for="CP" class="labeltam">Tipo Sucursal</label>
                        <input type="text" v-model="sucursal.TipSucursal" class="form-control"
                               placeholder="Tipo Sucursal" />

                    </div>
                </div>

                   <div class=" col-lg-4">
                    <span class="has-float-label">
                        <label for="CP" class="labeltam">Fecha Fac</label>
                        <input type="text" v-model="sucursal.Fecha_Fac" class="form-control"
                               placeholder="Usuario" />

                    </span>
                </div>
                   <div class=" col-lg-4">
                    <span class="has-float-label">
                        <label for="CP" class="labeltam">Plazo</label>
                        <input type="text" v-model="sucursal.Plazo" class="form-control"
                               placeholder="Usuario" />

                    </span>
                </div>
                  <div class=" col-lg-4">
                    <span class="has-float-label">
                        <label for="CP" class="labeltam">LatSuc</label>
                        <input type="text" v-model="sucursal.LatSuc" class="form-control"
                               placeholder="Usuario" />

                    </span>
                </div>

                 <div class=" col-lg-4">
                    <span class="has-float-label">
                        <label for="CP" class="labeltam">LongSuc</label>
                        <input type="text" v-model="sucursal.LongSuc" class="form-control"
                               placeholder="Usuario" />

                    </span>
                </div>
                 <div class=" col-lg-4">
                    <span class="has-float-label">
                        <label for="CP" class="labeltam">Paquete</label>
                        <input type="text" v-model="sucursal.PaqueteU" class="form-control"
                               placeholder="Usuario" />

                    </span>
                </div>
                  <div class=" col-lg-4">
                    <span class="has-float-label">
                        <label for="CP" class="labeltam">Comentario</label>
                        <input type="text" v-model="sucursal.Comentario" class="form-control"
                               placeholder="Usuario" />

                    </span>
                </div>
                <!--fin col-6-->


            
    </div>
</form>

    </div>
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from '@/components/Cbtnsave.vue'
export default {
    name:'Form',
    props:['IdSucursal'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'sucursal',//Sirve para donde va regresar
           sucursal:{
            
                  IdSucursal: 0,
        Nombre: "",
        Telefono: "",
        Direccion: "",
        Correo: "",
        Ciudad: "",
    Estado: "",
        CP: "",
        IdEmpresa: "",
        TipSucursal: "",

        Fecha_Fac: "",
        Plazo: "",
        LatSuc: "",
        LongSuc: "",
        PaqueteU: "",
        Comentario: ""

            },
            urlApi:"sucursal/recovery"
        }
    },
    components:{
        Cbtnsave
    },
    methods :
    {
    
       async Guardar()
        {
            console.log('Veces');
       let formData = new FormData();

            formData.set('IdSucursal',this.sucursal.IdSucursal);
            formData.set('Nombre',this.sucursal.Nombre);
            formData.set('Telefono', this.sucursal.Telefono);
            formData.set('Direccion', this.sucursal.Direccion);


                formData.set('Correo',this.sucursal.Correo);
            formData.set('Ciudad',this.sucursal.Ciudad);
            formData.set('Estado', this.sucursal.Estado);
            formData.set('CP', this.sucursal.CP);

                 formData.set('IdEmpresa',this.sucursal.IdEmpresa);
            formData.set('TipSucursal',this.sucursal.TipSucursal);
            formData.set('Fecha_Fac', this.sucursal.Fecha_Fac);
            formData.set('Plazo', this.sucursal.Plazo);

            
                 formData.set('LatSuc',this.sucursal.LatSuc);
            formData.set('LongSuc',this.sucursal.LongSuc);
            formData.set('PaqueteU', this.sucursal.PaqueteU);
            formData.set('Comentario', this.sucursal.Comentario);
     await this.$http.post(
        'sucursal/post',
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


     this.sucursal.IdSucursal= 0,
        this.sucursal.Nombre= "",
        this.sucursal.Telefono= "",
        this.sucursal.Direccion= "",
        this.sucursal.Correo="",
        this.sucursal.Ciudad= "",
    this.sucursal.Estado= "",
        this.sucursal.CP= "",
        this.sucursal.IdEmpresa= "",
        this.sucursal.TipSucursal= "",

        this.sucursal.Fecha_Fac= "",
        this.sucursal.Plazo="",
       this.sucursal.LatSuc= "",
        this.sucursal.LongSuc= "",
        this.sucursal.PaqueteU="",
        this.sucursal.Comentario=""
           
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdSucursal: this.sucursal.IdSucursal}
                }
            ).then( (res) => {
              
        this.sucursal.IdSucursal= res.data.data.sucursal.IdSucursal;
        this.sucursal.Nombre= res.data.data.sucursal.Nombre;
        this.sucursal.Telefono= res.data.data.sucursal.Telefono;
        this.sucursal.Direccion= res.data.data.sucursal.Direccion;
        this.sucursal.Correo=res.data.data.sucursal.Correo;
        this.sucursal.Ciudad= res.data.data.sucursal.Ciudad;
    this.sucursal.Estado= res.data.data.sucursal.Estado;
        this.sucursal.CP= res.data.data.sucursal.CP;
        this.sucursal.IdEmpresa= res.data.data.sucursal.IdEmpresa;
        this.sucursal.TipSucursal= res.data.data.sucursal.TipSucursal;

        this.sucursal.Fecha_Fac= res.data.data.sucursal.Fecha_Fac;
        this.sucursal.Plazo=res.data.data.sucursal.Plazo;
       this.sucursal.LatSuc= res.data.data.sucursal.LatSuc;
        this.sucursal.LongSuc= res.data.data.sucursal.LongSuc;
        this.sucursal.PaqueteU=res.data.data.sucursal.PaqueteU;
        this.sucursal.Comentario=res.data.data.sucursal.Comentario;



          
               
              
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
            this.sucursal.IdSucursal= Id;
            this.get_one();
            }
            
        });
        if (this.Id!=undefined)
        {
            this.sucursal.IdSucursal=this.Id;
            this.get_one();
        }

    }
}
</script>