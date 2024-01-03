<template>
  <div>
    <span style="display:none">  {{RegresaValor}}</span>
    <div class="row">
    
      <div class="col-lg-4">
        <gmap-autocomplete
         class="form-control"
          :options="{fields: ['geometry', 'formatted_address', 'address_components']}"
          @place_changed="setPlace">
        </gmap-autocomplete>
        
      </div>
      <div class="col-lg-2">
        <button type="button" class="btn btn-sm btn-info" @click="addMarker">Buscar</button>
      </div>

    </div>
    
    <br>
    <gmap-map
      :center="center"
      :zoom="18"
      style="width:100%;  height: 400px;"
    >
  
      <gmap-marker
        :position="center"
        :draggable="true" @drag="updateCoordinates"
       
        
      ></gmap-marker>

    </gmap-map>
  
  </div>
</template>

<script>
export default {
  name: "GoogleMap",
  props:['oLatLng'],
  data() {
    return {
      
      // default to Montreal to keep it simple
      // change this to whatever makes sense
       
      center: { lat:this.oLatLng.Lat, lng:this.oLatLng.Lng},
      markers: [],
      places: [],
      currentPlace: null,
       infoWindow: {
          position: {lat: 0, lng: 0},
          open: false,
          template: ''
        }
    };
  },

  mounted() {

    this.geolocate();
    
    this.markers.push({ position: this.center });
    
    this.addMarker();
  },

  methods: {
    // receives a place object via the autocomplete component
    setPlace(place) {
      this.currentPlace = place;
    },
    addMarker() {
       
      if (this.currentPlace) {
        //#endregionDatos del lugar
        //console.log(this.currentPlace);
        const marker = {
          lat: this.currentPlace.geometry.location.lat(),
          lng: this.currentPlace.geometry.location.lng()
        };
      
        this.markers.push({ position: marker });
        this.places.push(this.currentPlace);
        this.center = marker;
        this.currentPlace = null;
      }
    },
    geolocate: function() {
      navigator.geolocation.getCurrentPosition(position => {
        this.center = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
      });
    
    },
    updateCoordinates(location) {
      
       this.oLatLng.Lat=location.latLng.lat();
        this.oLatLng.Lng=location.latLng.lng();
            /*this.center = {
                lat: location.latLng.lat(),
                lng: location.latLng.lng(),
            };/*
    }, openInfoWindowTemplate (item) {
        /*this.setInfoWindowTemplate(item)
        this.infoWindow.position = this.getCoordinates(item)
        this.infoWindow.open = true*/
    },
    actualCordenadas()
    {
        
        this.center.lat = parseFloat( this.oLatLng.Lat);
        this.center.lng =parseFloat( this.oLatLng.Lng); 
    },

    actualCordenadas2()
    {
       
        this.center.lat =  19.43472888705915;
        this.center.lng = -99.13303711188354; 

        this.oLatLng.Lat=  19.43472888705915;
        this.oLatLng.Lng= -99.13303711188354; 

        this.addMarker();
    }
  },computed: {
      RegresaValor(){
        this.oLatLng.Lat=this.center.lat;
        this.oLatLng.Lng=this.center.lng;
        return this.center;
      }
     
  },
  created() {
      

      this.bus.$on("ActualC",data  =>{
            
            this.actualCordenadas();
      });

      this.bus.$on("actualCordenadas2",data  =>{
            
            this.actualCordenadas2();
      });
  },
};
</script>

<style >
.pac-container{
  z-index: 10000;
}
</style>