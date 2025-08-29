
<!DOCTYPE html>
<html>
    <head>
        <?php include "./inc/head.php"; ?>
    </head>
    <body>
        <?php

          if(!isset($_GET['vista']) || $_GET['vista']==""){
                $_GET['vista']="home";
            }


            if(is_file("./vistas/".$_GET['vista'].".php")){

                include "./inc/navbar.php";

                include "./vistas/".$_GET['vista'].".php";

                include "./inc/script.php";

            }else{
                include "./vistas/404.php";
            }
        ?>
    </body>
</html>