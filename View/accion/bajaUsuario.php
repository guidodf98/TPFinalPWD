<?php
include_once("../../config.php");

$control = new BajaDatosControl();
$retorno = $control -> bajaUsuario(data_submitted());

echo json_encode($retorno);