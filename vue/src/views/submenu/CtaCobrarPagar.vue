<template>
	<div>
		<section class="container-fluid">
			<div class="row justify-content-center">
				<div class="col-12 col-sm-12 col-md-12 col-lg-10">
					<div class="row justify-content-center mt-4">
						<div class="col-12 col-sm-2 col-md-6 col-lg-6">
							<div class="row justify-content-end">
								<div class="col-12 col-sm-12 col-md-12 col-lg-12">
									<h3>Cuentas por Cobrar y Pagar</h3>
									<hr />
								</div>
							</div>
							<div class="row justify-content-start">
								<div class="col-12 col-sm-12 col-md-5 col-lg-5 text-center">
									<router-link
										:to="{ name: 'ctacuentasporcobrar' }"
										class="btn btn-01 btn-sub btn-block"
									>
										<img
											src="../../images/finanzas/actualizar.png"
											alt="Desprosoft"
										/><br />
										<span class="text-btn">Cuentas Por Cobrar</span>
									</router-link>
								</div>
								<div class="col-12 col-sm-12 col-md-5 col-lg-5 text-center">
									<router-link
										:to="{ name: 'cuentasporpagaradmin' }"
										class="btn btn-01 btn-sub btn-block"
									>
										<img
											src="../../images/finanzas/actualizar.png"
											alt="Desprosoft"
										/><br />
										<span class="text-btn"
											>Administración Cuentas Por Pagar</span
										>
									</router-link>

									<router-link
										:to="{ name: 'cuentasporpagaropera' }"
										class="btn btn-01 btn-sub btn-block"
									>
										<img
											src="../../images/finanzas/actualizar.png"
											alt="Desprosoft"
										/><br />
										<span class="text-btn">Operaciones Cuentas Por Pagar</span>
									</router-link>
								</div>
								
							</div>
						</div>
						<div class="col-12 col-sm-2 col-md-6 col-lg-6">
							<div class="row justify-content-end">
								<div class="col-12 col-sm-12 col-md-12 col-lg-12">
									<h3>Configuración</h3>
									<hr />
								</div>
							</div>
							<div class="row justify-content-start">
								<div class="col-12 col-sm-12 col-md-5 col-lg-5 text-center">
									<router-link
										:to="{ name: 'ctaproveedores' }"
										class="btn btn-01 btn-sub btn-block"
									>
										<img
											src="../../images/finanzas/plan_denegocios.png"
											alt="Desprosoft"
										/><br />
										<span class="text-btn">Proveedores</span>
									</router-link>
								</div>
								<div
									class="col-12 col-sm-12 col-md-5 col-lg-5 text-center"
								></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</template>
