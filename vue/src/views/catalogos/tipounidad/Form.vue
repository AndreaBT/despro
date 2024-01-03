<template>
    <div>
                <div v-if="ContenidoForm==0 && loadform==true" class="modal-body form-cotizacion">
                    <div class="form-group form-row justify-content-center">
                  
                        <div class="col-md-5 col-lg-5">
                             <div v-if="this.Imagen!=''" :class="'circular_shadow borde-gris'">
                            <svg-injector  :class-name="'svg-inject iconic-signal-none'" :src="Imagen"></svg-injector>
                             </div>
                               <div v-else="" :class="'circular_shadow borde-gris'">
                             </div>
                             <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Icono" :Mensaje="errorvalidacion.Icono[0]"></Cvalidation></label>
                        </div>
                    </div>
                    <div class="form-inline justify-content-center mt-3">
                            <label class="mr-2">Nombre</label>
                            <input type="text" v-model="tipounidad.Nombre" class="form-control mr-2" placeholder="Escribir Nombre">
                            <button type="button" @click="get_iconos(5)" class="btn btn-03 search02" >Buscar Icono</button>
                            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Nombre" :Mensaje="errorvalidacion.Nombre[0]"></Cvalidation></label>
                    </div>
                </div>
            
            <!--Fin head del panel-->
              
   
    <section v-else-if="ContenidoForm==1 && loadform==true">
        <div class="nav nav-tabs nav-tabs-table">
    <b-tabs >
        <b-tab @click="get_iconos(data.Id)" v-for="(data,index) in ListaTipoico" :key="index" :title="data.Nombre" >
              <div class="tab-content tab-content-table">
                        <div class="tab-pane fade show active" id="nav-dato" role="tabpanel" aria-labelledby="dato-tab">
                            <div class="row">
                             
                                  <div   v-for="(data2,index2) in ListaIconos" :key="index2" @click="selectedItem(data2)" class="col-md-4 col-lg-2">
                                    <div  class="circular_shadow borde-gris">
                                        <svg-injector  :class-name="'svg-inject iconic-signal-none'" :src="data2.Imagen"></svg-injector> 
                                    </div>
                                    <h3 class="text-center equipo">{{data2.Nombre}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
        </b-tab>
     
    </b-tabs>
    </div>

      
    </section>
    </div>
</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from '@/components/Cbtnsave.vue'
import Clist from '@/components/Clist.vue';

export default {
    name:'Form',
    props:['IdTipoU','NameList','poBtnSave'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'tipounidad',//Sirve para donde va regresar
            tipounidad:{            
                IdTipoU:0,
                Nombre:"",
                IdIconoEq:"",
            },ContenidoForm:0,
            urlApi:"tipounidad/recovery",
            urlApiIco:"iconos_eq/get",
            ListaIconos:[],
            ListaTipoico:[{Id:5,Nombre:'Cocinas'},{Id:2,Nombre:'Electricidad'},{Id:1,Nombre:'HVAC'},{Id:7,Nombre:'Hotel'},{Id:3,Nombre:'Industria'}],
            Imagen:'',
            ItemSelect:'',
            errorvalidacion:[],
            loadform:false
        }
    },
    components:{
        Cbtnsave,
        Clist,
    },
    methods :
    {
    
       async Guardar()
        {
            //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;
            let formData = new FormData();
            formData.set('IdTipoU',this.tipounidad.IdTipoU);
            formData.set('Nombre',this.tipounidad.Nombre);
            formData.set('IdIconoEq', this.tipounidad.IdIconoEq);

            await this.$http.post(
                'tipounidad/post',
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
                this.errorvalidacion=err.response.data.message.errores;
                 this.poBtnSave.disableBtn=false;
                this.poBtnSave.toast=2;  

            });
       
        },
         Limpiar()
        {
            this.errorvalidacion=[];
            this.tipounidad.IdTipoU=0,
            this.tipounidad.Nombre="",
            this.tipounidad.IdSucursal="",
            this.tipounidad.IdIconoEq="",
            this.Imagen='';
            this.ContenidoForm=0;
            this.$emit('titulomodal','Tipo Unidad');
        },
        get_one()
        {
          
            this.$http.get(
                this.urlApi,
                {
                    params:{IdTipoU: this.tipounidad.IdTipoU}
                }
            ).then( (res) => {
                this.tipounidad.IdTipoU=res.data.data.tipounidad.IdTipoU;
                this.tipounidad.Nombre=res.data.data.tipounidad.Nombre;
                this.tipounidad.IdIconoEq=res.data.data.tipounidad.IdIconoEq;
                this.Imagen=res.data.data.rutaicono+res.data.data.tipounidad.Imagen;    
                this.loadform=true;
            });
        },async get_iconos(Tipo){   
             
            this.ListaIconos=[];        
            await this.$http.get(
                this.urlApiIco,
                {
                    params:{Tipo:Tipo,Entrada:50,pag:0, RegEstatus:'A'}
                }
            ).then( (res) => {
                this.ListaIconos =res.data.data.iconos;
                this.ContenidoForm=1;
                this.$emit('titulomodal','Iconos');
                this.ItemSelect=Tipo;
            });

        },ReturnConten(){
            this.$emit('titulomodal','Tipo Unidad');
            this.ContenidoForm=0;
            
        },selectedItem(objeto){
            this.tipounidad.IdIconoEq=objeto.IdIconoEq;
            this.tipounidad.Nombre=objeto.Nombre;
            this.Imagen=objeto.Imagen;
            this.ReturnConten();
        }

    },
    created() {
        this.bus.$off('ReturnConten');
       
        this.bus.$off('Nuevo');

      
        
        //Este es para modal
        this.bus.$on('Nuevo',(data,Id)=> 
        {   
             this.poBtnSave.disableBtn=false; 
              this.loadform=false;

             this.bus.$off('Save');
             this.bus.$on('Save',()=>
            {
            this.Guardar();
            });

             this.Limpiar();
            if (Id>0)
            {
            this.tipounidad.IdTipoU=Id;
            this.get_one();
            }
            else{
                this.Imagen='';
                  this.loadform=true;
            }
            this.bus.$emit('Desbloqueo',false);
            
        });
        if (this.Id!=undefined)
        {
            this.tipounidad.IdTipoU=this.Id;
            this.get_one();
        }

        this.bus.$on('ReturnConten',()=> 
        {
            this.ReturnConten();
        });
       
    }
}
</script>