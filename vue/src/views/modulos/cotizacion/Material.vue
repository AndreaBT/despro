<template>
    <div>
		<div class="row">
			<input type="hidden" :value="validateMarkups">

            <div class="col-12 col-sm-12 col-md-12 col-lg-12 text-right">
                <form class="form-inline float-right">
                    <button @click="add_Materiales(undefined)" type="button" class="btn btn-01 add mx-sm-2">
                        Agregar Fila
                    </button>
                    <button  data-toggle="modal" data-target="#ModalForm" type="button" class="btn btn-01 search">Producto</button>
                </form>
            </div>
        </div>

        <table class="table table-sm table-03 mt-3">
            <thead>
                <tr>
                    <th class="text-center colum02">Descripción</th>
                    <th class="text-center colum02">Cantidad</th>
                    <th class="text-center colum02">Costo Unit</th>
                    <th class="text-center colum02">Total</th>
                    <th class="text-center colum02"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in cotizacion_servicio.ListaMaterialCot" :key="index">
                    <td>
                        <input placeholder="Descripción"  v-model="item.NombreMat" type="text" class="form-control form-control-sm">
                    </td>
                    <td>
                        <vue-numeric placeholder="0" @input="calcula_importe(item)" :minus="false" class="form-control form-control-sm"  currency="" separator="," :precision="2" v-model="item.cantidad"></vue-numeric>
                    </td>
                    <td>
                        <vue-numeric placeholder="$ 0.00" @input="calcula_importe(item)" :minus="false" class="form-control form-control-sm"  currency="$" separator="," :precision="2" v-model="item.costoUnitario"></vue-numeric>
                    </td>
                    <td>
                        <vue-numeric placeholder="$ 0.00" disabled :minus="false" class="form-control form-control-sm"  currency="$" separator="," :precision="2" v-model="item.totalIndividual"></vue-numeric>
                    </td>
                    <td>
                        <button @click="delete_mat(index);" type="button" href="#" class="btn-icon-02">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right">Total</td>
                    <td colspan="1" class="text-right">
                        <vue-numeric disabled placeholder="$ 0.00" :minus="true" class="form-control form-control-sm"  currency="$" separator="," :precision="2" v-model="cotizacion_servicio.totalMateriales"></vue-numeric>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</template>

<script>

export default {
    name:'material',
    props:['pcotizacion_servicio','Listamarkup'],

    data() {
        return {
            size :"modal-xl",
			urlApi:"markup/get",
			cotizacion_servicio:{},
			Markups: []
        }
    },
	methods: {
		add_Materiales(obj){
            let IdMaterial	= 0;
            let NomMaterial	= '';
            let Precio		= 0;

            if(obj !== undefined) {
               IdMaterial 	= parseInt(obj.IdMaterial);
               NomMaterial 	= obj.NomMaterial.trim();
               Precio 		= parseFloat(obj.Precio);

			   // EVALUA SI EL MATERIAL NO YA HA SIDO AGREGADO, EVITA QUE SE REPITA DICHO ELEMENTO EN LA LISTA
			   let valObj = this.cotizacion_servicio.ListaMaterialCot.filter(function(elem) {
                    if(elem.IdMaterial === obj.IdMaterial) {
                        return elem.IdMaterial;
                    }
                });

			   if(valObj.length > 0){
				   return false;
			   }

            }

			let lista = {
				IdMaterial: IdMaterial,
				NombreMat: NomMaterial,
				costoUnitario: Precio,
				cantidad: 1,
				totalIndividual: Precio
			};

			this.cotizacion_servicio.ListaMaterialCot.push(lista);


        },

        calcula_importe(item) {
            let Precio	 	= parseFloat(item.costoUnitario);
            let Cantidad 	= parseFloat(item.cantidad);
            let Resultado  	= (Precio * Cantidad);

            // MARKUP - SE UBICA CON IMPORTE DEL MATERIAL EL RANGO EN EL QUE CALIFICA PARA TENER EL VALOR DE MARKUP
            let valObj = this.Listamarkup.filter(function(elem) {
                if(parseFloat(elem.Monto_I) <= Resultado && parseFloat(elem.Monto_F) >= Resultado) {
					return elem;
                }
            });

            let CostoMarkup = 0;
            if(valObj.length > 0){
				CostoMarkup = parseFloat(valObj[0].CostoM);
            }


			if(CostoMarkup === 0){CostoMarkup=0}
            if(Precio === 0){Precio=0}
            if(Cantidad === 0){Cantidad=0}
            if(Resultado === 0){Resultado=0}

            let subTotal = isNaN( (Resultado * CostoMarkup) ) ? 0 : (Resultado * CostoMarkup);

			item.totalIndividual = subTotal.toFixed(2);


           this.calcula_total();
        },

		calcula_total(){
			let Total = 0;
			this.cotizacion_servicio.ListaMaterialCot.forEach(element => {
				if(parseFloat(element.totalIndividual) !== 0){
					Total+=parseFloat(element.totalIndividual);
				}
			});

			let subTotal = isNaN(Total) ? 0 : Total;
			this.cotizacion_servicio.totalMateriales = subTotal.toFixed(2);
		},

        delete_mat(index) {
            this.cotizacion_servicio.ListaMaterialCot.splice(index, 1);
			this.calcula_total();
        },

    },
    created() {
        this.bus.$off('Add_Materiales');

        this.cotizacion_servicio = this.pcotizacion_servicio;

        this.bus.$on('Add_Materiales',(obj)=> {
            this.add_Materiales(obj);
        });
    },
    computed: {

		validateMarkups() {
			this.Markups = this.Listamarkup;
			return this.Markups;
		},


    },
}
</script>
