<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Correo</title>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: "Source Sans Pro", Arial, sans-serif !important;
    }

    a {
        text-decoration: none !important;
    }
    </style>
</head>

<body style="margin: 0; background: #fff">
    <div>
        <table style="margin: 0 auto; overflow-wrap: break-word; font-size: 14px;" cellpadding="0" cellspacing="0">
            <tr>
                <td style="overflow-wrap: break-word;">
                    <?php  echo $mensaje?>
                    <br>
                </td>
            </tr>

            <tr>
                <td style="overflow-wrap: break-word;">
                    <?php  
                    
                        if($firma == 1){

                    ?>
                        <img src="<?php echo $urlFirma ?>" alt="Firma">
                    <?php
                        }
                    
                    ?>

                    <br>
                    <br>
                    <br>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>