<template>
    <div class="form-row">
        <div class="col-lg-4 form-group">
            <label >Categoría *</label>
            <select class="form-control" v-model="vehiculo.IdCategoria" >
                <option :value="lista.IdCategoria"  v-for="(lista,key,index) in   ListaCategoria" :key="index">
                    {{lista.Nombre}}
                </option>
            </select>
            <Cvalidation v-if="this.errorvalidacion.IdCategoria" :Mensaje="'Campo obligatorio'"></Cvalidation>
        </div>

        <div class="col-lg-4 form-group">
            <label >Descripción</label>
            <input type="text" placeholder="Descripción" v-model="vehiculo.Categoria" class="form-control" >
            <Cvalidation v-if="this.errorvalidacion.Categoria" :Mensaje="'Campo olbigatorio'"></Cvalidation>
        </div>
    
        <div class="col-lg-4 form-group">
            <label >Marca</label>
            <input type="text" placeholder="Marca" v-model="vehiculo.Marca" class="form-control" >
            <Cvalidation v-if="this.errorvalidacion.Marca" :Mensaje="errorvalidacion.Marca[0]"></Cvalidation>
        </div>

        <div class="col-lg-4 form-group">
            <label >Modelo</label>
            <input type="text" placeholder="Modelo"  v-model="vehiculo.Modelo" class="form-control" >
            <Cvalidation v-if="this.errorvalidacion.Modelo" :Mensaje="errorvalidacion.Modelo[0]"></Cvalidation>
        </div>

        <div class="col-lg-4 form-group">
            <label >Número</label>
            <input type="text" placeholder="Numero"  v-model="vehiculo.Numero" class="form-control" >
            <Cvalidation v-if="this.errorvalidacion.Numero" :Mensaje="errorvalidacion.Numero[0]"></Cvalidation>
        </div>

        <div class="col-lg-4 form-group">
            <label >Año</label>
            <vue-numeric :minus="false" class="form-control" currency="" separator="," :precision="0" v-model="vehiculo.Ano"></vue-numeric>
            <Cvalidation v-if="this.errorvalidacion.Ano" :Mensaje="'Campo obligatorio'"></Cvalidation>
        </div>

        <div class="col-lg-4 form-group">
            <label >Placa</label>
            <input type="text" placeholder="Placa" v-model="vehiculo.Placa"  class="form-control" >
            <Cvalidation v-if="this.errorvalidacion.Placa" :Mensaje="errorvalidacion.Placa[0]"></Cvalidation>
        </div>


        <div class="col-lg-4 form-group">
            <label >Tipo de Cobro</label>
            <select class="form-control" v-model="vehiculo.TipoVehiculo">
                <option value="">Seleccione una opción</option>
                <option value="Kilometros">Kilometros</option>
                <option value="Horas">Horas</option>
            </select>
            <Cvalidation v-if="this.errorvalidacion.TipoVehiculo" :Mensaje="'Campo obligatorio'"></Cvalidation>
        </div>

        <div class="col-lg-4 form-group">
            <label >Costo</label>
            <vue-numeric :minus="false" class="form-control" currency="$" separator="," :precision="0" v-model="vehiculo.CostoAnual"></vue-numeric>
            <Cvalidation v-if="this.errorvalidacion.CostoAnual" :Mensaje="'Costo anual'"></Cvalidation>
        </div>

        <div class="col-lg-6 form-group">
            <label >Historial</label>
            <textarea rows="4"  placeholder="Historial" v-model="vehiculo.Historial"  class="form-control"></textarea>
            <Cvalidation v-if="this.errorvalidacion.Historial" :Mensaje="'Campo obligatorio'"></Cvalidation>
        </div>

        <div class="col-lg-6 form-group">
            <label >Inventario</label>
            <textarea rows="4"  placeholder="Inventario" v-model="vehiculo.Inventario"  class="form-control"></textarea>
            <Cvalidation v-if="this.errorvalidacion.Inventario" :Mensaje="'Campo obligatorio'"></Cvalidation>
        </div>
    </div>

</template>
<script>
//El props Id es cuando no es modal y se mando con props
//El export de btnsave es por si no se usa el modal
import Cbtnsave from '@/components/Cbtnsave.vue'
import Cvalidation from '@/components/Cvalidation.vue'
import Option from '../tiposervicio/option.vue'

export default {
    name:'Form',
    props:['IdVehiculo','poBtnSave'],
    data() {
        return {
            Modal:true,//Sirve pra los botones de guardado
            FormName:'vehiculo',//Sirve para donde va regresar
            ListaCategoria:[],
            vehiculo:{   
                IdVehiculo:0,
                Categoria:"",
                Marca:"",
                Modelo:"",
                Ano:"",
                Placa:"",
                Numero:"",
                TipoVehiculo:"",
                CostoAnual:"",
                IdSucursal:"",
                RegEstatus:"",
                IdCategoria:"",
                Inventario:"",
                Historial:"",
            },
            urlApi:"vehiculo/recovery",
            urlApiCategoria:"categoriavehiculo/get",
            errorvalidacion:[]
        }
    },
    components:{
        Cbtnsave,Cvalidation,
        Option,
    },
    methods :
    {
        async listacategoria(){
            await this.$http.get(
                this.urlApiCategoria,
                {
                    params:{RegEstatus:'A'}
                }
            ).then( (res) => {
                this. ListaCategoria=res.data.data.categoriavehiculo;
        
            });
        },
        async Guardar()
        {
            //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;
            let formData = new FormData();

            formData.set('IdVehiculo',this.vehiculo.IdVehiculo);
            formData.set('Categoria',this.vehiculo.Categoria);
            formData.set('Marca', this.vehiculo.Marca);
            formData.set('Modelo', this.vehiculo.Modelo);
            formData.set('Numero', this.vehiculo.Numero);
            formData.set('Ano',this.vehiculo.Ano);
            formData.set('Placa', this.vehiculo.Placa);
            formData.set('TipoVehiculo',this.vehiculo.TipoVehiculo);
            formData.set('CostoAnual',this.vehiculo.CostoAnual);
            formData.set('IdSucursal', this.vehiculo.IdSucursal);
            formData.set('IdCategoria', this.vehiculo.IdCategoria);
            formData.set('Inventario',this.vehiculo.Inventario);
            formData.set('Historial', this.vehiculo.Historial);

            await this.$http.post(
                'vehiculo/post',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                },
            ).then((res) => {
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
            this.vehiculo.IdVehiculo=0,
            this.vehiculo.Categoria="",
            this.vehiculo.Marca="",
            this.vehiculo.Modelo="",
            this.vehiculo.Numero="",
            this.vehiculo.Ano="",
            this.vehiculo.Placa="",
            this.vehiculo.TipoVehiculo="",
            this.vehiculo.CostoAnual="",
            this.vehiculo.IdSucursal="",
            this.vehiculo.IdCategoria="",
            this.vehiculo.Inventario="",
            this.vehiculo.Historial="",
            this.errorvalidacion=[]
        },
        get_one()
        {
            this.$http.get(
                this.urlApi,
                {
                    params:{IdVehiculo: this.vehiculo.IdVehiculo}
                }
            ).then( (res) => {
                this.vehiculo.IdVehiculo=res.data.data.vehiculo.IdVehiculo;
                this.vehiculo.Categoria=res.data.data.vehiculo.Categoria;
                this.vehiculo.Marca=res.data.data.vehiculo.Marca;
                this.vehiculo.Modelo=res.data.data.vehiculo.Modelo;
                this.vehiculo.Ano=res.data.data.vehiculo.Ano;
                this.vehiculo.Placa=res.data.data.vehiculo.Placa;
                this.vehiculo.Numero=res.data.data.vehiculo.Numero;
                this.vehiculo.TipoVehiculo=res.data.data.vehiculo.TipoVehiculo;
                this.vehiculo.CostoAnual=res.data.data.vehiculo.CostoAnual;
                this.vehiculo.IdSucursal=res.data.data.vehiculo.IdSucursal;
                this.vehiculo.IdCategoria=res.data.data.vehiculo.IdCategoria;
                this.vehiculo.Inventario=res.data.data.vehiculo.Inventario;
                this.vehiculo.Historial=res.data.data.vehiculo.Historial;   
            });
        }
    },
    created() {
        this.bus.$off('Nuevo');
        this.listacategoria();
  
        //Este es para modal
        this.bus.$on('Nuevo',(data,Id)=> 
        {
            this.poBtnSave.disableBtn=false;  
            this.bus.$off('Save');
            this.bus.$on('Save',()=>
            {
                this.Guardar();
            });

            this.Limpiar();
            if (Id>0)
            {
                this.vehiculo.IdVehiculo=Id;
                this.get_one();
            }
            this.bus.$emit('Desbloqueo',false);
        });
        
        if (this.Id!=undefined)
        {
            this.vehiculo.IdVehiculo=this.Id;
            this.get_one();
        }
    }
}
</script>