<template>
<!--
  <div class="row">
    <div class="col-12 intro" v-if="isVisible">
        <form class="form-inline mb-2">
          <label class="mr-2">Año</label>
            <select @change="Lista" v-model="Grafica1.Anio" class="form-control form-control-sm mr-2">
                <option v-for="(item, index) in ListaAnios" :key="index" :value="item">{{item}}</option>
            </select>
            <label class="mr-2">Vendedor</label>
            <select @change="Lista" v-model="Grafica1.IdVendedor" class="form-control form-control-sm">
                <option>Selec. Vendedor</option>
                <option v-for="(item, index) in Listavendedores" :key="index" :value="item.IdTrabajador">{{item.Nombre}}</option>
            </select>
        </form>
    </div>
    <div class="col-12">
        <div class="card card-grafica-3">
            <div class="card-header">
                <h1 class="card-title">Forecast</h1>
            </div>
            <div class="card-body">
                <div class="row justify-content-start">
                    <div class="col-md-6 col-lg-6 mb-3">
                        <div class="card border-left-primary h-100 py-2">
                            <div class="card-body p-2">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div
                                            class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            {{data.MesUno}}</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{data.Uno}}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6  mb-3">
                        <div class="card border-left-success  h-100 py-2">
                            <div class="card-body p-2">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div
                                            class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            {{data.MesDos}}</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{data.Dos}}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <div class="card border-left-info h-100 py-2">
                            <div class="card-body p-2">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div
                                            class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            {{data.MesTres}}</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{data.Tres}}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <div class="card border-left-primary h-100 py-2">
                            <div class="card-body p-2">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div
                                            class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            {{data.MesCuatro}}</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{data.Cuatro}}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>-->
  <div class="col-md-12 col-lg-6 mb-2">
    <div class="row ">
        <div class="col-12">
            <div class="card card-grafica" style="height: 345px;">
                <div class="card-body">
                    <div class="filtro-grafic" id="grafica_004" style="display: none;">
                        <div class="row justify-content-start">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <form class="form-inline">
                                    <label class="mr-2">Año</label>
                                    <select @change="Lista" v-model="Grafica1.Anio" class="form-control form-control-sm mr-2">
                                    <option v-for="(item, index) in ListaAnios" :key="index" :value="item">{{item}}</option>
                                    </select>
                                    <label class="mr-2">Vendedor</label>
                                    <select @change="Lista" v-model="Grafica1.IdVendedor" class="form-control form-control-sm">
                                    <option>Selec. Vendedor</option>
                                    <option v-for="(item, index) in Listavendedores" :key="index" :value="item.IdTrabajador">{{item.Nombre}}</option>
                                    </select>
                                </form>
                                <button type="button" class="btn close abs_01"
                                    @click="Ocultar()"><i
                                        class="fal fa-times"></i></button>
                            </div>
                        </div>
                    </div><!--Filtro-->
                    <div class="row">
                        <div class="col-10 col-sm-10 col-md-8 col-lg-8">
                            <h6 class="title-grafic side-l">Forecast</h6>
                        </div>
                        <div class="col-2 col-sm-2 col-md-4 col-lg-4 text-right">
                            <button type="button" class="btn-fil-002" title="Filtros"@click="mostrarfiltro()">
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <hr>
                        </div>
                    </div> <!--Titulo-->
                    <div class="row justify-content-center mt-5">
                        <div class="col-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
                            <div class="row">
                                <div class="col-md-6 col-lg-6 mb-3">
                                        <div class="card border-left-primary h-100 py-2">
                                            <div class="card-body p-2">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div
                                                            class="text-xs font-weight-bold text-fin-03 text-uppercase mb-1">
                                                            {{data.MesUno}}</div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                            ${{data.Uno}}
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="icon-fin-03">
                                                            <i class="fas fa-dollar-sign"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="col-md-6 col-lg-6  mb-3">
                                        <div class="card border-left-success h-100 py-2">
                                            <div class="card-body p2">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                            {{data.MesDos}}
                                                        </div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                            ${{data.Dos}}
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="icon-fin-02">
                                                            <i class="fas fa-dollar-sign"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                        <div class="card border-left-info h-100 py-2">
                                            <div class="card-body p-2">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div
                                                            class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                            {{data.MesTres}}</div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                            ${{data.Tres}}
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="icon-fin">
                                                            <i class="fas fa-dollar-sign"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                        <div class="card border-left-adi h-100 py-2">
                                            <div class="card-body p-2">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div
                                                            class="text-xs font-weight-bold text-fin-04 text-uppercase mb-1">
                                                            {{data.MesCuatro}}</div></div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                           ${{data.Cuatro}}
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="icon-fin-04">
                                                            <i class="fas fa-dollar-sign"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!--Forecast-->
</template>

<script>
export default {
  data() {
    return {
      Listavendedores:[],
      ListaAnios:[],
      urlApi:'crmgraficas/forecast',
      urlApivendedor:"trabajador/ListTrabRolQuery",
      Grafica1:{
            Anio:'2019',
            Mes:'',
            IdVendedor:''
        },
      data:{
        Cuatro: 0,
        Dos: 0,
        MesCuatro:"",
        MesDos: "",
        MesTres: "",
        MesUno: "",
        Tres: 0,
        Uno: 0,
      },
      isVisible:false
    }
  },created() {

    this.bus.$off('crmOcultar4');
    this.bus.$on('crmOcultar4',(data)=> 
    {
      this.Ocultar(data);
    });

    this.Anios();
  },methods: {
      mostrarfiltro()
      {
          this.isVisible = true;
      },

      Ocultar(){
          this.isVisible = false;
      },
    async Anios()
    {
      await this.$http.get(
          'funciones/getanios',
      ).then( (res) => {
          this.ListaAnios =res.data.ListaAnios;
          this.Grafica1.Anio = res.data.AnioActual
          this.Vendedores();
      });
    },
    async Vendedores()
    {
        await this.$http.get(
            this.urlApivendedor,
            {
                params:{Nombre:'',RegEstatus:'A',Entrada:200,pag:1,Rol:JSON.stringify(['Vendedor','Gerente de ventas'])}
            }
        ).then( (res) => {
            //Rol=['Vendedor','Gerente de ventas']"
          this.Listavendedores =res.data.data.lista;
          this.Grafica1.IdVendedor = res.data.data.lista[0].IdTrabajador;
          this.Lista();
        });
          
    },

    async Lista()
    {
        await this.$http.get(
            this.urlApi,
            {
                params:{Anio:this.Grafica1.Anio,IdVendedor:this.Grafica1.IdVendedor}
            }
        ).then( (res) => {
          this.data = res.data.data
         
        });
          
    },
    Ocultar(data){
      this.isVisible = data;
    }

  },
}
</script>

<style>

</style>