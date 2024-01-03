<template>
    <div>
    <div class="row mt-2">
    
      <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <nav class="navbar navbar-breadcrumb navbar-expand-md bg-breadcrumb breadcrumb-borde">
          <div class="mr-auto">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb clearfix pt-3">
                <li class="breadcrumb-item"><a href="#">Ubicación</a></li>
                {{zoom}}
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
    <!--
    <h2>Search and add a pin</h2>
    <label>
    <gmap-autocomplete
      @place_changed="setPlace">
    </gmap-autocomplete>
    <button >Add</button>
    </label>
    <br/>
    -->

      <!--

        types of mapa

        *roadmap
        *terrain
        *hybrid
        *satellite

      -->
    <GmapMap
    ref="gmap" id="gmap"
        :center="center"
        :zoom="zoom"
        map-type-id="hybrid"
        style="width: 1330px; height: 500px"

        :mapTypeControl="false"
        :scaleControl="false"
        :streetViewControl="false"
        :rotateControl="false"
        :fullscreenControl="true"
        :disableDefaultUi="false"
        >
         <gmap-info-window  :options="infoOptions" :position="infoWindowPos" :opened="infoWinOpen" @mouseover="toggleInfoWindow(item, index)" @closeclick="infoWinOpen=false">
           
      <div class="info_content" v-if="datos.Tipo == 1">
      <h4 class="mb-1">
        <b-avatar :src="ruta +datos.Foto2" size="2rem"></b-avatar> 
       {{datos.Tecnico}} </h4><hr v-if="typeinfo == 0">
        <p  v-if="typeinfo == 0" ><span class="bold">Servicio:</span> {{datos.Servicio}}</p>
        <p  v-if="typeinfo == 0" ><span class="bold">Cliente:</span> {{datos.Cliente}}</p>
        <p  v-if="typeinfo == 0" ><span class="bold">Tipo De Servicio:</span> {{datos.TipoServicio}}</p>
        <p  v-if="typeinfo == 0" ><span class="bold">Fecha De Servicio:</span> {{datos.FechaI}}</p>
        <p  v-if="typeinfo == 0" ><span class="bold">Hora Inicio:</span> {{datos.HoraI}} </p>
        <p  v-if="typeinfo == 0" ><span class="bold">Hora Fin:</span> {{datos.HoraF}} </p>
      </div>
    
      <div class="info_content" v-else>
      <h4 class="mb-1">
        <b-avatar :src="'http://localhost/desprosoft/assets/files/iconemp/' +datos.Foto2" size="2rem"></b-avatar> 
       {{datos.Tecnico}} </h4><hr v-if="typeinfo == 0">
        <p v-if="typeinfo == 0"><span class="bold">Servicio:</span> {{datos.Servicio}}</p>
        <p v-if="typeinfo == 0"><span class="bold">Cliente:</span> {{datos.Cliente}}</p>
        <p v-if="typeinfo == 0"><span class="bold">Tipo De Servicio:</span> {{datos.TipoServicio}}</p>
        <p v-if="typeinfo == 0"><span class="bold">Fecha De Servicio:</span> {{datos.FechaI}}</p>
        <p v-if="typeinfo == 0"><span class="bold">Hora Inicio:</span> {{datos.HoraI}} </p>
        <p v-if="typeinfo == 0"><span class="bold">Hora Fin:</span> {{datos.HoraF}} </p>
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
          @mouseover="toggleInfoWindowMouse(item, index);position=item.position"
        />

        <!--

          *Eventos de Gmap

          @mouseout="toggleInfoWindow(item, index);center=item.position"

          @mouseover="toggleInfoWindow(item, index);center=item.position"

          @click="toggleInfoWindow(item, index);center=item.position"

          :title="getTooTip(item)"

          :icon=" item.datos.Estatus =='Disponible' ? { url:  require('@/style/images/Cliente.png')} : { url:  require('@/style/images/Ocupado.png')}"
        
        -->
        

    </GmapMap> 

    </div>
</template>

<script>

export default {
    name:'Cmapa',
    props:['Arreglo','rutatrab'],
    data() {
        return {
                typeinfo:0,
                infoContent :'',
                center:{lat:19.43441642213732, lng:-99.13307696649245},
                zoom:2,
                infoWindowPos: null,
                 datos:{
                  Tecnico:'',
                  Cliente:'',
                  Servicio:'',
                  TipoServicio:'',
                  FechaI:'',
                  FechaF:'',
                  HoraI:'',
                  HoraF:'',
                  Estatus:'',
                  Foto2:''
                  },
                infoWinOpen: false,
                infowindow:'',
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

      getTooTip(item)
      {
        var html= "";

          //console.log(item.datos);

          //this.infoWinOpen = !this.infoWinOpen;

          if(item.datos.Tipo == 1){

            html = 

              "Tecnico : " +this.validateinfo(item.datos.Tecnico)+"/n" +
              "Servicio : " + this.validateinfo(item.datos.Servicio) +"/n" +
              "Cliente : " +this.validateinfo(item.datos.Cliente)+"/n" +
              "Tipo De Servici : " +this.validateinfo(item.datos.TipoServicio)+"/n" +
              "Fecha De Servicio : " +this.validateinfo(item.datos.FechaI)+"/n"+
              "Hora Inicio : " +this.validateinfo(item.datos.HoraI)+"/n"+
              "Hora Fin : " +this.validateinfo(item.datos.HoraF)+"/n"

            ;

          }else{

            html = 

              "Cliente : " +this.validateinfo(item.datos.Cliente)+"/n" +
              "Servicio : " + this.validateinfo(item.datos.Servicio) +"/n" +
              "Fecha De Servicio : " +this.validateinfo(item.datos.FechaI)+"/n"+
              "Hora Inicio : " +this.validateinfo(item.datos.HoraI)+"/n"+
              "Hora Fin : " +this.validateinfo(item.datos.HoraF)+"/n"

            ;

          }

          

        return html;
      },

      validateinfo(value){

        let valores = '';
        

        if(value != null){
          valores = value;
        }

        

        return valores;

      },

      validateicon(item){
        
        //console.log(item.datos);
        let Val = '';
        if(item.datos.Tipo == 1){
          
          if(item.datos.Estatus =='Disponible'){

             Val = require('@/style/images/disponible.png');

          }else{

             Val = require('@/style/images/Ocupado.png');
          }

        }else{

            Val = require('@/style/images/Cliente.png');
        }
        
        return Val;
      },

      toggleInfoWindowMouse: function(marker, idx) {
            this.typeinfo= 1;
            this.infoWindowPos = marker.position;
            this.infoContent = marker.datos.Tecnico;
            this.datos.Tecnico=marker.datos.Tecnico;
            this.datos.Cliente=marker.datos.Cliente;
            this.datos.Servicio=marker.datos.Servicio;
            this.datos.TipoServicio=marker.datos.TipoServicio;
            this.datos.FechaI=marker.datos.FechaI;
            this.datos.FechaF=marker.datos.FechaF;
            this.datos.HoraI=marker.datos.HoraI;
            this.datos.HoraF=marker.datos.HoraF;
            this.datos.Estatus=marker.datos.Estatus;
            this.datos.Foto2=marker.datos.Foto2;
            this.datos.Tipo=marker.datos.Tipo;

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
        
     toggleInfoWindow: function(marker, idx) {
            this.typeinfo= 0;
            this.infoWindowPos = marker.position;
            this.infoContent = marker.datos.Tecnico;
            this.datos.Tecnico=marker.datos.Tecnico;
            this.datos.Cliente=marker.datos.Cliente;
            this.datos.Servicio=marker.datos.Servicio;
            this.datos.TipoServicio=marker.datos.TipoServicio;
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
              this.getUbicaciones();
            },


        async getUbicaciones(){
          
        await this.$http.get(
         // 'ubicacionmapa/get',
         'ubicacionmapa2/get',
          {
              params:{}
          }
        ).then( (res) => {
          
          this.ruta=res.data.data.ruta;
          this.markers=[];
          
          res.data.data.ubicaciones.forEach(element => {
            this.markers.push({
            position: {lat: parseFloat( element.lat), lng: parseFloat(element.lng)},

              //Nuevo
              datos:{
                Tipo:element.Tipo,
                Tecnico:element.Nombre,
                Cliente:element.Cliente,
                Servicio:element.Folio,
                TipoServicio:element.Concepto,
                FechaI:element.Fecha_I,
                FechaF:element.Fecha_F,
                HoraI:element.HoraInicio,
                HoraF:element.HoraFin,
                Estatus:element.Estatus,
                Foto2:element.Foto2,
              }

            });


          });

          


        });   
      },

           
    },
    created() {
        this.getUbicaciones();

        
       
    },mounted() {

        
     /*var self =this;
      self.$refs.gmap.$mapPromise.then(function(){
           self.Boud(); 
          
      })*/       
  },
  computed: {

    arreglodinamico()
    {
      let marcadores =  this.markers;
      this.markers= this.markers;
       
        //this.ruta=this.rutatrab;
        setTimeout(this.Boud, 1000); 
      return marcadores;
    }
      
  } 
}
</script>

<style >
.tooltip.tooltiptext{
width: 160px;
bottom:80%;
left:40%;
margin-left: 80px;
}
</style>