<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<base href="<?php echo base_url();?>">
    <title>M2 Desarollos</title>
	<!-- CSS Bootstrap 4.0-->
	<link href="assets/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
   	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,700,800" rel="stylesheet">
	
	<!-- CSS Login -->
	<link href="assets/recursos/css/signin.css" rel="stylesheet">
	<link rel="shortcut icon" href="#" />  
</head>
	
<body class="bg">
	<div class="container" style="z-index: 1;">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-3 col-lg-3"></div>
            <div class="col-12 col-sm-12 col-md-9 col-lg-9">
                <div class="row justify-content-center">
                    <div class="col-12 col-sm-6 col-md-5 col-lg-5">
                        <div class="card-lab card">
                            <div class="card-body">
                                <p class="text-center" style="background-color:black;"><img src="<?php echo base_url();?>assets/recursos/img/logo2.png" class="img-fluid logo" alt="Logo Secont"></p>
                                <br>
                                <form role="form" id="FormUser" method="post" accept-charset="utf-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control user" placeholder="Usuario" name="Usuario" id="Usuario">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control pass" placeholder="Password" name="Password" id="Password" autocomplete="off" readonly="readonly" onfocus="this.removeAttribute('readonly');">
                                    </div>
                                    <div class="form-group mt-5">
                                        <button type="button" id="btnentrar" class="btn btn-block btn-primary" onclick="Loginuser();">Entrar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
	<script src="assets/lib/jquery/jquery-3.3.1.min.js"></script>
    <script src="assets/lib/popper/popper.min.js"></script>
    <script src="assets/lib/bootstrap/js/bootstrap.min.js"></script>


	<script type="text/javascript">
		
	$(document).keypress(function(e) {
		if (e.keyCode === 13) {
			Loginuser();
		}
	});


	function Loginuser(){
		var Usuario = document.getElementById('Usuario').value.trim();
		var Password = document.getElementById('Password').value.trim();
        window.location.href = '<?php echo base_url() ?>principal';
		/*if(Usuario ==''){
			document.getElementById('Usuario').focus();
		}
		else if(Password==''){
			document.getElementById('Password').focus();
		}
		else  
		{ 
			var formData = new FormData($("#FormUser")[0]);

			$.ajax(
			{
				type: "POST",
				url: base_api+"api/v.1/login/Loginpost.api",
				data:formData,
				contentType: false,
				processData: false,                        
				beforeSend: function(){
					document.getElementById('btnentrar').disabled = true;
					$("#loader2").html('<center><img width="25" src="assets/recursos/img/ajax-loader.gif"/></center>');
				},
				success: function(data)
				{
					document.getElementById('btnentrar').disabled = false;
					
					if(data.Mensaje=='Error')
					{
						alert ('Usuario no encontrado');
						$("#loader2").html('');
					}
					else if(data.Mensaje=='success')
					{
						$("#loader2").html('');
						segurity(data);
					}
				}
			});
		}*/
	}
  </script>
</body>

</html>