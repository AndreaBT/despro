<template>

    <div class="row">
        <div class="col-lg-4">
            <label >Nombre</label>
            <input  class="form-control"  v-model="Equipo.Nequipo" placeholder="Nombre">
            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Nequipo" :Mensaje="errorvalidacion.Nequipo[0]"></Cvalidation></label>
        </div>

        <div class="col-lg-8">
            <label >Ubicación del Equipo</label>
            <input  class="form-control"  v-model="Equipo.Ubicacion" placeholder="Ubicación del equipo">
            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Ubicacion" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
        </div>

        <div class="col-lg-4">
            <label >Marca</label>
            <input  class="form-control"  v-model="Equipo.Marca" placeholder="Marca">
            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Marca" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
        </div>

        <div class="col-lg-4">
            <label >Modelo</label>
            <input  class="form-control"  v-model="Equipo.Modelo" placeholder="Modelo">
            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Modelo" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
        </div>

        <div class="col-lg-4">
            <label>Año</label>
            <input  class="form-control"  v-model="Equipo.Ano" placeholder="Año">
            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Ano" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
        </div>

        <div class="col-lg-4">
            <label >Serie</label>
            <input  class="form-control"  v-model="Equipo.Serie" placeholder="Serie">
            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.Serie" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
        </div>

        <div class="col-lg-8">
            <label >Tipo de Unidad</label>
            <select v-model="Equipo.TipoUnidad" class="form-control">
                <option value="" >Seleccione una opción</option>
                <option  v-for="(item, index) in ListTipoUnidad" :key="index" :value="item.IdTipoU"  >{{item.Nombre}}</option>
            </select>
            <label id="lblmsuser" style="color:red"><Cvalidation v-if="this.errorvalidacion.TipoUnidad" :Mensaje="'Campo obligatorio'"></Cvalidation></label>
        </div>

        <div class="col-lg-4"></div>
        <div class="col-lg-4">
          <img :src="RutaQr">
        </div>
    </div>

</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from '@/components/Cbtnsave.vue'
import Cvalidation from '@/components/Cvalidation.vue'

export default {
    name:'Form',
    props:['oClienteSucursalP','poBtnSave'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'equipamiento',//Sirve para donde va regresar
            Equipo:{
                IdEquipo:0,
                IdCliente:"",
                Ubicacion:"",
                Marca:"",
                Modelo:"",
                Serie:"",
                TipoUnidad:'',
                Ano:"",
                IdClienteS:"",
                Nequipo:"",
            },
            urlApi:"equipos/recovery",
            ListTipoUnidad:[],
            oClienteSucursal:{},
            RutaQr:"",
            errorvalidacion:[]
        }
    },
    components:{
        Cbtnsave,Cvalidation
    },
    methods :
    {
        async Guardar()
        {
             //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;

            await this.$http.post(
                'equipos/post',
                this.Equipo,
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
            this.Equipo.IdEquipo= 0;
            this.Equipo.Nombre="";
            this.Equipo.Ubicacion="";
            this.Equipo.Marca="";
            this.Equipo.Modelo="";
            this.Equipo.Serie="";
            this.Equipo.TipoUnidad='';
            this.Equipo.Ano="";
            this.Equipo.Nequipo="";
            this.RutaQr="";
            this.errorvalidacion=[];
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdEquipo: this.Equipo.IdEquipo}
                }
            ).then( (res) => {
                this.Equipo=res.data.data.Equipo;
                this.RutaQr=res.data.data.RutaQr;
            });
        },
        get_tipoUnidad(){
            this.$http.get(
                'tipounidad/get',
                {
                    params:{RegEstatus: 'A',Entrada:10000}
                }
            ).then( (res) => {
                this.ListTipoUnidad=res.data.data.tipounidad
            });
        }
    },
    created() {
        this.bus.$off('Nuevo');

        if (this.oClienteSucursalP!=undefined)
        {
            sessionStorage.setItem('oClienteSucursal',JSON.stringify(this.oClienteSucursalP));
        }
        this.oClienteSucursal=JSON.parse(sessionStorage.getItem('oClienteSucursal'));
        //Este es para modal
        this.bus.$on('Nuevo',(data,Id)=> 
        {
            this.poBtnSave.disableBtn=false; 
            this.bus.$off('Save');
            this.bus.$on('Save',()=>
            {
                this.Guardar();
            });

            this.get_tipoUnidad();
            this.Limpiar();
            this.Equipo.IdClienteS=this.oClienteSucursal.IdClienteS;
            this.Equipo.IdCliente=this.oClienteSucursal.IdCliente;

            if (Id>0)
            {
                this.Equipo.IdEquipo=Id;
                this.get_one();
            }
            this.bus.$emit('Desbloqueo',false);
        });

        if (this.Id!=undefined)
        {
            this.Equipo.IdEquipo=this.Id;
            this.get_one();
        }
    }
}
</script>