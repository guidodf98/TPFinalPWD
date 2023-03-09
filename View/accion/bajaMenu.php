<?php
include_once("../../config.php");

$control = new BajaDatosControl();
$retorno = $control -> bajaMenu(data_submitted());

echo json_encode($retorno);
