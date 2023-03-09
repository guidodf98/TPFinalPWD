<?php
class NuevoProductoControl {

  private function procesarDatos($datosProd) {

    if (isset($datosProd['prodetalleC']) && isset($datosProd['prodetalleD'])) {
      $datosProd['prodetalle2'] = array_combine($datosProd['prodetalleC'], $datosProd['prodetalleD']);
      unset($datosProd['prodetalleC']);
      unset($datosProd['prodetalleD']);
      $datosProd['prodetalle'] = array_merge($datosProd['prodetalle'], $datosProd['prodetalle2']);
      unset($datosProd['prodetalle2']);
    }

    if (isset($datosProd['prodetalle'])) {
      $datosProd['prodetalle'] = json_encode($datosProd['prodetalle']);
    }

    return $datosProd;
  }

  private function modificarProducto($datosProd) {
    $ambProducto = new AbmProducto();
    if ($ambProducto->buscar(['idproducto' => $datosProd['idproducto']])) {
      $datosProd['prodeshabilitado'] = null;
      $exito = $ambProducto->modificacion($datosProd);
    } else {
      $exito = 2;
    }
    return $exito;
  }

  private function agregarProducto($datosProd) {
    $ambProducto = new AbmProducto();
    if (!$ambProducto->buscar(['pronombre' => $datosProd['pronombre']])) {
      $datosProd['prodeshabilitado'] = null;
      $exito = $ambProducto->alta($datosProd);
    } else {
      $exito = 3;
    }
    return $exito;
  }

  private function guardarImagenes($idPro) {
    $exito = true;
    if ($_FILES['imagen']['name'][0] != '') {

      $dir = '../../View/img/Productos/' . md5($idPro) . '/'; // carpeta para guardar imagen

      if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
      }

      $i = 0;
      while ($i < count($_FILES['imagen']['name'])) {
        if ($_FILES['imagen']['error'][$i] <= 0) {
          if (!copy($_FILES['imagen']['tmp_name'][$i], $dir . $_FILES['imagen']['name'][$i])) {
            $exito = 4;
          }
        } else {
          $exito = 5;
        }
        $i++;
      }
    }
    return $exito;
  }

  public function productoActual($data) {
    $ambProducto = new AbmProducto();
    $producto = $ambProducto->buscar(['idproducto' => $data['id']]);
    return (count($producto) > 0) ? $producto[0] : null;
  }

  public function imagenesProducto($data) {
    $dirImg = md5($data["id"]);
    return scandir("img/Productos/" . $dirImg);
  }

  public function mensajes($num) {
    $mensajes = [
      0 => 'Acción realizada con exito',
      2 => 'Este producto no existe',
      3 => 'Ya existe un producto con este nombre',
      4 => 'ERROR: no se pudo cargar la imagen',
      5 => 'ERROR: No se pudo acceder al imagen temporal',
      6 => 'Los datos del producto son incorrectos'
    ];
    return $mensajes[$num];
  }

  public function tienePermiso() {
    $accesoPag = new ctrolPagina();
    return $accesoPag->ctrl_acceso();
  }

  public function proDetalles($producto) {
    return json_decode($producto->getProDetalle(), true);
  }

  public function accion($data) {
    $datosProd = $this->procesarDatos($data);
    $ambProducto = new AbmProducto();

    if ($datosProd) {
      $exito = (isset($datosProd['idproducto'])) ? $this->modificarProducto($datosProd) : $this->agregarProducto($datosProd);
    } else {
      $exito = 6;
    }

    if (isset($datosProd['pronombre'])) {
      $producto = $ambProducto->buscar(['pronombre' => $datosProd['pronombre']])[0];

      $this->guardarImagenes($producto->getIdProducto());
    }

    return $exito;
  }
}
