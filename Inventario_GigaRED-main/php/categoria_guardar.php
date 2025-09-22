<?php

    #Importacion del archivo main.php, para traer funciones (limpiar_cadena#
    #Conexion, verificar_datos#

	require_once "main.php";

    #Almacenando datos
    $nombre=limpiar_cadena($_POST['categoria_nombre']);
    $ubicacion=limpiar_cadena($_POST['categoria_ubicacion']);


    #Verificando campos obligatorios
    if($nombre==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }


    #Verificando integridad de los datos
    #El nombre debe tener entre 4 y 50 caracteres
    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}",$nombre)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if($ubicacion!=""){
    	if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}",$ubicacion)){
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                La UBICACION no coincide con el formato solicitado
	            </div>
	        ';
	        exit();
	    }
    }


    #Verificando que el nombre ya exista en la base de datos
    $check_nombre=conexion();
    $check_nombre=$check_nombre->query("SELECT categoria_nombre FROM categoria WHERE categoria_nombre='$nombre'");
    if($check_nombre->rowCount()>0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE ingresado ya se encuentra registrado, por favor elija otro
            </div>
        ';
        exit();
    }
    $check_nombre=null;


    #Guardando datos en la base de datos
    $guardar_categoria=conexion();
    $guardar_categoria=$guardar_categoria->prepare("INSERT INTO categoria(categoria_nombre,categoria_ubicacion) VALUES(:nombre,:ubicacion)");

    $marcadores=[
        ":nombre"=>$nombre,
        ":ubicacion"=>$ubicacion
    ];

    $guardar_categoria->execute($marcadores);


    #Redireccion a la lista de categorias
    if($guardar_categoria->rowCount()==1){
        echo json_encode([
            "success"=>true,
            "redirect"=>"/Inventario_GigaRED-main/index.php?vista=category_list"
        ]);
    }else{
        echo json_encode([
            "success"=>false,
            "message"=>"No se pudo registrar el producto"
        ]);
    }
    $guardar_categoria=null;