<?php

class EstadoProductoControl {

  public function listar() {
    $abmProducto = new AbmProducto();
    return $abmProducto->buscar(null);
  }

  public function textoDeshab($producto) {
    return ($producto->getProDeshabilitado()) ? "class=\"text-black-50\"" : "";
  }

  public function fotoDeshab($producto) {
    return ($producto->getProDeshabilitado()) ? "class=\"img-baja\"" : "";
  }

  public function getModelo($producto) {
    return (isset(json_decode($producto->getProDetalle(), true)['marca'])) ? json_decode($producto->getProDetalle(), true)['marca'] : 'Sin marca';
  }


  public function accion($data) {
    $abmProducto = new AbmProducto();
    
    $producto = $abmProducto->buscar(['idproducto' => $data['id']])[0];

    $abmProducto->modificacion($this->procesarDatos($producto, $data));
  }

  private function procesarDatos($producto, $data) {
    $datos = [
      'idproducto' => $producto->getIdProducto(),
      'pronombre' => $producto->getProNombre(),
      'prodetalle' => str_replace("'", "", $producto->getProDetalle()),
      'procantstock' => $producto->getProCantStock(),
      'proprecio' => $producto->getProPrecio(),
      'propreciooferta' => ($producto->getProPrecioOferta() == null) ? null : $producto->getProPrecioOferta(),
      'prodeshabilitado' => ($producto->getProDeshabilitado() == null) ? fecha() : null
    ];
    return $datos;
  }
}
