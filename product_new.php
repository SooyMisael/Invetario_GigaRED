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
                    <label>Código de barra
                        <span class="tooltip-icono">?
                            <span class="tooltip-texto">
                                El código de barra debe ser único para cada producto. Si el producto no tiene código de barra, puede ingresar un número ficticio.
                            </span>
                        </span>
                    </label>
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
                    <label>Precio
                        <span class="tooltip-icono">?
                            <span class="tooltip-texto">
                                El punto (.) se utiliza para separar los decimales. Ejemplo: 1500.50
                            </span>
                        </span>
                    </label>
                    <input class="input" type="text" name="producto_precio" pattern="[0-9.]{1,25}" placeholder="Solo números, máximo 25 dígitos." maxlength="25" required >
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Unidades por paquete
                        <span class="tooltip-icono">?
                            <span class="tooltip-texto">
                                Si el producto es un paquete, ingrese la cantidad de unidades que contiene el paquete.
                            </span>
                        </span>
                    </label>
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
                <label>Categoría
                    <span class="tooltip-icono">?
                        <span class="tooltip-texto">
                            Seleccione la categoría a la que pertenece el producto. Si no existe la categoría, puede crearla desde el menú Categorías.
                        </span>
                    </span>
                </label><br>
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
                <label>Condición</label>    
                <div class="control">
                    <div class="select">
                        <select name="producto_condicion" >
                            <option value="" selected="" >Seleccione una opción</option>
                            <option value="Nuevo">Nuevo</option>
                            <option value="Usado" >Usado</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="column">
                <label>Elegir Sección Existente</label>
                <div class="control">
                    <div class="select">
                        <select name="seccion_existente" id="seccion_existente">
                            <option value="" selected>Seleccione una opción (si existe)</option>
                            <?php
                                // Este código busca y muestra las secciones que ya existen
                                $secciones = conexion();
                                $secciones_query = $secciones->query("SELECT * FROM secciones ORDER BY nombre_seccion ASC");
                                if($secciones_query->rowCount() > 0){
                                    foreach($secciones_query as $row){
                                        echo '<option value="'.$row['seccion_id'].'">'.$row['nombre_seccion'].'</option>';
                                    }
                                }
                                $secciones = null;
                            ?>
                            <option value="crear_nueva" >Agregar Sección</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="column" id="seccion_nueva" >
                <label>O Crear una Nueva Sección</label>
                <div class="control">
                    <input class="input" type="text" name="input_seccion_nueva" id="input_seccion_nueva" 
                    pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{3,100}" maxlength="100" 
                    placeholder="Escriba aquí solo si la sección no está en la lista de arriba" disabled>
                </div>
            </div>
        </div>


        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Detalle o descripción</label>
                    <textarea class="textarea" name="producto_detalle" maxlength="1000" placeholder="Escribe una breve descripción del producto" required></textarea>
                </div>
            </div>
        </div>
        
        <div class="columns">
            <div class="column">
                <label>Fotos del producto</label>
                
                <input type="file" name="producto_fotos[]" id="fileInputMaster" multiple style="display: none;">

                <button type="button" id="addImageBtn" class="button is-info is-light mb-4">
                    <span class="icon is-small">
                      <i class="fas fa-plus"></i>
                    </span>
                    <span>Agregar Imagen</span>
                </button>
                
                <div id="imagePreviewContainer" style="display: flex; flex-wrap: wrap; gap: 15px;"></div>
            </div>
        </div>

        <p class="has-text-centered">
            <button type="submit" class="button is-info">Guardar</button>
            <button type="reset" class="button is-light">Limpiar</button>
        </p>
    </form>



    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const addImageBtn = document.getElementById('addImageBtn');
        const masterFileInput = document.getElementById('fileInputMaster');
        const previewContainer = document.getElementById('imagePreviewContainer');
        
        const fileStore = new DataTransfer();

        addImageBtn.addEventListener('click', () => {
            const tempInput = document.createElement('input');
            tempInput.type = 'file';
            tempInput.accept = '.jpg, .png, .jpeg';
            tempInput.style.display = 'none';

            tempInput.addEventListener('change', () => {
                const file = tempInput.files[0];
                if (file) {
                    const allowedTypes = ["image/jpeg", "image/png", "image/jpg"];
                    if (!allowedTypes.includes(file.type)) {
                        alert(`El archivo "${file.name}" tiene un formato no permitido.`);
                        return;
                    }
                    if (file.size > 3 * 1024 * 1024) { // 3MB
                        alert(`El archivo "${file.name}" supera el límite de 3MB.`);
                        return;
                    }

                    fileStore.items.add(file);
                    masterFileInput.files = fileStore.files;
                    createThumbnail(file);
                }
            });

            document.body.appendChild(tempInput);
            tempInput.click();
            document.body.removeChild(tempInput);
        });

        function createThumbnail(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const previewWrapper = document.createElement('div');
                previewWrapper.className = 'image-preview-wrapper';
                previewWrapper.style.position = 'relative';
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '128px';
                img.style.height = '128px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '8px';

                const deleteBtn = document.createElement('button');
                deleteBtn.className = 'delete is-small';
                deleteBtn.style.position = 'absolute';
                deleteBtn.style.top = '5px';
                deleteBtn.style.right = '5px';
                
                deleteBtn.addEventListener('click', () => {
                    removeFile(file, previewWrapper);
                });

                previewWrapper.appendChild(img);
                previewWrapper.appendChild(deleteBtn);
                previewContainer.appendChild(previewWrapper);
            };
            reader.readAsDataURL(file);
        }

        function removeFile(fileToRemove, previewWrapper) {
            const newFileStore = new DataTransfer();
            const currentFiles = Array.from(fileStore.files);
            
            currentFiles.forEach(f => {
                if (f.name !== fileToRemove.name || f.size !== fileToRemove.size) {
                    newFileStore.items.add(f);
                }
            });

            fileStore.clearData();
            Array.from(newFileStore.files).forEach(f => fileStore.items.add(f));

            masterFileInput.files = fileStore.files;
            previewContainer.removeChild(previewWrapper);
        }

        document.querySelector(".FormularioAjax").addEventListener("reset", () => {
            previewContainer.innerHTML = '';
            fileStore.clearData();
            masterFileInput.files = fileStore.files;
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

        inputPaquete.addEventListener('keydown', soloNumeros);

        const seccionSelect = document.getElementById('seccion_existente');
        const nuevaSeccionInput = document.getElementById('input_seccion_nueva');

        function actualizarEstadoSeccion() {
            if (seccionSelect.value === 'crear_nueva') {
                nuevaSeccionInput.disabled = false;
                nuevaSeccionInput.placeholder = "Escriba el nombre y presione Guardar";
                nuevaSeccionInput.focus();
            } else {
                nuevaSeccionInput.disabled = true;
                nuevaSeccionInput.placeholder = "Seleccione 'Agregar Sección' para habilitar este campo";
                nuevaSeccionInput.value = '';
            }
        }

        seccionSelect.addEventListener('change', actualizarEstadoSeccion);
        document.addEventListener('DOMContentLoaded', actualizarEstadoSeccion);
    });
    </script>
</div>