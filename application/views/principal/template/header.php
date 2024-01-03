<a class="navbar-brand" href="#"><img src="assets/recursos/img/logosecont.svg" alt="" height="35"></a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample05"
	aria-controls="navbarsExample05" aria-expanded="true" aria-label="Toggle navigation">
	<span class="navbar-toggler-icon"></span>
</button>

<div class="navbar-collapse collapse show" id="navbarsExample05">
	<ul class="navbar-nav mr-auto" id="firtsMenu">
		
		<li class="nav-item dropdown nav-end">
			<a class="nav-link nav-link-2 dropdown-toggle"  id="navbarDropdown" role="button"
				data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
				<i class="fa fa-certificate fa-1" aria-hidden="true"></i> Administracion
			</a>
			
			<ul class="dropdown-menu bg-gris" aria-labelledby="navbarDropdown">
				<li class="nav-item nav-item-2 dropdown">
					<a class="dropdown-item dropdown-toggle" id="recepcion" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Recepción
					</a>
					<ul class="dropdown-menu bg-gris" aria-labelledby="recepcion">
						<li><a class="dropdown-item" href="javascript:list_cotizacionesauto();">Cotizaciones Auto.</a></li>
						<div class="dropdown-divider"></div>
						<li><a class="dropdown-item" href="javascript:list_recepcioncalibracion();">Recepciones</a></li>
						<div class="dropdown-divider"></div>
						<li><a class="dropdown-item" href="javascript:0">Lista de trabajos</a></li>
					</ul>
				</li>

			</ul>
		</li>
		
		
	
	</ul>
	
	<ul class="navbar-nav  navbar-nav-right">
		<li class="nav-item dropdown nav-end item-a">
			<a class="nav-link dropdown-toggle" href="#" id="item_1" data-toggle="dropdown" aria-haspopup="true"
				aria-expanded="false">
				<i class="fa fa-bell-o fa-1"></i>
				<span class="new-indicator text-danger d-none d-lg-block">
					<i class="fa fa-fw fa-circle"></i>
					<span class="number">5</span>
				</span>
			</a>
			<div class="dropdown-menu dropdown-menu-right" aria-labelledby="item_1">
				<a href="#" class="dropdown-item preview-item">
					<p class="mb-0 font-weight-normal">Usted tiene 5 nuevas notificaciones <span
							class="badge badge-pill badge-warning">Ver todas</span></p>
				</a>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item preview-item" href="#">
					<div class="preview-thumbnail">
						<div class="preview-icon bg-success">
							<i class="fa fa-comment-o mx-0 color-blanco"></i>
						</div>
					</div>
					<div class="preview-item-content">
						<h6 class="titulo-aler">Nuevo Mensaje</h6>
						<p class="text-aler">
							Hola Mundo
						</p>
					</div>
				</a>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item preview-item" href="#">
					<div class="preview-thumbnail">
						<div class="preview-icon bg-secondary">
							<i class="fa fa-handshake-o mx-0 color-blanco" aria-hidden="true"></i>
						</div>
					</div>
					<div class="preview-item-content">
						<h6 class="titulo-aler">Trabajo terminado</h6>
						<p class="text-aler">
							Trabajo en recepción...
						</p>
					</div>
				</a>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item preview-item" href="#">
					<div class="preview-thumbnail">
						<div class="preview-icon bg-info">
							<i class="fa fa-file-code-o mx-0 color-blanco" aria-hidden="true"></i>
						</div>
					</div>
					<div class="preview-item-content">
						<h6 class="titulo-aler">Nuevo Factura</h6>
						<p class="text-aler">
							Factura de...
						</p>
					</div>
				</a>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item preview-item" href="#">
					<div class="preview-thumbnail">
						<div class="preview-icon bg-warning">
							<i class="fa fa-hourglass-half mx-0 color-negro" aria-hidden="true"></i>
						</div>
					</div>
					<div class="preview-item-content">
						<h6 class="titulo-aler">Calibrando equipo</h6>
						<p class="text-aler">
							Equipo es trabajo...
						</p>
					</div>
				</a>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item preview-item" href="#">
					<div class="preview-thumbnail">
						<div class="preview-icon bg-danger">
							<i class="fa fa-exclamation-circle mx-0 color-blanco" aria-hidden="true"></i>
						</div>
					</div>
					<div class="preview-item-content">
						<h6 class="titulo-aler">Error en la Factura</h6>
						<p class="text-aler">
							Setiene un error...
						</p>
					</div>
				</a>
			</div>
		</li>
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="item_2" data-toggle="dropdown" aria-haspopup="true"
				aria-expanded="false">
				<img src="assets/recursos/img/img9.jpg" alt="" class="user-ima rounded-circle">
			</a>
			<ul class="dropdown-menu dropdown-menu-right dropdown-derecho" aria-labelledby="item_2" id="secondMenu">
				<?php /*
				<li class="nav-item nav-item-2 dropdown">
					<a class="dropdown-item dropdown-toggle dropdown-item-2" href="#" id="config" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> Configuraciónes <i class=" fa fa-cog"></i></a>
					<ul class="dropdown-menu dropdown-menu-derecho bg-gris" aria-labelledby="config">
						<li class="nav-item nav-item-2">
							<a class="nav-link" href="javascript:get_estadistica_general();">Cof. Estadisticas</a>
						</li>
						<div class="dropdown-divider"></div>
						<li class="nav-item nav-item-2 dropdown">
							<a class="dropdown-item dropdown-item-derecho dropdown-toggle" href="#" id="admin1" role="button"
								data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								Administración
							</a>
							<ul class="dropdown-menu dropdown-menu-derecho bg-gris" aria-labelledby="admin1">
								<li><a class="dropdown-item" href="javascript:list_empresa();">Empresa</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_descartados();">Descartos</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_moneda();">Monedas</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_impuestos();">Impuestos</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_estado();">Estado</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_archivosat();">Archivos SAT</a></li>
							</ul>
						</li>
						<div class="dropdown-divider"></div>
						<li class="nav-item nav-item-2 dropdown">
							<a class="dropdown-item dropdown-item-derecho dropdown-toggle" href="#" id="coti" role="button"
								data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Cof. Laboratorios
							</a>
							<ul class="dropdown-menu dropdown-menu-derecho bg-gris" aria-labelledby="coti">
								<li><a class="dropdown-item" href="javascript:list_versionpdf();">Versión PDF's</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_servicio();">Proceso</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_labservicio();">Servicios</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_laboratorio();">Laboratorios</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:0">Asignar Laboratorios</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:0">Proceso de servicios</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_hojamaestra();">Hojas maestra</a></li>
							</ul>
						</li>
						<div class="dropdown-divider"></div>
						<li class="nav-item nav-item-2 dropdown">
							<a class="dropdown-item dropdown-item-derecho dropdown-toggle" href="#" id="fin2" role="button"
								data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Cof. Finanzas
							</a>
							<ul class="dropdown-menu dropdown-menu-derecho bg-gris" aria-labelledby="fin2">
								<li><a class="dropdown-item" href="javascript:list_proveedorfactura();">Proveedor de facturación</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_serie();">Series</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_regimenfiscal();">Régimen fiscal</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_formapago();">Formas de pago</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_metodopago();">Método de pago</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_unidadmedida();">Unidades de medida</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_usocfdi();">Uso CFDI</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_banco();">Bancos</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_claveprodserv();">Servicos/Productos</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_tiporelacioncfdi();">Tipo de relación CFDI</a></li>
							</ul>
						</li>
						<div class="dropdown-divider"></div>
						<li class="nav-item nav-item-2 dropdown">
							<a class="dropdown-item dropdown-item-derecho dropdown-toggle" href="#" id="seg" role="button"
								data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							   Cof. Seguridad
							</a>
							<ul class="dropdown-menu dropdown-menu-derecho bg-gris" aria-labelledby="seg">

								<li><a class="dropdown-item" href="javascript:list_permiso();">Permisos ADMIN</a></li>
								<li><a class="dropdown-item" href="javascript:list_panel();">Panel ADMIN</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_rol();">Perfil (Rol)</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_usuario();">Usuarios</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:0">Asignar permisos</a></li>
								<div class="dropdown-divider"></div>
								<li><a class="dropdown-item" href="javascript:list_puesto();">Puesto</a></li>
								<a class="dropdown-item" href="javascript:list_panelcliente();">Panel cliente</a>
							</ul>
						</li>
					</ul>
				</li>
				*/?>
				<div class="dropdown-divider"></div>
				<li><a class="dropdown-item dropdown-item-2" href="javascript:0"><i class="fa fa-user-circle"></i> Cambiar Password</a></li>
				<div class="dropdown-divider"></div>
				<li><a class="dropdown-item dropdown-item-2" href="<?php echo base_url().'Cseguro/logout_Session'?>"><i class="fa fa-sign-out"></i> Salir</a></li>
			</ul>
		</li>
	</ul>

</div>
<?php ?>