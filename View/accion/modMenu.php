<?php
include_once('../../config.php');

$control = new ModificarDatosControl();
$retorno = $control->modMenu(data_submitted());

echo json_encode($retorno);

