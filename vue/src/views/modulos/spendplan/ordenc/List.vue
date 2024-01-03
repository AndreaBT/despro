<template>
    <div>  
        <Clist @FiltrarC="Lista" :Filtro="Filtro" :regresar="true" :Nombre="NameList"  :isModal="EsModal">
            <template slot="Filtros">
                <div class="col">
                    <a-select
                        class="aselect"
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
           

                <div class="col">
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

              
                <div class="col">
                    <a-select
                        show-search
                        :allowClear="true"
                        size="large"
                        :show-arrow="true"
                        :value="IdProyecto"
                        placeholder="Buscar por Proyecto"
                        :filter-option="false"
                        :not-found-content="fetchingPro ? undefined : null"
                        @search="Get_Proyectos"
                        @change="handleChangePro"
                    >
                        <a-spin v-if="fetchingPro" slot="notFoundContent" size="small" />
                          <a-select-option  v-for="d in ListaProyectos" :key="d.IdProyecto">
                              {{ d.Folio }}  {{ d.Proyecto }}
                        </a-select-option>
                    </a-select>
                </div>
           
                <div class="mr-2">
                    <select @change="Lista" v-model="Filtro.Concepto" class="form-control ">
                        <option value="">--Concepto--</option>
                        <option v-for="(item, index) in ListaConceptos" :key="index" :value="item.Concepto">{{item.Concepto}}</option>
                    </select>
                </div>
                <div class="col mr-2">
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
                <div class="col">
                    <button type="button" @click="ClearDate" class="btn-icon"><i class="fas fa-calendar-times"></i></button>
                </div>
                
            </template>
            <template slot="header">
            <tr>
                <th>
                    Proyecto
                </th>
                <th>
                    Realizó
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
                <th>
                    Concepto
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
                   <tr v-for="(lista,key,index) in Listaordencompra" :key="index" >
                       <td>{{lista.Proyecto }}</td>
                       <td>{{lista.Usuario }}</td>
                       <td>{{lista.Cliente }}</td>
                       <td>{{lista.Sucursal }}</td>
                       <td>{{lista.Descripcion }}</td>
                       <td>{{lista.Concepto }}</td>
                       <td><i class="fas fa-calendar-day"></i> {{lista.FechaReg }}</td>
                       <td><b class="bold color-02">$ {{lista.Monto }}</b></td>
                    
                        <td class="text-center tw-2t">
                            <span v-if="lista.Archivo!=''">
                                <button @click="OpenFile(lista.Archivo)" type="button" class="btn-icon-03 mr-2" v-b-tooltip.hover :title="'Ver Evidencia: #'+lista.FolioArchivo"><i class="far fa-file-pdf"></i></button>
                            </span>
                            
                            <button @click="Edit(lista.IdOrdenCompra)" type="button" class="btn-icon mr-2" data-toggle="modal" data-target="#ModalForm" data-backdrop="static" ata-keyboard="false" v-b-tooltip.hover title="Editar"><i class="fas fa-pencil-alt"></i></button>
                            <span v-if="lista.IdServicio<=0">
                                <button @click="Eliminar(lista.IdOrdenCompra)" type="button" class="btn-icon-02" v-b-tooltip.hover title="Eliminar"><i class="fas fa-trash"></i></button>
                            </span>
                            
                        </td>
                   </tr>
                  
            </template>
            
            
        </Clist>

        <Modal   :size="size" :Nombre="NameForm" :poBtnSave="oBtnSave">
        <template slot="Form" >
            <Form :poBtnSave="oBtnSave">
            </Form>
        </template>
        </Modal>  

        

</div>
</template>
<script>

import Clist from '@/components/Clist.vue';
import Modal from '@/components/Cmodal.vue';
import Form from '@/views/modulos/spendplan/ordenc/Form.vue'

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
            NameList:"Orden de Compra",
            NameForm:"Orden de Compra",
            urlApi:"spendoc/get",
            Listaordencompra:[],
            ListaHeader:[],
            TotalPagina:2,
            Pag:0,
            UrlPdf:'',
            ListaClientes: [{IdCliente:0,Nombre:'Buscar Clientes'}],
            ListaClienteSuc: [{IdClienteS:0,Nombre:'Buscar Sucursales'}],
            ListaProyectos: [{IdProyecto:0,Proyecto:'Buscar Proyectos'}],
            IdCliente:'Clientes',
            IdClienteS:'Sucursales',
            IdProyecto:'Proyectos',
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
                Concepto:''
            },
            rangeDate:{start:'',end:''},
            ListaConceptos:[],
              oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                disableBtn:false,
                toast:0,
            },
            fetching:false,
            fetchingSuc:false,
            fetchingPro:false,
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
                            'spendoc/' + Id
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
                    params:{Nombre:this.Filtro.Nombre,Entrada:50,pag:0, RegEstatus:'A',IdProyecto:this.Filtro.IdProyecto,IdCliente:this.Filtro.IdCliente,IdClienteS:this.Filtro.IdClienteS,FechaI:this.Filtro.FechaI,FechaF:this.Filtro.FechaF,Concepto:this.Filtro.Concepto}
                }
            ).then( (res) => {
                this.Listaordencompra =res.data.ordencompra;
                this.UrlPdf=res.data.UrlPdf;
            });
              
        },Edit(Id){
            this.bus.$emit('Nuevo',false,Id);
        },OpenFile(NameFile){
            window.open(this.UrlPdf+NameFile, '_blank');
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
              
        },async Get_Proyectos(search)
        {   
            this.fetchingPro=true;
            
            await this.$http.get(
                'spendpoject/get',
                {
                    params:{Nombre:search,Entrada:15,IdCliente:this.Filtro.IdCliente,IdClienteS:this.Filtro.IdClienteS, RegEstatus:'A'}
                }
            ).then( (res) => {
               this.fetchingPro=false;
                this.ListaProyectos =res.data.proyecto;
            });
        },Get_Substring(value){
            if( value!=undefined){
               value= value.substring(0,18) 
            }
            return value;
        },ClearDate(){
            this.rangeDate={start:'',end:''};
            this.Lista();
        },async get_conceptos(){
            await this.$http.get(
                'spendpoject/conceptos',
                {
                    params:{IdProyecto:0,Tipo:'2'}
                }
            ).then( (res) => {
                this.ListaConceptos =res.data.conceptos;
            });
        },handleChange(selectedItems){
            this.IdCliente = selectedItems;
        },
        handleChangeSuc(selectedItems){
            this.IdClienteS = selectedItems;
        },
        handleChangePro(selectedItems){
            this.IdProyecto = selectedItems;
        },
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
    },mounted() {
        this.get_conceptos();
    },
    watch: {
    // whenever question changes, this function will run
    IdCliente: function (NewValue, OldValue) {
        this.Filtro.IdCliente=0;
        this.IdClienteS='Sucursales';
        this.Filtro.IdClienteS=0;
        this.IdProyecto='Proyectos';
        this.ListaClienteSuc=[];

        if(NewValue<=0 ){
             this.ListaClientes=[];
             this.IdCliente='Clientes';
        }else{
            this.Filtro.IdCliente=NewValue;
            //this.Get_ClienteSuc("",null,2);
            //this.Get_Proyectos("",null,2);
        }
        this.Lista();
    },
    IdClienteS: function (NewValue, OldValue) {
        this.Filtro.IdClienteS=0;
        this.IdProyecto='Proyectos';
        this.Filtro.IdProyecto=0;
        this.ListaProyectos=[];
        
        if(NewValue<=0 ){
            this.ListaClienteSuc=[];
            this.IdClienteS='Sucursales';
        }else{
            this.Filtro.IdClienteS=NewValue;    
            //this.Get_Proyectos("",null,2);
        }
       this.Lista();
    }, IdProyecto: function (NewValue, OldValue) {
        this.Filtro.IdProyecto=0;
        if(NewValue<=0 ){
            this.IdProyecto='Proyectos';
            this.ListaProyectos=[];
        }else{
            this.Filtro.IdProyecto=NewValue;    
        }
       this.Lista();
    }
    
  },
}
</script>

<style scoped>
.ant-select-selection--single{
    height: 39px;
    padding-right: 10px;
}

.col{
    padding-right: 1px;
    padding-left: 1px;
    
    max-width: 220px;
}

</style>