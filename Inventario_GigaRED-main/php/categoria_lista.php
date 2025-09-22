<?php
#Permite Buscar las categorias que se encuentran en la lista
#Muestra los resultados en una tabla que contiene diferentes opciones para las categorias
#Tales como ver producto, actualizar y Eliminar
#Solo se pueden eliminar las categorias cuando no se tenga ningun producto asociado.#


# Calculo para la paginacion #
$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;
$tabla="";


# Consultas SQL #
if(isset($busqueda) && $busqueda!=""){

    # Consulta para traer las categorias dependiendo la busqueda#
    # Parte de codigo que afecta al buscador, manda la tabla con lo buscado #
    $consulta_datos="SELECT * FROM categoria WHERE categoria_nombre LIKE '%$busqueda%' OR categoria_ubicacion LIKE '%$busqueda%' ORDER BY categoria_nombre ASC LIMIT $inicio,$registros";

    # Consulta para contar los resultados para la paginacion #
    $consulta_total="SELECT COUNT(categoria_id) FROM categoria WHERE categoria_nombre LIKE '%$busqueda%' OR categoria_ubicacion LIKE '%$busqueda%'";

}else{
    # Trae todos los resultados porque no se tiene busqueda#
    $consulta_datos="SELECT * FROM categoria ORDER BY categoria_nombre ASC LIMIT $inicio,$registros";
    # Cuenta todas las categorias para la paginacion #
    $consulta_total="SELECT COUNT(categoria_id) FROM categoria";
    
}

# Realiza la conexion a la BD #
$conexion=conexion();

# Trae los datos de categorias #
$datos = $conexion->query($consulta_datos);
$datos = $datos->fetchAll();

# Variable para contar el total de registros #
$total = $conexion->query($consulta_total);
$total = (int) $total->fetchColumn();

$Npaginas =ceil($total/$registros);

# Creacion de la parte superior de la tabla #
$tabla.='
<div class="table-container">
    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
            <tr class="has-text-centered">
                <th>#</th>
                <th>Nombre</th>
                <th>Ubicación</th>
                <th>Productos</th>';
                
                ##
                if(!isset($busqueda) || $busqueda==""){
                    $tabla.='<th colspan="2">Opciones</th>';
                }

$tabla.='</tr>
        </thead>
        <tbody>
';

# Mostrar los registros en la tabla # 
if($total>=1 && $pagina<=$Npaginas){
    $contador=$inicio+1;
    $pag_inicio=$inicio+1;
    foreach($datos as $rows){
        $tabla.='
            <tr class="has-text-centered" >
                <td>'.$contador.'</td>
                <td>'.$rows['categoria_nombre'].'</td>
                <td>'.substr($rows['categoria_ubicacion'],0,25).'</td>
                <td>
                    <a href="index.php?vista=product_category&category_id='.$rows['categoria_id'].'" class="button is-link is-rounded is-small">Ver productos</a>
                </td>';
                
                if(!isset($busqueda) || $busqueda==""){
                    $tabla.='
                        <td>
                            <a href="index.php?vista=category_update&category_id_up='.$rows['categoria_id'].'" class="button is-success is-rounded is-small">Actualizar</a>
                        </td>
                        <td>
                            <a href="#"
                               class="button is-danger is-rounded is-small js-delete-button"
                               data-id="'.$rows['categoria_id'].'">
                               Eliminar
                            </a>
                        </td>';
                }
            
        $tabla.='</tr>';
        $contador++;
    }
    $pag_final=$contador-1;
}else{
    # No hay registros en la tabla #
    if($total>=1){
        # Ajusta el numero de columnas de la tabla #
        $colspan = (!isset($busqueda) || $busqueda=="") ? 6 : 4;

        $tabla.='
            <tr class="has-text-centered" >
                <td colspan="'.$colspan.'">
                    <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
                        Haga clic acá para recargar el listado
                    </a>
                </td>
            </tr>
        ';
    }else{
        $colspan = (!isset($busqueda) || $busqueda=="") ? 6 : 4;

        $tabla.='
            <tr class="has-text-centered" >
                <td colspan="'.$colspan.'">
                    No hay registros en el sistema
                </td>
            </tr>
        ';
    }
}


$tabla.='</tbody></table></div>';

if($total>0 && $pagina<=$Npaginas){
    $tabla.='<p class="has-text-right">Mostrando categorías <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
}

# Cierra la conexion #
$conexion=null;

# Imprime la tabla #
echo $tabla;

# Mostrar la paginacion #
if($total>=1 && $pagina<=$Npaginas){
    echo paginador_tablas($pagina,$Npaginas,$url,7);
}