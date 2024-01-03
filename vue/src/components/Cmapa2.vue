<template>
    <div>
    <div class="row mt-2">
    
      <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <nav class="navbar navbar-breadcrumb navbar-expand-md bg-breadcrumb breadcrumb-borde">
          <div class="mr-auto">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb clearfix pt-3">
                <li class="breadcrumb-item"><a href="#">Ubicación</a></li>
              </ol>
            </nav>
          </div>
          <form class="form-inline d-none d-md-none d-lg-block">
            <div class="form-group mt-n2">
              <button :disabled="disabled" type="button" @click="Recargar" class="btn btn-info"> 
                <i v-show="disabled" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i> <i class="fa fa-undo"></i> Recargar </button>  
            </div>
          </form>
      
        </nav>
      </div>
    </div>
    <GmapMap
    ref="gmap" id="gmap"
        :center="center"
        :zoom="zoom"
        map-type-id="satellite"
        style="width: 780px; height: 500px"
        >
         <gmap-info-window :options="infoOptions" :position="infoWindowPos" :opened="infoWinOpen" @closeclick="infoWinOpen=false">
            
      <div class="info_content" v-if="datos.Tipo == 'Vendedor'">
        <h4 class="mb-1">
          <b-avatar :src="ruta +datos.Foto2" size="2rem"></b-avatar> 
          {{datos.Tecnico}} 
          {{datos.Tipo}}
        </h4>
        <hr>
        <p><span class="bold">Actividad:</span> {{datos.Actividad}}</p>
        <p><span class="bold">Cliente:</span> {{datos.Cliente}}</p>
        <p><span class="bold">Calle:</span> {{datos.Direccion}}</p>
        <p><span class="bold">Hora Inicio:</span> {{datos.HoraI}} </p>
        <p><span class="bold">Hora Fin:</span> {{datos.HoraF}} </p>
      </div>

      <div class="info_content" v-else>
        <h4 class="mb-1">
          <b-avatar :src="ruta +datos.Foto2" size="2rem"></b-avatar> 
          {{datos.Tecnico}} 
        </h4>
        <hr>
        <p><span class="bold">Telefono:</span> {{datos.Actividad}}</p>
        <p><span class="bold">Calle:</span> {{datos.Direccion}}</p>
      </div>

    
          </gmap-info-window>
        <GmapMarker
        ref="marker"
            v-for="(item, index) in arreglodinamico" :key="index"
            :position="item.position"
            :clickable="true"
            :draggable="false"
            :icon="validateicon(item)"
           
            @click="toggleInfoWindow(item, index);center=item.position"
                  
        />
    </GmapMap> 

    <!--

       :icon=" item.datos.Tipo == 'Vendedor' ? { url:  require('@/style/images/Cliente.png')} : { url:  require('@/style/images/Ocupado.png')}"

      -->

    </div>
</template>

<script>

export default {
    name:'Cmapa2',
    props:['Arreglo','rutatrab'],
    data() {
        return {
                infoContent :'',
                center:{lat:19.43441642213732, lng:-99.13307696649245},
                zoom:2,
                infoWindowPos: null,
                 datos:{
                  Tecnico:'',
                  Cliente:'',
                  Actividad:'',
                  Direccion:'',
                  FechaI:'',
                  FechaF:'',
                  HoraI:'',
                  HoraF:'',
                  Estatus:'',
                  Foto2:''
                  },
                infoWinOpen: false,
                currentMidx: null,
                //optional: offset infowindow so it visually sits nicely on top of our marker
                infoOptions: {
                    pixelOffset: {
                    width: 0,
                    height: -35
                    }
                },
        
            markers: [],
            ruta:'',
            disabled:false,
            
            
           
        }
    },
    methods: {
    
    validateicon(item){
      
      console.log(item.datos);
      let Val = '';
      if(item.datos.Tipo == 'Vendedor'){
        
        if(item.datos.Estatus == 'Ocupado'){

          Val = require('@/style/images/Ventas01.png');
            
        }else if(item.datos.Estatus == 'Disponible'){

          Val = require('@/style/images/Ventas02.png');
            
        }
        else{

          Val = require('@/style/images/Ventas02.png');

        }

      }else{

          Val = require('@/style/images/Cliente.png');
      }
      
      return Val;
    },

     toggleInfoWindow: function(marker, idx) {
            this.infoWindowPos = marker.position;
            this.infoContent = marker.datos.Tecnico;
              this.datos.Tecnico=marker.datos.Tecnico;
              this.datos.Cliente=marker.datos.Cliente;
               this.datos.Actividad=marker.datos.Actividad;
              this.datos.Direccion=marker.datos.Direccion;
              this.datos.FechaI=marker.datos.FechaI;
              this.datos.FechaF=marker.datos.FechaF;
              this.datos.HoraI=marker.datos.HoraI;
              this.datos.HoraF=marker.datos.HoraF;
              this.datos.Estatus=marker.datos.Estatus;
              this.datos.Foto2=marker.datos.Foto2;
              this.datos.Tipo=marker.datos.Tipo;

            if (this.zoom>17)
            {
              this.zoom=17;
            }
            else if (this.zoom==17)
            {
              this.zoom=16;
            }
            else{
              this.zoom=18;
            }
            //check if its the same marker that was selected if yes toggle
            if (this.currentMidx == idx) {
              this.infoWinOpen = !this.infoWinOpen;
            }
            //if different marker set infowindow to open and reset current marker index
            else {
              this.infoWinOpen = true;
              this.currentMidx = idx;

            }
          
            
              
          },     
            Boud()
            {
              
              /*
              if ( this.markers.length>0)
              {
              var cont=0;
              const bounds = new google.maps.LatLngBounds()     
              for (let m of this.markers) {   
                bounds.extend(m.position);
                this.$refs.marker[cont].$markerObject.setAnimation(google.maps.Animation.BOUNCE);
                cont ++;
              }
              this.$refs.gmap.$mapObject.fitBounds(bounds);
              }
              */


              this.disabled=false;

            },
            Recargar()
            {
              this.disabled=true;
              this.bus.$emit('ListarUbicacion');
            }
           
    },
    created() {
           this.bus.$off('OpenModal');
   
        this.bus.$on('OpenModal',()=> 
        {
          this.bus.$emit('ListarUbicacion');
            setTimeout(this.Boud, 1000);    
        });
       
    },mounted() {
     /*var self =this;
      self.$refs.gmap.$mapPromise.then(function(){
           self.Boud(); 
          
      })*/       
  },
  computed: {

    arreglodinamico()
    {
      let marcadores =  this.Arreglo;
      this.markers= this.Arreglo;
       
        this.ruta=this.rutatrab;
         setTimeout(this.Boud, 1000); 
      return marcadores;
    }
      
  } 
}
</script>

<style >

</style>