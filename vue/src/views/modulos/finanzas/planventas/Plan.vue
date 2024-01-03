<template>
    <div>
        {{Totales}}
        <table class="table-venta-01">
            <thead>
                <tr>
                    <th class="sticky marca" scope="col">Concepto</th>
                    <th scope="col">Trimeste 1</th>
                    <th scope="col">#Propuestas</th>
                    <th scope="col">Trimestre 2</th>
                    <th scope="col">#Propuestas</th>
                    <th scope="col">Trimestre 3</th>
                    <th scope="col">#Propuestas</th>
                    <th scope="col">Trimestre 4</th>
                    <th scope="col">#Propuestas</th>
                    <th scope="col">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item,index) in ListaDetalle" :key="index">
                <td class="sticky">{{item.Nombre}}</td>
                    <td >
                        <vue-numeric  placeholder="$ 0.00" class="form-control form-finanza form-control-sm text-center" currency="$" separator="," :precision="2" v-model="item.Data.UnoT"></vue-numeric>
					</td>
                    <td >
						<vue-numeric placeholder="0" disabled class="form-control form-finanza form-control-sm text-center" currency="" separator=","  v-model="item.Data.UnoP"></vue-numeric>
					</td>
                    <td>
                        <vue-numeric placeholder="$ 0.00" class="form-control form-finanza form-control-sm text-center" currency="$" separator="," :precision="2" v-model="item.Data.DosT"></vue-numeric>
                    </td>
                    <td>
						<vue-numeric placeholder="0" disabled class="form-control form-finanza form-control-sm text-center" currency="" separator=","  v-model="item.Data.DosP"></vue-numeric>
					</td>
                    <td>
						<vue-numeric placeholder="$ 0.00" class="form-control form-finanza form-control-sm text-center" currency="$" separator="," :precision="2" v-model="item.Data.TresT"></vue-numeric>
					</td>
                    <td>
						<vue-numeric placeholder="0" disabled class="form-control form-finanza form-control-sm text-center" currency="" separator=","  v-model="item.Data.TresP"></vue-numeric>
					</td>
                    <td>
						<vue-numeric placeholder="$ 0.00" class="form-control form-finanza form-control-sm text-center" currency="$" separator="," :precision="2" v-model="item.Data.CuatroT"></vue-numeric>
					</td>
                    <td>
						<vue-numeric placeholder="0" disabled class="form-control form-finanza form-control-sm text-center" currency="" separator=","  v-model="item.Data.CuatroP"></vue-numeric>
					</td>
                    <td>
						<vue-numeric  placeholder="$ 0.00" disabled class="form-control form-finanza form-control-sm text-center" currency="$" separator="," :precision="2" v-model="item.Data.TotalAnual"></vue-numeric>
					</td>
                </tr>

            </tbody>
            <tfoot>
                <tr>
                    <td class="color-01 bold sticky marca">Totales</td>
                    <td><vue-numeric disabled class="form-control form-finanza form-control-sm color-01 bold text-center" currency="$" separator="," :precision="2" v-model="Trimestre1"></vue-numeric></td>
                    <td><vue-numeric disabled class="form-control form-finanza form-control-sm color-01 bold text-center" currency="" separator=","  v-model="Propuesta1"></vue-numeric></td>
                    <td><vue-numeric disabled class="form-control form-finanza form-control-sm color-01 bold text-center" currency="$" separator="," :precision="2" v-model="Trimestre2"></vue-numeric></td>
                    <td><vue-numeric disabled class="form-control form-finanza form-control-sm color-01 bold text-center" currency="" separator=","  v-model="Propuesta2"></vue-numeric></td>
                    <td><vue-numeric disabled class="form-control form-finanza form-control-sm color-01 bold text-center" currency="$" separator="," :precision="2" v-model="Trimestre3"></vue-numeric></td>
                    <td><vue-numeric disabled class="form-control form-finanza form-control-sm color-01 bold text-center" currency="" separator=","  v-model="Propuesta3"></vue-numeric></td>
                    <td><vue-numeric disabled class="form-control form-finanza form-control-sm color-01 bold text-center" currency="$" separator="," :precision="2" v-model="Trimestre4"></vue-numeric></td>
                    <td><vue-numeric disabled class="form-control form-finanza form-control-sm color-01 bold text-center" currency="" separator=","  v-model="Propuesta4"></vue-numeric></td>
                    <td><vue-numeric disabled class="form-control form-finanza form-control-sm color-01 bold text-center" currency="$" separator="," :precision="2" v-model="TotalGral"></vue-numeric></td>
                </tr>
            </tfoot>
        </table>
    </div>
</template>

<script>
export default {
    props:['ListaDetalle'],
    data() {
        return {
            Trimestre1:0,
            Trimestre2:0,
            Trimestre3:0,
            Trimestre4:0,
            Propuesta1:0,
            Propuesta2:0,
            Propuesta3:0,
            Propuesta4:0,
            TotalGral:0,
            price:'',
            Decimal:1
        }
    },
    methods: {
       //formateando numeros
     formatNumber(num,prefix){
        num = Math.round(parseFloat(num)*Math.pow(10,2))/Math.pow(10,2)
        prefix = prefix || '';
        num += '';
        var splitStr = num.split('.');
        var splitLeft = splitStr[0];
        var splitRight = splitStr.length > 1 ? '.' + splitStr[1] : '.00';
        splitRight = splitRight + '00';
        splitRight = splitRight.substr(0,3);
        var regx = /(\d+)(\d{3})/;
        while (regx.test(splitLeft)) {
            splitLeft = splitLeft.replace(regx, '$1' + ',' + '$2');
        }
        return prefix + splitLeft + splitRight;
        }

    },
    created() {

    },
    computed: {
           Totales ()
        {
             this.Trimestre1=0;
             this.Trimestre2=0;
             this.Trimestre3=0;
             this.Trimestre4=0;
             this.TotalGral=0;
             this.Propuesta1=0;
             this.Propuesta2=0;
             this.Propuesta3=0;
             this.Propuesta4=0;
             var fixed=0;

            for ( var i =0;i<this.ListaDetalle.length;i++ )
            {
                var Total =0;

                this.ListaDetalle[i].Data.UnoP=0;
                if (this.ListaDetalle[i].Data.UnoT !='')
                {
                    this.Trimestre1 +=parseFloat( this.ListaDetalle[i].Data.UnoT);
                    Total   +=parseFloat( this.ListaDetalle[i].Data.UnoT);

                    if (this.ListaDetalle[i].Data.ValorPromedio !='' && this.ListaDetalle[i].Data.PorcentajeBCierre !='')
                    {
                    var y = (parseFloat(this.ListaDetalle[i].Data.UnoT)  /parseFloat(this.ListaDetalle[i].Data.ValorPromedio) ) * 100 ;
                    var Prop=parseFloat(y) /parseFloat(this.ListaDetalle[i].Data.PorcentajeBCierre);
                        if(Prop=='Infinity' || isNaN(Prop)==true){
                            Prop =0;
                        }
                    this.ListaDetalle[i].Data.UnoP= Prop.toFixed(fixed);
                    this.Propuesta1 +=parseFloat(Prop.toFixed(fixed));
                    }

                }

                this.ListaDetalle[i].Data.DosP=0;
                 if (this.ListaDetalle[i].Data.DosT !='')
                {
                this.Trimestre2 +=parseFloat( this.ListaDetalle[i].Data.DosT);
                  Total   +=parseFloat( this.ListaDetalle[i].Data.DosT);
                 if (this.ListaDetalle[i].Data.ValorPromedio !='' && this.ListaDetalle[i].Data.PorcentajeBCierre !='')
                    {
                    var y = (parseFloat(this.ListaDetalle[i].Data.DosT)  /parseFloat(this.ListaDetalle[i].Data.ValorPromedio) ) * 100 ;
                    var Prop=parseFloat(y) /parseFloat(this.ListaDetalle[i].Data.PorcentajeBCierre);
                        if(Prop=='Infinity' || isNaN(Prop)==true){
                            Prop =0;
                        }
                    this.ListaDetalle[i].Data.DosP= Prop.toFixed(fixed);
                     this.Propuesta2 +=parseFloat(Prop.toFixed(fixed));
                    }


                }

                  this.ListaDetalle[i].Data.TresP=0;
                 if (this.ListaDetalle[i].Data.TresT !='')
                {
                this.Trimestre3 +=parseFloat( this.ListaDetalle[i].Data.TresT);
                Total   +=parseFloat( this.ListaDetalle[i].Data.TresT);


                    if (this.ListaDetalle[i].Data.ValorPromedio !='' && this.ListaDetalle[i].Data.PorcentajeBCierre !='')
                    {
                    var y = (parseFloat(this.ListaDetalle[i].Data.TresT)  /parseFloat(this.ListaDetalle[i].Data.ValorPromedio) ) * 100 ;
                    var Prop=parseFloat(y) /parseFloat(this.ListaDetalle[i].Data.PorcentajeBCierre);
                        if(Prop=='Infinity' || isNaN(Prop)==true){
                            Prop =0;
                        }
                    this.ListaDetalle[i].Data.TresP= Prop.toFixed(fixed);
                    this.Propuesta3 +=parseFloat(Prop.toFixed(fixed));
                    }

                }

                this.ListaDetalle[i].Data.CuatroP=0;
                if (this.ListaDetalle[i].Data.CuatroT !='')
                {
                this.Trimestre4 +=parseFloat( this.ListaDetalle[i].Data.CuatroT);
                Total   +=parseFloat( this.ListaDetalle[i].Data.CuatroT);

                 if (this.ListaDetalle[i].Data.ValorPromedio !='' && this.ListaDetalle[i].Data.PorcentajeBCierre !='')
                    {
                    var y = (parseFloat(this.ListaDetalle[i].Data.CuatroT)  /parseFloat(this.ListaDetalle[i].Data.ValorPromedio) ) * 100 ;
                    var Prop=parseFloat(y) /parseFloat(this.ListaDetalle[i].Data.PorcentajeBCierre);
                        if(Prop=='Infinity' || isNaN(Prop)==true){
                            Prop =0;
                        }
                    this.ListaDetalle[i].Data.CuatroP= Prop.toFixed(fixed);
                        this.Propuesta4 +=parseFloat(Prop.toFixed(fixed));
                    }

                }
                this.TotalGral +=parseFloat(Total);
                this.ListaDetalle[i].Data.TotalAnual =parseFloat(Total);
            }

        }
    },


}
</script>
