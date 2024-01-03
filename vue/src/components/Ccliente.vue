<template>
  <div>
      
    <Modal :Showbutton="false" :NameModal="'ModalForm3'"  :TipoM="GetTipoModal"  :size="'modal-xl'" :Nombre="''" >
        <template slot="Form">
            <Clist :btnCli="true"  v-if="Mostrar" :NameReturn="NameReturn" :pShowBtnAdd="false" :regresar="Regresar" :Nombre="TituloLista" @FiltrarC="ListaCliente" :Filtro="Filtro" :isModal="true">
                <template slot="header">
                    <tr>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <!--<th>Dirección</th>-->
                        <th>Correo</th>
                        <th>Ciudad</th>
                        <th>Acciones</th>
                    </tr> 
                </template>
                <template slot="body">
                    <tr  v-for="(item , index ) in ListaClientes" :key="index" >
                        <td>{{item.Nombre.substr(0, 20)}}</td>
                        <td>{{item.Telefono}}</td>
                        <!--<td>{{item.Direccion}}</td>-->
                        <td>{{item.Correo}}</td>
                        <td>{{item.Ciudad}}</td>
                        <td>
                        <button @click="go_to_sucursal(item)" type="button" class="btn btn-table" title="Sucursales2"> <i class="fa fa-building" aria-hidden="true"></i></button>
                        </td>   
                    </tr>
                </template>
        
            </Clist>
            <Clist :btnCli="true" v-else :NameReturn="NameReturn" :pShowBtnAdd="false" :regresar="Regresar" :Nombre="TituloLista" @FiltrarC="go_to_sucursal(OClienteSelect)" :Filtro="Filtro" :isModal="true">
                <template slot="header">
                    <tr >
                        <th>Cliente</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Correo</th>
                        <th>Ciudad</th>
                        <th>Acciones</th>
                        </tr> 
                </template>
           
                <template slot="body">
                    <tr v-for="(item , index2 ) in ListaSucursal" :key="index2" >
                        <td>{{item.Nombre}}</td>
                        <td>{{item.Telefono}}</td>
                        <td>{{item.Direccion}}</td>
                        <td>{{item.Correo}}</td>
                        <td>{{item.Ciudad}}</td>
                        <td>
                        <button @click="SeleccionarCliente(item)" type="button" class="btn btn-table" title="Sucursales"><i class="fa fa-check" aria-hidden="true"></i></button>
                        </td>   
                    </tr>
                </template>
        
            </Clist>
        </template>
    </Modal>

     <Modal :Showbutton="false" :NameModal="'ModalNewUser'" :size="'modal-lg'"   :Nombre="'Nuevo Cliente'" :poBtnSave="oBtnSave" >
        <template slot="Form">
        
          <NewCliente v-if="this.IdCliente==0"  :poBtnSave="oBtnSave" ></NewCliente>
           <NewClienteSuc :ocliente="OClienteSelect" v-if="this.IdCliente>0"  :poBtnSave="oBtnSave" @titulomodal="Change"></NewClienteSuc>
         
        </template>
        </Modal> 
  </div>
</template>

<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import NewCliente from '@/views/modulos/servicios/NewCliente.vue'
import NewClienteSuc from '@/views/modulos/servicios/NewCliSuc.vue'

export default {
    name:'Form',
    props:['NameList','TipoModal'],
    data() {
        return {
            size :"modal-xl",
            ListaClientes:[],
            ListaSucursal:[],
            Mostrar:true,
            Regresar:false,
            TituloLista:'Lista de Clientes',
            OClienteSelect:{},
            NameReturn:'RegresarCliente',
            isEmit:true,
            tipoModal:2,
               Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                 TotalItem:0,
                Pagina:1
            },
            Nuevo:false,
            IdCliente:0,
              oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
            },
            NameForm:"Sucursal del Cliente : ",
            oClienteP:{},
        }
    },
    components:{
        Clist,Modal,NewCliente,NewClienteSuc
    },
    methods :
    {
        async ListaCliente()
        {
             this.IdCliente=0;
            await this.$http.get(
                'clientes/get',
                {
                    params:{Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina, RegEstatus:'A'}
                }
            ).then( (res) => {
                this.TituloLista='Lista de Clientes';
                this.Mostrar=true;
               this.Regresar=false;
                this.ListaClientes =res.data.data.clientes;
                  this.Filtro.Entrada=res.data.data.pagination.PageSize;
                this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
                
            });
              
        },
           async go_to_sucursal(ocliente)
        {
            if (this.Nuevo==false)
            {
                this.Filtro.Nombre='';
            }
            this.OClienteSelect=ocliente;
            await this.$http.get(
                'clientesucursal/get',
                {
                    params:{IdCliente:ocliente.IdCliente,Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina, RegEstatus:'A'}
                }
            ).then( (res) => {

                this.IdCliente=ocliente.IdCliente;
                this.Nuevo=true;
                this.TituloLista='Lista de Sucursales';
                this.ListaSucursal =res.data.data.clientesucursal;
                //this.ListaHeader=res.data.headers;
               this.Mostrar=false;
               this.Regresar=true;
                              this.Filtro.Entrada=res.data.data.pagination.PageSize;
                this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
            });
              
        },SeleccionarCliente(osucursal){
            this.bus.$emit('SeleccionarCliente',osucursal,this.OClienteSelect);
            $('#ModalForm3').modal('hide');
            if (this.TipoModal==1)
            {
                $('#'+this.NombreModal).modal('hide'); 
            }
            else{
                 document.body.classList.add("modal-open");
            }
              
             
        },
          Change(titulo)
        {
            var bdn=true;
            if(titulo=='Selecciona la imagen'){
                bdn=false;
            }else{
                titulo=titulo+' : '+this.oClienteP.Nombre;
            }
            this.NameForm=titulo;
           
            this.bus.$emit('cambiar_CloseModal',bdn);
        }
    },
    created() {
        this.IdCliente=0;
        this.bus.$off('ListCcliente');
        this.bus.$off('RegresarCliente');
        
        this.bus.$off('ListCliSuc');

        this.bus.$on('RegresarCliente',()=>
        {
            this.ListaCliente();
        });
    
        this.bus.$on('ListCcliente',()=>
        {
           this.ListaCliente();
        });

        
        this.bus.$on('ListCliSuc',(objeto)=>
        {
           this.go_to_sucursal(objeto);
        });
        
    },computed: {
        GetTipoModal(){
            
            if(this.TipoModal!=undefined){
                   this.tipoModal= this.TipoModal;
            }

            return this.tipoModal;
        }   
    }
}

</script>

<style>

</style>