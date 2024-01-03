<template>
    <div>  
        <Clist :regresar="true" :Nombre="NameList" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal">
            <template slot="header">
                <tr >
                    <th>Hora I</th>
                    <th>Hora F</th>
                    <th>Intervalo</th>
                    <th>Acciones</th>
                </tr> 
            </template>
             <template slot="body">
                <tr v-for="(lista,key,index) in  ListaHorasLaborales" :key="index" >
                    <td>{{lista.Hora_I }}</td>
                    <td>{{lista.Hora_F}}</td>
                    <td>{{lista.Intervalo}}</td>
                    
                    <td>
                        <Cbtnaccion :isModal="EsModal" :Id="lista.IdHorasL" :IrA="FormName" >
                            <template slot="btnaccion">
                            </template>
                        </Cbtnaccion>     
                    </td>   
                </tr>
            </template>
        </Clist>
        
        <Modal @ejecutar="GuardarDesdeList"  :size="size" :Nombre="NameList" >
            <template slot="Form">
                <Form ></Form>
            </template>
        </Modal>  
    </div>
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';
import Form from '@/views/catalogos/horaslaborales/Form.vue'

export default {
    name :'list',
      props:['IdSucursal'],
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        Form
    },
    data() {
        return {
            FormName:'usuariosForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"modal-xl",
            NameList:"Horas Laborales",
            urlApi:"horaslaborales/get",
            ListaHorasLaborales:[],
            ListaHeader:[],
            TotalPagina:2,
            Pag:0
        }
    },
    methods: {
        GuardarDesdeList()
        {
        },
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
                     this.$http.delete(
                        'horaslaborales/' + Id
                    ).then( (res) => {
                        this.$swal({showConfirmButton: true,timer: 1000,title: 'Información Eliminada'});
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
                    params:{Nombre:'',Entrada:50,pag:0, RegEstatus:'A', IdSucursal:this.IdSucursal}
                }
            ).then( (res) => {
              this.ListaHorasLaborales =res.data.data.horaslaborales;
                //this.TotalPagina=res.data.data.pagination.TotalItems;
                //this.Pag=res.data.data.pagination.CurrentPage;
            });
        }
    },
    created()
    {
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
           this.$router.push({name:'submenuadmon'});
        });
    }
}
</script>