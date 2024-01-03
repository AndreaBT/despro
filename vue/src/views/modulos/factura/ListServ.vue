<template>
    <div>  
       
        <Clist :ShowHead="false" :pShowBtnAdd="false" :regresar="true" :Nombre="NameList" @FiltrarC="Lista" :Filtro="Filtro" :isModal="EsModal">

            <template slot="header">
                   <tr >
                        <th>Folio</th>
                        <th>Fecha</th>
                    
                        <th>Sucursal</th>
                        <th>Tipo de Servicio</th>                    
                        <th>Acciones</th>
                    </tr> 
            </template>
             <template slot="body">
                   <tr v-for="(lista,index) in ListaServicio" :key="index" >
                        <td>{{lista.Folio }}</td>
                        <td>{{lista.Fecha_I}}</td>
                        <td>{{lista.Sucursal }}</td>
                        <td>{{lista.TipoServ }}</td>
                        <td>
                           
                            <button type="button" @click="Seleccionar(index)" class="btn btn-sm btn-01 mr-2">Seleccionar</button>    
                        </td>   
                   </tr>
                  
            </template>
          
        </Clist>
      
</div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';

import Form from '@/views/modulos/servicios/Form.vue'

export default {
    name :'list',
    props:['servicios','objTemp'],
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        Form
        
    },
    data() {
        return {
            FormName:'TipoUnidadForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"modal-xl",
            NameList:"Servicios",
            urlApi:"factura/servxfact",
            ListaServicio:[],
              Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                TotalItem:0,
                Pagina:1
            },
        }
    },
    methods: {
      
       async Lista()
        {
            await this.$http.get(
                this.urlApi,
                {
                    params:{IdCliente:this.servicios.IdCliente,Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina, RegEstatus:'A'}
                }
            ).then( (res) => {
              this.ListaServicio =res.data.data.servxfact;
               this.Filtro.Entrada=res.data.data.pagination.PageSize;
                this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
                
            });
              
        },
        Seleccionar(index)
        {
            //buscar si el servicio ya existe agregado
            if (this.objTemp.ListaServ.length>0)
            {
                let encontrado =this.objTemp.ListaServ.find(element => element.IdServicio==this.ListaServicio[index].IdServicio)
     
                if (encontrado==undefined)
                {
                    this.ListaServicio[index]
                this.objTemp.ListaServ.push({IdFactura:0,IdServicio:this.ListaServicio[index].IdServicio,Folio:this.ListaServicio[index].Folio,Descripcion:this.ListaServicio[index].ComentarioFin}); 
                }
                else{
                    alert('Ya se ha agregado el registro');
                }
            }
            else{
                this.objTemp.ListaServ.push({IdFactura:0,IdServicio:this.ListaServicio[index].IdServicio,Folio:this.ListaServicio[index].Folio,Descripcion:this.ListaServicio[index].ComentarioFin});  
            }
        }
    },
    created()
    {
        this.Lista();        
    }
}
</script>