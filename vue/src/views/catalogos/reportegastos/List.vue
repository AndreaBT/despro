<template>
    <div >
    
                       
                  
    <Clist :regresar="true" :Nombre="NameList" :Pag="Pag" :Total="TotalPagina" :isModal="EsModal">
            <template slot="header">
                   <tr >
                    <th>Concepto</th>
                        <th>Fecha Inicio</th>
                   <th>Fecha Fin</th>
                   <th>Monto</th>
                    <th>Total</th>
                
                    </tr> 
            </template>
             <template slot="body">
                   <tr v-for="(lista,key,index) in ListaGastoxtrabajador" :key="index" >
                       <td>{{lista.Concepto }}</td>
                            <td>{{lista.FechaInicio}}</td>
                        <td>{{lista.FechaFin}}</td>
                        <td>$ {{lista.Monto}}</td>
                        <td>$ {{lista.Total}}</td>
              
                   </tr>
                  
            </template>
          
        </Clist>
                     

                        <nav class="later-derecho mt-3" aria-label="Page navigation example">
                            <ul class="pagination justify-content-end">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="fas fa-angle-double-left"></i></a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#"><i class="fas fa-angle-double-right"></i></a>
                                </li>
                            </ul>
                        </nav>
                              

    </div>
    
</template>
<script>
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';
export default {
    name :'list',
    components :{
        Modal,
        Clist,
        Cbtnaccion
    },
    data() {
        return {
            FormName:'trabajadorForm',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"modal-lg",
            NameList:"Reporte de gastos",
            urlApi:"gastoxtrabajador/get",
            ListaGastoxtrabajador:[],
            ListaHeader:[],
            TotalPagina:2,
            Pag:0,
            ShowModal:true
        }
    },
    methods: {
        
       async Lista()
        {
            await this.$http.get(
                this.urlApi,
                {
                    params:{Nombre:'',Entrada:50,pag:0, RegEstatus:'A'}
                }
            ).then( (res) => {
                
                this.ListaGastoxtrabajador =res.data.data.gastoxtrabajador;
                
            });
              
        }
    },
    created()
    {
         this.bus.$off('Delete');
          this.bus.$off('List');
          this.bus.$off('Regresar');
         this.Lista();
 
         this.bus.$on('List',()=> 
        {
            this.Lista();
            
        });
         this.bus.$on('Regresar',()=> 
        {
            this.$router.push({name:'cajachica'});
            
        });
    }
}
</script>