<div class="container is-fluid mb-6">
    <h1 class="title">Productos</h1>
    <h2 class="subtitle">Lista de productos</h2>
</div>

<div class="container pb-6 pt-6">
    <?php
        require_once "./php/main.php";

        # Eliminar producto #
        if(isset($_GET['product_id_del'])){
            require_once "./php/producto_eliminar.php";
        }

        if(!isset($_GET['page'])){
            $pagina=1;
        }else{
            $pagina=(int) $_GET['page'];
            if($pagina<=1){
                $pagina=1;
            }
        }

        $categoria_id = (isset($_GET['category_id'])) ? $_GET['category_id'] : 0;

        $pagina=limpiar_cadena($pagina);
        $url="index.php?vista=product_list&page="; /* <== */
        $registros=10;
        $busqueda="";

        if(isset($_SESSION['mensaje'])){
            echo '<div class="notification is-'.$_SESSION['tipo'].' is-light">
                    '.$_SESSION['mensaje'].'
                </div>';
            unset($_SESSION['mensaje']);
            unset($_SESSION['tipo']); 
        }

        # Paginador producto #
        require_once "./php/producto_lista.php";
    ?>
    <div class="modal" id="modalEliminar">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Confirmar eliminación</p>
                <button class="delete" aria-label="close" id="cerrarModal"></button>
            </header>
            <section class="modal-card-body">
                <p>¿Estás seguro de que deseas eliminar este producto?  </p>
            </section>
            <footer class="modal-card-foot">
                <button class="button" id="cancelarEliminar">Cancelar</button>
                <a href="#" class="button is-danger" id="btnConfirmarEliminar">Eliminar</a>
            </footer>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById("modalEliminar");
        const btnCerrar = document.getElementById("cerrarModal");
        const btnCancelar = document.getElementById("cancelarEliminar");
        const btnConfirmar = document.getElementById("btnConfirmarEliminar");

        // Cuando se hace clic en un botón "Eliminar"
        document.querySelectorAll(".js-delete-button").forEach(boton => {
            boton.addEventListener("click", (e) => {
                e.preventDefault();
                let id = boton.getAttribute("data-id");
                btnConfirmar.href = "index.php?vista=product_list&product_id_del=" + id;
                modal.classList.add("is-active");
            });
        });

        // Cerrar modal
        btnCerrar.addEventListener("click", () => modal.classList.remove("is-active"));
        btnCancelar.addEventListener("click", () => modal.classList.remove("is-active"));
        modal.querySelector(".modal-background").addEventListener("click", () => modal.classList.remove("is-active"));
    });
</script>