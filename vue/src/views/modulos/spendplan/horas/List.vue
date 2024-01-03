<template>
    <div>  
        <Clist @FiltrarC="Lista" :Filtro="Filtro" :regresar="true" :Nombre="NameList" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal">
            <template slot="Filtros">
                <div class="form-group mb-2 mr-2">
                    <v-select  placeholder="Cliente"  label="Nombre" v-model="IdCliente" :filterable="false" :reduce="ListaClientes => ListaClientes.IdCliente" :options="ListaClientes" @search="Get_Clientes" >
                      <template slot="no-options">
                        No se encontraron resultados
                        </template>
                        <template  slot="option" slot-scope="option">
                            <div class=" d-center list" >
                                <!--<img :src='option.owner.avatar_url'/> -->
                                {{ option.Nombre }}
                            </div>
                        </template>
                        <template slot="selected-option" slot-scope="option">
                            <div class=" selected d-center">
                                <!--<img :src='option.owner.avatar_url'/>-->
                                {{ Get_Substring(option.Nombre) }}
                            </div>
                        </template>
                    </v-select>
                </div>

                <div class="form-group mb-2 mr-2">
                    <v-select   placeholder="Seleccionar sucursal"  label="IdClienteS" v-model="IdClienteS" :filterable="false" :reduce="ListaClienteSuc => ListaClienteSuc.IdClienteS" :options="ListaClienteSuc" @search="Get_ClienteSuc" >
                      <template slot="no-options">
                        No se encontraron resultados
                        </template>
                        <template  slot="option" slot-scope="option">
                            <div class=" d-center list" >
                                <!--<img :src='option.owner.avatar_url'/> -->
                                    {{ option.Nombre }}
                            </div>
                        </template>
                        <template slot="selected-option" slot-scope="option">
                            <div class=" selected d-center">
                                <!--<img :src='option.owner.avatar_url'/>-->
                                    {{ Get_Substring(option.Nombre) }}
                            </div>
                        </template>
                    </v-select>
                </div>

                <div class="form-group mb-2 mr-2">
                    <v-select   placeholder="Seleccionar Proyecto"  label="IdProyecto" v-model="IdProyecto" :filterable="false" :reduce="ListaProyectos => ListaProyectos.IdProyecto" :options="ListaProyectos" @search="Get_Proyectos" >
                      <template slot="no-options">
                         No se encontraron resultados
                        </template>
                        <template  slot="option" slot-scope="option">
                            <div class=" d-center list" >
                                <!--<img :src='option.owner.avatar_url'/> -->
                               {{ option.Folio }}  {{ option.Proyecto }}
                            </div>
                        </template>
                        <template slot="selected-option" slot-scope="option">
                            <div class=" selected d-center">
                                <!--<img :src='option.owner.avatar_url'/>-->
                                    {{ Get_Substring(option.Proyecto)}}
                            </div>
                        </template>
                    </v-select>
                </div>
                 <div class="form-group mb-2 mr-2">
                    <v-date-picker
                        mode='range'
                        v-model='rangeDate'
                        @input="Lista"
                        :input-props='{
                            class: "form-control calendar",
                            placeholder: "Fecha",
                            readonly: true
                        }'
                    />
                </div>
                
            </template>
            <template slot="header">
            <tr>
                <th>
                    Proyecto
                </th>
                <th>
                    Cliente
                </th>
                <th>
                    Sucursal
                </th>
                <th>
                    Descripción
                </th>
                <th class="">
                    Fecha
                </th>
                <th>
                    Monto
                </th>
                <th class="text-center tw-2">
                    Acciones
                </th>
            </tr>
                  
            </template>
             <template slot="body">
                   <tr v-for="(lista,key,index) in ListaHoras" :key="index" >
                       <td>{{lista.Proyecto }}</td>
                       <td>{{lista.Cliente }}</td>
                       <td>{{lista.Sucursal }}</td>
                       <td>{{lista.Descripcion }}</td>
                       <td><i class="fas fa-calendar-day"></i> {{lista.FechaReg }}</td>
                       <td><b class="bold color-02">{{lista.Horas }} hr</b></td>
                        <td class="text-center tw-2t">
                            <button @click="Edit(lista.IdSpendHora)" type="button" class="btn-icon mr-2" data-toggle="modal" data-target="#ModalForm" data-backdrop="static" ata-keyboard="false" v-b-tooltip.hover title="Editar"><i class="fas fa-pencil-alt"></i></button>
                            <button @click="Eliminar(lista.IdSpendHora)" type="button" class="btn-icon-02" v-b-tooltip.hover title="Eliminar"><i class="fas fa-trash"></i></button>
                        </td>
                   </tr>
                  
            </template>
            
            
        </Clist>

        <Modal   :size="size" :Nombre="NameForm" >
        <template slot="Form" >
            <Form>
            </Form>
        </template>
        </Modal>  

        

</div>
</template>
<script>

import Clist from '@/components/Clist.vue';
import Modal from '@/components/Cmodal.vue';
import Form from '@/views/modulos/spendplan/horas/Form.vue'

export default {
    name :'list',
    components :{
        Clist,Modal,Form
    },
    data() {
        return {
            FormName:'Form',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no,
            CloseModal:true,//indica si el modal cierra o de lo contrario asignarle un evento al boton
            size :"modal-lg",
            NameList:"Horas",
            NameForm:"Horas",
            urlApi:"spendhora/get",
            ListaHoras:[],
            ListaHeader:[],
            TotalPagina:2,
            Pag:0,
            UrlPdf:'',
            ListaClientes: [{IdCliente:0,Nombre:'Buscar Clientes'}],
            ListaClienteSuc: [{IdClienteS:0,Nombre:'Buscar Sucursales'}],
            ListaProyectos: [{IdProyecto:0,Proyecto:'Buscar Proyectos'}],
            IdCliente:0,
            IdClienteS:0,
            IdProyecto:0,
            Filtro:{
                Nombre:'',
                Placeholder:'Descripción',
                TotalItem:0,
                Pagina:1,
                IdCliente:0,
                IdClienteS:0,
                IdProyecto:0,
                FechaI:'',
                FechaF:'',
            },
            rangeDate:{start:'',end:''},
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
                            'spendhora/' + Id
                        ).then( (res) => {
                                this.Lista();
                        });
                        
                    } 
                });
        },
       async Lista()
        {
            this.Filtro.FechaI=this.rangeDate.start;
            this.Filtro.FechaF=this.rangeDate.end;
            await this.$http.get(
                this.urlApi,
                {
                    params:{Nombre:this.Filtro.Nombre,Entrada:50,pag:0, RegEstatus:'A',IdProyecto:this.Filtro.IdProyecto,IdCliente:this.Filtro.IdCliente,IdClienteS:this.Filtro.IdClienteS,FechaI:this.Filtro.FechaI,FechaF:this.Filtro.FechaF}
                }
            ).then( (res) => {
                this.ListaHoras =res.data.spend_horas;
            });
              
        },Edit(Id){
            this.bus.$emit('Nuevo',false,Id);
        },OpenFile(NameFile){
            window.open(this.UrlPdf+NameFile, '_blank');
        },
        Get_Clientes(search, loading) {
            
             loading(true);   
            this.$http.get(
                'clientes/get',
                {
                    params:{Nombre:search,Entrada:15,pag:0, RegEstatus:'A'}
                }
            ).then( (res) => {
                loading(false);
                this.ListaClientes=res.data.data.clientes;
                
            });
        },async Get_ClienteSuc(search, loading,type=1)
        {   
            
            if(type==1){
                loading(true); 
            }
            
            await this.$http.get(
                'clientesucursal/get',
                {
                    params:{Nombre:search,Entrada:15,IdCliente:this.Filtro.IdCliente, RegEstatus:'A'}
                }
            ).then( (res) => {
                if(type==1){
                    loading(false);
                }
                this.ListaClienteSuc =res.data.data.clientesucursal;
            });
              
        },async Get_Proyectos(search, loading,type=1)
        {   
            
            if(type==1){
                loading(true); 
            }
            
            await this.$http.get(
                'spendpoject/get',
                {
                    params:{Nombre:search,Entrada:15,IdCliente:this.Filtro.IdCliente,IdClienteS:this.Filtro.IdClienteS, RegEstatus:'A'}
                }
            ).then( (res) => {
                if(type==1){
                    loading(false);
                }
                this.ListaProyectos =res.data.proyecto;
            });
        },Get_Substring(value){
            if( value!=undefined){
               value= value.substring(0,18) 
            }
            return value;
        }
    },
    created()
    {
     
        this.bus.$off('Delete');
        this.bus.$off('List');
         this.bus.$off('Regresar');
         this.bus.$off('Nuevo');
        this.Lista();

         this.bus.$on('Nuevo',()=> 
        {
            
        });

        this.bus.$on('Delete',(Id)=> 
        {
            this.Eliminar(Id);    
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
        this.IdClienteS=0;
        this.Filtro.IdClienteS=0;
        this.ListaClienteSuc=[{IdClienteS:0,Nombre:'Buscar Sucursales'}];

        if(NewValue<=0 ){
             this.ListaClientes=[{IdCliente:0,Nombre:'Buscar Clientes'}];
             this.IdCliente=0;
        }else{
            this.Filtro.IdCliente=NewValue;
            //this.Get_ClienteSuc("",null,2);
            //this.Get_Proyectos("",null,2);
        }
        this.Lista();
    },
    IdClienteS: function (NewValue, OldValue) {
        this.Filtro.IdClienteS=0;
        this.IdProyecto=0;
        this.Filtro.IdProyecto=0;
        this.ListaProyectos=[{IdProyecto:0,Proyecto:'Buscar Proyectos'}];
        
        if(NewValue<=0 ){
            this.ListaClienteSuc=[{IdClienteS:0,Nombre:'Buscar Sucursales'}];
            this.IdClienteS=0;
        }else{
            this.Filtro.IdClienteS=NewValue;    
            //this.Get_Proyectos("",null,2);
        }
       this.Lista();
    }, IdProyecto: function (NewValue, OldValue) {
        this.Filtro.IdProyecto=0;
        if(NewValue<=0 ){
            this.IdProyecto=0;
            this.ListaProyectos=[{IdProyecto:0,Proyecto:'Buscar Proyectos'}];
        }else{
            this.Filtro.IdProyecto=NewValue;    
        }
       this.Lista();
    }
    
  },
}
</script>