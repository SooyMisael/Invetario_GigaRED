<div class="container is-fluid mb-6">
    <h1 class="title">Categorías</h1>
    <h2 class="subtitle">Buscar categoría</h2>
</div>

<div class="container pb-6 pt-6">
    <?php
        require_once "./php/main.php";
        require_once "./inc/session_start.php";

        if(isset($_POST['modulo_buscador'])){
            require_once "./php/buscador.php";
        }

        if(!isset($_SESSION['busqueda_categoria']) && empty($_SESSION['busqueda_categoria'])){
    ?>
    <div class="columns">
        <div class="column">
            <form action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="categoria">
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input class="input is-rounded" type="text" name="txt_buscador" placeholder="¿Qué estas buscando?" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" maxlength="30" >
                    </p>
                    <p class="control">
                        <button class="button is-info" type="submit" >Buscar</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
    <?php }else{ ?>
    <div class="columns">
        <div class="column">
            <form class="has-text-centered mt-6 mb-6" action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="categoria"> 
                <input type="hidden" name="eliminar_buscador" value="categoria">
                <p>Estas buscando <strong>“<?php echo $_SESSION['busqueda_categoria']; ?>”</strong></p>
                <br>
                <button type="submit" class="button is-danger is-rounded">Eliminar busqueda</button>
            </form>
        </div>
    </div>

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

    <?php
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
            $url="index.php?vista=category_search&page="; /* <== */
            $registros=15;
            $busqueda=$_SESSION['busqueda_categoria']; /* <== */

            # Paginador categoria #
            require_once "./php/categoria_lista.php";
        } 
    ?>
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
                btnConfirmar.href = "index.php?vista=product_list&category_id_del=" + id;
                modal.classList.add("is-active");
            });
        });

        // Cerrar modal
        btnCerrar.addEventListener("click", () => modal.classList.remove("is-active"));
        btnCancelar.addEventListener("click", () => modal.classList.remove("is-active"));
        modal.querySelector(".modal-background").addEventListener("click", () => modal.classList.remove("is-active"));
    });
</script>