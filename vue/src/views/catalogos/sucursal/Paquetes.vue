<template>
       <fieldset>
<div class="form-group">
<div class="custom-control custom-checkbox"  v-for="(lista,key,index) in ListaPaquetes" :key="index">    
<input type="checkbox" v-model="lista.Check" class="custom-control-input" :value="lista.IdPaquete" :id="lista.IdPaquete">
  <label class="custom-control-label" :for="lista.IdPaquete">{{lista.Nombre}}</label>
    </div>
    <br>
<!--<button class="btn btn-primary" @click="PrintCheck()">Cargar</button>
  <label>Checked names: {{  ListaPaquetesxSucursal }}</label>-->
  </div>
</fieldset>
</template>
<script>
export default {
name:'Paquetes',
props:['IdSucursal'],
data(){  
 return{
     ListaPaquetes:[],
     ListaPaquetesxSucursal:"",
     urlApi:"paquetexsucursal/get",
     ids:0,
     //urlApiPaquetexSucursal:'paquetexsucursal/post',
     PaquetexSucursal:{
     IdSucursal:0,
     IdPaquete:0
     }
   }
 },
 methods: {
  /* listaPaquete()
        {
 
          this.$http.get(
                this.urlApiPaquetexSucursal,
                {
                    params:{IdSucursal:this.IdSucursal}
                }
            ).then( (res) => {
              this.ListaPaquetesxSucursal =res.data.data.paquetexsucursal;
            });
              
        },*/
   async Guardar()
        {
let Lista=[];
this.ListaPaquetes.forEach(element => {
if(element.Check ==true)
{
  Lista.push(element.IdPaquete);
}
});
    let obj = {
      IdSucursal: this.IdSucursal,
      ListaIdPaquetes:Lista
    };

     await this.$http.post(
        'paquetexsucursal/post',
    JSON.stringify(obj),
        {
          headers: {
            'Content-Type': 'application/json'
          }
        },
      ).then((res) => {
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
      
 Lista()
        {
      this.$http.get(
                this.urlApi,
                {
                    params:{IdSucursal:this.IdSucursal}
                }
            ).then( (res) => {
              this.ListaPaquetes =res.data.data.paquetexsucursal;
            });
              
        }
          
   },
    created()
    {
    //  this.ListaPaquetes.IdSucursal = this.Id;
        this.bus.$off('Save');
        this.bus.$off('Nuevo');
         this.bus.$off('PrintCheck');

        this.bus.$on('Save',()=>
        {
           this.Guardar();
        });
        
        this.bus.$on('Nuevo',(data,Id)=> 
        {
       alert(Id);
            if (Id>0)
            {
            this.ids= Id;
  
            }
            
        });
        if (this.Id!=undefined)
        {
            this.PaquetexSucursal.IdSucursal=this.Id;

        }
        this.Lista();
         //    this.listaPaquete();
    }
}
</script>