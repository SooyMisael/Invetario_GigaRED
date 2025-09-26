<div class="container is-fluid mb-6">
	<h1 class="title">Productos</h1>
	<h2 class="subtitle">Detalles del producto</h2>
</div>



<div class="container pb-6 pt-6">
	<?php
		include "./inc/btn_back.php";
		require_once "./php/main.php";


		$id = (isset($_GET['product_id_up'])) ? $_GET['product_id_up'] : 0;

		/*== Verificando producto ==*/
    	$check_producto = conexion();
    	$check_producto = $check_producto->query("
    		SELECT p.*, c.categoria_nombre, c.categoria_ubicacion
    		FROM producto p
    		LEFT JOIN categoria c ON p.categoria_id = c.categoria_id
    		WHERE p.producto_id='$id'
    	");

        if($check_producto->rowCount() > 0){
        	$datos = $check_producto->fetch();
	?>

	<div class="form-rest mb-6 mt-6"></div>

	<div class="columns">
		<!-- Columna izquierda: Imagen del producto -->
		<div class="column is-two-fifths">
			<?php if(is_file("./img/producto/".$datos['producto_foto'])){ ?>
			<figure class="image mb-6">
			  	<img src="./img/producto/<?php echo $datos['producto_foto']; ?>">
			</figure>
			<form class="FormularioAjax" action="./php/producto_img_eliminar.php" method="POST" autocomplete="off">
				<input type="hidden" name="img_del_id" value="<?php echo $datos['producto_id']; ?>">
				<p class="has-text-centered">
					<button type="submit" class="button is-danger">Eliminar imagen</button>
				</p>
			</form>
			<?php } else { ?>
			<figure class="image mb-6">
			  	<img src="./img/producto.png">
			</figure>
			<?php } ?>
		</div>

		<!-- Columna derecha: Información del producto -->
		<div class="column">

		
			<!-- Nombre -->
			<h2 class="title has-text-centered"><?php echo $datos['producto_nombre']; ?></h2>

			<p><strong>Precio:</strong> <?php echo $datos['producto_precio']; ?></p>
			
			<p><strong>Categoria:</strong> <?php echo $datos['categoria_nombre']; ?></p>

			<p><strong>Paquete:</strong> <?php echo $datos['producto_paquete']; ?></p>

			<!-- Stock -->
			<p><strong>cantidad:</strong> <?php echo $datos['producto_stock']; ?></p>

			<p><strong>Proveedor:</strong> <?php echo $datos['producto_proveedor']; ?></p>

			<p><strong>Ubicación:</strong> <?php echo $datos['categoria_ubicacion']; ?></p>



			<!-- Descripción / Detalles -->
			<h3 class="title is-5 mt-6">Descripción del Producto</h3>
			<p><?php echo nl2br($datos['producto_detalle']); ?></p>
		</div>
	</div>

	<?php 
		} else {
			include "./inc/error_alert.php";
		}
		$check_producto = null;
	?>
</div>