<template>
    <div>  
        <Clist  @FiltrarC="Lista" :Filtro="Filtro" :regresar="true" :Nombre="NameList" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal">
            <template slot="Filtros">
                   
                <div class="col-2">
                    <a-select
                        show-search
                        :allowClear="true"
                        size="large"
                        :show-arrow="true"
                        :value="IdCliente"
                        placeholder="Buscar por Clientes"
                        :filter-option="false"
                        :not-found-content="fetching ? undefined : null"
                        @search="Get_Clientes"
                        @change="handleChange"
                    >
                        <a-spin v-if="fetching" slot="notFoundContent" size="small" />
                          <a-select-option  v-for="d in ListaClientes" :key="d.IdCliente">
                                {{ d.Nombre }}
                        </a-select-option>
                    </a-select>
                </div>
                 
                <!--<div class="form-group mb-2 mr-2">
                    <v-select  placeholder="Cliente"  label="Nombre" v-model="IdCliente" :filterable="false" :reduce="ListaClientes => ListaClientes.IdCliente" :options="ListaClientes" @search="Get_Clientes" >
                      <template slot="no-options">
                        No se encontraron resultados
                        </template>
                        <template  slot="option" slot-scope="option">
                            <div class=" d-center list" >
                                <img :src='option.owner.avatar_url'/>
                                {{ option.Nombre }}
                            </div>
                        </template>
                        <template slot="selected-option" slot-scope="option">
                            <div class=" selected d-center">
                            <img :src='option.owner.avatar_url'/>
                                {{Get_Substring(option.Nombre)}}
                            </div>
                        </template>
                    </v-select>
                </div>-->
               
                <div class="col-2">
                    <a-select
                        show-search
                        :allowClear="true"
                        size="large"
                        :show-arrow="true"
                        :value="IdClienteS"
                        placeholder="Buscar por Sucursales"
                        :filter-option="false"
                        :not-found-content="fetchingSuc ? undefined : null"
                        @search="Get_ClienteSuc"
                        @change="handleChangeSuc"
                    >
                        <a-spin v-if="fetchingSuc" slot="notFoundContent" size="small" />
                          <a-select-option  v-for="d in ListaClienteSuc" :key="d.IdClienteS">
                                {{ d.Nombre }}
                        </a-select-option>
                    </a-select>
                </div>
                <!--
                <div class="form-group mb-2 mr-2">
                    <v-select   placeholder="Seleccionar sucursal"  label="IdClienteS" v-model="IdClienteS" :filterable="false" :reduce="ListaClienteSuc => ListaClienteSuc.IdClienteS" :options="ListaClienteSuc" @search="Get_ClienteSuc" >
                      <template slot="no-options">
                        No se encontraron resultados
                        </template>
                        <template  slot="option" slot-scope="option">
                            <div class=" d-center list" >
                                <img :src='option.owner.avatar_url'/> 
                                {{ option.Nombre }}
                            </div>
                        </template>
                        <template slot="selected-option" slot-scope="option">
                            <div class=" selected d-center">
                                <img :src='option.owner.avatar_url'/>
                                    {{ Get_Substring(option.Nombre)}}
                            </div>
                        </template>
                    </v-select>
                </div>-->
                 <div class="form-group mb-2 mr-2">
                    <v-date-picker
                        mode='range'
                        v-model='rangeDate'
                        @input="Lista"
                        :input-props='{
                            class: "form-control calendar",
                            placeholder: "Fecha Inicio",
                            readonly: true
                        }'
                    />
                </div>
                <div class="form-group mb-2 mr-2">
                    <button type="button" @click="ClearDate" class="btn-icon"><i class="fas fa-calendar-times"></i></button>
                </div>
                
            </template>
            <template slot="header">
            <tr>
                <th>
                    Folio
                </th>
                <th>
                    Proyecto
                </th>
                <th>
                    Fecha Inicio
                </th>
                <th>
                    Fecha Termino
                </th>
                <th>
                    Cliente
                </th>
                <th>Sucursal</th>
                <th>
                    Dirección
                </th>
                <th>
                    Ciudad
                </th>
                <th class="text-center tw-2">
                    Acciones
                </th>
            </tr>
                  
            </template>
             <template slot="body">
                   <tr v-for="(lista,key,index) in ListaProyectos" :key="index" >
                       <td>{{lista.Folio }}</td>
                       <td>{{lista.Proyecto }}</td>
                       <td>{{lista.FechaI }}</td>
                       <td>{{lista.CantidadTermino}} {{ lista.FechaTermino }}(s)</td>
                       <td>{{lista.Cliente }}</td>
                       <td>{{lista.Sucursal }}</td>
                       <td>{{lista.Direccion }}</td>
                       <td>{{lista.Ciudad }}</td>
                       <td class="col-acciones text-center">
                            <span v-if="lista.Archivo!=''">
                                <button @click="OpenFile(lista.Archivo)" type="button" class="btn-icon-03 mr-2" v-b-tooltip.hover title="Ver Documento de Autorización"><i class="far fa-file-pdf"></i></button>
                            </span>
                            <button v-b-tooltip.hover title="Editar" @click="go_toForm(lista)" type="button" class="btn-icon mr-2" ><i class="fas fa-pencil-alt"></i></button>
                         
                            <span v-if="lista.Estatus=='Cerrado'">
                                <button v-b-tooltip.hover title="Proyecto Cerrado" class="btn-icon-03 mr-2" type="button"><i class=" fa fa-lock"></i></button>
                            </span> 
                            <span v-else>
                                 <button v-b-tooltip.hover title="Finalizar Proyecto" @click="EndProyect(lista.IdProyecto)" type="button" class="btn-icon-03 mr-2"><i class="fa fa-check-square" aria-hidden="true"></i></button>
                            </span>

                            <button v-b-tooltip.hover title="Eliminar" @click="Eliminar(lista.IdProyecto)" type="button" class="btn-icon-02 mr-2"><i class="fas fa-trash"></i></button>
                        </td>
                   </tr>
            </template>
        </Clist>
</div>
</template>
<script>

import Clist from '@/components/Clist.vue';

export default {
    name :'list',
    components :{
        Clist
    },
    data() {
        return {
            FormName:'spend_proform',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:false,//indica si es modal o no,
            CloseModal:true,//indica si el modal cierra o de lo contrario asignarle un evento al boton
            size :"modal-xl",
            NameList:"Lista de Proyectos",
            NameForm:"Lista de Proyetos",
            urlApi:"spendpoject/get",
            ListaProyectos:[],
            ListaHeader:[],
            TotalPagina:2,
            Pag:0,
            Rutaicono:'',
            ListaClientes: [],
            ListaClienteSuc: [],
            IdCliente:'Clientes ',
            IdClienteS:'Sucursales',
            Filtro:{
                Nombre:'',
                Placeholder:'Folio | Proyecto.',
                TotalItem:0,
                Pagina:1,
                IdCliente:0,
                IdClienteS:0,
                FechaI:'',
                FechaF:'',
            },
            rangeDate:{start:'',end:''},
            UrlPdf:'',
            fetching:false,
            fetchingSuc:false,
        }
    },
    methods: {
        Eliminar(Id)
        {
           
            this.$swal({
                title: 'Esta seguro que desea eliminar este dato?',
                text: 'No se podra revertir esta acción',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No, mantener',
                showCloseButton: true,
                showLoaderOnConfirm: true
                }).then((result) => {
                if(result.value) {
                   
                    this.$toast.success('Información eliminada');
                     this.$http.delete(
                            'spendpoject/' + Id
                        ).then( (res) => {
                                this.Lista();
                        });
                        
                    } 
                });
        },
       async Lista()
        {
            if(this.rangeDate!=null){
                this.Filtro.FechaI=this.rangeDate.start;
                this.Filtro.FechaF=this.rangeDate.end;
           }
            await this.$http.get(
                this.urlApi,
                {
                    params:{Nombre:this.Filtro.Nombre,IdCliente:this.Filtro.IdCliente,IdClienteS:this.Filtro.IdClienteS,Entrada:50,pag:0, RegEstatus:'A',FechaI:this.Filtro.FechaI,FechaF:this.Filtro.FechaF}
                }
            ).then( (res) => {
                this.ListaProyectos =res.data.proyecto;
                this.UrlPdf =res.data.UrlPdf;
            });
              
        },go_toForm(obj){
            this.$router.push({name:'spend_proform', params: {pobj:obj,Id:obj.IdProyecto}});
        },
        handleChange(selectedItems){
            this.IdCliente = selectedItems;
        },
        Get_Clientes(search) {
            
            this.fetching=true; 
            this.$http.get(
                'clientes/get',
                {
                    params:{Nombre:search,Entrada:15,pag:0, RegEstatus:'A'}
                }
            ).then( (res) => {
                this.fetching=false;
                this.ListaClientes=res.data.data.clientes;
            });
        },async Get_ClienteSuc(search)
        {   
            //this.ListaClienteSuc=[{IdClienteS:0,Nombre:'Buscar Sucursales'}];
            this.fetchingSuc=true;
            await this.$http.get(
                'clientesucursal/get',
                {
                    params:{Nombre:search,Entrada:15,IdCliente:this.Filtro.IdCliente, RegEstatus:'A'}
                }
            ).then( (res) => {
                
                this.fetchingSuc=false;
                this.ListaClienteSuc =res.data.data.clientesucursal;
               
                
            });
              
        },
        handleChangeSuc(selectedItems){
            this.IdClienteS = selectedItems;
        },Get_Substring(value){
            if( value!=undefined){
               value= value.substring(0,18) 
            }
            return value;
        },OpenFile(NameFile){
            window.open(this.UrlPdf+NameFile, '_blank');
        },ClearDate(){
            this.rangeDate={start:'',end:''};
            this.Lista();
        },EndProyect(IdProyecto){
            this.$swal({
                title: 'Esta seguro que desea finalizar el proyecto?',
                text: 'No se podra revertir esta acción',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No, mantener',
                showCloseButton: true,
                showLoaderOnConfirm: true
                }).then((result) => {
                if(result.value) {
                   
                    this.$toast.success('El proyecto ha sido finalizado');
                     this.$http.get(
                            'spendpoject/finish',
                            {
                                params:{IdProyecto:IdProyecto}
                            }
                        ).then( (res) => {
                                this.Lista();
                        });
                        
                    } 
            });
        }
    },
    created()
    {
     
        this.bus.$off('List');
         this.bus.$off('Regresar');
         this.bus.$off('Nuevo');
        this.Lista();

         this.bus.$on('Nuevo',()=> 
        {
            let obj={IdProyecto:0};
            this.go_toForm(obj);
        });

         this.bus.$on('List',()=> 
        {
            this.Lista();
        });

         this.bus.$on('Regresar',()=> 
        {
            this.$router.push({name:'spendplan'});   
        });
    },
    watch: {
    // whenever question changes, this function will run
    IdCliente: function (NewValue, OldValue) {
        this.Filtro.IdCliente=0;
        this.IdClienteS='Sucursales';
        this.Filtro.IdClienteS=0;
        this.ListaClienteSuc=[];
        if(NewValue<=0 ){
             this.ListaClientes=[];
             
             this.IdCliente='Clientes';
        }else{
            this.Filtro.IdCliente=NewValue;
            //this.Get_ClienteSuc("",null,2);
        }
        this.Lista();
    },
    IdClienteS: function (NewValue, OldValue) {
        this.Filtro.IdClienteS=0;
        if(NewValue<=0 ){
            this.ListaClienteSuc=[];
            this.IdClienteS='Sucursales';
        }else{
            this.Filtro.IdClienteS=NewValue;    
        }
       this.Lista();
    }
    
  },
}
</script>
<style scoped>

.ant-select-selection--single{
    height: 37px;
    padding-right: 10px;
}

.col-2{
    padding-right: 4px;
    padding-left: 4px;
    
    max-width: 220px;
}

</style>