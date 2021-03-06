<!DOCTYPE html>
<html lang="es">
    
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Modulo de mantenimiento preventivo y rendimiento DIANA">
        <meta name="author" content="Disatel.com">
        <meta name="keyword" content="Modulo de mantenimiento preventivo y rendimiento DIANA">
        <link rel="shortcut icon" href="img/logo-diana.png">

        <title>Módulo de mantenimiento preventivo y rendimiento - DIANA</title>

        <!-- Main styles for this application -->
        <link href="css/plantilla.css" rel="stylesheet">

    </head>

    <body class="app flex-row align-items-center">
        
        <div class="container">
            
            @yield('login')
        
        </div>

        <script src="js/plantilla.js"></script>

        <script>
            $(document).ready(function(){

                $('form').keypress(function(e){   
                    if(e == 13){
                    return false;
                    }
                });

                $('input').keypress(function(e){
                    if(e.which == 13){
                    return false;
                    }
                });
                
            });
        </script>

    </body>

</html>