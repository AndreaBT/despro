<template>
    <div>
        <input type="hidden" :value="calcularValores">
        <section class="container-fluid">
            <div class="row mt-3">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                    <nav class="navbar navbar-breadcrumb navbar-expand-md bg-breadcrumb breadcrumb-borde">
                        <div class="mr-auto">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb clearfix pt-3">
                                    <li class="breadcrumb-item"><a href="#" @click="Menu">Menú</a></li>
                                    <li class="breadcrumb-item active">Actualizar Facturación de Servicios</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="form-inline">
                            <div class="form-group mt-n1">
                                <button @click="Descargar" type="button" class="btn btn-01 print mr-2">Imprimir</button>
                                <button :disabled="Disabled" @click="Guardar" type="button" class="btn btn-01 save mr-2"><i v-show="Disabled" class="fa fa-spinner fa-pulse fa-1x fa-fw"></i> {{txtSave}}</button>
                            </div>
                        </div>
                    </nav>

                </div>
            </div>
            <input type="hidden" :value="IdConfigS">
            <div class="row justify-content-start mt-3">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card card-01">
                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <div class="form-inline justify-content-start mt-2">
                                    <label class="mr-1">Servicio</label>
                                    <select @change="ListaSubtipo();" v-model="IdConfigS"   class="form-control form-control-sm mr-2">
                                        <option v-for="(item, index) in ListaServicios" :key="index" :value="item.IdConfigS">{{item.Nombre}}</option>
                                        <option :value="6">Todos</option>
                                    </select>
                                    <label class="mr-1">Tipo Servicio</label>
                                    <select  @change="get_listdata();" v-model="IdTipoSubservicio"    class="form-control form-control-sm mr-2">
                                        <option :value="''">Todos</option>
                                        <option v-for="(item, index) in ListaTipoServicio" :key="index" :value="item.IdTipoSer">{{item.Concepto}}</option>
                                    </select>
                                    <Cvalidation v-if="this.errorvalidacion.IdTipoServ" :Mensaje="'Seleccione el Servicio'"></Cvalidation>
                                    <label class="mr-1">Año</label>

                                    <select :disabled="Disabled" @change="get_listdata"  v-model="Anio" class="form-control mr-2">
                                        <option v-for="(item,index) in ListaAnios" :key="index" :value="item">{{item}}</option>
                                    </select>

                                    <label class="mr-1">Mes</label>
                                    <select :disabled="Disabled" @change="get_listdata()"  v-model="Mes"  class="form-control form-control-sm mr-2">
                                        <option  :value="1">Enero</option>
                                        <option  :value="2">Febrero</option>
                                        <option  :value="3">Marzo</option>
                                        <option  :value="4">Abril</option>
                                        <option  :value="5">Mayo</option>
                                        <option  :value="6">Junio</option>
                                        <option  :value="7">Julio</option>
                                        <option  :value="8">Agosto</option>
                                        <option  :value="9">Septiembre</option>
                                        <option  :value="10">Octubre</option>
                                        <option  :value="11">Noviembre</option>
                                        <option  :value="12">Diciembre</option>
                                    </select>
                                </div>
                                <hr>
                                <div class="form-inline justify-content-start mt-3">
                                    <h6 class="text-center mr-2" v-if="cliente.Nombre != ''">{{cliente.Nombre}}</h6>
                                    <h6 v-else class="text-center mr-2">Cliente Sin Selecionar</h6>
                                    <button @click="ListaCliente"  data-toggle="modal" data-target="#ModalForm3"  data-backdrop="static" type="button" class="btn btn-01 search mr-2">Cliente</button>
                                    <label class="mr-1">No. De Contrato</label>
                                    <select @change="get_listdata" v-model="IdContrato" name="" id=""  class="form-control form-control-sm mr-2">
                                        <option :value="''">Seleccione Un Numero de Contrato</option>
                                        <option v-for="(item,index) in ListaNumc" :key="index" :value="item.IdContrato">
                                        {{item.NumeroC}}
                                        </option>
                                    </select>
                                    <button @click="Limpiar"  type="button" class="btn btn-04"><i class="fas fa-times"></i></button>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <h5 v-if="cliente.Nombre != ''" class="mb-3 text-center">{{cliente.Nombre}}</h5>
                                <h5 v-else class="mb-3 text-center">Cliente Sin Selecionar</h5>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="table-finanzas">
                                    <table class="table-fin-05" v-if="!loading">
                                        <thead>
                                            <tr>
                                                <th class="sticky mediana marca"><b>Descripción</b></th>
                                                <th class="mediana text-center">Plan</th>
                                                <th class="mediana text-center">%</th>
                                                <th class="blue-02 mediana text-center">Actual</th>
                                                <th class="blue-02 mediana text-center">%</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr v-for="(item,index) in ListaDetalle" :key="index">
												<!-- TITULOS -->
                                                <td v-if="IdConfigS!=6" :class="item.Descripcion === 'Facturacion' ? 'sticky borde_bajo' : 'sticky' ">
													<b>{{item.Descripcion}}</b>
												</td>

                                                <td v-if="IdConfigS==6" :class="item.Descripcion === 'Facturacion' ? 'sticky borde_bajo' : 'sticky' ">
													<b>{{item.Nombre}}</b>
												</td>
                                                

												<!-- COLUMNA MONTO PLAN -->
                                                <template v-if=" cliente.Nombre == '' && IdConfigS!=6">
                                                    <td v-if="item.Descripcion=='Facturacion'" :class="item.Descripcion === 'Facturacion' ? 'sticky borde_bajo' : 'sticky' "> <Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="Factura" ></Cmoneda>  </td>
                                                    <td v-if="item.Descripcion=='Materiales'"><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="Material" ></Cmoneda>  </td>
                                                    <td v-if="item.Descripcion=='Equipos'"><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="Equipo" ></Cmoneda>  </td>
                                                    <td v-if="item.Descripcion=='Mano de Obra'"><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="Mano" ></Cmoneda>  </td>
                                                    <td v-if="item.Descripcion=='Vehiculos'"><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="Vehiculo" ></Cmoneda>  </td>
                                                    <td v-if="item.Descripcion=='Contratistas'"><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="Contratista"></Cmoneda>  </td>
                                                    <td v-if="item.Descripcion=='Viaticos'"><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="Viatico" ></Cmoneda>  </td>
                                                    <td v-if="item.Descripcion=='Burden'"><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="Burden" ></Cmoneda>  </td>

                                                </template>
                                                <template v-else-if="  cliente.Nombre != '' && IdConfigS!=6">
                                                    <td v-if="item.Descripcion=='Facturacion'" :class="item.Descripcion === 'Facturacion' ? 'sticky borde_bajo' : 'sticky' "> <Cmoneda  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'" currency="$" separator="," :precision="2" v-model="Factura" /> </td>
                                                    <td v-if="item.Descripcion=='Materiales'"><Cmoneda  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="Material" ></Cmoneda>  </td>
                                                    <td v-if="item.Descripcion=='Equipos'"><Cmoneda  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="Equipo" ></Cmoneda>  </td>
                                                    <td v-if="item.Descripcion=='Mano de Obra'"><Cmoneda  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="Mano" ></Cmoneda>  </td>
                                                    <td v-if="item.Descripcion=='Vehiculos'"><Cmoneda  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="Vehiculo" ></Cmoneda>  </td>
                                                    <td v-if="item.Descripcion=='Contratistas'"><Cmoneda  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="Contratista"></Cmoneda>  </td>
                                                    <td v-if="item.Descripcion=='Viaticos'"><Cmoneda  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="Viatico" ></Cmoneda>  </td>
                                                    <td v-if="item.Descripcion=='Burden'"><Cmoneda  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="Burden" ></Cmoneda>  </td>
                                                </template>

                                                <template v-else-if="IdConfigS==6">
                                                    <td v-if="item.Nombre=='Facturacion'" :class="item.Nombre === 'Facturacion' ? 'sticky borde_bajo' : 'sticky' "> <Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'" currency="$" separator="," :precision="2" v-model="item.PlanMes" /> </td>
                                                    <td v-if="item.Nombre=='Materiales'"><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="item.PlanMes" ></Cmoneda>  </td>
                                                    <td v-if="item.Nombre=='Equipos'"><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="item.PlanMes" ></Cmoneda>  </td>
                                                    <td v-if="item.Nombre=='Mano de Obra'"><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="item.PlanMes" ></Cmoneda>  </td>
                                                    <td v-if="item.Nombre=='Vehiculos'"><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="item.PlanMes" ></Cmoneda>  </td>
                                                    <td v-if="item.Nombre=='Contratistas'"><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="item.PlanMes"></Cmoneda>  </td>
                                                    <td v-if="item.Nombre=='Viaticos'"><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="item.PlanMes" ></Cmoneda>  </td>
                                                    <td v-if="item.Nombre=='Burden'"><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="item.PlanMes" ></Cmoneda>  </td>
                                                </template>

                                                

												<!-- COLUMNA PORCENTAJE PLAN -->
                                                <td v-if="item.Descripcion=='Facturacion' && IdConfigS!=6 " :class="item.Descripcion === 'Facturacion' ? 'sticky borde_bajo' : 'sticky' "><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="2" v-model="PorcentMes"></Cporcentaje> </td>
                                                <td v-if="item.Descripcion=='Materiales'  && IdConfigS!=6 "><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="2" v-model="PorcentMes1"></Cporcentaje> </td>
                                                <td v-if="item.Descripcion=='Equipos'  && IdConfigS!=6  "><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="2" v-model="PorcentMes2"></Cporcentaje> </td>
                                                <td v-if="item.Descripcion=='Mano de Obra'  && IdConfigS!=6 "><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="2" v-model="PorcentMes3"></Cporcentaje> </td>
                                                <td v-if="item.Descripcion=='Vehiculos'  && IdConfigS!=6 "><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="2" v-model="PorcentMes4"></Cporcentaje> </td>
                                                <td v-if="item.Descripcion=='Contratistas'  && IdConfigS!=6 "><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="2" v-model="PorcentMes5"></Cporcentaje> </td>
                                                <td v-if="item.Descripcion=='Viaticos'  && IdConfigS!=6 "><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="2" v-model="PorcentMes6"></Cporcentaje> </td>
                                                <td v-if="item.Descripcion=='Burden'  && IdConfigS!=6 "><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="2" v-model="PorcentMes7"></Cporcentaje> </td>

                                                <!-- COLUMNA PORCENTAJE PLAN -->
                                                <td v-if="item.Nombre=='Facturacion' && IdConfigS==6 " :class="item.Nombre === 'Facturacion' ? 'sticky borde_bajo' : 'sticky' "><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="2" v-model="item.PorcentajePlanMes"></Cporcentaje> </td>
                                                <td v-if="item.Nombre=='Materiales'  && IdConfigS==6 "><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="2" v-model="item.PorcentajePlanMes"></Cporcentaje> </td>
                                                <td v-if="item.Nombre=='Equipos'  && IdConfigS==6  "><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="2" v-model="item.PorcentajePlanMes"></Cporcentaje> </td>
                                                <td v-if="item.Nombre=='Mano de Obra'  && IdConfigS==6 "><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="2" v-model="item.PorcentajePlanMes"></Cporcentaje> </td>
                                                <td v-if="item.Nombre=='Vehiculos'  && IdConfigS==6 "><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="2" v-model="item.PorcentajePlanMes"></Cporcentaje> </td>
                                                <td v-if="item.Nombre=='Contratistas'  && IdConfigS==6 "><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="2" v-model="item.PorcentajePlanMes"></Cporcentaje> </td>
                                                <td v-if="item.Nombre=='Viaticos'  && IdConfigS==6 "><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="2" v-model="item.PorcentajePlanMes"></Cporcentaje> </td>
                                                <td v-if="item.Nombre=='Burden'  && IdConfigS==6 "><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="2" v-model="item.PorcentajePlanMes"></Cporcentaje> </td>

												<!-- COLUMNA MONTO ACTUAL -->
                                                <td  v-if=" cliente.Nombre != '' && item.Descripcion=='Facturacion' && IdConfigS!=6" :class="item.Descripcion === 'Facturacion' ? 'sticky borde_bajo' : 'sticky' ">
													<Cmoneda  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="item.MesActualMonto"></Cmoneda>
                                                </td>
                                                
                                                <td  v-else-if="IdConfigS==6" :class="item.Nombre === 'Facturacion' ? 'sticky borde_bajo' : '' ">
                                                    <Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="item.ActualMes"></Cmoneda>
                                                </td>

                                                <td v-else :class="item.Descripcion === 'Facturacion' ? 'sticky borde_bajo' : '' ">
													<Cmoneda  :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="$" separator="," :precision="2" v-model="item.MesActualMonto"></Cmoneda>
												</td>

                                                

                                                

												<!-- COLUMNA PORCENTAJE ACTUAL -->
												<td v-if="IdConfigS!=6" :class="item.Descripcion === 'Facturacion' ? 'sticky borde_bajo' : '' "><Cporcentaje :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="2" v-model="item.MesActualPorcen"></Cporcentaje> </td>

                                                <td v-else :class="item.Nombre === 'Facturacion' ? 'sticky borde_bajo' : '' "><Cporcentaje :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm text-center'"  currency="%" separator="," :precision="2" v-model="item.PorcentajeMes"></Cporcentaje> </td>
                                            </tr>

                                        </tbody>
                                        <tfoot>
                                            <tr v-if="IdConfigS==6">
                                                <td class="color-01 bold sticky marca">Costo Operacional</td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  currency="$" separator="," :precision="2" v-model="SieteOp2"></Cmoneda></td>
                                                <td><Cporcentaje :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  currency="%" separator="," :precision="2" v-model="OchoOp2"></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  currency="$" separator="," :precision="2" v-model="NueveOp2"></Cmoneda></td>
                                                <td><Cporcentaje :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  currency="%" separator="," :precision="2" v-model="DiesOp2"></Cporcentaje></td>
                                            </tr>
                                            <tr v-if="IdConfigS!=6"> 
                                                <td class="color-01 bold sticky marca">Costo Operacional</td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  currency="$" separator="," :precision="2" v-model="SieteOp"></Cmoneda></td>
                                                <td><Cporcentaje :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  currency="%" separator="," :precision="2" v-model="Porcenataje"></Cporcentaje></td>
                                                <td><Cmoneda :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  currency="$" separator="," :precision="2" v-model="NueveOp"></Cmoneda></td>
                                                <td><Cporcentaje :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"  currency="%" separator="," :precision="2" v-model="DiesOp"></Cporcentaje></td>
                                            </tr>

                                            <tr  v-if="IdConfigS==6">
                                                <td  class="color-01 bold sticky marca">Gross Profit</td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"   currency="$" separator="," :precision="2" v-model="SieteGp2"></Cmoneda></td>
                                                <td><Cporcentaje :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"   currency="%" separator="," :precision="2" v-model="OchoGp2"></Cporcentaje></td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"   currency="$" separator="," :precision="2" v-model="NueveGp2"></Cmoneda></td>
                                                <td><Cporcentaje :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"   currency="%" separator="," :precision="2" v-model="DiesGp2"></Cporcentaje></td>
                                            </tr>

                                            <tr v-if="IdConfigS!=6">
                                                <td class="color-01 bold sticky marca">Gross Profit</td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"   currency="$" separator="," :precision="2" v-model="SieteGp"></Cmoneda></td>
                                                <td><Cporcentaje :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"   currency="%" separator="," :precision="2" v-model="PorcentajeGP"></Cporcentaje></td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"   currency="$" separator="," :precision="2" v-model="NueveGp"></Cmoneda></td>
                                                <td><Cporcentaje :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm color-01 bold text-center'"   currency="%" separator="," :precision="2" v-model="DiesGp"></Cporcentaje></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!---<div class="row mt-2">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <h5 class="mb-3 text-center">Cliente Sin Selecionar</h5>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="table-finanzas">
                                    <table class="table-fin-02" v-if="!loading">
                                        <thead>
                                            <tr>
                                                <th class="sticky marca"></th>
                                                <th colspan="4" class="text-center blue-02">Mes Actual</th>
                                            </tr>
                                            <tr>
                                                <th class="sticky mediana marca"><b>Descripción</b></th>
                                                <th class="blue-02 mediana text-center">Plan</th>
                                                <th class="blue-02 mediana text-center">%</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr v-for="(item,index) in Lista2022" :key="index" :style="ValidateFondo(item,index)">
                                                <td class="sticky"><b>{{item.Descripcion}}</b></td>
                                                <td  v-if="item.Descripcion=='Facturacion'"><Cmoneda  :minus="true" :clase="'form-control form-finanza form-control-sm'"  currency="$" separator="," :precision="Decimal" v-model="item.FacturacionPlan" ></Cmoneda>  </td>
                                                <td  v-if="item.Descripcion=='Materiales'"><Cmoneda  :minus="true" :clase="'form-control form-finanza form-control-sm'"  currency="$" separator="," :precision="Decimal" v-model="item.Materiales" ></Cmoneda>  </td>
                                                <td v-if="item.Descripcion=='Equipos'"><Cmoneda  :minus="true" :clase="'form-control form-finanza form-control-sm'"  currency="$" separator="," :precision="Decimal" v-model="item.Equipos" ></Cmoneda>  </td>
                                                <td v-if="item.Descripcion=='Mano de Obra'"><Cmoneda  :minus="true" :clase="'form-control form-finanza form-control-sm'"  currency="$" separator="," :precision="Decimal" v-model="item.ManoDeObra" ></Cmoneda>  </td>
                                                <td v-if="item.Descripcion=='Vehiculos'"><Cmoneda  :minus="true" :clase="'form-control form-finanza form-control-sm'"  currency="$" separator="," :precision="Decimal" v-model="item.Vehiculos" ></Cmoneda>  </td>
                                                <td v-if="item.Descripcion=='Contratistas'"><Cmoneda  :minus="true" :clase="'form-control form-finanza form-control-sm'"  currency="$" separator="," :precision="Decimal" v-model="item.Contratistas"></Cmoneda>  </td>
                                                <td v-if="item.Descripcion=='Viaticos'"><Cmoneda  :minus="true" :clase="'form-control form-finanza form-control-sm'"  currency="$" separator="," :precision="Decimal" v-model="item.Viaticos" ></Cmoneda>  </td>
                                                <td v-if="item.Descripcion=='Burden'"><Cmoneda  :minus="true" :clase="'form-control form-finanza form-control-sm'"  currency="$" separator="," :precision="Decimal" v-model="item.Burden" ></Cmoneda>  </td>
                                                <td><Cporcentaje :activo="true" :minus="true" :clase="'form-control form-finanza form-control-sm'"  currency="%" separator="," :precision="1" v-model="item.AnioActualPorcen"></Cporcentaje> </td>
                                            </tr>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="sticky"><b>Costo Operacional</b></td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm color-01 bold'"  currency="$" separator="," :precision="Decimal" v-model="SieteOp"></Cmoneda></td>
                                                <td><Cporcentaje :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm color-01 bold'"  currency="%" separator="," :precision="1" v-model="Porcenataje"></Cporcentaje></td>
                                            </tr>

                                            <tr>
                                                <td class="sticky"><b>Gross Profit</b></td>
                                                <td><Cmoneda :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm color-01 bold'"   currency="$" separator="," :precision="Decimal" v-model="SieteGp"></Cmoneda></td>
                                                <td><Cporcentaje :activo="true"  :minus="true" :clase="'form-control form-finanza form-control-sm color-01 bold'"   currency="%" separator="," :precision="1" v-model="PorcentajeGP"></Cporcentaje></td>

                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>----->
                    </div>
                </div>
            </div>
        </section>

         <Ccliente :TipoModal='1'></Ccliente>
    </div>
</template>
<script>
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
        Cvalidation
    },
    data() {
        return {
            ListaAnios:[],
            planventas:{},
            ListaServicios:[],
            ListaDetalle:[],
            ListaTipoServicio:[],
            Lista2022:[],
            Head:{
                Title:'Actualización de facturación',
                BtnNewShow:false,
                BtnNewName:'Nuevo',
                isreturn:true,
                isModal:false,
                isEmit:false,
                Url:'MenusFinanzas',
                ObjReturn:'',
            },
            IdConfigS:0,
            IdTipoSubservicio:'',
            Anio:2020,
            Mes:1,

            UnoOp:0,
            DosOp:0,
            TresOp:0,
            CuatroOp:0,
            CincoOp:0,
            SeisOp:0,
            SieteOp:0,
            OchoOp:0,
            NueveOp:0,
            DiesOp:0,

            SieteOp2:0,
            OchoOp2:0,
            NueveOp2:0,
            DiesOp2:0,

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

            SieteGp2:'',
            OchoGp2:'',
            NueveGp2:'',
            DiesGp2:'',

            Factura:0,
            Material:0,
            Equipo:0,
            Mano:0,
            Vehiculo:0,
            Contratista:0,
            Burden:0,
            Viatico:0,

            PorcentMes:0,
            PorcentMes1:0,
            PorcentMes2:0,
            PorcentMes3:0,
            PorcentMes4:0,
            PorcentMes5:0,
            PorcentMes6:0,
            PorcentMes7:0,

            Porcenataje:0,
            PorcentajeGP:0,
            Disabled:false,
            loading:true,
            txtSave:'Guardar',
            Decimal:1,

            //clientes
            cliente:{
                Nombre:'',
                IdCliente: '',
                IdClienteS: '',
            },
            ListaNumc:[],
            IdContrato:'',
            errorvalidacion :[],
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
        get_lisServicios(){
            this.$http.get(
                'baseactual/get',
                {
                    params:{ RegEstatus:'A',Entrada:''}
                }
            ).then( (res) => {
                this.ListaServicios=res.data.data.lista;

                this.IdConfigS=this.ListaServicios[0].IdConfigS;
                this.ListaSubtipo();

            });
        },
        async ListaSubtipo()
        {
            if (this.IdConfigS>0)
            {
                await this.$http.get(
                    'tiposervicio/get',
                    {
                        params:{ RegEstatus:'A',IdConfigS:this.IdConfigS,IdTipoServ:this.IdTipoServ,Entrada:''}
                    }
                ).then( (res) => {

                 this.ListaTipoServicio =res.data.data.tiposervicio;
                 this.IdTipoSubservicio="";
                 this.get_listdata();


                });
            }
        },
        get_listdata(){
            this.ListaDetalle= [];
            //costo operacional
            this.UnoOp=0;
            this.DosOp=0;
            this.TresOp=0;
            this.CuatroOp=0;
            this.CincoOp=0;
            this.SeisOp=0;
            this.SieteOp=0;
            this.OchoOp=0;
            this.NueveOp=0;
            this.DiesOp=0;

            this.SieteOp2=0;
            this.OchoOp2=0;
            this.NueveOp2=0;
            this.DiesOp2=0;
            //GP
            this.UnoGp=0;
            this.DosGp=0;
            this.TresGp=0;
            this.CuatroGp=0;
            this.CincoGp=0;
            this.SeisGp=0;
            this.SieteGp=0;
            this.OchoGp=0;
            this.NueveGp=0;
            this.DiesGp=0;

            this.SieteGp2=0;
            this.OchoGp2=0;
            this.NueveGp2=0;
            this.DiesGp2=0;

            var url ='estadosfinancierosinfo/get';

            if (this.IdConfigS==6)
            {
                var url ='estadosFinancieros/getTodos';
            }

            if (this.IdConfigS >0 )
            {
                this.$http.get(url,
                {
                    params:{
                        IdConfigS:this.IdConfigS,
                        IdTipoServ:this.IdTipoSubservicio,
                        Anio:this.Anio,
                        Mes:this.Mes,
                        IdCliente:this.cliente.IdCliente,
                        IdClienteS:this.cliente.IdClienteS,
                        IdContrato:this.IdContrato
                    }
                })
                .then( (res) => {
                    //console.log(res.data)

                    if (this.IdConfigS==6) {
                        this.ListaDetalle = res.data.data.detalle.row;
                        let valores =  res.data.data.detalle;

                        this.SieteOp2 = valores.SieteCp;
                        this.OchoOp2 = valores.OchoCp;
                        this.NueveOp2 = valores.NueveCp;
                        this.DiesOp2 = valores.DiesCp;

                        this.SieteGp2 = valores.SieteGp;
                        this.OchoGp2 = valores.OchoGp;
                        this.NueveGp2 = valores.NueveGp;
                        this.DiesGp2 = valores.DiesGp;
                    }else{
                        this.ListaDetalle = res.data.data.row;
                        let valores =  res.data.data.rowconfig;
                        //costo operacional
                        this.UnoOp = valores.COAnioAnteriorMonto;
                        this.DosOp = valores.COAnioAnteriorPorcen;
                        this.TresOp = valores.COAnioActualPlan;
                        this.CuatroOp = valores.COAnioActualPlanPorcen;
                        this.CincoOp = valores.COAnioActualMonto;
                        this.SeisOp = valores.COAnioActualPorcen;
                        this.SieteOp = valores.COMesActualPlan;
                        this.OchoOp = valores.COMesActualPlanPorcen;
                        this.NueveOp = valores.COMesActualMonto;
                        this.DiesOp = valores.COMesActualPorcen;

                        this.UnoGp = valores.GPAnioAnteriorMonto;
                        this.DosGp = valores.GPAnioAnteriorPorcen;
                        this.TresGp = valores.GPAnioActualPlan;
                        this.CuatroGp = valores.GPAnioActualPlanPorcen;
                        this.CincoGp = valores.GPAnioActualMonto;
                        this.SeisGp = valores.GPAnioActualPorcen;
                        this.SieteGp = valores.GPMesActualPlan;
                        this.OchoGp = valores.GPMesActualPlanPorcen;
                        this.NueveGp = valores.GPMesActualMonto;
                        this.DiesGp = valores.GPMesActualPorcen;

                        this.Disabled=false;
                        this.loading=false;
                        this.get_ListaPlann2022();
                        
                    }
                    
                });
            }
        },

        get_ListaPlann2022(){
            this.Porcenataje=0
            this.PorcentajeGP=0;

            this.Factura=0;
            this.Material=0;
            this.Equipo=0;
            this.Contratista=0;
            this.Mano=0;
            this.Vehiculo=0;
            this.Burden=0;
            this.Viatico=0;

            this.PorcentMes=0;
            this.PorcentMes1=0;
            this.PorcentMes2=0;
            this.PorcentMes3=0;
            this.PorcentMes4=0;
            this.PorcentMes5=0;
            this.PorcentMes6=0;
            this.PorcentMes7=0;


            var url ='PlanMensual2022/get';
            if (this.IdConfigS >0 )
            {
                this.$http.get(url,
                {
                    params:{
                        IdConfigS:this.IdConfigS,
                        IdTipoServ:this.IdTipoSubservicio,
                        Anio:this.Anio,
                        Mes:this.Mes,
                        IdCliente:this.cliente.IdCliente,
                        IdClienteS:this.cliente.IdClienteS,
                        IdContrato:this.IdContrato
                    }
                })
                .then( (res) => {
                    //console.log(res.data)
                    this.Lista2022 = res.data.data.row;

                    let valoresItem = res.data.data.row;
                    this.Factura=valoresItem[0].FacturacionPlan;
                    this.Material=valoresItem[1].Materiales;
                    this.Equipo=valoresItem[2].Equipos;
                    this.Mano=valoresItem[3].ManoDeObra;
                    this.Vehiculo=valoresItem[4].Vehiculos;
                    this.Contratista=valoresItem[5].Contratistas;
                    this.Viatico=valoresItem[6].Viaticos;
                    this.Burden=valoresItem[7].Burden;

                    this.PorcentMes=valoresItem[0].AnioActualPorcen;
                    this.PorcentMes1=valoresItem[1].AnioActualPorcen;
                    this.PorcentMes2=valoresItem[2].AnioActualPorcen;
                    this.PorcentMes3=valoresItem[3].AnioActualPorcen;
                    this.PorcentMes4=valoresItem[4].AnioActualPorcen;
                    this.PorcentMes5=valoresItem[5].AnioActualPorcen;
                    this.PorcentMes6=valoresItem[6].AnioActualPorcen;
                    this.PorcentMes7=valoresItem[7].AnioActualPorcen;





                    let valores2022 =  res.data.data.rowconfig;


                    //costo operacional

                    this.Porcenataje = valores2022.COAnioActualPorcen;
                    this.PorcentajeGP= valores2022.GPAnioActualPlanPorcen;


                    this.Disabled=false;
                    this.loading=false;
                });
            }
        },
        async Guardar()
        {
            var Facturacion = this.ListaDetalle[0].MesActualMonto;
            if(Facturacion=="")
            {
                Facturacion=0;
            }

            var FacturacionPMensual = this.Lista2022[0].FacturacionPlan;
            if(FacturacionPMensual=="")
            {
                FacturacionPMensual=0;
            }
            var Materiales = this.Lista2022[1].Materiales;
            if(Materiales=="")
            {
                Materiales=0;
            }

            var Equipos = this.Lista2022[2].Equipos;
            if(Equipos=="")
            {
                Equipos=0;
            }
            var ManoDeObra = this.Lista2022[3].ManoDeObra;
            if(ManoDeObra=="")
            {
                ManoDeObra=0;
            }
            var Vehiculos = this.Lista2022[4].Vehiculos;
            if(Vehiculos=="")
            {
                Vehiculos=0;
            }
            var Contratistas = this.Lista2022[5].Contratistas;
            if(Contratistas=="")
            {
                Contratistas=0;
            }
            var Viaticos = this.Lista2022[6].Viaticos;
            if(Viaticos=="")
            {
                Viaticos=0;
            }

            var Burden = this.Lista2022[7].Burden;
            if(Burden=="")
            {
                Burden=0;
            }

            if(this.IdConfigS == 0 || this.IdConfigS == ''){
                this.$toast.warning('Debe seleccionar un servicio');
                return false;
            }
            else if(this.IdTipoSubservicio == 0 || this.IdTipoSubservicio == '')
            {
                this.$toast.warning('Debe seleccionar un tipo de servicio');
                return false;
            }
            else if(this.cliente.IdClienteS == 0 || this.cliente.IdClienteS == '')
            {
                this.$toast.warning('Debe seleccionar un cliente y su sucursal');
                return false;
            }

            // else if(this.IdContrato == 0 || this.IdContrato == '')
            // {
            //     this.$toast.warning('Debe seleccionar un número de contrato');
            //     return false;
            // }
            else
            {
                this.Disabled = true;
                this.$http.post(
                'estadosfinancierosUpt/post',
                {
                    IdConfigS:this.IdConfigS,
                    Anio:this.Anio,
                    Mes:this.Mes,
                    Facturacion:Facturacion,
                    Materiales:this.Material,
                    Equipos:this.Equipo,
                    ManoDeObra:this.Mano,
                    Vehiculos:this.Vehiculo,
                    Contratistas:this.Contratista,
                    Burden:this.Burden,
                    Viaticos:this.Viatico,
                    FacturacionPMensual:this.Factura,
                    IdTipoServ :this.IdTipoSubservicio,
                    IdClienteS:this.cliente.IdClienteS,
                    IdCliente:this.cliente.IdCliente,
                    IdContrato:this.IdContrato,
                    Detalle: this.ListaDetalle,
                })
                .then((res) => {
                    this.Disabled = false;
                    this.$toast.success('Información Guardada');
                    this.get_listdata();
                    this.errorvalidacion = [];
                })
                .catch( err => {
                    this.Disabled = false;
                    this.$toast.error('Seleccione los Campos Solicitados');
                    this.errorvalidacion = err.response.data.message.errores;
                });
            }


           /*  console.log('IdConfig '+this.IdConfigS)
            console.log('Facturacion '+Facturacion)
            console.log('Mes '+this.Mes)
            console.log('IdTipoServ '+this.IdTipoSubservicio)
            console.log('IdCliente '+this.cliente.IdCliente)
            console.log('IdClienteS '+this.cliente.IdClienteS)
            console.log('IdContrato '+this.IdContrato) */
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
            this.get_listdata();
        } ,
        Descargar()
        {
            var url="estadofinanciero";
            var Tipo=1;

            if (this.IdConfigS >0 )
            {
                if (this.IdConfigS==6)
                {
                    Tipo=2;
                }

                this.$http.get(
                'reporte/'+url,
                {
                responseType: 'arraybuffer',
                params :{
                        IdConfigS:this.IdConfigS,IdTipoServ:this.IdTipoSubservicio,Anio:this.Anio,Mes:this.Mes,IdCliente:this.cliente.IdCliente,IdClienteS:this.cliente.IdClienteS,IdContrato:this.IdContrato,Tipo:Tipo
                    }
                })
                .then( (response) => {

                    let pdfContent = response.data;
                    let file = new Blob([pdfContent], { type: 'application/pdf' });
                    var fileUrl = URL.createObjectURL(file);

                    window.open(fileUrl);
                });
            }
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
                this.Mes= parseInt( res.data.MesActual);
                this.get_lisServicios();
            });
        },
    },
    created() {
        this.get_anios();

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
        calcularValores()
        {
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




            var Reg1=0;
            var Reg2=0;
            var Reg3=0;
            var Reg4=0;
            var Reg5=0;
            var Reg6=0;
            var Reg7=0;
            var Reg8=0;
            var Reg9=0;
            var Reg10=0;

            for(var i =0;i<this.ListaDetalle.length;i++ )
            {
                if(i>0)
                {
                    if(this.ListaDetalle[i].MesActualMonto!='')
                    {
                        Nueve+= parseFloat(this.ListaDetalle[i].MesActualMonto);
                        var porcentajenuevo = 0;

                        if(this.ListaDetalle[0].MesActualMonto!="")
                        {
                            if(isNaN((parseFloat(this.ListaDetalle[i].MesActualMonto) *100 )/ parseFloat(this.ListaDetalle[0].MesActualMonto)) == true || ((parseFloat(this.ListaDetalle[i].MesActualMonto) *100 )/ parseFloat(this.ListaDetalle[0].MesActualMonto)) == 'Infinity') {
                                porcentajenuevo = 0;
                            }
                            else
                            {
                                porcentajenuevo = (parseFloat(this.ListaDetalle[i].MesActualMonto) *100 )/ parseFloat(this.ListaDetalle[0].MesActualMonto);
                            }
                        }
                        let result = parseFloat(porcentajenuevo).toFixed(1);
                        this.ListaDetalle[i].MesActualPorcen = (isNaN(result))?0:result;
                        Diez+= (isNaN(porcentajenuevo))?0:porcentajenuevo;
                    }
                }
                else
                {
                      if(this.ListaDetalle[i].MesActualMonto!='')
                    {
                        Reg9+= parseFloat(this.ListaDetalle[i].MesActualMonto);
                    }

                    if(this.ListaDetalle[i].MesActualPorcen!='')
                    {
                        let result2 = parseFloat(this.ListaDetalle[i].MesActualPorcen);
                        Reg10 = (isNaN(result2))?0:result2;
                    }
                }
            }

            //! FOR NUEVO ANDREA
            for(var i =0;i<this.Lista2022.length;i++ )
            {
                if(i>0)
                {
                    if(this.Lista2022[i].FacturacionPlan!='')
                    {
                        Siete+= parseFloat(this.Lista2022[i].FacturacionPlan);
                        var porcentajenuevo1 = 0;

                        if(this.Lista2022[0].FacturacionPlan!="")
                        {
                            if(isNaN((parseFloat(this.Lista2022[i].FacturacionPlan) *100 )/ parseFloat(this.Lista2022[0].FacturacionPlan)) == true || ((parseFloat(this.Lista2022[i].FacturacionPlan) *100 )/ parseFloat(this.Lista2022[0].FacturacionPlan)) == 'Infinity') {
                                porcentajenuevo1 = 0;
                            }
                            else
                            {
                                porcentajenuevo1 = (parseFloat(this.Lista2022[i].FacturacionPlan) *100 )/ parseFloat(this.Lista2022[0].FacturacionPlan);
                            }
                        }

                    }




                    if(this.Lista2022[i].Materiales!='')
                    {
                        Uno+= parseFloat(this.Lista2022[i].Materiales);
                        var porcentajenuevo2 = 0;

                        if(this.Lista2022[0].Materiales!="")
                        {
                            if(isNaN((parseFloat(this.Lista2022[i].Materiales) *100 )/ parseFloat(this.Lista2022[0].Materiales)) == true || ((parseFloat(this.Lista2022[i].Materiales) *100 )/ parseFloat(this.Lista2022[0].Materiales)) == 'Infinity') {
                                porcentajenuevo2 = 0;
                            }
                            else
                            {
                                porcentajenuevo2 = (parseFloat(this.Lista2022[i].Materiales) *100 )/ parseFloat(this.Lista2022[0].Materiales);
                            }
                        }

                    }

                    if(this.Lista2022[i].Equipos!='')
                    {
                        Tres+= parseFloat(this.Lista2022[i].Equipos);
                        var porcentajenuevo3 = 0;

                        if(this.Lista2022[0].Equipos!="")
                        {
                            if(isNaN((parseFloat(this.Lista2022[i].Equipos) *100 )/ parseFloat(this.Lista2022[0].Equipos)) == true || ((parseFloat(this.Lista2022[i].Equipos) *100 )/ parseFloat(this.Lista2022[0].Equipos)) == 'Infinity') {
                                porcentajenuevo3 = 0;
                            }
                            else
                            {
                                porcentajenuevo3 = (parseFloat(this.Lista2022[i].Equipos) *100 )/ parseFloat(this.Lista2022[0].Equipos);
                            }
                        }

                    }
                    if(this.Lista2022[i].ManoDeObra!='')
                    {
                        Cinco+= parseFloat(this.Lista2022[i].ManoDeObra);
                        var porcentajenuevo4 = 0;

                        if(this.Lista2022[0].ManoDeObra!="")
                        {
                            if(isNaN((parseFloat(this.Lista2022[i].ManoDeObra) *100 )/ parseFloat(this.Lista2022[0].ManoDeObra)) == true || ((parseFloat(this.Lista2022[i].ManoDeObra) *100 )/ parseFloat(this.Lista2022[0].ManoDeObra)) == 'Infinity') {
                                porcentajenuevo4 = 0;
                            }
                            else
                            {
                                porcentajenuevo4 = (parseFloat(this.Lista2022[i].ManoDeObra) *100 )/ parseFloat(this.Lista2022[0].ManoDeObra);
                            }
                        }
                    }
                    if(this.Lista2022[i].Vehiculos!='')
                    {
                        Once+= parseFloat(this.Lista2022[i].Vehiculos);
                        var porcentajenuevo5 = 0;

                        if(this.Lista2022[0].Vehiculos!="")
                        {
                            if(isNaN((parseFloat(this.Lista2022[i].Vehiculos) *100 )/ parseFloat(this.Lista2022[0].Vehiculos)) == true || ((parseFloat(this.Lista2022[i].Vehiculos) *100 )/ parseFloat(this.Lista2022[0].Vehiculos)) == 'Infinity') {
                                porcentajenuevo5 = 0;
                            }
                            else
                            {
                                porcentajenuevo5 = (parseFloat(this.Lista2022[i].Vehiculos) *100 )/ parseFloat(this.Lista2022[0].Vehiculos);
                            }
                        }

                    }

                    if(this.Lista2022[i].Contratistas!='')
                    {
                        Trece+= parseFloat(this.Lista2022[i].Contratistas);
                        var porcentajenuevo6 = 0;

                        if(this.Lista2022[0].Contratistas!="")
                        {
                            if(isNaN((parseFloat(this.Lista2022[i].Contratistas) *100 )/ parseFloat(this.Lista2022[0].Contratistas)) == true || ((parseFloat(this.Lista2022[i].Contratistas) *100 )/ parseFloat(this.Lista2022[0].Contratistas)) == 'Infinity') {
                                porcentajenuevo6 = 0;
                            }
                            else
                            {
                                porcentajenuevo6 = (parseFloat(this.Lista2022[i].Contratistas) *100 )/ parseFloat(this.Lista2022[0].Contratistas);
                            }
                        }

                    }

                    if(this.Lista2022[i].Viaticos!='')
                    {
                        Quince+= parseFloat(this.Lista2022[i].Viaticos);
                        var porcentajenuevo7 = 0;

                        if(this.Lista2022[0].Viaticos!="")
                        {
                            if(isNaN((parseFloat(this.Lista2022[i].Viaticos) *100 )/ parseFloat(this.Lista2022[0].Viaticos)) == true || ((parseFloat(this.Lista2022[i].Viaticos) *100 )/ parseFloat(this.Lista2022[0].Viaticos)) == 'Infinity') {
                                porcentajenuevo7 = 0;
                            }
                            else
                            {
                                porcentajenuevo7 = (parseFloat(this.Lista2022[i].Viaticos) *100 )/ parseFloat(this.Lista2022[0].Viaticos);
                            }
                        }

                    }

                    if(this.Lista2022[i].Burden!='')
                    {
                        Diecisiete+= parseFloat(this.Lista2022[i].Burden);
                        var porcentajenuevo8 = 0;

                        if(this.Lista2022[0].Burden!="")
                        {
                            if(isNaN((parseFloat(this.Lista2022[i].Burden) *100 )/ parseFloat(this.Lista2022[0].Burden)) == true || ((parseFloat(this.Lista2022[i].Burden) *100 )/ parseFloat(this.Lista2022[0].Burden)) == 'Infinity') {
                                porcentajenuevo8 = 0;
                            }
                            else
                            {
                                porcentajenuevo8 = (parseFloat(this.Lista2022[i].Burden) *100 )/ parseFloat(this.Lista2022[0].Burden);
                            }
                        }

                    }


                }
                else
                {


                    if(this.Lista2022[i].FacturacionPlan!='')
                    {
                        Reg7+= parseFloat(this.Lista2022[i].FacturacionPlan);
                    }
                    if(this.Lista2022[i].AnioActualPorcen!='')
                    {
                        Reg8+= parseFloat(this.Lista2022[i].AnioActualPorcen);
                    }
                }
            }
            //! FIN FOR NUEVO ANDREA
            //Cosotos Operacionales
            if (Diez >= 100) {
                Diez = 100;
            }
            this.SieteOp=Siete.toFixed(2);
            this.SieteOp=Uno+ Tres + Cinco + Once + Trece + Quince + Diecisiete ;

            this.NueveOp = Nueve.toFixed(2);
            this.DiesOp = Diez.toFixed(1);

            //Costo GrossProfit
            var val = Reg9 - Nueve;
            var val2 = parseFloat(Reg10) - parseFloat(Diez);

            var val3 = Reg7-Uno -Tres - Cinco  - Once -Trece - Quince - Diecisiete - Siete;;
            this.SieteGp=val3.toFixed(1);

            this.NueveGp = val.toFixed(2);
            this.DiesGp = val2.toFixed(1);
        }
    },
}
</script>
