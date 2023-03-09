<?php include_once '../../config.php';

$control = new NuevoProductoControl();

if ($sesion->getRolActual() == 2) {
  $resultado = $control->accion(data_submitted());
  $resultado = ($resultado === true) ? "?m=0" : "?m={$resultado}";
} else {
  $resultado = null;
}

header("Status: 301 Moved Permanently");
header("Location: ../nuevoProducto.php" . $resultado);
