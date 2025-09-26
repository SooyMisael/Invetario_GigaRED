<div class="container is-fluid mb-6">
	<h1 class="title">Productos</h1>
	<h2 class="subtitle">Nuevo producto</h2>
</div>

<div class="container pb-6 pt-6">
	<?php
		require_once "./php/main.php";
	?>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/producto_guardar.php" method="POST" class="FormularioAjax" autocomplete="off" enctype="multipart/form-data" >
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Código de barra</label>
				  	<input class="input" type="text" name="producto_codigo" pattern="[0-9]{1,70}" placeholder="Solo números, máximo 70 dígitos." maxlength="70" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="producto_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}" maxlength="70" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Precio</label>
				  	<input class="input" type="text" name="producto_precio" pattern="[0-9.]{1,25}" placeholder="Solo números, máximo 25 dígitos." maxlength="25" required >
				</div>
		  	</div>
			<div class="column">
    			<div class="control">
        			<label>Unidades por paquete</label>
        			<input class="input" type="text" name="producto_paquete" id="input_paquete" pattern="[0-9]{1,25}" placeholder="Ej: 12" disabled>
    			</div>
    			<div class="control" style="margin-top: 10px;">
					<label class="checkbox">
						<input type="checkbox" id="checkbox_producto" name="es_paquete" value="1">
						¿Este producto es un paquete?
					</label>
    			</div>
			</div>
		  	<div class="column">
		    	<div class="control">
					<label>Cantidad</label>
				  	<input class="input" type="text" name="producto_stock" pattern="[0-9]{1,25}" placeholder="Solo números, máximo 25 dígitos." maxlength="25" required >
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<label>Proveedor</label>
				  	<input class="input" type="text" name="producto_proveedor" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}" maxlength="70" required >
				</div>
		  	</div>
		  	<div class="column">
				<label>Categoría</label><br>
		    	<div class="select">
				  	<select name="producto_categoria" >
				    	<option value="" selected="" >Seleccione una opción</option>
				    	<?php
    						$categorias=conexion();
    						$categorias=$categorias->query("SELECT * FROM categoria");
    						if($categorias->rowCount()>0){
    							$categorias=$categorias->fetchAll();
    							foreach($categorias as $row){
    								echo '<option value="'.$row['categoria_id'].'" >'.$row['categoria_nombre'].'</option>';
				    			}
				   			}
				   			$categorias=null;
				    	?>
				  	</select>
				</div>
		  	</div>
		</div>

		<div class="columns">
			<div class="column">
				<div class="control">
					<label>Detalle o descripción</label>
					<textarea class="textarea" name="producto_detalle" maxlength="255" placeholder="Escribe una breve descripción del producto" required></textarea>
				</div>
			</div>
		</div>
		
		<div class="columns">
			<div class="column">
				<label>Foto o imagen del producto</label><br>
				<div class="file is-small has-name">
				  	<label class="file-label">
				    	<input id="fileInput" class="file-input" type="file" name="producto_foto" accept=".jpg, .png, .jpeg" >
				    	<span class="file-cta">
				      		<span class="file-label">Imagen</span>
				    	</span>
				    	<span id="fileName" class="file-name">JPG, JPEG, PNG. (MAX 3MB)</span>
				  	</label>
				</div>
			</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-info">Guardar</button>
			<button type="reset" class="button is-light">Limpiar</button>
		</p>
	</form>



	<script>

		// Validación del archivo (tamaño y extensión)
		document.getElementById('fileInput').addEventListener('change', function () {
			const file = this.files[0];
			const fileName = document.getElementById('fileName');

			if (file) {
				const allowedTypes = ["image/jpeg", "image/png", "image/jpg"];
				if (!allowedTypes.includes(file.type)) {
					alert("**Solo se permiten archivos JPG, JPEG o PNG**");
					this.value = ""; // limpia el input
					fileName.textContent = "JPG, JPEG, PNG. (MAX 3MB)";
					return;
				}

				if (file.size > 3 * 1024 * 1024) { // 3MB
					alert("**El archivo no puede superar los 3MB**");
					this.value = "";
					fileName.textContent = "JPG, JPEG, PNG. (MAX 3MB)";
					return;
				}

				fileName.textContent = file.name;
			}
		});

		document.querySelector(".FormularioAjax").addEventListener("reset", function(){
			document.getElementById("fileName").textContent = "JPG, JPEG, PNG. (MAX 3MB)";

			document.querySelectorAll(".error-msg").forEach(span => {
				span.textContent = "";
			});
		});

		const soloNumeros = (evento) => {
			const teclaPresionada = evento.key;
			const esNumero = teclaPresionada >= '0' && teclaPresionada <= '9';
			const teclasControl = ['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End'];
			if (!esNumero && !teclasControl.includes(teclaPresionada)) {
				evento.preventDefault();
			}
		};

		const soloPrecio = (evento) => {
			const input = evento.target;
			const teclaPresionada = evento.key;
			const esNumero = teclaPresionada >= '0' && teclaPresionada <= '9';
			const esPunto = teclaPresionada === '.';
			const teclasControl = ['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End'];
			if (esPunto && input.value.includes('.')) {
				evento.preventDefault();
				return;
			}
			if (!esNumero && !esPunto && !teclasControl.includes(teclaPresionada)) {
				evento.preventDefault();
			}
		};

		document.querySelector('input[name="producto_codigo"]').addEventListener('keydown', soloNumeros);
		document.querySelector('input[name="producto_stock"]').addEventListener('keydown', soloNumeros);
		document.querySelector('input[name="producto_precio"]').addEventListener('keydown', soloPrecio);

		const checkboxPaquete = document.getElementById("checkbox_producto");
		const inputPaquete = document.getElementById("input_paquete");

		checkboxPaquete.addEventListener('change', function() {
			if (this.checked) {
				inputPaquete.disabled = false;
				inputPaquete.focus();
			} else {
				inputPaquete.disabled = true;
				inputPaquete.value = '';
			}
    	});

		// Asegurarnos de que el campo paquete también acepte solo números
		inputPaquete.addEventListener('keydown', soloNumeros);
	</script>
</div>


 