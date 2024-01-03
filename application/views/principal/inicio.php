<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>M2 DESARROLLOS</title>
    <base href="<?php echo base_url();?>">
	
	<!-- CSS Bootstrap 4.0-->
    <link href="assets/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,700,800" rel="stylesheet" />
    <link href="assets/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    
	<!-- CSS Custom -->
	<link href="assets/recursos/css/bootnavbar.css" rel="stylesheet" />
    <link href="assets/recursos/css/style.css" rel="stylesheet" />
    
 
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-menu" id="main_navbar">
        <?php  $this->load->view("principal/template/header");?>
    </nav>
	
	<div class="container-fluid">
        <div class="row justify-content-center mt-2" id="content">
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

</body>

  


</html>
