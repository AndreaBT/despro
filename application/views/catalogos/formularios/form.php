<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>M2 DESARROLLOS</title>
    <base href="<?php echo base_url();?>">

    <!-- CSS Bootstrap 4.0-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,700,800" rel="stylesheet" />
    <link href="assets/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet" />

    <script src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Dropdown
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#">Disabled</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">

            <div class="col-lg-12 mt-4">

                <div class="form-group">
                    <label for="selectInputProject">Seleccione un proyecto</label>
                    <select class="form-control" id="selectInputProject" aria-describedby="projectHelp" onchange="CambiarProyecto();">
                        <?php 
                            foreach($lista as $element){

                            
                        ?>
                        <option value="<?php echo $element->IdProyecto ?>"><?php echo $element->Nombre ?></option>
                        <?php

                            }
                        ?>
                    </select>
                    <small id="projectHelp" class="form-text text-muted">We'll never share your email with anyone
                        else.</small>
                </div>

                <button type="button" class="btn btn-primary" onclick="VerFormulario();">Ver Formulario</button>
            </div>

        </div>

        <div class="row" hidden id="FormularioProyecto">

            <div class="col-lg-12 mt-4 mb-4">
                <div class="card">

                    <div class="card-body row">

                        <input type="text" hidden id="IdFormulario">
                        <input type="text" hidden id="IdProyecto">

                        <div class="col-6 form-group mt-4">
                            <label for="NombreFormulario">Nombre del Formulario</label>
                            <input type="text" class="form-control" id="NombreFormulario"
                                aria-describedby="formtitleHelp" placeholder="Nombre del Formulario">
                        </div>

                        <div class="col-6 ">
                            <div class="row">

                                <div class="col-12 d-flex justify-content-center">
                                    <img id="ImagenProyecto" src="<?php echo base_url();?>assets/recursos/img/upload.png"
                                        class="img-fluid" style="height: 80px !important;" alt="Logotipo del Proyecto">
                                </div>

                                <div class="col-12 d-flex justify-content-center">
                                    <label for="ImagenProyecto">Logotipo del Proyecto</label>
                                </div>

                            </div>
                        </div>

                        <div class="col-12 custom-control custom-checkbox ml-4 mt-4">
                            <input type="checkbox" class="custom-control-input" id="checkDescription">
                            <label class="custom-control-label" for="checkDescription">Añadir descripción</label>
                        </div>

                        <div class="col-12 form-group mt-4" hidden id="FormDescription">
                            <label for="DescripcionFormulario">Descripción del formulario</label>
                            <textarea class="form-control" id="DescripcionFormulario" rows="2"></textarea>
                        </div>

                    </div>

                </div>
            </div>

            <div class="col-lg-12 mt-4 mb-4">

                <div class="card">

                    <div class="card-body">

                        <h5>Datos obligatorios del prospecto</h5>

                        <div class="row">

                            <div class="col-6 form-group mt-4">
                                <label>Nombre</label>
                                <input disabled type="text" class="form-control" placeholder="">
                            </div>

                            <div class="col-6 form-group mt-4">
                                <label>Apellidos</label>
                                <input disabled type="text" class="form-control" placeholder="">
                            </div>

                            <div class="col-6 form-group mt-4">
                                <label>Correo electrónico</label>
                                <input disabled type="text" class="form-control" placeholder="">
                            </div>

                            <div class="col-6 form-group mt-4">
                                <label>Teléfono</label>
                                <input disabled type="text" class="form-control" placeholder="">
                            </div>

                        </div>

                    </div>

                </div>
            </div>

            <div class="col-lg-12 mt-4 mb-4 d-flex justify-content-end">
                <button type="button" id="btnPrevisualizar" onclick="Previsualizar()" class="btn btn-ligth mr-3"><i
                        class="fa fa-eye"></i> Previsualizar</button>
                <button type="button" id="btnNuevoCampo" onclick="NuevoCampo()" class="btn btn-ligth"><i
                        class="fa fa-plus"></i> Nuevo campo</button>
            </div>

            <div class="col-lg-12" id="ListaCampos">

                <!--<div class="card mt-4 mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9 form-group">
                                <label for="dynamicInput">Horario de contacto</label>
                                <input type="text" disabled class="form-control" name="" id="dynamicInput"
                                    aria-describedby="hDynamicInput" placeholder="">
                                <small id="hDynamicInput" class="form-text text-muted">Horario en el que el personal
                                    contactará al prospecto</small>
                            </div>
                            <div class="col-3 form-group">
                                <label class="text-danger">* Obligatorio</label>
                            </div>
                            <div class="col-lg-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-ligth mr-3"><i class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-ligth"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4 mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9 form-group">
                                <label for="dynamicInput2">Horario de contacto</label>
                                <textarea rows="2" disabled class="form-control" id="dynamicInput2" aria-describedby="hDynamicInput2"></textarea>
                                <small id="hDynamicInput2" class="form-text text-muted">Horario en el que el personal
                                    contactará al prospecto</small>
                            </div>
                            <div class="col-3 form-group">
                                <label class="text-danger">* Obligatorio</label>
                            </div>
                            <div class="col-lg-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-ligth mr-3"><i class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-ligth"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>-->

            </div>

        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalFormCampos" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Nuevo campo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="col-lg-12">
                        <div class="form-group mt-4">
                            <label>Nombre del campo</label>
                            <input type="text" id="formName" class="form-control" placeholder="Nombre">
                        </div>

                        <div class="form-group mt-4">
                            <label for="formType">Seleccione un proyecto</label>
                            <select class="form-control" id="formType">
                                <option value="text">Respuesta corta</option>
                                <option value="textarea">Respuesta larga</option>
                            </select>
                        </div>

                        <div class="custom-control custom-checkbox my-1 mr-sm-2 mt-4">
                            <input type="checkbox" id="formRequired" class="custom-control-input">
                            <label class="custom-control-label" for="formRequired">Obligatorio</label>
                        </div>

                        <div class="form-group mt-4 Descripcion">
                            <label>Descripción del campo (opcional)</label>
                            <textarea id="formDescription" class="form-control" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="col-lg-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-ligth" onclick="GuardarCampo()"><i class="fa fa-floppy-o"></i> Guardar</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <footer></footer>

    <!-- ./wrapper -->


    <!-- Logout Modal -->
    <div class="modal fade bd-example-modal-lg" id="modalform" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div id="contmodalform">

                </div>
            </div>
        </div>
    </div>
    <!-- Seguridad -->

    <!--  <script src="assets/recursos/js/sistema/rol.js"></script>
    <script src="assets/recursos/js/sistema/usuario.js"></script> -->
    <script src="<?php echo base_url();?>assets/recursos/js/form.js"></script>
</body>

</html>