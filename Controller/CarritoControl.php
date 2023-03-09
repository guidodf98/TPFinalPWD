
<?php
/* Si no existe un id compra significa que sigo en el carrito actual entonces lo manejo con un id random. Una vez que confirmo la compra dejo $_SESSION["idCarrito"] vacio? */

/* Debo guardar en $_SESSION el arreglo con todos los productos que voy a tener en el carrito con sus id y cantidades  */
class CarritoControl
{

	public function agregarProducto($param)
	{
		if (isset($param["idProducto"])) {
			if (!isset($_SESSION["carrito"])) {
				$_SESSION["carrito"] = [];
			}
			$existeProd = false;
			$i = 0;
			$carrito = $_SESSION["carrito"];
			// mostrarArray($carrito);
			if ($carrito != []) {

				do {
					if ($carrito[$i]["idProducto"] == $param["idProducto"]) {
						$existeProd = true;
					}
					$i++;
				} while ($i < count($carrito) && $existeProd == false);
			}
			if (!$existeProd) {
				if (!isset($param["cantidadProducto"])) {
					$param["cantidadProducto"] = 1;
				}
				array_push($_SESSION["carrito"], $param);
			}
		}
		return $existeProd;
	}

	public function eliminarProductoCarrito($param)
	{
		if (isset($param["id"])) {
			$encontreProducto = false;
			$arrNuevo = [];
			$carrito = $_SESSION["carrito"];
			$i = 0;
			do {
				if ($carrito[$i]["idProducto"] == $param["id"]) {
					$encontreProducto = true;
					unset($carrito[$i]);
				}
				$i++;
			} while ($i < count($carrito) && $encontreProducto == false);
			$arrNuevo = array_merge($carrito, $arrNuevo);
			unset($_SESSION["carrito"]);
			$_SESSION["carrito"] = $arrNuevo;
		}
		return $encontreProducto;
	}

	public function modificarCantidadProducto($param)
	{
		if (isset($param["idProd"]) && isset($param["cantidadProd"]) && is_numeric($param["cantidadProd"])) {
			$encontreProducto = false;
			$arrNuevo = [];
			$carrito = $_SESSION["carrito"];
			$i = 0;
			do {
				if ($carrito[$i]["idProducto"] == $param["idProd"]) {
					$encontreProducto = true;
					$carrito[$i]["cantidadProducto"] = $param["cantidadProd"];
					// mostrarArray($carrito);
				}
				$i++;
			} while ($i < count($carrito) && $encontreProducto == false);
			$arrNuevo = array_merge($carrito, $arrNuevo);
			$_SESSION["carrito"] = $arrNuevo;
		}
		return $encontreProducto;
	}

	public function mensajesC($num)
	{
		$mensajes = [
			1 => 'La cantidad se cambio correctamente',
			2 => 'No se pudo cambiar la cantidad'
		];
		return $mensajes[$num];
	}
}
