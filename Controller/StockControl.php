<?php

class StockControl {


  public function accion($data) {
    $abmProducto = new AbmProducto();

    $producto = $abmProducto->buscar(['idproducto' => $data['idproducto']])[0];

    $abmProducto->modificacion($this->procesarDatos($producto, $data));
  }

  function listar() {
    $abmProducto = new AbmProducto();
    return $abmProducto->buscar(null);
  }

  public function getMarca($producto) {
    return json_decode($producto->getProDetalle(), true)['marca'];
  }

  private function procesarDatos($producto, $data) {
    $datos =  [
      'idproducto' => $producto->getIdProducto(),
      'pronombre' => $producto->getProNombre(),
      'prodetalle' => str_replace("'", "", $producto->getProDetalle()),
      'procantstock' => $data['procantstock'],
      'proprecio' => $producto->getProPrecio(),
      'propreciooferta' => ($producto->getProPrecioOferta() == null) ? null : $producto->getProPrecioOferta(),
      'prodeshabilitado' => ($producto->getProDeshabilitado() == null) ? null : $producto->getProDeshabilitado()
    ];
    return $datos;
  }
}
