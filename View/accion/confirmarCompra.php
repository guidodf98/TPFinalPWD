<?php
include_once "../../config.php";

$control = new CompraControl();
$resp = $control->confirmarCompra();

header("Status: 301 Moved Permanently");
header('Location: ../estadoDeCompra.php?resp='.$resp);

?>
