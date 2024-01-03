<template>
<div>
   
    <CHead :oHead="oHead"></CHead>
    
    <div class="row mt-2">
        
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <form class="form-inline">
                                <div class="form-group mr-2">
                                    <input v-on:keyup="Lista" v-model="Filtro.Nombre"  type="text" class="form-control lup"  placeholder="Sucursal...">
                                </div>
                                <div class="form-group">
                                    <label>Filas &nbsp;</label>
                                    <select @change="Lista" v-model="Filtro.Entrada" class="form-control">
                                        <option :value="10">10</option>
                                        <option :value="50">50</option>
                                        <option :value="100">100</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-12 col-lg-12">
                        <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <ul class="box-form">
                                <li v-for="(lista,key,index) in ListaClientes" :key="index" >
                                    <div class="box">
                                        <div @click="go_to_equipo(lista)" class="row">
                                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 text-center">
                                                <div class="aligner">
                                                    <div class="aligner-item">
                                                        <img :src="RutaIcoEmp+lista.IdIconoEmp" class="img-emp" alt="jobswell">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 text-center">
                                                <h5 class="titulo-box">{{lista.Nombre}}</h5>
                                            </div>
                                        </div>
                                        <span :class="lista.Incidencia >0 ?'noti-02 bg-warning' :'noti-02 bg-success'">{{lista.TotalE}}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                           <Pagina :Filtro="Filtro" @Pagina="Lista" :Entrada="Filtro.Entrada" ></Pagina>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--
        <div style="cursor:pointer" v-for="(lista,key,index) in ListaClientes" :key="index" class="col">
            <b-img @click="go_to_equipo(lista)"  :src="RutaIcoEmp+lista.IdIconoEmp" rounded="circle" alt="Circle image"></b-img>
        </div>  -->
    </div>
</div>

</template>
<script>
import Pagina from '@/components/Cpagina.vue'
export default {
    name :'list',
    props:['ocliente'],
    components :{
        Pagina
    },
    data() {
        return {
            NameList:"Sucursales",
            urlApi:"clientesucursal/get",
            ListaClientes:[],
            ListaHeader:[],
            TotalPagina:2,
            Pag:0,
            oClienteP:{},
            ShowBtns:false,
            RutaIcoEmp:'',
            oHead:{
                isreturn:true,
                Title:'Sucursales',
                Url:'monitoreo_cli',
                ShowHead:true,
            }, 
           Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                 TotalItem:0,
                Pagina:1,
                Entrada:10
            },
        }
    },

    methods: {
       async Lista()
        {
            await this.$http.get(
                this.urlApi,
                {
                    params:{Nombre:this.Filtro.Nombre,IdSucursa:this.oClienteP.IdSucursal,IdCliente:this.oClienteP.IdCliente,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina, RegEstatus:'A'}
                }
            ).then( (res) => {
                this.ListaClientes =res.data.data.clientesucursal;
                this.RutaIcoEmp=res.data.RutaIcoEmp;
                this.ListaHeader=res.data.headers;
                this.Filtro.Entrada=res.data.data.pagination.PageSize;
                this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
            });
        },
        go_to_equipo(objsucursal){
            this.$router.push({name:'mon_equipo',params:{obj:objsucursal,objCliente:this.oClienteP}});
        }
    },
    created()
    {
        //recibiendo objetos
        if (this.ocliente!=undefined)
        {
            sessionStorage.setItem('IdSaved',JSON.stringify(this.ocliente));
        }
        this.oClienteP=JSON.parse( sessionStorage.getItem('IdSaved'));
        
        var osucursalSession=JSON.parse( sessionStorage.getItem('clientelog'));
    
        if(osucursalSession==null){//Datos desde el admin
        }else{//datos desde login admin template
            //#region desde el login
            this.oClienteP=JSON.parse( sessionStorage.getItem('clientelog'));
            this.oHead.ShowHead=false;
        }
        
        this.oHead.Title=this.oClienteP.Nombre+" | "+this.NameList;
        
        this.bus.$off('Regresar');
    
        this.bus.$on('Regresar',()=> 
        {
            this.Regresar();
        });

    },
    mounted() {
        this.Lista();
    },
    destroyed() {
        sessionStorage.removeItem('IdSaved');
    },
}
</script>