<?php
include_once("../../config.php");

$control = new BajaDatosControl();
$retorno = $control -> bajaRol(data_submitted());

echo json_encode($retorno);

