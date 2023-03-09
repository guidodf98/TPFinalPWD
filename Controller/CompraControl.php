
<?php

class CompraControl {

  public function cambiarEstado($param) {
    $resp = false;
    if (isset($param["idcompra"]) && isset($param["nuevoestado"])) {
      $abmCompraEstado = new AbmCompraEstado();
      $paramIdCompra["idcompra"] = $param["idcompra"];
      $nuevoEstado = $param["nuevoestado"];
      $compraEstado = $abmCompraEstado->buscar($paramIdCompra)[0];
      $datos = [
        "idcompraestado" => $compraEstado->getIdCompraEstado(),
        "idcompra" => $compraEstado->getObjCompra()->getIdCompra(),
        "idcompraestadotipo" => $nuevoEstado,
        "cefechaini" => $compraEstado->getCeFechaIni(),
        "cefechafin" => " null",
      ];
      $resp = $abmCompraEstado->modificacion($datos);
    }
    return $resp;
  }

  public function cancelarCompra($param) {
    $resp = false;
    if (isset($param["idcompracancelar"])) {
      $abmCompraEstado = new AbmCompraEstado();
      $abmCompraItem = new AbmCompraItem();
      $abmProducto = new AbmProducto();

      $paramIdCompra["idcompra"] = $param["idcompracancelar"];

      $compraEstado = $abmCompraEstado->buscar($paramIdCompra);
      $arrCompraItem = $abmCompraItem->buscar($paramIdCompra);


      $datos = [
        "idcompraestado" => $compraEstado[0]->getIdCompraEstado(),
        "idcompra" => $_POST["idcompracancelar"],
        "idcompraestadotipo" => 4,
        "cefechaini" => $compraEstado[0]->getCeFechaIni(),
        "cefechafin" =>  fecha(),
      ];

      foreach ($arrCompraItem as $compraItem) {
        $idProd["idproducto"] = $compraItem->getObjProducto()->getIdProducto();
        $objProducto = $abmProducto->buscar($idProd)[0];
        $datosProducto = [
          'idproducto' => $objProducto->getIdProducto(),
          'pronombre' => $objProducto->getProNombre(),
          'prodetalle' => str_replace("'", "", $objProducto->getProDetalle()),
          'procantstock' => $objProducto->getProCantStock() + $compraItem->getCiCantidad(),
          'proprecio' => $objProducto->getProPrecio(),
          'propreciooferta' => ($objProducto->getProPrecioOferta() == null) ? "null" : $objProducto->getProPrecioOferta(),
          'prodeshabilitado' => $objProducto->getProDeshabilitado(),
        ];
        $respStock = $abmProducto->modificacion($datosProducto);
      }


      $resp = $abmCompraEstado->modificacion($datos);
    }
    if ($resp) {
      $msj = 3;
    } else $msj = 2;
    return $msj;
  }

  private function crearCompra() {
    $sesion = new Session();
    $abmCompra = new AbmCompra();
    $param["idusuario"] = $sesion->getObjUsuario()->getIdUsuario();
    $param["cofecha"] = date('Y-m-d H:i:s');

    return $abmCompra->alta($param);
  }

  private function datosMod($objProducto) {
    $datosMod = [
      'idproducto' => $objProducto->getIdProducto(),
      'pronombre' => $objProducto->getProNombre(),
      'prodetalle' => str_replace("'", "", $objProducto->getProDetalle()),
      'procantstock' => $objProducto->getProCantStock(),
      'proprecio' => $objProducto->getProPrecio(),
      'propreciooferta' => ($objProducto->getProPrecioOferta() == null) ? "null" : $objProducto->getProPrecioOferta(),
      'prodeshabilitado' => $objProducto->getProDeshabilitado(),
    ];
    return $datosMod;
  }

  private function verificacionCompraItems($j, $i, $respCompra) {
    $altaCompraEstado = false;

    if ($j == $i) {
      $datosCompraEstado = [
        "idcompra" => $respCompra["idcompra"],
        "idcompraestadotipo" => 1,
        "cefechaini" => date('Y-m-d H:i:s'),
        "cefechafin" => "null",
      ];
      $abmCompraEstado = new AbmCompraEstado();
      $altaCompraEstado =  $abmCompraEstado->alta($datosCompraEstado);
      /* Vacio el carrito */
      $_SESSION["carrito"] = [];
      $compraExitosa = true;
      /* Si no es igual la cantidad, voy a buscar cada compra item y lo voy a dar de baja */
    } else if ($j < $i || $altaCompraEstado == false) {
      $abmCompraItem = new AbmCompraItem();
      $arrCompraItems = $abmCompraItem->buscar($respCompra["idcompra"]);
      foreach ($arrCompraItems as $compraItem) {
        $compraItem->baja(["idcompraitem" => $compraItem->getIdCompraItem()]);
      }
      $compraExitosa = false;
    }

    return $compraExitosa;
  }

  public function confirmarCompra() {
    $compraExitosa = false;

    $carrito = $_SESSION['carrito'];

    if (count($carrito) > 0) {

      $respCompra = $this->crearCompra();

      $precioTotal = 0;
      /* Si se da de alta la compra */
      if ($respCompra["exito"]) {
        $i = 0;
        $j = 0;
        $falloCompraItem = false;

        /* Ciclo el carrito y voy a crear un compra item por cada producto */
        do {
          $producto = $carrito[$i];
          $abmProducto = new AbmProducto();
          $objProducto = $abmProducto->buscar(["idproducto" => $producto["idProducto"]])[0];
          $precioTotal = $objProducto->getProPrecio() * $producto["cantidadProducto"];

          $abmCompraItem = new AbmCompraItem();

          $datosCompraItem["idproducto"] = $producto["idProducto"];
          $datosCompraItem["cicantidad"] = $producto["cantidadProducto"];
          $datosCompraItem["idcompra"] = $respCompra["idcompra"];
          $datosCompraItem["cipreciototal"] = $precioTotal;

          /* Si se da de alta el compra item, voy a modificar el stock del producto y modificarlo en la bd */
          if ($abmCompraItem->alta($datosCompraItem)) {
            $cantActual = $objProducto->getProCantStock();
            $nuevaCant = $cantActual - $datosCompraItem["cicantidad"];
            $objProducto->setProCantStock($nuevaCant);

            $datosMod = $this->datosMod($objProducto);
            $abmProducto->modificacion($datosMod);

            $j++;
          } else $falloCompraItem = true;

          $i++;
        } while ($i < count($carrito) && $falloCompraItem == false);


        /* Si la cantidad de productos modificados fue igual a la cantidad de productos en el carrito, creo el estado de la compra */
        $compraExitosa = $this->verificacionCompraItems($j, $i, $respCompra);
      };
    };

    $msj = ($compraExitosa) ? 5 : 4;

    return $msj;
  }

  public function buscarCompras($sesion) {
    $abmCompra = new AbmCompra();
    if ($sesion->getRolActual() == 2) {
      $arrCompras = $abmCompra->buscar("");
    } else {
      $datos["idusuario"] = $sesion->getObjUsuario()->getIdUsuario();
      $arrCompras = $abmCompra->buscar($datos);
    }
    return $arrCompras;
  }

  public function mensajesCompraControl($num) {
    $mensajes = [
      /* Cambiar estado de compra*/
      0 => 'No se pudo cambiar el estado de compra',
      1 => 'El estado de la compra se cambio correctamente.',
      /* Cancelar compra */
      2 => 'Hubo un error al cancelar su compra.',
      3 => 'Compra cancelada correctamente.',
      /* Confirmar compra */
      4 => "Hubo un error al confirmar su compra",
      5 => "Compra confirmada correctamente.",
    ];
    return $mensajes[$num];
  }
}
