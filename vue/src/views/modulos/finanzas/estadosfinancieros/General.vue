<template>
  <div>
      {{calcularporcentajeanioactual}}
      {{calcularPorcentajesGA}}
      {{calcularPorcentajesDeptoV}}
      {{calcularPorcentajesDeptoVOpera}}
      {{calcularPorcentajesVehiculos}}
      {{calcularPorcentajesCostosFinancieros}}
    <section class="container-fluid">
        <div class="row mt-3">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <nav class="navbar navbar-breadcrumb navbar-expand-md bg-breadcrumb breadcrumb-borde">
                    <div class="mr-auto">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb clearfix pt-3">
                                <li class="breadcrumb-item"><a href="#" @click="Menu">Menú</a></li>
                                <li class="breadcrumb-item active">Estados Financieros General</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="form-inline">
                        <div class="form-group mt-n1">
                             <!-- <button @click="PDF2022"  type="button" class="btn btn-01 print mr-2">Pdf NUEVO</button> -->
                            <button  @click="Descargar" type="button" class="btn btn-01 print mr-2">Imprimir</button>
                        </div>
                    </div>
                </nav>
            </div>
        </div>


        <div class="row justify-content-start mt-3">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <b-overlay :show="isOverlay" rounded="sm" spinner-variant="primary" class="h-100">
                    <div class="card card-01">
                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <div class="form-inline justify-content-start mt-2">
                                    <label class="mr-1">Año</label>
                                    <select :disabled="Disabled" @change="get_EstadoFinGenFiltro"  v-model="Anio" class="form-control mr-2">
                                            <option v-for="(item,index) in ListaAnios" :key="index" :value="item">{{item}}</option>
                                        </select>

                                    <label class="mr-1">Mes</label>
                                    <select :disabled="Disabled" @change="get_EstadoFinGenFiltro"  v-model="Mes" class="form-control form-control-sm mr-2">
                                        <option  :value="0">Enero</option>
                                        <option  :value="1">Febrero</option>
                                        <option  :value="2">Marzo</option>
                                        <option  :value="3">Abril</option>
                                        <option  :value="4">Mayo</option>
                                        <option  :value="5">Junio</option>
                                        <option  :value="6">Julio</option>
                                        <option  :value="7">Agosto</option>
                                        <option  :value="8">Septiembre</option>
                                        <option  :value="9">Octubre</option>
                                        <option  :value="10">Noviembre</option>
                                        <option  :value="11">Diciembre</option>
                                    </select>

                                    <label class="mr-1">Departamentos</label>
                                    <select @change=" get_EstadoFinGenFiltro()"  v-model="Grafica1.Mes" class="form-control form-control-sm mr-2">
                                        <option  value="12">Estados Financieros General.</option>
                                        <option  value="13">Costo G&A</option>
                                        <option  value="14">Costo Depto. Venta.</option>
                                        <option  value="15">Costo Depto. Oper.</option>
                                        <option  value="16">Costo Vehículo Oper.</option>
                                        <option  value="17">Costo Financiero.</option>
                                    <!--  <option  value="18">Sueldos Personal Operativo.</option>-->
                                    </select>
                                    <!-- <h6 class="text-center mr-2" v-if="cliente.Nombre != ''">{{cliente.Nombre}}</h6>
                                    <h6 v-else class="text-center mr-2">Cliente Sin Selecionar</h6> -->
                                    <!-- <button @click="ListaCliente"  data-toggle="modal" data-target="#ModalForm3"  data-backdrop="static"  type="button" class="btn btn-01 search mr-2">Cliente</button> -->
                                    <!-- <label class="mr-1">No. De Contrato</label>
                                    <select @change="get_listdata" v-model="IdContrato" name="" id=""  class="form-control form-control-sm mr-2">
                                            <option :value="''">Seleccione Un Numero de Contrato</option>
                                            <option v-for="(item,index) in ListaNumc" :key="index" :value="item.IdContrato">
                                            {{item.NumeroC}}
                                            </option>
                                    </select>
                                    <button @click="Limpiar"  type="button" class="btn btn-04"><i class="fas fa-times"></i></button>
                                    -->
                                </div>
                                <hr>
                            </div>
                        </div>

                        <!---ESTADOS FINANCIEROS-->
                        
                            <div  v-if="!loading && parseInt(this.Grafica1.Mes) != 13 && parseInt(this.Grafica1.Mes) != 14 && parseInt(this.Grafica1.Mes) != 15 && parseInt(this.Grafica1.Mes) != 16 && parseInt(this.Grafica1.Mes) != 17"  class="row mt-2">
                                
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="table-finanzas">
                                        <table class="table-fin-02">
                                            <thead>
                                                <tr>
                                                    <th class="sticky marca"></th>
                                                    <th colspan="2" class="text-center">Año Pasado</th>
                                                    <th colspan="4" class="text-center blue-03">Año Actual</th>
                                                    <th colspan="4" class="text-center blue-02">Mes Actual</th>
                                                </tr>
                                                <tr>
                                                    <th class="sticky mediana marca"><b>Descripción</b></th>
                                                    <th class="mediana text-center">Actual</th>
                                                    <th class="blue-01 mediana text-center">%</th>
                                                    <th class="blue-03 mediana text-center">Plan</th>
                                                    <th class="blue-03 mediana text-center">%</th>
                                                    <th class="blue-03 mediana text-center">Actual</th>
                                                    <th class="blue-03 mediana text-center">%</th>
                                                    <th class="blue-02 mediana text-center">Plan</th>
                                                    <th class="blue-02 mediana text-center">%</th>
                                                    <th class="blue-02 mediana text-center">Actual</th>
                                                    <th class="blue-02 mediana text-center">%</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item,index) in ListaDetalle" :key="index" :style="ValidateFondo(item,index)">
                                                    <td :class="{ 'stickyNegro': index == 0, 'sticky': index > 0 }" ><b>{{item.Descripcion}}</b></td>
                                                    <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="item.AnioAnteriorMonto"></Cmoneda>   </td>
                                                    <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.AnioAnteriorPorcen"></Cporcentaje> </td>
                                                    <td><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal"  v-model="item.AnioActualPlan"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.AnioActualPlanPorcent"></Cporcentaje> </td>
                                                    <td><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal"  v-model="item.AnioActualMonto"></Cmoneda> </td>
                                                    <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.AnioActualPorcen"></Cporcentaje> </td>
                                                    <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="item.MesActualPlan"></Cmoneda> </td>
                                                    <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.MesActualPlanPorcen"></Cporcentaje> </td>
                                                    <td><Cmoneda  :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="item.MesActualMonto"></Cmoneda> </td>
                                                    <td><Cporcentaje :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"   v-model="item.MesActualPorcen"></Cporcentaje> </td>
                                                </tr>
                                                <tr>
                                                    <td height="1" class="marcan" ></td>
                                                    <td height="1" class="marcan" ></td>
                                                    <td height="1" class="marcan" ></td>
                                                    <td height="1" class="marcan" ></td>
                                                    <td height="1" class="marcan" ></td>
                                                    <td height="1" class="marcan" ></td>
                                                    <td height="1" class="marcan" ></td>
                                                    <td height="1" class="marcan" ></td>
                                                    <td height="1" class="marcan" ></td>
                                                    <td height="1" class="marcan" ></td>
                                                    <td height="1" class="marcan" ></td>
                                                </tr>
                                                <tr>
                                                    <td class="sticky"><b>Costo Operacional</b></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="UnoOp">   </Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="DosOp">     </Cporcentaje> </td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="TresOp"></Cmoneda> </td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="CuatroOp"></Cporcentaje> </td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="CincoOp"></Cmoneda> </td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="SeisOp"></Cporcentaje> </td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="SieteOp"></Cmoneda> </td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="OchoOp"></Cporcentaje> </td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="NueveOp"></Cmoneda> </td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="DiesOp"></Cporcentaje> </td>
                                                </tr>
                                                <tr>
                                                    <td class="sticky"><b>Gross Profit</b></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="UnoGp"></Cmoneda> </td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="DosGp"></Cporcentaje> </td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="TresGp"></Cmoneda> </td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="CuatroGp"></Cporcentaje> </td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="CincoGp"></Cmoneda> </td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="SeisGp"></Cporcentaje> </td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="SieteGp"></Cmoneda> </td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="OchoGp"></Cporcentaje> </td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="NueveGp"></Cmoneda> </td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="DiesGp"></Cporcentaje> </td>
                                                </tr>
                                                <tr>
                                                    <td class="marca"><br></td>
                                                    <td class="marca"></td>
                                                    <td class="marca"></td>
                                                    <td class="marca"></td>
                                                    <td class="marca"></td>
                                                    <td class="marca"></td>
                                                    <td class="marca"></td>
                                                </tr>
                                                <tr>
                                                    <td class="sticky"><b>Costos G & A</b></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="ga1"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="ga2"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="ga3"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="ga4"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true"     :minus="true"  currency="$" separator="," :precision="Decimal" v-model="ga5"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="ga6"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="ga7"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="ga8"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="ga9"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="ga10"></Cporcentaje></td>
                                                </tr>
                                                <!-- mover ingresos y egresos aqui y cambiar nombre por Costos Financieros-->
                                                <tr>
                                                    <td class="sticky"><b>Costos Financieros</b></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="ie1"></Cmoneda> </td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="ie2"></Cporcentaje> </td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="ie3"></Cmoneda> </td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="ie4"></Cporcentaje> </td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="ie5"></Cmoneda> </td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="ie6"></Cporcentaje> </td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="ie7"></Cmoneda> </td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="ie8"></Cporcentaje> </td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="ie9"></Cmoneda> </td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="ie10"></Cporcentaje> </td>
                                                </tr>
                                                <!-- mover ingresos y egresos aqui y cambiar nombre por Costos Financieros-->
                                                <tr>
                                                    <td class="sticky"><b>Costos Depto. Venta</b></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="UnoDV"></Cmoneda></td>
                                                    <td ><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="DosDV"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="TresDV"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="CuatroDV"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="CincoDV"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="SeisDV"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="SieteDV"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="OchoDV"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="NueveDV"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="DiesDV"></Cporcentaje></td>
                                                </tr>
                                                <tr>
                                                    <td class="marca"><br></td>
                                                    <td class="marca"></td>
                                                    <td class="marca"></td>
                                                    <td class="marca"></td>
                                                    <td class="marca"></td>
                                                    <td class="marca"></td>
                                                    <td class="marca"></td>
                                                </tr>
                                                <tr>
                                                    <td class="sticky"><b>Varianza Burden</b></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="vb1"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="vb2"></Cporcentaje></td>
                                                    <!-- modificacion 19/08/2021 mencionan que en el plan va 0 -->
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="vb3"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="vb4"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="vb5"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="vb6"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="vb7"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="vb8"></Cporcentaje></td>                                      
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="vb9"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="vb10"></Cporcentaje></td>
                                                </tr>
                                                <tr>
                                                    <td class="sticky"><b>Varianza Vehículo</b></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="vv1"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="vv2"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="vv3"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="vv4"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="vv5"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="vv6"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="vv7"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="vv8"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="vv9"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="vv10"></Cporcentaje></td>
                                                </tr>
                                                <tr>
                                                    <td class="sticky"><b>Varianza Mano de Obra</b></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="varianzaManoObra1"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="varianzaManoObra2"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="varianzaManoObra3"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="varianzaManoObra4"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="varianzaManoObra5"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="varianzaManoObra6"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="varianzaManoObra7"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="varianzaManoObra8"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="varianzaManoObra9"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="varianzaManoObra10"></Cporcentaje></td>
                                                </tr>
                                                <tr>
                                                    <td class="sticky"><b>Varianza de Almacén </b></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="varianzaAlmacen1"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="varianzaAlmacen2"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="varianzaAlmacen3"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="varianzaAlmacen4"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="varianzaAlmacen5"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="varianzaAlmacen6"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="varianzaAlmacen7"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="varianzaAlmacen8"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="varianzaAlmacen9"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'" :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="varianzaAlmacen10"></Cporcentaje></td>
                                                </tr>
                                                <tr>
                                                    <td class="marca"><br></td>
                                                    <td class="marca"></td>
                                                    <td class="marca"></td>
                                                    <td class="marca"></td>
                                                    <td class="marca"></td>
                                                    <td class="marca"></td>
                                                    <td class="marca"></td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td class="color-01 bold sticky marca">OPERATION PROFIT</td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="np1"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="np2"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="np3"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="np4"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="np5"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="np6"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="np7"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="np8"></Cporcentaje></td>
                                                    <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="np9"></Cmoneda></td>
                                                    <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="np10"></Cporcentaje></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                
                            </div>
                    

                        <!--COSTO G&A--->
                        <div  v-if="parseInt(this.Grafica1.Mes) === 13" class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="table-finanzas">
                                    <table class="table-fin-02">
                                        <thead>
                                            
                                            <tr>
                                                <th class="sticky marca"></th>
                                                <th colspan="2" class="text-center">Año Pasado</th>
                                                <th colspan="4" class="text-center blue-03">Año Actual</th>
                                                <th colspan="4" class="text-center blue-02">Mes Actual</th>
                                            </tr>
                                            <tr>
                                                <th class="sticky mediana marca"><b>Descripción</b></th>
                                                <th class="mediana text-center">Actual</th>
                                                <th class="blue-01 mediana text-center">%</th>
                                                <th class="blue-03 mediana text-center">Plan</th>
                                                <th class="blue-03 mediana text-center">%</th>
                                                <th class="blue-03 mediana text-center">Actual</th>
                                                <th class="blue-03 mediana text-center">%</th>
                                                <th class="blue-02 mediana text-center">Plan</th>
                                                <th class="blue-02 mediana text-center">%</th>
                                                <th class="blue-02 mediana text-center">Actual</th>
                                                <th class="blue-02 mediana text-center">%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="stickyNegro" ><b>Facturacion</b></td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="AnioAnterior2" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="AnioAnteriorPlanGA2">     </Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="AnioActualPlan2"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="AnioActualPlanPorcen2"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="AnioActualGA2"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="AnioActualPorcenGA2"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="PlanMes2"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="PlanMesPorcen2"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="MesActual2"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="MesActualPorce2"></Cporcentaje> </td>
                                            
                                            </tr>
                                            <tr>
                                                <td height="1" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                            </tr>
                                            <tr v-for="(item,index) in ListaGA" :key="index" >
                                                
                                                <td :class="{ 'stickyNegro': index == 0, 'sticky': index > 0 }" ><b>{{item.Descripcion}}</b></td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="item.AnioAnterior"></Cmoneda>   </td>
                                                <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.AnioAnteriorPlan"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal"  v-model="item.PlanAnual"></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.PlanAnualPorcen"></Cporcentaje> </td>
                                                <td ><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal"  v-model="item.AnioActual"></Cmoneda> </td>
                                                <td ><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"   v-model="item.AnioActualProcen"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="item.PlanMes"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.PlanMesPorcen"></Cporcentaje> </td>
                                                <td><Cmoneda  :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="item.MesActual"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"   v-model="item.MesActualPorce"></Cporcentaje> </td>
                                            </tr>
                                            
                                        </tbody>    
                                        <tfoot>
                                            <tr>
                                                <td class="color-01 bold sticky marca">TOTALES</td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp1" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="Gp2" ></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp3" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="Gp4" ></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp5" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1" v-model="Gp6"  ></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp7" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="Gp8" ></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp9" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="Gp10" ></Cporcentaje></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div  v-if="parseInt(this.Grafica1.Mes) === 13 && this.ListaGA.length<=0" class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            
                                <h1 style="text-align:center">Aún no tienes registros en Costos G&A</h1>
                            
                            </div>
                        </div>

                        <!---COSTO DEPTO. VENTA-->
                        <div  v-if="parseInt(this.Grafica1.Mes) === 14" class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="table-finanzas">
                                    <table class="table-fin-02">
                                        <thead>
                                            
                                            <tr>
                                                <th class="sticky marca"></th>
                                                <th colspan="2" class="text-center">Año Pasado</th>
                                                <th colspan="4" class="text-center blue-03">Año Actual</th>
                                                <th colspan="4" class="text-center blue-02">Mes Actual</th>
                                            </tr>
                                            <tr>
                                                <th class="sticky mediana marca"><b>Descripción</b></th>
                                                <th class="mediana text-center">Actual</th>
                                                <th class="blue-01 mediana text-center">%</th>
                                                <th class="blue-03 mediana text-center">Plan</th>
                                                <th class="blue-03 mediana text-center">%</th>
                                                <th class="blue-03 mediana text-center">Actual</th>
                                                <th class="blue-03 mediana text-center">%</th>
                                                <th class="blue-02 mediana text-center">Plan</th>
                                                <th class="blue-02 mediana text-center">%</th>
                                                <th class="blue-02 mediana text-center">Actual</th>
                                                <th class="blue-02 mediana text-center">%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="stickyNegro" ><b>Facturacion</b></td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="AnioAnterior2" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="AnioAnteriorPlanGA2">     </Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="AnioActualPlan2"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="AnioActualPlanPorcen2"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="AnioActualGA2"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="AnioActualPorcenGA2"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="PlanMes2"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="PlanMesPorcen2"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="MesActual2"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="MesActualPorce2"></Cporcentaje> </td>
                                            
                                            </tr>
                                            <tr>
                                                <td height="1" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                            </tr>
                                            <tr v-for="(item,index) in ListaDV" :key="index" >
                                                
                                                <td :class="{ 'stickyNegro': index == 0, 'sticky': index > 0 }" ><b>{{item.Descripcion}}</b></td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="item.AnioAnterior"></Cmoneda>   </td>
                                                <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.AnioAnteriorPlan"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal"  v-model="item.PlanAnual"></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.PlanAnualPorcen"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal"  v-model="item.AnioActual"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.AnioActualProcen"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="item.PlanMes"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.PlanMesPorcen"></Cporcentaje> </td>
                                                <td><Cmoneda  :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="item.MesActual"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"   v-model="item.MesActualPorce"></Cporcentaje> </td>
                                            </tr>
                                            
                                        </tbody>    
                                        <tfoot>
                                            <tr>
                                                <td class="color-01 bold sticky marca">TOTALES</td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp1" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="Gp2" ></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp3" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="Gp4" ></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp5" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1" v-model="Gp6"  ></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp7" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="Gp8" ></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp9" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="Gp10" ></Cporcentaje></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div  v-if="parseInt(this.Grafica1.Mes) === 14 && this.ListaDV.length<=0" class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            
                                <h1 style="text-align:center">Aún no tienes registros en Costos Depto. Venta</h1>
                            
                            </div>
                        </div>

                        <!---COSTO DEPTO. Opera-->
                        <div  v-if="parseInt(this.Grafica1.Mes) === 15" class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="table-finanzas">
                                    <table class="table-fin-02">
                                        <thead>
                                            
                                            <tr>
                                                <th class="sticky marca"></th>
                                                <th colspan="2" class="text-center">Año Pasado</th>
                                                <th colspan="4" class="text-center blue-03">Año Actual</th>
                                                <th colspan="4" class="text-center blue-02">Mes Actual</th>
                                            </tr>
                                            <tr>
                                                <th class="sticky mediana marca"><b>Descripción</b></th>
                                                <th class="mediana text-center">Actual</th>
                                                <th class="blue-01 mediana text-center">%</th>
                                                <th class="blue-03 mediana text-center">Plan</th>
                                                <th class="blue-03 mediana text-center">%</th>
                                                <th class="blue-03 mediana text-center">Actual</th>
                                                <th class="blue-03 mediana text-center">%</th>
                                                <th class="blue-02 mediana text-center">Plan</th>
                                                <th class="blue-02 mediana text-center">%</th>
                                                <th class="blue-02 mediana text-center">Actual</th>
                                                <th class="blue-02 mediana text-center">%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="stickyNegro" ><b>Facturacion</b></td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="AnioAnterior2" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="AnioAnteriorPlanGA2">     </Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="AnioActualPlan2"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="AnioActualPlanPorcen2"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="AnioActualGA2"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="AnioActualPorcenGA2"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="PlanMes2"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="PlanMesPorcen2"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="MesActual2"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="MesActualPorce2"></Cporcentaje> </td>
                                            
                                            </tr>
                                            <tr>
                                                <td height="1" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                            </tr>
                                            <tr v-for="(item,index) in ListaDO" :key="index" >
                                                
                                                <td :class="{ 'stickyNegro': index == 0, 'sticky': index > 0 }" ><b>{{item.Descripcion}}</b></td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="item.AnioAnterior"></Cmoneda>   </td>
                                                <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.AnioAnteriorPlan"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal"  v-model="item.PlanAnual"></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.PlanAnualPorcen"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal"  v-model="item.AnioActual"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.AnioActualProcen"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="item.PlanMes"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.PlanMesPorcen"></Cporcentaje> </td>
                                                <td><Cmoneda  :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="item.MesActual"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"   v-model="item.MesActualPorce"></Cporcentaje> </td>
                                            </tr>
                                            
                                        </tbody> 
                                        <tfoot>
                                            <tr>
                                                <td class="color-01 bold sticky marca">TOTALES</td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp1" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="Gp2" ></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp3" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="Gp4" ></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp5" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1" v-model="Gp6"  ></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp7" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="Gp8" ></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp9" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="Gp10" ></Cporcentaje></td>
                                            </tr>
                                        </tfoot>   
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div  v-if="parseInt(this.Grafica1.Mes) === 15 && this.ListaDO.length<=0" class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            
                                <h1 style="text-align:center">Aún no tienes registros en Costos Depto. Oper.</h1>
                            
                            </div>
                        </div>

                        <!---COSTO DEPTO. VEHICULO-->
                        <div  v-if="parseInt(this.Grafica1.Mes) === 16" class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="table-finanzas">
                                    <table class="table-fin-02">
                                        <thead>
                                            
                                            <tr>
                                                <th class="sticky marca"></th>
                                                <th colspan="2" class="text-center">Año Pasado</th>
                                                <th colspan="4" class="text-center blue-03">Año Actual</th>
                                                <th colspan="4" class="text-center blue-02">Mes Actual</th>
                                            </tr>
                                            <tr>
                                                <th class="sticky mediana marca"><b>Descripción</b></th>
                                                <th class="mediana text-center">Actual</th>
                                                <th class="blue-01 mediana text-center">%</th>
                                                <th class="blue-03 mediana text-center">Plan</th>
                                                <th class="blue-03 mediana text-center">%</th>
                                                <th class="blue-03 mediana text-center">Actual</th>
                                                <th class="blue-03 mediana text-center">%</th>
                                                <th class="blue-02 mediana text-center">Plan</th>
                                                <th class="blue-02 mediana text-center">%</th>
                                                <th class="blue-02 mediana text-center">Actual</th>
                                                <th class="blue-02 mediana text-center">%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="stickyNegro" ><b>Facturacion</b></td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="AnioAnterior2" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="AnioAnteriorPlanGA2">     </Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="AnioActualPlan2"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="AnioActualPlanPorcen2"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="AnioActualGA2"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="AnioActualPorcenGA2"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="PlanMes2"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="PlanMesPorcen2"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="MesActual2"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="MesActualPorce2"></Cporcentaje> </td>
                                            
                                            </tr>
                                            <tr>
                                                <td height="1" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                            </tr>
                                            <tr v-for="(item,index) in ListaCVO" :key="index">
                                                
                                                <td :class="{ 'stickyNegro': index == 0, 'sticky': index > 0 }" ><b>{{item.Descripcion}}</b></td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="item.AnioAnterior"></Cmoneda>   </td>
                                                <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.AnioAnteriorPlan"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal"  v-model="item.PlanAnual"></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.PlanAnualPorcen"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal"  v-model="item.AnioActual"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.AnioActualProcen"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="item.PlanMes"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.PlanMesPorcen"></Cporcentaje> </td>
                                                <td><Cmoneda  :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="item.MesActual"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"   v-model="item.MesActualPorce"></Cporcentaje> </td>
                                            </tr>
                                            
                                        </tbody> 
                                        <tfoot>
                                            <tr>
                                                <td class="color-01 bold sticky marca">TOTALES</td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp1" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="Gp2" ></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp3" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="Gp4" ></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp5" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1" v-model="Gp6"  ></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp7" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="Gp8" ></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp9" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="Gp10" ></Cporcentaje></td>
                                            </tr>
                                        </tfoot>      
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div  v-if="parseInt(this.Grafica1.Mes) === 16 && this.ListaCVO.length<=0" class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            
                                <h1 style="text-align:center">Aún no tienes registros en Costo Vehículo Oper.</h1>
                            
                            </div>
                        </div>

                        <!---COSTO FINANCIEROS-->
                        <div  v-if="parseInt(this.Grafica1.Mes) === 17" class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="table-finanzas">
                                    <table class="table-fin-02">
                                        <thead>
                                            
                                            <tr>
                                                <th class="sticky marca"></th>
                                                <th colspan="2" class="text-center">Año Pasado</th>
                                                <th colspan="4" class="text-center blue-03">Año Actual</th>
                                                <th colspan="4" class="text-center blue-02">Mes Actual</th>
                                            </tr>
                                            <tr>
                                                <th class="sticky mediana marca"><b>Descripción</b></th>
                                                <th class="mediana text-center">Actual</th>
                                                <th class="blue-01 mediana text-center">%</th>
                                                <th class="blue-03 mediana text-center">Plan</th>
                                                <th class="blue-03 mediana text-center">%</th>
                                                <th class="blue-03 mediana text-center">Actual</th>
                                                <th class="blue-03 mediana text-center">%</th>
                                                <th class="blue-02 mediana text-center">Plan</th>
                                                <th class="blue-02 mediana text-center">%</th>
                                                <th class="blue-02 mediana text-center">Actual</th>
                                                <th class="blue-02 mediana text-center">%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="stickyNegro" ><b>Facturacion</b></td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="AnioAnterior2" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="AnioAnteriorPlanGA2">     </Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="AnioActualPlan2"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="AnioActualPlanPorcen2"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="AnioActualGA2"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="AnioActualPorcenGA2"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="PlanMes2"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="PlanMesPorcen2"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="MesActual2"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"   v-model="MesActualPorce2"></Cporcentaje> </td>
                                            
                                            </tr>
                                            <tr>
                                                <td height="1" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                                <td height="0" class="marcan" ></td>
                                            </tr>
                                            <tr v-for="(item,index) in ListaCF" :key="index" >
                                                
                                                <td :class="{ 'stickyNegro': index == 0, 'sticky': index > 0 }" ><b>{{item.Descripcion}}</b></td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="item.AnioAnterior"></Cmoneda>   </td>
                                                <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.AnioAnteriorPlan"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal"  v-model="item.PlanAnual"></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.PlanAnualPorcen"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal"  v-model="item.AnioActual"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.AnioActualProcen"></Cporcentaje> </td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="item.PlanMes"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"    v-model="item.PlanMesPorcen"></Cporcentaje> </td>
                                                <td><Cmoneda  :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="Decimal" v-model="item.MesActual"></Cmoneda> </td>
                                                <td><Cporcentaje :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="1"   v-model="item.MesActualPorce"></Cporcentaje> </td>
                                            </tr>
                                            
                                        </tbody>    
                                        <tfoot>
                                            <tr>
                                                <td class="color-01 bold sticky marca">TOTALES</td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp1" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="Gp2" ></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp3" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="Gp4" ></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp5" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1" v-model="Gp6"  ></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp7" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="Gp8" ></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="$" separator="," :precision="Decimal" v-model="Gp9" ></Cmoneda></td>
                                                <td><Cporcentaje :activo="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  :readonly="true" :minus="true"  currency="%" separator="," :precision="1"  v-model="Gp10" ></Cporcentaje></td>
                                            </tr>
                                        </tfoot>    
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div  v-if="parseInt(this.Grafica1.Mes) === 17 && this.ListaCF.length<=0" class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            
                                <h1 style="text-align:center">Aún no tienes registros en Costos Financieros.</h1>
                            
                            </div>
                        </div>

                        
                        
                    </div>
                </b-overlay>
            </div>
        </div>
    </section>

    <Ccliente :TipoModal='1'></Ccliente>

  </div>
</template>
<script>
import Cmoneda from "@/components/Cmoneda";
import Cporcentaje from "@/components/Cporcentaje";
import Cbtnsave from '@/components/Cbtnsave.vue'
import Cvalidation from '@/components/Cvalidation.vue'
import Modal from '@/components/Cmodal.vue';
import Clist from '@/components/Clist.vue';
import Ccliente from '@/components/Ccliente.vue'

import Plan from '@/views/modulos/finanzas/planventas/Plan.vue'

export default {
    props:['Id'],
    components:{
        Plan,
		Cmoneda,
		Cporcentaje
    },
    data() {
        return {
            valorcero:0,
            ListaAnios:[],
            planventas:{},
            ListaServicios:[],
            ListaDetalle:[],
            ListaGA:[],
            ListaDV:[],
            ListaDO:[],
            ListaCVO:[],
            ListaCF:[],
            ListaSuma2:[],
            ListaFactura:[],
            ListaTipoServicio:[],
            Head:{
                Title:'Estado Financiero General',
                BtnNewShow:false,
                BtnNewName:'Nuevo',
                isreturn:true,
                isModal:false,
                isEmit:false,
                Url:'MenusFinanzas',
                ObjReturn:'',
            },
            IdConfigS:6,
            IdTipoSubservicio:'',
            Anio:2022,
            Mes:1,

            UnoOp:'',
            DosOp:'',
            TresOp:'',
            CuatroOp:'',
            CincoOp:'',
            SeisOp:'',
            SieteOp:'',
            OchoOp:'',
            NueveOp:'',
            DiesOp:'',

            UnoGp:'',
            DosGp:'',
            TresGp:'',
            CuatroGp:'',
            CincoGp:'',
            SeisGp:'',
            SieteGp:'',
            OchoGp:'',
            NueveGp:'',
            DiesGp:'',

            ga1:'',
            ga2:'',
            ga3:'',
            ga4:'',
            ga5:'',
            ga6:'',
            ga7:'',
            ga8:'',
            ga9:'',
            ga10:'',

            cv1:'',
            cv2:'',
            cv3:'',
            cv4:'',
            cv5:'',
            cv6:'',
            cv7:'',
            cv8:'',
            cv9:'',
            cv10:'',
            DiesDV:'',

            UnoCO:'',
            DosCO:'',
            TresCO:'',
            CuatroCO:'',
            CincoCO:'',
            SeisCO:'',
            SieteCO:'',
            OchoCO:'',
            NueveCO:'',
            DiesCO:'',

            UnoCV:'',
            DosCV:'',
            TresCV:'',
            CuatroCV:'',
            CincoCV:'',
            SeisCV:'',
            SieteCV:'',
            OchoCV:'',
            NueveCV:'',
            DiesCV:'',

            ie1:'',
            ie2:'',
            ie3:'',
            ie4:'',
            ie5:'',
            ie6:'',
            ie7:'',
            ie8:'',
            ie9:'',
            ie10:'',

            vb1:'',
            vb2:'',
            vb3:'',
            vb4:'',
            vb5:'',
            vb6:'',
            vb7:'',
            vb8:'',
            vb9:'',
            vb10:'',

            vv1:'',
            vv2:'',
            vv3:'',
            vv4:'',
            vv5:'',
            vv6:'',
            vv7:'',
            vv8:'',
            vv9:'',
            vv10:'',

			varianzaManoObra1:'',
            varianzaManoObra2:'',
            varianzaManoObra3:'',
            varianzaManoObra4:'',
            varianzaManoObra5:'',
            varianzaManoObra6:'',
            varianzaManoObra7:'',
            varianzaManoObra8:'',
            varianzaManoObra9:'',
            varianzaManoObra10:'',

			varianzaAlmacen1:'',
            varianzaAlmacen2:'',
            varianzaAlmacen3:'',
            varianzaAlmacen4:'',
            varianzaAlmacen5:'',
            varianzaAlmacen6:'',
            varianzaAlmacen7:'',
            varianzaAlmacen8:'',
            varianzaAlmacen9:'',
            varianzaAlmacen10:'',

            UnoNP:'',
            DosNP:'',
            TresNP:'',
            CuatroNP:'',
            CincoNP:'',
            SeisNP:'',
            SieteNP:'',
            OchoNP:'',
            NueveNP:'',
            DiesNP:'',
            Disabled:false,
            Decimal:1,
            //clientes
            cliente:{Nombre:''},
            ListaNumc:[],
            IdContrato:'',
            loading:true,

            //PARRA FILTRO 
            AnioAnteriorMontoFact:'',
            AnioAnteriorPorcenFact:'',
            AnioActualPlanFact:'',
            AnioActualPlanPorcentFact:'',
            AnioActualMontoFact:'',
            AnioActualPorcenFact:'',
            MesActualPlanFact:'',
            MesActualPlanPorcenFact:'',
            MesActualMontoFact:'',
            MesActualPorcenFact:'',

            AnioAnterior:'',
            AnioAnteriorPlanGA:'',
            AnioActualPlan:'',
            AnioActualPlanPorcen:'',
            AnioActualGA:'',
            AnioActualPorcenGA:'',
            PlanMes:'',
            PlanMesPorcen:'',
            MesActual:'',
            MesActualPorce:'',

            AnioAnterior2:'',
            AnioAnteriorPlanGA2:'',
            AnioActualPlan2:'',
            AnioActualPlanPorcen2:'',
            AnioActualGA2:'',
            AnioActualPorcenGA2:'',
            PlanMes2:'',
            PlanMesPorcen2:'',
            MesActual2:'',
            MesActualPorce2:'',

            Gp1:0,
            Gp2:0,
            Gp3:0,
            Gp4:0,
            Gp5:0,
            Gp6:0,
            Gp7:0,
            Gp8:0,
            Gp9:0,
            Gp10:0,
            //FIN FILTRO
            TotalMeses:0, 
            TotalMeses2:0,

            Grafica1: {
				Mes: ""
			},
            isOverlay: true,
            Pdf:0,
            COAnioAnteriorPorcen:0,
            COAnioActualPlanPorcen:0,
            COAnioActualPorcenValor:0,
            COMesActualPlanPorcen:0,
            COMesActualPorcen:0,

            GPAnioAnteriorPorcen:0,
            GPAnioActualPlanPorcen:0,
            GPAnioActualPorcen:0,
            GPMesActualPlanPorcen:0,
            GPMesActualPorcen:0
        }
    },
    methods: {
        ValidateFondo(item,index){
            let fondo = '';
            if(index == 0){
                fondo = 'border-bottom: 7px solid  #9B9B9B;';
                //fondo = 'border: px solid #413F3F; border-radius: 1px; background-color: #9B9B9B;';
            }
            return fondo;
        },
        Menu(){
            this.$router.push({name:'MenusFinanzas', params: {}});
        },
        async ListaCliente()
        {
            this.bus.$emit('ListCcliente');
        },
        get_listdata(){

            this.loading =true;
            this.isOverlay = true;
            var url ='estadosFinancieros/get';

            if (this.IdConfigS==6)
            {
                var url ='estadosFinancieros/getTodos';
            }

            if (this.IdConfigS >0 )
            {
                this.$http.get('financieroantiguo/get',
                {
                    params:{
                        IdConfigS:'',IdTipoServ:'',Anio:this.Anio,Mes:this.Mes,IdContrato:'',Tipo:2

                        // IdConfigS:this.IdConfigS,
                        // IdTipoServ:this.IdTipoSubservicio,
                        // Anio:this.Anio,
                        // Mes:this.Mes,
                        // IdCliente:this.cliente.IdCliente,
                        // IdClienteS:this.cliente.IdClienteS,
                        // IdContrato:this.IdContrato,
                        // Tipo:2
                    }
                }).then( (res) => {
                    
                    this.ListaDetalle=res.data.data.row;
                    const valores =  res.data.data.rowconfig;

                    this.Pdf=6;

                    this.AnioAnterior2 =this.ListaDetalle[0].AnioAnteriorMonto; 
                    this.AnioAnteriorPlanGA2 =this.ListaDetalle[0].AnioAnteriorPorcen; 
                    this.AnioActualPlan2 =this.ListaDetalle[0].AnioActualPlan; 
                    this.AnioActualPlanPorcen2 =this.ListaDetalle[0].AnioActualPlanPorcent; 
                    this.AnioActualGA2 =this.ListaDetalle[0].AnioActualMonto; 
                    this.AnioActualPorcenGA2 =this.ListaDetalle[0].AnioActualPorcen; 
                    this.PlanMes2 =this.ListaDetalle[0].MesActualPlan; 
                    this.PlanMesPorcen2 =this.ListaDetalle[0].MesActualPlanPorcen; 
                    this.MesActual2 =this.ListaDetalle[0].MesActualMonto; 
                    this.MesActualPorce2 =this.ListaDetalle[0].MesActualPorcen; 

                    //Varianza Vehículo
                    this.vv1 = valores.vv1;
                    this.vv2 = valores.vv2;
                    this.vv3 = valores.vv3;
                    this.vv4 = valores.vv4;
                    this.vv5 = valores.vv5;
                    this.vv6 = valores.vv6;
                    this.vv7 = valores.vv7;
                    this.vv8 = valores.vv8;
                    this.vv9 = valores.vv9;
                    this.vv10 = valores.vv10;

					//Varianza Mano Obra
                    this.varianzaManoObra1 = valores.varianzaManoObra1;
                    this.varianzaManoObra2 = valores.varianzaManoObra2;
                    this.varianzaManoObra3 = valores.varianzaManoObra3;
                    this.varianzaManoObra4 = valores.varianzaManoObra4;
                    this.varianzaManoObra5 = valores.varianzaManoObra5;
                    this.varianzaManoObra6 = valores.varianzaManoObra6;
                    this.varianzaManoObra7 = valores.varianzaManoObra7;
                    this.varianzaManoObra8 = valores.varianzaManoObra8;
                    this.varianzaManoObra9 = valores.varianzaManoObra9;
                    this.varianzaManoObra10 = valores.varianzaManoObra10;

					//Varianza Almacén
                    this.varianzaAlmacen1 = valores.varianzaAlmacen1;
                    this.varianzaAlmacen2 = valores.varianzaAlmacen2;
                    this.varianzaAlmacen3 = valores.varianzaAlmacen3;
                    this.varianzaAlmacen4 = valores.varianzaAlmacen4;
                    this.varianzaAlmacen5 = valores.varianzaAlmacen5;
                    this.varianzaAlmacen6 = valores.varianzaAlmacen6;
                    this.varianzaAlmacen7 = valores.varianzaAlmacen7;
                    this.varianzaAlmacen8 = valores.varianzaAlmacen8;
                    this.varianzaAlmacen9 = valores.varianzaAlmacen9;
                    this.varianzaAlmacen10 = valores.varianzaAlmacen10;

                    //Varianza Burden
                    this.vb1 = valores.vb1;
                    this.vb2 = valores.vb2;
                    this.vb3 = valores.vb3;
                    this.vb4 = valores.vb4;
                    this.vb5 = valores.vb5;
                    this.vb6 = valores.vb6;
                    this.vb7 = valores.vb7;
                    this.vb8 = valores.vb8;
                    this.vb9 = valores.vb9;
                    this.vb10 = valores.vb10;

                    //Ingresos y Egresos
                    this.ie1 =  valores.ie1;
                    this.ie2 =  valores.ie2;
                    this.ie3 =  valores.ie3;
                    this.ie4 =  valores.ie4;
                    this.ie5 =  valores.ie5;
                    this.ie6 =  valores.ie6;
                    this.ie7 =  valores.ie7;
                    this.ie8 =  valores.ie8;
                    this.ie9 =  valores.ie9;
                    this.ie10 =  valores.ie10;

                    //Costos G & A
                    this.ga1 = valores.ga1;
                    this.ga2 = valores.ga2;
                    this.ga3 = valores.ga3;
                    this.ga4 = valores.ga4;
                    this.ga5 = valores.ga5;
                    this.ga6 = valores.ga6;
                    this.ga7 = valores.ga7;
                    this.ga8 = valores.ga8;
                    this.ga9 = valores.ga9;
                    this.ga10 = valores.ga10;

                    //VALIDACIONES COSTO OP
                        if(valores.COAnioAnteriorPorcen>=100){
                            this.COAnioAnteriorPorcen = 100;
                        }else{
                            this.COAnioAnteriorPorcen =valores.COAnioAnteriorPorcen;
                        }

                        if(valores.COAnioActualPlanPorcen>=100){
                            this.COAnioActualPlanPorcen = 100;
                        }else{
                            this.COAnioActualPlanPorcen =valores.COAnioActualPlanPorcen;
                        }

                        if(valores.COAnioActualPorcen>=100){
                            this.COAnioActualPorcenValor = 100;
                        }else{
                            this.COAnioActualPorcenValor =valores.COAnioActualPorcen;
                        }

                        if(valores.COMesActualPlanPorcen>=100){
                            this.COMesActualPlanPorcen = 100;
                        }else{
                            this.COMesActualPlanPorcen =valores.COMesActualPlanPorcen;
                        }

                        if(valores.COMesActualPorcen>=100){
                            this.COMesActualPorcen = 100;
                        }else{
                            this.COMesActualPorcen =valores.COMesActualPorcen;
                        }

                    //TOTAL COSTO OPERACIONAL
                    this.UnoOp      = valores.COAnioAnteriorMonto;
                    // this.DosOp      = valores.COAnioAnteriorPorcen;
                    this.DosOp      = this.COAnioAnteriorPorcen;
                    this.TresOp     = valores.COAnioActualPlan;
                    // this.CuatroOp   = valores.COAnioActualPlanPorcen;
                    this.CuatroOp   = this.COAnioActualPlanPorcen;
                    this.CincoOp    = valores.COAnioActualMonto;
                    // this.SeisOp     = valores.COAnioActualPorcen;
                    this.SeisOp     = this.COAnioActualPorcenValor;
                    this.SieteOp    = valores.COMesActualPlan;
                    // this.OchoOp     = valores.COMesActualPlanPorcen;
                    this.OchoOp     = this.COMesActualPlanPorcen;
                    this.NueveOp    = valores.COMesActualMonto;
                    // this.DiesOp     = valores.COMesActualPorcen;
                    this.DiesOp     =  this.COMesActualPorcen;

                    //VALIDACIONES GROSS PROFIT
                        if(valores.GPAnioAnteriorPorcen>=100){
                            this.GPAnioAnteriorPorcen = 100;
                        }else{
                            this.GPAnioAnteriorPorcen =valores.GPAnioAnteriorPorcen;
                        }

                        if(valores.GPAnioActualPlanPorcen>=100){
                            this.GPAnioActualPlanPorcen = 100;
                        }else{
                            this.GPAnioActualPlanPorcen =valores.GPAnioActualPlanPorcen;
                        }

                        if(valores.GPAnioActualPorcen>=100){
                            this.GPAnioActualPorcen = 100;
                        }else{
                            this.GPAnioActualPorcen =valores.GPAnioActualPorcen;
                        }

                        if(valores.GPMesActualPlanPorcen>=100){
                            this.GPMesActualPlanPorcen = 100;
                        }else{
                            this.GPMesActualPlanPorcen =valores.GPMesActualPlanPorcen;
                        }

                        if(valores.GPMesActualPorcen>=100){
                            this.GPMesActualPorcen = 100;
                        }else{
                            this.GPMesActualPorcen =valores.GPMesActualPorcen;
                        }


                    //GROSS PROFIT
                    this.UnoGp      = valores.GPAnioAnteriorMonto;
                    // this.DosGp      = valores.GPAnioAnteriorPorcen;
                    this.DosGp      = this.GPAnioAnteriorPorcen;
                    this.TresGp     = valores.GPAnioActualPlan;
                    // this.CuatroGp   = valores.GPAnioActualPlanPorcen;
                    this.CuatroGp   =  this.GPAnioActualPlanPorcen;
                    this.CincoGp    = valores.GPAnioActualMonto;
                    // this.SeisGp     = valores.GPAnioActualPorcen;
                    this.SeisGp     = this.GPAnioActualPorcen;
                    this.SieteGp    = valores.GPMesActualPlan;
                    // this.OchoGp     = valores.GPMesActualPlanPorcen;
                    this.OchoGp     =  this.GPMesActualPlanPorcen;
                    this.NueveGp    = valores.GPMesActualMonto;
                    this.DiesGp     = this.GPMesActualPorcen;

                    //Costos Depto. Vent
                    this.UnoDV      = valores.cv1;
                    this.DosDV      = valores.cv2;
                    this.TresDV     = valores.cv3;
                    this.CuatroDV   = valores.cv4;
                    this.CincoDV    = valores.cv5;
                    this.SeisDV     = valores.cv6;
                    this.SieteDV    = valores.cv7;
                    this.OchoDV     = valores.cv8;
                    this.NueveDV    = valores.cv9;
                    this.DiesDV     = valores.cv10;

                    this.np1 = valores.np1;
                    this.np2 = valores.np2;
                    this.np3 = valores.np3;
                    this.np4 = valores.np4;
                    this.np5 = valores.np5;
                    this.np6 = valores.np6;
                    this.np7 = valores.np7;
                    this.np8 = valores.np8;
                    this.np9 = valores.np9;
                    this.np10 = valores.np10;
                    this.Disabled=false;
                    this.loading = false;
                }).catch((e) => {
                    this.isOverlay = false;
                }).finally(()=>{
                    this.isOverlay = false;
                });
            }
        },

        get_CostoDeptVenta(){
            this.loading =true;
            this.isOverlay = true;
            this.$http.get('CostoDeptoVenta/get',
                {
                    params:{
                        Anio:this.Anio,
                        Mes:parseInt(this.Mes + 1),
                        Anio2:this.Anio,
                        Mes2:this.Mes ,
                    }
                }).then( (res) => {
                    this.ListaDV =  res.data.data.CostoDV;
                    const DV =  res.data.data.CostoDV;

                    this.Pdf=1;

                    this.AnioAnterior = DV.AnioAnteriorGA;
                    this.AnioAnteriorGA = DV.AnioAnteriorPlanGA;
                    this.AnioAnteriorPlanGA = DV.AnioActualPlan;
                    this.AnioActualPlan = DV.AnioActualPlanPorcen;
                    this.AnioActualPlanPorcen = DV.AnioActualGA;
                    this.AnioActualGA = DV.AnioActualPorcenGA;
                    this.AnioActualPorcenGA = DV.PlanMes;
                    this.PlanMes = DV.PlanMesPorcen;
                    this.PlanMesPorcen = DV.MesActual;
                    this.MesActual = DV.MesActualPorce;
                }).catch((e) => {
                    this.isOverlay = false;
                }).finally(()=>{
                    this.isOverlay = false;
                });
        },
        get_CostoDeptOpera(){
            this.loading =true;
            this.isOverlay = true;
            this.$http.get('nuevo/get',
                {
                    params:{
                        Anio:this.Anio,
                        Mes:parseInt(this.Mes + 1),
                        Anio2:this.Anio,
                        Mes2:this.Mes ,
                    }
                }).then( (res) => {
                    this.ListaDO =  res.data.data.CostoDO;
                    const DO =  res.data.data.CostoDO;

                    this.Pdf=3;
                   
                    this.AnioAnterior = DO.AnioAnteriorGA;
                    this.AnioAnteriorGA = DO.AnioAnteriorPlanGA;
                    this.AnioAnteriorPlanGA = DO.AnioActualPlan;
                    this.AnioActualPlan = DO.AnioActualPlanPorcen;
                    this.AnioActualPlanPorcen = DO.AnioActualGA;
                    this.AnioActualGA = DO.AnioActualPorcenGA;
                    this.AnioActualPorcenGA = DO.PlanMes;
                    this.PlanMes = DO.PlanMesPorcen;
                    this.PlanMesPorcen = DO.MesActual;
                    this.MesActual = DO.MesActualPorce;
                }).catch((e) => {
                    this.isOverlay = false;
                }).finally(()=>{
                    this.isOverlay = false;
                });
        },

        get_CostoVehiculoOpera(){
            this.loading =true;
            this.isOverlay = true;
            this.$http.get('CostoDeptoVehiculo/get',
                {
                    params:{
                        Anio:this.Anio,
                        Mes:parseInt(this.Mes + 1),
                        Anio2:this.Anio,
                        Mes2:this.Mes ,
                    }
                }).then( (res) => {
                    this.ListaCVO =  res.data.data.CostoV;
                    const CVO =  res.data.data.CostoV;

                    this.Pdf=5
                    
                    this.AnioAnterior = CVO.AnioAnteriorGA;
                    this.AnioAnteriorGA = CVO.AnioAnteriorPlanGA;
                    this.AnioAnteriorPlanGA = CVO.AnioActualPlan;
                    this.AnioActualPlan = CVO.AnioActualPlanPorcen;
                    this.AnioActualPlanPorcen = CVO.AnioActualGA;
                    this.AnioActualGA = CVO.AnioActualPorcenGA;
                    this.AnioActualPorcenGA = CVO.PlanMes;
                    this.PlanMes = CVO.PlanMesPorcen;
                    this.PlanMesPorcen = CVO.MesActual;
                    this.MesActual = CVO.MesActualPorce;
                }).catch((e) => {
                    this.isOverlay = false;
                }).finally(()=>{
                    this.isOverlay = false;
                });
        },

        get_CostsFinancieros(){
            this.loading =true;
            this.isOverlay = true;
            this.$http.get('CostoFinanciero/get',
                {
                    params:{
                        Anio:this.Anio,
                        Mes:parseInt(this.Mes + 1),
                        Anio2:this.Anio,
                        Mes2:this.Mes ,
                    }
                }).then( (res) => {
                    this.ListaCF =  res.data.data.CostoF;
                    
                    const CF =  res.data.data.CostoF;

                    this.Pdf=4;

                    this.AnioAnterior = CF.AnioAnteriorGA;
                    this.AnioAnteriorGA = CF.AnioAnteriorPlanGA;
                    this.AnioAnteriorPlanGA = CF.AnioActualPlan;
                    this.AnioActualPlan = CF.AnioActualPlanPorcen;
                    this.AnioActualPlanPorcen = CF.AnioActualGA;
                    this.AnioActualGA = CF.AnioActualPorcenGA;
                    this.AnioActualPorcenGA = CF.PlanMes;
                    this.PlanMes = CF.PlanMesPorcen;
                    this.PlanMesPorcen = CF.MesActual;
                    this.MesActual = CF.MesActualPorce;
                }).catch((e) => {
                    this.isOverlay = false;
                }).finally(()=>{
                    this.isOverlay = false;
                });
        },

        get_EstadoFinGenFiltro(){
            if(parseInt(this.Grafica1.Mes) === 13){
                this.loading =true;
                this.isOverlay = true;
                this.$http.get('CostoGA/get',
                {
                    params:{
                        Anio:this.Anio,
                        Mes:parseInt(this.Mes + 1),
                        Anio2:this.Anio,
                        Mes2:this.Mes ,
                    }
                }).then( (res) => {
                    this.ListaGA    =  res.data.data.CostoGA;
                    this.ListaSuma2 =  res.data.data.SumaAnioActual;

                    this.Pdf=2;

                    const Suma =  res.data.data.SumaAnioActual;
                    this.AnioAnteriorMontoFact = Suma.AnioActualMonto2;
                    this.AnioAnteriorPorcenFact = Suma.PorcentAnioActual2;
                   
                    const GA =  res.data.data.CostoGA;
                    
                    

                    this.AnioAnterior = GA.AnioAnteriorGA;
                    this.AnioAnteriorGA = GA.AnioAnteriorPlanGA;
                    this.AnioAnteriorPlanGA = GA.AnioActualPlan;
                    this.AnioActualPlan = GA.AnioActualPlanPorcen;
                    this.AnioActualPlanPorcen =this.ListaSuma2[0].AnioActualMonto2;
                    this.AnioActualGA = Suma.PorcentAnioActual2;
                    this.AnioActualPorcenGA = GA.PlanMes;
                    this.PlanMes = GA.PlanMesPorcen;
                    this.PlanMesPorcen = GA.MesActual;
                    this.MesActual = GA.MesActualPorce;
                    
                    
                   
                }).catch((e) => {
                    this.isOverlay = false;
                }).finally(()=>{
                    this.isOverlay = false;
                });
            }else if(parseInt(this.Grafica1.Mes) === 14){
                this.get_CostoDeptVenta();
            }else if(parseInt(this.Grafica1.Mes) === 15){
                this.get_CostoDeptOpera();
            }else if(parseInt(this.Grafica1.Mes) === 16){
                this.get_CostoVehiculoOpera();
            }else if(parseInt(this.Grafica1.Mes) === 17){
                this.get_CostsFinancieros();
            }
            else{
                this.get_listdata();
            }

        },

        async Anios() {
			await this.$http.get("funciones/getanios").then(res => {
				this.ListaAnios = res.data.ListaAnios;
				this.Grafica1.Mes = 12;
                this.get_EstadoFinGenFiltro();
				
			});
		},

        

        SeleccionarCliente(objeto)
        {
            this.cliente=objeto;
            this.get_listdata();
            this.ListaNumContrato();
        },
        async ListaNumContrato()
        {
            await this.$http.get(
                'numcontrato/get',
                {
                    params:{IdClienteS:this.cliente.IdClienteS}
                }
            ).then( (res) => {
                this.ListaNumc =res.data.data.row;
            });
        },
        Limpiar()
        {
            this.IdConfigS=1;
            this.IdTipoSubservicio="";
            //this.Anio=;
            //this.Mes="";
            this.cliente={Nombre:''};
            this.IdContrato="";
            this.get_EstadoFinGenFiltro();
        },
        Descargar()
        {
            if(this.Pdf==6){
                var url="estadofinangral";

                this.$http.get('reporte/'+url,
                {
                    responseType: 'arraybuffer',
                    params :{
                        IdConfigS:this.IdConfigS,
                        IdTipoServ:this.IdTipoSubservicio,
                        Anio:this.Anio,
                        Mes:this.Mes,
                        IdCliente:this.cliente.IdCliente,
                        IdClienteS:this.cliente.IdClienteS,
                        IdContrato:this.IdContrato,
                        Tipo:2
                    }
                }).then( (response) => {
                    let pdfContent = response.data;
                    let file = new Blob([pdfContent], { type: 'application/pdf' });
                    var fileUrl = URL.createObjectURL(file);
                    window.open(fileUrl);
                });
            }else if(this.Pdf==1){
                var url="CostoDeptoVenta";

                this.$http.get('reporte/'+url,
                {
                    responseType: 'arraybuffer',
                    params :{
                        Anio:this.Anio,
                        Mes:parseInt(this.Mes + 1),
                        Anio2:this.Anio,
                        Mes2:this.Mes ,
                    }
                }).then( (response) => {
                    let pdfContent = response.data;
                    let file = new Blob([pdfContent], { type: 'application/pdf' });
                    var fileUrl = URL.createObjectURL(file);
                    window.open(fileUrl);
                });
            }else if(this.Pdf==2){
                var url="CostoGA";

                this.$http.get('reporte/'+url,
                {
                    responseType: 'arraybuffer',
                    params :{
                        Anio:this.Anio,
                        Mes:parseInt(this.Mes + 1),
                        Anio2:this.Anio,
                        Mes2:this.Mes ,
                    }
                }).then( (response) => {
                    let pdfContent = response.data;
                    let file = new Blob([pdfContent], { type: 'application/pdf' });
                    var fileUrl = URL.createObjectURL(file);
                    window.open(fileUrl);
                });
            }else if(this.Pdf==3){
                var url="DeptoOper";

                this.$http.get('reporte/'+url,
                {
                    responseType: 'arraybuffer',
                    params :{
                        Anio:this.Anio,
                        Mes:parseInt(this.Mes + 1),
                        Anio2:this.Anio,
                        Mes2:this.Mes ,
                    }
                }).then( (response) => {
                    let pdfContent = response.data;
                    let file = new Blob([pdfContent], { type: 'application/pdf' });
                    var fileUrl = URL.createObjectURL(file);
                    window.open(fileUrl);
                });
            }else if(this.Pdf==4){
                var url="CostoFinan";

                this.$http.get('reporte/'+url,
                {
                    responseType: 'arraybuffer',
                    params :{
                        Anio:this.Anio,
                        Mes:parseInt(this.Mes + 1),
                        Anio2:this.Anio,
                        Mes2:this.Mes ,
                    }
                }).then( (response) => {
                    let pdfContent = response.data;
                    let file = new Blob([pdfContent], { type: 'application/pdf' });
                    var fileUrl = URL.createObjectURL(file);
                    window.open(fileUrl);
                });
            }else if(this.Pdf==5){
                var url="CostoVehiculoGen";

                this.$http.get('reporte/'+url,
                {
                    responseType: 'arraybuffer',
                    params :{
                        Anio:this.Anio,
                        Mes:parseInt(this.Mes + 1),
                        Anio2:this.Anio,
                        Mes2:this.Mes ,
                    }
                }).then( (response) => {
                    let pdfContent = response.data;
                    let file = new Blob([pdfContent], { type: 'application/pdf' });
                    var fileUrl = URL.createObjectURL(file);
                    window.open(fileUrl);
                });
            }
            
        },

        PDF2022(){
             this.$http.get('reporte/estadofinan2022',
            {
                
                responseType: 'arraybuffer',
                params :{
                }
            }).then( (response) => {
                let pdfContent = response.data;
                let file = new Blob([pdfContent], { type: 'application/pdf' });
                var fileUrl = URL.createObjectURL(file);
                window.open(fileUrl);
            });
        },
        get_anios(){
            this.Disabled=true;
            this.$http.get(
            'funciones/getanios',
            {
                params:{}
            }
            ).then( (res) => {
                this.ListaAnios=res.data.ListaAnios;
                this.Anio=res.data.AnioActual;
                this.Mes= parseInt(res.data.MesActual - 1);
                // this.get_listdata();
                this.get_EstadoFinGenFiltro()
            });
            


            
        },
    },
    created() {
        this.get_anios();
        this.Anios();
        
        this.bus.$off('Regresar');
        this.bus.$on('Regresar',()=>
        {
            this.$router.push({name:'SubMenusFinanzas'});
        });

        this.bus.$off('SeleccionarCliente');
        this.bus.$on('SeleccionarCliente',(oSucursal)=>
        {
           this.SeleccionarCliente(oSucursal);
        });
    },
    mounted() {
    },
    computed: {
        calcularporcentajeanioactual(){
            if(!this.loading)
            {
                this.ListaDetalle.forEach(element => {
                    if(this.ListaDetalle[0].ColocarAnioActual > 0)
                    {
                        element.PorcentajeAnio = (element.ColocarAnioActual*100/this.ListaDetalle[0].ColocarAnioActual);
                    }
                    else
                    {
                        element.PorcentajeAnio = 0;  
                    }
                });
            }
        },

        calcularPorcentajesGA(){
            var Uno=0;
            var Dos=0;
            var Tres=0;
            var Cuatro=0;
            var Cinco=0;
            var Seis=0;
            var Siete=0;
            var Ocho=0;
            var Nueve=0;
            var Diez=0;

            var Once=0;
            var Doce=0;
            var Trece=0;
            var Catorce=0; 
            var Quince=0; 
            var Dieciseis=0;
            var Diecisiete=0;
            var Dieciocho=0;

            var Diecinueve=0;
            var Veinte=0; 
            var veintiuno=0;
            var veintidos=0;
            var veintitres=0;
            var veinticuatro=0;
            var veinticinco=0;
            var veintiseis=0;
            var veintisiete=0;
            var veintiocho=0;
            var veintinueve=0;
            var treinta=0;
            var treintauno=0;
            var treintados=0;
            var treintatres=0;
            var treintacuatro=0;
            var treintacinco=0;
            var treintaseis=0;
            var treintasiete=0;
            var treintaocho=0;
            var treintanueve=0;
            var cuarenta=0;

            for(var i =0;i<this.ListaGA.length;i++ ){
                if(i>0){
                    
                    if(this.ListaGA[i].AnioAnterior!=''){
                        Uno=parseFloat(this.ListaGA[0].AnioAnterior);
                        Dos+= parseFloat(this.ListaGA[i].AnioAnterior);
                        Tres= Uno+Dos;
                    }
                    Cuatro=Tres;

                    if(this.ListaGA[i].AnioAnteriorPlan!=''){
                        Cinco=parseFloat(this.ListaGA[0].AnioAnteriorPlan);
                        Seis+= parseFloat(this.ListaGA[i].AnioAnteriorPlan);
                        Siete= Cinco+Seis;
                    }
                    Ocho=Siete;

                    if(this.ListaGA[i].PlanAnual!=''){
                        Nueve=parseFloat(this.ListaGA[0].PlanAnual);
                        Diez+= parseFloat(this.ListaGA[i].PlanAnual);
                        Once= Nueve+Diez;
                    }
                    Doce=Once;

                    if(this.ListaGA[i].PlanAnualPorcen!=''){
                        Trece=parseFloat(this.ListaGA[0].PlanAnualPorcen);
                        Catorce+= parseFloat(this.ListaGA[i].PlanAnualPorcen);
                        Quince= Trece+Catorce;
                    }
                    Dieciseis=Quince;

                    if(this.ListaGA[i].AnioActual!=''){
                        Diecisiete=parseFloat(this.ListaGA[0].AnioActual);
                        Dieciocho+= parseFloat(this.ListaGA[i].AnioActual);
                        Diecinueve= Diecisiete+Dieciocho;
                    }
                    Veinte=Diecinueve;

                    if(this.ListaGA[i].AnioActualProcen!=''){
                        veintiuno=parseFloat(this.ListaGA[0].AnioActualProcen);
                        veintidos+= parseFloat(this.ListaGA[i].AnioActualProcen);
                        veintitres= veintiuno+veintidos;
                    }
                    veinticuatro=veintitres;

                    if(this.ListaGA[i].PlanMes!=''){
                        veinticinco=parseFloat(this.ListaGA[0].PlanMes);
                        veintiseis+= parseFloat(this.ListaGA[i].PlanMes);
                        veintisiete= veinticinco+veintiseis;
                    }
                    veintiocho=veintisiete;

                    if(this.ListaGA[i].PlanMesPorcen!=''){
                        veintinueve=parseFloat(this.ListaGA[0].PlanMesPorcen);
                        treinta+= parseFloat(this.ListaGA[i].PlanMesPorcen);
                        treintauno= veintinueve+treinta;
                    }
                    treintados=treintauno;

                    if(this.ListaGA[i].MesActual!=''){
                        treintatres=parseFloat(this.ListaGA[0].MesActual);
                        treintacuatro+= parseFloat(this.ListaGA[i].MesActual);
                        treintacinco= treintatres+treintacuatro;
                    }
                    treintaseis=treintacinco;

                    if(this.ListaGA[i].MesActualPorce!=''){
                        treintasiete=parseFloat(this.ListaGA[0].MesActualPorce);
                        treintaocho+= parseFloat(this.ListaGA[i].MesActualPorce);
                        treintanueve= treintasiete+treintaocho;
                    }
                    cuarenta=treintanueve;
                }
            }
            this.Gp1=Cuatro;
            this.Gp2=Ocho;
            this.Gp3=Doce;
            this.Gp4=Dieciseis;
            this.Gp5=Veinte;
            this.Gp6=veinticuatro;
            this.Gp7=veintiocho;
            this.Gp8=treintados;
            this.Gp9=treintaseis;
            this.Gp10=cuarenta;


            
        },

        calcularPorcentajesDeptoV(){
            var Uno=0;
            var Dos=0;
            var Tres=0;
            var Cuatro=0;
            var Cinco=0;
            var Seis=0;
            var Siete=0;
            var Ocho=0;
            var Nueve=0;
            var Diez=0;

            var Once=0;
            var Doce=0;
            var Trece=0;
            var Catorce=0; 
            var Quince=0; 
            var Dieciseis=0;
            var Diecisiete=0;
            var Dieciocho=0;

            var Diecinueve=0;
            var Veinte=0; 
            var veintiuno=0;
            var veintidos=0;
            var veintitres=0;
            var veinticuatro=0;
            var veinticinco=0;
            var veintiseis=0;
            var veintisiete=0;
            var veintiocho=0;
            var veintinueve=0;
            var treinta=0;
            var treintauno=0;
            var treintados=0;
            var treintatres=0;
            var treintacuatro=0;
            var treintacinco=0;
            var treintaseis=0;
            var treintasiete=0;
            var treintaocho=0;
            var treintanueve=0;
            var cuarenta=0;

            for(var i =0;i<this.ListaDV.length;i++ ){
                if(i>0){
                    
                    if(this.ListaDV[i].AnioAnterior!=''){
                        Uno=parseFloat(this.ListaDV[0].AnioAnterior);
                        Dos+= parseFloat(this.ListaDV[i].AnioAnterior);
                        Tres= Uno+Dos;
                    }
                    Cuatro=Tres;

                    if(this.ListaDV[i].AnioAnteriorPlan!=''){
                        Cinco=parseFloat(this.ListaDV[0].AnioAnteriorPlan);
                        Seis+= parseFloat(this.ListaDV[i].AnioAnteriorPlan);
                        Siete= Cinco+Seis;
                    }
                    Ocho=Siete;

                    if(this.ListaDV[i].PlanAnual!=''){
                        Nueve=parseFloat(this.ListaDV[0].PlanAnual);
                        Diez+= parseFloat(this.ListaDV[i].PlanAnual);
                        Once= Nueve+Diez;
                    }
                    Doce=Once;

                    if(this.ListaDV[i].PlanAnualPorcen!=''){
                        Trece=parseFloat(this.ListaDV[0].PlanAnualPorcen);
                        Catorce+= parseFloat(this.ListaDV[i].PlanAnualPorcen);
                        Quince= Trece+Catorce;
                    }
                    Dieciseis=Quince;

                    if(this.ListaDV[i].AnioActual!=''){
                        Diecisiete=parseFloat(this.ListaDV[0].AnioActual);
                        Dieciocho+= parseFloat(this.ListaDV[i].AnioActual);
                        Diecinueve= Diecisiete+Dieciocho;
                    }
                    Veinte=Diecinueve;

                    if(this.ListaDV[i].AnioActualProcen!=''){
                        veintiuno=parseFloat(this.ListaDV[0].AnioActualProcen);
                        veintidos+= parseFloat(this.ListaDV[i].AnioActualProcen);
                        veintitres= veintiuno+veintidos;
                    }
                    veinticuatro=veintitres;

                    if(this.ListaDV[i].PlanMes!=''){
                        veinticinco=parseFloat(this.ListaDV[0].PlanMes);
                        veintiseis+= parseFloat(this.ListaDV[i].PlanMes);
                        veintisiete= veinticinco+veintiseis;
                    }
                    veintiocho=veintisiete;

                    if(this.ListaDV[i].PlanMesPorcen!=''){
                        veintinueve=parseFloat(this.ListaDV[0].PlanMesPorcen);
                        treinta+= parseFloat(this.ListaDV[i].PlanMesPorcen);
                        treintauno= veintinueve+treinta;
                    }
                    treintados=treintauno;

                    if(this.ListaDV[i].MesActual!=''){
                        treintatres=parseFloat(this.ListaDV[0].MesActual);
                        treintacuatro+= parseFloat(this.ListaDV[i].MesActual);
                        treintacinco= treintatres+treintacuatro;
                    }
                    treintaseis=treintacinco;

                    if(this.ListaDV[i].MesActualPorce!=''){
                        treintasiete=parseFloat(this.ListaDV[0].MesActualPorce);
                        treintaocho+= parseFloat(this.ListaDV[i].MesActualPorce);
                        treintanueve= treintasiete+treintaocho;
                    }
                    cuarenta=treintanueve;
                }
            }
            this.Gp1=Cuatro;
            this.Gp2=Ocho;
            this.Gp3=Doce;
            this.Gp4=Dieciseis;
            this.Gp5=Veinte;
            this.Gp6=veinticuatro;
            this.Gp7=veintiocho;
            this.Gp8=treintados;
            this.Gp9=treintaseis;
            this.Gp10=cuarenta;


            
        },

        calcularPorcentajesDeptoVOpera(){
            var Uno=0;
            var Dos=0;
            var Tres=0;
            var Cuatro=0;
            var Cinco=0;
            var Seis=0;
            var Siete=0;
            var Ocho=0;
            var Nueve=0;
            var Diez=0;

            var Once=0;
            var Doce=0;
            var Trece=0;
            var Catorce=0; 
            var Quince=0; 
            var Dieciseis=0;
            var Diecisiete=0;
            var Dieciocho=0;

            var Diecinueve=0;
            var Veinte=0; 
            var veintiuno=0;
            var veintidos=0;
            var veintitres=0;
            var veinticuatro=0;
            var veinticinco=0;
            var veintiseis=0;
            var veintisiete=0;
            var veintiocho=0;
            var veintinueve=0;
            var treinta=0;
            var treintauno=0;
            var treintados=0;
            var treintatres=0;
            var treintacuatro=0;
            var treintacinco=0;
            var treintaseis=0;
            var treintasiete=0;
            var treintaocho=0;
            var treintanueve=0;
            var cuarenta=0;

            for(var i =0;i<this.ListaDO.length;i++ ){
                if(i>0){
                    
                    if(this.ListaDO[i].AnioAnterior!=''){
                        Uno=parseFloat(this.ListaDO[0].AnioAnterior);
                        Dos+= parseFloat(this.ListaDO[i].AnioAnterior);
                        Tres= Uno+Dos;
                    }
                    Cuatro=Tres;

                    if(this.ListaDO[i].AnioAnteriorPlan!=''){
                        Cinco=parseFloat(this.ListaDO[0].AnioAnteriorPlan);
                        Seis+= parseFloat(this.ListaDO[i].AnioAnteriorPlan);
                        Siete= Cinco+Seis;
                    }
                    Ocho=Siete;

                    if(this.ListaDO[i].PlanAnual!=''){
                        Nueve=parseFloat(this.ListaDO[0].PlanAnual);
                        Diez+= parseFloat(this.ListaDO[i].PlanAnual);
                        Once= Nueve+Diez;
                    }
                    Doce=Once;

                    if(this.ListaDO[i].PlanAnualPorcen!=''){
                        Trece=parseFloat(this.ListaDO[0].PlanAnualPorcen);
                        Catorce+= parseFloat(this.ListaDO[i].PlanAnualPorcen);
                        Quince= Trece+Catorce;
                    }
                    Dieciseis=Quince;

                    if(this.ListaDO[i].AnioActual!=''){
                        Diecisiete=parseFloat(this.ListaDO[0].AnioActual);
                        Dieciocho+= parseFloat(this.ListaDO[i].AnioActual);
                        Diecinueve= Diecisiete+Dieciocho;
                    }
                    Veinte=Diecinueve;

                    if(this.ListaDO[i].AnioActualProcen!=''){
                        veintiuno=parseFloat(this.ListaDO[0].AnioActualProcen);
                        veintidos+= parseFloat(this.ListaDO[i].AnioActualProcen);
                        veintitres= veintiuno+veintidos;
                    }
                    veinticuatro=veintitres;

                    if(this.ListaDO[i].PlanMes!=''){
                        veinticinco=parseFloat(this.ListaDO[0].PlanMes);
                        veintiseis+= parseFloat(this.ListaDO[i].PlanMes);
                        veintisiete= veinticinco+veintiseis;
                    }
                    veintiocho=veintisiete;

                    if(this.ListaDO[i].PlanMesPorcen!=''){
                        veintinueve=parseFloat(this.ListaDO[0].PlanMesPorcen);
                        treinta+= parseFloat(this.ListaDO[i].PlanMesPorcen);
                        treintauno= veintinueve+treinta;
                    }
                    treintados=treintauno;

                    if(this.ListaDO[i].MesActual!=''){
                        treintatres=parseFloat(this.ListaDO[0].MesActual);
                        treintacuatro+= parseFloat(this.ListaDO[i].MesActual);
                        treintacinco= treintatres+treintacuatro;
                    }
                    treintaseis=treintacinco;

                    if(this.ListaDO[i].MesActualPorce!=''){
                        treintasiete=parseFloat(this.ListaDO[0].MesActualPorce);
                        treintaocho+= parseFloat(this.ListaDO[i].MesActualPorce);
                        treintanueve= treintasiete+treintaocho;
                    }
                    cuarenta=treintanueve;
                }
            }
            this.Gp1=Cuatro;
            this.Gp2=Ocho;
            this.Gp3=Doce;
            this.Gp4=Dieciseis;
            this.Gp5=Veinte;
            this.Gp6=veinticuatro;
            this.Gp7=veintiocho;
            this.Gp8=treintados;
            this.Gp9=treintaseis;
            this.Gp10=cuarenta;


            
        },

        calcularPorcentajesVehiculos(){
            var Uno=0;
            var Dos=0;
            var Tres=0;
            var Cuatro=0;
            var Cinco=0;
            var Seis=0;
            var Siete=0;
            var Ocho=0;
            var Nueve=0;
            var Diez=0;

            var Once=0;
            var Doce=0;
            var Trece=0;
            var Catorce=0; 
            var Quince=0; 
            var Dieciseis=0;
            var Diecisiete=0;
            var Dieciocho=0;

            var Diecinueve=0;
            var Veinte=0; 
            var veintiuno=0;
            var veintidos=0;
            var veintitres=0;
            var veinticuatro=0;
            var veinticinco=0;
            var veintiseis=0;
            var veintisiete=0;
            var veintiocho=0;
            var veintinueve=0;
            var treinta=0;
            var treintauno=0;
            var treintados=0;
            var treintatres=0;
            var treintacuatro=0;
            var treintacinco=0;
            var treintaseis=0;
            var treintasiete=0;
            var treintaocho=0;
            var treintanueve=0;
            var cuarenta=0;

            for(var i =0;i<this.ListaCVO.length;i++ ){
                if(i>0){
                    
                    if(this.ListaCVO[i].AnioAnterior!=''){
                        Uno=parseFloat(this.ListaCVO[0].AnioAnterior);
                        Dos+= parseFloat(this.ListaCVO[i].AnioAnterior);
                        Tres= Uno+Dos;
                    }
                    Cuatro=Tres;

                    if(this.ListaCVO[i].AnioAnteriorPlan!=''){
                        Cinco=parseFloat(this.ListaCVO[0].AnioAnteriorPlan);
                        Seis+= parseFloat(this.ListaCVO[i].AnioAnteriorPlan);
                        Siete= Cinco+Seis;
                    }
                    Ocho=Siete;

                    if(this.ListaCVO[i].PlanAnual!=''){
                        Nueve=parseFloat(this.ListaCVO[0].PlanAnual);
                        Diez+= parseFloat(this.ListaCVO[i].PlanAnual);
                        Once= Nueve+Diez;
                    }
                    Doce=Once;

                    if(this.ListaCVO[i].PlanAnualPorcen!=''){
                        Trece=parseFloat(this.ListaCVO[0].PlanAnualPorcen);
                        Catorce+= parseFloat(this.ListaCVO[i].PlanAnualPorcen);
                        Quince= Trece+Catorce;
                    }
                    Dieciseis=Quince;

                    if(this.ListaCVO[i].AnioActual!=''){
                        Diecisiete=parseFloat(this.ListaCVO[0].AnioActual);
                        Dieciocho+= parseFloat(this.ListaCVO[i].AnioActual);
                        Diecinueve= Diecisiete+Dieciocho;
                    }
                    Veinte=Diecinueve;

                    if(this.ListaCVO[i].AnioActualProcen!=''){
                        veintiuno=parseFloat(this.ListaCVO[0].AnioActualProcen);
                        veintidos+= parseFloat(this.ListaCVO[i].AnioActualProcen);
                        veintitres= veintiuno+veintidos;
                    }
                    veinticuatro=veintitres;

                    if(this.ListaCVO[i].PlanMes!=''){
                        veinticinco=parseFloat(this.ListaCVO[0].PlanMes);
                        veintiseis+= parseFloat(this.ListaCVO[i].PlanMes);
                        veintisiete= veinticinco+veintiseis;
                    }
                    veintiocho=veintisiete;

                    if(this.ListaCVO[i].PlanMesPorcen!=''){
                        veintinueve=parseFloat(this.ListaCVO[0].PlanMesPorcen);
                        treinta+= parseFloat(this.ListaCVO[i].PlanMesPorcen);
                        treintauno= veintinueve+treinta;
                    }
                    treintados=treintauno;

                    if(this.ListaCVO[i].MesActual!=''){
                        treintatres=parseFloat(this.ListaCVO[0].MesActual);
                        treintacuatro+= parseFloat(this.ListaCVO[i].MesActual);
                        treintacinco= treintatres+treintacuatro;
                    }
                    treintaseis=treintacinco;

                    if(this.ListaCVO[i].MesActualPorce!=''){
                        treintasiete=parseFloat(this.ListaCVO[0].MesActualPorce);
                        treintaocho+= parseFloat(this.ListaCVO[i].MesActualPorce);
                        treintanueve= treintasiete+treintaocho;
                    }
                    cuarenta=treintanueve;
                }
            }
            this.Gp1=Cuatro;
            this.Gp2=Ocho;
            this.Gp3=Doce;
            this.Gp4=Dieciseis;
            this.Gp5=Veinte;
            this.Gp6=veinticuatro;
            this.Gp7=veintiocho;
            this.Gp8=treintados;
            this.Gp9=treintaseis;
            this.Gp10=cuarenta;


            
        },

        calcularPorcentajesCostosFinancieros(){
            var Uno=0;
            var Dos=0;
            var Tres=0;
            var Cuatro=0;
            var Cinco=0;
            var Seis=0;
            var Siete=0;
            var Ocho=0;
            var Nueve=0;
            var Diez=0;

            var Once=0;
            var Doce=0;
            var Trece=0;
            var Catorce=0; 
            var Quince=0; 
            var Dieciseis=0;
            var Diecisiete=0;
            var Dieciocho=0;

            var Diecinueve=0;
            var Veinte=0; 
            var veintiuno=0;
            var veintidos=0;
            var veintitres=0;
            var veinticuatro=0;
            var veinticinco=0;
            var veintiseis=0;
            var veintisiete=0;
            var veintiocho=0;
            var veintinueve=0;
            var treinta=0;
            var treintauno=0;
            var treintados=0;
            var treintatres=0;
            var treintacuatro=0;
            var treintacinco=0;
            var treintaseis=0;
            var treintasiete=0;
            var treintaocho=0;
            var treintanueve=0;
            var cuarenta=0;

            for(var i =0;i<this.ListaCF.length;i++ ){
                if(i>0){
                    
                    if(this.ListaCF[i].AnioAnterior!=''){
                        Uno=parseFloat(this.ListaCF[0].AnioAnterior);
                        Dos+= parseFloat(this.ListaCF[i].AnioAnterior);
                        Tres= Uno+Dos;
                    }
                    Cuatro=Tres;

                    if(this.ListaCF[i].AnioAnteriorPlan!=''){
                        Cinco=parseFloat(this.ListaCF[0].AnioAnteriorPlan);
                        Seis+= parseFloat(this.ListaCF[i].AnioAnteriorPlan);
                        Siete= Cinco+Seis;
                    }
                    Ocho=Siete;

                    if(this.ListaCF[i].PlanAnual!=''){
                        Nueve=parseFloat(this.ListaCF[0].PlanAnual);
                        Diez+= parseFloat(this.ListaCF[i].PlanAnual);
                        Once= Nueve+Diez;
                    }
                    Doce=Once;

                    if(this.ListaCF[i].PlanAnualPorcen!=''){
                        Trece=parseFloat(this.ListaCF[0].PlanAnualPorcen);
                        Catorce+= parseFloat(this.ListaCF[i].PlanAnualPorcen);
                        Quince= Trece+Catorce;
                    }
                    Dieciseis=Quince;

                    if(this.ListaCF[i].AnioActual!=''){
                        Diecisiete=parseFloat(this.ListaCF[0].AnioActual);
                        Dieciocho+= parseFloat(this.ListaCF[i].AnioActual);
                        Diecinueve= Diecisiete+Dieciocho;
                    }
                    Veinte=Diecinueve;

                    if(this.ListaCF[i].AnioActualProcen!=''){
                        veintiuno=parseFloat(this.ListaCF[0].AnioActualProcen);
                        veintidos+= parseFloat(this.ListaCF[i].AnioActualProcen);
                        veintitres= veintiuno+veintidos;
                    }
                    veinticuatro=veintitres;

                    if(this.ListaCF[i].PlanMes!=''){
                        veinticinco=parseFloat(this.ListaCF[0].PlanMes);
                        veintiseis+= parseFloat(this.ListaCF[i].PlanMes);
                        veintisiete= veinticinco+veintiseis;
                    }
                    veintiocho=veintisiete;

                    if(this.ListaCF[i].PlanMesPorcen!=''){
                        veintinueve=parseFloat(this.ListaCF[0].PlanMesPorcen);
                        treinta+= parseFloat(this.ListaCF[i].PlanMesPorcen);
                        treintauno= veintinueve+treinta;
                    }
                    treintados=treintauno;

                    if(this.ListaCF[i].MesActual!=''){
                        treintatres=parseFloat(this.ListaCF[0].MesActual);
                        treintacuatro+= parseFloat(this.ListaCF[i].MesActual);
                        treintacinco= treintatres+treintacuatro;
                    }
                    treintaseis=treintacinco;

                    if(this.ListaCF[i].MesActualPorce!=''){
                        treintasiete=parseFloat(this.ListaCF[0].MesActualPorce);
                        treintaocho+= parseFloat(this.ListaCF[i].MesActualPorce);
                        treintanueve= treintasiete+treintaocho;
                    }
                    cuarenta=treintanueve;
                }
            }
            this.Gp1=Cuatro;
            this.Gp2=Ocho;
            this.Gp3=Doce;
            this.Gp4=Dieciseis;
            this.Gp5=Veinte;
            this.Gp6=veinticuatro;
            this.Gp7=veintiocho;
            this.Gp8=treintados;
            this.Gp9=treintaseis;
            this.Gp10=cuarenta;


            
        },

       
    },
}
</script>
