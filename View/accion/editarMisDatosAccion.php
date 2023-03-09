<?php include_once "../../config.php";

$control = new EditarMisDatosControl();

$mensaje = $control->accion(data_submitted());
$mensaje = ($mensaje === null) ? "?m=0" : "?m={$mensaje}";

header("Status: 301 Moved Permanently");
header("Location: ../editarMisDatos.php" . $mensaje);
