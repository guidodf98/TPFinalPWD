<?php
include_once "../../config.php";

$control = new CompraControl();
$resp = $control->cambiarEstado(data_submitted());

header("Status: 301 Moved Permanently");
header('Location: ../estadoDeCompra.php?resp='.$resp);
