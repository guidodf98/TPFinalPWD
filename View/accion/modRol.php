<?php
include_once("../../config.php");

$control = new ModificarDatosControl();
$retorno = $control->modRol(data_submitted());

echo json_encode($retorno);
?>