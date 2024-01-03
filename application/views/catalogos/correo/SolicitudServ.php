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
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td style="text-align: left; background: #FFFFFF; padding: 8px 0px 20px 0px; width: 60%;">
                                <p style="color: #282828; font-size: 18px; font-weight: 700; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly; margin-bottom: 2px;">
                                        Solicitud de Servicio
                                    </p>
                                    <p style="color: #282828; font-size: 14px; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly; margin-bottom: -5px;">
                                        <b>Folio:</b> <?php echo $Folio;?>
                                    </p>
                                    <p style="color: #282828; font-size: 14px; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly; margin-bottom: -5px;">
                                        <b>Cliente.</b> <?php echo $Cliente;?>
                                    </p>
                                    <p style="color: #282828; font-size: 14px; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly; margin-bottom: -5px;">
                                        <b>Sucursal:</b> <?php echo $Sucursal;?>
                                    </p>
                                    <p style="color: #282828; font-size: 14px; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly; margin-bottom: -5px;">
                                        <b>Solicitante:</b> <?php echo $Contacto;?>
                                    </p>
                                    <p style="color: #282828; font-size: 14px; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly; margin-bottom: -5px;">
                                        <b>Correo:</b> <?php echo $Correo;?>
                                    </p>
                                    <p style="color: #282828; font-size: 14px; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly; margin-bottom: -5px;">
                                        <b>Fecha de Solicitud:</b> <?php echo $Fecha;?>
                                    </p>
                                    <p style="color: #282828; font-size: 14px; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly; margin-bottom: -5px;">
                                        <b>Hora de Solicitud:</b> <?php echo $Hora;?>
                                    </p>
                                </td>
                                <td style="text-align: center; background: #FFFFFF; padding: 8px 8px 20px 8px; width: 40%;">
                                    <img src="<?php echo $Logo;?>" alt="Desprosoft" style="width: 100%;">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left; background: #FFFFFF; padding: 0px 50px 20px 50px; width: 100%;">
                         <p style="color: #282828; font-size: 14px; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly; margin-bottom: -5px;"><b>Mensaje:</b></p>
                         <p style="color: #282828; font-size: 14px; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly; line-height: 20px; text-align: justify;">
                         <?php echo $Mensaje;?>
                         </p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>

