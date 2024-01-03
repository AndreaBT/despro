<template>
    <div 
        @keypress="$emit('cerrar', false)"
        class="dropdown-menu dropdown-notificacion dropdown-menu-right preview-list show" 
        aria-labelledby="Notificaciones"  
        ref="mainComponent"
    >
        <h6 class="dropdown-header" >Nuevos mensajes:</h6>
        <div class="dropdown-divider" ></div>
        <template
            v-for="(item, index) of listaAlertas"
        >   
            <a @click="ir_a_despacho(item)" :key="index" class="dropdown-item preview-item">
                <span class="">
                    <strong>
                        {{item.Contacto}}
                    </strong>
                </span>
                <span class="small text-muted">{{item.Fecha}}</span>
                <i class="fa fa-fw fa-bell fa-lg"></i>

                <div class="dropdown-message small">{{item.Mensaje.substr(0, 30)}}</div>
            </a>

        </template>
        <div v-if="loading" class="row" >
            <div class="col-12">
                <LoadingButton></LoadingButton>
            </div>
        </div>
        <div class="dropdown-divider" ></div>
        <a v-if="showVermas" @click="agregarAlertas()" class="dropdown-item small text-center"  href="#">
            Ver mas
        </a>
    </div>
</template>

<script>

export default {

    data() {
        return {

            listaAlertas: [],

            currentPage: 1,
            totalRows: 0,
           
            loading: false,

        }
    },

    computed: {

        showVermas: function() {

            let countAlertas = this.listaAlertas.length;

            if(countAlertas < this.totalRows) return true;

            return false;

        }

    },

    methods: {

        ir_a_despacho(obj){

            if(this.$route.name == "despacho"){

                
                this.bus.$emit("OpenChatD",obj);

            }else{

                this.bus.$emit("verAlertas");
                this.$router.push({name:'despacho', params:{ocliente:obj}});

            }
            
        },

        verificarTipoEvento(item) {

            if(item.IdCliente != 0)
                return 'Cliente';

            if(item.esGuardia == 1)
                return 'Guardia';

            if(item.EsEvento == 1)
                return 'Evento';

        },

        agregarAlertas() {

            this.currentPage++;

            this.listarAlertas(false);

        },

        listarAlertas(click) {

            this.loading = true;

            this.$http.get(
                'despacho/notificationchat', {
                    params: {
                        pag: this.currentPage
                    }
                }
            ).then( res => {

                if(!click) {

                    let listaAlertas = res.data.Lista;
    
                    this.listaAlertas = this.listaAlertas.concat(listaAlertas);

                } else {

                    this.listaAlertas = res.data.Lista;

                }

                this.totalRows = res.data.pagination.TotalItems;

                this.loading = false;

            }).catch( err => {

                this.loading = false;

            });

        },

    },

    // beforeDestroy() {

    //     this.bus.$off('verAlertas');

    // },

    mounted() {

        this.$refs.mainComponent.focus();

        this.listarAlertas(true);        
    }

}
</script>

<style>

</style>