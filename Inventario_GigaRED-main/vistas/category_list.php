<div class="container is-fluid mb-6">
    <h1 class="title">Categorías</h1>
    <h2 class="subtitle">Lista de categoría</h2>
</div>

    <div class="container pb-6 pt-6">
        <?php
            require_once "./php/main.php";

            # Eliminar categoria #
            if(isset($_GET['category_id_del'])){
                require_once "./php/categoria_eliminar.php";
            }

            if(!isset($_GET['page'])){
                $pagina=1;
            }else{
                $pagina=(int) $_GET['page'];
                if($pagina<=1){
                    $pagina=1;
                }
            }

            $pagina=limpiar_cadena($pagina);
            $url="index.php?vista=category_list&page="; /* <== */
            $registros=15;
            $busqueda="";

            # Paginador categoria #
            require_once "./php/categoria_lista.php";
        ?>
        <!-- Modal de confirmación (Bulma) -->
        <div class="modal" id="modalEliminar">
        <div class="modal-background"></div>
        <div class="modal-card">
        <header class="modal-card-head">
        <p class="modal-card-title">Confirmar eliminación</p>
        <button class="delete" aria-label="close" id="cerrarModal"></button>
        </header>
        <section class="modal-card-body">
        <p>¿Estás seguro de que deseas eliminar esta categoría?</p>
        </section>
        <footer class="modal-card-foot">
        <button class="button" id="cancelarEliminar">Cancelar</button>
        <a href="#" class="button is-danger" id="btnConfirmarEliminar">Eliminar</a>
        </footer>
    </div>
    </div>
</div>
<style>
    .modal-background{
        background-color: rgba(0, 0, 0, 0.3) !important;
    }
</style>
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
                btnConfirmar.href = "index.php?vista=category_list&category_id_del=" + id;
                modal.classList.add("is-active");
            });
        });

        // Cerrar modal
        btnCerrar.addEventListener("click", () => modal.classList.remove("is-active"));
        btnCancelar.addEventListener("click", () => modal.classList.remove("is-active"));
        modal.querySelector(".modal-background").addEventListener("click", () => modal.classList.remove("is-active"));
    });
</script>