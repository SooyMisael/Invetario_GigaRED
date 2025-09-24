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
				  	<input class="input" type="text" name="producto_codigo" pattern="[0-9]{1,70}" maxlength="70" required >
					<small class="help">Formato: solo números, máximo 70 dígitos.</small>
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
				  	<input class="input" type="text" name="producto_precio" pattern="[0-9.]{1,25}" maxlength="25" required >
					<small class="help">Formato: solo números, máximo 25 dígitos.</small>
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Cantidad</label>
				  	<input class="input" type="text" name="producto_stock" pattern="[0-9]{1,25}" maxlength="25" required >
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
		    	<div class="select is-rounded">
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
			<button type="submit" class="button is-info is-rounded">Guardar</button>
			<button type="reset" class="button is-light is-rounded">Limpiar</button>
		</p>
	</form>
<script>
    // Valida campos en tiempo real
    document.querySelectorAll("input[pattern]").forEach(input => {
        input.addEventListener("input", () => {
            const regex = new RegExp("^" + input.getAttribute("pattern") + "$");
            let errorSpan = input.nextElementSibling;

            // Si no existe el span de error, lo creamos
            if (!errorSpan || !errorSpan.classList.contains("error-msg")) {
                errorSpan = document.createElement("span");
                errorSpan.classList.add("error-msg");
                errorSpan.style.color = "red";
                errorSpan.style.fontSize = "12px";
                input.insertAdjacentElement("afterend", errorSpan);
            }

            // Validamos con la regex del pattern
            if (input.value !== "" && !regex.test(input.value)) {
                errorSpan.textContent = "***Formato no valido***";
            } else {
                errorSpan.textContent = "";
            }
        });
    });

    // Validación del archivo (tamaño y extensión)
    document.getElementById('fileInput').addEventListener('change', function () {
        const file = this.files[0];
        const fileName = document.getElementById('fileName');

        if (file) {
            const allowedTypes = ["image/jpeg", "image/png", "image/jpg"];
            if (!allowedTypes.includes(file.type)) {
                alert("***Solo se permiten archivos JPG, JPEG o PNG***");
                this.value = ""; // limpia el input
                fileName.textContent = "JPG, JPEG, PNG. (MAX 3MB)";
                return;
            }

            if (file.size > 3 * 1024 * 1024) { // 3MB
                alert("***El archivo no puede superar los 3MB***");
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
</script>
</div>


 