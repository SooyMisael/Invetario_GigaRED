<div class="container is-fluid mb-6">
    <h1 class="title">Productos</h1>
    <h2 class="subtitle">Detalles del producto</h2>
</div>

<div class="container pb-6 pt-6">
    <?php
        include "./inc/btn_back.php";
        require_once "./php/main.php";

        $id = (isset($_GET['product_id_up'])) ? $_GET['product_id_up'] : 0;
        $conexion = conexion();

        // Consulta para los datos del producto
        $check_producto = $conexion->query("
            SELECT p.*, c.categoria_nombre, c.categoria_ubicacion
            FROM producto p
            LEFT JOIN categoria c ON p.categoria_id = c.categoria_id
            WHERE p.producto_id='$id'
        ");

        if($check_producto->rowCount() > 0){
            $datos = $check_producto->fetch();

            // MODIFICADO: Nueva consulta para obtener todas las imágenes del producto
            $check_imagenes = $conexion->prepare("SELECT nombre_archivo FROM producto_imagenes WHERE producto_id = :id ORDER BY orden ASC, imagen_id ASC");
            $check_imagenes->execute([':id' => $id]);
            $imagenes = $check_imagenes->fetchAll(PDO::FETCH_ASSOC);

    ?>

    <div class="form-rest mb-6 mt-6"></div>

    <div class="columns is-vcentered">
        <div class="column is-two-fifths has-text-centered">
            <?php if (!empty($imagenes)) { ?>
                <div class="product-image-container mb-4" id="imageContainer">
                    <img src="./img/producto/<?php echo htmlspecialchars($imagenes[0]['nombre_archivo']); ?>" 
                         alt="Imagen principal del producto" 
                         class="product-image"
                         id="mainImage">
                </div>
                <div class="zoom-view" id="zoomView">
                    <img src="./img/producto/<?php echo htmlspecialchars($imagenes[0]['nombre_archivo']); ?>" alt="Zoom producto" id="zoomImage">
                </div>

                <div class="thumbnails-container" style="display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
                    <?php foreach ($imagenes as $img) { ?>
                        <img src="./img/producto/<?php echo htmlspecialchars($img['nombre_archivo']); ?>" 
                             alt="Miniatura" 
                             class="thumbnail" 
                             style="width: 64px; height: 64px; object-fit: cover; cursor: pointer; border: 2px solid transparent; border-radius: 5px;">
                    <?php } ?>
                </div>

            <?php } else { ?>
                <div class="product-image-container">
                    <img src="./img/producto.png" alt="Sin imagen disponible" class="product-image">
                </div>
            <?php } ?>
        </div>

        <div class="column">
            <h2 class="title has-text-centered"><?php echo $datos['producto_nombre']; ?></h2> <br>
            <p><strong>Precio:</strong> <?php echo $datos['producto_precio']; ?></p>
            <p><strong>Categoría:</strong> <?php echo $datos['categoria_nombre']; ?></p>
            <p><strong>Paquete:</strong> <?php echo $datos['producto_paquete']; ?></p>
            <p><strong>Cantidad:</strong> <?php echo $datos['producto_stock']; ?></p>
            <p><strong>Proveedor:</strong> <?php echo $datos['producto_proveedor']; ?></p>
            <p><strong>Ubicación:</strong> <?php echo $datos['categoria_ubicacion']; ?></p>

            <h3 class="title is-5 mt-6">Descripción del Producto</h3>
            <p><?php echo nl2br($datos['producto_detalle']); ?></p>
        </div>
    </div>

    <?php 
        } else {
            include "./inc/error_alert.php";
        }
        $check_producto = null;
        $conexion = null;
    ?>
</div>

<style>
    .thumbnail:hover {
        border-color: #3273dc; /* Color de Bulma para 'link' */
    }
    .thumbnail.is-active {
        border-color: #3273dc;
        box-shadow: 0 0 5px rgba(50, 115, 220, 0.5);
    }
	/* Imagen principal */
	.product-image-container {
		width: 100%;
		max-width: 350px;
		height: 350px;
		margin: 0 auto;
		border-radius: 10px;
		overflow: hidden;
		display: flex;
		justify-content: center;
		align-items: center;
		position: relative;
		background-color: transparent;
		cursor: crosshair;
	}

	.product-image {
		width: 100%;
		height: 100%;
		object-fit: contain;
	}

	/* Imagen ampliada tipo lupa */
	.zoom-view {
		display: none;
		position: fixed;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		z-index: 9999;
		pointer-events: none;
		overflow: hidden;
		width: 600px;
		height: 600px;
		border-radius: 0;
	}

	.zoom-view img {
		position: absolute;
		width: 200%;
		height: 200%;
		object-fit: contain;
		transform-origin: top left;
	}
    /* ... (Tus otros estilos para zoom, etc., se quedan igual) ... */
</style>

<script>
    // Seleccionamos los elementos de la galería
    const mainImage = document.getElementById("mainImage");
    const zoomImage = document.getElementById("zoomImage");
    const thumbnails = document.querySelectorAll(".thumbnail");
    const imageContainer = document.getElementById("imageContainer");
    const zoomView = document.getElementById("zoomView");

    // Función para actualizar la imagen activa
    function setActiveThumbnail(activeThumb) {
        thumbnails.forEach(thumb => thumb.classList.remove('is-active'));
        if (activeThumb) {
            activeThumb.classList.add('is-active');
        }
    }

    // Añadir evento de clic a cada miniatura
    thumbnails.forEach(thumb => {
        thumb.addEventListener("click", () => {
            const newImageSrc = thumb.src;
            mainImage.src = newImageSrc;
            zoomImage.src = newImageSrc; // Actualizar también la imagen del zoom
            setActiveThumbnail(thumb);
        });
    });

    // Marcar la primera miniatura como activa al cargar la página
    if (thumbnails.length > 0) {
        setActiveThumbnail(thumbnails[0]);
    }

    // Lógica para el efecto de lupa (tu código original, funciona bien)
    if (imageContainer) {
        imageContainer.addEventListener("mouseenter", () => {
            zoomView.style.display = "block";
        });

        imageContainer.addEventListener("mouseleave", () => {
            zoomView.style.display = "none";
        });

        imageContainer.addEventListener("mousemove", (e) => {
            const rect = imageContainer.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const xPercent = x / rect.width;
            const yPercent = y / rect.height;

            zoomImage.style.left = `-${xPercent * (zoomImage.offsetWidth - zoomView.offsetWidth)}px`;
            zoomImage.style.top = `-${yPercent * (zoomImage.offsetHeight - zoomView.offsetHeight)}px`;
        });
    }
</script>