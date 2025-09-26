<?php
	#Almacenando datos
    $product_id_del=limpiar_cadena($_GET['product_id_del']);

    #Verificando si el producto existe
    $check_producto=conexion();
    $check_producto=$check_producto->query("SELECT * FROM producto WHERE producto_id='$product_id_del'");

    if($check_producto->rowCount()==1){

    	$datos=$check_producto->fetch();

		#Eliminar producto de la base de datos
    	$eliminar_producto=conexion();
    	$eliminar_producto=$eliminar_producto->prepare("DELETE FROM producto WHERE producto_id=:id");

    	$eliminar_producto->execute([":id"=>$product_id_del]); 


		#Verificacion de eliminacion de producto
    	if($eliminar_producto->rowCount()==1){

			#Eliminar la imagen asociada
    		if(is_file("./img/producto/".$datos['producto_foto'])){
    			chmod("./img/producto/".$datos['producto_foto'], 0777);
				unlink("./img/producto/".$datos['producto_foto']);
    		}

	        echo '
	            <div class="notification is-info is-light">
	                <strong>¡PRODUCTO ELIMINADO!</strong><br>
	                Los datos del producto se eliminaron con exito
	            </div>

				<script>
            		setTimeout(function(){
                		window.location.href = "index.php?vista=product_list";
            		}, 3500); 
       			</script>
	        ';
	    }else{
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                No se pudo eliminar el producto, por favor intente nuevamente
	            </div>

				<script>
            		setTimeout(function(){
                		window.location.href = "index.php?vista=product_list";
            		}, 3500); 
       			</script>
	        ';
	    }
	    $eliminar_producto=null;
    }else{

		#Producto no existe
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El PRODUCTO que intenta eliminar no existe
            </div>

			<script>
            		setTimeout(function(){
                		window.location.href = "index.php?vista=product_list";
            		}, 3500); 
       			</script>
        ';
    }
    $check_producto=null;