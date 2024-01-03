<template>

<form class="form-cotizacion">
              <div class="form-group form-row">
                   <div class="col-md-3 col-lg-3">
                     <label>Tipo de servicio</label>
                     <select class="form-control form-control-sm">
                      <option value="">Servicio</option>
                      <option value="">Proyecto</option>
                    </select>
                   </div>
                   <div class="col-md-7 col-lg-7"></div>
                   <div class="col-md-2 col-lg-2">
                       <label>Folio</label>
                       <input type="text" class="form-control form-control-sm bold color-02" value="O. Servicio529" readonly="">
                   </div>
              </div>
            <!--Inicio primera parte-->
            <div class="row mt-2">
              <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <h4 class="titulo-04">DATOS DEL CLIENTE</h4>
                <hr>
              </div>
            </div>
            <div class="form-group form-row mt-2">
              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                <label>Nombre</label>
                <input type="text" class="form-control form-control-sm" placeholder="Nombre cliente">
              </div>
              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                <div class="form-inline">
                  <button type="button" class="btn btn-01 search mt-3b">Buscar</button>
                  <button type="button" class="btn btn-01 add mt-3b mx-sm-2">Añadir</button>
                </div>
              </div>
            </div>
            <div class="form-group form-row">
              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                <label>Dirección</label>
                <input type="text" class="form-control form-control-sm" placeholder="Dirección conocida">
              </div>
              <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <label>Distancia Km</label>
                <input type="text" class="form-control form-control-sm" placeholder="Distancia Km.">
              </div>
              <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <label>Velocidad Km/H</label>
                <input type="text" class="form-control form-control-sm" placeholder="Distancia Km.">
              </div>
            </div>
            <div class="form-group form-row">
              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                <label>Contacto</label>
                <input type="text" class="form-control form-control-sm" placeholder="Nombre del contacto">
              </div>
              <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <label>No. de contrato</label>
                <input type="text" class="form-control form-control-sm" placeholder="Distancia Km.">
              </div>
            </div>
            <div class="form-group form-row">
              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                <label>Correo electrónico</label>
                <input type="text" class="form-control form-control-sm" placeholder="ejemplo@email.com">
              </div>
              <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <label>Teléfono</label>
                <input type="text" class="form-control form-control-sm" placeholder="+52 9999 99 9999">
              </div>
            </div>
            <!--Fin primera parte-->

            <!--Fechas-->
            <div class="row mt-2">
              <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <h4 class="titulo-04">FECHAS</h4>
                <hr>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <label>Fecha de inicio</label>
                <input type="text" class=" form-control form-control-sm calendar" placeholder="Inicio">
              </div>
              <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                <label>Fecha para finalizar</label>
                <input type="text" class=" form-control form-control-sm calendar" placeholder="Finalizar">
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                <div class="box-01">
                  <div class="form-row">
                    <div class="col-md-4 col-lg-4">
                      <div class="row">
                        <div class="col-12 text-center">
                          <span class="dia">19</span><br>
                          <span class="mes">Ene</span>
                        </div>
                        <div class="col-12"></div>
                      </div>
                    </div>
                    <div class="col-md-8 col-lg-8">
                      <div class="row">
                        <div class="col-12">
                          <select class="form-control border-verde">
                            <option value="">Hora de inicio</option>
                            <option value="">10:00</option>
                            <option value="">11:00</option>
                          </select>
                        </div>
                        <div class="col-12 mt-2">
                          <select class="form-control border-rojo">
                            <option value="">Hora de Final</option>
                            <option value="">10:00</option>
                            <option value="">11:00</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                <div class="box-01">
                  <div class="form-row">
                    <div class="col-md-4 col-lg-4">
                      <div class="row">
                        <div class="col-12 text-center">
                          <span class="dia">20</span><br>
                          <span class="mes">Ene</span>
                        </div>
                        <div class="col-12"></div>
                      </div>
                    </div>
                    <div class="col-md-8 col-lg-8">
                      <div class="row">
                        <div class="col-12">
                          <select class="form-control border-verde">
                            <option value="">Hora de inicio</option>
                            <option value="">10:00</option>
                            <option value="">11:00</option>
                          </select>
                        </div>
                        <div class="col-12 mt-2">
                          <select class="form-control border-rojo">
                            <option value="">Hora de Final</option>
                            <option value="">10:00</option>
                            <option value="">11:00</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                <div class="box-01">
                  <div class="form-row">
                    <div class="col-md-4 col-lg-4">
                      <div class="row">
                        <div class="col-12 text-center">
                          <span class="dia">21</span><br>
                          <span class="mes">Ene</span>
                        </div>
                        <div class="col-12"></div>
                      </div>
                    </div>
                    <div class="col-md-8 col-lg-8">
                      <div class="row">
                        <div class="col-12">
                          <select class="form-control border-verde">
                            <option value="">Hora de inicio</option>
                            <option value="">10:00</option>
                            <option value="">11:00</option>
                          </select>
                        </div>
                        <div class="col-12 mt-2">
                          <select class="form-control border-rojo">
                            <option value="">Hora de Final</option>
                            <option value="">10:00</option>
                            <option value="">11:00</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                <div class="box-01">
                  <div class="form-row">
                    <div class="col-md-4 col-lg-4">
                      <div class="row">
                        <div class="col-12 text-center">
                          <span class="dia">22</span><br>
                          <span class="mes">Ene</span>
                        </div>
                        <div class="col-12"></div>
                      </div>
                    </div>
                    <div class="col-md-8 col-lg-8">
                      <div class="row">
                        <div class="col-12">
                          <select class="form-control border-verde">
                            <option value="">Hora de inicio</option>
                            <option value="">10:00</option>
                            <option value="">11:00</option>
                          </select>
                        </div>
                        <div class="col-12 mt-2">
                          <select class="form-control border-rojo">
                            <option value="">Hora de Final</option>
                            <option value="">10:00</option>
                            <option value="">11:00</option>
                          </select>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>

              <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                <div class="box-01">
                  <div class="form-row">
                    <div class="col-md-4 col-lg-4">
                      <div class="row">
                        <div class="col-12 text-center">
                          <span class="dia">23</span><br>
                          <span class="mes">Ene</span>
                        </div>
                        <div class="col-12"></div>
                      </div>
                    </div>
                    <div class="col-md-8 col-lg-8">
                      <div class="row">
                        <div class="col-12">
                          <select class="form-control border-verde">
                            <option value="">Hora de inicio</option>
                            <option value="">10:00</option>
                            <option value="">11:00</option>
                          </select>
                        </div>
                        <div class="col-12 mt-2">
                          <select class="form-control border-rojo">
                            <option value="">Hora de Final</option>
                            <option value="">10:00</option>
                            <option value="">11:00</option>
                          </select>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            <!--End fechas-->

            <!--Vehiculos y personal -->
            <div class="row mt-2">
              <div class="col-md-12 col-lg-6">
                <div class="row">
                  <div class="col-md-12 col-lg-12">
                    <h4>PERSONAL</h4>
                    <hr class="mb-2">
                  </div>
                  <div class="col-md-6 col-lg-6">
                    <div id="div1" class="droppable bg-feed" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="removeDrop(event)">
                      <div draggable="true" ondragstart="drag(event)">
                        Manuel Zapata <span type="button" class="icon-eye"><i class="far fa-eye"></i></span>
                      </div>
                      <div draggable="true" ondragstart="drag(event)">
                        Ricardo Santana <span type="button" class="icon-eye"><i class="far fa-eye"></i></span>
                      </div>
                      <div draggable="true" ondragstart="drag(event)">
                        Hector Osita
                        <span type="button" class="icon-eye"><i class="far fa-eye"></i></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-6">
                    <div id="div2" class="droppable" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="removeDrop(event)"></div>
                  </div>
                </div>
              </div>
              <div class="col-md-12 col-lg-6">
                <div class="row">
                  <div class="col-md-12 col-lg-12">
                    <h4>VEHÍCULOS</h4>
                    <hr class="mb-2">
                  </div>
                  <div class="col-md-6 col-lg-6">
                    <div id="div3" class="droppable bg-feed" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="removeDrop(event)">
                      <div draggable="true" ondragstart="drag(event)">
                        Ford 150 <span type="button" class="icon-eye"><i class="far fa-eye"></i></span>
                      </div>
                      <div draggable="true" ondragstart="drag(event)">
                        Toyota Hiace <span type="button" class="icon-eye"><i class="far fa-eye"></i></span>
                      </div>
                      <div draggable="true" ondragstart="drag(event)">
                        Chevy Silverado
                        <span type="button" class="icon-eye"><i class="far fa-eye"></i></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-6">
                    <div id="div3" class="droppable" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="removeDrop(event)"></div>
                  </div>

                </div>
              </div>
            </div>
            <!-- End vehiculos y personal-->

            <!--Tareas y materiales-->
            <div class="row mt-2">
              <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <h4 class="titulo-04">TAREAS Y MATERIALES</h4>
                <hr>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-md-6 col-lg-6">
                <label>Lista tareas</label>
                <textarea class="form-control" rows="5"></textarea>
              </div>
              <div class="col-md-6 col-lg-6">
                <label>Lista materiales</label>
                <textarea class="form-control" rows="5"></textarea>
              </div>
            </div>
            <!--End tareas y materiales-->

            <!--Observaciones-->
            <div class="row mt-2">
              <div class="col-md-12 col-lg-12">
                <h4 class="titulo-04">OBSERVACIONES</h4>
              </div>
              <div class="col-md-12 col-lg-12">
                <hr>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-md-6 col-lg-6">
                <label>Observaciones del técnico</label>
                <textarea class="form-control" rows="5"></textarea>
              </div>
              <div class="col-md-6 col-lg-6">
                <label>Observaciones finales</label>
                <textarea class="form-control" rows="5"></textarea>
              </div>
            </div>
            <!--End Observaciones-->

            <!--Costos-->
            <div class="row mt-2">
              <div class="col-md-12 col-lg-12">
                <h4>COSTOS</h4>
                <hr>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-md-3 col-lg-3">
                <label>Equipos</label>
                <input type="text" class="form-control form-control-sm dollar">
              </div>
              <div class="col-md-3 col-lg-3">
                <label>Materiales</label>
                <input type="text" class="form-control form-control-sm dollar">
              </div>
              <div class="col-md-3 col-lg-3">
                <label>Vehiculos</label>
                <input type="text" class="form-control form-control-sm dollar">
              </div>
              <div class="col-md-3 col-lg-3">
                <label>Contratista</label>
                <input type="text" class="form-control form-control-sm dollar">
              </div>
              <div class="col-md-3 col-lg-3">
                <label>Costo mano de obra</label>
                <input type="text" class="form-control form-control-sm dollar" readonly="">
              </div>
              <div class="col-md-3 col-lg-3">
                <label>Burden</label>
                <input type="text" class="form-control form-control-sm dollar" readonly="">
              </div>
              <div class="col-md-3 col-lg-3">
                <label>Costo vehiculos</label>
                <input type="text" class="form-control form-control-sm dollar" readonly="">
              </div>
            </div>
            <div class="row mt-4">
              <div class="col-md-12 col-lg-12">
                <h4>ESTATUS Y FACTURACIÓN</h4>
                <hr>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-md-3 col-lg-3">
                <label>Estatus</label>
                <select class="form-control form-control-sm">
                  <option value="">Abierta</option>
                  <option value="">Cerrada</option>
                  <option value="">Pendiente</option>
                  <option value="">Cancelada</option>
                </select>
              </div>
              <div class="col-md-3 col-lg-3">
                <label>Factura</label>
                <select class="form-control form-control-sm">
                  <option value="">No factura</option>
                  <option value="">Facturar</option>
                </select>
              </div>
            </div>
            <!--End Costos-->

            <!--Imagenes-->
            <div class="row mt-2">
              <div class="col-md-12 col-lg-12">
                <h4 class="mt-2">ANEXOS</h4>
                <hr>
              </div>
              <div class="col-md-12 col-lg-12 mt-2">
                <button type="button" class="btn btn-01" data-toggle="modal" data-target="#Observacion">Observaciones <span class="badge badge-light">0</span></button>
                <button type="button" class="btn btn-01" data-toggle="modal" data-target="#Foto">Fotografías <span class="badge badge-light">4</span></button>
              </div>
            </div>
            <!--End Imagenes-->
          </form>
</template>