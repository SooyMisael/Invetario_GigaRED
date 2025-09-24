<?php 
	
	#Calcular la paginacion de la pagina
	$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;
	$tabla="";

	#Definir los campos en las consultas
	$campos = "producto.producto_id,producto.producto_codigo,producto.producto_nombre,producto.producto_precio,producto.producto_stock,producto.producto_foto,producto.producto_proveedor,producto.categoria_id,categoria.categoria_id,categoria.categoria_nombre, categoria.categoria_ubicacion";

	#Existe alguna busqueda
	if(isset($busqueda) && $busqueda!=""){

		#Consulta los productos por busqueda
		$consulta_datos = "SELECT $campos FROM producto INNER JOIN categoria ON producto.categoria_id=categoria.categoria_id WHERE producto.producto_codigo LIKE '%$busqueda%' OR producto.producto_nombre LIKE '%$busqueda%' ORDER BY producto.producto_nombre ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(producto_id) FROM producto WHERE producto_codigo LIKE '%$busqueda%' OR producto_nombre LIKE '%$busqueda%'";

	}elseif($categoria_id>0){

		#Filtro por categoria
		$consulta_datos = "SELECT $campos FROM producto INNER JOIN categoria ON producto.categoria_id=categoria.categoria_id WHERE producto.categoria_id='$categoria_id' ORDER BY producto.producto_nombre ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(producto_id) FROM producto WHERE categoria_id='$categoria_id'";

	}else{

		#No se realizo busqueda y filtra todos los productos
		$consulta_datos = "SELECT $campos FROM producto INNER JOIN categoria ON producto.categoria_id=categoria.categoria_id ORDER BY producto.producto_nombre ASC LIMIT $inicio,$registros";

		$consulta_total="SELECT COUNT(producto_id) FROM producto";

	}

	#Ejecutar consultas en la base de datos
	$conexion=conexion();

	$datos = $conexion->query($consulta_datos);
	$datos = $datos->fetchAll();

	$total = $conexion->query($consulta_total);
	$total = (int) $total->fetchColumn();

	$Npaginas =ceil($total/$registros);

	$vista_actual = $_GET['vista'] ?? "home";

	#Listado de productos
	if($total>=1 && $pagina<=$Npaginas){
		$contador=$inicio+1;
		$pag_inicio=$inicio+1;
		foreach($datos as $rows){

			$tabla.='
				<article class="media">
			        <figure class="media-left">
			            <p class="image is-64x64">';
			            if(is_file("./img/producto/".$rows['producto_foto'])){
			            	$tabla.='<img src="./img/producto/'.$rows['producto_foto'].'">';
			            }else{
			            	$tabla.='<img src="./img/producto.png">';
			            }
			$tabla.='</p>
			        </figure>
			        <div class="media-content">
			            <div class="content">
			              <p>
			                <strong>'.$contador.' - '.$rows['producto_nombre'].'</strong><br>

							<strong>PRECIO:</strong> $'.$rows['producto_precio'].',
							<strong>CANTIDAD:</strong> $'.$rows['producto_stock'].',
							<strong>CATEGORIA:</strong> '.$rows['categoria_nombre'].',
							<strong>UBICACIÓN:</strong> '.$rows['categoria_ubicacion'].'
			              </p>
			            </div>
			            <div class="has-text-right">
			                <a href="index.php?vista=product_img&product_id_up='.$rows['producto_id'].'" class="button is-link is-rounded is-small">Ver Detalles</a>';

			// Mostrar el botón de actualizar y el control de stock solo en product_list
			if($vista_actual=="product_list"){
				$tabla.='
					<a href="index.php?vista=product_update&product_id_up='.$rows['producto_id'].'" class="button is-success is-rounded is-small">Actualizar</a>
					<a href="#" class="button is-danger is-rounded is-small js-delete-button" style="content-align: left;" data-id="'.$rows['producto_id'].'"> Eliminar </a>
					<form action="./php/producto_stock.php" method="POST" style="display:inline-block; margin-left:10px;">
						<input type="hidden" name="producto_id" value="'.$rows['producto_id'].'">
						<input type="hidden" name="vista_actual" value="'.$vista_actual.'">
						<div class="field has-addons">
							<p class="control">
								<button class="button is-small is-danger" type="submit" name="accion" value="menos">-</button>
							</p>
							<p class="control">
								<input class="input is-small" type="text" value="'.$rows['producto_stock'].'" readonly style="width:50px; text-align:center;">
							</p>
							<p class="control">
								<button class="button is-small is-primary" type="submit" name="accion" value="mas">+</button>
							</p>
						</div>
					</form>
				';
			}else{
				// En category_list solo mostramos el stock
				$tabla.='
					<span class="tag is-info is-medium" style="margin-left:10px;">
						Stock: '.$rows['producto_stock'].'
					</span>
				';
			}

			$tabla.='
			            </div>
			        </div>
			    </article>
			    <hr>
            ';
            $contador++;
		}
		$pag_final=$contador-1;
	}else{
		if($total>=1){
			$tabla.='
				<p class="has-text-centered" >
					<a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
						Haga clic acá para recargar el listado
					</a>
				</p>
			';
		}else{
			$tabla.='
				<p class="has-text-centered" >No hay registros en el sistema</p>
			';
		}
	}

	if($total>0 && $pagina<=$Npaginas){
		$tabla.='<p class="has-text-right">Mostrando productos <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
	}

	$conexion=null;
	echo $tabla;

	if($total>=1 && $pagina<=$Npaginas){
		echo paginador_tablas($pagina,$Npaginas,$url,7);
	}
?>