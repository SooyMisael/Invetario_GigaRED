<?php
require_once "../inc/session_start.php";
require_once "main.php";

/*== Almacenando datos ==*/
$codigo = limpiar_cadena($_POST['producto_codigo']);
$nombre = limpiar_cadena($_POST['producto_nombre']);
$precio = limpiar_cadena($_POST['producto_precio']);
$stock = limpiar_cadena($_POST['producto_stock']);
$categoria = limpiar_cadena($_POST['producto_categoria']);
$detalle = limpiar_cadena($_POST['producto_detalle']);
$proveedor = limpiar_cadena($_POST['producto_proveedor']);
$paquete = isset($_POST['producto_paquete']) ? limpiar_cadena($_POST['producto_paquete']) : 0;
$condicion = limpiar_cadena($_POST['producto_condicion']);

$opcion_seccion = limpiar_cadena($_POST['seccion_existente']);
$seccion_nueva_nombre = limpiar_cadena($_POST['input_seccion_nueva']);
$seccion_id = null;

/*== Verificando campos obligatorios ==*/
if ($codigo == "" || $nombre == "" || $precio == "" || $stock == "" || $categoria == "" || $detalle == "" || $proveedor == "" || $condicion == "" || $opcion_seccion == "") {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>
    ';
    exit();
}

/*== Verificando integridad de los datos ==*/
if (verificar_datos("[0-9.]{1,70}", $codigo)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El CÓDIGO de BARRAS no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}", $nombre)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El NOMBRE no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos("[0-9.]{1,25}", $precio)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El PRECIO no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos("[0-9]{1,25}", $stock)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El STOCK no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}", $proveedor)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El PROVEEDOR no coincide con el formato solicitado
        </div>
    ';
    exit();
}

/*== Lógica para manejar la sección ==*/
if ($opcion_seccion == 'crear_nueva') {
    if (empty($seccion_nueva_nombre)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                Seleccionaste agregar una nueva sección pero no escribiste un nombre.
            </div>
        ';
        exit();
    }

    $pdo_seccion = conexion();
    $check_seccion = $pdo_seccion->prepare("SELECT seccion_id FROM secciones WHERE nombre_seccion = :nombre");
    $check_seccion->execute([":nombre" => $seccion_nueva_nombre]);

    if ($check_seccion->rowCount() > 0) {
        $seccion_id = $check_seccion->fetchColumn();
    } else {
        $guardar_seccion = $pdo_seccion->prepare("INSERT INTO secciones(nombre_seccion) VALUES(:nombre)");
        $guardar_seccion->execute([":nombre" => $seccion_nueva_nombre]);
        $seccion_id = $pdo_seccion->lastInsertId();
    }
    $pdo_seccion = null;

} elseif (!empty($opcion_seccion)) {
    $seccion_id = (int) $opcion_seccion;
} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            Debes seleccionar una sección o crear una nueva.
        </div>
    ';
    exit();
}

$pdo = conexion();

/*== Verificando código ==*/
$check_codigo = $pdo->query("SELECT producto_codigo FROM producto WHERE producto_codigo='$codigo'");
if ($check_codigo->rowCount() > 0) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El CÓDIGO de BARRAS ingresado ya se encuentra registrado, por favor elija otro
        </div>
    ';
    exit();
}

/*== Verificando nombre ==*/
$check_nombre = $pdo->query("SELECT producto_nombre FROM producto WHERE producto_nombre='$nombre'");
if ($check_nombre->rowCount() > 0) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El NOMBRE ingresado ya se encuentra registrado, por favor elija otro
        </div>
    ';
    exit();
}

/*== Verificando categoría ==*/
$check_categoria = $pdo->query("SELECT categoria_id FROM categoria WHERE categoria_id='$categoria'");
if ($check_categoria->rowCount() <= 0) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            La categoría seleccionada no existe
        </div>
    ';
    exit();
}

/*== Guardando datos ==*/
$guardar_producto = $pdo->prepare("INSERT INTO producto(producto_codigo,producto_nombre,producto_precio,producto_stock,producto_detalle,categoria_id, producto_proveedor,producto_paquete, producto_condicion, seccion_id) VALUES(:codigo,:nombre,:precio,:stock,:detalle,:categoria, :proveedor, :paquete, :condicion, :seccion_id)");

$marcadores = [
    ":codigo" => $codigo,
    ":nombre" => $nombre,
    ":precio" => $precio,
    ":stock" => $stock,
    ":detalle" => $detalle,
    ":categoria" => $categoria,
    ":proveedor" => $proveedor,
    ":paquete" => $paquete,
    ":condicion" => $condicion,
    ":seccion_id" => $seccion_id
];

$guardar_producto->execute($marcadores);

if ($guardar_producto->rowCount() == 1) {
    
    $producto_id_nuevo = $pdo->lastInsertId();
    $img_dir = '../img/producto/';

    if (isset($_FILES['producto_fotos']) && count($_FILES['producto_fotos']['name']) > 0) {
        if (!file_exists($img_dir)) {
            if (!mkdir($img_dir, 0777, true)) {
                echo '<div class="notification is-danger is-light">Error al crear el directorio de imágenes</div>';
                exit();
            }
        }
        foreach ($_FILES['producto_fotos']['name'] as $key => $nombre_foto) {
            if ($_FILES['producto_fotos']['error'][$key] === UPLOAD_ERR_OK) {
                $tmp_name = $_FILES['producto_fotos']['tmp_name'][$key];

                if (!in_array(mime_content_type($tmp_name), ["image/jpeg", "image/png"])) {
                    echo '<div class="notification is-danger is-light">El archivo '.$nombre_foto.' tiene un formato no permitido.</div>';
                    exit();
                }

                if (($_FILES['producto_fotos']['size'][$key] / 1024) > 3072) {
                    echo '<div class="notification is-danger is-light">El archivo '.$nombre_foto.' supera las 3MB.</div>';
                    exit();
                }

                $extension = pathinfo($nombre_foto, PATHINFO_EXTENSION);
                $nombre_archivo_unico = "prod_" . $producto_id_nuevo . "_" . time() . "_" . $key . "." . $extension;
                
                if (move_uploaded_file($tmp_name, $img_dir . $nombre_archivo_unico)) {
                    $guardar_img = $pdo->prepare("INSERT INTO producto_imagenes(producto_id, nombre_archivo) VALUES(:pid, :nombre)");
                    $guardar_img->execute([
                        ":pid" => $producto_id_nuevo,
                        ":nombre" => $nombre_archivo_unico
                    ]);
                } else {
                    echo '<div class="notification is-danger is-light">Error al mover el archivo '.$nombre_foto.'</div>';
                    exit();
                }
            }
        }
    }

    echo json_encode([
        "success" => true,
        "message" => "¡Producto registrado exitosamente!",
        "redirect" => "/INV/index.php?vista=product_list"
    ]);

} else {
    echo json_encode([
        "success" => false,
        "message" => "No se pudo registrar el producto, por favor intente nuevamente."
    ]);
}
$pdo = null;