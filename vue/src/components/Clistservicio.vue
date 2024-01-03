

<template>
    <div>
        
        <input type="hidden" :disabled="validate">
        <!--<div class="row mt-2">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <nav class="navbar navbar-breadcrumb navbar-expand-md bg-breadcrumb breadcrumb-borde">
                    <div class="mr-auto">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb clearfix pt-3">
                                <li style="cursor:pointer;" v-if="regresar" @click="Regresar" class="breadcrumb-item active">Volver</li>
                                <li class="breadcrumb-item active">{{Nombre}}</li>
                            </ol>
                        </nav>
                    </div>
                    <form class="form-inline">
                        <template v-if="validate">
                            <button  v-if="isModal" @click="Nuevo(0)"  data-toggle="modal" data-target="#ModalForm"  data-backdrop="static" data-keyboard="false"  type="button" class="btn btn-bradcrumb btn-primary">{{NameButonNuevo}}</button>
                            <button v-else type="button" @click="Nuevo(1)"  class="btn btn-bradcrumb btn-primary">{{NameButonNuevo}}</button>
                        </template>
                    </form>
                </nav>
            </div>
        </div>-->
       
        <CHead :oHead="Head">
            <template slot="component">
                    <button v-if="btnCli2"  data-toggle="modal" data-target="#ModalNewUser"  data-backdrop="static" type="button" class="btn btn-bradcrumb btn-primary">Añadir</button>
            </template>

        </CHead>
        <div class="row justify-content-center mt-2">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <slot name="contoleradicional"></slot>
                <div class="card card-01">
                    <div class="row" v-show="this.FiltroC.Show">
                        <div class="col-md-12 col-lg-12">
                            <form class="form-inline">
                                <div class="form-group mr-2">
                                    <input autocomplete="off" v-on:keyup="pasarvalor" v-model="FiltroC.Nombre" type="text" class="form-control lup" id="inputPassword23" :placeholder="validateFiltros">
                                </div>
                                <div class="form-group  mr-2">
                                    <label>Filas &nbsp;</label>
                                    <select @change="Filtrar" v-model="FiltroC.Entrada" class="form-control">
                                        <option :value="10">10</option>
                                        <option :value="50">50</option>
                                        <option :value="100">100</option>
                                    </select>
                                </div>
                                 <slot name="Filtros">
                                </slot>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table-01 text-nowrap mt-2">
                            <thead >
                            <slot name="header"></slot>
                            </thead>
                            <tbody >
                                <slot name="body"></slot>
                                <slot name="botones">
                                    
                                </slot>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- <Pagina :Pag="Pag" :Entrada="Entrada" :Total="Total"  > </Pagina>-->
                    <Pagina :Filtro="Filtro" :Entrada="FiltroC.Entrada" @Pagina="Filtrar" ></Pagina>
                   
                </div>
            </div>
        </div>
        
    <!--Fin del conenido-->
    </div>
</template>
<script>
import Pagina from '@/components/Cpagina.vue'
export default {
    name:"Clist",
    props:['regresar','Nombre','Pag','Total','isModal','pShowBtnAdd','PNameButonNuevo','ShowHead','Filtro','NameReturn','btnCli'],
    components:{
        Pagina
         
    },

    data() {
        return {
            Entrada:50,
            ShowBtnAdd:true,
            NameButonNuevo:'Nuevo',
            Head:{
                ShowHead:true,
                Title:'Datos',
                BtnNewShow:true,
                BtnNewName:'Nuevo',
                isreturn:true,
                isModal:true,                 
                isEmit:true,
                Url:'',
                ObjReturn:'',
                NameReturn:'Regresar',
            },
            FiltroC:{
                Nombre:'',
                Entrada:10,
                Placeholder:'Buscar..',
                Show:true

            },
             btnCli2:false,
             
        }
    },
    methods :{
        Nuevo()
        {
          
            if (this.isModal==true)
            { 
                this.bus.$emit('Nuevo',true);
            }
            else{
                this.bus.$emit('Nuevo');
            }
        },
        Regresar()
        {
            this.bus.$emit('Regresar');    
            
        },

        pasarvalor()
        {
             this.Filtro.Nombre=this.FiltroC.Nombre;
        },

        Filtrar()
        {   
            if (this.FiltroC.Entrada!=this.Filtro.Entrada)
            {
               this.Filtro.Pagina=1; 
            }

            this.Filtro.Nombre=this.FiltroC.Nombre;
            this.Filtro.Entrada=this.FiltroC.Entrada;
            this.$emit('FiltrarC');   
        }
    },created() {
        /*
        if(this.PNameButonNuevo!=undefined){
            this.NameButonNuevo=this.PNameButonNuevo;
        }*/
        
    },computed: {
        validate(){
            if(this.pShowBtnAdd!=undefined){
                this.ShowBtnAdd=this.pShowBtnAdd;
                this.Head.BtnNewShow=this.pShowBtnAdd;
            }
            if(this.Nombre!=undefined){
                this.Head.Title=this.Nombre;
            }
            if(this.isModal!=undefined){
                this.Head.isModal=this.isModal;
            }
            
            if(this.PNameButonNuevo!=undefined){
                this.Head.BtnNewName=this.PNameButonNuevo;
            }
            if(this.regresar!=undefined){
                this.Head.isreturn=this.regresar;
            }
             if(this.ShowHead!=undefined){
                this.Head.ShowHead=this.ShowHead;
            }

            if(this.NameReturn!=undefined){
                this.Head.NameReturn=this.NameReturn;
            }
            if(this.btnCli!=undefined){
                this.btnCli2=this.btnCli;
            }
            
            
            return this.isModal;
        },
          validateFiltros(){
              if (this.Filtro !=undefined)
              {
                if(this.Filtro.Nombre!=undefined){
                    this.FiltroC.Nombre=this.Filtro.Nombre;
                }
                if(this.Filtro.Placeholder!=undefined){
                    this.FiltroC.Placeholder=this.Filtro.Placeholder;
                }
                 if(this.Filtro.Show!=undefined){
                    this.FiltroC.Show=this.Filtro.Show;
                }
                 if(this.Filtro.Entrada!=undefined){
                    this.FiltroC.Entrada=this.Filtro.Entrada;
                }
              }
            
            return this.FiltroC.Placeholder;
        },
         
    },

}
</script>