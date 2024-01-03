<template>
<div>
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-8 mb-2">
            <h3>Mantenimiento</h3>
            <hr>
            <div class="form-group form-row">
                <label class="col-sm-2 col-form-label">Año</label>
                <div class="col-sm-4">
                    <select @change="getData" v-model="Anio" class="form-control ">
                        <option v-for="(item,index) in ListaAnios" :key="index" :value="item">{{item}}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-8">
            <table class="table-01">
                <thead>
                    <tr>
                        <th scope="col">Tipo Servicio</th>
                        <th scope="col">Base Actual</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,index) in Lista" :key="index" >
                        <td>{{item.Concepto}}</td>
                        <td>
                            <vue-numeric  placeholder="$ 0.00" class="form-control form-finanza form-control-sm text-center" currency="$" separator="," :precision="0" v-model="item.data.BaseActual"></vue-numeric> 
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</template>
<script>
export default {
    props:['poBtnSave'],
    data() {
        return {
            IdConfigS:0,
            Lista:[],
            Anio:'',
            ListaAnios:[]
        }
    },
    methods: {
         get_anios(){ 
                this.Disabled=true;          
                this.$http.get(
                    'funciones/getanios',
                    {
                        params:{}
                    }
                ).then( (res) => {
                this.ListaAnios=res.data.ListaAnios;         
                this.Anio=res.data.AnioActual;
        
                this.getData();

                
                });                    
        },

          async getData()
        {
            await this.$http.get(
                'baseactual/getone',
                {
                    params:{IdConfigS:this.IdConfigS , RegEstatus:'A',Anio:this.Anio}
                }
            ).then( (res) => {
              this.Lista=res.data.data;
        
            });
              
        },
             async Guardar()
        {
            if (this.Lista.length>0)
            { 
             //deshabilita botones
            this.poBtnSave.toast=0; 
            this.poBtnSave.disableBtn=true;
                this.$http.post(
                    'baseactual/post',
                    {Detalle:this.Lista,Anio:this.Anio}
                ).then( (res) => {
                 this.poBtnSave.disableBtn=false;  
                this.poBtnSave.toast=1;
                this.bus.$emit('List');
                $('#ModalForm').modal('hide');
                    
                }).catch( err => {
                     this.poBtnSave.disableBtn=false;
                     this.poBtnSave.toast=2;  
                });
            }
        },
        
    },
    created() {
        this.bus.$off('AbrirBase');
       
       this.bus.$on('AbrirBase',(Id)=>{
            this.poBtnSave.disableBtn=false; 
           this.bus.$off('Save');
            this.bus.$on('Save',()=>
            {
                this.Guardar();
            });

           this.Lista=[];
           this.Anio=[];
           if (Id>0)
           {
               this.IdConfigS=Id;
               this.get_anios();

           }

        });

       

    },
}
</script>