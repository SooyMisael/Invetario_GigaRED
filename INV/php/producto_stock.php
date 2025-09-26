<?php
    require_once "main.php";

    if(isset($_POST['producto_id']) && isset($_POST['accion'])){
        $id = limpiar_cadena($_POST['producto_id']);
        $accion = limpiar_cadena($_POST['accion']);

        $vista_actual = isset($_POST['vista_actual']) ? limpiar_cadena($_POST['vista_actual']) : "product_list";

        $conexion = conexion();

        // Obtener stock actual
        $consulta = $conexion->query("SELECT producto_stock FROM producto WHERE producto_id='$id'");
        if($consulta->rowCount() == 1){
            $stock = (int) $consulta->fetchColumn();

            if($accion=="mas"){
                $nuevo_stock = $stock + 1;
                $mensaje = "agregado";
            }elseif($accion=="menos" && $stock>0){
                $nuevo_stock = $stock - 1;
                $mensaje = "quitado";
            }else{
                $nuevo_stock = $stock;
                $mensaje = "no_cambio";
            }

            // Actualizar en DB
            $update = $conexion->prepare("UPDATE producto SET producto_stock=:stock WHERE producto_id=:id");
            $update->execute([":stock"=>$nuevo_stock, ":id"=>$id]);

            // Redirigir de vuelta a la lista con mensaje
            header("Location: ../index.php?vista=$vista_actual&msg=".$mensaje);
            exit();

        }else{
            // Producto no encontrado
            header("Location: ../index.php?vista=product_list&msg=error");
            exit();
        }

        $conexion=null;
    }
?>