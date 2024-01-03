<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>

<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #e6e6e6;">
    <div style="width: 100%; background-color: #e6e6e6;">
        <div style="max-width: 600px; margin: 0 auto; margin-top: 5px; margin-bottom: 5px;" class="email-container">
            <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
                <tr>
                    <td valign="top" style="background: #FFFFFF; padding: 0 50px 0 50px;">
                        <table align="center">
                            <tr>
                                <td style="width: 400px;">
                                    <p style="color: #282828; font-size: 18px; font-weight: 700; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly; margin-bottom: 2px;">
                                        Servicio programado
                                    </p>
                                    <p style="font-family: lato, Helvetica, sans-serif; font-size: 14px; margin-bottom: 0px; color: #282828;">
                                            <b>Folio:</b> <?php echo $datos['data']->Folio;?>
                                        <br>
                                            <b>Tipo de servicio:</b> <?php echo $TipoServicio ?>
                                        <br>
                                            <b>Cliente.</b> <?php echo $NombreCliente ?>
                                        <br>
                                            <b>Sucursal:</b> <?php echo $NombreClienteSuc ?>
                                        <br>
                                            <b>Fecha de servicio:</b> <?php echo $Fecha->FechaInicio;?>
                                        <br>
                                            <b>Hora Inicio:</b> <?php echo $Fecha->HoraInicio;?>
                                    </p>
                                </td>
                                <td style="width: 200px;">
                                    <img src="<?php echo $Logo ?>" alt="Desprosoft" style="width: 100%; margin: auto;">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td valign="middle" style="background: #FFFFFF; width: 100%;">
                        <table align="center" style="width: 100%;">
                            <tr>
                                <td style=" padding: 0 50px 0 50px">
                                    <p style="font-family: lato, Helvetica, sans-serif; font-size: 18px; color:#282828; font-weight: 700;">
                                       Personal Asignado:
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style=" padding: 0 50px 30px 50px; font-family: lato, Helvetica, sans-serif; font-size: 14px; color:#282828;">
                                    <table align="center" cellspacing="0" cellpadding="0" border="0" style="width: 100%;">

                                        <?php foreach ($Trabajadores as  $element){ ?>

                                            <tr>
                                                <td>
													<img src="<?php echo $element->FotoT ?>" width="212px" alt="<?php echo $element->Nombre;?>" class="cicular">
                                                </td>
                                                <td>
                                                    <p style=" line-height: 20px; margin-left: 5px;">

                                                        <?php if($element->Responsable == true){  ?>
                                                            <b>Responsable:</b> <span style="color: #757575;"><?php echo $element->Nombre;?></span><br>
                                                            <b>Profesi&oacute;n:</b> <span style="color: #757575;"> <?php echo $element->Profesion;?></span><br>
                                                            <b>Tel&eacute;fono:</b> <span style="color: #757575;"><?php echo $element->Telefono;?></span><br>
                                                        <?php }else{?>
                                                            <b>Nombre:</b> <span style="color: #757575;"><?php echo $element->Nombre;?></span><br>
                                                            <b>Profesi&oacute;n:</b> <span style="color: #757575;"> <?php echo $element->Profesion;?></span><br>
                                                            <b>Tel&eacute;fono:</b> <span style="color: #757575;"><?php echo $element->Telefono;?></span><br>
                                                        <?php }?>



                                                    </p>
                                                </td>
                                            </tr>
                                        <?php }?>

                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
