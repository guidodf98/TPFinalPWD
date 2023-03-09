<?php
include_once "../../config.php";

$control = new EstadoProductoControl();
$control->accion(data_submitted());

header("Status: 301 Moved Permanently");
header("Location: ../estadoProducto.php");
