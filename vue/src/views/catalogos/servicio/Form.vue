<template>

    <form autocomplete="off" id="FormTrabajador" class="form-horizontal" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-6">
      <div class="form-group">
        <label for="exampleInputPassword1">Nombre</label>
        <input  class="form-control"  v-model="servicio.Cliente" placeholder="Nombre">
      </div>

    </div>
    <div class="col-lg-6">
       <div class="form-group">
      <label for="exampleInputPassword1">Dirección</label>
      <input  class="form-control" v-model="servicio.Direccion" placeholder="Dirección">
      </div>  
   </div>



<div class="col-lg-4">
  <div class="form-group">
    <label for="exampleInputEmail1">Tipo de servicio</label>
   <Select v-model="servicio.Tipo_Serv" ></Select>
       </div>
</div>

    <div class="col-lg-4">
       <div class="form-group">
    <label for="exampleInputPassword1">Distancia KM</label>
    <input  class="form-control"  placeholder="Distancia km">
  </div>
  </div>
    <div class="col-lg-4">
       <div class="form-group">
    <label for="exampleInputPassword1">Distancia km/h</label>
    <input  class="form-control"  placeholder="Distancia km/h">
  </div>
  </div>

   <div class="col-lg-4">
       <div class="form-group">
    <label for="exampleInputPassword1">Contacto</label>
    <input  class="form-control"  placeholder="Contacto">
  </div>
  </div>

     <div class="col-lg-4">
       <div class="form-group">
    <label for="exampleInputPassword1">Teléfono</label>
    <input  class="form-control"  placeholder="Teléfono">
  </div>
  </div>
     <div class="col-lg-4">
       <div class="form-group">
    <label for="exampleInputPassword1">Correo</label>
    <input  class="form-control"  placeholder="Correo" type="email">
  </div>
  </div>

<div class="col-lg-4">
       <div class="form-group">
    <label >Fecha Inicio</label>
    <input  class="form-control"  placeholder="Correo" type="date">
  </div>
  </div>
<div class="col-lg-4">
       <div class="form-group">
    <label >Fecha Fin</label>
    <input  class="form-control"  type="date">
  </div>
  </div>
  <div class="col-lg-2">
     <div class="form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Enviar</label>
  </div>
</div>
    </div>
  

 
  
</form>

   
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from '@/components/Cbtnsave.vue'
import Select from '@/views/catalogos/tiposervicio/option.vue'
export default {
    name:'Form',
    props:['IdServicio'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'servicio',//Sirve para donde va regresar
          servicio:{
            
                IdServicio:0,
              Cliente:"",
              Tipo_Serv:"",
             Personal:"",
            Vehiculo:"",
              Fecha_I:"",
              Fecha_F:"",
            Hora_I:"",
           Hora_F:"",
           Distancia:"",
           Observaciones:"",
         Materiales:"",
          IdSucursal:"",
           Direccion:"",
            Folio:"",
          IdVehiculo:"",
       IdCliente:"",

        IdClienteS:"",
              EstadoS:"",
           Velocidad:"",
          Econtacto:"",
            Contacto:"",
             EquiposD:"",
          MaterialesD:"",
         ViaticosD:"",
           ContratistasD:"",
         Comentario:"",
       BurdenTotal:"",
      ManoObraT:"",
        CostoV:"",
         ComentarioFin:"",
          IdContrato:"",
    NumContrato:"",
         Factura:"",
       EstadoFactura:""

            },
            urlApi:"servicio/recovery"
        }
    },
    components:{
        Cbtnsave,
        Select
    },
    methods :
    {
    
       async Guardar()
        {
            console.log('Veces');
       let formData = new FormData();
    formData.set('IdServicio',this.servicio.IdServicio);
            formData.set('Cliente',this.servicio.Cliente);
            formData.set('Tipo_Serv',this.servicio.Tipo_Serv);
            formData.set('Personal', this.servicio.Personal);
            formData.set('Vehiculo', this.servicio.Vehiculo);
            formData.set('Fecha_I',this.servicio.Fecha_I);
            formData.set('Fecha_F',this.servicio.Fecha_F);
            formData.set('Hora_I',this.servicio.Hora_I);
            formData.set('Hora_F',this.servicio.Hora_F);
            formData.set('Distancia',this.servicio.Distancia);
       formData.set('Observaciones',this.servicio.Observaciones);
formData.set('Materiales',this.servicio.Materiales);
formData.set('IdSucursal',this.servicio.IdSucursal);
formData.set('Direccion',this.servicio.Direccion);
formData.set('Folio',this.servicio.Folio);
formData.set('IdVehiculo',this.servicio.IdVehiculo);
formData.set('IdCliente',this.servicio.IdCliente);
formData.set('IdClienteS',this.servicio.IdClienteS);
formData.set('EstadoS',this.servicio.EstadoS);
formData.set('Velocidad',this.servicio.Velocidad);
formData.set('Econtacto',this.servicio.Econtacto);
formData.set('Contacto',this.servicio.Contacto);
formData.set('EquiposD',this.servicio.EquiposD);
formData.set('MaterialesD',this.servicio.MaterialesD);
formData.set('ViaticosD',this.servicio.ViaticosD);
formData.set('ContratistasD',this.servicio.ContratistasD);
formData.set('Comentario',this.servicio.Comentario);
formData.set('BurdenTotal',this.servicio.BurdenTotal);
formData.set('ManoObraT',this.servicio.ManoObraT);


formData.set('CostoV',this.servicio.CostoV);
formData.set('ComentarioFin',this.servicio.ComentarioFin);
formData.set('IdContrato',this.servicio.IdContrato);
formData.set('NumContrato',this.servicio.NumContrato);
formData.set('Factura',this.servicio.Factura);
formData.set('EstadoFactura',this.servicio.EstadoFactura);

     await this.$http.post(
        'servicio/post',
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

  this.servicio.IdServicio= 0,
      this.servicio.Cliente="",
      this.servicio.Tipo_Serv="",
    this.servicio.Personal="",
      this.servicio.Vehiculo="",
      this.servicio.Fecha_I="",
      this.servicio.Fecha_F="",
      this.servicio.Hora_I= "",
      this.servicio.Hora_F="",
      this.servicio.Distancia="",
      this.servicio.Observaciones="",
      this.servicio.Materiales="",
      this.servicio.IdSucursal="",
      this.servicio.Direccion="",
      this.servicio.Folio= "",
      this.servicio.IdVehiculo="",
      this.servicio.IdCliente= "",
      this.servicio.IdClienteS="",
      this.servicio.EstadoS="",
      this.servicio.Velocidad= "",
      this.servicio.Econtacto= "",
      this.servicio.Contacto= "",
      this.servicio.EquiposD= "",
      this.servicio.MaterialesD= "",
      this.servicio.ViaticosD="",
      this.servicio.ContratistasD="",
      this.servicio.Comentario= "",
      this.servicio.BurdenTotal="",
      this.servicio.ManoObraT= "",
    this.servicio.CostoV="",
      this.servicio.ComentarioFin="",
      this.servicio.IdContrato="",
      this.servicio.NumContrato="",
      this.servicio.Factura="",
      this.servicio.EstadoFactura=""
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdServicio: this.servicio.IdServicio}
                }
            ).then( (res) => {
              
  
this.servicio.IdServicio= res.data.data.servicio.IdServicio;
      this.servicio.Cliente=res.data.data.servicio.Cliente;
      this.servicio.Tipo_Serv=res.data.data.servicio.Tipo_Serv;
    this.servicio.Personal=res.data.data.servicio.Personal;
      this.servicio.Vehiculo=res.data.data.servicio.Vehiculo;
      this.servicio.Fecha_I=res.data.data.servicio.Fecha_I;
      this.servicio.Fecha_F=res.data.data.servicio.Fecha_F;
      this.servicio.Hora_I= res.data.data.servicio.Hora_I;
      this.servicio.Hora_F=res.data.data.servicio.Hora_F;
      this.servicio.Distancia=res.data.data.servicio.Distancia;
      this.servicio.Observaciones=res.data.data.servicio.Observaciones;
      this.servicio.Materiales=res.data.data.servicio.Materiales;
      this.servicio.IdSucursal=res.data.data.servicio.IdSucursal;
      this.servicio.Direccion=res.data.data.servicio.Direccion;
      this.servicio.Folio= res.data.data.servicio.Folio;
      this.servicio.IdVehiculo=res.data.data.servicio.IdVehiculo;
      this.servicio.IdCliente= res.data.data.servicio.IdCliente;
      this.servicio.IdClienteS=res.data.data.servicio.IdClienteS;
      this.servicio.EstadoS=res.data.data.servicio.EstadoS;
      this.servicio.Velocidad= res.data.data.servicio.Velocidad;
      this.servicio.Econtacto= res.data.data.servicio.Econtacto;
      this.servicio.Contacto= res.data.data.servicio.Contacto;
      this.servicio.EquiposD= res.data.data.servicio.EquiposD;
      this.servicio.MaterialesD= res.data.data.servicio.MaterialesD;
      this.servicio.ViaticosD=res.data.data.servicio.ViaticosD;
      this.servicio.ContratistasD=res.data.data.servicio.ContratistasD;
      this.servicio.Comentario= res.data.data.servicio.Comentario;
      this.servicio.BurdenTotal=res.data.data.servicio.BurdenTotal;
      this.servicio.ManoObraT= res.data.data.servicio.ManoObraT;
    this.servicio.CostoV=res.data.data.servicio.CostoV;
      this.servicio.ComentarioFin=res.data.data.servicio.ComentarioFin;
      this.servicio.IdContrato=res.data.data.servicio.IdContrato;
      this.servicio.NumContrato=res.data.data.servicio.NumContrato;
      this.servicio.Factura=res.data.data.servicio.Factura;
      this.servicio.EstadoFactura=res.data.data.servicio.EstadoFactura;
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
            this.servicio.IdServicio=Id;
            this.get_one();
            }
            
        });
        if (this.Id!=undefined)
        {
            this.servicio.IdServicio=this.Id;
            this.get_one();
        }

    }
}
</script>