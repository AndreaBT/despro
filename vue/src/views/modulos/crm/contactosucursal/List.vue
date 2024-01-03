<template>
    <div>  
        <Clist :pShowBtnAdd="ShowBtns" @FiltrarC="Lista" :Filtro="Filtro" :regresar="true" :Nombre="NameList" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal">
            <template slot="header">
                <tr >
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Ciudad</th>
                    <th>Tipo</th>
                    <th>Acciones</th>
                </tr> 
            </template>
             <template slot="body">
                   <tr v-for="(lista,key,index) in ListaClientes" :key="index" >
                            <td>{{lista.Nombre }}</td>
                            <td>{{lista.ContactoS }}</td>
                            <td>{{lista.Telefono}}</td>
                            <td>{{lista.Correo}}</td>
                            <td>{{lista.Ciudad}}</td>
                            <td>{{lista.Tipo}}</td>
                        <td v-if="ShowBtns">
                           
                            <Cbtnaccion :ShowButtonG="ShowBtns" :isModal="EsModal" :Id="lista.IdClienteS" :IrA="FormName" >
                                <template slot="btnaccion">
                                   
                              </template>
                          </Cbtnaccion>   
                        </td>   
                        <td v-else>
                            <button v-b-tooltip.hover.lefttop v-if="TipoList=='Scanning'" @click="go_to_equipo_sucursal(lista)" title="Agregar equipos"  type="button" class="btn-icon mr-2"> <i class="fa fa-plus-square" aria-hidden="true"></i></button>
                            
                        </td>
                   </tr>
                  
            </template>
          
        </Clist>
        <Modal  :size="size" :Nombre="'Contacto'" :poBtnSave="oBtnSave">
        <template slot="Form">
              <Form :NameList="NameForm" :ocliente="ocliente" @titulomodal="Change" :poBtnSave="oBtnSave" >
              </Form>
        </template>
        </Modal>  
</div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';

import Form from '../contactosucursal/Form.vue'

export default {
    name :'list',
    props:['ocliente','tipolistp'],
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        Form
        
    },
    data() {
        return {
            FormName:'clientesForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"modal-lg",
            NameList:"Sucursales del contacto : ",
            urlApi:"crmsucursal/list",
            ListaClientes:[],
            ListaHeader:[],
            TotalPagina:2,
            Pag:0,
            oClienteP:{},
            NameForm:"Sucursal del contacto : ",
            TipoList:'',
            ShowBtns:true,
            Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                TotalItem:0,
                Pagina:1,
                Entrada: 10
            },  
            oBtnSave:{//valores  isModal(true),nombreModal('ModalForm'),tipoModal=1,regresarA(''),disableBtn(false),txtSave('Guardar'),txtCancel('Cerrar');
                isModal:true,
                disableBtn:false,
                toast:0,
            },
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
                        'clientesucursal/' + Id
                    ).then( (res) => {
                            this.Lista();
                    });
                } 
            });
        },
        async Lista()
        {
            await this.$http.get(
                this.urlApi,
                {
                    params:{Nombre:this.Filtro.Nombre,IdSucursa:this.oClienteP.IdSucursal,IdCliente:this.oClienteP.IdCliente,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina, RegEstatus:'A'}
                }
            ).then( (res) => {
     
                this.ListaClientes =res.data.data.clientesucursal;
                this.ListaHeader=res.data.headers;
                this.Filtro.Entrada=res.data.data.pagination.PageSize;
                this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
            });
        },
        Regresar(){
            this.$router.push({name:'crmcontactos', params: { tipolistp:this.TipoList}});
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
        },
        go_to_equipo_sucursal(objsucursal){
            this.$router.push({name:'equipos', params:{obj:objsucursal,objCliente:this.oClienteP}})
        }
    },
    created()
    {
        if (this.tipolistp!=undefined)
        {
            sessionStorage.setItem('TipoList',JSON.stringify(this.tipolistp));
        }

        this.TipoList=JSON.parse( sessionStorage.getItem('TipoList'));
        
        if(this.TipoList=='Scanning'){
            this.ShowBtns=false;
        }

        //recibiendo objetos
        if (this.ocliente!=undefined)
        {
            sessionStorage.setItem('IdSaved',JSON.stringify(this.ocliente));
        }

        this.oClienteP=JSON.parse( sessionStorage.getItem('IdSaved'));
        this.NameList=this.NameList+" "+this.oClienteP.Nombre;
        this.NameForm="Equipos de la sucursal: "+this.oClienteP.Nombre;
        
        this.bus.$off('Delete');
        this.bus.$off('List');
        this.bus.$off('Regresar');
        this.Lista();
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
            this.Regresar();
            
        });

    }
}
</script>