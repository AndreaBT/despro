<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Recuperar contraseña</title>

    <style>
        a {
            text-decoration: none;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: black !important;
        }

        .titulo-01 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 100px;
            font-weight: bold;
            color: #cb2529;
        }

        .titulo-02 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 60px;
            font-weight: bold;
        }

        .texto-01 {
            font-size: 30px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .texto-02 {
            font-size: 30px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .btn {
            background: #cb2529;
            text-align: center;
            padding: 20px;
            font-size: 30px;
            color: #ffffff;
            text-decoration: none;
            max-width: 60%;
            font-weight: bold;
            border-radius: 5px;
        }

        .logo-02 {
            width: 300px;
        }

        .logo-02 {
            width: 200px;
        }

        .logo-01 {
                width: 350px;
         }

        /* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
        @media only screen and (min-device-width: 320px) and (max-device-width: 374px) {}

        /* iPhone 6, 6S, 7, 8, and X */
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
            .titulo-01 {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 25px;
                font-weight: bold;
            }

            .titulo-02 {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 15px;
                font-weight: bold;
            }

            .texto-01 {
                font-size: 16px;
                font-family: Arial, Helvetica, sans-serif;
            }

            .texto-02 {
                font-size: 16px;
                font-family: Arial, Helvetica, sans-serif;
            }

            .email-container {
                max-width: 300px;
            }

            .logo-01 {
                width: 150px;
            }

            .logo-02 {
                width: 100px;
            }

            .btn {
                background: #20b2a8;
                text-align: center;
                padding: 20px;
                font-size: 20px;
                color: #ffffff;
                text-decoration: none;
                max-width: 100%;
                font-weight: bold;
            }
        }

        /* iPhone 6+, 7+, and 8+ */
        @media only screen and (min-device-width: 414px) and (max-device-width: 767px) {}

        /* iPhone 6, 6S, 7, 8, and X */
        @media only screen and (min-device-width: 768px) and (max-device-width: 800px) {
            .titulo-01 {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 60px;
                font-weight: bold;
            }

            .titulo-02 {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 40px;
                font-weight: bold;
            }

            .texto-01 {
                font-size: 20px;
                font-family: Arial, Helvetica, sans-serif;
            }

            .texto-02 {
                font-size: 20px;
                font-family: Arial, Helvetica, sans-serif;
            }

            .email-container {
                max-width: 600px;
            }

            .logo-01 {
                width: 200px;
            }

            .logo-02 {
                width: 150px;
            }

            .btn {
                background: #20b2a8;
                text-align: center;
                padding: 20px;
                font-size: 40px;
                color: #ffffff;
                text-decoration: none;
                max-width: 100%;
                font-weight: bold;
            }
        }
    </style>
</head>

<body width="100%" style="margin: 0;  mso-line-height-rule: exactly; background-color: #f1f1f1; padding: 5% 5% 5% 5%;">
    <div style=" max-width: 800px; background: #ffffff;  padding: 5% 5% 5% 5%;  margin: 0 auto;"
        class="email-container">
        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
            style="margin: auto;">
            <tr>
                <td align="center">
                    <!--<img src="https://lugarcreativo.mx/email/sistema.png" class="logo-01" alt="WP" />-->
                    <br><br><br>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td width="100%" style="text-align: center;">
                                <span class="titulo-01">&iexcl;Recuperar contraseña!</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <br><br>
                    <span class="texto-01">
                        Hola <?php echo $Nombre;?>, da click en el boton para recuperar tu contraseña
                    </span><br><br><br>
                </td>
            </tr>
            <tr>
                <td>
                    
                </td>
            </tr>
            <tr>
                <td><br>
            </tr>
            <tr>
                <td align="center">
                    <a href="<?php echo $enlace;?>">
                        <div class="btn">
                            Recuperar
                        </div>
                    </a>
                </td>
            </tr>
            <tr>
                <td align="center">    
                    <br /><br />            
                    <strong>¿No solicitaste el cambio de contraseña ?, omite el mensaje.</strong>
                </td>
            </tr>

        </table>
    </div>
    <div style=" max-width: 800px; padding: 5% 5% 5% 5%; margin: 0 auto;" class="email-container">
        <table style="margin: 0 auto; font-family: 'Arial', sans-serif; font-size: 14px;" cellpadding="0"
            cellspacing="0" width="100%">
            <tr>
                <td style="text-align: center;">
                    <span class="titulo-02">&iexcl;Nos encanta escucharte!</span><br><br>
                    <span class="texto-02">Ay&uacute;danos a mejorar, env&iacute;anos tus comentarios.</span>
                    <br><br>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <br><br>
                    Copyright © 2020 <b>DESPROSOFT</b> Todos los derechos reservados.<br>
                    <!--contacto@lapatroncita.com | +52 (999) 999-9999<br>-->
                </td>
            </tr>

        </table>
    </div>
</body>

</html>