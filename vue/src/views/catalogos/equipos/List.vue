<template>
    <div>          
        <Clist :regresar="true"  @FiltrarC="Lista" :Filtro="Filtro"  :Nombre="NameList"  :isModal="EsModal">
            <template slot="header">
                <tr >
                    <th>Nombre</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Serie</th>
                    <th>Unidad</th>
                    <th>Año</th>
                    <th>Ubicación</th>
                    <th>Acciones</th>
                </tr> 
            </template>
            <template slot="body">
                <tr v-for="(lista,key,index) in ListaEquipos" :key="index" >
                    <td>{{lista.Nequipo }}</td>
                    <td>{{lista.Marca }}</td>
                    <td>{{lista.Modelo }}</td>
                    <td>{{lista.Serie }}</td>
                    <td>{{lista.Unidad }}</td>
                    <td>{{lista.Ano }}</td>
                    <td>{{lista.Ubicacion }}</td>
                    <td>
                        <Cbtnaccion :isModal="EsModal" :Id="lista.IdEquipo" :IrA="FormName" >
                            <template slot="btnaccion">
                                <button v-b-tooltip.hover.lefttop  @click="dwl_qr(lista.IdEquipo)" title="Descargar QR"  type="button" class="btn-icon mr-2"> <i class="fa fa-qrcode"></i></button>
                                <button v-b-tooltip.hover.lefttop  @click="copy_equipo(lista);" title="Copiar" type="button" class="btn-icon mr-2"> <i class="fa fa-copy"></i> </button>
                                <button v-b-tooltip.hover.lefttop  @click="go_to_pdfquipo(lista)" title="Subir PDF"  type="button" class="btn-icon mr-2"> <i class="fa fa-file-pdf"></i> </button>
                            </template>
                        </Cbtnaccion>     
                    </td>   
                </tr>
            </template>
          
        </Clist>
        
        <Modal :size="size" :Nombre="FormName" :poBtnSave="oBtnSave" >
            <template slot="Form">
                <Form :oClienteSucursalP="oClienteSucursal" :poBtnSave="oBtnSave"></Form>
            </template>
        </Modal>
    </div>
</template>
<script>

import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Cbtnaccion from '@/components/Cbtnaccion.vue';
import Form from '@/views/catalogos/equipos/Form.vue'

export default {
    name :'list',
    props:['obj','objCliente'],
    components :{
        Modal,
        Clist,
        Cbtnaccion,
        Form
    },
    data() {
        return {
            FormName:'Equipos',//Por si no es modal y queremos ir a una vista declarada en el router
            EsModal:true,//indica si es modal o no
            size :"modal-lg",
            NameList:"Equipos",
            urlApi:"equipos/get",
            ListaEquipos:[],
            ListaHeader:[],
            oClienteSucursal:{},
            NameForm:"Equipo de la Sucursal : ",
            oCliente:{},
            Equipo:{
              IdEquipo:0,
              IdCliente:"",
              Ubicacion:"",
              Marca:"",
              Modelo:"",
              Serie:"",
              TipoUnidad:0,
              Ano:"",
              IdClienteS:"",
              Nequipo:"",
            },
            BndCopiar:true,
            Filtro:{
                Nombre:'',
                Placeholder:'Nombre..',
                TotalItem:0,
                Pagina:1,
                Entrada: 10,
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
                     this.$http.delete(
                        'equipos/' + Id
                    ).then( (res) => {
                        this.$toast.success('Información eliminada');
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
                    params:{Nombre:this.Filtro.Nombre,Entrada:this.Filtro.Entrada,pag:this.Filtro.Pagina, RegEstatus:'A',IdClienteS:this.oClienteSucursal.IdClienteS}
                }
            ).then( (res) => {
                this.ListaEquipos =res.data.data.equipos;
                this.Filtro.Entrada=res.data.data.pagination.PageSize;
                this.Filtro.TotalItem=res.data.data.pagination.TotalItems;
            });   
        }, 
        Regresar(){
            this.$router.push({name:'clientesucursal','ocliente':this.oCliente})
            localStorage.removeItem('ssobjcliente');
        },
        dwl_qr(Id){
            const link = document.createElement('a')
            link.href = this.$http.defaults.baseURL+'equipos/getqr?IdEquipo='+Id;
            link.setAttribute('download', 'imgqr.png') //or any other extension
            link.style.display = 'none';
            link.click();
        },
        async copy_equipo(obj){
            if(this.BndCopiar){//si es false es por que sigue ejecutando la accion de copiar
                this.$swal({
                    title: 'Desea copiar esta informacion',
                    text: 'La información se duplicará.',
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'No, mantener',
                    showCloseButton: true,
                    showLoaderOnConfirm: true
                }).then((result) => {
                if(result.value) {
                        this.BndCopiar=false;
                        this.Equipo=obj;
                        this.Equipo.IdEquipo=0;
                        this.Equipo.Nequipo=obj.Nequipo+" - Copy";

                         this.$http.post(
                            'equipos/post',
                            this.Equipo,
                        ).then( (res) => {
                            this.$toast.success('Información copiada');
                            this.BndCopiar=true;
                            this.Lista();
                        }).catch( err => {
                            this.BndCopiar=true;
                        });
                    } 
                });             
            }
        },
        go_to_pdfquipo(objequipo){
            this.$router.push({name:'pdfequipo', params:{oEquipoP:objequipo}})
        },
        Descargar() {
                this.$http.get(
                'https://78.media.tumblr.com/tumblr_m39nv7PcCU1r326q7o1_500.png',
                {
                responseType: 'arraybuffer',
                params :{ }
                }
            ).then( (response) => {
                const url = window.URL.createObjectURL(new Blob([response.data]))
                const link = document.createElement('a')
                link.href = url
                link.setAttribute('download', 'file.png') //or any other extension
                document.body.appendChild(link)
                link.click()       
            });
        },
        downloadWithAxios(){
            this.$http.get({
                url: 'https://78.media.tumblr.com/tumblr_m39nv7PcCU1r326q7o1_500.png',
                responseType: 'arraybuffer'
            })
            .then(response => {
                this.forceFileDownload(response)
            })
            .catch(() => console.log('error occured'))
        }
    },
    created()
    {
        if (this.obj!=undefined)
        {
            sessionStorage.setItem('IdSaved',JSON.stringify(this.obj));
            sessionStorage.setItem('ssobjcliente',JSON.stringify(this.objCliente));
        }
        this.oClienteSucursal=JSON.parse(sessionStorage.getItem('IdSaved'));
        this.oCliente=JSON.parse( sessionStorage.getItem('ssobjcliente'));
        this.NameList="Equipos de la Sucursal : "+this.oClienteSucursal.Nombre;

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