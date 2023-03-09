<?php
include_once("../../config.php");

$control = new NewDatosControl();
$retorno = $control -> newRol(data_submitted());

echo json_encode($retorno);
